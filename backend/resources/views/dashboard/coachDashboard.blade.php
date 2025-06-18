@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
@include('partials.header', ['title' => 'Welcome, Coach {{ $user->name }}'])
<div class="container">
    <main>
        <section id="groups">
            <h2>Groups</h2>
            @if(isset($groups) && $groups->count() > 0)
                <div class="text-end mt-4">
                    <a href="{{ route('groups.create') }}" class="btn btn-primary">Create New Group</a>
                </div>
                <br>
                <div class="group-list">
                    @foreach($groups as $group)
                        <div class="task-item">
                            <h3>{{ $group->name }}</h3>
                            <p>{{ $group->description }}</p>
                            <div class="task-actions">
                                <a href="{{ route('groups.edit', $group->id) }}" class="btn btn-primary">Edit</a>
                                <form action="{{ route('groups.destroy', $group->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this group?')">Delete</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state text-center mt-4">
                    <p>You haven't created any groups yet.</p>
                    <a href="{{ route('groups.create') }}" class="btn btn-primary">Create Your First Group</a>
                </div>
            @endif
        </section>

        <section id="notifications">
            <h2>Notifications</h2>
            @if(isset($notifications) && $notifications->count() > 0)
                <div class="notification-list">
                    @foreach($notifications as $notification)
                        <div class="notification-item">
                            <p>{{ $notification->message }}</p>
                            <small>{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <p>No notifications at this time.</p>
                </div>
            @endif
        </section>

        <section id="comments">
            <h2>Comments</h2>
            
            @if(isset($comments) && $comments->where('user_id', $user->id)->count() > 0)
                <div class="comment-list">
                    @foreach($comments as $comment)
                        @if($comment->user_id === $user->id)
                        <div class="comment-item">
                            <p>{{ $comment->comment }}</p>
                            <small>On Task: {{ $comment->task->title ?? 'Unknown Task' }}</small><br>
                            <small>By {{ $comment->user->name }} - {{ $comment->created_at->diffForHumans() }}</small>
                            <form action="{{ route('comment.delete', $comment->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this comment?')">Delete</button>
                            </form>
                        </div>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="empty-state text-center mt-4">
                    <p>No comments yet.</p>
                    <a href="{{ route('comment.create') }}" class="btn btn-primary">Create New Comment</a>
                </div>
            @endif
        </section>

        <section id="tasks">
            <h2>Tasks</h2>
            <div class="mb-4">
                <a href="{{ route('coach.task.create') }}" class="btn btn-primary">Create New Task</a>
            </div>
            <!-- 1. User Tasks Needing Approval or Rejection -->
            <div class="mb-5">
                <h4>User Tasks Needing Approval or Rejection</h4>
                @if(isset($userTasksToApprove) && $userTasksToApprove->count() > 0)
                    <div class="task-list">
                        @foreach($userTasksToApprove as $task)
                            <div class="task-item">
                                <h5>{{ $task->title }}</h5>
                                <p>{{ $task->description }}</p>
                                <p>Created by: {{ $task->user ? $task->user->name : 'Unknown' }}</p>
                                <p class="task-date">Due: {{ $task->dueDate ? date('F j, Y', strtotime($task->dueDate)) : 'N/A' }}</p>
                                <p>Status: <span class="badge badge-{{ $task->status === 'rejected' ? 'danger' : ($task->status === 'pending' ? 'warning' : 'success') }}">{{ ucfirst($task->status ?? 'pending') }}</span></p>
                                <div class="task-actions">
                                    <form action="{{ route('task.approve', $task->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success">Approve</button>
                                    </form>
                                    <form action="{{ route('task.reject', $task->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-warning">Reject</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state text-center mt-4">
                        <p>No user tasks need your approval at the moment.</p>
                    </div>
                @endif
            </div>
            <!-- 2. Tasks Created by You -->
            <div class="mb-5">
                <h4>Tasks Created by You</h4>
                @if(isset($coachCreatedTasks) && $coachCreatedTasks->count() > 0)
                    <div class="task-list">
                        @foreach($coachCreatedTasks as $task)
                            <div class="task-item">
                                <h5>{{ $task->title }}</h5>
                                <p>{{ $task->description }}</p>
                                <p>Assigned to: {{ $task->user ? $task->user->name : 'Unassigned' }}</p>
                                <p class="task-date">Due: {{ $task->dueDate ? date('F j, Y', strtotime($task->dueDate)) : 'N/A' }}</p>
                                <p>Status: <span class="badge badge-{{ $task->status === 'rejected' ? 'danger' : ($task->status === 'pending' ? 'warning' : 'success') }}">{{ ucfirst($task->status) }}</span></p>
                                <div class="task-actions">
                                    <a href="{{ route('coach.task.updateForm', $task->id) }}" class="btn btn-primary">Edit</a>
                                    <form action="{{ route('coach.task.delete', $task->id) }}" method="POST" class="delete-form" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state text-center mt-4">
                        <p>You haven't created any tasks yet.</p>
                        <a href="{{ route('coach.task.create') }}" class="btn btn-primary">Create Your First Task</a>
                    </div>
                @endif
            </div>
            <!-- 3. Tasks Assigned to You by an Admin -->
            <div>
                <h4>Tasks Assigned to You by an Admin</h4>
                @if(isset($adminAssignedTasks) && $adminAssignedTasks->count() > 0)
                    <div class="task-list">
                        @foreach($adminAssignedTasks as $task)
                            <div class="task-item">
                                <h5>{{ $task->title }}</h5>
                                <p>{{ $task->description }}</p>
                                <p>Assigned by: {{ $task->admin ? $task->admin->name : 'Unknown Admin' }}</p>
                                <p class="task-date">Due: {{ $task->dueDate ? date('F j, Y', strtotime($task->dueDate)) : 'N/A' }}</p>
                                <p>Status: <span class="badge badge-{{ $task->status === 'rejected' ? 'danger' : ($task->status === 'pending' ? 'warning' : 'success') }}">{{ ucfirst($task->status) }}</span></p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state text-center mt-4">
                        <p>No tasks have been assigned to you by an admin.</p>
                    </div>
                @endif
            </div>
        </section>
    </main>
</div>
@endsection