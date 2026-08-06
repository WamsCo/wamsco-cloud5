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
// use App\Models\Ordre_fabrication;

class ListeNomenclature extends Component
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
    
    #[Validate('required|max:255')]
    public $libelle; 

    #[Validate('required|max:55')]
    public $type_nomencla = 'Fabrication';        

    #[Validate('required|max:255')]
    public $produit_a_fabrique; 

    #[Validate('required|numeric')]
    public $quantite; 

    #[Validate('required|max:255')]
    public $unite_mesure = 'Unité(s)';

    #[Validate('required|max:5')]
    public $duree;     

    #[Validate('required|numeric')]
    public $entrepot_fabrication; 

    #[Validate('max:255')]
    public $description;
    
    #[Validate('required|numeric')]
    public $etat = 0;

    public $autoriser; // pour gerer les marges
    public $orderField = 'id'; 
    public $orderDirection = 'DESC'; 

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
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $this->autoriser = $role[0]->voir_marge;
            $autoriser = $role[0]->liste_nomencla;
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
    }
    public function render()
    {
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();
        $jourValid = $entite_mod[0]->validite_mod;
        $mod_fabrication = $entite_mod[0]->mod_fabrication;
        $soldeClient = $entite_mod[0]->solde; 
        if($dateJour <= $jourValid){
            if($mod_fabrication == 1){
                $title = 'Liste nomenclature | WamsCo';
                $module = 'Gestion fabrication';
                $title_fils = 'Liste nomenclature';
                $lien = 'listing_nomencla?active=9&champ=1-1&choix=2';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');      
                $dateJour = date('Y-m-d');
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');

                $start = Carbon::parse($this->date_debut)->startOfDay(); //2016-09-29 00:00:00.000000
                $end = Carbon::parse($this->date_fin)->endOfDay();     // 2016-09-29 23:59:59.000000

                // if(empty($this->parEtat) && empty($this->parUser)){
                if(!empty($this->parUser)){
                    $listeNomenclatur = Nomenclature::where('societe',auth()->user()->societe)->where('code','like','%'.$this->parRef.'%')->where('produit_a_fabrique','like','%'.$this->query.'%')->where('user_id',$this->parUser)->whereBetween('created_at',[$start, $end])->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage);
                }
                else{ 
                    $listeNomenclatur = Nomenclature::where('societe',auth()->user()->societe)->where('code','like','%'.$this->parRef.'%')->where('produit_a_fabrique','like','%'.$this->query.'%')->whereBetween('created_at',[$start, $end])->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage);
                }
                $listeNomenclatureCount = $listeNomenclatur->count();      
                
                $produit = Produit::where('societe',auth()->user()->societe)->where('nature_produit','!=','Matière première')->where('etat',1)->orderBy('nom_produit')->get();
                $entrepot = Entrepot::where('societe',auth()->user()->societe)->where('active',1)->orderBy('nom','asc')->get(); 
                
                $utilisat = Utilisateur::where('societe',auth()->user()->societe)->orderBy('name','asc')->get();                 
                  
                $resultat = Nomenclature::where('societe',auth()->user()->societe)->get();  
                $nbreTotalNomenclature = $resultat->where('etat',1)->count();     
                $nbreTotalNomenclatureTotal = $resultat->count();     

                $derniereActivite = Nomenclature::where('societe',auth()->user()->societe)->latest('updated_at')->first();
                
                $page = 'Nomenclature'; // Pour evenement lie
                $log = LogActivityModel::where('user_societe',auth()->user()->societe)->where('page', $page)->limit(50)->orderBy('id','desc')->get();
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
                return view('livewire.fabrication.liste-nomenclature',compact('title_fils','module','lien','dateJour','listeNomenclatur','listeNomenclatureCount','produit','entrepot','utilisat',
                'nbreTotalNomenclature','nbreTotalNomenclatureTotal','derniereActivite','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
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
        $this->validate(); 
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_nomencla;
            if($autoriser == 1){ 
                // recuperer id produit tres important pour envoyer les produits fabriques dans dans le stock 
                $prod = Produit::where('societe',auth()->user()->societe)->where('id',$this->produit_a_fabrique)->where('etat',1)->orderBy('nom_produit')->first();
                $prods_id =$prod->id;
                $nom_produit_a_fabriq =$prod->nom_produit;
                
                // recuperer id et nom entrepot
                $entrepo = Entrepot::where('societe',auth()->user()->societe)->where('id',$this->entrepot_fabrication)->where('active',1)->orderBy('nom')->first();
                $entrepo_id =$entrepo->id;
                $nom_entrepot =$entrepo->nom;

                $date = date('dmy');
                $length = 3;
                $token = bin2hex(random_bytes($length));
                $token_ok = 'BOM/'.$date.'/'.$token; 

                Nomenclature::create(['libelle'=>$this->libelle,'produit_id'=>$prods_id,'produit_a_fabrique'=>$nom_produit_a_fabriq,'code'=>$token_ok,'quantite'=>$this->quantite,'unite_mesure'=>$this->unite_mesure,
                'entrepot_fabrication'=>$nom_entrepot,'id_entrepot'=>$entrepo_id,'duree'=>$this->duree,'type_nomencla'=>$this->type_nomencla,'description'=>$this->description,'etat'=>$this->etat,'societe'=>auth()->user()->societe,
                'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                                               
                // ceci recupere le dernier enregistrement cree a l'instant
                $dernier_id = Nomenclature::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id; 
                $id_activite = $dernier_id;  
                $page = 'Nomenclature';    
                LogActivity::addToLog('Nomenclature » '.$this->libelle.' ('.$token_ok.') créée', $id_activite, $page); 
                $this->dispatch('alert',                    
                    title:'Nomenclature (<strong>'.$this->libelle.'</strong>) créée!',
                    timer:3000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                ); 
                flash ('Nomenclature (<strong>'.$this->libelle.'</strong>) créée')->success();
                $this->redirect('/detail_nomencla?id='.$dernier_id.'&active=9&champ=1-1&choix=2', navigate: true);                
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
    public function changeEtat(int $id, int $etat){  
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_produit;
            if($autoriser == 1){        
                if($etat == 1){
                    $ferme = 0;
                    Nomenclature::find($id)->update(['etat'=>$ferme,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                    $id_activite = $id;
                    $page = 'Nomenclature';
                    LogActivity::addToLog('Etat nomenclature (Fermé)', $id_activite, $page); 
                    $this->dispatch('alert',                    
                        title:'Nomenclature désactivé avec succès!',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );  
                }
                elseif($etat == 0){
                    $ouvert = 1;
                    Nomenclature::find($id)->update(['etat'=>$ouvert,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                    $id_activite = $id;
                    $page = 'Nomenclature';
                    LogActivity::addToLog('Etat nomenclature (Ouvert)', $id_activite, $page); 
                    $this->dispatch('alert',                    
                        title:'Nomenclature activé avec succès!',
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
    public function confirmerDeleted(int $id){
        $this->confirmer = $id;        
    }
    public function supprimerAll(int $ids){ 
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->supprimer_nomencla;
            if($autoriser == 1){
                Nomenclature::where('id',$ids)->delete();
                ComposantNomenclature::where('nomencla_id',$ids)->delete();
                $page = 'Nomenclature';    
                LogActivityModel::where('id_activite',$ids)->where('page',$page)->delete();
                $id_activite = $ids;  
                LogActivity::addToLog('Nomenclature et Composant supprimés', $id_activite, $page);
                $this->dispatch('alert',                    
                    title:'Nomenclature et Composant supprimés',
                    timer:3000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                ); 
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
