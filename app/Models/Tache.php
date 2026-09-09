<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tache extends Model
{
    use HasFactory;
    protected $fillable = ['reference','nom_tache','utilisateur','id_utilisateur','client','id_client','telephone_client','etape','id_etape','step', 'position','priorite','date_cloture',
    'temps_alloue','description','assignation_id','societe','societe_id','nom_user','user_id'];
}
