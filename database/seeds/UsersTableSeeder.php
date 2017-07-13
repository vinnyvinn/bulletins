<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'first_name' => 'superuser',
            'username' => 'superuser',
            'email' => 'dev@wizag.biz',
            'password' => bcrypt(env('SUPER_PASSWORD', 'BOd23309J(!j~)@@1')),
            'permissions' => json_encode(['*'])
        ]);
    }
}
