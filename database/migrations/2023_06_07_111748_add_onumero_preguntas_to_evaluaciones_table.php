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
        Schema::table('r12evaluaciones', function (Blueprint $table) {
            $table->integer('onumero_preguntas')->default('10')->nullable()->after('ointentos_max');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('r12evaluaciones', function (Blueprint $table) {
            $table->dropColumn('onumero_preguntas');
        });
    }
};
