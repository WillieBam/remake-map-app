<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Country;
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

       return response(['search_results'=>$result, 'countries'=>collect()]);
    }

    




}
