<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCursosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('r10cursos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('onombre');
            $table->text('odescripcion');
            $table->string('oimg_path')->nullable();
            $table->date('ofecha_inicio');
            $table->date('ofecha_fin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('r10cursos');
    }
}
