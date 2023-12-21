<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\UserNotification;
use App\Policies\UserNotificationPolicy;

class UserNotificationController extends Controller
{

    public function markNotifAsViewed($id){
        if(!Auth::check()) return redirect()->back();
        if(Auth::user()->can('update',UserNotification::where('notificationid', $id)))
        $affected = DB::table('usernotification')
            ->where('notificationid', $id)
            ->update(['viewed' => true]);
        return redirect()->back();
    }
}
