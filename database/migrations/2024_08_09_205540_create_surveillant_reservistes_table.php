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
        Schema::create('surveillant_reservistes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_enseignant');
            $table->date('date');
            $table->enum('demi_journee', ['matin', 'apres-midi']); // Add this line
            $table->boolean('affecte')->default(false);
            $table->timestamps();
        
            $table->foreign('id_enseignant')->references('id')->on('enseignants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surveillant_reservistes');
    }
};
