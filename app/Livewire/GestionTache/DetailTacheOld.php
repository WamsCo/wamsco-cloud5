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
use App\Models\ProformaClientEntete;
use App\Models\factureClientEntete;
use App\Models\SousTache;
use App\Mail\TacheMail;
use Mail;


class DetailTache extends Component
{
    protected $paginationTheme = 'bootstrap';
    use WithPagination;

    public $ids;
     // pour recherche client
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
    
    public $client;
    // public $ids_client; // id client selectionne  
    public $telephone_client;
    public $date_cloture; 
    public $description; 
    public $assignation_id;     
    public $temps_alloue; 
    public $date_creation; 
    
    public $nom_sous_tache;    
    
    public $confirmer;
    public $approuver;
    public $ouvre = 0;

    public $orderField = 'id'; 
    public $orderDirection = 'ASC'; 

    public function onDataAjout(){
        $this->reset('ouvre');
    }     
    public function ajoutLigne(int $idd){
        $this->ouvre = $idd;
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
            $autoriser = $role[0]->detail_tache;
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
                $title = 'Détail tâche | WamsCo';
                $module = 'Tâche';
                $title_fils = 'Tâche';
                $lien = 'taches?active=14&champ=1-1';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');
                $dateJour = date('Y-m-d'); 
                
                $id = request('id'); // id Pipeline 
                // ceci au chargement de la page
                $test_tache = Tache::where('societe',auth()->user()->societe)->where('id',$id)->count();    
                if($test_tache > 0){
                    $tacher = Tache::where('societe',auth()->user()->societe)->where('id',$id)->first();               
                    $this->ids = $tacher->id;
                    $this->utilisateur = $tacher->utilisateur; 
                    $this->ids_utilisateur = $tacher->id_utilisateur; 
                    // $this->ids_client = $tacher->id_client; 
                    $this->client = $tacher->id_client;  // id client / tier        
                    $this->reference = $tacher->reference;
                    $this->nom_tache = $tacher->nom_tache;
                    $this->telephone_client = $tacher->telephone_client;
                    $this->priorite = $tacher->priorite;
                    $this->evolution = $tacher->id_etape;
                    $this->assignation_id = $tacher->assignation_id;               
                    $this->date_cloture = $tacher->date_cloture;                
                    $this->description = $tacher->description; 
                    $this->temps_alloue = $tacher->temps_alloue; 
                    $this->date_creation = $tacher->created_at;                                    
                }                
                
                $etape = EtapeTache :: where('societe',auth()->user()->societe)->orderBy('id','asc')->get(); 
                $tier = Tier::where('societe',auth()->user()->societe)->orderBy('nom','asc')->get();  
                $user = Utilisateur::where('societe',auth()->user()->societe)->orderBy('name','asc')->get();  
                
                $verifie = Tier::where('societe',auth()->user()->societe)->where('id',$this->client)->count();  
                if($verifie > 0){
                    $tiers = Tier::where('societe',auth()->user()->societe)->where('id',$this->client)->first();
                    $this->telephone_client = $tiers->telephone;
                }
                else{
                    $this->telephone_client = '';
                }

                $sous_tache = SousTache::where('societe',auth()->user()->societe)->where('id_tache_entete',$this->ids)->orderBy($this->orderField, $this->orderDirection)->get();
                $sous_tacheCount = $sous_tache->count();

                $page = 'Tache';
                $log = LogActivityModel::where('user_societe',auth()->user()->societe)->where('id_activite', $this->ids)->where('page', $page)->limit(7)->orderBy('id','desc')->get();
                $logCount = $log->count();

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
                    return view('livewire.gestion-tache.detail-tache',compact('title_fils','module','lien','dateJour','etape','tier','user','sous_tache','sous_tacheCount','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));                  
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
        $this->telephone_utilisateur = $record->telephone;
        $this->showdiv = false;
    }
    public function update(){        
        $this->validate([
            'utilisateur'=>'required|max:255',
            'nom_tache'=>'required|max:255',
            'client'=>'nullable|numeric',
            'telephone_client'=>'nullable|max:255',
            'evolution'=>'required|numeric', // id etape
            'priorite'=>'required|max:255', 
            'date_cloture'=>'required|date|after:today', 
            'temps_alloue'=>'required|date_format:H:i', 
            'description' => 'nullable|string|max:15000', 
        ]);   
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_tache;
            if($autoriser == 1){ 
                
                $test_user = Utilisateur ::where('societe',auth()->user()->societe)->where('id',$this->ids_utilisateur)->count();
                if($test_user > 0){                    

                    $test_etapes = EtapeTache::where('societe',auth()->user()->societe)->where('id', $this->evolution)->count();
                    if($test_etapes > 0){

                        try {
                            $etapes = EtapeTache::where('societe',auth()->user()->societe)->where('id', $this->evolution)->first();
                            $nom_etape = $etapes->nom_etape; 

                            $verifie = Tier::where('societe',auth()->user()->societe)->where('id',$this->client)->count();  
                            if($verifie > 0){
                                $tiers = Tier::where('societe',auth()->user()->societe)->where('id',$this->client)->first();
                                $nom_client = $tiers->nom;
                                $telephone_client = $tiers->telephone;
                            }
                            else{
                                $nom_client = '';
                                $telephone_client = '';

                            }
                            $position = 0;
                            Tache :: find($this->ids)->update(['utilisateur'=>$this->utilisateur,'id_utilisateur'=>$this->ids_utilisateur,'client'=>$nom_client,'id_client'=>$this->client,
                            'nom_tache'=>$this->nom_tache,'telephone_client'=>$telephone_client,'etape'=>$nom_etape,'id_etape'=>$this->evolution,'step'=>$this->evolution,'position'=>$position,
                            'priorite'=>$this->priorite,'date_cloture'=>$this->date_cloture,'temps_alloue'=>$this->temps_alloue,'description'=>$this->description,]);  
                            
                            // ************* debut envoi email ********************
                              
                            $user = Utilisateur::where('id', $this->ids_utilisateur)->get();  
                            $email = $user[0]->email;
                            $name = $user[0]->name;
                            $societe = $user[0]->societe;

                            $entite_all = Entite::where('enseigne',$societe)->get();
                            $logo = $entite_all[0]->logo;
                            
                            $tache_all = Tache::where('id',$this->ids)->first();
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
                        catch (\Symfony\Component\Mailer\Exception\TransportExceptionInterface $e) 
                        {                       
                            flash ('Erreur lors de l\'envoi d\'email : cette adresse ('.$email.') semble invalide ou le domaine n\'existe pas !')->error();
                        }  
                        // ********** Fin envoi email **************                       

                        $id_activite = $this->ids;   
                        $page = 'Tache';
                        LogActivity::addToLog('Tâche » <strong> '.$this->nom_tache.' ('.$this->reference.') </strong> modifiée', $id_activite, $page); 
                        $this->dispatch('alert',                    
                            title:'Tâche » '.$this->nom_tache.' ('.$this->reference.') modifiée!',
                            timer:15000,
                            icon:'success',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        ); 

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
    public function confirmerDelete($id){    

        $this->confirmer = $id;      
    } 
    public function supprimer(int $id){ 

        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->supprimer_tache;
            if($autoriser == 1){   
                if($id){  
                                 
                    Tache::where('id',$id)->delete();                    
                    $id_activite = $id;
                    $page = 'Tache';
                    LogActivity::addToLog('Tâche supprimée', $id_activite, $page);
                    $this->dispatch('alert',                    
                        title:'Suppression effectuée!',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );
                    $this->redirect('/taches?active=14&champ=1-1', navigate: false);   
                }
            } 
            else{                 
                $this->dispatch('alert',                    
                    title:'Vous n\'êtes pas autorisé à effectuer cette opération !',
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
    public function precedant(int $id){ 

        if(auth()->user()->type_user == "Administrateur"){

            $testPrecedant = Tache::where('societe',auth()->user()->societe)->where('id','<',$id)->orderBy('id','desc')->count();
            if($testPrecedant > 0){ 
                $precedant = Tache::where('societe',auth()->user()->societe)->where('id','<',$id)->orderBy('id','desc')->first();        
                $previous = $precedant->id; 
                $this->redirect('/detail_tache?id='.$previous.'&active=14&champ=1-1', navigate: true);                         
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
                $this->redirect('/detail_tache?id='.$id.'&active=14&champ=1-1', navigate: true);
            } 
        }
        else{
            
            $testPrecedant = Tache::where('id_utilisateur',auth()->user()->id)->where('id','<',$id)->orderBy('id','desc')->count();
            if($testPrecedant > 0){ 
                $precedant = Tache::where('id_utilisateur',auth()->user()->id)->where('id','<',$id)->orderBy('id','desc')->first();        
                $previous = $precedant->id; 
                $this->redirect('/detail_tache?id='.$previous.'&active=14&champ=1-1', navigate: true);                         
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
                $this->redirect('/detail_tache?id='.$id.'&active=14&champ=1-1', navigate: true);
            } 
        }   
    } 
    public function suivant(int $id){  

       if(auth()->user()->type_user == "Administrateur"){

            $testSuivant = Tache::where('societe',auth()->user()->societe)->where('id','>',$id)->orderBy('id','asc')->count();
            if($testSuivant > 0){
                $suivant = Tache::where('societe',auth()->user()->societe)->where('id','>',$id)->orderBy('id','asc')->first();
                $next = $suivant->id;             
                $this->redirect('/detail_tache?id='.$next.'&active=14&champ=1-1', navigate: true);  
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
                $this->redirect('/detail_tache?id='.$id.'&active=14&champ=1-1', navigate: true);  
            } 
        }
        else{

            $testSuivant = Tache::where('id_utilisateur',auth()->user()->id)->where('id','>',$id)->orderBy('id','asc')->count();
            if($testSuivant > 0){
                $suivant = Tache::where('id_utilisateur',auth()->user()->id)->where('id','>',$id)->orderBy('id','asc')->first();
                $next = $suivant->id;             
                $this->redirect('/detail_tache?id='.$next.'&active=14&champ=1-1', navigate: true);  
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
                $this->redirect('/detail_tache?id='.$id.'&active=14&champ=1-1', navigate: true);  
            } 
        }
    }
    public function creerProforma(){
        $this->validate([
            'utilisateur'=>'required|max:255',
            'nom_tache'=>'required|max:255',
            'client'=>'required|numeric',
            'telephone_client'=>'required|max:255',
            'evolution'=>'required|numeric', // id etape
            'priorite'=>'required|max:255', 
            'date_cloture'=>'required|date|after:today', 
            'temps_alloue'=>'required|date_format:H:i', 
            'description' => 'nullable|string|max:15000',            
        ]);        
       $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_commande;
            if($autoriser == 1){  

                // $client = '';  
                // $id_client = 0;                     
                $date_proforma = date('Y-m-d');
                $date_livraison = date('Y-m-d');
                $mode_reglement = 'Espèce';
                $compte_bancaire = '';
                $note = '';
                $etat = 'Brouillon';
                $montant_ht = 0;
                $montant_remise = 0;
                $montant_tva = 0;
                $montant_precompte = 0;
                $montant_ttc = 0;
                $marge = 0;
                $montant_recu = 0;
                $reste_a_percevoir = 0;
                

                $dates = date('dmy/His');
                $length = 2;
                $token = bin2hex(random_bytes($length));
                $token_ok = 'PROF/'.$dates;
                // $token_ok = 'FACT/'.$dates.'/'.$token;
                $verifie = Tier::where('societe',auth()->user()->societe)->where('id',$this->client)->count();  
                if($verifie > 0){
                    $tiers = Tier::where('societe',auth()->user()->societe)->where('id',$this->client)->first();
                    $nom_client = $tiers->nom;
                    $telephone_client = $tiers->telephone;
                }
                else{
                    $nom_client = '';
                    $telephone_client = '';
                }
                ProformaClientEntete :: create(['code_proforma'=>$token_ok,'nom_client'=>$nom_client,'id_client'=>$this->client,'telephone'=>$telephone_client,'date_proforma'=>$date_proforma,'date_livraison'=>$date_livraison,
                            'montant_ht'=>$montant_ht,'montant_remise'=>$montant_remise,'montant_tva'=>$montant_tva,'montant_precompte'=>$montant_precompte,'montant_ttc'=>$montant_ttc,'marge'=>$marge,
                            'montant_recu'=>$montant_recu,'reste_a_percevoir'=>$reste_a_percevoir,'mode_reglement'=>$mode_reglement,'compte_bancaire'=>$compte_bancaire,'note'=>$this->nom_tache,'etat'=>$etat,'societe'=>auth()->user()->societe,
                            'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                        // ceci recupere le dernier enregistrement cree a l'instant
                $dernier_id = ProformaClientEntete::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id; 

                $id_activite = $dernier_id;
                $page = 'ProformaClient';
                LogActivity::addToLog('Proforma client » '.$token_ok.' créé', $id_activite, $page);
                $this->dispatch('alert',                    
                    title:'proforma client enregistrée!',
                    timer:3000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                );             
                $this->redirect('/nouveau_prof_clt?id='.$dernier_id.'&ref='.$token_ok.'&active=6&champ=1-1&choix=1',navigate: true);               
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
    public function creerFacture(){ 
        $this->validate([
            'utilisateur'=>'required|max:255',
            'nom_tache'=>'required|max:255',
            'client'=>'required|numeric',
            'telephone_client'=>'required|max:255',
            'evolution'=>'required|numeric', // id etape
            'priorite'=>'required|max:255', 
            'date_cloture'=>'required|date|after:today', 
            'temps_alloue'=>'required|date_format:H:i', 
            'description' => 'nullable|string|max:15000',            
        ]); 
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_facture;
            if($autoriser == 1){   

                // $client = '';  
                // $id_client = 0;                     
                $date_facturation = date('Y-m-d');
                $date_echeance = date('Y-m-d');
                $mode_reglement = 'Espèce';
                $compte_bancaire = '';
                // $note = '';
                $etat = 'Brouillon';
                $montant_ht = 0;
                $montant_remise = 0;
                $montant_tva = 0;
                $montant_precompte = 0;
                $montant_ttc = 0;
                $marge = 0;
                $montant_recu = 0;
                $reste_a_percevoir = 0;
                

                $dates = date('dmy/His');
                $length = 2;
                $token = bin2hex(random_bytes($length));
                $token_ok = 'FACT/'.$dates;
                // $token_ok = 'FACT/'.$dates.'/'.$token;
                $verifie = Tier::where('societe',auth()->user()->societe)->where('id',$this->client)->count();  
                if($verifie > 0){
                    $tiers = Tier::where('societe',auth()->user()->societe)->where('id',$this->client)->first();
                    $nom_client = $tiers->nom;
                    $telephone_client = $tiers->telephone;
                }
                else{
                    $nom_client = '';
                    $telephone_client = '';                    
                }
                factureClientEntete :: create(['code_facture'=>$token_ok,'nom_client'=>$nom_client,'id_client'=>$this->client,'telephone'=>$telephone_client,'date_facturation'=>$date_facturation,'date_echeance'=>$date_echeance,
                            'montant_ht'=>$montant_ht,'montant_remise'=>$montant_remise,'montant_tva'=>$montant_tva,'montant_precompte'=>$montant_precompte,'montant_ttc'=>$montant_ttc,'marge'=>$marge,
                            'montant_recu'=>$montant_recu,'reste_a_percevoir'=>$reste_a_percevoir,'mode_reglement'=>$mode_reglement,'compte_bancaire'=>$compte_bancaire,'note'=>$this->nom_tache,'etat'=>$etat,'societe'=>auth()->user()->societe,
                            'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                        // ceci recupere le dernier enregistrement cree a l'instant
                $dernier_id = factureClientEntete::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id; 

                $id_activite = $dernier_id;
                $page = 'factureClient';
                LogActivity::addToLog('Facture ('.$token_ok.') client créée', $id_activite, $page); 
                $this->dispatch('alert',                    
                    title:'Facture client enregistrée!',
                    timer:3000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                );             
                $this->redirect('/nouveau_fact_clt?id='.$dernier_id.'&ref='.$token_ok.'&active=7&champ=1-1&choix=1', navigate: true);               
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
                showConfirmButton: true,
                position:'center',
            );
        }   
    }
    public function ajouter(){
        $this->validate([
            'nom_sous_tache'=>'required|max:255',
        ]);    
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_tache;
            if($autoriser == 1){  
                $statut = 'En attente';
                SousTache::create(['id_tache_entete'=>$this->ids,'nom_sous_tache'=>$this->nom_sous_tache,
                                    'utilisateur'=>$this->utilisateur,'id_utilisateur'=>$this->ids_utilisateur,'statut'=>$statut,
                                    'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                     
                
                $id_activite = $this->ids;
                $page = 'Tache';
                LogActivity::addToLog('Sous tâche » ' .$this->nom_sous_tache.' ajoutée', $id_activite, $page);
                $this->dispatch('alert',                    
                    title:'Sous tâche ajoutée!',
                    timer:3000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                );  
                $this->nom_sous_tache='';                     
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
    public function Lancer(int $id, string $statut){  
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_tache;
            if($autoriser == 1){        
                if($statut == 'En attente'){
                    $encours = 'Encours';
                    SousTache::find($id)->update(['statut'=>$encours,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                    $id_activite = $this->ids;
                    $page = 'Tache';
                    LogActivity::addToLog('Statut tâche (Encours)', $id_activite, $page); 
                    $this->dispatch('alert',                    
                        title:'Tâche encours !',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );  
                }
                elseif($statut == 'Encours'){
                    $terminer = 'Terminer';
                    SousTache::find($id)->update(['statut'=>$terminer,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                    $id_activite = $this->ids;
                    $page = 'Tache';
                    LogActivity::addToLog('Statut tâche (terminée)', $id_activite, $page); 
                    $this->dispatch('alert',                    
                        title:'Tâche terminée !',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );    
                }
                else{
                    $this->dispatch('alert',                    
                        title:'La tâche est terminée, vous ne pouvez modifier ce statut!',
                        timer:5000,
                        icon:'error',
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
    public function confirmerEcraser($id){  
        $this->approuver = $id;      
    } 
    public function ecraser(int $idx){        
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_tache;
            if($autoriser == 1){ 
                                 
                    $page = 'Tache';
                    SousTache::where('id',$idx)->delete();                   

                    $id_activite = $this->ids;
                    LogActivity::addToLog('Sous tâche supprimée', $id_activite, $page); 
                    $this->dispatch('alert',                    
                        title:'Sous tâche supprimée avec succes!',
                        timer:5000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );
                    // $this->redirect('/listing_fact_clt?active=7&champ=1-1&choix=1', navigate: true);
                
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
