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
