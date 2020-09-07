<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ForgotPasswordController;


Route::group(['middleware' => 'auth:api'], function(){
  Route::apiResource('/products', ProductController::class)->except(['index','show']);
  Route::apiResource('/users', UserController::class)->except(['store']);
  Route::post('/logout', [UserController::class, 'logout'])->name('user.logout');
});

Route::post('/users', [UserController::class, 'store']);
Route::apiResource('/products', ProductController::class)->only(['index','show']);
Route::post('/login', [UserController::class, 'login'])->name('user.login');

Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('/password/reset', [ForgotPasswordController::class, 'passwordReset']);