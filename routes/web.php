<?php

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

use App\Http\Controllers\TaskController;

Route::get('/', [TaskController::class, 'index']);
Route::get('/task', [TaskController::class, 'index']);
Route::get('/task/edit', [TaskController::class, 'edit']);
Route::post('/task', [TaskController::class, 'post']);
Route::post('/task/put', [TaskController::class, 'put']);
Route::delete('/task', [TaskController::class, 'delete']);