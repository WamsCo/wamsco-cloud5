<?php

namespace App\Livewire\GestionBanque;

use Livewire\Component;
use Livewire\Attributes\Validate; 
Use Carbon\Carbon;
use Livewire\WithPagination;
use App\Helpers\LogActivity;
use App\Models\LogActivity as LogActivityModel;
use App\Models\Entite;
use App\Models\DeviseTva;
use App\Models\Role;
use App\Models\PaiementDiver;
use App\Models\CompteBancaire;
use App\Models\EcritureBancaire;

class PaiementDivers extends Component
{
    protected $paginationTheme = 'bootstrap';
    use WithPagination;

    public $id;
    public $ids = 0; // gerer ouverture update 
   
    public $date_debut; 
    public $date_fin; 
    
   #[Validate('required|max:255')] 
    public $reference;

    #[Validate('required')] 
    public $date_paiement;

    #[Validate('required')] 
    public $date_valeur;

    #[Validate('required|max:255')] 
    public $libele_paiement;

    #[Validate('required|numeric')] 
    public $montant;

    #[Validate('required|max:255')] 
    public $nom_compte_bancaire;

    #[Validate('required|max:255')] 
    public $mode_reglement;

    #[Validate('nullable|max:255')]
    public $numero_cheque_virement;
    #[Validate('nullable|max:255')]
    public $emetteur;
    #[Validate('nullable|max:255')]
    public $nom_banque;

    #[Validate('required|max:255')] 
    public $sens;
    
    #[Validate('nullable|max:255')]
    public $note;     

    public $id_ecriture_bancaire;
    
    public $confirmer;
    public $query;
    public $parRef;
    public $parCompte;        
    public $parPage = 20;
    public $orderField = 'id'; 
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
    // ceci permet de masquer le formulaire apres le update
    protected $listeners = [
        'paieUpdate' => 'onPaieUpdated'
    ];
    public function onPaieUpdated(){
        $this->reset('ids');
    } 
    public function mount(){
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->consulter_paie_divers;
            if($autoriser == 0){
                alert()->error('Oups Désolé', 'Vous n\'êtes pas autorisé à ouvrir cette page !!!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
                $this->redirect('/bienvenue', navigate: true);
            }
        }
        else{
            alert()->error('Oups Désolé', 'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
            $this->redirect('/bienvenue', navigate: true);
        }  
        $this->date_debut = date('Y-m-d', strtotime('-1 year'));  // ceci pour affiche toutes les sessions en permanance sur 1 mois par defaut
        $this->date_fin = date('Y-m-d');  
        
        $this->date_paiement = date('Y-m-d');
        $this->date_valeur = date('Y-m-d');   
        $this->reference = date('ymdHis');
    }
    public function render(){    
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();
        $jourValid = $entite_mod[0]->validite_mod; 
        $mod_banque_caisse = $entite_mod[0]->mod_banque_caisse;
        $soldeClient = $entite_mod[0]->solde;
        if($dateJour <= $jourValid){
            if($mod_banque_caisse == 1){
                $title = 'Paiement divers | WamsCo'; 
                $module = 'Gestion Banque';
                $title_fils = 'Paiement divers';
                $lien = 'listing_paie_divers'; 
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');
                $dateJour = date('Y-m-d');
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');           
                // $paiementDiv = PaiementDiver :: where('societe',auth()->user()->societe)->where('libele_paiement','like','%'.$this->query.'%')->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage); 
                // $paiementDivCount = $paiementDiv->count(); 

                $compte = CompteBancaire::where('societe',auth()->user()->societe)->orderBy('nom_compte_bancaire','Asc')->get();
                $compteBancaire = CompteBancaire::where('societe',auth()->user()->societe)->where('etat',1)->get();
                $infos = 'Pour le versement utilisez Crédit pour enregistrer un règlement reçu.<br> Pour le retrait, utilisez Débit pour enregistrer un règlement reçu.';

                $start = Carbon::parse($this->date_debut)->startOfDay(); //2016-09-29 00:00:00.000000
                $end = Carbon::parse($this->date_fin)->endOfDay();     // 2016-09-29 23:59:59.000000
                if(!empty($this->parCompte)){
                    $paiementDiv = PaiementDiver::where('societe',auth()->user()->societe)->where('libele_paiement','like','%'.$this->query.'%')->where('reference','like','%'.$this->parRef.'%')->where('nom_compte_bancaire',$this->parCompte)->whereBetween('created_at',[$start, $end])->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage);
                }
                else{
                    $paiementDiv = PaiementDiver::where('societe',auth()->user()->societe)->where('libele_paiement','like','%'.$this->query.'%')->where('reference','like','%'.$this->parRef.'%')->whereBetween('created_at',[$start, $end])->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage);
                }
                $paiementDivCount = $paiementDiv->count();
                // Totaux solde
                $soldeDebit = $paiementDiv->sum('debit');  
                $soldeCredit = $paiementDiv->sum('credit');

                 // KPI
                $resultat = PaiementDiver :: where('societe',auth()->user()->societe)->get();
                $TotalDebit = $resultat->sum('debit'); 
                $TotalCredit = $resultat->sum('credit');
                $soldeTotal = $TotalCredit - $TotalDebit; 
                // Fin KPI

                $resultat = PaiementDiver::where('societe',auth()->user()->societe)->get();  
                $nbreTotalPaiementDiver = $resultat->count();     

                $derniereActivite = PaiementDiver::where('societe',auth()->user()->societe)->latest('updated_at')->first();
                
                $page = 'PaiementDiver'; // Pour evenement lie
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
                return view('livewire.gestion-banque.paiement-divers',compact('title_fils','module','lien','dateJour','compte','compteBancaire','infos','paiementDiv','paiementDivCount','soldeDebit','soldeCredit','TotalDebit','TotalCredit','soldeTotal','nbreTotalPaiementDiver','derniereActivite','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));     
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
            $title_fils = 'Magasin';
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
        $this->validate();        
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_paie_divers;
            if($autoriser == 1){                       
                    
                    $test_compte = CompteBancaire::where('societe',auth()->user()->societe)->where('id',$this->nom_compte_bancaire)->count();    
                    if($test_compte > 0){
                        $compte = CompteBancaire::where('societe',auth()->user()->societe)->where('id',$this->nom_compte_bancaire)->first();               
                        $this->ids = $compte->id;
                        $this->compte_bancaire = $compte->nom_compte_bancaire;
                    }           

                    // ceci calcul le solde
                    $soldeCredit = EcritureBancaire::where('societe',auth()->user()->societe)->where('id_compte_bancaire',$this->ids)->sum('credit');
                    $soldeDebit = EcritureBancaire::where('societe',auth()->user()->societe)->where('id_compte_bancaire',$this->ids)->sum('debit');  
                    $solde = $soldeCredit - $soldeDebit;

                    $type_paiement = 'PaiementDivers';

                    if($this->sens == 'Débit'){

                        $solde_net = $solde - $this->montant;
                        // Creation et enregistrement ecriture bancaire  
                        $credit = 0;
                        EcritureBancaire::create(['id_compte_bancaire'=>$this->ids,'nom_compte_bancaire'=>$this->compte_bancaire,'reference'=>$this->reference,'description'=>$this->libele_paiement,'date_operation'=>$this->date_paiement,'date_valeur'=>$this->date_valeur,
                        'type_operation'=>$this->mode_reglement,'debit'=>$this->montant,'credit'=>$credit,'solde'=>$solde_net,'type_paiement'=>$type_paiement,'id_type_paiement'=>0,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);      
                         // ceci recupere le dernier enregistrement cree a l'instant
                        $dernier_id = EcritureBancaire::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id; 

                        PaiementDiver::create(['reference'=>$this->reference,'date_paiement'=>$this->date_paiement,'date_valeur'=>$this->date_valeur,'libele_paiement'=>$this->libele_paiement,
                                               'id_ecriture_bancaire'=>$dernier_id,'nom_compte_bancaire'=>$this->compte_bancaire,'id_compte_bancaire'=>$this->ids,'mode_reglement'=>$this->mode_reglement,'numero_cheque_virement'=>$this->numero_cheque_virement,
                                               'emetteur'=>$this->emetteur,'nom_banque'=>$this->nom_banque,'debit'=>$this->montant,'credit'=>$credit,'note'=>$this->note,'sens'=>$this->sens,'societe'=>auth()->user()->societe,
                                               'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                        // ajout id
                        $last_id = PaiementDiver::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id; 
                        EcritureBancaire::where('societe',auth()->user()->societe)->where('id',$dernier_id)->update(['id_type_paiement'=>$last_id]); 

                        CompteBancaire::where('societe',auth()->user()->societe)->where('id',$this->ids)->update(['solde'=>$solde_net]); 
                    }
                    elseif($this->sens == 'Crédit'){

                        $solde_net = $solde + $this->montant;                       
                        // Creation et enregistrement ecriture bancaire  
                        $debit = 0;
                        EcritureBancaire::create(['id_compte_bancaire'=>$this->ids,'nom_compte_bancaire'=>$this->compte_bancaire,'reference'=>$this->reference,'description'=>$this->libele_paiement,'date_operation'=>$this->date_paiement,'date_valeur'=>$this->date_valeur,
                                                  'type_operation'=>$this->mode_reglement,'debit'=>$debit,'credit'=>$this->montant,'solde'=>$solde_net,'type_paiement'=>$type_paiement,'id_type_paiement'=>0,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);      
                        
                        $dernier_id = EcritureBancaire::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id;

                        PaiementDiver::create(['reference'=>$this->reference,'date_paiement'=>$this->date_paiement,'date_valeur'=>$this->date_valeur,'libele_paiement'=>$this->libele_paiement,
                                               'id_ecriture_bancaire'=>$dernier_id,'nom_compte_bancaire'=>$this->compte_bancaire,'id_compte_bancaire'=>$this->ids,'mode_reglement'=>$this->mode_reglement,'numero_cheque_virement'=>$this->numero_cheque_virement,
                                               'emetteur'=>$this->emetteur,'nom_banque'=>$this->nom_banque,'debit'=>$debit,'credit'=>$this->montant,'note'=>$this->note,'sens'=>$this->sens,'societe'=>auth()->user()->societe,
                                               'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                        // ajout id
                        $last_id = PaiementDiver::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id; 
                        EcritureBancaire::where('societe',auth()->user()->societe)->where('id',$dernier_id)->update(['id_type_paiement'=>$last_id]); 

                        CompteBancaire::where('societe',auth()->user()->societe)->where('id',$this->ids)->update(['solde'=>$solde_net]); 
                    }
                    else{
                        $this->dispatch('alert',                    
                            title:'Désolé, ce sens n\'existe pas!',
                            timer:3000,
                            icon:'error',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        );  
                    }                                                  
                  
                    $id_activite = $last_id;
                    $page = 'PaiementDiver';
                    LogActivity::addToLog('Paiement divers » '.$this->libele_paiement.' créé', $id_activite, $page);    
                    $this->dispatch('alert',                    
                        title:'paiement divers ('.$this->libele_paiement.') enregistré!',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );  
                    // $this->resetinputFields();  
                    flash ('Paiement divers <strong>'.$this->libele_paiement.'</strong> créé')->success();
                    $this->redirect('/listing_paie_divers?active=8&champ=1-3', navigate: true);  
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
                showConfirmButton: false,
                position:'top-end',
            ); 
        }  
    }    
    public function confirmerDelete($id){  
        $this->confirmer = $id;      
    } 
    public function supprimer($id){ 
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->supprimer_paie_divers;
            if($autoriser == 1){   
                if($id){                   
                    // supprime le paiement Divers
                    PaiementDiver::where('societe',auth()->user()->societe)->where('id',$id)->delete(); 
                    EcritureBancaire::where('societe',auth()->user()->societe)->where('id_type_paiement',$id)->delete(); 
                    $page = 'PaiementDiver';
                    LogActivityModel::where('id_activite',$id)->where('page',$page)->delete();

                    $id_activite = $id;
                    LogActivity::addToLog('Paiement divers supprimé définitivement', $id_activite, $page);
                    $this->dispatch('alert',                    
                        title:'Suppression effectuée!',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );  
                }
            } 
            else{ 
                $this->dispatch('alert',                    
                    title:'Vous n\'êtes pas autorisé à supprimer!',
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
                showConfirmButton: false,
                position:'top-end',
            ); 
        }        
    }  
}
