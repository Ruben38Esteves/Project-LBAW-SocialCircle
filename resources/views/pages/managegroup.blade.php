@extends('layouts.app')

@section('content')
@include('sidebars.bar')
    <h1 class="manage-group-text">Manage {{ $group->name }}</h1>
    <div class="manage-lists-container">
        <div class="members-lists">
            <h2 class="members-lists-heading">Members:</h2>
            <ul class="members-list">
                @foreach ($members as $member)
                    <li class="member-list-item">
                        <a href="{{ route('user', ['username' => $member->username]) }}"><h3 class="member-list-item-text">{{ $member->username }}</h3></a>
                        <form action="{{ route('group-join-request.remove', ['id' => $group->groupid]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="remove-member-button" name="userid" value="{{ $member->id }}">Remove</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="join-requests-list">
            <h2 class="join-requests-list-heading">Join Requests:</h2>
            <ul class="join-requests-list">
                @foreach ($joinRequests as $joinRequest)
                    <li class="join-requests-list-item">
                        <a href="{{ route('user', ['username' => $joinRequest->user()->username]) }}"><h3 class="join-requests-list-item-text">{{ $joinRequest->user()->username }}</h3></a>
                        <form action="{{ route('group-join-request.remove', ['id' => $group->groupid]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="remove-join-request-button" name="userid" value="{{ $joinRequest->user()->id }}">Remove</button>
                        </form>
                        <form action="{{ route('group-join-request.accept', ['id' => $group->groupid]) }}" method="POST">
                            @csrf
                            <button class="accept-join-request-button" name="userid" value="{{ $joinRequest->user()->id }}">Accept</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection