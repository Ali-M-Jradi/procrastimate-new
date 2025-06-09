@extends('layouts.app')

@section('content')
<div class="container">
    <section>
        <h2>Manage Groups</h2>
        <p class="section-description">Use the options below to manage groups.</p>
        
        <div class="task-actions mt-4">
            <a href="{{ route('groups.create') }}" class="btn btn-primary">Create New Group</a>
            <a href="{{ route('groups.index') }}" class="btn btn-success">View All Groups</a>
        </div>

        @if(isset($groups) && $groups->count() > 0)
            <div class="group-list mt-4">
                @foreach($groups as $group)
                    <div class="task-item">
                        <h3>{{ $group->name }}</h3>
                        <p>{{ $group->description }}</p>
                        <div class="task-actions">
                            <a href="{{ route('groups.edit', $group->id) }}" class="btn btn-primary">Edit</a>
                            <form action="{{ route('groups.destroy', $group->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this group?')">Delete</button>
                            </form>
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
