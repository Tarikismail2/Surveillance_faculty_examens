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
            $table->string('code_etape'); // Assurez-vous que cette colonne existe
            $table->unsignedBigInteger('id_session');
            $table->foreign('code_etape')->references('code_etape')->on('filieres')->onDelete('cascade');
            $table->foreign('id_session')->references('id')->on('session_exams')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->dropForeign(['code_etape']);
            $table->dropForeign(['id_session']);
        });

        Schema::dropIfExists('modules');
    }
}
