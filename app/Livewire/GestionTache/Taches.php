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
use Mail;
use App\Mail\TacheMail;
use App\Models\Parametre;

class Taches extends Component
{
     protected $paginationTheme = 'bootstrap';
    use WithPagination;

    // pour recherche utilisateur
    public $parNomTier; // Pour la recherche
    public $records;
    public $recordCount;
    public $showdiv = false;

    public $nom_tache;
    public $reference;    
    public $utilisateur;
    public $ids_utilisateur; // id utilisateur selectionne    
    public $telephone_utilisateur;
    public $priorite = 'Faible';
    public $evolution;
    
    public $envoi_mail;    
    
     public function resetinputFields(){
         $this->nom_tache = '';
        $this->utilisateur = '';
        $this->email_contact = '';
        $this->telephone_utilisateur = '';
        $this->evolution = '';
        $this->priorite = 'Faible';
    }
    public function mount(){  
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->consulter_tache;
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
        $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();
        $jourValid = $entite_mod[0]->validite_mod;
        $mod_tache = $entite_mod[0]->mod_tache;
        $soldeClient = $entite_mod[0]->solde;
        $this->activer_fidelite = $entite_mod[0]->activer_fidelite; 
        if($dateJour <= $jourValid){ 
            if($mod_tache == 1){   
                $title = 'Tâches | WamsCo';
                $module = 'Tâches';
                $title_fils = 'Tâches';
                $lien = 'taches?active=14&champ=1-1';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');
                $dateJour = date('Y-m-d');             

                $etape = EtapeTache :: where('societe',auth()->user()->societe)->orderBy('id','asc')->get(); 

                // if(auth()->user()->societe == "Administration" && auth()->user()->type_user == "Administrateur"){ 
                if(auth()->user()->type_user == "Administrateur"){ 
                    $tacher = Tache :: where('societe',auth()->user()->societe)->orderBy('step')->orderBy('position')->get()->groupBy('step');
                }
                else{
                    $tacher = Tache::where('societe',auth()->user()->societe)->where('id_utilisateur', auth()->user()->id)->orderBy('step')->orderBy('position')->get()->groupBy('step');
                }
                $tacherCount = $tacher->count();
                $user = Utilisateur::where('societe',auth()->user()->societe)->orderBy('name','asc')->get();
                
                $config = Parametre::where('societe',auth()->user()->societe)->limit(1)->get();
                $this->envoi_mail = $config[0]->envoi_mail;                
                
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
                return view('livewire.gestion-tache.taches',compact('title_fils','module','lien','dateJour','etape','tacher','tacherCount','user'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
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
        if(!empty($this->utilisateur)){
            if(ctype_alpha($this->utilisateur)){ // ctype_alpha: cette fonction permet de savoir si le caractere ou mot est une lettre  
                $this->records = Utilisateur::where('etat',1)->where('societe',auth()->user()->societe)->where('name','like','%'.$this->utilisateur.'%')->orderBy('name','asc')->limit(8)->get(); 
                $this->recordCount = Utilisateur::where('etat',1)->where('societe',auth()->user()->societe)->where('name','like','%'.$this->utilisateur.'%')->count();
                $this->showdiv = true;
            }
            else{
                $this->records = Utilisateur::where('etat',1)->where('societe',auth()->user()->societe)->where('telephone','like','%'.$this->utilisateur.'%')->orderBy('name','asc')->limit(8)->get(); 
                $this->recordCount = Utilisateur::where('etat',1)->where('societe',auth()->user()->societe)->where('telephone','like','%'.$this->utilisateur.'%')->count(); 
                $this->showdiv = true;
            }        
        }
        else{
            $this->showdiv = false;
        }
    }
    public function ajouterTier($id = 0){
        $record = Utilisateur::where('id', $id)->first();
        $this->utilisateur = $record->name;
        $this->ids_utilisateur = $record->id;
        $this->telephone_utilisateur = $record->telephone;
        $this->showdiv = false;
        $this->nom_tache = 'Tâche de '.$this->utilisateur;        
    }
    public function store(){        
        $this->validate([
            'utilisateur'=>'required|max:255',
            'nom_tache'=>'required|max:255',
            'evolution'=>'required|numeric', // id etape
            'priorite'=>'required|max:255', 
        ]);   
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_tache;
            if($autoriser == 1){ 

                $test_user = Utilisateur ::where('societe',auth()->user()->societe)->where('id',$this->ids_utilisateur)->count();               
                if($test_user > 0){                    
                    $test_etapes = EtapeTache::where('societe',auth()->user()->societe)->where('id', $this->evolution)->count();
                    if($test_etapes > 0){

                        $etapes = EtapeTache::where('societe',auth()->user()->societe)->where('id', $this->evolution)->first();
                        $nom_etape = $etapes->nom_etape; 
                        $date_cloture = date('Y-m-d', strtotime('2 month'));                  

                        $position = 0;
                        $temps_alloue = '00:45';
                        $dates = date('dmy/His');  
                        $token_ok = 'TAC/'.$dates;
                        $reference = $token_ok;
                        Tache :: create(['reference'=>$reference,'utilisateur'=>$this->utilisateur,'id_utilisateur'=>$this->ids_utilisateur,'nom_tache'=>$this->nom_tache,
                        'etape'=>$nom_etape,'id_etape'=>$this->evolution,'step'=>$this->evolution,'position'=>$position,'priorite'=>$this->priorite,'temps_alloue'=>$temps_alloue,
                        'date_cloture'=>$date_cloture,'assignation_id'=>auth()->user()->id,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->email,'user_id'=>auth()->user()->id]);
                        // ceci recupere le dernier enregistrement cree a l'instant
                        $dernier_id = Tache::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id; 
                        $this->dispatch('pipelineStore');
                        $id_activite = $dernier_id;   
                        $page = 'Tache';    
                        LogActivity::addToLog('Tâche » '.$this->nom_tache.' ('.$reference.') créée', $id_activite, $page); 
                        $this->dispatch('alert',                    
                            title:'Tâche » '.$this->nom_tache.' ('.$reference.') créée !',
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
    public function tacher(int $id){
        $etapes = EtapeTache::where('societe',auth()->user()->societe)->where('id', $id)->first();
        $this->evolution = $etapes->id;
    }    
    public function getTotalParTache(int $id_etape){

        if(auth()->user()->type_user == "Administrateur"){ 

            return Tache :: where('societe',auth()->user()->societe)->where('id_etape', $id_etape)->count();  
        }
        else{

            return Tache :: where('societe',auth()->user()->societe)->where('id_utilisateur', auth()->user()->id)->where('id_etape', $id_etape)->count(); 
        }            
    }      
    public function moveTask($taskId, $newEtape, $newPosition){    
        try {
            $task = Tache::find($taskId)->update([ 'step' => $newEtape,'position' => $newPosition,]);    
            // Réordonner dans cette colonne
            Tache::where('step', $newEtape)->orderBy('position')->get()
                ->each(function ($t, $i) {
                    $t->update(['position' => $i]);
                });

            // NB:  ceci enregistrer dans Tache l'etape actuelle
            $Task =  Tache::where('societe',auth()->user()->societe)->where('id',$taskId)->first();
            $id_step_task = $Task->step;   
            $id_utilisateur = $Task->id_utilisateur;   

            $etap =  EtapeTache::where('societe',auth()->user()->societe)->where('id',$id_step_task)->first();
            $nom_etape = $etap->nom_etape;
            
            Tache :: find($taskId)->update(['etape'=>$nom_etape,'id_etape'=>$id_step_task,]);        
            // Fin NB

            // ************* debut envoi email ******************** 
                       
            if($this->envoi_mail == 1){
                $user = Utilisateur::where('id', $id_utilisateur)->get();  
                $email = $user[0]->email;
                $name = $user[0]->name;
                $societe = $user[0]->societe;

                $entite_all = Entite::where('enseigne',$societe)->get();
                $logo = $entite_all[0]->logo;
                
                // recupere les donnees taches mise a jour
                $tache_all = Tache::where('id',$taskId)->first();
                $id_tache = $tache_all->id; 
                $reference = $tache_all->reference; 
                $nom_tache = $tache_all->nom_tache;
                $etape = $tache_all->etape;
                $priorite = $tache_all->priorite;
                $date_cloture = $tache_all->date_cloture;
                $temps_alloue = $tache_all->temps_alloue;
                $created_at = $tache_all->created_at;

                $date = date('d-m-Y H:i:s');           
                $body = [
                    'entite'=>$societe,
                    'date'=>$date,              
                    'email'=>$email,
                    'name'=>$name,
                    'id_tache'=>$id_tache, 
                    'reference'=>$reference, 
                    'nom_tache'=>$nom_tache, 
                    'etape'=>$etape, 
                    'priorite'=>$priorite,                 
                    'date_cloture'=>date('d-m-Y', strtotime($date_cloture)), 
                    'temps_alloue'=>$temps_alloue,
                    'created_at'=>date('d-m-Y H:i:s', strtotime($created_at)),  
                    'lien'=>'http://wamsco-cloud.net/detail_tache?id='.$id_tache.'&active=14&champ=1-1',
                    'url_a'=>'http://wamsco-cloud.net',
                    'logo'=>'https://wamsco-cloud.net/storage/'.$logo,
                ];  
                Mail::to($email)->send(new TacheMail($body)); 
            }            
        }
        catch (\Symfony\Component\Mailer\Exception\TransportExceptionInterface $e) 
        {                       
            flash ('Erreur lors de l\'envoi d\'email : cette adresse ('.$email.') semble invalide ou le domaine n\'existe pas !')->error();
        }  
        // ********** Fin envoi email ************** 

        $id_activite = $taskId;   
        $page = 'Tache';    
        LogActivity::addToLog('Étape tâche modifée en » <strong> '.$nom_etape.' </strong>', $id_activite, $page); 
    }
    public function voirDetail(int $id){
        $this->redirect('/detail_tache?id='.$id.'&active=14&champ=1-1', navigate: true);
    }
}
