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
