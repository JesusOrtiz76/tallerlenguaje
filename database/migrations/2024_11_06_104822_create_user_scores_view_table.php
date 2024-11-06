<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("DROP VIEW IF EXISTS r12user_scores_view;");

        DB::statement("
            CREATE VIEW r12user_scores_view AS
            WITH total_questions AS (
                SELECT
                    c.id AS curso_id,
                    SUM(e.onumero_preguntas) AS total_answers
                FROM
                    r12evaluaciones e
                        INNER JOIN
                    r12modulos m ON e.modulo_id = m.id
                        INNER JOIN
                    r12cursos c ON m.curso_id = c.id
                GROUP BY
                    c.id
            )
            SELECT
                c.id AS curso_id,
                r.user_id,
                tq.total_answers,
                COALESCE(SUM(op.oes_correcta), 0) AS correct_answers,
                (COALESCE(SUM(op.oes_correcta), 0) / NULLIF(tq.total_answers, 0)) * 100 AS score_percentage
            FROM
                r12resultados r
                    LEFT JOIN LATERAL (
                    SELECT jt.position_id
                    FROM JSON_TABLE(IFNULL(r.orespuestas, '[]'), '$[*]' COLUMNS (position_id INT PATH '$')) AS jt
                    ) AS response ON TRUE
                    LEFT JOIN r12opciones op ON response.position_id = op.id
                    LEFT JOIN r12preguntas p ON op.pregunta_id = p.id
                    LEFT JOIN r12evaluaciones e ON p.evaluacion_id = e.id
                    LEFT JOIN r12modulos m ON e.modulo_id = m.id
                    LEFT JOIN r12cursos c ON m.curso_id = c.id
                    LEFT JOIN total_questions tq ON tq.curso_id = c.id
            GROUP BY
                r.user_id, c.id, tq.total_answers
            HAVING
                COUNT(op.id) = tq.total_answers
            ORDER BY
                c.id, r.user_id ASC;"
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
