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
use App\Models\ComposantNomenclature;
use App\Models\OrdreFabrication;
use App\Models\ComposantNomenclatureOrdreFab;
use App\Models\MagConsoComposantOf;
use App\Models\Entrepot;
use App\Models\Stock;
use App\Models\Mouvement;

class DetailOrdreFabrication extends Component
{
    public $ids;
    public $idy; // pour id composant  
    public $id_ofab; // id Ordre fab      
    public $ouvre = 0;
    public $confirmer; 
    public $approuver; 

    public $nomenclatures; 
    public $ref_ordre;    
    public $nomencla_id;     
    public $libelle; 
    public $type_nomencla; 
    public $produit_a_fabrique; 
    public $produit_id; 
    public $quantite; // quantite total a fabrique ou a produire 
    public $quantite_fabrique; // quantite deja fabrique
    public $quantite_recu = 0;  // quantite recu chaque fois
    public $unite_mesure = 'Unité(s)';
    public $duree;     
    public $date_debut;     
    public $date_fin; 
    public $entrepot_fabrication; 
    public $note;
    public $responsable;
    public $tiers;
    public $code;
    public $statut;       
    public $composant_id; // id produit (composant) 
    public $etatNomen;       
    public $created_at;       
         

     // pour composant 
    public $composant; 
    public $quantiteCompos; 
    public $quantiteConsommer;    
    public $resteAconsommer;
    public $reste_a_consommer;    
    public $choix_entrepot;

    // pour ajour composant 
    public $choix_composant; 
    public $magasin; 
    public $unite = 'Unité(s)';  
    public $quantite_composant = 1;  
    // fin 
    
    public $qte_a_produire; // ceci pour modifier la quantite a produire
    public $coutTotalFinal;
    
    public $nom_composant; // pour afficher liste conso Mag
    public $entrepotFab;
    
    public $created_at2;
    public $updated_at;
    public $nom_user;

    public $orderField = 'id'; 
    public $orderDirection = 'ASC'; 

    public function resetinputFields(){           
        $this->choix_composant = '';      
        $this->quantite_composant = 1;        
        $this->unite = 'Unité(s)'; 
    }     
    public function onDataAjout(){
        $this->reset('ouvre');
    } 
    public function ajoutLigne(int $idd){
        $this->ouvre = $idd;
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
            $this->autoriser = $role[0]->voir_marge;
            $autoriser = $role[0]->creer_ordre_fab;
            if($autoriser == 0){
                alert()->error('Oups Désolé', 'Vous n\'êtes pas autorisé à ouvrir cette page !!!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
                $this->redirect('/bienvenue', navigate: true);
            }
        }
        else{
            alert()->error('Oups Désolé', 'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
            $this->redirect('/bienvenue', navigate: true);
        } 
        $this->date_debut = date('Y-m-d H:i');  
        $this->date_fin = date('Y-m-d H:i');
    }
    public function render(){
    
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();
        $jourValid = $entite_mod[0]->validite_mod;
        $mod_fabrication = $entite_mod[0]->mod_fabrication;
        $soldeClient = $entite_mod[0]->solde; 
        if($dateJour <= $jourValid){
            if($mod_fabrication == 1){
                $title = 'Ordre de fabrication | WamsCo';
                $module = 'Gestion fabrication';
                $title_fils = 'Ordre de fabrication';
                $lien = 'listing_ordre?active=9&champ=2-1&choix=2';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');      
                $dateJour = date('Y-m-d');
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');

                $id = request('id'); // id Ordre de fabrication composant_id
                // $listeNomenclatur = Nomenclature::where('societe',auth()->user()->societe)->where('etat',1)->orderBy('libelle')->get();
                $produit = Produit::where('societe',auth()->user()->societe)->where('nature_produit','!=','Matière première')->where('etat',1)->orderBy('nom_produit')->get();
                $entrepot = Entrepot::where('societe',auth()->user()->societe)->where('active',1)->orderBy('nom','asc')->get();
                $stock_entrepot = Stock::where('societe',auth()->user()->societe)->where('id_produit', $this->composant_id)->where('etat',1)->orderBy('nom_produit','asc')->get();
                // $composant_produit = Produit::where('societe',auth()->user()->societe)->where('nature_produit','Matière première')->where('etat',1)->orderBy('nom_produit')->get();
                $composant_produit = Produit::where('societe',auth()->user()->societe)->where('nature_produit','!=','Manufacturé')->where('etat',1)->orderBy('nom_produit')->get();
                $utilisateur = Utilisateur::where('societe',auth()->user()->societe)->orderBy('name','asc')->get();
                $tier = Tier::where('societe',auth()->user()->societe)->where('etat',1)->orderBy('nom','asc')->get();
                $detail_conso = MagConsoComposantOf::where('societe',auth()->user()->societe)->where('composant_id', $this->id_ofab)->where('ordre_fabrication', $this->ids)->orderBy('id','asc')->get();
                $detail_consoCount = $detail_conso->count();
                $detail_consoTotal = $detail_conso->sum('quantite_consommer');
                // ceci au chargement de la page
                $test_nomen = OrdreFabrication::where('id',$id)->count();    
                if($test_nomen > 0){ 
                    
                    $nomen = OrdreFabrication::where('id',$id)->first();               
                    $this->ids = $nomen->id;
                    $this->ref_ordre = $nomen->ref_ordre;
                    $this->nomenclatures = $nomen->code_nomencla;
                    $this->nomencla_id = $nomen->nomencla_id;
                    $this->type_nomencla = $nomen->type_nomencla;
                    $this->libelle = $nomen->libelle;
                    $this->produit_id = $nomen->produit_id; // recupere id nomencla
                    $this->produit_a_fabrique = $nomen->produit_a_fabrique; // libelle produit
                    $this->quantite_fabrique = $nomen->quantite_fabrique; // quantite deja fabrique
                    $this->quantite = $nomen->quantite; // quantite total a fabrique ou a produire 
                    $this->unite_mesure = $nomen->unite_mesure;
                    $this->duree = $nomen->duree;
                    $this->entrepot_fabrication = $nomen->id_entrepot;
                    $this->entrepotFab = $nomen->entrepot_fabrication;  
                    $this->note = $nomen->description;
                    $this->tiers = $nomen->tiers_id;
                    $this->responsable = $nomen->responsable_id;
                    $this->statut = $nomen->statut; 
                    $this->date_debut = $nomen->date_debut; 
                    $this->date_fin = $nomen->date_fin; 

                    $this->nom_user = $nomen->nom_user;
                    $this->created_at2 = $nomen->created_at;
                    $this->updated_at = $nomen->updated_at;
                    
                    $men = Nomenclature::where('id',$this->nomencla_id)->first();               
                    $this->etatNomen = $men->etat;
                    $this->created_at = $men->created_at;
                    
                }
                    $listeComposant = ComposantNomenclatureOrdreFab::where('societe',auth()->user()->societe)->where('ordre_fabrication',$this->ids)->orderBy($this->orderField, $this->orderDirection)->get();
                    $coutMTotal = $listeComposant->sum('cout');
                    $coutMTotal = $listeComposant->sum('cout');
                    $this->coutTotalFinal = $coutMTotal; 
                    
                    $ComposantCount = ComposantNomenclatureOrdreFab::where('societe',auth()->user()->societe)->where('ordre_fabrication',$this->ids)->count();

                    // select
                    $listEntrepot = Entrepot::where('societe',auth()->user()->societe)->orderBy('nom','asc')->where('active',1)->get(); 
                    $stock = Stock::where('societe',auth()->user()->societe)->where('id_produit',$this->choix_composant)->where('type_produit','Produit')->where('etat',1)->get();  
                    // Fin select

                    $page = 'OrdreFabrication'; // Pour evenement lie
                    $log = LogActivityModel::where('user_societe',auth()->user()->societe)->where('id_activite', $this->ids)->where('page', $page)->limit(50)->orderBy('id','desc')->get();
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
                    return view('livewire.fabrication.detail-ordre-fabrication',compact('title_fils','module','lien','dateJour','utilisateur','tier','detail_conso','detail_consoCount','detail_consoTotal','listeComposant','coutMTotal','ComposantCount',
                    'listEntrepot','stock','log','logCount','produit','entrepot','stock_entrepot','composant_produit'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
                
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
                return view('livewire.bienvenue',compact('dateJour','entite_mod'))->layout('components.layouts.app',compact('title','module','title_fils','lien','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));          
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
    public function update(){       
        $this->validate([
            'libelle'=>'required|max:255',
            // 'quantite_recu'=>'required|numeric',            
            'duree'=>'required|max:5',            
            'entrepot_fabrication'=>'required|max:255',   // id entrepot         
            'date_debut'=>'required',            
            'date_fin'=>'required', 
            'responsable'=>'max:25',                        
            'tiers'=>'max:25',                        
            'note'=>'max:255',                        
        ]);
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_ordre_fab;
            if($autoriser == 1){ 

                // recuperer id et nom entrepot
                $entrepo = Entrepot::where('societe',auth()->user()->societe)->where('id',$this->entrepot_fabrication)->where('active',1)->orderBy('nom')->first();
                $entrepo_id =$entrepo->id;
                $nom_entrepot = $entrepo->nom;

                $test_users = Utilisateur::where('societe',auth()->user()->societe)->where('id',$this->responsable)->where('etat',1)->orderBy('name')->count();
                if($test_users > 0){
                    $users = Utilisateur::where('societe',auth()->user()->societe)->where('id',$this->responsable)->where('etat',1)->orderBy('name')->first();
                    $id_respo = $users->id;
                    $nom_respo = $users->name;
                }
                else{
                    $id_respo = NULL;
                    $nom_respo = NULL;
                }

                $test_tier = Tier::where('societe',auth()->user()->societe)->where('id',$this->tiers)->where('etat',1)->orderBy('nom')->count();
                if($test_tier > 0){
                    $tie = Tier::where('societe',auth()->user()->societe)->where('id',$this->tiers)->where('etat',1)->orderBy('nom')->first();
                    $id_tier = $tie->id;
                    $nom_tier = $tie->nom;
                }
                else{
                    $id_tier = NULL;
                    $nom_tier = NULL;
                }
                
                $quantite_fabrique = 0;                
                // $coutTotal = 0;
                OrdreFabrication::find($this->ids)->update(['libelle'=>$this->libelle,'quantite'=>$this->quantite,'quantite_fabrique'=>$quantite_fabrique,
                'duree'=>$this->duree,'entrepot_fabrication'=>$nom_entrepot,'id_entrepot'=>$entrepo_id,'date_debut'=>$this->date_debut,
                'date_fin'=>$this->date_fin,'responsable'=>$nom_respo,'responsable_id'=>$id_respo,'tiers'=>$nom_tier,'tiers_id'=>$id_tier,
                'description'=>$this->note,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                                                              
                $id_activite = $this->ids;  
                $page = 'OrdreFabrication';    
                LogActivity::addToLog('Ordre fabrication » '.$this->produit_a_fabrique.' modifiée', $id_activite, $page); 
                $this->dispatch('alert',                    
                    title:'Ordre fabrication (<strong>'.$this->produit_a_fabrique.'</strong>) modifiée!',
                    timer:3000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                ); 
                flash ('Ordre fabrication (<strong>'.$this->produit_a_fabrique.'</strong>) modifiée!')->success();
                $this->redirect('/detail_ordre_fab?id='.$this->ids.'&active=9&champ=2-1&choix=2', navigate: true);                
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
    public function ajoutComposant(){
        $this->validate([
            'choix_composant'=>'required|numeric',    // id produit
            'magasin'=>'required|numeric',            // id entrepot
            'quantite_composant'=>'required|numeric',
            'unite'=>'required|max:10',            
        ]);
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->ajouter_composant;
            if($autoriser == 1){       
               
                $compos = Produit::where('societe',auth()->user()->societe)->where('id',$this->choix_composant)->first();
                $composant_id = $compos->id;                
                $nom_composant = $compos->nom_produit;                
                $cout = $compos->prix_achat;

                $mag = Entrepot::where('societe',auth()->user()->societe)->where('id',$this->magasin)->first();
                $id_magasin = $mag->id;                
                $nom_magasin = $mag->nom;  

                if($id_magasin){ 
                    $quantite_consommer = 0;
                    $coutFinal = $cout * $this->quantite_composant;
                    ComposantNomenclatureOrdreFab::create(['ordre_fabrication'=>$this->ids,'ref_ordre'=>$this->ref_ordre,'composant'=>$nom_composant,'composant_id'=>$composant_id,'nomencla_id'=>$this->nomencla_id,'quantite'=>$this->quantite_composant,
                        'id_entrepot'=>$id_magasin,'nom_entrepot'=>$nom_magasin,'cout'=>$coutFinal,'unite'=>$this->unite,'quantite_consommer'=>$quantite_consommer,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                    $id_activite = $this->ids;  
                    $page = 'OrdreFabrication';    
                    LogActivity::addToLog('Composant nomenclature (OF) » '.$nom_composant.' ajouté', $id_activite, $page); 
                    $this->dispatch('alert',                    
                        title:'Composant nomenclature » <strong>'.$nom_composant.'</strong> ajouté',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    ); 
                    $this->resetinputFields();
                    // $this->redirect('/detail_ordre_fab?id='.$this->ids.'&active=9&champ=2-1&choix=1', navigate: true); 
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
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->supprimer_composant;
            if($autoriser == 1){   
                if($id){ 

                    // Retour quantite                    
                    $prod = ComposantNomenclatureOrdreFab::where('societe',auth()->user()->societe)->where('id',$id)->first();
                    $produit_id = $prod->composant_id;
                    $quantite = $prod->quantite_consommer;
                    $id_entrepot = $prod->id_entrepot;
                    $id_ordre_fabrication = $prod->ordre_fabrication;
                    $ref_ordre = $prod->ref_ordre;

                    if($id_entrepot){
                    
                        $stockAtuel = Stock :: where('societe',auth()->user()->societe)->where('id_entrepot',$id_entrepot)->where('id_produit',$produit_id)->first(); 
                        $id_stockProd = $stockAtuel->id;
                        $QteStockActuel = $stockAtuel->quantite;
                        $prix_moyen_pondere_achat = $stockAtuel->prix_moyen_pondere_achat;
                        $prix_vente_unitaire = $stockAtuel->prix_vente_unitaire; 
                        $nom_produit = $stockAtuel->nom_produit;        
                        $id_produit = $stockAtuel->id_produit;
                        $reference = $stockAtuel->reference;                   

                        // Mise a jour du stock                             
                        $qteSockFinal = $QteStockActuel + $quantite;
                        $valorisation_achat_total = $prix_moyen_pondere_achat * $qteSockFinal;
                        $valeur_vente_total = $prix_vente_unitaire * $qteSockFinal;
                        Stock::where('societe',auth()->user()->societe)->where('id',$id_stockProd)->update(['quantite'=>$qteSockFinal,'valorisation_achat_total'=>$valorisation_achat_total,'valeur_vente_total'=>$valeur_vente_total,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);   
                        // Fin retour

                        $entrepo = Entrepot::where('societe',auth()->user()->societe)->where('id',$id_entrepot)->first();                    
                        $nom_entrepot = $entrepo->nom; 
                        
                        $libele_mouvement = 'Suppression OrdreFabrication (Composant)';
                        $code_mouvement = date('YmdHis');
                        $statut = 'OF';
                        Mouvement::create(['id_entrepot'=>$id_entrepot,'entrepot'=>$nom_entrepot,'nom_produit'=>$nom_produit,'id_produit'=>$id_produit,'reference'=>$reference,'quantite'=>$quantite,'libele_mouvement'=>$libele_mouvement,
                        'code_mouvement'=>$code_mouvement,'statut'=>$statut,'origine'=>$ref_ordre,'id_ordre_fab'=>$id_ordre_fabrication,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                        ComposantNomenclatureOrdreFab::where('societe',auth()->user()->societe)->where('id',$id)->delete();
                        $id_activite = $this->ids;  
                        $page = 'OrdreFabrication';    
                        LogActivity::addToLog('Composant nomenclature (OF) supprimé', $id_activite, $page);
                        $this->dispatch('alert',                    
                            title:'Composant nomenclature (OF) supprimé',
                            timer:3000,
                            icon:'success',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        ); 
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
    public function editer(int $id){
        $affiche = ComposantNomenclatureOrdreFab::where('societe',auth()->user()->societe)->where('id',$id)->first();               
        $this->idy = $affiche->id;
        $this->composant_id = $affiche->composant_id;
        $this->composant = $affiche->composant;        
        $this->quantiteCompos = $affiche->quantite;
        $this->quantiteConsommer = $affiche->quantite_consommer;
        $this->resteAconsommer = $this->quantiteCompos - $this->quantiteConsommer;
    }
    public function fabricationPartiel(){
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_ordre_fab;
            if($autoriser == 1){   
                $this->validate([            
                    'reste_a_consommer'=>'required|numeric',            
                    'choix_entrepot'=>'required|numeric',  // id entrepot          
                ]);        
                $test_statut = OrdreFabrication::where('societe',auth()->user()->societe)->where('id',$this->ids)->first(); 
                $statut = $test_statut->statut;   
                if($statut != 'Fabriqué'){ 

                    // *** Laisser qu'on consomme plus de composant $this->reste_a_consommer <= $this->resteAconsommer C'EST FAIT EXPRES  ****

                    // if($this->reste_a_consommer <= $this->resteAconsommer){  
                        
                        $ligneComposant = ComposantNomenclatureOrdreFab::where('societe',auth()->user()->societe)->where('ordre_fabrication',$this->ids)->where('id',$this->idy)->get(); 
                                                    
                        foreach($ligneComposant as $ligneComposants){ 
                                
                            $id_compos = $ligneComposants->id;             
                            $id_produit = $ligneComposants->composant_id;
                            $composant = $ligneComposants->composant;
                            $ordre_fabrication = $ligneComposants->ordre_fabrication;
                            $ref_ordre = $ligneComposants->ref_ordre;
                            $unite = $ligneComposants->unite;                            
                            $total_quantite_conso = $ligneComposants->quantite_consommer + $this->reste_a_consommer;
                            $resteAconso = $ligneComposants->quantite - $ligneComposants->quantite_consommer; 

                            $id_entrep = $this->choix_entrepot;
                            $Entrepo = Entrepot::where('societe',auth()->user()->societe)->where('id',$id_entrep)->first();                    
                            $id_entrepot = $Entrepo->id; 
                            $nom_entrepot = $Entrepo->nom; 

                            $prods = Produit::where('societe',auth()->user()->societe)->where('id',$id_produit)->first();                    
                            $id_prod = $prods->id; 
                            $nom_produit = $prods->nom_produit; 
                            $reference = $prods->reference; 
                            $categorie = $prods->categorie; 
                            $type_produit = $prods->type_produit; 
                            $nature_produit = $prods->nature_produit; 
                            $prix_achat = $prods->prix_achat; 
                            $prix_vente = $prods->prix_vente; 
                            $prix_vente_min = $prods->prix_vente_min; 
                            $etat = $prods->etat; 
                            $image = $prods->image; 
                            
                            $verifie_stock = Stock::where('societe',auth()->user()->societe)->where('id_entrepot', $id_entrepot)->where('id_produit',$id_produit)->count();
                            if($verifie_stock == 0){
                                
                                $quantite = 0;
                                $valorisation_achat_total = 0;
                                $valeur_vente_total = 0;
                                $limite_stock_alerte_bd = 5;
                                Stock::create(['id_entrepot'=>$id_entrepot,'nom_produit'=>$nom_produit,'id_produit'=>$id_produit,'reference'=>$reference,'categorie'=>$categorie,'type_produit'=>$type_produit,'nature_produit'=>$nature_produit,'quantite'=>$quantite,
                                'prix_achat_last'=>$prix_achat, 'prix_moyen_pondere_achat'=>$prix_achat, 'valorisation_achat_total'=>$valorisation_achat_total,'prix_vente_unitaire'=>$prix_vente,'prix_vente_min'=>$prix_vente_min,'valeur_vente_total'=>$valeur_vente_total,
                                'limite_stock_alerte'=>$limite_stock_alerte_bd,'etat'=>$etat,'image'=>$image,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                                
                            }
                            
                            if( $this->type_nomencla == 'Fabrication'){
                                $stockTrouver = Stock::where('societe',auth()->user()->societe)->where('id_entrepot', $id_entrepot)->where('id_produit',$id_produit)->first();
                                $nom_produit = $stockTrouver->nom_produit;
                                // $id_produit = $stockTrouver->id_produit;
                                $reference = $stockTrouver->reference;                            
                                $qteSockFinal = $stockTrouver->quantite - $this->reste_a_consommer;
                                $valorisation_achat_total = $stockTrouver->prix_moyen_pondere_achat * $qteSockFinal;
                                $valeur_vente_total = $stockTrouver->prix_vente_unitaire * $qteSockFinal;
                                $quantiteStock = $stockTrouver->quantite; 
                                
                                if($this->reste_a_consommer > $quantiteStock){
                                    $this->dispatch('alert',                    
                                        title:'Désolé, le stock ('.$quantiteStock.') est insuffisant dans ce magasin',
                                        timer:6000,
                                        icon:'warning',
                                        toast:true,
                                        showConfirmButton: false,
                                        position:'top-end',
                                    );
                                }
                                else{
                                    
                                    Stock::where('societe',auth()->user()->societe)->where('id_entrepot',$id_entrepot)->where('id_produit',$id_produit)->update(['quantite'=>$qteSockFinal,'valorisation_achat_total'=>$valorisation_achat_total,'valeur_vente_total'=>$valeur_vente_total,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);   
                            
                                    MagConsoComposantOf::create(['entrepot_id'=>$id_entrepot,'entrepot_conso'=>$nom_entrepot,'ordre_fabrication'=>$ordre_fabrication,'ref_ordre'=>$ref_ordre,'quantite_consommer'=>$this->reste_a_consommer,'unite'=>$unite,'composant_id'=>$id_produit,'composant'=>$composant,
                                    'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);   

                                    $quantiteConsommerFinal = MagConsoComposantOf::where('societe',auth()->user()->societe)->where('composant_id',$id_produit)->where('ordre_fabrication',$this->ids)->sum('quantite_consommer');
                                    ComposantNomenclatureOrdreFab::find($this->idy)->update(['quantite_consommer'=>$quantiteConsommerFinal,'id_entrepot'=>$id_entrepot,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);   


                                    $libele_mouvement = 'OrdreFabrication (Composant-conso)';           
                                    $code_mouvement = date('YmdHis');
                                    $statut = 'OF';                                    
                                    
                                    Mouvement::create(['id_entrepot'=>$id_entrepot,'nom_produit'=>$nom_produit,'id_produit'=>$id_produit,'reference'=>$reference,'quantite'=>-$this->reste_a_consommer,'libele_mouvement'=>$libele_mouvement,
                                    'code_mouvement'=>$code_mouvement,'statut'=>$statut,'origine'=>$this->ref_ordre,'id_ordre_fab'=>$this->ids,'entrepot'=>$nom_entrepot,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                                                  
                                    $sommeResteQte = ComposantNomenclatureOrdreFab::where('societe',auth()->user()->societe)->where('ordre_fabrication',$this->ids)->sum('quantite');
                                    $sommeResteQteConso = ComposantNomenclatureOrdreFab::where('societe',auth()->user()->societe)->where('ordre_fabrication',$this->ids)->sum('quantite_consommer');
                                    $sommeResteAconsommer = $sommeResteQte - $sommeResteQteConso;                                                                            
                                    
                                    $etats = 'En cours';                  
                                    OrdreFabrication::where('societe',auth()->user()->societe)->where('id',$this->ids)->update(['statut'=>$etats,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                                    
                                    $id_activite = $this->ids;
                                    $page = 'OrdreFabrication';
                                    LogActivity::addToLog('Consommation partielle » '.$nom_produit.' (-'.$this->reste_a_consommer.')', $id_activite, $page); 

                                    $id_activite = $id_produit;                    
                                    $page = 'Produits';
                                    LogActivity::addToLog('Consommation (OF) partielle » '.$nom_produit.' (-'.$this->reste_a_consommer.')', $id_activite, $page);

                                    $id_activite = $id_entrepot;                    
                                    $page = 'Entrepot';
                                    LogActivity::addToLog('Consommation (OF) partielle » '.$nom_produit.' (-'.$this->reste_a_consommer.') dans '.$nom_entrepot, $id_activite, $page);

                                    $this->dispatch('alert',                    
                                        title:'Consommation effectuée avec succes!',
                                        timer:5000,
                                        icon:'success',
                                        toast:true,
                                        showConfirmButton: false,
                                        position:'top-end',
                                    );
                                    $this->redirect('/detail_ordre_fab?id='.$this->ids.'&active=9&champ=2-1&choix=2', navigate: true);
                                }    
                            }
                            else{

                                $stockTrouver = Stock::where('societe',auth()->user()->societe)->where('id_entrepot', $id_entrepot)->where('id_produit',$id_produit)->first();
                                $nom_produit = $stockTrouver->nom_produit;
                                // $id_produit = $stockTrouver->id_produit;
                                $reference = $stockTrouver->reference;                            
                                $qteSockFinal = $stockTrouver->quantite + $this->reste_a_consommer;
                                $valorisation_achat_total = $stockTrouver->prix_moyen_pondere_achat * $qteSockFinal;
                                $valeur_vente_total = $stockTrouver->prix_vente_unitaire * $qteSockFinal;
                                $quantiteStock = $stockTrouver->quantite; 

                                Stock::where('societe',auth()->user()->societe)->where('id_entrepot',$id_entrepot)->where('id_produit',$id_produit)->update(['quantite'=>$qteSockFinal,'valorisation_achat_total'=>$valorisation_achat_total,'valeur_vente_total'=>$valeur_vente_total,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);   
                        
                                MagConsoComposantOf::create(['entrepot_id'=>$id_entrepot,'entrepot_conso'=>$nom_entrepot,'ordre_fabrication'=>$ordre_fabrication,'ref_ordre'=>$ref_ordre,'quantite_consommer'=>$this->reste_a_consommer,'unite'=>$unite,'composant_id'=>$id_produit,'composant'=>$composant,
                                'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);   

                                $quantiteConsommerFinal = MagConsoComposantOf::where('societe',auth()->user()->societe)->where('composant_id',$id_produit)->where('ordre_fabrication',$this->ids)->sum('quantite_consommer');
                                ComposantNomenclatureOrdreFab::find($this->idy)->update(['quantite_consommer'=>$quantiteConsommerFinal,'id_entrepot'=>$id_entrepot,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);   


                                $libele_mouvement = 'OrdreDéassemblage (Composant-prod)';           
                                $code_mouvement = date('YmdHis');
                                $statut = 'OF';                                
                                
                                Mouvement::create(['id_entrepot'=>$id_entrepot,'nom_produit'=>$nom_produit,'id_produit'=>$id_produit,'reference'=>$reference,'quantite'=>$this->reste_a_consommer,'libele_mouvement'=>$libele_mouvement,
                                'code_mouvement'=>$code_mouvement,'statut'=>$statut,'origine'=>$this->ref_ordre,'id_ordre_fab'=>$this->ids,'entrepot'=>$nom_entrepot,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                                              
                                $sommeResteQte = ComposantNomenclatureOrdreFab::where('societe',auth()->user()->societe)->where('ordre_fabrication',$this->ids)->sum('quantite');
                                $sommeResteQteConso = ComposantNomenclatureOrdreFab::where('societe',auth()->user()->societe)->where('ordre_fabrication',$this->ids)->sum('quantite_consommer');
                                $sommeResteAconsommer = $sommeResteQte - $sommeResteQteConso;
                                                                    
                                // if($sommeResteAconsommer == 0){
                                //     $etats = 'Clôturée';                        
                                // }
                                // else{   
                                //     $etats = 'Partiel';                  
                                // }
                                $etats = 'En cours';                  
                                OrdreFabrication::where('societe',auth()->user()->societe)->where('id',$this->ids)->update(['statut'=>$etats,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                                
                                $id_activite = $this->ids;
                                $page = 'OrdreFabrication';
                                LogActivity::addToLog('Consommation partielle » '.$nom_produit.' (-'.$this->reste_a_consommer.')', $id_activite, $page); 

                                $id_activite = $id_produit;                    
                                $page = 'Produits';
                                LogActivity::addToLog('Consommation (OF) partielle » '.$nom_produit.' (-'.$this->reste_a_consommer.')', $id_activite, $page);

                                $id_activite = $id_entrepot;                    
                                $page = 'Entrepot';
                                LogActivity::addToLog('Consommation (OF) partielle » '.$nom_produit.' (-'.$this->reste_a_consommer.') dans '.$nom_entrepot, $id_activite, $page);

                                $this->dispatch('alert',                    
                                    title:'Consommation effectuée avec succes!',
                                    timer:5000,
                                    icon:'success',
                                    toast:true,
                                    showConfirmButton: false,
                                    position:'top-end',
                                );
                                $this->redirect('/detail_ordre_fab?id='.$this->ids.'&active=9&champ=2-1&choix=2', navigate: true);
                            }                     
                        }
                    // }
                    // else{
                    //     $this->dispatch('alert',                    
                    //         title:'Désolé, reste à consommer »'.$this->resteAconsommer,
                    //         timer:5000,
                    //         icon:'warning',
                    //         toast:true,
                    //         showConfirmButton: false,
                    //         position:'top-end',
                    //     );
                    // }
                }
                else{
                        
                    $this->dispatch('alert',                    
                        title:'Cette fabrication a déja été cloturée!',
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
    public function afficheConso(int $id){
        $this->id_ofab = $id;
        $test_afficher = MagConsoComposantOf::where('societe',auth()->user()->societe)->where('composant_id',$id)->count();   
        if($test_afficher > 0){
            $afficher = MagConsoComposantOf::where('societe',auth()->user()->societe)->where('composant_id',$id)->first();               
            $this->nom_composant = $afficher->composant;
            // $this->id_ofab = $afficher->ordre_fabrication;
        } 
        else{
            $this->dispatch('alert',  
                title:'Désolé, pas de consommation trouvée',  
                timer:3000,
                icon:'warning',
                toast:true,
                showConfirmButton: false,
                position:'top-end',
            );    
        }           
    }
    public function precedant(int $id){ 
        $testPrecedant = OrdreFabrication::where('societe',auth()->user()->societe)->where('id','<',$id)->orderBy('id','desc')->count();
        if($testPrecedant > 0){ 
            $precedant = OrdreFabrication::where('societe',auth()->user()->societe)->where('id','<',$id)->orderBy('id','desc')->first();        
            $previous = $precedant->id; 
            $this->redirect('/detail_ordre_fab?id='.$previous.'&active=9&champ=2-1&choix=2', navigate: true);             
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
            $this->redirect('/detail_ordre_fab?id='.$id.'&active=9&champ=2-1&choix=2', navigate: true);  // ceci evite une erreur
        }    
    }    
    public function suivant(int $id){    
        
        $testSuivant = OrdreFabrication::where('societe',auth()->user()->societe)->where('id','>',$id)->orderBy('id','asc')->count();
        if($testSuivant > 0){
            $suivant = OrdreFabrication::where('societe',auth()->user()->societe)->where('id','>',$id)->orderBy('id','asc')->first();
            $next = $suivant->id;             
            $this->redirect('/detail_ordre_fab?id='.$next.'&active=9&champ=2-1&choix=2', navigate: true);                     
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
            $this->redirect('/detail_ordre_fab?id='.$id.'&active=9&champ=2-1&choix=2', navigate: true); // ceci evite une erreur
        } 
    }
    public function produire(){
        $this->validate([
            'quantite_recu'=>'required|numeric',
        ]);
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_ordre_fab;
            if($autoriser == 1){                  
                
                $of = OrdreFabrication::where('societe',auth()->user()->societe)->where('id',$this->ids)->first(); 
                $produit_a_fabrique = $of->produit_a_fabrique;  
                $id_produit = $of->produit_id;  
                $id_entrepot = $of->id_entrepot;  
                $nom_entrepot = $of->entrepot_fabrication;  
                $type_nomencla = $of->type_nomencla;  
                $ref_ordre = $of->ref_ordre;  
                $quantite_fabriqueBD = $of->quantite_fabrique;  
                $coutTotal = $of->cout_total;  
                $qteabriqueFinal = $quantite_fabriqueBD + $this->quantite_recu;
               
                OrdreFabrication::find($this->ids)->update(['quantite_fabrique'=>$qteabriqueFinal,'cout_total'=>$this->coutTotalFinal,
                'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                $prods = Produit::where('societe',auth()->user()->societe)->where('id',$id_produit)->first();                    
                $id_prod = $prods->id; 
                $nom_produit = $prods->nom_produit; 
                $reference = $prods->reference; 
                $categorie = $prods->categorie; 
                $type_produit = $prods->type_produit; 
                $nature_produit = $prods->nature_produit; 
                $prix_achat = $prods->prix_achat; 
                $prix_vente = $prods->prix_vente; 
                $prix_vente_min = $prods->prix_vente_min; 
                $etat = $prods->etat; 
                $image = $prods->image;

                $verifie_stock = Stock::where('societe',auth()->user()->societe)->where('id_entrepot', $id_entrepot)->where('id_produit',$id_produit)->count();
                if($verifie_stock == 0){
                    
                    $quantite = 0;
                    $valorisation_achat_total = 0;
                    $valeur_vente_total = 0;
                    $limite_stock_alerte_bd = 5;
                    Stock::create(['id_entrepot'=>$id_entrepot,'nom_produit'=>$nom_produit,'id_produit'=>$id_produit,'reference'=>$reference,'categorie'=>$categorie,'type_produit'=>$type_produit,'nature_produit'=>$nature_produit,'quantite'=>$quantite,
                    'prix_achat_last'=>$prix_achat, 'prix_moyen_pondere_achat'=>$prix_achat, 'valorisation_achat_total'=>$valorisation_achat_total,'prix_vente_unitaire'=>$prix_vente,'prix_vente_min'=>$prix_vente_min,'valeur_vente_total'=>$valeur_vente_total,
                    'limite_stock_alerte'=>$limite_stock_alerte_bd,'etat'=>$etat,'image'=>$image,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                                
                }

                $stockTrouver = Stock::where('societe',auth()->user()->societe)->where('id_entrepot',$id_entrepot)->where('id_produit',$id_produit)->first();
                $nom_produit = $stockTrouver->nom_produit;
                $reference = $stockTrouver->reference; 

                if($type_nomencla == 'Fabrication'){                          
                    $qteSockFinal = $stockTrouver->quantite + $this->quantite_recu;
                }
                else{ 
                    $qteSockFinal = $stockTrouver->quantite - $this->quantite_recu;
                }
                $valorisation_achat_total = $stockTrouver->prix_moyen_pondere_achat * $qteSockFinal;
                $valeur_vente_total = $stockTrouver->prix_vente_unitaire * $qteSockFinal;
                $quantiteStock = $stockTrouver->quantite; 
                
                Stock::where('societe',auth()->user()->societe)->where('id_entrepot',$id_entrepot)->where('id_produit',$id_produit)->update(['quantite'=>$qteSockFinal,
                'valorisation_achat_total'=>$valorisation_achat_total,'valeur_vente_total'=>$valeur_vente_total,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);   
                
                // $libele_mouvement = 'OrdreFabrication';           
                $code_mouvement = date('YmdHis');
                $statut = 'OF';                                    
                
                if($type_nomencla == 'Fabrication'){ 
                    $libele_mouvement = 'OrdreFabrication (Production)';
                    Mouvement::create(['id_entrepot'=>$id_entrepot,'nom_produit'=>$nom_produit,'id_produit'=>$id_produit,'reference'=>$reference,'quantite'=>$this->quantite_recu,'libele_mouvement'=>$libele_mouvement,
                    'code_mouvement'=>$code_mouvement,'statut'=>$statut,'origine'=>$ref_ordre,'id_ordre_fab'=>$this->ids,'entrepot'=>$nom_entrepot,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                }
                else{
                     $libele_mouvement = 'OrdreDéassemblage (Consommation)';
                     Mouvement::create(['id_entrepot'=>$id_entrepot,'nom_produit'=>$nom_produit,'id_produit'=>$id_produit,'reference'=>$reference,'quantite'=>-$this->quantite_recu,'libele_mouvement'=>$libele_mouvement,
                    'code_mouvement'=>$code_mouvement,'statut'=>$statut,'origine'=>$ref_ordre,'id_ordre_fab'=>$this->ids,'entrepot'=>$nom_entrepot,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                }
                $id_activite = $this->ids;  
                $page = 'OrdreFabrication';    
                LogActivity::addToLog('Production (OF) » '.$this->quantite_recu.' '.$produit_a_fabrique.' ajouté', $id_activite, $page); 
                $this->dispatch('alert',                    
                    title:'Production » <strong>'.$this->quantite_recu.'</strong> '.$produit_a_fabrique.' ajouté',
                    timer:3000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                ); 
                $this->redirect('/detail_ordre_fab?id='.$this->ids.'&active=9&champ=2-1&choix=1', navigate: true); 
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
    public function Cloturer(){        
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_ordre_fab;
            if($autoriser == 1){   
               
                $statut = 'Terminé';                                    
                OrdreFabrication::find($this->ids)->update(['statut'=>$statut,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                 
                           
                $id_activite = $this->ids;  
                $page = 'OrdreFabrication';    
                LogActivity::addToLog('Production ('.$this->ref_ordre.') » '.$this->produit_a_fabrique.' '.$statut, $id_activite, $page); 
                $this->dispatch('alert',                    
                    title:'Production » <strong>'.$this->produit_a_fabrique.'</strong> '.$statut,
                    timer:3000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                ); 
                $this->redirect('/detail_ordre_fab?id='.$this->ids.'&active=9&champ=2-1&choix=1', navigate: true); 
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
    public function confirmerEcraser($id){  
        $this->approuver = $id;      
    } 
    public function ecraser(){        
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->supprimer_ordre_fab;
            if($autoriser == 1){                
                // suppression definitive et redirection                    
                $ligneComposantOf = ComposantNomenclatureOrdreFab::where('societe',auth()->user()->societe)->where('ordre_fabrication',$this->ids)->get(); 
                foreach($ligneComposantOf as $ligneComposantOfs){
                    $produit_id = $ligneComposantOfs->composant_id;
                    $quantite = $ligneComposantOfs->quantite_consommer;
                    $id_entrepot = $ligneComposantOfs->id_entrepot;
                    $id_ordre_fabrication = $ligneComposantOfs->ordre_fabrication;
                    $ref_ordre = $ligneComposantOfs->ref_ordre;

                    if($id_entrepot){
                    
                        $stockAtuel = Stock :: where('societe',auth()->user()->societe)->where('id_entrepot',$id_entrepot)->where('id_produit',$produit_id)->first(); 
                        $id_stockProd = $stockAtuel->id;
                        $QteStockActuel = $stockAtuel->quantite;
                        $prix_moyen_pondere_achat = $stockAtuel->prix_moyen_pondere_achat;
                        $prix_vente_unitaire = $stockAtuel->prix_vente_unitaire; 
                        $nom_produit = $stockAtuel->nom_produit;        
                        $id_produit = $stockAtuel->id_produit;
                        $reference = $stockAtuel->reference;                    

                        // Mise a jour du stock                             
                        $qteSockFinal = $QteStockActuel + $quantite;
                        $valorisation_achat_total = $prix_moyen_pondere_achat * $qteSockFinal;
                        $valeur_vente_total = $prix_vente_unitaire * $qteSockFinal;
                        Stock::where('societe',auth()->user()->societe)->where('id',$id_stockProd)->update(['quantite'=>$qteSockFinal,'valorisation_achat_total'=>$valorisation_achat_total,'valeur_vente_total'=>$valeur_vente_total,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);   
                        // Fin retour

                        $entrepo = Entrepot::where('societe',auth()->user()->societe)->where('id',$id_entrepot)->first();                    
                        $nom_entrepot = $entrepo->nom; 
                        
                        $code_mouvement = date('YmdHis');
                        $statut = 'OF';
                        if($this->type_nomencla == 'Fabrication'){
                            $quantite = $quantite;
                            $libele_mouvement = 'Suppression Ordre Fabrication (Composant)';
                        }
                        else{
                            $quantite = -$quantite;
                            $libele_mouvement = 'Suppression Ordre Déassemblage (Composant)';
                        }
                        Mouvement::create(['id_entrepot'=>$id_entrepot,'entrepot'=>$nom_entrepot,'nom_produit'=>$nom_produit,'id_produit'=>$id_produit,'reference'=>$reference,'quantite'=>$quantite,'libele_mouvement'=>$libele_mouvement,
                        'code_mouvement'=>$code_mouvement,'statut'=>$statut,'origine'=>$ref_ordre,'id_ordre_fab'=>$id_ordre_fabrication,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
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

                // Cette partie gere l'entete de l'orde de fabrication
                $ofab = OrdreFabrication::where('societe',auth()->user()->societe)->where('id',$this->ids)->first(); 
                $produit_id = $ofab->produit_id;
                $quantite = $ofab->quantite_fabrique;
                $id_entrepot = $ofab->id_entrepot;
                $id_ordre_fabrication = $ofab->id;
                $ref_ordre = $ofab->ref_ordre;
                $type_nomencla = $ofab->type_nomencla;

                $test_StockAtuel = Stock :: where('societe',auth()->user()->societe)->where('id_entrepot',$id_entrepot)->where('id_produit',$produit_id)->count(); 
                if($test_StockAtuel > 0){
                    $stockAtuel = Stock :: where('societe',auth()->user()->societe)->where('id_entrepot',$id_entrepot)->where('id_produit',$produit_id)->first(); 
                    $id_stockProd = $stockAtuel->id;
                    $QteStockActuel = $stockAtuel->quantite;
                    $prix_moyen_pondere_achat = $stockAtuel->prix_moyen_pondere_achat;
                    $prix_vente_unitaire = $stockAtuel->prix_vente_unitaire; 
                    $nom_produit = $stockAtuel->nom_produit;        
                    $id_produit = $stockAtuel->id_produit;
                    $reference = $stockAtuel->reference;                   

                    // Mise a jour du stock                             
                    $qteSockFinal = $QteStockActuel + $quantite;
                    $valorisation_achat_total = $prix_moyen_pondere_achat * $qteSockFinal;
                    $valeur_vente_total = $prix_vente_unitaire * $qteSockFinal;
                    Stock::where('societe',auth()->user()->societe)->where('id',$id_stockProd)->update(['quantite'=>$qteSockFinal,'valorisation_achat_total'=>$valorisation_achat_total,'valeur_vente_total'=>$valeur_vente_total,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);   
                    // Fin retour

                    $entrepo = Entrepot::where('societe',auth()->user()->societe)->where('id',$id_entrepot)->first();                    
                    $nom_entrepot = $entrepo->nom; 
                    
                    $code_mouvement = date('YmdHis');
                    $statut = 'OF';
                    if($type_nomencla == 'Fabrication'){
                        $quantite = -$quantite;
                        $libele_mouvement = 'Suppression Ordre Fabrication';
                    }
                    else{
                        $quantite = $quantite;
                        $libele_mouvement = 'Suppression Ordre Déassemblage';
                    }
                    Mouvement::create(['id_entrepot'=>$id_entrepot,'entrepot'=>$nom_entrepot,'nom_produit'=>$nom_produit,'id_produit'=>$id_produit,'reference'=>$reference,'quantite'=>$quantite,'libele_mouvement'=>$libele_mouvement,
                    'code_mouvement'=>$code_mouvement,'statut'=>$statut,'origine'=>$ref_ordre,'id_ordre_fab'=>$id_ordre_fabrication,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                }

                $page = 'OrdreFabrication';                        
                OrdreFabrication::where('id',$this->ids)->delete(); 
                ComposantNomenclatureOrdreFab::where('ordre_fabrication',$this->ids)->delete(); 
                MagConsoComposantOf::where('ordre_fabrication',$this->ids)->delete(); 
                LogActivityModel::where('id_activite',$this->ids)->where('page',$page)->delete();

                $id_activite = $this->ids;
                LogActivity::addToLog('OrdreFabrication » '.$this->ref_ordre.' supprimée définitivement', $id_activite, $page); 
                $this->dispatch('alert',                    
                    title:'OrdreFabrication supprimée avec succes!',
                    timer:5000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                );
                flash ('OrdreFabrication » <strong>'.$this->ref_ordre.'</strong> supprimée définitivement!')->success();
                $this->redirect('/listing_ordre?active=9&champ=2-1&choix=2', navigate: true);                
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
    public function quantiteAproduire(){
        $this->validate([
            'qte_a_produire'=>'required|numeric',
        ]);
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_ordre_fab;
            if($autoriser == 1){                  

                OrdreFabrication::find($this->ids)->update(['quantite'=>$this->qte_a_produire,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                $id_activite = $this->ids;  
                $page = 'OrdreFabrication';    
                LogActivity::addToLog('Quantité à produire (OF) » '.$this->qte_a_produire.' modifiée', $id_activite, $page);
                $this->dispatch('alert',
                    title:'Quantité à produire (OF) » <strong>'.$this->qte_a_produire.'</strong> modifiée',   
                    timer:3000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                );
                $this->redirect('/detail_ordre_fab?id='.$this->ids.'&active=9&champ=2-1&choix=1', navigate: true); 
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
