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
            $table->string('code_elp')->unique();
            $table->string('lib_elp');
            $table->string('version_etape');
            $table->string('code_etape');
            $table->unsignedBigInteger('id_filiere')->nullable();
            $table->foreign('id_filiere')->references('id')->on('filieres')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('modules');
    }
}
