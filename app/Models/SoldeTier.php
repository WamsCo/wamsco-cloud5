<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoldeTier extends Model
{
    use HasFactory;
    protected $fillable = ['id_tier','nom_tier','code_tier','raison_sociale','designation','debit','credit','compte','pays','ville','adresse','telephone','email','logo','nom_user','user_id','societe',];
}
