<?php

namespace App\Livewire\Administration;

use Livewire\Component;
use Livewire\Attributes\Validate;  
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use App\Helpers\LogActivity;
use App\Models\LogActivity as LogActivityModel;
use App\Models\Poste_travail;
use App\Models\Entite;
use App\Models\DeviseTva;
use App\Models\Role;

class PosteTravails extends Component
{
    protected $paginationTheme = 'bootstrap';
    use WithPagination;
    public $ids;
    public $nom_poste;
    public $description;

    public $confirmer;
    public $query;
    public $parPage = 24;

    public function resetinputFields(){
        $this->nom_poste ='';
        $this->description ='';
    }
    public function updatingQuery(){
        $this->resetPage();
    }
    public function mount(){ 
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->consulter_depart_poste;
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
    public function render()
    {
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('id',auth()->user()->societe_id)->get();
        $jourValid = $entite_mod[0]->validite_mod; 
        $mod_administration = $entite_mod[0]->mod_administration; 
        $soldeClient = $entite_mod[0]->solde;
        if($dateJour <= $jourValid){
            if($mod_administration == 1){
                $title = 'Poste de travail | WamsCo';
                $module = 'Paramètres';
                $title_fils = 'Poste de travail';
                $lien = 'poste_travail?active=12&champ=1-1&choix=3';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');      
                $dateJour = date('Y-m-d');
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');

                $poste_travail = Poste_travail :: where('societe_id',auth()->user()->societe_id)->where('nom_poste','like','%'.$this->query.'%')->orderBy('id','desc')->paginate($this->parPage); 
                $poste_travailCount = $poste_travail->count();             
                                
                $resultat = Poste_travail :: where('societe_id',auth()->user()->societe_id)->get();  
                $nbreTotalPosteTravail = $resultat->count(); 

                $derniereActivite = Poste_travail::where('societe_id',auth()->user()->societe_id)->latest('updated_at')->first(); 

                $page = 'Poste_travail'; // Pour evenement lie
                $log = LogActivityModel::where('societe_id',auth()->user()->societe_id)->where('page', $page)->limit(50)->orderBy('id','desc')->get();
                $logCount = $log->count();

                $deviseTva = DeviseTva :: where('societe_id',auth()->user()->societe_id)->limit(1)->orderBy('id','asc')->count(); 
                if($deviseTva == 0){
                    $this->devise = 'FCFA';
                }
                else{
                    $deviseTva = DeviseTva :: where('societe_id',auth()->user()->societe_id)->limit(1)->orderBy('id','asc')->get(); 
                    $this->devise = $deviseTva[0]->devise;
                }
                $entite_mod = Entite::where('id',auth()->user()->societe_id)->get();          
                $jourValid = $entite_mod[0]->validite_mod; 
                // ceci pour trouver le nombre de jour restant avant expiration
                $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24));
                return view('livewire.administration.poste_travail.poste-travail',compact('title_fils','module','lien','dateJour','poste_travail','poste_travailCount','nbreTotalPosteTravail','derniereActivite','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
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
            'nom_poste'=>'required|max:255',
            'description'=>'required|max:255',
        ]);
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_depart_poste;
            if($autoriser == 1){ 

                $posteTrav = Poste_travail :: create(['nom_poste'=>$this->nom_poste,'description'=>$this->description,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                $dernier_id = $posteTrav->id;

                $id_activite = $dernier_id;  
                $page = 'Poste_travail';    
                LogActivity::addToLog('Poste travail » '.$this->nom_poste.' créée', $id_activite, $page);    
                $this->dispatch('alert',                    
                    title:'Poste travail ('.$this->nom_poste.') enregistré!',
                    timer:3000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                ); 
                $this->resetinputFields();
                $this->redirect('/poste_travail?active=12&champ=1-6', navigate: true);
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
    public function edit($id){
        $PosteTravail  = Poste_travail::where('id',$id)->first();
        $this->ids = $PosteTravail->id;
        $this->nom_poste = $PosteTravail->nom_poste;
        $this->description = $PosteTravail->description;
    }
    public function update(){
        $validatedata = $this->validate([
            'nom_poste'=>'required|max:255',
            'description'=>'required|max:255',     
        ]); 
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_depart_poste;
            if($autoriser == 1){  
                if($this->ids){
                    
                    Poste_travail::find($this->ids)->update(['nom_poste'=>$this->nom_poste,'description'=>$this->description,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name]);
                    $id_activite = $this->ids; 
                    $page = 'Poste_travail';
                    LogActivity::addToLog('Poste_travail » '.$this->nom_poste.' modifiée', $id_activite, $page);
                    $this->dispatch('alert',                    
                        title:'Poste Travail ('.$this->nom_poste.') modifié!',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );    
                    $this->resetinputFields();
                    $this->redirect('/poste_travail?active=12&champ=1-6', navigate: true);
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
    public function confirmerDelete($id){
        $this->confirmer = $id;        
    } 
    public function supprimer($id){
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->supprimer_depart_poste;
            if($autoriser == 1){   
                if($id){
                    Poste_travail::where('id',$id)->delete();
                    $page = 'Poste_travail'; // Pour evenement lie                   
                    LogActivityModel::where('id_activite',$id)->where('page',$page)->delete();

                    $id_activite = $id; 
                    LogActivity::addToLog('Poste travail Supprimé définitivement', $id_activite, $page); 
                    $this->dispatch('alert',                    
                        title:'Suppression effectuée!',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    ); 
                    $this->redirect('/poste_travail?active=12&champ=1-6', navigate: true);                
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
