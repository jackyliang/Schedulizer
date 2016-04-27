<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'SchedulizerController@home');

/**
 * Home page of Schedulizer
 */
Route::get('about', 'SchedulizerController@about');

/**
 * Schedulizer class search form
 */
Route::get('search', 'SchedulizerController@search');

/**
 * Schedulizer generated schedules
 */
Route::get('schedule/{key?}', 'SchedulizerController@schedule');

/**
 * Add class to session
 */
Route::post('add', 'SchedulizerController@add');

/**
 * Save schedule to database
 */
Route::post('saveschedule', 'SchedulizerController@saveSchedule');

/**
 * Remove class from session
 */
Route::post('remove', 'SchedulizerController@remove');

/**
 * API for classes generated
 */
Route::get('generate', 'SchedulizerController@generate');

/**
 * Clear the cart
 * TODO: Change this to POST
 */
Route::get('cart/clear', 'SchedulizerController@clear');

/**
 * TODO: Remove this test API
 */
Route::get('classes', 'SchedulizerController@classes');

/**
 * Get cart contents
 */
Route::get('cart', 'SchedulizerController@cart');

/**
 * Schedulizer autocomplete API
 */
Route::get('autocomplete', 'SchedulizerController@autocomplete');

/**
 * Schedulizer class search results page
 */
Route::get('results', 'SchedulizerController@results');

/**
 * Authentication
 */
Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
