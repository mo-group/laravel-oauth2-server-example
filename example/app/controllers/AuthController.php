<?php

class AuthController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Auth Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function showSignupForm()
	{
		return View::make('signup-form');
	}

	public function showLoginForm()
	{
		return View::make('login-form');
	}

	public function signup()
	{

	}

	public function login()
	{

	}

	public function logout()
	{

	}
}
