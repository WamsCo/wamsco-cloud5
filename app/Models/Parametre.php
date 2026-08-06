<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parametre extends Model
{
    use HasFactory;
    protected $fillable = ['entrepot_pv','id_entrepot_pv','id_entrepot_restau','id_entrepot_fctclt','id_entrepot_fctfourni','activer_fidelite','activer_ecran_cuisine','envoi_mail','user_id','nom_user','nom_user_modif','user_id_modif','societe'];
}
