<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('filieres', function (Blueprint $table) {
            $table->string('code_etape')->primary();
            $table->string('version_etape');
            $table->unsignedBigInteger('id_session');
            $table->foreign('id_session')->references('id')->on('session_exams')->onDelete('cascade');            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('filieres');
    }
};
