<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestauCommandeAttenteEntete extends Model
{
    use HasFactory;
    public $fillable = ['ref_table','nom_table','id_session_pos','ref_session_pos','code_facture','nom_client','id_client','date_facturation','date_echeance','mode_reglement',
    'compte_bancaire','id_compte_bancaire','note','montant_ht','montant_remise','montant_tva','montant_precompte','montant_ttc','marge','montant_recu','reste_a_percevoir','etat','en_cuisine',
    'lieu_consommation','date_consommation','adresse_livraison','user_id','nom_user','societe'];
}
