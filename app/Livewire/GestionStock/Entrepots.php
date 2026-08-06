<?php

namespace App\Livewire\GestionStock;

use Livewire\Component;
use Livewire\Attributes\Validate; 
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use App\Models\Role;
use App\Models\Entrepot;
use App\Helpers\LogActivity;
use App\Models\LogActivity as LogActivityModel;
use App\Models\Entite;
use App\Models\Stock;
use App\Models\DeviseTva;

class Entrepots extends Component
{
    protected $paginationTheme = 'bootstrap';
    use WithPagination;

    public $ids = 0;

    public $nom; 
    public $reference;  
    public $etat; // pour create 
    public $etats; // pour update 
    public $entrepot_parent;
    public $description;
    public $adresse;
    public $code_postal;
    public $ville;
    public $pays = 'Cameroon'; 
    public $telephone;
    public $email; 
    
    public $devise;  

    public $confirmer;
    public $query;
    public $parPage = 20;
    public $parTier; 
    public array $selection = [];
    public $orderField = 'id'; 
    public $orderDirection = 'DESC'; 

    public function resetinputFields(){ 
        $this->nom ='';
        $this->reference ='';        
        $this->entrepot_parent = '';        
        $this->etat = 1;
        $this->description ='';
        $this->adresse ='';
        $this->code_postal ='';
        $this->ville ='';
        $this->pays ='Cameroon';
        $this->telephone ='';        
        $this->email ='';       
    }
    // ceci permet de masquer le formulaire apres le update
    protected $listeners = [
        'entrepotUpdate' => 'onEntrepotUpdated'
    ];
    public function onEntrepotUpdated(){
        $this->reset('ids');
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
        $this->etat = "1";        
    }
    public function render(){

        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();
        $jourValid = $entite_mod[0]->validite_mod;
        $mod_gestion_stock = $entite_mod[0]->mod_gestion_stock; 
        $soldeClient = $entite_mod[0]->solde;
        if($dateJour <= $jourValid){
            if($mod_gestion_stock == 1){
                $title = 'Listing Magasin | WamsCo';
                $module = 'Gestion stock';
                $title_fils = 'Magasins';
                $lien = 'entrepot';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');  
                $dateJour = date('Y-m-d');
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px'); 
                $entrepot = Entrepot::where('societe',auth()->user()->societe)->where('nom','like','%'.$this->query.'%')->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage);       
                $entrepotCount =  $entrepot->count();

                $entrepo = Entrepot::where('societe',auth()->user()->societe)->get();  // pour select

                $deviseTva = DeviseTva :: where('societe',auth()->user()->societe)->limit(1)->orderBy('id','asc')->count();             
                if($deviseTva == 0){
                    $this->devise = 'FCFA';
                }
                else{
                    $deviseTva = DeviseTva :: where('societe',auth()->user()->societe)->limit(1)->orderBy('id','asc')->get(); 
                    $this->devise = $deviseTva[0]->devise;                
                } 

                $resultat = Entrepot :: where('societe',auth()->user()->societe)->get();  
                $nbreTotalEntrepot = $resultat->count();     
                $stock_total = $resultat->sum('stock_total');
                $valorisation_achat_total = $resultat->sum('valorisation_achat_total');
                $valeur_vente_total = $resultat->sum('valeur_vente_total');

                $derniereActivite = Entrepot::where('societe',auth()->user()->societe)->latest('updated_at')->first(); 

                $page = 'Entrepot'; // Pour evenement lie
                $log = LogActivityModel::where('user_societe',auth()->user()->societe)->where('page', $page)->limit(50)->orderBy('id','desc')->get();
                $logCount = $log->count();                

                $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get(); 
                $jourValid = $entite_mod[0]->validite_mod; 
                // ceci pour trouver le nombre de jour restant avant expiration
                $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24));
                return view('livewire.gestion-stock.entrepots.entrepots',compact('title_fils','module','lien','dateJour','entrepot','entrepotCount','entrepo','nbreTotalEntrepot','stock_total',
                'valorisation_achat_total','valeur_vente_total','derniereActivite','log','logCount'))->layout('components.layouts.app',compact('title','module','title_fils','lien','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant')); 
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
            $module = 'Gestion stock';
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
    public function store(){
        $this->validate([
            'nom'=>'required|max:255',
            'reference'=>'required|max:255',
            'etat'=>'required|numeric',
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
            $autoriser = $role[0]->creer_entrepot;
            if($autoriser == 1){   
                
                Entrepot::create(['nom'=>$this->nom,'reference'=>$this->reference,'entrepot_parent'=>$this->entrepot_parent,'active'=>$this->etat,'description'=>$this->description,
                'adresse'=>$this->adresse,'code_postal'=>$this->code_postal,'ville'=>$this->ville,'pays'=>$this->pays,'telephone'=>$this->telephone,
                'email'=>$this->email,'societe'=>auth()->user()->societe,'societe_mere'=>auth()->user()->societe_mere,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);               
                
                // ceci recupere le dernier enregistrement cree a l'instant
                $dernier_id = Entrepot::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id; 
                $id_activite = $dernier_id;
                $page = 'Entrepot'; // Pour evenement lie
                LogActivity::addToLog('Entrepôt » '.$this->nom.' crée', $id_activite, $page);
                $this->dispatch('alert',                    
                    title:'Entrepôt/Magasin ('.$this->nom.') enregistré!',
                    timer:3000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                ); 
                flash ('Entrepôt/Magasin <strong>('.$this->nom.')</strong> crée')->success();
                $this->resetinputFields();
                $this->redirect('/detail_entrepot?id='.$dernier_id.'&active=4&champ=3-1&choix=2', navigate: true);
                // $this->dispatch('entrepotCreate');
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
            'etat'=>'required|numeric',
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
                    $this->resetinputFields();
                    $this->redirect('/entrepot?active=4&champ=3-1&choix=2', navigate: true);
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
    public function changeEtat(int $id, int $etat){  
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_entrepot; 
            if($autoriser == 1){      
                if($etat == 1){
                    $ferme = 0;
                    Entrepot::find($id)->update(['active'=>$ferme,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                    $id_activite = $id;
                    $page = 'Entrepot';
                    LogActivity::addToLog('Etat entrepôt (Fermé)', $id_activite, $page); 
                    $this->dispatch('alert',                    
                        title:'Entrepot désactivé avec succès!',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );  
                }
                elseif($etat == 0){
                    $ouvert = 1;
                    Entrepot::find($id)->update(['active'=>$ouvert,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                    $id_activite = $id;
                    $page = 'Entrepot';
                    LogActivity::addToLog('Etat entrepôt (Ouvert)', $id_activite, $page);
                    $this->dispatch('alert',                    
                        title:'Entrepot activé avec succès!',
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
    }
    public function confirmerDelete($id){ 
        $this->confirmer = $id;      
    } 
    public function supprimer($id){ 
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->supprimer_entrepot;
            if($autoriser == 1){  
                if($id){ 
                    Entrepot::where('id',$id)->delete();
                    Stock::where('id_entrepot',$id)->delete();
                    $page = 'Entrepot';
                    LogActivityModel::where('id_activite',$id)->where('page',$page)->delete();
                    $id_activite = $id;
                    LogActivity::addToLog('Entrepôt supprimé définitivement', $id_activite, $page);
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
    // suppression multiple
    // public function deleteEntrepot(array $ids){   
    //     $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
    //     if($test > 0){
    //         $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
    //         $autoriser = $role[0]->supprimer_produit;
    //         if($autoriser == 1){            
    //             Entrepot::destroy($ids);
    //             $this->selection = [];
    //             $this->dispatchBrowserEvent('swal', [
    //                 'title' => 'Suppression effectué !',
    //                 'timer'=>3000,
    //                 'icon'=>'success',
    //                 'toast'=>true,
    //                 'showConfirmButton'=> false,
    //                 'position'=>'top-end',
    //             ]);
    //         }
    //         else{                 
    //             $this->dispatchBrowserEvent('swal', [
    //                 'title' => 'Vous n\'êtes pas autorisé à effectuer cette opération !!!',
    //                 'timer'=>3000,
    //                 'icon'=>'error',
    //                 'toast'=>false,
    //                 'position'=>'center'
    //             ]);   
    //         } 
    //     }
    //     else{             
    //         $this->dispatchBrowserEvent('swal', [
    //             'title' => 'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!',
    //             'timer'=>3000,
    //             'icon'=>'error',
    //             'toast'=>false,
    //             'position'=>'center'
    //         ]);
    //     } 
    // }
}
