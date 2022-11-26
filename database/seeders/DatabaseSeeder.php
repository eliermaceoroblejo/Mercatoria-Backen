<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $this->call([
            AccountTypesSeeder::class,
            AccountNatureSeeder::class,
            CurrenciesSeeder::class,
            UserSeeder::class,
            AccountGroupsSeeder::class,
            MovementTypesSeeder::class
        ]);
        // $this->call(AccountTypesSeeder::class);
        // $this->call(AccountNatureSeeder::class);
        // $this->call(CurrenciesSeeder::class);
        // $this->call(UserSeeder::class);
        // $this->call(AccountGroupsSeeder::class);
    }
}
