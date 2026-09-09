<?php

namespace App\Livewire\Fabrication;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
Use Carbon\Carbon;
use Livewire\Attributes\Validate; 
use App\Helpers\LogActivity;
use App\Models\LogActivity as LogActivityModel;
use Livewire\WithPagination;
use Livewire\WithFileUploads; 
use App\Models\Role;
use App\Models\Produit;
use App\Models\Tier;
use App\Models\Utilisateur;
use App\Models\DeviseTva;
use App\Models\Entite;
use App\Models\Nomenclature;
use App\Models\ComposantNomenclatureOrdreFab;
use App\Models\OrdreFabrication;
use App\Models\MagConsoComposantOf;
use App\Models\ComposantNomenclature;
use App\Models\Entrepot;
use App\Models\Stock;

class ListeOrdreFabrication extends Component
{
    protected $paginationTheme = 'bootstrap';
    use WithPagination;
    use WithFileUploads;

    public $id; 
    public $confirmer;
    public $query;
    public $parEtat; 
    public $parUser;   
    public $parRef;     
    public $parPage = 20;  
    public $date_debut; 
    public $date_fin;
    
    public $ids;
    public $ouvre = 0;

    public $nomenclatures; 
    public $libelle; 
    public $type_nomencla = 'Fabrication'; 
    public $produit_a_fabrique; 
    public $produit_id; 
    public $quantite;
    public $unite_mesure = 'Unité(s)';
    public $duree;     
    public $entrepot_fabrication; 
    public $note;
    public $responsable;
    public $tiers;
    public $code;
    public $date_entree;
    public $date_sortie;
    

    public $quantite_formule;
     // pour composant 
    public $choix_composant; 
    public $magasin; 
    public $unite = 'Unité(s)';  
    public $quantite_composant = 1;  
    // fin

    public $autoriser; // pour gerer les marges
    public $orderField = 'id'; 
    public $orderDirection = 'ASC'; 

    public function resetinputFields(){           
        $this->choix_composant = '';   
        $this->magasin = '';            
        $this->quantite_composant = 1;        
        $this->unite = 'Unité(s)'; 
    }
    public function resetinputFields2(){ 
        $this->nomenclatures ='';
        $this->libelle ='';
        $this->quantite ='';
        $this->duree = '';
        $this->entrepot_fabrication = '';
        $this->responsable ='';
        $this->tiers ='';
        $this->note ='';
        $this->date_debut = date('Y-m-d H:i');  
        $this->date_fin = date('Y-m-d H:i');
    } 
    public function onDataAjout(){
        $this->reset('ouvre');
    } 
    public function ajoutLigne(int $idd){
        $this->ouvre = $idd;
    } 
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
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $this->autoriser = $role[0]->voir_marge;
            $autoriser = $role[0]->liste_ordre_fab;
            if($autoriser == 0){
                alert()->error('Oups Désolé', 'Vous n\'êtes pas autorisé à ouvrir cette page !!!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
                $this->redirect('/bienvenue', navigate: true);
            }
        }
        else{
            alert()->error('Oups Désolé', 'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
            $this->redirect('/bienvenue', navigate: true);
        } 

        // $this->date_debut = date('Y-m-d', strtotime('-1 month'));  // ceci pour affiche toutes les sessions en permanance sur 1 mois par defaut
        $this->date_debut = date('Y-m-d', strtotime('-1 year')); // ceci pour affiche toutes les sessions en permanance sur 1 an par defaut
        $this->date_fin = date('Y-m-d'); 

        $this->date_entree = date('Y-m-d H:i');  
        $this->date_sortie = date('Y-m-d H:i');
    }
    public function render()
    {
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('id',auth()->user()->societe_id)->get();
        $jourValid = $entite_mod[0]->validite_mod;
        $mod_fabrication = $entite_mod[0]->mod_fabrication;
        $soldeClient = $entite_mod[0]->solde; 
        if($dateJour <= $jourValid){
            if($mod_fabrication == 1){
                $title = 'Liste ordre fabrication | WamsCo';
                $module = 'Gestion fabrication';
                $title_fils = 'Liste ordre fabrication';
                $lien = 'listing_ordre?active=9&champ=2-1&choix=2';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');      
                $dateJour = date('Y-m-d');
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');

                $start = Carbon::parse($this->date_debut)->startOfDay(); //2016-09-29 00:00:00.000000
                $end = Carbon::parse($this->date_fin)->endOfDay();     // 2016-09-29 23:59:59.000000

                // if(empty($this->parEtat) && empty($this->parUser)){
                if(!empty($this->parUser)){
                    $listeOrdreFab = OrdreFabrication::where('societe_id',auth()->user()->societe_id)->where('ref_ordre','like','%'.$this->parRef.'%')->where('produit_a_fabrique','like','%'.$this->query.'%')->where('user_id',$this->parUser)->whereBetween('created_at',[$start, $end])->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage);
                }
                else{ 
                    $listeOrdreFab = OrdreFabrication::where('societe_id',auth()->user()->societe_id)->where('ref_ordre','like','%'.$this->parRef.'%')->where('produit_a_fabrique','like','%'.$this->query.'%')->whereBetween('created_at',[$start, $end])->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage);
                }
                $listelisteOrdreFabCount = $listeOrdreFab->count();            
                
                $utilisat = Utilisateur::where('societe_id',auth()->user()->societe_id)->orderBy('name','asc')->get();   

                $resultat = OrdreFabrication::where('societe_id',auth()->user()->societe_id)->get();  
                $nbreTotalOrdre = $resultat->where('statut','Terminé')->count();     
                $nbreTotalOrdreTotal = $resultat->count();

                // select nomenclature
                $listeNomenclatur = Nomenclature::where('societe_id',auth()->user()->societe_id)->where('etat',1)->orderBy('libelle')->get();
                $produit = Produit::where('societe_id',auth()->user()->societe_id)->where('nature_produit','!=','Matière première')->where('etat',1)->orderBy('nom_produit')->get();
                $entrepot = Entrepot::where('societe_id',auth()->user()->societe_id)->where('active',1)->orderBy('nom','asc')->get();
                $composant_produit = Produit::where('societe_id',auth()->user()->societe_id)->where('nature_produit','!=','Manufacturé')->where('etat',1)->orderBy('nom_produit')->get();
                $utilisateur = Utilisateur::where('societe_id',auth()->user()->societe_id)->where('etat',1)->orderBy('name','asc')->get();
                $tier = Tier::where('societe_id',auth()->user()->societe_id)->where('etat',1)->orderBy('nom','asc')->get();
                
                $test_nomen = Nomenclature::where('id',$this->nomenclatures)->count();    
                if($test_nomen > 0){
                    $nomen = Nomenclature::where('id',$this->nomenclatures)->first();               
                    $this->ids = $nomen->id;
                    $this->code = $nomen->code;
                    $this->libelle = $nomen->libelle;
                    $this->type_nomencla = $nomen->type_nomencla;
                    $this->produit_id = $nomen->produit_id; // recupere id nomencla
                    $this->produit_a_fabrique = $nomen->produit_a_fabrique; // libelle produit
                    $this->quantite = $nomen->quantite;
                    $this->unite_mesure = $nomen->unite_mesure;
                    $this->duree = $nomen->duree;
                    $this->entrepot_fabrication = $nomen->id_entrepot;
                    $this->note = $nomen->description;
                    $this->quantite_formule = $nomen->quantite; // ceci pour formule
                } 
                $listeComposant = ComposantNomenclature::where('societe_id',auth()->user()->societe_id)->where('nomencla_id',$this->nomenclatures)->orderBy($this->orderField, $this->orderDirection)->get();
                $ComposantCount = ComposantNomenclature::where('societe_id',auth()->user()->societe_id)->where('nomencla_id',$this->nomenclatures)->count();
                // Fin

                // select
                $listEntrepot = Entrepot::where('societe_id',auth()->user()->societe_id)->orderBy('nom','asc')->where('active',1)->get(); 
                $stock = Stock::where('societe_id',auth()->user()->societe_id)->where('id_produit',$this->choix_composant)->where('type_produit','Produit')->where('etat',1)->get();  
                // Fin select

                $derniereActivite = OrdreFabrication::where('societe_id',auth()->user()->societe_id)->latest('updated_at')->first();
                
                $page = 'OrdreFabrication'; // Pour evenement lie
                $log = LogActivityModel::where('user_societe',auth()->user()->societe)->where('page', $page)->limit(50)->orderBy('id','desc')->get();
                $logCount = $log->count();
                
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
                return view('livewire.fabrication.liste-ordre-fabication',compact('title_fils','module','lien','dateJour','listeOrdreFab','listelisteOrdreFabCount','utilisat',
                'nbreTotalOrdre','nbreTotalOrdreTotal','listeNomenclatur','entrepot','composant_produit','tier','utilisateur','listeComposant','ComposantCount','listEntrepot','stock','derniereActivite','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
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
    public function ajoutComposant(){
        $this->validate([
            'choix_composant'=>'required|numeric',    // id produit
            'magasin'=>'required|numeric',            // id entrepot
            'quantite_composant'=>'required|numeric',
            'unite'=>'required|max:10',            
        ]);
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->ajouter_composant;
            if($autoriser == 1){       
               
                $compos = Produit::where('societe_id',auth()->user()->societe_id)->where('id',$this->choix_composant)->first();
                $composant_id = $compos->id;                
                $nom_composant = $compos->nom_produit;                
                $cout = $compos->prix_achat;

                $mag = Entrepot::where('societe_id',auth()->user()->societe_id)->where('id',$this->magasin)->first();
                $id_magasin = $mag->id;                
                $nom_magasin = $mag->nom;  

                if($id_magasin){
                    $quantite_consommer = 0;
                    $coutFinal = $cout * $this->quantite_composant; 
                    ComposantNomenclature::create(['composant'=>$nom_composant,'composant_id'=>$composant_id,'nomencla_id'=>$this->ids,'quantite'=>$this->quantite_composant,
                        'id_entrepot'=>$id_magasin,'nom_entrepot'=>$nom_magasin,'cout'=>$coutFinal,'unite'=>$this->unite,'quantite_consommer'=>$quantite_consommer,'societe'=>auth()->user()->societe,
                        'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                    $id_activite = $this->ids;  
                    $page = 'Nomenclature';    
                    LogActivity::addToLog('Composant nomenclature » '.$nom_composant.' ajouté', $id_activite, $page); 
                    $this->dispatch('alert',                    
                        title:'Composant nomenclature » <strong>'.$nom_composant.'</strong> ajouté',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    ); 
                    $this->resetinputFields();
                    // $this->redirect('/detail_nomencla?id='.$this->ids.'&active=9&champ=1-1&choix=2', navigate: true); // ceci evite une erreur
                }
                else{
                    $this->dispatch('alert',                    
                        title:'Désolé, veuillez renseigner l\'entrepôt de ce produit svp !',
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
     public function confirmerDelete(int $id){
        $this->confirmer = $id;        
    } 
    public function supprimer(int $id){
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->supprimer_composant;
            if($autoriser == 1){   
                if($id){ 
                    ComposantNomenclature::where('id',$id)->delete();
                    $id_activite = $this->ids;  
                    $page = 'Nomenclature';    
                    LogActivity::addToLog('Composant nomenclature supprimé', $id_activite, $page);
                    $this->dispatch('alert',                    
                        title:'Composant nomenclature supprimé',
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
    public function creation(){ 
        $this->validate([
            'nomenclatures'=>'required|numeric',
            'libelle'=>'required|max:255',
            'quantite'=>'required|numeric',            
            'unite_mesure'=>'required|max:10',            
            'duree'=>'required|max:5',            
            'entrepot_fabrication'=>'required|max:255',   // id entrepot         
            'date_entree'=>'required',            
            'date_sortie'=>'required', 
            'responsable'=>'max:25',                        
            'tiers'=>'max:25',                        
            'note'=>'max:255',                        
        ]);
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_ordre_fab;
            if($autoriser == 1){   
                $test_composant = ComposantNomenclature ::where('societe_id',auth()->user()->societe_id)->where('nomencla_id',$this->nomenclatures)->count();
                if($test_composant > 0){
                    $date = date('dmy');
                    $length = 3;
                    $token = bin2hex(random_bytes($length));
                    $token_ok = 'OF/'.$date.'/'.$token; 
                    $statut = 'Validé (à fabriquer)';
                    $quantite_fabrique = 0;
                    $coutTotal = 0; 
                    
                    // recuperer id et nom entrepot
                    $entrepo = Entrepot::where('societe_id',auth()->user()->societe_id)->where('id',$this->entrepot_fabrication)->where('active',1)->orderBy('nom')->first();
                    $entrepo_id =$entrepo->id;
                    $nom_entrepot = $entrepo->nom;


                    $test_users = Utilisateur::where('societe_id',auth()->user()->societe_id)->where('id',$this->responsable)->where('etat',1)->orderBy('name')->count();
                    if($test_users > 0){
                        $users = Utilisateur::where('societe_id',auth()->user()->societe_id)->where('id',$this->responsable)->where('etat',1)->orderBy('name')->first();
                        $id_respo = $users->id;
                        $nom_respo = $users->name;
                    }
                    else{
                        $id_respo = NULL;
                        $nom_respo = NULL;
                    }

                    $test_tier = Tier::where('societe_id',auth()->user()->societe_id)->where('id',$this->tiers)->where('etat',1)->orderBy('nom')->count();
                    if($test_tier > 0){
                        $tie = Tier::where('societe_id',auth()->user()->societe_id)->where('id',$this->tiers)->where('etat',1)->orderBy('nom')->first();
                        $id_tier = $tie->id;
                        $nom_tier = $tie->nom;
                    }
                    else{
                        $id_tier = NULL;
                        $nom_tier = NULL;
                    }
                        
                    $ordreFab = OrdreFabrication::create(['ref_ordre'=>$token_ok,'produit_a_fabrique'=>$this->produit_a_fabrique,'produit_id'=>$this->produit_id,'type_nomencla'=>$this->type_nomencla,
                    'nomencla_id'=>$this->nomenclatures,'code_nomencla'=>$this->code,'libelle'=>$this->libelle,'quantite'=>$this->quantite,'quantite_fabrique'=>$quantite_fabrique,'cout_total'=>$coutTotal,
                    'unite_mesure'=>$this->unite_mesure,'duree'=>$this->duree,'entrepot_fabrication'=>$nom_entrepot,'id_entrepot'=>$this->entrepot_fabrication,'date_debut'=>$this->date_entree,
                    'date_fin'=>$this->date_sortie,'statut'=>$statut,'responsable'=>$nom_respo,'responsable_id'=>$id_respo,'tiers'=>$nom_tier,'tiers_id'=>$id_tier,'description'=>$this->note,'societe'=>auth()->user()->societe,
                    'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                    // ceci recupere le dernier enregistrement cree a l'instant 
                    $dernier_id = $ordreFab->id;
                                                            
                    $CompoNomenclature = ComposantNomenclature::where('societe_id',auth()->user()->societe_id)->where('nomencla_id',$this->nomenclatures)->get(); 
                    foreach($CompoNomenclature as $CompoNomenclatures){

                        /* **** calcul quantite a consommer automatique en fonction de la formule nomenclature ****
                             $this->quantite : qte a produire vient de Ordre Fab 
                             $CompoNomenclatures->quantite : qte a consomme vient de la formule : ComposantNomenclature
                             $this->quantite_formule : qte a produit qui vient dela formule : Nomenclature

                        ********************* Fin Formule ************************ */

                        $test_product = Produit::where('id',$CompoNomenclatures->composant_id)->count(); 
                        if($test_product > 0){                            
                            $product = Produit::where('id',$CompoNomenclatures->composant_id)->first();               
                            $prix_achat = $product->prix_achat;
                        } 
                        else{
                            $prix_achat = 0;
                        }            

                        $qte_a_consommer = ($this->quantite * $CompoNomenclatures->quantite) / $this->quantite_formule;
                        $coutTotal = $prix_achat * $qte_a_consommer; 

                        // creation et copie FactureClientLigne
                        ComposantNomenclatureOrdreFab::create([ 
                            'ordre_fabrication'=>$dernier_id,
                            'ref_ordre'=>$token_ok,
                            'nomencla_id'=>$CompoNomenclatures->nomencla_id,                
                            'id_entrepot'=>$CompoNomenclatures->id_entrepot,                
                            'nom_entrepot'=>$CompoNomenclatures->nom_entrepot,                
                            'composant'=>$CompoNomenclatures->composant,
                            'composant_id'=>$CompoNomenclatures->composant_id,
                            'quantite'=>$qte_a_consommer,
                            'unite'=>$CompoNomenclatures->unite,
                            'quantite_consommer'=>$CompoNomenclatures->quantite_consommer,
                            'cout'=>$coutTotal,                            
                            'user_id'=>auth()->user()->id,
                            'nom_user'=>auth()->user()->name,
                            'societe_id'=>auth()->user()->societe_id,
                            'societe'=>auth()->user()->societe]);
                    }
                    // ceci recupere le dernier enregistrement cree a l'instant
                    // $dernier_id = OrdreFabrication::where('societe_id',auth()->user()->societe_id)->where('user_id',auth()->user()->id)->latest()->first()->id; 
                    $id_activite = $dernier_id;  
                    $page = 'OrdreFabrication';    
                    LogActivity::addToLog('Ordre fabrication » '.$token_ok.' créée', $id_activite, $page);

                    $this->resetinputFields2(); 
                    $this->redirect('/detail_ordre_fab?id='.$dernier_id.'&active=9&champ=2-1&choix=1', navigate: true);
                }
                else{
                    $this->dispatch('alert',                    
                        title:'Désolé, vous n\'avez pas sélectionné de composants à consommer!',
                        timer:3000,
                        icon:'warning',
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
    // suppression de l'ordre
    public function confirmerDeleted(int $id){
        $this->confirmer = $id;        
    }
    public function supprimerAll(int $ids, int $idx){ 
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->supprimer_nomencla;
            if($autoriser == 1){
                OrdreFabrication::where('id',$ids)->delete();
                ComposantNomenclatureOrdreFab::where('ordre_fabrication',$ids)->delete();
                MagConsoComposantOf::where('ordre_fabrication',$ids)->delete();
                $page = 'OrdreFabrication';    
                LogActivityModel::where('id_activite',$ids)->where('page',$page)->delete();
                $id_activite = $ids;  
                LogActivity::addToLog('OrdreFabrication et Composant supprimés', $id_activite, $page);
                $this->dispatch('alert',                    
                    title:'OrdreFabrication et Composant supprimés',
                    timer:3000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                ); 
                $this->redirect('/listing_ordre?active=9&champ=2-1&choix=2', navigate: true);         
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
