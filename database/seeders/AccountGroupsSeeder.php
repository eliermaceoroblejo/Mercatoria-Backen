<?php

namespace Database\Seeders;

use App\Models\AccountGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountGroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $accountGroup0 = new AccountGroup();
        $accountGroup0->code = '0';
        $accountGroup0->name = 'Activos Fijos (Valor)';
        $accountGroup0->save();

        $accountGroup1 = new AccountGroup();
        $accountGroup1->code = '1';
        $accountGroup1->name = 'Gasto';
        $accountGroup1->save();

        $accountGroup2 = new AccountGroup();
        $accountGroup2->code = '2';
        $accountGroup2->name = 'Pagos';
        $accountGroup2->save();

        $accountGroup3 = new AccountGroup();
        $accountGroup3->code = '3';
        $accountGroup3->name = 'Otros';
        $accountGroup3->save();

        $accountGroup4 = new AccountGroup();
        $accountGroup4->code = '4';
        $accountGroup4->name = 'Inventario';
        $accountGroup4->save();

        $accountGroup5 = new AccountGroup();
        $accountGroup5->code = '5';
        $accountGroup5->name = 'Cuenta Puente';
        $accountGroup5->save();

        $accountGroup6 = new AccountGroup();
        $accountGroup6->code = '6';
        $accountGroup6->name = 'Ingresos';
        $accountGroup6->save();

        $accountGroup7 = new AccountGroup();
        $accountGroup7->code = '7';
        $accountGroup7->name = 'Resultados';
        $accountGroup7->save();

        $accountGroup8 = new AccountGroup();
        $accountGroup8->code = '8';
        $accountGroup8->name = 'Cuenta de Capital o Traspaso de Resultados';
        $accountGroup8->save();

        $accountGroup9 = new AccountGroup();
        $accountGroup9->code = '9';
        $accountGroup9->name = 'Efectivo en Banco';
        $accountGroup9->save();

        $accountGroupA = new AccountGroup();
        $accountGroupA->code = 'A';
        $accountGroupA->name = 'Efectivo en Caja';
        $accountGroupA->save();

        $accountGroupB = new AccountGroup();
        $accountGroupB->code = 'B';
        $accountGroupB->name = 'Efectos en Tramite de Pago';
        $accountGroupB->save();

        $accountGroupC = new AccountGroup();
        $accountGroupC->code = 'C';
        $accountGroupC->name = 'Efectos en Tramite de Cobro';
        $accountGroupC->save();

        $accountGroupD = new AccountGroup();
        $accountGroupD->code = 'D';
        $accountGroupD->name = 'Cobros';
        $accountGroupD->save();

        $accountGroupE = new AccountGroup();
        $accountGroupE->code = 'E';
        $accountGroupE->name = 'Costos';
        $accountGroupE->save();

        $accountGroupF = new AccountGroup();
        $accountGroupF->code = 'F';
        $accountGroupF->name = 'Inversiones';
        $accountGroupF->save();

        $accountGroupG = new AccountGroup();
        $accountGroupG->code = 'G';
        $accountGroupG->name = 'Cobros Anticipados';
        $accountGroupG->save();

        $accountGroupH = new AccountGroup();
        $accountGroupH->code = 'H';
        $accountGroupH->name = 'Pagos Anticipados';
        $accountGroupH->save();

        $accountGroupI = new AccountGroup();
        $accountGroupI->code = 'I';
        $accountGroupI->name = 'Transferencias';
        $accountGroupI->save();

        $accountGroupJ = new AccountGroup();
        $accountGroupJ->code = 'J';
        $accountGroupJ->name = 'Activos Fijos (Depreciación)';
        $accountGroupJ->save();

        $accountGroupK = new AccountGroup();
        $accountGroupK->code = 'K';
        $accountGroupK->name = 'Memo';
        $accountGroupK->save();

        $accountGroupL = new AccountGroup();
        $accountGroupL->code = 'L';
        $accountGroupL->name = 'Faltantes en Investigación';
        $accountGroupL->save();

        $accountGroupM = new AccountGroup();
        $accountGroupM->code = 'M';
        $accountGroupM->name = 'Sobrantes en Investigación';
        $accountGroupM->save();

        $accountGroupN = new AccountGroup();
        $accountGroupN->code = 'N';
        $accountGroupN->name = 'Contravalor por Pagar';
        $accountGroupN->save();

        $accountGroupO = new AccountGroup();
        $accountGroupO->code = 'O';
        $accountGroupO->name = 'Contravalor por Cobrar';
        $accountGroupO->save();

        $accountGroupP = new AccountGroup();
        $accountGroupP->code = 'P';
        $accountGroupP->name = 'Activos Fijos (Compras)';
        $accountGroupP->save();
    }
}
