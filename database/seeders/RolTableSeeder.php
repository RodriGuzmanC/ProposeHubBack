<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rol')->insert([
            ['id' => 1, 'nombre' => 'Admin', 'descripcion' => 'Rol para administradores'],
            ['id' => 2, 'nombre' => 'Empleado', 'descripcion' => 'Rol para empleados'],
        ]);
    }
}

