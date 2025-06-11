@extends('layouts.app')

@section('content')
<div class="container">
    <section>
        <h2>Create Notification</h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('notification.send') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" class="form-control" required value="{{ old('title') }}">
            </div>
            <div class="form-group">
                <label for="message">Message</label>
                <textarea name="message" id="message" class="form-control" required rows="4">{{ old('message') }}</textarea>
            </div>
            <div class="form-group">
                <label for="to_user_id">Recipient</label>
                <select name="to_user_id" id="to_user_id" class="form-control" required>
                    <option value="">Select User</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" @if(old('to_user_id') == $user->id) selected @endif>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="task-actions">
                <button type="submit" class="btn btn-primary">Send Notification</button>
                <a href="{{ route('notifications.view') }}" class="btn btn-danger">Cancel</a>
            </div>
        </form>
    </section>
</div>
@endsection
