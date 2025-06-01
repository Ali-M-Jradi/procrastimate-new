<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GuestController;

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Guest Routes

Route::middleware(['auth'])->group(function () {
    Route::get('homepage',[\App\Http\Controllers\GuestController::class, 'index'])->name('homepage');
    Route::get('/login', [\App\Http\Controllers\Auth\GuestController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Auth\GuestController::class, 'login']);
    Route::get('/register', [\App\Http\Controllers\Auth\GuestController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [\App\Http\Controllers\Auth\GuestController::class, 'register']);
});

//User Routes

Route::middleware(['auth','role:user'])->group(function(){
    Route::get('/dashboard',[App\Http\Controllers\UserController::class,'viewDashboard'])->name('dashboard');
    
    Route::get('/task/{id}', [App\Http\Controllers\UserController::class, 'viewTask'])->name('task.view');
    Route::post('/task/{id}/update', [App\Http\Controllers\UserController::class, 'updateTask'])->name('task.update');
    Route::get('/task/{id}/delete', [App\Http\Controllers\UserController::class, 'deleteTask'])->name('task.delete');
    Route::get('/task/create', [App\Http\Controllers\UserController::class, 'createTask'])->name('task.create');
    Route::post('/task/store', [App\Http\Controllers\UserController::class, 'storeTask'])->name('task.store');
    
    Route::get('/my-notifications', [App\Http\Controllers\UserController::class, 'viewNotifications'])->name('notifications.view');
    Route::post('/create-notification', [App\Http\Controllers\UserController::class, 'createNotification'])->name('notification.create');
    
    Route::get('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
});

//Coach Routes

Route::middleware(['auth','role:coach'])->group(function(){
    Route::get('/coach-dashboard', [App\Http\Controllers\CoachController::class, 'viewDashboard'])->name('coach.dashboard');
    
    Route::get('/coach/task/{id}', [App\Http\Controllers\CoachController::class, 'viewTask'])->name('coach.task.view');
    Route::post('/coach/task/{id}/update', [App\Http\Controllers\CoachController::class, 'updateTask'])->name('coach.task.update');
    Route::get('/coach/task/{id}/delete', [App\Http\Controllers\CoachController::class, 'deleteTask'])->name('coach.task.delete');
    Route::get('/coach/task/create', [App\Http\Controllers\CoachController::class, 'createTask'])->name('coach.task.create');
    Route::post('/coach/task/store', [App\Http\Controllers\CoachController::class, 'storeTask'])->name('coach.task.store');
    Route::post('coach/user/{id}/assign-task', [App\Http\Controllers\CoachController::class, 'assignTask'])->name('coach.user.assign.task');
    Route::post('coach/user/{id}/remove-task', [App\Http\Controllers\CoachController::class, 'removeTask'])->name('coach.user.remove.task');
 
    Route::get('/coach/my-notifications', [App\Http\Controllers\CoachController::class, 'viewNotifications'])->name('coach.notifications.view');
    Route::post('/coach/create-notification', [App\Http\Controllers\CoachController::class, 'createNotification'])->name('coach.notification.create');
    
    Route::post('/coach/logout', [\App\Http\Controllers\Auth\CoachController::class, 'logout'])->name('coach.logout');
    
       
});

// Admin Routes

Route::middleware(['auth','role:admin'])->group(function(){
    Route::get('/admin-dashboard', [App\Http\Controllers\AdminController::class, 'viewDashboard'])->name('admin.dashboard');
    
    Route::get('/admin/task/{id}', [App\Http\Controllers\AdminController::class, 'viewTask'])->name('admin.task.view');
    Route::post('/admin/task/{id}/update', [App\Http\Controllers\AdminController::class, 'updateTask'])->name('admin.task.update');
    Route::get('/admin/task/{id}/delete', [App\Http\Controllers\AdminController::class, 'deleteTask'])->name('admin.task.delete');
    Route::get('/admin/task/create', [App\Http\Controllers\AdminController::class, 'createTask'])->name('admin.task.create');
    Route::post('/admin/task/store', [App\Http\Controllers\AdminController::class, 'storeTask'])->name('admin.task.store');
     Route::post('/admin/user/{id}/assign-task', [App\Http\Controllers\AdminController::class, 'assignTask'])->name('admin.user.assign.task');
    Route::post('/admin/user/{id}/remove-task', [App\Http\Controllers\AdminController::class, 'removeTask'])->name('admin.user.remove.task');
 
    Route::get('/admin/my-notifications', [App\Http\Controllers\AdminController::class, 'viewNotifications'])->name('admin.notifications.view');
    Route::post('/admin/create-notification', [App\Http\Controllers\AdminController::class, 'createNotification'])->name('admin.notification.create');
    
    Route::post('/admin/logout', [\App\Http\Controllers\Auth\AdminController::class, 'logout'])->name('admin.logout');
    
    Route::post('/admin/user/{id}/update', [App\Http\Controllers\AdminController::class, 'updateUser'])->name('admin.user.update');
    Route::get('/admin/user/{id}/delete', [App\Http\Controllers\AdminController::class, 'deleteUser'])->name('admin.user.delete');
    Route::get('/admin/user/create', [App\Http\Controllers\AdminController::class, 'createUser'])->name('admin.user.create');
    //Route::post('/admin/user/store', [App\Http\Controllers\AdminController::class, 'storeUser'])->name('admin.user.store');
    
    Route::post('/admin/user/{id}/promote-user', [App\Http\Controllers\AdminController::class, 'promoteUser'])->name('admin.user.promote');
    Route::post('/admin/user/{id}/demote-coach', [App\Http\Controllers\AdminController::class, 'demoteCoach'])->name('admin.coach.demote');
    Route::post('admin/create-coach', [App\Http\Controllers\AdminController::class, 'createCoach'])->name('admin.create.coach');
    Route::get('admin/coach/{id}/delete', [App\Http\Controllers\AdminController::class, 'deleteCoach'])->name('admin.coach.delete');
    Route::post('admin/coach/{id}/update', [App\Http\Controllers\AdminController::class, 'updateCoach'])->name('admin.coach.update');
    //Route::post('admin/coach/{id}/store-coach',[App\Http\Controllers\AdminController::class, 'storeCoach'])->name('admin.coach.store');

});