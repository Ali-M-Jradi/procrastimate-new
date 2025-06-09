@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Demote Coach to User</h1>

    <div class="alert alert-warning mt-4">
        <h4>Demote {{ $coach->name }} to regular user?</h4>
        <p>This will remove all coach privileges and access to coach features.</p>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <h5>Coach Details</h5>
            <p><strong>Name:</strong> {{ $coach->name }}</p>
            <p><strong>Email:</strong> {{ $coach->email }}</p>
            
            @if($coach->groups->count() > 0)
                <div class="alert alert-warning">
                    <h5>Current Responsibilities:</h5>
                    <p>This coach is currently managing {{ $coach->groups->count() }} group(s).</p>
                    <p>After demotion, these groups will need to be reassigned to another coach.</p>
                </div>
            @endif
        </div>
    </div>

    <form action="{{ route('admin.coach.demote', $coach->id) }}" method="POST" class="mt-4">
        @csrf
        
        <button type="submit" class="btn btn-warning">Confirm Demotion</button>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
