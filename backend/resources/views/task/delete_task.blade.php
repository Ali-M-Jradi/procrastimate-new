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
    <h1>Delete Task</h1>
    <p>Are you sure you want to delete the task "{{ $task->name }}"?</p>
    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit">Yes, delete this task</button>
    </form>
    <a href="{{ route('userDashboard') }}">Cancel</a>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth-theme.css') }}">
@endpush
