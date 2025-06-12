@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
@endpush

@section('content')
<div class="container">
    <section>
        <h2>Join Groups</h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($groups->isEmpty())
            <div class="empty-state">
                <p>No groups available to join at the moment.</p>
                <a href="{{ route('userDashboard') }}" class="btn btn-primary">Back to Dashboard</a>
            </div>
        @else
            <div class="row">
                @foreach($groups as $group)
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">{{ $group->name }}</h5>
                                <p class="card-text">{{ $group->description }}</p>
                                <form action="{{ route('groups.join') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="group_id" value="{{ $group->id }}">
                                    <button type="submit" class="btn btn-primary">Join This Group</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-3">
                <a href="{{ route('userDashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
            </div>
        @endif
    </section>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/coach-dashboard.js') }}"></script>
