<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('r12evaluaciones', function (Blueprint $table) {
            $table->foreignId('tema_id')
                ->nullable()
                ->after('modulo_id')
                ->constrained('r12temas')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('r12evaluaciones', function (Blueprint $table) {
            $table->dropForeign(['tema_id']);
            $table->dropColumn('tema_id');
        });
    }
};
