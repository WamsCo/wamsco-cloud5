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
        Schema::create('tiers', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('raison_sociale')->nullable();
            $table->string('type_tiers');
            $table->tinyInteger('etat');
            $table->string('adresse')->nullable();
            $table->string('code_postal')->nullable();
            $table->string('ville')->nullable();
            $table->string('pays');
            $table->string('telephone');
            $table->string('email')->nullable();
            $table->string('site_web')->nullable();
            $table->string('commercial_charge')->nullable();
            $table->string('sexe')->nullable();
            $table->string('logo')->nullable();
            $table->string('activite_tier')->nullable();
            $table->string('date_debut')->nullable();
            $table->string('produit')->nullable();
            $table->string('quantite')->nullable();
            $table->string('periode_essai')->nullable();
            $table->string('validation')->nullable();
            $table->string('paiement')->nullable();
            $table->text('observation')->nullable();
            $table->double('nombre_point');
            $table->double('retrait_point');
            $table->double('objectif_point');
            $table->string('societe');
            $table->string('nom_user');
            $table->integer('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tiers');
    }
};
