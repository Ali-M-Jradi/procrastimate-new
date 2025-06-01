@extends('layouts.app')

@section('content')
    <h1>User Dashboard</h1>
    <p>Welcome, {{ $user->name }}!</p>
    <ul>
        <li><a href="{{ route('dashboard.user') }}">Refresh User Dashboard</a></li>
        <li><a href="{{ route('dashboard') }}">Go to Main Dashboard</a></li>
        <li><a href="{{ route('logout') }}" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Logout
        </a></li>
    </ul>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
@endsection