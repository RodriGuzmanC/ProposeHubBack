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
            $table->foreignId('id_cliente');
            $table->foreignId('id_organizacion');
            $table->string('titulo'); // Campo 'titulo' con varchar(255)
            $table->decimal('monto', 10, 2)->nullable(); // Campo 'monto' que permite null
            $table->foreignId('id_estado');
            $table->foreignId('id_plantilla');
            $table->foreignId('id_servicio');
            $table->text('informacion')->nullable(); // Campo 'informacion' que permite null
            $table->timestamp('fecha_creacion')->default(DB::raw('CURRENT_TIMESTAMP')); // Campo 'fecha_creacion'
            $table->timestamps(); // Agrega created_at y updated_at
            $table->foreignId('id_usuario');
            $table->integer('version_publicada')->nullable(); // Campo 'version_publicada' que permite null
            $table->mediumText('html')->nullable(); // Campo 'html' que permite null
            $table->mediumText('css')->nullable(); // Campo 'css' que permite null
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
