<?php

namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

use App\Models\Message;
use App\Models\User;

class MessageController extends Controller
{   
    public function getMsg(){
        $messages=Message::where('messageid', $this->messageID)->get();
        return $messages;
    }

    public function messages($username){
        $user = User::where('username', $username)->first();
        $user_me = Auth::user();
        if($user && $user_me){
            return Message::where('sourceid', $user_me->id)->where('targetid', $user->id)->orWhere('sourceid', $user->id)->where('targetid', $user_me->id)->get();
        }
        else{
            return redirect('/login');
        }
    }

    public function showPage($username){
        $user = User::where('username', $username)->first();
        $user_me = Auth::user();
        if($user && $user_me){
            return view('pages.messages', ['user' => $user]);
        }
        else{
            return redirect('/login');
        }
    }

    public function sendMessage(Request $request, $username){
        $user_me = Auth::user();
        if($user_me){
            $message = new Message();
            $message->sourceid = $user_me->id;
            $message->targetid = User::where('username', $username)->first()->id;
            $message->sent_at = now();
            $message->message = $request->input('message-content');
            $message->save();
            return redirect('/messages/'.$username);
        }
        else{
            return redirect('/login');
        }
    }
}