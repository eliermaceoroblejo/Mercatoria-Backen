<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->name = 'Elier';
        $user->email = 'eroblejo@vivaldi.net';
        $user->password = Hash::make('Macintosh95');
        $user->save();
    }
}
