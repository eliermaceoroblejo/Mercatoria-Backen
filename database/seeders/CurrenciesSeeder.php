<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrenciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currencies = new Currency();
        $currencies->name = 'Peso Cubano';
        $currencies->abbreviation = 'CUP';
        $currencies->rate = '1';
        $currencies->save();
        
        $currencies1 = new Currency();
        $currencies1->name = 'Dolar Estadounidense';
        $currencies1->abbreviation = 'USD';
        $currencies1->rate = '24';
        $currencies1->save();
    }
}
