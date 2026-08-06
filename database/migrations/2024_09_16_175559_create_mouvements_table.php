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
        Schema::create('mouvements', function (Blueprint $table) {
            $table->id();
            $table->string('entrepot');
            $table->integer('id_entrepot');
            $table->string('nom_produit');
            $table->integer('id_produit');
            $table->string('reference');
            $table->double('quantite');
            $table->string('libele_mouvement');
            $table->string('code_mouvement');
            $table->string('statut')->nullable();
            $table->string('origine')->nullable();
            $table->integer('id_inventaire')->nullable();
            $table->integer('id_expedition')->nullable();
            $table->integer('id_reception')->nullable();
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
        Schema::dropIfExists('mouvements');
    }
};
