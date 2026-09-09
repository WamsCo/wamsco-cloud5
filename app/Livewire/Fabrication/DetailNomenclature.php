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
use App\Models\Entrepot;
use App\Models\Tier;
use App\Models\Utilisateur;
use App\Models\DeviseTva;
use App\Models\Entite;
use App\Models\Nomenclature;
use App\Models\ComposantNomenclature;
use App\Models\Stock;

class DetailNomenclature extends Component
{
    public $id; 
    public $ids;
    public $ouvre = 0;
    public $confirmer; 
    public $approuver; 
    

    #[Validate('required|max:255')]
    public $libelle; 

    #[Validate('required|max:55')]
    public $type_nomencla = 'Fabrication';        

    #[Validate('required|max:255')]
    public $produit_a_fabrique; 

    #[Validate('required|numeric')]
    public $quantite; 

    #[Validate('required|max:10')]
    public $unite_mesure = 'Unité(s)';  

    #[Validate('required|max:5')]
    public $duree;     

    #[Validate('required|numeric')]
    public $entrepot_fabrication; 

    #[Validate('max:255')]
    public $description;
    
    #[Validate('required|numeric')]
    public $etat;
    public $code; 

    // pour composant 
    public $choix_composant; 
    public $magasin;     
    public $unite = 'Unité(s)';  
    public $quantite_composant = 1;  
    // fin

    public $created_at;
    public $updated_at;
    public $nom_user;
    public $produitFab;
    public $entrepotFab;
    
    public $orderField = 'id'; 
    public $orderDirection = 'ASC'; 

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
    public function onDataAjout(){
        $this->reset('ouvre');
    } 
    public function ajoutLigne(int $idd){
        $this->ouvre = $idd;
    } 
    public function resetinputFields(){           
        $this->choix_composant = '';      
        $this->quantite_composant = 1;        
        $this->unite = 'Unité(s)'; 
    }
    public function render(){    
        $id = request('id'); // id Nomenclature     
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('id',auth()->user()->societe_id)->get();
        $jourValid = $entite_mod[0]->validite_mod;
        $mod_fabrication = $entite_mod[0]->mod_fabrication;
        $soldeClient = $entite_mod[0]->solde; 
        if($dateJour <= $jourValid){
            if($mod_fabrication == 1){
                $title = 'Détails nomenclature | WamsCo';
                $module = 'Gestion fabrication';
                $title_fils = 'Détails nomenclature';
                $lien = 'listing_fact_clt';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');      
                $dateJour = date('Y-m-d');
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');

                // ceci au chargement de la page
                    $test_nomen = Nomenclature::where('id',$id)->count();    
                    if($test_nomen > 0){
                        $nomen = Nomenclature::where('id',$id)->first();               
                        $this->ids = $nomen->id;
                        $this->libelle = $nomen->libelle;
                        $this->type_nomencla = $nomen->type_nomencla;
                        $this->produit_a_fabrique = $nomen->produit_id; // recupere id nomencla
                        $this->quantite = $nomen->quantite;
                        $this->unite_mesure = $nomen->unite_mesure;
                        $this->duree = $nomen->duree;
                        $this->entrepot_fabrication = $nomen->id_entrepot;
                        $this->description = $nomen->description;
                        $this->etat = $nomen->etat;
                        $this->code = $nomen->code;

                        $this->nom_user = $nomen->nom_user;
                        $this->created_at = $nomen->created_at;
                        $this->updated_at = $nomen->updated_at;
                        $this->produitFab = $nomen->produit_a_fabrique;
                        $this->entrepotFab = $nomen->entrepot_fabrication;                        
                    } 
                
                $produit = Produit::where('societe_id',auth()->user()->societe_id)->where('nature_produit','!=','Matière première')->where('etat',1)->orderBy('nom_produit')->get();
                $entrepot = Entrepot::where('societe_id',auth()->user()->societe_id)->where('active',1)->orderBy('nom','asc')->get(); 
                $composant_produit = Produit::where('societe_id',auth()->user()->societe_id)->where('nature_produit','!=','Manufacturé')->where('etat',1)->orderBy('nom_produit')->get();
                // select
                $listEntrepot = Entrepot::where('societe_id',auth()->user()->societe_id)->orderBy('nom','asc')->where('active',1)->get(); 
                $stock = Stock::where('societe_id',auth()->user()->societe_id)->where('id_produit',$this->choix_composant)->where('type_produit','Produit')->where('etat',1)->get();  
                // Fin select

                $page = 'Nomenclature'; // Pour evenement lie
                $log = LogActivityModel::where('societe_id',auth()->user()->societe_id)->where('id_activite', $this->ids)->where('page', $page)->limit(50)->orderBy('id','desc')->get();
                $logCount = $log->count();

                $listeComposant = ComposantNomenclature ::where('societe_id',auth()->user()->societe_id)->where('nomencla_id',$this->ids)->orderBy($this->orderField, $this->orderDirection)->get();
                $listeComposantCount = $listeComposant->count();
                
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
                return view('livewire.fabrication.detail-nomenclature',compact('title_fils','module','lien','dateJour','produit','entrepot','composant_produit','listEntrepot','stock','log','logCount','listeComposant','listeComposantCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
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
    public function update(){       
        $this->validate(); 
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_nomencla;
            if($autoriser == 1){ 
                // recuperer id produit tres important pour envoyer les produits fabriques dans dans le stock 
                $prod = Produit::where('societe_id',auth()->user()->societe_id)->where('id',$this->produit_a_fabrique)->where('etat',1)->orderBy('nom_produit')->first();
                $prods_id =$prod->id;
                $nom_produit_a_fabriq =$prod->nom_produit;
                
                // recuperer id et nom entrepot
                $entrepo = Entrepot::where('societe_id',auth()->user()->societe_id)->where('id',$this->entrepot_fabrication)->where('active',1)->orderBy('nom')->first();
                $entrepo_id =$entrepo->id;
                $nom_entrepot =$entrepo->nom;

                // $date = date('dmy');
                // $length = 3;
                // $token = bin2hex(random_bytes($length));
                // $token_ok = 'NOM'.$date.'-'.$token; 

                Nomenclature::where('id',$this->ids)->update(['libelle'=>$this->libelle,'produit_id'=>$prods_id,'produit_a_fabrique'=>$nom_produit_a_fabriq,'quantite'=>$this->quantite,'unite_mesure'=>$this->unite_mesure,
                'entrepot_fabrication'=>$nom_entrepot,'id_entrepot'=>$entrepo_id,'duree'=>$this->duree,'type_nomencla'=>$this->type_nomencla,'description'=>$this->description,'etat'=>$this->etat,'societe'=>auth()->user()->societe,
                'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                                               
                $id_activite = $this->ids;  
                $page = 'Nomenclature';    
                LogActivity::addToLog('Nomenclature » '.$this->libelle.' ('.$this->code.') modifiée', $id_activite, $page);  
                $this->dispatch('alert',                    
                    title:'Nomenclature (<strong>'.$this->libelle.'</strong>) modifiée!',
                    timer:3000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                ); 
                flash ('Nomenclature (<strong>'.$this->libelle.'</strong>) modifiée')->success();
                $this->redirect('/detail_nomencla?id='.$this->ids.'&active=9&champ=1-1&choix=2', navigate: true);                
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
        $testPrecedant = Nomenclature::where('societe_id',auth()->user()->societe_id)->where('id','<',$id)->orderBy('id','desc')->count();
        if($testPrecedant > 0){ 
            $precedant = Nomenclature::where('societe_id',auth()->user()->societe_id)->where('id','<',$id)->orderBy('id','desc')->first();        
            $previous = $precedant->id; 
            $this->redirect('/detail_nomencla?id='.$previous.'&active=9&champ=1-1&choix=2', navigate: true);             
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
            $this->redirect('/detail_nomencla?id='.$id.'&active=9&champ=1-1&choix=2', navigate: true);  // ceci evite une erreur
        }    
    }    
    public function suivant(int $id){    
        
        $testSuivant = Nomenclature::where('societe_id',auth()->user()->societe_id)->where('id','>',$id)->orderBy('id','asc')->count();
        if($testSuivant > 0){
            $suivant = Nomenclature::where('societe_id',auth()->user()->societe_id)->where('id','>',$id)->orderBy('id','asc')->first();
            $next = $suivant->id;             
            $this->redirect('/detail_nomencla?id='.$next.'&active=9&champ=1-1&choix=2', navigate: true);                     
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
            $this->redirect('/detail_nomencla?id='.$id.'&active=9&champ=1-1&choix=2', navigate: true); // ceci evite une erreur
        } 
    } 
     // gerer les composants
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
    public function confirmerEcraser(int $id){
        $this->approuver = $id;        
    }
    public function ecraser(){ 
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->supprimer_nomencla;
            if($autoriser == 1){
                Nomenclature::where('id',$this->ids)->delete();
                ComposantNomenclature::where('nomencla_id',$this->ids)->delete();
                $page = 'Nomenclature';    
                LogActivityModel::where('id_activite',$this->ids)->where('page',$page)->delete();
                $id_activite = $this->ids;  
                LogActivity::addToLog('Nomenclature  » '.$this->code.' et Composant supprimés', $id_activite, $page);
                $this->dispatch('alert',                    
                    title:'Nomenclature et Composant supprimés',
                    timer:3000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                ); 
                flash ('Nomenclature  » <strong>'.$this->code.'</strong> et Composant supprimés')->success();
                $this->redirect('/listing_nomencla?active=9&champ=1-1&choix=2', navigate: true);         
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
