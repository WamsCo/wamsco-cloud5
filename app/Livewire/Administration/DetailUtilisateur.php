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
use Mail;
use App\Mail\ConfirmationMail;
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

class DetailUtilisateur extends Component
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
    public $enseigne; 
    public $societe; 
    public $societe_id;
    public $salarie; 
    public $sexe;     
    public $profil;    
    public $old_image; 
    
    public $envoi_mail;     
    
    public $devise;
    public $confirmer; 

    // Parametre Rh
    public $horaire_journalier = 0;
    public $horaire_hebdo = 0;
    public $horaire_mensuel = 0;
    
    public $titre;   
    public $telephone;
    public $departement;
    public $poste_travail;
    public $lieu_travail;
    public $adresse_travail;
    public $manager;
    public $validateur_conges;
    public $image;
    public $images; // image pour update
    public $statut = 1; 
    public $date_naissance;
    public $lieu_naissance;
    public $nationalite = 'Cameroon';
    public $cni;
    public $passeport;
    public $etat_civil = 'Célibataire';
    public $nbre_enfant = 0;
    public $nom_conjoint;
    public $date_nais_conjoint;
    public $persone_contact_urgence;
    public $telephone_urgence;
    public $type_employe  = 'Salarié';
    public $type_contrat;
    public $type_salaire  = 'Salaire Mensuel';
    public $date_debut_contrat;
    public $date_fin_contrat;
    public $responsable_rh;
    public $salaire = 0;
    public $categorie;
    public $echelon;
    public $niu;
    public $cnps;
    public $dipe;
    public $matricule;
    public $mode_paiement = 'Espèces';
    public $nom_banque;
    public $numero_compte;    
    public $rib;
    public $note_interne; 
    // Fin Rh

    public function mount(){ 
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->consulter_user;
            if($autoriser == 0){
                alert()->error('Oups Désolé', 'Vous n\'êtes pas autorisé à ouvrir cette page !!!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
                $this->redirect('/bienvenue', navigate: true);
            }
        }
        else{
            alert()->error('Oups Désolé', 'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
            $this->redirect('/bienvenue', navigate: true);
        } 
        $this->ids = request('id'); // id user
    }
    public function render(){    
        $this->society = request('soc'); // recupere societe

        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('id',auth()->user()->societe_id)->get();
        $jourValid = $entite_mod[0]->validite_mod; 
        $mod_administration = $entite_mod[0]->mod_administration; 
        $soldeClient = $entite_mod[0]->solde;
        if($dateJour <= $jourValid){
            if($mod_administration == 1){
                $title = 'Détails Utilisateur | WamsCo';
                $module = 'Paramètres';
                $title_fils = 'Détails utilisateur';
                $lien = 'utilisateurs?active=8&champ=8-1';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix'); 
                $dateJour = date('Y-m-d');                   
                
                // $entite = Entite::orderBy('enseigne','asc')->get();
                $departe = Departement :: where('societe_id',auth()->user()->societe_id)->orderBy('nom_departement','asc')->get();  
                $posteTravail = Poste_travail :: where('societe_id',auth()->user()->societe_id)->orderBy('nom_poste','asc')->get(); 
                if(auth()->user()->societe == "Administration"){
                    $privillege = Role::where('societe_id', $this->societe)->orderBy('nom','asc')->get(); // ceci affiche en fonction de la societe choisie
                }
                else{ 
                    $privillege = Role::where('societe_id',auth()->user()->societe_id)->orderBy('nom','asc')->get();
                }                
                
                // ceci au chargement de la page
                if(auth()->user()->societe == "Administration"){
                    // ceci permet de creer un utilisateur dans autre societe en restant dans l'administration
                    $user = Utilisateur::where('id',$this->ids)->get();  
                    $entite = Entite::orderBy('enseigne','asc')->get();
                }
                else{
                    $user = Utilisateur::where('societe_id',auth()->user()->societe_id)->where('id',$this->ids)->get();
                    $entite = Entite::where('societe_mere_id',auth()->user()->societe_mere_id)->orderBy('enseigne','asc')->get();     
                }                      
                $usersCount = $user->count();

                    // Gere l'affichage du bouton suppression
                $uti = Utilisateur::where('id',$this->ids)->first();
                $societ =  $uti->societe;
                $societe_id =  $uti->societe_id;
                $userSociete = Utilisateur::where('societe_id',$societe_id)->get();
                $userDispo = $userSociete->count();  

                $configCount = Parametre::where('societe_id',auth()->user()->societe_id)->limit(1)->count();
                if($configCount > 0){
                   $config = Parametre::where('societe_id',auth()->user()->societe_id)->limit(1)->get();
                    $this->envoi_mail = $config[0]->envoi_mail;
                }
                else{
                    $this->envoi_mail = 0;
                }

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
                $log = LogActivityModel::where('societe_id',auth()->user()->societe_id)->where('id_activite', $this->ids)->where('page', $page)->limit(50)->orderBy('id','desc')->get();
                $logCount = $log->count();
                 
                $utilisa = Utilisateur::where('societe_id',auth()->user()->societe_id)->orderBy('name','asc')->get();

                // gerer les heures employes
                if($this->horaire_journalier > 0){
                    $resultatHebdo = $this->horaire_journalier * 5;                
                    $this->horaire_hebdo = $resultatHebdo;
                    $this->horaire_mensuel = number_format($this->horaire_hebdo * 52 / 12,2,',',' ');
                } 

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
                return view('livewire.administration.users.detail-utilisateur',compact('title_fils','module','lien','dateJour','user','usersCount','factClient_entete','factCltEntCount','factClientEnteteCount','factClientEnteteMarge','factClientEnteteTTC',
                'factClientEnteteResteApercevoir','cmd_client','cmdClientCount','log','logCount','entite','utilisa','userDispo','departe','posteTravail','privillege'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant')); 
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
    public function confirmerDelete($id){  
        $this->id = $id;      
        $this->confirmer = $id;      
    } 
    public function supprimer($id){ 

        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->supprimer_user;
            if($autoriser == 1){   
                if($id){
                    
                    $verifier_society =  Utilisateur::where('id',$id)->count();
                    if($verifier_society > 0){
                    
                        $society =  Utilisateur::where('id',$id)->first();
                        $societe_user = $society->societe;
                        $societe_id = $society->societe_id;
                        Utilisateur::where('id',$id)->delete(); 
                        
                        $users = Utilisateur::where('societe_id',$societe_id)->count(); 
                        Entite::where('id',$societe_id)->update(['nombre_users'=>$users]);
                        
                        $page = 'Utilisateur';
                        LogActivityModel::where('id_activite',$id)->where('page',$page)->delete();
                        $id_activite = $id;
                        LogActivity::addToLog('Utilisateur supprimé définitivement', $id_activite, $page);
                        $this->dispatch('alert',                    
                            title:'Suppression effectuée!',
                            timer:3000,
                            icon:'success',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        ); 
                        flash ('<strong>Suppression</strong> effectuée !')->success();
                        $this->redirect('/utilisateurs?active=12&champ=1-1', navigate: true);  
                    }
                    else{ 
                        $this->dispatch('alert',                    
                            title:'Erreur lors de la suppression : cet utilisateur n\'existe pas',
                            timer:3000,
                            icon:'error',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        ); 
                        flash ('Erreur lors de la suppression : cet utilisateur n\'existe pas !')->error();
                        $this->redirect('/utilisateurs?active=8&champ=8-1', navigate: true); 
                    }   
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
    public function edit($id){ 

        $user = Utilisateur::where('id',$id)->first();
        $this->id = $user->id; // important gere le suivant et precedent
        $this->ids = $user->id; // important gere le suivant et precedent
        $this->email = $user->email;
        $this->confirmerEmail = $user->confirmer;        
        $this->nom = $user->name;
        $this->role = $user->type_user;
        // $this->password = ;
        // $this->password_confirmation = '';
        $this->password_test = $user->password;
        $this->date_valide = $user->date_valide;
        $this->note_interne = $user->note_interne;
        $this->enseigne = $user->societe;
        $this->societe = $user->societe_id;
        $this->societe_id = $user->societe_id;
        $this->salarie = $user->salarie;
        $this->sexe = $user->sexe;
        $this->etat = $user->etat;
        $this->created_at = $user->created_at;
        $this->updated_at = $user->updated_at;
        $this->old_image = $user->profil; 
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
        $this->type_contrat = $user->type_contrat; 
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
        $this->nom_banque = $user->nom_banque; 
        $this->numero_compte = $user->numero_compte; 
        $this->rib = $user->rib; 

        // suggerer un matricle si vide
        if(empty($this->matricule)){           
            $date = date('ymd-Hi');            
            // $societe = substr($this->enseigne,0,3);
            $societe = strtoupper(substr($this->enseigne, 0, 3));  // strtoupper() mettre en majuscule
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
            $autoriser = $role[0]->modifier_user;
            if($autoriser == 1){
                if(empty($this->password_actuel)){ 
                    $this->validate([        
                        
                        'email'=>'required|email|max:255',
                        'titre'=>'required',
                        'nom'=>'required', 
                        'telephone'=>'required', 
                        'salarie'=>'required',  
                        'sexe'=>'required',
                        'nationalite'=>'required|max:255', 
                        'societe'=>'required|max:255',
                        'role'=>'required|max:255',                                  
                        'date_valide'=>'required|date',            
                        'matricule'=>'required|max:255', 
                        'etat'=>'required|numeric', 

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

                        'departement'=>'nullable',
                        'poste_travail'=>'nullable', 
                        'lieu_travail'=>'nullable|max:255', 
                        'adresse_travail'=>'nullable|max:255', 

                        'responsable_rh'=>'nullable|max:255',
                        'manager'=>'nullable|max:255',
                        'validateur_conges'=>'nullable|max:255',

                        'type_employe'=>'required|max:255',
                        'type_contrat'=>'required|max:255',                        
                        'type_salaire'=>'required|max:255',
                        'date_debut_contrat'=>'required|max:255',
                        'date_fin_contrat'=>'nullable|max:255',
                        
                        'horaire_journalier'=>'required|numeric|min:1',
                        'horaire_hebdo'=>'required|numeric',
                        'horaire_mensuel'=>'required',  

                        'salaire'=>'nullable|numeric',
                        'categorie'=>'nullable|numeric',
                        'echelon'=>'nullable|max:5',

                        'mode_paiement'=>'nullable|max:255',
                        'nom_banque'=>'nullable|max:255',
                        'numero_compte'=>'nullable|max:255',
                        'rib'=>'nullable|max:255',

                        'cnps'=>'nullable|max:255',
                        'dipe'=>'nullable|max:255',
                        'note_interne'=>'nullable|max:255',                         
                    ]);  
                    if(auth()->user()->societe == "Administration"){ 
                                                                 
                        if($this->id){  
                            $verification = Entite ::where('id',$this->societe)->count(); 
                            if($verification > 0){
                                $entiteEnseigne = Entite ::where('id',$this->societe)->get(); 
                                $societe_id = $entiteEnseigne[0]->id;   
                                $nom_societe = $entiteEnseigne[0]->enseigne;   
                                $societe_mere = $entiteEnseigne[0]->societe_mere;   
                                $societe_mere_id = $entiteEnseigne[0]->societe_mere_id;   
                            }
                            else{
                                $societe_mere = 'Inconnue';
                            }  
                            
                            // recuperer le nom du Departement via son id 
                            $test_depart = Departement::where('societe_id',auth()->user()->societe_id)->where('id',$this->departement)->count();
                            if($test_depart > 0){
                                $depart = Departement::where('societe_id',auth()->user()->societe_id)->where('id',$this->departement)->get();
                                $nomDepart = $depart[0]->nom_departement;
                            }
                            else{
                                $nomDepart ='Non defini';
                                $this->departement = 0;
                            }                            

                            // recuperer le nom du poste de travail via son id
                            $test_postes = Poste_travail::where('societe_id',auth()->user()->societe_id)->where('id',$this->poste_travail)->count();
                            if($test_postes > 0){
                                $postes = Poste_travail::where('societe_id',auth()->user()->societe_id)->where('id',$this->poste_travail)->get();
                                $nomPostes = $postes[0]->nom_poste;
                            }
                            else{
                                $nomPostes ='Non defini';
                                $this->poste_travail = 0;
                            }
                            // calcul Horaire
                            if($this->horaire_journalier > 0){
                                $resultatHebdo = $this->horaire_journalier * 5;                
                                $horaire_hebdo = $resultatHebdo;
                                $horaire_mensuel = $horaire_hebdo * 52 / 12;
                            } 

                            Utilisateur::find($this->id)->update(['titre'=>$this->titre,'name'=>$this->nom,'telephone'=>$this->telephone,'salarie'=>$this->salarie,'sexe'=>$this->sexe,'nationalite'=>$this->nationalite,
                                        'societe'=>$nom_societe,'societe_id'=>$societe_id,'societe_mere'=>$societe_mere,'societe_mere_id'=>$societe_mere_id,'type_user'=>$this->role,'date_valide'=>$this->date_valide,'matricule'=>$this->matricule,'etat'=>$this->etat,
                                        'cni'=>$this->cni,'passeport'=>$this->passeport,'niu'=>$this->niu,'date_naissance'=>$this->date_naissance,'lieu_naissance'=>$this->lieu_naissance,'etat_civil'=>$this->etat_civil,
                                        'nbre_enfant'=>$this->nbre_enfant,'nom_conjoint'=>$this->nom_conjoint,'date_nais_conjoint'=>$this->date_nais_conjoint,'persone_contact_urgence'=>$this->persone_contact_urgence,
                                        'telephone_urgence'=>$this->telephone_urgence,'departement'=>$nomDepart,'departement_id'=>$this->departement,'poste_travail'=>$nomPostes,'poste_travail_id'=>$this->poste_travail,
                                        'lieu_travail'=>$this->lieu_travail,'adresse_travail'=>$this->adresse_travail,'responsable_rh'=>$this->responsable_rh,'manager'=>$this->manager,'validateur_conges'=>$this->validateur_conges,
                                        'type_employe'=>$this->type_employe,'type_contrat'=>$this->type_contrat,'type_salaire'=>$this->type_salaire,'date_debut_contrat'=>$this->date_debut_contrat,'date_fin_contrat'=>$this->date_fin_contrat,
                                        'horaire_journalier'=>$this->horaire_journalier,'horaire_hebdo'=>$horaire_hebdo,'horaire_mensuel'=>$horaire_mensuel,
                                        'salaire'=>$this->salaire,'categorie'=>$this->categorie, 'echelon'=>$this->echelon,'mode_paiement'=>$this->mode_paiement,'nom_banque'=>$this->nom_banque,'numero_compte'=>$this->numero_compte,'rib'=>$this->rib,
                                        'cnps'=>$this->cnps,'dipe'=>$this->dipe,'note_interne'=>$this->note_interne,'nom_user_modif'=>auth()->user()->email,]); 

                                        
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
                            $this->redirect('/detail_user?id='.$this->id.'&active=12&champ=1-1', navigate: true);
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
                        if($this->id){  

                            // recuperer le nom du Departement via son id 
                            $test_depart = Departement::where('societe_id',auth()->user()->societe_id)->where('id',$this->departement)->count();
                            if($test_depart > 0){
                                $depart = Departement::where('societe_id',auth()->user()->societe_id)->where('id',$this->departement)->get();
                                $nomDepart = $depart[0]->nom_departement;
                            }
                            else{
                                $nomDepart ='Non defini';
                                $this->departement = 0;
                            }                            

                            // recuperer le nom du poste de travail via son id
                            $test_postes = Poste_travail::where('societe_id',auth()->user()->societe_id)->where('id',$this->poste_travail)->count();
                            if($test_postes > 0){
                                $postes = Poste_travail::where('societe_id',auth()->user()->societe_id)->where('id',$this->poste_travail)->get();
                                $nomPostes = $postes[0]->nom_poste;
                            }
                            else{
                                $nomPostes ='Non defini';
                                $this->poste_travail = 0;
                            }
                            // calcul Horaire
                            if($this->horaire_journalier > 0){
                                $resultatHebdo = $this->horaire_journalier * 5;                
                                $horaire_hebdo = $resultatHebdo;
                                $horaire_mensuel = $horaire_hebdo * 52 / 12;
                            } 

                            Utilisateur::find($this->id)->update(['titre'=>$this->titre,'name'=>$this->nom,'telephone'=>$this->telephone,'salarie'=>$this->salarie,'sexe'=>$this->sexe,'nationalite'=>$this->nationalite,
                                        'type_user'=>$this->role,'date_valide'=>$this->date_valide,'matricule'=>$this->matricule,'etat'=>$this->etat,
                                        'cni'=>$this->cni,'passeport'=>$this->passeport,'niu'=>$this->niu,'date_naissance'=>$this->date_naissance,'lieu_naissance'=>$this->lieu_naissance,'etat_civil'=>$this->etat_civil,
                                        'nbre_enfant'=>$this->nbre_enfant,'nom_conjoint'=>$this->nom_conjoint,'date_nais_conjoint'=>$this->date_nais_conjoint,'persone_contact_urgence'=>$this->persone_contact_urgence,
                                        'telephone_urgence'=>$this->telephone_urgence,'departement'=>$nomDepart,'departement_id'=>$this->departement,'poste_travail'=>$nomPostes,'poste_travail_id'=>$this->poste_travail,
                                        'lieu_travail'=>$this->lieu_travail,'adresse_travail'=>$this->adresse_travail,'responsable_rh'=>$this->responsable_rh,'manager'=>$this->manager,'validateur_conges'=>$this->validateur_conges,
                                        'type_employe'=>$this->type_employe,'type_contrat'=>$this->type_contrat,'type_salaire'=>$this->type_salaire,'date_debut_contrat'=>$this->date_debut_contrat,'date_fin_contrat'=>$this->date_fin_contrat,
                                        'horaire_journalier'=>$this->horaire_journalier,'horaire_hebdo'=>$horaire_hebdo,'horaire_mensuel'=>$horaire_mensuel,
                                        'salaire'=>$this->salaire,'categorie'=>$this->categorie, 'echelon'=>$this->echelon,'mode_paiement'=>$this->mode_paiement,'nom_banque'=>$this->nom_banque,'numero_compte'=>$this->numero_compte,'rib'=>$this->rib,
                                        'cnps'=>$this->cnps,'dipe'=>$this->dipe,'note_interne'=>$this->note_interne,'nom_user_modif'=>auth()->user()->email,]);

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
                            $this->redirect('/detail_user?id='.$this->id.'&active=12&champ=1-1', navigate: true);
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
                }
                else{ 
                     
                    $this->validate([        
                        
                        'email'=>'required|email|max:255',
                        'titre'=>'required',
                        'nom'=>'required', 
                        'telephone'=>'required', 

                        'password_actuel'=>'required|min:8',
                        'password'=>'required|confirmed|min:8',
                        'password_confirmation'=>'required', 

                        'salarie'=>'required',  
                        'sexe'=>'required',
                        'nationalite'=>'required|max:255', 
                        'societe'=>'required',
                        'role'=>'required',                                  
                        'date_valide'=>'required|date',            
                        'matricule'=>'required', 
                        'etat'=>'required|numeric', 

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

                        'departement'=>'nullable',
                        'poste_travail'=>'nullable', 
                        'lieu_travail'=>'nullable|max:255', 
                        'adresse_travail'=>'nullable|max:255', 

                        'responsable_rh'=>'nullable|max:255',
                        'manager'=>'nullable|max:255',
                        'validateur_conges'=>'nullable|max:255',

                        'type_employe'=>'required|max:255',
                        'type_contrat'=>'required|max:255',
                        'type_salaire'=>'required|max:255',
                        'date_debut_contrat'=>'required|max:255',
                        'date_fin_contrat'=>'nullable|max:255',
                        
                        'horaire_journalier'=>'required|numeric|min:1',
                        'horaire_hebdo'=>'required|numeric',
                        'horaire_mensuel'=>'required',  

                        'salaire'=>'nullable|numeric',
                        'categorie'=>'nullable|numeric',
                        'echelon'=>'nullable|max:5',

                        'mode_paiement'=>'nullable|max:255',
                        'nom_banque'=>'nullable|max:255',
                        'numero_compte'=>'nullable|max:255', 
                        'rib'=>'nullable|max:255',

                        'cnps'=>'nullable|max:255',
                        'dipe'=>'nullable|max:255',

                        'note_interne'=>'nullable|max:255',                         
                    ]);   
                    if (Hash::check($this->password_actuel, $this->password_test)) {
                        if(auth()->user()->societe ==  "Administration"){ 
                                                  
                            if($this->id){  

                                $entiteEnseigne = Entite ::where('id',$this->societe)->get(); 
                                $societe_mere = $entiteEnseigne[0]->societe_mere; 
                                $societe_id = $entiteEnseigne[0]->societe_id;   
                                $nom_societe = $entiteEnseigne[0]->enseigne; 
                                $societe_mere_id = $entiteEnseigne[0]->societe_mere_id;

                                // recuperer le nom du Departement via son id 
                                $test_depart = Departement::where('societe_id',auth()->user()->societe_id)->where('id',$this->departement)->count();
                                if($test_depart > 0){
                                    $depart = Departement::where('societe_id',auth()->user()->societe_id)->where('id',$this->departement)->get();
                                    $nomDepart = $depart[0]->nom_departement;
                                }
                                else{
                                    $nomDepart ='Non defini';
                                    $this->departement = 0;
                                }                            

                                // recuperer le nom du poste de travail via son id
                                $test_postes = Poste_travail::where('societe_id',auth()->user()->societe_id)->where('id',$this->poste_travail)->count();
                                if($test_postes > 0){
                                    $postes = Poste_travail::where('societe_id',auth()->user()->societe_id)->where('id',$this->poste_travail)->get();
                                    $nomPostes = $postes[0]->nom_poste;
                                }
                                else{
                                    $nomPostes ='Non defini';
                                    $this->poste_travail = 0;
                                }
                                // calcul Horaire
                                if($this->horaire_journalier > 0){
                                    $resultatHebdo = $this->horaire_journalier * 5;                
                                    $horaire_hebdo = $resultatHebdo;
                                    $horaire_mensuel = $horaire_hebdo * 52 / 12;
                                }                                 

                                Utilisateur::find($this->id)->update(['titre'=>$this->titre,'name'=>$this->nom,'telephone'=>$this->telephone,'password'=>bcrypt($this->password),'salarie'=>$this->salarie,'sexe'=>$this->sexe,'nationalite'=>$this->nationalite,
                                        'societe'=>$nom_societe,'societe_id'=>$societe_id,'societe_mere'=>$societe_mere,'societe_mere_id'=>$societe_mere_id,'type_user'=>$this->role,'date_valide'=>$this->date_valide,'matricule'=>$this->matricule,'etat'=>$this->etat,
                                        'cni'=>$this->cni,'passeport'=>$this->passeport,'niu'=>$this->niu,'date_naissance'=>$this->date_naissance,'lieu_naissance'=>$this->lieu_naissance,'etat_civil'=>$this->etat_civil,
                                        'nbre_enfant'=>$this->nbre_enfant,'nom_conjoint'=>$this->nom_conjoint,'date_nais_conjoint'=>$this->date_nais_conjoint,'persone_contact_urgence'=>$this->persone_contact_urgence,
                                        'telephone_urgence'=>$this->telephone_urgence,'departement'=>$nomDepart,'departement_id'=>$this->departement,'poste_travail'=>$nomPostes,'poste_travail_id'=>$this->poste_travail,
                                        'lieu_travail'=>$this->lieu_travail,'adresse_travail'=>$this->adresse_travail,'responsable_rh'=>$this->responsable_rh,'manager'=>$this->manager,'validateur_conges'=>$this->validateur_conges,
                                        'type_employe'=>$this->type_employe,'type_contrat'=>$this->type_contrat,'type_salaire'=>$this->type_salaire,'date_debut_contrat'=>$this->date_debut_contrat,'date_fin_contrat'=>$this->date_fin_contrat,
                                        'horaire_journalier'=>$this->horaire_journalier,'horaire_hebdo'=>$horaire_hebdo,'horaire_mensuel'=>$horaire_mensuel,
                                        'salaire'=>$this->salaire,'categorie'=>$this->categorie, 'echelon'=>$this->echelon,'mode_paiement'=>$this->mode_paiement,'nom_banque'=>$this->nom_banque,'numero_compte'=>$this->numero_compte,'rib'=>$this->rib,
                                        'cnps'=>$this->cnps,'dipe'=>$this->dipe,'note_interne'=>$this->note_interne,'nom_user_modif'=>auth()->user()->email,]);
                                
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
                                $this->redirect('/detail_user?id='.$this->id.'&active=12&champ=1-1', navigate: true);
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
                                            
                            if($this->id){                   
                                
                                // recuperer le nom du Departement via son id 
                                $test_depart = Departement::where('societe_id',auth()->user()->societe_id)->where('id',$this->departement)->count();
                                if($test_depart > 0){
                                    $depart = Departement::where('societe_id',auth()->user()->societe_id)->where('id',$this->departement)->get();
                                    $nomDepart = $depart[0]->nom_departement;
                                }
                                else{
                                    $nomDepart ='Non defini';
                                    $this->departement = 0;
                                }                            

                                // recuperer le nom du poste de travail via son id
                                $test_postes = Poste_travail::where('societe_id',auth()->user()->societe_id)->where('id',$this->poste_travail)->count();
                                if($test_postes > 0){
                                    $postes = Poste_travail::where('societe_id',auth()->user()->societe_id)->where('id',$this->poste_travail)->get();
                                    $nomPostes = $postes[0]->nom_poste;
                                }
                                else{
                                    $nomPostes ='Non defini';
                                    $this->poste_travail = 0;
                                }
                                // calcul Horaire
                                if($this->horaire_journalier > 0){
                                    $resultatHebdo = $this->horaire_journalier * 5;                
                                    $horaire_hebdo = $resultatHebdo;
                                    $horaire_mensuel = $horaire_hebdo * 52 / 12;
                                }                                

                                Utilisateur::find($this->id)->update(['titre'=>$this->titre,'name'=>$this->nom,'telephone'=>$this->telephone,'password'=>bcrypt($this->password),'salarie'=>$this->salarie,'sexe'=>$this->sexe,'nationalite'=>$this->nationalite,
                                        'type_user'=>$this->role,'date_valide'=>$this->date_valide,'matricule'=>$this->matricule,'etat'=>$this->etat,
                                        'cni'=>$this->cni,'passeport'=>$this->passeport,'niu'=>$this->niu,'date_naissance'=>$this->date_naissance,'lieu_naissance'=>$this->lieu_naissance,'etat_civil'=>$this->etat_civil,
                                        'nbre_enfant'=>$this->nbre_enfant,'nom_conjoint'=>$this->nom_conjoint,'date_nais_conjoint'=>$this->date_nais_conjoint,'persone_contact_urgence'=>$this->persone_contact_urgence,
                                        'telephone_urgence'=>$this->telephone_urgence,'departement'=>$nomDepart,'departement_id'=>$this->departement,'poste_travail'=>$nomPostes,'poste_travail_id'=>$this->poste_travail,
                                        'lieu_travail'=>$this->lieu_travail,'adresse_travail'=>$this->adresse_travail,'responsable_rh'=>$this->responsable_rh,'manager'=>$this->manager,'validateur_conges'=>$this->validateur_conges,
                                        'type_employe'=>$this->type_employe,'type_contrat'=>$this->type_contrat,'type_salaire'=>$this->type_salaire,'date_debut_contrat'=>$this->date_debut_contrat,'date_fin_contrat'=>$this->date_fin_contrat,
                                        'horaire_journalier'=>$this->horaire_journalier,'horaire_hebdo'=>$horaire_hebdo,'horaire_mensuel'=>$horaire_mensuel,
                                        'salaire'=>$this->salaire,'categorie'=>$this->categorie, 'echelon'=>$this->echelon,'mode_paiement'=>$this->mode_paiement,'nom_banque'=>$this->nom_banque,'numero_compte'=>$this->numero_compte,'rib'=>$this->rib,
                                        'cnps'=>$this->cnps,'dipe'=>$this->dipe,'note_interne'=>$this->note_interne,'nom_user_modif'=>auth()->user()->email,]);
                                
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
                                $this->redirect('/detail_user?id='.$this->id.'&active=12&champ=1-1', navigate: true);
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
    // Modifier image du utilisateur
    public function valider_img(){
        $this->validate([                      
            'profil'=>'required|image|mimes:jpeg,jpg,png,gif|max:2048',          
        ]);
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_user;
            if($autoriser == 1){ 
            
                if($this->id){     
                    $path = $this->profil->store('img_profil','public'); 
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
                    $this->redirect('/detail_user?id='.$this->id, navigate: true);
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
        
        if(auth()->user()->societe == "Administration"){

            $testPrecedant = Utilisateur::where('id','<',$id)->orderBy('id','desc')->count();
            if($testPrecedant > 0){ 
                $precedant = Utilisateur::where('id','<',$id)->orderBy('id','desc')->first();        
                $previous = $precedant->id; 
                $this->redirect('/detail_user?id='.$previous.'&active=12&champ=1-1', navigate: true);                         
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
                $this->redirect('/detail_user?id='.$id.'&active=12&champ=1-1', navigate: true);
            } 
        }
        else{

            $testPrecedant = Utilisateur::where('societe_id',auth()->user()->societe_id)->where('id','<',$id)->orderBy('id','desc')->count();
            if($testPrecedant > 0){ 
                $precedant = Utilisateur::where('societe_id',auth()->user()->societe_id)->where('id','<',$id)->orderBy('id','desc')->first();        
                $previous = $precedant->id; 
                $this->redirect('/detail_user?id='.$previous.'&active=12&champ=1-1', navigate: true);                         
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
                $this->redirect('/detail_user?id='.$id.'&active=12&champ=1-1', navigate: true);
            } 
        }   
    }    
    public function suivant(int $id){    
        
        if(auth()->user()->societe == "Administration"){
        
            $testSuivant = Utilisateur::where('id','>',$id)->orderBy('id','asc')->count();
            if($testSuivant > 0){
                $suivant = Utilisateur::where('id','>',$id)->orderBy('id','asc')->first();
                $next = $suivant->id;             
                $this->redirect('/detail_user?id='.$next.'&active=12&champ=1-1', navigate: true);  
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
                $this->redirect('/detail_user?id='.$id.'&active=12&champ=1-1', navigate: true);  
            } 
        }
        else{

            $testSuivant = Utilisateur::where('societe_id',auth()->user()->societe_id)->where('id','>',$id)->orderBy('id','asc')->count();
            if($testSuivant > 0){
                $suivant = Utilisateur::where('societe_id',auth()->user()->societe_id)->where('id','>',$id)->orderBy('id','asc')->first();
                $next = $suivant->id;             
                $this->redirect('/detail_user?id='.$next.'&active=12&champ=1-1', navigate: true);  
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
                $this->redirect('/detail_user?id='.$id.'&active=12&champ=1-1', navigate: true); 
            } 
        }
    }
    public function EmailConfirmation(){
        try { 
            if($this->envoi_mail == 1){                              
                $user = Utilisateur::where('id', $this->id)->get();  
                $email = $user[0]->email;
                $name = $user[0]->name;
                $societe = $user[0]->societe;
                $societe_id = $user[0]->societe_id;
                $created_at = $user[0]->created_at;                       

                $entite_all = Entite::where('id',$societe_id)->get();
                $logo = $entite_all[0]->logo; 

                $date = date('d-m-Y H:i:s');           
                $body = [
                    'entite'=>$societe,
                    'date'=>$date,              
                    'email'=>$email,
                    'name'=>$name,
                    'created_at'=>date('d-m-Y H:i:s', strtotime($created_at)),
                    'lien'=>'http://wamsco-cloud.net/connexion?email='.$email.'&user='.$name.'&active=ok&champ=1-1',
                    'logo'=>'https://wamsco-cloud.net/storage/'.$logo,
                ];  
                Mail::to($email)->send(new ConfirmationMail($body));
                flash ('Un e-mail de confirmation a été envoyé à cette adresse » <strong>'.$this->email.'</strong> avec succès!')->success();
            } 
            else{
                $confirmer = 1;
                Utilisateur :: where('id',$this->id)->update(['confirmer'=>$confirmer]); 
                flash ('L\'utilisateur » <strong>'.$this->email.'</strong> a été confirmé avec succès!')->success();                
            }
            $this->redirect('/detail_user?id='.$this->id, navigate: true);
        }
        catch (\Symfony\Component\Mailer\Exception\TransportExceptionInterface $e) 
        {                                      
            flash ('Erreur lors de l\'envoi d\'email : cette adresse ('.$email.') semble invalide ou le domaine n\'existe pas !')->error();
            $this->redirect('/detail_user?id='.$this->id, navigate: true);
        } 
    }
}
