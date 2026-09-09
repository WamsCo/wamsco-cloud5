<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class factureClientEntete extends Model
{
    use HasFactory;
    public $fillable = ['code_facture','code_commande','id_commande_client_entete','code_expedition','id_expedition_client_entete','nom_client','id_client','telephone','date_facturation',
    'date_echeance','mode_reglement','compte_bancaire','id_compte_bancaire','note','montant_ht','montant_remise','montant_tva','montant_precompte','montant_ttc','marge','montant_recu',
    'reste_a_percevoir','statut','etat','etat_expedi','id_session_pos','ref_session_pos','lieu_consommation','date_consommation','adresse_livraison','user_id','nom_user','societe','societe_id'];
}
