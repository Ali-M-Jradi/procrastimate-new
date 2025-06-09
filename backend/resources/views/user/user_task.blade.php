@extends('layouts.app')

@section('content')
<div class="container">
    <header>
        <h1>Your Tasks</h1>
        <p>Manage and track your tasks effectively.</p>
    </header>

    <section>
        <h2>Current Tasks</h2>
        @if($tasks->isEmpty())
            <div class="empty-state">
                <p>No tasks available.</p>
                <a href="{{ route('task.create') }}" class="btn btn-primary">Create Your First Task</a>
            </div>
        @else
            <div class="task-list">
                @foreach($tasks as $task)
                    <div class="task-item">
                        <h3>{{ $task->title }}</h3>
                        <p>{{ $task->description }}</p>
                        <div class="task-actions">
                            <a href="{{ route('task.update', $task->id) }}" class="btn btn-primary">Edit</a>
                            <form action="{{ route('task.delete', $task->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth-theme.css') }}">
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/coach-dashboard.js') }}"></script>
@endpush
