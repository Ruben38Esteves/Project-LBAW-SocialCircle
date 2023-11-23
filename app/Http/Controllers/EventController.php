<?php

namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

use App\Models\Event;

class EventController extends Controller
{

    public function getPosts(){
        if(!Auth::check()){
            return redirect('/login');
        }else{
            
            $events = Event::all();
            return view('pages.home', ['events' => $events]);
        }

    }
}