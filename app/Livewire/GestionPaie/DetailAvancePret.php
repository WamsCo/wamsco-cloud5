<?php

namespace App\Livewire\GestionPaie;

use Livewire\Component;
use Livewire\Attributes\Validate; 
Use Carbon\Carbon;
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

class DetailAvancePret extends Component
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

    public $ids;
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
    public $confirmer;

    public function mount(){         
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
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
        $entite_mod = Entite::where('id',auth()->user()->societe_id)->get();
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
               
                $id = request('id'); // id 
                $test_avance = AvancePret::where('societe_id',auth()->user()->societe_id)->where('id',$id)->count();
                if($test_avance > 0){
                    
                    $avances = AvancePret::where('societe_id',auth()->user()->societe_id)->where('id',$id)->first();                           
                    $this->ids = $avances->id;
                    $this->ids_utilisateur = $avances->id_salarie;
                    $this->utilisateur = $avances->salarie;
                    $this->type_pret = $avances->type_pret;                
                    $this->libelle = $avances->libelle; 
                    $this->montant = $avances->montant;
                    $this->nombre_tranche = $avances->nombre_tranche; 
                    $this->telephone = $avances->telephone; 
                    $this->telephone_user = $avances->telephone_user;
                    $this->mode_reglement = $avances->mode_reglement;                
                    $this->date_paiement = $avances->date_paiement;  
                    $this->etat = $avances->etat;                           
                    $this->note = $avances->note;                           
                } 

                $config = Parametre::where('societe_id',auth()->user()->societe_id)->limit(1)->get();
                $this->envoi_mail = $config[0]->envoi_mail;

                $page = 'AvancePret'; // Pour evenement lie
                $log = LogActivityModel::where('societe_id',auth()->user()->societe_id)->where('id_activite', $this->ids)->where('page', $page)->limit(11)->orderBy('id','desc')->get();
                $logCount = $log->count();
            
                $deviseTva = DeviseTva :: where('societe_id',auth()->user()->societe_id)->limit(1)->orderBy('id','asc')->count(); 
                if($deviseTva == 0){
                    $this->devise = 'FCFA';
                }
                else{
                    $deviseTva = DeviseTva :: where('societe_id',auth()->user()->societe_id)->limit(1)->orderBy('id','asc')->get(); 
                    $this->devise = $deviseTva[0]->devise;
                }            
                $entite_mod = Entite::where('id',auth()->user()->societe_id)->get();          
                $jourValid = $entite_mod[0]->validite_mod; 
                // ceci pour trouver le nombre de jour restant avant expiration
                $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24));
                return view('livewire.gestion-paie.avance_pret.detail-avance-pret',compact('title_fils','module','lien','dateJour','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
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
                $this->records = Utilisateur::where('etat',1)->where('societe_id',auth()->user()->societe_id)->where('name','like','%'.$this->utilisateur.'%')->orderBy('name','asc')->limit(8)->get(); 
                $this->recordCount = Utilisateur::where('etat',1)->where('societe_id',auth()->user()->societe_id)->where('name','like','%'.$this->utilisateur.'%')->count();
                $this->showdiv = true;
            }
            else{
                $this->records = Utilisateur::where('etat',1)->where('societe_id',auth()->user()->societe_id)->where('telephone','like','%'.$this->utilisateur.'%')->orderBy('name','asc')->limit(8)->get(); 
                $this->recordCount = Utilisateur::where('etat',1)->where('societe_id',auth()->user()->societe_id)->where('telephone','like','%'.$this->utilisateur.'%')->count(); 
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
    public function precedant(int $id){ 
        $testPrecedant = AvancePret::where('societe_id',auth()->user()->societe_id)->where('id','<',$id)->orderBy('id','desc')->count();
        if($testPrecedant > 0){ 
            $precedant = AvancePret::where('societe_id',auth()->user()->societe_id)->where('id','<',$id)->orderBy('id','desc')->first();        
            $previous = $precedant->id; 
            $this->redirect('/detail_avance?id='.$previous.'&active=15&champ=2-1&choix=2', navigate: true);              
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
            $this->redirect('/detail_avance?id='.$id.'&active=15&champ=2-1&choix=2', navigate: true);  // ceci evite une erreur
        }    
    }    
    public function suivant(int $id){    
        
        $testSuivant = AvancePret::where('societe_id',auth()->user()->societe_id)->where('id','>',$id)->orderBy('id','asc')->count();
        if($testSuivant > 0){
            $suivant = AvancePret::where('societe_id',auth()->user()->societe_id)->where('id','>',$id)->orderBy('id','asc')->first();
            $next = $suivant->id;             
            $this->redirect('/detail_avance?id='.$next.'&active=15&champ=2-1&choix=2', navigate: true);                     
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
            $this->redirect('/detail_avance?id='.$id.'&active=15&champ=2-1&choix=2', navigate: true); // ceci evite une erreur
        } 
    }
    public function confirmerDelete($id){ 
        $this->confirmer = $id;      
    } 
    public function supprimer($id){ 
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->supprimer_ticket;
            if($autoriser == 1){  
                if($this->ids){                     
                    $page = 'AvancePret'; // Pour evenement lie
                    AvancePret::where('id',$this->ids)->delete();
                    LogActivityModel::where('id_activite',$this->ids)->where('page',$page)->delete();
                    $id_activite = $this->ids;
                    LogActivity::addToLog('Avance ou Prêt supprimé', $id_activite, $page);
                        $this->dispatch('alert',                    
                        title:'Suppression effectuée!',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    ); 
                    $this->redirect('/liste_avance?active=15&champ=2-1&choix=2', navigate: true);                    
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
    public function update(){      
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
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_tache;
            if($autoriser == 1){ 
                
                $test_user = Utilisateur ::where('societe_id',auth()->user()->societe_id)->where('id',$this->ids_utilisateur)->count();
                if($test_user > 0){                    

                    $test_avances = AvancePret::where('societe_id',auth()->user()->societe_id)->where('id', $this->ids)->count();
                    if($test_avances > 0){

                        try {                                                         
                            
                            AvancePret::find($this->ids)->update(['salarie'=>$this->utilisateur,'id_salarie'=>$this->ids_utilisateur,'type_pret'=>$this->type_pret,
                                        'libelle'=>$this->libelle,'montant'=>$this->montant,'nombre_tranche'=>$this->nombre_tranche,'telephone'=>$this->telephone,'note'=>$this->note,
                                        'mode_reglement'=>$this->mode_reglement,'date_paiement'=>$this->date_paiement,'etat'=>$this->etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);  

                            // ************* debut envoi email ********************
                            if($this->envoi_mail == 1){
                                
                                $user = Utilisateur::where('id', $this->ids_utilisateur)->get();  
                                $email = $user[0]->email;
                                $name = $user[0]->name;
                                $societe = $user[0]->societe;
                                $societe_id = $user[0]->societe_id;

                                $entite_all = Entite::where('id',auth()->user()->societe_id)->get();
                                $logo = $entite_all[0]->logo;

                                $devises = DeviseTva::where('societe_id',auth()->user()->societe_id)->get();
                                $devise = $devises[0]->devise;
                                
                                $avance_all = AvancePret::where('id',$this->ids)->first();
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
                                $statut  = 'Modifiée';

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
                        $id_activite = $this->ids;   
                        $page = 'AvancePret';
                        LogActivity::addToLog('Avance ou Prêt » '.$this->type_pret.' ('.$this->libelle.') modifiée.', $id_activite, $page);
                        $this->dispatch('alert',                    
                            title:'Avance ou Prêt » '.$this->type_pret.' ('.$this->libelle.') modifiée!',
                            timer:5000,
                            icon:'success',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        ); 

                    }else{
                        $this->dispatch('alert',                    
                            title:'Désolé, cette avance n\'existe plus ou  pas! <br> Sélectionnez ou créez une autre',
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
}
