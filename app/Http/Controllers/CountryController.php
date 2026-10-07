<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Country;
use Illuminate\Support\Facades\Cookie;
use App\Models\News;                    
use Illuminate\Support\Facades\Auth;   
class CountryController extends Controller
{
    function index($country_id=null){
        // get all countries
        $countries = Country::all();

        if($country_id){
            
            $country_data = Country::with(['messages','news'])
            ->where('country_id',$country_id)
            ->firstOrFail();

             Message::where('country_id',$country_id)->increment('views');

            return view('home', 
            ['countries' => $countries, 
            'country_data' => $country_data,
            'messages' =>$country_data->messages,
            'news'=>$country_data->news, 
            'search_results'=>collect()]); 

        }
        else{
             return view('home', ['countries' => $countries, 'search_results'=>collect()]);
        }
    }
    

    function queryCountry(Request $request){
       $search = $request->keyword; 
       $filter=$request->continent;

                                                //name of the input field in the form
                                                // SQL 'like' operator --> SQL query: where column_name like %+"search" +%;
       $result = Country::where('name','like',"%$search%")->get();
       if($filter!=0){
        $result = Country::where([['name','like',"%$search%"],['continent_id','=',$filter]])->get();
       }  

       return response(['search_results'=>$result, 'countries'=>collect()])
       ->cookie('country_search', $search, 60,'/',null,true,true)
       ->cookie('country_filter', $filter, 60,'/',null,true,true);
    }

    public function getCountriesList (Request $request){
        $countries = Country::with('continent:continent_id,name')->orderBy('name','asc')->get(['country_id','continent_id','name'])
        ->map(function($country){
            return[
                'country_id' => $country->country_id,
                'name' => $country->name,
                'continent_id'=> $country->continent_id,
                'continent_name' => optional($country->continent)->name ?? '',
            ];
        });
        return response()->json($countries);
    }

    public function getCountryData (Request $request, $id){
        $country = Country::with('continent')->findOrFail($id);
        
        // increment message view count for this country
        Message::where('country_id',$id)->increment('views');
        $user = Auth::user();

        $messages = Message::with('user:user_id,name')->where('country_id', $id)->latest()->get()
        ->map(function ($msg) use ($user){
            return [
                'message_id' => $msg->message_id,
                'content' => $msg->content,
                'user_id' => $msg->user_id,
                'user_name' => optional($msg->user)->name ?? 'User#'.$msg->user_id,
                'created_at' => $msg->created_at ? $msg->created_at->format('M d, Y'): '',
                'views' => $msg->views,
                'can_delete' => $user ? $user->can('delete', $msg): false,
                'can_report' => $user ? $user->can('report-message', $msg): false,
            ];
        });

        $news = News::where('country_id', $id)->latest()->get()->map(function ($item){
            return [
                'news_id' => $item->news_id,
                'title' => $item->title,
                'content' => $item->content,
                'views' => $item->views,
                'created_at' => $item->created_at ? $item->created_at->format('M d, Y') : '',
                'url' => route('news.show', [$item->country_id, $item->news_id]),
            ];
        });

  return response()->json([
            'country' => [
                'country_id'     => $country->country_id,
                'name'           => $country->name,
                'continent_id'   => $country->continent_id,
                'continent_name' => optional($country->continent)->name ?? 'Unknown',
            ],
            'messages' => $messages,
            'news'     => $news,
            'auth'     => [
                'is_logged_in' => Auth::check(),
                'user'         => $user ? ['id' => $user->user_id, 'name' => $user->name] : null,
                'can_post'     => $user && !$user->is_banned,
            ],
        ]);
    }
}
