<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrixVente extends Model
{
    use HasFactory;
    public $fillable = ['id_produit','base_prix','taux_taxe','prix_achat','prix_vente','prix_vente_min','user_id','nom_user','societe'];
}
