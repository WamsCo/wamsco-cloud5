<?php

namespace App\Livewire\GestionTache;

use Livewire\Component;
use Livewire\Attributes\Validate; 
use App\Helpers\LogActivity;
use App\Models\LogActivity as LogActivityModel;
use Livewire\WithPagination;
use Livewire\WithFileUploads; 
use App\Models\Tier;
use App\Models\Role;
use App\Models\Utilisateur;
use App\Models\Entite;
use App\Models\DeviseTva;
use App\Models\EtapeTache;
use App\Models\Tache;

class EtapeTaches extends Component
{
    protected $paginationTheme = 'bootstrap';
    use WithPagination;

    public $ids;

    #[Validate('required|max:255')]
    public $nom_etape;
    
    #[Validate('max:255')]
    public $description;
    public $opacite;

    public $confirmer;
    public $query;
    public $parPage = 24;

     public function resetinputFields(){
        $this->nom_etape ='';
        $this->description ='';
    }
    public function updatingQuery(){
        $this->resetPage();
    }
    public function mount(){  
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->consulter_etapeTache;
            if($autoriser == 0){
                toast()->error('Oups Désolé', 'Vous n\'êtes pas autorisé à ouvrir cette page!')->position('top-end')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
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
        $mod_tache = $entite_mod[0]->mod_tache;
        $soldeClient = $entite_mod[0]->solde;
        $this->activer_fidelite = $entite_mod[0]->activer_fidelite; 
        if($dateJour <= $jourValid){ 
            if($mod_tache == 1){  
                $title = 'Etape Tâche | WamsCo';
                $module = 'Tâche';
                $title_fils = 'Etape';
                $lien = 'etapes_taches?active=14&champ=1-2';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');           
                $etape = EtapeTache :: where('societe_id',auth()->user()->societe_id)->where('nom_etape','like','%'.$this->query.'%')->orderBy('id','asc')->paginate($this->parPage); 
                $etapeCount = $etape->count();   

                $resultat = EtapeTache :: where('societe_id',auth()->user()->societe_id)->get();  
                $nbreTotalEtapeTache = $resultat->count(); 
                // $catRestau = $resultat->where('restaurant','Oui')->count(); 
                
                $derniereActivite = EtapeTache::where('societe_id',auth()->user()->societe_id)->latest('updated_at')->first();                 

                $page = 'EtapeTache'; // Pour evenement lie
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
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');            
                $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get(); 
                $jourValid = $entite_mod[0]->validite_mod; 
                // ceci pour trouver le nombre de jour restant avant expiration
                $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24));           
                    return view('livewire.gestion-tache.etape-tache',compact('title_fils','module','lien','dateJour','etape','etapeCount','nbreTotalEtapeTache','derniereActivite','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));                  
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

        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_etapeTache;
            if($autoriser == 1){ 
                $etapTach = EtapeTache :: create(['nom_etape'=>$this->nom_etape,'description'=>$this->description,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->email,'user_id'=>auth()->user()->id]);
                // ceci recupere le dernier enregistrement cree a l'instant
                $dernier_id = $etapTach->id; 
                $id_activite = $dernier_id;   
                $page = 'EtapeTache';    
                LogActivity::addToLog('Étape tâche » '.$this->nom_etape.' créée', $id_activite, $page); 
                $this->dispatch('alert',                    
                    title:'Étape ('.$this->nom_etape.') créée!',
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
        $etape  = EtapeTache::where('id',$id)->first();
        $this->ids = $etape->id;
        $this->nom_etape = $etape->nom_etape;
        $this->description = $etape->description;
        $this->opacite = $etape->opacite;
    }
    public function update(){
        $this->validate();        
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_etapeTache;
            if($autoriser == 1){  
                if($this->ids){
                    EtapeTache::find($this->ids)->update(['nom_etape'=>$this->nom_etape,'description'=>$this->description,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,
                    'nom_user'=>auth()->user()->email,'user_id'=>auth()->user()->id]);
                    $this->dispatch('etapeUpdate');
                    $id_activite = $this->ids; 
                    $page = 'EtapeTache';
                    LogActivity::addToLog('Étape tâche » '.$this->nom_etape.' modifiée', $id_activite, $page);                      
                    $this->dispatch('alert',                    
                        title:'Étape ('.$this->nom_etape.') modifiée!',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    ); 
                    $this->resetinputFields();
                    // $this->redirect('/etapes-pipeline?active=3&champ=3-3&choix=2', navigate: true);
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
            $autoriser = $role[0]->supprimer_etapeTache;
            if($autoriser == 1){   
                if($id){
                    $test_tache = Tache::where('societe_id',auth()->user()->societe_id)->where('id_etape',$id)->count();
                    if($test_tache == 0){  

                        $page = 'EtapeTache'; // Pour evenement lie
                        EtapeTache::where('id',$id)->delete();
                        Tache::where('id_etape',$id)->delete();

                        LogActivityModel::where('id_activite',$id)->where('page',$page)->delete();
                        $id_activite = $id; 
                        LogActivity::addToLog('Etape tâche supprimée définitivement', $id_activite, $page);  
                        $this->dispatch('etapeUpdate');
                        $this->dispatch('alert',                    
                            title:'Suppression effectuée!',
                            timer:3000,
                            icon:'success',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        );
                        // $this->redirect('/etapes-pipeline?active=3&champ=3-3&choix=2', navigate: true);  
                    }
                    else{
                        $this->dispatch('alert',                    
                            title:'Désolé, vous ne pouvez pas supprimer une étape lié à une tâche!',
                            timer:5000,
                            icon:'error',
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
    public function getTotalParTache(int $id_etape){

        if(auth()->user()->type_user == "Administrateur"){ 

            return Tache :: where('societe_id',auth()->user()->societe_id)->where('id_etape', $id_etape)->count();  
        }
        else{

            return Tache :: where('societe_id',auth()->user()->societe_id)->where('vendeur', auth()->user()->id)->where('id_etape', $id_etape)->count(); 
        }            
    } 
    // Ceci permet d'activer l'opacite sur les taches
    public function activer(){
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_etapeTache;
            if($autoriser == 1){ 
                
                $opacite = 'Non';
                EtapeTache::where('societe_id',auth()->user()->societe_id)->update(['opacite'=>$opacite]);
                $opacite = 'Oui';
                EtapeTache::find($this->ids)->update(['opacite'=>$opacite]);                       
                
                $page = 'EtapeTache'; // Pour evenement lie
                $id_activite = $this->ids; 
                LogActivity::addToLog('Opacité étape ('.$this->nom_etape.') » Activée', $id_activite, $page); 
                $this->dispatch('etapeUpdate');
                $this->dispatch('alert',                    
                    title:'Opacité étape ('.$this->nom_etape.') » Activée !',
                    timer:13000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                );
            }
            else{  
                $this->dispatch('alert',                    
                    title:'Vous n\'êtes pas autorisé à effectuer cette opération!',
                    timer:3000,
                    icon:'error',
                    toast:true,
                    showConfirmButton: false,
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
