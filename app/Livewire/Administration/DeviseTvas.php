<?php

namespace App\Livewire\Administration;

use Livewire\Component;
use Livewire\Attributes\Validate; 
use App\Helpers\LogActivity;
use App\Models\LogActivity as LogActivityModel;
use Livewire\WithPagination;
use App\Models\Entite;
use App\Models\Role;
use App\Models\DeviseTva;

class DeviseTvas extends Component
{
    protected $paginationTheme = 'bootstrap';
    use WithPagination;    
   
    public $ids;

    #[Validate('required|max:255')]
    public $pays;

    #[Validate('required|max:255')]
    public $devise;

    #[Validate('required|max:255')]
    public $taxe;

    #[Validate('required|numeric|max:255')]
    public $taux_tva;

    public $confirmer;
    public $query;
    public $parPage = 20;

    public function updatingQuery(){
        $this->resetPage();
    }
    public function mount(){ 
        $this->pays ='Cameroon';
        $this->devise ='FCFA';           
    }
    public function resetinputFields(){
        $this->pays ='Cameroon';
        $this->devise ='FCFA';
        $this->taxe ='';
        $this->taux_tva ='';
    }
    public function render()
    {
        if(auth()->guest()){ 
            
            $title = 'Connexion';  
            flash ('Veuillez vous reconnecter svp!')->error();           
            return view('livewire.gestion_erreur.redirection')->layout('layouts.master',compact('title')); 
        }  
        else{  
            $dateJour = date('Y-m-d');            
            $entite_mod = Entite::where('id',auth()->user()->societe_id)->get();
            $jourValid = $entite_mod[0]->validite_mod; 
            $mod_administration = $entite_mod[0]->mod_administration; 
            $soldeClient = $entite_mod[0]->solde;
            if($dateJour <= $jourValid){
                if($mod_administration == 1){
                    $title = 'Dévise & Tva | WamsCo'; 
                    $module = 'Administration';
                    $title_fils = 'Dévise-Taxe';
                    $lien = 'devise'; 
                    $active = request('active');
                    $champ = request('champ');
                    $choix = request('choix');
                    $dateJour = date('Y-m-d');
                    toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');           
                    $deviseTva = DeviseTva :: where('societe_id',auth()->user()->societe_id)->where('taxe','like','%'.$this->query.'%')->orderBy('id','asc')->paginate($this->parPage); 
                    $deviseTvacount = DeviseTva :: where('societe_id',auth()->user()->societe_id)->where('pays','like','%'.$this->query.'%')->count(); 
                    
                    $resultat = DeviseTva :: where('societe_id',auth()->user()->societe_id)->get();  
                    $nbreTotalDeviseTva = $resultat->count(); 

                    $derniereActivite = DeviseTva::where('societe_id',auth()->user()->societe_id)->latest('updated_at')->first();  

                    $page = 'DeviseTva'; // Pour evenement lie
                    $log = LogActivityModel::where('societe_id',auth()->user()->societe_id)->where('page', $page)->limit(50)->orderBy('id','desc')->get();
                    $logCount = $log->count();

                    $entite_mod = Entite::where('id',auth()->user()->societe_id)->get();                    
                    $jourValid = $entite_mod[0]->validite_mod; 
                    // ceci pour trouver le nombre de jour restant avant expiration
                    $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24));
                    return view('livewire.administration.devisetva.devise-tvas',compact('title_fils','module','lien','dateJour','deviseTva','deviseTvacount','nbreTotalDeviseTva','derniereActivite','log','logCount'))->layout('components.layouts.app',compact('title','module','title_fils','lien','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
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
    public function store(){
        $this->validate();       
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->configurer;
            if($autoriser == 1){                
                $devise = DeviseTva :: create(['pays'=>$this->pays,'devise'=>$this->devise,'taxe'=>$this->taxe,'taux_tva'=>$this->taux_tva,'societe'=>auth()->user()->societe,
                'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                $dernier_id = $devise->id; 
                $id_activite = $dernier_id;  
                $page = 'DeviseTva';    
                LogActivity::addToLog('Taxe » '.$this->taxe.' créée', $id_activite, $page);   
                $this->dispatch('alert',                    
                    title:'Dévise ('.$this->devise.') enregistrée!',
                    timer:3000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                );  
                $this->resetinputFields();                              
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
    public function edit($id){
        $devise = DeviseTva::where('id',$id)->first();
        $this->ids = $devise->id;
        $this->pays = $devise->pays;
        $this->devise = $devise->devise;
        $this->taxe = $devise->taxe;
        $this->taux_tva = $devise->taux_tva;
    }
    public function update(){
        $validatedata = $this->validate();            
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->configurer;
            if($autoriser == 1){  
                if($this->ids){                    
                    DeviseTva::find($this->ids)->update(['pays'=>$this->pays,'devise'=>$this->devise,'taxe'=>$this->taxe,'taux_tva'=>$this->taux_tva,'societe'=>auth()->user()->societe,
                    'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                    
                    $id_activite = $this->ids; 
                    $page = 'DeviseTva';
                    LogActivity::addToLog('Taxe » '.$this->taxe.' modifiée', $id_activite, $page);   
                    $this->dispatch('deviseTvaUpdate');
                    $this->dispatch('alert',                    
                        title:'Dévise ('.$this->devise.') modifiée!',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );  
                    $this->resetinputFields();
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
    public function confirmerDelete($id){
        $this->confirmer = $id;        
    } 
    public function supprimer($id){
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->configurer;
            if($autoriser == 1){   
                if($id){
                    $page = 'DeviseTva';
                    DeviseTva::where('id',$id)->delete();
                    LogActivityModel::where('id_activite',$id)->where('page',$page)->delete();

                    $id_activite = $id; 
                    LogActivity::addToLog('Devise, Tva, Précompte Supprimée définitivement', $id_activite, $page); 
                    $this->dispatch('alert',                    
                        title:'Suppression effectuée!',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );  
                    $this->dispatch('deviseTvaUpdate');
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
