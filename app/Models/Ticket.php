<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    public $fillable = ['reference','nom_ticket','type_demande','priorite','description','etiquettes','assignation','assignation_id','tiers','id_tiers','telephone_tier','adresse_tier',
    'progression','date_cloture','statut','fichier_joint','note','telephone_user','source','user_id','nom_user','societe','societe_id'];
}
