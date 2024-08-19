<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModulesTable extends Migration
{
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('code_elp');
            $table->string('lib_elp');
            $table->string('version_etape');
            $table->string('code_etape');
            $table->unsignedBigInteger('id_filiere')->nullable();
            $table->unsignedBigInteger('id_session');
            $table->foreign('id_filiere')->references('id')->on('filieres')->onDelete('cascade');
            $table->foreign('id_session')->references('id')->on('session_exams')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('modules');
    }
}
