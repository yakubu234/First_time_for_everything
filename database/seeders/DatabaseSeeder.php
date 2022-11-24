<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
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
        Model::unguard();

        DB::beginTransaction();
        $this->call([
            UserSeeder::class,
            AddressSeeder::class,
            ProfileSeeder::class
        ]);

        DB::commit();

        Model::reguard();
    }
}
