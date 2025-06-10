@extends('layouts.app')

@section('content')
<div class="container">
    <header>
        <h1>Admin Dashboard</h1>
        <p>Manage users, tasks, and system settings.</p>
        <nav>
            <ul>
                <li><a href="#user-management">Users</a></li>
                <li><a href="#group-management">Groups</a></li>
                <li><a href="#task-management">Tasks</a></li>
                <li><a href="#coach-management">Coaches</a></li>
                <li><a href="#comment-management">Comments</a></li>
                <li><a href="#notification-management">Notifications</a></li>
            </ul>
        </nav>
    </header>

    <section id="user-management">
        <h2>User Management</h2>
        <div class="task-actions">
            <a href="{{ route('admin.user.list') }}" class="btn btn-primary">View Users</a>
            <a href="{{ route('admin.user.create') }}" class="btn btn-success">Add User</a>
        </div>
        @if(isset($users) && $users->count() > 0)
            <div class="task-list mt-4">
                @foreach($users as $user)
                    <div class="task-item">
                        <h3>{{ $user->name }}</h3>
                        <p>{{ $user->email }}</p>
                        <p>Role: <span class="badge badge-primary">{{ ucfirst($user->role) }}</span></p>
                        <div class="task-actions">
                            <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-primary">Edit</a>
                            @if($user->role === 'user')
                                <a href="{{ route('admin.user.promote', $user->id) }}" class="btn btn-success">Promote to Coach</a>
                            @elseif($user->role === 'coach')
                                <a href="{{ route('admin.coach.demote', $user->id) }}" class="btn btn-warning">Demote to User</a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    <section id="group-management">
        <h2>Group Management</h2>
        <div class="task-list">
            @if(isset($groups) && $groups->count() > 0)
                @foreach($groups as $group)
                    <div class="task-item">
                        <h3>{{ $group->name }}</h3>
                        <p>{{ $group->description }}</p>
                        <p>Members: {{ $group->users()->count() }}</p>
                        <div class="task-actions">
                            <a href="{{ route('groups.edit', $group->id) }}" class="btn btn-primary">Edit Group</a>
                            <form action="{{ route('groups.destroy', $group->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete Group</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <p>No groups created yet.</p>
                </div>
            @endif
        </div>
    </section>

    <section id="task-management">
        <h2>Task Management</h2>
        @if(isset($tasks) && $tasks->count() > 0)
            <div class="task-list">
                @foreach($tasks as $task)
                    <div class="task-item">
                        <h3>{{ $task->title }}</h3>
                        <p>{{ $task->description }}</p>
                        <p>Due: {{ date('F j, Y', strtotime($task->dueDate)) }}</p>
                        <p>Assigned to: {{ $task->user->name }}</p>
                        <div class="task-actions">
                            <a href="{{ route('task.update', $task->id) }}" class="btn btn-primary">Edit</a>
                            <form action="{{ route('task.delete', $task->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                            @if($task->isCompleted)
                                <span class="badge badge-success">Completed</span>
                            @else
                                <span class="badge badge-warning">Pending</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <p>No tasks available.</p>
            </div>
        @endif
    </section>

    <section id="comment-management">
        <h2>Comments Management</h2>
        <!-- Inline Add Comment Form -->
        <form action="{{ route('admin.comment.create') }}" method="POST" class="mb-3 d-flex flex-wrap align-items-end gap-2">
            @csrf
            <select name="user_id" class="form-control" required>
                <option value="">User</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
            <select name="task_id" class="form-control" required>
                <option value="">Task</option>
                @foreach($tasks as $task)
                    <option value="{{ $task->id }}">{{ $task->title }}</option>
                @endforeach
            </select>
            <input type="text" name="comment" class="form-control" placeholder="Comment" required maxlength="255">
            <button type="submit" class="btn btn-success">Add</button>
        </form>
        @if(isset($comments) && $comments->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Task</th>
                        <th>Comment</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($comments->take(5) as $comment)
                    <tr>
                        <td>{{ $comment->id }}</td>
                        <td>{{ $comment->user->name ?? '-' }}</td>
                        <td>{{ $comment->task->title ?? '-' }}</td>
                        <td>{{ $comment->comment }}</td>
                        <td>
                            <!-- Edit Button triggers modal -->
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editCommentModal{{ $comment->id }}">Edit</button>
                            <form action="{{ route('admin.comment.delete', $comment->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this comment?')">Delete</button>
                            </form>
                            <!-- Edit Modal -->
                            <div class="modal fade" id="editCommentModal{{ $comment->id }}" tabindex="-1" aria-labelledby="editCommentModalLabel{{ $comment->id }}" aria-hidden="true">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <form action="{{ route('admin.comment.update', $comment->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                      <h5 class="modal-title" id="editCommentModalLabel{{ $comment->id }}">Edit Comment</h5>
                                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                      <div class="mb-2">
                                        <label>User</label>
                                        <select name="user_id" class="form-control" required>
                                          @foreach($users as $user)
                                            <option value="{{ $user->id }}" @if($user->id == $comment->user_id) selected @endif>{{ $user->name }}</option>
                                          @endforeach
                                        </select>
                                      </div>
                                      <div class="mb-2">
                                        <label>Task</label>
                                        <select name="task_id" class="form-control" required>
                                          @foreach($tasks as $task)
                                            <option value="{{ $task->id }}" @if($task->id == $comment->task_id) selected @endif>{{ $task->title }}</option>
                                          @endforeach
                                        </select>
                                      </div>
                                      <div class="mb-2">
                                        <label>Comment</label>
                                        <input type="text" name="comment" class="form-control" value="{{ $comment->comment }}" required maxlength="255">
                                      </div>
                                    </div>
                                    <div class="modal-footer">
                                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                      <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                  </form>
                                </div>
                              </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <a href="{{ route('admin.comment.index') }}" class="btn btn-secondary">Manage All Comments</a>
        @else
            <p>No comments found.</p>
            <a href="{{ route('admin.comment.index') }}" class="btn btn-secondary">Manage All Comments</a>
        @endif
    </section>

    <section id="notification-management">
        <h2>Notifications Management</h2>
        <!-- Inline Add Notification Form -->
        <form action="{{ route('admin.notification.create') }}" method="POST" class="mb-3 d-flex flex-wrap align-items-end gap-2">
            @csrf
            <select name="to_user_id" class="form-control" required>
                <option value="">To User</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
            <input type="text" name="message" class="form-control" placeholder="Message" required maxlength="1000">
            <button type="submit" class="btn btn-success">Add</button>
        </form>
        @if(isset($notifications) && $notifications->count() > 0)
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
                    @foreach($notifications->take(5) as $notification)
                    <tr>
                        <td>{{ $notification->id }}</td>
                        <td>{{ $notification->toUser->name ?? '-' }}</td>
                        <td>{{ $notification->message }}</td>
                        <td>{{ $notification->created_at->diffForHumans() }}</td>
                        <td>
                            <!-- Edit Button triggers modal -->
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editNotificationModal{{ $notification->id }}">Edit</button>
                            <form action="{{ route('admin.notification.delete', $notification->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this notification?')">Delete</button>
                            </form>
                            <!-- Edit Modal -->
                            <div class="modal fade" id="editNotificationModal{{ $notification->id }}" tabindex="-1" aria-labelledby="editNotificationModalLabel{{ $notification->id }}" aria-hidden="true">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <form action="{{ route('admin.notification.update', $notification->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                      <h5 class="modal-title" id="editNotificationModalLabel{{ $notification->id }}">Edit Notification</h5>
                                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                      <div class="mb-2">
                                        <label>To User</label>
                                        <select name="to_user_id" class="form-control" required>
                                          @foreach($users as $user)
                                            <option value="{{ $user->id }}" @if($user->id == $notification->to_user_id) selected @endif>{{ $user->name }}</option>
                                          @endforeach
                                        </select>
                                      </div>
                                      <div class="mb-2">
                                        <label>Message</label>
                                        <input type="text" name="message" class="form-control" value="{{ $notification->message }}" required maxlength="1000">
                                      </div>
                                    </div>
                                    <div class="modal-footer">
                                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                      <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                  </form>
                                </div>
                              </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <a href="{{ route('admin.notification.index') }}" class="btn btn-secondary">Manage All Notifications</a>
        @else
            <p>No notifications found.</p>
            <a href="{{ route('admin.notification.index') }}" class="btn btn-secondary">Manage All Notifications</a>
        @endif
    </section>
</div>
@endsection
