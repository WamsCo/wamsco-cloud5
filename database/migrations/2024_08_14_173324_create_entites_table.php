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
        Schema::create('entites', function (Blueprint $table) {
            $table->id();
            $table->double('solde');
            $table->string('enseigne');
            $table->string('societe_mere')->nullable();
            $table->string('raison_sociale')->nullable();
            $table->string('responsable_societe')->nullable();
            $table->string('pays')->nullable();
            $table->string('ville')->nullable();
            $table->string('adresse')->nullable();
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->string('registre_com')->nullable();
            $table->string('logo')->nullable();
            $table->tinyInteger('active');
            $table->tinyInteger('mod_pointe_vente');
            $table->tinyInteger('mod_administration');
            $table->tinyInteger('mod_gestion_tier');
            $table->tinyInteger('mod_gestion_stock');
            $table->tinyInteger('mod_fabrication');
            $table->tinyInteger('mod_pressing');
            $table->tinyInteger('mod_facturation');
            $table->tinyInteger('mod_caisse');
            $table->tinyInteger('mod_multisociete');
            $table->tinyInteger('mod_gestion_employe');
            $table->string('validite_mod');
            $table->string('jour_restant');
            $table->double('nombre_users');
            $table->double('nbre_user_max');
            $table->integer('nombre_societe');
            $table->double('montant_paye');
            $table->string('periode')->nullable();
            $table->string('recommandation')->nullable();
            $table->double('taux_commission')->nullable();
            $table->double('montant_commission')->nullable();
            $table->string('etat_commission')->nullable();
            $table->string('logo_fact_entete')->nullable();
            $table->string('logo_fact_vertical')->nullable();
            $table->string('logo_fact_pied')->nullable();
            $table->string('condition_vente')->nullable();
            $table->tinyInteger('activer_fidelite')->nullable();
            $table->double('montant_point')->nullable();
            $table->double('objectif_point')->nullable();
            $table->string('nom_user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entites');
    }
};
