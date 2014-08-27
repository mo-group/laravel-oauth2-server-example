laravel-oauth2-server-example
=============================

[OAuth-Server-Laravel](https://github.com/lucadegasperi/oauth2-server-laravel)

laravel-oauth2-server-example

## 1. download th Laravel installer

    composer global require "laravel/installer=~1.1"
    
## 2. create new project

    laravel new example
    
## 3. add oauth2 library denpendency to `composer.json`

    "require": {
		"laravel/framework": "4.2.*",
		"zetacomponents/database": "1.4.6",
		"lucadegasperi/oauth2-server-laravel": "dev-master"
	},
	
## 4. update composer dependency

    composer update
    
## 5. add providers & aliases to `app/config/app.php`

providers :

	'LucaDegasperi\OAuth2Server\OAuth2ServerServiceProvider',
	
aliases :

	'AuthorizationServer' => 'LucaDegasperi\OAuth2Server\Facades\AuthorizationServerFacade',
	'ResourceServer' => 'LucaDegasperi\OAuth2Server\Facades\ResourceServerFacade',
	

## 6. config `app/config/database.php` to your setting

	'mysql' => array(
			'driver'    => 'mysql',
			'host'      => 'localhost',
			'database'  => 'test_db',
			'username'  => 'test_user',
			'password'  => '123456',
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => '',
		),
	

## 7. publish lib configuration

	php artisan config:publish lucadegasperi/oauth2-server-laravel

## 8. library migration

	php artisan migrate --package="lucadegasperi/oauth2-server-laravel"
	
## 9. add users table migration
	
	php artisan migrate:make create_users_table
	
2014_08_21_141820_create_users_table

	public function up()
	{

		Schema::create('users', function($table) {

		    $table->increments('id');

		    $table->string('email', 255);
		    $table->string('password', 60);

		    $table->string('first_name', 32);
		    $table->string('last_name', 32);

		    $table->timestamps();
		    $table->rememberToken();

		});

	}
	
## 10. run create table migration
	
	php artisan migrate	
	
## 11. add oauth models

models/OauthClient.php

	<?php

    class OauthClient extends Eloquent  {

	    /**
	     * The database table used by the model.
	     *
	     * @var string
	     */
	    protected $table = 'oauth_clients';

	    /**
	     * The attributes excluded from the model's JSON form.
	     *
	     * @var array
	     */
	    protected $hidden = [ 'secret' ];

    }


models/OauthScope.php

	<?php

    class OauthScope extends Eloquent  {

    	/**
    	 * The database table used by the model.
    	 *
    	 * @var string
    	 */
    	protected $table = 'oauth_scopes';

    	/**
    	 * The attributes excluded from the model's JSON form.
    	 *
    	 * @var array
    	 */
    	protected $hidden = [];

    }

## 12. add oauth seeding
	
database/seeds/DatabaseSeeder.php

	<?php

    class DatabaseSeeder extends Seeder {

    	/**
    	 * Run the database seeds.
    	 *
    	 * @return void
    	 */
    	public function run()
    	{
    		Eloquent::unguard();

    		$this->call('OAuthClientTableSeeder');
            $this->command->info('OAuthClient table seeded!');

            $this->call('OAuthScopeTableSeeder');
            $this->command->info('OAuthScope table seeded!');
    	}

    }
    
database/seeds/OAuthClientTableSeeder.php



database/seeds/OAuthScopeTableSeeder.php
	
	
## 13. routing

add authorize code routing

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
    
add get token routing

	Route::post('oauth/access_token', function()
	{
		return AuthorizationServer::performAccessTokenFlow();
	});
