<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\DB;

class MessagesController extends Controller
{
    function createMessage(Request $request){

        // create message
    }

    function deleteMessage($message_id){

        // admin perform delete message
    }


    function getCountryMessages($country_id){

        //show country message 
    }

    function getUserMessages($user_id){

        // past messages posted by a user when inside user profile
    }

    function reportMessage($message_id){

        // normal user can report message
    }


}
