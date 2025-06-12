@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add New Comment</h2>
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('admin.comment.create') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="user_id">User</label>
            <select name="user_id" id="user_id" class="form-control" required>
                <option value="">Select User</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role }})</option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group">
            <label for="task_id">Task</label>
            <select name="task_id" id="task_id" class="form-control" required>
                <option value="">Select Task</option>
                @foreach($tasks as $task)
                    <option value="{{ $task->id }}">{{ $task->title }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group">
            <label for="comment">Comment</label>
            <textarea name="comment" id="comment" rows="5" class="form-control" required></textarea>
        </div>
        
        <div class="form-group mt-3">
            <button type="submit" class="btn btn-primary">Add Comment</button>
            <a href="{{ route('admin.comment.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
