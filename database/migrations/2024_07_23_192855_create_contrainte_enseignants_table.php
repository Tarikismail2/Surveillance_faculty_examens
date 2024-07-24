<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContrainteEnseignantsTable extends Migration
{
    public function up()
    {
        Schema::create('contrainte_enseignants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_enseignant');
            $table->date('date');
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->boolean('validee')->default(false);
            $table->timestamps();

            $table->foreign('id_enseignant')->references('id')->on('enseignants')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('contraintes_enseignants');
    }
}
