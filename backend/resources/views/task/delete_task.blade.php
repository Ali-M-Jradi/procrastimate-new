@extends('layouts.app')
@section('content')
    <h1>Delete Task</h1>
    <p>Are you sure you want to delete the task "{{ $task->name }}"?</p>
    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit">Yes, delete this task</button>
    </form>
    <a href="{{ route('userDashboard') }}">Cancel</a>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth-theme.css') }}">
@endpush
