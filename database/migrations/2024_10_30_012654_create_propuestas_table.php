<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Agrega esta línea

class CreatePropuestasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('propuestas', function (Blueprint $table) {
            $table->id(); // Campo 'id' autoincremental
            $table->foreignId('id_cliente')->nullable(); // Campo 'id_cliente' que permite null
            $table->foreignId('id_organizacion')->nullable(); // Campo 'id_organizacion' que permite null
            $table->string('titulo'); // Campo 'titulo' con varchar(255)
            $table->decimal('monto', 10, 2)->nullable(); // Campo 'monto' que permite null
            $table->foreignId('id_estado')->nullable(); // Campo 'id_estado' que permite null
            $table->foreignId('id_plantilla')->nullable(); // Campo 'id_plantilla' que permite null
            $table->foreignId('id_servicio')->nullable(); // Campo 'id_servicio' que permite null
            $table->text('informacion')->nullable(); // Campo 'informacion' que permite null
            $table->timestamp('fecha_creacion')->default(DB::raw('CURRENT_TIMESTAMP')); // Campo 'fecha_creacion'
            $table->timestamps(); // Agrega created_at y updated_at
            $table->foreignId('id_usuario')->nullable(); // Campo 'id_usuario' que permite null
            $table->integer('version_publicada')->nullable(); // Campo 'version_publicada' que permite null
            $table->text('html')->nullable(); // Campo 'html' que permite null
            $table->text('css')->nullable(); // Campo 'css' que permite null
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('propuestas');
    }
}
