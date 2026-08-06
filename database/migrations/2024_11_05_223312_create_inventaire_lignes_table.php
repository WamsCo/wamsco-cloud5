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
        Schema::create('inventaire_lignes', function (Blueprint $table) {
            $table->id();
            $table->string('entrepot');
            $table->integer('id_entrepot');
            $table->string('produit');
            $table->integer('id_produit');
            $table->integer('quantite_initialle');
            $table->integer('quantite_reelle');
            $table->integer('id_inventaire');
            $table->integer('id_stock');
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
        Schema::dropIfExists('inventaire_lignes');
    }
};
