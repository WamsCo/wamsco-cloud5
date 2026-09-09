<?php

namespace App\Livewire\GestionPointVente\Restaurant;

use Livewire\Component;
use Livewire\WithPagination;
use App\Helpers\LogActivity;
use App\Models\LogActivity as LogActivityModel;
use App\Models\TableRestau;
use App\Models\Entite;
use App\Models\DeviseTva;
use App\Models\Role;
use App\Models\Parametre;
use App\Models\EspaceRestau;

class TableRestaurant extends Component
{
    protected $paginationTheme = 'bootstrap';
    use WithPagination;

    public $ids;
    public $nom_table; 
    public $description; 
    public $evolution;
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
        $this->nom_table ='';
        $this->description ='';
        $this->evolution ='';
    }
    public function updatingQuery(){
        $this->resetPage();
    }
    public function mount(){
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->consulter_table;
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
        $mod_pointe_vente = $entite_mod[0]->mod_pointe_vente; 
        $soldeClient = $entite_mod[0]->solde;
        if($dateJour <= $jourValid){
            if($mod_pointe_vente == 1){
                $title = 'Table | WamsCo';
                $module = 'Gestion table';
                $title_fils = 'Table';
                $lien = 'table_restau?active=5&champ=2-1&choix=4';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');      
                $dateJour = date('Y-m-d');
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');

                $table = TableRestau :: where('societe_id',auth()->user()->societe_id)->where('nom_table','like','%'.$this->query.'%')->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage); 
                $tableCount = $table->count();
                
                // Parametre
                $verifie = Parametre ::where('societe_id',auth()->user()->societe_id)->count();
                if($verifie > 0){                
                    $config = Parametre::where('societe_id',auth()->user()->societe_id)->limit(1)->get();
                    $this->activer_ecran_cuisine = $config[0]->activer_ecran_cuisine;               
                }
                else{               
                    $this->activer_ecran_cuisine = 0;       
                }
                            
                $espace = EspaceRestau :: where('societe_id',auth()->user()->societe_id)->orderBy('id','asc')->get(); 

                $deviseTva = DeviseTva :: where('societe_id',auth()->user()->societe_id)->limit(1)->orderBy('id','asc')->count(); 
                if($deviseTva == 0){
                    $this->devise = 'FCFA';
                }
                else{
                    $deviseTva = DeviseTva :: where('societe_id',auth()->user()->societe_id)->limit(1)->orderBy('id','asc')->get(); 
                    $this->devise = $deviseTva[0]->devise;
                }

                $resultat = TableRestau::where('societe_id',auth()->user()->societe_id)->get();  
                $nbreTotalEspaceRestau = $resultat->count();     

                $derniereActivite = TableRestau::where('societe_id',auth()->user()->societe_id)->latest('updated_at')->first(); 

                $page = 'TableRestau'; // Pour evenement lie
                $log = LogActivityModel::where('societe_id',auth()->user()->societe_id)->where('page', $page)->limit(50)->orderBy('id','desc')->get();
                $logCount = $log->count();   
                
                $entite_mod = Entite::where('id',auth()->user()->societe_id)->get();          
                $jourValid = $entite_mod[0]->validite_mod; 
                // ceci pour trouver le nombre de jour restant avant expiration
                $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24));
                return view('livewire.gestion-point-vente.restaurant.table.table-restaurant',compact('title_fils','module','lien','dateJour','table','tableCount','espace','nbreTotalEspaceRestau','derniereActivite','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
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
            'nom_table'=>'required|max:255',
            'description'=>'nullable|max:255',
            'evolution'=>'required|numeric', // id espace
        ]);   
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_table;
            if($autoriser == 1){                                    
                $test_espace = EspaceRestau::where('societe_id',auth()->user()->societe_id)->where('id', $this->evolution)->count();
                if($test_espace > 0){

                    $etapes = EspaceRestau::where('societe_id',auth()->user()->societe_id)->where('id', $this->evolution)->first();
                    $nom_espace = $etapes->nom_espace;

                    $position = 0;
                    $length = 3;
                    $token = bin2hex(random_bytes($length));
                    $dates = date('dmy/His'); 
                    $token_ok = 'REST/'.$dates.'/'.$token;                   
                    $reference = $token_ok;
                    $tabloRes = TableRestau :: create(['nom_table'=>$this->nom_table,'reference'=>$reference,'description'=>$this->description,'nom_espace'=>$nom_espace,'id_espace'=>$this->evolution,
                    'step'=>$this->evolution,'position'=>$position,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                    // ceci recupere le dernier enregistrement cree a l'instant
                    $dernier_id = $tabloRes->id; 
                    $this->dispatch('pipelineStore');
                    $id_activite = $dernier_id;   
                    $page = 'TableRestau';    
                    LogActivity::addToLog('Table » '.$this->nom_table.' ('.$reference.') créée', $id_activite, $page); 
                    $this->dispatch('alert',                    
                        title:'Table » '.$this->nom_table.' ('.$reference.') créée !',
                        timer:5000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    ); 
                    $this->resetinputFields();

                }else{ 
                    $this->dispatch('alert',                    
                        title:'Désolé, cet espace n\'existe pas! <br> Sélectionnez ou créez un autre',
                        timer:5000,
                        icon:'error',
                        toast:true,
                        showConfirmButton: false,
                        position:'center',
                    ); 
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
    public function edit($id){
        $table  = TableRestau::where('id',$id)->first();
        $this->ids = $table->id;
        $this->nom_table = $table->nom_table;
        $this->description = $table->description;
        $this->evolution = $table->id_espace;
    }
    public function update(){
        $validatedata = $this->validate([
            'nom_table'=>'required|max:255',
            'description'=>'nullable|max:255',
            'evolution'=>'required|numeric',    
        ]); 
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_table;
            if($autoriser == 1){  
                if($this->ids){

                    $etapes = EspaceRestau::where('societe_id',auth()->user()->societe_id)->where('id', $this->evolution)->first();
                    $nom_espace = $etapes->nom_espace; 

                    $position = 0;                   
                    TableRestau::find($this->ids)->update(['nom_table'=>$this->nom_table,'description'=>$this->description,'nom_espace'=>$nom_espace,'id_espace'=>$this->evolution,
                                'step'=>$this->evolution,'position'=>$position,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                    $id_activite = $this->ids; 
                    $page = 'TableRestau';    
                    LogActivity::addToLog('Table » '.$this->nom_table.' modifiée', $id_activite, $page);
                    $this->dispatch('alert',                    
                        title:'table ('.$this->nom_table.') modifié!',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );                    
                    $this->redirect('/table_restau?active=5&champ=2-1&choix=4', navigate: true);
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
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->supprimer_table;
            if($autoriser == 1){   
                if($id){
                    $utilisers = TableRestau::where('id',$id)->first();
                    $utiliser = $utilisers->utiliser;
                    if($utiliser == 'Non'){ 
                        $page = 'TableRestau'; // Pour evenement lie
                        TableRestau::where('id',$id)->delete();                 
                        LogActivityModel::where('id_activite',$id)->where('page',$page)->delete();
                        $id_activite = $id; 
                        LogActivity::addToLog('Table supprimée définitivement', $id_activite, $page);
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
                            title:'Désolé, cette table est encours d\'utilisation!',
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
