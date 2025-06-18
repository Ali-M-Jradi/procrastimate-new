@extends('layouts.app')

@section('content')
<div class="container fade-section">    @php
        $user = auth()->user();
        $title = '';
        if($user && $user->role === 'admin') {
            $title = 'Admin Dashboard';
        } elseif($user && $user->role === 'coach') {
            $title = 'Coach Dashboard';
        } elseif($user && $user->role === 'user') {
            $title = 'My Dashboard';
        }
    @endphp
    @include('partials.header', ['title' => $title])
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>

    <section>
        <h2>Task Details</h2>
        <h3>{{ $task->title }}</h3>
        <p>{{ $task->description }}</p>
        <p>Due: {{ $task->dueDate }}</p>
        <p>Status: <span class="badge badge-{{ $task->status }}">{{ ucfirst($task->status) }}</span></p>
        <a href="{{ route('task.updateForm', $task->id) }}" class="btn btn-primary">Edit</a>
        <form action="{{ route('task.delete', $task->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
        <br><br>
        @if($user && $user->role === 'admin')
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary mt-2">Return to Dashboard</a>
        @elseif($user && $user->role === 'coach')
            <a href="{{ route('coach.dashboard') }}" class="btn btn-secondary mt-2">Return to Dashboard</a>
        @else
            <a href="{{ route('userDashboard') }}" class="btn btn-secondary mt-2">Return to Dashboard</a>
        @endif
    </section>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/coach-dashboard.js') }}"></script>