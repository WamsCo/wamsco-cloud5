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

class UpdatePaiementDivers extends Component
{
    public $id;
    public $ids = 0; // gerer ouverture update

    #[Validate('required|max:255')] 
    public $reference;

    #[Validate('required')] 
    public $date_paiement;

    #[Validate('required')] 
    public $date_valeur;

    #[Validate('required|max:255')] 
    public $libele_paiement;

    #[Validate('nullable|numeric')] 
    public $montant;

    #[Validate('nullable|max:255')] 
    public $nom_compte_bancaire;

    #[Validate('required|max:255')] 
    public $mode_reglement;

    #[Validate('nullable|max:255')]
    public $numero_cheque_virement;
    #[Validate('nullable|max:255')]
    public $emetteur;
    #[Validate('nullable|max:255')]
    public $nom_banque;
    public $sens;
    
    #[Validate('nullable|max:255')]
    public $note;     
    public $id_ecriture_bancaire;

    public $devise;
    public $created_at;
    public $updated_at;
    public $nom_user;

    public $confirmer;

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
    }
    public function render(){    
        $id = request('id'); // id Paiement Divers
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();
        $jourValid = $entite_mod[0]->validite_mod;
        $mod_banque_caisse = $entite_mod[0]->mod_banque_caisse; 
        $soldeClient = $entite_mod[0]->solde;
        if($dateJour <= $jourValid){
            if($mod_banque_caisse == 1){
                $title = 'Modifier paiement divers | WamsCo'; 
                $module = 'Gestion Banque';
                $title_fils = 'Modifier paiement divers';
                $lien = 'listing_paie_divers?active=8&champ=1-3'; 
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');
                $dateJour = date('Y-m-d');
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px'); 

                $test_paie = PaiementDiver::where('societe',auth()->user()->societe)->where('id',$id)->count();    
                if($test_paie > 0){
                    $paie = PaiementDiver::where('societe',auth()->user()->societe)->where('id',$id)->first();                 
                    $this->ids = $paie->id;
                    $this->reference = $paie->reference;
                    $this->date_paiement = $paie->date_paiement;
                    $this->date_valeur = $paie->date_valeur;
                    $this->libele_paiement = $paie->libele_paiement;
                    $this->sens = $paie->sens;
                    $debit = $paie->debit;
                    $credit = $paie->credit;
                    $this->nom_compte_bancaire = $paie->nom_compte_bancaire;
                    $this->mode_reglement = $paie->mode_reglement;
                    $this->numero_cheque_virement = $paie->numero_cheque_virement;
                    $this->emetteur =$paie->emetteur;
                    $this->nom_banque =$paie->nom_banque;
                    $this->id_ecriture_bancaire =$paie->id_ecriture_bancaire;
                    $this->note =$paie->note;

                    $this->nom_user = $paie->nom_user;
                    $this->created_at = $paie->created_at;
                    $this->updated_at = $paie->updated_at;
                    
                    if($debit > $credit){
                        $this->montant = $debit;
                    }
                    else{
                        $this->montant = $credit;
                    }
                } 
                $compteBancaire = CompteBancaire::where('societe',auth()->user()->societe)->where('etat',1)->get();
                $infos = 'Pour le versement utilisez Crédit pour enregistrer un règlement reçu.<br> Pour le retrait, utilisez Débit pour enregistrer un règlement reçu.';

                $page = 'PaiementDiver'; // Pour evenement lie
                $log = LogActivityModel::where('user_societe',auth()->user()->societe)->where('id_activite', $this->ids)->where('page', $page)->limit(11)->orderBy('id','desc')->get();
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
                return view('livewire.gestion-banque.update-paiement-divers',compact('title_fils','module','lien','dateJour','compteBancaire','infos','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));     
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
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_paie_divers;
            if($autoriser == 1){   
                if($this->ids){ 
                    PaiementDiver::find($this->ids)->update(['date_paiement'=>$this->date_paiement,'date_valeur'=>$this->date_valeur,'libele_paiement'=>$this->libele_paiement,
                    'mode_reglement'=>$this->mode_reglement,'numero_cheque_virement'=>$this->numero_cheque_virement,
                    'emetteur'=>$this->emetteur,'note'=>$this->note,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                    EcritureBancaire::where('societe',auth()->user()->societe)->where('id',$this->id_ecriture_bancaire)->update(['date_operation'=>$this->date_paiement,'date_valeur'=>$this->date_valeur,
                                            'description'=>$this->libele_paiement,'type_operation'=>$this->mode_reglement,]);

                    $id_activite = $this->ids;
                    $page = 'PaiementDiver';
                    LogActivity::addToLog('Paiement divers ['.$this->reference.'] » '.$this->libele_paiement.' modifié', $id_activite, $page);  
                    $this->dispatch('alert',                    
                        title:$this->libele_paiement.' modifié!',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );                     
                    flash ('Paiement divers <strong> ['.$this->reference.'] » '.$this->libele_paiement.'</strong> modifié')->success();                 
                    $this->redirect('/update_paie_divers?id='.$this->ids.'&active=8&champ=1-3', navigate: true); 
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
        $testPrecedant = PaiementDiver::where('societe',auth()->user()->societe)->where('id','<',$id)->orderBy('id','desc')->count();
        if($testPrecedant > 0){ 
            $precedant = PaiementDiver::where('societe',auth()->user()->societe)->where('id','<',$id)->orderBy('id','desc')->first();        
            $previous = $precedant->id; 
            $this->redirect('/update_paie_divers?id='.$previous.'&active=8&champ=1-3', navigate: true);              
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
            $this->redirect('/update_paie_divers?id='.$id.'&active=8&champ=1-3', navigate: true);  // ceci evite une erreur
        }    
    }    
    public function suivant(int $id){    
        
        $testSuivant = PaiementDiver::where('societe',auth()->user()->societe)->where('id','>',$id)->orderBy('id','asc')->count();
        if($testSuivant > 0){
            $suivant = PaiementDiver::where('societe',auth()->user()->societe)->where('id','>',$id)->orderBy('id','asc')->first();
            $next = $suivant->id;             
            $this->redirect('/update_paie_divers?id='.$next.'&active=8&champ=1-3', navigate: true);                     
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
            $this->redirect('/update_paie_divers?id='.$id.'&active=8&champ=1-3', navigate: true); // ceci evite une erreur
        } 
    } 
    public function confirmerDelete($id){  
        $this->confirmer = $id;      
    } 
    public function supprimer(){ 
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->supprimer_paie_divers;
            if($autoriser == 1){   
                if($this->ids){                   
                    // supprime le paiement Divers
                    PaiementDiver::where('societe',auth()->user()->societe)->where('id',$this->ids)->delete(); 
                    EcritureBancaire::where('societe',auth()->user()->societe)->where('id_type_paiement',$this->ids)->delete(); 
                    $page = 'PaiementDiver';
                    LogActivityModel::where('id_activite',$this->ids)->where('page',$page)->delete();

                    $id_activite = $this->ids;
                    LogActivity::addToLog('Paiement divers et Ecriture supprimés définitivement', $id_activite, $page);
                    $this->dispatch('alert',                    
                        title:'Suppression effectuée!',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    ); 
                    flash ('<strong>Paiement divers et Ecriture </strong>supprimés définitivement')->success(); 
                    $this->redirect('/listing_paie_divers?active=8&champ=1-3', navigate: true);  
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
