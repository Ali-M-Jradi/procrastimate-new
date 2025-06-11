@extends('layouts.app')

@section('content')
<div class="container fade-section">
    <section>
        <h2>Select a Task to Comment On</h2>
        @if($tasks->isEmpty())
            <div class="alert alert-warning">You have no tasks to comment on.</div>
        @else
            <form method="POST" action="{{ route('comment.create') }}">
                @csrf
                <div class="form-group">
                    <label for="task_id">Task</label>
                    <select name="task_id" id="task_id" class="form-control" required>
                        <option value="">-- Select Task --</option>
                        @foreach($tasks as $task)
                            <option value="{{ $task->id }}">{{ $task->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mt-3">
                    <label for="comment">Comment</label>
                    <textarea name="comment" id="comment" class="form-control" rows="3" required placeholder="Enter your comment..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Submit</button>
                <a href="{{ route('userDashboard') }}" class="btn btn-danger mt-2">Cancel</a>
            </form>
            
        @endif
    </section>
</div>
@endsection
