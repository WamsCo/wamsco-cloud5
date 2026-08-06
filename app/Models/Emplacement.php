<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emplacement extends Model
{
    use HasFactory;
    protected $fillable = ['nom_emplacement','description','utiliser','statut','societe','nom_user','non_caissiere','lieu_consommation','date_consommation','adresse_livraison','user_initial','user_id'];

}
