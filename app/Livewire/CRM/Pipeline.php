<?php

namespace App\Livewire\CRM;

use Livewire\Component;
use Livewire\Attributes\Validate; 
Use Carbon\Carbon;
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
use App\Models\Note;

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
    public $ville;    
    public $pays;
    public $adresse_societe;     
    
    public $profils; 
    public $type_user; 
    public $nom_vendeur; 
    public $phone_vendeur; 

    public $date_debut; 
    public $date_fin;
    public $start;
    public $end;
    
    public $query;
    public $parSec; 
    public $parCap; 
    public $parUser;   
    public $parSource;   
    
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
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
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
        $this->date_debut = date('Y-m-d', strtotime('-1 year'));  // ceci pour affiche toutes les sessions en permanance sur 1 mois par defaut
        $this->date_fin = date('Y-m-d');  
    }
    public function render(){
    
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('id',auth()->user()->societe_id)->get();
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

                $etape = Etape :: where('societe_id',auth()->user()->societe_id)->orderBy('position','asc')->get();                

                $this->start = Carbon::parse($this->date_debut)->startOfDay(); //2016-09-29 00:00:00.000000
                $this->end = Carbon::parse($this->date_fin)->endOfDay();     // 2016-09-29 23:59:59.000000

                if(auth()->user()->type_user == "Administrateur"){ 

                    $query = Opportunite::where('societe_id', auth()->user()->societe_id);
                    if (!empty($this->query)) {
                        $query->where('ville', 'like', '%' . $this->query . '%');
                    }
                    if (!empty($this->parSec)) {
                        $query->where('secteur_activite', 'like', '%' . $this->parSec . '%');
                    }
                    if (!empty($this->parCap)) {
                        $query->where('campagne', 'like', '%' . $this->parCap . '%');
                    }
                    if (!empty($this->parSource)) {
                        $query->where('source', $this->parSource);
                    }
                    if (!empty($this->parUser)) {
                        $query->where('vendeur', $this->parUser);
                    }
                    $query->whereBetween('created_at', [$this->start, $this->end]);
                    // Tri + regroupement par étape
                    $opportuniter = $query->orderBy('step')->orderBy('position')->get()->groupBy('step');  
                }
                else{

                    $query = Opportunite::where('societe_id', auth()->user()->societe_id)->where('vendeur', auth()->user()->id);
                    if (!empty($this->query)) {
                        $query->where('ville', 'like', '%' . $this->query . '%');
                    }
                    if (!empty($this->parSec)) {
                        $query->where('secteur_activite', 'like', '%' . $this->parSec . '%');
                    }
                    if (!empty($this->parCap)) {
                        $query->where('campagne', 'like', '%' . $this->parCap . '%');
                    }
                    if (!empty($this->parSource)) {
                        $query->where('source', $this->parSource);
                    }
                    if (!empty($this->parUser)) {
                        $query->where('vendeur', $this->parUser);
                    }
                    $query->whereBetween('created_at', [$this->start, $this->end]);
                    // Tri + regroupement par étape
                    $opportuniter = $query->orderBy('step')->orderBy('position')->get()->groupBy('step');  
                } 

                $opportuniterCount = $opportuniter->count();

                $user = Utilisateur::where('societe_id',auth()->user()->societe_id)->orderBy('name','asc')->get(); 
                $note = Note::where('societe_id',auth()->user()->societe_id)->get(); 
                $utilisat = Utilisateur::where('societe_id',auth()->user()->societe_id)->orderBy('name','asc')->get(); 

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
                    return view('livewire.crm.pipeline',compact('title_fils','module','lien','dateJour','etape','opportuniter','opportuniterCount','user','note','utilisat'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
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
                $this->records = Tier::where('etat',1)->where('societe_id',auth()->user()->societe_id)->where('nom','like','%'.$this->client.'%')->orderBy('nom','asc')->limit(8)->get(); 
                $this->recordCount = Tier::where('etat',1)->where('societe_id',auth()->user()->societe_id)->where('nom','like','%'.$this->client.'%')->count();
                $this->showdiv = true;
            }
            else{
                $this->records = Tier::where('etat',1)->where('societe_id',auth()->user()->societe_id)->where('telephone','like','%'.$this->client.'%')->orderBy('nom','asc')->limit(8)->get(); 
                $this->recordCount = Tier::where('etat',1)->where('societe_id',auth()->user()->societe_id)->where('telephone','like','%'.$this->client.'%')->count(); 
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
        $this->ville = $record->ville;
        $this->adresse_societe = $record->adresse;
        $this->pays = $record->pays;
        
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
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_opportunite;
            if($autoriser == 1){ 

                $test_tiers = Tier ::where('societe_id',auth()->user()->societe_id)->where('id',$this->ids_client)->count();
                if($test_tiers > 0){                    

                    $test_etapes = Etape::where('societe_id',auth()->user()->societe_id)->where('id', $this->evolution)->count();
                    if($test_etapes > 0){

                        $etapes = Etape::where('societe_id',auth()->user()->societe_id)->where('id', $this->evolution)->first();
                        $nom_etape = $etapes->nom_etape; 
                        $date_cloture = date('Y-m-d', strtotime('2 month'));                  

                        $position = 0;
                        $opportu = Opportunite :: create(['client'=>$this->client,'id_client'=>$this->ids_client,'nom_opportunite'=>$this->nom_opportunite,'email_contact'=>$this->email_contact,'nom_societe'=>$this->nom_societe,'adresse_societe'=>$this->adresse_societe,
                        'telephone_contact'=>$this->telephone_contact,'montant_attendu'=>$this->montant_attendu,'etape'=>$nom_etape,'id_etape'=>$this->evolution,'step'=>$this->evolution,'position'=>$position,'priorite'=>$this->priorite,'ville'=>$this->ville,
                        'date_cloture'=>$date_cloture,'pays'=>$this->pays,'langue'=>'Français','vendeur'=>auth()->user()->id,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->email,'user_id'=>auth()->user()->id]);
                        
                        // ceci recupere le dernier enregistrement cree a l'instant
                        $dernier_id = $opportu->id;

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
        $etapes = Etape::where('societe_id',auth()->user()->societe_id)->where('id', $id)->first();
        $this->evolution = $etapes->id;
    }
    public function getTotalParEtape(int $id_etape){ 

        if(auth()->user()->type_user == "Administrateur"){ 

            $query = Opportunite::where('societe_id', auth()->user()->societe_id)->where('id_etape', $id_etape);
            if (!empty($this->query)) {
                $query->where('ville', 'like', '%' . $this->query . '%');
            }
            if (!empty($this->parSec)) {
                $query->where('secteur_activite', 'like', '%' . $this->parSec . '%');
            }
            if (!empty($this->parCap)) {
                $query->where('campagne', 'like', '%' . $this->parCap . '%');
            }
            if (!empty($this->parSource)) {
                $query->where('source', $this->parSource);
            }
            if (!empty($this->parUser)) {
                $query->where('vendeur', $this->parUser);
            }
            $query->whereBetween('created_at', [$this->start, $this->end]);
            return $query->sum('montant_attendu');
            // return Opportunite :: where('societe_id',auth()->user()->societe_id)->where('id_etape', $id_etape)->sum('montant_attendu'); 
        }
        else{

            $query = Opportunite::where('societe_id', auth()->user()->societe_id)->where('vendeur', auth()->user()->id)->where('id_etape', $id_etape);
            if (!empty($this->query)) {
                $query->where('ville', 'like', '%' . $this->query . '%');
            }
            if (!empty($this->parSec)) {
                $query->where('secteur_activite', 'like', '%' . $this->parSec . '%');
            }
            if (!empty($this->parCap)) {
                $query->where('campagne', 'like', '%' . $this->parCap . '%');
            }
            if (!empty($this->parSource)) {
                $query->where('source', $this->parSource);
            }
            if (!empty($this->parUser)) {
                $query->where('vendeur', $this->parUser);
            }
            $query->whereBetween('created_at', [$this->start, $this->end]);
            return $query->sum('montant_attendu');
            // return Opportunite :: where('societe_id',auth()->user()->societe_id)->where('vendeur', auth()->user()->id)->where('id_etape', $id_etape)->sum('montant_attendu'); 
        }            
    }
    public function getTotalParOpportunite(int $id_etape){

        if(auth()->user()->type_user == "Administrateur"){ 
            
            $query = Opportunite::where('societe_id', auth()->user()->societe_id)->where('id_etape', $id_etape);
            if (!empty($this->query)) {
                $query->where('ville', 'like', '%' . $this->query . '%');
            }
            if (!empty($this->parSec)) {
                $query->where('secteur_activite', 'like', '%' . $this->parSec . '%');
            }
            if (!empty($this->parCap)) {
                $query->where('campagne', 'like', '%' . $this->parCap . '%');
            }
            if (!empty($this->parSource)) {
                $query->where('source', $this->parSource);
            }
            if (!empty($this->parUser)) {
                $query->where('vendeur', $this->parUser);
            }
            $query->whereBetween('created_at', [$this->start, $this->end]);
            return $query->count();
            // return Opportunite :: where('societe_id',auth()->user()->societe_id)->where('id_etape', $id_etape)->count();  
        }
        else{

            $query = Opportunite::where('societe_id', auth()->user()->societe_id)->where('vendeur', auth()->user()->id)->where('id_etape', $id_etape);
            if (!empty($this->query)) {
                $query->where('ville', 'like', '%' . $this->query . '%');
            }
            if (!empty($this->parSec)) {
                $query->where('secteur_activite', 'like', '%' . $this->parSec . '%');
            }
            if (!empty($this->parCap)) {
                $query->where('campagne', 'like', '%' . $this->parCap . '%');
            }
            if (!empty($this->parSource)) {
                $query->where('source', $this->parSource);
            }
            if (!empty($this->parUser)) {
                $query->where('vendeur', $this->parUser);
            }
            $query->whereBetween('created_at', [$this->start, $this->end]);
            return $query->count();
            // return Opportunite :: where('societe_id',auth()->user()->societe_id)->where('vendeur', auth()->user()->id)->where('id_etape', $id_etape)->count(); 
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
        $Task =  Opportunite::where('societe_id',auth()->user()->societe_id)->where('id',$taskId)->first();
        $id_step_task = $Task->step;
        $etap =  Etape::where('societe_id',auth()->user()->societe_id)->where('id',$id_step_task)->first();
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

    // Pour glisser et deposer Etape   
    public function moveEtape($etapeId, $newPosition)
    {
        $societeId = auth()->user()->societe_id;

        $etape = Etape::where('id', $etapeId)->where('societe_id', $societeId)->first();

        if (!$etape) {
            return;
        }

        /*
        * Toutes les étapes de la société
        * dans leur ordre actuel.
        */
        $etapes = Etape::where('societe_id', $societeId)->orderBy('position')->orderBy('id')->get();

        /*
        * Retirer l'étape déplacée de la collection
        */
        $etapes = $etapes->reject(function ($item) use ($etapeId) {
                return $item->id == $etapeId;
            })->values();

        /*
        * Sécuriser la nouvelle position
        */
        $newPosition = max(0, min($newPosition,$etapes->count()));

        /*
        * Insérer l'étape à sa nouvelle position
        */
        $etapes->splice($newPosition, 0,[$etape]);

        /*
        * Réécrire TOUTES les positions.
        *
        * Cela évite les doublons :
        *
        * 1
        * 2
        * 2
        * 4
        *
        * et garantit toujours :
        *
        * 1
        * 2
        * 3
        * 4
        */
        foreach ($etapes as $index => $item) {
            Etape::where('id', $item->id)->where('societe_id', $societeId)->update(['position' => $index + 1]);
        }

        /*
        * Recharger le composant Livewire
        */
        $this->etape = Etape::where('societe_id', $societeId)->orderBy('position')->get();
    }
}
