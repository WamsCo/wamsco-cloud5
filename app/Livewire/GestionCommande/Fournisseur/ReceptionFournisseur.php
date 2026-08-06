<?php

namespace App\Livewire\GestionCommande\Fournisseur;

use Livewire\Component;
use Livewire\Attributes\Validate; 
Use Carbon\Carbon;
use App\Helpers\LogActivity;
use App\Models\LogActivity as LogActivityModel;
use Livewire\WithPagination;
use App\Models\Role;
// use App\Models\Entrepot;
// use App\Models\Categorie;
use App\Models\Produit;
use App\Models\Tier;
use App\Models\Utilisateur;
use App\Models\DeviseTva;
use App\Models\Entite;
use App\Models\CompteBancaire;
use App\Models\factureFournisseurEntete;
use App\Models\factureFournisseurLigne;
use App\Models\CommandeFournisseurEntete;
use App\Models\Reglement;
use App\Models\ReceptionFournisseurEntete;
use App\Models\ReceptionFournisseurLigne;

class ReceptionFournisseur extends Component
{
    protected $paginationTheme = 'bootstrap';
    use WithPagination;

    public $id; 
    public $confirmer;
    public $query;
    public $parEtat;
    public $ParRef;  
    public $parPage = 20;
    public $date_debut; 
    public $date_fin;     

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
            $autoriser = $role[0]->consulter_reception;
            if($autoriser == 0){
                alert()->error('Oups Désolé', 'Vous n\'êtes pas autorisé à ouvrir cette page !!!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
                $this->redirect('/bienvenue', navigate: true);
            }
        }
        else{
            alert()->error('Oups Désolé', 'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
            $this->redirect('/bienvenue', navigate: true);
        }
        // $this->date_debut = date('Y-m-d', strtotime('-1 year')); // ceci pour affiche toutes les sessions en permanance sur 1 an par defaut
        $this->date_debut = date('Y-m-d', strtotime('-1 month'));  // ceci pour affiche toutes les sessions en permanance sur 1 mois par defaut
        $this->date_fin = date('Y-m-d');           
    }
    public function render()
    {
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();
        $jourValid = $entite_mod[0]->validite_mod;
        $mod_cmd = $entite_mod[0]->mod_cmd;
        $soldeClient = $entite_mod[0]->solde;
        if($dateJour <= $jourValid){
            if($mod_cmd == 1){
                $title = 'Liste réception fournisseur | WamsCo';
                $module = 'Gestion commande';
                $title_fils = 'Réception fournisseur';
                $lien = 'listing_fact_fourni';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');      
                $dateJour = date('Y-m-d');
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');

                $start = Carbon::parse($this->date_debut)->startOfDay(); //2016-09-29 00:00:00.000000
                $end = Carbon::parse($this->date_fin)->endOfDay();
                if(!empty($this->parEtat)){
                    $reception_fournisseur = ReceptionFournisseurEntete::where('societe',auth()->user()->societe)->where('nom_fournisseur','like','%'.$this->query.'%')->where('code_reception','like','%'.$this->ParRef.'%')->where('etat',$this->parEtat)->whereBetween('created_at',[$start, $end])->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage);
                }
                else{
                    $reception_fournisseur = ReceptionFournisseurEntete::where('societe',auth()->user()->societe)->where('nom_fournisseur','like','%'.$this->query.'%')->where('code_reception','like','%'.$this->ParRef.'%')->whereBetween('created_at',[$start, $end])->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage);
                }
                $receptionFournisseurCount = $reception_fournisseur->count();
                
                // pour les KPI
                $resultat = ReceptionFournisseurEntete::where('societe',auth()->user()->societe)->get();  
                $nbreTotalRecept = $resultat->count();    

                $derniereActivite = ReceptionFournisseurEntete::where('societe',auth()->user()->societe)->latest('updated_at')->first();

                $page = 'ReceptionFournisseur'; // pour evenement lies
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
                return view('livewire.gestion-commande.fournisseur.reception-fournisseur',compact('title_fils','module','lien','dateJour','reception_fournisseur','receptionFournisseurCount','nbreTotalRecept','derniereActivite','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
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
    public function detailFact(int $idx, $codeFact_cmd){
        // ceci au chargement de la page
        $test_facture = factureFournisseurEntete::where('societe',auth()->user()->societe)->where('id',$idx)->count();    
        if($test_facture > 0){
            $compte = factureFournisseurEntete::where('societe',auth()->user()->societe)->where('id',$idx)->first();               
            $this->ids = $compte->id;           
            $this->reference = $compte->code_facture; // reference facture
            $this->redirect('/nouveau_fact_fourni?id='.$idx.'&ref='.$this->reference.'&active=7&champ=2-1&choix=1', navigate: true);
        }  
        else{
            $this->dispatch('alert',                    
            title:'Désolé, cette facture n\'existe pas!',
                timer:5000,
                icon:'warning',
                toast:true,
                showConfirmButton: false,
                position:'top-end',
            );  
            flash ('Désolé, cette facture <strong>('.$codeFact_cmd.')</strong> n\'existe pas!')->error();
            $this->redirect('/listing_fact_fourni?active', navigate: true);
        }
    }
    public function detailCmd(int $id, $codeFact_cmd){
        // ceci au chargement de la page
        $test_facture = CommandeFournisseurEntete::where('societe',auth()->user()->societe)->where('id',$id)->count();    
        if($test_facture > 0){
            $compte = CommandeFournisseurEntete::where('societe',auth()->user()->societe)->where('id',$id)->first();               
            $this->ids = $compte->id;           
            $this->reference = $compte->code_commande; // reference commande
            $this->redirect('/nouveau_cmd_fourni?id='.$id.'&ref='.$this->reference.'&active=6&champ=2-1&choix=1', navigate: true);
        }  
        else{
            $this->dispatch('alert',                    
            title:'Désolé, cette commande n\'existe pas!',
                timer:5000,
                icon:'warning',
                toast:true,
                showConfirmButton: false,
                position:'top-end',
            );  
            flash ('Désolé, cette commande <strong>('.$codeFact_cmd.')</strong> n\'existe pas!')->error();
            $this->redirect('/listing_cmd_clt?active', navigate: true);
        }
    }
}
