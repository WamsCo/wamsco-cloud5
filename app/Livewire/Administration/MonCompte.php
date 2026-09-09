<?php

namespace App\Livewire\Administration;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;  
use Livewire\WithPagination;
use Livewire\WithFileUploads; 
use App\Helpers\LogActivity;
use App\Models\LogActivity as LogActivityModel;
use App\Models\Entite;
use App\Models\DeviseTva;
use App\Models\Role;
use App\Models\Utilisateur;
use App\Models\Departement;
use App\Models\Poste_travail;
use App\Models\SessionPos;
use App\Models\factureClientEntete;
use App\Models\factureClientLigne;
use App\Models\CommandeClientEntete;
use App\Models\CommandeClientLigne;
use App\Models\ProformaClientEntete;
use App\Models\ProformaClientLigne;
use App\Models\ExpeditionClientEntete;
use App\Models\ExpeditionClientLigne;
use App\Models\Mouvement;
use App\Models\Emplacement;
use App\Models\CommandeAttenteEntete;
use App\Models\CommandeAttenteLigne;

use App\Models\Parametre;
use App\Models\EcritureBancaire;
use App\Models\CompteBancaire;
use App\Models\Tier;
use App\Models\Produit;
use App\Models\Stock;
use App\Models\Reglement;
use App\Models\Entrepot;
use App\Models\Etape;
use App\Models\EtapeTache;
use App\Models\SousTache;
use App\Models\Opportunite;
use App\Models\soldeClient;
use App\Models\OrdreFabrication;
use App\Models\Nomenclature;
use App\Models\Inventaire;
use App\Models\InventaireLigne;
use App\Models\PosFactureClientEntete;
use App\Models\PosFactureClientLigne;
use App\Models\Ticket;
use App\Models\Transfert;
use App\Models\TransfertLigne;
use App\Models\TransfertFiliale;
use App\Models\TransfertFilialeLigne;
use App\Models\ReglementCommercial;
use App\Models\Categorie;
use App\Models\CategoriePaie;
use App\Models\SoldeTier;

class MonCompte extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $id; // Edit et update
    public $ids;
    public $email;
    public $confirmerEmail;    
    public $nom;
    public $role; 
    public $password_test;
    public $password_actuel; 
    public $password; 
    public $password_confirmation;
    public $date_valide;
    public $etat = 1;
    public $societe; 
    public $salarie; 
    public $sexe;     
    public $profil;  
    public $profils; // gere le update      
    public $old_image; 
    public $note_interne;

    public $titre; 
    public $telephone;
    public $nationalite = 'Cameroon';
    public $cni;
    public $niu;
    public $passeport;
    public $matricule;

    public $date_naissance;
    public $lieu_naissance;
    public $etat_civil = 'Célibataire';
    public $nbre_enfant = 0;
    public $nom_conjoint;
    public $date_nais_conjoint;
    public $persone_contact_urgence;
    public $telephone_urgence;

    public $devise;
    public $confirmer; 

    public function mount(){        
        $this->ids = request('id'); // id user
    }
    public function render(){
    
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('id',auth()->user()->societe_id)->get();
        $jourValid = $entite_mod[0]->validite_mod; 
        $mod_administration = $entite_mod[0]->mod_administration; 
        $soldeClient = $entite_mod[0]->solde;
        if($dateJour <= $jourValid){
            if($mod_administration == 1){
                $title = 'Mon compte | WamsCo';
                $module = 'Paramètres';
                $title_fils = 'Mon compte';
                $lien = 'utilisateurs?active=8&champ=8-1';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix'); 
                $dateJour = date('Y-m-d');                   
                
                // $entite = Entite::orderBy('enseigne','asc')->get();
                $departe = Departement :: where('societe_id',auth()->user()->societe_id)->orderBy('nom_departement','asc')->get();  
                $posteTravail = Poste_travail :: where('societe_id',auth()->user()->societe_id)->orderBy('nom_poste','asc')->get(); 
                
                // ceci au chargement de la page                
                $user = Utilisateur::where('societe_id',auth()->user()->societe_id)->where('id',$this->ids)->get();
                $entite = Entite::where('societe_mere',auth()->user()->societe_mere)->orderBy('enseigne','asc')->get(); 
                $usersCount = $user->count();

                // Facture
                $factClient_entete = factureClientEntete::where('societe_id',auth()->user()->societe_id)->where('user_id',$this->ids)->orderBy('id','DESC')->limit(15)->get();
                $factCltEntCount = $factClient_entete->count();                 

                $factClient_all = factureClientEntete::where('societe_id',auth()->user()->societe_id)->where('user_id',$this->ids)->orderBy('id','DESC')->get();
                $factClientEnteteCount = $factClient_all->count();                 
                $factClientEnteteMarge = $factClient_all->sum('marge');   
                $factClientEnteteTTC = $factClient_all->sum('montant_ttc');  
                $factClientEnteteResteApercevoir = $factClient_all->sum('reste_a_percevoir'); 
                // commande
                $cmd_client = CommandeClientEntete::where('societe_id',auth()->user()->societe_id)->where('user_id',$this->ids)->orderBy('id', 'DESC')->limit(15)->get();
                $cmdClientCount = $cmd_client->count(); 
                
                $page = 'Utilisateur'; // Pour evenement lie
                $log = LogActivityModel::where('societe_id',auth()->user()->societe_id)->where('id_activite', $this->ids)->where('page', $page)->limit(9)->orderBy('id','desc')->get();
                $logCount = $log->count();
               
                        
                $utilisa = Utilisateur::where('societe_id',auth()->user()->societe_id)->orderBy('name','asc')->get();  
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
                return view('livewire.administration.users.mon_compte',compact('title_fils','module','lien','dateJour','user','usersCount','factClient_entete','factCltEntCount','factClientEnteteCount','factClientEnteteMarge','factClientEnteteTTC',
                'factClientEnteteResteApercevoir','cmd_client','cmdClientCount','entite','utilisa','departe','posteTravail','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant')); 
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
            return view('livewire.bienvenue',compact('dateJour','entite_mod'))->layout('components.layouts.app',compact('title','module','title_fils','lien','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant')); 
        }
    }
    public function edit($id){ 
       
        $user = Utilisateur::where('id',$id)->first();
        $this->id = $user->id; // important     
        $this->email = $user->email;
        $this->confirmerEmail = $user->confirmer;
        $this->nom = $user->name;
        $this->role = $user->type_user;
        // $this->password = ;
        // $this->password_confirmation = '';
        $this->password_test = $user->password;
        $this->date_valide = $user->date_valide;
        $this->note_interne = $user->note_interne;
        $this->societe = $user->societe;
        $this->salarie = $user->salarie;
        $this->sexe = $user->sexe;
        $this->etat = $user->etat;
        $this->created_at = $user->created_at;
        $this->updated_at = $user->updated_at;
        $this->profil = $user->profil; 
        $this->titre = $user->titre; 
        $this->telephone = $user->telephone; 
        $this->horaire_journalier = $user->horaire_journalier; 
        $this->horaire_hebdo = $user->horaire_hebdo; 
        $this->horaire_mensuel = $user->horaire_mensuel; 
        $this->departement = $user->departement_id; 
        $this->poste_travail = $user->poste_travail_id; 
        $this->lieu_travail = $user->lieu_travail; 
        $this->adresse_travail = $user->adresse_travail; 
        $this->manager = $user->manager; 
        $this->validateur_conges = $user->validateur_conges; 
        $this->date_naissance = $user->date_naissance; 
        $this->lieu_naissance = $user->lieu_naissance; 
        $this->nationalite = $user->nationalite; 
        $this->cni = $user->cni; 
        $this->passeport = $user->passeport; 
        $this->etat_civil = $user->etat_civil; 
        $this->nbre_enfant = $user->nbre_enfant; 
        $this->nom_conjoint = $user->nom_conjoint; 
        $this->date_nais_conjoint = $user->date_nais_conjoint; 
        $this->persone_contact_urgence = $user->persone_contact_urgence; 
        $this->telephone_urgence = $user->telephone_urgence; 
        $this->type_employe = $user->type_employe; 
        $this->type_salaire = $user->type_salaire; 
        $this->date_debut_contrat = $user->date_debut_contrat; 
        $this->date_fin_contrat = $user->date_fin_contrat; 
        $this->responsable_rh = $user->responsable_rh; 
        $this->salaire = $user->salaire; 
        $this->categorie = $user->categorie; 
        $this->echelon = $user->echelon; 
        $this->niu = $user->niu; 
        $this->cnps = $user->cnps; 
        $this->dipe = $user->dipe; 
        $this->matricule = $user->matricule; 
        $this->mode_paiement = $user->mode_paiement; 
        $this->numero_compte = $user->numero_compte; 
        $this->rib = $user->rib; 
        $this->note_interne = $user->note_interne;        

        // suggerer un matricle si vide
        if(empty($this->matricule)){           
            $date = date('ymd-Hi');            
            $societe = substr($this->societe,0,1);
            $this->matricule = 'M-'.$societe.$date;  
        }
        if(empty($this->salaire)){            
            $this->salaire = 0;  
        }        
    }
     public function update(){        
        // ceci teste pour verifier si l'user encours a un role dans la bd 
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();            
            if(empty($this->password_actuel)){
                $this->validate([ 
                    'email'=>'required|email|max:255',
                    'titre'=>'required|max:255',
                    'nom'=>'required|max:255',
                    'telephone'=>'required|max:255',
                    'role'=>'required|max:255',                                  
                    'date_valide'=>'required|date',            
                    'societe'=>'nullable|max:255',
                    'salarie'=>'required|max:255',  
                    'sexe'=>'required|max:255',  
                    'nationalite'=>'nullable|max:255',  
                    'etat'=>'required|numeric',  
                    'note_interne'=>'nullable|max:255', 
                    'telephone_urgence'=>'required|max:255',                          
                    'cni'=>'nullable|max:255',                          
                    'passeport'=>'nullable|max:255',                          
                    'niu'=>'nullable|max:255',                          

                    'date_naissance'=>'nullable',
                    'lieu_naissance'=>'nullable|max:255',
                    'etat_civil'=>'nullable',
                    'nbre_enfant'=>'nullable|numeric',

                    'nom_conjoint'=>'nullable|max:255',
                    'date_nais_conjoint'=>'nullable',            
                    'persone_contact_urgence'=>'nullable|max:255',
                    'telephone_urgence'=>'nullable|max:255',
                ]);                                                          
                if($this->id){   

                     Utilisateur::find($this->id)->update(['titre'=>$this->titre,'name'=>$this->nom,'telephone'=>$this->telephone,'sexe'=>$this->sexe,'nationalite'=>$this->nationalite,                                        
                                        'cni'=>$this->cni,'passeport'=>$this->passeport,'niu'=>$this->niu,'date_naissance'=>$this->date_naissance,'lieu_naissance'=>$this->lieu_naissance,'etat_civil'=>$this->etat_civil,
                                        'nbre_enfant'=>$this->nbre_enfant,'nom_conjoint'=>$this->nom_conjoint,'date_nais_conjoint'=>$this->date_nais_conjoint,'persone_contact_urgence'=>$this->persone_contact_urgence,
                                        'telephone_urgence'=>$this->telephone_urgence,'note_interne'=>$this->note_interne,'nom_user_modif'=>auth()->user()->email,]); 

                                        
                    Entite:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);
                    SessionPos:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);
                    factureClientEntete:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);
                    factureClientLigne:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);
                    CommandeClientEntete:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]); 
                    CommandeClientLigne:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);
                    ProformaClientEntete:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]); 
                    ProformaClientLigne:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);
                    ExpeditionClientEntete:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);
                    ExpeditionClientLigne:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);
                    Mouvement:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);
                    Emplacement:: where('user_id',$this->id)->update(['nom_user'=>$this->nom,'non_caissiere'=>$this->nom]); 
                    CommandeAttenteEntete:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]); 
                    CommandeAttenteLigne:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);
                    
                    EcritureBancaire:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);
                    CompteBancaire:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);                                                                                   
                    Tier:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);                                                                                   
                    Role:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);                                                                                   
                    Parametre:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);                                                                                   
                    Produit:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);                                                                                   
                    Stock:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]); 
                    Reglement:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);                                                                                   
                    Entrepot:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);                                                                                   
                    Etape:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);                                                                                   
                    EtapeTache:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);                                                                                                              
                    SousTache:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]); 
                    Opportunite:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]); 
                    soldeClient:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);    
                    OrdreFabrication:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);                                                                                   
                    Nomenclature:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);                                                                                   
                    Inventaire:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);                                                                                   
                    InventaireLigne:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]); 
                    PosFactureClientEntete:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]); 
                    PosFactureClientLigne:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]); 
                    Ticket:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);                                                                                   
                    Transfert:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);                                                                                   
                    TransfertLigne:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);                                                                                   
                    TransfertFiliale:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);                                                                                   
                    TransfertFilialeLigne:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);  
                    ReglementCommercial:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);
                    Categorie:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);                    
                    CategoriePaie:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);                    
                    SoldeTier:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);
                    LogActivityModel:: where('user_id',$this->id)->update(['user_email'=>$this->nom]);                    
                    
                    $id_activite = $this->id;
                    $page = 'Utilisateur';      
                    LogActivity::addToLog('Utilisateur » '.$this->nom.' modifié', $id_activite, $page); 
                    $this->redirect('/mon_compte?id='.$this->id, navigate: true);
                    $this->dispatch('alert',                    
                    title:'L\'utilisateur ('.$this->nom.') modifié !',
                        timer:5000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    ); 
                } 
            }
            else{
                $this->validate([ 
                    'email'=>'required|email|max:255',
                    'titre'=>'required|max:255',
                    'nom'=>'required|max:255',
                    'telephone'=>'required|max:255',
                    'role'=>'required|max:255',
                    'date_valide'=>'required|date',            
                    'etat'=>'required|max:255', 
                    'note_interne'=>'nullable|max:255',
                    'sexe'=>'required|max:255',           
                    'nationalite'=>'nullable|max:255',
                    'password_actuel'=>'required|min:8',
                    'password'=>'required|confirmed|min:8',
                    'password_confirmation'=>'required',
                    'telephone_urgence'=>'required|max:255',                          
                    'cni'=>'nullable|max:255',                          
                    'passeport'=>'nullable|max:255',                          
                    'niu'=>'nullable|max:255',                          
                    
                    'date_naissance'=>'nullable',
                    'lieu_naissance'=>'nullable|max:255',
                    'etat_civil'=>'nullable',
                    'nbre_enfant'=>'nullable|numeric',

                    'nom_conjoint'=>'nullable|max:255',
                    'date_nais_conjoint'=>'nullable',            
                    'persone_contact_urgence'=>'nullable|max:255',
                    'telephone_urgence'=>'nullable|max:255',
                ]);   
                if (Hash::check($this->password_actuel, $this->password_test)) {
                    if($this->id){ 

                        Utilisateur::find($this->id)->update(['titre'=>$this->titre,'name'=>$this->nom,'telephone'=>$this->telephone,'sexe'=>$this->sexe,'password'=>bcrypt($this->password),'nationalite'=>$this->nationalite,                                        
                                        'cni'=>$this->cni,'passeport'=>$this->passeport,'niu'=>$this->niu,'date_naissance'=>$this->date_naissance,'lieu_naissance'=>$this->lieu_naissance,'etat_civil'=>$this->etat_civil,
                                        'nbre_enfant'=>$this->nbre_enfant,'nom_conjoint'=>$this->nom_conjoint,'date_nais_conjoint'=>$this->date_nais_conjoint,'persone_contact_urgence'=>$this->persone_contact_urgence,
                                        'telephone_urgence'=>$this->telephone_urgence,'note_interne'=>$this->note_interne,'nom_user_modif'=>auth()->user()->email,]);
                        
                        Entite:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);
                        SessionPos:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);
                        factureClientEntete:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);
                        factureClientLigne:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);
                        CommandeClientEntete:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]); 
                        CommandeClientLigne:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);
                        ProformaClientEntete:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]); 
                        ProformaClientLigne:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);
                        ExpeditionClientEntete:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);
                        ExpeditionClientLigne:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);
                        Mouvement:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]); 
                        Emplacement:: where('user_id',$this->id)->update(['nom_user'=>$this->nom,'non_caissiere'=>$this->nom]); 
                        CommandeAttenteEntete:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]); 
                        CommandeAttenteLigne:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]); 

                        EcritureBancaire:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);
                        CompteBancaire:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);                                                                                   
                        Tier:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);                                                                                   
                        Role:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);                                                                                   
                        Parametre:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);                                                                                   
                        Produit:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);                                                                                   
                        Stock:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]); 
                        Reglement:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);                                                                                   
                        Entrepot:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);                                                                                   
                        Etape:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);                                                                                   
                        EtapeTache:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);                                                                                                              
                        SousTache:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]); 
                        Opportunite:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]); 
                        soldeClient:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);    
                        OrdreFabrication:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);                                                                                   
                        Nomenclature:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);                                                                                   
                        Inventaire:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);                                                                                   
                        InventaireLigne:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]); 
                        PosFactureClientEntete:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]); 
                        PosFactureClientLigne:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]); 
                        Ticket:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);                                                                                   
                        Transfert:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);                                                                                   
                        TransfertLigne:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);                                                                                   
                        TransfertFiliale:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);                                                                                   
                        TransfertFilialeLigne:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);  
                        ReglementCommercial:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]); 
                        Categorie:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);                    
                        CategoriePaie:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]);
                        SoldeTier:: where('user_id',$this->id)->update(['nom_user'=>$this->nom]); 
                        LogActivityModel:: where('user_id',$this->id)->update(['user_email'=>$this->nom]);
                                                    
                        $id_activite = $this->id;
                        $page = 'Utilisateur';      
                        LogActivity::addToLog('Compte » '.$this->nom.' modifié', $id_activite, $page); 
                        $this->redirect('/mon_compte?id='.$this->id, navigate: true);
                        $this->dispatch('alert',                    
                        title:'L\'utilisateur ('.$this->nom.') modifié !',
                            timer:5000,
                            icon:'success',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        ); 
                    }   
                }
                else{
                    $this->dispatch('alert',                    
                        title:'Désolé <strong>'.$this->nom.'</strong>, votre mot de passe actuel est incorrect!',
                        timer:10000,
                        icon:'error',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    ); 
                }
                    
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
     // Modifier image du utilisateur
    public function valider_img(){
        $this->validate([                      
            'profils'=>'required|image|mimes:jpeg,jpg,png,gif|max:2048',          
        ]); 
        if($this->id){     
            $path = $this->profils->store('img_profil','public'); 
            Utilisateur::find($this->id)->update(['profil'=>$path]);
            LogActivityModel:: where('user_id',$this->id)->update(['profil'=>$path]); 

            $id_activite = $this->id;
            $page = 'Utilisateur';      
            LogActivity::addToLog('Profil changé', $id_activite, $page); 
            $this->dispatch('alert',                    
                title:'Profil enregistré!',
                timer:3000,
                icon:'success',
                toast:true,
                showConfirmButton: false,
                position:'top-end',
            ); 
            $this->redirect('/mon_compte?id='.$this->id, navigate: true);
        }        
    } 
}
