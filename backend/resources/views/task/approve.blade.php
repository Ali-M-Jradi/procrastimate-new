@extends('layouts.app')

@section('content')
@php
    $user = auth()->user();
    $title = '';
    if($user && $user->role === 'admin') {
        $title = 'Admin Dashboard';
    } elseif($user && $user->role === 'coach') {
        $title = 'Coach Dashboard';
    }
@endphp
@include('partials.header', ['title' => $title])
<div class="container fade-section">
    <h2>Approve Task</h2>
    <div class="alert alert-info">
        <p>Are you sure you want to approve the following task?</p>
        <ul>
            <li><strong>Title:</strong> {{ $task->title }}</li>
            <li><strong>Description:</strong> {{ $task->description }}</li>
            <li><strong>Due Date:</strong> {{ $task->dueDate }}</li>
            <li><strong>Assigned User:</strong> {{ $task->user ? $task->user->name : 'Unassigned' }}</li>
        </ul>
    </div>
    <form action="{{ route('task.approve', $task->id) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-success">Approve Task</button>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
