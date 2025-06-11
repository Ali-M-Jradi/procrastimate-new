@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<header class="header">
    <h1>Welcome, User {{ $user->name }}</h1>
    <nav>
        <ul>
            <li><a href="{{ route('userDashboard') }}">Dashboard</a></li>
            <li><a href="#tasks">Tasks</a></li>
            <li><a href="#groups">Groups</a></li>
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
<div class="container">
    <main>
        <section id="tasks">
            <h2>Your Tasks</h2>
            @if ($tasks->isEmpty())
                <div class="empty-state">
                    <p>No tasks yet. Create your first task below!</p>
                    <a href="{{ route('task.create') }}" class="btn btn-primary">Create Your First Task</a>
                </div>
            @else
                <div class="task-list">
                    @foreach($tasks as $task)
                        @php
                            $dueDate = \Carbon\Carbon::parse($task->dueDate);
                            $daysLeft = $dueDate->diffInDays(now(), false);
                        @endphp
                        <div class="task-item @if($daysLeft >= 0 && $daysLeft <= 3) alerted-task @endif">
                            <h3>{{ $task->title }}</h3>
                            <p>{{ $task->description }}</p>
                            <p class="task-date">Due: {{ date('F j, Y', strtotime($task->dueDate)) }}
                                @if($daysLeft >= 0 && $daysLeft <= 3)
                                    <span class="badge badge-danger ml-2">Due Soon!</span>
                                @endif
                            </p>
                            <p>Status: <span class="badge badge-{{ $task->status === 'out_of_date' ? 'danger' : ($task->status === 'pending' ? 'warning' : ($task->status === 'approved' ? 'success' : 'secondary')) }}">{{ ucfirst($task->status) }}</span></p>
                            <div class="task-actions">
                                <a href="{{ route('task.updateForm', $task->id) }}" class="btn btn-primary">Edit</a>
                                <form action="{{ route('task.delete', $task->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this task?')">Delete</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    <a href="{{ route('task.create') }}" class="btn btn-primary">Create New Task</a>
                </div>
            @endif
        </section>

        <section id="groups">
            <h2>Your Groups</h2>
            @if(isset($user->groups) && $user->groups->count() > 0)
                <div class="group-list">
                    @foreach($user->groups as $group)
                        <div class="task-item">
                            <h3>{{ $group->name }}</h3>
                            <p>{{ $group->description }}</p>
                            <div class="task-actions">
                                <form action="{{ route('groups.leave') }}" method="POST" style="display: inline;">
                                    @csrf
                                    <input type="hidden" name="group_id" value="{{ $group->id }}">
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to leave this group?')">Leave Group</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    <a href="{{ route('groups.joinForm') }}" class="btn btn-primary">Join Another Group</a>
                </div>
            @else
                <div class="empty-state">
                    <p>You're not a member of any groups yet.</p>
                    <a href="{{ route('groups.joinForm') }}" class="btn btn-primary">Join a Group</a>
                </div>
            @endif
        </section>

        <section id="notifications">
            <h2>Notifications</h2>
            @if($notifications->isEmpty())
                <div class="empty-state">
                    <p>No notifications at this time.</p>
                </div>
            @else
                <div class="notification-list">
                    @foreach($notifications as $notification)
                        <div class="notification-item">
                            <p>{{ $notification->message }}</p>
                            <small>{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        <section id="comments">
            <h2>Comments</h2>
            @if($comments->isEmpty())
                <div class="empty-state">
                    <p>No comments yet.</p>
                </div>
            @else
                <div class="comment-list">
                    @foreach($comments as $comment)
                        @if($comment->user_id === $user->id)
                        <div class="comment-item">
                            <strong>Message:</strong>
                            <p>{{ $comment->comment }}</p>
                            <small><strong>User:</strong> {{ $user->name }}</small>
                        </div>
                        <hr>
                        @endif
                    @endforeach
                </div>
            @endif
            <a href="{{ route('comment.create') }}" class="btn btn-outline-info mb-3">Add Comment</a>
        </section>
    </main>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/coach-dashboard.js') }}"></script>