@extends('layouts.app')

@section('content')
<div class="container fade-section">
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

    <section>
        <h2>Task Details</h2>
        <h3>{{ $task->title }}</h3>
        <p>{{ $task->description }}</p>
        <p>Due: {{ $task->dueDate }}</p>
        <p>Status: <span class="badge badge-{{ $task->status }}">{{ ucfirst($task->status) }}</span></p>
        <a href="{{ route('task.updateForm', $task->id) }}" class="btn btn-primary">Edit</a>
        <form action="{{ route('task.delete', $task->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
        <br><br>
        @if($user && $user->role === 'admin')
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary mt-2">Return to Dashboard</a>
        @elseif($user && $user->role === 'coach')
            <a href="{{ route('coach.dashboard') }}" class="btn btn-secondary mt-2">Return to Dashboard</a>
        @else
            <a href="{{ route('userDashboard') }}" class="btn btn-secondary mt-2">Return to Dashboard</a>
        @endif
    </section>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/coach-dashboard.js') }}"></script>