@extends('layouts.app')

@section('content') 
    <div class="group-container">
        <h1 class="group-name">{{ $group->name }}</h1>
        <h2 class="group-description">{{ $group->description }}</h2>

        <h3 class="group-posts-heading">Posts:</h3>
        <section class="group-posts-section" id='posts'>
            @each ('partials.posts', $posts, 'post')
        </section>
            
@endsection