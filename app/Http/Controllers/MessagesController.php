<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;

class MessagesController extends Controller
{
    /**
     // user post message  
    */
    function store(Request $request, $country_id){

      //$this->authorize('createMessage',Message::class);
        //validate message
        $validate_message = $request->validate([
            'content'=> 'required|min:10|max:500',
        ]);

        // create message with country id
        $create_message = Message::create([
            'content' => $validate_message['content'],
            'user_id' => auth()->id(),
            'country_id' => $country_id,
            'views' => 0
        ]);
       

        return redirect()->route('countries.show', ['id' => $country_id])
                     ->with('success', 'Message posted successfully!');
    }

    function delete($country_id,$message_id){
        $to_delete = Message::where('message_id',$message_id)->delete();

            return redirect()->route('countries.show', ['id' => $country_id])
            ->with('success', 'Message deleted successfully!');

    }






}
