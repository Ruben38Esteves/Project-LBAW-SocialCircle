<?php

namespace App\Policies;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class PostPolicy
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
    public function view(User $user, Post $post): bool
    {
        $postOwner = $post->owner;
        if($postOwner->ispublic){
            return $postOwner->ispublic;
        }
        if(Auth::check()){
            $postViewer = Auth::user();
            if($postOwner==$postViewer){
                return $postOwner == $postViewer;
            }
            if($postOwner->isFriend($postViewer)){
                return $postOwner->isFriend($postViewer);
            }
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): response
    {
        return $user->isActive()
            ? Response::allow()
            : Response::deny('You must be logged in to create a post.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->userid or $user->isAdmin()
            ? Response::allow()
            : Response::deny('You do not own this post.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $post): bool
    {
        return $user->id === $post->userid or $user->isAdmin()
            ? Response::allow()
            : Response::deny('You do not own this post.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Post $post): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Post $post): bool
    {
        //
    }
}
