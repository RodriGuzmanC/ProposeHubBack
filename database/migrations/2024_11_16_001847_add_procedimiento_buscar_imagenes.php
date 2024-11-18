<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Definir el procedimiento almacenado
        $sql = "
        DROP PROCEDURE IF EXISTS buscar_imagenes;
CREATE PROCEDURE buscar_imagenes(IN nombre_buscar VARCHAR(255))
BEGIN
    -- Buscar coincidencias en las columnas 'path' y 'nombre'
    SELECT * 
    FROM imagenes
    WHERE path LIKE CONCAT('%', nombre_buscar, '%') COLLATE utf8mb4_unicode_ci
       OR nombre LIKE CONCAT('%', nombre_buscar, '%') COLLATE utf8mb4_unicode_ci;
END;
";

        // Ejecutar el SQL para crear el procedimiento almacenado
        DB::unprepared($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Eliminar el procedimiento almacenado
    }
};
