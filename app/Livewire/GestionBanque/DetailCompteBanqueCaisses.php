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
use App\Models\CompteBancaire;
use App\Models\EcritureBancaire;
use App\Models\Reglement;
use App\Models\Reglement_fourni;
use App\Models\PaiementDiver;
use App\Models\factureClientEntete;
use App\Models\factureFournisseurEntete;
use App\Models\CommandeClientEntete;

class DetailCompteBanqueCaisses extends Component
{
    protected $paginationTheme = 'bootstrap';    // ceci pour pagination avec les chiffres tres important
    use WithPagination;

    public $id;
    public $ids;
    
    #[Validate('required')] 
    public $reference;

    #[Validate('required')] 
    public $nom_compte_bancaire;

    #[Validate('required')] 
    public $type_compte;
   
    // public $solde;  

    #[Validate('max:200')]                  
    public $nom_banque; 

    #[Validate('max:200')] 
    public $num_compte;

    #[Validate('max:200')] 
    public $nom_proprietaire;
    
    #[Validate('required|numeric')] 
    public $solde_initial;

    #[Validate('max:200')] 
    public $note;    
    public $etat;    
    
    public $parPage = 20;
    public $query;
    public $confirmer;
    public $approuver;    
    public $orderField = 'id'; 
    public $orderDirection = 'DESC'; 
    public $date_debut; 
    public $date_fin;

    public $created_at;
    public $updated_at;
    public $nom_user;
    public $devise;

    public $affiche = 0;
    public function afficherSolde(int $idz){
        $this->affiche = $idz;
    } 
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
            $autoriser = $role[0]->consulter_compte;
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
        $this->id = request('id'); // id CompteBancaire 

    }
    public function render()
    { 
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('id',auth()->user()->societe_id)->get();
        $jourValid = $entite_mod[0]->validite_mod; 
        $mod_banque_caisse = $entite_mod[0]->mod_banque_caisse; 
        $soldeClient = $entite_mod[0]->solde;
        if($dateJour <= $jourValid){
            if($mod_banque_caisse == 1){
                $title = 'Details Banque & Caisse | WamsCo';
                $module = 'Gestion Banque & Caisse';
                $title_fils = 'Comptes bancaires';
                $lien = 'banque_caisse';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');    
                $dateJour = date('Y-m-d');

                $deviseTva = DeviseTva :: where('societe_id',auth()->user()->societe_id)->limit(1)->orderBy('id','asc')->count(); 
                if($deviseTva == 0){
                    $this->devise = 'FCFA';
                }
                else{
                    $deviseTva = DeviseTva :: where('societe_id',auth()->user()->societe_id)->limit(1)->orderBy('id','asc')->get(); 
                    $this->devise = $deviseTva[0]->devise;
                }
                // $this->id = request('id'); // id CompteBancaire           

                toast()->success('Prêt', '')->position('top-right')->autoClose(1000)->background('#fff')->width('220px')->padding('5px'); 
                
                //     // ceci au chargement de la page
                    $test_compte = CompteBancaire::where('societe_id',auth()->user()->societe_id)->where('id',$this->id)->count();    
                    if($test_compte > 0){
                        $compte = CompteBancaire::where('societe_id',auth()->user()->societe_id)->where('id',$this->id)->first();               
                        $this->ids = $compte->id;
                        $this->reference = $compte->reference;
                        $this->nom_compte_bancaire = $compte->nom_compte_bancaire;
                        $this->type_compte = $compte->type_compte;
                        $this->nom_banque = $compte->nom_banque; 
                        $this->num_compte = $compte->num_compte;
                        $this->nom_proprietaire = $compte->nom_proprietaire;
                        $this->note = $compte->note;
                        $this->etat = $compte->etat;

                        $this->nom_user = $compte->nom_user;
                        $this->created_at = $compte->created_at;
                        $this->updated_at = $compte->updated_at;
                    }  
            
                // Ceci affiche le solde initial du compte
                $this->solde_initial = EcritureBancaire::where('societe_id',auth()->user()->societe_id)->where('id_compte_bancaire',$this->ids)->where('type_paiement','SoldeInitial')->sum('solde');

                $start = Carbon::parse($this->date_debut)->startOfDay(); //2016-09-29 00:00:00.000000
                $end = Carbon::parse($this->date_fin)->endOfDay();     // 2016-09-29 23:59:59.000000
                if(empty($this->query)){
                    $ecriture = EcritureBancaire :: where('societe_id',auth()->user()->societe_id)->where('id_compte_bancaire',$this->ids)->whereBetween('created_at',[$start, $end])->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage); 
                } 
                else{
                    $ecriture = EcritureBancaire::where('societe_id',auth()->user()->societe_id)->where('id_compte_bancaire',$this->ids)->where('tiers','like','%'.$this->query.'%')->whereBetween('created_at',[$start, $end])->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage);
                }
                $banqueCount = $ecriture->count();
                // Totaux solde
                $soldeCredit = $ecriture->sum('credit');
                $soldeDebit = $ecriture->sum('debit'); 
                $solde = $soldeCredit - $soldeDebit; 

                $page = 'CompteBancaire'; // Pour evenement lie
                $log = LogActivityModel::where('societe_id',auth()->user()->societe_id)->where('id_activite', $this->ids)->where('page', $page)->limit(42)->orderBy('id','desc')->get();
                $logCount = $log->count();

                $entite_mod = Entite::where('id',auth()->user()->societe_id)->get(); 
                $jourValid = $entite_mod[0]->validite_mod; 
                // ceci pour trouver le nombre de jour restant avant expiration
                $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24));
                return view('livewire.gestion-banque.detail-compte-banque-caisses',compact('title_fils','module','lien','dateJour','ecriture','banqueCount','soldeDebit','soldeCredit','solde','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant')); 
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
    public function update(){
        $this->validate();        
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_compte;
            if($autoriser == 1){     
                
                CompteBancaire::find($this->ids)->update(['reference'=>$this->reference,'nom_compte_bancaire'=>$this->nom_compte_bancaire,'type_compte'=>$this->type_compte,
                                'nom_banque'=>$this->nom_banque,'num_compte'=>$this->num_compte,'nom_proprietaire'=>$this->nom_proprietaire,'note'=>$this->note,'etat'=>$this->etat,
                                'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);   
                                
                EcritureBancaire::where('id_compte_bancaire',$this->ids)->update(['nom_compte_bancaire'=>$this->nom_compte_bancaire,'reference'=>$this->reference,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                Reglement::where('id_compte_bancaire',$this->ids)->update(['compte_bancaire'=>$this->nom_compte_bancaire,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                Reglement_fourni::where('id_compte_bancaire',$this->ids)->update(['compte_bancaire'=>$this->nom_compte_bancaire,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                factureClientEntete::where('id_compte_bancaire',$this->ids)->update(['compte_bancaire'=>$this->nom_compte_bancaire]); 
                factureFournisseurEntete::where('id_compte_bancaire',$this->ids)->update(['compte_bancaire'=>$this->nom_compte_bancaire]);  
                PaiementDiver::where('id_compte_bancaire',$this->ids)->update(['nom_compte_bancaire'=>$this->nom_compte_bancaire]);  
                               
                               
                $id_activite = $this->ids;
                $page = 'CompteBancaire';
                LogActivity::addToLog('Entête Banque & Caisse » '.$this->nom_compte_bancaire.' modifiée', $id_activite, $page);
                $this->dispatch('alert',                    
                    title:$this->nom_compte_bancaire.' modifié(e)!',
                    timer:3000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                );
                flash ('Entête Banque & Caisse » <strong>'.$this->nom_compte_bancaire.'</strong> modifiée')->success();                 
                $this->redirect('/detail_banque?id='.$this->ids.'&active=8&champ=1-1', navigate: true); 
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
    public function confirmerEcraser($id){
        $this->approuver = $id;        
    } 
    public function ecraser(){ 
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->supprimer_compte;
            if($autoriser == 1){             
                // dd($verifierEcriture = EcritureBancaire::where('societe_id',auth()->user()->societe_id)->where('id_compte_bancaire',$this->ids)->first());
                $verification = CompteBancaire::where('societe_id',auth()->user()->societe_id)->where('id',$this->ids)->first();
                $solde = $verification->solde;
                $etat = $verification->etat;
                if($solde == 0 && $etat == 0){

                    $cpte = CompteBancaire::where('societe_id',auth()->user()->societe_id)->where('id',$this->ids)->first();
                    $test_solde = $cpte->solde;
                    $etat = $cpte->etat;
                    if($test_solde == 0 && $etat == 0){ 
                        
                            // suppression definitive
                            $page = 'CompteBancaire';
                            CompteBancaire::where('id',$this->ids)->delete(); 
                            EcritureBancaire::where('id_compte_bancaire',$this->ids)->delete(); 
                            LogActivityModel::where('id_activite',$this->ids)->where('page',$page)->delete();

                            $id_activite = $this->ids;
                            LogActivity::addToLog('Compte Bancaire supprimé définitivement', $id_activite, $page);
                            $this->dispatch('alert',                    
                                title:'Compte bancaire supprimé avec succes!',
                                timer:5000,
                                icon:'success',
                                toast:true,
                                showConfirmButton: false,
                                position:'top-end',
                            );   
                            flash ('<strong>Compte Bancaire et Ecriture(s) »</strong> supprimé définitivement')->success();
                            $this->redirect('/banque_caisse?active=8&champ=1-1', navigate: true);                         
                    }
                    else{
                        $this->dispatch('alert',                    
                            title:'Désolé, veuillez solder le compte(0) et désactiver avant de supprimer!',
                            timer:6000,
                            icon:'warning',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        ); 
                    } 
                }
                else{
                    $this->dispatch('alert',                    
                        title:'Désolé, des <strong> écritures sont liées </strong> à ce compte. <br/> Vous ne pouvez supprimer! (Attention, Désactiver le compte et réessayez )',
                        timer:8000,
                        icon:'warning',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    ); 
                }        
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
                toast:false,
                showConfirmButton: true,
                position:'center',
            );  
        }   
    }
    public function changeSoldeInitial(int $id_CpteBq){  
            $this->validate([            
            'solde_initial'=>'required|numeric',            
        ]);    
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_compte;
            if($autoriser == 1){  

                $compte = EcritureBancaire::where('societe_id',auth()->user()->societe_id)->where('id_compte_bancaire',$this->ids)->where('type_paiement','SoldeInitial')->first();    
                $id_compte_bancaire = $compte->id;           
                $type_paiement= $compte->type_paiement;       

                if($type_paiement == 'SoldeInitial'){
                    
                    EcritureBancaire::where('societe_id',auth()->user()->societe_id)->where('id_compte_bancaire',$this->ids)->where('type_paiement','SoldeInitial')->update(['credit'=>$this->solde_initial,'solde'=>$this->solde_initial,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                    
                    // ceci calcul le solde
                    $soldeCredit = EcritureBancaire::where('societe_id',auth()->user()->societe_id)->where('id_compte_bancaire',$this->ids)->sum('credit');
                    $soldeDebit = EcritureBancaire::where('societe_id',auth()->user()->societe_id)->where('id_compte_bancaire',$this->ids)->sum('debit');  
                    $solde = $soldeCredit - $soldeDebit;
                    CompteBancaire::where('societe_id',auth()->user()->societe_id)->where('id',$this->ids)->update(['solde'=>$solde,'societe'=>auth()->user()->societe,
                    'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                    $page = 'CompteBancaire'; 
                    $id_activite = $this->ids;
                    LogActivity::addToLog('Solde initial ('.$this->solde_initial.' '.$this->devise.') Banque & Caisse modifié', $id_activite, $page);  
                    $this->dispatch('alert',                    
                        title:'solde Initial ('.$this->solde_initial.') modifié!',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );
                    $this->affiche = 0;
                    $this->redirect('/detail_banque?id='.$this->ids.'&active=8&champ=1-1', navigate: true);
                }  
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
                toast:false,
                showConfirmButton: true,
                position:'center',
            );  
        } 
    } 
    public function confirmerDelete($id){    

        $this->confirmer = $id;      
    } 
    public function supprimer($id){ 

        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->supprimer_compte;
            if($autoriser == 1){   
                if($id){ 
                    $compte = EcritureBancaire::where('societe_id',auth()->user()->societe_id)->where('id',$id)->first();    
                    $id_compte_bancaire = $compte->id_compte_bancaire;           
                    $type_paiement= $compte->type_paiement;

                    if($type_paiement == 'PaiementDivers'){
                        
                        EcritureBancaire::where('societe_id',auth()->user()->societe_id)->where('id',$id)->delete(); 
                        PaiementDiver::where('societe_id',auth()->user()->societe_id)->where('id_ecriture_bancaire',$id)->delete(); 
                        // ceci calcul le solde
                        $soldeCredit = EcritureBancaire::where('societe_id',auth()->user()->societe_id)->where('id_compte_bancaire',$id_compte_bancaire)->sum('credit');
                        $soldeDebit = EcritureBancaire::where('societe_id',auth()->user()->societe_id)->where('id_compte_bancaire',$id_compte_bancaire)->sum('debit');  
                        $solde = $soldeCredit - $soldeDebit;
                        CompteBancaire::where('societe_id',auth()->user()->societe_id)->where('id',$id_compte_bancaire)->update(['solde'=>$solde]);

                        $id_activite = $id_compte_bancaire;
                        $page = 'CompteBancaire';
                        LogActivity::addToLog('Ecriture Bancaire (PaiementDivers) supprimée définitivement', $id_activite, $page);
                        $this->dispatch('alert',                    
                            title:'Suppression effectuée!',
                            timer:3000,
                            icon:'success',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        ); 
                        $this->redirect('/detail_banque?id='.$id_compte_bancaire.'&active=8&champ=1-1', navigate: true); 
                    }
                    elseif($type_paiement == 'SoldeInitial'){
                        
                        // EcritureBancaire::where('societe_id',auth()->user()->societe_id)->where('id',$id)->delete();
                        // // ceci calcul le solde
                        // $soldeCredit = EcritureBancaire::where('societe_id',auth()->user()->societe_id)->where('id_compte_bancaire',$id_compte_bancaire)->sum('credit');
                        // $soldeDebit = EcritureBancaire::where('societe_id',auth()->user()->societe_id)->where('id_compte_bancaire',$id_compte_bancaire)->sum('debit');  
                        // $solde = $soldeCredit - $soldeDebit;
                        // CompteBancaire::where('societe_id',auth()->user()->societe_id)->where('id',$id_compte_bancaire)->update(['solde'=>$solde]);

                        // $id_activite = $id_compte_bancaire;
                        // $page = 'CompteBancaire';
                        // LogActivity::addToLog('Ecriture Bancaire (SoldeInitial) supprimée définitivement', $id_activite, $page);
                        $this->dispatch('alert',                    
                            title:'Désolé, la suppression du <strong>Solde Initial</strong> n\'etes pas autorisée ! <br> Veuillez modifier plutôt le montant svp !',
                            timer:15000,
                            icon:'info',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        ); 
                        // $this->redirect('/detail_banque?id='.$id_compte_bancaire.'&active=8&champ=1-1', navigate: true);
                    }
                    elseif($type_paiement == 'VirementInterne'){
                        
                        EcritureBancaire::where('societe_id',auth()->user()->societe_id)->where('id',$id)->delete(); 

                        // ceci calcul le solde
                        $soldeCredit = EcritureBancaire::where('societe_id',auth()->user()->societe_id)->where('id_compte_bancaire',$id_compte_bancaire)->sum('credit');
                        $soldeDebit = EcritureBancaire::where('societe_id',auth()->user()->societe_id)->where('id_compte_bancaire',$id_compte_bancaire)->sum('debit');  
                        $solde = $soldeCredit - $soldeDebit;
                        CompteBancaire::where('societe_id',auth()->user()->societe_id)->where('id',$id_compte_bancaire)->update(['solde'=>$solde]);

                        $id_activite = $id_compte_bancaire;
                        $page = 'CompteBancaire';
                        LogActivity::addToLog('Ecriture Bancaire (VirementInterne) supprimée définitivement', $id_activite, $page);
                        $this->dispatch('alert',                    
                            title:'Suppression effectuée!',
                            timer:3000,
                            icon:'success',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        ); 
                        $this->redirect('/detail_banque?id='.$id_compte_bancaire.'&active=8&champ=1-1', navigate: true);
                    }
                    elseif($type_paiement == 'ReglementClient'){
                       
                        $this->dispatch('alert',                    
                            title:'Désolé, la suppression du règlement client n\'etes pas autorisée ici ! <br> Rendez-vous au niveau de la facture !',
                            timer:10000,
                            icon:'warning',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        ); 
                    }
                    else{
                        $this->dispatch('alert',                    
                            title:'Désolé, cette valeur n\'etes pas autorisée!',
                            timer:3000,
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
                toast:false,
                showConfirmButton: true,
                position:'center',
            );  
        }        
    } 
    public function precedant(int $id){ 
        $testPrecedant = CompteBancaire::where('societe_id',auth()->user()->societe_id)->where('id','<',$id)->orderBy('id','desc')->count();
        if($testPrecedant > 0){ 
            $precedant = CompteBancaire::where('societe_id',auth()->user()->societe_id)->where('id','<',$id)->orderBy('id','desc')->first();        
            $previous = $precedant->id; 
            $this->redirect('/detail_banque?id='.$previous.'&ref='.$this->reference.'&active=8&champ=1-1', navigate: true);              
        }  
        else{
            $this->dispatch('alert',                    
                title:'Désolé, Fin enregistrements',
                timer:3000,
                icon:'warning',
                toast:true,
                showConfirmButton: false,
                position:'top-end',
            ); 
        }    
    }    
    public function suivant(int $id){    
        
        $testSuivant = CompteBancaire::where('societe_id',auth()->user()->societe_id)->where('id','>',$id)->orderBy('id','asc')->count();
        if($testSuivant > 0){
            $suivant = CompteBancaire::where('societe_id',auth()->user()->societe_id)->where('id','>',$id)->orderBy('id','asc')->first();
            $next = $suivant->id;             
            $this->redirect('/detail_banque?id='.$next.'&ref='.$this->reference.'&active=8&champ=1-1', navigate: true);                     
        }  
        else{
            $this->dispatch('alert',                    
                title:'Désolé, Fin enregistrements',
                timer:3000,
                icon:'warning',
                toast:true,
                showConfirmButton: false,
                position:'top-end',
            ); 
        } 
    }
}
