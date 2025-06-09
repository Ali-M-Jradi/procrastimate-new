@extends('layouts.app')

@section('content')
<div class="container">
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
