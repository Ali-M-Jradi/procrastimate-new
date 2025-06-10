@extends('layouts.app')
@section('content')
<div class="container">
    <h2>All Notifications</h2>
    <a href="{{ route('admin.notification.createForm') }}" class="btn btn-primary mb-3">Add Notification</a>
    @if($notifications->count())
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>To User</th>
                    <th>Message</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($notifications as $notification)
                <tr>
                    <td>{{ $notification->id }}</td>
                    <td>{{ $notification->toUser->name ?? '-' }}</td>
                    <td>{{ $notification->message }}</td>
                    <td>{{ $notification->created_at->diffForHumans() }}</td>
                    <td>
                        <a href="{{ route('admin.notification.editForm', $notification->id) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('admin.notification.delete', $notification->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this notification?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No notifications found.</p>
    @endif
</div>
@endsection
