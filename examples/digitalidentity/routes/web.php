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

Route::get('/generate-share', 'IdentityController@show');
Route::get('/receipt-info', 'ReceiptController@show');
Route::get('/generate-session', 'IdentityController@generateSession');