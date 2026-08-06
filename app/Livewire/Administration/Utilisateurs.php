<?php

namespace App\Livewire\Administration;

use Livewire\Component;
use Livewire\Attributes\Validate;  
use Livewire\WithPagination;
use App\Helpers\LogActivity;
use App\Models\LogActivity as LogActivityModel;
use App\Models\Entite;
use App\Models\DeviseTva;
use App\Models\Role;
use App\Models\Utilisateur;
use App\Models\Departement;
use App\Models\Poste_travail;
use Mail;
use App\Mail\ConfirmationMail;
use App\Models\Parametre;

class Utilisateurs extends Component
{
    protected $paginationTheme = 'bootstrap';
    use WithPagination;    

    public $confirmer;
    public $query;
    public $parPage = 20;
    public $parSociete;

    public $email;
    public $nom;
    public $telephone;    
    public $role; 
    public $password_test;
    public $password_actuel; 
    public $password; 
    public $password_confirmation;
    public $date_valide;
    public $profil;
    public $note_interne;
    public $societe; 
    public $sexe; 
    public $etat = 1;
    public $salarie = 0;
    public $telephone_urgence;
    public $cni;
    public $passeport;
    public $niu;
    public $nationalite = 'Cameroon';
    public $envoi_mail;
   
     // Parametre Rh
    public $horaire_journalier = 0;
    public $horaire_hebdo = 0;
    public $horaire_mensuel = 0;
    
    public $titre; 
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
    public $etat_civil = 'Célibataire';
    public $nbre_enfant = 0;
    public $nom_conjoint;
    public $date_nais_conjoint;
    public $persone_contact_urgence;
    public $type_employe  = 'Salarié';
    public $type_contrat;
    public $type_salaire  = 'Salaire Mensuel';
    public $date_debut_contrat;
    public $date_fin_contrat;
    public $responsable_rh;
    public $salaire = 0;
    public $categorie;
    public $echelon;
    public $cnps;
    public $dipe;
    public $matricule;
    public $mode_paiement = 'Espèces';
    public $nom_banque;
    public $numero_compte; 
    public $rib;
    // Fin Rh

    public $orderField = 'id'; 
    public $orderDirection = 'DESC'; 

    public function updatingQuery(){ // ceci pour faire revenir a la 1er page lors de la recherche dynamique par les mots dans le champs
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
        $this->date_valide = date('Y-m-d', strtotime('3 month'));
        $this->parSociete = auth()->user()->societe;
        $this->date_debut_contrat = date('Y-m-d');
        
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
    public function render(){

        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();
        $jourValid = $entite_mod[0]->validite_mod; 
        $mod_administration = $entite_mod[0]->mod_administration; 
        $soldeClient = $entite_mod[0]->solde;
        if($dateJour <= $jourValid){
            if($mod_administration == 1){
                $title = 'Utilisateurs | WamsCo';
                $module = 'Paramètres';
                $title_fils = 'Utilisateurs';
                $lien = 'utilisateurs?active=8&champ=8-1';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');      
                $dateJour = date('Y-m-d');
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');

                // $utilisateur = Utilisateur::where('id','>=',1)->where('name','like','%'.$this->query.'%')->where('societe','like','%'.$this->parSociete.'%')->orderBy('id','desc')->paginate($this->parPage);
                $utilisateur = Utilisateur::where('name','like','%'.$this->query.'%')->where('societe','like','%'.$this->parSociete.'%')->orderBy($this->orderField,$this->orderDirection)->paginate($this->parPage);
                $utilisateurCount = $utilisateur->count(); 

                $entite = Entite::orderBy('enseigne','asc')->get();
                $departe = Departement :: where('societe',auth()->user()->societe)->orderBy('nom_departement','asc')->get();  
                $posteTravail = Poste_travail :: where('societe',auth()->user()->societe)->orderBy('nom_poste','asc')->get(); 
                
                if(auth()->user()->societe == "Administration"){
                    $privillege = Role::where('societe', $this->societe)->orderBy('nom','asc')->get(); // ceci affiche en fonction de la societe choisie
                }
                else{
                    $privillege = Role::where('societe',auth()->user()->societe)->orderBy('nom','asc')->get();
                }

                $configCount = Parametre::where('societe',auth()->user()->societe)->limit(1)->count();
                if($configCount > 0){
                    $config = Parametre::where('societe',auth()->user()->societe)->limit(1)->get();
                    $this->envoi_mail = $config[0]->envoi_mail;
                }
                else{
                    $this->envoi_mail = 0;
                }

                $resultat = Utilisateur::where('societe',auth()->user()->societe)->get();  
                $nbreTotalUtilisateur = $resultat->count();     

                $derniereActivite = Utilisateur::where('societe',auth()->user()->societe)->latest('updated_at')->first();

                $utilisa = Utilisateur::where('societe',auth()->user()->societe)->orderBy('name','asc')->get();
                // gerer les heures employes : affiche directement en modifiant
                if($this->horaire_journalier > 0){
                    $resultatHebdo = $this->horaire_journalier * 5;                
                    $this->horaire_hebdo = $resultatHebdo;
                    $this->horaire_mensuel = number_format($this->horaire_hebdo * 52 / 12,2,',',' ');
                } 
                
                $page = 'Utilisateur'; // pour evenement lies
                $log = LogActivityModel::where('user_societe',auth()->user()->societe)->where('page', $page)->limit(50)->orderBy('id','desc')->get();
                $logCount = $log->count();

                if(auth()->user()->societe == "Administration"){                     
                    $entite = Entite::orderBy('enseigne','asc')->get();
                    $userSociete = Utilisateur::where('societe',$this->parSociete)->get();
                }
                else{
                    $entite = Entite::where('enseigne',auth()->user()->societe)->get();
                    $userSociete = Utilisateur::where('societe',auth()->user()->societe)->get();
                }                 
                $userDispo = $userSociete->count();

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
                return view('livewire.administration.users.utilisateurs',compact('title_fils','module','lien','dateJour','utilisateur','utilisateurCount','entite','departe','posteTravail','privillege','nbreTotalUtilisateur','derniereActivite',
                'utilisa','log','logCount','entite','userDispo'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
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
        // ceci teste pour verifier si l'user encours a un role dans la bd 
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_user;
            if($autoriser == 1){
                if(auth()->user()->societe ==  "Administration"){                     

                    $this->validate([ 
                        // 'profil'=>=>'required|image|mimes:jpeg,jpg,png,gif|max:2048', 
                        'email'=>'required|max:255|email|unique:utilisateurs,email,{$utilisateurs->id}',
                        'titre'=>'required',
                        'nom'=>'required', 
                        'telephone'=>'required', 
                        'salarie'=>'required',  
                        'sexe'=>'required',
                        'nationalite'=>'required|max:255', 
                        'societe'=>'required|max:255',
                        'password'=>'required|confirmed|min:8',
                        'password_confirmation'=>'required|max:255',
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

                    $verification = Entite ::where('enseigne',$this->societe)->count(); 
                    if($verification > 0){
                        $entiteEnseigne = Entite ::where('enseigne',$this->societe)->get(); 
                        $societe_mere = $entiteEnseigne[0]->societe_mere;   
                        $nbre_user_max = $entiteEnseigne[0]->nbre_user_max;   
                    }
                    else{
                        $societe_mere = 'Inconnue';
                        $nbre_user_max = 0;
                    }  
                    
                    $nbreUsers = Utilisateur::where('societe',$this->societe)->count();   
                    if($nbreUsers < $nbre_user_max){  
                        
                        try {
                                // recuperer le nom du Departement via son id 
                                $test_depart = Departement::where('societe',auth()->user()->societe)->where('id',$this->departement)->count();
                                if($test_depart > 0){
                                    $depart = Departement::where('societe',auth()->user()->societe)->where('id',$this->departement)->get();
                                    $nomDepart = $depart[0]->nom_departement;
                                }
                                else{
                                    $nomDepart ='Non defini';
                                    $this->departement = 0;
                                }                            

                                // recuperer le nom du poste de travail via son id
                                $test_postes = Poste_travail::where('societe',auth()->user()->societe)->where('id',$this->poste_travail)->count();
                                if($test_postes > 0){
                                    $postes = Poste_travail::where('societe',auth()->user()->societe)->where('id',$this->poste_travail)->get();
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

                                Utilisateur::create(['titre'=>$this->titre,'email'=>$this->email,'name'=>$this->nom,'telephone'=>$this->telephone,'password'=>bcrypt($this->password),'salarie'=>$this->salarie,'sexe'=>$this->sexe,'nationalite'=>$this->nationalite,
                                        'societe'=>$this->societe,'societe_mere'=>$societe_mere,'type_user'=>$this->role,'date_valide'=>$this->date_valide,'matricule'=>$this->matricule,'etat'=>$this->etat,
                                        'cni'=>$this->cni,'passeport'=>$this->passeport,'niu'=>$this->niu,'date_naissance'=>$this->date_naissance,'lieu_naissance'=>$this->lieu_naissance,'etat_civil'=>$this->etat_civil,
                                        'nbre_enfant'=>$this->nbre_enfant,'nom_conjoint'=>$this->nom_conjoint,'date_nais_conjoint'=>$this->date_nais_conjoint,'persone_contact_urgence'=>$this->persone_contact_urgence,
                                        'telephone_urgence'=>$this->telephone_urgence,'departement'=>$nomDepart,'departement_id'=>$this->departement,'poste_travail'=>$nomPostes,'poste_travail_id'=>$this->poste_travail,
                                        'lieu_travail'=>$this->lieu_travail,'adresse_travail'=>$this->adresse_travail,'responsable_rh'=>$this->responsable_rh,'manager'=>$this->manager,'validateur_conges'=>$this->validateur_conges,
                                        'type_employe'=>$this->type_employe,'type_contrat'=>$this->type_contrat,'type_salaire'=>$this->type_salaire,'date_debut_contrat'=>$this->date_debut_contrat,'date_fin_contrat'=>$this->date_fin_contrat,
                                        'horaire_journalier'=>$this->horaire_journalier,'horaire_hebdo'=>$horaire_hebdo,'horaire_mensuel'=>$horaire_mensuel,
                                        'salaire'=>$this->salaire,'categorie'=>$this->categorie, 'echelon'=>$this->echelon,'mode_paiement'=>$this->mode_paiement,'nom_banque'=>$this->nom_banque,'numero_compte'=>$this->numero_compte,'rib'=>$this->rib,
                                        'cnps'=>$this->cnps,'dipe'=>$this->dipe,'note_interne'=>$this->note_interne,'nom_user'=>auth()->user()->email,'user_id'=>auth()->user()->id]);
                                                                
                                
                                $nbre_users = Utilisateur::where('societe',$this->societe)->count(); 
                                Entite :: where('enseigne',$this->societe)->update(['nombre_users'=>$nbre_users]); 
                                
                                $dernier_id = Utilisateur::where('societe',$this->societe)->where('user_id',auth()->user()->id)->latest()->first()->id; 
                                                                       
                                // ************* debut envoi email ********************   
                                if($this->envoi_mail == 1){                           
                                    $user = Utilisateur::where('id', $dernier_id)->get();  
                                    $email = $user[0]->email;
                                    $name = $user[0]->name;
                                    $societe = $user[0]->societe;
                                    $created_at = $user[0]->created_at;                       

                                    $entite_all = Entite::where('enseigne',$this->societe)->get();
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
                                    flash ('L\'utilisateur (<strong>'.$this->nom.'</strong>) a été créé avec succès. Un e-mail de confirmation a été envoyé à l\'adresse indiquée !')->success();
                                }                              
                                else{
                                    $confirmer = 1;
                                    Utilisateur :: where('id',$dernier_id)->update(['confirmer'=>$confirmer]); 
                                    flash ('L\'utilisateur (<strong>'.$this->nom.'</strong>) a été créé et confirmer avec succès!')->success();
                                }
                                // ********** Fin envoi email ************** 

                                $id_activite = $dernier_id;
                                $page = 'Utilisateur';
                                LogActivity::addToLog('Utilisateur » '.$this->nom.' créé', $id_activite, $page); 
                                $this->dispatch('alert',                    
                                    title:'L\'utilisateur ('.$this->nom.') enregistré !',
                                    timer:5000,
                                    icon:'success',
                                    toast:true,
                                    showConfirmButton: false,
                                    position:'top-end',
                                );                                
                                $this->redirect('/detail_user?id='.$dernier_id, navigate: true);
                        }
                        catch (\Symfony\Component\Mailer\Exception\TransportExceptionInterface $e) 
                        {   
                            Utilisateur::where('id',$dernier_id)->delete();                            
                            flash ('Erreur lors de l\'envoi d\'email : cette adresse ('.$email.') semble invalide ou le domaine n\'existe pas !')->error();
                            $this->dispatch('alert',                    
                                title:'Erreur lors de l\'envoi d\'email : ('.$email.') semble invalide ou le domaine n\'existe pas !',
                                timer:10000,
                                icon:'error',
                                toast:true,
                                width:'4200px',
                                showConfirmButton: false,
                                position:'center',
                            );  
                        }  
                    }
                    else{
                        $this->dispatch('alert',                    
                            title:'Nombre d\'utilisateurs ['.$this->societe.'] atteint ('.$nbre_user_max.'), Contactez l\'administrateur!',
                            timer:10000,
                            icon:'warning',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        ); 
                    }      
                }
                else{

                    $this->validate([ 
                        // 'profil'=>=>'required|image|mimes:jpeg,jpg,png,gif|max:2048', 
                        'email'=>'required|max:255|email|unique:utilisateurs,email,{$utilisateurs->id}',
                        'titre'=>'required',
                        'nom'=>'required', 
                        'telephone'=>'required', 
                        'salarie'=>'required',  
                        'sexe'=>'required',
                        'nationalite'=>'required|max:255', 
                        'societe'=>'nullable|max:255',
                        'password'=>'required|confirmed|min:8',
                        'password_confirmation'=>'required|max:255',
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
                    
                    $verification = Entite ::where('enseigne',auth()->user()->societe)->count(); 
                    if($verification > 0){
                        $entiteEnseigne = Entite ::where('enseigne',auth()->user()->societe)->get(); 
                        $societe_mere = $entiteEnseigne[0]->societe_mere;   
                        $nbre_user_max = $entiteEnseigne[0]->nbre_user_max;   
                    }
                    else{
                        $societe_mere = 'Inconnue';
                        $nbre_user_max = 0;
                    }
                    
                    $nbreUsers = Utilisateur::where('societe',auth()->user()->societe)->count();   
                    if($nbreUsers < $nbre_user_max){                      
                        try {

                                // recuperer le nom du Departement via son id 
                                $test_depart = Departement::where('societe',auth()->user()->societe)->where('id',$this->departement)->count();
                                if($test_depart > 0){
                                    $depart = Departement::where('societe',auth()->user()->societe)->where('id',$this->departement)->get();
                                    $nomDepart = $depart[0]->nom_departement;
                                }
                                else{
                                    $nomDepart ='Non defini';
                                    $this->departement = 0;
                                }                            

                                // recuperer le nom du poste de travail via son id
                                $test_postes = Poste_travail::where('societe',auth()->user()->societe)->where('id',$this->poste_travail)->count();
                                if($test_postes > 0){
                                    $postes = Poste_travail::where('societe',auth()->user()->societe)->where('id',$this->poste_travail)->get();
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
                                                           
                                Utilisateur::create(['titre'=>$this->titre,'email'=>$this->email,'name'=>$this->nom,'telephone'=>$this->telephone,'password'=>bcrypt($this->password),'salarie'=>$this->salarie,'sexe'=>$this->sexe,'nationalite'=>$this->nationalite,
                                        'societe'=>auth()->user()->societe,'societe_mere'=>auth()->user()->societe_mere,'type_user'=>$this->role,'date_valide'=>$this->date_valide,'matricule'=>$this->matricule,'etat'=>$this->etat,
                                        'cni'=>$this->cni,'passeport'=>$this->passeport,'niu'=>$this->niu,'date_naissance'=>$this->date_naissance,'lieu_naissance'=>$this->lieu_naissance,'etat_civil'=>$this->etat_civil,
                                        'nbre_enfant'=>$this->nbre_enfant,'nom_conjoint'=>$this->nom_conjoint,'date_nais_conjoint'=>$this->date_nais_conjoint,'persone_contact_urgence'=>$this->persone_contact_urgence,
                                        'telephone_urgence'=>$this->telephone_urgence,'departement'=>$nomDepart,'departement_id'=>$this->departement,'poste_travail'=>$nomPostes,'poste_travail_id'=>$this->poste_travail,
                                        'lieu_travail'=>$this->lieu_travail,'adresse_travail'=>$this->adresse_travail,'responsable_rh'=>$this->responsable_rh,'manager'=>$this->manager,'validateur_conges'=>$this->validateur_conges,
                                        'type_employe'=>$this->type_employe,'type_contrat'=>$this->type_contrat,'type_salaire'=>$this->type_salaire,'date_debut_contrat'=>$this->date_debut_contrat,'date_fin_contrat'=>$this->date_fin_contrat,
                                        'horaire_journalier'=>$this->horaire_journalier,'horaire_hebdo'=>$horaire_hebdo,'horaire_mensuel'=>$horaire_mensuel,
                                        'salaire'=>$this->salaire,'categorie'=>$this->categorie, 'echelon'=>$this->echelon,'mode_paiement'=>$this->mode_paiement,'nom_banque'=>$this->nom_banque,'numero_compte'=>$this->numero_compte,'rib'=>$this->rib,
                                        'cnps'=>$this->cnps,'dipe'=>$this->dipe,'note_interne'=>$this->note_interne,'nom_user'=>auth()->user()->email,'user_id'=>auth()->user()->id]);
                           
                           
                            $nbre_users = Utilisateur::where('societe',auth()->user()->societe)->count();                    
                            Entite :: where('enseigne',auth()->user()->societe)->update(['nombre_users'=>$nbre_users]); 

                            $dernier_id = Utilisateur::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id;
                            
                            // ************* debut envoi email ******************** 
                            if($this->envoi_mail == 1){                              
                                $user = Utilisateur::where('id', $dernier_id)->get();  
                                $email = $user[0]->email;
                                $name = $user[0]->name;
                                $societe = $user[0]->societe;
                                $created_at = $user[0]->created_at;                       

                                $entite_all = Entite::where('enseigne',auth()->user()->societe)->get();
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
                                flash ('L\'utilisateur (<strong>'.$this->nom.'</strong>) a été créé avec succès. Un e-mail de confirmation a été envoyé à l\'adresse indiquée !')->success();
                            } 
                            else{
                                $confirmer = 1;
                                Utilisateur :: where('id',$dernier_id)->update(['confirmer'=>$confirmer]); 
                                flash ('L\'utilisateur (<strong>'.$this->nom.'</strong>) a été créé et confirmer avec succès!')->success();
                            }
                            // ********** Fin envoi email ************** 

                            $id_activite = $dernier_id;
                            $page = 'Utilisateur';
                            LogActivity::addToLog('Utilisateur » '.$this->nom.' créé', $id_activite, $page);
                            $this->dispatch('alert',                    
                                title:'L\'utilisateur ('.$this->nom.') enregistré !',
                                timer:3000,
                                icon:'success',
                                toast:true,
                                showConfirmButton: false,
                                position:'top-end',
                            );                             
                            flash ('L\'utilisateur (<strong>'.$this->nom.'</strong>) a été créé avec succès. Un e-mail de confirmation a été envoyé à l\'adresse indiquée !')->success();                      
                            $this->redirect('/detail_user?id='.$dernier_id, navigate: true);
                        }
                        catch (\Symfony\Component\Mailer\Exception\TransportExceptionInterface $e) 
                        {   
                            Utilisateur::where('id',$dernier_id)->delete();                            
                            flash ('Erreur lors de l\'envoi d\'email : cette adresse ('.$email.') semble invalide ou le domaine n\'existe pas !')->error();
                        }
                    }
                    else{
                        $this->dispatch('alert',                    
                            title:'Désolé, nombre d\'utilisateur ('.$nbre_user_max.') souscrit atteint. <br> Veuillez contacter votre fournisseur pour augmentation!',
                            timer:10000,
                            icon:'warning',
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
    public function changeEtat(int $id, int $etat){ 
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_user;
            if($autoriser == 1){          
                if($etat == 1){
                    $ferme = 0;
                    Utilisateur::find($id)->update(['etat'=>$ferme,'nom_user'=>auth()->user()->email,'user_id'=>auth()->user()->id]);
                    // Stock::where('id_produit',$id)->update(['etat'=>$ferme,]);  
                    $id_activite = $id;
                    $page = 'Utilisateur';
                    LogActivity::addToLog('Etat utilisateur (Fermé)', $id_activite, $page); 
                    $this->dispatch('alert',                    
                        title:'Utilisateur désactivé avec succès!',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );  
                }
                elseif($etat == 0){
                    $ouvert = 1;
                    Utilisateur::find($id)->update(['etat'=>$ouvert,'nom_user'=>auth()->user()->email,'user_id'=>auth()->user()->id]); 
                    // Stock::where('id_produit',$id)->update(['etat'=>$ouvert,]);
                    $id_activite = $id;
                    $page = 'Utilisateur';
                    LogActivity::addToLog('Etat utilisateur (Ouvert)', $id_activite, $page);  
                    $this->dispatch('alert',                    
                        title:'Utilisateur activé avec succès!',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );    
                }
                else{
                    $this->dispatch('alert',                    
                        title:'Vous ne pouvez modifier cet état ici!',
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
    public function confirmerDelete($id){  
        $this->confirmer = $id;      
    }    
    public function supprimer($id){ 

        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->supprimer_user;
            if($autoriser == 1){   
                if($id){
                    
                    $verifier_society =  Utilisateur::where('id',$id)->count();
                    if($verifier_society > 0){

                        $society =  Utilisateur::where('id',$id)->first();
                        $societe_user = $society->societe;
                        Utilisateur::where('id',$id)->delete(); 
                        
                        $users = Utilisateur::where('societe',$societe_user)->count(); 
                        Entite::where('enseigne',$societe_user)->update(['nombre_users'=>$users]);
                        
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
}
