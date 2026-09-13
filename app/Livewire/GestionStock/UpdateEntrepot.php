<?php

namespace App\Livewire\GestionStock;

use Livewire\Component;
use Livewire\Attributes\Validate; 
use App\Helpers\LogActivity;
use App\Models\LogActivity as LogActivityModel;
use App\Models\Entite;
use App\Models\Entrepot;
use App\Models\Role;

class UpdateEntrepot extends Component
{
    public $ids;

    #[Validate('required|max:255')]
    public $nom; 

    #[Validate('required|max:255')]
    public $reference;  

    #[Validate('required')]  
    public $etat;
    public $entrepot_parent;
    #[Validate('nullable|max:255')]
    public $description;
    #[Validate('nullable|max:255')]
    public $adresse;
    #[Validate('nullable|numeric|max:255')]
    public $code_postal;
    #[Validate('nullable|max:255')]
    public $ville;
    #[Validate('required|max:255')]
    public $pays = 'Cameroon'; 
    #[Validate('nullable|max:255')]
    public $telephone;
    #[Validate('nullable|email|max:255')]
    public $email;  

    public function mount(){  
        
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_entrepot;
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
        $this->etat = 1;            
    }
    public function render(){
    
        $id = request('id'); // id entrepot
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();
        $jourValid = $entite_mod[0]->validite_mod;
        $mod_gestion_stock = $entite_mod[0]->mod_gestion_stock; 
        $soldeClient = $entite_mod[0]->solde;
        if($dateJour <= $jourValid){
            if($mod_gestion_stock == 1){
                $title = 'Modifier Magasin | WamsCo';
                $module = 'Gestion stock';
                $title_fils = 'Modifier magasin';
                $lien = 'entrepot?active=4&champ=3-1&choix=2';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');  
                $dateJour = date('Y-m-d');
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px'); 
                
                $test_entrep = Entrepot::where('societe',auth()->user()->societe)->where('id',$id)->count();    
                if($test_entrep > 0){
                    $entrepot = Entrepot::where('societe',auth()->user()->societe)->where('id',$id)->first(); 
                    $this->ids = $entrepot->id;
                    $this->nom = $entrepot->nom;
                    $this->reference = $entrepot->reference;       
                    $this->entrepot_parent = $entrepot->entrepot_parent;       
                    $this->etat = $entrepot->active;
                    $this->description = $entrepot->description;
                    $this->adresse = $entrepot->adresse;
                    $this->code_postal = $entrepot->code_postal;
                    $this->ville = $entrepot->ville;
                    $this->pays = $entrepot->pays;
                    $this->telephone = $entrepot->telephone;        
                    $this->email = $entrepot->email;
                } 
                $entrepotTest = Entrepot::where('societe',auth()->user()->societe)->count(); 
                if($entrepotTest > 0 ){
                    $entrepot = Entrepot::where('societe',auth()->user()->societe)->get();
                }
                else{
                    $entrepot = '';
                }  

                $page = 'Entrepot'; // Pour evenement lie
                $log = LogActivityModel::where('user_societe',auth()->user()->societe)->where('id_activite', $this->ids)->where('page', $page)->limit(7)->orderBy('id','desc')->get();
                $logCount = $log->count();

                $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get(); 
                $jourValid = $entite_mod[0]->validite_mod; 
                // ceci pour trouver le nombre de jour restant avant expiration
                $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24));
                return view('livewire.gestion-stock.entrepots.update-entrepots',compact('title_fils','module','lien','dateJour','entrepot','entrepotTest','log','logCount'))->layout('components.layouts.app',compact('title','module','title_fils','lien','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant')); 
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
    public function update(){
        $this->validate();            
           
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_entrepot;
            if($autoriser == 1){ 
                if($this->ids){  
                    Entrepot::find($this->ids)->update(['nom'=>$this->nom,'reference'=>$this->reference,'entrepot_parent'=>$this->entrepot_parent,'active'=>$this->etat,'description'=>$this->description,
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
                    $this->dispatch('entrepotUpdate');
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
            $this->redirect('/update_entrepot?id='.$previous.'&active=4&champ=3-1&choix=2', navigate: true);              
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
            $this->redirect('/update_entrepot?id='.$id.'&active=4&champ=3-1&choix=2', navigate: true);  // ceci evite une erreur
        }    
    }    
    public function suivant(int $id){    
        
        $testSuivant = Entrepot::where('societe',auth()->user()->societe)->where('id','>',$id)->orderBy('id','asc')->count();
        if($testSuivant > 0){
            $suivant = Entrepot::where('societe',auth()->user()->societe)->where('id','>',$id)->orderBy('id','asc')->first();
            $next = $suivant->id;             
            $this->redirect('/update_entrepot?id='.$next.'&active=4&champ=3-1&choix=2', navigate: true);                     
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
            $this->redirect('/update_entrepot?id='.$id.'&active=4&champ=3-1&choix=2', navigate: true); // ceci evite une erreur
        } 
    } 
}
