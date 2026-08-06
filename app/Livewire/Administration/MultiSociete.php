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

class MultiSociete extends Component
{
    protected $paginationTheme = 'bootstrap';
    use WithPagination;

    public $raison_sociale;
    public $responsable_societe;
    public $ville;
    public $pays;
    public $adresse;
    public $telephone;
    public $email;
    public $condition_vente; 
    public $registre_commerce;
    public $niu;  
    
    public $societe_mere;
    public $societe_filiale;    
    public $filiale;

    public $code_postal;
    public $site_web;

    //
    public $mod_pointe_vente;
    public $mod_cuisine;    
    public $mod_administration;
    public $mod_gestion_tier;
    public $mod_crm;    
    public $mod_gestion_stock;
    public $mod_pressing;
    public $mod_facturation;
    public $mod_caisse;    
    public $mod_multisociete;
    public $mod_ticket;  
    public $mod_tache;       
    public $validite_mod;
    public $created_at;
    public $nbre_user_max;
    public $jour_restant;
    public $nombre_users;
    public $active;
    //
    public $devise;
    public $orderField = 'enseigne'; 
    public $orderDirection = 'ASC'; 

    public $query;   
    public $parPage = 20;
    public $confirmer; 

    public function mount(){        
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->consulter_societe;
            if($autoriser == 0){
                alert()->error('Oups Désolé', 'Vous n\'êtes pas autorisé à ouvrir cette page !!!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
                $this->redirect('/bienvenue', navigate: true);
            }
        }
        else{
            alert()->error('Oups Désolé', 'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
            $this->redirect('/bienvenue', navigate: true);
        }        
        // $this->filiale = auth()->user()->societe;
    }   
    public function render()
    {   
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();
        $this->filiale = $entite_mod[0]->id; 
        $jourValid = $entite_mod[0]->validite_mod; 
        $mod_multisociete = $entite_mod[0]->mod_multisociete; 
        $soldeClient = $entite_mod[0]->solde;
        $societe_mere = $entite_mod[0]->societe_mere;
        if($dateJour <= $jourValid){
            if($mod_multisociete == 1){           
                    $title = 'Sociétés | WamsCo';
                    $module = 'Multi-sociétés';
                    $title_fils = 'Sociétés';
                    $lien = 'liste_societe?active=7&champ=7-1';
                    $active = request('active');
                    $champ = request('champ');
                    $choix = request('choix');      
                    $dateJour = date('Y-m-d');
                    toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');

                    if($societe_mere == auth()->user()->societe){ 
                        $liste_entit = Entite::where('societe_mere',auth()->user()->societe)->where('enseigne','like','%'.$this->query.'%')->where('active',1)->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage);                 
                        $resultat = Entite::where('societe_mere',auth()->user()->societe_mere)->get(); 
                        $derniereActivite = Entite::where('societe_mere',auth()->user()->societe_mere)->latest('updated_at')->first();  
                    }
                    else{ 
                        $liste_entit = Entite::where('enseigne',auth()->user()->societe)->where('enseigne','like','%'.$this->query.'%')->where('active',1)->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage);                 
                        $resultat = Entite::where('enseigne',auth()->user()->societe)->get(); 
                        $derniereActivite = Entite::where('enseigne',auth()->user()->societe)->latest('updated_at')->first();  
                    }
                    $entite_count = $liste_entit->count(); 

                    $nbreTotalEntite = $resultat->count();     

                    $entiteFiliale = Entite::where('societe_mere',auth()->user()->societe_mere)->where('active',1)->orderBy('enseigne','asc')->get(); 
                    $user = Utilisateur::where('email',auth()->user()->email)->get(); 
                    $this->societe_mere = $user[0]->societe_mere; // ceci gere: il faut se place sur entite mere pour cree une entite et non le contraire
                    $this->societe_filiale = $user[0]->societe;
                    
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
                    return view('livewire.administration.multi_societe.multi-societe',compact('title_fils','module','lien','dateJour','liste_entit','entite_count','entiteFiliale','nbreTotalEntite','derniereActivite','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
                
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
    // ceci permet de changer d'entite en entite
    public function valider(){
        $validedata = $this->validate([       
        'filiale'=>'required|numeric', // id entite                    
        ]);
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->first();
            if ($role?->acces_entite[$this->filiale] ?? false) { 
                   $entity = Entite::where('id', $this->filiale)->first();  
                   $nomSociete = $entity->enseigne; 
                    // verifie s'il ya un role similaire dans l'entite de destination                
                   $role_verifie =  Role:: where('societe',$nomSociete)->where('nom',auth()->user()->type_user)->count(); 
                   if($role_verifie > 0){                   
                       $user_verifie =  Utilisateur:: where('email',auth()->user()->email)->count(); 
                       if($user_verifie > 0){                            
                            Utilisateur:: where('email',auth()->user()->email)->update(['societe'=>$nomSociete]);  

                            $id_activite = 0;
                            $page = 'ChangementFiliale';
                            LogActivity::addToLog('Changement filiale ('.$nomSociete.') enregistrée', $id_activite, $page); 
                            $this->dispatch('alert',                    
                                title:'Filiale ('.$nomSociete.') enregistrée!',
                                timer:3000,
                                icon:'success',
                                toast:true,
                                showConfirmButton: false,
                                position:'top-end',
                            ); 
                            $this->redirect('/liste_societe?active=11&champ=1-1', navigate: true);                                    
                        } 
                        $this->dispatch('alert',                    
                            title:'Désolé, ce utilisateur n\'exite pas ou plus dans cette entité!',
                            timer:10000,
                            icon:'warning',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        );                      
                   }
                   else{
                        $this->dispatch('alert',                    
                            title:'Désolé, veuillez créer dans l\'entité de destination, le même <strong>rôle</strong> que vous utitilisez dans cette entité!',
                            timer:10000,
                            icon:'warning',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        );  
                    }                
            }
            else{  
                $this->dispatch('alert',                    
                    title:'Vous n\'êtes pas autorisé à effectuer cette opération!',
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
                title:'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!',
                timer:5000,
                icon:'error',
                toast:true,
                showConfirmButton: false,
                position:'top-end',
            );  
        }   
    }
    public function store(){ 
        $validedata = $this->validate([  
        'raison_sociale'=>'required|max:255|unique:entites,enseigne,{$entites->id}',        
        'raison_sociale'=>'required|max:255', 
        'responsable_societe'=>'required|max:255',                              
        'ville'=>'required|max:255', 
        'pays'=>'required|max:255', 
        'adresse'=>'required|max:255',     
        'telephone'=>'required|max:255',
        'email'=>'required|email|max:255',     
        'registre_commerce'=>'nullable|max:255',
        'niu'=>'nullable|max:255',                              
        'condition_vente'=>'nullable|max:255', 
        'code_postal'=>'nullable|max:255',  
        'site_web'=>'nullable|max:255',  
        ]);        
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_societe;
            if($autoriser == 1){ 
                $entite = Entite::where('societe_mere', auth()->user()->societe_mere)->count();    
                $Allentite = Entite::where('societe_mere', auth()->user()->societe_mere)->first();  
                $nbre_societe = $Allentite->nombre_societe;                   
                if($entite < $nbre_societe){                             
                    $dateJour = date('Y-m-d');
                    $entite = Entite::where('enseigne', auth()->user()->societe)->get();                   
                    $this->nombre_users = 0; 
                    $this->nombre_societe = 0;                     
                    $this->active = $entite[0]->active;    
                    $this->nbre_user_max = $entite[0]->nbre_user_max;    
                    $this->mod_pointe_vente = $entite[0]->mod_pointe_vente;
                    $this->mod_cuisine = $entite[0]->mod_cuisine;                    
                    $this->mod_administration = $entite[0]->mod_administration;
                    $this->mod_gestion_tier = $entite[0]->mod_gestion_tier;
                    $this->mod_crm = $entite[0]->mod_crm;                    
                    $this->mod_gestion_stock = $entite[0]->mod_gestion_stock;
                    $this->mod_banque_caisse = $entite[0]->mod_banque_caisse;
                    $this->mod_facturation = $entite[0]->mod_facturation;
                    $this->mod_cmd = $entite[0]->mod_cmd;                    
                    $this->mod_multisociete = $entite[0]->mod_multisociete;
                    $this->mod_ticket = $entite[0]->mod_ticket;                  
                    $this->mod_tache = $entite[0]->mod_tache;
                    $this->validite_mod = $entite[0]->validite_mod;
                    $this->jour_restant = $entite[0]->jour_restant;
                    $this->logo = $entite[0]->logo;
                    $periode = $entite[0]->periode;

                    $solde = 0;
                    $etatCommission = 'Gratuit';
                    $montant_paye = 0;
                    Entite::create(['solde'=>$solde,'societe_mere'=>auth()->user()->societe_mere,'enseigne'=>$this->raison_sociale,'raison_sociale'=>$this->raison_sociale,'ville'=>$this->ville,'adresse'=>$this->adresse,'telephone'=>$this->telephone,'email'=>$this->email,
                        'registre_com'=>$this->registre_commerce,'niu'=>$this->niu,'code_postal'=>$this->code_postal,'site_web'=>$this->site_web,'responsable_societe'=>$this->responsable_societe,'condition_vente'=>$this->condition_vente,'pays'=>$this->pays,'nombre_users'=>$this->nombre_users,'nbre_user_max'=>$this->nbre_user_max,'active'=>$this->active,
                        'mod_pointe_vente'=>$this->mod_pointe_vente,'mod_cuisine'=>$this->mod_cuisine,'mod_administration'=>$this->mod_administration,'mod_gestion_tier'=>$this->mod_gestion_tier,'mod_crm'=>$this->mod_crm,'mod_gestion_stock'=>$this->mod_gestion_stock,
                        'mod_banque_caisse'=>$this->mod_banque_caisse,'mod_facturation'=>$this->mod_facturation,'mod_cmd'=>$this->mod_cmd,'mod_multisociete'=>$this->mod_multisociete,'mod_ticket'=>$this->mod_ticket,'mod_tache'=>$this->mod_tache,'validite_mod'=>$this->validite_mod,
                        'jour_restant'=>$this->jour_restant,'logo'=>$this->logo,'nombre_societe'=>$this->nombre_societe,'montant_paye'=>$montant_paye,'periode'=>$periode,'etat_commission'=>$etatCommission,'nom_user'=>auth()->user()->email,'user_id'=>auth()->user()->id]); 
                    
                    // Dupliquer les roles en fonction des disponibilites 
                    $liste_role = Role::where('societe',auth()->user()->societe)->get(); 
                    foreach($liste_role as $liste_roles){  
                        $new_rol = $liste_roles->replicate();
                        $new_rol->societe = $this->raison_sociale;  
                        $new_rol->nom = $liste_roles->nom;
                        $new_rol->nom_user = auth()->user()->name;
                        $new_rol->user_id = auth()->user()->id;                    
                        $new_rol->save();                        
                    }     
                    
                    // // creer automatiquement un utilisateur et lui donner ce role 
                    // $liste_user = Utilisateur::where('id',auth()->user()->id)->where('societe',auth()->user()->societe)->get(); 
                    // foreach($liste_user as $liste_users){  
                    //     $new_rol = $liste_users->replicate();
                    //     $new_rol->societe = $this->raison_sociale;  
                    //     // $new_rol->nom = $liste_users->nom;
                    //     // $new_rol->nom_user = auth()->user()->name;
                    //     // $new_rol->user_id = auth()->user()->id;                    
                    //     $new_rol->save();                        
                    // }  

                    //  Utilisateur:: create(['email'=>$this->email,'name'=>$this->nom_utilisateur,'telephone'=>$this->telephone,'password'=>bcrypt($this->password),'type_user'=>$this->nom,
                    // 'date_valide'=>$date_valide,'etat'=>$activer,'sexe'=>$sexe,'salarie'=>$salarie,'societe'=>$this->societe,'societe_mere'=>$this->societe,
                    // 'nom_user'=>$this->email,'user_id'=>0]); 
                    
                    // ceci recupere le dernier enregistrement cree a l'instant
                    $dernier_id = Entite::where('enseigne',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id;

                    $id_activite = $dernier_id;
                    $page = 'Entite';
                    LogActivity::addToLog('Filiale » '.$this->raison_sociale.' créée', $id_activite, $page);                     
                    $this->dispatch('alert',                    
                        title:'Entité ('.$this->raison_sociale.') modifié!',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    ); 
                    $this->redirect('/liste_societe??active=7&champ=7-1', navigate: true);  
                }
                else{
                    $this->dispatch('alert',                    
                        title:'Désolé, nombre maximum ('.$nbre_societe.') de société atteint !',
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
    public function confirmerDelete($id){
        $this->confirmer = $id;      
    } 
    public function supprimer($id){
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->supprimer_entite;
            if($autoriser == 1){
                if(auth()->user()->societe == "Administration"){ 
                    if($id){   
                        // $entite = Entite::where('id',$id)->first();                       
                        // $this->enseigne = $entite->enseigne;
                        // Entite::where('id',$id)->delete();
                        // Role::where('societe',$this->enseigne)->delete();
                        // Utilisateur::where('societe',$this->enseigne)->delete();                       
                        // DeviseTva::where('societe',$this->enseigne)->delete();
                        // Produit::where('societe',$this->enseigne)->delete();
                        // Emplacement ::where('societe',$this->enseigne)->delete(); 
                       
                        // Departement::where('societe',$this->enseigne)->delete();
                        // Categorie::where('societe',$this->enseigne)->delete();
                        // CommandeAttenteEntete::where('societe',$this->enseigne)->delete();
                        // CommandeAttenteLigne::where('societe',$this->enseigne)->delete();
                        // CommandeClientEntete::where('societe',$this->enseigne)->delete();
                        // CommandeClientLigne::where('societe',$this->enseigne)->delete();
                        // CommandeFournisseurEntete::where('societe',$this->enseigne)->delete(); 
                        // CommandeFournisseurLigne::where('societe',$this->enseigne)->delete(); 

                        // CompteBancaire::where('societe',$this->enseigne)->delete();                        
                        // EcritureBancaire::where('societe',$this->enseigne)->delete();                        
                        // Entrepot::where('societe',$this->enseigne)->delete();                        
                        // ExpeditionClientEntete::where('societe',$this->enseigne)->delete();                        
                        // ExpeditionClientLigne::where('societe',$this->enseigne)->delete();                        
                        // factureClientEntete::where('societe',$this->enseigne)->delete();                        
                        // factureClientLigne::where('societe',$this->enseigne)->delete();                        
                        // Inventaire::where('societe',$this->enseigne)->delete();                        
                        // InventaireLigne::where('societe',$this->enseigne)->delete();                        
                        // Mouvement::where('societe',$this->enseigne)->delete();                        
                        // PaiementDiver::where('societe',$this->enseigne)->delete();                        
                        // Parametre::where('societe',$this->enseigne)->delete();                        
                        // PosFactureClientEntete::where('societe',$this->enseigne)->delete();                        
                        // PosFactureClientLigne::where('societe',$this->enseigne)->delete();  

                        // Poste_travail::where('societe',$this->enseigne)->delete();                        
                        // PrixVente::where('societe',$this->enseigne)->delete();                        
                        // ReceptionFournisseurEntete::where('societe',$this->enseigne)->delete();                        
                        // ReceptionFournisseurLigne::where('societe',$this->enseigne)->delete();                        
                        // Reglement_fourni::where('societe',$this->enseigne)->delete();                        
                        // Reglement::where('societe',$this->enseigne)->delete();                        
                        // SessionPos::where('societe',$this->enseigne)->delete();    

                        // Stock::where('societe',$this->enseigne)->delete();                        
                        // Tier::where('societe',$this->enseigne)->delete();                        
                        // Transfert::where('societe',$this->enseigne)->delete();                        
                        // TransfertLigne::where('societe',$this->enseigne)->delete();                        
                        // Utilisateur::where('societe',$this->enseigne)->delete();

                        $this->dispatch('alert',                    
                            title:'Désolé, veuillez effectuer cette opération dans la liste des entités',
                            timer:13000,
                            icon:'warning',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        );                   
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
}
