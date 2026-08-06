<?php

namespace App\Livewire\GestionBanque;

use Livewire\Component;
use Livewire\Attributes\Validate; 
Use Carbon\Carbon;
use Livewire\WithPagination;
use App\Helpers\LogActivity;
use App\Models\LogActivity as LogActivityModel;
use App\Models\Entite;
use App\Models\Role;
use App\Models\CompteBancaire;
use App\Models\EcritureBancaire;
use App\Models\PaiementDiver;
use App\Models\DeviseTva;

class EcritureBancaireCaisses extends Component
{
    protected $paginationTheme = 'bootstrap';
    use WithPagination;

    public $id;    
    public $confirmer;
    public $query;
    public $parPage = 20;
    public $parCompte;
    
    public $orderField = 'id'; 
    public $orderDirection = 'DESC'; 

    public $devise;

    public $date_debut; 
    public $date_fin;

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
            $autoriser = $role[0]->consulter_ecriture;
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
        $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();
        $jourValid = $entite_mod[0]->validite_mod;
        $mod_banque_caisse = $entite_mod[0]->mod_banque_caisse;  
        $soldeClient = $entite_mod[0]->solde;
        if($dateJour <= $jourValid){
            if($mod_banque_caisse == 1){
                $title = 'Écritures bancaires | WamsCo'; 
                $module = 'Gestion Banque';
                $title_fils = 'Écritures bancaires';
                $lien = 'banque_caisse'; 
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');
                $dateJour = date('Y-m-d');
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');

                $start = Carbon::parse($this->date_debut)->startOfDay(); //2016-09-29 00:00:00.000000
                $end = Carbon::parse($this->date_fin)->endOfDay();     // 2016-09-29 23:59:59.000000   
                if(empty($this->parCompte) && empty($this->query)){
                    $ecriture = EcritureBancaire :: where('societe',auth()->user()->societe)->whereBetween('created_at',[$start, $end])->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage); 
                } 
                elseif(!empty($this->parCompte) && empty($this->query)){
                    $ecriture = EcritureBancaire :: where('societe',auth()->user()->societe)->where('id_compte_bancaire', $this->parCompte)->whereBetween('created_at',[$start, $end])->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage); 
                }
                elseif(empty($this->parCompte) && !empty($this->query)){
                    $ecriture = EcritureBancaire :: where('societe',auth()->user()->societe)->where('tiers','like','%'.$this->query.'%')->whereBetween('created_at',[$start, $end])->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage);   
                } 
                else{
                    $ecriture = EcritureBancaire :: where('societe',auth()->user()->societe)->where('id_compte_bancaire', $this->parCompte)->where('tiers','like','%'.$this->query.'%')->whereBetween('created_at',[$start, $end])->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage);   
                }     
                $ecritureCount = $ecriture->count();
                
                // Totaux solde
                $soldeDebit = $ecriture->sum('debit');
                $soldeCredit = $ecriture->sum('credit');             
                $solde = $soldeCredit - $soldeDebit; 
                
                $compteBanq = CompteBancaire::where('societe',auth()->user()->societe)->where('etat',1)->orderBy('nom_compte_bancaire', 'ASC')->get(); 

                // KPI
                $resultat = EcritureBancaire :: where('societe',auth()->user()->societe)->get();
                $TotalDebit = $resultat->sum('debit'); 
                $TotalCredit = $resultat->sum('credit');
                $soldeTotal = $TotalCredit - $TotalDebit; 
                // Fin KPI

                $resultat = EcritureBancaire::where('societe',auth()->user()->societe)->get();  
                $nbreTotalEcritureBancaire = $resultat->count();     

                $derniereActivite = EcritureBancaire::where('societe',auth()->user()->societe)->latest('updated_at')->first();
                
                $page = 'EcritureBancaire'; // pour evenement lies
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
                return view('livewire.gestion-banque.ecriture-bancaire-caisses',compact('title_fils','module','lien','dateJour','ecriture','ecritureCount','soldeDebit','soldeCredit','solde','compteBanq','TotalDebit','TotalCredit','soldeTotal',
                'nbreTotalEcritureBancaire','derniereActivite','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));     
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
    public function confirmerDelete($id){    

        $this->confirmer = $id;      
    } 
    public function supprimer($id){ 
        
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->supprimer_compte;
            if($autoriser == 1){   
                    if($id){                   
                        // supprime le paiement Divers
                        // EcritureBancaire::where('societe',auth()->user()->societe)->where('id',$id)->delete(); 

                        $compte = EcritureBancaire::where('societe',auth()->user()->societe)->where('id',$id)->first();               
                        $id_compte_bancaire = $compte->id_compte_bancaire;
                        $type_paiement = $compte->type_paiement;
                        
                        if($type_paiement == 'PaiementDivers'){

                            EcritureBancaire::where('societe',auth()->user()->societe)->where('id',$id)->delete(); 
                            PaiementDiver::where('societe',auth()->user()->societe)->where('id_ecriture_bancaire',$id)->delete(); 

                             // ceci calcul le solde
                            $soldeCredit = EcritureBancaire::where('societe',auth()->user()->societe)->where('id_compte_bancaire',$id_compte_bancaire)->sum('credit');
                            $soldeDebit = EcritureBancaire::where('societe',auth()->user()->societe)->where('id_compte_bancaire',$id_compte_bancaire)->sum('debit');  
                            $solde = $soldeCredit - $soldeDebit;
                            CompteBancaire::where('societe',auth()->user()->societe)->where('id',$id_compte_bancaire)->update(['solde'=>$solde]);
                           
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
                        }
                        elseif($type_paiement == 'SoldeInitial'){

                            EcritureBancaire::where('societe',auth()->user()->societe)->where('id',$id)->delete(); 

                            // ceci calcul le solde
                            $soldeCredits = EcritureBancaire::where('societe',auth()->user()->societe)->where('id_compte_bancaire',$id_compte_bancaire)->sum('credit');
                            $soldeDebits = EcritureBancaire::where('societe',auth()->user()->societe)->where('id_compte_bancaire',$id_compte_bancaire)->sum('debit');  
                            $soldes = $soldeCredits - $soldeDebits;
                            CompteBancaire::where('societe',auth()->user()->societe)->where('id',$id_compte_bancaire)->update(['solde'=>$soldes]);
                            
                            $id_activite = $id_compte_bancaire;
                            $page = 'CompteBancaire';
                            LogActivity::addToLog('Ecriture Bancaire (SoldeInitial) supprimée définitivement', $id_activite, $page);
                            $this->dispatch('alert',                    
                                title:'Suppression effectuée!',
                                timer:3000,
                                icon:'success',
                                toast:true,
                                showConfirmButton: false,
                                position:'top-end',
                            ); 
                        }
                        elseif($type_paiement == 'VirementInterne'){

                            EcritureBancaire::where('societe',auth()->user()->societe)->where('id',$id)->delete(); 

                            // ceci calcul le solde
                            $soldeCredit = EcritureBancaire::where('societe',auth()->user()->societe)->where('id_compte_bancaire',$id_compte_bancaire)->sum('credit');
                            $soldeDebit = EcritureBancaire::where('societe',auth()->user()->societe)->where('id_compte_bancaire',$id_compte_bancaire)->sum('debit');  
                            $solde = $soldeCredit - $soldeDebit;
                            CompteBancaire::where('societe',auth()->user()->societe)->where('id',$id_compte_bancaire)->update(['solde'=>$solde]);

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
                        }
                        elseif($type_paiement == 'ReglementClient'){

                            // la suppression de Reglement ne peut etre effectuer ici: id reglement ne se trouve pas dans EcritureBancaire

                            // EcritureBancaire::where('societe',auth()->user()->societe)->where('id',$id)->delete(); 
                            // Reglement::where('id',$id)->delete(); // ne peut etre effectuer ici id reglement ne se trouve pas dans EcritureBancaire                            
                            // // ceci calcul le solde
                            // $soldeCredit = EcritureBancaire::where('societe',auth()->user()->societe)->where('id_compte_bancaire',$id_compte_bancaire)->sum('credit');
                            // $soldeDebit = EcritureBancaire::where('societe',auth()->user()->societe)->where('id_compte_bancaire',$id_compte_bancaire)->sum('debit');  
                            // $solde = $soldeCredit - $soldeDebit;
                            // CompteBancaire::where('societe',auth()->user()->societe)->where('id',$id_compte_bancaire)->update(['solde'=>$solde]);

                           $this->dispatch('alert',                    
                               title:'Désolé, la suppression du règlement client n\'est pas autorisée ici ! <br> Rendez-vous au niveau de la facture!',
                               timer:10000,
                               icon:'warning',
                               toast:true,
                               showConfirmButton: false,
                               position:'top-end',
                           ); 
                        }
                        elseif($type_paiement == 'ReglementFournisseur'){

                            // la suppression de Reglement ne peut etre effectuer ici: id reglement ne se trouve pas dans EcritureBancaire

                            // EcritureBancaire::where('societe',auth()->user()->societe)->where('id',$id)->delete(); 
                            // Reglement::where('id',$id)->delete(); // ne peut etre effectuer ici id reglement fournisseur ne se trouve pas dans EcritureBancaire                            
                            // // ceci calcul le solde
                            // $soldeCredit = EcritureBancaire::where('societe',auth()->user()->societe)->where('id_compte_bancaire',$id_compte_bancaire)->sum('credit');
                            // $soldeDebit = EcritureBancaire::where('societe',auth()->user()->societe)->where('id_compte_bancaire',$id_compte_bancaire)->sum('debit');  
                            // $solde = $soldeCredit - $soldeDebit;
                            // CompteBancaire::where('societe',auth()->user()->societe)->where('id',$id_compte_bancaire)->update(['solde'=>$solde]);

                           $this->dispatch('alert',                    
                               title:'Désolé, la suppression du règlement fournisseur n\'est pas autorisée ici ! <br> Rendez-vous au niveau de la facture!',
                               timer:10000,
                               icon:'warning',
                               toast:true,
                               showConfirmButton: false,
                               position:'top-end',
                           ); 
                        }
                        elseif($type_paiement == 'ReglementCommercial'){

                            // la suppression de Reglement ne peut etre effectuer ici: id reglement ne se trouve pas dans EcritureBancaire

                            // EcritureBancaire::where('societe',auth()->user()->societe)->where('id',$id)->delete(); 
                            // Reglement::where('id',$id)->delete(); // ne peut etre effectuer ici id reglement fournisseur ne se trouve pas dans EcritureBancaire                            
                            // // ceci calcul le solde
                            // $soldeCredit = EcritureBancaire::where('societe',auth()->user()->societe)->where('id_compte_bancaire',$id_compte_bancaire)->sum('credit');
                            // $soldeDebit = EcritureBancaire::where('societe',auth()->user()->societe)->where('id_compte_bancaire',$id_compte_bancaire)->sum('debit');  
                            // $solde = $soldeCredit - $soldeDebit;
                            // CompteBancaire::where('societe',auth()->user()->societe)->where('id',$id_compte_bancaire)->update(['solde'=>$solde]);

                           $this->dispatch('alert',                    
                               title:'Désolé, la suppression du règlement commercial n\'est pas autorisée ici ! <br> Rendez-vous au niveau de l\'entité!',
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
