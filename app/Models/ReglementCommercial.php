<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReglementCommercial extends Model
{
    use HasFactory;
    public $fillable = ['id_societe','nom_societe','ref_reglement','id_commercial','nom_commercial','email_commercial','mode_reglement','id_compte_bancaire','compte_bancaire','id_ecriture_bancaire','ecriture_bancaire','date_reglement','statut','num_cheq_virement','emetteur_cheq_virement','banque_cheq_virement','commentaire','montant_regler','user_id','nom_user','societe'];
}
