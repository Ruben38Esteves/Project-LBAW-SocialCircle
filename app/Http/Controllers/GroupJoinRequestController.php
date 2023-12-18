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

    public function accept(Request $request, $id) {
        $user = Auth::user();
        $group = Group::findOrFail($id);
        // falta policy
        $requestingUser = User::findOrFail($request->userid);
        $groupJoinRequest = GroupJoinRequest::where('userid', $requestingUser->id)->where('groupid', $group->groupid)->first();
        if ($groupJoinRequest) {
            $group->addMember($requestingUser);     

            GroupJoinRequest::where('groupid', $groupJoinRequest->groupid)
            ->where('userid', $groupJoinRequest->userid)
            ->delete();

            $members = $group->getMembers()->get();
            $joinRequests = $group->getJoinRequests()->get();
            return view('pages.managegroup', compact('group', 'members', 'joinRequests'));
        }
    }
}