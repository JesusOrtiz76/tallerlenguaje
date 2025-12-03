<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('r12users', function (Blueprint $table) {
            // Índice único para RFC
            $table->unique('orfc', 'r12users_orfc_unique');

            // Índice único para email
            $table->unique('email', 'r12users_email_unique');
        });
    }

    public function down(): void
    {
        Schema::table('r12users', function (Blueprint $table) {
            $table->dropUnique('r12users_orfc_unique');
            $table->dropUnique('r12users_email_unique');
        });
    }
};
