<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstadoPropuestasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estado_propuestas', function (Blueprint $table) {
            $table->id(); // Esto crea un campo 'id' autoincremental
            $table->string('nombre'); // Esto crea el campo 'nombre' con varchar(255)
            $table->timestamps(); // Esto agrega created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estado_propuestas');
    }
}
