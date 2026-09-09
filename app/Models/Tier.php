<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tier extends Model
{
    use HasFactory;
    protected $fillable = ['nom','code_tier','raison_sociale','type_tiers','etat','adresse','code_postal','ville','pays','telephone','solde','email','site_web','commercial_charge','sexe','logo',
    'activite_tier','date_debut','produit','quantite','prix','periode_essai','validation','paiement','observation','nombre_point','retrait_point','objectif_point','societe','societe_id','nom_user','nom_user_modif','user_id'];
}
