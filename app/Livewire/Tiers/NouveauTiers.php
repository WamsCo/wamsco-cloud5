<?php

namespace App\Livewire\Tiers;

use Livewire\Component;
use Livewire\Attributes\Validate; 
use App\Models\Utilisateur;
use App\Models\Tier;
use App\Models\Role;
use App\Models\Entite;
use App\Helpers\LogActivity;
use App\Models\LogActivity as LogActivityModel;
use App\Models\DeviseTva;

class NouveauTiers extends Component
{
    #[Validate('required')]
    public $nom; 
    
    #[Validate('max:255')]
    public $raison_sociale;

    #[Validate('required')]
    public $type_tiers;  
    
    #[Validate('required')]
    public $telephone;

    public $email;
    public $pays;
    public $ville;
    public $adresse;
    public $code_postal;
    public $site_web;

    #[Validate('required')] 
    public $sexe;
    
    public $commercial_charge; 

    #[Validate('required')] 
    public $etat;
    public $date_debut;
    // #[Validate('required|image|mimes:jpeg,jpg,png,gif|max:2048')]
    // public $logo;

    public function mount(){          
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_tier;
            if($autoriser == 0){
                alert()->error('Oups Désolé', 'Vous n\'êtes pas autorisé à ouvrir cette page !!!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
                $this->redirect('/bienvenue', navigate: true);
            }
        }
        else{
            alert()->error('Oups Désolé', 'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
            $this->redirect('/bienvenue', navigate: true);
        }
        $this->pays ='Cameroon';
        $this->etat = 1;  
        $this->date_debut =  date('Y-m-d');
        // $this->type_tiers = request('code');
    }     
    public function resetinputFields(){ 
        $this->nom ='';
        $this->raison_sociale ='';
        $this->type_tiers ='';
        $this->etat = 1;
        $this->telephone ='';
        $this->adresse ='';
        $this->code_postal ='';
        $this->ville ='';
        $this->pays ='Cameroon';
        $this->email ='';       
        $this->site_web ='';
        $this->commercial_charge ='';          
        $this->sexe;          
    }      
    public function render()
    {       
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();
        $jourValid = $entite_mod[0]->validite_mod;
        $mod_gestion_tier = $entite_mod[0]->mod_gestion_tier;
        $soldeClient = $entite_mod[0]->solde; 
        if($dateJour <= $jourValid){ 
            if($mod_gestion_tier == 1){             
                $title = 'Nouveau Tier | WamsCo';
                $module = 'Gestion tiers';
                $title_fils = 'Nouveau tiers (Prospect, Client, Fournisseur)';
                $lien = 'listing-tiers?active=3&champ=3-2';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix'); 
                $dateJour = date('Y-m-d');
                // $menuModule = request('module');
                $utilisateur = Utilisateur::where('societe',auth()->user()->societe)->orderBy('name','asc')->get();
                $tiersCount = Tier::where('societe',auth()->user()->societe)->count(); 
                
                $page = 'Tiers'; // Pour evenement lie
                $log = LogActivityModel::where('user_societe',auth()->user()->societe)->where('page', $page)->limit(9)->orderBy('id','desc')->get();
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
                return view('livewire.tiers.create-tier',compact('title_fils','module','lien','dateJour','utilisateur','tiersCount','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant')); 
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
        $this->validate();  
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){      
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_tier;
            if($autoriser == 1){                   
                $validation = '';
                $paiement = '';
                $dates = date('dmy-His');
                $length = 2;
                $token = bin2hex(random_bytes($length));

                if($this->type_tiers == 'Fournisseur'){
                    $token_ok = 'SUP-'.$dates;
                }
                else{
                    $token_ok = 'CUS-'.$dates;
                }

                $test_point = Tier::where('societe',auth()->user()->societe)->count();
                if($test_point > 0){
                    $objectifPoint = Tier::where('societe',auth()->user()->societe)->get();
                    $objectif_point = $objectifPoint[0]->objectif_point;
                }
                else{
                    $objectif_point = 0;
                }
                $solde = 0;
                Tier::create(['nom'=>$this->nom,'code_tier'=>$token_ok,'raison_sociale'=>$this->raison_sociale,'solde'=>$solde,'type_tiers'=>$this->type_tiers,'etat'=>$this->etat,'telephone'=>$this->telephone,
                    'adresse'=>$this->adresse,'code_postal'=>$this->code_postal,'ville'=>$this->ville,'pays'=>$this->pays,'email'=>$this->email,'site_web'=>$this->site_web,
                    'validation'=>$validation,'paiement'=>$paiement,'date_debut'=>$this->date_debut,'commercial_charge'=>$this->commercial_charge,'sexe'=>$this->sexe,'objectif_point'=>$objectif_point,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                    
                    
                    // ceci recupere le dernier enregistrement cree a l'instant
                    $dernier_id = Tier::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id; 
                    $id_activite = $dernier_id;
                    $page = 'Tiers';
                    LogActivity::addToLog('Tier » '.$this->nom.' créé', $id_activite, $page);  
                    $this->dispatch('alert',                    
                        title:'Tiers ('.$this->nom.') enregistré!',
                            timer:3000,
                            icon:'success',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                    );  
                    $this->resetinputFields(); 
                    $this->redirect('/detail_tier?id='.$dernier_id, navigate: true);
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
