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
        DB::statement("DROP VIEW IF EXISTS r12cursos_users_detail_view;");

        DB::statement("
            CREATE VIEW r12cursos_users_detail_view AS
            SELECT
                c.id AS curso_id,
                u.id AS user_id,
                m.id AS modulo_id,
                m.onombre AS modulo_nombre,
                e.id AS evaluacion_id,
                e.onombre AS evaluacion_nombre,
                e.ointentos_max AS intentos_max,
                COALESCE(eu.ointentos, 0) AS intentos,
                e.onumero_preguntas AS num_preguntas,
                COALESCE((
                    SELECT COUNT(*)
                    FROM r12resultados r
                    CROSS JOIN JSON_TABLE(
                        r.orespuestas,
                        '$[*]' COLUMNS(
                            optionId INT PATH '$'
                        )
                    ) jt
                    JOIN r12opciones op ON op.id = jt.optionId
                    WHERE r.user_id = u.id
                      AND r.evaluacion_id = e.id
                      AND op.oes_correcta = 1
                ), 0) AS aciertos
            FROM
                r12cursos c
                LEFT JOIN r12inscripciones i ON c.id = i.curso_id
                LEFT JOIN r12users u ON i.user_id = u.id
                LEFT JOIN r12modulos m ON c.id = m.curso_id
                LEFT JOIN r12evaluaciones e ON m.id = e.modulo_id
                LEFT JOIN r12evaluacion_user eu ON e.id = eu.evaluacion_id
                    AND u.id = eu.user_id
            ORDER BY
                c.id, u.id, m.id, e.id;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Puedes definir la reversión de la migración aquí.
    }
};
