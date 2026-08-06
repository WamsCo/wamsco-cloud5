<?php

namespace App\Livewire\CRM;

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
use App\Models\Etape;
use App\Models\Opportunite;

class Pipeline extends Component
{
    protected $paginationTheme = 'bootstrap';
    use WithPagination;

    // pour recherche client
    public $parNomTier; // Pour la recherche
    public $records;
    public $recordCount;
    public $showdiv = false;

    public $client;
    public $ids_client; // id client selectionne    
    public $nom_opportunite;
    public $email_contact;
    public $telephone_contact;
    public $montant_attendu;
    public $priorite = 'Faible';
    public $evolution;
    public $nom_societe; 
    
    public $profils; 
    public $type_user; 
    public $nom_vendeur; 
    public $phone_vendeur; 
    
     public function resetinputFields(){
        $this->client = '';
        $this->nom_opportunite = '';
        $this->email_contact = '';
        $this->telephone_contact = '';
        $this->montant_attendu = '';
        $this->evolution = '';
        $this->priorite = 'Faible';
    }
    public function mount(){  
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->consulter_opportunite;
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
    public function render(){
    
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();
        $jourValid = $entite_mod[0]->validite_mod;
        $mod_crm = $entite_mod[0]->mod_crm;
        $soldeClient = $entite_mod[0]->solde;
        $this->activer_fidelite = $entite_mod[0]->activer_fidelite; 
        if($dateJour <= $jourValid){ 
            if($mod_crm == 1){   
                $title = 'Pipeline CRM | WamsCo';
                $module = 'CRM';
                $title_fils = 'Pipeline';
                $lien = 'pipeline_tiers?active=3&champ=3-3&choix=1';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');
                $dateJour = date('Y-m-d');             

                $etape = Etape :: where('societe',auth()->user()->societe)->orderBy('id','asc')->get(); 

                // if(auth()->user()->societe == "Administration" && auth()->user()->type_user == "Administrateur"){ 
                if(auth()->user()->type_user == "Administrateur"){ 

                    $opportuniter = Opportunite :: where('societe',auth()->user()->societe)->orderBy('step')->orderBy('position')->get()->groupBy('step');
                }
                else{

                    $opportuniter = Opportunite::where('societe',auth()->user()->societe)->where('vendeur', auth()->user()->id)->orderBy('step')->orderBy('position')->get()->groupBy('step');
                } 

                $opportuniterCount = $opportuniter->count();

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
                    return view('livewire.crm.pipeline',compact('title_fils','module','lien','dateJour','etape','opportuniter','opportuniterCount','user'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
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
    public function searchResult(){ 
        if(!empty($this->client)){
            if(ctype_alpha($this->client)){ // ctype_alpha: cette fonction permet de savoir si le caractere ou mot est une lettre  
                $this->records = Tier::where('etat',1)->where('societe',auth()->user()->societe)->where('nom','like','%'.$this->client.'%')->orderBy('nom','asc')->limit(8)->get(); 
                $this->recordCount = Tier::where('etat',1)->where('societe',auth()->user()->societe)->where('nom','like','%'.$this->client.'%')->count();
                $this->showdiv = true;
            }
            else{
                $this->records = Tier::where('etat',1)->where('societe',auth()->user()->societe)->where('telephone','like','%'.$this->client.'%')->orderBy('nom','asc')->limit(8)->get(); 
                $this->recordCount = Tier::where('etat',1)->where('societe',auth()->user()->societe)->where('telephone','like','%'.$this->client.'%')->count(); 
                $this->showdiv = true;
            }        
        }
        else{
            $this->showdiv = false;
        }
    }
    public function ajouterTier($id = 0){
        $record = Tier::where('id', $id)->first();
        $this->client = $record->nom.' » '.$record->raison_sociale;
        $this->ids_client = $record->id;
        $this->email_contact = $record->email;
        $this->telephone_contact = $record->telephone;
        $this->showdiv = false;
        $this->nom_opportunite = 'Opportunité de '.$this->client;        
        $this->nom_societe = $record->raison_sociale;
    }
    public function store(){        
        $this->validate([
            'client'=>'required|max:255',
            'nom_opportunite'=>'required|max:255',
            'email_contact'=>'nullable|email|max:255',
            'telephone_contact'=>'required|max:255',
            'montant_attendu'=>'required|numeric',
            'evolution'=>'required|numeric', // id etape
            'priorite'=>'required|max:255', 
        ]);   
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_opportunite;
            if($autoriser == 1){ 

                $test_tiers = Tier ::where('societe',auth()->user()->societe)->where('id',$this->ids_client)->count();
                if($test_tiers > 0){                    

                    $test_etapes = Etape::where('societe',auth()->user()->societe)->where('id', $this->evolution)->count();
                    if($test_etapes > 0){

                        $etapes = Etape::where('societe',auth()->user()->societe)->where('id', $this->evolution)->first();
                        $nom_etape = $etapes->nom_etape; 
                        $date_cloture = date('Y-m-d', strtotime('2 month'));                  

                        $position = 0;
                        Opportunite :: create(['client'=>$this->client,'id_client'=>$this->ids_client,'nom_opportunite'=>$this->nom_opportunite,'email_contact'=>$this->email_contact,'nom_societe'=>$this->nom_societe,
                        'telephone_contact'=>$this->telephone_contact,'montant_attendu'=>$this->montant_attendu,'etape'=>$nom_etape,'id_etape'=>$this->evolution,'step'=>$this->evolution,'position'=>$position,'priorite'=>$this->priorite,
                        'date_cloture'=>$date_cloture,'pays'=>'Cameroon','langue'=>'Français','vendeur'=>auth()->user()->id,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->email,'user_id'=>auth()->user()->id]);
                        // ceci recupere le dernier enregistrement cree a l'instant
                        $dernier_id = Opportunite::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id; 
                        $this->dispatch('pipelineStore');
                        $id_activite = $dernier_id;   
                        $page = 'Opportunite';    
                        LogActivity::addToLog('Opportunité » '.$this->nom_opportunite.' créée', $id_activite, $page); 
                        $this->dispatch('alert',                    
                            title:'Opportunité ('.$this->nom_opportunite.') créée!',
                            timer:5000,
                            icon:'success',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        ); 
                        $this->resetinputFields();

                    }else{
                        $this->dispatch('alert',                    
                            title:'Désolé, cette étape n\'existe pas! <br> Sélectionnez ou créez une autre',
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
                        title:'Désolé, ce nom n\'existe pas! <br> Sélectionnez ou créez un autre',
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
    public function opportuniter(int $id){
        $etapes = Etape::where('societe',auth()->user()->societe)->where('id', $id)->first();
        $this->evolution = $etapes->id;
    }
    public function getTotalParEtape(int $id_etape){ 

        if(auth()->user()->type_user == "Administrateur"){ 

            return Opportunite :: where('societe',auth()->user()->societe)->where('id_etape', $id_etape)->sum('montant_attendu'); 
        }
        else{

            return Opportunite :: where('societe',auth()->user()->societe)->where('vendeur', auth()->user()->id)->where('id_etape', $id_etape)->sum('montant_attendu'); 
        }            
    }
    public function getTotalParOpportunite(int $id_etape){

        if(auth()->user()->type_user == "Administrateur"){ 

            return Opportunite :: where('societe',auth()->user()->societe)->where('id_etape', $id_etape)->count();  
        }
        else{

            return Opportunite :: where('societe',auth()->user()->societe)->where('vendeur', auth()->user()->id)->where('id_etape', $id_etape)->count(); 
        }            
    }      
    public function moveTask($taskId, $newEtape, $newPosition){    
        
        $task = Opportunite::find($taskId)->update([ 'step' => $newEtape,'position' => $newPosition,]);    
        // Réordonner dans cette colonne
        Opportunite::where('step', $newEtape)->orderBy('position')->get()
            ->each(function ($t, $i) {
                $t->update(['position' => $i]);
            });

        // NB:  ceci enregistrer dans l'opportunite l'etape actuelle
        $Task =  Opportunite::where('societe',auth()->user()->societe)->where('id',$taskId)->first();
        $id_step_task = $Task->step;
        $etap =  Etape::where('societe',auth()->user()->societe)->where('id',$id_step_task)->first();
        $nom_etape = $etap->nom_etape;
        
        Opportunite :: find($taskId)->update(['etape'=>$nom_etape,'id_etape'=>$id_step_task,]);        
        // Fin NB
        $id_activite = $taskId;   
        $page = 'Opportunite';    
        LogActivity::addToLog('Étapes opportunité modifée en » <strong> '.$nom_etape.' </strong>', $id_activite, $page); 
    }
    public function voirDetail(int $id){
        $this->redirect('/detail_pipeline?id='.$id.'&active=3&champ=3-3&choix=1', navigate: true);
    }
}
