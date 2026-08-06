<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionRestau extends Model
{
    use HasFactory;
     public $fillable = ['session_id','nom_point_vente','date_ouverture','date_fermeture','solde_initial','solde_final','solde_cloture_theorique','etat','note','user_id','nom_user','societe'];
}
