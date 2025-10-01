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
        Schema::create('mecaniciens', function (Blueprint $table) {
            
            $table->id();

            $table->unsignedBigInteger("employe_id")->unique();

            $table->string("nom");
            $table->string("telephone");
            $table->text("adresse");
            $table->string("email")->nullable();
            $table->string("photo");


            $table->foreign("employe_id")->references("id")->on("employes");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mecaniciens');
    }
};
