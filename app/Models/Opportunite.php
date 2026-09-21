<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opportunite extends Model
{
    use HasFactory;
    protected $fillable = ['client','id_client','nom_opportunite','email_contact','telephone_contact','montant_attendu','etape','id_etape','step', 'position','priorite','date_cloture','note','vendeur','probabilite',
                           'nom_societe','adresse_societe','ville','pays','langue','telephone_recommande_par','campagne','source','secteur_activite','poste_contact','site_web','recommande_par','societe','societe_id','nom_user','user_id'];
}
