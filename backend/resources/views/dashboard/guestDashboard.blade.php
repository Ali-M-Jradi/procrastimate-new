@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<header class="header">
    <h1>Welcome, Guest!</h1>
    <nav>
        <ul>
            <li><a href="{{ route('homepage') }}">Home</a></li>
            <li><a href="{{ route('login') }}">Login</a></li>
            <li><a href="{{ route('register') }}">Register</a></li>
        </ul>
    </nav>
</header>
<div class="container">
    <section>
        <h2>Guest Dashboard</h2>
        <p>
            Please <a href="{{ route('login') }}">login</a> or 
            <a href="{{ route('register') }}">register</a> to access your dashboard.
        </p>
        <p>
            Or visit the <a href="{{ route('dashboard.guest') }}">Guest Dashboard</a>.
        </p>
    </section>
</div>
@endsection