<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TokenRecuperacion extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('recuperacion_contrasenas')->insert([
            [
                'id' => 1,
                'id_usuario' => 1,
                'token' => Str::random(60)
            ],
        ]);
    }
}
