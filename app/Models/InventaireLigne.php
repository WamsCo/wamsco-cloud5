<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventaireLigne extends Model
{
    use HasFactory;
    public $fillable = ['entrepot','id_entrepot','produit','id_produit','quantite_initiale','quantite_reelle','ecart','id_inventaire','id_stock','etat','user_id','nom_user','societe'];
}
