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
        Schema::create('facture_client_lignes', function (Blueprint $table) {
            $table->id();
            $table->string('code_facture');
            $table->integer('id_facture_client_entete');
            $table->string('produit');
            $table->integer('id_produit');
            $table->double('prix_achat');
            $table->double('prix_vente');
            $table->integer('quantite');
            $table->double('quantite_expediee');
            $table->double('reste_a_expedier');
            $table->double('remise');
            $table->double('montant_remise');
            $table->double('tva');
            $table->double('montant_tva');
            $table->double('precompte');
            $table->double('montant_precompte');
            $table->double('montant_ht');
            $table->double('montant_ttc');
            $table->double('marge');
            $table->integer('id_entrepot');
            $table->string('nom_client');
            $table->integer('id_client');
            $table->string('offrir');
            $table->string('etat');
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
        Schema::dropIfExists('facture_client_lignes');
    }
};
