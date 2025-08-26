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
            
            return view('home', ['countries' => $countries, 'country_data' => $country_data,
            'messages' =>$country_data->messages,'news'=>$country_data->news, 'search_results'=>collect()]); 

        }
        else{
             return view('home', ['countries' => $countries, 'search_results'=>collect()]);
        }
    }
    

    function searchCountry(Request $request){
       $search = $request->input('search_country'); //name of the input field in the form
                                                // SQL 'like' operator --> SQL query: where column_name like %+"search" +%;
       $result = Country::where('name','like',"%$search%")->get();  

       return view('home',['search_results'=>$result, 'countries' =>collect()]);


    }




}
