<?php

use App\Http\Controllers\CommentController;
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
Route::redirect('/', '/messages');

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
    Route::post('/posts/createGroupPost/{id}',  'createGroupPost')->name('group-posts.create');
});

Route::controller(UserController::class)->group(function (){
    Route::get('/profile/{username}', 'fillProfile')->name('user');
    Route::get('/search', 'search')->name('search');
    Route::get('/friends/{username}', 'friends')->name('friends');
    Route::get('/groups/{username}', 'groups')->name('groups');
    //Route::get('/messages/{username}', 'messages')->name('messages');
});

Route::controller(CommentController::class)->group(function (){
    Route::put('/comments/create/{id}', [CommentController::class, 'create']);
});

Route::controller(GroupController::class)->group(function (){
    Route::post('/group/createFinal', 'create')->name('group.create');
    Route::get('/group/create', 'createPage')->name('group.createPage');
    Route::get('/group/{id}', 'show')->name('group');
    Route::delete('/group/{id}/manage/removeMember', [GroupController::class, 'removeMember'])->name('group.remove.member');
    Route::get('/group/{id}/manage', 'manage')->name('group.manage');
});

Route::controller(GroupJoinRequestController::class)->group(function (){
    Route::post('/group/{id}/join-request', 'create')->name('group-join-request.create');
    Route::put('/group/{id}/accept-request', 'accept')->name('group-join-request.accept');
    Route::put('/group/{id}/reject-request', 'reject')->name('group-join-request.reject');
    Route::delete('/group/{id}/remove-request', 'remove')->name('group-join-request.remove');
});

Route::controller(MessageController::class)->group(function (){
    Route::get('/messages/{username}', 'showPage')->name('messages');
    Route::get('/message/{username}', 'messages')->name('messages');
    Route::post('/message/send/{username}', 'sendMessage')->name('messages.send');
});