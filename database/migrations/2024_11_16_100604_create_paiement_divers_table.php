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
        Schema::create('paiement_divers', function (Blueprint $table) {
            $table->id();
            $table->string('reference');
            $table->string('date_paiement');
            $table->string('date_valeur');
            $table->string('libele_paiement');
            $table->double('montant');
            $table->string('nom_compte_bancaire');
            $table->string('id_compte_bancaire');
            $table->string('mode_reglement');
            $table->string('ecriture_bancaire');
            $table->integer('id_ecriture_bancaire');
            $table->string('numeru_cheque_virement')->nullable();
            $table->string('emetteur')->nullable();
            $table->string('nom_banque')->nullable();
            $table->double('debit');
            $table->double('credit');
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
        Schema::dropIfExists('paiement_divers');
    }
};
