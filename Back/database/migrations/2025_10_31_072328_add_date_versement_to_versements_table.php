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
        Schema::table('versements', function (Blueprint $table) {
            //
            $table->date('date_versement')->nullable()->after('montant');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('versements', function (Blueprint $table) {
            //
            $table->dropColumn('date_versement');
        });
    }
};
