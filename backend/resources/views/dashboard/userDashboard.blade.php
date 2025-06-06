@extends('layouts.app')

@section('content')
<header>
    <h1>Welcome, {{ $user->name }}</h1>
    <p>Manage your tasks and track your time effectively.</p>
    <nav>
        <ul>
            <li><a href="{{ route('userDashboard') }}">Dashboard</a></li>
            <li><a href="#tasks">View Tasks</a></li>
            <li><a href="#notifications">View Notifications</a></li>
            <li><a href="#comments">View Comments</a></li>
        </ul>
    </nav>
</header>
<main>
    <section id="tasks">
        <h2>Your Tasks</h2>
        <ul>
            @foreach($tasks as $task)
                <li>{{ $task->name }} - {{ $task->status }}</li>
            @endforeach
            <li><a href="{{ route('task.create') }}">Create Task</a></li>
        </ul>
    </section>
    <section id="notifications">
        <h2>Notifications</h2>
        <ul>
            @foreach($notifications as $notification)
                <li>{{ $notification->message }}</li>
            @endforeach
        </ul>
    </section>
    <section id="comments">
        <h2>Comments</h2>
        <ul>
            @foreach($comments as $comment)
                <li>{{ $comment->content }} - {{ $comment->author }}</li>
            @endforeach
        </ul>
    </section>
</main>
@endsection