@use(App\Models\Friendship);
@use(App\Models\FriendRequest);

@extends('layouts.app')
@section('content')
@include('sidebars.bar')
    <div class="profile-container">
        <h1 class="profile-heading">Profile</h1>
        <h2 class="profile-name">{{ $user->firstname }} {{ $user->lastname }}</h2>
        <h2 class="profile-username">{{ $user->username }}</h2>
        <h3 class="profile-about">About me: {{ $user->aboutme }}</h3>
        @if (Auth::user()->id == $user->id)
            <a href="/profile/edit" class="profile-edit-link">Edit Profile</a>
        @elseif (Friendship::areFriends(Auth::user()->id, $user->id))
            <form action="/profile/{{ $user->username }}/unfriend" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="profile-unfriend-button">Unfriend</button>
            </form>
        @elseif (FriendRequest::exists(Auth::user()->id, $user->id))
            <form action="{{ route('friend-request.remove', ['username' => $user->username]) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="profile-removefriend-button">Requested</button>
            </form>
        @else
            <form action="{{ route('friend-request.create', ['username' => $user->username]) }}" method="POST">
                @csrf
                <button type="submit" class="profile-addfriend-button">Add Friend</button>
            </form>
        @endif

        <h3 class="profile-posts-heading">Posts:</h3>
        <section class="profile-posts-section" id='posts'>
            <?php $posts =  $user->ownPosts()->get();?>
            @foreach($posts as $post)
                @include('partials.posts', ['post' => $post])
            @endforeach
        </section>
    </div>
@endsection