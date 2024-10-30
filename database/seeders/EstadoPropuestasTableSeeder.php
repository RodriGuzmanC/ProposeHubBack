<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoPropuestasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('estado_propuestas')->insert([
            ['id' => 1, 'nombre' => 'progreso'],
            ['id' => 2, 'nombre' => 'abierto'],
            ['id' => 3, 'nombre' => 'aceptado'],
            ['id' => 4, 'nombre' => 'declinado'],
        ]);
    }
}
