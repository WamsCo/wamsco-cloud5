<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransfertFilialeLigne extends Model
{
    use HasFactory;
    public $fillable = ['produit','id_produit','produit_recep','id_produit_recep','message','quantite','quantite_stock_entre_origine','quantite_stock_entre_destination','filiale_envoi',
    'filiale_reception','entrepot_origine','entrepot_destination','id_entrepot_origine','id_entrepot_destination','id_transfert','id_stock_origine','id_stock_destinataire','etat',
    'user_id','nom_user','societe','societe_id'];

}
