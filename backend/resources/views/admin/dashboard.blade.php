@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Admin Dashboard</h1>

    <!-- Users Section -->
    <div class="section">
        <h2>Users Management</h2>
        <a href="{{ route('admin.user.createForm') }}" class="btn btn-primary">Create New User</a>
        
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ ucfirst($user->role) }}</td>
                    <td>
                        <a href="{{ route('admin.user.updateForm', $user->id) }}" class="btn btn-sm btn-primary">Edit</a>
                        <a href="{{ route('admin.user.deleteForm', $user->id) }}" class="btn btn-sm btn-danger">Delete</a>
                        @if($user->role === 'user')
                            <a href="{{ route('admin.user.promoteForm', $user->id) }}" class="btn btn-sm btn-success">Promote to Coach</a>
                        @elseif($user->role === 'coach')
                            <a href="{{ route('admin.coach.demoteForm', $user->id) }}" class="btn btn-sm btn-warning">Demote to User</a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Tasks Overview -->
    <div class="section mt-5">
        <h2>Tasks Overview</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Assigned To</th>
                    <th>Due Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tasks as $task)
                <tr>
                    <td>{{ $task->title }}</td>
                    <td>{{ $task->user->name }}</td>
                    <td>{{ $task->dueDate }}</td>
                    <td>{{ $task->isCompleted ? 'Completed' : 'Pending' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Groups Overview -->
    <div class="section mt-5">
        <h2>Groups Overview</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Members</th>
                    <th>Created By</th>
                </tr>
            </thead>
            <tbody>
                @foreach($groups as $group)
                <tr>
                    <td>{{ $group->name }}</td>
                    <td>{{ $group->users->count() }}</td>
                    <td>{{ $group->creator->name }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
