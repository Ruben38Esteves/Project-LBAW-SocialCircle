@extends('layouts.app')

@section('content')
@include('sidebars.bar')
    <div class="message-chat-page">
        <section class="msg-contact-info">
            <div>
                <a href="../users/{$users->id}"><h1>{{$users->firstName}}</h1></a>
                <h2>&#64;{$users->username}</h2>
            </div>
        </section>

        <section id="message-content-container" class="msg-contents">
            @each('partials.message', $usermessage, 'message')
        </section>

        <section id="message-chat-box" class="msg-write">
            <article class="message-create-article">
                