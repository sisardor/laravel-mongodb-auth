<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

// Routes for ads controller
Route::post('/account/ads', 'AccountController@store');
Route::get('/account/ads', 'AccountController@index');
Route::get('/account/ads/{id}', 'AccountController@show');
Route::put('/account/ads/{id}', 'AccountController@update');
Route::get('account', array('as' => 'home', function() {
	return View::make('ads.index');
}))->before('auth');

// Routes for auth controller
Route::get('logout', array('as' => 'logout', 'uses' => 'AuthController@logout'))->before('auth');
Route::post('login', 'AuthController@login');
Route::post('register', 'AuthController@register');
Route::get('register', array('as'=>'register_form', 'uses' => 'AuthController@register_form'))->before('guest');
Route::get('login', array('as' => 'login', 'uses' => 'AuthController@index'))->before('guest');
Route::get('profile', array('as' => 'profile', 'uses' => 'AuthController@profile'))->before('auth');

// Routes for contacts controller
Route::resource('contact', 'ContactsController');


Route::get('/', function() {
	return View::make('home');
});


Route::get('/rest', function() {
	return View::make('rest');
});
Route::get('list', function() {
	return View::make('list');
});
Route::get('detail', function() {
	return View::make('detail');
});







// Test cases

Route::get('/rest/projects', function() {
	return '[ { "_id"  : "51b8588fe4b0c2edf2e75818" , "name" : "Cappucino" , "site" : "http://cappuccino.org/" , "description" : "Objective-J."},
			  { "_id" :  "51b8588fe4b0c2edf2e70000" , "name" : "Sardor" , "site" : "http://cappuccino.org/" , "description" : "Sardor-J."} ]';
});
Route::get('/rest/projects/{id}', function($id) {
	return '{ "_id" :  "51b8588fe4b0c2edf2e75818" , "name" : "Cappucino" , "site" : "http://cappuccino.org/" , "description" : "Objective-J."}';
});
Route::post('/rest/projects', function() {
	return "Good";
});
Route::put('/rest/projects/{id}', function($id) {
	return 'We got the post ' . Input::get('name');
});
Route::delete('/rest/projects/{id}', function($id) {
	return 'Ok we will delete ';
});



