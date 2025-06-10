@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<header class="header">
    <h1>Add Comment</h1>
    <nav>
        <ul>
            <li><a href="{{ route('homepage') }}">Home</a></li>
            <li><a href="{{ route('login') }}">Login</a></li>
            <li><a href="{{ route('register') }}">Register</a></li>
        </ul>
    </nav>
</header>
<div class="container fade-section">
    <h2>Add Comment to Task: {{ $task->title ?? '' }}</h2>
    <form action="{{ request()->routeIs('coach.comment.createForm') ? route('coach.comment.create') : route('comment.create') }}" method="POST">
        @csrf
        <input type="hidden" name="task_id" value="{{ $task->id }}">
        <div class="form-group">
            <label for="comment">Comment</label>
            <textarea name="comment" id="comment" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Add Comment</button>
    </form>
</div>
@endsection
