@extends('layouts.app')

@section('content')
@php
    $title = 'Join Groups';
@endphp
@include('partials.header', ['title' => $title])
<div class="container fade-section">
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
            <style>
                .card {
                    height: 250px;
                    display: flex;
                    flex-direction: column;
                }
                .card-body {
                    flex: 1;
                    display: flex;
                    flex-direction: column;
                }
                .card-text {
                    max-height: 100px;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    display: -webkit-box;
                    -webkit-line-clamp: 4;
                    -webkit-box-orient: vertical;
                    flex-grow: 1;
                }
                .card-actions {
                    margin-top: auto;
                }
            </style>
            <div class="row">
                @foreach($groups as $group)
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">{{ $group->name }}</h5>
                                <p class="card-text">{{ \Illuminate\Support\Str::limit($group->description, 150) }}</p>
                                <div class="card-actions">
                                    <form action="{{ route('groups.join') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="group_id" value="{{ $group->id }}">
                                        <button type="submit" class="btn btn-primary">Join This Group</button>
                                    </form>
                                </div>
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
