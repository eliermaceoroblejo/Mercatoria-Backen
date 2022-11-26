<?php

namespace Database\Seeders;

use App\Models\AccountType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tipoCuenta = new AccountType();
        $tipoCuenta->name = 'Activos';
        $tipoCuenta->save();

        $tipoCuenta1 = new AccountType();
        $tipoCuenta1->name = 'Gastos';
        $tipoCuenta1->save();

        $tipoCuenta2 = new AccountType();
        $tipoCuenta2->name = 'Ingresos';
        $tipoCuenta2->save();

        $tipoCuenta3 = new AccountType();
        $tipoCuenta3->name = 'Pasivos';
        $tipoCuenta3->save();
        
        $tipoCuenta4 = new AccountType();
        $tipoCuenta4->name = 'Capital';
        $tipoCuenta4->save();
    }
}
