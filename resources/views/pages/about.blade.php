@extends('layouts.app')

@section('content') 
@include('sidebars.bar')

<div class="aboutContainer">
    <h1>About Us</h1>
    <p>This social media website is designed for the curricular unit LBAW at FEUP. In SocialCircle users can post what they're thinking, make friends, comment and like eachother's posts. Although it is still in development, anyone using this social network can already do all of these things!</p>
    <div class="teamContainer">
        <h2>Team</h2>
        <div class="members">
            <div class="teamMember">
                <h3>Miguel Dionísio</h3>
                <img class="aboutPhoto" src="{{ asset('images/miguel.JPG') }}" alt="miguel">
                <p>up202108788</p>
            </div>
            <div class="teamMember">
                <h3>Rúben Esteves</h3>
                <img class="aboutPhoto" src="{{ asset('images/ruben.JPG') }}" alt="ruben">
                <p>up202006479</p>
            </div>
        </div>
</div>

@endsection