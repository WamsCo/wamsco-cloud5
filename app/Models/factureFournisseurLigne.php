<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class factureFournisseurLigne extends Model
{
    use HasFactory;
    public $fillable = ['code_facture','id_facture_fournisseur_entete','produit','id_produit','reference','type_produit','prix_achat','prix_vente','quantite','quantite_recue','reste_a_recevoir',
    'remise','montant_remise','tva','montant_tva','precompte','montant_precompte','montant_ht','montant_ttc','marge','id_entrepot','nom_fournisseur','id_fournisseur','offrir','infos',
    'etat','id_session_pos','ref_session_pos','user_id','nom_user','societe','societe_id'];

}
