@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
@php
    $user = auth()->user();
@endphp
@if($user && $user->role === 'admin')
    <header class="header">
        <h1>Admin Dashboard</h1>
        <nav>
            <ul>
                <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('groups.index') }}">Groups</a></li>
                <li><a href="{{ route('admin.user.createForm') }}">Add User</a></li>
                <li><a href="{{ route('admin.notification.index') }}">Notifications</a></li>
                <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
            </ul>
        </nav>
    </header>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
@elseif($user && $user->role === 'coach')
    <header class="header">
        <h1>Coach Dashboard</h1>
        <nav>
            <ul>
                <li><a href="{{ route('coach.dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('groups.index') }}">Groups</a></li>
                <li><a href="{{ route('coach.task.create') }}">Add Task</a></li>
                <li><a href="{{ route('notifications.view') }}">Notifications</a></li>
                <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
            </ul>
        </nav>
    </header>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
@elseif($user && $user->role === 'user')
    <header class="header">
        <h1>User Dashboard</h1>
        <nav>
            <ul>
                <li><a href="{{ route('userDashboard') }}">Dashboard</a></li>
                <li><a href="#tasks">Tasks</a></li>
                <li><a href="#groups">Groups</a></li>
                <li><a href="#comments">Comments</a></li>
                <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
            </ul>
        </nav>
    </header>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
@endif
<div class="container fade-section">
    <section>
        <h2>Task Details</h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('task.create') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" class="form-control" required value="{{ old('title') }}" placeholder="Enter task title">
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" required placeholder="Enter task description" rows="4">{{ old('description') }}</textarea>
            </div>
            <div class="form-group">
                <label for="dueDate">Due Date</label>
                <input type="date" name="dueDate" id="dueDate" class="form-control" required value="{{ old('dueDate') }}">
            </div>
            <div class="task-actions">
                <button type="submit" class="btn btn-success">Create Task</button>
                @if($user && $user->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-danger">Cancel</a>
                @elseif($user && $user->role === 'coach')
                    <a href="{{ route('coach.dashboard') }}" class="btn btn-danger">Cancel</a>
                @else
                    <a href="{{ route('userDashboard') }}" class="btn btn-danger">Cancel</a>
                @endif
            </div>
        </form>
    </section>
</div>
@endsection