@extends('layouts.app')
@section('content')
    <h1>Create Group</h1>
    <p>Use the form below to create a new group.</p>
    <form action="{{ route('groups.store') }}" method="POST">
        @csrf
        <label for="group_name">Group Name:</label>
        <input type="text" id="group_name" name="group_name" required>
        <br><br>
        <button type="submit">Create Group</button>
    </form>
@endsection
