@extends('layouts.app')
@section('content')
    <h1>Create a New Comment</h1>
    <p>Fill out the form below to create a new comment.</p>
    <form action="{{ route('comments.store') }}" method="POST">
        @csrf
        <label for="task_id">Task ID:</label>
        <input type="text" id="task_id" name="task_id" required>
        <br><br>
        <label for="content">Comment:</label>
        <textarea id="content" name="content" required></textarea>
        <br><br>
        <button type="submit">Create Comment</button>
    </form>
@endsection
