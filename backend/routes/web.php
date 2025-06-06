<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\AdminController;

// Auth routes (public)
Route::get('/', function () {
    return ['Laravel' => app()->version()];
});
Route::get('/login', [GuestController::class, 'showLoginForm'])->name('login');
Route::post('/login', [GuestController::class, 'login']);
Route::get('/register', [GuestController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [GuestController::class, 'register']);
Route::get('/index', [GuestController::class, 'index'])->name('homepage');
Route::post('/logout', [GuestController::class, 'logout'])->name('logout');

// User Routes
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user/dashboard', [UserController::class, 'viewDashboard'])->name('userDashboard');
    Route::get('/task/{id}', [UserController::class, 'viewTask'])->name('task.view');
    Route::put('/task/{id}/update', [UserController::class, 'updateTask'])->name('task.update'); // changed to PUT
    Route::get('/task/{id}/delete', [UserController::class, 'deleteTask'])->name('task.delete');
    Route::get('/task/create', [UserController::class, 'createTask'])->name('task.create');
    Route::post('/task/store', [UserController::class, 'storeTask'])->name('task.store');
    Route::get('/my-notifications', [UserController::class, 'recieveNotifications'])->name('notifications.view');
    Route::post('/create-notification', [UserController::class, 'sendNotification'])->name('notification.create');
    Route::post('/comment/store', [UserController::class, 'createComment'])->name('comment.store');
    Route::post('/group/join', [UserController::class, 'joinGroup'])->name('group.join');
    Route::post('/group/leave', [UserController::class, 'leaveGroup'])->name('group.leave');
});

// Coach Routes
Route::middleware(['auth', 'role:coach'])->group(function () {
    Route::get('/coach-dashboard', [CoachController::class, 'viewDashboard'])->name('coach.dashboard');
    Route::get('/coach/task/{id}', [CoachController::class, 'viewTask'])->name('coach.task.view');
    Route::put('/coach/task/{id}/update', [CoachController::class, 'updateTask'])->name('coach.task.update'); // changed to PUT
    Route::get('/coach/task/{id}/delete', [CoachController::class, 'deleteTask'])->name('coach.task.delete');
    Route::get('/coach/task/create', [CoachController::class, 'createTask'])->name('coach.task.create');
    Route::post('/coach/task/store', [CoachController::class, 'storeTask'])->name('coach.task.store');
    Route::post('/coach/user/{id}/assign-task', [CoachController::class, 'assignTask'])->name('coach.user.assign.task');
    Route::post('/coach/user/{id}/remove-task', [CoachController::class, 'removeTask'])->name('coach.user.remove.task');
    Route::get('/coach/my-notifications', [CoachController::class, 'viewNotifications'])->name('coach.notifications.view');
    Route::post('/coach/create-notification', [CoachController::class, 'createNotification'])->name('coach.notification.create');
    Route::post('/coach/comment/store', [CoachController::class, 'storeComment'])->name('coach.comment.store');
    Route::post('/coach/group/join', [CoachController::class, 'joinGroup'])->name('coach.group.join');
    Route::post('/coach/group/leave', [CoachController::class, 'leaveGroup'])->name('coach.group.leave');
    Route::post('/coach/logout', [CoachController::class, 'logout'])->name('coach.logout');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin-dashboard', [AdminController::class, 'viewDashboard'])->name('admin.dashboard');
    Route::get('/admin/task/{id}', [AdminController::class, 'viewTask'])->name('admin.task.view');
    Route::put('/admin/task/{id}/update', [AdminController::class, 'updateTask'])->name('admin.task.update'); // changed to PUT
    Route::get('/admin/task/{id}/delete', [AdminController::class, 'deleteTask'])->name('admin.task.delete');
    Route::get('/admin/task/create', [AdminController::class, 'createTask'])->name('admin.task.create');
    Route::post('/admin/task/store', [AdminController::class, 'storeTask'])->name('admin.task.store');
    Route::post('/admin/user/{id}/assign-task', [AdminController::class, 'assignTask'])->name('admin.user.assign.task');
    Route::post('/admin/user/{id}/remove-task', [AdminController::class, 'removeTask'])->name('admin.user.remove.task');
    Route::get('/admin/my-notifications', [AdminController::class, 'viewNotifications'])->name('admin.notifications.view');
    Route::post('/admin/create-notification', [AdminController::class, 'createNotification'])->name('admin.notification.create');
    Route::post('/admin/comment/store', [AdminController::class, 'storeComment'])->name('admin.comment.store');
    Route::post('/admin/group/join', [AdminController::class, 'joinGroup'])->name('admin.group.join');
    Route::post('/admin/group/leave', [AdminController::class, 'leaveGroup'])->name('admin.group.leave');
    Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');
    Route::put('/admin/user/{id}/update', [AdminController::class, 'updateUser'])->name('admin.user.update'); // changed to PUT
    Route::get('/admin/user/{id}/delete', [AdminController::class, 'deleteUser'])->name('admin.user.delete');
    Route::get('/admin/user/create', [AdminController::class, 'createUser'])->name('admin.user.create');
    Route::post('/admin/user/{id}/promote-user', [AdminController::class, 'promoteUser'])->name('admin.user.promote');
    Route::post('/admin/user/{id}/demote-coach', [AdminController::class, 'demoteCoach'])->name('admin.coach.demote');
    Route::post('/admin/create-coach', [AdminController::class, 'createCoach'])->name('admin.create.coach');
    Route::get('/admin/coach/{id}/delete', [AdminController::class, 'deleteCoach'])->name('admin.coach.delete');
    Route::put('/admin/coach/{id}/update', [AdminController::class, 'updateCoach'])->name('admin.coach.update'); // changed to PUT
});