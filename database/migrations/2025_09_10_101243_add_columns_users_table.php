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
            $table->string('istatus')->default('A')->after('remember_token');
            $table->string('iusrins')->nullable()->default('DBA')->after('istatus');
            $table->string('iusrmod')->nullable()->after('iusrins');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('r12users', function (Blueprint $table) {
            $table->dropColumn('istatus');
            $table->dropColumn('iusrins');
            $table->dropColumn('iusrmod');
        });
    }
};
