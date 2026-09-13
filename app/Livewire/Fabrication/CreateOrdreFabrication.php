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
use App\Models\Entrepot;


class CreateOrdreFabrication extends Component
{
    public $ids;
    public $ouvre = 0;
    public $confirmer; 
    public $approuver; 

    public $nomenclatures; 
    public $libelle; 
    public $type_nomencla = 'Fabrication'; 
    public $produit_a_fabrique; 
    public $produit_id; 
    public $quantite;
    public $unite_mesure = 'Unité(s)';
    public $duree;     
    public $date_debut;     
    public $date_fin;    
    public $entrepot_fabrication; 
    public $note;
    public $responsable;
    public $tiers;
    public $code;

    public $quantite_formule;    

     // pour composant 
    public $choix_composant; 
    public $unite = 'Unité(s)';  
    public $quantite_composant = 1;  
    // fin

    public $orderField = 'id'; 
    public $orderDirection = 'ASC'; 

    public function resetinputFields(){           
        $this->choix_composant = '';      
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
                $title = 'Nouvelle Ordre de fabrication | WamsCo';
                $module = 'Gestion fabrication';
                $title_fils = 'Nouvelle Ordre de fabrication';
                $lien = 'listing_ordre?active=9&champ=2-1&choix=2';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');      
                $dateJour = date('Y-m-d');
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');
                
                $listeNomenclatur = Nomenclature::where('societe',auth()->user()->societe)->where('etat',1)->orderBy('libelle')->get();
                $produit = Produit::where('societe',auth()->user()->societe)->where('nature_produit','!=','Matière première')->where('etat',1)->orderBy('nom_produit')->get();
                $entrepot = Entrepot::where('societe',auth()->user()->societe)->where('active',1)->orderBy('nom','asc')->get();
                // $composant_produit = Produit::where('societe',auth()->user()->societe)->where('nature_produit','Matière première')->where('etat',1)->orderBy('nom_produit')->get();
                $composant_produit = Produit::where('societe',auth()->user()->societe)->where('nature_produit','!=','Manufacturé')->where('etat',1)->orderBy('nom_produit')->get();
                $utilisateur = Utilisateur::where('societe',auth()->user()->societe)->orderBy('name','asc')->get();
                $tier = Tier::where('societe',auth()->user()->societe)->orderBy('nom','asc')->get();
                // ceci au chargement de la page
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
                $listeComposant = ComposantNomenclature::where('societe',auth()->user()->societe)->where('nomencla_id',$this->nomenclatures)->orderBy($this->orderField, $this->orderDirection)->get();
                
                $page = 'OrdreFabrication'; // Pour evenement lie
                $log = LogActivityModel::where('user_societe',auth()->user()->societe)->where('page', $page)->limit(10)->orderBy('id','desc')->get();
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
                return view('livewire.fabrication.create-ordre-fabrication',compact('title_fils','module','lien','dateJour','listeNomenclatur','utilisateur','tier','listeComposant','log','logCount','produit','entrepot','composant_produit'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
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
            'choix_composant'=>'required|numeric',
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
                $id_entrepot = $compos->entrepot;

                if($id_entrepot){
                    $quantite_consommer = 0;
                    $coutFinal = $cout * $this->quantite_composant;
                    ComposantNomenclature::create(['composant'=>$nom_composant,'composant_id'=>$composant_id,'nomencla_id'=>$this->ids,'quantite'=>$this->quantite_composant,
                        'id_entrepot'=>$id_entrepot,'cout'=>$coutFinal,'unite'=>$this->unite,'quantite_consommer'=>$quantite_consommer,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

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
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
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
            'date_debut'=>'required',            
            'date_fin'=>'required', 
            'responsable'=>'max:25',                        
            'tiers'=>'max:25',                        
            'note'=>'max:255',                        
        ]);
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_ordre_fab;
            if($autoriser == 1){   
                $test_composant = ComposantNomenclature ::where('societe',auth()->user()->societe)->where('nomencla_id',$this->nomenclatures)->count();
                if($test_composant > 0){
                    $date = date('dmy');
                    $length = 3;
                    $token = bin2hex(random_bytes($length));
                    $token_ok = 'OF/'.$date.'/'.$token; 
                    $statut = 'Validé (à fabriquer)';
                    $quantite_fabrique = 0;
                    $coutTotal = 0; 

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

                    OrdreFabrication::create(['ref_ordre'=>$token_ok,'produit_a_fabrique'=>$this->produit_a_fabrique,'produit_id'=>$this->produit_id,'type_nomencla'=>$this->type_nomencla,
                    'nomencla_id'=>$this->nomenclatures,'code_nomencla'=>$this->code,'libelle'=>$this->libelle,'quantite'=>$this->quantite,'quantite_fabrique'=>$quantite_fabrique,'cout_total'=>$coutTotal,
                    'unite_mesure'=>$this->unite_mesure,'duree'=>$this->duree,'entrepot_fabrication'=>$nom_entrepot,'id_entrepot'=>$this->entrepot_fabrication,'date_debut'=>$this->date_debut,
                    'date_fin'=>$this->date_fin,'statut'=>$statut,'responsable'=>$nom_respo,'responsable_id'=>$id_respo,'tiers'=>$nom_tier,'tiers_id'=>$id_tier,'description'=>$this->note,'societe'=>auth()->user()->societe,
                    'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                    // ceci recupere le dernier enregistrement cree a l'instant 
                    $dernier_id = OrdreFabrication::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id;
                                                            
                    $CompoNomenclature = ComposantNomenclature::where('societe',auth()->user()->societe)->where('nomencla_id',$this->nomenclatures)->get(); 
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
                            'composant'=>$CompoNomenclatures->composant,
                            'composant_id'=>$CompoNomenclatures->composant_id,
                            'quantite'=>$qte_a_consommer,
                            'unite'=>$CompoNomenclatures->unite,
                            'quantite_consommer'=>$CompoNomenclatures->quantite_consommer,
                            'cout'=>$coutTotal,                            
                            'user_id'=>auth()->user()->id,
                            'nom_user'=>auth()->user()->name,
                            'societe'=>auth()->user()->societe]);
                    }
                    // ceci recupere le dernier enregistrement cree a l'instant
                    $dernier_id = OrdreFabrication::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id; 
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
}
