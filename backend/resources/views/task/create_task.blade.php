@extends('layouts.app')
@section('content')
    <h1>Create a New Task</h1>
    <p>Fill out the form below to create a new task.</p>
    <form action="{{ route('tasks.store') }}" method="POST">
        @csrf
        <label for="task_name">Task Name:</label>
        <input type="text" id="task_name" name="task_name" required>
        <br><br>
        <button type="submit">Create Task</button>
    </form>
@endsection
