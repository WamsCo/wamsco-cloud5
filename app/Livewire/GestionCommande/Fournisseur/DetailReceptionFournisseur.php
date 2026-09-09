<?php

namespace App\Livewire\GestionCommande\Fournisseur;

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
use App\Models\factureFournisseurEntete;
use App\Models\factureFournisseurLigne;
use App\Models\Reglement_fourni;
use App\Models\CommandeFournisseurEntete;
use App\Models\ReceptionFournisseurEntete;
use App\Models\ReceptionFournisseurLigne;
use App\Models\ReceptionFournisseurLignePartiel;

class DetailReceptionFournisseur extends Component
{
    public $id; 
    public $ids; 
    public $fact_entete_id; // ceci est id facture entete copier
    public $cmd_entete_id; // ceci est id commande entete copier
    
    public $id_fournisseur;
    public $fournisseur;
    public $reference;
    public $date_facturation;
    public $date_echeance;
    public $methode_reception;
    public $numero_suivi;
    public $note;
    public $entrepot_expedition;
    public $nonEntrepot;
    public $idEntrepot;
    public $codeReception;
    public $code_commande;

    public $montant_ttc;
    public $montant_ht;
    public $montant_remise;
    public $montant_tva;
    public $montant_precompte;
    public $marge; 
    
    public $etat;
    public $etat_facture;    
    public $created_at;
    public $updated_at;
    
    public $confirmer;

    public $choix_produit;

    // pour Modal
    public $idy; // recupere id expedition ligne dans le modal
    public $reste_recevoir;
    public $nom_produit;
    public $quantite_cmd;
    public $quantiteRecue;
    public $resteRecevoir;
    public $auteur;  
    
    public $affiche = 0;
    public function afficherDateLivraison(int $idz){
       $this->affiche = $idz;
    } 
    public $ouvre = 0;
    public function afficherMethodeReception(int $idx){
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
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->consulter_reception;
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
                $title = 'Réception fournisseur | WamsCo';
                $module = 'Gestion commande';
                $title_fils = 'Réception';
                $lien = 'listing_reception_fourni';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');      
                $dateJour = date('Y-m-d');
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');

                $this->id = request('id'); // id entete facture
                $this->ref_exp = request('ref'); // reference facture
                //     // ceci au chargement de la page
                $test_facture = ReceptionFournisseurEntete::where('societe_id',auth()->user()->societe_id)->where('id',$this->id)->count(); 
                if($test_facture > 0){
                    $compte = ReceptionFournisseurEntete::where('societe_id',auth()->user()->societe_id)->where('id',$this->id)->first();               
                    $this->ids = $compte->id;
                    $this->fact_entete_id = $compte->id_facture_fournisseur_entete;
                    $this->codeReception = $compte->code_reception;
                    $this->id_fournisseur = $compte->id_fournisseur;
                    $this->fournisseur = $compte->nom_fournisseur;
                    $this->reference = $compte->code_facture; // reference facture
                    $this->code_commande = $compte->code_commande; // reference commande
                    $this->cmd_entete_id = $compte->id_commande_fournisseur_entete; // id entete commande
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

                    $this->methode_reception = $compte->methode_reception;
                    $this->numero_suivi = $compte->numero_suivi;
                    $this->created_at = $compte->created_at;
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
                $tier = Tier::where('societe_id',auth()->user()->societe_id)->where('id',$this->id_fournisseur)->get();                  
                
                if(!empty($this->fact_entete_id)){
                
                    $recepClient_ligne = ReceptionFournisseurLignePartiel::where('societe_id',auth()->user()->societe_id)->where('id_facture_fournisseur_entete',$this->fact_entete_id)->orderBy($this->orderField, $this->orderDirection)->get();
                    $recepClientLigneCount = $recepClient_ligne->count();

                    $montantHT = $recepClient_ligne->sum('montant_ht');
                    $montantTTC = $recepClient_ligne->sum('montant_ttc');
                    $montantRemise = $recepClient_ligne->sum('montant_remise');
                    $montantTva = $recepClient_ligne->sum('montant_tva');
                    $montantPrecompte = $recepClient_ligne->sum('montant_precompte');                    
                }
                else{
                    
                    $recepClient_ligne = ReceptionFournisseurLignePartiel::where('societe_id',auth()->user()->societe_id)->where('id_commande_fournisseur_entete',$this->cmd_entete_id)->where('code_reception',$this->codeReception)->orderBy($this->orderField, $this->orderDirection)->get();
                    $recepClientLigneCount = $recepClient_ligne->count();
                    $montantHT = $recepClient_ligne->sum('montant_ht');
                    $montantTTC = $recepClient_ligne->sum('montant_ttc');
                    $montantRemise = $recepClient_ligne->sum('montant_remise');
                    $montantTva = $recepClient_ligne->sum('montant_tva');
                    $montantPrecompte = $recepClient_ligne->sum('montant_precompte');
                }

                // Parametre
                $config = Parametre::where('societe_id',auth()->user()->societe_id)->limit(1)->get();
                $id_entrepot = $config[0]->id_entrepot_fctfourni;

                $produit = Stock::where('societe_id',auth()->user()->societe_id)->where('id_entrepot', $id_entrepot)->get();
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
                
                $cmdFourniEntete = CommandeFournisseurEntete::where('societe_id',auth()->user()->societe_id)->where('id',$this->cmd_entete_id)->orderBy('id','desc')->get();
                $factFourniEntete = factureFournisseurEntete::where('societe_id',auth()->user()->societe_id)->where('id',$this->fact_entete_id)->orderBy('id','desc')->get();
                
                $page = 'ReceptionFournisseur'; // Pour evenement lie
                $log = LogActivityModel::where('user_societe',auth()->user()->societe)->where('id_activite', $this->ids)->where('page', $page)->limit(22)->orderBy('id','desc')->get();
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
                return view('livewire.gestion-commande.fournisseur.detail-reception-fournisseur',compact('title_fils','module','lien','dateJour','banque','tier','cmdFourniEntete','factFourniEntete','recepClient_ligne','recepClientLigneCount','produit','stockProd','ListeEntrepot','taxe','log','logCount',
                'montantHT','montantTTC','montantRemise','montantTva','montantPrecompte'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
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
            $autoriser = $role[0]->creer_reception;
            if($autoriser == 1){
                $this->validate([            
                    'date_echeance'=>'required|date',            
                ]);            
                ReceptionFournisseurEntete::where('societe_id',auth()->user()->societe_id)->where('id',$this->ids)->update(['date_echeance'=>$this->date_echeance,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                $id_activite = $this->ids;
                $page = 'ReceptionFournisseur';
                LogActivity::addToLog('Date livraison (réception) modifiée', $id_activite, $page); 
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
    public function changeMethodeReception(){  
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_reception;
            if($autoriser == 1){
                $this->validate([            
                    'methode_reception'=>'max:255',            
                ]);        
                ReceptionFournisseurEntete::where('societe_id',auth()->user()->societe_id)->where('id',$this->ids)->update(['methode_reception'=>$this->methode_reception,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                $id_activite = $this->ids;
                $page = 'ReceptionFournisseur';
                LogActivity::addToLog('Méthode réception modifiée', $id_activite, $page); 
                $this->dispatch('alert',                    
                    title:'Méthode Réception <br> ('.$this->methode_reception.') modifiée!',
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
            $autoriser = $role[0]->creer_reception;
            if($autoriser == 1){
                $this->validate([            
                    'numero_suivi'=>'max:255',            
                ]);        
                ReceptionFournisseurEntete::where('societe_id',auth()->user()->societe_id)->where('id',$this->ids)->update(['numero_suivi'=>$this->numero_suivi,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                $id_activite = $this->ids;
                $page = 'ReceptionFournisseur';
                LogActivity::addToLog('Numéro de suivi (réception) modifiée', $id_activite, $page); 
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
            $autoriser = $role[0]->creer_reception;
            if($autoriser == 1){
                $this->validate([            
                    'note'=>'max:255',            
                ]);        
                ReceptionFournisseurEntete::where('societe_id',auth()->user()->societe_id)->where('id',$this->ids)->update(['note'=>$this->note,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                $id_activite = $this->ids;
                $page = 'ReceptionFournisseur';
                LogActivity::addToLog('Note (réception) modifiée', $id_activite, $page);
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
    // public function reception(){    
    //     $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
    //     if($test > 0){ 
    //         $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
    //         $autoriser = $role[0]->creer_reception;
    //         if($autoriser == 1){
    //             if(!empty($this->fact_entete_id)){
    //                 $test_reception = ReceptionFournisseurLigne::where('societe_id',auth()->user()->societe_id)->where('id_facture_fournisseur_entete',$this->fact_entete_id)->count(); 
    //             }
    //             else{
    //                 $test_reception = ReceptionFournisseurLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_fournisseur_entete',$this->cmd_entete_id)->count();
    //             }

    //             if($test_reception > 0){   

    //                 $test_etat = ReceptionFournisseurEntete::where('societe_id',auth()->user()->societe_id)->where('id',$this->ids)->first(); 
    //                 $etat = $test_etat->etat;           

    //                 if($etat == 'Brouillon'){ 
                        
    //                     $etat = 'Clôturée';
    //                     if(!empty($this->fact_entete_id)){
    //                         $ligneReception = ReceptionFournisseurLigne::where('societe_id',auth()->user()->societe_id)->where('id_facture_fournisseur_entete',$this->fact_entete_id)->get();        
    //                     }
    //                     else{
    //                         $ligneReception = ReceptionFournisseurLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_fournisseur_entete',$this->cmd_entete_id)->get(); 
    //                     }

    //                     foreach($ligneReception as $ligneReceptions){
                                
    //                         $id_exp = $ligneReceptions->id;            
    //                         $quantite_stock = $ligneReceptions->quantite;            
    //                         $id_produit = $ligneReceptions->id_produit;
    //                         $code_reception = $ligneReceptions->code_reception;
    //                         $quantite_recue = $ligneReceptions->quantite_recue;
    //                         $id_entrepot = $ligneReceptions->id_entrepot;
    //                         $id_fact_fourni_entete = $ligneReceptions->id_facture_fournisseur_entete;
    //                         $id_cmd_fourni_entete = $ligneReceptions->id_commande_fournisseur_entete;
                        
    //                         $stockTrouver = Stock::where('societe_id',auth()->user()->societe_id)->where('id_entrepot',$id_entrepot)->where('id_produit',$id_produit)->first();
    //                         // $stockTrouver = Stock::find($id_stock_destinataire);
    //                         $nom_produit = $stockTrouver->nom_produit;
    //                         // $id_produit = $stockTrouver->id_produit;
    //                         $reference = $stockTrouver->reference;
    //                         $qteSockFinal = $stockTrouver->quantite + $quantite_stock;
    //                         $valorisation_achat_total = $stockTrouver->prix_moyen_pondere_achat * $qteSockFinal;
    //                         $valeur_vente_total = $stockTrouver->prix_vente_unitaire * $qteSockFinal;
    //                         $reste_a_recevoir = 0;
                        
    //                         Stock::where('societe_id',auth()->user()->societe_id)->where('id_entrepot',$id_entrepot)->where('id_produit',$id_produit)->update(['quantite'=>$qteSockFinal,'valorisation_achat_total'=>$valorisation_achat_total,'valeur_vente_total'=>$valeur_vente_total,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);   
    //                         ReceptionFournisseurLigne::where('id',$id_exp)->update(['quantite_recue'=>$quantite_stock,'reste_a_recevoir'=>$reste_a_recevoir,'etat'=>$etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);   
                        
    //                         $Entrepo = Entrepot::where('societe_id',auth()->user()->societe_id)->where('id',$id_entrepot)->first();                    
    //                         $nom_entrepot = $Entrepo->nom; 

    //                         $libele_mouvement = 'Réception';           
    //                         $code_mouvement = date('YmdHis');
    //                         $statut = 'RCP';
                            
    //                         Mouvement::create(['id_entrepot'=>$id_entrepot,'nom_produit'=>$nom_produit,'id_produit'=>$id_produit,'reference'=>$reference,'quantite'=>$quantite_stock,'libele_mouvement'=>$libele_mouvement,
    //                         'code_mouvement'=>$code_mouvement,'statut'=>$statut,'origine'=>$code_reception,'id_reception'=>$this->ids,'entrepot'=>$nom_entrepot,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                            
    //                         ReceptionFournisseurEntete::where('societe_id',auth()->user()->societe_id)->where('id',$this->ids)->update(['etat'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
    //                         if(!empty($this->fact_entete_id)){ 
    //                             factureFournisseurEntete::where('societe_id',auth()->user()->societe_id)->where('id',$id_fact_fourni_entete)->update(['etat_reception'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
    //                             CommandeFournisseurEntete::where('societe_id',auth()->user()->societe_id)->where('id_facture_fournisseur_entete',$id_fact_fourni_entete)->update(['etat_reception'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);  
    //                         }
    //                         else{
    //                             CommandeFournisseurEntete::where('societe_id',auth()->user()->societe_id)->where('id',$id_cmd_fourni_entete)->update(['etat_reception'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
    //                             factureFournisseurEntete::where('societe_id',auth()->user()->societe_id)->where('id_commande_fournisseur_entete',$id_cmd_fourni_entete)->update(['etat_reception'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
    //                         } 
    //                         $id_activite = $this->ids;
    //                         $page = 'ReceptionFournisseur';
    //                         LogActivity::addToLog('Réception cloturée » '.$nom_produit, $id_activite, $page);
    //                         $this->dispatch('alert',                    
    //                             title:'Réception effectuée et cloturée avec succes!',
    //                             timer:5000,
    //                             icon:'success',
    //                             toast:true,
    //                             showConfirmButton: false,
    //                             position:'top-end',
    //                         );
    //                         $this->redirect('/detail_reception_fourni?id='.$this->ids.'&ref='.$this->reference.'&active=6&champ=2-1&choix=2', navigate: true);
    //                     }
    //                 }
    //                 else{
                        
    //                     $this->dispatch('alert',                    
    //                         title:'Cette réception a déja été cloturée!',
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
    //                     title:'Désolé, pas de produit à réceptionner!',
    //                     timer:5000,
    //                     icon:'error',
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
    public function precedant(){ 
        $testPrecedant = ReceptionFournisseurEntete::where('societe_id',auth()->user()->societe_id)->where('id','<',$this->ids)->orderBy('id','desc')->count();
        if($testPrecedant > 0){ 
            $precedant = ReceptionFournisseurEntete::where('societe_id',auth()->user()->societe_id)->where('id','<',$this->ids)->orderBy('id','desc')->first();        
            $previous = $precedant->id; 
            $this->redirect('/detail_reception_fourni?id='.$previous.'&ref='.$this->reference.'&active=6&champ=2-1&choix=2', navigate: true);              
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
        
        $testSuivant = ReceptionFournisseurEntete::where('societe_id',auth()->user()->societe_id)->where('id','>',$this->ids)->orderBy('id','asc')->count();
        if($testSuivant > 0){
            $suivant = ReceptionFournisseurEntete::where('societe_id',auth()->user()->societe_id)->where('id','>',$this->ids)->orderBy('id','asc')->first();
            $next = $suivant->id;             
            $this->redirect('/detail_reception_fourni?id='.$next.'&ref='.$this->reference.'&active=6&champ=2-1&choix=2', navigate: true);                     
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
    // public function editer(int $id){
    //     $affiche = ReceptionFournisseurLigne::where('societe_id',auth()->user()->societe_id)->where('id',$id)->first();               
    //     $this->idy = $affiche->id;
    //     $this->nom_produit = $affiche->produit;
    //     $this->quantite_cmd = $affiche->quantite;
    //     $this->quantiteRecue = $affiche->quantite_recue;
    //     $this->resteRecevoir = $affiche->reste_a_recevoir;
    // }  
    // public function receptionPartiel(){
    //     $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
    //     if($test > 0){ 
    //         $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
    //         $autoriser = $role[0]->creer_reception;
    //         if($autoriser == 1){
    //             $this->validate([            
    //                 'reste_recevoir'=>'required|numeric',            
    //             ]);        
    //             $test_etat = ReceptionFournisseurEntete::where('societe_id',auth()->user()->societe_id)->where('id',$this->ids)->first(); 
    //             $etat = $test_etat->etat;   
    //             if($etat == 'Brouillon' || $etat == 'Partiel'){ 

    //                 if($this->reste_recevoir <= $this->resteRecevoir){                                
                        
    //                     if(!empty($this->fact_entete_id)){ 
    //                         $ligneReception = ReceptionFournisseurLigne::where('societe_id',auth()->user()->societe_id)->where('id_facture_fournisseur_entete',$this->fact_entete_id)->where('id',$this->idy)->get();        
    //                     }
    //                     else{
    //                         $ligneReception = ReceptionFournisseurLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_fournisseur_entete',$this->cmd_entete_id)->where('id',$this->idy)->get();        
    //                     }
                        
    //                     foreach($ligneReception as $ligneReceptions){
                                
    //                         $id_exp = $ligneReceptions->id;            
    //                         // $quantite_stock = $ligneReceptions->quantite;            
    //                         $quantite_stock = $this->reste_recevoir;            
    //                         $id_produit = $ligneReceptions->id_produit;
    //                         $code_reception = $ligneReceptions->code_reception;
    //                         $quantite_recue = $ligneReceptions->quantite_recue + $this->reste_recevoir;
    //                         $resteArecevoir = $ligneReceptions->reste_a_recevoir;                    
    //                         $id_entrepot = $ligneReceptions->id_entrepot;
    //                         $id_fact_fourni_entete = $ligneReceptions->id_facture_fournisseur_entete;
    //                         $id_cmd_fourni_entete = $ligneReceptions->id_commande_fournisseur_entete;
                            
    //                         $stockTrouver = Stock::where('societe_id',auth()->user()->societe_id)->where('id_entrepot',$id_entrepot)->where('id_produit',$id_produit)->first();
    //                         $nom_produit = $stockTrouver->nom_produit;
    //                         // $id_produit = $stockTrouver->id_produit;
    //                         $reference = $stockTrouver->reference;
    //                         $qteSockFinal = $stockTrouver->quantite + $quantite_stock;
    //                         $valorisation_achat_total = $stockTrouver->prix_moyen_pondere_achat * $qteSockFinal;
    //                         $valeur_vente_total = $stockTrouver->prix_vente_unitaire * $qteSockFinal;
    //                         $reste_a_recevoir = $resteArecevoir - $this->reste_recevoir;
                        
    //                         if($reste_a_recevoir == 0 ){
    //                             $etat = 'Clôturée'; 
    //                         }
    //                         else{
    //                             $etat = 'Partiel'; 
    //                         }
    //                         Stock::where('societe_id',auth()->user()->societe_id)->where('id_entrepot',$id_entrepot)->where('id_produit',$id_produit)->update(['quantite'=>$qteSockFinal,'valorisation_achat_total'=>$valorisation_achat_total,'valeur_vente_total'=>$valeur_vente_total,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);   
    //                         ReceptionFournisseurLigne::where('id',$id_exp)->update(['quantite_recue'=>$quantite_recue,'reste_a_recevoir'=>$reste_a_recevoir,'etat'=>$etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);   
                            
    //                         $Entrepo = Entrepot::where('societe_id',auth()->user()->societe_id)->where('id',$id_entrepot)->first();                    
    //                         $nom_entrepot = $Entrepo->nom; 

    //                         $libele_mouvement = 'Réception';           
    //                         $code_mouvement = date('YmdHis');
    //                         $statut = 'RCP';
                            
    //                         Mouvement::create(['id_entrepot'=>$id_entrepot,'nom_produit'=>$nom_produit,'id_produit'=>$id_produit,'reference'=>$reference,'quantite'=>$quantite_stock,'libele_mouvement'=>$libele_mouvement,
    //                         'code_mouvement'=>$code_mouvement,'statut'=>$statut,'origine'=>$code_reception,'id_reception'=>$this->ids,'entrepot'=>$nom_entrepot,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                            
    //                         if(!empty($this->fact_entete_id)){
    //                             $sommeResteArecevoir = ReceptionFournisseurLigne::where('societe_id',auth()->user()->societe_id)->where('id_facture_fournisseur_entete',$this->fact_entete_id)->sum('reste_a_recevoir');        
    //                         }
    //                         else{
    //                             $sommeResteArecevoir = ReceptionFournisseurLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_fournisseur_entete',$this->cmd_entete_id)->sum('reste_a_recevoir');        
    //                         }
                            
    //                         if($sommeResteArecevoir == 0){
    //                             $etats = 'Clôturée';                        
    //                         }
    //                         else{   
    //                             $etats = 'Partiel';                  
    //                         }

    //                         ReceptionFournisseurEntete::where('societe_id',auth()->user()->societe_id)->where('id',$this->ids)->update(['etat'=>$etats,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                            
    //                         if(!empty($this->fact_entete_id)){ 
    //                             factureFournisseurEntete::where('societe_id',auth()->user()->societe_id)->where('id',$id_fact_fourni_entete)->update(['etat_reception'=>$etats,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
    //                             CommandeFournisseurEntete::where('societe_id',auth()->user()->societe_id)->where('id_facture_fournisseur_entete',$id_fact_fourni_entete)->update(['etat_reception'=>$etats,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);  
    //                         }
    //                         else{
    //                             CommandeFournisseurEntete::where('societe_id',auth()->user()->societe_id)->where('id',$id_cmd_fourni_entete)->update(['etat_reception'=>$etats,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
    //                             factureFournisseurEntete::where('societe_id',auth()->user()->societe_id)->where('id_commande_fournisseur_entete',$id_cmd_fourni_entete)->update(['etat_reception'=>$etats,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
    //                         }                    

    //                         $id_activite = $this->ids;
    //                         $page = 'ReceptionFournisseur';
    //                         LogActivity::addToLog('Réception partielle » (+'.$this->reste_recevoir.') '.$nom_produit, $id_activite, $page);
    //                         $this->dispatch('alert',                    
    //                             title:'Réception effectuée avec succes!',
    //                             timer:5000,
    //                             icon:'success',
    //                             toast:true,
    //                             showConfirmButton: false,
    //                             position:'top-end',
    //                         );
    //                         $this->redirect('/detail_reception_fourni?id='.$this->ids.'&ref='.$this->reference.'&active=6&champ=2-1&choix=2', navigate: true);
    //                     }
    //                 }
    //                 else{
    //                     $this->dispatch('alert',                    
    //                         title:'Désolé, le reste à recevoir » '.$this->resteRecevoir,
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
    //                     title:'Cette réception a déja été cloturée!',
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
    public function confirmerDelete($id){
        $this->confirmer = $id;      
    }     
    public function supprimer(){         
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->supprimer_reception;
            if($autoriser == 1){                 
                $test_reception = ReceptionFournisseurLignePartiel::where('societe_id',auth()->user()->societe_id)->where('id_reception_fournisseur_entete',$this->ids)->where('id_commande_fournisseur_entete',$this->cmd_entete_id)->count();
                if($test_reception > 0){ 
                    $ligneReception = ReceptionFournisseurLignePartiel::where('societe_id',auth()->user()->societe_id)->where('id_reception_fournisseur_entete',$this->ids)->where('id_commande_fournisseur_entete',$this->cmd_entete_id)->get(); 
                    foreach($ligneReception as $ligneReceptions){
                            
                        $id_exp = $ligneReceptions->id;            
                        $quantite_stock = $ligneReceptions->quantite;            
                        $quantite_recue = $ligneReceptions->quantite_recue;
                        $quantite_total_recue = $ligneReceptions->quantite_total_recue;
                        $nom_produit = $ligneReceptions->produit;
                        $reference = $ligneReceptions->reference;
                        $id_produit = $ligneReceptions->id_produit;
                        $type_produit = $ligneReceptions->type_produit;
                        $id_entrepot = $ligneReceptions->id_entrepot;                    
                        $code_reception = $ligneReceptions->code_reception;

                        if($type_produit == 'Produit'){
                            $stockTrouver = Stock::where('societe_id',auth()->user()->societe_id)->where('id_entrepot',$id_entrepot)->where('id_produit',$id_produit)->first();
                            // $id_produit = $stockTrouver->id_produit;
                            $nom_produit = $stockTrouver->nom_produit;
                            $reference = $stockTrouver->reference;
                            $qteSockFinal = $stockTrouver->quantite - $quantite_recue;
                            $valorisation_achat_total = $stockTrouver->prix_moyen_pondere_achat * $qteSockFinal;
                            $valeur_vente_total = $stockTrouver->prix_vente_unitaire * $qteSockFinal;                   
                            $reste_a_recevoir = 0;                    
                            Stock::where('societe_id',auth()->user()->societe_id)->where('id_entrepot',$id_entrepot)->where('id_produit',$id_produit)->update(['quantite'=>$qteSockFinal,'valorisation_achat_total'=>$valorisation_achat_total,'valeur_vente_total'=>$valeur_vente_total,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);   
                        
                            // retour dans quantite
                            $testTrouver = ReceptionFournisseurLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_fournisseur_entete',$this->cmd_entete_id)->where('id_entrepot',$id_entrepot)->where('id_produit',$id_produit)->count();
                            if($testTrouver > 0){
                                $stockTrouver = ReceptionFournisseurLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_fournisseur_entete',$this->cmd_entete_id)->where('id_entrepot',$id_entrepot)->where('id_produit',$id_produit)->first();
                                $idExpCltLigne = $stockTrouver->id;
                                $quantite = $stockTrouver->quantite;
                                $qteRecue = $stockTrouver->quantite_recue;
                                $resteArecevoir = $stockTrouver->reste_a_recevoir;

                                // $reste_a_recevoirTotal = $qteRecue + $resteArecevoir;
                                $reste_a_recevoirTotal = $resteArecevoir + $quantite_recue;
                                $quantite_recueTotal = $quantite - $reste_a_recevoirTotal;                               
                                $etats = 'Validée';
                                ReceptionFournisseurLigne::where('id',$idExpCltLigne)->update(['quantite_recue'=>$quantite_recueTotal,'reste_a_recevoir'=>$reste_a_recevoirTotal,                                                                                           
                                'etat'=>$etats,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);   
                            }                            
                            // Fin retour
                            
                            $Entrepo = Entrepot::where('societe_id',auth()->user()->societe_id)->where('id',$id_entrepot)->first();                    
                            $nom_entrepot = $Entrepo->nom; 
                        }
                        else{ 
                            // retour dans quantite
                            $testTrouver = ReceptionFournisseurLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_fournisseur_entete',$this->cmd_entete_id)->where('id_produit',$id_produit)->count();
                            if($testTrouver > 0){
                                $stockTrouver = ReceptionFournisseurLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_fournisseur_entete',$this->cmd_entete_id)->where('id_produit',$id_produit)->first();
                                $idExpCltLigne = $stockTrouver->id;
                                $quantite = $stockTrouver->quantite;
                                $qteRecue = $stockTrouver->quantite_recue;
                                $resteArecevoir = $stockTrouver->reste_a_recevoir;

                                // $reste_a_recevoirTotal = $qteRecue + $resteArecevoir;
                                $reste_a_recevoirTotal = $resteArecevoir + $quantite_recue;
                                $quantite_recueTotal = $quantite - $reste_a_recevoirTotal; 
                                $etats = 'Validée';
                                ReceptionFournisseurLigne::where('id',$idExpCltLigne)->update(['quantite_recue'=>$quantite_recueTotal,'reste_a_recevoir'=>$reste_a_recevoirTotal,                                                                                           
                                'etat'=>$etats,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);   
                            }                               
                            // Fin retour
                            $nom_entrepot = 'Pas d\'entrepot (Service)';
                            $id_entrepot = 0;
                        }
                        $vider = NULL;
                        factureFournisseurEntete::where('societe_id',auth()->user()->societe_id)->where('id',$this->fact_entete_id)->update(['id_reception_fournisseur_entete'=>$vider,'code_reception'=>$vider,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 

                        $libele_mouvement = 'Réception (retour)';           
                        $code_mouvement = date('YmdHis');
                        $statut = 'RCP';
                        if($type_produit == 'Produit'){
                            Mouvement::create(['id_entrepot'=>$id_entrepot,'nom_produit'=>$nom_produit,'id_produit'=>$id_produit,'reference'=>$reference,'quantite'=>-$quantite_recue,'libele_mouvement'=>$libele_mouvement,
                            'code_mouvement'=>$code_mouvement,'statut'=>$statut,'origine'=>$code_reception,'id_reception'=>$this->ids,'entrepot'=>$nom_entrepot,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                            
                        }
                    }                    
                    // suppression definitive et redirection
                    $page = 'ReceptionFournisseur';
                    ReceptionFournisseurEntete::where('id',$this->ids)->delete(); 
                    ReceptionFournisseurLignePartiel::where('id_reception_fournisseur_entete',$this->ids)->delete();
                    $verfierRecep = ReceptionFournisseurEntete::where('societe_id',auth()->user()->societe_id)->where('id_commande_fournisseur_entete',$this->cmd_entete_id)->count();
                    if($verfierRecep > 0){
                        $etat = 'Partiel'; 
                    }
                    else{
                        $etat = ''; // ceci affiche 'Non créée'
                    }
                    CommandeFournisseurEntete::where('id',$this->cmd_entete_id)->update(['etat_reception'=>$etat,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                    
                    LogActivityModel::where('id_activite',$this->ids)->where('page',$page)->delete();
                    $id_activite = $this->ids;
                    LogActivity::addToLog('Réception supprimée définitivement', $id_activite, $page);
                    $this->dispatch('alert',                    
                        title:'Réception supprimée avec succes!',
                        timer:5000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );
                    flash ('Réception <strong>supprimée</strong> avec succes!')->success();
                    $this->redirect('/listing_reception_fourni?active=6&champ=2-1&choix=2', navigate: true);                    
                }
                else{
                    $this->dispatch('alert',                    
                        title:'Désolé, pas de produit à réceptionner ou supprimer!',
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
    public function clotureReception(){
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_reception;
            if($autoriser == 1){  
                $etat = 'Clôturée';                
                $statut = 0; // tres important
                if($this->ids){
                    
                    ReceptionFournisseurEntete::where('id_commande_fournisseur_entete',$this->cmd_entete_id)->where('statut',1)->update(['etat'=>$etat,'statut'=>$statut,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                    ReceptionFournisseurLignePartiel::where('id_commande_fournisseur_entete',$this->cmd_entete_id)->where('id_reception_fournisseur_entete',$this->ids)->update(['etat'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                     
                    $this->dispatch('alert',                    
                        title:'Réception clôturée avec succès!',
                        timer:5000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );
                    flash ('Réception <strong>clôturée</strong> ('.$this->codeReception.') avec succès!')->success();
                    $this->creerFacture(); // creation de la Facture directement avec cette fonction
                    // $this->redirect('/detail_expedition_clt?id='.$this->ids.'&ref='.$this->codeReception.'&active=6&champ=1-1&choix=3', navigate: true);
                }
                else{  
                    $this->dispatch('alert',                    
                        title:'Désolé, cette réception est erronée!',
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
            $autoriser = $role[0]->creer_fact_fourni;
            if($autoriser == 1){ 
                $test_facture = factureFournisseurEntete::where('societe_id',auth()->user()->societe_id)->where('id_reception_fournisseur_entete',$this->ids)->count();
                if($test_facture == 0){
                    
                    $etat = 'Brouillon';
                    $dates = date('dmy/His');
                    $length = 2;
                    $token = bin2hex(random_bytes($length));
                    $token_ok = 'SFACT/'.$dates;
                    // $token_ok = 'FACT/'.$dates.'/'.$token;
                
                        // copier la table CommandeFournisseurEntete dans factureFournisseurEntete
                    $enteteCmdFournisseur = CommandeFournisseurEntete::where('societe_id',auth()->user()->societe_id)->where('id',$this->cmd_entete_id)->get(); 
                    foreach($enteteCmdFournisseur as $enteteCmdFournisseurs){
                        // creation et copie entete Facture Client Entete
                        $factCltEntet = factureFournisseurEntete::create([                        
                            'code_facture'=>$token_ok,
                            'code_commande'=>$enteteCmdFournisseurs->code_commande,
                            'id_commande_fournisseur_entete'=>$enteteCmdFournisseurs->id,
                            'code_reception'=>$this->codeReception,
                            'id_reception_fournisseur_entete'=>$this->ids,
                            'nom_fournisseur'=>$enteteCmdFournisseurs->nom_fournisseur,
                            'id_fournisseur'=>$enteteCmdFournisseurs->id_fournisseur,
                            'telephone'=>$enteteCmdFournisseurs->telephone,
                            'date_facturation'=>$enteteCmdFournisseurs->date_commande,
                            'date_echeance'=>$enteteCmdFournisseurs->date_livraison,
                            'mode_reglement'=>'Espèce', // Espèce par defaut
                            'note'=>$enteteCmdFournisseurs->note,
                            'montant_ttc'=>$this->montant_ttc,
                            'montant_ht'=>$this->montant_ht,
                            'montant_remise'=>$this->montant_remise,
                            'montant_tva'=>$this->montant_tva,
                            'montant_precompte'=>$this->montant_precompte,
                            'marge'=>$this->marge, 
                            'montant_recu'=>0,
                            'reste_a_percevoir'=>0,
                            'etat'=>$etat,
                            'etat_reception'=>'Clôturée',
                            'societe'=>auth()->user()->societe,
                            'societe_id'=>auth()->user()->societe_id,
                            'nom_user'=>auth()->user()->name,
                            'user_id'=>auth()->user()->id]);
                    }
                    // ceci recupere le dernier enregistrement cree a l'instant
                    $dernier_id = $factCltEntet->id; 
                
                    $ligneCmdFournisseur = ReceptionFournisseurLignePartiel::where('societe_id',auth()->user()->societe_id)->where('id_reception_fournisseur_entete',$this->ids)->get(); 
                    foreach($ligneCmdFournisseur as $ligneCmdFournisseurs){
                        // creation et copie entete Expedition Client Ligne
                        factureFournisseurLigne::create([ 
                            'code_facture'=>$token_ok,
                            'id_facture_fournisseur_entete'=> $dernier_id,
                            'nom_fournisseur'=>$ligneCmdFournisseurs->nom_fournisseur,
                            'id_fournisseur'=>$ligneCmdFournisseurs->id_fournisseur,
                            'produit'=>$ligneCmdFournisseurs->produit,
                            'id_produit'=>$ligneCmdFournisseurs->id_produit,
                            'reference'=>$ligneCmdFournisseurs->reference,
                            'type_produit'=>$ligneCmdFournisseurs->type_produit,                            
                            'prix_achat'=>$ligneCmdFournisseurs->prix_achat,
                            'prix_vente'=>$ligneCmdFournisseurs->prix_vente,
                            'quantite'=>$ligneCmdFournisseurs->quantite_total_recue,
                            'quantite_recue'=>$ligneCmdFournisseurs->quantite_recue,
                            'reste_a_recevoir'=>$ligneCmdFournisseurs->reste_a_recevoir,
                            'remise'=>$ligneCmdFournisseurs->remise,
                            'montant_remise'=>$ligneCmdFournisseurs->montant_remise,
                            'tva'=>$ligneCmdFournisseurs->tva,
                            'montant_tva'=>$ligneCmdFournisseurs->montant_tva,
                            'precompte'=>$ligneCmdFournisseurs->precompte,
                            'montant_precompte'=>$ligneCmdFournisseurs->montant_precompte,
                            'montant_ht'=>$ligneCmdFournisseurs->montant_ht,
                            'montant_ttc'=>$ligneCmdFournisseurs->montant_ttc,
                            'marge'=>$ligneCmdFournisseurs->marge,
                            'id_entrepot'=>$ligneCmdFournisseurs->id_entrepot,                   
                            'offrir'=>$ligneCmdFournisseurs->offrir,
                            'etat'=>$etat,
                            'user_id'=>auth()->user()->id,
                            'nom_user'=>auth()->user()->name,
                            'societe_id'=>auth()->user()->societe_id,
                            'societe'=>auth()->user()->societe]);
                    }
                    $factFourniEnteteCount = factureFournisseurEntete::where('societe_id',auth()->user()->societe_id)->where('id_commande_fournisseur_entete',$this->cmd_entete_id)->count();
                    $NbrefactFourni = $factFourniEnteteCount + 1;
                    CommandeFournisseurEntete::where('societe_id',auth()->user()->societe_id)->where('id',$this->cmd_entete_id)->update(['nbre_facture'=>$NbrefactFourni,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                    ReceptionFournisseurEntete::where('societe_id',auth()->user()->societe_id)->where('id',$this->ids)->update(['etat_facture'=>$etat,'code_facture'=>$token_ok,'id_facture_fournisseur_entete'=>$dernier_id,'date_facturation'=>date('Y-m-d'),'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                    ReceptionFournisseurLignePartiel::where('societe_id',auth()->user()->societe_id)->where('id_reception_fournisseur_entete',$this->ids)->update(['code_facture'=>$token_ok,'id_facture_fournisseur_entete'=>$dernier_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                                                    
                    $id_activite = $dernier_id;
                    $page = 'factureFournisseur';
                    LogActivity::addToLog('Facture » '.$token_ok.' créée', $id_activite, $page);
                    $this->dispatch('alert',                    
                        title:'Facture fournisseur créée!',
                        timer:5000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    ); 
                    flash ('Facture fournisseur <strong>créée</strong> ('.$token_ok.') avec succès!')->success();
                    $this->redirect('/nouveau_fact_fourni?id='.$dernier_id.'&ref='.$token_ok.'&active=7&champ=2-1&choix=1', navigate: true); 
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
                    $enteteFactFourni = factureFournisseurEntete::where('societe_id',auth()->user()->societe_id)->where('id_reception_fournisseur_entete',$this->ids)->first();
                    $id_fact = $enteteFactFourni->id;
                    $code_fact = $enteteFactFourni->code_facture;
                    flash ('Oups, voici la <strong>facture</strong> encours pour cette commande!')->warning();
                    $this->redirect('/nouveau_fact_fourni?id='.$id_fact.'&ref='.$code_fact.'&active=7&champ=2-1&choix=1', navigate: true);
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
