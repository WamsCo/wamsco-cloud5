<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaiementDiver extends Model
{
    use HasFactory;
    public $fillable = ['reference','date_paiement','date_valeur','libele_paiement','montant','nom_compte_bancaire','id_compte_bancaire','mode_reglement','id_ecriture_bancaire',
    'numero_cheque_virement','emetteur','nom_banque','sens','debit','credit','note','user_id','nom_user','societe','societe_id'];
}
