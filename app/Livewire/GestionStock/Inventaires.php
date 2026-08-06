<?php

namespace App\Livewire\GestionStock;

use Livewire\Component;
use Livewire\Attributes\Validate; 
Use Carbon\Carbon;
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

class Inventaires extends Component
{    

    protected $paginationTheme = 'bootstrap';    // ceci pour pagination avec les chiffres tres important
    use WithPagination;
    
    public $query;
    public $parEntrepot; // id
    public $parPage = 20;
    public $confirmer;
    public $image_produit; 
    public $orderField = 'id'; 
    public $orderDirection = 'DESC'; 
    public $recherchePar = 'nom';  // (Recherche par: nom , reference)
    public $filtre; 
    // public $cherche;

    public $reference; 
    public $libelle; 
    public $date_inventaire;  
    public $entrepot;   
    public $note; 
    
    public $date_debut; 
    public $date_fin; 

    public function resetinputFields(){ 
        $this->reference ='';
        $this->libelle ='';
        $this->date_inventaire = '';            
        $this->note = ''; 
    }
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
    public function mount(){        
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->consulter_inventaire;
            if($autoriser == 0){
                alert()->error('Oups Désolé', 'Vous n\'êtes pas autorisé à ouvrir cette page !!!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
                $this->redirect('/bienvenue', navigate: true);
            }
        }
        else{
            alert()->error('Oups Désolé', 'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
            $this->redirect('/bienvenue', navigate: true);
        }
        $this->date_debut = date('Y-m-d', strtotime('-1 year'));  // ceci pour affiche toutes les sessions en permanance sur 1 mois par defaut
        $this->date_fin = date('Y-m-d'); 
    }
    public function render(){
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();
        $jourValid = $entite_mod[0]->validite_mod; 
        $mod_gestion_stock = $entite_mod[0]->mod_gestion_stock;
        $soldeClient = $entite_mod[0]->solde;
        if($dateJour <= $jourValid){
            if($mod_gestion_stock == 1){
                $title = 'Listing Inventaires | WamsCo';
                $module = 'Gestion stock';
                $title_fils = 'Inventaire';
                $lien = 'inventaires';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');      
                $dateJour = date('Y-m-d');
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px'); 
                
                $start = Carbon::parse($this->date_debut)->startOfDay(); //2016-09-29 00:00:00.000000
                $end = Carbon::parse($this->date_fin)->endOfDay();     // 2016-09-29 23:59:59.000000
                if(!empty($this->parEntrepot)){
                    $inventaire = Inventaire::where('societe',auth()->user()->societe)->where('reference','like','%'.$this->query.'%')->where('id_entrepot',$this->parEntrepot)->whereBetween('created_at',[$start, $end])->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage);
                }
                else{
                    $inventaire = Inventaire::where('societe',auth()->user()->societe)->where('reference','like','%'.$this->query.'%')->whereBetween('created_at',[$start, $end])->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage);
                }
                $inventaireCount = $inventaire->count(); 
                $listedeviseTva = DeviseTva :: where('societe',auth()->user()->societe)->get();
                $listEntrepot = Entrepot::where('societe',auth()->user()->societe)->where('active',1)->orderBy('nom','asc')->get(); 

                $resultat = Inventaire::where('societe',auth()->user()->societe)->get();  
                $nbreTotalInventaire = $resultat->count();     

                $derniereActivite = Inventaire::where('societe',auth()->user()->societe)->latest('updated_at')->first();                

                $page = 'Inventaire'; // pour evenement lies
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
                return view('livewire.gestion-stock.inventaires.inventaire',compact('title_fils','module','lien','dateJour','inventaire','inventaireCount','listedeviseTva','listEntrepot','nbreTotalInventaire','derniereActivite','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
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
        return view('livewire.gestion-stock.inventaire');
    }    
    public function store(){
        $this->validate([
            'libelle'=>'required|max:255',
            'reference'=>'required|max:255',
            'entrepot'=>'required|max:255',
            'date_inventaire'=>'required|max:255',
            'note'=>'nullable|max:255',
        ]);                
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_inventaire;
            if($autoriser == 1){  

                    $dataEntrepot = Entrepot::where('societe',auth()->user()->societe)->where('id',$this->entrepot)->get(); 
                    $ide = $dataEntrepot[0]->id;
                    $nameEntrepot = $dataEntrepot[0]->nom;

                    $this->etat = 'Brouillon';
                    Inventaire::create(['reference'=>$this->reference,'libelle'=>$this->libelle,'entrepot'=>$nameEntrepot,'id_entrepot'=>$this->entrepot,'date_inventaire'=>$this->date_inventaire,
                    'note'=>$this->note,'etat'=>$this->etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                   
                    // ceci recupere le dernier enregistrement cree a l'instant
                    $dernier_id = Inventaire::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id;
                    $id_activite = $dernier_id;
                    $page = 'Inventaire'; // Pour evenement lie
                    LogActivity::addToLog('Entête inventaire » '.$this->reference.' créé', $id_activite, $page); 
                    $this->dispatch('alert',                    
                        title:'Veuillez ajouter les produits de l\'inventaire!',
                        timer:5000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );  
                    $this->resetinputFields();  
                    $this->redirect('/detail_inventaire?id='.$dernier_id.'&active=4&champ=3-1&choix=7', navigate: true);  
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
                    $page = 'Inventaire'; // Pour evenement lie
                    Inventaire::where('id',$id)->delete();
                    InventaireLigne::where('id_inventaire',$id)->delete();
                    LogActivityModel::where('id_activite',$id)->where('page',$page)->delete();
                    $id_activite = $id;
                    LogActivity::addToLog('Inventaire supprimé', $id_activite, $page);
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
}
