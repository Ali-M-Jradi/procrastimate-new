@extends('layouts.app')

@section('content')
@php
    $user = auth()->user();
    $title = '';
    if($user && $user->role === 'admin') {
        $title = 'Admin Dashboard';
    } elseif($user && $user->role === 'coach') {
        $title = 'Coach Dashboard';
    } elseif($user && $user->role === 'user') {
        $title = 'My Dashboard';
    }
@endphp
@include('partials.header', ['title' => $title])
<div class="container fade-section">
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                    <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
                </ul>
            </nav>
        </header>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
    @endif

    <section>
        <h2>Manage Groups</h2>
        <p class="section-description">Use the options below to manage groups.</p>
        
        @php
            $isAdminOrCoach = auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'coach');
        @endphp

        <div class="task-actions mt-4">
            @if($isAdminOrCoach)
                <a href="{{ route('group.create') }}" class="btn btn-primary">Create New Group</a>
            @endif
            <a href="{{ route('group.index') }}" class="btn btn-success">View All Groups</a>
        </div>

        @if(isset($groups) && $groups->count() > 0)
            <div class="group-list mt-4">
                @foreach($groups as $group)
                    <div class="task-item">
                        <h3>{{ $group->name }}</h3>
                        <p>{{ $group->description }}</p>
                        <div class="task-actions">
                            @if($isAdminOrCoach)
                                <a href="{{ route('group.edit_group', $group->id) }}" class="btn btn-primary">Edit</a>
                                <form action="{{ route('group.delete_group', $group->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this group?')">Delete</button>
                                </form>
                            @endif
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
