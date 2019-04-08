<?php

use Illuminate\Database\Seeder;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::firstOrCreate([
            'email'   => 'hem@7span.com',
        ], [
            'role_id' => '1',
            'password'=>'1234567890',
            'first_name'=>'hem',
            'last_name'=>'7span'
        ]);
    }
}
