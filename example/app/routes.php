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


Route::get('/signup', 'AuthController@showSignupForm');
Route::post('/signup', array(
    'before' => 'csrf', 'AuthController@signup'));

Route::get('/login', 'AuthController@showLoginForm');
Route::post('/login', array(
    'before' => 'csrf', 'AuthController@login'));

Route::get('/logout', 'AuthController@logout');



Route::get('/oauth/developer_platform', array(
    'before' => 'auth', 'OAuthController@showDeveloperPlatform'));



Route::get('/oauth/authorize', array(
    'before' => 'check-authorization-params|auth', 'OAuthController@showAuthorizationForm'));


Route::post('/oauth/authorize', array(
    'before' => 'check-authorization-params|auth|csrf', 'OAuthController@getAuthorizationCode'));




Route::post('/oauth/access_token', 'OAuthController@getToken');