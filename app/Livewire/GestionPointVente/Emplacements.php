<?php

namespace App\Livewire\GestionPointVente;

use Livewire\Component;
use Livewire\WithPagination;
use App\Helpers\LogActivity;
use App\Models\LogActivity as LogActivityModel;
use App\Models\Emplacement;
use App\Models\Entite;
use App\Models\DeviseTva;
use App\Models\Role;
use App\Models\Parametre;

class Emplacements extends Component
{
    protected $paginationTheme = 'bootstrap';
    use WithPagination;

    public $ids;
    public $nom_emplacement;
    public $description;

    public $activer_ecran_cuisine; 

    public $confirmer;
    public $query;
    public $parPage = 20;

    public $orderField = 'id'; 
    public $orderDirection = 'DESC'; 

    public function setOrderField(string $name){
        if($name === $this->orderField){
            $this->orderDirection = $this->orderDirection === 'ASC' ? 'DESC' : 'ASC';
        }
        else{
            $this->orderField = $name;
            $this->reset('orderDirection');
        }
    }
    public function resetinputFields(){
        $this->nom_emplacement ='';
        $this->description ='';
    }
    public function updatingQuery(){
        $this->resetPage();
    }
    public function mount(){
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->consulter_emplacement;
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
        $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();
        $jourValid = $entite_mod[0]->validite_mod;
        $mod_pointe_vente = $entite_mod[0]->mod_pointe_vente; 
        $soldeClient = $entite_mod[0]->solde;
        if($dateJour <= $jourValid){
            if($mod_pointe_vente == 1){
                $title = 'Emplacement | WamsCo';
                $module = 'Gestion emplacement';
                $title_fils = 'Emplacement';
                $lien = 'emplacement?active=5&champ=1-2';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');      
                $dateJour = date('Y-m-d');
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');

                $emplacement = Emplacement :: where('societe',auth()->user()->societe)->where('nom_emplacement','like','%'.$this->query.'%')->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage); 
                $emplacementCount = $emplacement->count();  
            
                // Parametre
                $verifie = Parametre ::where('societe',auth()->user()->societe)->count();
                if($verifie > 0){                
                    $config = Parametre::where('societe',auth()->user()->societe)->limit(1)->get();
                    $this->activer_ecran_cuisine = $config[0]->activer_ecran_cuisine;               
                }
                else{               
                    $this->activer_ecran_cuisine = 0;       
                }

                $deviseTva = DeviseTva :: where('societe',auth()->user()->societe)->limit(1)->orderBy('id','asc')->count(); 
                if($deviseTva == 0){
                    $this->devise = 'FCFA';
                }
                else{
                    $deviseTva = DeviseTva :: where('societe',auth()->user()->societe)->limit(1)->orderBy('id','asc')->get(); 
                    $this->devise = $deviseTva[0]->devise;
                }

                $resultat = Emplacement::where('societe',auth()->user()->societe)->get();  
                $nbreTotalEmplacement = $resultat->count();     

                $derniereActivite = Emplacement::where('societe',auth()->user()->societe)->latest('updated_at')->first();

                $page = 'Emplacement'; // Pour evenement lie
                $log = LogActivityModel::where('user_societe',auth()->user()->societe)->where('page', $page)->limit(8)->orderBy('id','desc')->get();
                $logCount = $log->count();   
                
                $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();          
                $jourValid = $entite_mod[0]->validite_mod; 
                // ceci pour trouver le nombre de jour restant avant expiration
                $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24));
                return view('livewire.gestion-point-vente.emplacement.emplacements',compact('title_fils','module','lien','dateJour','emplacement','emplacementCount','nbreTotalEmplacement','derniereActivite','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
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
            'nom_emplacement'=>'required|max:255',
            'description'=>'max:250',
        ]);
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_emplacement;
            $utiliser = 'Non';
            if($autoriser == 1){ 
                Emplacement :: create(['nom_emplacement'=>$this->nom_emplacement,'description'=>$this->description,'utiliser'=>$utiliser,'societe'=>auth()->user()->societe,'user_id'=>auth()->user()->id,'nom_user'=>auth()->user()->name]);
                // ceci recupere le dernier enregistrement cree a l'instant
                $dernier_id = Emplacement::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id; 
                $id_activite = $dernier_id;  
                $page = 'Emplacement';    
                LogActivity::addToLog('Emplacement » '.$this->nom_emplacement.' créée', $id_activite, $page);
                $this->dispatch('emplacementAjouter');
                $this->dispatch('alert',                    
                    title:'Emplacement ('.$this->nom_emplacement.') enregistré!',
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
        $emplacement  = Emplacement::where('id',$id)->first();
        $this->ids = $emplacement->id;
        $this->nom_emplacement = $emplacement->nom_emplacement;
        $this->description = $emplacement->description;
    }
    public function update(){
        $validatedata = $this->validate([
            'nom_emplacement'=>'required|max:255',
            'description'=>'max:250',     
        ]); 
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_emplacement;
            if($autoriser == 1){  
                if($this->ids){

                    Emplacement::find($this->ids)->update(['nom_emplacement'=>$this->nom_emplacement,'description'=>$this->description,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                    $this->dispatch('emplacementUpdate');
                    $id_activite = $this->ids; 
                    $page = 'Emplacement';    
                    LogActivity::addToLog('Emplacement » '.$this->nom_emplacement.' modifiée', $id_activite, $page);
                    $this->dispatch('alert',                    
                        title:'Emplacement ('.$this->nom_emplacement.') modifié!',
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
            $autoriser = $role[0]->supprimer_emplacement;
            if($autoriser == 1){   
                if($id){
                    $utilisers = Emplacement::where('id',$id)->first();
                    $utiliser = $utilisers->utiliser;
                    if($utiliser == 'Non'){
                        $page = 'Emplacement'; // Pour evenement lie
                        Emplacement::where('id',$id)->delete();                 
                        LogActivityModel::where('id_activite',$id)->where('page',$page)->delete();
                        $id_activite = $id; 
                        LogActivity::addToLog('Emplacement Supprimé définitivement', $id_activite, $page);
                        $this->dispatch('emplacementUpdate');
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
                        $this->dispatch('emplacementUpdate');
                        $this->dispatch('alert',                    
                            title:'Désolé, Cet emplacement est encours d\'utilisation!',
                            timer:3000,
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
}
