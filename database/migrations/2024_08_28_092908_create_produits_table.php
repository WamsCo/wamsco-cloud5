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
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->string('nom_produit');
            $table->string('reference');
            $table->tinyInteger('etat');
            $table->string('description')->nullable();
            $table->string('categorie')->nullable();
            $table->string('type_produit'); // Produit ou Service
            $table->string('nature_produit'); // Matiere premiere ou Manufacture
            $table->string('fournisseur');
            $table->string('entrepot')->nullable();
            $table->double('prix_achat');
            $table->double('prix_vente');
            $table->double('prix_vente_min');
            $table->double('tva');
            $table->integer('quantite_pv')->nullable();
            $table->double('montant_total')->nullable();
            $table->string('image')->nullable();
            $table->integer('limite_stock_alerte');
            $table->string('pays_origine')->nullable();
            $table->string('date_peremption')->nullable();
            $table->string('responsable_achat')->nullable();
            $table->integer('user_id');
            $table->string('nom_user');
            $table->integer('user_id_modif');
            $table->string('nom_user_modif');
            $table->string('societe');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};
