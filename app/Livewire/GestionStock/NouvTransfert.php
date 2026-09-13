<?php

namespace App\Livewire\GestionStock;

use Livewire\Component;
use Livewire\Attributes\Validate; 
use App\Helpers\LogActivity;
use App\Models\LogActivity as LogActivityModel;
use Livewire\WithPagination;
// use Livewire\WithFileUploads; 
use App\Models\Role;
use App\Models\Entrepot;
// use App\Models\Categorie;
use App\Models\Produit;
use App\Models\Tier;
use App\Models\Utilisateur;
use App\Models\DeviseTva;
use App\Models\Entite;
use App\Models\Stock;
use App\Models\Transfert;

class NouvTransfert extends Component
{
    // use WithFileUploads; 

    public $id;    
    #[Validate('required')] 
    public $entrepot_origine; 

    #[Validate('required')] 
    public $entrepot_destination; 

    #[Validate('required')] 
    public $date_sortie; 

    #[Validate('required')]     
    public $date_entree;  
    
    public $transporteur;

    #[Validate('required|numeric')]
    public $nombre_paquets; 
   
    public $code_inventaire;  

    #[Validate('required')]   
    public $etiquette_transfert; 
    
    public $note;  
    public $etat; 

    public function resetinputFields(){ 
        $this->entrepot_origine ='';
        $this->entrepot_destination ='';
        $this->date_sortie = date('Y-m-d');
        $this->date_entree = date('Y-m-d');
        $this->transporteur = '';          
        $this->nombre_paquets = 1; 
        $this->code_inventaire ='';        
        $this->etiquette_transfert = 'Transfert de stock '.date('Y-m-d H:i');             
        $this->note = ''; 
    }
    public function mount(){        
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_transfert;
            if($autoriser == 0){
                alert()->error('Oups Désolé', 'Vous n\'êtes pas autorisé à ouvrir cette page !!!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
                $this->redirect('/bienvenue', navigate: true);
            }
        }
        else{
            alert()->error('Oups Désolé', 'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
            $this->redirect('/bienvenue', navigate: true);
        }
        $this->date_sortie = date('Y-m-d');
        $this->date_entree = date('Y-m-d');
        $this->nombre_paquets = 1;
        $this->etiquette_transfert = 'Transfert de stock '.date('Y-m-d H:i');
    }
    public function render()
    {
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();
        $jourValid = $entite_mod[0]->validite_mod;
        $mod_gestion_stock = $entite_mod[0]->mod_gestion_stock; 
        $soldeClient = $entite_mod[0]->solde;
        if($dateJour <= $jourValid){
            if($mod_gestion_stock == 1){
                $title = 'Nouveau Transfert | WamsCo';
                $module = 'Gestion stock';
                $title_fils = 'Nouveau transfert';
                $lien = 'produit';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');      
                $dateJour = date('Y-m-d');
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px'); 
                
                $listEntrepot = Entrepot::where('societe',auth()->user()->societe)->where('active',1)->orderBy('nom','asc')->get();   
                $listedeviseTva = DeviseTva :: where('societe',auth()->user()->societe)->get();

                $page = 'Transfert'; // Pour evenement lie
                $log = LogActivityModel::where('user_societe',auth()->user()->societe)->where('page', $page)->limit(7)->orderBy('id','desc')->get();
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
                return view('livewire.gestion-stock.transferts.nouv-transfert',compact('title_fils','module','lien','dateJour','listEntrepot','listedeviseTva','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
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
            $autoriser = $role[0]->creer_transfert;
            if($autoriser == 1){                  
                    if($this->entrepot_origine == $this->entrepot_destination){
                        $this->dispatch('alert',                    
                            title:'Les entrepôts <strong>origine</strong> et <strong>destination</strong> doivent être différents!',
                            timer:5000,
                            icon:'error',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        ); 
                        flash ('Les entrepôts <strong>origine</strong> et <strong>destination</strong> doivent être différents!')->error(); 
                        return back();            
                    }  

                    $entrepot =  Entrepot::where('societe',auth()->user()->societe)->where('id',$this->entrepot_origine)->first();
                    $nom_entrepot_origine = $entrepot->nom;

                    $entrepot =  Entrepot::where('societe',auth()->user()->societe)->where('id',$this->entrepot_destination)->first();
                    $nom_entrepot_destination = $entrepot->nom;

                    $this->etat = 'Brouillon';
                    Transfert::create(['entrepot_origine'=>$nom_entrepot_origine,'entrepot_destination'=>$nom_entrepot_destination,'id_entrepot_origine'=>$this->entrepot_origine,'id_entrepot_destination'=>$this->entrepot_destination,
                    'date_sortie'=>$this->date_sortie,'date_entree'=>$this->date_entree,'transporteur'=>$this->transporteur,'nombre_paquets'=>$this->nombre_paquets,'code_inventaire'=>$this->code_inventaire,
                    'etiquette_transfert'=>$this->etiquette_transfert,'note'=>$this->note,'etat'=>$this->etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                   
                    // ceci recupere le dernier enregistrement cree a l'instant
                    $dernier_id = Transfert::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id; 
                    $id_activite = $dernier_id;
                    $page = 'Transfert'; // Pour evenement lie
                    LogActivity::addToLog('Entête transfert » '.$nom_entrepot_origine.' vers » '.$nom_entrepot_destination.' créé', $id_activite, $page);
                    $this->dispatch('alert',                    
                        title:'Veuillez ajouter les produits à transferer!',
                        timer:5000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );  
                    $this->resetinputFields();  
                    $this->redirect('/detail_transfert?id='.$dernier_id.'&active=4&champ=3-1&choix=5', navigate: true);  
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
