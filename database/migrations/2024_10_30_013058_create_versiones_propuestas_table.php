<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Agrega esta línea

class CreateVersionesPropuestasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('versiones_propuestas', function (Blueprint $table) {
            $table->id(); // Campo 'id' autoincremental
            $table->foreignId('id_propuesta')->nullable(); // Campo 'id_propuesta' que permite null
            $table->integer('version_numero')->nullable(); // Campo 'version_numero' que permite null
            $table->text('contenido')->nullable(); // Campo 'contenido' que permite null
            $table->timestamp('fecha_creacion')->default(DB::raw('CURRENT_TIMESTAMP')); // Campo 'fecha_creacion'
            $table->boolean('generado_por_ia')->default(0); // Campo 'generado_por_ia' que por defecto es 0
            $table->boolean('en_edicion')->default(0); // Campo 'en_edicion' que por defecto es 0
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('versiones_propuestas');
    }
}
