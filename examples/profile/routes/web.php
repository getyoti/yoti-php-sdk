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

Route::get('/', 'ShareController@show');

Route::get('/profile', 'ProfileController@show');

Route::get('/dynamic-share', 'DynamicShareController@show');

Route::get('/dbs-check', 'DbsCheckController@show');
Route::get('/advanced-identity', 'AdvancedIdentityController@show');
