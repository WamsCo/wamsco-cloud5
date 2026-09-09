<?php

namespace App\Livewire\Administration;

use Livewire\Component;
use Livewire\Attributes\Validate;  
use App\Helpers\LogActivity;
use App\Models\LogActivity as LogActivityModel;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Entite;
use App\Models\DeviseTva;
use App\Models\Role;
use App\Models\soldeClient;

class HistoriquePaieClt extends Component
{
     protected $paginationTheme = 'bootstrap';
    use WithPagination;
    use WithFileUploads;

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
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->consulter_entite;
            if($autoriser == 0){
                alert()->error('Oups Désolé', 'Vous n\'êtes pas autorisé à ouvrir cette page !!!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
                $this->redirect('/bienvenue', navigate: true);
            }
        }
        else{
            alert()->error('Oups Désolé', 'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
            $this->redirect('/bienvenue', navigate: true);
        }        
        $this->pays = 'Cameroon';
        $this->recommandation = 'contact@wamsco-cloud.net';
        // $this->parSociete = auth()->user()->societe_mere;
    }  
    public function render()
    {
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('id',auth()->user()->societe_id)->get();
        $jourValid = $entite_mod[0]->validite_mod; 
        $mod_administration = $entite_mod[0]->mod_administration; 
        $soldeClient = $entite_mod[0]->solde;
        if($dateJour <= $jourValid){    
            if($mod_administration == 1){        
                $title = 'Historique paie clients | WamsCo';
                $module = 'Paramètres';
                $title_fils = 'Historique paie clients';
                $lien = 'solde_clients';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');      
                $dateJour = date('Y-m-d');
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');

                if(empty($this->query)){
                    $liste_soldeClient = soldeClient::where('id_enseigne',auth()->user()->societe_id)->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage); 
                }
                else{
                    $liste_soldeClient = soldeClient::where('id_enseigne',auth()->user()->societe_id)->where('designation','like','%'.$this->query.'%')->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage); 
                }
                $soldeClient_count = $liste_soldeClient->count(); 
                $montantDebit = $liste_soldeClient->sum('debit');
                $montantCredit = $liste_soldeClient->sum('credit');                                                   
                // $entite = Entite::orderBy('enseigne','Asc')->get();  
                
                // KPI
                $resultat = soldeClient::where('enseigne',auth()->user()->societe)->get();
                $nbreTotalSoldeClient = $resultat->count();  
                $TotalDebit = $resultat->sum('debit'); 
                $TotalCredit = $resultat->sum('credit');
                $soldeTotal = $TotalCredit - $TotalDebit; 
                // Fin KPI

                $derniereActivite = soldeClient::where('enseigne',auth()->user()->societe)->latest('updated_at')->first();

                $page = 'soldeClient'; // pour evenement lies
                $log = LogActivityModel::where('societe_id',auth()->user()->societe_id)->where('page', $page)->limit(50)->orderBy('id','desc')->get();
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
                return view('livewire.administration.soldes.historique-paie-clt',compact('title_fils','module','lien','dateJour','liste_soldeClient','soldeClient_count',
                'montantDebit','montantCredit','TotalDebit','TotalCredit','soldeTotal','nbreTotalSoldeClient','derniereActivite','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));                       
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
}
