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

class Roles extends Component
{
    protected $paginationTheme = 'bootstrap';
    use WithPagination;     

    public $query;   
    public $parPage = 21;
    public $parSociete;
    public $confirmer;
    
    public function updatingQuery(){
        $this->resetPage();
    }
    public function mount(){        
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
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
        $this->societe = auth()->user()->societe;
        $this->parSociete = auth()->user()->societe;
    }      
    public function render()
    {
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();
        $jourValid = $entite_mod[0]->validite_mod; 
        $mod_administration = $entite_mod[0]->mod_administration; 
        $soldeClient = $entite_mod[0]->solde;
        if($dateJour <= $jourValid){
            if($mod_administration == 1){
                $title = 'Rôle & Privillèges | WamsCo';
                $module = 'Paramètres';
                $title_fils = 'Rôle & Privillèges';
                $lien = 'role_privillege?active=8&champ=8-2';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');      
                $dateJour = date('Y-m-d');
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');

                if(auth()->user()->societe  == 'Administration'){ 
                    
                    $liste_privillege = Role::where('nom','like','%'.$this->query.'%')->where('societe','like','%'.$this->parSociete.'%')->orderBy('id','desc')->paginate($this->parPage); 
                    $roleCount = $liste_privillege->count();
                    $entite = Entite::where('active',1)->orderBy('enseigne','asc')->orderBy('enseigne','asc')->get(); 
                    
                    $resultat = Role :: where('societe','like','%'.$this->parSociete.'%')->get();  
                    $nbreTotalRole = $resultat->count(); 

                    $derniereActivite = Role::where('societe','like','%'.$this->parSociete.'%')->latest('updated_at')->first();    
                
                    $page = 'Role'; // Pour evenement lie
                    $log = LogActivityModel::where('user_societe',auth()->user()->societe)->where('page', $page)->limit(50)->orderBy('id','desc')->get();
                    $logCount = $log->count();

                    $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();      
                    $jourValid = $entite_mod[0]->validite_mod; 
                    // ceci pour trouver le nombre de jour restant avant expiration
                    $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24)); 
                    return view('livewire.administration.roles.role_liste',compact('title_fils','module','lien','dateJour','liste_privillege','roleCount','entite','entite_mod','nbreTotalRole','derniereActivite','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
                }
                else{
                
                    $liste_privillege = Role::where('societe',auth()->user()->societe)->where('nom','like','%'.$this->query.'%')->orderBy('id','desc')->paginate($this->parPage); 
                    $roleCount = $liste_privillege->count();
                    $entite = Entite::where('enseigne',auth()->user()->societe)->where('active',1)->orderBy('enseigne','asc')->get();

                    $resultat = Role :: where('societe',auth()->user()->societe)->get();  
                    $nbreTotalRole = $resultat->count(); 

                    $derniereActivite = Role::where('societe',auth()->user()->societe)->latest('updated_at')->first();   

                    $page = 'Role'; // Pour evenement lie
                    $log = LogActivityModel::where('user_societe',auth()->user()->societe)->where('page', $page)->limit(10)->orderBy('id','desc')->get();
                    $logCount = $log->count();

                    $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get(); 
                    $jourValid = $entite_mod[0]->validite_mod; 
                    // ceci pour trouver le nombre de jour restant avant expiration
                    $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24)); 
                    return view('livewire.administration.roles.role_liste',compact('title_fils','module','lien','dateJour','liste_privillege','roleCount','entite','entite_mod','nbreTotalRole','derniereActivite','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
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
}
