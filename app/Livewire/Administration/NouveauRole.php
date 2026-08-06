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

class NouveauRole extends Component
{
    public $id;
    public $ids;
    public $nom, $societe, $nom_user, $user_id, $description; 
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

    public function mount(){        
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->consulter_role;
            $this->updated_at = $role[0]->updated_at;
            if($autoriser == 0){
                alert()->error('Oups Désolé', 'Vous n\'êtes pas autorisé à ouvrir cette page !!!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
                $this->redirect('/bienvenue', navigate: true);
            }
        }
        else{
            alert()->error('Oups Désolé', 'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
            $this->redirect('/bienvenue', navigate: true);
        }            
        $this->societe = auth()->user()->societe;
        $this->parSociete = auth()->user()->societe;
        $this->auteur = auth()->user()->name;
        
    } 
    public function render()
    {
        $this->ids = request('id'); // id user
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();
        $jourValid = $entite_mod[0]->validite_mod;
        $mod_administration = $entite_mod[0]->mod_administration; 
        $soldeClient = $entite_mod[0]->solde; 
        if($dateJour <= $jourValid){
            if($mod_administration == 1){
                $title = 'Nouveau Rôle | WamsCo';
                $module = 'Paramètres';
                $title_fils = 'Nouveau rôle';
                $lien = 'role_privillege';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix'); 
                $dateJour = date('Y-m-d');                   
                
                $entite = Entite::orderBy('enseigne','asc')->get();
                if(auth()->user()->societe  == 'Administration'){
                    $entite = Entite::where('active',1)->orderBy('enseigne','asc')->orderBy('enseigne','asc')->get(); 
                }
                else{

                    $entite = Entite::where('enseigne',auth()->user()->societe)->where('active',1)->orderBy('enseigne','asc')->get();  
                }  

                $entit = Entite::where('enseigne',$this->societe)->where('active',1)->orderBy('enseigne','asc')->first();
                $societe_mere = $entit->societe_mere;
                $entiteFiliale = Entite::where('societe_mere',$societe_mere)->where('active',1)->orderBy('enseigne','asc')->get();
                // $entiteFiliale = Entite::where('societe_mere',auth()->user()->societe_mere)->where('active',1)->orderBy('enseigne','asc')->get(); 

                $page = 'Role';
                $log = LogActivityModel::where('user_societe',auth()->user()->societe)->where('page', $page)->limit(50)->orderBy('id','desc')->get();
                $logCount = $log->count();

                $deviseTva = DeviseTva :: where('societe',auth()->user()->societe)->limit(1)->orderBy('id','asc')->count();             
                if($deviseTva == 0){
                    $this->devise = 'FCFA';
                }
                else{
                    $deviseTva = DeviseTva :: where('societe',auth()->user()->societe)->limit(1)->orderBy('id','asc')->get(); 
                    $this->devise = $deviseTva[0]->devise;                
                }                    
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');    
                $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get(); 
                $jourValid = $entite_mod[0]->validite_mod; 
                // ceci pour trouver le nombre de jour restant avant expiration
                $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24));
                return view('livewire.administration.roles.nouveau-role',compact('title_fils','module','lien','dateJour','entite','entiteFiliale','log','logCount','entite_mod'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant')); 
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
    public function store(){
        
        $this->validate([            
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
        
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_role;
            if($autoriser == 1){                
                Role::create(['nom'=>$this->nom,'societe'=>$this->societe,'description'=>$this->description,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id,
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

                $dernier_id = Role::where('societe',$this->societe)->where('user_id',auth()->user()->id)->latest()->first()->id; 

                $entit = Entite::where('enseigne',$this->societe)->where('active',1)->orderBy('enseigne','asc')->first();
                $societe_mere = $entit->societe_mere;
                // creer ce role dans toutes les entites societe mere 
                $liste_entite= Entite::where('societe_mere',$societe_mere)->get(); 
                // $liste_entite= Entite::where('societe_mere',auth()->user()->societe_mere)->get(); 
                foreach($liste_entite as $liste_entites){                            
                    $rol = Role::find($dernier_id);
                    $new_rol = $rol->replicate();
                    $new_rol->societe = $liste_entites->enseigne;
                    $new_rol->nom_user = auth()->user()->name;
                    $new_rol->user_id = auth()->user()->id;                    
                    $new_rol->save();                        
                }                 
                Role::find($dernier_id)->update(['nom'=>$this->nom.'-Copie']);
                Role::where('id',$dernier_id)->delete();
                $dernier_id = $dernier_id+1; // ceci pour afiiche le bon role par redirection

                $id_activite = $dernier_id;
                $page = 'Role';
                LogActivity::addToLog('Rôle » '.$this->nom.' crée', $id_activite, $page);
                flash ('Rôle » <strong>'.$this->nom.'</strong> crée')->success(); 
                $this->redirect('/detail_role?id='.$dernier_id.'&active=12&champ=1-2', navigate: true);

                $this->dispatch('alert',                    
                    title:'Rôle <strong>'.$this->nom.'</strong> enregistré!',
                    timer:5000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                );                
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
