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


class FactureClient extends Component
{
    protected $paginationTheme = 'bootstrap';
    use WithPagination;

    public $id; 
    public $confirmer;
    public $query;
    public $parEtat; 
    public $parUser;   
    public $parRef;     
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
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
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

        $nom_client = request('nom_client'); // ceci vient du click sur detail tier
        $date_debut = request('date_debut'); // ceci vient du click sur detail tier
        $date_fin = request('date_fin'); // ceci vient du click sur detail tier
        if(empty($nom_client) && empty($date_debut) && empty($date_fin)){
            $this->date_debut = date('Y-m-d', strtotime('-2 month'));  // ceci pour affiche toutes les sessions en permanance sur 1 mois par defaut
            $this->date_fin = date('Y-m-d'); 
        }
        else{
            $this->date_debut = $date_debut;
            $this->date_fin = $date_fin;
            $this->query = $nom_client;
        }
        // $this->date_debut = date('Y-m-d', strtotime('-1 year')); // ceci pour affiche toutes les sessions en permanance sur 1 an par defaut
        // $this->date_debut = date('Y-m-d', strtotime('-1 month'));  // ceci pour affiche toutes les sessions en permanance sur 1 mois par defaut
        // $this->date_fin = date('Y-m-d'); 
    }
    public function render()
    {
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();
        $jourValid = $entite_mod[0]->validite_mod;
        $mod_facturation = $entite_mod[0]->mod_facturation;
        $soldeClient = $entite_mod[0]->solde; 
        if($dateJour <= $jourValid){
            if($mod_facturation == 1){
                $title = 'Facturation client | WamsCo';
                $module = 'Gestion facturation';
                $title_fils = 'Facturation client';
                $lien = 'listing_fact_clt';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');      
                $dateJour = date('Y-m-d');
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');

                $start = Carbon::parse($this->date_debut)->startOfDay(); //2016-09-29 00:00:00.000000
                $end = Carbon::parse($this->date_fin)->endOfDay();     // 2016-09-29 23:59:59.000000

                if(empty($this->parEtat) && empty($this->parUser)){
                    $fact_client = factureClientEntete::where('societe',auth()->user()->societe)->where('nom_client','like','%'.$this->query.'%')->where('code_facture','like','%'.$this->parRef.'%')->whereBetween('created_at',[$start, $end])->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage);
                }
                elseif(!empty($this->parEtat) && empty($this->parUser)){
                    $fact_client = factureClientEntete::where('societe',auth()->user()->societe)->where('nom_client','like','%'.$this->query.'%')->where('code_facture','like','%'.$this->parRef.'%')->where('etat',$this->parEtat)->whereBetween('created_at',[$start, $end])->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage);
                }
                elseif(empty($this->parEtat) && !empty($this->parUser)){
                    $fact_client = factureClientEntete::where('societe',auth()->user()->societe)->where('nom_client','like','%'.$this->query.'%')->where('code_facture','like','%'.$this->parRef.'%')->where('user_id',$this->parUser)->whereBetween('created_at',[$start, $end])->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage);
                }
                else{ 
                    $fact_client = factureClientEntete::where('societe',auth()->user()->societe)->where('nom_client','like','%'.$this->query.'%')->where('code_facture','like','%'.$this->parRef.'%')->where('etat',$this->parEtat)->where('user_id',$this->parUser)->whereBetween('created_at',[$start, $end])->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage);
                }
                $factClientCount = $fact_client->count();
                $montantTTC = $fact_client->sum('montant_ttc');
                $montantTrecu = $fact_client->sum('montant_recu');
                $montantTreste_Percevoir = $fact_client->sum('reste_a_percevoir');
                $montantTRemise = $fact_client->sum('montant_remise');
                $montantTmarge = $fact_client->sum('marge');

                // pour les KPI
                $resultat = factureClientEntete::where('societe',auth()->user()->societe)->get();  
                $nbreTotalFact = $resultat->count(); 
                $montantTTC_all = $resultat->sum('montant_ttc');
                $montantTrecu_all = $resultat->sum('montant_recu');
                $montantTmarge_All = $resultat->sum('marge'); 
                $montantCreance_all = $resultat->sum('reste_a_percevoir');     

                $derniereActivite = factureClientEntete::where('societe',auth()->user()->societe)->latest('updated_at')->first();
                
                $page = 'factureClient'; // pour evenement lies
                $log = LogActivityModel::where('user_societe',auth()->user()->societe)->where('page', $page)->limit(50)->orderBy('id','desc')->get();
                $logCount = $log->count();
                
                $utilisat = Utilisateur::where('societe',auth()->user()->societe)->orderBy('name','asc')->get();   
                
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
                return view('livewire.gestion-facturation.client.facture-client',compact('title_fils','module','lien','dateJour','fact_client','factClientCount','montantTTC','montantTrecu','montantTreste_Percevoir','montantTmarge',
                'montantTRemise','nbreTotalFact','montantTTC_all','montantTrecu_all','montantTmarge_All','montantCreance_all','derniereActivite','log','logCount','utilisat'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
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
              
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_facture;
            if($autoriser == 1){   
           
                // creation et enregistrement auto Banque ou caisse
                // $reference = date('YmdHis');
                $client = '';  
                $id_client = 0;                     
                $date_facturation = date('Y-m-d');
                $date_echeance = date('Y-m-d');
                $mode_reglement = 'Espèce';
                $compte_bancaire = '';
                $note = '';
                $etat = 'Brouillon';
                $montant_ht = 0;
                $montant_remise = 0;
                $montant_tva = 0;
                $montant_precompte = 0;
                $montant_ttc = 0;
                $marge = 0;
                $montant_recu = 0;
                $reste_a_percevoir = 0;
                

                $dates = date('dmy/His');
                $length = 2;
                $token = bin2hex(random_bytes($length));
                $token_ok = 'FACT/'.$dates;
                // $token_ok = 'FACT/'.$dates.'/'.$token;
                factureClientEntete :: create(['code_facture'=>$token_ok,'nom_client'=>$client,'id_client'=>$id_client,'date_facturation'=>$date_facturation,'date_echeance'=>$date_echeance,
                            'montant_ht'=>$montant_ht,'montant_remise'=>$montant_remise,'montant_tva'=>$montant_tva,'montant_precompte'=>$montant_precompte,'montant_ttc'=>$montant_ttc,'marge'=>$marge,
                            'montant_recu'=>$montant_recu,'reste_a_percevoir'=>$reste_a_percevoir,'mode_reglement'=>$mode_reglement,'compte_bancaire'=>$compte_bancaire,'note'=>$note,'etat'=>$etat,'societe'=>auth()->user()->societe,
                            'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                        // ceci recupere le dernier enregistrement cree a l'instant
                $dernier_id = factureClientEntete::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id; 

                $id_activite = $dernier_id;
                $page = 'factureClient';
                LogActivity::addToLog('Facture ('.$token_ok.') client créée', $id_activite, $page); 
                $this->dispatch('alert',                    
                    title:'Facture client enregistrée!',
                    timer:3000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                );             
                $this->redirect('/nouveau_fact_clt?id='.$dernier_id.'&ref='.$token_ok.'&active=7&champ=1-1&choix=1', navigate: true);               
            }
            else{                 
                $this->dispatch('alert',                    
                    title:'Vous n\'êtes pas autorisé à effectuer cette opération!',
                    timer:3000,
                    icon:'error',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                ); 
            }
        }        
        else{             
            $this->dispatch('alert',                    
                title:'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!',
                timer:3000,
                icon:'error',
                toast:true,
                showConfirmButton: true,
                position:'center',
            );
        }   
    } 
    public function confirmerDelete($id){
        $this->confirmer = $id;        
    } 
    public function supprimer(int $id, string $code_fact){
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->supprimer_facture;
            if($autoriser == 1){   
                if($id){
                    
                    $test_regle = Reglement::where('societe',auth()->user()->societe)->where('code_facture',$code_fact)->count();
                    if($test_regle == 0){

                         $page = 'factureClient';
                         factureClientEntete::where('id',$id)->delete();
                         factureClientLigne::where('id_facture_client_entete',$id)->delete();
                         LogActivityModel::where('id_activite',$id)->where('page',$page)->delete();

                         $id_activite = $id;
                         LogActivity::addToLog('Facture client supprimée définitivement', $id_activite, $page);
                        $this->dispatch('alert',                    
                            title:'Suppression effectuée!',
                            timer:3000,
                            icon:'success',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        ); 
                    }
                    else{
                        $this->dispatch('alert',                    
                            title:'Désolé, vous ne pouvez pas supprimer cette facture. <br> Veuillez détacher au préalable les paiements liés!',
                            timer:100000,
                            icon:'error',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        ); 
                    }                                             
                }  
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
