<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComposantNomenclatureOrdreFab extends Model
{
    use HasFactory;
    protected $fillable = ['ordre_fabrication','ref_ordre','nomencla_id','id_entrepot','nom_entrepot','composant','composant_id','quantite','quantite_consommer','cout',
    'nomencla_id','unite','societe','societe_id','nom_user','user_id'];
}
