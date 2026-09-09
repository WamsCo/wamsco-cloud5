<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComposantNomenclature extends Model
{
    use HasFactory;
    protected $fillable = ['composant','composant_id','quantite','quantite_consommer','cout','id_entrepot','nom_entrepot','nomencla_id','unite','societe','societe_id','nom_user','user_id'];
}
