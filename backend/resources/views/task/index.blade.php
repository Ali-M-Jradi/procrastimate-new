@extends('layouts.app')

@section('content')
@php
    $user = auth()->user();
    $title = '';
    if($user && $user->role === 'admin') {
        $title = 'Admin Dashboard';
    } elseif($user && $user->role === 'coach') {
        $title = 'Coach Dashboard';
    } elseif($user && $user->role === 'user') {
        $title = 'My Dashboard';
    }
@endphp
@include('partials.header', ['title' => $title])
<div class="container fade-section">
    <h1>All Tasks</h1>
    <a href="{{ route('task.createForm') }}" class="btn btn-primary mb-3">Create New Task</a>
    @if($tasks->isEmpty())
        <div class="empty-state">
            <p>No tasks available.</p>
        </div>
    @else
        <div class="task-list">
            @foreach($tasks as $task)
                <div class="task-item">
                    <h3>{{ $task->title }}</h3>
                    <p>{{ $task->description }}</p>
                    <p>Due: {{ $task->dueDate }}</p>
                    <a href="{{ route('task.updateForm', $task->id) }}" class="btn btn-primary">Edit</a>
                    <form action="{{ route('task.delete', $task->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
