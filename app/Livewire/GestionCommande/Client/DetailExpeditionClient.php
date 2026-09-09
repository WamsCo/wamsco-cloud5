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
    public $id_expedi_client_entete;
    
    public $client;
    public $client_id;    
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
    public $montant_ht;
    public $montant_remise;
    public $montant_tva;
    public $montant_precompte;
    public $marge;    

    public $nom_entrepot;  
    public $limite_stock_alerte;
    public $quantite_produit;    
    
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

    public $auteur;    
    
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
    
    public $orderField = 'produit'; 
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
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
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
        $entite_mod = Entite::where('id',auth()->user()->societe_id)->get();
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
                $test_facture = ExpeditionClientEntete::where('societe_id',auth()->user()->societe_id)->where('id',$this->id)->count(); 
                if($test_facture > 0){
                    $compte = ExpeditionClientEntete::where('societe_id',auth()->user()->societe_id)->where('id',$this->id)->first();               
                    $this->ids = $compte->id;                    
                    $this->fact_entete_id = $compte->id_facture_client_entete;
                    $this->codeExpedition = $compte->code_expedition;
                    $this->client = $compte->nom_client;
                    $this->client_id = $compte->id_client;                    
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
                    $this->montant_ht = $compte->montant_ht;
                    $this->montant_remise = $compte->montant_remise;
                    $this->montant_tva = $compte->montant_tva;
                    $this->montant_precompte = $compte->montant_precompte;
                    $this->marge = $compte->marge;                   

                    $this->methode_expedition = $compte->methode_expedition;
                    $this->numero_suivi = $compte->numero_suivi;
                    $this->created_at = $compte->created_at;
                    $this->etat_cmd = $compte->etat_cmd;
                    $this->etat_facture = $compte->etat_facture;
                    $this->updated_at = $compte->updated_at;
                    $id_entrepot = $compte->id_entrepot;                    
                    $this->auteur = $compte->nom_user;

                    $essai = Entrepot::where('societe_id',auth()->user()->societe_id)->where('id',$id_entrepot)->count();   
                    if($essai > 0){
                        $Entrepo = Entrepot::where('societe_id',auth()->user()->societe_id)->where('id',$id_entrepot)->first();               
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
                $banque = CompteBancaire :: where('societe_id',auth()->user()->societe_id)->get(); 
                $tier = Tier::where('societe_id',auth()->user()->societe_id)->where('id',$this->client_id)->get(); 
                
                if(!empty($this->fact_entete_id)){
                
                    $expeClient_ligne = ExpeditionClientLignePartiel::where('societe_id',auth()->user()->societe_id)->where('id_facture_client_entete',$this->fact_entete_id)->orderBy($this->orderField, $this->orderDirection)->get();
                    $expeClientLigneCount = $expeClient_ligne->count();

                    $montantHT = $expeClient_ligne->sum('montant_ht');
                    $montantTTC = $expeClient_ligne->sum('montant_ttc');
                    $montantRemise = $expeClient_ligne->sum('montant_remise');
                    $montantTva = $expeClient_ligne->sum('montant_tva');
                    $montantPrecompte = $expeClient_ligne->sum('montant_precompte');                    
                }
                else{
                    
                    $expeClient_ligne = ExpeditionClientLignePartiel::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->cmd_entete_id)->where('code_expedition',$this->codeExpedition)->orderBy($this->orderField, $this->orderDirection)->get();
                    $expeClientLigneCount = $expeClient_ligne->count();
                    $montantHT = $expeClient_ligne->sum('montant_ht');
                    $montantTTC = $expeClient_ligne->sum('montant_ttc');
                    $montantRemise = $expeClient_ligne->sum('montant_remise');
                    $montantTva = $expeClient_ligne->sum('montant_tva');
                    $montantPrecompte = $expeClient_ligne->sum('montant_precompte');
                }
            
                // Parametre
                $config = Parametre::where('societe_id',auth()->user()->societe_id)->limit(1)->get();
                $id_entrepot = $config[0]->id_entrepot_fctclt;

                $produit = Stock::where('societe_id',auth()->user()->societe_id)->where('id_entrepot',$id_entrepot)->get();
                $stockProd = Stock::where('societe_id',auth()->user()->societe_id)->get();
                $ListeEntrepot = Entrepot::where('societe_id',auth()->user()->societe_id)->get();
                
                $testChoix = Stock::where('societe_id',auth()->user()->societe_id)->where('id',$this->choix_produit)->count();
                if($testChoix > 0){
                    // ceci permet d'afficher la quantite entrepot origine
                    $choixProd = Stock::where('societe_id',auth()->user()->societe_id)->where('id',$this->choix_produit)->get();
                    $this->id_produit = $choixProd[0]->id_produit;                

                    // avoir le prix_vente_min 
                    $prod = Produit::where('societe_id',auth()->user()->societe_id)->where('id',$this->id_produit)->first();
                    $this->prix_vente_min = $prod->prix_vente_min;                
                }

                $cmdCltEntete = CommandeClientEntete::where('societe_id',auth()->user()->societe_id)->where('id',$this->cmd_entete_id)->orderBy('id','desc')->get();
                $factCltEntete = factureClientEntete::where('societe_id',auth()->user()->societe_id)->where('id',$this->fact_entete_id)->orderBy('id','desc')->get();

                $page = 'ExpeditionClient'; // Pour evenement lie
                $log = LogActivityModel::where('societe_id',auth()->user()->societe_id)->where('id_activite', $this->ids)->where('page', $page)->limit(22)->orderBy('id','desc')->get();
                $logCount = $log->count();

                $taxe = DeviseTva::where('societe_id',auth()->user()->societe_id)->orderBy('taux_tva','asc')->get();

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
                return view('livewire.gestion-commande.client.detail-expedition-client',compact('title_fils','module','lien','dateJour','banque','tier','cmdCltEntete','factCltEntete','expeClient_ligne','expeClientLigneCount','produit','stockProd','ListeEntrepot','taxe','log','logCount','montantHT','montantTTC','montantRemise','montantTva','montantPrecompte',))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
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
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_expedition;
            if($autoriser == 1){
                $this->validate([            
                    'date_echeance'=>'required|date',            
                ]);            
                ExpeditionClientEntete::where('societe_id',auth()->user()->societe_id)->where('id',$this->ids)->update(['date_echeance'=>$this->date_echeance,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
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
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_expedition;
            if($autoriser == 1){
                $this->validate([            
                    'methode_expedition'=>'max:255',            
                ]);                
                ExpeditionClientEntete::where('societe_id',auth()->user()->societe_id)->where('id',$this->ids)->update(['methode_expedition'=>$this->methode_expedition,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                 
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
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_expedition;
            if($autoriser == 1){
                $this->validate([            
                    'numero_suivi'=>'max:255',            
                ]);        
                ExpeditionClientEntete::where('societe_id',auth()->user()->societe_id)->where('id',$this->ids)->update(['numero_suivi'=>$this->numero_suivi,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
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
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_expedition;
            if($autoriser == 1){
                $this->validate([            
                    'note'=>'max:255',            
                ]);        
                ExpeditionClientEntete::where('societe_id',auth()->user()->societe_id)->where('id',$this->ids)->update(['note'=>$this->note,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
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
    public function precedant(){ 
        $testPrecedant = ExpeditionClientEntete::where('societe_id',auth()->user()->societe_id)->where('id','<',$this->ids)->orderBy('id','desc')->count();
        if($testPrecedant > 0){ 
            $precedant = ExpeditionClientEntete::where('societe_id',auth()->user()->societe_id)->where('id','<',$this->ids)->orderBy('id','desc')->first();        
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
        
        $testSuivant = ExpeditionClientEntete::where('societe_id',auth()->user()->societe_id)->where('id','>',$this->ids)->orderBy('id','asc')->count();
        if($testSuivant > 0){
            $suivant = ExpeditionClientEntete::where('societe_id',auth()->user()->societe_id)->where('id','>',$this->ids)->orderBy('id','asc')->first();
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
    public function confirmerDelete($id){   
        $this->confirmer = $id;      
    } 
    public function supprimer(){         
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->supprimer_expedition;
            if($autoriser == 1){                 
                $test_expedition = ExpeditionClientLignePartiel::where('societe_id',auth()->user()->societe_id)->where('id_expedition_client_entete',$this->ids)->where('id_commande_client_entete',$this->cmd_entete_id)->count();
                if($test_expedition > 0){ 
                    $ligneExpedition = ExpeditionClientLignePartiel::where('societe_id',auth()->user()->societe_id)->where('id_expedition_client_entete',$this->ids)->where('id_commande_client_entete',$this->cmd_entete_id)->get(); 
                    foreach($ligneExpedition as $ligneExpeditions){
                            
                        $id_exp = $ligneExpeditions->id;            
                        $quantite_stock = $ligneExpeditions->quantite;            
                        $quantite_expediee = $ligneExpeditions->quantite_expediee;
                        $quantite_total_expediee = $ligneExpeditions->quantite_total_expediee;
                        $nom_produit = $ligneExpeditions->produit;
                        $reference = $ligneExpeditions->reference;
                        $id_produit = $ligneExpeditions->id_produit;
                        $type_produit = $ligneExpeditions->type_produit;
                        $id_entrepot = $ligneExpeditions->id_entrepot;                    
                        $code_expedition = $ligneExpeditions->code_expedition;

                        if($type_produit == 'Produit'){
                            $stockTrouver = Stock::where('societe_id',auth()->user()->societe_id)->where('id_entrepot',$id_entrepot)->where('id_produit',$id_produit)->first();
                            // $id_produit = $stockTrouver->id_produit;
                            $nom_produit = $stockTrouver->nom_produit;
                            $reference = $stockTrouver->reference;
                            $qteSockFinal = $stockTrouver->quantite + $quantite_expediee;
                            $valorisation_achat_total = $stockTrouver->prix_moyen_pondere_achat * $qteSockFinal;
                            $valeur_vente_total = $stockTrouver->prix_vente_unitaire * $qteSockFinal;                   
                            $reste_a_expedier = 0;                    
                            Stock::where('societe_id',auth()->user()->societe_id)->where('id_entrepot',$id_entrepot)->where('id_produit',$id_produit)->update(['quantite'=>$qteSockFinal,'valorisation_achat_total'=>$valorisation_achat_total,'valeur_vente_total'=>$valeur_vente_total,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);   
                        
                            // retour dans quantite
                            $testTrouver = ExpeditionClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->cmd_entete_id)->where('id_entrepot',$id_entrepot)->where('id_produit',$id_produit)->count();
                            if($testTrouver > 0){
                                $stockTrouver = ExpeditionClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->cmd_entete_id)->where('id_entrepot',$id_entrepot)->where('id_produit',$id_produit)->first();
                                $idExpCltLigne = $stockTrouver->id;
                                $quantite = $stockTrouver->quantite;
                                $qteExpediee = $stockTrouver->quantite_expediee;
                                $resteAexpedier = $stockTrouver->reste_a_expedier;

                                // $reste_a_expedierTotal = $qteExpediee + $resteAexpedier;
                                $reste_a_expedierTotal = $resteAexpedier + $quantite_expediee;
                                $quantite_expedieeTotal = $quantite - $reste_a_expedierTotal;                               
                                $etats = 'Validée';
                                ExpeditionClientLigne::where('id',$idExpCltLigne)->update(['quantite_expediee'=>$quantite_expedieeTotal,'reste_a_expedier'=>$reste_a_expedierTotal,                                                                                           
                                                                                           'etat'=>$etats,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,
                                                                                           'user_id'=>auth()->user()->id]);   
                            }                            
                            // Fin retour
                            
                            $Entrepo = Entrepot::where('societe_id',auth()->user()->societe_id)->where('id',$id_entrepot)->first();                    
                            $nom_entrepot = $Entrepo->nom; 
                        }
                        else{ 
                            // retour dans quantite
                            $testTrouver = ExpeditionClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->cmd_entete_id)->where('id_produit',$id_produit)->count();
                            if($testTrouver > 0){
                                $stockTrouver = ExpeditionClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->cmd_entete_id)->where('id_produit',$id_produit)->first();
                                $idExpCltLigne = $stockTrouver->id;
                                $quantite = $stockTrouver->quantite;
                                $qteExpediee = $stockTrouver->quantite_expediee;
                                $resteAexpedier = $stockTrouver->reste_a_expedier;

                                // $reste_a_expedierTotal = $qteExpediee + $resteAexpedier;
                                $reste_a_expedierTotal = $resteAexpedier + $quantite_expediee;
                                $quantite_expedieeTotal = $quantite - $reste_a_expedierTotal; 
                                $etats = 'Validée';
                                ExpeditionClientLigne::where('id',$idExpCltLigne)->update(['quantite_expediee'=>$quantite_expedieeTotal,'reste_a_expedier'=>$reste_a_expedierTotal,'etat'=>$etats,'societe'=>auth()->user()->societe,
                                'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);   
                            }                               
                            // Fin retour
                            $nom_entrepot = 'Pas d\'entrepot (Service)';
                            $id_entrepot = 0;
                        }
                        $vider = NULL;
                        factureClientEntete::where('societe_id',auth()->user()->societe_id)->where('id',$this->fact_entete_id)->update(['id_expedition_client_entete'=>$vider,'code_expedition'=>$vider,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 

                        $libele_mouvement = 'Expédition (retour)';           
                        $code_mouvement = date('YmdHis');
                        $statut = 'EXP';
                        if($type_produit == 'Produit'){
                            Mouvement::create(['id_entrepot'=>$id_entrepot,'nom_produit'=>$nom_produit,'id_produit'=>$id_produit,'reference'=>$reference,'quantite'=>$quantite_expediee,'libele_mouvement'=>$libele_mouvement,
                            'code_mouvement'=>$code_mouvement,'statut'=>$statut,'origine'=>$code_expedition,'id_expedition'=>$this->ids,'entrepot'=>$nom_entrepot,'societe'=>auth()->user()->societe,
                            'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                            
                        }
                    }                    
                    // suppression definitive et redirection
                    $page = 'ExpeditionClient';
                    ExpeditionClientEntete::where('id',$this->ids)->delete(); 
                    ExpeditionClientLignePartiel::where('id_expedition_client_entete',$this->ids)->delete();
                    $verfierExped = ExpeditionClientEntete::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->cmd_entete_id)->count();
                    if($verfierExped > 0){
                        $etat = 'Partiel'; 
                    }
                    else{
                        $etat = ''; // ceci affiche 'Non créée'
                    }
                    CommandeClientEntete::where('id',$this->cmd_entete_id)->update(['etat_expedi'=>$etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                    
                    LogActivityModel::where('id_activite',$this->ids)->where('page',$page)->delete();
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
                    flash ('Expédition <strong>supprimée</strong> avec succes!')->success();
                    $this->redirect('/listing_expedition_clt?active=6&champ=1-1&choix=3', navigate: true);                    
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
    public function clotureExpedition(){
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_expedition;
            if($autoriser == 1){  
                $etat = 'Clôturée';                
                $statut = 0; // tres important
                if($this->ids){
                    
                    ExpeditionClientEntete::where('id_commande_client_entete',$this->cmd_entete_id)->where('statut',1)->update(['etat'=>$etat,'statut'=>$statut,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                    ExpeditionClientLignePartiel::where('id_commande_client_entete',$this->cmd_entete_id)->where('id_expedition_client_entete',$this->ids)->update(['etat'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                     
                    $this->dispatch('alert',                    
                        title:'Expédition clôturée avec succès!',
                        timer:5000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );
                    flash ('Expédition <strong>clôturée</strong> ('.$this->codeExpedition.') avec succès!')->success();
                    $this->creerFacture(); // creation de la Facture directement avec cette fonction
                    // $this->redirect('/detail_expedition_clt?id='.$this->ids.'&ref='.$this->codeExpedition.'&active=6&champ=1-1&choix=3', navigate: true);
                }
                else{  
                    $this->dispatch('alert',                    
                        title:'Désolé, cette expéditon est erronée!',
                        timer:5000,
                        icon:'error',
                        toast:false,
                        showConfirmButton: true,
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
    public function creerFacture(){ 
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_facture;
            if($autoriser == 1){ 
                $test_facture = factureClientEntete::where('societe_id',auth()->user()->societe_id)->where('id_expedition_client_entete',$this->ids)->count();
                if($test_facture == 0){
                    
                    $etat = 'Brouillon';
                    $dates = date('dmy/His');
                    $length = 2;
                    $token = bin2hex(random_bytes($length));
                    $token_ok = 'FACT/'.$dates;
                    // $token_ok = 'FACT/'.$dates.'/'.$token;
                
                        // copier la table CommandeClientEntete dans factureClientEntete
                    $enteteCmdClient = CommandeClientEntete::where('societe_id',auth()->user()->societe_id)->where('id',$this->cmd_entete_id)->get(); 
                    foreach($enteteCmdClient as $enteteCmdClients){
                        // creation et copie entete Facture Client Entete
                        $factCltEntet = factureClientEntete::create([                        
                            'code_facture'=>$token_ok,
                            'code_commande'=>$enteteCmdClients->code_commande,
                            'id_commande_client_entete'=>$enteteCmdClients->id,
                            'code_expedition'=>$this->codeExpedition,
                            'id_expedition_client_entete'=>$this->ids,
                            'nom_client'=>$enteteCmdClients->nom_client,
                            'id_client'=>$enteteCmdClients->id_client,
                            'telephone'=>$enteteCmdClients->telephone,
                            'date_facturation'=>$enteteCmdClients->date_commande,
                            'date_echeance'=>$enteteCmdClients->date_livraison,
                            'mode_reglement'=>'Espèce', // Espèce par defaut
                            'note'=>$enteteCmdClients->note,
                            'montant_ttc'=>$this->montant_ttc,
                            'montant_ht'=>$this->montant_ht,
                            'montant_remise'=>$this->montant_remise,
                            'montant_tva'=>$this->montant_tva,
                            'montant_precompte'=>$this->montant_precompte,
                            'marge'=>$this->marge, 
                            'montant_recu'=>0,
                            'reste_a_percevoir'=>0,
                            'etat'=>$etat,
                            'etat_expedi'=>'Clôturée',
                            'societe'=>auth()->user()->societe,
                            'societe_id'=>auth()->user()->societe_id,
                            'nom_user'=>auth()->user()->name,
                            'user_id'=>auth()->user()->id]);
                    }
                    // ceci recupere le dernier enregistrement cree a l'instant
                    $dernier_id = $factCltEntet->id; 
                
                    $ligneCmdClient = ExpeditionClientLignePartiel::where('societe_id',auth()->user()->societe_id)->where('id_expedition_client_entete',$this->ids)->get(); 
                    foreach($ligneCmdClient as $ligneCmdClients){
                        // creation et copie entete Expedition Client Ligne
                        factureClientLigne::create([ 
                            'code_facture'=>$token_ok,
                            'id_facture_client_entete'=> $dernier_id,
                            'nom_client'=>$ligneCmdClients->nom_client,
                            'id_client'=>$ligneCmdClients->id_client,
                            'produit'=>$ligneCmdClients->produit,
                            'id_produit'=>$ligneCmdClients->id_produit,
                            'reference'=>$ligneCmdClients->reference,
                            'type_produit'=>$ligneCmdClients->type_produit,                            
                            'prix_achat'=>$ligneCmdClients->prix_achat,
                            'prix_vente'=>$ligneCmdClients->prix_vente,
                            'quantite'=>$ligneCmdClients->quantite_total_expediee,
                            'quantite_expediee'=>$ligneCmdClients->quantite_expediee,
                            'reste_a_expedier'=>$ligneCmdClients->reste_a_expedier,
                            'remise'=>$ligneCmdClients->remise,
                            'montant_remise'=>$ligneCmdClients->montant_remise,
                            'tva'=>$ligneCmdClients->tva,
                            'montant_tva'=>$ligneCmdClients->montant_tva,
                            'precompte'=>$ligneCmdClients->precompte,
                            'montant_precompte'=>$ligneCmdClients->montant_precompte,
                            'montant_ht'=>$ligneCmdClients->montant_ht,
                            'montant_ttc'=>$ligneCmdClients->montant_ttc,
                            'marge'=>$ligneCmdClients->marge,
                            'id_entrepot'=>$ligneCmdClients->id_entrepot,                   
                            'offrir'=>$ligneCmdClients->offrir,
                            'etat'=>$etat,
                            'user_id'=>auth()->user()->id,
                            'nom_user'=>auth()->user()->name,
                            'societe_id'=>auth()->user()->societe_id,
                            'societe'=>auth()->user()->societe]);
                    }
                    $factCltEnteteCount = factureClientEntete::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->cmd_entete_id)->count();
                    $NbrefactClt = $factCltEnteteCount + 1;
                    CommandeClientEntete::where('societe_id',auth()->user()->societe_id)->where('id',$this->cmd_entete_id)->update(['nbre_facture'=>$NbrefactClt,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                    ExpeditionClientEntete::where('societe_id',auth()->user()->societe_id)->where('id',$this->ids)->update(['etat_facture'=>$etat,'code_facture'=>$token_ok,'id_facture_client_entete'=>$dernier_id,'date_facturation'=>date('Y-m-d'),'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                    ExpeditionClientLignePartiel::where('societe_id',auth()->user()->societe_id)->where('id_expedition_client_entete',$this->ids)->update(['code_facture'=>$token_ok,'id_facture_client_entete'=>$dernier_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                                                    
                    $id_activite = $dernier_id;
                    $page = 'factureClient';
                    LogActivity::addToLog('Facture » '.$token_ok.' créée', $id_activite, $page);
                    $this->dispatch('alert',                    
                        title:'Facture client créée!',
                        timer:5000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    ); 
                    flash ('Facture client <strong>créée</strong> ('.$token_ok.') avec succès!')->success();
                    $this->redirect('/nouveau_fact_clt?id='.$dernier_id.'&ref='.$token_ok.'&active=7&champ=1-1&choix=1', navigate: true); 
                }
                else{
                    $this->dispatch('alert',                    
                        title:'Oups, voici la facture encours pour cette commande!',
                        timer:5000,
                        icon:'warning',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    ); 
                    $enteteFactClient = factureClientEntete::where('societe_id',auth()->user()->societe_id)->where('id_expedition_client_entete',$this->ids)->first();
                    $id_fact = $enteteFactClient->id;
                    $code_fact = $enteteFactClient->code_facture;
                    flash ('Oups, voici la <strong>facture</strong> encours pour cette commande!')->warning();
                    $this->redirect('/nouveau_fact_clt?id='.$id_fact.'&ref='.$code_fact.'&active=7&champ=1-1&choix=1', navigate: true);
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
