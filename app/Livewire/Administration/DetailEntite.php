<?php

namespace App\Livewire\Administration;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;  
use Livewire\WithPagination;
use Livewire\WithFileUploads; 
use App\Helpers\LogActivity;
use App\Models\LogActivity as LogActivityModel;
use App\Models\Entite;
use App\Models\DeviseTva;
use App\Models\Role;
use App\Models\Utilisateur;
use App\Models\Departement;
use App\Models\Poste_travail;
use App\Models\soldeClient;
use App\Models\CompteBancaire;
use App\Models\EcritureBancaire;
use App\Models\ReglementCommercial;


class DetailEntite extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $ids;
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
    public $old_image;
    public $activer;
    public $iden;
    
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
    public $nbre_user_max;
    public $nombre_users = 1;
    public $nombre_societe = 1;    
    public $validite_mod;
    public $taux_commission;
    public $montant_commission;
    public $etat_commission;
    public $recommandation;
    
    public $montant_recu;    
    public $sens;
    
    public $date_reglement;
    public $date_created_at; 
    public $mode_reglement; 
    public $montant_reglement;
    public $compte_bancaire;
    public $num_cheq_virement;
    public $emeteur;
    public $banque_cheque;
    public $commentaire;
    public $nom_user;
    public $user_id;
           
    
    public $devise;
    public $confirmer; 
    public $confirmation;    

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
    }
    public function render(){    
        $this->ids = request('id'); // id entite
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();
        $jourValid = $entite_mod[0]->validite_mod; 
        $mod_administration = $entite_mod[0]->mod_administration; 
        $soldeClient = $entite_mod[0]->solde;
        if($dateJour <= $jourValid){
            if($mod_administration == 1){
                $title = 'Détails Entité | WamsCo';
                $module = 'Paramètres';
                $title_fils = 'Détails entité';
                $lien = 'detail_entite';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix'); 
                $dateJour = date('Y-m-d');                   
                
                $entite = Entite::orderBy('enseigne','asc')->get();               
                $utilisateurAll = Utilisateur::where('societe_mere',auth()->user()->societe)->orderBy('name','asc')->get(); 

                if($this->ids){
                    // ceci au chargement de la page
                    if(auth()->user()->societe == "Administration"){
                        // ceci permet de creer une entite dans autre societe en restant dans l'administration
                        $entit = Entite::where('id',$this->ids)->get();
                        $ent = Entite::where('id',$this->ids)->first(); 
                        $user_id = $ent->user_id;  
                    }
                    else{
                        $entit = Entite::where('enseigne',auth()->user()->societe)->where('id',$this->ids)->get();  
                        $ent = Entite::where('enseigne',auth()->user()->societe)->where('id',$this->ids)->first(); 
                        $user_id = $ent->user_id;   
                    }                      
                    $entitCount = $entit->count();
                    $reglementCom = ReglementCommercial::where('societe',auth()->user()->societe)->where('id_societe',$this->ids)->orderBy('id','desc')->get();
                    $dejaRegler = $reglementCom->sum('montant_regler');                    
                    
                    $page = 'Entite'; // Pour evenement lie
                    $log = LogActivityModel::where('user_societe',auth()->user()->societe)->where('id_activite', $this->ids)->where('page', $page)->limit(6)->orderBy('id','desc')->get();
                    $logCount = $log->count(); 
                }
                else{
                    // ceci quand on click sur Edit() ou modifier
                    if(auth()->user()->societe == "Administration"){
                        // ceci permet de creer une entite dans autre societe en restant dans l'administration
                        $entit = Entite::where('id',$this->id)->get();
                        $ent = Entite::where('id',$this->id)->first(); 
                        $user_id = $ent->user_id;    
                    }
                    else{
                        $entit = Entite::where('enseigne',auth()->user()->societe)->where('id',$this->id)->get();  
                        $ent = Entite::where('enseigne',auth()->user()->societe)->where('id',$this->id)->first(); 
                        $user_id = $ent->user_id;   
                        
                    }                        
                    $entitCount = $entit->count();
                    $reglementCom = ReglementCommercial::where('societe',auth()->user()->societe)->where('id_societe',$this->id)->orderBy('id','desc')->get();
                    $dejaRegler = $reglementCom->sum('montant_regler');

                    $page = 'Entite'; // Pour evenement lie
                    $log = LogActivityModel::where('user_societe',auth()->user()->societe)->where('id_activite', $this->id)->where('page', $page)->limit(50)->orderBy('id','desc')->get();
                    $logCount = $log->count();                 
                }
                
                $user = Utilisateur::where('id',$user_id)->get();
                $banque = CompteBancaire :: where('societe',auth()->user()->societe)->where('etat',1)->orderBy('nom_compte_bancaire','asc')->get();                
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
                return view('livewire.administration.entite.detail-entite',compact('title_fils','module','lien','dateJour','entit','entitCount','reglementCom','dejaRegler','user','utilisateurAll','banque','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant')); 
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
    public function edit(int $id){ 

        $entites = Entite::where('id',$id)->first();
        $this->id = $entites->id; // important gere le suivant et precedent
        $this->ids = $entites->id; // important gere le suivant et precedent
        $this->enseigne = $entites->enseigne;
        $this->societe_mere = $entites->societe_mere;
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
        $this->solde = $entites->solde;        
        $this->validite_mod = $entites->validite_mod;          
        $this->nbre_user_max = $entites->nbre_user_max;  
        $this->nombre_societe = $entites->nombre_societe; 
        $this->recommandation = $entites->recommandation;          
        $this->montant_commission = $entites->montant_commission;     
        $this->taux_commission = $entites->taux_commission;     
        $this->etat_commission = $entites->etat_commission;
        $this->old_image = $entites->logo; 
        $this->nom_user = $entites->nom_user; 
        $this->user_id = $entites->user_id; 
        $this->date_created_at = $entites->created_at;         
        $this->date_reglement = date('Y-m-d'); 
            

        // $this->mod_pointe_vente = $entites->mod_pointe_vente;  
        if($entites->mod_pointe_vente == 1){
            $this->mod_pointe_vente = true;
        } 
        // $this->mod_cuisine = $entites->mod_cuisine;  
        if($entites->mod_cuisine == 1){
            $this->mod_cuisine = true;
        }             
        // $this->mod_administration = $entites->mod_administration;        
        if($entites->mod_administration == 1){
            $this->mod_administration = true;
        }   
        // $this->mod_gestion_tier = $entites->mod_gestion_tier; 
        if($entites->mod_gestion_tier == 1){
            $this->mod_gestion_tier = true;
        } 
        // $this->mod_crm = $entites->mod_crm; 
        if($entites->mod_crm == 1){
            $this->mod_crm = true;
        }         
        // $this->mod_gestion_stock = $entites->mod_gestion_stock; 
        if($entites->mod_gestion_stock == 1){
            $this->mod_gestion_stock = true;
        } 
        // $this->mod_banque_caisse = $entites->mod_banque_caisse; 
        if($entites->mod_banque_caisse == 1){
            $this->mod_banque_caisse = true;
        }  
        // $this->mod_cmd = $entites->mod_cmd;  
        if($entites->mod_cmd == 1){
            $this->mod_cmd = true;
        }  
        // $this->mod_facturation = $entites->mod_facturation;
        if($entites->mod_facturation == 1){
            $this->mod_facturation = true;
        }  
        // $this->mod_multisociete = $entites->mod_multisociete; 
        if($entites->mod_multisociete == 1){
            $this->mod_multisociete = true;
        }  
        // $this->mod_fabrication = $entites->mod_fabrication;
        if($entites->mod_fabrication == 1){
            $this->mod_fabrication = true;
        } 
        // $this->mod_ticket = $entites->mod_ticket;
        if($entites->mod_ticket == 1){
            $this->mod_ticket = true;
        } 
        // $this->mod_tache = $entites->mod_tache;
        if($entites->mod_tache == 1){
            $this->mod_tache = true;
        } 
        // $this->mod_gestion_commercial = $entites->mod_gestion_commercial;
        if($entites->mod_gestion_commercial == 1){
            $this->mod_gestion_commercial = true;
        } 
        // $this->mod_restaurant = $entites->mod_restaurant;
        if($entites->mod_restaurant == 1){
            $this->mod_restaurant = true;
        }           
        
        
        // $this->mod_gestion_employe = $entites->mod_gestion_employe;
        // if($entites->mod_gestion_employe == 1){
        //     $this->mod_gestion_employe = true;
        // }          
          
    }
    public function update(){ 
        $validedata = $this->validate([       
        'raison_sociale'=>'required|max:255', 
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
        'commercial_charge'=>'required|max:255',                              
        ]);        
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_entite;
            if($autoriser == 1){                
                if($this->id){                     
                    Entite::find($this->id)->update(['raison_sociale'=>$this->raison_sociale,'ville'=>$this->ville,'adresse'=>$this->adresse,'telephone'=>$this->telephone,
                    'responsable_societe'=>$this->responsable_societe,'pays'=>$this->pays,'email'=>$this->email,'registre_com'=>$this->registre_commerce,'niu'=>$this->niu,'condition_vente'=>$this->condition_vente,
                    'code_postal'=>$this->code_postal,'site_web'=>$this->site_web,'commercial_charge'=>$this->commercial_charge,'nom_user_modif'=>auth()->user()->email,'user_id_modif'=>auth()->user()->id]);                                      
                    // $this->dispatch('societeUpdate'); 
                    $id_activite = $this->id;
                    $page = 'Entite';
                    LogActivity::addToLog('Entité » '.$this->enseigne.' modifié', $id_activite, $page);                    
                    $this->dispatch('alert',                    
                        title:'Entité ('.$this->raison_sociale.') modifié!',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    ); 
                    $this->redirect('/detail_entite?id='.$this->id.'&active=12&champ=1-3', navigate: true);
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
    public function updateModule(){
        $validedata = $this->validate([       
            'validite_mod'=>'required', 
            'nbre_user_max'=>'required|numeric', 
            'nombre_societe'=>'required|numeric',                                          
            'montant_paye'=>'required|numeric|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',                                          
            'periode'=>'required',                                          
            'recommandation'=>'required|email|max:255',                                          
            'taux_commission'=>'required|numeric',                                          
            'etat_commission'=>'required',                                           
            ]);
            $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
            if($test > 0){
                $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
                $autoriser = $role[0]->modifier_entite;
                if($autoriser == 1){ 
                    if($this->id){ ;
                        $dateJour = date('Y-m-d');                       
                        $nbjoursRestant = round((strtotime($this->validite_mod) - strtotime($dateJour))/(60*60*24));
                        // Calcul commission
                        $montantCommission = $this->montant_paye * $this->taux_commission / 100;                        
                        Entite::find($this->id)->update(['validite_mod'=>$this->validite_mod,'jour_restant'=>$nbjoursRestant,'nbre_user_max'=>$this->nbre_user_max,'mod_pointe_vente'=>$this->mod_pointe_vente,'mod_cuisine'=>$this->mod_cuisine,
                        'mod_administration'=>$this->mod_administration,'mod_gestion_tier'=>$this->mod_gestion_tier,'mod_crm'=>$this->mod_crm,'mod_gestion_stock'=>$this->mod_gestion_stock,'mod_banque_caisse'=>$this->mod_banque_caisse,
                        'mod_facturation'=>$this->mod_facturation,'mod_cmd'=>$this->mod_cmd,'mod_multisociete'=>$this->mod_multisociete,'mod_ticket'=>$this->mod_ticket,'mod_tache'=>$this->mod_tache,'mod_gestion_commercial'=>$this->mod_gestion_commercial,
                        'mod_restaurant'=>$this->mod_restaurant,
                        'mod_fabrication'=>$this->mod_fabrication,'nombre_societe'=>$this->nombre_societe,'montant_paye'=>$this->montant_paye,
                        'periode'=>$this->periode,'recommandation'=>$this->recommandation,'taux_commission'=>$this->taux_commission,'montant_commission'=>$montantCommission,'etat_commission'=>$this->etat_commission,
                        'condition_vente'=>$this->condition_vente,'nom_user_modif'=>auth()->user()->email,'user_id_modif'=>auth()->user()->id]);                                      
                        $id_activite = $this->id;
                        $page = 'Entite';
                        LogActivity::addToLog('Module entité » '.$this->enseigne.' modifié', $id_activite, $page);                
                        $this->dispatch('alert', 
                            title:'Module ('.$this->enseigne.') modifié!',
                            timer:3000,
                            icon:'success',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',                            
                        ); 
                        $this->redirect('/detail_entite?id='.$this->id.'&active=12&champ=1-3', navigate: true); 
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
    public function precedant(int $id){ 

        if(auth()->user()->societe == "Administration"){

            $testPrecedant = Entite::where('id','<',$id)->orderBy('id','desc')->count();
            if($testPrecedant > 0){ 
                $precedant = Entite::where('id','<',$id)->orderBy('id','desc')->first();        
                $previous = $precedant->id; 
                $this->redirect('/detail_entite?id='.$previous.'&active=12&champ=1-3', navigate: true);                         
            }  
            else{
                $this->dispatch('alert',                    
                    title:'Désolé, Fin enregistrements',
                    timer:3000,
                    icon:'warning',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                ); 
                $this->redirect('/detail_entite?id='.$id.'&active=12&champ=1-3', navigate: true);
            } 
        }
        else{
            
            $testPrecedant = Entite::where('enseigne',auth()->user()->societe)->where('id','<',$id)->orderBy('id','desc')->count();
            if($testPrecedant > 0){ 
                $precedant = Entite::where('enseigne',auth()->user()->societe)->where('id','<',$id)->orderBy('id','desc')->first();        
                $previous = $precedant->id; 
                $this->redirect('/detail_entite?id='.$previous.'&active=12&champ=1-3', navigate: true);                         
            }  
            else{
                $this->dispatch('alert',                    
                    title:'Désolé, Fin enregistrements',
                    timer:3000,
                    icon:'warning',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                ); 
                $this->redirect('/detail_entite?id='.$id.'&active=12&champ=1-3', navigate: true);
            } 
        }   
    }    
    public function suivant(int $id){  

       if(auth()->user()->societe == "Administration"){

            $testSuivant = Entite::where('id','>',$id)->orderBy('id','asc')->count();
            if($testSuivant > 0){
                $suivant = Entite::where('id','>',$id)->orderBy('id','asc')->first();
                $next = $suivant->id;             
                $this->redirect('/detail_entite?id='.$next.'&active=12&champ=1-3', navigate: true);  
            }  
            else{
                $this->dispatch('alert',                    
                    title:'Désolé, Fin enregistrements',
                    timer:3000,
                    icon:'warning',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                ); 
                $this->redirect('/detail_entite?id='.$id.'&active=12&champ=1-3', navigate: true);  
            } 
        }
        else{

            $testSuivant = Entite::where('enseigne',auth()->user()->societe)->where('id','>',$id)->orderBy('id','asc')->count();
            if($testSuivant > 0){
                $suivant = Entite::where('enseigne',auth()->user()->societe)->where('id','>',$id)->orderBy('id','asc')->first();
                $next = $suivant->id;             
                $this->redirect('/detail_entite?id='.$next.'&active=12&champ=1-3', navigate: true);  
            }  
            else{
                $this->dispatch('alert',                    
                    title:'Désolé, Fin enregistrements',
                    timer:3000,
                    icon:'warning',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                ); 
                $this->redirect('/detail_entite?id='.$id.'&active=12&champ=1-3', navigate: true);  
            } 
        }
    }
    // Modifier logo entite
    public function valider_logo(){
        $this->validate([                      
            'logo'=>'required|image|mimes:jpeg,jpg,png,gif|max:2048',          
        ]);
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count(); 
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_entite;
            if($autoriser == 1){
                if($this->id){   
                    $path = $this->logo->store('logo_entite','public');
                    Entite::find($this->id)->update(['logo'=>$path,'nom_user_modif'=>auth()->user()->email,'user_id_modif'=>auth()->user()->id]);
                    session()->flash('msg-success', 'Logo modifié !');
                    $this->reset('logo');
                    // $this->dispatch('logoUpdate');
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
                    $this->redirect('/detail_entite?id='.$this->id.'&active=12&champ=1-3', navigate: true);  
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
    public function rechargeCredit(){        
        $this->validate([                      
            'montant_recu'=>'required|numeric', 
            'sens'=>'required|max:6', 
                     
        ]);
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count(); 
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_entite;
            if($autoriser == 1){
                if($this->id){  
                    $designation = 'Recharge de crédit';
                    if($this->sens == 'Crédit'){
                        $solde_final = $this->solde + $this->montant_recu;  
                        $debit = 0;
                        soldeClient::create(['enseigne'=>$this->enseigne,'id_enseigne'=>$this->id,'societe_mere'=>$this->societe_mere,'raison_sociale'=>$this->raison_sociale,'designation'=>$designation,'debit'=>$debit,'credit'=>$this->montant_recu,
                                            'responsable_societe'=>$this->responsable_societe,'pays'=>$this->pays,'ville'=>$this->ville,'adresse'=>$this->adresse,
                                            'telephone'=>$this->telephone,'email'=>$this->email,'registre_com'=>$this->registre_commerce,'niu'=>$this->niu,'logo'=>$this->old_image,
                                            'nom_user'=>auth()->user()->email,'user_id'=>auth()->user()->id]);
                        Entite::find($this->id)->update(['solde'=>$solde_final,'nom_user_modif'=>auth()->user()->email,'user_id_modif'=>auth()->user()->id]);
                        
                    }
                    elseif($this->sens == 'Débit'){
                        $solde_final = $this->solde - $this->montant_recu;
                        $credit = 0;
                        soldeClient::create(['enseigne'=>$this->enseigne,'id_enseigne'=>$this->id,'societe_mere'=>$this->societe_mere,'raison_sociale'=>$this->raison_sociale,'designation'=>$designation,'debit'=>$this->montant_recu,'credit'=>$credit,
                                            'responsable_societe'=>$this->responsable_societe,'pays'=>$this->pays,'ville'=>$this->ville,'adresse'=>$this->adresse,
                                            'telephone'=>$this->telephone,'email'=>$this->email,'registre_com'=>$this->registre_commerce,'niu'=>$this->niu,'logo'=>$this->old_image,
                                            'nom_user'=>auth()->user()->email,'user_id'=>auth()->user()->id]);
                        Entite::find($this->id)->update(['solde'=>$solde_final,'nom_user_modif'=>auth()->user()->email,'user_id_modif'=>auth()->user()->id]);
                    }
                    else{
                       $this->dispatch('alert',                    
                            title:'Désolé, veuillez selectionner un choix valable (Crédit / Débit)!',
                            timer:3000,
                            icon:'error',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        );  
                    } 
                    $id_activite = $this->id;
                    $page = 'Entite';
                    LogActivity::addToLog($this->enseigne.' » '.$this->sens.' solde '.$this->montant_recu.' '.$this->devise.' (T='.$solde_final.' '.$this->devise.')',$id_activite, $page); // ceci recense les activites dans le systeme 
                    $this->dispatch('alert',                    
                        title:'Solde '.$this->enseigne.' ajouté',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    ); 
                    flash ('Recharge '.$this->enseigne.' » <strong>'.$this->montant_recu.' '.$this->devise.'</strong> ajoutée!')->success();
                    $this->redirect('/detail_entite?id='.$this->id.'&active=12&champ=1-3', navigate: true);  
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
    public function coller(){ 
        $mont = Entite::where('id',$this->id)->first();
        $this->montant_reglement = $mont->montant_commission;            
    }
    public function payer(){
        $this->validate([
            'date_reglement' => 'required|date|before_or_equal:today',
            'mode_reglement'=>'required|max:255',
            'compte_bancaire'=>'required|max:255', //recupere id
            'montant_reglement'=>'required|numeric',
            'num_cheq_virement'=>'nullable|max:50',
            'emeteur'=>'nullable|max:200',
            'banque_cheque'=>'nullable|max:200',
            'commentaire'=>'nullable|max:255',
        ]);
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_entite;
            if($autoriser == 1){ 
                        
                if($this->recommandation != 'contact@wamsco-cloud.net'){
                    
                    if($this->montant_reglement <= $this->montant_commission){                            
                        // Compte bancaire
                        $CompteBq = CompteBancaire::where('societe',auth()->user()->societe)->where('id',$this->compte_bancaire)->first();   
                        $nom_compte_bancaire = $CompteBq->nom_compte_bancaire;

                        // Ecriture bancaire
                        $ref_ecritureBq = date('ymd-His');
                        $description = 'Règlement commercial » '.$this->enseigne;
                        $date_valeur = date('Y-m-d');
                        // $date_operation = date('Y-m-d');
                        $credit = 0; 
                        $solde = 0;  
                        $type_paiement = 'ReglementCommercial';    
                        $statut = 'Confirmer';           
                        EcritureBancaire::create(['id_compte_bancaire'=>$this->compte_bancaire,'id_type_paiement'=>$this->compte_bancaire,'nom_compte_bancaire'=>$nom_compte_bancaire,'reference'=>$ref_ecritureBq,'description'=>$description,
                                        'date_operation'=>$this->date_reglement,'date_valeur'=>$date_valeur,'type_operation'=>$this->mode_reglement,'debit'=>number_format($this->montant_reglement,0,',',''),'credit'=>$credit,'solde'=>$solde,
                                        'type_paiement'=>$type_paiement,'statut'=>$statut,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                    

                        // ceci recupere le dernier enregistrement cree a l'instant
                        $dernier_id = EcritureBancaire::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id; 

                        // ceci calcul le solde
                        $soldeCredit = EcritureBancaire::where('societe',auth()->user()->societe)->where('id_compte_bancaire',$this->compte_bancaire)->sum('credit');
                        $soldeDebit = EcritureBancaire::where('societe',auth()->user()->societe)->where('id_compte_bancaire',$this->compte_bancaire)->sum('debit');  
                        $solde = $soldeCredit - $soldeDebit;
                        CompteBancaire::where('societe',auth()->user()->societe)->where('id',$this->compte_bancaire)->update(['solde'=>$solde,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                        // Reglement 
                        $refReglement = 'PAY'.date('ymd-His');
                        $statut = 'Payé';
                        ReglementCommercial::create(['id_societe'=>$this->id,'nom_societe'=>$this->enseigne,'ref_reglement'=>$refReglement,'id_commercial'=>$this->user_id,'nom_commercial'=>$this->nom_user,'email_commercial'=>$this->recommandation,'id_ecriture_bancaire'=>$dernier_id,'ecriture_bancaire'=>$ref_ecritureBq,
                                        'mode_reglement'=>$this->mode_reglement,'compte_bancaire'=>$nom_compte_bancaire,'id_compte_bancaire'=>$this->compte_bancaire,'date_reglement'=>$this->date_reglement,'statut'=>$statut,
                                        'num_cheq_virement'=>$this->num_cheq_virement,'emetteur_cheq_virement'=>$this->emeteur,'banque_cheq_virement'=>$this->banque_cheque,'commentaire'=>$this->commentaire,
                                        'montant_regler'=>number_format($this->montant_reglement,0,',',''),'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                        // $dejaRegler = Reglement::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->sum('montant_regler');                                                                                                     

                        $id_activite = $this->id;
                        $page = 'Entite';
                        LogActivity::addToLog('Paiement commercial ('.$nom_compte_bancaire.') » '.$this->montant_reglement.' '.$this->devise.' règlement ('.$refReglement.') ajouté', $id_activite, $page);
                        $this->dispatch('alert',                    
                            title:'Paiement commercial ('.$this->montant_reglement.') enregistré !',
                            timer:5000,
                            icon:'success',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        );  
                        // $this->dispatch('fermerPayer');
                        $this->redirect('/detail_entite?id='.$this->id.'&active=12&champ=1-3', navigate: true); 
                    }
                    else{
                        $this->dispatch('alert',                    
                            title:'Désolé, le montant règlement ('.$this->montant_reglement.' '.$this->devise.') est supérieur au montant commission ('.$this->montant_commission.' '.$this->devise.')',
                            timer:5000,
                            icon:'error',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        ); 
                    }                         
                }
                else{
                    $this->dispatch('alert',                    
                        title:'Désolé, pas de commission pour un compte générique ('.$this->recommandation.')',
                        timer:10000,
                        icon:'error',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    ); 
                }                                     
            }
            else{                 
                $this->dispatchBrowserEvent('swal', [
                    'title' => 'Vous n\'êtes pas autorisé à effectuer cette opération !!!',
                    'timer'=>3000,
                    'icon'=>'error',
                    'toast'=>false,
                    'position'=>'center'
                ]);   
            } 
        }
        else{             
            $this->dispatchBrowserEvent('swal', [
                'title' => 'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!',
                'timer'=>3000,
                'icon'=>'error',
                'toast'=>false,
                'position'=>'center'
            ]);
        }     
    } 
    public function confirmerDelete(int $id, int $ids){ 
        $this->id = $ids;      
        $this->ids = $ids;      
        $this->confirmer = $id;      
    } 
    public function effacer(int $id){
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_entite;
            if($autoriser == 1){   
                if($id){                   

                    $Regler = ReglementCommercial::where('societe',auth()->user()->societe)->where('id',$id)->first();
                    $id_regle = $Regler->id_ecriture_bancaire;
                    $id_cpteBq = $Regler->id_compte_bancaire;
                    
                    ReglementCommercial::where('id',$id)->delete();
                    EcritureBancaire::where('id',$id_regle)->delete();

                    // ceci calcul le solde                    
                    $soldeCredit = EcritureBancaire::where('societe',auth()->user()->societe)->where('id_compte_bancaire',$id_cpteBq)->sum('credit');
                    $soldeDebit = EcritureBancaire::where('societe',auth()->user()->societe)->where('id_compte_bancaire',$id_cpteBq)->sum('debit');  
                    $solde = $soldeCredit - $soldeDebit;
                    CompteBancaire::where('societe',auth()->user()->societe)->where('id',$id_cpteBq)->update(['solde'=>$solde,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                  
                    $id_activite = $this->id;
                    $page = 'Entite';
                    LogActivity::addToLog('Ligne règlement commercial supprimé', $id_activite, $page);
                    $this->dispatch('alert',                    
                        title:'Un règlement commercial a été supprimé!',
                        timer:4000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );     
                }  
            }
            else{  
                $this->dispatch('alert',                    
                    title:'Vous n\'êtes pas autorisé à effectuer cette opération!',
                    timer:3000,
                    icon:'error',
                    toast:false,
                    showConfirmButton: true,
                    position:'center',
                );  
            } 
        }
        else{ 
            $this->dispatch('alert',                    
                title:'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!',
                timer:3000,
                icon:'error',
                toast:false,
                showConfirmButton: true,
                position:'center',
            );  
        }   
    }  
}
