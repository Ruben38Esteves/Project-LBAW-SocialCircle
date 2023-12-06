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
        $messages=Message::where('messageID', $this->messageID)->get();
        return $messages;
    }

    public function showPage(int $id) {
        $user=User::where('id',$this->id)->get();
        return view('pages.message', ['user' => $user, 'messages' => $user->getMsg()->get()]);
    }

}