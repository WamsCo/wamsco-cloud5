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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->string('nom_produit');
            $table->string('reference');
            $table->double('quantite');
            $table->double('prix_achat_last')->nullable();
            $table->double('prix_moyen_pondere_achat');
            $table->double('valorisation_achat_total');
            $table->double('prix_vente_umitaire');
            $table->double('valeur_vente_total');
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
        Schema::dropIfExists('stocks');
    }
};
