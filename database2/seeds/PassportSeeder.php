<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PassportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('oauth_clients')->insert([
            [
                'name' => config('app.name') . ' Personal Access Client',
                'secret' => 'bB3AtuAV10t3sZIS6Z7OQm7MBBXqBkJN1tbQKjmA',
                'redirect' => 'http://localhost',
                'personal_access_client' => 1,
                'password_client' => 0,
                'revoked' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => config('app.name') . ' Password Grant Client',
                'secret' => 'twuq6czARQXNX2gYosgMpOQlBpumydKGqcjdq4xx',
                'redirect' => 'http://localhost',
                'personal_access_client' => 0,
                'password_client' => 1,
                'revoked' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);

        Artisan::call('passport:keys');
    }
}
