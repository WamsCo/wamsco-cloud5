<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mouvement extends Model
{
    use HasFactory;
    public $fillable = ['id_entrepot','nom_produit','id_produit','reference','quantite','libele_mouvement','code_mouvement','statut','origine','id_inventaire','id_expedition','id_reception','entrepot','id_session_pos','id_ordre_fab','user_id','nom_user','societe'];
}
