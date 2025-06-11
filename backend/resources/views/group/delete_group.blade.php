@extends('layouts.app')
@section('content')
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

    <h1>Delete Group</h1>
    <p>Are you sure you want to delete the group "{{ $group->name }}"?</p>
    @php
        $isAdminOrCoach = auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'coach');
    @endphp
    <form action="{{ route('groups.destroy', $group->id) }}" method="POST">
        @csrf
        @method('DELETE')
        @if($isAdminOrCoach)
            <button type="submit">Delete Group</button>
        @else
            <button type="button" class="btn btn-secondary" disabled>Delete Group (Not Allowed)</button>
        @endif
    </form>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth-theme.css') }}">
@endpush
