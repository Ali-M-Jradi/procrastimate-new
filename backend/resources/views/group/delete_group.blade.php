@extends('layouts.app')
@section('content')
    <h1>Delete Group</h1>
    <p>Are you sure you want to delete the group "{{ $group->name }}"?</p>
    <form action="{{ route('groups.destroy', $group->id) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit">Delete Group</button>
    </form>
@endsection
