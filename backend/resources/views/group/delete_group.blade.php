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
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                    <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
                </ul>
            </nav>
        </header>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
    @endif

    <h1>Delete Group</h1>
    <p>Are you sure you want to delete the group "{{ $group->name }}"?</p>
    @php
        $isAdminOrCoach = auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'coach');
    @endphp
    <form action="{{ route('groups.destroy', $group->id) }}" method="POST">
        @csrf
        @method('DELETE')
        @if($isAdminOrCoach)
            <button type="submit">Delete Group</button>
        @else
            <button type="button" class="btn btn-secondary" disabled>Delete Group (Not Allowed)</button>
        @endif
    </form>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth-theme.css') }}">
@endpush
