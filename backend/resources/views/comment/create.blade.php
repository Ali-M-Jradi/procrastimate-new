@extends('layouts.app')

@section('content')
<div class="container fade-section">
    <section>
        <h2>Add Comment</h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('comment.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="task_id">Select Task</label>
                <select name="task_id" id="task_id" class="form-control" required>
                    <option value="">Choose a task</option>
                    @foreach($tasks as $task)
                        <option value="{{ $task->id }}">{{ $task->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="comment">Comment</label>
                <textarea name="comment" id="comment" class="form-control" required></textarea>
            </div>
            <div class="task-actions">
                <button type="submit" class="btn btn-success">Add Comment</button>
                <a href="{{ url()->previous() }}" class="btn btn-danger">Cancel</a>
            </div>
        </form>
    </section>
</div>
@endsection
