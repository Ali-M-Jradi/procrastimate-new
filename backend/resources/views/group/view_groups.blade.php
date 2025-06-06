@extends('layouts.app')
@section('content')
    <h1>View All Groups</h1>
    <p>Below is a list of all groups.</p>
    <ul>
        @foreach($groups as $group)
            <li><strong>{{ $group->name }}:</strong> {{ $group->description }}</li>
        @endforeach
    </ul>
@endsection
