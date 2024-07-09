<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('examen_salle', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_examen');
            $table->foreign('id_examen')->references('id')->on('examens')->onDelete('cascade');
            $table->unsignedBigInteger('id_salle');
            $table->foreign('id_salle')->references('id')->on('salles')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('examen_salle');
    }
};


