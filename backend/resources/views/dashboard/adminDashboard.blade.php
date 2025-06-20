@extends('layouts.app')

@section('content')
@include('partials.header', ['title' => 'Welcome, Admin ' . ($admin->name ?? 'User')])
<div class="container">
    <main>
        <section id="users">
            <h2>All Users</h2>
            <div class="mb-3">
                <a href="{{ route('admin.user.createForm') }}" class="btn btn-success me-2">Create New User</a>
                <a href="{{ route('admin.coach.createForm') }}" class="btn btn-info me-2">Create New Coach</a>
            </div>
            <br>
            @if(isset($users) && $users->count() > 0)
                <div class="user-list">
                    @foreach($users as $userItem)
                        <div class="task-item">
                            <h3>{{ $userItem->name }}</h3>
                            <p>{{ $userItem->email }}</p>
                            <span class="badge badge-primary">{{ ucfirst($userItem->role) }}</span>
                            <div class="task-actions">
                                <a href="{{ route('admin.user.updateForm', $userItem->id) }}" class="btn btn-primary">Edit</a>
                                @if($userItem->role === 'user')
                                    <a href="{{ route('admin.user.promote', $userItem->id) }}" class="btn btn-success">Promote to Coach</a>
                                @elseif($userItem->role === 'coach')
                                    <a href="{{ route('admin.coach.demote', $userItem->id) }}" class="btn btn-warning">Demote to User</a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <p>No users found.</p>
                </div>
            @endif
        </section>

        <section id="groups">
            <h2>All Groups</h2>
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
                    <p>No groups found.</p>
                    <a href="{{ route('groups.create') }}" class="btn btn-primary">Create Your First Group</a>
                </div>
            @endif
        </section>

        <section id="tasks">
            <h2>All Tasks</h2>
            @if(isset($tasks) && $tasks->count() > 0)
                <div class="task-list">
                    @foreach($tasks as $task)
                        <div class="task-item">
                            <h3>{{ $task->title }}</h3>
                            <p>{{ $task->description }}</p>
                            <p class="task-date">Due: {{ date('F j, Y', strtotime($task->dueDate)) }}</p>
                            <p>Assigned to: {{ $task->user->name }}</p>
                            <p>Status: <span class="badge badge-{{ $task->status === 'out_of_date' ? 'danger' : ($task->status === 'pending' ? 'warning' : ($task->status === 'approved' ? 'success' : 'secondary')) }}">{{ ucfirst($task->status) }}</span></p>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <p>No tasks found.</p>
                </div>
            @endif
        </section>

        <section id="comments">
            <h2>All Comments</h2>
            <a href="{{ route('admin.comment.index') }}" class="btn btn-primary mb-3">Manage Comments</a>
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
                    <p>No notifications at this time.</p>
                </div>
            @endif
        </section>
    </main>
</div>
@endsection