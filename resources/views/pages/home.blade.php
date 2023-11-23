
@extends('layouts.app')


@section('content')

    <section id="post-form">
        @auth
            <form action="{{ route('posts.create') }}" method="POST">
                @csrf
                <br>
                <label for="content">Content:</label>
                <textarea name="content" required></textarea>
                <br>
                <button type="submit">Create Post</button>
            </form>
        @endauth
    </section>

    <section id="posts">
        @each('partials.posts', $posts, 'post')
    </section>
@endsection