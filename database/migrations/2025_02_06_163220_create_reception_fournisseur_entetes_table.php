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
        Schema::create('reception_fournisseur_entetes', function (Blueprint $table) {
            $table->id();
            $table->string('code_expedition')->nullable();
            $table->string('code_facture')->nullable();
            $table->integer('id_facture_fournisseur_entete')->nullable();
            $table->string('code_commande')->nullable();
            $table->integer('id_commande_fournisseur_entete')->nullable();
            $table->string('nom_fournisseur')->nullable();
            $table->string('id_fournisseur');
            $table->string('date_facturation');
            $table->string('date_echeance');
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
            $table->string('etat_facture');  
            $table->integer('id_entrepot')->nullable();  
            $table->string('methode_expedition')->nullable();  
            $table->string('numero_suivi')->nullable();  
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
        Schema::dropIfExists('reception_fournisseur_entetes');
    }
};
