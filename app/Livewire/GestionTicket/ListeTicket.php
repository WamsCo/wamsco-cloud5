<?php

namespace App\Livewire\GestionTicket;

use Livewire\Component;
use Livewire\Attributes\Validate; 
Use Carbon\Carbon;
use App\Helpers\LogActivity;
use App\Models\LogActivity as LogActivityModel;
use Livewire\WithPagination;
use Livewire\WithFileUploads; 
use App\Models\Role;
use App\Models\Entite;
use App\Models\Utilisateur;
use App\Models\DeviseTva;
use App\Models\Ticket;

class ListeTicket extends Component
{
    protected $paginationTheme = 'bootstrap';    // ceci pour pagination avec les chiffres tres important
    use WithPagination;
    use WithFileUploads;

    public $confirmer;
    public $query;
    public $parPage = 20;
    public $parAuteur;   
    public $parSociete;   
    public $parRef;

    public $orderField = 'id'; 
    public $orderDirection = 'DESC'; 
    public $recherchePar = 'nom';  // (Recherche par: nom , reference)
    public $filtre; 
    public $cherche;

    public $reference;
    public $nom_ticket;
    public $type_demande;
    public $priorite;
    public $description;
    // public $etiquettes;
    public $assignation;
    public $nom_user;
    public $telephone_user;
    public $societe;    
    
    public $date_debut; 
    public $date_fin; 

    public function updatingQuery(){
        $this->resetPage();
    }
    public function setOrderField(string $name){
        if($name === $this->orderField){
            $this->orderDirection = $this->orderDirection === 'ASC' ? 'DESC' : 'ASC';
        }
        else{
            $this->orderField = $name;
            $this->reset('orderDirection');
        }
    }
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
        // $this->date_debut = date('Y-m-d', strtotime('-1 month'));  // ceci pour affiche toutes les sessions en permanance sur 1 mois par defaut
        $this->date_debut = date('Y-m-d', strtotime('-1 year'));  // ceci pour affiche toutes les sessions en permanance sur 1 mois par defaut
        $this->date_fin = date('Y-m-d'); 
        $dates = date('dmy/His');
        //  $length = 2;
        //  $token = bin2hex(random_bytes($length));
        $token_ok = 'TK/'.$dates;
        $this->reference = $token_ok;
        $this->nom_user = auth()->user()->name;
        $this->telephone_user = auth()->user()->telephone;
    }
    public function render()
    {
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();
        $jourValid = $entite_mod[0]->validite_mod;
        $mod_ticket = $entite_mod[0]->mod_ticket;
        $soldeClient = $entite_mod[0]->solde; 
        if($dateJour <= $jourValid){ 
            if($mod_ticket == 1){             
                $title = 'Listing Tickets | WamsCo';
                $module = 'Gestion ticket';
                $title_fils = 'Tickets';
                $lien = 'liste_ticket?active=13&champ=1-2';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');      
                $dateJour = date('Y-m-d');
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px'); 
                
                $start = Carbon::parse($this->date_debut)->startOfDay(); //2016-09-29 00:00:00.000000
                $end = Carbon::parse($this->date_fin)->endOfDay();     // 2016-09-29 23:59:59.000000     
                
                if(auth()->user()->societe == "Administration"){
                    
                    if(empty($this->parAuteur) && empty($this->parSociete)){
                        $ticket = Ticket::where('nom_ticket','like','%'.$this->query.'%')->where('reference','like','%'.$this->parRef.'%')->whereBetween('created_at',[$start, $end])->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage);
                    }
                    elseif(!empty($this->parAuteur) && empty($this->parSociete)){
                        $ticket = Ticket::where('nom_ticket','like','%'.$this->query.'%')->where('reference','like','%'.$this->parRef.'%')->where('user_id',$this->parAuteur)->whereBetween('created_at',[$start, $end])->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage);
                    }
                    elseif(empty($this->parAuteur) && !empty($this->parSociete)){
                        $ticket = Ticket::where('nom_ticket','like','%'.$this->query.'%')->where('reference','like','%'.$this->parRef.'%')->where('societe',$this->parSociete)->whereBetween('created_at',[$start, $end])->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage);
                    }
                    else{ 
                        $ticket = Ticket::where('nom_ticket','like','%'.$this->query.'%')->where('reference','like','%'.$this->parRef.'%')->where('user_id',$this->parAuteur)->where('societe',$this->parSociete)->whereBetween('created_at',[$start, $end])->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage);
                    }
                }
                else{                

                    if(empty($this->parAuteur) && empty($this->parSociete)){
                        $ticket = Ticket::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->where('nom_ticket','like','%'.$this->query.'%')->where('reference','like','%'.$this->parRef.'%')->whereBetween('created_at',[$start, $end])->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage);
                    }
                    elseif(!empty($this->parAuteur) && empty($this->parSociete)){
                        $ticket = Ticket::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->where('nom_ticket','like','%'.$this->query.'%')->where('reference','like','%'.$this->parRef.'%')->where('user_id',$this->parAuteur)->whereBetween('created_at',[$start, $end])->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage);
                    }
                    elseif(empty($this->parAuteur) && !empty($this->parSociete)){
                        $ticket = Ticket::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->where('nom_ticket','like','%'.$this->query.'%')->where('reference','like','%'.$this->parRef.'%')->where('societe',$this->parSociete)->whereBetween('created_at',[$start, $end])->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage);
                    }
                    else{ 
                        $ticket = Ticket::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->where('nom_ticket','like','%'.$this->query.'%')->where('reference','like','%'.$this->parRef.'%')->where('user_id',$this->parAuteur)->where('societe',$this->parSociete)->whereBetween('created_at',[$start, $end])->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage);
                    }
                }  
                $ticketCount = $ticket->count();

                $liste_user = Utilisateur::orderBy('name','asc')->get();
                $liste_entite = Entite::orderBy('enseigne','asc')->get();

                if(auth()->user()->societe == "Administration"){
                    $listUser = Utilisateur::where('societe',auth()->user()->societe)->where('etat',1)->orderBy('name','asc')->get();
                    
                    // PKI      
                    $resultat = Ticket::get();  
                    $nbreTotalResolu = $resultat->where('statut','Résolu')->count();
                    $nbreTotalTicket = $resultat->count();  
                }
                else{
                    $listUser = Utilisateur::where('email','support@wamsco-cloud.net')->where('etat',1)->orderBy('name','asc')->get(); 
                    
                    // PKI      
                    $resultat = Ticket::where('societe',auth()->user()->societe)->get();  
                    $nbreTotalResolu = $resultat->where('statut','Résolu')->count();
                    $nbreTotalTicket = $resultat->count();  
                }                        

                $derniereActivite = Ticket::where('societe',auth()->user()->societe)->latest('updated_at')->first();
                               
                $page = 'Tickets'; // Pour evenement lie
                $log = LogActivityModel::where('user_societe',auth()->user()->societe)->where('page', $page)->limit(50)->orderBy('id','desc')->get();
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
                return view('livewire.gestion-ticket.liste-ticket',compact('title_fils','module','lien','dateJour','ticket','ticketCount','liste_entite','liste_user','listUser','nbreTotalResolu','nbreTotalTicket','derniereActivite','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
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
        if(auth()->user()->societe == 'Administration'){
            $this->validate([                      
                'reference'=>'required|max:255',          
                'nom_ticket'=>'required|max:255',          
                'type_demande'=>'required|max:255',          
                'priorite'=>'required|max:255',          
                'description'=>'nullable|max:15000',          
                'assignation'=>'required|numeric',   
                'telephone_user'=>'required|max:255',
                'societe'=>'required|max:255',
            ]); 
        }
        else{
            $this->validate([                      
                'reference'=>'required|max:255',          
                'nom_ticket'=>'required|max:255',          
                'type_demande'=>'required|max:255',          
                'priorite'=>'required|max:255',          
                'description'=>'nullable|max:15000',          
                'assignation'=>'required|numeric',   
                'telephone_user'=>'required|max:255',
                'societe'=>'nullable|max:255',
            ]); 
        }                            
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_ticket;
            if($autoriser == 1){   
                
                $statut = 'Nouveau';
                $progression = 0; // 0%
               
                $users = Utilisateur::where('id',$this->assignation)->where('etat',1)->orderBy('name','asc')->first();            
                $name = $users->name;
                if(auth()->user()->societe == 'Administration'){
                    Ticket::create(['nom_ticket'=>$this->nom_ticket,'reference'=>$this->reference,'type_demande'=>$this->type_demande,'priorite'=>$this->priorite,'description'=>$this->description,
                    'assignation'=>$name,'assignation_id'=>$this->assignation,'telephone_user'=>$this->telephone_user,'statut'=>$statut,'progression'=>$progression,                
                    'societe'=>$this->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                }
                else{
                     Ticket::create(['nom_ticket'=>$this->nom_ticket,'reference'=>$this->reference,'type_demande'=>$this->type_demande,'priorite'=>$this->priorite,'description'=>$this->description,
                    'assignation'=>$name,'assignation_id'=>$this->assignation,'telephone_user'=>$this->telephone_user,'statut'=>$statut,'progression'=>$progression,               
                    'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                }                                    
                
                // ceci recupere le dernier enregistrement cree a l'instant
                if(auth()->user()->societe == 'Administration'){
                    $dernier_id = Ticket::where('societe',$this->societe)->where('user_id',auth()->user()->id)->latest()->first()->id; 
                }
                else{
                    $dernier_id = Ticket::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id;
                }
                $id_activite = $dernier_id;
                $page = 'Tickets'; // Pour evenement lie
                LogActivity::addToLog('Ticket » '.$this->reference.' ('.$this->nom_ticket.') crée', $id_activite, $page);
                $this->dispatch('alert',                    
                    title:'Ticket ('.$this->nom_ticket.') enregistré!',
                    timer:3000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                );  
                flash ('Ticket » <strong>'.$this->reference.' ('.$this->nom_ticket.')</strong> crée')->success();    
                $this->redirect('/detail_ticket?id='.$dernier_id.'&active=4&champ=3-1&choix=5', navigate: true);   
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
            $autoriser = $role[0]->supprimer_ticket;
            if($autoriser == 1){  
                if($id){                     
                    $page = 'Tickets'; // Pour evenement lie
                    Ticket::where('id',$id)->delete();
                    LogActivityModel::where('id_activite',$id)->where('page',$page)->delete();
                    $id_activite = $id;
                    LogActivity::addToLog('Ticket supprimé', $id_activite, $page);
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
