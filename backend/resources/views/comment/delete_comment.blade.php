@extends('layouts.app')
@section('content')
    <h1>Delete Comment</h1>
    <form method="POST" action="{{ route('comments.destroy', $comment->id) }}">
        @csrf
        @method('DELETE')
        <p>Are you sure you want to delete this comment?</p>
        <button type="submit">Delete Comment</button>
    </form>
@endsection
