<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AccountPasswordController;
use App\Http\Controllers\AccountProfilePictureController;
use App\Http\Controllers\StreamTokenController;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return response()->json([
        'user' => new UserResource($request->user())
    ]);
})->middleware('auth:sanctum');


Route::get('/users', function () {
    return response()->json([
        "users" => UserResource::collection(User::all())
    ]);
});


Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('account')->group(function () {
        Route::patch('', [AccountController::class, 'update']);
        Route::patch('password', [AccountPasswordController::class, 'update']);
        Route::patch('profile-picture', [AccountProfilePictureController::class, 'update']);
        Route::delete('profile-picture', [AccountProfilePictureController::class, 'destroy']);
    });

    Route::get('/stream-token', [StreamTokenController::class, 'index']);
});
