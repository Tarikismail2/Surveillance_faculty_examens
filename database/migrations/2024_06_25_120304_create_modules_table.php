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
            $table->unsignedBigInteger('id_department');
            $table->foreign('id_department')->references('id_department')->on('departments')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('modules');
    }
}
