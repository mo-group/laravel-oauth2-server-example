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
