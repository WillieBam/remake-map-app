<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;

class MessagesController extends Controller
{
    /**
     // user views on country's message
     */
    function show($country_id){
        $retrieved = Message::where('country_id',$country_id)->get();
        return view('home',[
            'country_messages' =>  $retrieved 
        ]);
    }

    
    /**
     // user post message  
    */
    function store(Request $request, $country_id){
        //validate message
        $validate_message = $request->validate([
            'content'=> 'required|min:10|max:500',
            'user_id' => 'required|exists:users,user_id',
        ]);

        // create message with country id
        $create_message = Message::create([
            'content' => $validate_message['content'],
            'user_id' => $validate_message['user_id'],
            'country_id' => $country_id,
            'views' => 0
        ]);

        return redirect()->route('countries.show', ['id' => $country_id])
                     ->with('success', 'Message posted successfully!');
    }

    function delete($message_id){
        $to_delete = Message::where('message_id',$message_id)->delete();
    }






}
