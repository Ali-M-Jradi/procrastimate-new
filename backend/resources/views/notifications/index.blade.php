@extends('layouts.app')

@section('content')
<div class="container">
    <header>
        <h1>Notifications</h1>
        <p>Stay updated with your latest notifications.</p>
    </header>

    <section>
        <h2>Your Notifications</h2>
        @if($notifications->isEmpty())
            <div class="empty-state">
                <p>No notifications at this time.</p>
            </div>
        @else
            <div class="notification-list">
                @foreach($notifications as $notification)
                    <div class="notification-item">
                        <h3>{{ $notification->title }}</h3>
                        <p>{{ $notification->message }}</p>
                        <small>{{ $notification->created_at->diffForHumans() }}</small>
                    </div>
                @endforeach
            </div>
        @endif
        
        <div class="task-actions mt-4">
            <a href="{{ route('notification.createForm') }}" class="btn btn-primary">Create New Notification</a>
        </div>
    </section>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth-theme.css') }}">
@endpush
