<?php

namespace App\Livewire\GestionPaie;

use Livewire\Component;
use Livewire\Attributes\Validate; 
use Livewire\WithPagination;
use App\Helpers\LogActivity;
use App\Models\LogActivity as LogActivityModel;
use App\Models\CategoriePaie;
use App\Models\Entite;
use App\Models\Role;

class CategoriePaies extends Component
{
    protected $paginationTheme = 'bootstrap';
    use WithPagination;
    
    public $ids;

    #[Validate('required|max:255')]
    public $nom_categorie;
    
    #[Validate('max:255')]
    public $description;

    public $confirmer;
    public $query;
    public $parPage = 24;

    
    public function resetinputFields(){
        $this->nom_categorie ='';
        $this->description ='';
    }
    public function updatingQuery(){
        $this->resetPage();
    }
    public function mount(){
        
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->consulter_categorie;
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
        $mod_gestion_stock = $entite_mod[0]->mod_gestion_stock;
        $soldeClient = $entite_mod[0]->solde;
        if($dateJour <= $jourValid){
            if($mod_gestion_stock == 1){
                $menu = 'Catégorie'; 
                $title = 'Catégorie paie | WamsCo'; 
                $module = 'Gestion Paie';
                $title_fils = 'Catégorie paie';
                $lien = 'liste_categorie?active=15&champ=3-1&choix=1'; 
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');
                $dateJour = date('Y-m-d');
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');           
                $categorie = CategoriePaie :: where('societe',auth()->user()->societe)->where('nom_categorie','like','%'.$this->query.'%')->orderBy('nom_categorie','asc')->paginate($this->parPage); 
                $categoriecount = $categorie->count();             
                
                $page = 'CategoriePaie'; // Pour evenement lie
                $log = LogActivityModel::where('user_societe',auth()->user()->societe)->where('page', $page)->limit(5)->orderBy('id','desc')->get();
                $logCount = $log->count();
            
                $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();                      
                $jourValid = $entite_mod[0]->validite_mod; 
                // ceci pour trouver le nombre de jour restant avant expiration
                $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24));
                return view('livewire.gestion-paie.categorie.categorie-paie',compact('title_fils','module','lien','dateJour','categorie','categoriecount','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));     
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
            $title_fils = 'Magasin';
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
        $this->validate(); 

        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_categorie;
            if($autoriser == 1){ 
                CategoriePaie :: create(['nom_categorie'=>$this->nom_categorie,'description'=>$this->description,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->email,'user_id'=>auth()->user()->id]);
                // ceci recupere le dernier enregistrement cree a l'instant
                $dernier_id = CategoriePaie::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id; 
                $id_activite = $dernier_id;   
                $page = 'CategoriePaie';    
                LogActivity::addToLog('Catégorie paie » '.$this->nom_categorie.' créée', $id_activite, $page); 
                $this->dispatch('alert',                    
                    title:'Categorie paie ('.$this->nom_categorie.') créée!',
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
        $categorie  = CategoriePaie::where('id',$id)->first();
        $this->ids = $categorie->id;
        $this->nom_categorie = $categorie->nom_categorie;
        $this->description = $categorie->description;
    }
    public function update(){
        $this->validate();        
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_categorie;
            if($autoriser == 1){  
                if($this->ids){
                    CategoriePaie::find($this->ids)->update(['nom_categorie'=>$this->nom_categorie,'description'=>$this->description,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->email,'user_id'=>auth()->user()->id]);
                    $this->dispatch('categorietUpdate');
                    $id_activite = $this->ids; 
                    $page = 'CategoriePaie';
                    LogActivity::addToLog('Catégorie paie » '.$this->nom_categorie.' modifiée', $id_activite, $page);                      
                    $this->dispatch('alert',                    
                        title:'Categorie paie ('.$this->nom_categorie.') modifiée!',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    ); 
                    $this->resetinputFields();
                    // $this->redirect('/categorie', navigate: true);
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
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->supprimer_categorie;
            if($autoriser == 1){   
                if($id){
                    $page = 'CategoriePaie'; // Pour evenement lie
                    CategoriePaie::where('id',$id)->delete();
                    LogActivityModel::where('id_activite',$id)->where('page',$page)->delete();
                    $id_activite = $id; 
                    LogActivity::addToLog('Catégorie paie supprimée définitivement', $id_activite, $page);  
                    $this->dispatch('categorietUpdate');
                    $this->dispatch('alert',                    
                        title:'Suppression effectuée!',
                        timer:3000,
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
