<?php

namespace App\Livewire\GestionStock;

use Livewire\Component;
Use Carbon\Carbon;
use Livewire\WithPagination;
use App\Models\Role;
use App\Models\Entrepot;
use App\Helpers\LogActivity;
use App\Models\LogActivity as LogActivityModel;
use App\Models\Entite;
use App\Models\DeviseTva;
use App\Models\Produit;
use App\Models\Stock;
use App\Models\Mouvement;

class Mouvements extends Component
{
    protected $paginationTheme = 'bootstrap';    // ceci pour pagination avec les chiffres tres important
    use WithPagination;

    public $date_debut;
    public $date_fin; 

    public $query;
    public $ParMag;    

    public $orderField = 'created_at'; 
    public $orderDirection = 'DESC'; 
    public $affiche = 10;
    public $parPage = 20;

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
            $autoriser = $role[0]->mouvement_stock;
            if($autoriser == 0){
                alert()->error('Oups Désolé', 'Vous n\'êtes pas autorisé à ouvrir cette page !!!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
                $this->redirect('/bienvenue', navigate: true);
            }
        }
        else{
            alert()->error('Oups Désolé', 'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
            $this->redirect('/bienvenue', navigate: true);
        }

        $dates = request('dates'); // ceci vient du click sur detail produit        
        if(empty($dates)){
            $this->date_debut = date('Y-m-d');
            $this->date_fin = date('Y-m-d');
        }
        else{
            $this->date_debut = $dates;
            $this->date_fin = $dates;
        }
        
        $requete = request('requete'); // ceci vient du click sur stock a date
        $date_debut = request('debut');
        if(empty($requete)){
            $this->query;
        }
        else{
            $this->query = $requete;
            $this->date_debut = $date_debut;
            $this->date_fin = date('Y-m-d');
        }

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
                $title = 'Mouvements Produits | WamsCo';
                $module = 'Gestion stock';
                $title_fils = 'Mouvements de stock';
                $lien = 'produit';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');      
                $dateJour = date('Y-m-d');

                $dateDebut = $this->date_debut;  
                $dateFin = $this->date_fin; 
                $start = Carbon::parse($dateDebut)->startOfDay(); //2016-09-29 00:00:00.000000
                $end = Carbon::parse($dateFin )->endOfDay();     // 2016-09-29 23:59:59.000000   

                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px'); 
                $mouvement = Mouvement::where('societe',auth()->user()->societe)->where('reference','like','%'.$this->query.'%')->where('entrepot','like','%'.$this->ParMag.'%')->whereBetween('created_at',[$start, $end])->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage);  
                $mouvCountAfficher = $mouvement->count();
                $mouvSommeAfficher = $mouvement->sum('quantite');

                $resultat = Mouvement :: where('societe',auth()->user()->societe)->get();  
                $TotalMouvement = $resultat->count();     
                $quantite_total = $resultat->sum('quantite');
                $valorisation_achat_total = $resultat->sum('valorisation_achat_total');
                $valeur_vente_total = $resultat->sum('valeur_vente_total');

                $derniereActivite = Mouvement::where('societe',auth()->user()->societe)->latest('updated_at')->first(); 

                $page = 'Mouvement'; // Pour evenement lie
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
                // $id_activite = 0;
                // $page = 'Mouvement'; // Pour evenement lie
                // LogActivity::addToLog('Liste mouvements',  $id_activite, $page);    
                $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();          
                $jourValid = $entite_mod[0]->validite_mod; 
                // ceci pour trouver le nombre de jour restant avant expiration
                $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24));
                return view('livewire.gestion-stock.entrepots.mouvements',compact('title_fils','module','lien','dateJour','mouvement','mouvCountAfficher','mouvSommeAfficher','TotalMouvement','quantite_total','derniereActivite','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
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
        return view('livewire.gestion-stock.mouvements');
    }
    public function trouver(){
        if($this->date_debut == '' or $this->date_fin == '' ){ 
            $this->dispatch('alert',                    
                title:'Désolé, veuillez renseigner les dates!',
                timer:3000,
                icon:'warning',
                toast:true,
                showConfirmButton: false,
                position:'top-end',
            );
        } 
        elseif($this->date_debut > $this->date_fin){
            $this->dispatch('alert',                    
                title:'Désolé, dates incorrectes!',
                timer:3000,
                icon:'error',
                toast:true,
                showConfirmButton: false,
                position:'top-end',
            );
        }       
    }      
}
