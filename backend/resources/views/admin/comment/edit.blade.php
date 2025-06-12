@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Comment</h2>
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('admin.comment.update', $comment->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="user_id">User</label>
            <select name="user_id" id="user_id" class="form-control" required>
                <option value="">Select User</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $comment->user_id == $user->id ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->role }})
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group">
            <label for="task_id">Task</label>
            <select name="task_id" id="task_id" class="form-control" required>
                <option value="">Select Task</option>
                @foreach($tasks as $task)
                    <option value="{{ $task->id }}" {{ $comment->task_id == $task->id ? 'selected' : '' }}>
                        {{ $task->title }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group">
            <label for="comment">Comment</label>
            <textarea name="comment" id="comment" rows="5" class="form-control" required>{{ $comment->comment }}</textarea>
        </div>
        
        <div class="form-group mt-3">
            <button type="submit" class="btn btn-primary">Update Comment</button>
            <a href="{{ route('admin.comment.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
