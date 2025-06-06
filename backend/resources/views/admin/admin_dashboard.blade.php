@extends('layouts.app')
@section('content')
    <h1>Admin Dashboard</h1>
    <section id="user-management">
        <h2>User Management</h2>
        <p>Manage users and their roles.</p>
        <ul>
            <li><a href="{{ route('admin.user.list') }}">View Users</a></li>
            <li><a href="{{ route('admin.user.create') }}">Add User</a></li>
            <li><a href="{{ route('admin.user.edit', 1) }}">Edit Users</a></li>
            <li><a href="{{ route('admin.user.promote', 1) }}">Promote User to Coach</a></li>
            <li><a href="{{ route('admin.coach.demote', 1) }}">Demote Coach to User</a></li>
        </ul>
    </section>
    <section id="group-management">
        <h2>Group Management</h2>
        <p>Manage all groups (coach abilities included).</p>
        <ul>
            <li><a href="{{ route('groups.index') }}">View Groups</a></li>
        </ul>
    </section>
@endsection
