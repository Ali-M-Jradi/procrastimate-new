@extends('layouts.app')
@section('content')
    <h1>Group: {{ $group->name }}</h1>
    <p>{{ $group->description }}</p>
    <h3>Members</h3>
    <ul>
        @foreach($group->users as $user)
            <li>{{ $user->name }} ({{ $user->email }})</li>
        @endforeach
    </ul>
    <h3>Actions</h3>
    @if(Auth::id() === $group->owner_id)
        <a href="{{ route('groups.edit', $group->id) }}">Edit Group</a> |
        <form action="{{ route('groups.destroy', $group->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit">Delete Group</button>
        </form>
    @endif
    <form action="{{ route('groups.leave') }}" method="POST" style="margin-top:10px;">
        @csrf
        <input type="hidden" name="group_id" value="{{ $group->id }}">
        <button type="submit">Leave Group</button>
    </form>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth-theme.css') }}">
@endpush
