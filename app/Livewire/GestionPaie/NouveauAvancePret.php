<?php

namespace App\Livewire\GestionPaie;

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
use App\Models\AvancePret;
use Mail;
use App\Mail\AvanceMail;
use App\Models\Parametre;

class NouveauAvancePret extends Component
{
    protected $paginationTheme = 'bootstrap';    // ceci pour pagination avec les chiffres tres important
    use WithPagination;
    use WithFileUploads;

    // public $parNomTier; // Pour la recherche
    public $records;
    public $recordCount;
    public $showdiv = false;
    public $ids_utilisateur;
    public $utilisateur;
    public $telephone;
    public $etat;

    // public $salarie;
    public $libelle;
    public $montant;
    public $nombre_tranche;
    public $type_pret;
    public $note;
    public $mode_reglement;
    public $date_paiement;
    public $montant_deja_preleve; 
    
    public $envoi_mail;     

    public function mount(){         
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_ticket;
            if($autoriser == 0){
                alert()->error('Oups Désolé', 'Vous n\'êtes pas autorisé à ouvrir cette page !!!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
                $this->redirect('/bienvenue', navigate: true);
            }
        }
        else{
            alert()->error('Oups Désolé', 'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
            $this->redirect('/bienvenue', navigate: true);
        } 
        $this->etat = 1; 
        $this->date_paiement = date('Y-m-t'); // ceci affiche le dernier jour du mois       
        
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
                $title = 'Nouvelle Avance ou Prêt | WamsCo';
                $module = 'Gestion paie';
                $title_fils = 'Nouvelle avance ou prêt';
                $lien = 'liste_avance?active=15&champ=2-1&choix=2';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');      
                $dateJour = date('Y-m-d');
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px'); 
                
                $page = 'AvancePret'; // Pour evenement lie
                $log = LogActivityModel::where('user_societe',auth()->user()->societe)->where('page', $page)->limit(7)->orderBy('id','desc')->get();
                $logCount = $log->count();

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
                $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();          
                $jourValid = $entite_mod[0]->validite_mod; 
                // ceci pour trouver le nombre de jour restant avant expiration
                $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24));
                return view('livewire.gestion-paie.avance_pret.nouveau-avance-pret',compact('title_fils','module','lien','dateJour','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
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
        $this->telephone = $record->telephone;
        $this->showdiv = false;
    }
    public function store(){        
        $this->validate([                      
            'utilisateur'=>'required|max:255',          
            'type_pret'=>'required|max:255',   
            'libelle'=>'required|max:255',
            'montant'=>'required|numeric',          
            'nombre_tranche'=>'required|numeric',          
            'telephone'=>'required|max:255',
            'note'=>'nullable|string|max:15000',
            'mode_reglement'=>'required|max:255',
            'date_paiement'=>'required|max:255',
            'etat'=>'required|numeric',
        ]);                                  
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_ticket;
            if($autoriser == 1){  
                
                if(!empty($this->ids_utilisateur)){
                    try {
                            AvancePret::create(['salarie'=>$this->utilisateur,'id_salarie'=>$this->ids_utilisateur,'type_pret'=>$this->type_pret,'libelle'=>$this->libelle,
                                        'montant'=>$this->montant,'nombre_tranche'=>$this->nombre_tranche,'telephone'=>$this->telephone,'note'=>$this->note,
                                        'mode_reglement'=>$this->mode_reglement,'date_paiement'=>$this->date_paiement,'etat'=>$this->etat,'societe'=>auth()->user()->societe,
                                        'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                                               
                        
                            // ceci recupere le dernier enregistrement cree a l'instant               
                            $dernier_id = AvancePret::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id;                    

                            // ************* debut envoi email ********************
                            if($this->envoi_mail == 1){
                                
                                $user = Utilisateur::where('id', $this->ids_utilisateur)->get();  
                                $email = $user[0]->email;
                                $name = $user[0]->name;
                                $societe = $user[0]->societe;

                                $entite_all = Entite::where('enseigne',auth()->user()->societe)->get();
                                $logo = $entite_all[0]->logo;

                                $devises = DeviseTva::where('societe',auth()->user()->societe)->get();
                                $devise = $devises[0]->devise;
                                
                                $avance_all = AvancePret::where('id',$dernier_id)->first();
                                $id_avance = $avance_all->id; 
                                $type_pret = $avance_all->type_pret; 
                                $libelle = $avance_all->libelle;
                                $montant = $avance_all->montant;
                                $nombre_tranche = $avance_all->nombre_tranche;
                                $mode_reglement = $avance_all->mode_reglement;
                                $montant_deja_preleve = $avance_all->montant_deja_preleve;
                                $date_paiement = $avance_all->date_paiement;
                                $note = $avance_all->note;
                                $created_at = $avance_all->created_at;                            
                                $statut  = 'Crée';                            

                                $date = date('d-m-Y H:i:s');           
                                $body = [
                                    'entite'=>$societe,
                                    'date'=>$date,              
                                    'email'=>$email,
                                    'name'=>$name,
                                    'id_avance'=>$id_avance, 
                                    'type_pret'=>$type_pret, 
                                    'libelle'=>$libelle, 
                                    'montant'=>$montant, 
                                    'nombre_tranche'=>$nombre_tranche, 
                                    'mode_reglement'=>$mode_reglement, 
                                    'montant_deja_preleve'=>$montant_deja_preleve, 
                                    'date_paiement'=>date('d-m-Y', strtotime($date_paiement)),
                                    'note'=>$note, 
                                    'statut'=>$statut, 
                                    'devise'=>$devise, 
                                    'created_at'=>date('d-m-Y H:i:s', strtotime($created_at)),
                                    'lien'=>'http://wamsco-cloud.net/detail_avance?id='.$id_avance.'&active=15&champ=2-1&choix=2',
                                    'url_a'=>'http://wamsco-cloud.net',
                                    'logo'=>'https://wamsco-cloud.net/storage/'.$logo,
                                ];  
                                Mail::to($email)->send(new AvanceMail($body));
                            } 
                            
                    }
                    catch (\Symfony\Component\Mailer\Exception\TransportExceptionInterface $e) 
                    {                       
                        flash ('Erreur lors de l\'envoi d\'email : cette adresse ('.$email.') semble invalide ou le domaine n\'existe pas !')->error();
                    }  
                        // ********** Fin envoi email **************
                    $id_activite = $dernier_id;
                    $page = 'AvancePret'; // Pour evenement lie
                    flash ('Avance ou Prêt » '.$this->type_pret.' ('.$this->libelle.') enregistré!')->success();
                    LogActivity::addToLog('Avance ou Prêt » '.$this->type_pret.' ('.$this->libelle.') crée.', $id_activite, $page);
                    $this->dispatch('alert',                    
                        title:'Avance ou Prêt » '.$this->type_pret.' ('.$this->libelle.') enregistré!',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );      
                    $this->redirect('/detail_avance?id='.$dernier_id.'active=15&champ=2-1&choix=2', navigate: true);
                }
                else{
                    $this->dispatch('alert',                    
                    title:'Désolé, ce nom n\'existe pas! <br> Sélectionnez ou créez un autre',
                        timer:5000,
                        icon:'warning',
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
}
