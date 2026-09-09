<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nomenclature extends Model
{
    use HasFactory;
    protected $fillable = ['libelle','produit_a_fabrique','produit_id','code','quantite','unite_mesure','entrepot_fabrication','id_entrepot','duree','type_nomencla','description','etat',
    'societe','societe_id','nom_user','user_id'];
}
