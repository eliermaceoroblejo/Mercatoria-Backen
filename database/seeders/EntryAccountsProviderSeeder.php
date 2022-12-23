<?php

namespace Database\Seeders;

use App\Models\Bussiness;
use App\Models\EntryAccountsProviders;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EntryAccountsProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bussinesses = Bussiness::all();
        for ($i=0; $i < $bussinesses->count(); $i++) { 
            $entry = new EntryAccountsProviders();
            $entry->bussiness_id = $bussinesses[$i]->id;
            $entry->concept = 'IMPORTADORA';
            $entry->save();

            $entry1 = new EntryAccountsProviders();
            $entry1->bussiness_id = $bussinesses[$i]->id;
            $entry1->concept = 'GASTOS FINANCIEROS';
            $entry1->save();

            $entry2 = new EntryAccountsProviders();
            $entry2->bussiness_id = $bussinesses[$i]->id;
            $entry2->concept = 'TRANSPORTACIÓN';
            $entry2->save();

            $entry3 = new EntryAccountsProviders();
            $entry3->bussiness_id = $bussinesses[$i]->id;
            $entry3->concept = 'MANIPULACIÓN';
            $entry3->save();
        }
    }
}
