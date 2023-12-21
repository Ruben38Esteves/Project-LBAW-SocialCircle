@extends('layouts.app')

@section('content') 
@include('sidebars.bar')

<div class="helpContainer">
    <h1 style="text-align: center;">Help</h1>
    <div class="helpContent" style="color: black;">
        <h2>Using SocialCircle</h2>
        <p>Using SocialCircle is very simple. You can create an account by clicking on the register button on the top right corner of the page. After that, you can login and start using the website. You can create posts, comment on other people's posts, like them, and even create groups and invite your friends to join them!</p>
        <h2>Posting</h2>
        <p>You can post using the form in the home page, or if you want to post in a group, use the form in the grouo page</p>
        <h2>Groups</h2>
        <p>You can create groups by clicking on the "Groups" button on the sidebar, and then clicking on the "Create Group" button. Search for groups you want to join and request to join, or just join them if they are public.</p>
        <h2>Friends</h2>
        <p>You can add friends by searching for them in the search bar, and then clicking on the "Add Friend" button. You can also accept friend requests from other people in the notifications page.</p>
        <h2>Notifications</h2>
        <p>You can see your notifications by clicking on the "Notifications" button on the sidebar.<p>
    </div>
</div>

@endsection
