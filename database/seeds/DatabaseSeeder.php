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
            'username' => 'admin',
            'password' => bcrypt('123456'),
        ]);
        // $this->call(UserSeeder::class);

        DB::table('specialties')->insert([
            'name'=>'Infectious diseases',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('days')->insert([
            'name'=>'Monday',
        ]);
        DB::table('days')->insert([
            'name'=>'Tuesday',
        ]);
        DB::table('days')->insert([
            'name'=>'Wednesday',
        ]);
        DB::table('days')->insert([
            'name'=>'Thursday',
        ]);
        DB::table('days')->insert([
            'name'=>'Friday',
        ]);
        DB::table('days')->insert([
            'name'=>'Saturday',
        ]);
        DB::table('days')->insert([
            'name'=>'Sunday',
        ]);
    }
}