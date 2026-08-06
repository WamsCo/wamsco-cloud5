<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProformaClientEntete extends Model
{
    use HasFactory;
    public $fillable = ['code_proforma','code_commande','id_commande_client_entete','nom_client','id_client','telephone','date_proforma','date_livraison','mode_reglement','condition_reglement','note','montant_ht','montant_remise','montant_tva','montant_precompte','montant_ttc','marge','montant_recu','reste_a_percevoir','etat','etat_cmd','etat_expedi','user_id','nom_user','societe'];
}
