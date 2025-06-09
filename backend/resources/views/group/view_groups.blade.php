@extends('layouts.app')

@section('content')
<div class="container">
    <section>
        <h2>All Groups</h2>
        @if($groups->isEmpty())
            <div class="empty-state">
                <p>No groups available.</p>
            </div>
        @else
            <div class="group-list">
                @foreach($groups as $group)
                    <div class="task-item">
                        <h3>{{ $group->name }}</h3>
                        <p>{{ $group->description }}</p>
                        <div class="task-actions">
                            <a href="{{ route('groups.view', $group->id) }}" class="btn btn-primary">View Details</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth-theme.css') }}">
@endpush
