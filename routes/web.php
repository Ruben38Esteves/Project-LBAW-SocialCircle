<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserNotificationController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\GroupJoinRequestController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Home
Route::redirect('/', '/login');

// Cards
Route::controller(CardController::class)->group(function () {
    Route::get('/cards', 'list')->name('cards');
    Route::get('/cards/{id}', 'show');
});


// API
Route::controller(ItemController::class)->group(function () {
    Route::put('/api/cards/{card_id}', 'create');
    Route::post('/api/item/{id}', 'update');
    Route::delete('/api/item/{id}', 'delete');
});

// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});

Route::controller(PostController::class)->group(function () {
    Route::get('/home', 'homeFeed')->name('posts');
    Route::post('/posts', 'create')->name('posts.create');
    Route::put('/posts/edit/{id}', [PostController::class, 'update']);
    Route::delete('/posts/delete/{id}', [PostController::class, 'delete']); 
});

Route::controller(UserController::class)->group(function (){
    Route::get('/profile/{username}', 'fillProfile')->name('user');
    Route::get('/search', 'search')->name('search');
    Route::get('/friends/{username}', 'friends')->name('friends');
    Route::get('/groups/{username}', 'groups')->name('groups');
    Route::get('/notifications/{username}','notifications')->name('notifications');
});

Route::controller(CommentController::class)->group(function (){
    Route::put('/comments/create/{id}', [CommentController::class, 'create']);
});

Route::controller(GroupController::class)->group(function (){
    Route::get('/group/{id}', 'show')->name('group');
    Route::get('/group/{id}/manage', 'manage')->name('group.manage');
});

Route::controller(GroupJoinRequestController::class)->group(function (){
    Route::post('/group/{id}/join-request', 'create')->name('group-join-request.create');
    Route::delete('/group/{id}/remove-request', 'remove')->name('group-join-request.remove');
});

Route::controller(UserNotificationController::class)->group(function (){
    Route::post('/notification/{id}/markViewed', 'markAsViewed')->name('markNotifViewed');
});
