@extends('layouts.app')

@section('content')
<div class="container">
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
    <form action="{{ route('admin.notification.create') }}" method="POST">
        @csrf
        <div class="form-group mb-3">
            <label for="to_user_id">To User</label>
            <select name="to_user_id" id="to_user_id" class="form-control" required>
                <option value="">Select User</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group mb-3">
            <label for="message">Message</label>
            <textarea name="message" id="message" class="form-control" required maxlength="1000">{{ old('message') }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Create Notification</button>
        <a href="{{ route('admin.notification.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
