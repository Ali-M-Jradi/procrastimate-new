@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Welcome to Procrastimate!</h1>
    <p>Your productivity companion. Track tasks, set goals, and beat procrastination.</p>
    @guest
        <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
        <a href="{{ route('register') }}" class="btn btn-secondary">Register</a>
    @else
        <a href="{{ route('dashboard') }}" class="btn btn-success">Go to Dashboard</a>
    @endguest
</div>
@endsection