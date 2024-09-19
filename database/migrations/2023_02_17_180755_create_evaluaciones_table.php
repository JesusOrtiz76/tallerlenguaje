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
        Schema::create('r12evaluaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('modulo_id');
            $table->string('onombre');
            $table->integer('otiempo_lim')->default(900); // 900 seconds = 15 minutes
            $table->integer('ointentos_max')->default(3);
            $table->timestamps();

            $table->foreign('modulo_id')->references('id')->on('r12modulos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('r12evaluaciones');
    }
};
