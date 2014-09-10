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

		$email     = Input::get('email');
		$password  = Input::get('password');
		$firstName = Input::get('first_name');
		$lastName  = Input::get('last_name');

		User::create(array(
			'email'      => $email,
			'password'   => Hash::make($password),
			'first_name' => $firstName,
			'last_name'  => $lastName,
		));

		if (Auth::attempt(array('email' => $email, 'password' => $password))) {

			return Redirect::intended('/oauth/developer_platform');
		}

		// TODO: with error
		return View::make('login-form');
	}

	public function login()
	{

		$email     = Input::get('email');
		$password  = Input::get('password');

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
