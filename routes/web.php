<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () { return view('welcome'); });


Route::get('/users/add', [App\Http\Controllers\EditUserController::class, 'index'])->name('users');
Route::post('/users/add/successResult/',  [App\Http\Controllers\EditUserController::class, 'addNewUser']);
Route::post('/users/add/successEditResult/',  [App\Http\Controllers\EditUserController::class, 'editUser']);

Route::get('/users/del/{id}/',  [App\Http\Controllers\EditUserController::class, 'deleteUser']);
Route::get('/users/edit/{id}/',  [App\Http\Controllers\EditUserController::class, 'editUserShow']);

Auth::routes();
Route::get('/users', [App\Http\Controllers\UsersListController::class, 'index'])->name('users');
