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
use App\Models\SessionRestau;
use App\Models\RestauCommandeAttenteEntete;

class PipelineRestaurant extends Component
{
    protected $paginationTheme = 'bootstrap';
    use WithPagination;
   
    public $nom_table; 
    public $description; 
    public $evolution;

    public $profils; 
    public $type_user; 
    public $nom_vendeur; 
    public $phone_vendeur;

    public $id_session_posRes; 
    public $ref_session_posRes;     

    public function resetinputFields(){
        $this->nom_table = '';
        $this->description = '';
        $this->evolution = '';
    }
    public function mount(){  
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->passe_cmd_restau;
            if($autoriser == 0){
                toast()->error('Oups Désolé', 'Vous n\'êtes pas autorisé à ouvrir cette page!')->position('top-end')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
                $this->redirect('/bienvenue', navigate: true);
            }
            else{
                // tres important
                $id_session_pos = request('id'); // id session pos restau
                $ref_session_pos = request('ref'); // reference session pos restau

                $sess = SessionRestau::where('societe',auth()->user()->societe)->where('id',$id_session_pos)->first();               
                $this->id_session_posRes = $sess->id;
                $this->ref_session_posRes = $sess->session_id;
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
        $mod_crm = $entite_mod[0]->mod_crm;
        $soldeClient = $entite_mod[0]->solde;
        $this->activer_fidelite = $entite_mod[0]->activer_fidelite; 
        if($dateJour <= $jourValid){ 
            if($mod_crm == 1){   
                $title = 'Pipeline Restaurant | WamsCo';
                $module = 'Gestion Point vente';
                $title_fils = 'Pipeline';
                $lien = 'restau_sessions?active=5&champ=2-1&choix=1';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');
                $dateJour = date('Y-m-d');             
                $aller = -2;
                $id = $this->id_session_posRes; 
                $ref = $this->ref_session_posRes;
                                
                $espace = EspaceRestau :: where('societe',auth()->user()->societe)->orderBy('id','asc')->get(); 
                $opportuniter = TableRestau :: where('societe',auth()->user()->societe)->orderBy('step')->orderBy('position')->get()->groupBy('step');  
                $opportuniterCount = $opportuniter->count();

                $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
                $autoriser = $role[0]->voir_cmd_autre_restau;
                if($autoriser == 1){ 
                    $cmd = RestauCommandeAttenteEntete ::where('societe',auth()->user()->societe)->where('ref_session_pos',$this->ref_session_posRes)->orderBy('id','asc')->get();
                }
                else{ 
                    $cmd = RestauCommandeAttenteEntete ::where('user_id',auth()->user()->id)->where('societe',auth()->user()->societe)->where('ref_session_pos',$this->ref_session_posRes)->orderBy('id','asc')->get();
                }                
                $cmdCount = $cmd->count();

                $user = Utilisateur::where('societe',auth()->user()->societe)->orderBy('name','asc')->get(); 

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
                    return view('livewire.gestion-point-vente.restaurant.pipeline-restaurant',compact('title_fils','module','lien','dateJour','espace','opportuniter','opportuniterCount','cmdCount','user'))->layout('components.layouts.app_pos',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant','aller','id','ref'));
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
                return view('livewire.bienvenue',compact('dateJour','entite_mod'))->layout('components.layouts.app',compact('title','module','title_fils','lien','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));          
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
    public function table(int $id){
        $espaces = EspaceRestau::where('societe',auth()->user()->societe)->where('id', $id)->first();
        $this->evolution = $espaces->id;
    }
    public function store(){        
        $this->validate([
            'nom_table'=>'required|max:255',
            'description'=>'nullable|max:255',
            'evolution'=>'required|numeric', // id espace
        ]);   
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_table;
            if($autoriser == 1){                                    
                $test_espace = EspaceRestau::where('societe',auth()->user()->societe)->where('id', $this->evolution)->count();
                if($test_espace > 0){

                    $etapes = EspaceRestau::where('societe',auth()->user()->societe)->where('id', $this->evolution)->first();
                    $nom_espace = $etapes->nom_espace; 

                    $position = 0;
                    $dates = date('dmy/His');  
                    $token_ok = 'REST/'.$dates;
                    $reference = $token_ok;
                    TableRestau :: create(['nom_table'=>$this->nom_table,'reference'=>$reference,'description'=>$this->description,'nom_espace'=>$nom_espace,'id_espace'=>$this->evolution,
                    'step'=>$this->evolution,'position'=>$position,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->email,'user_id'=>auth()->user()->id]);
                    // ceci recupere le dernier enregistrement cree a l'instant
                    $dernier_id = TableRestau::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id; 
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
    public function getTotalParOpportunite(int $id_espace){ 
        return TableRestau :: where('societe',auth()->user()->societe)->where('id_espace', $id_espace)->count(); 
    }
    public function moveTask($taskId, $newEtape, $newPosition){    
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_table;
            if($autoriser == 1){
                $task = TableRestau::find($taskId)->update([ 'step' => $newEtape,'position' => $newPosition,]);    
                // Réordonner dans cette colonne
                TableRestau::where('step', $newEtape)->orderBy('position')->get()
                    ->each(function ($t, $i) {
                        $t->update(['position' => $i]);
                    });

                // NB:  ceci enregistrer dans l'opportunite l'etape actuelle
                $Task =  TableRestau::where('societe',auth()->user()->societe)->where('id',$taskId)->first();
                $id_step_task = $Task->step;
                $esp =  EspaceRestau::where('societe',auth()->user()->societe)->where('id',$id_step_task)->first();
                $nom_espace = $esp->nom_espace;
                
                TableRestau :: find($taskId)->update(['nom_espace'=>$nom_espace,'id_espace'=>$id_step_task,]);        
                // Fin NB
                $id_activite = $taskId;   
                $page = 'TableRestau';    
                LogActivity::addToLog('Espace restau modifée en » <strong> '.$nom_espace.' </strong>', $id_activite, $page); 
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
    public function voirTable(int $id){ 
        $esp = TableRestau :: where('societe',auth()->user()->societe)->where('id',$id)->first();
        $nom_table = $esp->nom_table;
        $reference = $esp->reference; // ceci devient id: de la table (tres important)
        $utiliser = $esp->utiliser;
        $id_caissiere = $esp->id_caissiere;
        $ref_session_restau = $esp->ref_session_restau;

        if($utiliser == 'Oui'){
            if(auth()->user()->id == $id_caissiere && $this->ref_session_posRes == $ref_session_restau){
                $this->redirect('/pos_restau?id='.$reference.'&table='.$nom_table.'&id_session_restau='.$this->id_session_posRes.'&ref_session_restau='.$this->ref_session_posRes.'&active=14&champ=1-1', navigate: true);
            }
            else{
                $this->dispatch('alert',                    
                    title:'Cette table ('.$nom_table.') est déja occupée <br> par un collaborateur(trice) ou une autre session!',
                    timer:7000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                ); 
            }
        }
        else{
            $this->redirect('/pos_restau?id='.$reference.'&table='.$nom_table.'&id_session_restau='.$this->id_session_posRes.'&ref_session_restau='.$this->ref_session_posRes.'&active=14&champ=1-1', navigate: true);
        }

    }
    public function Tables(int $id_session_posRes,string $ref_session_posRes){
        $this->redirect('/pipeline_restau?id='.$this->id_session_posRes.'&ref='.$this->ref_session_posRes.'&active=5&champ=2-1', navigate: true);
    }
    public function CmdAttente(int $id_session_posRes,string $ref_session_posRes){
        $this->redirect('/cmd_attente?id='.$this->id_session_posRes.'&ref='.$this->ref_session_posRes.'&active=5&champ=2-1', navigate: true);
    }
     public function backSession(int $id_session_posRes,string $ref_session_posRes){
        $this->redirect('/detail_restau_session?id='.$this->id_session_posRes.'&ref='.$this->ref_session_posRes.'&active=5&champ=2-1&choix=1', navigate: true);
    }
}
