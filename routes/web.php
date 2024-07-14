<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/app', function () {
    return view("app");
});
Route::get('/app/{any}', function () {
    return view('app');
})->where("any", ".*");


Route::post("/register", [RegisterController::class, "store"]);
Route::prefix("login")->controller(LoginController::class)->group(function () {
    Route::post("", "store");
    Route::delete("", "destroy");
});

Route::get('/tes', function () {
    dd('tes');

    return "";
});

Route::fallback(function () {
    return redirect("/app");
});
