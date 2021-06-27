<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->insert([
            'username'=>'admin',
            'password'=>bcrypt('123456')
        ]);
        // $this->call(UserSeeder::class);
    }
}
