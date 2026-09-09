<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcritureBancaire extends Model
{
    use HasFactory;
    protected $fillable = ['id_compte_bancaire','id_type_paiement','nom_compte_bancaire','reference','description','date_operation','date_valeur','type_operation',
    'id_tiers','tiers','numero','debit','credit','solde','type_paiement','id_facture_client_entete','id_facture_fournisseur_entete','code_facture','releve',
    'rapprochement','numero_cheque','statut','societe','societe_id','nom_user','user_id'];
}
