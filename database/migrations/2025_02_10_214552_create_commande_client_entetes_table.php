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
        Schema::create('commande_client_entetes', function (Blueprint $table) {
            $table->id();
            $table->string('code_commande');
            $table->string('code_facture')->nullable();
            $table->integer('id_facture_client_entete')->nullable();
            $table->string('nom_client')->nullable();
            $table->integer('id_client');
            $table->string('date_commande');
            $table->string('date_livraison');
            $table->string('mode_reglement');
            $table->string('condition_reglement')->nullable();
            $table->string('note')->nullable();
            $table->double('montant_ht');
            $table->double('montant_remise');
            $table->double('montant_tva');
            $table->double('montant_precompte');
            $table->double('montant_ttc');
            $table->double('marge');
            $table->double('montant_recu');
            $table->double('reste_a_percevoir');
            $table->string('etat');
            $table->string('etat_fact')->nullable();;
            $table->string('etat_expedi')->nullable();;            
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
        Schema::dropIfExists('commande_client_entetes');
    }
};
