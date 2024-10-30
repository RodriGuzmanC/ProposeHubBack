<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsuariosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('usuarios')->insert([
            [
                'id' => 1,
                'nombre' => 'rodrigo',
                'correo' => 'eduis.guzman123@gmail.com',
                'contrasena_hash' => '$2y$12$JDKdCtdowIUWzu52TKE2t.SxLdiWD3lc0pefojEb3LgBru.WFALKe',
                'id_rol' => 2,
                'created_at' => '2024-10-29 17:25:41',
                'updated_at' => '2024-10-29 17:25:41',
            ],
        ]);
    }
}
