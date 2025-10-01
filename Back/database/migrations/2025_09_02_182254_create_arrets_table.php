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
        Schema::create('arrets', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger("vehicule_id");
            $table->string("motif");


            // clés étrangères
            $table->foreign("vehicule_id")->references("id")->on("vehicules")->onDelete("set null");
            

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arrets');
    }
};
