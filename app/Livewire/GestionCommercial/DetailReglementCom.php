<?php

namespace App\Livewire\GestionCommercial;

use Livewire\Component;
use Livewire\Attributes\Validate;  
use Illuminate\Support\Str;
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

class DetailReglementCom extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $ids;
    public $devise;

    public function mount(){ 
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->consulter_souscription;
            if($autoriser == 0){
                alert()->error('Oups Désolé', 'Vous n\'êtes pas autorisé à ouvrir cette page !!!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
                $this->redirect('/bienvenue', navigate: true);
            }
        }
        else{
            alert()->error('Oups Désolé', 'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
            $this->redirect('/bienvenue', navigate: true);
        }  
        $this->ids = request('id'); // id entite   
    }
    public function render(){    
        // $this->ids = request('id'); // id entite
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();
        $jourValid = $entite_mod[0]->validite_mod; 
        $mod_gestion_commercial = $entite_mod[0]->mod_gestion_commercial; 
        $soldeClient = $entite_mod[0]->solde;
        if($dateJour <= $jourValid){
            if($mod_gestion_commercial == 1){
                $title = 'Détails Souscription Commercial | WamsCo';
                $module = 'Commercial';
                $title_fils = 'Détails Souscription Commercial';
                $lien = 'detail_entite';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix'); 
                $dateJour = date('Y-m-d');                   
                
                $entite = Entite::orderBy('enseigne','asc')->get();               
                $utilisateurAll = Utilisateur::where('societe_mere',auth()->user()->societe)->orderBy('name','asc')->get(); 
                
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

                $user = Utilisateur::where('societe',auth()->user()->societe)->where('id',$user_id)->get();  
                
                $page = 'Entite'; // Pour evenement lie
                $log = LogActivityModel::where('user_societe',auth()->user()->societe)->where('id_activite', $this->ids)->where('page', $page)->limit(50)->orderBy('id','desc')->get();
                $logCount = $log->count();                 
                
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
                return view('livewire.gestion-commercial.detail-reglement-com',compact('title_fils','module','lien','dateJour','entit','entitCount','reglementCom','dejaRegler','utilisateurAll','banque','user','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant')); 
                
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
       
}
