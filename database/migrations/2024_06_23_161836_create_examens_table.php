<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamensTable extends Migration
{
    public function up()
    {
        Schema::create('examens', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->unsignedBigInteger('id_module');
            $table->unsignedBigInteger('id_salle');
            $table->unsignedBigInteger('id_personne');
            $table->foreign('id_module')->references('id')->on('modules')->onDelete('cascade');
            $table->foreign('id_salle')->references('id')->on('salles')->onDelete('cascade');
            $table->foreign('id_personne')->references('id')->on('personnes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('examens');
    }
}
