<?php

namespace App\Livewire;

use Livewire\Component;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;
use App\Helpers\LogActivity;
use App\Models\LogActivity as LogActivityModel;
use App\Models\Entite;
use App\Models\Stock;
use App\Models\Entrepot;
use App\Models\Parametre;
use App\Models\Role;


class Parametres extends Component
{
    public $entrepot;
    public $entrepot_restau;    
    public $entrepot_client;
    public $entrepot_fournisseur;
    public $activer_fidelite = 0; 
    public $activer_ecran_cuisine = 0; 
    public $envoi_mail = 0;
    public $societe;
    public $created_at;
    public $updated_at;
    public $auteur; 

    public function mount(){ 
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->configurer;
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
    public function render()
    {
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('id',auth()->user()->societe_id)->get();
        $jourValid = $entite_mod[0]->validite_mod; 
        $mod_administration = $entite_mod[0]->mod_administration; 
        $soldeClient = $entite_mod[0]->solde;
        if($dateJour <= $jourValid){
            if($mod_administration == 1){
                $active = request('active');  
                $champ = request('champ');
                $title = 'Configuration | WamsCo';
                $module = 'Parametres';
                $title_fils = 'Configuration';
                $lien = 'config';
                $dateJour = date('Y-m-d');
                $entite_mod = Entite::where('id',auth()->user()->societe_id)->get(); 
                $jourValid = $entite_mod[0]->validite_mod; 
                // ceci pour trouver le nombre de jour restant avant expiration
                $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24));  

                $listEntrepot = Entrepot::where('societe_id',auth()->user()->societe_id)->where('active',1)->orderBy('nom','asc')->get(); 
                $stock = Stock::where('societe_id',auth()->user()->societe_id)->where('etat',1)->get();
                
                $page = 'Parametre'; // Pour evenement lie
                $log = LogActivityModel::where('societe_id',auth()->user()->societe_id)->where('page', $page)->limit(50)->orderBy('id','desc')->get();
                $logCount = $log->count();

                $test_vide = Parametre ::where('societe_id',auth()->user()->societe_id)->count();
                if($test_vide > 0){
                    
                    $config = Parametre::where('societe_id',auth()->user()->societe_id)->limit(1)->get();
                    $this->entrepot = $config[0]->id_entrepot_pv;
                    $this->entrepot_restau = $config[0]->id_entrepot_restau;                    
                    $this->entrepot_client = $config[0]->id_entrepot_fctclt;
                    $this->entrepot_fournisseur = $config[0]->id_entrepot_fctfourni;
                    $this->activer_fidelite = $config[0]->activer_fidelite;
                    $this->activer_ecran_cuisine = $config[0]->activer_ecran_cuisine;
                    $this->envoi_mail = $config[0]->envoi_mail;
                    $this->societe = $config[0]->societe;
                    $this->created_at = $config[0]->created_at;
                    $this->updated_at = $config[0]->updated_at;
                    $this->auteur = $config[0]->nom_user;                    
                }
                else{ 
                    
                    $this->entrepot = 0;
                    $this->entrepot_restau = 0;
                    $this->entrepot_client = 0;
                    $this->entrepot_fournisseur = 0;
                    $this->activer_fidelite = 0;
                    $this->activer_ecran_cuisine = 0;
                    $this->envoi_mail = 0;
                    $this->societe = auth()->user()->societe;
                    $this->created_at = auth()->user()->updated_at;
                    $this->updated_at = auth()->user()->updated_at;
                    $this->auteur = auth()->user()->name;
                }   
                            
                // Identifiez les produits qui se trouvent dans plus d'un magasin
                $ProduitDansPlusieursMag = DB::table('stocks')
                    ->select('id_entrepot')
                    ->groupBy('id_entrepot')
                    ->havingRaw('COUNT(DISTINCT id_produit) > 0')
                    ->where('societe_id',auth()->user()->societe_id)
                    ->pluck('id_entrepot'); // Récupère seulement les IDs des produits

                // Ensuite, joignez cette liste pour sommer les quantités
                $SommeParProduit = DB::table('stocks as stock')
                    ->select('stock.id_entrepot', DB::raw('SUM(stock.quantite) as total_quantite'))
                    ->whereIn('stock.id_entrepot', $ProduitDansPlusieursMag)
                    ->groupBy('stock.id_entrepot')
                    ->get();

                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');
                //ceci permet de creer le lien symboliqiue dans le cas d'appli avec setup
                // \File::link(storage_path('app/public'), public_path('storage'));             
                return view('livewire.parametre',compact('title_fils','module','lien','dateJour','listEntrepot','stock','SommeParProduit','log','logCount'))->layout('components.layouts.app',compact('title','module','title_fils','lien','active','champ','entite_mod','dateJour','soldeClient','nbjoursRestant'));  
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
            $title = 'Bienvenue  | WamsCo'; 
            $module = 'Home';
            $title_fils = 'Bienvenue';
            $lien = 'bienvenue';
            $active = request('active');
            $champ = request('champ');
            $choix = request('choix');  
            $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24));  
            alert()->error('Bonjour M/Mme '.auth()->user()->name.'! Votre accès a expiré le: ' .date('d-m-Y', strtotime($jourValid)). ', veuillez renouveller votre abonnement en cliquant sur un module svp !')->position('center')->autoClose(50000)->background('#fff')->width('520px')->padding('5px'); 
            return view('livewire.bienvenue',compact('dateJour','entite_mod'))->layout('layouts.app',compact('title','module','title_fils','lien','active','entite_mod','dateJour','soldeClient','nbjoursRestant')); 
        }
    }
    public function storePv(){
        $this->validate([            
            'entrepot'=>'required|numeric',     // id entrepot Pv
            'entrepot_restau'=>'required|numeric',     // id entrepot Pv restau
            'entrepot_client'=>'required|numeric',     // id entrepot client         
            'entrepot_fournisseur'=>'required|numeric',     // id entrepot fournisseur   
            'activer_fidelite'=>'required|numeric', 
            'activer_ecran_cuisine'=>'required|numeric',                     
            'envoi_mail'=>'required|numeric',                     
         ]);        
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->configurer;
            if($autoriser == 1){    
                   
                    $test_vide = Parametre ::where('societe_id',auth()->user()->societe_id)->count();
                    if($test_vide > 0){
                        Parametre::where('societe_id',auth()->user()->societe_id)->update(['id_entrepot_pv'=>$this->entrepot,'id_entrepot_restau'=>$this->entrepot_restau,'id_entrepot_fctclt'=>$this->entrepot_client,'id_entrepot_fctfourni'=>$this->entrepot_fournisseur,
                        'activer_fidelite'=>$this->activer_fidelite,'activer_ecran_cuisine'=>$this->activer_ecran_cuisine,'envoi_mail'=>$this->envoi_mail,
                        'nom_user_modif'=>auth()->user()->name,'user_id_modif'=>auth()->user()->id]);  

                        Entite::where('id',auth()->user()->societe_id)->update(['activer_fidelite'=>$this->activer_fidelite]);                   
                    } 
                    else{
                        Parametre::create(['id_entrepot_pv'=>$this->entrepot,'id_entrepot_restau'=>$this->entrepot_restau,'id_entrepot_fctclt'=>$this->entrepot_client,'id_entrepot_fctfourni'=>$this->entrepot_fournisseur,
                        'activer_fidelite'=>$this->activer_fidelite,'activer_ecran_cuisine'=>$this->activer_ecran_cuisine,'envoi_mail'=>$this->envoi_mail,
                        'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);  
                        
                        Entite::where('id',auth()->user()->societe_id)->update(['activer_fidelite'=>$this->activer_fidelite]);
                    }                   
                   
                    $id_activite = 0; 
                    $page = 'Parametre';
                    LogActivity::addToLog('Paramètres modifiés', $id_activite, $page);                   
                    $this->dispatch('alert',                    
                        title:'Paramètres enregistrés!',
                        timer:5000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );  
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
