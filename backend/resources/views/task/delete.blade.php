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
    <section>
        <h2>Delete Task</h2>
        <div class="alert alert-danger">
            <p>Are you sure you want to delete this task?</p>
            
            <div class="task-item">
                <h3>{{ $task->title }}</h3>
                <p>{{ $task->description }}</p>
                <p>Due: {{ date('F j, Y', strtotime($task->dueDate)) }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('task.delete', $task->id) }}">
            @csrf
            @method('DELETE')
            <div class="task-actions">
                <button type="submit" class="btn btn-danger">Delete Task</button>
                <a href="{{ url()->previous() }}" class="btn btn-primary">Cancel</a>
            </div>
        </form>
    </section>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth-theme.css') }}">
@endpush
