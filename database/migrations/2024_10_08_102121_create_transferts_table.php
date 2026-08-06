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
        Schema::create('transferts', function (Blueprint $table) {
            $table->id();
            $table->string('entrepot_origine');
            $table->string('entrepot_destination');
            $table->string('date_sortie');
            $table->string('date_entree');
            $table->string('transporteur');
            $table->integer('nombre_paquets');
            $table->string('code_inventaire');
            $table->string('etiquette_transfert');
            $table->string('note');
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
        Schema::dropIfExists('transferts');
    }
};
