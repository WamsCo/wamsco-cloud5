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
        Schema::create('ecriture_bancaires', function (Blueprint $table) {
            $table->id();            
            $table->integer('id_compte_bancaire');
            $table->integer('id_type_paiement');
            $table->string('reference');
            $table->string('description');
            $table->string('date_operation');
            $table->string('date_valeur');
            $table->string('type_operation');
            $table->integer('id_tiers')->nullable();
            $table->string('tiers')->nullable();
            $table->string('numero');
            $table->string('nom_compte_bancaire');
            $table->double('debit');
            $table->double('credit');
            $table->double('solde');
            $table->string('type_paiement');
            $table->integer('id_facture_client_entete')->nullable();
            $table->string('code_facture')->nullable();
            $table->string('releve');
            $table->string('rapprochement');
            $table->string('numero_cheque');
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
        Schema::dropIfExists('ecriture_bancaires');
    }
};
