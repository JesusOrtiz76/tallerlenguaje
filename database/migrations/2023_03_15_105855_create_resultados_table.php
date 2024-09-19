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
        Schema::create('r12resultados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('evaluacion_id');
            $table->json('orespuestas');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('r12users')->onDelete('cascade');
            $table->foreign('evaluacion_id')->references('id')->on('r12evaluaciones')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('r12resultados');
    }
};
