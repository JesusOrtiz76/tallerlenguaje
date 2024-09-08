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
        Schema::table('r10users', function (Blueprint $table) {
            $table->unsignedBigInteger('centrotrabajo_id')->nullable()->after('id');

            $table->foreign('centrotrabajo_id')->references('id')->on('r10centrostrabajo')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('r10users', function (Blueprint $table) {
            $table->dropForeign(['centrotrabajo_id']);

            $table->dropColumn('centrotrabajo_id');
        });
    }
};
