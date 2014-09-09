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

		User::create(array(
			'email'      => Input::get('email'),
			'password'   => Hash::make(Input::get('password')),
			'first_name' => Input::get('first_name'),
			'last_name'  => Input::get('last_name'),
		));

		if (Auth::attempt(array('email' => $email, 'password' => $password))) {

			return Redirect::intended('/oauth/developer_platform');
		}

		// TODO: with error
		return View::make('login-form');
	}

	public function login()
	{

		if (Auth::attempt(array('email' => $email, 'password' => $password))) {

			return Redirect::intended('/oauth/developer_platform');
		}

		// TODO: with error
		return View::make('login-form');
	}

	public function logout()
	{

		Auth::logout();

		return Redirect::to('/login');
	}
}
