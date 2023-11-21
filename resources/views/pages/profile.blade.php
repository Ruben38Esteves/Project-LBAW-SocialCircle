@extends('layouts.app')


@section('content') 
    <div class="profileidk">
        <h1>Profile</h1>
        <h2>Name: {{ $user->firstname }} {{ $user->lastname }}</h2>
        <h2>Username: {{ $user->username }}</h2>
        <h3>About me: {{ $user->aboutme }}</h3>

        <h3>Posts:</h3>
        <section>
            <?php $events = $user->ownEvents; ?>
            @each('partials.events', $events, 'event')
        </section>
        <section id='posts'>
            @each('partials.posts', $nonEventPosts, 'post')
        </section>
    </div>
@endsection