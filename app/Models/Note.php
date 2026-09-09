<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;
    protected $fillable = ['opportunite_id','type_activite','sujet','date_echeance','commentaire','statut','profil','user_id','nom_user','societe','societe_id'];
}
