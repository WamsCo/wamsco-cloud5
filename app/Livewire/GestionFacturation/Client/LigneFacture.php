<?php

namespace App\Livewire\GestionFacturation\Client;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
Use Carbon\Carbon;
use Livewire\Attributes\Validate; 
use App\Helpers\LogActivity;
use App\Models\LogActivity as LogActivityModel;
use Livewire\WithPagination;
use App\Models\Role;
use App\Models\Produit;
use App\Models\Tier;
use App\Models\Utilisateur;
use App\Models\DeviseTva;
use App\Models\Entite;
use App\Models\CompteBancaire;
use App\Models\factureClientEntete;
use App\Models\factureClientLigne;
use App\Models\Reglement;

class LigneFacture extends Component
{
    protected $paginationTheme = 'bootstrap';
    use WithPagination;

    public $id; 
    public $confirmer;
    public $query;
    public $parFact;
    public $parNomProduit;
    public $parPage = 20; 
    public $date_debut; 
    public $date_fin;    

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
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $this->autoriser = $role[0]->voir_marge;
            $autoriser = $role[0]->consulter_facture;
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
        $this->date_debut = date('Y-m-d', strtotime('-2 month'));  // ceci pour affiche toutes les sessions en permanance sur 1 mois par defaut
        $this->date_fin = date('Y-m-d');       
    }
    public function render()
    {
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('id',auth()->user()->societe_id)->get();
        $jourValid = $entite_mod[0]->validite_mod;
        $mod_facturation = $entite_mod[0]->mod_facturation;
        $soldeClient = $entite_mod[0]->solde; 
        if($dateJour <= $jourValid){
            if($mod_facturation == 1){
                $title = 'Ligne de facture client | WamsCo';
                $module = 'Gestion facturation';
                $title_fils = 'Ligne de facture client';
                $lien = 'ligne_facture';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');      
                $dateJour = date('Y-m-d');
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px'); 
                
                $start = Carbon::parse($this->date_debut)->startOfDay(); //2016-09-29 00:00:00.000000
                $end = Carbon::parse($this->date_fin)->endOfDay();  

                // $fact_client = DB::table('facture_client_lignes')->select('*', DB::Raw('Sum(prix_achat) as prix_achatTotal, prix_achat as prixAchat, Sum(quantite) as quantiteTotal, Sum(montant_ttc) as montantTotal, Sum(marge) as margeTotal, Sum(montant_remise) as montant_remiseTotal, Sum(montant_tva) as montant_tvaTotal, Sum(montant_precompte) as montant_precompteTotal'))->where('societe_id',auth()->user()->societe_id)->where('statut_paiement','like','%'.$this->statut_paiement.'%')->whereBetween('created_at',[$start, $end])->where('num_facture','like','%'.$this->query.'%')->where('user_id','like','%'.$this->parNom.'%')->where('categorie','like','%'.$this->parCat.'%')->where('client','like','%'.$this->parClt.'%')->where('designation','like','%'.$this->parProd.'%')->where('offrir','like','%'.$this->parOffre.'%')->groupBy('designation')->orderBy($this->orderField2, $this->orderDirection2)->get();
                $produit = Produit::where('societe_id',auth()->user()->societe_id)->orderBy('nom_produit','ASC')->get();
                $fact_client = factureClientLigne::where('societe_id',auth()->user()->societe_id)->where('code_facture','like','%'.$this->parFact.'%')->where('nom_client','like','%'.$this->query.'%')->where('id_produit','like','%'.$this->parNomProduit.'%')->whereBetween('created_at',[$start, $end])->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage);
                $factClientCount = $fact_client->count();
                $QuantiteTotal = $fact_client->sum('quantite');
                $montantTTC = $fact_client->sum('montant_ttc');
                $montantTmarge = $fact_client->sum('marge');  
                $montantTRemise = $fact_client->sum('montant_remise');
                
                // pour les KPI
                $resultat = factureClientLigne::where('societe_id',auth()->user()->societe_id)->get();  
                $nbreTotalFact = $resultat->count(); 
                $quantite_all = $resultat->sum('quantite');     
                $montantTTC_all = $resultat->sum('montant_ttc');
                $montantTremise_all = $resultat->sum('montant_remise');
                $montantTmarge_All = $resultat->sum('marge'); 
                $montantCreance_all = $resultat->sum('reste_a_percevoir');     

                $derniereActivite = factureClientLigne::where('societe_id',auth()->user()->societe_id)->latest('updated_at')->first();
                
                $page = 'factureClient'; // pour evenement lies
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
                return view('livewire.gestion-facturation.client.ligne-facture',compact('title_fils','module','lien','dateJour','produit','fact_client','factClientCount','QuantiteTotal','montantTTC','montantTmarge',
                'montantTRemise','nbreTotalFact','quantite_all','montantTTC_all','montantTremise_all','montantTmarge_All','montantCreance_all','derniereActivite','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
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
