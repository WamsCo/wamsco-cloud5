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
use App\Models\PrixVente;
use App\Models\Stock;
use App\Models\ExpeditionClientLigne;
use App\Models\CommandeClientLigne;
use App\Models\factureClientLigne;
use App\Models\ProformaClientLigne;
use App\Models\ComposantNomenclature;
use App\Models\ComposantNomenclatureOrdreFab;
use App\Models\Nomenclature;
use Illuminate\Support\Str; // pour code barre
use Illuminate\Validation\Rule;

class Produits extends Component
{
    protected $paginationTheme = 'bootstrap';    // ceci pour pagination avec les chiffres tres important
    use WithPagination;
    use WithFileUploads;

    public $ids;    
    #[Validate('required')] 
    public $nom_produit; 

    #[Validate('required')] 
    public $reference; 
    public $code_barre; 
    
    public $type_produit;     
    public $nature_produit;     
    public $description;

    #[Validate('required')] 
    public $categorie; 

    public $entrepot; 

    #[Validate('required')] 
    public $fournisseur; 

    #[Validate('required|numeric')] 
    public $prix_achat; 

    #[Validate('required|numeric')]
    public $prix_vente; 

    #[Validate('required|numeric')] 
    public $prix_vente_min;  

    public $tva;  

    #[Validate('required|numeric')]   
    public $limite_stock_alerte; 
      
    public $pays_origine; 
    public $date_peremption;    
    public $responsable_achat;    
    public $etat; 

    // #[Validate('image|mimes:jpeg,jpg,png,gif|max:2048|nullable')] 
    public $image;
    public $quantite_pv = 0;     
    public $montant_total = 0; 
    public $devise;

    public $query;
    public $parNature;
    public $parCat;    
    public $parPage = 20;
    public $confirmer;
    public $image_produit; 
    // public array $selection = [];
    public $orderField = 'nom_produit'; 
    public $orderDirection = 'ASC'; 
    public $recherchePar = 'nom';  // (Recherche par: nom , reference)
    public $filtre; 
    public $cherche; 

    public function updatingQuery(){
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
    public function resetinputFields(){ 
        $this->nom_produit ='';
        $this->reference ='';
        $this->code_barre ='';        
        $this->etat = 1;       
        $this->description ='';        
        $this->categorie ='';
        $this->type_produit = 'Produit';          
        $this->nature_produit = 'Manufacture'; 
        $this->fournisseur ='';        
        // $this->entrepot ='';             
        $this->prix_achat = 0;      
        $this->prix_vente = 0;       
        $this->prix_vente_min = 0;
        $this->tva = 0; 
        $this->quantite_pv = 0; 
        $this->montant_total = 0; 
        $this->image ='';      
        $this->limite_stock_alerte = 5; 
        $this->pays_origine = 'Cameroon';      
        $this->date_peremption ='';        
        $this->responsable_achat ='';        
    }
    public function mount(){         
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->consulter_produit;
            if($autoriser == 0){
                alert()->error('Oups Désolé', 'Vous n\'êtes pas autorisé à ouvrir cette page !!!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
                $this->redirect('/bienvenue', navigate: true);
            }
        }
        else{
            alert()->error('Oups Désolé', 'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
            $this->redirect('/bienvenue', navigate: true);
        } 
        $this->etat = 1;
        $this->prix_achat = 0;                
        $this->prix_vente = 0;                
        $this->prix_vente_min = 0;                
        $this->tva = 0;               
        $this->pays_origine = 'Cameroon';                
        $this->limite_stock_alerte = 5;  
        $this->type_produit = 'Produit';          
        $this->nature_produit = 'Manufacturé';                
        $this->image =''; 
    }
    public function render(){

        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();
        $jourValid = $entite_mod[0]->validite_mod;
        $mod_gestion_stock = $entite_mod[0]->mod_gestion_stock;
        $soldeClient = $entite_mod[0]->solde; 
        if($dateJour <= $jourValid){      
            if($mod_gestion_stock == 1){      
                $title = 'Listing Produits | WamsCo';
                $module = 'Gestion stock';
                $title_fils = 'Produits';
                $lien = 'produit';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');      
                $dateJour = date('Y-m-d');
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px'); 
                
                $produit = Produit::where('societe',auth()->user()->societe)->where('nature_produit','like','%'.$this->parNature.'%')->where('categorie','like','%'.$this->parCat.'%')->where('nom_produit','like','%'.$this->query.'%')->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage);
                $produitCount = $produit->count();

                $listEntrepot = Entrepot::where('societe',auth()->user()->societe)->orderBy('nom','asc')->get();  
                    
                $listCategorie = Categorie::where('societe',auth()->user()->societe)->orderBy('nom_categorie','asc')->get();  
                $listFourni = Tier::where('societe',auth()->user()->societe)->where('type_tiers','Fournisseur')->where('etat', 1)->orderBy('nom','asc')->get(); 
                $listUser = Utilisateur::where('societe',auth()->user()->societe)->where('type_user','!=','Super-admin')->where('etat',1)->orderBy('name','asc')->get(); 
                $listedeviseTva = DeviseTva :: where('societe',auth()->user()->societe)->get();

                $resultat = Produit :: where('societe',auth()->user()->societe)->get();  
                $nbreTotalProduit = $resultat->count();     

                $NbreTVA = Produit::where('societe', auth()->user()->societe)->distinct('tva')->count('tva');
                $NbreProdOff = Produit::where('societe', auth()->user()->societe)->where('etat', '!=', 1)->count();
                $NbreProdSansCodeBarre = Produit::where('societe', auth()->user()->societe)->where(function ($query) { $query->whereNull('code_barre')->orWhere('code_barre', ''); })->count();

                $derniereActivite = Produit::where('societe',auth()->user()->societe)->latest('updated_at')->first(); 

                $page = 'Produits'; // pour evenement lies
                $log = LogActivityModel::where('user_societe',auth()->user()->societe)->where('page', $page)->limit(20)->orderBy('id','desc')->get();
                $logCount = $log->count();
            
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
                return view('livewire.gestion-stock.produits.produits',compact('title_fils','module','lien','dateJour','produit','produitCount','listEntrepot','listCategorie','listFourni','listUser','listedeviseTva','nbreTotalProduit',
                'NbreTVA','NbreProdOff','NbreProdSansCodeBarre','derniereActivite','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
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
        if($this->type_produit == 'Produit'){
            $this->validate([                      
                'nom_produit'=>'required|max:255',          
                'reference'=>'required|max:255',  
                // 'code_barre'=>'nullable|max:255',
                'code_barre' => ['nullable','string','max:255', Rule::unique('produits', 'code_barre')->where(fn($query) => $query->where('societe', auth()->user()->societe)),],                         
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
                // 'code_barre'=>'nullable|max:2', 
                'code_barre' => ['nullable','string','max:255', Rule::unique('produits', 'code_barre')->where(fn($query) => $query->where('societe', auth()->user()->societe)),],       
                'type_produit'=>'required|max:255',          
                'nature_produit'=>'required|max:255',          
                // 'description'=>'required|max:255',          
                'categorie'=>'required|max:255',   
                // 'entrepot'=>'required|max:255',
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
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_produit;
            if($autoriser == 1){   
                    // pas enregistrement image en BD                    
                    Produit::create(['nom_produit'=>$this->nom_produit,'reference'=>$this->reference,'code_barre'=>$this->code_barre,'type_produit'=>$this->type_produit,'nature_produit'=>$this->nature_produit,'description'=>$this->description,
                                    'fournisseur'=>$this->fournisseur,'prix_achat'=>$this->prix_achat,'prix_vente'=>$this->prix_vente,'prix_vente_min'=>$this->prix_vente_min,'entrepot'=>$this->entrepot,
                                    'categorie'=>$this->categorie,'tva'=>$this->tva,'limite_stock_alerte'=>$this->limite_stock_alerte,'pays_origine'=>$this->pays_origine,'date_peremption'=>$this->date_peremption,
                                    'responsable_achat'=>$this->responsable_achat,'etat'=>$this->etat,'quantite_pv'=>$this->quantite_pv, 'montant_total'=>$this->montant_total,'societe'=>auth()->user()->societe,
                                    'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                    // ceci recupere le dernier enregistrement cree a l'instant
                    $dernier_id = Produit::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id; 

                    // Creation entrepot dans stock
                    if($this->entrepot > 0){
                        $quantite = 0;
                        $valorisation_achat_total = 0;
                        $valeur_vente_total = 0;
                        $limite_stock_alerte_bd = 5;
                        Stock::create(['id_entrepot'=>$this->entrepot,'nom_produit'=>$this->nom_produit,'id_produit'=>$dernier_id,'reference'=>$this->reference,'code_barre'=>$this->code_barre,'categorie'=>$this->categorie,'type_produit'=>$this->type_produit,'nature_produit'=>$this->nature_produit,'quantite'=>$quantite,
                        'prix_achat_last'=>$this->prix_achat, 'prix_moyen_pondere_achat'=>$this->prix_achat, 'valorisation_achat_total'=>$valorisation_achat_total,'prix_vente_unitaire'=>$this->prix_vente,'prix_vente_min'=>$this->prix_vente_min,'valeur_vente_total'=>$valeur_vente_total,
                        'limite_stock_alerte'=>$limite_stock_alerte_bd,'etat'=>$this->etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                    }
                    $base_prix = 'HT';
                    PrixVente::create(['id_produit'=>$dernier_id,'base_prix'=>$base_prix,'taux_taxe'=>$this->tva,'prix_achat'=>$this->prix_achat,'prix_vente'=>$this->prix_vente,'prix_vente_min'=>$this->prix_vente_min,
                                'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 

                    $id_activite = $dernier_id;
                    $page = 'Produits';
                    LogActivity::addToLog('Produit » '.$this->nom_produit.' créé', $id_activite, $page);  
                    $this->dispatch('alert',                    
                        title:'Produit ('.$this->nom_produit.') enregistré!',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );  
                    flash ('Produit » <strong>'.$this->nom_produit.'</strong> créé!')->success();
                    $this->resetinputFields();  
                    $this->redirect('/detail_product?id='.$dernier_id.'&active=4&champ=1-1&choix=2', navigate: true);  
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
    function generateEAN13() {
        // 12 chiffres aléatoires
        // $code = str_pad(rand( 0, 999999999999), 12, '0', STR_PAD_LEFT); 
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
             Rule::unique('produits', 'code_barre')->where(fn($query) => $query->where('societe', auth()->user()->societe)),]
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
    public function changeEtat(int $id, int $etat){  
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_produit;
            if($autoriser == 1){        
                if($etat == 1){
                    $ferme = 0;
                    Produit::find($id)->update(['etat'=>$ferme,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                    Stock::where('id_produit',$id)->update(['etat'=>$ferme,]); 
                    $id_activite = $id;
                    $page = 'Produits';
                    LogActivity::addToLog('Etat produit » Fermé', $id_activite, $page); 
                    $this->dispatch('alert',                    
                        title:'Produit désactivé avec succès!',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );  
                }
                elseif($etat == 0){
                    $ouvert = 1;
                    Produit::find($id)->update(['etat'=>$ouvert,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                    Stock::where('id_produit',$id)->update(['etat'=>$ouvert,]);  
                    $id_activite = $id;
                    $page = 'Produits';
                    LogActivity::addToLog('Etat produit » Ouvert', $id_activite, $page); 
                    $this->dispatch('alert',                    
                        title:'Produit activé avec succès!',
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
    // ceci permet de dupliquer un produit
    public function dupliquer(int $id){ 
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_produit;
            if($autoriser == 1){        
                $produit = Produit::find($id);
                $new_produit = $produit->replicate();
                $new_produit->quantite_pv = 0; 
                $new_produit->montant_total = 0;  
                $new_produit->user_id_modif = NULL;  
                $new_produit->nom_user_modif = NULL ;  
                $new_produit->etat = 0; 
                $new_produit->save();

                $id_activite = $id;
                $page = 'Produits';
                LogActivity::addToLog('Duplication produit', $id_activite, $page); 
                $this->dispatch('alert',                    
                    title:'Duplication effectuée avec succès!',
                    timer:3000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                );  
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
            $autoriser = $role[0]->supprimer_produit;
            if($autoriser == 1){  
                if($id){ 
                    $test_stock = Stock::where('societe',auth()->user()->societe)->where('id_produit',$id)->sum('quantite');
                    if($test_stock == 0){                        
                        $test_expedi = ExpeditionClientLigne::where('societe',auth()->user()->societe)->where('id_produit',$id)->count();
                        if($test_expedi == 0){
                            $test_cmd = CommandeClientLigne::where('societe',auth()->user()->societe)->where('id_produit',$id)->count();
                            if($test_cmd == 0){ 
                                $test_fact = factureClientLigne::where('societe',auth()->user()->societe)->where('id_produit',$id)->count();
                                if($test_fact == 0){
                                    $test_prof = ProformaClientLigne::where('societe',auth()->user()->societe)->where('id_produit',$id)->count();
                                    if($test_prof == 0){
                                        $test_compo = ComposantNomenclature::where('societe',auth()->user()->societe)->where('composant_id',$id)->count();
                                        if($test_compo == 0){ 
                                            $test_compofo = ComposantNomenclatureOrdreFab::where('societe',auth()->user()->societe)->where('composant_id',$id)->count();
                                            if($test_compofo == 0){
                                                $test_nomencl = Nomenclature::where('societe',auth()->user()->societe)->where('produit_id',$id)->count();
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
