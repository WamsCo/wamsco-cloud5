<?php

namespace App\Livewire\Administration;

use Livewire\Component;
use Livewire\Attributes\Validate;  
use App\Helpers\LogActivity;
use App\Models\LogActivity as LogActivityModel;
use Livewire\WithPagination;
use App\Models\DeviseTva;
use App\Models\Entite;
use App\Models\Utilisateur;
use App\Models\Role;

class DetailRole extends Component
{
    
    public $id;
    public $ids;
    public $nom, $societe, $societe_id, $nom_user, $user_id, $description; 
    public $created_at;
    public $updated_at;
    public $auteur; 
    
    // Tier
    public $consulter_tier, $creer_tier, $modifier_tier, $supprimer_tier;
    //stock
    public $consulter_produit, $creer_produit, $modifier_produit, $supprimer_produit;
    public $consulter_categorie, $creer_categorie, $modifier_categorie, $supprimer_categorie;
    public $mouvement_stock, $correction_stock, $consulter_entrepot, $creer_entrepot, $modifier_entrepot, $supprimer_entrepot;    
    public $consulter_transfert, $creer_transfert, $modifier_transfert, $supprimer_transfert; 
    public $consulter_inventaire, $creer_inventaire, $modifier_inventaire, $supprimer_inventaire; 
    // Point de vente   
    public $tablobord_pv, $consulter_session_pv, $creer_session, $voir_session_autre, $pv;
    public $gerer_cmd_attente,$voir_ecran_cuisine, $changer_statut_cmd_cuisine, $reservation;
    public $modifier_fidelite, $consulter_emplacement, $creer_emplacement, $modifier_emplacement, $supprimer_emplacement;
    // Commande
    public $consulter_commande, $creer_commande, $modifier_commande, $supprimer_commande, $consulter_expedition, $creer_expedition, $supprimer_expedition;    
    public $consulter_com_fourni, $creer_com_fourni, $modifier_com_fourni, $supprimer_com_fourni, $consulter_reception, $creer_reception, $supprimer_reception;
    // Facturation
    public $consulter_facture, $creer_facture, $modifier_facture, $supprimer_facture, $consulter_reglement, $creer_reglement, $supprimer_reglement;
    public $consulter_fact_fourni, $creer_fact_fourni, $modifier_fact_fourni, $supprimer_fact_fourni, $consulter_reglement_fourni, $creer_reglement_fourni, $supprimer_reglement_fourni;
    // Banque & Caisse
    public $consulter_compte, $creer_compte, $modifier_compte, $supprimer_compte, $consulter_ecriture, $mod_ecriture, $consulter_paie_divers, $creer_paie_divers,
           $modifier_paie_divers, $supprimer_paie_divers, $voir_marge ,$effectuer_vire_interne;
    // Multi societe
    public $consulter_societe, $creer_societe, $modifier_societe, $changer_filiale, $transfer_stock_filiale, $consulter_transfert_filiale;
    public $acces_entite = []; // ce tableau recupere plusieurs id entite
    // Entite
    public $consulter_entite, $creer_entite, $modifier_entite, $supprimer_entite, $activer_compte_enite;
    // Utilisateur
    public $consulter_user, $creer_user, $modifier_user, $supprimer_user;
    // Role
    public $consulter_role, $creer_role, $modifier_role, $supprimer_role;
    // Depart / Poste
    public $consulter_depart_poste, $creer_depart_poste, $modifier_depart_poste, $supprimer_depart_poste;
    // config 
    public $configurer;
    // Fabrication
    public $liste_nomencla, $creer_nomencla, $modifier_nomencla, $supprimer_nomencla, $liste_ordre_fab, $creer_ordre_fab, $modifier_ordre_fab, $supprimer_ordre_fab, 
    $ajouter_composant, $supprimer_composant;
    // CRM
    public $consulter_opportunite, $creer_opportunite, $detail_opportunite, $modifier_opportunite, $supprimer_opportunite;                          
    public $consulter_etape, $creer_etape, $modifier_etape, $supprimer_etape;
    // Ticket
    public $consulter_ticket, $creer_ticket, $modifier_ticket, $supprimer_ticket;
    // Taches
    public $consulter_tache, $creer_tache, $detail_tache, $modifier_tache, $supprimer_tache;                          
    public $consulter_etapeTache, $creer_etapeTache, $modifier_etapeTache, $supprimer_etapeTache;
    // Commercial
    public $consulter_souscription, $creer_souscription;
    // Restaurant
    public $consulter_session_restau, $creer_session_restau,$passe_cmd_restau,$voir_cmd_autre_restau,
    $eff_paie_restau,$consulter_espace,$creer_espace,$modifier_espace,$supprimer_espace,$consulter_table,$creer_table,$modifier_table,$supprimer_table;

    public $confirmer;
    public function mount(){        
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->consulter_role;
            if($autoriser == 0){
                alert()->error('Oups Désolé', 'Vous n\'êtes pas autorisé à ouvrir cette page !!!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
                $this->redirect('/bienvenue', navigate: true);
            }
        }
        else{
            alert()->error('Oups Désolé', 'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
            $this->redirect('/bienvenue', navigate: true);
        }            
        // $this->societe = auth()->user()->societe;
        // $this->parSociete = auth()->user()->societe;
        // $this->ids = request('id'); // id user
    }   
    public function render(){
        $this->ids = request('id'); // id user
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('id',auth()->user()->societe_id)->get();
        $jourValid = $entite_mod[0]->validite_mod; 
        $mod_administration = $entite_mod[0]->mod_administration; 
        $soldeClient = $entite_mod[0]->solde;
        if($dateJour <= $jourValid){
            if($mod_administration == 1){
                $title = 'Détails Rôle & privillèges | WamsCo';
                $module = 'Paramètres';
                $title_fils = 'Rôle & privillèges';
                $lien = 'detail_role';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix'); 
                $dateJour = date('Y-m-d'); 

                // ceci gere la suppression: quand on clique sur btn supprimer, ids disparait et devient null  
                if($this->ids){
                    $role =  Role::where('id',$this->ids)->first();
                    $this->nom = $role->nom;
                    $this->description = $role->description;
                    $this->societe = $role->societe;
                    $this->societe_id = $role->societe_id;
                    $this->created_at = $role->created_at;
                    $this->updated_at = $role->updated_at;
                    $this->auteur = $role->nom_user;  
                }
                else{
                    $role =  Role::where('id',$this->id)->first();                
                    $this->ids = $role->id; // important pour suppression
                    $this->nom = $role->nom;
                    $this->description = $role->description;
                    $this->societe = $role->societe;
                    $this->societe_id = $role->societe_id;
                    $this->created_at = $role->created_at;
                    $this->updated_at = $role->updated_at;
                    $this->auteur = $role->nom_user;  
                }   

                $entit = Entite::where('id',$this->societe_id)->where('active',1)->orderBy('enseigne','asc')->first();
                $societe_mere = $entit->societe_mere;
                $societe_mere_id = $entit->societe_mere_id;
                $entiteFiliale = Entite::where('societe_mere_id',$societe_mere_id)->where('active',1)->orderBy('enseigne','asc')->get();
                // $entiteFiliale = Entite::where('societe_mere',auth()->user()->societe_mere)->where('active',1)->orderBy('enseigne','asc')->get();

                $page = 'Role';
                $log = LogActivityModel::where('societe_id',auth()->user()->societe_id)->where('page', $page)->limit(50)->orderBy('id','desc')->get();
                $logCount = $log->count();

                // ceci pour selection droit (les radio)
                //Tier
                $consulter_tier = $role->consulter_tier;
                if($consulter_tier == 1){
                    $this->consulter_tier = true;
                }  
                $creer_tier = $role->creer_tier;
                if($creer_tier == 1){
                    $this->creer_tier = true;
                } 

                $modifier_tier = $role->modifier_tier;
                if($modifier_tier == 1){
                    $this->modifier_tier = true;
                }  
                $supprimer_tier = $role->supprimer_tier;
                if($supprimer_tier == 1){
                    $this->supprimer_tier = true;
                }
                // Fin Tier 

                // produit
                $consulter_produit = $role->consulter_produit;
                if($consulter_produit == 1){
                    $this->consulter_produit = true;
                }  
                $creer_produit = $role->creer_produit;
                if($creer_produit == 1){
                    $this->creer_produit = true;
                } 
                $modifier_produit = $role->modifier_produit;
                if($modifier_produit == 1){
                    $this->modifier_produit = true;
                }  
                $supprimer_produit = $role->supprimer_produit;
                if($supprimer_produit == 1){
                    $this->supprimer_produit = true;
                }
                
                        // categorie
                $consulter_categorie = $role->consulter_categorie;
                if($consulter_categorie == 1){
                    $this->consulter_categorie = true;
                }  
                $creer_categorie = $role->creer_categorie;
                if($creer_categorie == 1){
                    $this->creer_categorie = true;
                } 

                $modifier_categorie = $role->modifier_categorie;
                if($modifier_categorie == 1){
                    $this->modifier_categorie = true;
                }  
                $supprimer_categorie = $role->supprimer_categorie;
                if($supprimer_categorie == 1){
                    $this->supprimer_categorie = true;
                }

                        // Magasin                    
                $mouvement_stock = $role->mouvement_stock;
                if($mouvement_stock == 1){
                    $this->mouvement_stock = true;
                }
                $correction_stock = $role->correction_stock;
                if($correction_stock == 1){
                    $this->correction_stock = true;
                } 
                
                $consulter_entrepot = $role->consulter_entrepot;
                if($consulter_entrepot == 1){
                    $this->consulter_entrepot = true;
                }  
                $creer_entrepot = $role->creer_entrepot;
                if($creer_entrepot == 1){
                    $this->creer_entrepot = true;
                } 

                $modifier_entrepot = $role->modifier_entrepot;
                if($modifier_entrepot == 1){
                    $this->modifier_entrepot = true;
                }  
                $supprimer_entrepot = $role->supprimer_entrepot;
                if($supprimer_entrepot == 1){
                    $this->supprimer_entrepot = true;
                }

                        // Transfert                    
                $consulter_transfert = $role->consulter_transfert;
                if($consulter_transfert == 1){
                    $this->consulter_transfert = true;
                } 
                $creer_transfert = $role->creer_transfert;
                if($creer_transfert == 1){
                    $this->creer_transfert = true;
                }  
                $modifier_transfert = $role->modifier_transfert;
                if($modifier_transfert == 1){
                    $this->modifier_transfert = true;
                } 

                $supprimer_transfert = $role->supprimer_transfert;
                if($supprimer_transfert == 1){
                    $this->supprimer_transfert = true;
                } 

                // Inventaire                    
                $consulter_inventaire = $role->consulter_inventaire;
                if($consulter_inventaire == 1){
                    $this->consulter_inventaire = true;
                } 
                $creer_inventaire = $role->creer_inventaire;
                if($creer_inventaire == 1){
                    $this->creer_inventaire = true;
                }  
                $modifier_inventaire = $role->modifier_inventaire;
                if($modifier_inventaire == 1){
                    $this->modifier_inventaire = true;
                } 

                $supprimer_inventaire = $role->supprimer_inventaire;
                if($supprimer_inventaire == 1){
                    $this->supprimer_inventaire = true;
                } 

                        // Point de vente                    
                $tablobord_pv = $role->tablobord_pv;
                if($tablobord_pv == 1){
                    $this->tablobord_pv = true;
                } 
                $consulter_session_pv = $role->consulter_session_pv;
                if($consulter_session_pv == 1){
                    $this->consulter_session_pv = true;
                }  
                $creer_session = $role->creer_session;
                if($creer_session == 1){
                    $this->creer_session = true;
                }
                $voir_session_autre = $role->voir_session_autre;
                if($voir_session_autre == 1){
                    $this->voir_session_autre = true;
                } 
                $pv = $role->pv;
                if($pv == 1){
                    $this->pv = true;
                } 

                // Back office                    
                $gerer_cmd_attente = $role->gerer_cmd_attente;
                if($gerer_cmd_attente == 1){
                    $this->gerer_cmd_attente = true;
                } 
                $voir_ecran_cuisine = $role->voir_ecran_cuisine;
                if($voir_ecran_cuisine == 1){
                    $this->voir_ecran_cuisine = true;
                }  
                $changer_statut_cmd_cuisine = $role->changer_statut_cmd_cuisine;
                if($changer_statut_cmd_cuisine == 1){
                    $this->changer_statut_cmd_cuisine = true;
                }
                $reservation = $role->reservation;
                if($reservation == 1){
                    $this->reservation = true;
                } 
                // modifier_fidelite
                $modifier_fidelite = $role->modifier_fidelite;
                if($modifier_fidelite == 1){
                    $this->modifier_fidelite = true;
                } 
                
                // Emplacement
                $consulter_emplacement = $role->consulter_emplacement;
                if($consulter_emplacement == 1){
                    $this->consulter_emplacement = true;
                } 
                $creer_emplacement = $role->creer_emplacement;
                if($creer_emplacement == 1){
                    $this->creer_emplacement = true;
                } 
                $modifier_emplacement = $role->modifier_emplacement;
                if($modifier_emplacement == 1){
                    $this->modifier_emplacement = true;
                } 
                $supprimer_emplacement = $role->supprimer_emplacement;
                if($supprimer_emplacement == 1){
                    $this->supprimer_emplacement = true;
                } 

                // commande client                    
                $consulter_commande = $role->consulter_commande;
                if($consulter_commande == 1){
                    $this->consulter_commande = true;
                } 
                $creer_commande = $role->creer_commande;
                if($creer_commande == 1){
                    $this->creer_commande = true;
                }  
                $modifier_commande = $role->modifier_commande;
                if($modifier_commande == 1){
                    $this->modifier_commande = true;
                }
                $supprimer_commande = $role->supprimer_commande;
                if($supprimer_commande == 1){
                    $this->supprimer_commande = true;
                } 

                // Expedition
                $consulter_expedition = $role->consulter_expedition;
                if($consulter_expedition == 1){
                    $this->consulter_expedition = true;
                }
                $creer_expedition = $role->creer_expedition;
                if($creer_expedition == 1){
                    $this->creer_expedition = true;
                } 
                $supprimer_expedition = $role->supprimer_expedition;
                if($supprimer_expedition == 1){
                    $this->supprimer_expedition = true;
                } 

                // commande fournisseur                    
                $consulter_com_fourni = $role->consulter_com_fourni;
                if($consulter_com_fourni == 1){
                    $this->consulter_com_fourni = true;
                } 
                $creer_com_fourni = $role->creer_com_fourni;
                if($creer_com_fourni == 1){
                    $this->creer_com_fourni = true;
                }  
                $modifier_com_fourni = $role->modifier_com_fourni;
                if($modifier_com_fourni == 1){
                    $this->modifier_com_fourni = true;
                }
                $supprimer_com_fourni = $role->supprimer_com_fourni;
                if($supprimer_com_fourni == 1){
                    $this->supprimer_com_fourni = true;
                } 

                // Reception
                $consulter_reception = $role->consulter_reception;
                if($consulter_reception == 1){
                    $this->consulter_reception = true;
                }
                $creer_reception = $role->creer_reception;
                if($creer_reception == 1){
                    $this->creer_reception = true;
                } 
                $supprimer_reception = $role->supprimer_reception;
                if($supprimer_reception == 1){
                    $this->supprimer_reception = true;
                } 

                // Facturation client                    
                $consulter_facture = $role->consulter_facture;
                if($consulter_facture == 1){
                    $this->consulter_facture = true;
                } 
                $creer_facture = $role->creer_facture;
                if($creer_facture == 1){
                    $this->creer_facture = true;
                }  
                $modifier_facture = $role->modifier_facture;
                if($modifier_facture == 1){
                    $this->modifier_facture = true;
                }
                $supprimer_facture = $role->supprimer_facture;
                if($supprimer_facture == 1){
                    $this->supprimer_facture = true;
                } 

                // Reglement client
                $consulter_reglement = $role->consulter_reglement;
                if($consulter_reglement == 1){
                    $this->consulter_reglement = true;
                }
                $creer_reglement = $role->creer_reglement;
                if($creer_reglement == 1){
                    $this->creer_reglement = true;
                } 
                $supprimer_reglement = $role->supprimer_reglement;
                if($supprimer_reglement == 1){
                    $this->supprimer_reglement = true;
                } 

                // Facturation fournisseur                    
                $consulter_fact_fourni = $role->consulter_fact_fourni;
                if($consulter_fact_fourni == 1){
                    $this->consulter_fact_fourni = true;
                } 
                $creer_fact_fourni = $role->creer_fact_fourni;
                if($creer_fact_fourni == 1){
                    $this->creer_fact_fourni = true;
                }  
                $modifier_fact_fourni = $role->modifier_fact_fourni;
                if($modifier_fact_fourni == 1){
                    $this->modifier_fact_fourni = true;
                }
                $supprimer_fact_fourni = $role->supprimer_fact_fourni;
                if($supprimer_fact_fourni == 1){
                    $this->supprimer_fact_fourni = true;
                } 

                // Reglement fournisseur
                $consulter_reglement_fourni = $role->consulter_reglement_fourni;
                if($consulter_reglement_fourni == 1){
                    $this->consulter_reglement_fourni = true;
                }
                $creer_reglement_fourni = $role->creer_reglement_fourni;
                if($creer_reglement_fourni == 1){
                    $this->creer_reglement_fourni = true;
                } 
                $supprimer_reglement_fourni = $role->supprimer_reglement_fourni;
                if($supprimer_reglement_fourni == 1){
                    $this->supprimer_reglement_fourni = true;
                } 

                // Banque & Caisse                    
                $consulter_compte = $role->consulter_compte;
                if($consulter_compte == 1){
                    $this->consulter_compte = true;
                } 
                $creer_compte = $role->creer_compte;
                if($creer_compte == 1){
                    $this->creer_compte = true;
                }  
                $modifier_compte = $role->modifier_compte;
                if($modifier_compte == 1){
                    $this->modifier_compte = true;
                }
                $supprimer_compte = $role->supprimer_compte;
                if($supprimer_compte == 1){
                    $this->supprimer_compte = true;
                } 

                    // Écritures bancaires
                $consulter_ecriture = $role->consulter_ecriture;
                if($consulter_ecriture == 1){
                    $this->consulter_ecriture = true;
                }
                $mod_ecriture = $role->mod_ecriture;
                if($mod_ecriture == 1){
                    $this->mod_ecriture = true;
                } 

                    // Paiement divers
                $consulter_paie_divers = $role->consulter_paie_divers;
                if($consulter_paie_divers == 1){
                    $this->consulter_paie_divers = true;
                }
                $creer_paie_divers = $role->creer_paie_divers;
                if($creer_paie_divers == 1){
                    $this->creer_paie_divers = true;
                } 
                $modifier_paie_divers = $role->modifier_paie_divers;
                if($modifier_paie_divers == 1){
                    $this->modifier_paie_divers = true;
                } 
                $supprimer_paie_divers = $role->supprimer_paie_divers;
                if($supprimer_paie_divers == 1){
                    $this->supprimer_paie_divers = true;
                } 
                $voir_marge = $role->voir_marge;
                if($voir_marge == 1){
                    $this->voir_marge = true;
                } 

                // Virement interne
                $effectuer_vire_interne = $role->effectuer_vire_interne;
                if($effectuer_vire_interne == 1){
                    $this->effectuer_vire_interne = true;
                }  

                // Multi societe
                $consulter_societe = $role->consulter_societe;
                if($consulter_societe == 1){
                    $this->consulter_societe = true;
                }
                $creer_societe = $role->creer_societe;
                if($creer_societe == 1){
                    $this->creer_societe = true;
                } 
                $modifier_societe = $role->modifier_societe;
                if($modifier_societe == 1){
                    $this->modifier_societe = true;
                } 
                $changer_filiale = $role->changer_filiale;
                if($changer_filiale == 1){
                    $this->changer_filiale = true;
                }
                $transfer_stock_filiale = $role->transfer_stock_filiale;
                if($transfer_stock_filiale == 1){
                    $this->transfer_stock_filiale = true;
                }
                $consulter_transfert_filiale = $role->consulter_transfert_filiale;
                if($consulter_transfert_filiale == 1){
                    $this->consulter_transfert_filiale = true;
                }

                $this->acces_entite = $role->acces_entite ?? []; // ceci est special il affiche depuis un table Json                

                // Entite
                $consulter_entite = $role->consulter_entite;
                if($consulter_entite == 1){
                    $this->consulter_entite = true;
                }
                $creer_entite = $role->creer_entite;
                if($creer_entite == 1){
                    $this->creer_entite = true;
                } 
                $modifier_entite = $role->modifier_entite;
                if($modifier_entite == 1){
                    $this->modifier_entite = true;
                } 
                $supprimer_entite = $role->supprimer_entite;
                if($supprimer_entite == 1){
                    $this->supprimer_entite = true;
                }
                $activer_compte_enite = $role->activer_compte_enite;
                if($activer_compte_enite == 1){
                    $this->activer_compte_enite = true;
                }

                // Utilisateur
                $consulter_user = $role->consulter_user;
                if($consulter_user == 1){
                    $this->consulter_user = true;
                }
                $creer_user = $role->creer_user;
                if($creer_user == 1){
                    $this->creer_user = true;
                }
                $modifier_user = $role->modifier_user;
                if($modifier_user == 1){
                    $this->modifier_user = true;
                }
                $supprimer_user = $role->supprimer_user;
                if($supprimer_user == 1){
                    $this->supprimer_user = true;
                }

                // Role
                $consulter_role = $role->consulter_role;
                if($consulter_role == 1){
                    $this->consulter_role = true;
                }
                $creer_role = $role->creer_role;
                if($creer_role == 1){
                    $this->creer_role = true;
                }
                $modifier_role = $role->modifier_role;
                if($modifier_role == 1){
                    $this->modifier_role = true;
                }
                $supprimer_role = $role->supprimer_role;
                if($supprimer_role == 1){
                    $this->supprimer_role = true;
                }            

                // Departement / Poste
                $consulter_depart_poste = $role->consulter_depart_poste;
                if($consulter_depart_poste == 1){
                    $this->consulter_depart_poste = true;
                }
                $creer_depart_poste = $role->creer_depart_poste;
                if($creer_depart_poste == 1){
                    $this->creer_depart_poste = true;
                }
                $modifier_depart_poste = $role->modifier_depart_poste;
                if($modifier_depart_poste == 1){
                    $this->modifier_depart_poste = true;
                }
                $supprimer_depart_poste = $role->supprimer_depart_poste;
                if($supprimer_depart_poste == 1){
                    $this->supprimer_depart_poste = true;
                }

                // config
                $configurer = $role->configurer;
                if($configurer == 1){
                    $this->configurer = true;
                }

                // Fabrication           
                $liste_nomencla = $role->liste_nomencla;
                if($liste_nomencla == 1){
                    $this->liste_nomencla = true;
                }
                $creer_nomencla = $role->creer_nomencla;
                if($creer_nomencla == 1){
                    $this->creer_nomencla = true;
                }
                $modifier_nomencla = $role->modifier_nomencla;
                if($modifier_nomencla == 1){
                    $this->modifier_nomencla = true;
                }
                $supprimer_nomencla = $role->supprimer_nomencla;
                if($supprimer_nomencla == 1){
                    $this->supprimer_nomencla = true;
                }
                $liste_ordre_fab = $role->liste_ordre_fab;
                if($liste_ordre_fab == 1){
                    $this->liste_ordre_fab = true;
                }
                $creer_ordre_fab = $role->creer_ordre_fab;
                if($creer_ordre_fab == 1){
                    $this->creer_ordre_fab = true;
                }
                $modifier_ordre_fab = $role->modifier_ordre_fab;
                if($modifier_ordre_fab == 1){
                    $this->modifier_ordre_fab = true;
                }
                $supprimer_ordre_fab = $role->supprimer_ordre_fab;
                if($supprimer_ordre_fab == 1){
                    $this->supprimer_ordre_fab = true;
                }
                $ajouter_composant = $role->ajouter_composant;
                if($ajouter_composant == 1){
                    $this->ajouter_composant = true;
                }
                $supprimer_composant = $role->supprimer_composant;
                if($supprimer_composant == 1){
                    $this->supprimer_composant = true;
                }  
                
                // CRM
                $consulter_opportunite = $role->consulter_opportunite;
                if($consulter_opportunite == 1){
                    $this->consulter_opportunite = true;
                }  
                $creer_opportunite = $role->creer_opportunite;
                if($creer_opportunite == 1){
                    $this->creer_opportunite = true;
                }  
                $detail_opportunite = $role->detail_opportunite;
                if($detail_opportunite == 1){
                    $this->detail_opportunite = true;
                }  
                $modifier_opportunite = $role->modifier_opportunite;
                if($modifier_opportunite == 1){
                    $this->modifier_opportunite = true;
                }  
                $supprimer_opportunite = $role->supprimer_opportunite;
                if($supprimer_opportunite == 1){
                    $this->supprimer_opportunite = true;
                } 
                $consulter_etape = $role->consulter_etape;
                if($consulter_etape == 1){
                    $this->consulter_etape = true;
                }  
                $creer_etape = $role->creer_etape;
                if($creer_etape == 1){
                    $this->creer_etape = true;
                }  
                $modifier_etape = $role->modifier_etape;
                if($modifier_etape == 1){
                    $this->modifier_etape = true;
                }  
                $supprimer_etape = $role->supprimer_etape;
                if($supprimer_etape == 1){
                    $this->supprimer_etape = true;
                }              

                // Ticket
                $consulter_ticket = $role->consulter_ticket;
                if($consulter_ticket == 1){
                    $this->consulter_ticket = true;
                }  
                $creer_ticket = $role->creer_ticket;
                if($creer_ticket == 1){
                    $this->creer_ticket = true;
                }  
                $modifier_ticket = $role->modifier_ticket;
                if($modifier_ticket == 1){
                    $this->modifier_ticket = true;
                }  
                $supprimer_ticket = $role->supprimer_ticket;
                if($supprimer_ticket == 1){
                    $this->supprimer_ticket = true;
                } 

                // Taches
                $consulter_tache = $role->consulter_tache;
                if($consulter_tache == 1){
                    $this->consulter_tache = true;
                }  
                $creer_tache = $role->creer_tache;
                if($creer_tache == 1){
                    $this->creer_tache = true;
                }  
                $detail_tache = $role->detail_tache;
                if($detail_tache == 1){
                    $this->detail_tache = true;
                }  
                $modifier_tache = $role->modifier_tache;
                if($modifier_tache == 1){
                    $this->modifier_tache = true;
                } 
                $supprimer_tache = $role->supprimer_tache;
                if($supprimer_tache == 1){
                    $this->supprimer_tache = true;
                } 

                // Etape Taches
                $consulter_etapeTache = $role->consulter_etapeTache;
                if($consulter_etapeTache == 1){
                    $this->consulter_etapeTache = true;
                }  
                $creer_etapeTache = $role->creer_etapeTache;
                if($creer_etapeTache == 1){
                    $this->creer_etapeTache = true;
                }
                $modifier_etapeTache = $role->modifier_etapeTache;
                if($modifier_etapeTache == 1){
                    $this->modifier_etapeTache = true;
                } 
                $supprimer_etapeTache = $role->supprimer_etapeTache;
                if($supprimer_etapeTache == 1){
                    $this->supprimer_etapeTache = true;
                }
                
                // Commercial
                $consulter_souscription = $role->consulter_souscription;
                if($consulter_souscription == 1){
                    $this->consulter_souscription = true;
                }  
                $creer_souscription = $role->creer_souscription;
                if($creer_souscription == 1){
                    $this->creer_souscription = true;
                }

                // Restaurant
                $consulter_session_restau = $role->consulter_session_restau;
                if($consulter_session_restau == 1){
                    $this->consulter_session_restau = true;
                }
                $creer_session_restau = $role->creer_session_restau;
                if($creer_session_restau == 1){
                    $this->creer_session_restau = true;
                }                
                $passe_cmd_restau = $role->passe_cmd_restau;
                if($passe_cmd_restau == 1){
                    $this->passe_cmd_restau = true;
                }
                $voir_cmd_autre_restau = $role->voir_cmd_autre_restau;
                if($voir_cmd_autre_restau == 1){
                    $this->voir_cmd_autre_restau = true;
                }
                $eff_paie_restau = $role->eff_paie_restau;
                if($eff_paie_restau == 1){
                    $this->eff_paie_restau = true;
                }
                $consulter_espace = $role->consulter_espace;
                if($consulter_espace == 1){
                    $this->consulter_espace = true;
                }
                $creer_espace = $role->creer_espace;
                if($creer_espace == 1){
                    $this->creer_espace = true;
                }
                $modifier_espace = $role->modifier_espace;
                if($modifier_espace == 1){
                    $this->modifier_espace = true;
                }
                $supprimer_espace = $role->supprimer_espace;
                if($supprimer_espace == 1){
                    $this->supprimer_espace = true;
                }
                $consulter_table = $role->consulter_table;
                if($consulter_table == 1){
                    $this->consulter_table = true;
                }
                $creer_table = $role->creer_table;
                if($creer_table == 1){
                    $this->creer_table = true;
                }
                $modifier_table = $role->modifier_table;
                if($modifier_table == 1){
                    $this->modifier_table = true;
                }
                $supprimer_table = $role->supprimer_table;
                if($supprimer_table == 1){
                    $this->supprimer_table = true;
                }
                // Fin

                $deviseTva = DeviseTva :: where('societe_id',auth()->user()->societe_id)->limit(1)->orderBy('id','asc')->count();             
                if($deviseTva == 0){
                    $this->devise = 'FCFA';
                }
                else{
                    $deviseTva = DeviseTva :: where('societe_id',auth()->user()->societe_id)->limit(1)->orderBy('id','asc')->get(); 
                    $this->devise = $deviseTva[0]->devise;                
                }                    
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');    
                $entite_mod = Entite::where('id',auth()->user()->societe_id)->get(); 
                $jourValid = $entite_mod[0]->validite_mod; 
                // ceci pour trouver le nombre de jour restant avant expiration
                $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24));
                return view('livewire.administration.roles.detail-role',compact('title_fils','module','lien','dateJour','entiteFiliale','log','logCount','entite_mod'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant')); 
            }
            else{
                $title = 'Bienvenue'; 
                $module = 'Home';
                $title_fils = 'Bienvenue';
                $lien = 'bienvenue';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix'); 
                $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24));  
                toast()->error('Désolé M/Mme <strong>'.auth()->user()->name.'!</strong> <br> Vous n\'avez pas accès à ce module !')->position('top-end')->autoClose(15000)->background('#fff')->width('420px')->padding('5px'); 
                return view('livewire.bienvenue',compact('dateJour','entite_mod'))->layout('components.layouts.app',compact('title','module','title_fils','lien','active','entite_mod','dateJour','soldeClient','nbjoursRestant'));          
            }
        }
        else{
            $title = 'Bienvenue'; 
            $module = 'Home';
            $title_fils = 'Bienvenue';
            $lien = 'bienvenue';
            $active = request('active');
            $champ = request('champ');
            $choix = request('choix'); 
            $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24));  
            toast()->error('Bonjour M/Mme <strong>'.auth()->user()->name.'!</strong> <br> Votre accès a expiré le: <strong>' .date('d-m-Y', strtotime($jourValid)). '</strong>,<br> veuillez renouveller votre abonnement en cliquant sur un module svp !')->position('top-end')->autoClose(50000)->background('#fff')->width('520px')->padding('5px'); 
            return view('livewire.bienvenue',compact('dateJour','entite_mod'))->layout('components.layouts.app',compact('title','module','title_fils','lien','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));          
        }
    }
    public function confirmerDelete($id){ 
        $this->id = $id;
        $this->confirmer = $id;      
    } 
    public function supprimer($id){ 
        
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->supprimer_role;
            $role_non = Role::select('nom')->where('id',$id)->get();
            $nom_role = $role_non[0]->nom;
            if($autoriser == 1){   
                if($id){ 

                    if($nom_role == 'Administrateur' || $nom_role == 'Super-admin'){ dd('ok1');
                        $this->dispatch('alert',                    
                            title:'Vous ne pouvez pas supprimer ce rôle!',
                            timer:3000,
                            icon:'error',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        );   
                    }
                    else{  //dd($id);
                        Role::where('id',$id)->delete();  
                        $page = 'Role';
                        LogActivityModel::where('id_activite',$id)->where('page',$page)->delete();

                        $id_activite = $id;
                        LogActivity::addToLog('Rôle supprimé définitivement', $id_activite, $page);
                        $this->dispatch('alert',                    
                            title:'Suppression effectuée!',
                            timer:3000,
                            icon:'success',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        ); 
                        $this->redirect('/role_privillege?active=12&champ=1-2', navigate: true); 
                    }
                }
            } 
            else{ 
                
                $this->dispatch('alert',                    
                    title:'Vous n\'êtes pas autorisé à effectuer cette opération !',
                    timer:3000,
                    icon:'error',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                );                  
            } 
        }
        else{
            $this->dispatch('alert',                    
                title:'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!',
                timer:3000,
                icon:'error',
                toast:true,
                showConfirmButton: false,
                position:'top-end',
            ); 
        }        
    } 
    public function update(int $id){
        $this->id = $id; // pour envoyer id dans role
        $validedata = $this->validate([            
            'nom'=>'required', 
            // 'description'=>'required',
            // 'nom_user'=>'required',
            // 'user_id'=>'required',            
            'societe'=>'required', 
            // 'consulter_tier'=>'numeric',
            // 'creer_tier'=>'required',
            // 'modifier_tier'=>'required', 
            // 'supprimer_tier'=>'required',
            // 'consulter_produit'=>'required',
            // 'creer_produit'=>'required',
            // 'modifier_produit'=>'required',
            // 'supprimer_produit'=>'required',
            // 'consulter_categorie'=>'required',
            // 'creer_categorie'=>'required',
            // 'modifier_categorie'=>'required',
            // 'supprimer_categorie'=>'required',
            // 'mouvement_stock'=>'required',
            // 'correction_stock'=>'required',
            // 'consulter_entrepot'=>'required',
            // 'creer_entrepot'=>'required',
            // 'modifier_entrepot'=>'required',
            // 'supprimer_entrepot'=>'required',                           
            // 'consulter_transfert'=>'required',
            // 'creer_transfert'=>'required',
            // 'modifier_transfert'=>'required',
            // 'supprimer_transfert'=>'required',
            // 'consulter_inventaire'=>'required',
            // 'creer_inventaire'=>'required',
            // 'modifier_inventaire'=>'required',
            // 'supprimer_inventaire'=>'required',
            // 'tablobord_pv'=>'required',
            // 'consulter_session_pv'=>'required',
            // 'creer_session'=>'required',
            // 'voir_session_autre'=>'required',
            // 'pv'=>'required',
            // 'gerer_cmd_attente'=>'required',
            // 'voir_ecran_cuisine'=>'required',
            // 'changer_statut_cmd_cuisine'=>'required',
            // 'reservation'=>'required',
            // 'modifier_fidelite'=>'required',
            // 'consulter_emplacement'=>'required',
            // 'creer_emplacement'=>'required',
            // 'modifier_emplacement'=>'required',
            // 'supprimer_emplacement'=>'required',
            // 'consulter_commande'=>'required',
            // 'creer_commande'=>'required',
            // 'modifier_commande'=>'required',
            // 'supprimer_commande'=>'required',
            // 'consulter_expedition'=>'required',
            // 'creer_expedition'=>'required',
            // 'supprimer_expedition'=>'required',
            // 'consulter_com_fourni'=>'required',
            // 'creer_com_fourni'=>'required',
            // 'modifier_com_fourni'=>'required',
            // 'supprimer_com_fourni'=>'required',
            // 'consulter_reception'=>'required',
            // 'creer_reception'=>'required',
            // 'supprimer_reception'=>'required',
            // 'consulter_facture'=>'required',
            // 'creer_facture'=>'required',
            // 'modifier_facture'=>'required',
            // 'supprimer_facture'=>'required',
            // 'consulter_reglement'=>'required',
            // 'creer_reglement'=>'required',
            // 'supprimer_reglement'=>'required',
            // 'consulter_fact_fourni'=>'required',
            // 'creer_fact_fourni'=>'required',
            // 'modifier_fact_fourni'=>'required',
            // 'supprimer_fact_fourni'=>'required',
            // 'consulter_reglement_fourni'=>'required',
            // 'creer_reglement_fourni'=>'required',
            // 'supprimer_reglement_fourni'=>'required',
            // 'consulter_compte'=>'required',
            // 'creer_compte'=>'required',
            // 'modifier_compte'=>'required',
            // 'supprimer_compte'=>'required',
            // 'consulter_ecriture'=>'required',
            // 'mod_ecriture'=>'required',
            // 'consulter_paie_divers'=>'required',
            // 'creer_paie_divers'=>'required',
            // 'modifier_paie_divers'=>'required',
            // 'supprimer_paie_divers'=>'required',
            // 'voir_marge'=>'required',            
            // 'effectuer_vire_interne'=>'required',
            // 'consulter_societe'=>'required',
            // 'creer_societe'=>'required',
            // 'modifier_societe'=>'required',
            // 'changer_filiale'=>'required',
            // 'transfer_stock_filiale'=>'required',
            // 'consulter_transfert_filiale'=>'required',
            // 'consulter_entite'=>'required',
            // 'creer_entite'=>'required',
            // 'modifier_entite'=>'required',
            // 'supprimer_entite'=>'required',
            // 'activer_compte_enite'=>'required',
            // 'consulter_user'=>'required',
            // 'creer_user'=>'required', 
            // 'modifier_user'=>'required',
            // 'supprimer_user'=>'required',
            // 'consulter_role'=>'required',
            // 'creer_role'=>'required', 
            // 'modifier_role'=>'required',
            // 'supprimer_role'=>'required',   
            // 'consulter_depart_poste'=>'required',
            // 'creer_depart_poste'=>'required',
            // 'modifier_depart_poste'=>'required',
            // 'supprimer_depart_poste'=>'required',
            // 'configurer'=>'required',  
            // 'liste_nomencla'=>'required',  
            // 'creer_nomencla'=>'required',  
            // 'modifier_nomencla'=>'required',  
            // 'supprimer_nomencla'=>'required',  
            // 'liste_ordre_fab'=>'required',  
            // 'creer_ordre_fab'=>'required',  
            // 'modifier_ordre_fab'=>'required',  
            // 'supprimer_ordre_fab'=>'required',  
            // 'ajouter_composant'=>'required',  
            // 'supprimer_composant'=>'required', 
            // 'consulter_opportunite'=>'required', 
            // 'creer_opportunite'=>'required', 
            // 'detail_opportunite'=>'required', 
            // 'modifier_opportunite'=>'required', 
            // 'supprimer_opportunite'=>'required', 
            // 'consulter_etape'=>'required', 
            // 'creer_etape'=>'required', 
            // 'modifier_etape'=>'required', 
            // 'supprimer_etape'=>'required',
            // 'consulter_ticket'=>'required',   
            // 'creer_ticket'=>'required',   
            // 'modifier_ticket'=>'required',   
            // 'supprimer_ticket'=>'required',
            
            // 'consulter_tache'=>'required',  
            // 'creer_tache'=>'required',  
            // 'detail_tache'=>'required',  
            // 'modifier_tache'=>'required',  
            // 'supprimer_tache'=>'required',  
            // 'consulter_etapeTache'=>'required',  
            // 'creer_etapeTache'=>'required',  
            // 'modifier_etapeTache'=>'required',  
            // 'supprimer_etapeTache'=>'required', 
            // 'consulter_souscription'=>'required', 
            // 'creer_souscription'=>'required', 

            // 'consulter_session_restau'=>'required',
            // 'creer_session_restau'=>'required',
            // 'passe_cmd_restau'=>'required',
            // 'voir_cmd_autre_restau'=>'required',
            // 'eff_paie_restau'=>'required',
            // 'consulter_espace'=>'required',
            // 'creer_espace'=>'required',
            // 'modifier_espace'=>'required',
            // 'supprimer_espace'=>'required',
            // 'consulter_table'=>'required',
            // 'creer_table'=>'required',
            // 'modifier_table'=>'required',
            // 'supprimer_table'=>'required',
            
        ]);        
        
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_role;
            if($autoriser == 1){
                if($id){    
                    Role::find($this->id)->update(['description'=>$this->description,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id,
                           'consulter_tier'=>$this->consulter_tier,'creer_tier'=>$this->creer_tier,'modifier_tier'=>$this->modifier_tier, 'supprimer_tier'=>$this->supprimer_tier,
                           'consulter_produit'=>$this->consulter_produit,'creer_produit'=>$this->creer_produit,'modifier_produit'=>$this->modifier_produit,'supprimer_produit'=>$this->supprimer_produit,
                           'consulter_categorie'=>$this->consulter_categorie,'creer_categorie'=>$this->creer_categorie,'modifier_categorie'=>$this->modifier_categorie,'supprimer_categorie'=>$this->supprimer_categorie,
                           'mouvement_stock'=>$this->mouvement_stock,'correction_stock'=>$this->correction_stock,'consulter_entrepot'=>$this->consulter_entrepot,'creer_entrepot'=>$this->creer_entrepot,'modifier_entrepot'=>$this->modifier_entrepot,'supprimer_entrepot'=>$this->supprimer_entrepot,                           
                           'consulter_transfert'=>$this->consulter_transfert,'creer_transfert'=>$this->creer_transfert,'modifier_transfert'=>$this->modifier_transfert,'supprimer_transfert'=>$this->supprimer_transfert,
                           'consulter_inventaire'=>$this->consulter_inventaire,'creer_inventaire'=>$this->creer_inventaire,'modifier_inventaire'=>$this->modifier_inventaire,'supprimer_inventaire'=>$this->supprimer_inventaire,
                           'tablobord_pv'=>$this->tablobord_pv,'consulter_session_pv'=>$this->consulter_session_pv,'creer_session'=>$this->creer_session,'voir_session_autre'=>$this->voir_session_autre,'pv'=>$this->pv,
                           'gerer_cmd_attente'=>$this->gerer_cmd_attente,'voir_ecran_cuisine'=>$this->voir_ecran_cuisine,'changer_statut_cmd_cuisine'=>$this->changer_statut_cmd_cuisine,'reservation'=>$this->reservation,
                           'modifier_fidelite'=>$this->modifier_fidelite,'consulter_emplacement'=>$this->consulter_emplacement,'creer_emplacement'=>$this->creer_emplacement,'modifier_emplacement'=>$this->modifier_emplacement,'supprimer_emplacement'=>$this->supprimer_emplacement,
                           'consulter_commande'=>$this->consulter_commande,'creer_commande'=>$this->creer_commande,'modifier_commande'=>$this->modifier_commande,'supprimer_commande'=>$this->supprimer_commande,'consulter_expedition'=>$this->consulter_expedition,'creer_expedition'=>$this->creer_expedition,'supprimer_expedition'=>$this->supprimer_expedition,
                           'consulter_com_fourni'=>$this->consulter_com_fourni,'creer_com_fourni'=>$this->creer_com_fourni,'modifier_com_fourni'=>$this->modifier_com_fourni,'supprimer_com_fourni'=>$this->supprimer_com_fourni,'consulter_reception'=>$this->consulter_reception,'creer_reception'=>$this->creer_reception,'supprimer_reception'=>$this->supprimer_reception,
                           'consulter_facture'=>$this->consulter_facture,'creer_facture'=>$this->creer_facture,'modifier_facture'=>$this->modifier_facture,'supprimer_facture'=>$this->supprimer_facture,'consulter_reglement'=>$this->consulter_reglement,'creer_reglement'=>$this->creer_reglement,'supprimer_reglement'=>$this->supprimer_reglement,
                           'consulter_fact_fourni'=>$this->consulter_fact_fourni,'creer_fact_fourni'=>$this->creer_fact_fourni,'modifier_fact_fourni'=>$this->modifier_fact_fourni,'supprimer_fact_fourni'=>$this->supprimer_fact_fourni,'consulter_reglement_fourni'=>$this->consulter_reglement_fourni,'creer_reglement_fourni'=>$this->creer_reglement_fourni,'supprimer_reglement_fourni'=>$this->supprimer_reglement_fourni,
                           'consulter_compte'=>$this->consulter_compte,'creer_compte'=>$this->creer_compte,'modifier_compte'=>$this->modifier_compte,'supprimer_compte'=>$this->supprimer_compte,'consulter_ecriture'=>$this->consulter_ecriture,'mod_ecriture'=>$this->mod_ecriture,'consulter_paie_divers'=>$this->consulter_paie_divers,'creer_paie_divers'=>$this->creer_paie_divers,'modifier_paie_divers'=>$this->modifier_paie_divers,
                           'supprimer_paie_divers'=>$this->supprimer_paie_divers,'voir_marge'=>$this->voir_marge,'effectuer_vire_interne'=>$this->effectuer_vire_interne,
                           'consulter_societe'=>$this->consulter_societe,'creer_societe'=>$this->creer_societe,'modifier_societe'=>$this->modifier_societe,'changer_filiale'=>$this->changer_filiale,'transfer_stock_filiale'=>$this->transfer_stock_filiale,'consulter_transfert_filiale'=>$this->consulter_transfert_filiale,'acces_entite'=>$this->acces_entite,
                           'consulter_entite'=>$this->consulter_entite,'creer_entite'=>$this->creer_entite,'modifier_entite'=>$this->modifier_entite,'supprimer_entite'=>$this->supprimer_entite,'activer_compte_enite'=>$this->activer_compte_enite,
                           'consulter_user'=>$this->consulter_user,'creer_user'=>$this->creer_user, 'modifier_user'=>$this->modifier_user,'supprimer_user'=>$this->supprimer_user,
                           'consulter_role'=>$this->consulter_role,'creer_role'=>$this->creer_role, 'modifier_role'=>$this->modifier_role,'supprimer_role'=>$this->supprimer_role,   
                           'consulter_depart_poste'=>$this->consulter_depart_poste,'creer_depart_poste'=>$this->creer_depart_poste,'modifier_depart_poste'=>$this->modifier_depart_poste,'supprimer_depart_poste'=>$this->supprimer_depart_poste,'configurer'=>$this->configurer,
                           'liste_nomencla'=>$this->liste_nomencla,'creer_nomencla'=>$this->creer_nomencla,'modifier_nomencla'=>$this->modifier_nomencla,'supprimer_nomencla'=>$this->supprimer_nomencla,'liste_ordre_fab'=>$this->liste_ordre_fab,
                           'creer_ordre_fab'=>$this->creer_ordre_fab,'modifier_ordre_fab'=>$this->modifier_ordre_fab,'supprimer_ordre_fab'=>$this->supprimer_ordre_fab,'ajouter_composant'=>$this->ajouter_composant,'supprimer_composant'=>$this->supprimer_composant,
                           'consulter_opportunite'=>$this->consulter_opportunite,'creer_opportunite'=>$this->creer_opportunite,'detail_opportunite'=>$this->detail_opportunite,'modifier_opportunite'=>$this->modifier_opportunite,'supprimer_opportunite'=>$this->supprimer_opportunite,
                           'consulter_etape'=>$this->consulter_etape,'creer_etape'=>$this->creer_etape,'modifier_etape'=>$this->modifier_etape,'supprimer_etape'=>$this->supprimer_etape,
                           'consulter_ticket'=>$this->consulter_ticket,'creer_ticket'=>$this->creer_ticket,'modifier_ticket'=>$this->modifier_ticket,'supprimer_ticket'=>$this->supprimer_ticket,
                           'consulter_tache'=>$this->consulter_tache,'creer_tache'=>$this->creer_tache,'detail_tache'=>$this->detail_tache,'modifier_tache'=>$this->modifier_tache,'supprimer_tache'=>$this->supprimer_tache,
                           'consulter_etapeTache'=>$this->consulter_etapeTache,'creer_etapeTache'=>$this->creer_etapeTache,'modifier_etapeTache'=>$this->modifier_etapeTache,'supprimer_etapeTache'=>$this->supprimer_etapeTache,
                           'consulter_souscription'=>$this->consulter_souscription,'creer_souscription'=>$this->creer_souscription,
                           'consulter_session_restau'=>$this->consulter_session_restau,'creer_session_restau'=>$this->creer_session_restau,
                           'passe_cmd_restau'=>$this->passe_cmd_restau,
                           'voir_cmd_autre_restau'=>$this->voir_cmd_autre_restau,'eff_paie_restau'=>$this->eff_paie_restau,
                           'consulter_espace'=>$this->consulter_espace,'creer_espace'=>$this->creer_espace,
                           'modifier_espace'=>$this->modifier_espace,'supprimer_espace'=>$this->supprimer_espace,
                           'consulter_table'=>$this->consulter_table,'creer_table'=>$this->creer_table,
                           'modifier_table'=>$this->modifier_table,'supprimer_table'=>$this->supprimer_table,
                    ]);  
                    // $this->dispatch('roleUpdate'); 
                    $id_activite = $this->id;
                    $page = 'Role';
                    LogActivity::addToLog('Rôle » '.$this->nom.' modifié', $id_activite, $page);
                    $this->dispatch('alert',                    
                        title:'les rôles modifiés sauf le <strong>nom</strong>!',
                        timer:5000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );
                }
            }
            else{                 
                $this->dispatch('alert',                    
                    title:'Vous n\'êtes pas autorisé à effectuer cette opération !',
                    timer:3000,
                    icon:'error',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                );  
            } 
        }
        else{   
            $this->dispatch('alert',                    
                title:'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!',
                timer:3000,
                icon:'error',
                toast:true,
                showConfirmButton: false,
                position:'top-end',
            ); 
        }  
    }
}
