<?php

namespace App\Livewire\Tiers;

use Livewire\Component;
use Livewire\Attributes\Validate;  
use App\Helpers\LogActivity;
use App\Models\LogActivity as LogActivityModel;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Entite;
use App\Models\DeviseTva;
use App\Models\Role;
use App\Models\SoldeTier;
use App\Models\Tier;
use App\Models\factureClientEntete;


class SoldeTiers extends Component
{
    protected $paginationTheme = 'bootstrap';
    use WithPagination;
    use WithFileUploads;

    public $ids;
    public $parSociete;
    public $devise;
    public $query;   
    public $parPage = 20;
    public $confirmer; 

    public $orderField = 'id'; 
    public $orderDirection = 'DESC'; 

    public function setOrderField(string $name){
        if($name === $this->orderField){
            $this->orderDirection = $this->orderDirection === 'ASC' ? 'DESC' : 'ASC';
        }
        else{
            $this->orderField = $name;
            $this->reset('orderDirection');
        }
    }
    public function updatingQuery(){
        $this->resetPage();
    }
    public function mount(){        
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->consulter_tier;
            if($autoriser == 0){
                alert()->error('Oups Désolé', 'Vous n\'êtes pas autorisé à ouvrir cette page !!!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
                $this->redirect('/bienvenue', navigate: true);
            }
        }
        else{
            alert()->error('Oups Désolé', 'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
            $this->redirect('/bienvenue', navigate: true);
        }      
        $this->ids = request('id');  // id tier  tres important de le mettre ici.       
    }  
    public function render(){        
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();
        $jourValid = $entite_mod[0]->validite_mod; 
        $mod_administration = $entite_mod[0]->mod_administration; 
        $soldeClient = $entite_mod[0]->solde;        
        if($dateJour <= $jourValid){   
              
            $title = 'Solde Tiers | WamsCo';
            $module = 'Paramètres';
            $title_fils = 'Solde Tiers';
            $lien = 'solde_tiers';
            $active = request('active');
            $champ = request('champ');
            $choix = request('choix');      
            $dateJour = date('Y-m-d');
            toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');           

            if(empty($this->query)){
                $liste_soldeTier = SoldeTier::where('societe',auth()->user()->societe)->where('id_tier',$this->ids)->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage); 
            }
            else{
                $liste_soldeTier = SoldeTier::where('societe',auth()->user()->societe)->where('id_tier',$this->ids)->where('designation','like','%'.$this->query.'%')->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage); 
            }
            $soldeTier_count = $liste_soldeTier->count(); 
            $montantDebit = $liste_soldeTier->sum('debit');
            $montantCredit = $liste_soldeTier->sum('credit');  
            $soldes = $montantCredit - $montantDebit;
                                                            
            $entite = Entite::orderBy('enseigne','Asc')->get(); 

            $tier = Tier::where('societe',auth()->user()->societe)->where('id',$this->ids)->get();      
            $tiersCount = $tier->count(); 
            // Facture
            $factClient_entete = factureClientEntete::where('societe',auth()->user()->societe)->where('id_client',$this->ids)->orderBy('id', 'DESC')->limit(10)->get();
            $factClientEnteteCount = $factClient_entete->count();                 
            $factClientEnteteTTC = $factClient_entete->sum('montant_ttc');  
            $factClientEnteteMarge = $factClient_entete->sum('marge');   
            $factClientEnteteResteApercevoir = $factClient_entete->sum('reste_a_percevoir'); 

            $page = 'Tiers'; // pour evenement lies
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
            return view('livewire.tiers.solde-tiers',compact('title_fils','module','lien','dateJour','liste_soldeTier','soldeTier_count','entite','tier','tiersCount','factClientEnteteCount','log','logCount',
            'factClientEnteteTTC','factClientEnteteMarge','factClientEnteteResteApercevoir','montantDebit','montantCredit','soldes'))->layout('components.layouts.app',compact('title','active','champ',
            'choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));  
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
    public function detailTier(){ 
        
        $this->redirect('/detail_tier?id='.$this->ids.'&active=3&champ=3-2&', navigate: true); 
    } 
}
