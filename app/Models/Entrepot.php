<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrepot extends Model
{
    use HasFactory;
    protected $fillable = ['nom','reference','active','entrepot_parent','description','adresse','code_postal','ville','pays','telephone','email','stock_total','valorisation_achat_total','valeur_vente_total','societe','societe_mere','nom_user','user_id'];
}
