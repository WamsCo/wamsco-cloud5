<?php

namespace App\Livewire\GestionPointVente;

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
use App\Models\SessionPos;
// use App\Models\CompteBancaire;
// use App\Models\factureClientEntete;
// use App\Models\factureClientLigne;

class Sessions extends Component
{
    protected $paginationTheme = 'bootstrap';
    use WithPagination;

    public $id; 
    public $confirmer;
    public $query;
    public $parUser;    
    public $parPage = 20; 
    public $date_debut; 
    public $date_fin;      

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
            $autoriser = $role[0]->consulter_session_pv;
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
        $mod_pointe_vente = $entite_mod[0]->mod_pointe_vente; 
        $soldeClient = $entite_mod[0]->solde;
        if($dateJour <= $jourValid){
            if($mod_pointe_vente == 1){
                $title = 'Point de vente | WamsCo';
                $module = 'Gestion Point de vente';
                $title_fils = 'Point de vente';
                $lien = 'pos_sessions';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');      
                $dateJour = date('Y-m-d');
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');

                $start = Carbon::parse($this->date_debut)->startOfDay(); //2016-09-29 00:00:00.000000           
                $end = Carbon::parse($this->date_fin)->endOfDay();     // 2016-09-29 23:59:59.000000

                $utilisat = Utilisateur::where('societe',auth()->user()->societe)->orderBy('name','asc')->get();  
                
                if(empty($this->parUser)){
                    $session = SessionPos::where('societe',auth()->user()->societe)->where('nom_user','like','%'.$this->query.'%')->whereBetween('created_at',[$start, $end])->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage);
                }
                else{
                    $session = SessionPos::where('societe',auth()->user()->societe)->where('nom_user','like','%'.$this->query.'%')->where('user_id', $this->parUser)->whereBetween('created_at',[$start, $end])->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage);
                }
                $sessionCount = $session->count();  
                                    
                $deviseTva = DeviseTva :: where('societe',auth()->user()->societe)->limit(1)->orderBy('id','asc')->count(); 
                if($deviseTva == 0){
                    $this->devise = 'FCFA';
                }
                else{
                    $deviseTva = DeviseTva :: where('societe',auth()->user()->societe)->limit(1)->orderBy('id','asc')->get(); 
                    $this->devise = $deviseTva[0]->devise;
                }

                $resultat = SessionPos::where('societe',auth()->user()->societe)->get();  
                $nbreTotalSessionPos = $resultat->count();     

                $derniereActivite = SessionPos::where('societe',auth()->user()->societe)->latest('updated_at')->first();

                $page = 'SessionPos'; // pour evenement lies
                $log = LogActivityModel::where('user_societe',auth()->user()->societe)->where('page', $page)->limit(50)->orderBy('id','desc')->get();
                $logCount = $log->count();

                $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();          
                $jourValid = $entite_mod[0]->validite_mod; 
                // ceci pour trouver le nombre de jour restant avant expiration
                $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24));
                return view('livewire.gestion-point-vente.sessions',compact('title_fils','module','lien','dateJour','session','sessionCount','utilisat','nbreTotalSessionPos','derniereActivite','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
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
            $autoriser = $role[0]->creer_session;
            if($autoriser == 1){   
                
                $countSession = SessionPos::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->count(); 
                if($countSession == 0){  
                     // creation et enregistrement auto Pos session 
                     $nom_point_vente = auth()->user()->societe;              
                     // $date_ouverture = date('Y-m-d');
                     // $date_fermeture = date('Y-m-d');
                     $solde_initial = 0;
                     $solde_final = 0;
                     $solde_cloture_theorique = 0;         
                     $etat = 'Contrôle à l\'ouverture';           
                     $dates = date('dmy');
                     $length = 2;
                     $token = bin2hex(random_bytes($length));
                     $token_ok = 'SES/'.$dates.'/'.$token;
                     // $token_ok = 'POS/'.$dates;
                     SessionPos :: create(['session_id'=>$token_ok,'nom_point_vente'=>$nom_point_vente,'solde_initial'=>$solde_initial,
                                 'solde_final'=>$solde_final,'solde_cloture_theorique'=>$solde_cloture_theorique,'etat'=>$etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
 
                     // ceci recupere le dernier enregistrement cree a l'instant
                     $dernier_id = SessionPos::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id; 
                     $id_activite = $dernier_id;
                     $page = 'SessionPos';
                     LogActivity::addToLog('Session '.$token_ok.' POS créé', $id_activite, $page);
                     $this->dispatch('alert',                    
                         title:'Nouvelle Session enregistrée!',
                         timer:3000,
                         icon:'success',
                         toast:true,
                         showConfirmButton: false,
                         position:'top-end',
                     );             
                     $this->redirect('/detail_pos_session?id='.$dernier_id.'&ref='.$token_ok.'&active=5&champ=1-1', navigate: true);
                }
                else{
                    
                    $dernier_id = SessionPos::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id; 
                    $test_session = SessionPos::where('societe',auth()->user()->societe)->where('id',$dernier_id)->first();
                    $etat = $test_session->etat;  
                    if($etat == 'Clôturée'){
    
                        // creation et enregistrement auto Pos session               
                        $nom_point_vente = auth()->user()->societe;              
                        // $date_ouverture = date('Y-m-d');
                        // $date_fermeture = date('Y-m-d');
                        $solde_initial = 0;
                        $solde_final = 0;
                        $solde_cloture_theorique = 0;         
                        $etat = 'Contrôle à l\'ouverture';           
    
                        $dates = date('dmy');
                        $length = 2;
                        $token = bin2hex(random_bytes($length));
                        $token_ok = 'SES/'.$dates.'/'.$token;
                        // $token_ok = 'POS/'.$dates;
                        SessionPos :: create(['session_id'=>$token_ok,'nom_point_vente'=>$nom_point_vente,'solde_initial'=>$solde_initial,
                                    'solde_final'=>$solde_final,'solde_cloture_theorique'=>$solde_cloture_theorique,'etat'=>$etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
    
                                // ceci recupere le dernier enregistrement cree a l'instant
                        $dernier_id = SessionPos::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id; 
                        $id_activite = $dernier_id;
                        $page = 'SessionPos';
                        LogActivity::addToLog('Session '.$token_ok.' POS créé', $id_activite, $page);
                        $this->dispatch('alert',                    
                            title:'Nouvelle Session enregistrée!',
                            timer:3000,
                            icon:'success',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        );             
                        $this->redirect('/detail_pos_session?id='.$dernier_id.'&ref='.$token_ok.'&active=5&champ=1-1', navigate: true);  
                    }
                    else {
                        $this->dispatch('alert',                    
                            title:'Désolé, veuillez <strong>clôturer</strong> votre session en cours!',
                            timer:5000,
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
