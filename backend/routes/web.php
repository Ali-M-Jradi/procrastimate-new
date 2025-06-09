<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\CheckRole;


// Auth routes (public)
// Route::get('/', function () {
//     return ['Laravel' => app()->version()];
// });

Route::get('/login', [GuestController::class, 'showLoginForm'])->name('login');
Route::post('/login', [GuestController::class, 'login']);
Route::get('/register', [GuestController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [GuestController::class, 'register']);
Route::get('/', [GuestController::class, 'index'])->name('homepage');
Route::post('/logout', [GuestController::class, 'logout'])->name('logout');

// User Dashboard
// Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user/dashboard', [UserController::class, 'viewDashboard'])->name('userDashboard');
// });

// Coach Dashboard
// Route::middleware(['auth', 'role:coach'])->group(function () {
    Route::get('/coach/dashboard', [CoachController::class, 'viewDashboard'])->name('coach.dashboard');
// });

// Coach and Admin shared routes
// Route::middleware(['auth', 'role:coach|admin'])->group(function () {
    // Task approval/decline
    Route::get('/task/{id}/showApprovalForm', [CoachController::class, 'showApprovalForm'])->name('task.approvalForm');
    Route::post('/task/{id}/approve', [CoachController::class, 'approveTask'])->name('task.approve');
    Route::get('/task/{id}/showDeclineForm', [CoachController::class, 'showDeclineForm'])->name('task.declineForm');
    Route::post('/task/{id}/decline', [CoachController::class, 'declineTask'])->name('task.decline');

    // Group creation
    // Group creation (coach/admin only)
    // Route::middleware(['auth', 'role:coach,admin'])->group(function () {
        Route::get('/groups/create', [CoachController::class, 'showGroupCreationForm'])->name('groups.create');
        Route::post('/groups', [CoachController::class, 'createGroup'])->name('groups.store');
    // });
    // Group update
    Route::get('/group/{id}/update', [CoachController::class, 'showGroupUpdateForm'])->name('group.updateForm');
    Route::post('/group/{id}/update', [CoachController::class, 'updateGroup'])->name('group.update');
    // Group delete
    Route::get('/group/{id}/delete', [CoachController::class, 'showGroupDeleteForm'])->name('group.deleteForm');
    Route::post('/group/{id}/delete', [CoachController::class, 'deleteGroup'])->name('group.delete');
    Route::get('/groups/{id}/edit', [App\Http\Controllers\CoachController::class, 'updateGroup'])->name('groups.edit');
    Route::delete('/groups/{id}', [App\Http\Controllers\CoachController::class, 'deleteGroup'])->name('groups.destroy');
// });

// Admin-only management routes
// Route::middleware(['auth', 'role:admin'])->group(function () {
    // Admin dashboard
    Route::get('/admin/dashboard', [AdminController::class, 'viewDashboard'])->name('admin.dashboard');

    // User creation
    Route::get('/admin/user/create', [AdminController::class, 'showUserCreationForm'])->name('admin.user.createForm');
    Route::post('/admin/user/create', [AdminController::class, 'createUser'])->name('admin.user.create');
    // User update
    Route::get('/admin/user/{id}/update', [AdminController::class, 'showUpdateUserForm'])->name('admin.user.updateForm');
    Route::put('/admin/user/{id}/update', [AdminController::class, 'updateUser'])->name('admin.user.update');
    // User delete
    Route::get('/admin/user/{id}/delete', [AdminController::class, 'showDeleteUserForm'])->name('admin.user.deleteForm');
    Route::post('/admin/user/{id}/delete', [AdminController::class, 'deleteUser'])->name('admin.user.delete');

    // Promote/demote
    Route::get('/admin/user/{id}/promote', [AdminController::class, 'showPromoteUserForm'])->name('admin.user.promoteForm');
    Route::post('/admin/user/{id}/promote', [AdminController::class, 'promoteUser'])->name('admin.user.promote');
    Route::get('/admin/user/{id}/demote-coach', [AdminController::class, 'showDemoteCoachForm'])->name('admin.coach.demoteForm');
    Route::post('/admin/user/{id}/demote-coach', [AdminController::class, 'demoteCoach'])->name('admin.coach.demote');

    // Coach creation
    Route::get('/admin/coach/create', [AdminController::class, 'showCoachCreationForm'])->name('admin.coach.createForm');
    Route::post('/admin/coach/create', [AdminController::class, 'createCoach'])->name('admin.create.coach');
    // Coach update
    Route::get('/admin/coach/{id}/update', [AdminController::class, 'showUpdateCoachForm'])->name('admin.coach.updateForm');
    Route::put('/admin/coach/{id}/update', [AdminController::class, 'updateCoach'])->name('admin.coach.update');
    // Coach delete
    Route::get('/admin/coach/{id}/delete', [AdminController::class, 'showDeleteCoachForm'])->name('admin.coach.deleteForm');
    Route::post('/admin/coach/{id}/delete', [AdminController::class, 'deleteCoach'])->name('admin.coach.delete');
// });

// Shared routes for all authenticated users (user, coach, admin)
// Route::middleware(['auth'])->group(function () {
    // Shared task routes
    Route::get('/task/create', [UserController::class, 'showTaskCreationForm'])->name('task.createForm');
    Route::post('/task/create', [UserController::class, 'createTask'])->name('task.create');
    Route::get('/task/{id}/update', [UserController::class, 'showTaskUpdateForm'])->name('task.updateForm');
    Route::post('/task/{id}/update', [UserController::class, 'updateTask'])->name('task.update');
    Route::delete('/task/{id}/delete', [UserController::class, 'deleteTask'])->name('task.delete');

    // Shared notification routes
    Route::get('/my-notifications', [UserController::class, 'recieveNotifications'])->name('notifications.view');
    Route::post('/create-notification', [UserController::class, 'sendNotification'])->name('notification.send');
    Route::get('/create-notification', [UserController::class, 'showNotificationForm'])->name('notification.createForm');

    // Shared comment and group routes
    Route::post('/comment/create', [UserController::class, 'createComment'])->name('comment.create');
    Route::get('/comment/creationForm', [UserController::class, 'showCommentCreationForm'])->name('comment.createForm');
    Route::post('/group/join', [UserController::class, 'joinGroup'])->name('group.join');
    Route::get('/group/joinForm', [UserController::class, 'showGroupJoinForm'])->name('group.joinForm');
    Route::post('/group/leave', [UserController::class, 'leaveGroup'])->name('group.leave');
// });