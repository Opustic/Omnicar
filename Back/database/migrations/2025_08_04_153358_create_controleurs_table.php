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
        Schema::create('controleurs', function (Blueprint $table) {
            
            $table->id();

            $table->unsignedBigInteger("equipe_id")->unique()->nullable();  //clé étrangère de la table équipe
            $table->unsignedBigInteger("employe_id")->unique();


            $table->string("nom");
            $table->string("telephone");
            $table->text("adresse");
            $table->string("email")->nullable();
            $table->string("photo");
            $table->timestamps();


            // Déclaration de la clé étrangère
            $table->foreign("equipe_id")->references("id")->on("equipes")->onDelete("set null");
            $table->foreign("employe_id")->references("id")->on("employes");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('controleurs');
    }
};
