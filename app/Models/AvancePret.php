<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvancePret extends Model
{
    use HasFactory;
    protected $fillable = ['salarie','id_salarie','telephone','libelle','montant','nombre_tranche','type_pret','note','mode_reglement','date_paiement','montant_deja_preleve','etat','societe','societe_id','nom_user','user_id'];
}
