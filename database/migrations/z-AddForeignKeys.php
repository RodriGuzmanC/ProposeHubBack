<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Añadir la clave foránea 'id_organizacion' en la tabla 'clientes'
        Schema::table('clientes', function (Blueprint $table) {
            // Establecer la relación de clave foránea
            $table->foreign('id_organizacion')
                ->references('id') // Columna de la tabla 'organizaciones'
                ->on('organizaciones') // Tabla de referencia
                ->onDelete('cascade'); // Eliminar en cascada si la organización es eliminada
        });

        Schema::table('usuarios', function (Blueprint $table) {
            // Establecer la relación de clave foránea
            $table->foreign('id_rol')
                ->references('id') // Columna de la tabla 'organizaciones'
                ->on('rol') // Tabla de referencia
                ->onDelete('cascade'); // Eliminar en cascada si la organización es eliminada
        });

        Schema::table('versiones_propuestas', function (Blueprint $table) {
            // Establecer la relación de clave foránea
            $table->foreign('id_propuesta')
                ->references('id') // Columna de la tabla 'organizaciones'
                ->on('propuestas') // Tabla de referencia
                ->onDelete('cascade'); // Eliminar en cascada si la organización es eliminada
        });

        Schema::table('propuestas', function (Blueprint $table) {
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
        // Eliminar la clave foránea si revertimos la migración
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropForeign(['id_organizacion']);
        });

        // Puedes eliminar más claves foráneas aquí si es necesario.
    }
}
