<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizacionesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('organizaciones')->insert([
            [
                'id' => 1,
                'nombre' => 'Loopsy Peru',
                'telefono' => '948948948',
                'correo' => 'loopsyperu@gmail.com',
                'created_at' => '2024-10-29 09:19:31',
                'updated_at' => '2024-10-29 09:31:55',
            ],
        ]);
    }
}
