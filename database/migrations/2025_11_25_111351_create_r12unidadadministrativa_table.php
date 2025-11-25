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
    public function up(): void
    {
        Schema::create('r12unidadadministrativa', function (Blueprint $table) {
            $table->id();
            $table->string('onombre', 191);
            $table->timestamps();
        });

        // Catálogo inicial de Unidades Administrativas
        DB::table('r12unidadadministrativa')->insert([
            ['onombre' => 'Dirección General', 'created_at' => now(), 'updated_at' => now()],
            ['onombre' => 'Órgano Interno de Control', 'created_at' => now(), 'updated_at' => now()],
            ['onombre' => 'Unidad de Modernización para la Calidad del Servicio', 'created_at' => now(), 'updated_at' => now()],
            ['onombre' => 'Unidad de Asuntos Jurídicos e Igualdad de Género', 'created_at' => now(), 'updated_at' => now()],
            ['onombre' => 'Unidad de Comunicación Social', 'created_at' => now(), 'updated_at' => now()],
            ['onombre' => 'Unidad del Sistema para la Carrera de las Maestras y Maestros', 'created_at' => now(), 'updated_at' => now()],
            ['onombre' => 'Consejo Directivo', 'created_at' => now(), 'updated_at' => now()],
            ['onombre' => 'Dirección de Planeación y Evaluación', 'created_at' => now(), 'updated_at' => now()],
            ['onombre' => 'Secretaría Técnica de la Junta de Gobierno de Educación Superior y Educación Continua', 'created_at' => now(), 'updated_at' => now()],
            ['onombre' => 'Coordinación Académica y de Operación Educativa', 'created_at' => now(), 'updated_at' => now()],
            ['onombre' => 'Coordinación de Administración y Finanzas', 'created_at' => now(), 'updated_at' => now()],
            ['onombre' => 'Dirección de Educación Elemental', 'created_at' => now(), 'updated_at' => now()],
            ['onombre' => 'Dirección de Educación Secundaria y Servicios de Apoyo', 'created_at' => now(), 'updated_at' => now()],
            ['onombre' => 'Dirección de Preparatoria Abierta', 'created_at' => now(), 'updated_at' => now()],
            ['onombre' => 'Dirección de Educación Superior', 'created_at' => now(), 'updated_at' => now()],
            ['onombre' => 'Dirección de Servicios Regionalizados', 'created_at' => now(), 'updated_at' => now()],
            ['onombre' => 'Dirección de Instalaciones Educativas', 'created_at' => now(), 'updated_at' => now()],
            ['onombre' => 'Dirección de Administración y Desarrollo de Personal', 'created_at' => now(), 'updated_at' => now()],
            ['onombre' => 'Dirección de Recursos Materiales y Financieros', 'created_at' => now(), 'updated_at' => now()],
            ['onombre' => 'Dirección de Informática y Telecomunicaciones', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('r12unidadadministrativa');
    }
};
