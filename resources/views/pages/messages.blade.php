@extends('layouts.app')

@section('content')
    @include('sidebars.bar')
    <div id = 'message-heading'>
        <h1>{{$user->firstname}} {{$user->lastname}}</h1>
    <div id="messages-container">
        <script> getMessages("{{ Auth::user()->id }}", "{{ $user->id }}", "{{ Auth::user()->username }}", "{{ $user->username }}");   </script>
    </div>
    <div id="message-input">
    <form action="{{ route('messages.send', ['username' => $user->username]) }}" method="POST">
            @csrf
            <textarea name="message-content" id="message-content" required></textarea>          
            <button type="submit" id="send-message-button">Send</button>
        </form>
    </div>

    <script src="{{ asset('js/getMessages.js') }}"></script>
    <!--<script src="{{ asset('js/sendMessage.js') }}"></script>-->
    <script>
        getMessages("{{ Auth::user()->id }}", "{{ $user->id }}", "{{ Auth::user()->username }}", "{{ $user->username }}");  
        setInterval(function() {
            getMessages("{{ Auth::user()->id }}", "{{ $user->id }}", "{{ Auth::user()->username }}", "{{ $user->username }}");
        }, 500);
    </script>
@endsection