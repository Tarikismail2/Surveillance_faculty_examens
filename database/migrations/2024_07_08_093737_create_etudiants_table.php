<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('etudiants', function (Blueprint $table) {
            $table->id();
            $table->string('code_etudiant')->unique();
            $table->string('nom');
            $table->string('prenom');
            $table->string('cin')->nullable(); 
            $table->string('cne')->nullable(); ;
            $table->date('date_naissance')->nullable(); ;
            $table->unsignedBigInteger('id_session');
            $table->foreign('id_session')->references('id')->on('session_exams')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('etudiants');
    }
};
