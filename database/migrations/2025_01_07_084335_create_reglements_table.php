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
        Schema::create('reglements', function (Blueprint $table) {
            $table->id();
            $table->string('ref_reglement');
            $table->integer('id_facture_client_entete');
            $table->string('code_facture');
            $table->integer('id_client');
            $table->string('nom_client');
            $table->string('mode_reglement');
            $table->string('compte_bancaire');
            $table->integer('id_compte_bancaire');
            $table->integer('id_ecriture_bancaire');
            $table->string('ecriture_bancaire');
            $table->string('date_reglement');
            $table->string('num_cheq_virement');
            $table->string('emetteur_cheq_virement');
            $table->string('banque_cheq_virement');
            $table->string('commentaire');
            $table->double('montant_regler');
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
        Schema::dropIfExists('reglements');
    }
};
