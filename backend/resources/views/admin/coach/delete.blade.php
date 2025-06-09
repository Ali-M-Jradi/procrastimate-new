@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Delete Coach</h1>

    <div class="alert alert-danger mt-4">
        <h4>Are you sure you want to delete this coach?</h4>
        <p>This action cannot be undone. All associated data will also be deleted.</p>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <h5>Coach Details</h5>
            <p><strong>Name:</strong> {{ $coach->name }}</p>
            <p><strong>Email:</strong> {{ $coach->email }}</p>
            
            @if($coach->groups->count() > 0)
                <div class="alert alert-warning">
                    <p><strong>Warning:</strong> This coach is managing {{ $coach->groups->count() }} group(s).</p>
                </div>
            @endif
        </div>
    </div>

    <form action="{{ route('admin.coach.delete', $coach->id) }}" method="POST" class="mt-4">
        @csrf
        
        <button type="submit" class="btn btn-danger">Confirm Delete</button>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
