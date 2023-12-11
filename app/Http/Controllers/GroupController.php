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
        foreach($posts as $post){
            if(Auth::user()->cannot('view', $post)){
                $posts->forget($post);
            }
        }
        return $posts;
    }

    public function show($id){
        $group = Group::where('groupid', $id)->first();
        if(Auth::check()){
            if(Auth::user()->cannot('view', $group)){
                return redirect('/login');
            }
        }elseif(!($group->ispublic)){
            return redirect('/login');
        }
        return view('pages.group', ['group' => $group], ['posts' => $group->getPosts()->get()]);
    }

    public function manage($id){
        $group = Group::where('groupid', $id)->first();
        $members = $group->getMembers()->get();
        $joinRequests = $group->getJoinRequests()->get();

        if (Auth::user()->can('manage', $group)) {
            return view('pages.managegroup', compact('group', 'members', 'joinRequests'));
        } else {
            return redirect()->route('group', ['id' => $group->groupid]);
        }
    }

    public function createPage(){
        if(!Auth::check()){
            return redirect('/login');
        }
        else{
            return view('pages.createGroup');
        }
    }

    public function create(Request $request){
        if(!Auth::check()){
            return redirect('/login');
        }else{
            $user = Auth::user();
            $group = new Group();
            $group->ownerid = $request->ownerID;
            $group->name = $request->name;
            $group->description = $request->description;
            $group->ispublic = $request->isPublic;
            $group->save();
            $group->addMember($user);
            $groupId = $group->groupid;            
            return redirect('/group/' . $groupId);
        }
    }

    public function removeMember(Request $request, $id){
        $group = Group::where('groupid', $id)->first();
        $user = User::where('id', $request->userid)->first();
        // falta aqui policy
        $group->removeMember($user);
        return redirect()->route('group.manage', ['id' => $group->groupid]);
    }
}