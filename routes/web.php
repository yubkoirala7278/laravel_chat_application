<?php

use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;




Route::middleware(['auth.admin'])->group(function(){
    Route::get('/chat/{id}',[ChatController::class,'chat'])->name('chat');
    Route::post('/send-message', [ChatController::class, 'store'])->name('chat.send');
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/messenger',[ChatController::class,'messenger'])->name('messenger');
    Route::get('/fetch-latest-notification', [ChatController::class, 'fetchLatestNotification']);
});



// authentication
Auth::routes();

Route::get('/', function () {
    return view('auth.login'); 
})->name('login.home');