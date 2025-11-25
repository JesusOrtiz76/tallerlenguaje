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
            // Sexo: M / F, lo dejamos nullable para no romper usuarios existentes
            $table->string('sexo', 1)->nullable()->after('orfc');

            // Relación con unidad administrativa
            $table->unsignedBigInteger('unidadadministrativa_id')
                ->nullable()
                ->after('centrotrabajo_id');

            $table->foreign('unidadadministrativa_id')
                ->references('id')
                ->on('r12unidadadministrativa')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('r12users', function (Blueprint $table) {
            // Quitamos el dropForeign porque al no existir truena
            // $table->dropForeign(['unidadadministrativa_id']);

            // Primero borramos la columna de unidad administrativa
            if (Schema::hasColumn('r12users', 'unidadadministrativa_id')) {
                $table->dropColumn('unidadadministrativa_id');
            }

            // Luego la de sexo
            if (Schema::hasColumn('r12users', 'sexo')) {
                $table->dropColumn('sexo');
            }
        });
    }

};
