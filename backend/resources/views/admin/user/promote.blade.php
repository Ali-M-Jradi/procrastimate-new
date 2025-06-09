@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Promote User to Coach</h1>

    <div class="alert alert-info mt-4">
        <h4>Promote {{ $user->name }} to Coach?</h4>
        <p>This will give the user coach privileges and access to coach features.</p>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <h5>User Details</h5>
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Current Role:</strong> {{ ucfirst($user->role) }}</p>
        </div>
    </div>

    <form action="{{ route('admin.user.promote', $user->id) }}" method="POST" class="mt-4">
        @csrf
        
        <button type="submit" class="btn btn-success">Confirm Promotion</button>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
