laravel-oauth2-server-example
=============================

# Warning: In progress

for debug, set config `debug: true`



=============================

This project is a example of [OAuth-Server-Laravel](https://github.com/lucadegasperi/oauth2-server-laravel) package usage.

# Step by Step

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

## 12. add a hashGenerator



    <?php namespace HashGenerator;

    class HashGenerator {

        public static function generateNumber($length) {

            if ($length > 9) {
                return self::generateNumber(9) . self::generateNumber($length - 9);
            }


            $random_number = rand(0, pow(10, $length) - 1);

            return substr(str_repeat('0', $length - 1) . $random_number, -$length);

        }

        public static function generateNumberAlphabet($length) {

            $arr = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

            $hash = '';

            while ($length > 0) {
                $hash .= $arr[rand(0, 61)];
                $length--;
            }

            return $hash;

        }
    }


## 13. add oauth seeding

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

	<?php

    use HashGenerator\HashGenerator;

    class OAuthClientTableSeeder extends Seeder {

        public function run()
        {

            DB::table('oauth_clients')->delete();

            for ($i = 0; $i < 3; $i++) {

                OauthClient::create([
                    'id'     => HashGenerator::generateNumber(32),
                    'secret' => HashGenerator::generateNumberAlphabet(32),
                    'name'   => 'test_client_' . $i
                ]);
            }
        }

    }


database/seeds/OAuthScopeTableSeeder.php

	<?php

    class OAuthScopeTableSeeder extends Seeder {

        public function run()
        {

            DB::table('oauth_scopes')->delete();

            OauthScope::create([
                'scope'       => 'basic',
                'name'        => 'basic',
                'description' => 'basic'
            ]);

        }

    }


## 14. routing

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

## 15. add authorization-form view

app/views/authorization-form.php

	<form action="/oauth/authorize" method="post">
		<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
		<!-- foreach -->
		<input type="hidden" name="client_id" value="<?php echo $params['client_id']; ?>">
		<input type="hidden" name="redirect_uri" value="<?php echo $params['redirect_uri']; ?>">
		<input type="text" name="redirect_uri">
		<input type="password" name="password">
		<input type="submit" name="approve">
	</form>
