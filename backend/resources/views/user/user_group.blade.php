@extends('layouts.app')

@section('content')
<div class="container">
    <header>
        <h1>Your Groups</h1>
        <p>View and manage your group memberships.</p>
    </header>

    <section>
        <h2>Your Groups</h2>
        @if($groups->isEmpty())
            <div class="empty-state">
                <p>You're not a member of any groups yet.</p>
                <a href="{{ route('groups.joinForm') }}" class="btn btn-primary">Join a Group</a>
            </div>
        @else
            <div class="group-list">
                @foreach($groups as $group)
                    <div class="task-item">
                        <h3>{{ $group->name }}</h3>
                        <p>{{ $group->description }}</p>
                        <div class="task-actions">
                            <form action="{{ route('groups.leave') }}" method="POST" style="display: inline;">
                                @csrf
                                <input type="hidden" name="group_id" value="{{ $group->id }}">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to leave this group?')">Leave Group</button>
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

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/coach-dashboard.js') }}"></script>
@endpush
