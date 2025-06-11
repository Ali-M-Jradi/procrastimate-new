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
@endif
<div class="container">
    @php
        $isAdminOrCoach = auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'coach');
    @endphp
    <section>
        <h2>All Groups</h2>
        @if($groups->isEmpty())
            <div class="empty-state">
                <p>No groups available.</p>
            </div>
        @else
            <div class="group-list">
                @foreach($groups as $group)
                    <div class="task-item">
                        <h3>{{ $group->name }}</h3>
                        <p>{{ $group->description }}</p>
                        <div class="task-actions">
                            <a href="{{ route('groups.view', $group->id) }}" class="btn btn-primary">View Details</a>
                            @if($isAdminOrCoach)
                                <a href="{{ route('group.updateForm', $group->id) }}" class="btn btn-secondary">Edit</a>
                                <form action="{{ route('groups.destroy', $group->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this group?')">Delete</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
        @if($isAdminOrCoach)
            <div class="mt-4">
                <a href="{{ route('groups.create') }}" class="btn btn-success">Create New Group</a>
            </div>
        @endif
    </section>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth-theme.css') }}">
@endpush
