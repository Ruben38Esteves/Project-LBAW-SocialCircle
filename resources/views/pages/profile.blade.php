@extends('layouts.app')

@section('content') 
    <div class="profile-container">
        <h1 class="profile-heading">Profile</h1>
        <h2 class="profile-name">{{ $user->firstname }} {{ $user->lastname }}</h2>
        <h2 class="profile-username">{{ $user->username }}</h2>
        <h3 class="profile-about">About me: {{ $user->aboutme }}</h3>

        <h3 class="profile-posts-heading">Posts:</h3>
        <section class="profile-posts-section" id='posts'>
            @each('partials.posts', $nonEventPosts, 'post')
        </section>
    </div>
@endsection