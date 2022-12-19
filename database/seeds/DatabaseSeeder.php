<?php

use App\Models\Admin;
use App\Models\ZoomToken;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Admin::truncate();

        Admin::create([
            'name' => 'super Admin',
            'email' => 'super_admin@quichealth.ng',
            'password' => bcrypt('123456'),
        ]);
        // $this->call(UserSeeder::class);

        ZoomToken::factory()->create();
        // Doctor::factory(10)->create();
    }
}