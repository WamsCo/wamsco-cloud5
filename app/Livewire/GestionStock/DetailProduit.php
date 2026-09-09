<?php

namespace App\Livewire\GestionStock;

use Livewire\Component;
use Livewire\Attributes\Validate; 
use App\Helpers\LogActivity;
use App\Models\LogActivity as LogActivityModel;
use Livewire\WithPagination;
use Livewire\WithFileUploads; 
use App\Models\Role;
use App\Models\Entrepot;
use App\Models\Categorie;
use App\Models\Produit;
use App\Models\Tier;
use App\Models\Utilisateur;
use App\Models\DeviseTva;
use App\Models\Entite;
use App\Models\Stock;
use App\Models\Mouvement;
use App\Models\PrixVente;
use App\Models\factureClientLigne;
use App\Models\ExpeditionClientLigne;
use App\Models\CommandeClientLigne;
use App\Models\ProformaClientLigne;
use App\Models\ComposantNomenclature;
use App\Models\ComposantNomenclatureOrdreFab;
use App\Models\Nomenclature;
use App\Models\InventaireLigne;
use App\Models\TransfertLigne;
use Illuminate\Support\Str; // pour code barre
use Illuminate\Validation\Rule;

class DetailProduit extends Component
{
    use WithPagination;
    use WithFileUploads;
    
    public $id;    
    public $ids;    
    public $nom_produit;
    public $reference; 
    public $code_barre; 
    public $type_produit;
    public $nature_produit;
    public $description;
    public $categorie; 
    public $entrepot; 
    public $fournisseur; 
    public $prix_achat; 
    public $prix_vente; 
    public $prix_vente_min; 
    public $tva;
    public $limite_stock_alerte;      
    public $pays_origine; 
    public $date_peremption;    
    public $responsable_achat;    
    public $etat; 

    // #[Validate('image|mimes:jpeg,jpg,png,gif|max:2048|nullable')] 
    public $image;
    public $old_image;    

    public $quantite_pv = 0;     
    public $montant_total = 0; 
    public $devise;
  
    public $orderField = 'created_at'; 
    public $orderDirection = 'DESC'; 
    public $affiche = 50;
    public $confirmer;
    public $ouvre = 0;
    public $active = 1;
    public $approuver;

    // donnnees corriger Stock  
    public $nom_entrepot;
    public $prix_achats = 0;
    public $libele_mouvement;
    public $sens_stock = 'Ajouter';
    public $quantite;
    public $code_mouvement;
    public $origine;
    
    public function onDataAjout(){
        $this->reset('ouvre');
    } 
    public function resetinputFields(){           
        $this->nom_entrepot = '';      
        $this->prix_achats = 0;       
        $this->libele_mouvement = 'Correction du stock produit';
        $this->quantite = 0;      
        $this->code_mouvement = date('YmdHis');   
        $this->sens_stock = 'Ajouter';      
    }
    public function render(){
    
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('id',auth()->user()->societe_id)->get();
        $jourValid = $entite_mod[0]->validite_mod;
        $mod_gestion_stock = $entite_mod[0]->mod_gestion_stock; 
        $soldeClient = $entite_mod[0]->solde;
        if($dateJour <= $jourValid){ 
            if($mod_gestion_stock == 1){           
                $title = 'Détails Produit | WamsCo';
                $module = 'Gestion stock';
                $title_fils = 'Détail produit';
                $lien = 'produit';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');      
                $dateJour = date('Y-m-d');
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');

                $this->ids = request('id'); // id produit
                $listCategorie = Categorie::where('societe_id',auth()->user()->societe_id)->orderBy('nom_categorie','asc')->get();  
                $listEntrepot = Entrepot::where('societe_id',auth()->user()->societe_id)->orderBy('nom','asc')->where('active',1)->get();  
                $listFourni = Tier::where('societe_id',auth()->user()->societe_id)->where('type_tiers','Fournisseur')->where('etat',1)->orderBy('nom','asc')->get(); 
                $listUser = Utilisateur::where('societe_id',auth()->user()->societe_id)->where('type_user','!=','Super-admin')->where('etat',1)->orderBy('name','asc')->get(); 
                $listedeviseTva = DeviseTva :: where('societe_id',auth()->user()->societe_id)->get();            

                if($this->ids){
                    // ceci au chargement de la page
                    $produit = Produit::where('societe_id',auth()->user()->societe_id)->where('id',$this->ids)->get(); 
                    $stock = Stock::where('societe_id',auth()->user()->societe_id)->where('id_produit',$this->ids)->where('type_produit','Produit')->where('etat',1)->get();  
                    $stockCount = $stock->count();
                    $stockPieceCount = $stock->sum('quantite'); 
                    $valeurVenteTotal = $stock->sum('valeur_vente_total'); 
                    $valorisation_achat_total = $stock->sum('valorisation_achat_total');

                    $mouvement = Mouvement::where('societe_id',auth()->user()->societe_id)->where('id_produit',$this->ids)->orderBy($this->orderField, $this->orderDirection)->limit($this->affiche)->get(); 
                    $mouvCountAfficher = $mouvement->count();
                    $mouvementCount = Mouvement::where('societe_id',auth()->user()->societe_id)->where('id_produit',$this->ids)->count();                
                    $listePrixVente = PrixVente::where('societe_id',auth()->user()->societe_id)->where('id_produit',$this->ids)->orderBy($this->orderField, $this->orderDirection)->limit($this->affiche)->get(); 
                    $listePrixVenteCount = $listePrixVente->count();
                    
                    $page = 'Produits'; // Pour evenement lie
                    $log = LogActivityModel::where('societe_id',auth()->user()->societe_id)->where('id_activite', $this->ids)->where('page', $page)->limit(20)->orderBy('id','desc')->get();
                    $logCount = $log->count();
                }
                else{
                    // ceci quand on click sur Edit() ou modifier
                    $produit = Produit::where('societe_id',auth()->user()->societe_id)->where('id',$this->id)->get();  
                    $stock = Stock::where('societe_id',auth()->user()->societe_id)->where('id_produit',$this->id)->where('type_produit','Produit')->where('etat',1)->get();  
                    $stockCount = $stock->count();
                    $stockPieceCount = $stock->sum('quantite'); 
                    $valeurVenteTotal = $stock->sum('valeur_vente_total'); 
                    $valorisation_achat_total = $stock->sum('valorisation_achat_total');
                    
                    $mouvement = Mouvement::where('societe_id',auth()->user()->societe_id)->where('id_produit',$this->id)->orderBy($this->orderField, $this->orderDirection)->limit($this->affiche)->get(); 
                    $mouvCountAfficher = $mouvement->count();
                    $mouvementCount = Mouvement::where('societe_id',auth()->user()->societe_id)->where('id_produit',$this->id)->count();
                    $listePrixVente = PrixVente::where('societe_id',auth()->user()->societe_id)->where('id_produit',$this->id)->orderBy($this->orderField, $this->orderDirection)->limit($this->affiche)->get(); 
                    $listePrixVenteCount = $listePrixVente->count();
                    
                    $page = 'Produits'; // Pour evenement lie
                    $log = LogActivityModel::where('societe_id',auth()->user()->societe_id)->where('id_activite', $this->id)->where('page', $page)->limit(20)->orderBy('id','desc')->get();
                    $logCount = $log->count();
                }
                
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
                return view('livewire.gestion-stock.produits.detail-produit',compact('title_fils','module','lien','dateJour','produit','listCategorie','listEntrepot','listFourni','listUser','listedeviseTva','log','logCount','stock','stockCount','stockPieceCount','valeurVenteTotal','valorisation_achat_total','mouvement',
                'mouvCountAfficher','mouvementCount','listePrixVente','listePrixVenteCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
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
    public function edit(int $id){ 
        $prod = Produit::where('id',$id)->first(); 
        $this->id = $prod->id;
        $this->nom_produit = $prod->nom_produit;
        $this->reference = $prod->reference;
        $this->code_barre = $prod->code_barre;
        $this->type_produit = $prod->type_produit;        
        $this->nature_produit = $prod->nature_produit;        
        $this->description = $prod->description;
        $this->categorie = $prod->categorie;
        $this->entrepot = $prod->entrepot;        
        $this->fournisseur = $prod->fournisseur;
        $this->prix_achat = round($prod->prix_achat,1);
        $this->prix_vente_min = $prod->prix_vente_min;
        $this->prix_vente = $prod->prix_vente;
        $this->tva = $prod->tva;
        $this->limite_stock_alerte = $prod->limite_stock_alerte;
        $this->pays_origine = $prod->pays_origine;
        $this->date_peremption = $prod->date_peremption;
        $this->responsable_achat = $prod->responsable_achat;
        $this->etat = $prod->etat; 
        $this->old_image = $prod->image; 
    } 
    public function update(){
        // $this->validate(); 
        if($this->type_produit == 'Produit'){
            $this->validate([                      
                'nom_produit'=>'required|max:255',          
                'reference'=>'required|max:255',    
                // 'code_barre'=>'nullable|max:255',  
                'code_barre' => ['nullable','string','max:255', Rule::unique('produits', 'code_barre')->where(fn($query) => $query->where('societe_id',auth()->user()->societe_id))->ignore($this->id),],     
                'type_produit'=>'required|max:255',          
                'nature_produit'=>'required|max:255',          
                // 'description'=>'required|max:255',          
                'categorie'=>'required|max:255',   
                'entrepot'=>'required|numeric',
                'fournisseur'=>'required|max:255',          
                'prix_achat'=>'required|numeric',          
                'prix_vente'=>'required|numeric',          
                'prix_vente_min'=>'required|numeric',          
                'tva'=>'required|numeric',          
                'limite_stock_alerte'=>'required|numeric',          
                'pays_origine'=>'required|max:255',          
                // 'date_peremption'=>'required',          
                'responsable_achat'=>'max:255',          
                'etat'=>'required|numeric',          
            ]); 
        }
        else{
            $this->validate([                      
                'nom_produit'=>'required|max:255',          
                'reference'=>'required|max:255',
                // 'code_barre'=>'nullable|max:255', 
                'code_barre' => ['nullable','string','max:255', Rule::unique('produits', 'code_barre')->where(fn($query) => $query->where('societe_id',auth()->user()->societe_id))->ignore($this->id),],         
                'type_produit'=>'required|max:255',          
                'nature_produit'=>'required|max:255',          
                // 'description'=>'required|max:255',          
                'categorie'=>'required|max:255',   
                // 'entrepot'=>'required|numeric',
                'fournisseur'=>'required|max:255',          
                'prix_achat'=>'required|numeric',          
                'prix_vente'=>'required|numeric',          
                'prix_vente_min'=>'required|numeric',          
                'tva'=>'required|numeric',          
                'limite_stock_alerte'=>'required|numeric',          
                'pays_origine'=>'required|max:255',          
                // 'date_peremption'=>'required',          
                'responsable_achat'=>'max:255',          
                'etat'=>'required|numeric',          
            ]); 
        }        
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_produit;
            if($autoriser == 1){      
                // dd($this->entrepot);
                // pas enregistrement image en BD
                Produit::find($this->id)->update(['nom_produit'=>$this->nom_produit,'reference'=>$this->reference,'code_barre'=>$this->code_barre,'type_produit'=>$this->type_produit,'nature_produit'=>$this->nature_produit,'description'=>$this->description,
                                'fournisseur'=>$this->fournisseur,'prix_achat'=>$this->prix_achat,'prix_vente'=>$this->prix_vente,'prix_vente_min'=>$this->prix_vente_min,
                                'categorie'=>$this->categorie,'entrepot'=>$this->entrepot,'tva'=>$this->tva,'limite_stock_alerte'=>$this->limite_stock_alerte,'pays_origine'=>$this->pays_origine,'date_peremption'=>$this->date_peremption,
                                'responsable_achat'=>$this->responsable_achat,'etat'=>$this->etat,'quantite_pv'=>$this->quantite_pv, 'montant_total'=>$this->montant_total,
                                'nom_user_modif'=>auth()->user()->name,'user_id_modif'=>auth()->user()->id]);

                Stock::where('id_produit',$this->id)->update(['nom_produit'=>$this->nom_produit,'reference'=>$this->reference,'code_barre'=>$this->code_barre,'categorie'=>$this->categorie,
                'prix_vente_unitaire'=>$this->prix_vente,'prix_vente_min'=>$this->prix_vente_min,'limite_stock_alerte'=>$this->limite_stock_alerte,'type_produit'=>$this->type_produit,
                'nature_produit'=>$this->nature_produit,'categorie'=>$this->categorie,'etat'=>$this->etat]);  

                if($this->type_produit == 'Produit'){

                    $stockLigne = Stock::where('societe_id',auth()->user()->societe_id)->where('id_produit',$this->id)->get(); 
                    foreach($stockLigne as $stockLignes){ 
                        $id_stock = $stockLignes->id;
                        $valeur_vente_total = $stockLignes->prix_vente_unitaire * $stockLignes->quantite; 
                        
                        Stock::where('id',$id_stock)->update(['valeur_vente_total'=>$valeur_vente_total,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                    }
                }

                $base_prix = 'HT';
                PrixVente::create(['id_produit'=>$this->id,'base_prix'=>$base_prix,'taux_taxe'=>$this->tva,'prix_achat'=>$this->prix_achat,'prix_vente'=>$this->prix_vente,'prix_vente_min'=>$this->prix_vente_min,
                                'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);               
                
                
                // mise a jour nom du produit partout  
                Mouvement::where('id_produit',$this->id)->update(['nom_produit'=>$this->nom_produit,'reference'=>$this->reference]);   
                ExpeditionClientLigne::where('id_produit',$this->id)->update(['produit'=>$this->nom_produit]);   
                factureClientLigne::where('id_produit',$this->id)->update(['produit'=>$this->nom_produit]);   
                InventaireLigne::where('id_produit',$this->id)->update(['produit'=>$this->nom_produit]);   
                TransfertLigne::where('id_produit',$this->id)->update(['produit'=>$this->nom_produit]);                   
                                
                $id_activite = $this->id;
                $page = 'Produits';
                LogActivity::addToLog($this->type_produit.' » '.$this->nom_produit.' modifié', $id_activite, $page);  
                $this->dispatch('alert',                    
                    title:$this->nom_produit.' modifié(e)!',
                    timer:3000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                );     
                // $this->dispatch('produitUpdate');              
                $this->redirect('/detail_product?id='.$this->id.'&active=4&champ=1-1&choix=2', navigate: true);
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
    // Modifier image du produit
    public function valider_img(){
        $this->validate([                      
            'image'=>'required|image|mimes:jpeg,jpg,png,gif|max:2048',          
        ]);
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_produit;
            if($autoriser == 1){ 
            
                if($this->id){     
                    $path = $this->image->store('img_produit','public');
                    Produit::find($this->id)->update(['image'=>$path,'nom_user_modif'=>auth()->user()->name,'user_id_modif'=>auth()->user()->id]);
                    Stock::where('id_produit',$this->id)->update(['image'=>$path]);  
                    $this->dispatch('imageUpdate');

                    $id_activite = $this->id;
                    $page = 'Produits';
                    LogActivity::addToLog('Image produit changée', $id_activite, $page);  
                    $this->dispatch('alert',                    
                        title:'Image modifié(e)!',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    ); 
                    $this->redirect('/detail_product?id='.$this->id.'&active=4&champ=1-1&choix=2', navigate: true);
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
    // suppression historique produit
    public function supprimerPrix($id, $ide){
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->supprimer_produit;
            if($autoriser == 1){   
                if($id){
                    PrixVente::where('id',$id)->delete();
                    // $id_activite = 0;
                    // $page = 'Produits';
                    // LogActivity::addToLog('Produit supprimé', $id_activite, $page);  
                    $this->redirect('/detail_product?id='.$ide, navigate: true);  
                    $this->dispatch('alert',                    
                        title:'Suppression effectuée!',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );  
                    // $this->redirect('/detail_product?id='.$ide, navigate: true);                            
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
    public function ajuster(int $id){
        $this->ouvre = $id;
        $prod = Produit::where('id',$id)->first(); 
        $this->id = $prod->id;
        $this->nom_produit = $prod->nom_produit;
        $this->libele_mouvement = 'Correction du stock produit '.substr($this->nom_produit,0,28);
        $this->code_mouvement = date('YmdHis');
    }
    public function corriger(){
        $this->validate([            
             'nom_entrepot'=>'required|numeric',  // id_entrepot         
             'prix_achats'=>'required|numeric',             
             'libele_mouvement'=>'max:255',
             'sens_stock'=>'required',
             'quantite'=>'required|numeric',
             'code_mouvement'=>'max:255',
               
        ]);    
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->correction_stock;
            if($autoriser == 1){                 
                    $produit = Produit::where('societe_id',auth()->user()->societe_id)->where('id',$this->id)->first();
                    $id_produit = $produit->id;
                    $nom = $produit->nom_produit;
                    $reference = $produit->reference;
                    $code_barre = $produit->code_barre;
                    $prix_achat_bd = $produit->prix_achat;
                    $prix_vente = $produit->prix_vente;
                    $prix_vente_min = $produit->prix_vente_min;
                    $limite_stock_alerte_bd = $produit->limite_stock_alerte;
                    $categorie = $produit->categorie;
                    $image = $produit->image;
                    $nature_produit = $produit->nature_produit;
                    $type_produit = $produit->type_produit;
                    
                    
                    $entrepo = Entrepot::where('societe_id',auth()->user()->societe_id)->where('id',$this->nom_entrepot)->first();
                    $this->id_entrepot = $entrepo->id;
                    $this->nom_entrepot = $entrepo->nom;
                   
                    if($this->sens_stock == 'Ajouter'){                    
                        
                        $test = Stock::where('societe_id',auth()->user()->societe_id)->where('id_entrepot',$this->id_entrepot)->where('id_produit',$this->id)->count(); 
                        if($test > 0){
                            $stoc = Stock::where('societe_id',auth()->user()->societe_id)->where('id_entrepot',$this->id_entrepot)->where('id_produit',$this->id)->first();                         
                            $id_stock = $stoc->id;
                            $quantite_stock = $stoc->quantite;

                            // Calcul CUMP                       
                            if($this->prix_achats > $prix_achat_bd || $this->prix_achats <> 0 ){ 
                                $valeurInitiale = ($prix_achat_bd * $quantite_stock);
                                $valeurEntree = ($this->prix_achats * $this->quantite);
                                $stockGlobal = $quantite_stock + $this->quantite;
                                if($stockGlobal <= 0){                                
                                    $prix_moyen_pondere_achat = 0;
                                    $valorisation_achat_total = $stockGlobal * $prix_moyen_pondere_achat; // valorisation achat total
                                    $valeur_vente_total = $prix_vente * $stockGlobal;  // valeur vente total
                                    $prix_achat_ok = $this->prix_achats;                                    
                                }
                                else{ 
                                    $prix_moyen_pondere_achat = ($valeurInitiale + $valeurEntree) / $stockGlobal; //CUMP
                                    $valorisation_achat_total = $stockGlobal * $prix_moyen_pondere_achat; // valorisation achat total
                                    $valeur_vente_total = $prix_vente * $stockGlobal;  // valeur vente total
                                    $prix_achat_ok = $this->prix_achats;
                                } 
                                
                                Stock::where('id',$id_stock)->update(['quantite'=>$stockGlobal,'prix_achat_last'=>$prix_achat_ok, 'prix_moyen_pondere_achat'=>$prix_moyen_pondere_achat,'categorie'=>$categorie,'image'=>$image,
                                        'valorisation_achat_total'=>$valorisation_achat_total,'prix_vente_unitaire'=>$prix_vente,'valeur_vente_total'=>$valeur_vente_total,'prix_vente_min'=>$prix_vente_min,'limite_stock_alerte'=>$limite_stock_alerte_bd,
                                        'type_produit'=>$type_produit,'nature_produit'=>$nature_produit,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                                Produit::where('id',$this->id)->update(['prix_achat'=>$prix_moyen_pondere_achat]); // Mise a jour Prix achat avec PMP                                
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
                                
                                Stock::where('id',$id_stock)->update(['quantite'=>$stockGlobal,'prix_achat_last'=>$prix_achat_ok, 'prix_moyen_pondere_achat'=>$prix_moyen_pondere_achat,'categorie'=>$categorie,'image'=>$image,
                                        'valorisation_achat_total'=>$valorisation_achat_total,'prix_vente_unitaire'=>$prix_vente,'valeur_vente_total'=>$valeur_vente_total,'prix_vente_min'=>$prix_vente_min,'limite_stock_alerte'=>$limite_stock_alerte_bd,
                                        'type_produit'=>$type_produit,'nature_produit'=>$nature_produit,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                                Produit::where('id',$this->id)->update(['prix_achat'=>$prix_moyen_pondere_achat]); // Mise a jour Prix achat avec PMP
                            }
                            
                        }
                        else{                       
                            if($this->prix_achats > $prix_achat_bd || $this->prix_achats <> 0 ){
                                $prix_moyen_pondere_achat = $this->prix_achats; 
                                $valorisation_achat_total = $this->quantite * $prix_moyen_pondere_achat; // valorisation achat total 
                                $valeur_vente_total = $this->quantite * $prix_vente; // valeur vente total
                                $prix_achat_ok = $this->prix_achats;
                                Stock::create(['id_entrepot'=>$this->id_entrepot,'nom_produit'=>$nom,'id_produit'=>$id_produit,'reference'=>$reference,'code_barre'=>$code_barre,'quantite'=>$this->quantite,'prix_achat_last'=>$this->prix_achats,
                                'prix_moyen_pondere_achat'=>$prix_moyen_pondere_achat,'categorie'=>$categorie,'image'=>$image,
                                'valorisation_achat_total'=>$valorisation_achat_total,'prix_vente_unitaire'=>$prix_vente,'valeur_vente_total'=>$valeur_vente_total,'limite_stock_alerte'=>$limite_stock_alerte_bd,
                                'type_produit'=>$type_produit,'nature_produit'=>$nature_produit,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                                Produit::where('id',$this->id)->update(['prix_achat'=>$prix_moyen_pondere_achat]); // Mise a jour Prix achat avec PMP
                            }  
                            else{                         
                                $prix_moyen_pondere_achat = $prix_achat_bd; 
                                $prix_achats = $prix_achat_bd;  // Prix qui vient de A BD
                                $valorisation_achat_total = $this->quantite * $prix_achat; // valorisation achat total
                                $valeur_vente_total = $this->quantite * $prix_vente; // valeur vente total
                                $prix_achat_ok = $prix_achat_bd;
                                Stock::create(['id_entrepot'=>$this->id_entrepot,'nom_produit'=>$nom,'id_produit'=>$id_produit,'reference'=>$reference,'code_barre'=>$code_barre,'quantite'=>$this->quantite,'prix_achat_last'=>$prix_achats, 
                                'prix_moyen_pondere_achat'=>$prix_moyen_pondere_achat,'categorie'=>$categorie,'image'=>$image,
                                'valorisation_achat_total'=>$valorisation_achat_total,'prix_vente_unitaire'=>$prix_vente,'valeur_vente_total'=>$valeur_vente_total,'prix_vente_min'=>$prix_vente_min,'limite_stock_alerte'=>$limite_stock_alerte_bd,
                                'type_produit'=>$type_produit,'nature_produit'=>$nature_produit,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                                Produit::where('id',$this->id)->update(['prix_achat'=>$prix_moyen_pondere_achat]); // Mise a jour Prix achat avec PMP
                            } 
                        }                  
                        
                        Mouvement::create(['id_entrepot'=>$this->id_entrepot,'nom_produit'=>$nom,'id_produit'=>$id_produit,'reference'=>$reference,'quantite'=>$this->quantite,'libele_mouvement'=>$this->libele_mouvement,'code_mouvement'=>$this->code_mouvement,'origine'=>$this->origine,
                                            'entrepot'=>$this->nom_entrepot,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                         // Ceci pour mettre a jour les valeur dans l'entrepot
                         $stockPieceCount = Stock::where('societe_id',auth()->user()->societe_id)->where('id_entrepot',$this->id_entrepot)->sum('quantite');
                         $valorisation_achat_total = Stock::where('societe_id',auth()->user()->societe_id)->where('id_entrepot',$this->id_entrepot)->sum('valorisation_achat_total'); 
                         $valeurVenteTotal = Stock::where('societe_id',auth()->user()->societe_id)->where('id_entrepot',$this->id_entrepot)->sum('valeur_vente_total'); 

                         Entrepot::find($this->id_entrepot)->update(['stock_total'=>$stockPieceCount,'valorisation_achat_total'=>$valorisation_achat_total,'valeur_vente_total'=>$valeurVenteTotal,'nom_user'=>auth()->user()->name]);
                                                            
                        $id_activite = $this->id;
                        $page = 'Produits';
                        LogActivity::addToLog('Correction de stock »'.$nom.' (+'.$this->quantite.')', $id_activite, $page);  
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
                        
                        $test = Stock::where('societe_id',auth()->user()->societe_id)->where('id_entrepot',$this->id_entrepot)->where('id_produit',$this->id)->count(); 
                        if($test > 0){
                            $stoc = Stock::where('societe_id',auth()->user()->societe_id)->where('id_entrepot',$this->id_entrepot)->where('id_produit',$this->id)->get();                         
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

                                Stock::where('id',$id_stock)->update(['quantite'=>$stockGlobal, 'prix_moyen_pondere_achat'=>$prix_moyen_pondere_achat,
                                        'valorisation_achat_total'=>$valorisation_achat_total,'prix_vente_unitaire'=>$prix_vente,'valeur_vente_total'=>$valeur_vente_total,'prix_vente_min'=>$prix_vente_min,'categorie'=>$categorie,'image'=>$image,
                                        'type_produit'=>$type_produit,'nature_produit'=>$nature_produit,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                                Mouvement::create(['id_entrepot'=>$this->id_entrepot,'nom_produit'=>$nom,'id_produit'=>$id_produit,'reference'=>$reference,'quantite'=>-$this->quantite,'libele_mouvement'=>$this->libele_mouvement,'code_mouvement'=>$this->code_mouvement,'origine'=>$this->origine,
                                'entrepot'=>$this->nom_entrepot,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                                
                                // Ceci pour mettre a jour les valeurs dans l'entrepot
                                $stockPieceCount = Stock::where('societe_id',auth()->user()->societe_id)->where('id_entrepot',$this->id_entrepot)->sum('quantite');
                                $valorisation_achat_total = Stock::where('societe_id',auth()->user()->societe_id)->where('id_entrepot',$this->id_entrepot)->sum('valorisation_achat_total'); 
                                $valeurVenteTotal = Stock::where('societe_id',auth()->user()->societe_id)->where('id_entrepot',$this->id_entrepot)->sum('valeur_vente_total'); 

                                Entrepot::find($this->id_entrepot)->update(['stock_total'=>$stockPieceCount,'valorisation_achat_total'=>$valorisation_achat_total,'valeur_vente_total'=>$valeurVenteTotal,
                               'nom_user'=>auth()->user()->name]);
                                        $id_activite = $this->id;
                                        $page = 'Produits';
                                        LogActivity::addToLog('Correction de stock »'.$nom.' (-'.$this->quantite.')', $id_activite, $page);  
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

                                    Stock::where('id',$id_stock)->update(['quantite'=>$stockGlobal, 'prix_moyen_pondere_achat'=>$prix_moyen_pondere_achat,
                                            'valorisation_achat_total'=>$valorisation_achat_total,'prix_vente_unitaire'=>$prix_vente,'valeur_vente_total'=>$valeur_vente_total,'prix_vente_min'=>$prix_vente_min,'categorie'=>$categorie,'image'=>$image,
                                            'type_produit'=>$type_produit,'nature_produit'=>$nature_produit,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                                    

                                    // Ceci pour mettre a jour les valeurs dans l'entrepot
                                    $stockPieceCount = Stock::where('societe_id',auth()->user()->societe_id)->where('id_entrepot',$this->id_entrepot)->sum('quantite');
                                    $valorisation_achat_total = Stock::where('societe_id',auth()->user()->societe_id)->where('id_entrepot',$this->id_entrepot)->sum('valorisation_achat_total'); 
                                    $valeurVenteTotal = Stock::where('societe_id',auth()->user()->societe_id)->where('id_entrepot',$this->id_entrepot)->sum('valeur_vente_total');                   
                                    
                                    Entrepot::find($this->id_entrepot)->update(['stock_total'=>$stockPieceCount,'valorisation_achat_total'=>$valorisation_achat_total,'valeur_vente_total'=>$valeurVenteTotal,
                                    'nom_user'=>auth()->user()->name]);

                                    Mouvement::create(['id_entrepot'=>$this->id_entrepot,'nom_produit'=>$nom,'id_produit'=>$id_produit,'reference'=>$reference,'quantite'=>-$this->quantite,'libele_mouvement'=>$this->libele_mouvement,'code_mouvement'=>$this->code_mouvement,'origine'=>$this->origine,
                                    'entrepot'=>$this->nom_entrepot,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);  
                                    
                                    $this->dispatch('correctioStock');
                                    $id_activite = $this->id;
                                    $page = 'Produits';
                                    LogActivity::addToLog('Correction de stock »'.$nom.' (-'.$this->quantite.') et suppression', $id_activite, $page); 
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
        $testPrecedant = Produit::where('societe_id',auth()->user()->societe_id)->where('id','<',$id)->orderBy('id','desc')->count();
        if($testPrecedant > 0){ 
            $precedant = Produit::where('societe_id',auth()->user()->societe_id)->where('id','<',$id)->orderBy('id','desc')->first();        
            $previous = $precedant->id; 
            $this->redirect('/detail_product?id='.$previous.'&active=4&champ=1-1&choix=2', navigate: true);             
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
            $this->redirect('/detail_product?id='.$id.'&active=4&champ=1-1&choix=2', navigate: true);  // ceci evite une erreur
        }    
    }    
    public function suivant(int $id){    
        
        $testSuivant = Produit::where('societe_id',auth()->user()->societe_id)->where('id','>',$id)->orderBy('id','asc')->count();
        if($testSuivant > 0){
            $suivant = Produit::where('societe_id',auth()->user()->societe_id)->where('id','>',$id)->orderBy('id','asc')->first();
            $next = $suivant->id;             
            $this->redirect('/detail_product?id='.$next.'&active=4&champ=1-1&choix=2', navigate: true);                     
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
            $this->redirect('/detail_product?id='.$id.'&active=4&champ=1-1&choix=2', navigate: true); // ceci evite une erreur
        } 
    }
     function generateEAN13() {
        // 12 chiffres aléatoires
        // $code = str_pad(rand(0, 999999999999), 12, '0', STR_PAD_LEFT);
        $code = '';
        for ($i = 0; $i < 12; $i++) {
            $code .= random_int(0, 9);
        }

        // Calcul du chiffre de contrôle
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $sum += $code[$i] * (($i % 2 == 0) ? 1 : 3);
        }

        $checkDigit = (10 - ($sum % 10)) % 10; // garantit l’intégrité du code , évite les erreurs en caisse et est exigé par la norme EAN-13 (GS1)

        return $code . $checkDigit;
    }
    public function genererCodeBarre(){    
        $this->validate([              
            'code_barre' => ['nullable','string','max:255',
             Rule::unique('produits', 'code_barre')->where(fn($query) => $query->where('societe_id',auth()->user()->societe_id))->ignore($this->id),]
        ]);         
                
        do {   
            $this->code_barre = $this->generateEAN13(); // appel de la fonction
        } 
        while ( 
            Produit::where('code_barre', $this->code_barre)->exists()
        );
        $this->dispatch('alert',                    
            title:'Code barre généré !',
            timer:3000,
            icon:'success',
            toast:true,
            showConfirmButton: false,
            position:'top-end',
        ); 
    }
    // public function genererCodeBarre()
    // {
    //     $this->validate([        
    //         'code_barre' => ['nullable','string','max:255',
    //         Rule::unique('produits', 'code_barre')->where(fn($query) => $query->where('societe_id',auth()->user()->societe_id))->ignore($this->id),]           
    //     ]); 
        
    //     $length = 10;
    //     $resultat = substr(auth()->user()->societe, 0, 3); // recupere les 3 premiers les lettre de la societe        
    //     do {            
    //         $this->code_barre = Str::upper($resultat.'-'.Str::random($length));
    //     } 
    //     while ( 
    //         Produit::where('code_barre', $this->code_barre)->exists()
    //     );
    //     $this->dispatch('alert',                    
    //         title:'Code barre généré !',
    //         timer:3000,
    //         icon:'success',
    //         toast:true,
    //         showConfirmButton: false,
    //         position:'top-end',
    //     ); 
    // }
     public function confirmerDelete(int $id){ 
        $this->approuver = $id;
        $this->id = $id;
    } 
    public function supprimer(int $id){
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->supprimer_produit;
            if($autoriser == 1){  
                if($id){ 
                    $test_stock = Stock::where('societe_id',auth()->user()->societe_id)->where('id_produit',$id)->sum('quantite');
                    if($test_stock == 0){                        
                        $test_expedi = ExpeditionClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_produit',$id)->count();
                        if($test_expedi == 0){
                            $test_cmd = CommandeClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_produit',$id)->count();
                            if($test_cmd == 0){ 
                                $test_fact = factureClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_produit',$id)->count();
                                if($test_fact == 0){
                                    $test_prof = ProformaClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_produit',$id)->count();
                                    if($test_prof == 0){
                                        $test_compo = ComposantNomenclature::where('societe_id',auth()->user()->societe_id)->where('composant_id',$id)->count();
                                        if($test_compo == 0){ 
                                            $test_compofo = ComposantNomenclatureOrdreFab::where('societe_id',auth()->user()->societe_id)->where('composant_id',$id)->count();
                                            if($test_compofo == 0){
                                                $test_nomencl = Nomenclature::where('societe_id',auth()->user()->societe_id)->where('produit_id',$id)->count();
                                                if($test_nomencl == 0){                                                
                                                    
                                                    Produit::where('id',$id)->delete();
                                                    Stock::where('id_produit',$id)->delete();
                                                    $page = 'Produits';
                                                    LogActivityModel::where('id_activite',$id)->where('page',$page)->delete();
                                                    $id_activite = $id;
                                                    LogActivity::addToLog('Produit supprimé définitivement', $id_activite, $page);
                                                    $this->dispatch('alert',                    
                                                        title:'Suppression effectuée!',
                                                        timer:3000,
                                                        icon:'success',
                                                        toast:true,
                                                        showConfirmButton: false,
                                                        position:'top-end',
                                                    );
                                                    flash ('Le produit a été supprimé!')->success();
                                                    $this->redirect('/produit?active=4&champ=1-1&choix=2', navigate: true);
                                                }
                                                else{
                                                   $this->dispatch('alert',                    
                                                        title:'Désolé, vous ne pouvez pas supprimer un produit lié à une nomenclature!',
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
                                                    title:'Désolé, vous ne pouvez pas supprimer un produit lié à des composants Ordre Fab.!',
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
                                                title:'Désolé, vous ne pouvez pas supprimer un produit lié aux composants nomenclatures!',
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
                                            title:'Désolé, vous ne pouvez pas supprimer un produit lié à des factures!',
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
                                        title:'Désolé, vous ne pouvez pas supprimer un produit lié à des factures!',
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
                                    title:'Désolé, vous ne pouvez pas supprimer un produit lié à des commandes!',
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
                                title:'Désolé, vous ne pouvez pas supprimer un produit lié à des expéditions!',
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
                            title:'Désolé, vous ne pouvez pas supprimer ce produit avec un stock (<strong>'.$test_stock.'</strong>) disponible!',
                            timer:5000,
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
}
