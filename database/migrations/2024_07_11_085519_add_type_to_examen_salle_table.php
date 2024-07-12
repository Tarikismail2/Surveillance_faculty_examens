<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('examen_salle', function (Blueprint $table) {
        $table->string('type')->default('primary'); // Adjust the type definition as per your requirements
    });
}

public function down()
{
    Schema::table('examen_salle', function (Blueprint $table) {
        $table->dropColumn('type');
    });
}
};
