@extends('layouts.app')

@section('content')
<div class="container">
    <header>
        <h1>Admin Dashboard</h1>
        <p>Manage users, tasks, and system settings.</p>
        <nav>
            <ul>
                <li><a href="#user-management">Users</a></li>
                <li><a href="#group-management">Groups</a></li>
                <li><a href="#task-management">Tasks</a></li>
                <li><a href="#coach-management">Coaches</a></li>
            </ul>
        </nav>
    </header>

    <section id="user-management">
        <h2>User Management</h2>
        <div class="task-actions">
            <a href="{{ route('admin.user.list') }}" class="btn btn-primary">View Users</a>
            <a href="{{ route('admin.user.create') }}" class="btn btn-success">Add User</a>
        </div>
        @if(isset($users) && $users->count() > 0)
            <div class="task-list mt-4">
                @foreach($users as $user)
                    <div class="task-item">
                        <h3>{{ $user->name }}</h3>
                        <p>{{ $user->email }}</p>
                        <p>Role: <span class="badge badge-primary">{{ ucfirst($user->role) }}</span></p>
                        <div class="task-actions">
                            <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-primary">Edit</a>
                            @if($user->role === 'user')
                                <a href="{{ route('admin.user.promote', $user->id) }}" class="btn btn-success">Promote to Coach</a>
                            @elseif($user->role === 'coach')
                                <a href="{{ route('admin.coach.demote', $user->id) }}" class="btn btn-warning">Demote to User</a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    <section id="group-management">
        <h2>Group Management</h2>
        <div class="task-list">
            @if(isset($groups) && $groups->count() > 0)
                @foreach($groups as $group)
                    <div class="task-item">
                        <h3>{{ $group->name }}</h3>
                        <p>{{ $group->description }}</p>
                        <p>Members: {{ $group->users()->count() }}</p>
                        <div class="task-actions">
                            <a href="{{ route('groups.edit', $group->id) }}" class="btn btn-primary">Edit Group</a>
                            <form action="{{ route('groups.destroy', $group->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete Group</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <p>No groups created yet.</p>
                </div>
            @endif
        </div>
    </section>

    <section id="task-management">
        <h2>Task Management</h2>
        @if(isset($tasks) && $tasks->count() > 0)
            <div class="task-list">
                @foreach($tasks as $task)
                    <div class="task-item">
                        <h3>{{ $task->title }}</h3>
                        <p>{{ $task->description }}</p>
                        <p>Due: {{ date('F j, Y', strtotime($task->dueDate)) }}</p>
                        <p>Assigned to: {{ $task->user->name }}</p>
                        <div class="task-actions">
                            <a href="{{ route('task.update', $task->id) }}" class="btn btn-primary">Edit</a>
                            <form action="{{ route('task.delete', $task->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                            @if($task->isCompleted)
                                <span class="badge badge-success">Completed</span>
                            @else
                                <span class="badge badge-warning">Pending</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <p>No tasks available.</p>
            </div>
        @endif
    </section>
</div>
@endsection
