<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommandeFournisseurLigne extends Model
{
    use HasFactory;
    public $fillable = ['code_commande','id_commande_fournisseur_entete','code_facture','id_facture_fournisseur_entete','produit','id_produit','reference','type_produit','prix_achat','prix_vente','quantite','quantite_recue','reste_a_recevoir','remise','montant_remise','tva','montant_tva','precompte','montant_precompte','montant_ht','montant_ttc','marge','id_entrepot','nom_fournisseur','id_fournisseur','offrir','etat','user_id','nom_user','societe'];
}
