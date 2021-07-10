<?php

use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::resource('messages', MessageController::class);
    Route::resource('chats', ChatController::class);
});

Route::get('getUser', function () {
    if (auth()->check())
        return response()->json([
            'authUser' => auth()->user()
        ]);
    return null;
});

Route::get('/chats/{chat}/get_messages/', [ChatController::class, 'get_messages'])->name('chat.get_messages');

require __DIR__ . '/auth.php';
