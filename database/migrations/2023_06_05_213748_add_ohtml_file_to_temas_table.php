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
        Schema::table('r12temas', function (Blueprint $table) {
            $table->string('ohtml_file')->nullable()->after('otitulo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('r12temas', function (Blueprint $table) {
            $table->dropColumn('ohtml_file');
        });
    }
};
