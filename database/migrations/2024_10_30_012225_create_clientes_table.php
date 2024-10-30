<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->increments('id'); // Cambiado a increments para crear un autoincremental
            $table->string('nombre');
            $table->string('correo')->nullable();
            $table->string('telefono', 20)->nullable();
            $table->unsignedInteger('id_organizacion')->nullable();
            $table->timestamps(0); // crea created_at y updated_at sin decimales
            $table->text('contrasena_hash')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clientes');
    }
}
