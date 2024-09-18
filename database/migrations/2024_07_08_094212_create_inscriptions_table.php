<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_etudiant');
            $table->unsignedBigInteger('id_module');
            $table->unsignedBigInteger('id_session');
            $table->timestamps();

            $table->foreign('id_session')->references('id')->on('session_exams')->onDelete('cascade');
            $table->foreign('id_etudiant')->references('id')->on('etudiants')->onDelete('cascade');
            $table->foreign('id_module')->references('id')->on('modules')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscriptions');
    }
};