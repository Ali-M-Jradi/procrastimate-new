@extends('layouts.app')
@section('content')
    <h1>Edit Group</h1>
    <p>Use the form below to edit the group details.</p>
    <form action="{{ route('groups.update', $group->id) }}" method="POST">
        @csrf
        @method('PUT')
        <label for="group_name">Group Name:</label>
        <input type="text" id="group_name" name="group_name" value="{{ $group->name }}" required>
        <br><br>
        <button type="submit">Update Group</button>
    </form>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth-theme.css') }}">
@endpush
