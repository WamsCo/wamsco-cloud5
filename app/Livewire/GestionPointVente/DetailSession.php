<?php

namespace App\Livewire\GestionPointVente;

use Livewire\Component;
use Livewire\Attributes\Validate; 
use App\Helpers\LogActivity;
use App\Models\LogActivity as LogActivityModel;
use Livewire\WithPagination;
use Livewire\WithFileUploads; 
use App\Models\Role;
use App\Models\Produit;
use App\Models\Tier;
use App\Models\Utilisateur;
use App\Models\DeviseTva;
use App\Models\Entite;
use App\Models\SessionPos;
use App\Models\Reglement;
use App\Models\factureClientEntete;
use App\Models\PosFactureClientEntete;
use App\Models\Mouvement;

// use App\Models\CompteBancaire;
// use App\Models\factureClientLigne;

class DetailSession extends Component
{
    use WithPagination;
    use WithFileUploads;
   
    public $id; 
    public $ids; 
    public $ref; //reference 
   
    public $session_id;   
    public $nom_point_vente;   
    public $date_ouverture;   
    public $date_fermeture;   
    public $solde_initial;   
    public $solde_final;
    public $solde_cloture_theorique;       
    public $etat;   
    public $nom_user;   
    public $autoriser; // pour gerer les marges
    
    public $devise;
    public $confirmer;

    public $montant = 0;
    public $note;  
    public $verifie_id;  // important pour eviter qu'une personne continue ou cloturer la session de l'autre
    
    public $created_at;
    public $updated_at;

    public $orderField = 'id'; 
    public $orderDirection = 'ASC'; 
    
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
            $autoriser = $role[0]->creer_session;
            if($autoriser == 0){
                alert()->error('Oups Désolé', 'Vous n\'êtes pas autorisé à ouvrir cette page !!!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
                $this->redirect('/bienvenue', navigate: true);
            }
        }
        else{
            alert()->error('Oups Désolé', 'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
            $this->redirect('/bienvenue', navigate: true);
        }        
        $this->date_facturation = date('Y-m-d'); 
        $this->date_echeance = date('Y-m-d'); 
    }
    public function render(){
    
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('id',auth()->user()->societe_id)->get();
        $jourValid = $entite_mod[0]->validite_mod; 
        $mod_pointe_vente = $entite_mod[0]->mod_pointe_vente; 
        $soldeClient = $entite_mod[0]->solde;
        if($dateJour <= $jourValid){
            if($mod_pointe_vente == 1){
                $title = 'Session Pos | WamsCo';
                $module = 'Gestion point vente';
                $title_fils = 'Session Pos';
                $lien = 'pos_sessions';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');      
                $dateJour = date('Y-m-d');
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');

                $id = request('id'); // id session pos
                $ref = request('ref'); // reference session pos
                
                //     // ceci au chargement de la page
                $test = SessionPos::where('societe_id',auth()->user()->societe_id)->where('id',$id)->count();    
                if($test > 0){
                    // verifie si l'user voir les autres sessions ou pas
                    $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
                    if($test > 0){
                        $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
                        $autoriser = $role[0]->voir_session_autre;
                        if($autoriser != 1){ 
                            $verifie = SessionPos::where('societe_id',auth()->user()->societe_id)->where('id',$id)->first(); 
                            $verifie_id = $verifie->user_id;
                            if($verifie_id != auth()->user()->id){                        
                                $this->dispatch('alert',                    
                                    title:'Désolé, vous ne pouvez ouvrir cette session!',
                                    timer:5000,
                                    icon:'warning',
                                    toast:true,
                                    showConfirmButton: false,
                                    position:'top-end',
                                );
                                $this->redirect('/pos_sessions?active=2&champ=2-5', navigate: true);
                            }
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

                    $compte = SessionPos::where('societe_id',auth()->user()->societe_id)->where('id',$id)->first();               
                    $this->ids = $compte->id;
                    $this->session_id = $compte->session_id;
                    $this->nom_point_vente = $compte->nom_point_vente;               
                    $this->date_ouverture = $compte->date_ouverture;
                    $this->date_fermeture = $compte->date_fermeture;
                    $this->solde_initial = $compte->solde_initial; 
                    $this->solde_final = $compte->solde_final;
                    $this->solde_cloture_theorique = $compte->solde_cloture_theorique;                
                    $this->etat = $compte->etat;
                    $this->nom_user = $compte->nom_user;                
                    $this->note = $compte->note;  
                    $this->verifie_id = $compte->user_id;

                    $this->created_at = $compte->created_at;
                    $this->updated_at = $compte->updated_at;          
                }  
                $factClient_entete = factureClientEntete::where('societe_id',auth()->user()->societe_id)->where('id_session_pos',$this->ids)->orderBy($this->orderField, $this->orderDirection)->get();
                $factClientEnteteCount = $factClient_entete->count();
                $montantTTC = $factClient_entete->sum('montant_ttc');
                $montantRecu = $factClient_entete->sum('montant_recu');
                $marge = $factClient_entete->sum('marge');
                $reste_a_percevoir = $factClient_entete->sum('reste_a_percevoir');                
               
                $mouvement = Mouvement::where('societe_id',auth()->user()->societe_id)->where('id_session_pos',$this->ids)->orderBy('id','DESC')->limit(50)->get();  
                $mouvCountAfficher = $mouvement->count();

                $page = 'SessionPos'; // Pour evenement lie
                $log = LogActivityModel::where('societe_id',auth()->user()->societe_id)->where('id_activite', $this->ids)->where('page', $page)->limit(50)->orderBy('id','desc')->get();
                $logCount = $log->count();            
                // $taxe = DeviseTva::where('societe_id',auth()->user()->societe_id)->orderBy('taux_tva','asc')->get();

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
                return view('livewire.gestion-point-vente.detail-session',compact('title_fils','module','lien','dateJour','factClient_entete','factClientEnteteCount',
                'montantTTC','montantRecu','marge','reste_a_percevoir','mouvement','mouvCountAfficher','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
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
    public function ouvertureCaisse(){
        $this->validate([            
            'montant'=>'required|numeric',                       
            'note'=>'max:100',                       
         ]); 
         $date_ouverture = date('Y-m-d H:i:s'); 
         $etat = 'En cours';   
         SessionPos::where('societe_id',auth()->user()->societe_id)->where('id',$this->ids)->update(['solde_initial'=>$this->montant,'etat'=>$etat,'date_ouverture'=>$date_ouverture,'note'=>$this->note,]);
         $id_activite = $this->ids;
         $page = 'SessionPos';
         LogActivity::addToLog('Ouverture de la caisse ('.$this->session_id.')', $id_activite, $page);
         $this->redirect('/pos?id='.$this->ids.'&ref='.$this->session_id, navigate: true);
    } 
    public function confirmerCloture($id){             
        $this->confirmer = $id;        
    } 
    public function closeSession(int $id){        
       
       $verifie = SessionPos::where('societe_id',auth()->user()->societe_id)->where('id',$id)->first(); 
       $verifie_id = $verifie->user_id;
       if($verifie_id == auth()->user()->id){       
            $test = PosFactureClientEntete::where('societe_id',auth()->user()->societe_id)->where('user_id',auth()->user()->id)->count(); 
            if($test == 0){ 
                    $etat = 'Clôturée';           
                    $date_fermeture = date('Y-m-d H:i:s');            
                    // $montant_recu = factureClientEntete::where('societe_id',auth()->user()->societe_id)->where('id_session_pos',$this->ids)->sum('montant_recu'); 
                    $montant_recu = Reglement::where('societe_id',auth()->user()->societe_id)->where('id_session_pos',$this->ids)->sum('montant_regler');
                    $solde_initial = SessionPos::where('societe_id',auth()->user()->societe_id)->where('id',$this->ids)->sum('solde_initial'); 
                    $solde_final = $montant_recu + $solde_initial;
                    SessionPos::where('societe_id',auth()->user()->societe_id)->where('id',$this->ids)->update(['solde_final'=>$solde_final,'etat'=>$etat,'date_fermeture'=>$date_fermeture,]);
                    $id_activite = $this->ids;
                    $page = 'SessionPos';
                    LogActivity::addToLog('Session ('.$this->session_id.') clôturée', $id_activite, $page);
                    $this->redirect('/detail_pos_session?id='.$this->ids.'&ref='.$this->session_id.'&active=5&champ=1-1', navigate: true);
                    $this->dispatch('alert',                    
                        title:'Session Clôturée avec succès !',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    ); 
            }
            else{
                $this->dispatch('alert',                    
                    title:'Désolé, veuillez finaliser l\'opération en cours (Point de vente) !',
                    timer:5000,
                    icon:'warning',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                );  
            }
        }
        else{
            $this->dispatch('alert',                    
                title:'Désolé, vous n\'êtes pas autorisé à clôturer cette session (vous n\'êtes pas l\'initiateur) !',
                timer:10000,
                icon:'warning',
                toast:true,
                showConfirmButton: false,
                position:'top-end',
            ); 
        }       
    }
    public function precedant(int $id){             
        $testPrecedant = SessionPos::where('societe_id',auth()->user()->societe_id)->where('id','<',$id)->orderBy('id','desc')->count();
        if($testPrecedant > 0){ 
            $precedant = SessionPos::where('societe_id',auth()->user()->societe_id)->where('id','<',$id)->orderBy('id','desc')->first();        
            $previous = $precedant->id; 
            $this->redirect('/detail_pos_session?id='.$previous.'&active=5&champ=1-1', navigate: true);                         
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
            $this->redirect('/detail_pos_session?id='.$id.'&active=5&champ=1-1', navigate: true);
        }           
    }    
    public function suivant(int $id){  
        $testSuivant = SessionPos::where('societe_id',auth()->user()->societe_id)->where('id','>',$id)->orderBy('id','asc')->count();
        if($testSuivant > 0){
            $suivant = SessionPos::where('societe_id',auth()->user()->societe_id)->where('id','>',$id)->orderBy('id','asc')->first();
            $next = $suivant->id;             
            $this->redirect('/detail_pos_session?id='.$next.'&active=5&champ=1-1', navigate: true);  
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
            $this->redirect('/detail_pos_session?id='.$id.'&active=5&champ=1-1', navigate: true);  
        }         
    }
}
