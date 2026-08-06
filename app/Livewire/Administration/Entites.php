<?php

namespace App\Livewire\Administration;

use Livewire\Component;
use Livewire\Attributes\Validate;  
use App\Helpers\LogActivity;
use App\Models\LogActivity as LogActivityModel;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Entite;
use App\Models\Role;
use App\Models\Utilisateur;
use App\Models\DeviseTva;
use App\Models\Produit;
// use App\Models\Agenda;
use App\Models\Entrepot;
use App\Models\Emplacement;
use App\Models\Departement;
use App\Models\Categorie;
use App\Models\CommandeAttenteEntete;
use App\Models\CommandeAttenteLigne;
use App\Models\CommandeClientEntete;
use App\Models\CommandeClientLigne;
use App\Models\CommandeFournisseurEntete;
use App\Models\CommandeFournisseurLigne; 
use App\Models\CompteBancaire;
use App\Models\EcritureBancaire;
use App\Models\ExpeditionClientEntete;
use App\Models\ExpeditionClientLigne;
use App\Models\factureClientEntete;
use App\Models\factureClientLigne;
use App\Models\Inventaire;
use App\Models\InventaireLigne;
use App\Models\Mouvement;
use App\Models\PaiementDiver;
use App\Models\Parametre;
use App\Models\PosFactureClientEntete;
use App\Models\PosFactureClientLigne;
use App\Models\Poste_travail;
use App\Models\PrixVente;
use App\Models\ReceptionFournisseurEntete;
use App\Models\ReceptionFournisseurLigne;
use App\Models\Reglement_fourni;
use App\Models\Reglement;
use App\Models\SessionPos;
use App\Models\Stock;
use App\Models\Tier;
use App\Models\Transfert;
use App\Models\TransfertLigne;
use App\Models\Ticket;
use App\Models\Tache;
use App\Models\SousTache;
use App\Models\Nomenclature;
use App\Models\OrdreFabrication;
use App\Models\ComposantNomenclature;
use App\Models\ComposantNomenclatureOrdreFab;
use App\Models\soldeClient;
use App\Models\TransfertFiliale;
use App\Models\TransfertFilialeLigne;
use App\Models\MagConsoComposantOf;
use App\Models\Etape;
use App\Models\EtapeTache;
use App\Models\GrilleSalariale;
use App\Models\CategoriePaie;
use App\Models\AvancePret;



class Entites extends Component
{
    protected $paginationTheme = 'bootstrap';
    use WithPagination;
    use WithFileUploads;

    // public $ids;
    public $id; // Edit et update
    public $code;
    public $enseigne;
    public $societe_mere;
    public $raison_sociale;
    public $responsable_societe;
    public $ville;
    public $pays;
    public $adresse;
    public $telephone;
    public $email;
    public $registre_commerce;
    public $niu;    
    public $condition_vente;    
    public $periode;
    public $montant_paye;
    public $solde;
    
    public $code_postal;
    public $site_web;
    public $commercial_charge; 
    
    public $logo;
    public $logos; // pour la modif entite users
    public $old_image;

    // Module
    public $mod_pointe_vente;
    public $mod_cuisine;
    public $mod_administration;
    public $mod_gestion_tier;
    public $mod_crm;
    public $mod_fabrication;
    public $mod_gestion_stock;
    public $mod_cmd;    
    public $mod_banque_caisse;
    public $mod_facturation;
    public $mod_multisociete;
    public $mod_ticket;
    public $mod_tache;
    public $mod_gestion_commercial;
    public $mod_restaurant;
    
    // public $mod_gestion_employe;
    public $nbre_user_max = 1;
    public $nombre_users = 0;
    public $nombre_societe = 1;    
    public $validite_mod;
    public $taux_commission;
    public $montant_commission;
    public $etat_commission;
    public $recommandation;
    public $activer_fidelite;
    public $montant_point;
    public $objectif_point;
    public $nom_user;
    public $nom_user_modif;
    
    public $created_at;
    public $updated_at;
   
    public $password;
    
    public $parSociete;
    public $active;
    public $devise;
    public $query;   
    public $parPage = 20;
    public $confirmer; 

    public $orderField = 'enseigne'; 
    public $orderDirection = 'ASC'; 

    public function setOrderField(string $name){
        if($name === $this->orderField){
            $this->orderDirection = $this->orderDirection === 'ASC' ? 'DESC' : 'ASC';
        }
        else{
            $this->orderField = $name;
            $this->reset('orderDirection');
        }
    }
    public function updatingQuery(){
        $this->resetPage();
    }
    public function mount(){        
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->consulter_entite;
            if($autoriser == 0){
                alert()->error('Oups Désolé', 'Vous n\'êtes pas autorisé à ouvrir cette page !!!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
                $this->redirect('/bienvenue', navigate: true);
            }
        }
        else{
            alert()->error('Oups Désolé', 'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
            $this->redirect('/bienvenue', navigate: true);
        }        
        $this->pays = 'Cameroon';
        $this->recommandation = 'contact@wamsco-cloud.net';
        // $this->parSociete = auth()->user()->societe_mere;
    }   
    public function render(){
    
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();
        $jourValid = $entite_mod[0]->validite_mod;
        $mod_administration = $entite_mod[0]->mod_administration;  
        $soldeClient = $entite_mod[0]->solde;
        if($dateJour <= $jourValid){
            if($mod_administration == 1){
                if(auth()->user()->societe == "Administration"){
                    $title = 'Liste entité | WamsCo';
                    $module = 'Paramètres';
                    $title_fils = 'Liste entité';
                    $lien = 'liste_entite?active=8&champ=8-3';
                    $active = request('active');
                    $champ = request('champ');
                    $choix = request('choix');      
                    $dateJour = date('Y-m-d');
                    toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');

                    $liste_entit = Entite::where('enseigne','like','%'.$this->query.'%')->where('enseigne','like','%'.$this->parSociete.'%')->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage); 
                    $entite_count = $liste_entit->count();  
                    $utilisateurCount = Utilisateur::count();
                    $utilisateurAll = Utilisateur::where('societe_mere',auth()->user()->societe)->orderBy('name','asc')->get(); 
                    $entite = Entite::orderBy('enseigne','Asc')->get();                                                      

                    $resultat = Entite::get();  
                    $nbreTotalEntite = $resultat->count();     

                    $derniereActivite = Entite::latest('updated_at')->first();

                    $page = 'Entite'; // Pour evenement lie
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
                    $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();          
                    $jourValid = $entite_mod[0]->validite_mod; 
                    // ceci pour trouver le nombre de jour restant avant expiration
                    $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24));
                    return view('livewire.administration.entite.entites',compact('title_fils','module','lien','dateJour','liste_entit','entite_count','utilisateurCount','utilisateurAll','entite','nbreTotalEntite','derniereActivite','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
                }
                else{

                    // Details entite users
                    $title = 'Détail Entité | WamsCo';
                    $module = 'Paramètres';
                    $title_fils = 'Détail entité';
                    $lien = 'entite';
                    $active = request('active');
                    $champ = request('champ');
                    $choix = request('choix');      
                    $dateJour = date('Y-m-d');
                    toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');                
                    $utilisateurAll = Utilisateur::where('societe_mere',auth()->user()->societe)->orderBy('name','asc')->get();
                    $test = Entite::where('enseigne',auth()->user()->societe)->where('active',1)->count(); 
                    if($test > 0){
                        $liste_entit = Entite::where('enseigne',auth()->user()->societe)->where('active',1)->first(); 
                        $this->id = $liste_entit->id; // important gere le suivant et precedent                
                        $this->enseigne = $liste_entit->enseigne;
                        $this->societe_mere = $liste_entit->societe_mere;
                        $this->active = $liste_entit->active;                
                        $this->raison_sociale = $liste_entit->raison_sociale;
                        $this->ville = $liste_entit->ville;
                        $this->pays = $liste_entit->pays;
                        $this->adresse = $liste_entit->adresse;
                        $this->telephone = $liste_entit->telephone;
                        $this->email = $liste_entit->email;       
                        $this->registre_commerce = $liste_entit->registre_com;
                        $this->niu = $liste_entit->niu;                           
                        $this->responsable_societe = $liste_entit->responsable_societe;       
                        $this->condition_vente = $liste_entit->condition_vente;
                        $this->code_postal = $liste_entit->code_postal;
                        $this->site_web = $liste_entit->site_web;
                        $this->commercial_charge = $liste_entit->commercial_charge;
                        $this->periode = $liste_entit->periode;
                        $this->montant_paye = $liste_entit->montant_paye;
                        $this->solde = $liste_entit->solde;                    
                        $this->validite_mod = $liste_entit->validite_mod;          
                        $this->nbre_user_max = $liste_entit->nbre_user_max;  
                        $this->nombre_users = $liste_entit->nombre_users; 
                        $this->nombre_societe = $liste_entit->nombre_societe; 
                        $this->recommandation = $liste_entit->recommandation;          
                        $this->taux_commission = $liste_entit->taux_commission;     
                        $this->etat_commission = $liste_entit->etat_commission;
                        $this->logo = $liste_entit->logo;
                        $this->montant_commission = $liste_entit->montant_commission;
                        $this->activer_fidelite = $liste_entit->activer_fidelite;
                        $this->montant_point = $liste_entit->montant_point;
                        $this->objectif_point = $liste_entit->objectif_point; 
                        $this->nom_user = $liste_entit->nom_user; 
                        $this->nom_user_modif = $liste_entit->nom_user_modif;                  
                        $this->created_at = $liste_entit->created_at;
                        $this->updated_at = $liste_entit->updated_at;
                        $this->user_id = $liste_entit->user_id;                        
                    }
                    else{
                        $this->redirect('/bienvenue?active=1', navigate: true);                              
                    }               
                    $user = Utilisateur::where('id',$this->user_id)->get();
                    $page = 'Entite'; // Pour evenement lie
                    $log = LogActivityModel::where('user_societe',auth()->user()->societe)->where('id_activite', $this->id)->where('page', $page)->limit(10)->orderBy('id','desc')->get();
                    $logCount = $log->count();

                    $deviseTva = DeviseTva :: where('societe',auth()->user()->societe)->limit(1)->orderBy('id','asc')->count(); 
                    if($deviseTva == 0){
                        $this->devise = 'FCFA';
                    }
                    else{
                        $deviseTva = DeviseTva :: where('societe',auth()->user()->societe)->limit(1)->orderBy('id','asc')->get(); 
                        $this->devise = $deviseTva[0]->devise;
                    }
                    $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();          
                    $jourValid = $entite_mod[0]->validite_mod; 
                    // ceci pour trouver le nombre de jour restant avant expiration
                    $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24));
                    return view('livewire.administration.entite.detail_entite_users',compact('title_fils','module','lien','dateJour','utilisateurAll','user','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
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
            return view('livewire.bienvenue',compact('dateJour','entite_mod'))->layout('components.layouts.app',compact('title','module','title_fils','lien','active','entite_mod','dateJour','soldeClient','nbjoursRestant'));          
        }
    }
    public function store(){ 
        $this->validate([   
            'raison_sociale'=>'required|max:100|unique:entites,enseigne,{$entites->id}',  
            'validite_mod'=> 'required', 
            'nbre_user_max'=> 'required',   
            'nombre_societe'=>'required|numeric',                                          
            'montant_paye'=>'required|numeric',                                          
            'periode'=>'required',                                          
            'recommandation'=>'required|email|max:255',                                          
            'taux_commission'=>'required|numeric',                                          
            'etat_commission'=>'required', 
            // 'condition_vente'=>'max:255',                                     
        ]);       
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_entite;
            if($autoriser == 1){                
               
                    $activer = 1;
                    $dateJour = date('Y-m-d');
                    $this->validite_mod;
                    $nbjoursRestant = round((strtotime($this->validite_mod) - strtotime($dateJour))/(60*60*24));
                    // Calcul commission
                    $montantCommission = $this->montant_paye * $this->taux_commission / 100;
                    $solde_final = 0;
                    Entite :: create(['enseigne'=>$this->raison_sociale,'societe_mere'=>$this->raison_sociale,'active'=>$activer,'solde'=>$solde_final,'mod_pointe_vente'=>$this->mod_pointe_vente,'mod_cuisine'=>$this->mod_cuisine,
                        'mod_administration'=>$this->mod_administration,'mod_gestion_tier'=>$this->mod_gestion_tier,'mod_crm'=>$this->mod_crm,'mod_gestion_stock'=>$this->mod_gestion_stock,'mod_banque_caisse'=>$this->mod_banque_caisse,
                        'mod_facturation'=>$this->mod_facturation,'mod_cmd'=>$this->mod_cmd,'mod_multisociete'=>$this->mod_multisociete,'mod_ticket'=>$this->mod_ticket,'mod_tache'=>$this->mod_tache,'mod_gestion_commercial'=>$this->mod_gestion_commercial,
                        'mod_restaurant'=>$this->mod_restaurant,
                        'mod_fabrication'=>$this->mod_fabrication,'validite_mod'=>$this->validite_mod,
                        'jour_restant'=>$nbjoursRestant,'nbre_user_max'=>$this->nbre_user_max,'nombre_users'=>$this->nombre_users,
                        'nombre_societe'=>$this->nombre_societe,'montant_paye'=>$this->montant_paye,'periode'=>$this->periode,'recommandation'=>$this->recommandation,
                        'taux_commission'=>$this->taux_commission,'montant_commission'=>$montantCommission,'etat_commission'=>$this->etat_commission,
                        'nom_user'=>auth()->user()->email,'user_id'=>auth()->user()->id]); 

                    $dernier_id = Entite::where('enseigne',$this->raison_sociale)->where('user_id',auth()->user()->id)->latest()->first()->id; 
                                                    
                    // Dupliquer les roles en fonction des disponibilites 
                    $liste_role = Role::where('societe',auth()->user()->societe)->get(); 
                    foreach($liste_role as $liste_roles){  
                        $new_rol = $liste_roles->replicate();
                        $new_rol->societe = $this->raison_sociale;  
                        $new_rol->nom = $liste_roles->nom;
                        $new_rol->nom_user = auth()->user()->name;
                        $new_rol->user_id = auth()->user()->id;
                        $new_rol->user_id = auth()->user()->id;
                        $new_rol->acces_entite = [$dernier_id => true,]; // initier le tableau avec l'id entite et le mettre a true                                       
                        $new_rol->save();                        
                    }   
                    // ceci recupere le dernier enregistrement cree a l'instant
                    $dernier_id = Entite::where('enseigne',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id;

                    $id_activite = $dernier_id;
                    $page = 'Produits';
                    LogActivity::addToLog('Entité » '.$this->raison_sociale.' créé', $id_activite, $page);                  
                    $this->dispatch('alert',                    
                        title:'Raison sociale ('.$this->raison_sociale.') enregistrée!',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    ); 
                    $this->redirect('/entite?active=12&champ=1-3', navigate: true);                
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
    public function changeEtat(int $id, int $etat){ 
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->activer_compte_enite;
            if($autoriser == 1){         
                if($etat == 1){
                    $ferme = 0;
                    Entite::find($id)->update(['active'=>$ferme,'nom_user'=>auth()->user()->email,'user_id'=>auth()->user()->id]); 
                    
                    $id_activite = $id;
                    $page = 'Entite';
                    LogActivity::addToLog('Etat entité (désactivé)', $id_activite, $page); 
                    $this->dispatch('alert',                    
                        title:'Entité désactivé avec succès!',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );  
                }
                elseif($etat == 0){
                    $ouvert = 1;
                    Entite::find($id)->update(['active'=>$ouvert,'nom_user'=>auth()->user()->email,'user_id'=>auth()->user()->id]); 

                    $id_activite = $id;
                    $page = 'Entite';
                    LogActivity::addToLog('Etat entité (activé)', $id_activite, $page); 
                    $this->dispatch('alert',                    
                        title:'Entité activé avec succès!',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );    
                }
                else{
                    $this->dispatch('alert',                    
                        title:'Vous ne pouvez modifier cet état ici!',
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
    // Modifier logo ou infos
    public function edit(int $id){ 

        $entites = Entite::where('id',5)->first();
        $this->id = $entites->id;
        $this->enseigne = $entites->enseigne;
        $this->raison_sociale = $entites->raison_sociale;
        $this->ville = $entites->ville;
        $this->pays = $entites->pays;
        $this->adresse = $entites->adresse;
        $this->telephone = $entites->telephone;
        $this->email = $entites->email;       
        $this->registre_commerce = $entites->registre_com;
        $this->niu = $entites->niu;               
        $this->responsable_societe = $entites->responsable_societe;       
        $this->condition_vente = $entites->condition_vente;
        $this->code_postal = $entites->code_postal;
        $this->site_web = $entites->site_web;
        $this->commercial_charge = $entites->commercial_charge;
        $this->periode = $entites->periode;
        $this->montant_paye = $entites->montant_paye;
        $this->validite_mod = $entites->validite_mod;          
        $this->nbre_user_max = $entites->nbre_user_max;  
        $this->nombre_societe = $entites->nombre_societe; 
        $this->recommandation = $entites->recommandation;          
        $this->taux_commission = $entites->taux_commission;     
        $this->etat_commission = $entites->etat_commission;
        // $this->logo = $entites->logo; 
        // $this->old_image = $entites->logo;
    }
     // Modifier logo entite
     public function valider_logo(){
        $this->validate([                      
            'logos'=>'required|image|mimes:jpeg,jpg,png,gif|max:2048',          
        ]);
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count(); 
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_entite;
            if($autoriser == 1){
                if($this->id){   
                    $path = $this->logos->store('logo_entite','public');
                    Entite::find($this->id)->update(['logo'=>$path,'nom_user_modif'=>auth()->user()->email,'user_id_modif'=>auth()->user()->id]);
                    session()->flash('msg-success', 'Logo modifié !');
                    $this->reset('logos');

                    $id_activite = $this->id;
                    $page = 'Entite';
                    LogActivity::addToLog('Logo entité modifié', $id_activite, $page);
                    $this->dispatch('alert',                    
                        title:'Logo modifié!',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    ); 
                    $this->redirect('/entite?id='.$this->id.'&active=12&champ=1-3', navigate: true);  
                }
            }
            else{ 
                $this->dispatch('alert',                    
                    title:'Vous n\'êtes pas autorisé à effectuer cette opération!',
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
    // Mise a jour cote client 
    public function update(){ 
        $validedata = $this->validate([       
        'raison_sociale'=>'required|max:100', 
        'responsable_societe'=>'required|max:255',                              
        'ville'=>'required|max:255', 
        'pays'=>'required|max:255', 
        'adresse'=>'required|max:255',     
        'telephone'=>'required|max:255',
        'email'=>'nullable|email|max:255',     
        'registre_commerce'=>'nullable|max:255',                                      
        'niu'=>'nullable|max:255',                              
        'condition_vente'=>'nullable|max:255',  
        'code_postal'=>'nullable|max:255',  
        'site_web'=>'nullable|max:255',  
        // 'commercial_charge'=>'nullable|max:255',
        ]);        
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_entite;
            if($autoriser == 1){                
                if($this->id){ 
                    
                    Entite::find($this->id)->update(['raison_sociale'=>$this->raison_sociale,'ville'=>$this->ville,'adresse'=>$this->adresse,'telephone'=>$this->telephone,
                    'responsable_societe'=>$this->responsable_societe,'pays'=>$this->pays,'email'=>$this->email,'registre_com'=>$this->registre_commerce,'niu'=>$this->niu,'condition_vente'=>$this->condition_vente,
                    'code_postal'=>$this->code_postal,'site_web'=>$this->site_web,'nom_user_modif'=>auth()->user()->email,'user_id_modif'=>auth()->user()->id]);                                      

                    $id_activite = $this->id;
                    $page = 'Entite';
                    LogActivity::addToLog('Entité » '.$this->raison_sociale.' modifié', $id_activite, $page);
                    $this->dispatch('alert',                    
                        title:'Entité ('.$this->raison_sociale.') modifié!',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    ); 
                    $this->redirect('/entite?id='.$this->id.'&active=12&champ=1-3', navigate: true);
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
    public function supprimer(){
        $this->validate([
            'enseigne'=>'required',
            'password'=>'required',                    
        ]); 
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->supprimer_entite;
            if($autoriser == 1){
                if(auth()->user()->societe == "Administration"){   
                    if($this->password == 'railley@2019'){
                        $entit = Entite::where('enseigne',$this->enseigne)->first(); 
                        $id =   $entit->id;                     
                        Entite::where('enseigne',$this->enseigne)->delete();                        
                        Role::where('societe',$this->enseigne)->delete();  
                        Utilisateur::where('societe',$this->enseigne)->delete(); 
                        DeviseTva::where('societe',$this->enseigne)->delete();
                        Produit::where('societe',$this->enseigne)->delete();
                        Emplacement ::where('societe',$this->enseigne)->delete(); 
                       
                        Departement::where('societe',$this->enseigne)->delete();
                        Categorie::where('societe',$this->enseigne)->delete();
                        CommandeAttenteEntete::where('societe',$this->enseigne)->delete();
                        CommandeAttenteLigne::where('societe',$this->enseigne)->delete();
                        CommandeClientEntete::where('societe',$this->enseigne)->delete();
                        CommandeClientLigne::where('societe',$this->enseigne)->delete();
                        CommandeFournisseurEntete::where('societe',$this->enseigne)->delete(); 
                        CommandeFournisseurLigne::where('societe',$this->enseigne)->delete(); 

                        CompteBancaire::where('societe',$this->enseigne)->delete();                        
                        EcritureBancaire::where('societe',$this->enseigne)->delete();                        
                        Entrepot::where('societe',$this->enseigne)->delete();                        
                        ExpeditionClientEntete::where('societe',$this->enseigne)->delete();                        
                        ExpeditionClientLigne::where('societe',$this->enseigne)->delete();                        
                        factureClientEntete::where('societe',$this->enseigne)->delete();                        
                        factureClientLigne::where('societe',$this->enseigne)->delete();                        
                        Inventaire::where('societe',$this->enseigne)->delete();                        
                        InventaireLigne::where('societe',$this->enseigne)->delete();                        
                        Mouvement::where('societe',$this->enseigne)->delete();                        
                        PaiementDiver::where('societe',$this->enseigne)->delete();                        
                        Parametre::where('societe',$this->enseigne)->delete();                        
                        PosFactureClientEntete::where('societe',$this->enseigne)->delete();                        
                        PosFactureClientLigne::where('societe',$this->enseigne)->delete();  

                        Poste_travail::where('societe',$this->enseigne)->delete();                        
                        PrixVente::where('societe',$this->enseigne)->delete();                        
                        ReceptionFournisseurEntete::where('societe',$this->enseigne)->delete();                        
                        ReceptionFournisseurLigne::where('societe',$this->enseigne)->delete();                        
                        Reglement_fourni::where('societe',$this->enseigne)->delete();                        
                        Reglement::where('societe',$this->enseigne)->delete();                        
                        SessionPos::where('societe',$this->enseigne)->delete();    

                        Stock::where('societe',$this->enseigne)->delete();                        
                        Tier::where('societe',$this->enseigne)->delete();                        
                        Transfert::where('societe',$this->enseigne)->delete();                        
                        TransfertLigne::where('societe',$this->enseigne)->delete();                        
                        Ticket::where('societe',$this->enseigne)->delete();                        
                        Tache::where('societe',$this->enseigne)->delete();                        
                        SousTache::where('societe',$this->enseigne)->delete();                        
                        Nomenclature ::where('societe',$this->enseigne)->delete();
                        OrdreFabrication ::where('societe',$this->enseigne)->delete();
                        ComposantNomenclatureOrdreFab ::where('societe',$this->enseigne)->delete();
                        ComposantNomenclature ::where('societe',$this->enseigne)->delete();
                        soldeClient ::where('enseigne',$this->enseigne)->delete();
                        TransfertFiliale ::where('societe',$this->enseigne)->delete();
                        TransfertFilialeLigne ::where('societe',$this->enseigne)->delete();  
                        MagConsoComposantOf ::where('societe',$this->enseigne)->delete();  
                        Etape ::where('societe',$this->enseigne)->delete(); 
                        EtapeTache ::where('societe',$this->enseigne)->delete();
                        GrilleSalariale ::where('societe',$this->enseigne)->delete(); 
                        CategoriePaie ::where('societe',$this->enseigne)->delete();
                        AvancePret ::where('societe',$this->enseigne)->delete();                           

                        $this->password = '';
                        $page = 'Entite';
                        LogActivityModel::where('id_activite',$id)->where('page',$page)->delete();

                        $id_activite = $id;
                        LogActivity::addToLog('Entité supprimée définitivement', $id_activite, $page);
                        $this->dispatch('alert',                    
                            title:'Suppression effectuée',
                            timer:3000,
                            icon:'success',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        ); 
                    }
                    else{
                        $this->password = '';
                        $this->dispatch('alert',                    
                            title:'Votre mot de passe est incorrect !',
                            timer:3000,
                            icon:'error',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        ); 
                    } 
                }
            }
            else{  
                $this->dispatch('alert',                    
                    title:'Vous n\'êtes pas autorisé à effectuer cette opération !!!',
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
