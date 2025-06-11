@extends('layouts.app')

@section('content')
<div class="container">
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

    <section>
        <h2>Manage Groups</h2>
        <p class="section-description">Use the options below to manage groups.</p>
        
        @php
            $isAdminOrCoach = auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'coach');
        @endphp

        <div class="task-actions mt-4">
            @if($isAdminOrCoach)
                <a href="{{ route('group.create') }}" class="btn btn-primary">Create New Group</a>
            @endif
            <a href="{{ route('group.index') }}" class="btn btn-success">View All Groups</a>
        </div>

        @if(isset($groups) && $groups->count() > 0)
            <div class="group-list mt-4">
                @foreach($groups as $group)
                    <div class="task-item">
                        <h3>{{ $group->name }}</h3>
                        <p>{{ $group->description }}</p>
                        <div class="task-actions">
                            @if($isAdminOrCoach)
                                <a href="{{ route('group.edit_group', $group->id) }}" class="btn btn-primary">Edit</a>
                                <form action="{{ route('group.delete_group', $group->id) }}" method="POST" style="display: inline;">
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
    </section>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth-theme.css') }}">
@endpush
