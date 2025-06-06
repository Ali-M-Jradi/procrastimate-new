@extends('layouts.app')
@section('content')
    <h1>User List</h1>
    <p>Welcome to the user list page!</p>
    <p>Here you can view and manage users.</p>
    <h2>Users</h2>
    <ul>
        @foreach($users as $user)
            <li>{{ $user->name }} - {{ $user->email }}</li>
        @endforeach
    </ul>
    <h2>Add New User</h2>
    <form method="POST" action="{{ route('admin.user.store') }}">
        @csrf
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <button type="submit">Add User</button>
    </form>
@endsection
