<?php

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
// Home
Route::get('/','AuctionController@index')->name('index');


//Auctions
Route::get('/auction','AuctionController@list');

// Auctions API
Route::get('api/auction_html','AuctionController@search_results_html');
Route::get('api/auction_json','AuctionController@search_results_json');

//Authenticated User API
Route::get('api/user','AuthenticatedUserController@search_results');

Route::get('/', 'HomePageController@index')->name('home');

// Authentication
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

Route::get('/admin/login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
Route::post('/admin/login', 'Auth\AdminLoginController@login')->name('admin.login.post');
Route::post('/admin/logout', 'Auth\AdminLoginController@logout')->name('admin.logout');
//Admin Home page after login
Route::group(['middleware'=>'admin'], function() {
    Route::get('/admin/home', 'Admin\HomeController@index');
});
//User Profile
Route::get('user/{username}','UserProfileController@showUserProfile')->name('user');
Route::put('user/{username}','UserProfileController@updateUserProfile')->name('user.update');
Route::delete('user/{username}','UserProfileController@deleteUserProfile')->name('user.delete');
Route::post('user/{username}','UserProfileController@store')->name('user.photo');
//User Profile API
Route::put('api/user/{username}/balance','UserProfileController@updateUserBalance')->name('user.balance');

