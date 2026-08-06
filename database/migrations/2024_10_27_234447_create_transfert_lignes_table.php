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
        Schema::create('transfert_lignes', function (Blueprint $table) {
            $table->id();
            $table->string('produit');
            $table->string('note')->nullable();
            $table->integer('quantite');
            $table->string('entrepot_origine');
            $table->string('entrepot_destination');
            $table->integer('id_entrepot_origine');
            $table->integer('id_entrepot_destination');
            $table->integer('id_tranfert');
            $table->string('etat')->nullable();
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
        Schema::dropIfExists('transfert_lignes');
    }
};
