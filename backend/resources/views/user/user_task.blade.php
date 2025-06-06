@extends('layouts.app')
@section('content')
    <h1>User Task View</h1>
    <p>Welcome to the user task view page!</p>
    <p>Here you can manage your tasks and view details.</p>
    <h2>Tasks</h2>
    <ul>
        @foreach($tasks as $task)
            <li>{{ $task->name }}: {{ $task->description }}</li>
        @endforeach
    </ul>
@endsection
