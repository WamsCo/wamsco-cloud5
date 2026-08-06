<?php

namespace App\Livewire\GestionStock;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
Use Carbon\Carbon;
use App\Helpers\LogActivity;
use App\Models\LogActivity as LogActivityModel;
use Livewire\WithPagination;
use Livewire\WithFileUploads; 
use App\Models\Role;
use App\Models\Entrepot;
use App\Models\Categorie;
use App\Models\Produit;
use App\Models\Tier;
use App\Models\Utilisateur;
use App\Models\DeviseTva;
use App\Models\Entite;
use App\Models\Stock;


class Stocks extends Component
{   
    protected $paginationTheme = 'bootstrap';    // ceci pour pagination avec les chiffres tres important
    use WithPagination;
    use WithFileUploads;

    public $query;
    public $parNature;
    public $parCat;    
    public $parPage = 20;
    public $orderField = 'nom_produit'; 
    public $orderDirection = 'ASC';

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
            $autoriser = $role[0]->consulter_produit;
            if($autoriser == 0){
                alert()->error('Oups Désolé', 'Vous n\'êtes pas autorisé à ouvrir cette page !!!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
                $this->redirect('/bienvenue', navigate: true);
            }
        }
        else{
            alert()->error('Oups Désolé', 'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
            $this->redirect('/bienvenue', navigate: true);
        } 
    }
    public function render(){
    
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();
        $jourValid = $entite_mod[0]->validite_mod;
        $mod_gestion_stock = $entite_mod[0]->mod_gestion_stock; 
        $soldeClient = $entite_mod[0]->solde;
        if($dateJour <= $jourValid){
            if($mod_gestion_stock == 1){
                $title = 'Stocks Produits | WamsCo';
                $module = 'Gestion stock';
                $title_fils = 'Produits (Stocks)';
                $lien = 'produit';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');      
                $dateJour = date('Y-m-d');
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px'); 
                        
                $listCategorie = Categorie::where('societe',auth()->user()->societe)->orderBy('nom_categorie','asc')->get();  
                $listFourni = Tier::where('societe',auth()->user()->societe)->where('type_tiers','Fournisseur')->where('etat', 1)->orderBy('nom','asc')->get(); 
                $listUser = Utilisateur::where('societe',auth()->user()->societe)->where('type_user','!=','Super-admin')->where('etat',1)->orderBy('name','asc')->get(); 
                $listedeviseTva = DeviseTva :: where('societe',auth()->user()->societe)->get();

                $totalStock = DB::table('stocks')
                                ->select('nom_produit','reference','code_barre','id_produit','type_produit','nature_produit','categorie',DB::raw('sum(quantite) as quantites, sum(valorisation_achat_total) as valorisationAchatTotal ,sum(valeur_vente_total) as valeurVentetotal, max(limite_stock_alerte) as limite_stock_alerte ,max(updated_at) as updated_at')) // Supposons que vous voulez la dernière date
                                ->where('societe',auth()->user()->societe)
                                ->where('nom_produit','like','%'.$this->query.'%')
                                ->where('nature_produit','like','%'.$this->parNature.'%')
                                ->where('categorie','like','%'.$this->parCat.'%')
                                ->orderBy($this->orderField, $this->orderDirection)
                                ->groupBy('nom_produit','reference','code_barre','id_produit','type_produit','nature_produit','categorie') // Si 'reference' et 'created_at' sont uniques par produit, vous pouvez les enlever du groupBy
                                ->paginate($this->parPage);
                $stockCount = $totalStock->count();
                $qteStockTotal = $totalStock->sum('quantites');
                $valAchatTotal = $totalStock->sum('valorisationAchatTotal');
                $valVenteTotal = $totalStock->sum('valeurVentetotal');
                

                $resultat = Stock :: where('societe',auth()->user()->societe)->get();
                $qteTotalProduit = $resultat->sum('quantite'); 
                $valorisation_achat_total = $resultat->sum('valorisation_achat_total'); 
                $valeur_vente_total = $resultat->sum('valeur_vente_total'); 

                $derniereActivite = Stock::where('societe',auth()->user()->societe)->latest('updated_at')->first(); 
                $NbreProdSansCodeBarre = Produit::where('societe', auth()->user()->societe)->where(function ($query) { $query->whereNull('code_barre')->orWhere('code_barre', ''); })->count();

                $page = 'Stock'; // pour evenement lies
                $log = LogActivityModel::where('user_societe',auth()->user()->societe)->where('page', $page)->limit(20)->orderBy('id','desc')->get();
                $logCount = $log->count();
                
                $deviseTva = DeviseTva :: where('societe',auth()->user()->societe)->limit(1)->orderBy('id','asc')->count(); 
                if($deviseTva == 0){
                    $this->devise = 'FCFA';
                }
                else{
                    $deviseTva = DeviseTva :: where('societe',auth()->user()->societe)->limit(1)->orderBy('id','asc')->get(); 
                    $this->devise = $deviseTva[0]->devise;
                }
                $id_activite = 0;
                $page = 'Stock'; // Pour evenement lie
                // LogActivity::addToLog('Consulter les stocks', $id_activite, $page);    
                $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();          
                $jourValid = $entite_mod[0]->validite_mod; 
                // ceci pour trouver le nombre de jour restant avant expiration
                $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24));
                return view('livewire.gestion-stock.produits.stocks',compact('title_fils','module','lien','dateJour','totalStock','stockCount','qteStockTotal','valAchatTotal','valVenteTotal',
                'qteTotalProduit','derniereActivite','valorisation_achat_total','valeur_vente_total','NbreProdSansCodeBarre','listCategorie','listFourni','listUser','listedeviseTva','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
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
