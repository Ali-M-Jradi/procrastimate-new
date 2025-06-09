@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
@endpush

@section('content')
<div class="container">
    <header>
        <h1>Welcome, Coach {{ $user->name }}</h1>
        <p>Manage your groups and tasks, and coach your team effectively.</p>
        <nav>
            <ul>
                <li><a href="{{ route('coach.dashboard') }}">Dashboard</a></li>
                <li><a href="#groups">Groups</a></li>
                <li><a href="#tasks">Tasks</a></li>
                <li><a href="#notifications">Notifications</a></li>
                <li><a href="#comments">Comments</a></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-danger">Logout</button>
                    </form>
                </li>
            </ul>
        </nav>
    </header>

    <main>
        <section id="groups">
            <h2>Your Groups</h2>
            @if(isset($groups) && $groups->count() > 0)
                <div class="group-list">
                    @foreach($groups as $group)
                        <div class="task-item">
                            <h3>{{ $group->name }}</h3>
                            <p>{{ $group->description }}</p>
                            <div class="task-actions">
                                <a href="{{ route('groups.edit', $group->id) }}" class="btn btn-primary">Edit</a>
                                <form action="{{ route('groups.destroy', $group->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this group?')">Delete</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    <a href="{{ route('groups.create') }}" class="btn btn-success">Create New Group</a>
                </div>
            @else
                <div class="empty-state">
                    <p>You haven't created any groups yet.</p>
                    <a href="{{ route('groups.create') }}" class="btn btn-primary">Create Your First Group</a>
                </div>
            @endif
        </section>

        <section id="tasks">
            <h2>Team Tasks</h2>
            @if(isset($tasks) && $tasks->count() > 0)
                <div class="task-list">
                    @foreach($tasks as $task)
                        <div class="task-item">
                            <h3>{{ $task->title }}</h3>
                            <p>{{ $task->description }}</p>
                            <p class="task-date">Due: {{ date('F j, Y', strtotime($task->dueDate)) }}</p>
                            <p>Assigned to: {{ $task->user->name }}</p>
                            <div class="task-actions">
                                @if(!$task->isCompleted)
                                    <form action="{{ route('task.approve', $task->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success">Approve</button>
                                    </form>
                                    <form action="{{ route('task.reject', $task->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-warning">Reject</button>
                                    </form>
                                @else
                                    <span class="badge badge-success">Completed</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <p>No team tasks to review at the moment.</p>
                </div>
            @endif
        </section>

        <section id="notifications">
            <h2>Notifications</h2>
            @if(isset($notifications) && $notifications->count() > 0)
                <div class="notification-list">
                    @foreach($notifications as $notification)
                        <div class="notification-item">
                            <p>{{ $notification->message }}</p>
                            <small>{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <p>No notifications at the moment.</p>
                </div>
            @endif
        </section>

        <section id="comments">
            <h2>Recent Comments</h2>
            @if(isset($comments) && $comments->count() > 0)
                <div class="comment-list">
                    @foreach($comments as $comment)
                        <div class="comment-item">
                            <p>{{ $comment->content }}</p>
                            <small>By {{ $comment->user->name }} - {{ $comment->created_at->diffForHumans() }}</small>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <p>No comments yet.</p>
                </div>
            @endif
        </section>
    </main>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/coach-dashboard.js') }}"></script>
@endsection