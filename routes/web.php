<?php

use App\Http\Controllers\AuthenticationController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/login', function () {
//     return view('login');
// });

Route::get('/home', function () {
    return view('home');
});

Route::get('/login', function () {
    return view('login/all');
});

Route::get('/register', function () {
    return view('register/all');
});

Route::get('dashboard', [AuthenticationController::class, 'dashboard']); 
// Route::get('login', [AuthenticationController::class, 'login'])->name('login');
Route::post('login', [AuthenticationController::class, 'login'])->name('login');
Route::post('register', [AuthenticationController::class, 'register'])->name('register');
// Route::get('register', [AuthenticationController::class, 'register'])->name('register');