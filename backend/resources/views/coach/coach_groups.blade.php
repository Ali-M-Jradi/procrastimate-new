@extends('layouts.app')
@section('content')
    <h1>Coach Groups</h1>
    <section id="groups">
        <h2>Your Groups</h2>
        <ul>
            @foreach($groups as $group)
                <li>
                    <strong>{{ $group->name }}</strong> - {{ $group->description }}
                    <span>Members: {{ $group->member_count }}</span>
                </li>
            @endforeach
        </ul>
    </section>
    <section id="create-group">
        <h2>Create New Group</h2>
        <form method="POST" action="{{ route('groups.store') }}">
            @csrf
            <label for="group_name">Group Name:</label>
            <input type="text" id="group_name" name="group_name" required>
            <br><br>
            <button type="submit">Create Group</button>
        </form>
    </section>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/coach-dashboard.js') }}"></script>
