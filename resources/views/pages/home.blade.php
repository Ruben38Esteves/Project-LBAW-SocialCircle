
@extends('layouts.app')


@section('content')
@include('sidebars.bar')
    <section id="post-form">
        @auth
            <form action="{{ route('posts.create') }}" method="POST">
                @csrf
                <br>
                <label for="content">New Post</label>
                <input type="text" name="content" required> </input
                <br>
                <button type="submit">Post</button>
            </form>
        @endauth
    </section>

    <section id="posts">
        @each('partials.posts', $posts, 'post')
    </section>
@endsection