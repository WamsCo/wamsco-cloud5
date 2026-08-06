<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransfertLigne extends Model
{
    use HasFactory;
    public $fillable = ['produit','id_produit','message','quantite','quantite_stock_entre_origine','quantite_stock_entre_destination','entrepot_origine','entrepot_destination','id_entrepot_origine','id_entrepot_destination','id_transfert','id_stock_origine','id_stock_destinataire','etat','user_id','nom_user','societe'];
}
