<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entite extends Model
{
    use HasFactory;
    protected $fillable = ['solde','enseigne','raison_sociale','responsable_societe','pays','ville','adresse','telephone','email','registre_com','niu','slug','logo','active','societe_mere','societe_mere_id','nom_user','user_id','nom_user_modif','user_id_modif','mod_pointe_vente','mod_cuisine','mod_administration',
    'mod_gestion_tier','mod_crm','mod_gestion_stock','mod_fabrication','mod_banque_caisse','mod_facturation','mod_cmd','mod_multisociete','mod_ticket','mod_tache','mod_gestion_employe','mod_gestion_commercial','mod_restaurant','validite_mod','jour_restant','nombre_users','nbre_user_max',
    'nombre_societe','montant_paye','periode','recommandation','taux_commission','montant_commission','etat_commission','logo_fact_entete','logo_fact_vertical','logo_fact_pied','condition_vente','code_postal','site_web','commercial_charge','activer_fidelite','montant_point','objectif_point',];
}
