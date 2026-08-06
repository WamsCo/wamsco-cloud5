<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MagConsoComposantOf extends Model
{
    use HasFactory;
    protected $fillable = ['entrepot_id','entrepot_conso','ordre_fabrication','ref_ordre','quantite_consommer','unite','composant_id','composant','societe','nom_user','user_id'];
}
