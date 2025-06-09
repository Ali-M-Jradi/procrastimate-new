@extends('layouts.app')
@section('content')
    <h1>Send Notification</h1>
    <p>Use the form below to send a notification.</p>
    <form action="{{ route('notifications.send') }}" method="POST">
        @csrf
        <label for="recipient">Recipient:</label>
        <input type="text" id="recipient" name="recipient" required>
        <br><br>
        <label for="message">Message:</label>
        <textarea id="message" name="message" required></textarea>
        <br><br>
        <button type="submit">Send Notification</button>
    </form>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth-theme.css') }}">
@endpush
