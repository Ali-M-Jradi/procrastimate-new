@extends('layouts.app')
@section('content')
    <h2>Promote User to Coach</h2>
    <form action="{{ route('admin.user.promote', $user->id) }}" method="POST">
        @csrf
        <label for="role">New Role:</label>
        <input type="text" id="role" name="role" value="coach" readonly>
        <br><br>
        <button type="submit">Promote</button>
    </form>
@endsection
