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
use App\Models\Mouvement;

class StockDate extends Component
{
    protected $paginationTheme = 'bootstrap';    // ceci pour pagination avec les chiffres tres important
    use WithPagination;

    public $date_debut;

    public $parProduit; // id
    public $parEntrepot; // id

    public $query;
    public $parPage = 20;

    public $orderField = 'created_at'; 
    public $orderDirection = 'DESC';
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
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
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
        // $this->date_debut = date('Y-m-d H:i');        
    }
    public function render()
    {
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('id',auth()->user()->societe_id)->get();
        $jourValid = $entite_mod[0]->validite_mod;
        $mod_gestion_stock = $entite_mod[0]->mod_gestion_stock;
        $soldeClient = $entite_mod[0]->solde; 
        if($dateJour <= $jourValid){
            if($mod_gestion_stock == 1){
                $title = 'Stock à date | WamsCo';
                $module = 'Gestion stock';
                $title_fils = 'Stock à date';
                $lien = 'produit';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');      
                $dateJour = date('Y-m-d');
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px'); 
                
                // $start = Carbon::parse($this->date_debut)->startOfDay(); //2016-09-29 00:00:00.000000
                // $end = Carbon::parse($this->date_fin)->endOfDay();     // 2016-09-29 23:59:59.000000
                $listProduit = Produit::where('societe_id',auth()->user()->societe_id)->where('etat',1)->orderBy('nom_produit','asc')->get();
                $listEntrepot = Entrepot::where('societe_id',auth()->user()->societe_id)->where('active',1)->orderBy('nom','asc')->get();
                
                // $mouvement = Mouvement::where('societe_id',auth()->user()->societe_id)->where('reference','like','%'.$this->query.'%')->where('created_at','<=',$start)->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage);  
                if(empty($this->date_debut)){                
                    $date_debut = 0;
                    $mouvement = 'null';
                    $mouvCountAfficher = 0;
                    
                }
                else{  

                    if(empty($this->parProduit) && empty($this->parEntrepot)){
                        $mouvement = DB::table('mouvements')
                        ->select(
                            'mouvements.id_produit',
                            'mouvements.nom_produit',
                            'mouvements.reference',
                            // Stock historique à la date
                            DB::raw('SUM(mouvements.quantite) as stock_a_date'),
                            // 🔥 SOMME du stock actuel
                            DB::raw('(
                                SELECT COALESCE(SUM(stocks.quantite), 0)
                                FROM stocks
                                WHERE stocks.id_produit = mouvements.id_produit
                                AND stocks.societe_id = mouvements.societe_id
                            ) as stock_actuel'),
                            DB::raw('COUNT(mouvements.id_produit) as nombreFois'),
                            DB::raw('MAX(mouvements.created_at) as created_at')
                        )
                        ->where('mouvements.societe_id', auth()->user()->societe_id)
                        ->where('mouvements.created_at', '<=', $this->date_debut)
                        ->groupBy(
                            'mouvements.id_produit',
                            'mouvements.nom_produit',
                            'mouvements.reference',
                            'mouvements.societe_id'
                        )
                        ->orderBy($this->orderField, $this->orderDirection)
                        ->paginate($this->parPage);
                        $mouvCountAfficher = $mouvement->count();
                    }
                    elseif(!empty($this->parProduit) && empty($this->parEntrepot)){

                        $mouvement = DB::table('mouvements')
                        ->select(
                            'mouvements.id_produit',
                            'mouvements.nom_produit',
                            'mouvements.reference',
                            // Stock historique à la date
                            DB::raw('SUM(mouvements.quantite) as stock_a_date'),
                            // 🔥 SOMME du stock actuel
                            DB::raw('(
                                SELECT COALESCE(SUM(stocks.quantite), 0)
                                FROM stocks
                                WHERE stocks.id_produit = mouvements.id_produit
                                AND stocks.societe_id = mouvements.societe_id
                            ) as stock_actuel'),
                            DB::raw('COUNT(mouvements.id_produit) as nombreFois'),
                            DB::raw('MAX(mouvements.created_at) as created_at')
                        )
                        ->where('mouvements.societe_id', auth()->user()->societe_id)
                        ->where('mouvements.id_produit', $this->parProduit)
                        ->where('mouvements.created_at', '<=', $this->date_debut)
                        ->groupBy(
                            'mouvements.id_produit',
                            'mouvements.nom_produit',
                            'mouvements.reference',
                            'mouvements.societe_id'
                        )
                        ->orderBy($this->orderField, $this->orderDirection)
                        ->paginate($this->parPage);
                        $mouvCountAfficher = $mouvement->count();
                    }
                    elseif(empty($this->parProduit) && empty(!$this->parEntrepot)){
                        
                        $mouvement = DB::table('mouvements')
                        ->select(
                            'mouvements.id_produit',
                            'mouvements.nom_produit',
                            'mouvements.reference',
                            // Stock historique à la date
                            DB::raw('SUM(mouvements.quantite) as stock_a_date'),
                            // 🔥 SOMME du stock actuel
                            DB::raw('(
                                SELECT COALESCE(SUM(stocks.quantite), 0)
                                FROM stocks
                                WHERE stocks.id_produit = mouvements.id_produit
                                AND stocks.societe_id = mouvements.societe_id
                            ) as stock_actuel'),
                            DB::raw('COUNT(mouvements.id_produit) as nombreFois'),
                            DB::raw('MAX(mouvements.created_at) as created_at')
                        )
                        ->where('mouvements.societe_id', auth()->user()->societe_id)
                        ->where('mouvements.id_entrepot', $this->parEntrepot)
                        ->where('mouvements.created_at', '<=', $this->date_debut)
                        ->groupBy(
                            'mouvements.id_produit',
                            'mouvements.nom_produit',
                            'mouvements.reference',
                            'mouvements.societe_id'
                        )
                        ->orderBy($this->orderField, $this->orderDirection)
                        ->paginate($this->parPage);
                        $mouvCountAfficher = $mouvement->count();
                    }
                    else{
                        
                        $mouvement = DB::table('mouvements')
                        ->select(
                            'mouvements.id_produit',
                            'mouvements.nom_produit',
                            'mouvements.reference',
                            // Stock historique à la date
                            DB::raw('SUM(mouvements.quantite) as stock_a_date'),
                            // 🔥 SOMME du stock actuel
                            DB::raw('(
                                SELECT COALESCE(SUM(stocks.quantite), 0)
                                FROM stocks
                                WHERE stocks.id_produit = mouvements.id_produit
                                AND stocks.societe_id = mouvements.societe_id
                            ) as stock_actuel'),
                            DB::raw('COUNT(mouvements.id_produit) as nombreFois'),
                            DB::raw('MAX(mouvements.created_at) as created_at')
                        )
                        ->where('mouvements.societe_id', auth()->user()->societe_id)
                        ->where('mouvements.id_produit', $this->parProduit)
                        ->where('mouvements.id_entrepot', $this->parEntrepot)
                        ->where('mouvements.created_at', '<=', $this->date_debut)
                        ->groupBy(
                            'mouvements.id_produit',
                            'mouvements.nom_produit',
                            'mouvements.reference',
                            'mouvements.societe_id'
                        )
                        ->orderBy($this->orderField, $this->orderDirection)
                        ->paginate($this->parPage);
                        $mouvCountAfficher = $mouvement->count();
                    }
                }           

                $page = 'Stock'; // pour evenement lies
                $log = LogActivityModel::where('societe_id',auth()->user()->societe_id)->where('page', $page)->limit(20)->orderBy('id','desc')->get();
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
                return view('livewire.gestion-stock.produits.stock-date',compact('title_fils','module','lien','dateJour','listProduit','listEntrepot','mouvement','mouvCountAfficher','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
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
        public function trouver(){             
        if($this->date_debut == ''){ 
            $this->dispatch('alert',                    
                title:'Désolé, veuillez renseigner la date et l\'heure !',
                timer:5000,
                icon:'warning',
                toast:true,
                showConfirmButton: false,
                position:'top-end',
            );
        } 
        // elseif($this->date_debut > date('Y-m-d')){
        //     $this->dispatch('alert',                    
        //         title:'Désolé, dates incorrectes!',
        //         timer:3000,
        //         icon:'error',
        //         toast:true,
        //         showConfirmButton: false,
        //         position:'top-end',
        //     );
        //     $this->redirect('/stock_a_date?active=4&champ=1-1&choix=4', navigate: true); 
        // }       
    } 
}
