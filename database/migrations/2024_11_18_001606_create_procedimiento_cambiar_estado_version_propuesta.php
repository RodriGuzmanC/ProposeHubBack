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
        DB::unprepared('
            DROP PROCEDURE IF EXISTS cambiar_estado_version_propuesta;
            -- Crear el procedimiento almacenado
            CREATE PROCEDURE cambiar_estado_version_propuesta(
                IN idPropuestaIn INT,
                IN idVersionIn INT
            )
            BEGIN
                -- Declarar un manejador de excepciones para errores SQL
                DECLARE EXIT HANDLER FOR SQLEXCEPTION
                BEGIN
                    -- Revertir la transacción si ocurre un error
                    ROLLBACK;
                END;

                -- Iniciar la transacción
                START TRANSACTION;

                -- Quitar el "en_edicion" de todas las versiones de la propuesta
                UPDATE `versiones_propuestas` 
                SET `en_edicion` = FALSE 
                WHERE `id_propuesta` = idPropuestaIn;

                -- Colocar el "en_edicion" a una sola versión específica
                UPDATE `versiones_propuestas` 
                SET `en_edicion` = TRUE 
                WHERE `id_propuesta` = idPropuestaIn 
                AND `id` = idVersionIn;

                -- Confirmar la transacción
                COMMIT;
            END;
        ');
    }

    /**
     * Revertir las migraciones.
     *
     * @return void
     */
    public function down()
    {
        // Eliminar el procedimiento si la migración se revierte
    }
};
