<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->truncate();
        DB::table('users')->insert([
            'name' => 'eihwan',
            'email' => 'cloz2me@gmail.com',
            'password' => bcrypt(env('USER_PASSWORD', 'secret')),
        ]);
    }
}
