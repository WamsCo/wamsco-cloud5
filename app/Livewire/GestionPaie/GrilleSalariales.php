<?php

namespace App\Livewire\GestionPaie;

use Livewire\Component;
use Livewire\Attributes\Validate; 
use Livewire\WithPagination;
use App\Helpers\LogActivity;
use App\Models\LogActivity as LogActivityModel;
use App\Models\GrilleSalariale;
use App\Models\Entite;
use App\Models\DeviseTva;
use App\Models\Role;
use App\Models\Parametre;

class GrilleSalariales extends Component
{
    protected $paginationTheme = 'bootstrap';
    use WithPagination;

    public $ids;
    #[Validate('required|numeric|min:1')]
    public $categorie;
    #[Validate('required|max:5')]
    public $echelon;
    #[Validate('required|numeric|min:1')]
    public $salaire_base;

    public $confirmer;
    public $query;
    public $parPage = 20;

    public $orderField = 'categorie'; 
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
    public function resetinputFields(){
        $this->categorie ='';
        $this->echelon ='';
        $this->salaire_base ='';
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
                $title = 'Grille Salariale | WamsCo';
                $module = 'Gestion Paie';
                $title_fils = 'Grille salariale';
                $lien = 'grille_salaire?active=15&champ=3-1&choix=2';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');      
                $dateJour = date('Y-m-d');
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');

                $grille = GrilleSalariale :: where('societe',auth()->user()->societe)->where('categorie','like','%'.$this->query.'%')->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage); 
                $grilleCount = $grille->count(); 

                $deviseTva = DeviseTva :: where('societe',auth()->user()->societe)->limit(1)->orderBy('id','asc')->count(); 
                if($deviseTva == 0){
                    $this->devise = 'FCFA';
                }
                else{
                    $deviseTva = DeviseTva :: where('societe',auth()->user()->societe)->limit(1)->orderBy('id','asc')->get(); 
                    $this->devise = $deviseTva[0]->devise;
                }

                $page = 'GrilleSalariale'; // Pour evenement lie
                $log = LogActivityModel::where('user_societe',auth()->user()->societe)->where('page', $page)->limit(10)->orderBy('id','desc')->get();
                $logCount = $log->count();   
                
                $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();          
                $jourValid = $entite_mod[0]->validite_mod; 
                // ceci pour trouver le nombre de jour restant avant expiration
                $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24));
                return view('livewire.gestion-paie.grille-salaire.grille_salariales',compact('title_fils','module','lien','dateJour','grille','grilleCount','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
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
        $this->validate(); 

        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_categorie;
            if($autoriser == 1){ 
                $reference = 'CAT-'.$this->categorie.''.$this->echelon;
                GrilleSalariale :: create(['reference'=>$reference,'categorie'=>$this->categorie,'echelon'=>$this->echelon,'salaire_base'=>$this->salaire_base,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->email,'user_id'=>auth()->user()->id]);
                // ceci recupere le dernier enregistrement cree a l'instant
                $dernier_id = GrilleSalariale::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id; 
                $id_activite = $dernier_id;   
                $page = 'GrilleSalariale';    
                LogActivity::addToLog('Catégorie salariale » '.$this->categorie.''.$this->echelon.' créée', $id_activite, $page); 
                $this->dispatch('alert',                    
                    title:'Catégorie salariale ('.$this->categorie.''.$this->echelon.') créée!',
                    timer:5000,
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
        $grille  = GrilleSalariale::where('id',$id)->first();
        $this->ids = $grille->id;
        $this->categorie = $grille->categorie;
        $this->echelon = $grille->echelon;
        $this->salaire_base = $grille->salaire_base;
    }
    public function update(){
        $this->validate();        
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_categorie;
            if($autoriser == 1){  
                if($this->ids){
                    $reference = 'CAT-'.$this->categorie.''.$this->echelon;
                    GrilleSalariale::find($this->ids)->update(['reference'=>$reference,'categorie'=>$this->categorie,'echelon'=>$this->echelon,'salaire_base'=>$this->salaire_base,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->email,'user_id'=>auth()->user()->id]);
                    $this->dispatch('grilleUpdate');
                    $id_activite = $this->ids; 
                    $page = 'GrilleSalariale';
                    LogActivity::addToLog('Catégorie salariale » '.$this->categorie.''.$this->echelon.' modifiée', $id_activite, $page);  
                    $this->dispatch('alert',   
                        title:'Catégorie salariale ('.$this->categorie.''.$this->echelon.') modifiée!',
                        timer:5000,
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
                    $page = 'GrilleSalariale'; // Pour evenement lie
                    GrilleSalariale::where('id',$id)->delete();
                    LogActivityModel::where('id_activite',$id)->where('page',$page)->delete();
                    $id_activite = $id; 
                    LogActivity::addToLog('Catégorie salariale supprimée définitivement', $id_activite, $page);  
                    $this->dispatch('grilleUpdate');
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
