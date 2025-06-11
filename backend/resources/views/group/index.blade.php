@extends('layouts.app')

@section('content')
<div class="container">
    <h1>All Groups</h1>
    <a href="{{ route('groups.create') }}" class="btn btn-primary mb-3">Create New Group</a>
    @if($groups->isEmpty())
        <div class="empty-state">
            <p>No groups available.</p>
        </div>
    @else
        <div class="group-list">
            @foreach($groups as $group)
                <div class="group-item">
                    <h3>{{ $group->name }}</h3>
                    <p>{{ $group->description }}</p>
                    <a href="{{ route('groups.view', $group->id) }}" class="btn btn-primary">View</a>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
