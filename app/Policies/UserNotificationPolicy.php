<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Auth\Access\Response;

use Illuminate\Support\Facades\Auth;

class UserNotificationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, UserNotification $userNotification): bool
    {
        return($user->isAdmin() || $user==$userNotification->user());
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UserNotification $userNotification): bool
    {
        if($user!=null){
            return $user->isAdmin() || $user->id==$userNotification->user()->id;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UserNotification $userNotification): bool
    {
        if($user!=null){
            return $user->isAdmin() || $user->id==$userNotification->user()->id;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, UserNotification $userNotification): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, UserNotification $userNotification): bool
    {
        //
    }
}
