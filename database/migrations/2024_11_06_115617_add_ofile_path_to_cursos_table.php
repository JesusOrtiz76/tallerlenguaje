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
        Schema::table('r12cursos', function (Blueprint $table) {
            $table->string('ofile_path')->  after('oimg_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('r12cursos', function (Blueprint $table) {
            $table->dropColumn('ofile_path');
        });
    }
};
