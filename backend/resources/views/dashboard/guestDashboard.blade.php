@extends('layouts.app')

@section('content')
    <h1>Welcome, Guest!</h1>
    <p>
        Please <a href="{{ route('login') }}">login</a> or 
        <a href="{{ route('register') }}">register</a> to access your dashboard.
    </p>
    <p>
        Or visit the <a href="{{ route('dashboard.guest') }}">Guest Dashboard</a>.
    </p>
@endsection