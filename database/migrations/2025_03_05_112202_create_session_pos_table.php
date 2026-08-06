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
        Schema::create('session_pos', function (Blueprint $table) {
            $table->id();
            $table->string('session_id');
            $table->string('nom_point_vente');
            $table->string('date_ouverture');
            $table->string('date_fermeture')->nullable();
            $table->double('solde_initial');
            $table->double('solde_final');
            $table->double('solde_cloture_theorique');
            $table->string('statut');
            $table->integer('user_id');
            $table->string('nom_user');
            $table->string('societe');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_pos');
    }
};
