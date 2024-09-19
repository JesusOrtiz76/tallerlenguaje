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
        Schema::create('r12temas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modulo_id')->constrained('r12modulos')->onDelete('cascade');
            $table->string('otitulo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('r12temas');
    }
};
