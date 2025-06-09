@extends('layouts.app')
@section('content')
    <h1>Comments</h1>
    <ul>
        @foreach($comments as $comment)
            <li>
                <strong>{{ $comment->user->name }}:</strong> {{ $comment->content }}
                <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
            </li>
        @endforeach
    </ul>
    <a href="{{ route('comments.create') }}">Add Comment</a>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/coach-dashboard.js') }}"></script>
