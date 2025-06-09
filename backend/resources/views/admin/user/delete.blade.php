@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Delete User</h1>

    <div class="alert alert-danger mt-4">
        <h4>Are you sure you want to delete this user?</h4>
        <p>This action cannot be undone. All associated data will also be deleted.</p>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <h5>User Details</h5>
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
        </div>
    </div>

    <form action="{{ route('admin.user.delete', $user->id) }}" method="POST" class="mt-4">
        @csrf
        
        <button type="submit" class="btn btn-danger">Confirm Delete</button>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
