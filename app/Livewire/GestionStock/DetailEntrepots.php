<?php

namespace App\Livewire\GestionStock;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use App\Models\Role;
use App\Models\Entrepot;
use App\Helpers\LogActivity;
use App\Models\LogActivity as LogActivityModel;
use App\Models\Entite;
use App\Models\DeviseTva;
use App\Models\Produit;
use App\Models\Stock;
use App\Models\Mouvement;

class DetailEntrepots extends Component
{
    protected $paginationTheme = 'bootstrap';    // ceci pour pagination avec les chiffres tres important

    public $id;
    public $ids;
    public $nom_produit;
    public $reference;
    public $quantite;
    public $sens_stock = 'Ajouter';
    
    public $prix_achat = 0;
    public $nom_entrepot;
    public $id_entrepot = 0;

    public $libele_mouvement;
    public $code_mouvement;
    public $origine;
    public $devise;

    public $orderField = 'created_at'; 
    public $orderDirection = 'DESC'; 
    public $affiche = 50;
    public $parPage = 20;

    // modif
    public $nom;
    public $entrepot_parent;
    public $etats;
    public $description;
    public $adresse;
    public $code_postal;
    public $ville;
    public $pays;
    public $telephone;
    public $email;   

    public function resetinputFields(){           
        $this->nom_produit = '';      
        $this->prix_achat = 0;      
        $this->libele_mouvement = 'Correction du stock produit';
        $this->quantite = 0;     
        $this->code_mouvement = date('YmdHis');   
        $this->sens_stock = 'Ajouter';    
    }
    public function mount(){  
       
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->consulter_entrepot;
            if($autoriser == 0){
                alert()->error('Oups Désolé', 'Vous n\'êtes pas autorisé à ouvrir cette page !!!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
                $this->redirect('/bienvenue', navigate: true);
            }
        }
        else{
            alert()->error('Oups Désolé', 'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
            $this->redirect('/bienvenue', navigate: true);
        }
        $this->pays ='Cameroon';
        $this->etats = 1;  
        $this->id = request('id'); // id entrepot          
    }
    public function render(){
    
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();
        $jourValid = $entite_mod[0]->validite_mod; 
        $mod_gestion_stock = $entite_mod[0]->mod_gestion_stock;
        $soldeClient = $entite_mod[0]->solde;
        if($dateJour <= $jourValid){    
            if($mod_gestion_stock == 1){        
                $title = 'Details Magasin | WamsCo';
                $module = 'Gestion stock';
                $title_fils = 'Details';
                $lien = 'entrepot';
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

                // $this->id = request('id'); // id entrepot            

                toast()->success('Prêt', '')->position('top-right')->autoClose(1000)->background('#fff')->width('220px')->padding('5px'); 
                if($this->id){
                    // ceci au chargement de la page
                    $stock = Stock::where('societe',auth()->user()->societe)->where('id_entrepot',$this->id)->where('type_produit','Produit')->orderBy('nom_produit', 'Asc')->get();  
                    $stockCount = $stock->count();
                    $stockPieceCount = $stock->sum('quantite'); 
                    $valorisation_achat_total = $stock->sum('valorisation_achat_total');
                    $valeurVenteTotal = $stock->sum('valeur_vente_total'); 
                    
                    Entrepot::where('id',$this->id)->update(['stock_total'=>$stockPieceCount,'valorisation_achat_total'=>$valorisation_achat_total,'valeur_vente_total'=>$valeurVenteTotal]); // Mise a jour stock_total chaq fois pque la page charge
                    $entrepot = Entrepot::where('societe',auth()->user()->societe)->where('id',$this->id)->get(); 
                    
                    $mouvement = Mouvement::where('societe',auth()->user()->societe)->where('id_entrepot',$this->id)->orderBy($this->orderField, $this->orderDirection)->limit($this->affiche)->get();  
                    $mouvCountAfficher = $mouvement->count();
                    $mouvementCount = Mouvement::where('societe',auth()->user()->societe)->where('id_entrepot',$this->id)->count();

                    // Identifiez les produits qui se trouvent dans plus d'un magasin
                    $ProduitDansPlusieursMag = DB::table('stocks')
                        ->select('id_produit')
                        ->groupBy('id_produit')
                        ->havingRaw('COUNT(DISTINCT id_entrepot) > 0')
                        ->where('type_produit','Produit')
                        ->where('societe',auth()->user()->societe)
                        ->pluck('id_produit'); // Récupère seulement les IDs des produits

                    // Ensuite, joignez cette liste pour sommer les quantités
                    $SommeParProduit = DB::table('stocks as stock')
                        ->select('stock.id_produit', DB::raw('SUM(stock.quantite) as total_quantite'))
                        ->whereIn('stock.id_produit', $ProduitDansPlusieursMag)
                        ->groupBy('stock.id_produit')
                        ->get();
                    
                    $page = 'Entrepot'; // Pour evenement lie
                    $log = LogActivityModel::where('user_societe',auth()->user()->societe)->where('id_activite', $this->id)->where('page', $page)->limit(50)->orderBy('id','desc')->get();
                    $logCount = $log->count();
                }
                else{
                    // ceci quand on click sur ajuster()
                    $stock = Stock::where('societe',auth()->user()->societe)->where('id_entrepot',$this->id_entrepot)->where('type_produit','Produit')->orderBy('nom_produit', 'Asc')->get();  
                    $stockCount = $stock->count();
                    $stockPieceCount = $stock->sum('quantite');
                    $valeurVenteTotal = $stock->sum('valeur_vente_total'); 
                    $valorisation_achat_total = $stock->sum('valorisation_achat_total'); 
                    
                    Entrepot::where('id',$this->id_entrepot)->update(['stock_total'=>$stockPieceCount,'valorisation_achat_total'=>$valorisation_achat_total,'valeur_vente_total'=>$valeurVenteTotal]); // Mise a jour stock_total chaq fois pque la page charge
                    $entrepot = Entrepot::where('societe',auth()->user()->societe)->where('id',$this->id_entrepot)->get(); 

                    $mouvement = Mouvement::where('societe',auth()->user()->societe)->where('id_entrepot',$this->id_entrepot)->orderBy($this->orderField, $this->orderDirection)->limit($this->affiche)->get(); 
                    $mouvCountAfficher = $mouvement->count();
                    $mouvementCount = Mouvement::where('societe',auth()->user()->societe)->where('id_entrepot',$this->id_entrepot)->count();                    

                    // Identifiez les produits qui se trouvent dans plus d'un magasin
                    $ProduitDansPlusieursMag = DB::table('stocks')
                        ->select('id_produit')
                        ->groupBy('id_produit')
                        ->havingRaw('COUNT(DISTINCT id_entrepot) > 0')
                        ->where('type_produit','Produit')
                        ->where('societe',auth()->user()->societe)
                        ->pluck('id_produit'); // Récupère seulement les IDs des produits

                    // Ensuite, joignez cette liste pour sommer les quantités
                    $SommeParProduit = DB::table('stocks as stock')
                        ->select('stock.id_produit', DB::raw('SUM(stock.quantite) as total_quantite'))
                        ->whereIn('stock.id_produit', $ProduitDansPlusieursMag)
                        ->groupBy('stock.id_produit')
                        ->get();

                    $page = 'Entrepot'; // Pour evenement lie
                    $log = LogActivityModel::where('user_societe',auth()->user()->societe)->where('id_activite', $this->id_entrepot)->where('page', $page)->limit(50)->orderBy('id','desc')->get();
                    $logCount = $log->count();
                }   
                $produit = Produit::where('societe',auth()->user()->societe)->where('etat',1)->orderBy('nom_produit', 'Asc')->get();                  
                $entrepo = Entrepot::where('societe',auth()->user()->societe)->get(); // pour select

                $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get(); 
                $jourValid = $entite_mod[0]->validite_mod; 
                // ceci pour trouver le nombre de jour restant avant expiration
                $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24));
                return view('livewire.gestion-stock.entrepots.detail-entrepots',compact('title_fils','module','lien','dateJour','entrepot','stock','stockCount','stockPieceCount','valeurVenteTotal','valorisation_achat_total','mouvement','mouvementCount',
                'SommeParProduit','log','logCount','mouvCountAfficher','produit','entrepo'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant')); 
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
    public function ajuster(int $id){  
        $entrepo = Entrepot::where('societe',auth()->user()->societe)->where('id',$id)->first();
        $this->id_entrepot = $entrepo->id;
        $this->nom_entrepot = $entrepo->nom;
        $this->libele_mouvement = 'Correction du stock produit ';
        $this->code_mouvement = date('YmdHis'); 
    }
    public function corriger(){
        $this->validate([            
             'nom_produit'=>'required|numeric',  // id_produit          
             'prix_achat'=>'required|numeric',             
             'libele_mouvement'=>'max:255',
             'sens_stock'=>'required',
             'quantite'=>'required|numeric',
             'code_mouvement'=>'max:255',
               
        ]);    
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->correction_stock;
            if($autoriser == 1){                 
                    $produit = Produit::where('societe',auth()->user()->societe)->where('id',$this->nom_produit)->first();
                    $id_produit = $produit->id;
                    $nom = $produit->nom_produit;
                    $reference = $produit->reference;
                    $prix_achat_bd = $produit->prix_achat;
                    $prix_vente = $produit->prix_vente;
                    $prix_vente_min = $produit->prix_vente_min;
                    $limite_stock_alerte_bd = $produit->limite_stock_alerte;
                    $categorie = $produit->categorie;
                    $image = $produit->image;
                    $etat = $produit->etat;
                    $nature_produit = $produit->nature_produit;
                    $type_produit = $produit->type_produit;

                    if($this->sens_stock == 'Ajouter'){                    
                        
                        $test = Stock::where('societe',auth()->user()->societe)->where('id_entrepot',$this->id_entrepot)->where('id_produit',$this->nom_produit)->count(); 
                        if($test > 0){
                            $stoc = Stock::where('societe',auth()->user()->societe)->where('id_entrepot',$this->id_entrepot)->where('id_produit',$this->nom_produit)->first();                         
                            $id_stock = $stoc->id;
                            $quantite_stock = $stoc->quantite;

                            // Calcul CUMP                       
                            if($this->prix_achat > $prix_achat_bd || $this->prix_achat <> 0 ){
                                $valeurInitiale = ($prix_achat_bd * $quantite_stock);
                                $valeurEntree = ($this->prix_achat * $this->quantite);
                                $stockGlobal = $quantite_stock + $this->quantite;
                                if($stockGlobal <= 0){ 
                                    $prix_moyen_pondere_achat = 0; //CUMP
                                    $valorisation_achat_total = $stockGlobal * $prix_moyen_pondere_achat; // valorisation achat total
                                    $valeur_vente_total = $prix_vente * $stockGlobal;  // valeur vente total
                                    $prix_achat_ok = $this->prix_achat;
                                }
                                else{
                                    $prix_moyen_pondere_achat = ($valeurInitiale + $valeurEntree) / $stockGlobal; //CUMP
                                    $valorisation_achat_total = $stockGlobal * $prix_moyen_pondere_achat; // valorisation achat total
                                    $valeur_vente_total = $prix_vente * $stockGlobal;  // valeur vente total
                                    $prix_achat_ok = $this->prix_achat;
                                }                                

                                Stock::where('id',$id_stock)->update(['quantite'=>$stockGlobal,'prix_achat_last'=>$prix_achat_ok, 'prix_moyen_pondere_achat'=>$prix_moyen_pondere_achat,'categorie'=>$categorie,'image'=>$image,'etat'=>$etat,
                                        'valorisation_achat_total'=>$valorisation_achat_total,'prix_vente_unitaire'=>$prix_vente,'valeur_vente_total'=>$valeur_vente_total,'prix_vente_min'=>$prix_vente_min,'limite_stock_alerte'=>$limite_stock_alerte_bd,
                                        'type_produit'=>$type_produit,'nature_produit'=>$nature_produit,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                                Produit::where('id',$this->nom_produit)->update(['prix_achat'=>$prix_moyen_pondere_achat]); // Mise a jour Prix achat avec PMP
                            }
                            else{ 
                                $prix_achat_ok = $prix_achat_bd;
                                $valeurInitiale = ($prix_achat_bd * $quantite_stock);
                                $valeurEntree = ($prix_achat_bd * $this->quantite);
                                $stockGlobal = $quantite_stock + $this->quantite;
                                if($stockGlobal <= 0){
                                    $prix_moyen_pondere_achat = 0; //CUMP
                                    $valorisation_achat_total = $stockGlobal * $prix_moyen_pondere_achat; // valorisation achat total
                                    $valeur_vente_total = $prix_vente * $stockGlobal;  // valeur vente total    
                                }
                                else{
                                    $prix_moyen_pondere_achat = ($valeurInitiale + $valeurEntree) / $stockGlobal; //CUMP
                                    $valorisation_achat_total = $stockGlobal * $prix_moyen_pondere_achat; // valorisation achat total
                                    $valeur_vente_total = $prix_vente * $stockGlobal;  // valeur vente total    
                                }                                                       
                                
                                Stock::where('id',$id_stock)->update(['quantite'=>$stockGlobal,'prix_achat_last'=>$prix_achat_ok, 'prix_moyen_pondere_achat'=>$prix_moyen_pondere_achat,'categorie'=>$categorie,'image'=>$image,'etat'=>$etat,
                                        'valorisation_achat_total'=>$valorisation_achat_total,'prix_vente_unitaire'=>$prix_vente,'valeur_vente_total'=>$valeur_vente_total,'prix_vente_min'=>$prix_vente_min,'limite_stock_alerte'=>$limite_stock_alerte_bd,
                                        'type_produit'=>$type_produit,'nature_produit'=>$nature_produit,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                                Produit::where('id',$this->nom_produit)->update(['prix_achat'=>$prix_moyen_pondere_achat]); // Mise a jour Prix achat avec PMP
                            }
                            
                        }
                        else{                           
                            if($this->prix_achat > $prix_achat_bd || $this->prix_achat <> 0 ){
                                $prix_moyen_pondere_achat = $this->prix_achat; 
                                $valorisation_achat_total = $this->quantite * $prix_moyen_pondere_achat; // valorisation achat total 
                                $valeur_vente_total = $this->quantite * $prix_vente; // valeur vente total
                                $prix_achat_ok = $this->prix_achat;
                                Stock::create(['id_entrepot'=>$this->id_entrepot,'nom_produit'=>$nom,'id_produit'=>$id_produit,'reference'=>$reference,'quantite'=>$this->quantite,'prix_achat_last'=>$this->prix_achat,
                                'prix_moyen_pondere_achat'=>$prix_moyen_pondere_achat,'categorie'=>$categorie,'image'=>$image,'etat'=>$etat,
                                'valorisation_achat_total'=>$valorisation_achat_total,'prix_vente_unitaire'=>$prix_vente,'valeur_vente_total'=>$valeur_vente_total,'prix_vente_min'=>$prix_vente_min,'limite_stock_alerte'=>$limite_stock_alerte_bd,
                                'type_produit'=>$type_produit,'nature_produit'=>$nature_produit,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                                Produit::where('id',$this->nom_produit)->update(['prix_achat'=>$prix_moyen_pondere_achat]); // Mise a jour Prix achat avec PMP
                            }  
                            else{                            
                                $prix_moyen_pondere_achat = $prix_achat_bd; 
                                $prix_achat = $prix_achat_bd;  // Prix qui vient de A BD
                                $valorisation_achat_total = $this->quantite * $prix_achat; // valorisation achat total
                                $valeur_vente_total = $this->quantite * $prix_vente; // valeur vente total
                                $prix_achat_ok = $prix_achat_bd;
                                Stock::create(['id_entrepot'=>$this->id_entrepot,'nom_produit'=>$nom,'id_produit'=>$id_produit,'reference'=>$reference,'quantite'=>$this->quantite,'prix_achat_last'=>$prix_achat, 
                                'prix_moyen_pondere_achat'=>$prix_moyen_pondere_achat,'categorie'=>$categorie,'image'=>$image,'etat'=>$etat,
                                'valorisation_achat_total'=>$valorisation_achat_total,'prix_vente_unitaire'=>$prix_vente,'valeur_vente_total'=>$valeur_vente_total,'prix_vente_min'=>$prix_vente_min,'limite_stock_alerte'=>$limite_stock_alerte_bd,
                                'type_produit'=>$type_produit,'nature_produit'=>$nature_produit,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                                Produit::where('id',$this->nom_produit)->update(['prix_achat'=>$prix_moyen_pondere_achat]); // Mise a jour Prix achat avec PMP
                            } 
                        }                  
                        
                        Mouvement::create(['id_entrepot'=>$this->id_entrepot,'nom_produit'=>$nom,'id_produit'=>$id_produit,'reference'=>$reference,'quantite'=>$this->quantite,'libele_mouvement'=>$this->libele_mouvement,'code_mouvement'=>$this->code_mouvement,'origine'=>$this->origine,
                                            'entrepot'=>$this->nom_entrepot,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                         // Ceci pour mettre a jour les valeur dans l'entrepot
                         $stockPieceCount = Stock::where('societe',auth()->user()->societe)->where('id_entrepot',$this->id_entrepot)->sum('quantite');
                         $valorisation_achat_total = Stock::where('societe',auth()->user()->societe)->where('id_entrepot',$this->id_entrepot)->sum('valorisation_achat_total'); 
                         $valeurVenteTotal = Stock::where('societe',auth()->user()->societe)->where('id_entrepot',$this->id_entrepot)->sum('valeur_vente_total'); 

                         Entrepot::find($this->id_entrepot)->update(['stock_total'=>$stockPieceCount,'valorisation_achat_total'=>$valorisation_achat_total,'valeur_vente_total'=>$valeurVenteTotal,
                        'nom_user'=>auth()->user()->name]);
                                        
                        $page = 'Produits';
                        $page2 = 'Entrepot';
                        $id_activite = $this->nom_produit;
                        $id_activite2 = $this->id_entrepot;                        
                        LogActivity::addToLog('Correction de stock » '.$nom.' (+'.$this->quantite.') dans '.$this->nom_entrepot, $id_activite, $page);
                        LogActivity::addToLog('Correction de stock » '.$nom.' (+'.$this->quantite.') dans '.$this->nom_entrepot, $id_activite2, $page2);
                        $this->dispatch('alert',                    
                            title:'Stock ('.$nom.') enregistré!',
                            timer:3000,
                            icon:'success',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        );  
                        $this->resetinputFields();  
                        // $this->dispatch('correctioStock');
                        // $this->redirect('/detail_entrepot?id='.$this->id_entrepot, navigate: false);    
                    }
                    elseif($this->sens_stock == 'Supprimer'){ 
                        
                        $test = Stock::where('societe',auth()->user()->societe)->where('id_entrepot',$this->id_entrepot)->where('id_produit',$this->nom_produit)->count(); 
                        if($test > 0){
                            $stoc = Stock::where('societe',auth()->user()->societe)->where('id_entrepot',$this->id_entrepot)->where('id_produit',$this->nom_produit)->get();                         
                            $id_stock = $stoc[0]->id;   
                            $quantite_stock = $stoc[0]->quantite; 

                            // Calcul CUMP                       
                            $stockGlobal = $quantite_stock - $this->quantite;
                            if($stockGlobal > 0 ){
                                $stockGlobal = $quantite_stock - $this->quantite;
                                $valeurInitiale = ($prix_achat_bd * $stockGlobal);
                               
                                $prix_moyen_pondere_achat = ($valeurInitiale) / $stockGlobal; //CUMP
                                $valorisation_achat_total = $stockGlobal * $prix_moyen_pondere_achat; // valorisation achat total
                                $valeur_vente_total = $prix_vente * $stockGlobal;  // valeur vente total

                                Stock::where('id',$id_stock)->update(['quantite'=>$stockGlobal, 'prix_moyen_pondere_achat'=>$prix_moyen_pondere_achat,'categorie'=>$categorie,'image'=>$image,
                                        'valorisation_achat_total'=>$valorisation_achat_total,'prix_vente_unitaire'=>$prix_vente,'valeur_vente_total'=>$valeur_vente_total,'prix_vente_min'=>$prix_vente_min,
                                        'type_produit'=>$type_produit,'nature_produit'=>$nature_produit,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                                Mouvement::create(['id_entrepot'=>$this->id_entrepot,'nom_produit'=>$nom,'id_produit'=>$id_produit,'reference'=>$reference,'quantite'=>-$this->quantite,'libele_mouvement'=>$this->libele_mouvement,'code_mouvement'=>$this->code_mouvement,'origine'=>$this->origine,
                                'entrepot'=>$this->nom_entrepot,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                                
                                // Ceci pour mettre a jour les valeurs dans l'entrepot
                                $stockPieceCount = Stock::where('societe',auth()->user()->societe)->where('id_entrepot',$this->id_entrepot)->sum('quantite');
                                $valorisation_achat_total = Stock::where('societe',auth()->user()->societe)->where('id_entrepot',$this->id_entrepot)->sum('valorisation_achat_total'); 
                                $valeurVenteTotal = Stock::where('societe',auth()->user()->societe)->where('id_entrepot',$this->id_entrepot)->sum('valeur_vente_total'); 

                                Entrepot::find($this->id_entrepot)->update(['stock_total'=>$stockPieceCount,'valorisation_achat_total'=>$valorisation_achat_total,'valeur_vente_total'=>$valeurVenteTotal,
                               'nom_user'=>auth()->user()->name]);
                                    
                                    $page = 'Produits';
                                    $page2 = 'Entrepot';
                                    $id_activite = $this->nom_produit;
                                    $id_activite2 = $this->id_entrepot;                        
                                    LogActivity::addToLog('Correction de stock » '.$nom.' (-'.$this->quantite.') dans '.$this->nom_entrepot, $id_activite, $page);
                                    LogActivity::addToLog('Correction de stock » '.$nom.' (-'.$this->quantite.') dans '.$this->nom_entrepot, $id_activite2, $page2);
                                    $this->dispatch('alert',                    
                                        title:'Stock ('.$nom.') enregistré!',
                                        timer:3000,
                                        icon:'success',
                                        toast:true,
                                        showConfirmButton: false,
                                        position:'top-end',
                                    );  
                                    $this->resetinputFields();  
                                    // $this->dispatch('correctioStock');
                                    // $this->redirect('/detail_entrepot?id='.$this->id_entrepot, navigate: false);
                            }
                            else{
                                if($this->quantite <= $quantite_stock){
                                    
                                    // Stock::where('id',$id_stock)->delete(); 
                                    $stockGlobal = $quantite_stock - $this->quantite;
                                    $valeurInitiale = ($prix_achat_bd * $stockGlobal); 

                                    if($stockGlobal > 0){
                                        $prix_moyen_pondere_achat = ($valeurInitiale) / $stockGlobal; //CUMP
                                    }
                                    else{
                                        $prix_moyen_pondere_achat = 0;
                                    }
                                    $valorisation_achat_total = $stockGlobal * $prix_moyen_pondere_achat; // valorisation achat total
                                    $valeur_vente_total = $prix_vente * $stockGlobal;  // valeur vente total

                                    Stock::where('id',$id_stock)->update(['quantite'=>$stockGlobal, 'prix_moyen_pondere_achat'=>$prix_moyen_pondere_achat,'categorie'=>$categorie,'image'=>$image,
                                            'valorisation_achat_total'=>$valorisation_achat_total,'prix_vente_unitaire'=>$prix_vente,'valeur_vente_total'=>$valeur_vente_total,'prix_vente_min'=>$prix_vente_min,
                                            'type_produit'=>$type_produit,'nature_produit'=>$nature_produit,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                                    // Ceci pour mettre a jour les valeurs dans l'entrepot
                                    $stockPieceCount = Stock::where('societe',auth()->user()->societe)->where('id_entrepot',$this->id_entrepot)->sum('quantite');
                                    $valorisation_achat_total = Stock::where('societe',auth()->user()->societe)->where('id_entrepot',$this->id_entrepot)->sum('valorisation_achat_total'); 
                                    $valeurVenteTotal = Stock::where('societe',auth()->user()->societe)->where('id_entrepot',$this->id_entrepot)->sum('valeur_vente_total');                   
                                    
                                    Entrepot::find($this->id_entrepot)->update(['stock_total'=>$stockPieceCount,'valorisation_achat_total'=>$valorisation_achat_total,'valeur_vente_total'=>$valeurVenteTotal,
                                    'nom_user'=>auth()->user()->name]);

                                    Mouvement::create(['id_entrepot'=>$this->id_entrepot,'nom_produit'=>$nom,'id_produit'=>$id_produit,'reference'=>$reference,'quantite'=>-$this->quantite,'libele_mouvement'=>$this->libele_mouvement,'code_mouvement'=>$this->code_mouvement,'origine'=>$this->origine,
                                    'entrepot'=>$this->nom_entrepot,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);  
                                                                        
                                    $page = 'Produits';
                                    $page2 = 'Entrepot';
                                    $id_activite = $this->nom_produit;
                                    $id_activite2 = $this->id_entrepot;                        
                                    LogActivity::addToLog('Correction de stock » '.$nom.' (-'.$this->quantite.') dans '.$this->nom_entrepot, $id_activite, $page);
                                    LogActivity::addToLog('Correction de stock » '.$nom.' (-'.$this->quantite.') dans '.$this->nom_entrepot, $id_activite2, $page2);


                                    $this->dispatch('correctioStock');
                                    $this->dispatch('alert',                    
                                        title:'Suppression effectuée!',
                                        timer:3000,
                                        icon:'success',
                                        toast:true,
                                        showConfirmButton: false,
                                        position:'top-end',
                                    ); 
                                    $this->resetinputFields();  
                                    // $this->dispatch('correctioStock');
                                    // $this->redirect('/detail_entrepot?id='.$this->id_entrepot, navigate: false);
                                }
                                else{
                                    $this->dispatch('alert',                    
                                        title:'La quantité à supprimer est supérieur ('.$this->quantite.') au stock disponible ('.$quantite_stock.')!',
                                        timer:5000,
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
                                title:'Desolé, ce produit n\'existe pas dans ce magasin!',
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
                            title:'Votre requête n\'est pas autorisé!',
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
        $testPrecedant = Entrepot::where('societe',auth()->user()->societe)->where('id','<',$id)->orderBy('id','desc')->count();
        if($testPrecedant > 0){ 
            $precedant = Entrepot::where('societe',auth()->user()->societe)->where('id','<',$id)->orderBy('id','desc')->first();        
            $previous = $precedant->id; 
            $this->redirect('/detail_entrepot?id='.$previous.'&active=4&champ=3-1&choix=2', navigate: true);              
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
            $this->redirect('/detail_entrepot?id='.$id.'&active=4&champ=3-1&choix=2', navigate: true);  // ceci evite une erreur
        }    
    }    
    public function suivant(int $id){    
        
        $testSuivant = Entrepot::where('societe',auth()->user()->societe)->where('id','>',$id)->orderBy('id','asc')->count();
        if($testSuivant > 0){
            $suivant = Entrepot::where('societe',auth()->user()->societe)->where('id','>',$id)->orderBy('id','asc')->first();
            $next = $suivant->id;             
            $this->redirect('/detail_entrepot?id='.$next.'&active=4&champ=3-1&choix=2', navigate: true);                     
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
            $this->redirect('/detail_entrepot?id='.$id.'&active=4&champ=3-1&choix=2', navigate: true); // ceci evite une erreur
        } 
    } 
    public function edit($id){
        $entrepot =  Entrepot::where('id',$id)->first();
        $this->ids = $entrepot->id;
        $this->nom = $entrepot->nom;
        $this->reference = $entrepot->reference;       
        $this->entrepot_parent = $entrepot->entrepot_parent;       
        $this->etats = $entrepot->active;        
        $this->description = $entrepot->description;
        $this->adresse = $entrepot->adresse;
        $this->code_postal = $entrepot->code_postal;
        $this->ville = $entrepot->ville;
        $this->pays = $entrepot->pays;
        $this->telephone = $entrepot->telephone;        
        $this->email = $entrepot->email;
    } 
    public function update(){
        $this->validate([
            'nom'=>'required|max:255',
            'reference'=>'required|max:255',
            'etats'=>'required|numeric',
            'entrepot_parent'=>'nullable|max:255',
            'description'=>'nullable|max:255',
            'adresse'=>'nullable|max:255',
            'code_postal'=>'nullable|numeric',
            'ville'=>'nullable|max:255',
            'pays'=>'nullable|max:255',
            'telephone'=>'nullable|max:255',
            'email'=>'nullable|email|max:255',
        ]);  
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_entrepot;
            if($autoriser == 1){ 
                if($this->ids){  
                    Entrepot::find($this->ids)->update(['nom'=>$this->nom,'reference'=>$this->reference,'entrepot_parent'=>$this->entrepot_parent,'active'=>$this->etats,'description'=>$this->description,
                    'adresse'=>$this->adresse,'code_postal'=>$this->code_postal,'ville'=>$this->ville,'pays'=>$this->pays,'telephone'=>$this->telephone,
                    'email'=>$this->email,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                    $id_activite = $this->ids;
                    $page = 'Entrepot'; // Pour evenement lie
                    LogActivity::addToLog('Entrepôt » '.$this->nom.' modifié', $id_activite, $page);
                    $this->dispatch('alert',                    
                        title:'Magasin ('.$this->nom.') modifié!',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    ); 
                    // $this->dispatch('entrepotUpdate');
                    flash ('Entrepôt/Magasin <strong>('.$this->nom.')</strong> modifié')->success();
                    // $this->resetinputFields();
                    $this->redirect('/detail_entrepot?id='.$this->ids.'&active=4&champ=3-1&choix=2', navigate: true);
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
