@extends('layouts.app')
@section('content')
    <h1>Manage Groups</h1>
    <p>Use the options below to manage groups.</p>
    <ul>
        <li><a href="{{ route('groups.create') }}">Create New Group</a></li>
        <li><a href="{{ route('groups.edit', 1) }}">Edit Group</a></li>
        <li><a href="{{ route('groups.delete', 1) }}">Delete Group</a></li>
        <li><a href="{{ route('groups.index') }}">View All Groups</a></li>
    </ul>
@endsection
