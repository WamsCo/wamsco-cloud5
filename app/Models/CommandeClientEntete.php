<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommandeClientEntete extends Model
{
    use HasFactory;
    public $fillable = ['code_commande','code_facture','id_facture_client_entete','code_proforma','id_proforma_client_entete','nom_client','id_client','telephone','date_commande',
    'date_livraison','mode_reglement','condition_reglement','note','montant_ht','montant_remise','montant_tva','montant_precompte','montant_ttc','marge','montant_recu','reste_a_percevoir',
    'etat','etat_fact','etat_expedi','nbre_facture','user_id','nom_user','societe','societe_id',];

}
