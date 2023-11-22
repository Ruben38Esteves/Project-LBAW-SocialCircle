
@extends('layouts.app')


@section('content')
    <section id="posts">
        @each('partials.posts', $posts, 'post')
    </section>
@endsection