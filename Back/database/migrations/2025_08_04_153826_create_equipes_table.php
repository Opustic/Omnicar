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
        Schema::create('equipes', function (Blueprint $table) {

            $table->id();


            // récupérer l'id de l'équipe
            $table->unsignedBigInteger("vehicule_id")->nullable()->unique();
            $table->timestamps();



            // déclaration des clés étrangères
            $table->foreign("vehicule_id")->references("id")->on("vehicules");

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipes');
    }
};
