@extends('layouts.app')
@section('content')
    <h1>Update Task</h1>
    <p>Modify the details of your task below.</p>
    <form action="{{ route('tasks.update', $task->id) }}" method="POST">
        @csrf
        @method('PUT')
        <label for="task_name">Task Name:</label>
        <input type="text" id="task_name" name="task_name" value="{{ $task->name }}" required>
        <br><br>
        <button type="submit">Update Task</button>
    </form>
@endsection
