<?php

namespace App\Livewire\GestionTicket;

use Livewire\Component;
use Livewire\Attributes\Validate; 
use App\Helpers\LogActivity;
use App\Models\LogActivity as LogActivityModel;
use Livewire\WithPagination;
use Livewire\WithFileUploads; 
use App\Models\Role;
use App\Models\Entite;
use App\Models\DeviseTva;
use App\Models\Utilisateur;
use App\Models\Ticket;

class DetailsTicket extends Component
{
    protected $paginationTheme = 'bootstrap';    // ceci pour pagination avec les chiffres tres important
    use WithPagination;
    use WithFileUploads;

    public $ids;
    public $societe;    
    
    public $reference;    
    public $nom_ticket;    
    public $type_demande;
    public $priorite;
    public $description;
    // public $etiquettes;    
    public $assignation;    
    public $nom_user;    
    public $telephone_user;
    public $statut;
    public $progression;
    public $note;
    public $source;

    public $created_at;
    public $updated_at;
    
    public $confirmer;    
    
    // public $adresse_tier;
    // public $date_cloture;
    // public $statut;
    // public $fichier_joint;

    public function mount(){         
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->consulter_ticket;
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
    public function render(){
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();
        $jourValid = $entite_mod[0]->validite_mod;
        $mod_ticket = $entite_mod[0]->mod_ticket;
        $soldeClient = $entite_mod[0]->solde; 
        if($dateJour <= $jourValid){ 
            if($mod_ticket == 1){           
                $title = 'Détails Ticket | WamsCo';
                $module = 'Gestion ticket';
                $title_fils = 'Détails ticket';
                $lien = 'liste_ticket?active=13&champ=1-2';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');      
                $dateJour = date('Y-m-d');
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');            
                
                $id = request('id'); // id 
                if(auth()->user()->societe == "Administration"){
                    $test_ticket = Ticket::where('id',$id)->count();
                }
                else{
                    $test_ticket = Ticket::where('societe',auth()->user()->societe)->where('id',$id)->count();
                } 

                if($test_ticket > 0){
                    if(auth()->user()->societe == "Administration"){
                        $tickets = Ticket::where('id',$id)->first(); 
                    }
                    else{
                        $tickets = Ticket::where('societe',auth()->user()->societe)->where('id',$id)->first(); 
                    }         
                    $this->ids = $tickets->id;
                    $this->reference = $tickets->reference;
                    $this->nom_ticket = $tickets->nom_ticket;
                    $this->type_demande = $tickets->type_demande; 
                    $this->priorite = $tickets->priorite;                
                    $this->description = $tickets->description;
                    $this->assignation = $tickets->assignation_id; 
                    $this->nom_user = $tickets->nom_user; 
                    $this->telephone_user = $tickets->telephone_user;
                    $this->statut = $tickets->statut;                
                    $this->note = $tickets->note;             
                    $this->progression = $tickets->progression;  
                    $this->source = $tickets->source;                           
                    $this->societe = $tickets->societe; 
                    
                    $this->nom_user = $tickets->nom_user;
                    $this->created_at = $tickets->created_at;
                    $this->updated_at = $tickets->updated_at;
                }     

                // $listUser = Utilisateur::where('societe',auth()->user()->societe)->where('etat',1)->orderBy('name','asc')->get(); 
                if(auth()->user()->societe == "Administration"){
                    $listUser = Utilisateur::where('societe',auth()->user()->societe)->where('etat',1)->orderBy('name','asc')->get();
                }
                else{
                    $listUser = Utilisateur::where('email','support@wamsco-cloud.net')->where('etat',1)->orderBy('name','asc')->get(); 
                } 
                $liste_entite = Entite::get();

                $page = 'Tickets'; // Pour evenement lie
                $log = LogActivityModel::where('user_societe',auth()->user()->societe)->where('id_activite', $this->ids)->where('page', $page)->limit(50)->orderBy('id','desc')->get();
                $logCount = $log->count();
            
                $deviseTva = DeviseTva :: where('societe',auth()->user()->societe)->limit(1)->orderBy('id','asc')->count(); 
                if($deviseTva == 0){
                    $this->devise = 'FCFA';
                }
                else{
                    $deviseTva = DeviseTva :: where('societe',auth()->user()->societe)->limit(1)->orderBy('id','asc')->get(); 
                    $this->devise = $deviseTva[0]->devise;
                }            
                $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();          
                $jourValid = $entite_mod[0]->validite_mod; 
                // ceci pour trouver le nombre de jour restant avant expiration
                $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24));
                return view('livewire.gestion-ticket.details-ticket',compact('title_fils','module','lien','dateJour','listUser','liste_entite','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
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
    public function update(){
        if(auth()->user()->societe == 'Administration'){
            $this->validate([                      
                'reference'=>'required|max:255',          
                'nom_ticket'=>'required|max:255',          
                'type_demande'=>'required|max:255',          
                'priorite'=>'required|max:255',          
                'description'=>'nullable|max:15000',          
                'assignation'=>'required|numeric',   
                'telephone_user'=>'required|max:255',
                'statut'=>'required|max:255',  
                'progression'=>'required|numeric|min:0|max:100',  
                'note'=>'nullable|max:15000',  
                'source'=>'required|max:255',              
                'societe'=>'required|max:255',              
            ]); 
        }
        else{ 
            $this->validate([                      
                'reference'=>'required|max:255',          
                'nom_ticket'=>'required|max:255',          
                'type_demande'=>'required|max:255',          
                'priorite'=>'nullable|max:255',          
                'description'=>'nullable|max:15000',          
                'assignation'=>'required|numeric',   
                'telephone_user'=>'required|max:255',
                'statut'=>'required|max:255',  
                'progression'=>'required|numeric|min:0|max:100',  
                'note'=>'nullable|max:15000',  
                'source'=>'nullable|max:255',              
            ]); 
        }       
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_ticket;
            if($autoriser == 1){         
                if(auth()->user()->societe == "Administration"){

                    $TestUser = Utilisateur::where('societe',auth()->user()->societe)->where('id',$this->assignation)->where('etat',1)->orderBy('name','asc')->count(); 
                    if($TestUser > 0){
                        $User = Utilisateur::where('societe',auth()->user()->societe)->where('id',$this->assignation)->where('etat',1)->orderBy('name','asc')->get();            
                        $name = $User[0]->name;
                        Ticket::find($this->ids)->update(['nom_ticket'=>$this->nom_ticket,'type_demande'=>$this->type_demande,'priorite'=>$this->priorite,'description'=>$this->description,
                        'assignation'=>$name,'assignation_id'=>$this->assignation,'telephone_user'=>$this->telephone_user,'statut'=>$this->statut,'progression'=>$this->progression,'note'=>$this->note,
                        'source'=>$this->source,'societe'=>$this->societe,]);

                        $id_activite = $this->ids;
                        $page = 'Tickets';
                        LogActivity::addToLog('Ticket » '.$this->reference.' modifié', $id_activite, $page);
                        $this->dispatch('alert',                    
                            title:'Enregistrement modifié avec succès!',
                            timer:5000,
                            icon:'success',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        );  
                        // $this->redirect('/detail_ticket?id='.$this->ids.'&active=13&champ=1-2', navigate: true);
                    }
                    else{
                       $this->dispatch('alert',                    
                            title:'Désolé, le compte du support assigné n\'exite plus!',
                            timer:5000,
                            icon:'error',
                            toast:true,
                            showConfirmButton: false,
                            position:'center',
                        );  
                    }                       
                }
                else{                    
                    Ticket::find($this->ids)->update(['nom_ticket'=>$this->nom_ticket,'type_demande'=>$this->type_demande,'description'=>$this->description,
                    'telephone_user'=>$this->telephone_user,]);
                    
                    $id_activite = $this->ids;
                    $page = 'Tickets';
                    LogActivity::addToLog('Ticket » '.$this->reference.' modifié', $id_activite, $page);
                    $this->dispatch('alert',                    
                        title:'Enregistrement modifié avec succès!',
                        timer:5000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );  
                    // $this->redirect('/detail_ticket?id='.$this->ids.'&active=13&champ=1-2', navigate: true);
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
    public function precedant(int $id){ 
        if(auth()->user()->societe == 'Administration'){
            $testPrecedant = Ticket::where('id','<',$id)->orderBy('id','desc')->count();
        }
        else{
            $testPrecedant = Ticket::where('societe',auth()->user()->societe)->where('id','<',$id)->orderBy('id','desc')->count();
        }

        if($testPrecedant > 0){ 
            if(auth()->user()->societe == 'Administration'){
                $precedant = Ticket::where('id','<',$id)->orderBy('id','desc')->first(); 
            }
            else{
                $precedant = Ticket::where('societe',auth()->user()->societe)->where('id','<',$id)->orderBy('id','desc')->first();
            }
            $previous = $precedant->id; 
            $this->redirect('/detail_ticket?id='.$previous.'&active=13&champ=1-2', navigate: true);              
        }  
        else{            
            $this->dispatch('alert',                    
                title:'Désolé, Fin enregistrements',
                timer:3000,
                icon:'warning',
                toast:true,
                showConfirmButton: false,
                position:'top-end',
            ); 
            $this->redirect('/detail_ticket?id='.$id.'&active=13&champ=1-2', navigate: true);  // ceci evite une erreur
        }    
    }    
    public function suivant(int $id){    
        if(auth()->user()->societe == 'Administration'){
            $testSuivant = Ticket::where('id','>',$id)->orderBy('id','asc')->count();
        }
        else{
            $testSuivant = Ticket::where('societe',auth()->user()->societe)->where('id','>',$id)->orderBy('id','desc')->count();
        }
        
        if($testSuivant > 0){
            if(auth()->user()->societe == 'Administration'){
                $suivant = Ticket::where('id','>',$id)->orderBy('id','asc')->first();
            }
            else{
                $suivant = Ticket::where('societe',auth()->user()->societe)->where('id','>',$id)->orderBy('id','desc')->first();
            }            
            $next = $suivant->id;             
            $this->redirect('/detail_ticket?id='.$next.'&active=13&champ=1-2', navigate: true);                     
        }  
        else{
            $this->dispatch('alert',                    
                title:'Désolé, Fin enregistrements',
                timer:3000,
                icon:'warning',
                toast:true,
                showConfirmButton: false,
                position:'top-end',
            ); 
            $this->redirect('/detail_ticket?id='.$id.'&active=13&champ=1-2', navigate: true); // ceci evite une erreur
        } 
    } 
    public function confirmerDelete($id){ 
        $this->confirmer = $id;      
    } 
    public function supprimer($id){ 
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->supprimer_ticket;
            if($autoriser == 1){  
                if($this->ids){                     
                    $page = 'Tickets'; // Pour evenement lie
                    Ticket::where('id',$this->ids)->delete();
                    LogActivityModel::where('id_activite',$this->ids)->where('page',$page)->delete();
                    $id_activite = $this->ids;
                    LogActivity::addToLog('Ticket » '.$this->nom_ticket.' supprimé', $id_activite, $page);
                        $this->dispatch('alert',                    
                        title:'Suppression effectuée!',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    ); 
                    flash ('Ticket » <strong>'.$this->nom_ticket.'</strong> supprimé')->success();
                    $this->redirect('/liste_ticket?active=13&champ=1-2', navigate: true);                    
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
