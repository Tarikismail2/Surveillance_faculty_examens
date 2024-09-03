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
        Schema::create('filiere_gp', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('version_etape');
            $table->string('code_etape');
            $table->unsignedBigInteger('id_module');
            $table->unsignedBigInteger('id_session');
            $table->timestamps();

            $table->foreign('code_etape')->references('code_etape')->on('filieres')->onDelete('cascade');
            $table->foreign('id_module')->references('id')->on('modules')->onDelete('cascade');
            $table->foreign('id_session')->references('id')->on('session_exams')->onDelete('cascade');
       
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filiere_gp');
    }
};