<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('recuperacion_contrasenas', function (Blueprint $table) {
            $table->id();  // Crea un campo 'id' auto-incremental
            $table->unsignedBigInteger('id_usuario');  // Referencia al 'id' de la tabla 'usuarios'
            $table->string('token', 255)->unique();  // Campo 'token' con restricción 'unique'
            $table->timestamps();  // Crea los campos 'created_at' y 'updated_at'

            // Definir la clave foránea
            $table->foreign('id_usuario')
                  ->references('id')
                  ->on('usuarios')
                  ->onDelete('cascade');  // Eliminar los tokens si el usuario se elimina
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recuperacion_contrasenas');
    }
};
