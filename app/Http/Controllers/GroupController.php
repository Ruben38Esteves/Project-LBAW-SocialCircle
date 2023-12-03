<?php

namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use Illuminate\Console\View\Components\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

use App\Models\Post;
use App\Models\User;
use App\Models\Group;

class GroupController extends Controller
{

    public function getPosts(){
        $posts = Post::where('groupid', $this->groupid)->get();
        return $posts;
    }

    public function show($id){
        $group = Group::where('groupid', $id)->first();
        return view('pages.group', ['group' => $group], ['posts' => $group->getPosts()->get()]);
    }
}