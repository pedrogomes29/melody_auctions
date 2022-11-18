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
Route::get('/', 'Auth\LoginController@home');

// Cards
Route::get('cards', 'CardController@list');
Route::get('cards/{id}', 'CardController@show');

// API
Route::put('api/cards', 'CardController@create');
Route::delete('api/cards/{card_id}', 'CardController@delete');
Route::put('api/cards/{card_id}/', 'ItemController@create');
Route::post('api/item/{id}', 'ItemController@update');
Route::delete('api/item/{id}', 'ItemController@delete');

// Authentication
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// Admin
Route::get('admin/{admin_id}', 'AdminController@show');
Route::get('admin/{admin_id}/auctions', 'AdminController@auctions')->name('admin.auctions');
Route::get('admin/{admin_id}/auctions/{auctions_id}', 'AdminController@edit_auctions');
Route::put('admin/{admin_id}/auctions/{auctions_id}', 'AdminController@edit_auction');
Route::delete('admin/{admin_id}/auctions/{auctions_id}', 'AdminController@delete_auction');
Route::post('admin/{admin_id}/auctions/{auctions_id}/default_image', 'AdminController@default_image');

// Auctions
Route::get('auction/{id}/edit', 'AuctionController@edit');