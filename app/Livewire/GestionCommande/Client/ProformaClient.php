<?php

namespace App\Livewire\GestionCommande\Client;

use Livewire\Component;
use Livewire\Attributes\Validate; 
Use Carbon\Carbon;
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
use App\Models\ProformaClientEntete;
use App\Models\ProformaClientLigne;
use App\Models\CommandeClientEntete;

class ProformaClient extends Component
{
    protected $paginationTheme = 'bootstrap';
    use WithPagination;

    public $id; 
    public $confirmer;
    public $query;
    public $parEtat; 
    public $parProf;
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
            $autoriser = $role[0]->consulter_commande;
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
        $mod_cmd = $entite_mod[0]->mod_cmd; 
        $soldeClient = $entite_mod[0]->solde;
        if($dateJour <= $jourValid){
            if($mod_cmd == 1){
                $title = 'Proforma client | WamsCo';
                $module = 'Gestion Commande';
                $title_fils = 'Proforma client';
                $lien = 'listing_fact_clt';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');      
                $dateJour = date('Y-m-d');
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');

                $start = Carbon::parse($this->date_debut)->startOfDay(); //2016-09-29 00:00:00.000000
                $end = Carbon::parse($this->date_fin)->endOfDay();     // 2016-09-29 23:59:59.000000
                if(!empty($this->parEtat)){
                    $profor_client = ProformaClientEntete::where('societe_id',auth()->user()->societe_id)->where('nom_client','like','%'.$this->query.'%')->where('code_proforma','like','%'.$this->parProf.'%')->where('etat',$this->parEtat)->whereBetween('created_at',[$start, $end])->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage);
                }
                else{
                    $profor_client = ProformaClientEntete::where('societe_id',auth()->user()->societe_id)->where('nom_client','like','%'.$this->query.'%')->where('code_proforma','like','%'.$this->parProf.'%')->whereBetween('created_at',[$start, $end])->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage);
                }
                $profClientCount = $profor_client->count();
                $montantTTC = $profor_client->sum('montant_ttc');
                $montantTrecu = $profor_client->sum('montant_recu');
                $montantTreste_Percevoir = $profor_client->sum('reste_a_percevoir');
                $montantTmarge = $profor_client->sum('marge');
                
                // pour les KPI
                $resultat = ProformaClientEntete::where('societe_id',auth()->user()->societe_id)->get();  
                $nbreTotalProforma = $resultat->count(); 
                $montantTTC_all = $resultat->sum('montant_ttc');
                $montantTrecu_all = $resultat->sum('montant_recu');
                $montantTmarge_All = $resultat->sum('marge'); 
                $montantCreance_all = $resultat->sum('reste_a_percevoir');     

                $derniereActivite = ProformaClientEntete::where('societe_id',auth()->user()->societe_id)->latest('updated_at')->first();
                
                $page = 'ProformaClient'; // pour evenement lies
                $log = LogActivityModel::where('user_societe',auth()->user()->societe)->where('page', $page)->limit(50)->orderBy('id','desc')->get();
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
                return view('livewire.gestion-commande.client.proforma',compact('title_fils','module','lien','dateJour','profor_client','profClientCount','montantTTC','montantTrecu','montantTreste_Percevoir','montantTmarge',
                'nbreTotalProforma','montantTTC_all','montantTrecu_all','montantTmarge_All','montantCreance_all','derniereActivite','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
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
        // $this->validate();       
       $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_commande;
            if($autoriser == 1){   
           
                // creation et enregistrement auto Banque ou caisse
                // $reference = date('YmdHis');
                $client = '';  
                $id_client = 0;                     
                $date_proforma = date('Y-m-d');
                $date_livraison = date('Y-m-d');
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
                $token_ok = 'PROF/'.$dates;
                // $token_ok = 'FACT/'.$dates.'/'.$token;
                $profCltEntet = ProformaClientEntete :: create(['code_proforma'=>$token_ok,'nom_client'=>$client,'id_client'=>$id_client,'date_proforma'=>$date_proforma,'date_livraison'=>$date_livraison,
                            'montant_ht'=>$montant_ht,'montant_remise'=>$montant_remise,'montant_tva'=>$montant_tva,'montant_precompte'=>$montant_precompte,'montant_ttc'=>$montant_ttc,'marge'=>$marge,
                            'montant_recu'=>$montant_recu,'reste_a_percevoir'=>$reste_a_percevoir,'mode_reglement'=>$mode_reglement,'compte_bancaire'=>$compte_bancaire,'note'=>$note,'etat'=>$etat,'societe'=>auth()->user()->societe,
                            'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                        // ceci recupere le dernier enregistrement cree a l'instant
                $dernier_id = $profCltEntet->id; 

                $id_activite = $dernier_id;
                $page = 'ProformaClient';
                LogActivity::addToLog('Proforma client » '.$token_ok.' créé', $id_activite, $page);
                $this->dispatch('alert',                    
                    title:'proforma client enregistrée!',
                    timer:3000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                );             
                $this->redirect('/nouveau_prof_clt?id='.$dernier_id.'&ref='.$token_ok, navigate: true);               
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
     public function confirmerDelete($id){
        $this->confirmer = $id;        
    } 
    public function supprimer(int $id, string $code_cmd){
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->supprimer_commande;
            if($autoriser == 1){   
                if($id){
                   
                    $test_regle = CommandeClientEntete::where('societe_id',auth()->user()->societe_id)->where('code_commande',$code_cmd)->count();
                    if($test_regle == 0){

                         $page = 'ProformaClient';
                         ProformaClientEntete::where('id',$id)->delete();
                         ProformaClientLigne::where('id_proforma_client_entete',$id)->delete();
                         LogActivityModel::where('id_activite',$id)->where('page',$page)->delete();

                         $id_activite = $id;
                         LogActivity::addToLog('Proforma client supprimé définitivement', $id_activite, $page);
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
                            title:'Désolé, vous ne pouvez pas supprimer cette proforma. <br> Veuillez supprimer au préalable les commandes liées!',
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
    public function detailCmd(int $idx, $codeCmd_prof){
        // ceci au chargement de la page
        $test_commande = CommandeClientEntete::where('societe_id',auth()->user()->societe_id)->where('id',$idx)->count();    
        if($test_commande > 0){
            $compte = CommandeClientEntete::where('societe_id',auth()->user()->societe_id)->where('id',$idx)->first();               
            $this->ids = $compte->id;           
            $this->reference = $compte->code_commande; // reference facture
            $this->redirect('/nouveau_cmd_clt?id='.$idx.'&ref='.$this->reference, navigate: true);
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
            flash ('Désolé, cette commande <strong>('.$codeCmd_prof.')</strong> n\'existe pas!')->error();
            $this->redirect('/listing_cmd_clt?active=6&champ=1-1&choix=2', navigate: true);
        }
    } 
}
