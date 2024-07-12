<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('examen_salle_enseignant', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_examen');
            $table->unsignedBigInteger('id_salle');
            $table->unsignedBigInteger('id_enseignant');
            $table->timestamps();

            $table->foreign('id_examen')->references('id')->on('examens')->onDelete('cascade');
            $table->foreign('id_salle')->references('id')->on('salles')->onDelete('cascade');
            $table->foreign('id_enseignant')->references('id')->on('enseignants')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('examen_salle_enseignant');
    }
};
