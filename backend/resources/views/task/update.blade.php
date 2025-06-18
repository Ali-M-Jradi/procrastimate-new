@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
@php
    $user = auth()->user();
    if (!isset($users)) {
        $users = \App\Models\User::all();
    }
@endphp
@if($user && $user->role === 'admin')
    @include('partials.header', ['title' => 'Admin Dashboard'])
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
@elseif($user && $user->role === 'coach')
    @include('partials.header', ['title' => 'Coach Dashboard'])
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
@elseif($user && $user->role === 'user')
    @include('partials.header', ['title' => 'My Dashboard'])
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
@endif
<div class="container fade-section">
    <section>
        <h2>Edit Task Details</h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('task.update', $task->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ $task->title }}" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" required>{{ $task->description }}</textarea>
            </div>
            <div class="form-group">
                <label for="dueDate">Due Date</label>
                <input type="date" name="dueDate" id="dueDate" class="form-control" value="{{ $task->dueDate }}" required>
            </div>
            
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="pending" @if($task->status === 'pending') selected @endif>Pending</option>
                    <option value="approved" @if($task->status === 'approved') selected @endif>Approved</option>
                    <option value="completed" @if($task->status === 'completed') selected @endif>Completed</option>
                    <option value="out_of_date" @if($task->status === 'out_of_date') selected @endif>Out of Date</option>
                </select>
            </div>
            
            @if($user->role === 'admin' || $user->role === 'coach')
            <div class="form-group">
                <label for="user_id">Assign to User</label>
                <select name="user_id" id="user_id" class="form-control" required>
                    <option value="">Select User</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" @if($task->user_id == $u->id) selected @endif>{{ $u->name }} ({{ $u->email }})</option>
                    @endforeach
                </select>
            </div>
            @else
            <!-- For regular users, we use a hidden field to maintain the current user as the task owner -->
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            @endif
            <div class="task-actions">
                <button type="submit" class="btn btn-primary">Update Task</button>
                @if($user->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Cancel</a>
                @elseif($user->role === 'coach')
                    <a href="{{ route('coach.dashboard') }}" class="btn btn-secondary">Cancel</a>
                @else
                    <a href="{{ route('userDashboard') }}" class="btn btn-secondary">Cancel</a>
                @endif
            </div>
        </form>
    </section>
</div>
@endsection
