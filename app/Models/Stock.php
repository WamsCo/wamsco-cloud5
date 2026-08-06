<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;
    public $fillable = ['id_entrepot','nom_produit','id_produit','reference','code_barre','type_produit','nature_produit','categorie','quantite','prix_achat_last','prix_moyen_pondere_achat','valorisation_achat_total','prix_vente_unitaire','valeur_vente_total','prix_vente_min','limite_stock_alerte','etat','image','user_id','nom_user','societe'];
}
