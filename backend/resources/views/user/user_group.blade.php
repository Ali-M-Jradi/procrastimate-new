@extends('layouts.app')
@section('content')
    <h1>User Group View</h1>
    <p>Welcome to the user group view page!</p>
    <p>Here you can manage your user groups and view details.</p>
    <h2>User Groups</h2>
    <ul>
        @foreach($groups as $group)
            <li>{{ $group->name }}: {{ $group->description }}</li>
        @endforeach
    </ul>
    <h2>Group Details</h2>
    <p>Details about each group will be displayed here.</p>
@endsection
