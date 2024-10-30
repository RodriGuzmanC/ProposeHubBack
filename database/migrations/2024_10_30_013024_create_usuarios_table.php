<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Asegúrate de importar DB

class CreateUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id(); // Campo 'id' autoincremental
            $table->string('nombre'); // Campo 'nombre' con varchar(255)
            $table->string('correo')->unique(); // Campo 'correo' con varchar(255) y debe ser único
            $table->char('contrasena_hash', 60); // Campo 'contrasena_hash' con char(60)
            $table->foreignId('id_rol')->nullable(); // Campo 'id_rol' que permite null
            $table->timestamps(); // Agrega created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
}
