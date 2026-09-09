<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosFactureClientLigne extends Model
{
    use HasFactory;
    public $fillable = ['code_facture','id_facture_client_entete','produit','id_produit','reference','type_produit','prix_achat','prix_vente','quantite','quantite_expediee','reste_a_expedier',
    'remise','montant_remise','tva','montant_tva','precompte','montant_precompte','montant_ht','montant_ttc','marge','id_entrepot','nom_client','id_client','offrir','infos','etat','user_id',
    'nom_user','societe','societe_id'];
}
