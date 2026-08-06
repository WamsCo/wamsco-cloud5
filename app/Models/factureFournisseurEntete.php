<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class factureFournisseurEntete extends Model
{
    use HasFactory;
    public $fillable = ['code_facture','code_commande','id_commande_fournisseur_entete','code_reception','id_reception_fournisseur_entete','nom_fournisseur','id_fournisseur','telephone','date_facturation',
    'date_echeance','mode_reglement','compte_bancaire','id_compte_bancaire','note','montant_ht','montant_remise','montant_tva','montant_precompte','montant_ttc','marge','montant_recu','reste_a_percevoir',
    'statut','etat','etat_reception','id_session_pos','ref_session_pos','lieu_consommation','date_consommation','adresse_livraison','user_id','nom_user','societe'];
}
