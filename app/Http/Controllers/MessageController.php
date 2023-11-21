<?php

namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

use App\Models\Message;
 
class MessageController extends Controller
{
    public function index(): View
    {
        
    }

    public function list(){
        if(!Auth::check()){
            return redirect('/login');
        }else{
            
            #$posts = DB::table('userPost')->get();
            $messages = Message::all();
            #$messages = DB::select('select * from userMessage');
            return view('pages.home', ['messages' => $messages]);
        }

    }
}