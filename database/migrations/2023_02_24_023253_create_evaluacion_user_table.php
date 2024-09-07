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
        Schema::create('r10evaluacion_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('evaluacion_id');
            $table->integer('ointentos')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('r10users')->onDelete('cascade');
            $table->foreign('evaluacion_id')->references('id')->on('r10evaluaciones')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('r10evaluacion_user');
    }
};
