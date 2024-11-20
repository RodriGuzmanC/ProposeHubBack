<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlantillasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plantillaInicial = file_get_contents(storage_path('app/plantilla_inicial.txt'));
        DB::table('plantillas')->insert([
            [
                'id' => 1,
                'nombre' => 'Plantilla de servicio de tienda virtual',
                'contenido' => $plantillaInicial,
                'descripcion' => 'plantilla especifica para servicios de tienda virtual',
            ],
        ]);
    }
}
