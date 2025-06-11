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
    @endif

    <section>
        <h2>Edit Group</h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @php
            $isAdminOrCoach = auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'coach');
        @endphp
        <form action="{{ route('groups.update', $group->id) }}" method="PUT">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Group Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $group->name }}" required @if(!$isAdminOrCoach) disabled @endif>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" required @if(!$isAdminOrCoach) disabled @endif>{{ $group->description }}</textarea>
            </div>
            <div class="task-actions">
                @if($isAdminOrCoach)
                    <button type="submit" class="btn btn-primary">Update Group</button>
                @endif
                @if($user && $user->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Cancel</a>
                @elseif($user && $user->role === 'coach')
                    <a href="{{ route('coach.dashboard') }}" class="btn btn-secondary">Cancel</a>
                @endif
            </div>
        </form>
    </section>
</div>
@endsection
