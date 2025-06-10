@extends('layouts.app')
@section('content')
<div class="container">
    <h2>All Comments</h2>
    <a href="{{ route('admin.comment.createForm') }}" class="btn btn-primary mb-3">Add Comment</a>
    @if($comments->count())
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Task</th>
                    <th>Comment</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($comments as $comment)
                <tr>
                    <td>{{ $comment->id }}</td>
                    <td>{{ $comment->user->name ?? '-' }}</td>
                    <td>{{ $comment->task->title ?? '-' }}</td>
                    <td>{{ $comment->comment }}</td>
                    <td>
                        <a href="{{ route('admin.comment.editForm', $comment->id) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('admin.comment.delete', $comment->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this comment?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No comments found.</p>
    @endif
</div>
@endsection
