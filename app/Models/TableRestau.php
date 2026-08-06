<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableRestau extends Model
{
    use HasFactory;
    protected $fillable = ['reference','id_session_restau','ref_session_restau','nom_table','description','nom_espace','id_espace','step','position','utiliser','statut','id_caissiere','non_caissiere','lieu_consommation','date_consommation','adresse_livraison','nom_user','user_id','societe',];
}
