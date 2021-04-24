<?php

use Illuminate\Database\Seeder;
use \Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'firstname' => "Alejandro",
            'lastname' => "Becerra",
            'user' => "Bec",
            'email' => "admin@admin.com",
            'password'=> Hash::make('123456')
        ]);
    }
}
