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
    return view('welcome');
});

Route::get('/admin','AdminController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// for admin routing in case we need admin functionality for poll creation 
// Route::group(['namespace' => 'Auth'], function () {

//     Route::post('login', 'LoginController@login')->name('admin');
//     Route::get('logout', 'LoginController@logout')->name('admin.logout');

// });

Auth::routes([
    'register' => false, // Registration Route
    'login' => false, // Login Route
]);