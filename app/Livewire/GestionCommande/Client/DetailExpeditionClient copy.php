<?php

namespace App\Livewire\GestionCommande\Client;

use Livewire\Component;
use Livewire\Attributes\Validate; 
use App\Helpers\LogActivity;
use App\Models\LogActivity as LogActivityModel;
use Livewire\WithPagination;
use App\Models\Role;
use App\Models\Entrepot;
use App\Models\Parametre;
use App\Models\Stock;
use App\Models\Mouvement;
use App\Models\Produit;
use App\Models\Tier;
use App\Models\Utilisateur;
use App\Models\DeviseTva;
use App\Models\Entite;
use App\Models\CompteBancaire;
use App\Models\factureClientEntete;
use App\Models\factureClientLigne;
use App\Models\Reglement;
use App\Models\CommandeClientEntete;
use App\Models\ExpeditionClientEntete;
use App\Models\ExpeditionClientLigne;
use App\Models\ExpeditionClientLignePartiel;

class DetailExpeditionClient extends Component
{
    public $id; 
    public $ids; 
    public $fact_entete_id; // ceci est id facture entete copier
    public $cmd_entete_id; // ceci est id commande entete copier
    
    public $client;
    public $reference;    
    public $date_commande;
    public $date_facturation;
    public $date_echeance;
    public $methode_expedition;
    public $numero_suivi;
    public $note;
    public $entrepot_expedition;
    public $nonEntrepot;
    public $idEntrepot;
    public $codeExpedition;
    public $code_commande;
    public $montant_ttc;
    
    public $etat;
    public $etat_cmd;    
    public $etat_facture;    
    public $created_at;
    public $updated_at;
    
    public $confirmer;

    public $choix_produit;

    // pour Modal
    public $idy; // recupere id expedition ligne dans le modal
    public $reste_expedier;
    public $nom_produit;
    public $quantite_cmd;
    public $quantiteExpedie;
    public $resteExpedier;
    
    public $affiche = 0;
    public function afficherDateLivraison(int $idz){
       $this->affiche = $idz;
    } 
    public $ouvre = 0;
    public function afficherMethodeExpedition(int $idx){
       $this->ouvre = $idx;
    } 
    public $open = 0;
    public function afficherNumeroSuivi(int $idf){
       $this->open = $idf;
    } 
    public $sortir = 0;
    public function afficherNote(int $idg){
       $this->sortir = $idg;
    } 
    public $ouverture = 0;
    public function ajoutLigne(int $idd){
        $this->ouverture = $idd;
    } 
    public function onDataAjout(){
        $this->reset('ouverture');
    } 
    // public $laisse = 0;
    // public function afficherEntrepotExpedition(int $idt){
    //    $this->laisse = $idt;
    // } 
    
    public $orderField = 'id'; 
    public $orderDirection = 'ASC'; 
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
            $autoriser = $role[0]->consulter_expedition;
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
        $mod_cmd = $entite_mod[0]->mod_cmd; 
        $soldeClient = $entite_mod[0]->solde;
        if($dateJour <= $jourValid){
            if($mod_cmd == 1){
                $title = 'Expédition client | WamsCo';
                $module = 'Gestion commande';
                $title_fils = 'Expédition';
                $lien = 'listing_expedition_clt';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');      
                $dateJour = date('Y-m-d');
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');

                $this->id = request('id'); // id entete facture
                $this->ref_exp = request('ref'); // reference facture
                //     // ceci au chargement de la page
                $test_facture = ExpeditionClientEntete::where('societe',auth()->user()->societe)->where('id',$this->id)->count(); 
                if($test_facture > 0){
                    $compte = ExpeditionClientEntete::where('societe',auth()->user()->societe)->where('id',$this->id)->first();               
                    $this->ids = $compte->id;
                    $this->fact_entete_id = $compte->id_facture_client_entete;
                    $this->codeExpedition = $compte->code_expedition;
                    $this->client = $compte->nom_client;
                    $this->reference = $compte->code_facture; // reference facture
                    $this->code_commande = $compte->code_commande; // reference commande
                    $this->cmd_entete_id = $compte->id_commande_client_entete; // id entete commande                    
                    $this->date_commande = $compte->date_commande;
                    $this->date_facturation = $compte->date_facturation;
                    $this->date_echeance = $compte->date_echeance;                    
                    $this->mode_reglement = $compte->mode_reglement; 
                    $this->compte_bancaire = $compte->id_compte_bancaire;
                    $this->note = $compte->note;
                    $this->etat = $compte->etat;
                    // $this->montant_recu = $compte->montant_recu;
                    $this->montant_ttc = $compte->montant_ttc;
                    $this->methode_expedition = $compte->methode_expedition;
                    $this->numero_suivi = $compte->numero_suivi;
                    $this->created_at = $compte->created_at;
                    $this->etat_cmd = $compte->etat_cmd;
                    $this->etat_facture = $compte->etat_facture;
                    $this->updated_at = $compte->updated_at;
                    $id_entrepot = $compte->id_entrepot;

                    $essai = Entrepot::where('societe',auth()->user()->societe)->where('id',$id_entrepot)->count();   
                    if($essai > 0){
                        $Entrepo = Entrepot::where('societe',auth()->user()->societe)->where('id',$id_entrepot)->first();               
                        $this->entrepot_expedition = $Entrepo->id;
                        $this->nonEntrepot = $Entrepo->nom;
                        $this->idEntrepot = $Entrepo->id;
                    }        
                    else{
                        $this->entrepot_expedition = '';
                        $this->nonEntrepot = '';
                        $this->idEntrepot = '';
                    }
                }  
                $banque = CompteBancaire :: where('societe',auth()->user()->societe)->get();  
                
                if(!empty($this->fact_entete_id)){
                
                    $expeClient_ligne = ExpeditionClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->fact_entete_id)->orderBy($this->orderField, $this->orderDirection)->get();
                    $expeClientLigneCount = $expeClient_ligne->count();

                    $montantHT = ExpeditionClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->fact_entete_id)->sum('montant_ht');
                    $montantTTC = ExpeditionClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->fact_entete_id)->sum('montant_ttc');
                    $montantRemise = ExpeditionClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->fact_entete_id)->sum('montant_remise');
                    $montantTva = ExpeditionClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->fact_entete_id)->sum('montant_tva');
                    $montantPrecompte = ExpeditionClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->fact_entete_id)->sum('montant_precompte');
                }
                else{
                    
                    $expeClient_ligne = ExpeditionClientLigne::where('societe',auth()->user()->societe)->where('id_commande_client_entete',$this->cmd_entete_id)->orderBy($this->orderField, $this->orderDirection)->get();
                    $expeClientLigneCount = $expeClient_ligne->count();

                    $montantHT = ExpeditionClientLigne::where('societe',auth()->user()->societe)->where('id_commande_client_entete',$this->cmd_entete_id)->sum('montant_ht');
                    $montantTTC = ExpeditionClientLigne::where('societe',auth()->user()->societe)->where('id_commande_client_entete',$this->cmd_entete_id)->sum('montant_ttc');
                    $montantRemise = ExpeditionClientLigne::where('societe',auth()->user()->societe)->where('id_commande_client_entete',$this->cmd_entete_id)->sum('montant_remise');
                    $montantTva = ExpeditionClientLigne::where('societe',auth()->user()->societe)->where('id_commande_client_entete',$this->cmd_entete_id)->sum('montant_tva');
                    $montantPrecompte = ExpeditionClientLigne::where('societe',auth()->user()->societe)->where('id_commande_client_entete',$this->cmd_entete_id)->sum('montant_precompte');
                }
            
                // Parametre
                $config = Parametre::where('societe',auth()->user()->societe)->limit(1)->get();
                $id_entrepot = $config[0]->id_entrepot_fctclt;

                $produit = Stock::where('societe',auth()->user()->societe)->where('id_entrepot',$id_entrepot)->get();
                $stockProd = Stock::where('societe',auth()->user()->societe)->get();
                $ListeEntrepot = Entrepot::where('societe',auth()->user()->societe)->get();
                
                $testChoix = Stock::where('societe',auth()->user()->societe)->where('id',$this->choix_produit)->count();
                if($testChoix > 0){
                    // ceci permet d'afficher la quantite entrepot origine
                    $choixProd = Stock::where('societe',auth()->user()->societe)->where('id',$this->choix_produit)->get();
                    $this->id_produit = $choixProd[0]->id_produit;                

                    // avoir le prix_vente_min 
                    $prod = Produit::where('societe',auth()->user()->societe)->where('id',$this->id_produit)->first();
                    $this->prix_vente_min = $prod->prix_vente_min;                
                }

                $page = 'ExpeditionClient'; // Pour evenement lie
                $log = LogActivityModel::where('user_societe',auth()->user()->societe)->where('id_activite', $this->ids)->where('page', $page)->limit(22)->orderBy('id','desc')->get();
                $logCount = $log->count();

                $taxe = DeviseTva::where('societe',auth()->user()->societe)->orderBy('taux_tva','asc')->get();

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
                return view('livewire.gestion-commande.client.detail-expedition-client',compact('title_fils','module','lien','dateJour','banque','expeClient_ligne','expeClientLigneCount','produit','stockProd','ListeEntrepot','taxe','log','logCount','montantHT','montantTTC','montantRemise','montantTva','montantPrecompte',))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
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
    public function changeDateLivraison(){  
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_expedition;
            if($autoriser == 1){
                $this->validate([            
                    'date_echeance'=>'required|date',            
                ]);            
                ExpeditionClientEntete::where('societe',auth()->user()->societe)->where('id',$this->ids)->update(['date_echeance'=>$this->date_echeance,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                $id_activite = $this->ids;
                $page = 'ExpeditionClient';
                LogActivity::addToLog('Date livraison (expédition) modifiée', $id_activite, $page); 
                $this->dispatch('alert',                    
                    title:'Date prévue livraison <br> ('.date('d-m-Y', strtotime($this->date_echeance)).') modifiée!',
                    timer:5000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                );
                $this->affiche = 0;
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
    public function changeMethodeExpedition(){  
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_expedition;
            if($autoriser == 1){
                $this->validate([            
                    'methode_expedition'=>'max:255',            
                ]);                
                ExpeditionClientEntete::where('societe',auth()->user()->societe)->where('id',$this->ids)->update(['methode_expedition'=>$this->methode_expedition,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                 
                $id_activite = $this->ids;
                $page = 'ExpeditionClient';
                LogActivity::addToLog('Méthode expédition modifiée', $id_activite, $page); 
                $this->dispatch('alert',                    
                    title:'Méthode expédition <br> ('.$this->methode_expedition.') modifiée!',
                    timer:5000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                );
                $this->ouvre = 0;
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
    public function changeNumeroSuivi(){  
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_expedition;
            if($autoriser == 1){
                $this->validate([            
                    'numero_suivi'=>'max:255',            
                ]);        
                ExpeditionClientEntete::where('societe',auth()->user()->societe)->where('id',$this->ids)->update(['numero_suivi'=>$this->numero_suivi,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                $id_activite = $this->ids;
                $page = 'ExpeditionClient';
                LogActivity::addToLog('Numéro de suivi (expédition) modifiée', $id_activite, $page); 
                $this->dispatch('alert',                    
                    title:'Numéro de suivi <br> ('.$this->numero_suivi.') modifiée!',
                    timer:5000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                );
                $this->open = 0;
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
    public function changeNote(){  
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_expedition;
            if($autoriser == 1){
                $this->validate([            
                    'note'=>'max:255',            
                ]);        
                ExpeditionClientEntete::where('societe',auth()->user()->societe)->where('id',$this->ids)->update(['note'=>$this->note,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                $id_activite = $this->ids;
                $page = 'ExpeditionClient';
                LogActivity::addToLog('Note (expédition) modifiée', $id_activite, $page); 
                $this->dispatch('alert',                    
                    title:'Note modifiée!',
                    timer:5000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                );
                $this->sortir = 0;
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
    public function expedier(){ 
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_expedition;
            if($autoriser == 1){       
                if(!empty($this->fact_entete_id)){
                    $test_expedition = ExpeditionClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->fact_entete_id)->count();     
                }
                else{
                    $test_expedition = ExpeditionClientLigne::where('societe',auth()->user()->societe)->where('id_commande_client_entete',$this->cmd_entete_id)->count();     
                }

                if($test_expedition > 0){   

                    $test_etat = ExpeditionClientEntete::where('societe',auth()->user()->societe)->where('id',$this->ids)->first(); 
                    $etat = $test_etat->etat;           

                    if($etat == 'Brouillon'){ 
                        
                        $etat = 'Clôturée';
                        if(!empty($this->fact_entete_id)){
                            $ligneExpedition = ExpeditionClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->fact_entete_id)->get();        
                        }
                        else{
                            $ligneExpedition = ExpeditionClientLigne::where('societe',auth()->user()->societe)->where('id_commande_client_entete',$this->cmd_entete_id)->get();        
                        }
                            
                        foreach($ligneExpedition as $ligneExpeditions){
                                
                            $id_exp = $ligneExpeditions->id;            
                            $quantite_stock = $ligneExpeditions->quantite;            
                            $id_produit = $ligneExpeditions->id_produit;
                            $code_expedition = $ligneExpeditions->code_expedition;
                            $quantite_expediee = $ligneExpeditions->quantite_expediee;
                            $id_entrepot = $ligneExpeditions->id_entrepot;
                            $id_fact_clt_entete = $ligneExpeditions->id_facture_client_entete;
                            $id_cmd_clt_entete = $ligneExpeditions->id_commande_client_entete;
                            
                            $stockTrouver = Stock::where('societe',auth()->user()->societe)->where('id_entrepot',$id_entrepot)->where('id_produit',$id_produit)->first();
                            // $stockTrouver = Stock::find($id_stock_destinataire);
                            $nom_produit = $stockTrouver->nom_produit;
                            // $id_produit = $stockTrouver->id_produit;
                            $reference = $stockTrouver->reference;
                            $qteSockFinal = $stockTrouver->quantite - $quantite_stock;
                            $valorisation_achat_total = $stockTrouver->prix_moyen_pondere_achat * $qteSockFinal;
                            $valeur_vente_total = $stockTrouver->prix_vente_unitaire * $qteSockFinal;
                            $reste_a_expedier = 0;
                        
                            Stock::where('societe',auth()->user()->societe)->where('id_entrepot',$id_entrepot)->where('id_produit',$id_produit)->update(['quantite'=>$qteSockFinal,'valorisation_achat_total'=>$valorisation_achat_total,'valeur_vente_total'=>$valeur_vente_total,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);   
                            ExpeditionClientLigne::where('id',$id_exp)->update(['quantite_expediee'=>$quantite_stock,'reste_a_expedier'=>$reste_a_expedier,'etat'=>$etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);   
                        
                            $Entrepo = Entrepot::where('societe',auth()->user()->societe)->where('id',$id_entrepot)->first();                    
                            $nom_entrepot = $Entrepo->nom; 

                            $libele_mouvement = 'Expédition';           
                            $code_mouvement = date('YmdHis');
                            $statut = 'EXP';
                            
                            Mouvement::create(['id_entrepot'=>$id_entrepot,'nom_produit'=>$nom_produit,'id_produit'=>$id_produit,'reference'=>$reference,'quantite'=>-$quantite_stock,'libele_mouvement'=>$libele_mouvement,
                            'code_mouvement'=>$code_mouvement,'statut'=>$statut,'origine'=>$code_expedition,'id_expedition'=>$this->ids,'entrepot'=>$nom_entrepot,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                            
                            ExpeditionClientEntete::where('societe',auth()->user()->societe)->where('id',$this->ids)->update(['etat'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                                                
                            if(!empty($this->fact_entete_id)){ 
                                factureClientEntete::where('societe',auth()->user()->societe)->where('id',$id_fact_clt_entete)->update(['etat_expedi'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                                CommandeClientEntete::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$id_fact_clt_entete)->update(['etat_expedi'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                            }
                            else{
                                CommandeClientEntete::where('societe',auth()->user()->societe)->where('id',$id_cmd_clt_entete)->update(['etat_expedi'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                                factureClientEntete::where('societe',auth()->user()->societe)->where('id_commande_client_entete',$id_cmd_clt_entete)->update(['etat_expedi'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                            }                    
                            
                            $id_activite = $this->ids;
                            $page = 'ExpeditionClient';
                            LogActivity::addToLog('Expédition cloturée', $id_activite, $page); 
                            $this->dispatch('alert',                    
                                title:'Expédition effectuée et cloturée avec succes!',
                                timer:5000,
                                icon:'success',
                                toast:true,
                                showConfirmButton: false,
                                position:'top-end',
                            );
                            $this->redirect('/detail_expedition_clt?id='.$this->ids.'&ref='.$this->reference.'&active=6&champ=1-1&choix=3', navigate: true);
                        }
                    }
                    else{
                        
                        $this->dispatch('alert',                    
                            title:'Cette expédition a déja été cloturée!',
                            timer:5000,
                            icon:'warning',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        );
                    }
                }
                else{
                    $this->dispatch('alert',                    
                        title:'Désolé, pas de produit à expédier!',
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
    public function precedant(){ 
        $testPrecedant = ExpeditionClientEntete::where('societe',auth()->user()->societe)->where('id','<',$this->ids)->orderBy('id','desc')->count();
        if($testPrecedant > 0){ 
            $precedant = ExpeditionClientEntete::where('societe',auth()->user()->societe)->where('id','<',$this->ids)->orderBy('id','desc')->first();        
            $previous = $precedant->id; 
            $this->redirect('/detail_expedition_clt?id='.$previous.'&ref='.$this->reference.'&active=6&champ=1-1&choix=3', navigate: true);              
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
        }    
    }    
    public function suivant(){    
        
        $testSuivant = ExpeditionClientEntete::where('societe',auth()->user()->societe)->where('id','>',$this->ids)->orderBy('id','asc')->count();
        if($testSuivant > 0){
            $suivant = ExpeditionClientEntete::where('societe',auth()->user()->societe)->where('id','>',$this->ids)->orderBy('id','asc')->first();
            $next = $suivant->id;             
            $this->redirect('/detail_expedition_clt?id='.$next.'&ref='.$this->reference.'&active=6&champ=1-1&choix=3', navigate: true);                     
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
        } 
    }
    public function editer(int $id){
        $affiche = ExpeditionClientLigne::where('societe',auth()->user()->societe)->where('id',$id)->first();               
        $this->idy = $affiche->id;
        $this->nom_produit = $affiche->produit;
        $this->quantite_cmd = $affiche->quantite;
        $this->quantiteExpedie = $affiche->quantite_expediee;
        $this->resteExpedier = $affiche->reste_a_expedier;
    }  
    // public function expeditionPartiel(){
    //     $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
    //     if($test > 0){ 
    //         $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
    //         $autoriser = $role[0]->creer_expedition;
    //         if($autoriser == 1){   
    //             $this->validate([            
    //                 'reste_expedier'=>'required|numeric',            
    //             ]);        
    //             $test_etat = ExpeditionClientEntete::where('societe',auth()->user()->societe)->where('id',$this->ids)->first(); 
    //             $etat = $test_etat->etat;   
    //             if($etat == 'Brouillon' || $etat == 'Partiel'){ 

    //                 if($this->reste_expedier <= $this->resteExpedier){                                
                        
    //                     if(!empty($this->fact_entete_id)){ 
    //                         $ligneExpedition = ExpeditionClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->fact_entete_id)->where('id',$this->idy)->get(); 
    //                     }
    //                     else{
    //                         $ligneExpedition = ExpeditionClientLigne::where('societe',auth()->user()->societe)->where('id_commande_client_entete',$this->cmd_entete_id)->where('id',$this->idy)->get(); 
    //                     }      
    //                     foreach($ligneExpedition as $ligneExpeditions){ 
                                
    //                         $id_exp = $ligneExpeditions->id;            
    //                         // $quantite_stock = $ligneExpeditions->quantite;            
    //                         $quantite_stock = $this->reste_expedier;            
    //                         $id_produit = $ligneExpeditions->id_produit;
    //                         $code_expedition = $ligneExpeditions->code_expedition;
    //                         $quantite_expediee = $ligneExpeditions->quantite_expediee + $this->reste_expedier;
    //                         $resteAexpedier = $ligneExpeditions->reste_a_expedier;                    
    //                         $id_entrepot = $ligneExpeditions->id_entrepot;
    //                         $id_fact_clt_entete = $ligneExpeditions->id_facture_client_entete;
    //                         $id_cmd_clt_entete = $ligneExpeditions->id_commande_client_entete;                            

    //                         $stockTrouver = Stock::where('societe',auth()->user()->societe)->where('id_entrepot',$id_entrepot)->where('id_produit',$id_produit)->first();
    //                         $nom_produit = $stockTrouver->nom_produit;
    //                         // $id_produit = $stockTrouver->id_produit;
    //                         $reference = $stockTrouver->reference;
    //                         $qteSockFinal = $stockTrouver->quantite - $quantite_stock;
    //                         $valorisation_achat_total = $stockTrouver->prix_moyen_pondere_achat * $qteSockFinal;
    //                         $valeur_vente_total = $stockTrouver->prix_vente_unitaire * $qteSockFinal;
    //                         $reste_a_expedier = $resteAexpedier - $this->reste_expedier;
                        
    //                         if($reste_a_expedier == 0 ){
    //                             $etat = 'Clôturée'; 
    //                         }
    //                         else{
    //                             $etat = 'Partiel'; 
    //                         }
    //                         Stock::where('societe',auth()->user()->societe)->where('id_entrepot',$id_entrepot)->where('id_produit',$id_produit)->update(['quantite'=>$qteSockFinal,'valorisation_achat_total'=>$valorisation_achat_total,'valeur_vente_total'=>$valeur_vente_total,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);   
    //                         ExpeditionClientLigne::where('id',$id_exp)->update(['quantite_expediee'=>$quantite_expediee,'reste_a_expedier'=>$reste_a_expedier,'etat'=>$etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);   
                        
    //                         $Entrepo = Entrepot::where('societe',auth()->user()->societe)->where('id',$id_entrepot)->first();                    
    //                         $nom_entrepot = $Entrepo->nom; 

    //                         $libele_mouvement = 'Expédition';           
    //                         $code_mouvement = date('YmdHis');
    //                         $statut = 'EXP';
                            
    //                         Mouvement::create(['id_entrepot'=>$id_entrepot,'nom_produit'=>$nom_produit,'id_produit'=>$id_produit,'reference'=>$reference,'quantite'=>-$quantite_stock,'libele_mouvement'=>$libele_mouvement,
    //                         'code_mouvement'=>$code_mouvement,'statut'=>$statut,'origine'=>$code_expedition,'id_expedition'=>$this->ids,'entrepot'=>$nom_entrepot,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                            
    //                         if(!empty($this->fact_entete_id)){
    //                             $sommeResteAexpedier = ExpeditionClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->fact_entete_id)->sum('reste_a_expedier');
    //                         }
    //                         else{
    //                             $sommeResteAexpedier = ExpeditionClientLigne::where('societe',auth()->user()->societe)->where('id_commande_client_entete',$this->cmd_entete_id)->sum('reste_a_expedier'); 
    //                         } 
                                
    //                         if($sommeResteAexpedier == 0){
    //                             $etats = 'Clôturée';                        
    //                         }
    //                         else{   
    //                             $etats = 'Partiel';                  
    //                         }

    //                         ExpeditionClientEntete::where('societe',auth()->user()->societe)->where('id',$this->ids)->update(['etat'=>$etats,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                                            

    //                         if(!empty($this->fact_entete_id)){ 
    //                             factureClientEntete::where('societe',auth()->user()->societe)->where('id',$id_fact_clt_entete)->update(['etat_expedi'=>$etats,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
    //                             CommandeClientEntete::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$id_fact_clt_entete)->update(['etat_expedi'=>$etats,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
    //                         }
    //                         else{
    //                             CommandeClientEntete::where('societe',auth()->user()->societe)->where('id',$id_cmd_clt_entete)->update(['etat_expedi'=>$etats,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
    //                             factureClientEntete::where('societe',auth()->user()->societe)->where('id_commande_client_entete',$id_cmd_clt_entete)->update(['etat_expedi'=>$etats,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
    //                         }

    //                         // Expedition ligne partiel
    //                         $test_ligPart = ExpeditionClientLignePartiel::where('societe',auth()->user()->societe)->where('etat','Brouillon')->where('id_produit',$id_produit)->count(); 
    //                         if($test_ligPart > 0){
    //                             ExpeditionClientLignePartiel::where('societe',auth()->user()->societe)->where('etat','Brouillon')->where('id_produit',$id_produit)->delete();
    //                         }                            
    //                         ExpeditionClientLignePartiel::create([ 
    //                             'id_expedition_client_entete'=>$this->ids,   // id                   
    //                             'code_expedition'=>$code_expedition,  // code_expedition                   
    //                             'code_facture'=>$ligneExpeditions->code_facture,
    //                             'id_facture_client_entete'=>$ligneExpeditions->id_facture_client_entete,
    //                             'code_commande'=>$ligneExpeditions->code_commande,
    //                             'id_commande_client_entete'=>$ligneExpeditions->id_commande_client_entete,
    //                             'produit'=>$ligneExpeditions->produit,
    //                             'id_produit'=>$ligneExpeditions->id_produit,
    //                             'prix_achat'=>$ligneExpeditions->prix_achat,
    //                             'prix_vente'=>$ligneExpeditions->prix_vente,
    //                             'quantite'=>$ligneExpeditions->quantite,
    //                             'quantite_expediee'=>$quantite_expediee, // quantite_expediee
    //                             'reste_a_expedier'=>$reste_a_expedier, // reste_a_expedier
    //                             'remise'=>$ligneExpeditions->remise,
    //                             'montant_remise'=>$ligneExpeditions->montant_remise,
    //                             'tva'=>$ligneExpeditions->tva,
    //                             'montant_tva'=>$ligneExpeditions->montant_tva,
    //                             'precompte'=>$ligneExpeditions->precompte,
    //                             'montant_precompte'=>$ligneExpeditions->montant_precompte,
    //                             'montant_ht'=>$ligneExpeditions->montant_ht,
    //                             'montant_ttc'=>$ligneExpeditions->montant_ttc,
    //                             'marge'=>$ligneExpeditions->marge,
    //                             'id_entrepot'=>$ligneExpeditions->id_entrepot,
    //                             'nom_client'=>$ligneExpeditions->nom_client,
    //                             'id_client'=>$ligneExpeditions->id_client,
    //                             'offrir'=>$ligneExpeditions->offrir,
    //                             'etat'=>'Brouillon',
    //                             'etat_facture'=>$ligneExpeditions->etat_facture, 
    //                             'user_id'=>auth()->user()->id,
    //                             'nom_user'=>auth()->user()->name,
    //                             'societe'=>auth()->user()->societe]);                            
    //                         // Fin Expedition ligne partiel

    //                         $id_activite = $this->ids;
    //                         $page = 'ExpeditionClient';
    //                         LogActivity::addToLog('Expédition partielle » (-'.$this->reste_expedier.') '.$nom_produit, $id_activite, $page); 
    //                         $this->dispatch('alert',                    
    //                             title:'Expédition partielle » (-'.$this->reste_expedier.') '.$nom_produit.' effectuée avec succes!',
    //                             timer:5000,
    //                             icon:'success',
    //                             toast:true,
    //                             showConfirmButton: false,
    //                             position:'top-end',
    //                         );
    //                         $this->redirect('/detail_expedition_clt?id='.$this->ids.'&ref='.$this->reference.'&active=6&champ=1-1&choix=3', navigate: true);
    //                     }
    //                 }
    //                 else{
    //                     $this->dispatch('alert',                    
    //                         title:'Désolé, le reste à expédier » '.$this->resteExpedier,
    //                         timer:5000,
    //                         icon:'warning',
    //                         toast:true,
    //                         showConfirmButton: false,
    //                         position:'top-end',
    //                     );
    //                 }
    //             }
    //             else{
                        
    //                 $this->dispatch('alert',                    
    //                     title:'Cette expédition a déja été cloturée!',
    //                     timer:5000,
    //                     icon:'warning',
    //                     toast:true,
    //                     showConfirmButton: false,
    //                     position:'top-end',
    //                 );
    //             }
    //         }
    //         else{  
    //             $this->dispatch('alert',                    
    //                 title:'Vous n\'êtes pas autorisé à effectuer cette opération!',
    //                 timer:3000,
    //                 icon:'error',
    //                 toast:false,
    //                 showConfirmButton: true,
    //                 position:'center',
    //             );  
    //         } 
    //     }
    //     else{ 
    //         $this->dispatch('alert',                    
    //             title:'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!',
    //             timer:3000,
    //             icon:'error',
    //             toast:false,
    //             showConfirmButton: true,
    //             position:'center',
    //         );  
    //     } 

    // }  
    // Supprime 
    public function confirmerDelete($id){   
        $this->confirmer = $id;      
    } 
    public function supprimer(){        
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->supprimer_expedition;
            if($autoriser == 1){
                if(!empty($this->fact_entete_id)){ 
                    $test_expedition = ExpeditionClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->fact_entete_id)->count();
                }
                else{
                    $test_expedition = ExpeditionClientLigne::where('societe',auth()->user()->societe)->where('id_commande_client_entete',$this->cmd_entete_id)->count();
                }     
                if($test_expedition > 0){   

                    $test_etat = ExpeditionClientEntete::where('societe',auth()->user()->societe)->where('id',$this->ids)->first(); 
                    $etat = $test_etat->etat;           

                    // if($etat == 'Clôturée'){ 
                        
                        $etat = 'Brouillon';
                        if(!empty($this->fact_entete_id)){ 
                            $ligneExpedition = ExpeditionClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->fact_entete_id)->get(); 
                        }
                        else{
                            $ligneExpedition = ExpeditionClientLigne::where('societe',auth()->user()->societe)->where('id_commande_client_entete',$this->cmd_entete_id)->get(); 
                        }       
                        foreach($ligneExpedition as $ligneExpeditions){
                                
                            $id_exp = $ligneExpeditions->id;            
                            $quantite_stock = $ligneExpeditions->quantite;            
                            $quantite_expediee = $ligneExpeditions->quantite_expediee;
                            $id_produit = $ligneExpeditions->id_produit;
                            $code_expedition = $ligneExpeditions->code_expedition;
                            $id_entrepot = $ligneExpeditions->id_entrepot;                    

                            $stockTrouver = Stock::where('societe',auth()->user()->societe)->where('id_entrepot',$id_entrepot)->where('id_produit',$id_produit)->first();
                            // $id_produit = $stockTrouver->id_produit;
                            $nom_produit = $stockTrouver->nom_produit;
                            $reference = $stockTrouver->reference;
                            // $qteSockFinal = $stockTrouver->quantite - $quantite_stock;
                            $qteSockFinal = $stockTrouver->quantite + $quantite_expediee;
                            $valorisation_achat_total = $stockTrouver->prix_moyen_pondere_achat * $qteSockFinal;
                            $valeur_vente_total = $stockTrouver->prix_vente_unitaire * $qteSockFinal;                   
                            $reste_a_expedier = 0;
                        
                            Stock::where('societe',auth()->user()->societe)->where('id_entrepot',$id_entrepot)->where('id_produit',$id_produit)->update(['quantite'=>$qteSockFinal,'valorisation_achat_total'=>$valorisation_achat_total,'valeur_vente_total'=>$valeur_vente_total,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);   
                            ExpeditionClientLigne::where('id',$id_exp)->update(['quantite_expediee'=>$reste_a_expedier,'reste_a_expedier'=>$quantite_stock,'etat'=>$etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);   
                        
                             $Entrepo = Entrepot::where('societe',auth()->user()->societe)->where('id',$id_entrepot)->first();                    
                             $nom_entrepot = $Entrepo->nom; 

                            $libele_mouvement = 'Expédition';           
                            $code_mouvement = date('YmdHis');
                            $statut = 'EXP';
                            
                            Mouvement::create(['id_entrepot'=>$id_entrepot,'nom_produit'=>$nom_produit,'id_produit'=>$id_produit,'reference'=>$reference,'quantite'=>$quantite_expediee,'libele_mouvement'=>$libele_mouvement,
                            'code_mouvement'=>$code_mouvement,'statut'=>$statut,'origine'=>$code_expedition,'id_expedition'=>$this->ids,'entrepot'=>$nom_entrepot,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                            
                            ExpeditionClientEntete::where('societe',auth()->user()->societe)->where('id',$this->ids)->update(['etat'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                            
                        }
                        // suppression definitive et redirection
                        $page = 'ExpeditionClient';
                        ExpeditionClientEntete::where('id',$this->ids)->delete(); 
                        LogActivityModel::where('id_activite',$this->ids)->where('page',$page)->delete();

                        if(!empty($this->fact_entete_id)){ 
                            ExpeditionClientLigne::where('id_facture_client_entete',$this->fact_entete_id)->delete(); 
                        }
                        else{
                            ExpeditionClientLigne::where('id_commande_client_entete',$this->cmd_entete_id)->delete();
                        }

                        $id_activite = $this->ids;
                        LogActivity::addToLog('Expédition supprimée définitivement', $id_activite, $page);
                        $this->dispatch('alert',                    
                            title:'Expédition supprimée avec succes!',
                            timer:5000,
                            icon:'success',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        );
                        $this->redirect('/listing_expedition_clt?active=6&champ=1-1&choix=3', navigate: true);
                    // }
                    // else{
                        
                    //     $this->dispatch('alert',                    
                    //         title:'Désolé, veuillez Clôturée cette expédition avant de supprimer!',
                    //         timer:6000,
                    //         icon:'warning',
                    //         toast:true,
                    //         showConfirmButton: false,
                    //         position:'top-end',
                    //     );
                    // }
                }
                else{
                    $this->dispatch('alert',                    
                        title:'Désolé, pas de produit à expédier ou supprimer!',
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
    public function detailFact(int $idx, $codeFact_cmd){
        // ceci au chargement de la page
        $test_facture = factureClientEntete::where('societe',auth()->user()->societe)->where('id',$idx)->count();    
        if($test_facture > 0){
            $compte = factureClientEntete::where('societe',auth()->user()->societe)->where('id',$idx)->first();               
            $this->ids = $compte->id;           
            $this->reference = $compte->code_facture; // reference facture
            $this->redirect('/nouveau_fact_clt?id='.$idx.'&ref='.$this->reference.'&active=7&champ=1-1&choix=1', navigate: true);
        }  
        else{
            $this->dispatch('alert',                    
            title:'Désolé, cette facture n\'existe pas!',
                timer:5000,
                icon:'warning',
                toast:true,
                showConfirmButton: false,
                position:'top-end',
            );  
            flash ('Désolé, cette facture <strong>('.$codeFact_cmd.')</strong> n\'existe pas!')->error();
            $this->redirect('/listing_fact_clt?active=7&champ=1-1&choix=1', navigate: true);
        }
    }
    public function detailCmd(int $id, $codeFact_cmd){
        // ceci au chargement de la page
        $test_facture = CommandeClientEntete::where('societe',auth()->user()->societe)->where('id',$id)->count();    
        if($test_facture > 0){
            $compte = CommandeClientEntete::where('societe',auth()->user()->societe)->where('id',$id)->first();               
            $this->ids = $compte->id;           
            $this->reference = $compte->code_commande; // reference commande
            $this->redirect('/nouveau_cmd_clt?id='.$id.'&ref='.$this->reference.'&active=6&champ=1-1&choix=2', navigate: true);
        }  
        else{
            $this->dispatch('alert',                    
            title:'Désolé, cette commande n\'existe pas!',
                timer:5000,
                icon:'warning',
                toast:true,
                showConfirmButton: false,
                position:'top-end',
            );  
            flash ('Désolé, cette commande <strong>('.$codeFact_cmd.')</strong> n\'existe pas!')->error();
            $this->redirect('/listing_cmd_clt?active=6&champ=2-1&choix=1', navigate: true);
        }
    }
}
