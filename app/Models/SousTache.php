<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SousTache extends Model
{
    use HasFactory;
    protected $fillable = ['id_tache_entete','nom_sous_tache','utilisateur','id_utilisateur','statut','societe','nom_user','user_id'];
}
