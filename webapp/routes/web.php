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
        });

    });
});

//User Profile
Route::get('user/{username}','UserProfileController@showUserProfile')->name('user');
Route::put('user/{username}','UserProfileController@updateUserProfile')->name('user.update');
Route::delete('user/{username}','UserProfileController@deleteUserProfile')->name('user.delete');
Route::post('user/{username}','UserProfileController@store')->name('user.photo');
Route::get('/user/{username}/auction','UserProfileController@showUserAuctions')->name('user.auction');
//User Profile API
Route::put('api/user/{username}/balance','UserProfileController@updateUserBalance')->name('user.balance');
Route::get('/api/user/{username}/bids', 'UserProfileController@getBids');


// Auction
Route::post('/auction/{auction_id}/updatePhoto', 'AuctionController@updatePhoto')->name('auction.updatePhoto');
Route::post('/auction/{auction_id}/defaultImage', 'AuctionController@defaultImage')->name('auction.defaultImage');
Route::put('auction/{auction_id}', 'AuctionController@ownerUpdate')->name('auction.update');
Route::delete('auction/{auction_id}', 'AuctionController@ownerDelete')->name('auction.delete');
Route::put('auction/{auction_id}/admin', 'AuctionController@adminUpdate')->name('auction.adminUpdate');
Route::delete('auction/{auction_id}/admin', 'AuctionController@adminDelete')->name('auction.adminDelete');
Route::get('auction/{auction_id}', 'AuctionController@show')->where('auction_id', '[0-9]+')->name('auction.show');
Route::get('auction/create', 'AuctionController@create')->name('auction.showCreate');
Route::post('auction', 'AuctionController@store')->name('auction.store');
Route::post('api/follow', 'FollowController@store')->name('follow.store');
Route::delete('api/follow', 'FollowController@destroy')->name('follow.destroy');


// Admin
Route::get('admin/{admin_username}', 'AdminController@show')->name('adminDashboard');
Route::post('admin/{admin_username}', 'AdminController@closeReport')->name('closeReport');

// Follows
Route::get('user/{username}/follow', 'FollowController@showFollows')->name('user.follows');


// bids
Route::post('api/auction/{auction_id}/bid', 'BidController@create')->where('auction_id', '[0-9]+')->name('bid.create');
Route::get('api/auction/{auction_id}/bid', 'AuctionController@bids')->where('auction_id', '[0-9]+')->name('bid.list');



// Reports
Route::post('user/{username}/report', 'ReportController@store')->name('report.create');

// Reviews
Route::get('user/{username}/reviews', 'ReviewController@showReviews')->name('user.reviews');
Route::post('/user/{username}/review', 'ReviewController@create')->name('review.create');


//notifications
Route::put('api/notifications/{userId}','NotificationController@markAsRead')->name('notifications.markAsRead');
//messages
Route::post('/api/auction/{auctionId}/message', 'MessageController@store')->name('message.store');

//about-us
Route::get('/about-us', function () {
    return view('pages.about-us');
})->name('about-us');
