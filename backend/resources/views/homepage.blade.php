@extends('layouts.app')

@section('content')
<div class="container">
    <header class="text-center">
        <h1>Welcome to Procrastimate!</h1>
        <p>Your productivity companion. Track tasks, set goals, and beat procrastination.</p>
    </header>

    <section>
        @guest
            <div class="task-actions" style="justify-content: center;">
                <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                <a href="{{ route('register') }}" class="btn btn-success">Register</a>
            </div>
        @else
            <div class="task-actions" style="justify-content: center;">
                <a href="{{ route('dashboard') }}" class="btn btn-success">Go to Dashboard</a>
            </div>
        @endguest
    </section>

    <section>
        <h2>Features</h2>
        <div class="task-list">
            <div class="task-item">
                <h3>Task Management</h3>
                <p>Create, update, and track your tasks easily.</p>
            </div>
            <div class="task-item">
                <h3>Progress Tracking</h3>
                <p>Monitor your productivity and task completion rates.</p>
            </div>
            <div class="task-item">
                <h3>Team Collaboration</h3>
                <p>Work together with coaches and team members.</p>
            </div>
            <div class="task-item">
                <h3>Smart Notifications</h3>
                <p>Stay updated with important task reminders.</p>
            </div>
        </div>
    </section>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth-theme.css') }}">
@endpush