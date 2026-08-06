<?php

namespace App\Livewire\GestionPointVente\Restaurant;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
Use Carbon\Carbon;
use Livewire\Attributes\Validate; 
use App\Helpers\LogActivity;
use Livewire\WithPagination;
use App\Models\Role;
use App\Models\Produit;
use App\Models\Tier;
use App\Models\Utilisateur;
use App\Models\DeviseTva;
use App\Models\Entite;
use App\Models\RestauCommandeAttenteEntete;
use App\Models\TableRestau;
use App\Models\SessionRestau;

class CommandeAttente extends Component
{
    protected $paginationTheme = 'bootstrap';
    use WithPagination;

    public $id; 
    public $confirmer;
    public $query;
    public $parTable;     
    public $parPage = 10;  
    // public $date_debut; 
    // public $date_fin; 
    
    public $id_session_posRes; 
    public $ref_session_posRes;

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
            $autoriser = $role[0]->passe_cmd_restau;
            if($autoriser == 0){
                toast()->error('Oups Désolé', 'Vous n\'êtes pas autorisé à ouvrir cette page!')->position('top-end')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
                $this->redirect('/bienvenue', navigate: true);
            }
            else{
                // tres important
                $id_session_pos = request('id'); // id session pos restau
                $ref_session_pos = request('ref'); // reference session pos restau

                $sess = SessionRestau::where('societe',auth()->user()->societe)->where('id',$id_session_pos)->first();               
                $this->id_session_posRes = $sess->id;
                $this->ref_session_posRes = $sess->session_id;
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
        $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();
        $jourValid = $entite_mod[0]->validite_mod;
        $mod_crm = $entite_mod[0]->mod_crm;
        $soldeClient = $entite_mod[0]->solde;
        $this->activer_fidelite = $entite_mod[0]->activer_fidelite; 
        if($dateJour <= $jourValid){ 
            if($mod_crm == 1){   
                $title = 'Commande en attente | WamsCo';
                $module = 'Gestion Point vente';
                $title_fils = 'Commande en attente';
                $lien = 'cmd_attente';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');
                $dateJour = date('Y-m-d');
                
                $aller = -2;
                $id = $this->id_session_posRes; 
                $ref = $this->ref_session_posRes;                
                                

                $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
                $autoriser = $role[0]->voir_cmd_autre_restau;
                if($autoriser == 1){ 
                    $cmd = RestauCommandeAttenteEntete ::where('societe',auth()->user()->societe)->where('ref_session_pos',$this->ref_session_posRes)->where('nom_client','like','%'.$this->query.'%')->where('nom_table','like','%'.$this->parTable.'%')->orderBy('id','asc')->paginate($this->parPage);
                }
                else{ 
                    $cmd = RestauCommandeAttenteEntete ::where('user_id',auth()->user()->id)->where('societe',auth()->user()->societe)->where('ref_session_pos',$this->ref_session_posRes)->where('nom_client','like','%'.$this->query.'%')->where('nom_table','like','%'.$this->parTable.'%')->orderBy('id','asc')->paginate($this->parPage);
                }                
                $cmdCount = $cmd->count();
                $montantTTC = $cmd->sum('montant_ttc');
                $montantRecu = $cmd->sum('montant_recu');
                $montantRemise = $cmd->sum('montant_remise');
               

                $user = Utilisateur::where('societe',auth()->user()->societe)->orderBy('name','asc')->get(); 

                $deviseTva = DeviseTva :: where('societe',auth()->user()->societe)->limit(1)->orderBy('id','asc')->count();             
                if($deviseTva == 0){
                    $this->devise = 'FCFA';
                }
                else{
                    $deviseTva = DeviseTva :: where('societe',auth()->user()->societe)->limit(1)->orderBy('id','asc')->get(); 
                    $this->devise = $deviseTva[0]->devise;                
                }  
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');            
                $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get(); 
                $jourValid = $entite_mod[0]->validite_mod; 
                // ceci pour trouver le nombre de jour restant avant expiration
                $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24));           
                    return view('livewire.gestion-point-vente.restaurant.commande-attente',compact('title_fils','module','lien','dateJour','cmd','cmdCount','montantTTC','montantRecu','montantRemise','user'))->layout('components.layouts.app_pos',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant','aller','id','ref'));
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
                return view('livewire.bienvenue',compact('dateJour','entite_mod'))->layout('components.layouts.app',compact('title','module','title_fils','lien','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));          
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
            return view('livewire.bienvenue',compact('dateJour','entite_mod'))->layout('components.layouts.app',compact('title','module','title_fils','lien','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));          
        }
    }
    public function Tables(int $id_session_posRes,string $ref_session_posRes){
        $this->redirect('/pipeline_restau?id='.$this->id_session_posRes.'&ref='.$this->ref_session_posRes.'&active=5&champ=2-1', navigate: true);
    }
    public function CmdAttente(int $id_session_posRes,string $ref_session_posRes){
        $this->redirect('/cmd_attente?id='.$this->id_session_posRes.'&ref='.$this->ref_session_posRes.'&active=5&champ=2-1', navigate: true);
    }
    public function backSession(int $id_session_posRes,string $ref_session_posRes){
        $this->redirect('/detail_restau_session?id='.$this->id_session_posRes.'&ref='.$this->ref_session_posRes.'&active=5&champ=2-1&choix=1', navigate: true);
    }
    public function reprendreCmd(string $ref_table, string $nom_table, string $id_session_pos, string $ref_session_pos){
            
        $this->redirect('/pos_restau?id='.$ref_table.'&table='.$nom_table.'&id_session_restau='.$id_session_pos.'&ref_session_restau='.$ref_session_pos.'&active=14&champ=1-1', navigate: true);
    }
}
