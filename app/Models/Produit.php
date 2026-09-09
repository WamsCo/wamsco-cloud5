<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;
    public $fillable = ['nom_produit','reference','code_barre','etat','description','categorie','type_produit','nature_produit','fournisseur','entrepot','prix_achat','prix_vente','prix_vente_min','tva','quantite_pv','montant_total',
    'image','limite_stock_alerte','pays_origine','date_peremption','responsable_achat','user_id','nom_user','user_id_modif','nom_user_modif','societe','societe_id'];
}
