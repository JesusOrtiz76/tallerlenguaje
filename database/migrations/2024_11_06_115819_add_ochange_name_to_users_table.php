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
        Schema::table('r12users', function (Blueprint $table) {
            $table->boolean('ochange_name')->default(false)->after('orol');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('r12users', function (Blueprint $table) {
            $table->dropColumn('ochange_name');
        });
    }
};
