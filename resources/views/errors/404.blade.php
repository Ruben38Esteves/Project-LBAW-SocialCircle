@extends('layouts.app')

@section('content') 
@include('sidebars.bar')

<div class="error">
<h1>OOOPS! Page not found!</h1>
<h2>Error 404</h2>
<h3>Go back to <a href="/home">home</a></h3>
</div>

@endsection