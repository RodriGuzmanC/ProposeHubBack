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
            $table->foreignId('id_cliente')->nullable();
            $table->foreignId('id_organizacion');
            $table->string('titulo'); // Campo 'titulo' con varchar(255)
            $table->decimal('monto', 10, 2)->nullable(); // Campo 'monto' que permite null
            $table->foreignId('id_estado')->nullable();
            $table->foreignId('id_plantilla')->nullable();
            $table->foreignId('id_servicio')->nullable();
            $table->text('informacion')->nullable(); // Campo 'informacion' que permite null
            $table->timestamp('fecha_creacion')->default(DB::raw('CURRENT_TIMESTAMP')); // Campo 'fecha_creacion'
            $table->timestamps(); // Agrega created_at y updated_at
            $table->foreignId('id_usuario')->nullable();
            $table->integer('version_publicada')->nullable(); // Campo 'version_publicada' que permite null
            $table->mediumText('html')->nullable(); // Campo 'html' que permite null
            $table->mediumText('css')->nullable(); // Campo 'css' que permite null

            // Relación con la tabla 'clientes'
            $table->foreign('id_cliente')
                ->references('id') // Columna 'id' en la tabla 'clientes'
                ->on('clientes')
                ->onDelete('set null');

            // Relación con la tabla 'organizaciones'
            $table->foreign('id_organizacion')
                ->references('id') // Columna 'id' en la tabla 'organizaciones'
                ->on('organizaciones')
                ->onDelete('cascade');

            // Relación con la tabla 'estados'
            $table->foreign('id_estado')
                ->references('id') // Columna 'id' en la tabla 'estados'
                ->on('estado_propuestas')
                ->onDelete('set null');

            // Relación con la tabla 'plantillas'
            $table->foreign('id_plantilla')
                ->references('id') // Columna 'id' en la tabla 'plantillas'
                ->on('plantillas')
                ->onDelete('set null');

            // Relación con la tabla 'servicios'
            $table->foreign('id_servicio')
                ->references('id') // Columna 'id' en la tabla 'servicios'
                ->on('servicios')
                ->onDelete('set null');

            // Relación con la tabla 'usuarios'
            $table->foreign('id_usuario')
                ->references('id') // Columna 'id' en la tabla 'usuarios'
                ->on('usuarios')
                ->onDelete('set null');
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
