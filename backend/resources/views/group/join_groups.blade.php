@extends('layouts.app')
@section('content')
    <h1>Join Groups</h1>
    <p>Use the form below to join a group.</p>
    <form action="{{ route('groups.join') }}" method="POST">
        @csrf
        <label for="group">Select Group:</label>
        <select id="group" name="group">
            @foreach($groups as $group)
                <option value="{{ $group->id }}">{{ $group->name }}</option>
            @endforeach
        </select>
        <br><br>
        <button type="submit">Join Group</button>
    </form>
@endsection
