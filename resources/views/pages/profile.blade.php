@use(App\Models\Friendship)
@use(App\Models\FriendRequest)

@extends('layouts.app')
@section('content')
@include('sidebars.bar')
    <?php  
    use App\Models\Image; 
    $image = Image::where('imageid', $user->profilepictureid)->first();
    ?>
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
        <h2 class="profile-name">{{ $user->firstname }} {{ $user->lastname }}</h2>
        <h2 class="profile-username">{{ $user->username }}</h2>
        <h3 class="profile-about">About me: {{ $user->aboutme }}</h3>
        
        @if (Auth::user()->id == $user->id)
            <a href="/profile/edit" class="profile-edit-link">Edit Profile</a>
            <form action='/profile/{{$user->id}}/add-image' method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" class="form-control" name="image" />
                <button type="submit" class="btn btn-sm">Upload</button>
            </form>
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
        <a href="/messages/{{$user->username}}">
            <p>Message</p>
        </a>
        <h3 class="profile-posts-heading">Posts:</h3>
        <section class="profile-posts-section" id='posts'>
            <?php $posts =  $user->ownPosts()->get();?>
            @foreach($posts as $post)
                @include('partials.posts', ['post' => $post])
            @endforeach
        </section>

    </div>
@endsection