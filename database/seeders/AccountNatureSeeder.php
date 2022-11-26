<?php

namespace Database\Seeders;

use App\Models\AccountNature;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountNatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $accountNature = new AccountNature();
        $accountNature->name = 'Deudora';
        $accountNature->save();
        
        $accountNature1 = new AccountNature();
        $accountNature1->name = 'Acreedora';
        $accountNature1->save();
    }
}
