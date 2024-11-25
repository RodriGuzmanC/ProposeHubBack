<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizaciones', function (Blueprint $table) {
            $table->id(); // Campo 'id' autoincremental
            $table->string('nombre'); // Campo 'nombre' con varchar(255)
            $table->string('telefono', 20)->nullable(); // Campo 'telefono' con varchar(20) que permite null
            $table->string('correo')->nullable(); // Campo 'correo' con varchar(255) que permite null
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
        Schema::dropIfExists('organizaciones');
    }
}
