<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reglement extends Model
{
    use HasFactory;
    public $fillable = ['ref_reglement','id_facture_client_entete','code_facture','id_client','nom_client','mode_reglement','id_compte_bancaire','compte_bancaire','id_ecriture_bancaire','ecriture_bancaire','date_reglement','num_cheq_virement','emetteur_cheq_virement','banque_cheq_virement','commentaire','montant_regler','id_session_pos','ref_session_pos','user_id','nom_user','societe'];
}
