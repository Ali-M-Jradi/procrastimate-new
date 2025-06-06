@extends('layouts.app')
@section('content')
    <h1>Edit User</h1>
    <form method="POST" action="{{ route('admin.user.update', $user->id) }}">
        @csrf
        @method('PUT')
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="{{ $user->name }}" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="{{ $user->email }}" required>
        <br>
        <label for="role">Role:</label>
        <select id="role" name="role">
            <option value="user" @if($user->role == 'user') selected @endif>User</option>
            <option value="admin" @if($user->role == 'admin') selected @endif>Admin</option>
            <option value="coach" @if($user->role == 'coach') selected @endif>Coach</option>
        </select>
        <br>
        <button type="submit">Save Changes</button>
    </form>
    <a href="{{ route('admin.user.list') }}">Back to User List</a>
@endsection
