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
            DROP TRIGGER IF EXISTS before_insert_versiones_propuestas;
            CREATE TRIGGER before_insert_versiones_propuestas
            BEFORE INSERT ON versiones_propuestas
            FOR EACH ROW
            BEGIN
                -- Declarar una variable para contar los registros con el mismo "propuesta_id"
                DECLARE version_count INT;

                -- Contar cuántos registros existen con el mismo "id_propuesta"
                SELECT COUNT(*) INTO version_count
                FROM versiones_propuestas
                WHERE id_propuesta = NEW.id_propuesta;

                -- Si no existen registros, asigna el valor "1" a version_numero
                IF version_count = 0 THEN
                    SET NEW.version_numero = 1;
                ELSE
                    -- Si existen registros, asigna el siguiente número de versión
                    SELECT MAX(version_numero) + 1 INTO @next_version
                    FROM versiones_propuestas
                    WHERE id_propuesta = NEW.id_propuesta;
                    
                    SET NEW.version_numero = @next_version;
                END IF;
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
        // Eliminar el trigger si la migración se revierte
    }
};
