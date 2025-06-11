@extends('layouts.app')

@section('content')
<div class="container fade-section">
    <h2>Reject Task</h2>
    <div class="alert alert-warning">
        <p>Are you sure you want to reject the following task?</p>
        <ul>
            <li><strong>Title:</strong> {{ $task->title }}</li>
            <li><strong>Description:</strong> {{ $task->description }}</li>
            <li><strong>Due Date:</strong> {{ $task->dueDate }}</li>
            <li><strong>Assigned User:</strong> {{ $task->user ? $task->user->name : 'Unassigned' }}</li>
        </ul>
    </div>
    <form action="{{ route('task.reject', $task->id) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-danger">Reject Task</button>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
