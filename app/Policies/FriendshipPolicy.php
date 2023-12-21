<?php

namespace App\Policies;

use App\Models\Friendship;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class FriendshipPolicy
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
    public function view(User $user, Friendship $friendship): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return Auth::check();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Friendship $friendship): bool
    {
        return Auth::check();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Friendship $friendship): bool
    {
        return (Auth::check() && ($user->id == $friendship->userid || $user->id == $friendship->friendid));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Friendship $friendship): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Friendship $friendship): bool
    {
        //
    }
}
