@extends('layouts.app')
@section('content')
    <h1>Coach Notifications</h1>
    <section id="notifications">
        <h2>Your Notifications</h2>
        <ul>
            @foreach($notifications as $notification)
                <li>
                    <strong>{{ $notification->sender }}:</strong> {{ $notification->message }}
                    <span>at {{ $notification->created_at }}</span>
                </li>
            @endforeach
        </ul>
    </section>
    <a href="{{ route('coach.dashboard') }}">Back to Dashboard</a>
@endsection
