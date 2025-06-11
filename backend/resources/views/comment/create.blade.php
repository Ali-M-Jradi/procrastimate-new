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
        <form action="{{ route('comment.create') }}" method="POST">
            @csrf
            <input type="hidden" name="task_id" value="{{ $task->id }}">
            <div class="form-group">
                <label for="comment">Comment</label>
                <textarea name="comment" id="comment" class="form-control" required></textarea>
            </div>
            <div class="task-actions">
                <button type="submit" class="btn btn-success">Add Comment</button>
                <a href="{{ route('comment.index') }}" class="btn btn-danger">Cancel</a>
            </div>
        </form>
    </section>
</div>
@endsection
