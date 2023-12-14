@extends('layouts.app')

@section('content') 
@include('sidebars.bar')

<h1>Create Group</h1>
  <form action="{{ route('group.create') }}" method="POST">
    @csrf
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required><br><br>
    
    <label for="description">Description:</label>
    <textarea id="description" name="description" required></textarea><br><br>
    
    <label for="isPublic">Private:</label>
    <label class="switch">
      <input type="checkbox" id="isPublic" name="isPublic" value="0">
      <span class="slider round"></span>
    </label><br><br>
    
    <input type="hidden" id="ownerID" name="ownerID" value="{{ Auth::user()->id }}">
    
    <input type="submit" value="Create Group">
  </form>

@endsection