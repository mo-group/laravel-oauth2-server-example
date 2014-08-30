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


Route::post('oauth/access_token', 'OAuthController@getToken');


Route::get('/oauth/authorize', array(
    'before' => 'check-authorization-params|auth', 'OAuthController@showAuthorizationForm'));


Route::post('/oauth/authorize', array(
    'before' => 'check-authorization-params|auth|csrf', 'OAuthController@getAuthorizationCode'));