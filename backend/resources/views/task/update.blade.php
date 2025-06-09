@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
@endpush

@section('content')
<div class="container">
    <section>
        <h2>Update Task</h2>
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
                <input type="text" name="title" id="title" class="form-control" required value="{{ old('title', $task->title) }}" placeholder="Enter task title">
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" required placeholder="Enter task description" rows="4">{{ old('description', $task->description) }}</textarea>
            </div>
            <div class="form-group">
                <label for="dueDate">Due Date</label>
                <input type="date" name="dueDate" id="dueDate" class="form-control" required value="{{ old('dueDate', $task->dueDate) }}">
            </div>
            <div class="task-actions">
                <button type="submit" class="btn btn-primary">Update Task</button>
                <a href="{{ route('userDashboard') }}" class="btn btn-danger">Cancel</a>
            </div>
        </form>
    </section>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/coach-dashboard.js') }}"></script>
