<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $catalog = new Module();
        $catalog->name = 'Catálogo';
        $catalog->save();

        $accountant = new Module();
        $accountant->name = 'Contabilidad';
        $accountant->save();

        $inventory = new Module();
        $inventory->name = 'Inventario';
        $inventory->save();
    }
}
