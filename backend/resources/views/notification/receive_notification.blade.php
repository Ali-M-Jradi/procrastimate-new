@extends('layouts.app')
@section('content')
    <h1>Received Notifications</h1>
    @if(Auth::user()->role === 'admin')
        <h2>Admin Notifications</h2>
    @elseif(Auth::user()->role === 'coach')
        <h2>Coach Notifications</h2>
    @else
        <h2>User Notifications</h2>
    @endif
    <ul>
        @foreach($notifications as $notification)
            <li>{{ $notification->message }}</li>
        @endforeach
    </ul>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth-theme.css') }}">
@endpush
