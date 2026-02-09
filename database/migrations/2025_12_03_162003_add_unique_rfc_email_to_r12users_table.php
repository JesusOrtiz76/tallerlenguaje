<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    private function indexExists(string $table, string $index): bool
    {
        $db = DB::connection()->getDatabaseName();

        $row = DB::selectOne("
            SELECT COUNT(1) AS c
            FROM information_schema.statistics
            WHERE table_schema = ?
              AND table_name   = ?
              AND index_name   = ?
        ", [$db, $table, $index]);

        return (int)($row->c ?? 0) > 0;
    }

    public function up(): void
    {
        // RFC unique (si no existe)
        if (! $this->indexExists('r12users', 'r12users_orfc_unique')) {
            Schema::table('r12users', function (Blueprint $table) {
                $table->unique('orfc', 'r12users_orfc_unique');
            });
        }

        // Email unique (si no existe)
        if (! $this->indexExists('r12users', 'r12users_email_unique')) {
            Schema::table('r12users', function (Blueprint $table) {
                $table->unique('email', 'r12users_email_unique');
            });
        }
    }

    public function down(): void
    {
        if ($this->indexExists('r12users', 'r12users_orfc_unique')) {
            Schema::table('r12users', function (Blueprint $table) {
                $table->dropUnique('r12users_orfc_unique');
            });
        }

        if ($this->indexExists('r12users', 'r12users_email_unique')) {
            Schema::table('r12users', function (Blueprint $table) {
                $table->dropUnique('r12users_email_unique');
            });
        }
    }
};
