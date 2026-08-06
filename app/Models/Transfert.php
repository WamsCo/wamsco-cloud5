<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfert extends Model
{
    use HasFactory;
    public $fillable = ['entrepot_origine','entrepot_destination','id_entrepot_origine','id_entrepot_destination','date_sortie','date_entree','transporteur','nombre_paquets','code_inventaire','etiquette_transfert','note','etat','user_id','nom_user','societe'];
}
