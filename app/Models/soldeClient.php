<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class soldeClient extends Model
{
    use HasFactory;
    protected $fillable = ['enseigne','id_enseigne','societe_mere','raison_sociale','designation','debit','credit','responsable_societe','pays','ville','adresse',
    'telephone','email','registre_com','logo','nom_user','user_id'];
}
