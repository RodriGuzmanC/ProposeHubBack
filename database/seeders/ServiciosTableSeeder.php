<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiciosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('servicios')->insert([
            ['id' => 1, 'nombre' => 'Diseño Web', 'descripcion' => 'Creación de sitios web personalizados y responsivos.'],
            ['id' => 2, 'nombre' => 'Tienda Virtual', 'descripcion' => 'Desarrollo de plataformas de e-commerce para ventas en línea.'],
            ['id' => 3, 'nombre' => 'Aplicación Web', 'descripcion' => 'Desarrollo de aplicaciones web a medida para diversas necesidades.'],
        ]);
    }
}
