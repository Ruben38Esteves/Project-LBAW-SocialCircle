@use(App\Models\Friendship)
@use(App\Models\FriendRequest)

@extends('layouts.app')
@section('content')
@include('sidebars.bar')
    <?php  
    use App\Models\Image; 
    use App\Policies\UserPolicy;
    use Illuminate\Support\Facades\Auth;
    $image = Image::where('imageid', $user->profilepictureid)->first();
    ?>
    
    <script type="text/javascript" src={{ asset('js/editProfile.js') }}></script>
    <div class="profile-container">
        @if ($image)
        <div class="user_header_img">
            <img src="/images/{{$image->imagepath}}">
        </div>
        @else
        <div class="user_header_img">
            <img src="/images/default-user.jpg">
        </div>
        @endif
        <h2 id="profile-name">{{ $user->firstname }} {{ $user->lastname }}<?php if(Auth::user()->can('update', $user)){ ?> <button id="profile-edit-name" onclick="getNameForm()">Edit</button> <?php } ?></h2>
        <form id="profile-name-edit" action='/profile/{{$user->username}}/editName' method="POST" style="display: none">
            @csrf
            @method('PUT')
            <input type="text" class="profile-edit" name="firstname" placeholder="{{$user->firstname}}" value="{{$user->firstname}}"/>
            <input type="text" class="profile-edit" name="lastname" placeholder="{{$user->lastname}}" value="{{$user->lastname}}"/>
            <button type="submit" class="btn btn-sm">Submit</button>
            <button onclick="hideNameForm()" class="btn btn-sm">Cancel</button>
        </form>
        <h2 class="profile-username">@ {{ $user->username }}</h2>
        <h3 id="profile-about">About me: {{ $user->aboutme }}<?php if(Auth::user()->can('update', $user)){ ?> <button id="profile-edit-aboutme" onclick="getAboutMeForm()">Edit</button> <?php } ?></h3>
        <form id="profile-about-edit" action='/profile/{{$user->username}}/editAboutMe' method="POST" style="display: none">
            @csrf
            @method('PUT')
            <input type="text" class="profile-edit" name="aboutme" placeholder="{{$user->aboutme}}" value="{{$user->aboutme}}"/>
            <button type="submit" class="btn btn-sm">submit</button>
            <button onclick="hideAboutMeForm()" class="btn btn-sm">Cancel</button>
        </form>
        @if (Auth::user()->can('update', $user))
            <button id="profile-edit-image" onclick="getPictureForm()">Edit Picture</button>
            <form id="profile-picture-edit" action='/profile/{{$user->id}}/add-image' method="POST" enctype="multipart/form-data" style="display: none">
                @csrf
                <input type="file" class="form-control" name="image" />
                <button type="submit" class="btn btn-sm">Upload</button>
                <button onclick="hidePictureForm()" class="btn btn-sm">Cancel</button>
            </form>
        @endif
        @if (Auth::user()->id == $user->id)
        @elseif (Friendship::areFriends(Auth::user()->id, $user->id))
            <form action="/profile/{{ $user->username }}/unfriend" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="profile-unfriend-button">Unfriend</button>
            </form>
        @elseif (FriendRequest::exists(Auth::user()->id, $user->id))
            <form action="{{ route('friend-request.remove', ['username' => $user->username]) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="profile-removefriendRequest-button">Requested</button>
            </form>
        @elseif (FriendRequest::exists($user->id, Auth::user()->id))
            <form action="{{ route('friend-request.accept', ['username' => $user->username]) }}" method="POST">
                @csrf
                @method('PUT')
                <button type="submit" class="profile-acceptfriendRequest-button">Accept Friend Request</button>
            </form>
            <form action="{{ route('friend-request.remove', ['username' => $user->username]) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="profile-removefriendRequest-button">Decline Friend Request</button>
            </form>
        @else
            <form action="{{ route('friend-request.create', ['username' => $user->username]) }}" method="POST">
                @csrf
                <button type="submit" class="profile-addfriend-button">Add Friend</button>
            </form>
        @endif
        @if ($user->id != Auth::user()->id)
        <a href="/messages/{{$user->username}}">
            <p>Message</p>
        </a>
        @endif
        <h3 class="profile-posts-heading">Posts:</h3>
        <section class="profile-posts-section" id='posts'>
            <?php $posts =  $user->ownPosts()->get();?>
            @foreach($posts as $post)
                @include('partials.posts', ['post' => $post])
            @endforeach
        </section>

    </div>
@endsection