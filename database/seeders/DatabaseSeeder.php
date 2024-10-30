<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            EstadoPropuestasTableSeeder::class,
            OrganizacionesTableSeeder::class,
            RolTableSeeder::class,
            ServiciosTableSeeder::class,
            UsuariosTableSeeder::class,
            // Agrega más seeders aquí si es necesario
        ]);
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
