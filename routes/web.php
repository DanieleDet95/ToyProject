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

Route::get('/', function () {
  return view('home');
})->name('home');

Route::resource('/holidays', 'HolidayController');
Route::post('/holidays/filter', 'HolidayController@filter')->name('holidays.filter');
Route::post('/holidays/order', 'HolidayController@order')->name('holidays.order');
