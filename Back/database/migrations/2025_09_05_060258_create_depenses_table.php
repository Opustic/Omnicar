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
        Schema::create('depenses', function (Blueprint $table) {
            $table->id();


            $table->enum("categorie", [
                'entretien',
                'reparation',
                'assurance',
                'taxe',
                'controle_technique',
                'amende',
                'salaire',
                'prime',
                'divers'
            ]);

            $table->decimal("montant", 12, 2);

            $table->unsignedBigInteger("vehicule_id")->nullable();
            $table->unsignedBigInteger("employe_id")->nullable();


            $table->foreign("vehicule_id")->references("id")->on("vehicules");
            $table->foreign("employe_id")->references("id")->on("employes");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('depenses');
    }
};
