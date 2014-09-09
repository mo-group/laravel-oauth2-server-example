<?php

class OAuthController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| OAuth Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function showWelcome()
	{
		return View::make('hello');
	}

	public function getToken()
	{
		return AuthorizationServer::performAccessTokenFlow();
	}

	public function showDeveloperPlatform()
	{

		$params = array();

		// display the developer platform
		return View::make('developer-platform', array('params' => $params));
	}

	public function showAuthorizationForm()
	{
		// get the data from the check-authorization-params filter
	    $params = Session::get('authorize-params');

	    // get the user id
	    $params['user_id'] = Auth::user()->id;

	    // display the authorization form
	    return View::make('authorization-form', array('params' => $params));
	}

	public function getAuthorizationCode()
	{

		// get the data from the check-authorization-params filter
	    $params = Session::get('authorize-params');

	    // get the user id
	    $params['user_id'] = Auth::user()->id;

	    // check if the user approved or denied the authorization request
	    if (Input::get('approve') !== null) {

	        $code = AuthorizationServer::newAuthorizeRequest('user', $params['user_id'], $params);

	        Session::forget('authorize-params');

	        return Redirect::to(AuthorizationServer::makeRedirectWithCode($code, $params));
	    }

	    if (Input::get('deny') !== null) {

	        Session::forget('authorize-params');

	        return Redirect::to(AuthorizationServer::makeRedirectWithError($params));
	    }
	}


}
