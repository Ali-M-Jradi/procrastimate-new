@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
@endpush

@section('content')
<div class="container">
    <section class="auth-section">
        <h2>Login</h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <div class="task-actions">
                <button type="submit" class="btn btn-primary">Login</button>
                <a href="{{ route('register') }}" class="btn btn-success">Register</a>
            </div>
        </form>
    </section>
</div>
@endsection