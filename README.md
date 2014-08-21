laravel-oauth2-server-example
=============================

laravel-oauth2-server-example

## 1. download th Laravel installer

    composer global require "laravel/installer=~1.1"
    
## 2. create new project

    laravel new example
    
## 3. add oauth2 lib denpendency to `composer.json`

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

## 8. lib migration

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
	
