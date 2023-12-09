@extends('layouts.app')

@section('content')
    @include('sidebars.bar')
    <div id = 'message-heading'>
        <h1>{{$user->username}}</h1>
    <div id="messages-container">
    </div>
    <div id="message-input">
        <form>
            <input type="text" id="message-content" placeholder="Type a message...">
        </form>
    </div>

    <script src="{{ asset('js/getMessages.js') }}"></script>
    <script>
        setInterval(function() {
            getMessages("{{ Auth::user()->id }}", "{{ $user->id }}", "{{ Auth::user()->username }}", "{{ $user->username }}");
        }, 500);
    </script>
@endsection