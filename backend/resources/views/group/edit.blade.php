@extends('layouts.app')

@section('content')
@php
    $user = auth()->user();
    $title = 'Edit Group';
    if($user && $user->role === 'admin') {
        $title = 'Edit Group - Admin';
    } elseif($user && $user->role === 'coach') {
        $title = 'Edit Group - Coach';
    }
@endphp
@include('partials.header', ['title' => $title])
<div class="container fade-section">

    <section>
        <h2>Edit Group</h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @php
            $isAdminOrCoach = auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'coach');
        @endphp        
        <form action="{{ route('groups.update', $group->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Group Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $group->name }}" required @if(!$isAdminOrCoach) disabled @endif>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" required @if(!$isAdminOrCoach) disabled @endif>{{ $group->description }}</textarea>
            </div>
            <div class="task-actions">
                @if($isAdminOrCoach)
                    <button type="submit" class="btn btn-primary">Update Group</button>
                @endif
                @if($user && $user->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Cancel</a>
                @elseif($user && $user->role === 'coach')
                    <a href="{{ route('coach.dashboard') }}" class="btn btn-secondary">Cancel</a>
                @endif
            </div>
        </form>
    </section>
</div>
@endsection
