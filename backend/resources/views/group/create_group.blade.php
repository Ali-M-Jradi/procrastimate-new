@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
@endpush

@section('content')
<div class="container">
    <section>
        <h2>Create New Group</h2>
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

        <form action="{{ route('groups.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Group Name</label>
                <input type="text" name="name" id="name" class="form-control" required value="{{ old('name') }}" placeholder="Enter group name">
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" placeholder="Enter group description" rows="4">{{ old('description') }}</textarea>
            </div>

            <div class="task-actions">
                <button type="submit" class="btn btn-primary">Create Group</button>
                <a href="{{ route('coach.dashboard') }}" class="btn btn-danger">Cancel</a>
            </div>
        </form>
    </section>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/coach-dashboard.js') }}"></script>
