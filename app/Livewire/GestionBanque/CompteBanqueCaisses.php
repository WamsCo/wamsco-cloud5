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
use App\Models\CompteBancaire;
use App\Models\EcritureBancaire;


class CompteBanqueCaisses extends Component
{
    protected $paginationTheme = 'bootstrap';
    use WithPagination;

    public $id;
    
    public $confirmer;
    public $query;
    public $parPage = 20;
    public $orderField = 'nom_compte_bancaire'; 
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
                $title = 'Comptes bancaires | WamsCo'; 
                $module = 'Gestion Banque';
                $title_fils = 'Comptes bancaires';
                $lien = 'banque_caisse'; 
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');
                $dateJour = date('Y-m-d');
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');           
                $banque = CompteBancaire :: where('societe_id',auth()->user()->societe_id)->where('nom_compte_bancaire','like','%'.$this->query.'%')->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage); 
                $banqueCount = $banque->count(); 
                
                $resultat = CompteBancaire::where('societe_id',auth()->user()->societe_id)->get();  
                $nbreTotalCompteBancaire = $resultat->count();     
                $nbreTotalCompteBancaireActif = $resultat->where('etat',1)->count();     

                $derniereActivite = CompteBancaire::where('societe_id',auth()->user()->societe_id)->latest('updated_at')->first();
                
                $page = 'CompteBancaire'; // pour evenement lies
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
                return view('livewire.gestion-banque.compte-banque-caisses',compact('title_fils','module','lien','dateJour','banque','banqueCount','nbreTotalCompteBancaire','nbreTotalCompteBancaireActif','derniereActivite','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));     
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
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_compte;
            if($autoriser == 1){   

                    // creation et enregistrement auto Banque ou caisse
                    $reference = date('YmdHis');
                    $nom_compte_bancaire = 'Nom du compte';
                    $type_compte = 'Compte caisse/liquide';
                    $solde = 0;
                    $nom_banque = '';
                    $num_compte = '';
                    $nom_proprietaire = '';
                    $etat = 0;
                    $cpteBanq = CompteBancaire::create(['reference'=>$reference,'nom_compte_bancaire'=>$nom_compte_bancaire,'type_compte'=>$type_compte,'solde'=>$solde,
                    'nom_banque'=>$nom_banque,'num_compte'=>$num_compte,'nom_proprietaire'=>$nom_proprietaire,'etat'=>$etat,'societe'=>auth()->user()->societe,
                    'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                    // ceci recupere le dernier enregistrement cree a l'instant
                    $dernier_id = $cpteBanq->id; 

                    // Creation en enregistrement ecriture bancaire                    
                    $description = '(Solde initial)';
                    $date_operation = date('Y-m-d');
                    $date_valeur = date('Y-m-d');
                    $type_operation = 'Espèce';
                    $debit = 0;
                    $credit = 0;
                    $type_paiement = 'SoldeInitial';
                    EcritureBancaire::create(['id_compte_bancaire'=>$dernier_id,'id_type_paiement'=>$dernier_id,'nom_compte_bancaire'=>$nom_compte_bancaire,'reference'=>$reference,'description'=>$description,'date_operation'=>$date_operation,'date_valeur'=>$date_valeur,
                    'debit'=>$debit,'credit'=>$credit,'solde'=>$solde,'type_paiement'=>$type_paiement,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                    
                   
                    $page = 'CompteBancaire'; 
                    $id_activite = $dernier_id;
                    LogActivity::addToLog('Compte Bancaire » '.$nom_compte_bancaire.' créé', $id_activite, $page); 
                    $this->dispatch('alert',                    
                        title:'Banque/Caisse ('.$nom_compte_bancaire.') enregistré!',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );  
                    // $this->resetinputFields();  
                    $this->redirect('/detail_banque?id='.$dernier_id.'&active=8&champ=1-1', navigate: true);  
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
    public function changeEtat(int $id, int $etat){
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_compte; 
            if($autoriser == 1){         
                if($etat == 1){
                    $ferme = 0;
                    CompteBancaire::find($id)->update(['etat'=>$ferme,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                    $id_activite = $id;
                    $page = 'CompteBancaire';
                    LogActivity::addToLog('Etat compte (Fermé)', $id_activite, $page);  
                    $this->dispatch('alert',                    
                        title:'Compte fermé avec succès!',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );  
                }
                elseif($etat == 0){
                    $ouvert = 1;
                    CompteBancaire::find($id)->update(['etat'=>$ouvert,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                    $id_activite = $id;
                    $page = 'CompteBancaire';
                    LogActivity::addToLog('Etat compte (ouvert)', $id_activite, $page); 
                    $this->dispatch('alert',                    
                        title:'Compte ouvert avec succès!',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );    
                }
                else{
                    $this->dispatch('alert',                    
                        title:'Vous ne pouvez modifier cet état ici!',
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
    public function supprimer($id){ 
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->supprimer_compte;
            if($autoriser == 1){   
                if($id){
                    
                    $verifierEcriture = EcritureBancaire::where('societe_id',auth()->user()->societe_id)->where('id_compte_bancaire',$id)->count();
                    if($verifierEcriture == 0){

                        $cpte = CompteBancaire::where('societe_id',auth()->user()->societe_id)->where('id',$id)->first();
                        $test_solde = $cpte->solde;
                        $etat = $cpte->etat;
                        if($test_solde == 0 && $etat == 0){ 
                            
                                // suppression definitive
                                $page = 'CompteBancaire';
                                CompteBancaire::where('id',$id)->delete(); 
                                LogActivityModel::where('id_activite',$id)->where('page',$page)->delete();

                                $id_activite = $id;
                                LogActivity::addToLog('Compte Bancaire supprimé définitivement', $id_activite, $page);
                                $this->dispatch('alert',                    
                                    title:'Compte bancaire supprimé avec succes!',
                                    timer:5000,
                                    icon:'success',
                                    toast:true,
                                    showConfirmButton: false,
                                    position:'top-end',
                                );                            
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
                            title:'Désolé, des <strong> écritures sont liées </strong> à ce compte. <br/> Vous ne pouvez supprimer !',
                            timer:6000,
                            icon:'warning',
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
