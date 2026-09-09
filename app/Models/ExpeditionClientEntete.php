<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpeditionClientEntete extends Model
{
    use HasFactory;
    public $fillable = ['code_expedition','code_facture','id_facture_client_entete','code_commande','id_commande_client_entete','nom_client','id_client','date_commande',
    'date_facturation','date_echeance','note','montant_ht','montant_remise','montant_tva','montant_precompte','montant_ttc','marge','montant_recu','reste_a_percevoir',
    'statut','etat','etat_cmd','etat_facture','id_entrepot','methode_expedition','numero_suivi','user_id','nom_user','societe','societe_id'];
}
