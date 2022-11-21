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
Route::get('/home', 'HomePageController@index');
// Authentication
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');


//Admin 
Route::group(['prefix' => 'admin', 'namespace' => 'Auth'], function () {
    Route::get('/login','AdminLoginController@getLogin')->name('adminLogin');
    Route::post('/login', 'AdminLoginController@postLogin')->name('adminLoginPost');
    Route::post('/logout', 'AdminLoginController@adminLogout')->name('adminLogout');	
    Route::group(['middleware' => 'adminauth'], function () {
        Route::get('/', function () {
            return view('welcome');
        })->name('adminDashboard');

    });
});

//User Profile
Route::get('user/{username}','UserProfileController@showUserProfile')->name('user');
Route::put('user/{username}','UserProfileController@updateUserProfile')->name('user.update');
Route::delete('user/{username}','UserProfileController@deleteUserProfile')->name('user.delete');
Route::post('user/{username}','UserProfileController@store')->name('user.photo');
//User Profile API
Route::put('api/user/{username}/balance','UserProfileController@updateUserBalance')->name('user.balance');


// Auction
Route::get('auction/{auction_id}/edit', 'AuctionController@edit')->name('auction.edit');
Route::post('/auction/{auction_id}/updatePhoto', 'AuctionController@updatePhoto')->name('auction.updatePhoto');
Route::put('auction/{auction_id}/edit', 'AuctionController@ownerUpdate')->name('auction.update');
Route::delete('auction/{auction_id}/edit', 'AuctionController@ownerDelete')->name('auction.delete');
Route::get('auction/{auction_id}', 'AuctionController@show')->where('auction_id', '[0-9]+')->name('auction.show');
Route::get('auction/create', 'AuctionController@create')->name('auction.showCreate');
Route::post('auction/create', 'AuctionController@store')->name('auction.store');


// Admin
Route::get('admin/{admin_id}', 'AdminController@show')->name('adminDashboard');
Route::get('admin/{admin_id}/auctions', 'AdminController@auctions')->name('admin.auctions');
Route::get('admin/{admin_id}/auctions/{auctions_id}', 'AdminController@edit_auctions');
Route::put('admin/{admin_id}/auctions/{auctions_id}', 'AdminController@edit_auction');
Route::delete('admin/{admin_id}/auctions/{auctions_id}', 'AdminController@delete_auction');
Route::post('admin/{admin_id}/auctions/{auctions_id}/default_image', 'AdminController@default_image');


// Follows
Route::get('user/{username}/follow', 'FollowController@showFollows')->name('user.follows');


// bids
Route::post('api/auction/{auction_id}/bid', 'BidController@create')->where('auction_id', '[0-9]+')->name('bid.create');
Route::get('api/auction/{auction_id}/bid', 'AuctionController@bids')->where('auction_id', '[0-9]+')->name('bid.list');



