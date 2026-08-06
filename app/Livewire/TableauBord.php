<?php

namespace App\Livewire;

use Livewire\Component;
use RealRashid\SweetAlert\Facades\Alert;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Helpers\LogActivity;
use App\Models\Entite;
use App\Models\Role;
use App\Models\DeviseTva;
use App\Models\factureClientEntete;
use App\Models\factureClientLigne;
use App\Models\Produit;
use App\Models\Stock;
use App\Models\Utilisateur;
use App\Models\Tier;
use App\Models\Categorie;
use App\Models\EcritureBancaire;
use App\Models\CompteBancaire;
use App\Models\PaiementDiver;
use App\Models\CommandeClientEntete;
use App\Models\factureFournisseurEntete;

class TableauBord extends Component
{
    public $date_debut; 
    public $date_fin; 
    public $devise;
    
    public $cmd_moyen;

    // Chart vente
    public $salesData;
    public $months = [];
    public $sales = [];
    public $ventes = [];

    // Chart
    public $usersData;
    public $mois;

    // Récupérer le mois courant
    public $productNames = [];
    public $productQty = [];
    public $productCA = [];
    public $currentMonthName;
    
    public $month;
    public $year;    
       
    public function mount()
    {
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->tablobord_pv;
            if($autoriser == 0){
                alert()->error('Oups Désolé', 'Vous n\'êtes pas autorisé à ouvrir cette page !!!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
                $this->redirect('/bienvenue', navigate: true);
            }
        }
        else{
            alert()->error('Oups Désolé', 'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
            $this->redirect('/bienvenue', navigate: true);
        } 
        $this->date_debut = date('Y-m-d');
        $this->date_fin = date('Y-m-d'); 

        Carbon::setLocale('fr');        
        $this->GrapheFactureClient();
        $this->GrapheFacturefournisseur();
        $this->GrapheMeilleuresVentesProduits();
        $this->month = (int) now()->month;
        $this->year = (int) now()->year;
            
    }    
    public function GrapheFactureClient(){
       // Liste des mois Janvier -> Décembre
        $months = collect(range(1, 12))->map(function ($month) {
            return Carbon::create()->month($month)->translatedFormat('F');
        });
        // Tableau des noms des mois
        $this->months = $months->toArray();
        // Données BD
        $salesData = factureClientEntete::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(montant_ttc) as total')
            )
            ->whereYear('created_at', now()->year)
            ->where('etat','!=','Brouillon')
            ->where('societe',auth()->user()->societe)
            ->groupBy('month')
            ->pluck('total', 'month');

        // Construire les données Janvier -> Décembre
        $this->sales = collect(range(1, 12))
            ->map(function ($month) use ($salesData) {
                return $salesData[$month] ?? 0;
            })->toArray();
    }
    public function GrapheFacturefournisseur(){
        //  Liste des mois Janvier -> Décembre
        $mois = collect(range(1, 12))->map(function ($mois) {
            return Carbon::create()->month($mois)->translatedFormat('F');
        });
        // Tableau des noms des mois
        $this->mois = $mois->toArray();
        // Données BD
        $salesDatas = factureFournisseurEntete::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(montant_ttc) as total')
            )
            ->whereYear('created_at', now()->year)
            ->where('etat','!=','Brouillon')
            ->where('societe',auth()->user()->societe)
            ->groupBy('month')
            ->pluck('total', 'month');

        // Construire les données Janvier -> Décembre
        $this->ventes = collect(range(1, 12))
            ->map(function ($mois) use ($salesDatas) {
                return $salesDatas[$mois] ?? 0;
            })->toArray();
    } 
    public function GrapheMeilleuresVentesProduits(){

        $start = Carbon::create($this->year, $this->month, 1)->startOfMonth();
        $end   = Carbon::create($this->year, $this->month, 1)->endOfMonth();        
        // 👉 Mois en cours (ex: Mai 2026)
        $this->currentMonthName = now()->translatedFormat('F Y');

        $salesData = factureClientLigne::select(
                'facture_client_lignes.id_produit','produits.nom_produit as produit_nom',
                DB::raw('SUM(facture_client_lignes.quantite) as total_qty'),
                DB::raw('SUM(facture_client_lignes.quantite * facture_client_lignes.prix_vente) as total_ca')
            )
            ->join('facture_client_entetes', 'facture_client_entetes.id', '=', 'facture_client_lignes.id_facture_client_entete')
            ->join('produits', 'produits.id', '=', 'facture_client_lignes.id_produit')
            ->whereBetween('facture_client_lignes.created_at', [$start, $end])
            ->where('facture_client_lignes.etat', '!=', 'Brouillon')
            ->where('facture_client_lignes.societe', auth()->user()->societe)
            ->groupBy('facture_client_lignes.id_produit', 'produits.nom_produit')
            ->OrderBy('total_qty','Desc')
            ->limit(20)
            ->get();

        // Labels
        $this->productNames = $salesData->map(fn($i) => $i->produit_nom)->toArray();
        // Quantité
        $this->productQty = $salesData->map(fn($i) => (int) $i->total_qty)->toArray();
        // CA
        $this->productCA = $salesData->map(fn($i) => (float) $i->total_ca)->toArray();
    }        
    public function render()
    {
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();
        $jourValid = $entite_mod[0]->validite_mod; 
        $soldeClient = $entite_mod[0]->solde;
        if($dateJour <= $jourValid){
            $active = request('active');  
            $champ = request('champ');
            $choix = request('choix'); 
            $title = 'Tableau de Bord | WamsCo';
            $module = 'Home';
            $title_fils = 'Tableau de Bord';
            $lien = 'bienvenue';
            $dateJour = date('Y-m-d'); 
            
             // Facture et produit
             $factClient_entete = factureClientEntete::where('societe',auth()->user()->societe)->orderBy('id','DESC')->limit(5)->get();
             $factCltEnteTTC_partiel = $factClient_entete->sum('montant_ttc'); 
             $factCltEnteTTC_all = factureClientEntete::where('societe',auth()->user()->societe)->sum('montant_ttc');
             $produit = Produit::where('societe',auth()->user()->societe)->orderBy('id','DESC')->limit(5)->get();
             $entit = Entite::where('enseigne',auth()->user()->societe)->get(); 

             $start = Carbon::parse($this->date_debut)->startOfDay(); //2016-09-29 00:00:00.000000
             $end = Carbon::parse($this->date_fin)->endOfDay();     // 2016-09-29 23:59:59.000000
            
            // 1er bloc            
            $vente = factureClientEntete :: where('societe',auth()->user()->societe)->where('etat','!=','Brouillon')->whereBetween('created_at',[$start, $end])->sum('montant_ttc');
            $marge = factureClientEntete :: where('societe',auth()->user()->societe)->where('etat','!=','Brouillon')->whereBetween('created_at',[$start, $end])->sum('marge');
            $count_qte = factureClientLigne:: where('societe',auth()->user()->societe)->where('etat','!=','Brouillon')->whereBetween('created_at',[$start, $end])->sum('quantite');
            // ceci  evite l'erreur division par zero(0)
            if($count_qte == 0){
                $this->cmd_moyen = 0;
            }
            else{
                $this->cmd_moyen = ($vente / $count_qte);
            }
            $cmd_moyen_bar = $this->cmd_moyen/1000; //ceci pour la bare de chargement cmd_moyen 

            $remise = factureClientEntete :: where('societe',auth()->user()->societe)->where('etat','!=','Brouillon')->whereBetween('created_at',[$start, $end])->sum('montant_remise');
            $remise_bar = $remise/1000; //ceci pour la bare de chargement taxe

            // Aperçu de l'activité                     
            $inventaire = Stock :: where('societe',auth()->user()->societe)->sum('valorisation_achat_total');
            $utilisateur = Utilisateur:: where('societe',auth()->user()->societe)->count();
            $produitCount = Produit :: where('societe',auth()->user()->societe)->count(); 
            $produit_videMag = Stock :: where('societe',auth()->user()->societe)->where('quantite','0')->count();
            $fourniCount = Tier:: where('societe',auth()->user()->societe)->where('type_tiers','Fournisseur')->count();
            $ResteAPayer = factureClientEntete :: where('societe',auth()->user()->societe)->where('etat','!=','Brouillon')->sum('reste_a_percevoir');  
            $client = Tier :: where('societe',auth()->user()->societe)->where('type_tiers','Client')->count();
            $categorieCount = Categorie:: where('societe',auth()->user()->societe)->count();

            $ecritureCount = EcritureBancaire :: where('societe',auth()->user()->societe)->count(); 
            $banqueCount = CompteBancaire :: where('societe',auth()->user()->societe)->count(); 
            $paiementDivCount = PaiementDiver :: where('societe',auth()->user()->societe)->count(); 
            $cmdClientCount = CommandeClientEntete::where('societe',auth()->user()->societe)->where('etat','Validée')->count();
            $entite_count = Entite::where('societe_mere',auth()->user()->societe)->where('active',1)->count(); 

            $deviseTva = DeviseTva :: where('societe',auth()->user()->societe)->limit(1)->orderBy('id','asc')->count(); 
            if($deviseTva == 0){
                $this->devise = 'FCFA';
            }
            else{
                $deviseTva = DeviseTva :: where('societe',auth()->user()->societe)->limit(1)->orderBy('id','asc')->get(); 
                $this->devise = $deviseTva[0]->devise;
            }
            $id_activite = 0;
            $page = 'Tableau de Bord';
            // LogActivity::addToLog('Tableau de bord', $id_activite, $page);    
            $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get(); 
            $jourValid = $entite_mod[0]->validite_mod; 
            // ceci pour trouver le nombre de jour restant avant expiration
            $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24));  

            //ceci permet de creer le lien symboliqiue dans le cas d'appli avec setup
            // \File::link(storage_path('app/public'), public_path('storage'));             
            return view('livewire.tableau-bord',compact('dateJour','title_fils','entite_mod','factClient_entete','factCltEnteTTC_partiel','factCltEnteTTC_all','produit','entit','vente','marge','cmd_moyen_bar','remise','remise_bar',
            'inventaire','utilisateur','produitCount','produit_videMag','fourniCount','ResteAPayer','client','categorieCount','ecritureCount','banqueCount','paiementDivCount','cmdClientCount','entite_count'))->layout('components.layouts.app',compact('title','module','title_fils','lien','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));  
        }
        else{
            $title = 'Bienvenue  | WamsCo'; 
            $module = 'Home';
            $title_fils = 'Bienvenue';
            $lien = 'bienvenue';
            $active = request('active');
            $champ = request('champ');
            $choix = request('choix');  
            $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24));  
            alert()->error('Bonjour M/Mme '.auth()->user()->name.'! Votre accès a expiré le: ' .date('d-m-Y', strtotime($jourValid)). ', veuillez renouveller votre abonnement en cliquant sur un module svp !')->position('center')->autoClose(50000)->background('#fff')->width('520px')->padding('5px'); 
            return view('livewire.bienvenue',compact('dateJour','entite_mod'))->layout('components.layouts.app',compact('title','module','title_fils','lien','active','entite_mod','dateJour','soldeClient','nbjoursRestant')); 
        }
    }
}
