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

Route::get('/', function()
{
	return View::make('hello');
});


Route::post('oauth/access_token', function()
{
    return AuthorizationServer::performAccessTokenFlow();
});


Route::get('/oauth/authorize', array('before' => 'check-authorization-params|auth', function()
{
    // get the data from the check-authorization-params filter
    $params = Session::get('authorize-params');

    // get the user id
    $params['user_id'] = Auth::user()->id;

    // display the authorization form
    return View::make('authorization-form', array('params' => $params));
}));


Route::post('/oauth/authorize', array('before' => 'check-authorization-params|auth|csrf', function()
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
}));