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
        Schema::create('facture_fournisseur_entetes', function (Blueprint $table) {
            $table->id();
            $table->string('code_facture');
            $table->string('nom_fournisseur')->nullable();
            $table->integer('id_fournisseur');
            $table->string('date_facturation');
            $table->string('date_echeance');
            $table->string('mode_reglement');
            $table->string('compte_bancaire')->nullable();
            $table->integer('id_compte_bancaire')->nullable();
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
        Schema::dropIfExists('facture_fournisseur_entetes');
    }
};
