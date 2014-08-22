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
