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

    function viewCreateNews(){
        
    }

    function deleteNews($id){
        //deleteNews
    }

    function editNews(Request $request, $id){
        // editNews post
    }

    function viewEditNews(){
        //get
    }

    function viewNews(){
        //specific news get
    }

    function viewAllNews(){
        // get all news
    }

    //search for title
    function searchNews(Request $request, $order){
        // searchNews by title or content
    }

    

}
