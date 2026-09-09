<?php

namespace App\Livewire\CRM;

use Livewire\Component;
use Livewire\Attributes\Validate; 
use Illuminate\Support\Str;
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
use App\Models\ProformaClientEntete;
use App\Models\factureClientEntete;
use App\Models\Note;

class DetailPipeline extends Component
{
    protected $paginationTheme = 'bootstrap';
    use WithPagination;

     // pour recherche client
    public $parNomTier; // Pour la recherche
    public $records;
    public $recordCount;
    public $showdiv = false;

    public $confirmer;
    public $approuver;

    public $ids;
    public $client;
    public $ids_client; // id client selectionne  
    public $nom_opportunite;
    public $email_contact;
    public $telephone_contact;
    public $montant_attendu;
    public $priorite = 'Faible';
    public $evolution;
    public $date_cloture; 
    public $note; 
    public $vendeur; 
    public $probabilite;     

    public $nom_societe; 
    public $adresse_societe; 
    public $ville; 
    public $pays ='Cameroon'; 
    public $langue ='Français'; 
    public $poste_contact; 
    public $site_web; 
    public $recommande_par; 
    public $telephone_recommande_par; 
    public $source; 
    public $secteur_activite; 
    
    public $ouverture = 0;
    public $ouvrir = 0;    
    public $note_interne;
    public $note_internes;
    public $id_act; // id activity
        
    public $activite; // recherche
    public $ParNote; // recherche

    public $type_activite; 
    public $sujet; 
    public $sujets; // juste pour afficher
    public $date_echeance; 
    public $commentaire; 
    public $commentaires; 
    
    public $date_created_at; 
    public $date_updated_at;     
    public $FactCount; 
    public $FactMontant_ttc; 
    public $FactMarge; 
    public $FactReste_a_percevoir; 
    


    public function onDataOuverture(){
        $this->reset('ouverture');
        $this->note_interne = '';
    }
    public function onDataOuvrir(){
        $this->reset('ouvrir');
        $this->note_internes = '';
    }
    public function resetinputFields(){

        $this->type_activite ='Note';     
        $this->sujet ='';
        $this->date_echeance = date('Y-m-d H:i');   
        $this->commentaire ='';        
    }
    public function mount(){  
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->detail_opportunite;
            if($autoriser == 0){
                toast()->error('Oups Désolé', 'Vous n\'êtes pas autorisé à ouvrir cette page!')->position('top-end')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
                $this->redirect('/bienvenue', navigate: true);
            }
        }
        else{
            alert()->error('Oups Désolé', 'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
            $this->redirect('/bienvenue', navigate: true);
        } 
        $this->type_activite = 'Note';     
        $this->date_echeance = date('Y-m-d H:i');    
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
                
                $id = request('id'); // id Pipeline 
                // ceci au chargement de la page
                $test_opportunite = Opportunite::where('societe_id',auth()->user()->societe_id)->where('id',$id)->count();    
                if($test_opportunite > 0){
                    $opportuniter = Opportunite::where('societe_id',auth()->user()->societe_id)->where('id',$id)->first();               
                    $this->ids = $opportuniter->id;
                    $this->ids_client = $opportuniter->id_client; 
                    $this->client = $opportuniter->client;                
                    $this->nom_opportunite = $opportuniter->nom_opportunite;
                    $this->email_contact = $opportuniter->email_contact;
                    $this->telephone_contact = $opportuniter->telephone_contact;
                    $this->montant_attendu = $opportuniter->montant_attendu;
                    $this->priorite = $opportuniter->priorite;
                    $this->evolution = $opportuniter->id_etape;
                    $this->vendeur = $opportuniter->vendeur;               
                    $this->probabilite = $opportuniter->probabilite;
                    $this->date_cloture = $opportuniter->date_cloture;                
                    $this->note = $opportuniter->note; 

                    $this->nom_societe = $opportuniter->nom_societe;                
                    $this->adresse_societe = $opportuniter->adresse_societe;                
                    $this->ville = $opportuniter->ville;                
                    $this->pays = $opportuniter->pays;                
                    $this->langue = $opportuniter->langue;                
                    $this->poste_contact = $opportuniter->poste_contact;                
                    $this->site_web = $opportuniter->site_web;                
                    $this->recommande_par = $opportuniter->recommande_par;                
                    $this->telephone_recommande_par = $opportuniter->telephone_recommande_par; 
                    $this->source = $opportuniter->source;  
                    $this->secteur_activite = $opportuniter->secteur_activite;  
                    $this->date_created_at = $opportuniter->created_at;  
                    $this->date_updated_at = $opportuniter->updated_at;  
                                                      
                } 

                $fact = factureClientEntete::where('societe_id',auth()->user()->societe_id)->where('id_client',$this->ids_client)->get();               
                $this->FactCount = $fact->count();
                $this->FactMontant_ttc = $fact->sum('montant_ttc');
                $this->FactMarge = $fact->sum('marge');
                $this->FactReste_a_percevoir = $fact->sum('reste_a_percevoir');

                $tier = Tier::where('societe_id',auth()->user()->societe_id)->where('id',$this->ids_client)->get(); 
                $etape = Etape :: where('societe_id',auth()->user()->societe_id)->orderBy('id','asc')->get();
                
                if(auth()->user()->societe == "Administration" && auth()->user()->type_user == "Administrateur"){ 
                    
                    $user = Utilisateur::where('societe_id',auth()->user()->societe_id)->orderBy('name','asc')->get();
                }
                else{
                    
                    $user = Utilisateur::where('societe_id',auth()->user()->societe_id)->where('id', $this->vendeur)->get();
                }  

                $planifier = Note::where('societe_id',auth()->user()->societe_id)->where('opportunite_id', $this->ids)->where('sujet','like','%'.$this->ParNote.'%')->limit(50)->orderBy('id','desc')->get();
                $planifierCount = $planifier->count();

                $page = 'Opportunite';
                $log = LogActivityModel::where('societe_id',auth()->user()->societe_id)->where('id_activite', $this->ids)->where('page', $page)->where('subject','like','%'.$this->activite.'%')->limit(50)->orderBy('id','desc')->get();
                $logCount = $log->count();

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
                    return view('livewire.crm.detail-pipeline',compact('title_fils','module','lien','dateJour','tier','etape','user','planifier','planifierCount','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));                  
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
        $this->telephone_contact = $record->telephone;
        $this->showdiv = false;
    }    
    public function update(){        
        $this->validate([
            'client'=>'required|max:255',
            'nom_opportunite'=>'required|max:255',
            'email_contact'=>'nullable|email|max:255',
            'telephone_contact'=>'required|max:255',
            'montant_attendu'=>'required|numeric',
            'vendeur'=>'required|max:255',
            'probabilite'=>'required|max:20',            
            'evolution'=>'required|numeric', // id etape
            'priorite'=>'required|max:255',
            'note' => 'nullable|string|max:15000',
            'date_cloture'=>'required|date|after:today', 

            'nom_societe'=>'nullable|max:255', 
            'adresse_societe'=>'nullable|max:255', 
            'ville'=>'nullable|max:255', 
            'pays'=>'nullable|max:255', 
            'langue'=>'nullable|max:255', 
            'telephone_recommande_par'=>'nullable|max:255', 
            'source'=>'nullable|max:255',
            'secteur_activite'=>'nullable|max:255',                         
            'poste_contact'=>'nullable|max:255', 
            'site_web'=>'nullable|max:255', 
            'recommande_par'=>'nullable|max:255', 
        ]);   
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_opportunite;
            if($autoriser == 1){ 

                $test_tiers = Tier ::where('societe_id',auth()->user()->societe_id)->where('id',$this->ids_client)->count();
                if($test_tiers > 0){                    

                    $test_etapes = Etape::where('societe_id',auth()->user()->societe_id)->where('id', $this->evolution)->count();
                    if($test_etapes > 0){

                        $etapes = Etape::where('societe_id',auth()->user()->societe_id)->where('id', $this->evolution)->first();
                        $nom_etape = $etapes->nom_etape; 

                        $position = 0;
                        Opportunite :: find($this->ids)->update(['client'=>$this->client,'id_client'=>$this->ids_client,'nom_opportunite'=>$this->nom_opportunite,'email_contact'=>$this->email_contact,
                        'telephone_contact'=>$this->telephone_contact,'montant_attendu'=>$this->montant_attendu,'etape'=>$nom_etape,'id_etape'=>$this->evolution,'step'=>$this->evolution,'position'=>$position,'priorite'=>$this->priorite,'vendeur'=>$this->vendeur,'probabilite'=>$this->probabilite,
                        'note'=>$this->note,'date_cloture'=>$this->date_cloture,'nom_societe'=>$this->nom_societe,'adresse_societe'=>$this->adresse_societe,'ville'=>$this->ville,'pays'=>$this->pays,
                        'langue'=>$this->langue,'telephone_recommande_par'=>$this->telephone_recommande_par,'source'=>$this->source,'secteur_activite'=>$this->secteur_activite,'poste_contact'=>$this->poste_contact,'site_web'=>$this->site_web,'recommande_par'=>$this->recommande_par,]);   // pas de 'nom_user et 'user_id' ici  
                        
                        $id_activite = $this->ids;   
                        $page = 'Opportunite';    
                        LogActivity::addToLog('Opportunité » <strong> '.$this->nom_opportunite.' </strong> modifiée', $id_activite, $page); 
                        $this->dispatch('alert',                    
                            title:'Opportunité ('.$this->nom_opportunite.') modifiée!',
                            timer:5000,
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
    public function precedant(int $id){ 

        if(auth()->user()->type_user == "Administrateur"){

            $testPrecedant = Opportunite::where('societe_id',auth()->user()->societe_id)->where('id','<',$id)->orderBy('id','desc')->count();
            if($testPrecedant > 0){ 
                $precedant = Opportunite::where('societe_id',auth()->user()->societe_id)->where('id','<',$id)->orderBy('id','desc')->first();        
                $previous = $precedant->id; 
                $this->redirect('/detail_pipeline?id='.$previous.'&active=3&champ=3-3&choix=1', navigate: true);                         
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
                $this->redirect('/detail_pipeline?id='.$id.'&active=3&champ=3-3&choix=1', navigate: true);
            } 
        }
        else{
            
            $testPrecedant = Opportunite::where('vendeur',auth()->user()->id)->where('id','<',$id)->orderBy('id','desc')->count();
            if($testPrecedant > 0){ 
                $precedant = Opportunite::where('vendeur',auth()->user()->id)->where('id','<',$id)->orderBy('id','desc')->first();        
                $previous = $precedant->id; 
                $this->redirect('/detail_pipeline?id='.$previous.'&active=3&champ=3-3&choix=1', navigate: true);                         
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
                $this->redirect('/detail_pipeline?id='.$id.'&active=3&champ=3-3&choix=1', navigate: true);
            } 
        }   
    }  
    public function suivant(int $id){  

       if(auth()->user()->type_user == "Administrateur"){

            $testSuivant = Opportunite::where('societe_id',auth()->user()->societe_id)->where('id','>',$id)->orderBy('id','asc')->count();
            if($testSuivant > 0){
                $suivant = Opportunite::where('societe_id',auth()->user()->societe_id)->where('id','>',$id)->orderBy('id','asc')->first();
                $next = $suivant->id;             
                $this->redirect('/detail_pipeline?id='.$next.'&active=3&champ=3-3&choix=1', navigate: true);  
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
                $this->redirect('/detail_pipeline?id='.$id.'&active=3&champ=3-3&choix=1', navigate: true);  
            } 
        }
        else{

            $testSuivant = Opportunite::where('vendeur',auth()->user()->id)->where('id','>',$id)->orderBy('id','asc')->count();
            if($testSuivant > 0){
                $suivant = Opportunite::where('vendeur',auth()->user()->id)->where('id','>',$id)->orderBy('id','asc')->first();
                $next = $suivant->id;             
                $this->redirect('/detail_pipeline?id='.$next.'&active=3&champ=3-3&choix=1', navigate: true);  
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
                $this->redirect('/detail_pipeline?id='.$id.'&active=3&champ=3-3&choix=1', navigate: true);  
            } 
        }
    }
    public function confirmerDelete($id){ 
        $this->confirmer = $id;      
    } 
    public function supprimer(int $id){ 

        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->supprimer_opportunite;
            if($autoriser == 1){   
                if($id){  
                                 
                    Opportunite::where('id',$id)->delete();                    
                    $id_activite = $id;
                    $page = 'Opportunite';
                    LogActivity::addToLog('Opportunité supprimé', $id_activite, $page);
                    $this->dispatch('alert',                    
                        title:'Suppression effectuée!',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );
                    $this->redirect('/pipeline_tiers?active=3&champ=3-3&choix=1', navigate: false);   
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
    public function creerProforma(){
        $this->validate([
            'client'=>'required|max:255',
            'nom_opportunite'=>'required|max:255',
            'email_contact'=>'nullable|email|max:255',
            'telephone_contact'=>'required|max:255',
            'montant_attendu'=>'required|numeric',
            'vendeur'=>'required|max:255',
            'evolution'=>'required|numeric', // id etape
            'priorite'=>'required|max:255',
            'note' => 'nullable|string|max:15000',
            'date_cloture'=>'nullable|date|after:today', 
        ]);        
       $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
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
                $profCltEntet = ProformaClientEntete :: create(['code_proforma'=>$token_ok,'nom_client'=>$this->client,'id_client'=>$this->ids_client,'telephone'=>$this->telephone_contact,'date_proforma'=>$date_proforma,'date_livraison'=>$date_livraison,
                            'montant_ht'=>$montant_ht,'montant_remise'=>$montant_remise,'montant_tva'=>$montant_tva,'montant_precompte'=>$montant_precompte,'montant_ttc'=>$montant_ttc,'marge'=>$marge,
                            'montant_recu'=>$montant_recu,'reste_a_percevoir'=>$reste_a_percevoir,'mode_reglement'=>$mode_reglement,'compte_bancaire'=>$compte_bancaire,'note'=>$this->nom_opportunite.', Revenu attendu '.$this->montant_attendu,
                            'etat'=>$etat,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                        // ceci recupere le dernier enregistrement cree a l'instant
                $dernier_id = $profCltEntet->id; 

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
            'client'=>'required|max:255',
            'nom_opportunite'=>'required|max:255',
            'email_contact'=>'nullable|email|max:255',
            'telephone_contact'=>'required|max:255',
            'montant_attendu'=>'required|numeric',
            'vendeur'=>'required|max:255',
            'evolution'=>'required|numeric', // id etape
            'priorite'=>'required|max:255',
            'note' => 'nullable|string|max:15000',
            'date_cloture'=>'nullable|date|after:today', 
        ]);     
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
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
                $factCltEntet = factureClientEntete :: create(['code_facture'=>$token_ok,'nom_client'=>$this->client,'id_client'=>$this->ids_client,'telephone'=>$this->telephone_contact,'date_facturation'=>$date_facturation,'date_echeance'=>$date_echeance,
                            'montant_ht'=>$montant_ht,'montant_remise'=>$montant_remise,'montant_tva'=>$montant_tva,'montant_precompte'=>$montant_precompte,'montant_ttc'=>$montant_ttc,'marge'=>$marge,
                            'montant_recu'=>$montant_recu,'reste_a_percevoir'=>$reste_a_percevoir,'mode_reglement'=>$mode_reglement,'compte_bancaire'=>$compte_bancaire,'note'=>$this->nom_opportunite.', Revenu attendu '.$this->montant_attendu,
                            'etat'=>$etat,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                // ceci recupere le dernier enregistrement cree a l'instant
                $dernier_id = $factCltEntet->id; 

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
    public function noter(){
        $this->ouverture = 1;
    }
    public function publierNote(){
        $this->validate([
            'note_interne'=>'required|max:255',            
        ]);
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_opportunite; 
            if($autoriser == 1){ 
                
                $id_activite = $this->ids;   
                $page = 'Opportunite';    
                LogActivity::addToLog('Note » '.$this->note_interne.'', $id_activite, $page);                
                $this->dispatch('alert',                    
                    title:'Note (<strong>'.$this->note_interne.'</strong>) ajoutée!',
                    timer:5000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                ); 
                $this->note_interne = '';
                $this->onDataOuverture();

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
    public function ModifNote(int $id){
        $this->ouvrir = $id;
        $noteActivite = Note::where('societe_id',auth()->user()->societe_id)->where('id',$id)->first();
        $this->id_act = $noteActivite->id;
        $this->type_activite = $noteActivite->type_activite;
        $this->sujets = $noteActivite->sujet;
        $this->commentaires = $noteActivite->commentaire;
    }
    public function modifierNote(){
        $this->validate([
            'commentaires'=>'required|max:255',            
        ]);
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_opportunite; 
            if($autoriser == 1){ 
                     
                Note::find($this->id_act)->update(['commentaire'=>$this->commentaires]);
                
                $id_activite = $this->ids;
                $page = 'Opportunite';
                LogActivity::addToLog('Opportunité : activité planifiée » '.$this->sujets.' modifiée', $id_activite, $page); 
                $this->dispatch('alert',                    
                    title:$this->type_activite.' (<strong>'.$this->sujets.'</strong>) modifiée!',
                    timer:5000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                ); 
                $this->onDataOuvrir();

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
    public function saveActivite(){
        $this->validate([
            'type_activite'=>'required|max:55',
            'sujet'=>'required|max:255',
            'date_echeance'=>'required|date',
            'commentaire' => 'nullable|string|max:255',          
        ]);    
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){      
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_opportunite;
            if($autoriser == 1){                   
                
                Note::create(['opportunite_id'=>$this->ids,'type_activite'=>$this->type_activite,'sujet'=>$this->sujet,'date_echeance'=>$this->date_echeance,'commentaire'=>$this->commentaire,
                'profil'=>auth()->user()->profil,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                    
                    
                    // ceci recupere le dernier enregistrement cree a l'instant
                    $id_activite = $this->ids;
                    $page = 'Opportunite';
                    LogActivity::addToLog('Opportunité : activité planifiée » '.$this->sujet.' créée', $id_activite, $page);  
                    $this->dispatch('alert',                    
                        title:'Activité planifiée ('.$this->sujet.') enregistrée!',
                            timer:3000,
                            icon:'success',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                    );  
                    $this->resetinputFields(); 
                    // $this->redirect('/detail_pipeline?id='.$this->ids.'&active=3&champ=3-3&choix=1', navigate: true);
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
    public function Terminer(){
        // $this->validate([
        //     'commentaires'=>'required|max:255',            
        // ]);
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_opportunite; 
            if($autoriser == 1){ 
                $statut = 'Terminer';
                Note::find($this->id_act)->update(['statut'=>$statut]);
                
                $id_activite = $this->ids;
                $page = 'Opportunite';
                LogActivity::addToLog('Opportunité : activité planifiée » '.$this->sujets.' Terminer', $id_activite, $page); 
                $this->dispatch('alert',                    
                    title:$this->type_activite.' (<strong>'.$this->sujets.'</strong>) Terminer!',
                    timer:5000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                ); 
                $this->onDataOuvrir();

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
    public function approuverDelete($id){    
        $this->approuver = $id;      
    } 
    public function ecraser(int $id){ 

        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->supprimer_tier;
            if($autoriser == 1){   
                if($id){                    
                    Note::where('id',$id)->delete();                    
                    // $id_activite = $id;
                    $id_activite = $this->ids; // pour afficher la suppresion dans l'opportunite object lie
                    $page = 'Opportunite';
                    LogActivity::addToLog('Opportunité : activité planifiée » supprimée', $id_activite, $page);
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
}
