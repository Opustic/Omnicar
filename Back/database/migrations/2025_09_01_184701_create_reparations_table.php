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
        Schema::create('reparations', function (Blueprint $table) {

            // attributs de la table
            $table->id();


            $table->unsignedBigInteger("vehicule_id");
            $table->unsignedBigInteger("mecanicien_id");

            $table->decimal("cout", 10, 2);
            $table->decimal("main_doeuvre", 10, 20);
            
            $table->text("description");
            $table->string("statut");


            // clés étrangères
            $table->foreign("vehicule_id")->references("id")->on("vehicules");
            $table->foreign("mecanicien_id")->references("id")->on("mecaniciens");



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reparations');
    }
};
