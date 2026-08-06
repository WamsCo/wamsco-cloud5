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
        Schema::create('commande_fournisseur_lignes', function (Blueprint $table) {
            $table->id();
            $table->string('code_commande');
            $table->integer('id_commande_fournisseur_entete');
            $table->string('code_facture')->nullable();
            $table->integer('id_facture_fournisseur_entete')->nullable();
            $table->string('produit');
            $table->integer('id_produit');
            $table->double('prix_achat');
            $table->double('prix_vente');
            $table->integer('quantite');
            $table->double('quantite_recue');
            $table->double('reste_a_recevoir');
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
            $table->string('nom_fournisseur');
            $table->integer('id_fournisseur');
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
        Schema::dropIfExists('commande_fournisseur_lignes');
    }
};
