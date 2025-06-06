@extends('layouts.app')
@section('content')
    <h1>Demote Coach</h1>
    <p>Use the form below to demote a coach.</p>
    <form action="{{ route('admin.user.demote', $user->id) }}" method="POST">
        @csrf
        <label for="role">New Role:</label>
        <input type="text" id="role" name="role" value="user" readonly>
        <br><br>
        <button type="submit">Demote</button>
    </form>
@endsection
