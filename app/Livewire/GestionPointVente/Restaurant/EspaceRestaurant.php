<?php

namespace App\Livewire\GestionPointVente\Restaurant;

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
use App\Models\EspaceRestau;
use App\Models\TableRestau;

class EspaceRestaurant extends Component
{
    protected $paginationTheme = 'bootstrap';
    use WithPagination;

    public $ids;

    #[Validate('required|max:255')]
    public $nom_espace;
    
    #[Validate('nullable|max:255')]
    public $description;
    
    public $confirmer;
    public $query;
    public $parPage = 24;

    
    public function resetinputFields(){
        $this->nom_espace ='';
        $this->description ='';
    }
    public function updatingQuery(){
        $this->resetPage();
    }
    public function mount(){  
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->consulter_espace;
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
        $mod_crm = $entite_mod[0]->mod_crm;
        $soldeClient = $entite_mod[0]->solde;
        $this->activer_fidelite = $entite_mod[0]->activer_fidelite; 
        if($dateJour <= $jourValid){ 
            if($mod_crm == 1){    
                $title = 'Espace Restaurant | WamsCo';
                $module = 'Gestion point vente';
                $title_fils = 'Espace Restaurant';
                $lien = 'espace_restau?active=5&champ=2-1&choix=5';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');           
                $espace = EspaceRestau :: where('societe_id',auth()->user()->societe_id)->where('nom_espace','like','%'.$this->query.'%')->orderBy('id','asc')->paginate($this->parPage); 
                $espaceCount = $espace->count();   

                $resultat = EspaceRestau :: where('societe_id',auth()->user()->societe_id)->get();  
                $nbreTotalEspaceRestau = $resultat->count(); 
                // $catRestau = $resultat->where('restaurant','Oui')->count(); 
                
                $derniereActivite = EspaceRestau::where('societe_id',auth()->user()->societe_id)->latest('updated_at')->first(); 

                $page = 'EspaceRestau'; // Pour evenement lie
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
                $entite_mod = Entite::where('id',auth()->user()->societe_id)->get(); 
                $jourValid = $entite_mod[0]->validite_mod; 
                // ceci pour trouver le nombre de jour restant avant expiration
                $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24));           
                    return view('livewire.gestion-point-vente.restaurant.bloc.bloc-restaurant',compact('title_fils','module','lien','dateJour','espace','espaceCount','nbreTotalEspaceRestau','derniereActivite','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));                  
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
            $autoriser = $role[0]->creer_espace;
            if($autoriser == 1){ 
                $espaceRes = EspaceRestau :: create(['nom_espace'=>$this->nom_espace,'description'=>$this->description,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->email,'user_id'=>auth()->user()->id]);
                // ceci recupere le dernier enregistrement cree a l'instant
                $dernier_id = $espaceRes->id; 
                $id_activite = $dernier_id;   
                $page = 'EspaceRestau';    
                LogActivity::addToLog('Espace » '.$this->nom_espace.' créée', $id_activite, $page); 
                $this->dispatch('alert',                    
                    title:'Espace ('.$this->nom_espace.') crée!',
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
        $etape  = EspaceRestau::where('id',$id)->first();
        $this->ids = $etape->id;
        $this->nom_espace = $etape->nom_espace;
        $this->description = $etape->description;
    }
    public function update(){
        $this->validate();        
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_espace;
            if($autoriser == 1){  
                if($this->ids){
                    EspaceRestau::find($this->ids)->update(['nom_espace'=>$this->nom_espace,'description'=>$this->description,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->email,'user_id'=>auth()->user()->id]);
                    $id_activite = $this->ids; 
                    $page = 'EspaceRestau';
                    LogActivity::addToLog('Espace » '.$this->nom_espace.' modifié', $id_activite, $page);                      
                    $this->dispatch('alert',                    
                        title:'Espace ('.$this->nom_espace.') modifié!',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    ); 
                    $this->redirect('/espace_restau?active=5&champ=2-1&choix=5', navigate: true);
                }
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
    public function confirmerDelete($id){
        $this->confirmer = $id;        
    } 
    public function supprimer($id){
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->supprimer_espace;
            if($autoriser == 1){   
                if($id){
                    $test_table = TableRestau::where('societe_id',auth()->user()->societe_id)->where('id_espace',$id)->count();
                    if($test_table == 0){  

                        EspaceRestau::where('id',$id)->delete();
                        
                        $page = 'EspaceRestau'; // Pour evenement lie
                        LogActivityModel::where('id_activite',$id)->where('page',$page)->delete();
                        $id_activite = $id; 
                        LogActivity::addToLog('Espace restau supprimé définitivement', $id_activite, $page);  
                        $this->dispatch('etapeUpdate');
                        $this->dispatch('alert',                    
                            title:'Suppression effectuée!',
                            timer:3000,
                            icon:'success',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        );
                        $this->redirect('/espace_restau?active=5&champ=2-1&choix=5', navigate: true);  
                    }
                    else{
                        $this->dispatch('alert',                    
                            title:'Désolé, vous ne pouvez pas supprimer : cet espace est lié à une table!',
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
