@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
@endpush

@section('content')
<div class="container">
    <header>
        <h1>Welcome, Admin {{ $admin->name }}</h1>
        <p>Manage users, groups, and oversee the platform.</p>
        <nav>
            <ul>
                <li><a href="{{ route('dashboard.admin') }}">Admin Dashboard</a></li>
                <li><a href="#users">Users</a></li>
                <li><a href="#groups">Groups</a></li>
                <li><a href="#tasks">Tasks</a></li>
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
        <section id="users">
            <h2>All Users</h2>
            @if(isset($users) && $users->count() > 0)
                <div class="user-list">
                    @foreach($users as $userItem)
                        <div class="task-item">
                            <h3>{{ $userItem->name }}</h3>
                            <p>{{ $userItem->email }}</p>
                            <span class="badge badge-primary">{{ ucfirst($userItem->role) }}</span>
                            <div class="task-actions">
                                <a href="{{ route('admin.user.edit', $userItem->id) }}" class="btn btn-primary">Edit</a>
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
                            <div class="task-actions">
                                @if(!$task->isCompleted)
                                    <span class="badge badge-warning">Pending</span>
                                @else
                                    <span class="badge badge-success">Completed</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <p>No tasks found.</p>
                </div>
            @endif
        </section>
    </main>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/coach-dashboard.js') }}"></script>
@endsection