<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlantillasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plantillas', function (Blueprint $table) {
            $table->id(); // Campo 'id' autoincremental
            $table->string('nombre'); // Campo 'nombre' con varchar(255)
            $table->text('contenido')->nullable(); // Campo 'contenido' que permite null
            $table->text('descripcion')->nullable(); // Campo 'descripcion' que permite null
            $table->boolean('is_active')->default(1); // Campo 'is_active' que por defecto es 1
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
        Schema::dropIfExists('plantillas');
    }
}
