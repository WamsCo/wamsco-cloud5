<?php

namespace App\Livewire\GestionStock;

use Livewire\Component;
use Livewire\Attributes\Validate; 
use App\Helpers\LogActivity;
use App\Models\LogActivity as LogActivityModel;
use Livewire\WithPagination;
// use Livewire\WithFileUploads; 
use App\Models\Role;
use App\Models\Entrepot;
use App\Models\Produit;
use App\Models\Tier;
use App\Models\Utilisateur;
use App\Models\DeviseTva;
use App\Models\Entite;
use App\Models\Stock;
use App\Models\Inventaire;
use App\Models\InventaireLigne;
use App\Models\Mouvement;


class DetailInventaire extends Component
{
    public $id;    
    public $ids; // ceci permet de gere le update
    #[Validate('required')] 
    public $reference; 

    #[Validate('required')] 
    public $libelle; 

    #[Validate('required')] 
    public $date_inventaire; 
    public $entrepot;  
    public $id_entrepot;   
    public $note;  
    public $etat; 
    public $confirmer;
    public $ouvre = 0;

    // donne inventaire
    public $choix_produit;
    public $quantiteInitialEntrepot;
    public $nameEntrepot;
    public $quantite_reelle;
    public $entrepot_nom;
    public $nom_produit;
    public $id_produit;
    public $id_Stock;

    public $created_at;
    public $updated_at;
    public $nom_user;    
    
    public function onDataAjout(){
        $this->reset('ouvre');
    } 
    public function ajoutLigne(int $idd){
        $this->ouvre = $idd;
    }  
    public function resetinputFields(){ 
        $this->choix_produit ='';
        $this->quantite_reelle ='';        
        $this->quantiteInitialEntrepot = '';
    }

    public function render(){
    
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();
        $jourValid = $entite_mod[0]->validite_mod; 
        $mod_gestion_stock = $entite_mod[0]->mod_gestion_stock;
        $soldeClient = $entite_mod[0]->solde;
        if($dateJour <= $jourValid){
            if($mod_gestion_stock == 1){
                $title = 'Détails Inventaire | WamsCo';
                $module = 'Gestion stock';
                $title_fils = 'Inventaire';
                $lien = 'inventaires';
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

                $this->id = request('id'); // id inventaire           

                toast()->success('Prêt', '')->position('top-right')->autoClose(1000)->background('#fff')->width('220px')->padding('5px'); 
                
                //     // ceci au chargement de la page
                    $test_trans = Inventaire::where('societe',auth()->user()->societe)->where('id',$this->id)->count();    
                    if($test_trans > 0){
                        $inv = Inventaire::where('societe',auth()->user()->societe)->where('id',$this->id)->first();               
                        $this->ids = $inv->id;
                        $this->libelle = $inv->libelle;
                        $this->reference = $inv->reference;
                        $this->entrepot_nom = $inv->entrepot;                    
                        $this->entrepot = $inv->id_entrepot; // ceci permet d'afficher dans le select Entrepôt sur la view
                        $this->date_inventaire = $inv->date_inventaire;
                        $this->note = $inv->note;
                        $this->etat = $inv->etat;
                        $this->created_at = $inv->created_at;
                        $this->updated_at = $inv->updated_at;
                        $this->nom_user = $inv->nom_user;
                    }  
                    $this->id_entrepot = $this->entrepot; // ceci permet d'avoir un id claire 

                // entrepot origine
                    $listEntrepot = Entrepot::where('societe',auth()->user()->societe)->where('active',1)->orderBy('nom','asc')->get(); 
                    $data_test = Entrepot::where('societe',auth()->user()->societe)->where('id',$this->id_entrepot)->count();  
                    if($data_test) {
                        $dataEntrepot = Entrepot::where('societe',auth()->user()->societe)->where('id',$this->id_entrepot)->get();                
                        $this->nameEntrepot = $dataEntrepot[0]->nom;
                    }             
                    else{
                        $this->nameEntrepot = '';
                }

                $produit_stock = Stock::where('societe',auth()->user()->societe)->where('id_entrepot',$this->id_entrepot)->orderBy('nom_produit','asc')->get(); 
                    
                    // // selection du produit a transferer
                    $testChoix = Stock::where('societe',auth()->user()->societe)->where('id',$this->choix_produit)->count();
                    if($testChoix > 0){
                        // ceci permet d'afficher la quantite entrepot origine
                        $choixProd = Stock::where('societe',auth()->user()->societe)->where('id',$this->choix_produit)->get();
                        $this->id_Stock = $choixProd[0]->id;
                        $this->quantiteInitialEntrepot = $choixProd[0]->quantite;
                        $this->id_produit = $choixProd[0]->id_produit;
                        $this->nom_produit = $choixProd[0]->nom_produit;
                    }
                    
                    $inventaire_lignes = InventaireLigne::where('societe',auth()->user()->societe)->where('id_inventaire',$this->ids)->get();
                    $inventaireLigneCount = $inventaire_lignes->count();

                    $page = 'Inventaire'; // Pour evenement lie
                    $log = LogActivityModel::where('user_societe',auth()->user()->societe)->where('id_activite', $this->ids)->where('page', $page)->limit(12)->orderBy('id','desc')->get();
                    $logCount = $log->count();
                
                $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get(); 
                $jourValid = $entite_mod[0]->validite_mod; 
                // ceci pour trouver le nombre de jour restant avant expiration
                $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24));
                return view('livewire.gestion-stock.inventaires.detail-inventaire',compact('title_fils','module','lien','dateJour','listEntrepot','inventaire_lignes','inventaireLigneCount','log','logCount','produit_stock'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant')); 
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
            $autoriser = $role[0]->modifier_inventaire;
            if($autoriser == 1){       
                                        
                    $test_data = Entrepot::where('societe',auth()->user()->societe)->where('id',$this->id_entrepot)->count(); 
                    if($test_data > 0){
                        $dataEntrepot = Entrepot::where('societe',auth()->user()->societe)->where('id',$this->id_entrepot)->get(); 
                        $nameEntrepot = $dataEntrepot[0]->nom;
                    }
                    else{
                        $nameEntrepot = '';
                        $this->id_entrepot = 0;
                    }

                    Inventaire::find($this->ids)->update(['reference'=>$this->reference,'libelle'=>$this->libelle,'entrepot'=>$nameEntrepot,'id_entrepot'=>$this->id_entrepot,'date_inventaire'=>$this->date_inventaire,
                                'note'=>$this->note,'etat'=>$this->etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                    
                    $id_activite = $this->ids;
                    $page = 'Inventaire';
                    LogActivity::addToLog('Entête inventaire » '.$this->reference.' modifié', $id_activite, $page);
                    $this->dispatch('alert',                    
                        title:'Enregistrement modifié avec succès!',
                        timer:5000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );  
                    // $this->resetinputFields();  
                    $this->redirect('/detail_inventaire?id='.$this->ids.'&active=4&champ=3-1&choix=7', navigate: true);  
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
            'quantite_reelle'=>'required|numeric|min:0',
            'choix_produit'=>'required|numeric',
        ]);    
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_inventaire;
            if($autoriser == 1){  

                    // $this->etat = 'Brouillon';
                    $ecart = $this->quantite_reelle - $this->quantiteInitialEntrepot;
                    InventaireLigne::create(['entrepot'=>$this->entrepot_nom,'id_entrepot'=>$this->id_entrepot,'produit'=>$this->nom_produit,'id_produit'=>$this->id_produit,
                                        'quantite_initiale'=>$this->quantiteInitialEntrepot,'quantite_reelle'=>$this->quantite_reelle,'ecart'=>$ecart,'id_inventaire'=>$this->ids,'id_stock'=>$this->id_Stock, 
                                        'etat'=>$this->etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                     
                    $id_activite = $this->ids;
                    $page = 'Inventaire';
                    LogActivity::addToLog('Produit ('.$this->nom_produit.') ajouté à l\'inventaire', $id_activite, $page);
                    $this->dispatch('alert',                    
                        title:'Produits ajouté à l\inventaire!',
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
            $autoriser = $role[0]->supprimer_inventaire;
            if($autoriser == 1){  
                if($id){ 
                    InventaireLigne::where('id',$id)->delete();
                    $id_activite = $this->ids;
                    $page = 'Inventaire';
                    LogActivity::addToLog('Ligne inventaire supprimé', $id_activite, $page);  
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
    public function generer(){
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_inventaire;
            if($autoriser == 1){ 

                $etat = 'Clôturé';
                Inventaire::find($this->ids)->update(['etat'=>$etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                InventaireLigne::where('id_inventaire',$this->ids)->update(['etat'=>$etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);  

                $ligneInventaire = InventaireLigne::where('societe',auth()->user()->societe)->where('id_inventaire',$this->ids)->get();        
                foreach($ligneInventaire as $ligneInventaires){
                    
                    $id_stock = $ligneInventaires->id_stock;
                    $quantiteReelle = $ligneInventaires->quantite_reelle;            
                    $id_entrepot = $ligneInventaires->id_entrepot;            
                    $nom_entrepot = $ligneInventaires->entrepot;            
                    
                    $stockTrouver = Stock::find($id_stock);
                    $nom_produit = $stockTrouver->nom_produit;
                    $id_produit = $stockTrouver->id_produit;
                    $reference = $stockTrouver->reference;
                    // $qteSockOrigine = $stockTrouver->quantite + $quantiteReelle;
                    $valorisation_achat_total = $stockTrouver->prix_moyen_pondere_achat * $quantiteReelle;
                    $valeur_vente_total = $stockTrouver->prix_vente_unitaire * $quantiteReelle;

                    Stock::find($id_stock)->update(['quantite'=>$quantiteReelle,'valorisation_achat_total'=>$valorisation_achat_total,'valeur_vente_total'=>$valeur_vente_total,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                    
                    $inventaireTrouver = Inventaire::find($this->ids);
                    // $id_entrepot = $inventaireTrouver->id_entrepot;
                    // $nom_entrepot = $inventaireTrouver->entrepot;
                    $libele_mouvement = $inventaireTrouver->libelle;
                    $code_mouvement = 'INV-'.$inventaireTrouver->reference; 
                    $origine = $inventaireTrouver->reference;
                    $statut = 'INV';

                    Mouvement::create(['id_entrepot'=>$id_entrepot,'entrepot'=>$nom_entrepot,'nom_produit'=>$nom_produit,'id_produit'=>$id_produit,'reference'=>$reference,'quantite'=>$quantiteReelle,'libele_mouvement'=>$libele_mouvement,
                    'code_mouvement'=>$code_mouvement,'origine'=>$origine,'statut'=>$statut,'id_inventaire'=>$this->ids,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                    
                    $id_activite = $id_produit;                    
                    $page = 'Produits';
                    LogActivity::addToLog('Inventaire #'.$origine.' » '.$nom_produit.' ('.$quantiteReelle.') généré', $id_activite, $page); 
                }   
                $id_activite = $this->ids;
                $page = 'Inventaire';
                LogActivity::addToLog('Inventaire #'.$origine.' » généré et '.$etat, $id_activite, $page);  
                $this->dispatch('alert',                    
                    title:'Inventaire '.$etat.' avec succès!',
                    timer:5000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                );  
                $this->redirect('/detail_inventaire?id='.$this->ids.'&active=4&champ=3-1&choix=7', navigate: true);
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
        $testPrecedant = Inventaire::where('societe',auth()->user()->societe)->where('id','<',$id)->orderBy('id','desc')->count();
        if($testPrecedant > 0){ 
            $precedant = Inventaire::where('societe',auth()->user()->societe)->where('id','<',$id)->orderBy('id','desc')->first();        
            $previous = $precedant->id; 
            $this->redirect('/detail_inventaire?id='.$previous.'&active=4&champ=3-1&choix=7', navigate: true);              
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
            $this->redirect('/detail_inventaire?id='.$id.'&active=4&champ=3-1&choix=7', navigate: true);  // ceci evite une erreur
        }    
    }    
    public function suivant(int $id){    
        
        $testSuivant = Inventaire::where('societe',auth()->user()->societe)->where('id','>',$id)->orderBy('id','asc')->count();
        if($testSuivant > 0){
            $suivant = Inventaire::where('societe',auth()->user()->societe)->where('id','>',$id)->orderBy('id','asc')->first();
            $next = $suivant->id;             
            $this->redirect('/detail_inventaire?id='.$next.'&active=4&champ=3-1&choix=7', navigate: true);                     
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
            $this->redirect('/detail_inventaire?id='.$id.'&active=4&champ=3-1&choix=7', navigate: true); // ceci evite une erreur
        } 
    } 
}
