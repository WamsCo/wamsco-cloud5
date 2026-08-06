<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdreFabrication extends Model
{
    use HasFactory;
    public $fillable = ['ref_ordre','libelle','produit_a_fabrique','produit_id','type_nomencla','nomencla_id','code_nomencla','quantite','quantite_fabrique','duree','date_debut','date_fin','entrepot_fabrication','id_entrepot','unite_mesure','cout_total','responsable','responsable_id','tiers','tiers_id','statut','description','societe','nom_user','user_id'];
}
