<?php

namespace App\Livewire\GestionStock;

use Livewire\Component;
use Livewire\Attributes\Validate; 
use Livewire\WithPagination;
use App\Models\Role;
use App\Models\Entrepot;
use App\Helpers\LogActivity;
use App\Models\LogActivity as LogActivityModel;
use App\Models\Entite;
use App\Models\DeviseTva;
// use App\Models\Produit;
use App\Models\Stock;
use App\Models\Mouvement;
use App\Models\Transfert;
use App\Models\TransfertLigne;


class DetailTransfert extends Component
{
    public $id; 
    public $ids; // ceci permet de gere le update
    public $ide; // ceci pour afficher id entrepot au niveau du select entrepot origine pour le lien
    public $nameEntrepot; // ceci pour afficher nom entrepot au niveau du select entrepot origine

    public $ide2; // ceci pour afficher id entrepot au niveau du select entrepot destination pour le lien
    public $nameEntrepot2; // ceci pour afficher nom entrepot au niveau du select entrepot destination

    #[Validate('required')] 
    public $entrepot_origine; 

    #[Validate('required')] 
    public $entrepot_destination; 

    #[Validate('required')] 
    public $date_sortie; 

    #[Validate('required')]     
    public $date_entree;  
    
    public $transporteur;

    #[Validate('required|numeric')]
    public $nombre_paquets; 
   
    public $code_inventaire;  

    #[Validate('required')]   
    public $etiquette_transfert;     
    public $note;  
    public $ouvre = 0;
    public $devise;
    public $etat;   
    
    public $libelle_origine;   
    public $libelle_destination;   
    public $created_at;
    public $updated_at;
    public $nom_user;

    // donne transfert
    public $choix_produit;
    public $quantiteEntrepotOrigine;
    public $quantiteEntrepotDestinataire;
    public $quantite;
    public $message;
    public $id_StockOrigine;
    public $id_StockDestinataire;
    
    public $id_produit;
    public $nom_produit;
    
    public $confirmer;

    public function onDataAjout(){
        $this->reset('ouvre');
    } 
    public function ajoutLigne(int $idd){
        $this->ouvre = $idd;
    }  
    public function resetinputFields(){ 
        $this->choix_produit ='';
        $this->message ='';        
        $this->quantite = '';        
        $this->quantiteEntrepotOrigine ='';
        $this->quantiteEntrepotDestinataire ='';
    }

    public function render(){
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();
        $jourValid = $entite_mod[0]->validite_mod; 
        $mod_gestion_stock = $entite_mod[0]->mod_gestion_stock;
        $soldeClient = $entite_mod[0]->solde;
        if($dateJour <= $jourValid){
            if($mod_gestion_stock == 1){
                $title = 'Details Transfert | WamsCo';
                $module = 'Gestion stock';
                $title_fils = 'Transfert de stock';
                $lien = 'transferts';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');    
                $dateJour = date('Y-m-d');

                $deviseTva = DeviseTva :: where('societe',auth()->user()->societe)->limit(1)->orderBy('id','asc')->count(); 
                if($deviseTva == 0){
                    $this->devise = 'FCFA';
                }
                else{
                    $deviseTva = DeviseTva :: where('societe',auth()->user()->societe)->limit(1)->orderBy('id','asc')->get(); 
                    $this->devise = $deviseTva[0]->devise;
                }

                $this->id = request('id'); // id transfert           

                toast()->success('Prêt', '')->position('top-right')->autoClose(1000)->background('#fff')->width('220px')->padding('5px'); 
                // if($this->id){
                //     // ceci au chargement de la page
                    $test_trans = Transfert::where('id',$this->id)->count();    
                    if($test_trans > 0){
                        $trans = Transfert::where('id',$this->id)->first();               
                        $this->ids = $trans->id;
                        $this->entrepot_origine = $trans->id_entrepot_origine;
                        $this->entrepot_destination = $trans->id_entrepot_destination;
                        $this->libelle_origine = $trans->entrepot_origine; // libelle
                        $this->libelle_destination = $trans->entrepot_destination;  // libelle                      
                        $this->date_entree = $trans->date_entree;
                        $this->date_sortie = $trans->date_sortie;
                        $this->transporteur = $trans->transporteur;
                        $this->nombre_paquets = $trans->nombre_paquets;
                        $this->code_inventaire = $trans->code_inventaire;
                        $this->etiquette_transfert = $trans->etiquette_transfert;
                        $this->note = $trans->note;
                        $this->etat = $trans->etat;
                        $this->nom_user = $trans->nom_user;
                        $this->created_at = $trans->created_at;
                        $this->updated_at = $trans->updated_at;
                        
                    }           

                // entrepot origine
                    $listEntrepot = Entrepot::where('societe',auth()->user()->societe)->where('active',1)->orderBy('nom','asc')->get(); 
                    $dataEntrepot = Entrepot::where('societe',auth()->user()->societe)->where('id',$this->entrepot_origine)->get(); 
                    $this->ide = $dataEntrepot[0]->id;
                    $this->nameEntrepot = $dataEntrepot[0]->nom;

                    // entrepot destination
                    $dataEntrepots = Entrepot::where('societe',auth()->user()->societe)->where('id',$this->entrepot_destination)->get(); 
                    $this->ide2 = $dataEntrepots[0]->id;
                    $this->nameEntrepot2 = $dataEntrepots[0]->nom;

                    $produit_stock = Stock::where('societe',auth()->user()->societe)->where('id_entrepot',$this->entrepot_origine)->orderBy('nom_produit','asc')->get(); 
                    
                    // selection du produit a transferer
                    $testChoix = Stock::where('societe',auth()->user()->societe)->where('id',$this->choix_produit)->count();
                    if($testChoix > 0){
                        // ceci permet d'afficher la quantite entrepot origine
                        $choixProd = Stock::where('societe',auth()->user()->societe)->where('id',$this->choix_produit)->get();
                        $this->id_StockOrigine = $choixProd[0]->id;
                        $this->quantiteEntrepotOrigine = $choixProd[0]->quantite;
                        $this->id_produit = $choixProd[0]->id_produit;
                        $this->nom_produit = $choixProd[0]->nom_produit;

                        // ceci permet d'afficher la quantite entrepot Destination
                        $selectTest = Stock::where('societe',auth()->user()->societe)->where('id_entrepot',$this->entrepot_destination)->where('id_produit',$this->id_produit)->count();
                        if($selectTest > 0){
                            $selectProd = Stock::where('societe',auth()->user()->societe)->where('id_entrepot',$this->entrepot_destination)->where('id_produit',$this->id_produit)->get();
                            $this->id_StockDestinataire = $selectProd[0]->id;
                            $this->quantiteEntrepotDestinataire = $selectProd[0]->quantite;
                        }
                        else{
                            $this->quantiteEntrepotDestinataire = '-';
                        }
                    }
                    
                    $transfert_lignes = TransfertLigne::where('societe',auth()->user()->societe)->where('id_transfert',$this->ids)->get();
                    $transfertLigneCount = $transfert_lignes->count();

                    $page = 'Transfert'; // Pour evenement lie
                    $log = LogActivityModel::where('user_societe',auth()->user()->societe)->where('id_activite', $this->ids)->where('page', $page)->limit(50)->orderBy('id','desc')->get();
                    $logCount = $log->count();
                
                $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get(); 
                $jourValid = $entite_mod[0]->validite_mod; 
                // ceci pour trouver le nombre de jour restant avant expiration
                $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24));
                return view('livewire.gestion-stock.transferts.detail-transfert',compact('title_fils','module','lien','dateJour','listEntrepot','produit_stock','transfert_lignes','transfertLigneCount',
                'log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant')); 
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
            $title_fils = 'Magasin';
            $lien = 'bienvenue';
            $active = request('active');
            $champ = request('champ');
            $choix = request('choix'); 
            $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24));  
            toast()->error('Bonjour M/Mme <strong>'.auth()->user()->name.'!</strong> <br> Votre accès a expiré le: <strong>' .date('d-m-Y', strtotime($jourValid)). '</strong>,<br> veuillez renouveller votre abonnement en cliquant sur un module svp !')->position('top-end')->autoClose(50000)->background('#fff')->width('520px')->padding('5px'); 
            return view('livewire.bienvenue',compact('dateJour','entite_mod'))->layout('components.layouts.app',compact('title','module','title_fils','lien','active','entite_mod','dateJour','soldeClient','nbjoursRestant'));          
        }
    }
    public function update(){
        $this->validate();        
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_transfert;
            if($autoriser == 1){       
                    if($this->entrepot_origine == $this->entrepot_destination){
                        $this->dispatch('alert',                    
                            title:'Les entrepôts <strong>origine</strong> et <strong>destination</strong> doivent être différents!',
                            timer:5000,
                            icon:'error',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        ); 
                        flash ('Les entrepôts <strong>origine</strong> et <strong>destination</strong> doivent être différents!')->error(); 
                        return back();            
                    }       
                     $this->id = request('id'); // id transfert      
                    $entrepot =  Entrepot::where('societe',auth()->user()->societe)->where('id',$this->entrepot_origine)->first();
                    $nom_entrepot_origine = $entrepot->nom;

                    $entrepot =  Entrepot::where('societe',auth()->user()->societe)->where('id',$this->entrepot_destination)->first();
                    $nom_entrepot_destination = $entrepot->nom;
                    
                    // $this->etat = 'Brouillon';
                    Transfert::find($this->ids)->update(['entrepot_origine'=>$nom_entrepot_origine,'entrepot_destination'=>$nom_entrepot_destination,'id_entrepot_origine'=>$this->entrepot_origine,'id_entrepot_destination'=>$this->entrepot_destination,
                    'date_sortie'=>$this->date_sortie,'date_entree'=>$this->date_entree,'transporteur'=>$this->transporteur,
                                    'nombre_paquets'=>$this->nombre_paquets,'code_inventaire'=>$this->code_inventaire,'etiquette_transfert'=>$this->etiquette_transfert,'note'=>$this->note,
                                    'etat'=>$this->etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                    $id_activite = $this->ids;
                    $page = 'Transfert';
                    LogActivity::addToLog('Entete transfert » '.$nom_entrepot_origine.' vers » '.$nom_entrepot_destination.' modifié', $id_activite, $page);
                    $this->dispatch('alert',                    
                        title:'Enregistrement modifié avec succès!',
                        timer:5000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );  
                    $this->resetinputFields();  
                    // $this->redirect('/detail_transfert?id='.$this->ids, navigate: true);  
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
    public function ajouter(){
        $this->validate([
            'choix_produit'=>'required|numeric',
            'quantite'=>'required|numeric',
            'message'=>'max:250',
        ]);    
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_transfert;
            if($autoriser == 1){ 

                    if($this->quantiteEntrepotDestinataire != '-'){
                        
                        if($this->quantite <= $this->quantiteEntrepotOrigine){
                            // $this->etat = 'Brouillon';
                            TransfertLigne::create(['produit'=>$this->nom_produit,'id_produit'=>$this->id_produit,'message'=>$this->message,'quantite'=>$this->quantite,'quantite_stock_entre_origine'=>$this->quantiteEntrepotOrigine,'quantite_stock_entre_destination'=>$this->quantiteEntrepotDestinataire,
                                                'entrepot_origine'=>$this->nameEntrepot,'entrepot_destination'=>$this->nameEntrepot2,'id_stock_origine'=>$this->id_StockOrigine,'id_stock_destinataire'=>$this->id_StockDestinataire,
                                                'id_entrepot_origine'=>$this->ide,'id_entrepot_destination'=>$this->ide2,'id_transfert'=>$this->ids,
                                                'etat'=>$this->etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                     
                            
                            $id_activite = $this->ids;
                            $page = 'Transfert';
                            LogActivity::addToLog($this->nom_produit.' (+'.$this->quantite.') » à transferer ajouté', $id_activite, $page);
                            $this->dispatch('alert',                    
                                title:'Produits à transferer ajouté!',
                                timer:3000,
                                icon:'success',
                                toast:true,
                                showConfirmButton: false,
                                position:'top-end',
                            );  
                            $this->resetinputFields(); 
                        }
                        else{
                            $this->dispatch('alert',                    
                                title:'Quantité ('.$this->quantite.') est supérieure au stock entrepôt origine ('.$this->quantiteEntrepotOrigine.')!',
                                timer:10000,
                                icon:'error',
                                toast:true,
                                showConfirmButton: false,
                                position:'top-end',
                            ); 
                        }
                         
                    } 
                    else{ 
                        if($this->quantite <= $this->quantiteEntrepotOrigine){

                            $stocks = Stock::find($this->choix_produit);
                            $new_produit = $stocks->replicate();
                            $new_produit->id_entrepot = $this->ide2;  
                            $new_produit->quantite = 0; 
                            $new_produit->valorisation_achat_total = 0; 
                            $new_produit->valeur_vente_total = 0; 
                            $new_produit->save();

                            $selectProd = Stock::where('societe',auth()->user()->societe)->where('id_entrepot',$this->ide2)->where('id_produit',$this->id_produit)->get();
                            $this->id_StockDestinataire = $selectProd[0]->id;

                            // $this->etat = 'Brouillon';
                            $qteEntrepDestina = 0;
                            TransfertLigne::create(['produit'=>$this->nom_produit,'id_produit'=>$this->id_produit,'message'=>$this->message,'quantite'=>$this->quantite,'quantite_stock_entre_origine'=>$this->quantiteEntrepotOrigine,'quantite_stock_entre_destination'=>$qteEntrepDestina,
                                                'entrepot_origine'=>$this->nameEntrepot,'entrepot_destination'=>$this->nameEntrepot2,'id_stock_origine'=>$this->id_StockOrigine,'id_stock_destinataire'=>$this->id_StockDestinataire,
                                                'id_entrepot_origine'=>$this->ide,'id_entrepot_destination'=>$this->ide2,'id_transfert'=>$this->ids,
                                                'etat'=>$this->etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);    
                            
                            $id_activite = $this->ids;
                            $page = 'Transfert';
                            LogActivity::addToLog($this->nom_produit.' (+'.$this->quantite.') » crée dans l\'entrepôt de destination ('.$this->nameEntrepot2.') et ajouté pour transfert', $id_activite, $page);
                            $this->dispatch('alert',                    
                                title:'Produit crée dans l\'entrepôt de destination et ajouté pour transfert!',
                                timer:10000,
                                icon:'success',
                                toast:true,
                                showConfirmButton: false,
                                position:'top-end',
                            );  
                            $this->resetinputFields();
                        }  
                        else{
                            $this->dispatch('alert',                    
                                title:'Quantité ('.$this->quantite.') est supérieure au stock entrepôt origine ('.$this->quantiteEntrepotOrigine.')!',
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
    public function envoyer(){
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_transfert;
            if($autoriser == 1){ 
                $etat = 'Envoyé';
                Transfert::find($this->ids)->update(['etat'=>$etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                TransfertLigne::where('id_transfert',$this->ids)->update(['etat'=>$etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);  

                $ligneTransfert = TransfertLigne::where('societe',auth()->user()->societe)->where('id_transfert',$this->ids)->get();        
                foreach($ligneTransfert as $ligneTransferts){
                    
                    $id_stockOrigine = $ligneTransferts->id_stock_origine;
                    $quantite_stockOrigine = $ligneTransferts->quantite;            
                    
                    $stockTrouver = Stock::find($id_stockOrigine);
                    $nom_produit = $stockTrouver->nom_produit;
                    $id_produit = $stockTrouver->id_produit;
                    $reference = $stockTrouver->reference;
                    $qteSockOrigine = $stockTrouver->quantite - $quantite_stockOrigine;
                    $valorisation_achat_total = $stockTrouver->prix_moyen_pondere_achat * $qteSockOrigine;
                    $valeur_vente_total = $stockTrouver->prix_vente_unitaire * $qteSockOrigine;

                    Stock::find($id_stockOrigine)->update(['quantite'=>$qteSockOrigine,'valorisation_achat_total'=>$valorisation_achat_total,'valeur_vente_total'=>$valeur_vente_total,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                    
                    $transfertTrouver = Transfert::find($this->ids);
                    $id_entrepot = $transfertTrouver->id_entrepot_origine;
                    $libele_mouvement = $transfertTrouver->etiquette_transfert;
                    $nom_entrepot = $transfertTrouver->entrepot_origine;
                    $code_mouvement = date('YmdHis'); 
                    $origine = '';
                    Mouvement::create(['id_entrepot'=>$id_entrepot,'nom_produit'=>$nom_produit,'id_produit'=>$id_produit,'reference'=>$reference,'quantite'=>-$quantite_stockOrigine,'libele_mouvement'=>$libele_mouvement,
                    'code_mouvement'=>$code_mouvement,'origine'=>$origine,'entrepot'=>$nom_entrepot,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);   
                    
                    $id_activite = $id_produit;                    
                    $page = 'Produits';
                    LogActivity::addToLog('Transfert #'.$this->ids.' » '.$nom_produit.' envoyé', $id_activite, $page); 
                }
                // Ceci pour mettre a jour les valeur dans l'entrepot
                $stockPieceCount = Stock::where('societe',auth()->user()->societe)->where('id_entrepot',$this->entrepot_origine )->sum('quantite');
                $valorisation_achat_total = Stock::where('societe',auth()->user()->societe)->where('id_entrepot',$this->entrepot_origine )->sum('valorisation_achat_total'); 
                $valeurVenteTotal = Stock::where('societe',auth()->user()->societe)->where('id_entrepot',$this->entrepot_origine )->sum('valeur_vente_total'); 
                Entrepot::find($this->entrepot_origine)->update(['stock_total'=>$stockPieceCount,'valorisation_achat_total'=>$valorisation_achat_total,'valeur_vente_total'=>$valeurVenteTotal,
                'nom_user'=>auth()->user()->name]);  

                $id_activite = $this->ids;
                $page = 'Transfert';
                LogActivity::addToLog('Transfert » #'.$this->ids.' envoyé', $id_activite, $page);  
                $this->dispatch('alert',                    
                    title:'Transfert '.$etat.' avec succès!',
                    timer:5000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                );  
                $this->redirect('/detail_transfert?id='.$this->ids.'&active=4&champ=3-1&choix=5', navigate: true); 
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
    public function recu(){
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_transfert;
            if($autoriser == 1){ 
                $etat = 'Reçu';
                Transfert::find($this->ids)->update(['etat'=>$etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                TransfertLigne::where('id_transfert',$this->ids)->update(['etat'=>$etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);  

                $ligneTransfert = TransfertLigne::where('societe',auth()->user()->societe)->where('id_transfert',$this->ids)->get();        
                foreach($ligneTransfert as $ligneTransferts){
                    
                    $id_stock_destinataire = $ligneTransferts->id_stock_destinataire;
                    $quantite_stockDestinataire = $ligneTransferts->quantite;            
                    
                    $stockTrouver = Stock::find($id_stock_destinataire);
                    $nom_produit = $stockTrouver->nom_produit;
                    $id_produit = $stockTrouver->id_produit;
                    $reference = $stockTrouver->reference;
                    $qteSockDestinataire = $stockTrouver->quantite + $quantite_stockDestinataire;
                    $valorisation_achat_total = $stockTrouver->prix_moyen_pondere_achat * $qteSockDestinataire;
                    $valeur_vente_total = $stockTrouver->prix_vente_unitaire * $qteSockDestinataire;

                    Stock::find($id_stock_destinataire)->update(['quantite'=>$qteSockDestinataire,'valorisation_achat_total'=>$valorisation_achat_total,'valeur_vente_total'=>$valeur_vente_total,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);   
                    
                    $transfertTrouver = Transfert::find($this->ids);
                    $id_entrepot = $transfertTrouver->id_entrepot_destination;
                    $libele_mouvement = $transfertTrouver->etiquette_transfert;           
                    $nom_entrepot = $transfertTrouver->entrepot_destination;
                    $code_mouvement = date('YmdHis'); 
                    $origine = '';
                    Mouvement::create(['id_entrepot'=>$id_entrepot,'nom_produit'=>$nom_produit,'id_produit'=>$id_produit,'reference'=>$reference,'quantite'=>$quantite_stockDestinataire,'libele_mouvement'=>$libele_mouvement,
                    'code_mouvement'=>$code_mouvement,'origine'=>$origine,'entrepot'=>$nom_entrepot,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                    
                    $id_activite = $id_produit;                    
                    $page = 'Produits';
                    LogActivity::addToLog('Transfert #'.$this->ids.' » '.$nom_produit.' reçu', $id_activite, $page); 
                }   
                 // Ceci pour mettre a jour les valeur dans l'entrepot
                 $stockPieceCount = Stock::where('societe',auth()->user()->societe)->where('id_entrepot',$this->entrepot_destination)->sum('quantite');
                 $valorisation_achat_total = Stock::where('societe',auth()->user()->societe)->where('id_entrepot',$this->entrepot_destination)->sum('valorisation_achat_total'); 
                 $valeurVenteTotal = Stock::where('societe',auth()->user()->societe)->where('id_entrepot',$this->entrepot_destination)->sum('valeur_vente_total'); 
                 Entrepot::find($this->entrepot_destination)->update(['stock_total'=>$stockPieceCount,'valorisation_achat_total'=>$valorisation_achat_total,'valeur_vente_total'=>$valeurVenteTotal,
                 'nom_user'=>auth()->user()->name]);

                $id_activite = $this->ids;
                $page = 'Transfert';
                LogActivity::addToLog('Transfert » #'.$this->ids.' reçu', $id_activite, $page);  
                $this->dispatch('alert',                    
                    title:'Transfert '.$etat.' avec succès!',
                    timer:5000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                );  
                $this->redirect('/detail_transfert?id='.$this->ids.'&active=4&champ=3-1&choix=5', navigate: true); 
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
    public function supprimer($id){ 
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_transfert;
            if($autoriser == 1){  
                if($id){ 
                    TransfertLigne::where('id',$id)->delete();
                    $id_activite = $this->ids;
                    $page = 'Transfert';
                    LogActivity::addToLog('Ligne à tranférer supprimée', $id_activite, $page);  
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
        $testPrecedant = Transfert::where('societe',auth()->user()->societe)->where('id','<',$id)->orderBy('id','desc')->count();
        if($testPrecedant > 0){ 
            $precedant = Transfert::where('societe',auth()->user()->societe)->where('id','<',$id)->orderBy('id','desc')->first();        
            $previous = $precedant->id; 
            $this->redirect('/detail_transfert?id='.$previous.'&active=4&champ=3-1&choix=5', navigate: true);             
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
            $this->redirect('/detail_transfert?id='.$id.'&active=4&champ=3-1&choix=5', navigate: true);  // ceci evite une erreur
        }    
    }    
    public function suivant(int $id){    
        
        $testSuivant = Transfert::where('societe',auth()->user()->societe)->where('id','>',$id)->orderBy('id','asc')->count();
        if($testSuivant > 0){
            $suivant = Transfert::where('societe',auth()->user()->societe)->where('id','>',$id)->orderBy('id','asc')->first();
            $next = $suivant->id;             
            $this->redirect('/detail_transfert?id='.$next.'&active=4&champ=3-1&choix=5', navigate: true);                     
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
            $this->redirect('/detail_transfert?id='.$id.'&active=4&champ=3-1&choix=5', navigate: true); // ceci evite une erreur
        } 
    }     
}
