<?php

namespace App\Livewire\GestionBanque;

use Livewire\Component;
use Livewire\Attributes\Validate; 
use Livewire\WithPagination;
use App\Helpers\LogActivity;
use App\Models\LogActivity as LogActivityModel;
use App\Models\Entite;
use App\Models\DeviseTva;
use App\Models\Role;
use App\Models\PaiementDiver;
use App\Models\CompteBancaire;
use App\Models\EcritureBancaire;

class VirementInterne extends Component
{
    protected $paginationTheme = 'bootstrap';
    use WithPagination;

    public $id;
    public $ids = 0; // pour ecriture source
    public $ide = 0; // pour ecriture destination

    #[Validate('required|numeric')] 
    public $compte_source;

    #[Validate('required|numeric')] 
    public $compte_destination;

    #[Validate('required')] 
    public $type_reglement = 'Virement bancaire';

    #[Validate('required')] 
    public $date_operation;

    #[Validate('required|max:32')] 
    public $description;

    #[Validate('required|numeric')] 
    public $montant;

    // pour ecriture
    public $compte_bancaire;
    public $solde_source;
    public $solde_destination;
    
    public function resetinputFields(){           
        $this->compte_source = '';      
        $this->compte_destination = '';      
        $this->type_reglement = 'Virement bancaire';
        $this->date_operation = date('Y-m-d');   
        $this->description = '';    
        $this->montant = '';    
    }
    public function mount(){  
       
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->effectuer_vire_interne;
            if($autoriser == 0){
                alert()->error('Oups Désolé', 'Vous n\'êtes pas autorisé à ouvrir cette page !!!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
                $this->redirect('/bienvenue', navigate: true);
            }
        }
        else{
            alert()->error('Oups Désolé', 'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
            $this->redirect('/bienvenue', navigate: true);
        }
        $this->date_operation = date('Y-m-d');        
    }
    public function render(){

        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();
        $jourValid = $entite_mod[0]->validite_mod; 
        $mod_banque_caisse = $entite_mod[0]->mod_banque_caisse;
        $soldeClient = $entite_mod[0]->solde;
        if($dateJour <= $jourValid){
            if($mod_banque_caisse == 1){
                $title = 'Virement Interne | WamsCo';
                $module = 'Gestion Banque';
                $title_fils = 'Virement interne';
                $lien = 'virement_interne';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');    
                $dateJour = date('Y-m-d');

                $deviseTva = DeviseTva :: where('societe',auth()->user()->societe)->limit(1)->orderBy('id','asc')->count(); 
                if($deviseTva == 0){
                    $this->devise = 'FCFA';
                }
                else{
                    $deviseTva = DeviseTva :: where('societe',auth()->user()->societe)->limit(1)->orderBy('id','asc')->get(); 
                    $this->devise = $deviseTva[0]->devise;
                }

                $this->id = request('id'); // id transfert           

                toast()->success('Prêt', '')->position('top-right')->autoClose(1000)->background('#fff')->width('220px')->padding('5px');   
                    
                $compte_banque = CompteBancaire::where('societe',auth()->user()->societe)->where('etat',1)->orderBy('nom_compte_bancaire','ASC')->get();
                    // $compteBanqueCount = $compte_banque->count();

                $resultat = CompteBancaire::where('societe',auth()->user()->societe)->where('etat',1)->get();  
                $nbreTotalCompteBancaire = $resultat->count();     

                $derniereActivite = CompteBancaire::where('societe',auth()->user()->societe)->latest('updated_at')->first(); 

                $page = 'VirementInterne'; // Pour evenement lie
                $log = LogActivityModel::where('user_societe',auth()->user()->societe)->where('page', $page)->limit(50)->orderBy('id','desc')->get();
                $logCount = $log->count();
                
                $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get(); 
                $jourValid = $entite_mod[0]->validite_mod; 
                // ceci pour trouver le nombre de jour restant avant expiration
                $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24));
                return view('livewire.gestion-banque.virement-interne',compact('title_fils','module','lien','dateJour','compte_banque','nbreTotalCompteBancaire','derniereActivite','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant')); 
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
    public function valider(){
        $this->validate();
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->effectuer_vire_interne;
            if($autoriser == 1){    
                    
                    if($this->compte_source == $this->compte_destination){
                        $this->dispatch('alert',                    
                            title:'Les comptes <strong>source</strong> et <strong>destination</strong> doivent être différents!',
                            timer:5000,
                            icon:'error',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        ); 
                        flash ('Les comptes <strong>source</strong> et <strong>destination</strong> doivent être différents!')->error(); 
                        return back();            
                    }   

                    $test_compte = CompteBancaire::where('societe',auth()->user()->societe)->where('id',$this->compte_source)->count();    
                    if($test_compte > 0){
                        $compte = CompteBancaire::where('societe',auth()->user()->societe)->where('id',$this->compte_source)->first();               
                        $this->ids = $compte->id;
                        $this->compte_bancaire = $compte->nom_compte_bancaire;
                        $this->solde_source = $compte->solde;

                        $compteDesti = CompteBancaire::where('societe',auth()->user()->societe)->where('id',$this->compte_destination)->first();               
                        $this->ide = $compteDesti->id;
                        $this->compte_bancaireDesti = $compteDesti->nom_compte_bancaire;
                        $this->solde_destination = $compteDesti->solde;
                    }     

                    $solde_net = $this->solde_source - $this->montant;  // Pour Debit
                    $solde_net_destination = $this->solde_destination + $this->montant; // Pour Credit

                    $type_paiement = 'VirementInterne';
                    $reference = date('ymd-His');

                    // ceci pour mettre le solde CompteBancaire Source a jour 
                    CompteBancaire::where('societe',auth()->user()->societe)->where('id',$this->ids)->update(['solde'=>$solde_net]); 
                    // ceci pour mettre le solde CompteBancaire Destination a jour 
                    CompteBancaire::where('societe',auth()->user()->societe)->where('id',$this->ide)->update(['solde'=>$solde_net_destination]); 

                    // pour la 1ere ecriture en debit
                    $credit = 0;
                    $statut = 'Confirmer';
                    EcritureBancaire::create(['id_compte_bancaire'=>$this->ids,'nom_compte_bancaire'=>$this->compte_bancaire,'reference'=>$reference,
                                    'description'=>'De » '.substr($this->compte_bancaire,0,22).' Vers » '.substr($this->compte_bancaireDesti,0,22).' - '.$this->description,'date_operation'=>$this->date_operation,
                                    'date_valeur'=>$this->date_operation,'type_operation'=>$this->type_reglement,'debit'=>$this->montant,'credit'=>$credit,'solde'=>$solde_net,
                                    'type_paiement'=>$type_paiement,'id_type_paiement'=>0,'statut'=>$statut,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);  
                                    
                    // pour la 2e ecriture en credit
                    $debit = 0;
                    EcritureBancaire::create(['id_compte_bancaire'=>$this->ide,'nom_compte_bancaire'=>$this->compte_bancaireDesti,'reference'=>$reference,
                    'description'=>'De » '.substr($this->compte_bancaire,0,22).' Vers » '.substr($this->compte_bancaireDesti,0,22).' - '.$this->description,'date_operation'=>$this->date_operation,
                    'date_valeur'=>$this->date_operation,'type_operation'=>$this->type_reglement,'debit'=>$debit,'credit'=>$this->montant,'solde'=>$solde_net_destination,
                    'type_paiement'=>$type_paiement,'id_type_paiement'=>0,'statut'=>$statut,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);      

                    $id_activite = 0;
                    $page = 'VirementInterne';
                    LogActivity::addToLog('Virement interne De » '.$this->compte_bancaire.' vers » '.$this->compte_bancaireDesti.' effectué', $id_activite, $page);
                    $this->dispatch('alert',                    
                        title:'Virement effectué avec succes!',
                        timer:10000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );  
                    $this->resetinputFields();
            }
            else{                 
                $this->dispatch('alert',                    
                    title:'Vous n\'êtes pas autorisé à effectuer cette opération !',
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
