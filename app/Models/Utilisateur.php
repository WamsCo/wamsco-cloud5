<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as BasicAuthenticatable;

class Utilisateur extends Model implements Authenticatable
{
    use HasFactory;
    use BasicAuthenticatable;

    protected $fillable = ['name','email','confirmer','password','type_user','date_valide','profil','etat','note_interne','sexe','salarie','nom_user','nom_user_modif','user_id','societe_id','societe','societe_mere_id','societe_mere',
                            'titre','telephone','departement','departement_id','poste_travail','poste_travail_id','lieu_travail','adresse_travail','manager','validateur_conges','horaire_journalier','horaire_hebdo','horaire_mensuel',
                            'date_naissance','lieu_naissance','nationalite','cni','passeport','etat_civil','nbre_enfant','nom_conjoint','date_nais_conjoint','persone_contact_urgence',
                            'telephone_urgence','type_employe','type_contrat','type_salaire','date_debut_contrat','date_fin_contrat','responsable_rh','salaire','categorie','echelon','niu','cnps','dipe','matricule',
                            'mode_paiement','nom_banque','numero_compte','rib'];
    //public $timestamps = false;  

    // ceci permet d'eviter erreur de la case a cocher (se souvernir de moi) puisqu'on n'utilise pas  
    public function getRememberTokenName()
    {
        return '';
    }
}
