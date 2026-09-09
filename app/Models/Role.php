<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $fillable = ['nom','societe','societe_id','nom_user','user_id','description',
                           'consulter_tier','creer_tier','modifier_tier', 'supprimer_tier',
                           'consulter_produit','creer_produit','modifier_produit','supprimer_produit',
                           'consulter_categorie','creer_categorie','modifier_categorie','supprimer_categorie',
                           'mouvement_stock','correction_stock','consulter_entrepot','creer_entrepot','modifier_entrepot','supprimer_entrepot',                           
                           'consulter_transfert','creer_transfert','modifier_transfert','supprimer_transfert',
                           'consulter_inventaire','creer_inventaire','modifier_inventaire','supprimer_inventaire',
                           'tablobord_pv','consulter_session_pv','creer_session','voir_session_autre','pv',
                           'gerer_cmd_attente','voir_ecran_cuisine','changer_statut_cmd_cuisine','reservation',
                           'modifier_fidelite','consulter_emplacement','creer_emplacement','modifier_emplacement','supprimer_emplacement',
                           'consulter_commande','creer_commande','modifier_commande','supprimer_commande','consulter_expedition','creer_expedition','supprimer_expedition',
                           'consulter_com_fourni','creer_com_fourni','modifier_com_fourni','supprimer_com_fourni','consulter_reception','creer_reception','supprimer_reception',
                           'consulter_facture','creer_facture','modifier_facture','supprimer_facture','consulter_reglement','creer_reglement','supprimer_reglement',
                           'consulter_fact_fourni','creer_fact_fourni','modifier_fact_fourni','supprimer_fact_fourni','consulter_reglement_fourni','creer_reglement_fourni','supprimer_reglement_fourni',
                           'consulter_compte','creer_compte','modifier_compte','supprimer_compte','consulter_ecriture','mod_ecriture','consulter_paie_divers','creer_paie_divers','modifier_paie_divers',
                           'supprimer_paie_divers','voir_marge','effectuer_vire_interne',
                           'consulter_societe','creer_societe','modifier_societe','changer_filiale','transfer_stock_filiale','consulter_transfert_filiale','acces_entite',
                           'consulter_entite','creer_entite','modifier_entite','supprimer_entite','activer_compte_enite',
                           'consulter_user','creer_user', 'modifier_user','supprimer_user',
                           'consulter_role','creer_role', 'modifier_role','supprimer_role',   
                           'consulter_depart_poste','creer_depart_poste','modifier_depart_poste','supprimer_depart_poste','configurer',
                           'liste_nomencla','creer_nomencla','modifier_nomencla','supprimer_nomencla','liste_ordre_fab','creer_ordre_fab','modifier_ordre_fab',
                           'supprimer_ordre_fab','ajouter_composant','supprimer_composant',
                           'consulter_opportunite','creer_opportunite','detail_opportunite','modifier_opportunite','supprimer_opportunite',                           
                           'consulter_etape','creer_etape','modifier_etape','supprimer_etape', 
                           'consulter_ticket','creer_ticket','modifier_ticket','supprimer_ticket',                            
                           'consulter_tache','creer_tache','detail_tache','modifier_tache','supprimer_tache', 
                           'consulter_etapeTache','creer_etapeTache','modifier_etapeTache','supprimer_etapeTache', 
                           'consulter_souscription','creer_souscription',
                           'consulter_session_restau','creer_session_restau','passe_cmd_restau','voir_cmd_autre_restau','eff_paie_restau',
                           'consulter_espace','creer_espace','modifier_espace','supprimer_espace',
                           'consulter_table','creer_table','modifier_table','supprimer_table',
                                                                      
                        ];

    // important: Sinon Laravel ne convertira pas le JSON en tableau.
    protected $casts = ['acces_entite' => 'array',];
}
