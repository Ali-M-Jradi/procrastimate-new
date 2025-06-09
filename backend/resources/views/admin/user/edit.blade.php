@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit User</h1>

    <form action="{{ route('admin.user.update', $user->id) }}" method="POST" class="mt-4">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                value="{{ old('name', $user->name) }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mt-3">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                value="{{ old('email', $user->email) }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mt-3">
            <label for="role">Role</label>
            <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" required>
                <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                <option value="coach" {{ $user->role === 'coach' ? 'selected' : '' }}>Coach</option>
            </select>
            @error('role')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Update User</button>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
