<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('DROP VIEW IF EXISTS r12user_scores_view');

        DB::statement(<<<SQL
            CREATE VIEW r12user_scores_view AS
            WITH questions_totals AS (
                SELECT
                    c.id AS curso_id,
                    COUNT(p.id) AS total_answers,
                    SUM(CASE WHEN e.id <> 15 THEN 1 ELSE 0 END) AS total_answers_exercises,
                    SUM(CASE WHEN e.id  = 15 THEN 1 ELSE 0 END) AS total_answers_final
                FROM r12preguntas p
                INNER JOIN r12evaluaciones e ON p.evaluacion_id = e.id
                INNER JOIN r12modulos     m ON e.modulo_id      = m.id
                INNER JOIN r12cursos      c ON m.curso_id       = c.id
                GROUP BY c.id
            ),
            raw_answers AS (
                SELECT
                    c.id AS curso_id,
                    r.user_id,
                    COUNT(DISTINCT p.id) AS answer_count,
                    SUM(CASE WHEN e.id <> 15 THEN op.oes_correcta ELSE 0 END) AS correct_exercises,
                    SUM(CASE WHEN e.id  = 15 THEN op.oes_correcta ELSE 0 END) AS correct_final
                FROM r12resultados r
                JOIN JSON_TABLE(
                        IFNULL(r.orespuestas, '[]'),
                        '$[*]' COLUMNS (position_id INT PATH '$')
                    ) AS jt
                    ON TRUE
                LEFT JOIN r12opciones    op ON jt.position_id = op.id
                LEFT JOIN r12preguntas   p  ON op.pregunta_id = p.id
                LEFT JOIN r12evaluaciones e ON p.evaluacion_id = e.id
                LEFT JOIN r12modulos     m  ON e.modulo_id = m.id
                LEFT JOIN r12cursos      c  ON m.curso_id = c.id
                GROUP BY c.id, r.user_id
            )
            SELECT
                qt.curso_id,
                ra.user_id,
                qt.total_answers,
                (COALESCE(ra.correct_exercises, 0) + COALESCE(ra.correct_final, 0)) AS correct_answers,
                (
                    (
                        COALESCE(ra.correct_exercises, 0) /
                        NULLIF(qt.total_answers_exercises, 0)
                    ) * 10 * 0.3
                    +
                    (
                        COALESCE(ra.correct_final, 0) /
                        NULLIF(qt.total_answers_final, 0)
                    ) * 10 * 0.7
                ) * 10 AS score_percentage
            FROM raw_answers ra
            INNER JOIN questions_totals qt
                ON qt.curso_id = ra.curso_id
            WHERE
                qt.total_answers_exercises > 0
                AND qt.total_answers_final > 0
                AND ra.answer_count = qt.total_answers
            ORDER BY
                qt.curso_id,
                ra.user_id;
        SQL);
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS r12user_scores_view');
        // Opcional: re-crear aquí la versión anterior si algún día la necesitas.
    }
};
