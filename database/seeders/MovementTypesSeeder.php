<?php

namespace Database\Seeders;

use App\Models\MovementType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MovementTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $movementType = new MovementType();
        $movementType->name = 'Compras';
        $movementType->in_out = true;
        $movementType->save();

        $movementType1 = new MovementType();
        $movementType1->name = 'Vales de Insumo';
        $movementType1->in_out = false;
        $movementType1->save();

        $movementType2 = new MovementType();
        $movementType2->name = 'Ventas';
        $movementType2->in_out = false;
        $movementType2->save();
    }
}
