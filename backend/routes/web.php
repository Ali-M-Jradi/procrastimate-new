<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NotificationController;

// --------------------
// Public Auth Routes
// --------------------
Route::get('/', [GuestController::class, 'index'])->name('homepage');
Route::get('/login', [GuestController::class, 'showLoginForm'])->name('login');
Route::post('/login', [GuestController::class, 'login']);
Route::get('/register', [GuestController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [GuestController::class, 'register']);
Route::post('/logout', [GuestController::class, 'logout'])->name('logout');

// --------------------
// User Dashboard
// --------------------
Route::middleware(['auth', 'checkRole:user'])->group(function () {
    Route::get('/user/dashboard', [UserController::class, 'viewDashboard'])->name('userDashboard');
});
// Route::get('/user/dashboard', [UserController::class, 'viewDashboard'])->name('userDashboard');

// --------------------
// Coach Dashboard
// --------------------
Route::middleware(['auth', 'checkRole:coach'])->group(function () {
    Route::get('/coach/dashboard', [CoachController::class, 'viewDashboard'])->name('coach.dashboard');
});

// --------------------
// Group Management (Coach/Admin/User)
// --------------------
Route::middleware(['auth', 'checkRole:user,coach,admin'])->group(function () {
    // View all
    Route::get('/groups', [GroupController::class, 'listGroups'])->name('groups.index');
    // Join/Leave
    Route::post('/groups/join', [GroupController::class, 'joinGroup'])->name('groups.join');
    Route::get('/groups/join', [GroupController::class, 'showGroupJoinForm'])->name('groups.joinForm');
    Route::post('/groups/leave', [GroupController::class, 'leaveGroup'])->name('groups.leave');
    // View single group (details) - This needs to be after the specific routes to avoid conflicts
    Route::get('/groups/{id}', [GroupController::class, 'viewGroup'])->name('groups.view');
});

Route::middleware(['auth', 'checkRole:coach,admin'])->group(function () {
    // Create
    Route::get('/groups/create', [GroupController::class, 'showGroupCreationForm'])->name('groups.create');
    Route::post('/groups', [GroupController::class, 'createGroup'])->name('groups.store');
    // Update
    Route::get('/groups/{id}/edit', [GroupController::class, 'showGroupUpdateForm'])->name('groups.edit');
    Route::put('/groups/{id}', [GroupController::class, 'updateGroup'])->name('groups.update');
    // Delete
    Route::delete('/groups/{id}', [GroupController::class, 'deleteGroup'])->name('groups.destroy');
});

// --------------------
// Task Management (Shared)
// --------------------
Route::middleware(['auth', 'checkRole:user,coach,admin'])->group(function () {
    // User tasks
    Route::get('/task/create', [TaskController::class, 'showTaskCreationForm'])->name('task.createForm');
    Route::post('/task/create', [TaskController::class, 'createTask'])->name('task.create');
    Route::get('/task/{id}/update', [TaskController::class, 'showTaskUpdateForm'])->name('task.updateForm');
    Route::post('/task/{id}/update', [TaskController::class, 'updateTask'])->name('task.update');
    Route::delete('/task/{id}/delete', [TaskController::class, 'deleteTask'])->name('task.delete');
});

// Coach-specific task management
Route::middleware(['auth', 'checkRole:coach,admin'])->group(function () {
    Route::get('/coach/task/create', [CoachController::class, 'showTaskCreationForm'])->name('coach.task.create');
    Route::post('/coach/task/create', [CoachController::class, 'createTask'])->name('coach.task.store');
    Route::get('/coach/task/{id}/update', [CoachController::class, 'showTaskUpdateForm'])->name('coach.task.updateForm');
    Route::post('/coach/task/{id}/update', [CoachController::class, 'updateTask'])->name('coach.task.update');
    Route::delete('/coach/task/{id}/delete', [CoachController::class, 'deleteTask'])->name('coach.task.delete');
    
    // Task approval/decline (Coach/Admin)
    Route::get('/task/{id}/showApprovalForm', [CoachController::class, 'showApprovalForm'])->name('task.approvalForm');
    Route::post('/task/{id}/approve', [CoachController::class, 'approveTask'])->name('task.approve');
    Route::get('/task/{id}/showDeclineForm', [CoachController::class, 'showDeclineForm'])->name('task.declineForm');
    Route::post('/task/{id}/reject', [CoachController::class, 'rejectTask'])->name('task.reject');
});

// --------------------
// Notification Management (Shared)
// --------------------
Route::middleware(['auth', 'checkRole:user,coach,admin'])->group(function () {
    Route::get('/my-notifications', [NotificationController::class, 'recieveNotifications'])->name('notifications.view');
});

// --------------------
// Comment Management (Shared)
// --------------------
Route::middleware(['auth', 'checkRole:user,coach,admin'])->group(function () {
    Route::get('/comment/create', [CommentController::class, 'showCommentCreationForm'])->name('comment.create');
    Route::post('/comment/create', [CommentController::class, 'createComment'])->name('comment.store');
    // Comment delete (shared for user/coach)
    Route::delete('/comment/{id}/delete', [App\Http\Controllers\CommentController::class, 'deleteComment'])->name('comment.delete');
});
// --------------------
// Admin Management
// --------------------
Route::middleware(['auth', 'checkRole:admin'])->group(function () {
    // Admin dashboard
    Route::get('/admin/dashboard', [AdminController::class, 'viewDashboard'])->name('admin.dashboard');
    
    // Admin comment management
    Route::get('/admin/comments', [AdminController::class, 'listComments'])->name('admin.comment.index');
    Route::get('/admin/comments/create', [AdminController::class, 'showCreateCommentForm'])->name('admin.comment.createForm');
    Route::post('/admin/comments/create', [AdminController::class, 'createComment'])->name('admin.comment.create');
    Route::get('/admin/comments/{id}/edit', [AdminController::class, 'showEditCommentForm'])->name('admin.comment.editForm');
    Route::put('/admin/comments/{id}/edit', [AdminController::class, 'updateComment'])->name('admin.comment.update');
    Route::delete('/admin/comments/{id}/delete', [AdminController::class, 'deleteComment'])->name('admin.comment.delete');
    
    // User management
    Route::get('/admin/user/create', [AdminController::class, 'showUserCreationForm'])->name('admin.user.createForm');
    Route::post('/admin/user/create', [AdminController::class, 'createUser'])->name('admin.user.create');
    Route::get('/admin/user/{id}/update', [AdminController::class, 'showUpdateUserForm'])->name('admin.user.updateForm');
    Route::put('/admin/user/{id}/update', [AdminController::class, 'updateUser'])->name('admin.user.update');
    Route::get('/admin/user/{id}/delete', [AdminController::class, 'showDeleteUserForm'])->name('admin.user.deleteForm');
    Route::post('/admin/user/{id}/delete', [AdminController::class, 'deleteUser'])->name('admin.user.delete');
    
    // Promote/demote
    Route::get('/admin/user/{id}/promote', [AdminController::class, 'showPromoteUserForm'])->name('admin.user.promoteForm');
    Route::post('/admin/user/{id}/promote', [AdminController::class, 'promoteUser'])->name('admin.user.promote');
    Route::get('/admin/user/{id}/demote-coach', [AdminController::class, 'showDemoteCoachForm'])->name('admin.coach.demoteForm');
    Route::post('/admin/user/{id}/demote-coach', [AdminController::class, 'demoteCoach'])->name('admin.coach.demote');
    
    // Coach management
    Route::get('/admin/coach/create', [AdminController::class, 'showCoachCreationForm'])->name('admin.coach.createForm');
    Route::post('/admin/coach/create', [AdminController::class, 'createCoach'])->name('admin.coach.create');
    Route::get('/admin/coach/{id}/update', [AdminController::class, 'showUpdateCoachForm'])->name('admin.coach.updateForm');
    Route::put('/admin/coach/{id}/update', [AdminController::class, 'updateCoach'])->name('admin.coach.update');
    Route::get('/admin/coach/{id}/delete', [AdminController::class, 'showDeleteCoachForm'])->name('admin.coach.deleteForm');
    Route::post('/admin/coach/{id}/delete', [AdminController::class, 'deleteCoach'])->name('admin.coach.delete');
    
    // Admin notification management
    Route::get('/admin/notifications', [AdminController::class, 'listNotifications'])->name('admin.notification.index');
    Route::get('/admin/notifications/create', [AdminController::class, 'showCreateNotificationForm'])->name('admin.notification.createForm');
    Route::post('/admin/notifications/create', [AdminController::class, 'createNotification'])->name('admin.notification.create');
    Route::get('/admin/notifications/{id}/edit', [AdminController::class, 'showEditNotificationForm'])->name('admin.notification.editForm');
    Route::put('/admin/notifications/{id}/edit', [AdminController::class, 'updateNotification'])->name('admin.notification.update');
    Route::delete('/admin/notifications/{id}/delete', [AdminController::class, 'deleteNotification'])->name('admin.notification.delete');
});