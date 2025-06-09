@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
@endpush

@section('content')
<div class="container">
    <section>
        <h2>Join Groups</h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($groups->isEmpty())
            <div class="empty-state">
                <p>No groups available to join at the moment.</p>
                <a href="{{ route('userDashboard') }}" class="btn btn-primary">Back to Dashboard</a>
            </div>
        @else
            <form action="{{ route('group.join') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="group_id">Select Group:</label>
                    <select id="group_id" name="group_id" class="form-control" required>
                        <option value="">Choose a group...</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="task-actions">
                    <button type="submit" class="btn btn-primary">Join Group</button>
                    <a href="{{ route('userDashboard') }}" class="btn btn-danger">Cancel</a>
                </div>
            </form>
        @endif
    </section>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/coach-dashboard.js') }}"></script>
