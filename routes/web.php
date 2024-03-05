<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\Admin\Home::class, 'index'])->name('admin.home');
Route::get('/customers/{page?}', [App\Http\Controllers\Admin\Customer::class, 'index'])->where('page', '[0-9]+')->name('admin.customers');
Route::post('/api/customer/{id}', [App\Http\Controllers\Admin\Customer::class, 'update']);

Route::get('/feels/{page?}', [App\Http\Controllers\Admin\Feel::class, 'index'])->where('page', '[0-9]+')->name('admin.feels');
Route::post('/api/feel/{id}', [App\Http\Controllers\Admin\Feel::class, 'update']);

Route::get('/groups/{page?}', [App\Http\Controllers\Admin\Group::class, 'index'])->where('page', '[0-9]+')->name('admin.groups');
Route::post('/api/group/{id}', [App\Http\Controllers\Admin\Group::class, 'update']);

Route::get('/friends/{page?}', [App\Http\Controllers\Admin\Friend::class, 'index'])->where('page', '[0-9]+')->name('admin.friends');
Route::post('/api/friend/{id}', [App\Http\Controllers\Admin\Friend::class, 'update']);

Route::get('/api/search/user/{id}', [App\Http\Controllers\Api\SearchUserController::class, 'searchUser']);
Route::get('/api/search/feel/{id}', [App\Http\Controllers\Api\SearchFeelController::class, 'searchFeel']);
