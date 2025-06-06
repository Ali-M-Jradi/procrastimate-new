@extends('layouts.app')
@section('content')
    <h1>Welcome, {{ $user->name }}</h1>
    <p>Manage your groups and tasks, and track your team's progress.</p>
    <nav>
        <ul>
            <li><a href="{{ route('coach.dashboard') }}">Dashboard</a></li>
            <li><a href="{{ route('coach.groups') }}">Manage Groups</a></li>
            <li><a href="{{ route('coach.tasks') }}">Manage Tasks</a></li>
            <li><a href="{{ route('coach.notifications.view') }}">View Notifications</a></li>
            <li><a href="{{ route('coach.comments') }}">View Comments</a></li>
        </ul>
    </nav>
    <section id="groups">
        <h2>Your Groups</h2>
        <ul>
            @foreach($groups as $group)
                <li>{{ $group->name }} - {{ $group->description }}
                    <a href="{{ route('groups.edit', $group->id) }}">Edit</a>
                    <a href="{{ route('groups.delete', $group->id) }}">Delete</a>
                </li>
            @endforeach
        </ul>
    </section>
@endsection
