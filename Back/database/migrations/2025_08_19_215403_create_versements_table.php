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
        Schema::create('versements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("vehicule_id")->nullable();
            $table->unsignedBigInteger("employe_id");
            $table->enum("role", ["chauffeur", "controleur"]);
            $table->decimal("montant", 10, 2);
            $table->timestamps();



            // clés étrangères
            $table->foreign("vehicule_id")->references("id")->on("vehicules")->onDelete("set null");


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('versements');
    }
};
