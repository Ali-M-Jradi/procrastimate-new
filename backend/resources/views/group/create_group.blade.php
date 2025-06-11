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
    <section>
        <h2>Group Details</h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @php
            $isAdminOrCoach = auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'coach');
        @endphp

        <form action="{{ route('groups.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Group Name</label>
                <input type="text" name="name" id="name" class="form-control" required value="{{ old('name') }}" placeholder="Enter group name" @if(!$isAdminOrCoach) disabled @endif>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" required @if(!$isAdminOrCoach) disabled @endif>{{ old('description') }}</textarea>
            </div>

            <div class="task-actions">
                @if($isAdminOrCoach)
                    <button type="submit" class="btn btn-success">Create Group</button>
                @endif
                <a href="{{ route('coach.dashboard') }}" class="btn btn-danger">Cancel</a>
            </div>
        </form>
    </section>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/coach-dashboard.js') }}"></script>
