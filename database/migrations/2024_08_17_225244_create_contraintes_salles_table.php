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
        Schema::create('contrainte_salles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_salle');
            $table->unsignedBigInteger('id_session');
            $table->date('date');
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->boolean('validee')->default(false);

            $table->foreign('id_salle')->references('id')->on('salles')->onDelete('cascade');
            $table->foreign('id_session')->references('id')->on('session_exams')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contrainte_salles');
    }
};
