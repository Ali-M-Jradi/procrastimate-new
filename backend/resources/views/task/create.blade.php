@extends('layouts.app')

@section('content')
@php
    $user = auth()->user();
    $title = '';
    if($user && $user->role === 'admin') {
        $title = 'Create Task - Admin';
    } elseif($user && $user->role === 'coach') {
        $title = 'Create Task - Coach';
    } elseif($user && $user->role === 'user') {
        $title = 'Create Task';
    }
@endphp
@include('partials.header', ['title' => $title])
<div class="container fade-section">
    <section>
        <h2>Task Details</h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('task.create') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" class="form-control" required value="{{ old('title') }}" placeholder="Enter task title">
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" required placeholder="Enter task description" rows="4">{{ old('description') }}</textarea>
            </div>
            <div class="form-group">
                <label for="dueDate">Due Date</label>
                <input type="date" name="dueDate" id="dueDate" class="form-control" required value="{{ old('dueDate') }}">
            </div>
            @if(isset($users) || isset($coaches))
                <div class="form-group">
                    <label for="user_id">Assign to User</label>
                    <select name="user_id" id="user_id" class="form-control" required>
                        <option value="">Select User</option>
                        @if(isset($users))
                            @foreach($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} (User)</option>
                            @endforeach
                        @endif
                        @if(isset($coaches))
                            @foreach($coaches as $c)
                                <option value="{{ $c->id }}">{{ $c->name }} (Coach)</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            @elseif($user && $user->role === 'user')
                <input type="hidden" name="user_id" value="{{ $user->id }}">
            @endif
            <div class="task-actions">
                <button type="submit" class="btn btn-success">Create Task</button>
                @if($user && $user->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-danger">Cancel</a>
                @elseif($user && $user->role === 'coach')
                    <a href="{{ route('coach.dashboard') }}" class="btn btn-danger">Cancel</a>
                @else
                    <a href="{{ route('userDashboard') }}" class="btn btn-danger">Cancel</a>
                @endif
            </div>
        </form>
    </section>
</div>
@endsection