<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class newsController extends Controller
{
    function createNews(Request $request){
        // addNews
    }

    function deleteNews($id){
        //deleteNews
    }

    function editNews(Request $request, $id){
        // editNews
    }

    //for continent admin 
    function getCountryNews($country_id){
        // getCountryNews
    }

    //for global admin
    function getAllNews(){
        // getAllNews
    }

    // for user which one all or based on country?


    /*Search functions*/
    //search by id ?? 
    function getNewsById($news_id){
        // getNewsById with view increment
    }

    //search for specific admin created news
    function getAdminNews($user_id){
        // getAdminNews
    }

    //search for titile
    function searchNews(Request $request){
        // searchNews by title or content
    }

    
    /*filter*/
    //Most viewed
    function getMostViewedNews(){
        // getMostViewedNews
    }

    //Latest news
    function getLatestNews(){
        // getLatestNews
    }

}
