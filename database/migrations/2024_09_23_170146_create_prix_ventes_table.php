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
        Schema::create('prix_ventes', function (Blueprint $table) {
            $table->id();
            $table->integer('id_produit');
            $table->string('base_prix');
            $table->double('taux_taxe');
            $table->double('hors_taxe');
            $table->double('prix_achat');
            $table->double('prix_vente');
            $table->double('prix_vente_min');
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
        Schema::dropIfExists('prix_ventes');
    }
};
