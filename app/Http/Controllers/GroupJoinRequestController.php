<?php
 
namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

use App\Models\Post;
use App\Models\User;
use App\Models\Group;
use App\Models\GroupJoinRequest;

class GroupJoinRequestController extends Controller
{
    public function create($id)
    {
        $user = Auth::user();
        $group = Group::findOrFail($id);
        if (GroupJoinRequest::exists($group->groupid, $user->id)) {
            return redirect('/group/'.$id);
        }
        GroupJoinRequest::insert(['userid' => $user->id, 'groupid' => $group->groupid]);
        return redirect('/group/'.$id);
    }

    public function remove($id) {
        $user = Auth::user();
        $group = Group::findOrFail($id);
        if (GroupJoinRequest::exists($group->groupid, $user->id)) {
            GroupJoinRequest::where('userid', $user->id)->where('groupid', $group->groupid)->delete();
        }
        return redirect('/group/'.$id);
    }
}