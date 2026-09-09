<?php

namespace App\Livewire\Administration;

use Livewire\Component;
use Livewire\Attributes\Validate;
Use Carbon\Carbon; 
use App\Helpers\LogActivity;
use App\Models\LogActivity as LogActivityModel;
use Livewire\WithPagination;
// use Livewire\WithFileUploads; 
use App\Models\Role;
use App\Models\Entrepot;
// use App\Models\Categorie;
use App\Models\Produit;
use App\Models\Tier;
use App\Models\Utilisateur;
use App\Models\DeviseTva;
use App\Models\Entite;
use App\Models\Stock;
use App\Models\TransfertFiliale;
use App\Models\TransfertFilialeLigne;


class TransfertStockFiliale extends Component
{
    protected $paginationTheme = 'bootstrap';    // ceci pour pagination avec les chiffres tres important
    use WithPagination;

    public $parEntrepot_orig; // id
    public $parEntrepot_desti; // id
    public $parEtiquette; 

    public $date_debut; 
    public $date_fin;
    
    public $query;
    public $parPage = 20;
    public $confirmer;
    public $orderField = 'id'; 
    public $orderDirection = 'DESC'; 
    
    // public $date_sortie;
    // public $date_entree;      
    // public $nombre_paquets;    
    // public $etiquette_transfert;  

     #[Validate('required')]   
    public $filiale;

    #[Validate('required')] 
    public $entrepot_origine; 

    #[Validate('required')] 
    public $entrepot_destination; 

    #[Validate('required')] 
    public $date_sortie; 

    #[Validate('required')]     
    public $date_entree;  
    
    public $transporteur;

    #[Validate('required|numeric')]
    public $nombre_paquets; 
   
    public $code_inventaire;  

    #[Validate('required')]   
    public $etiquette_transfert;     

    public $note;  
    public $etat; 

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
            $autoriser = $role[0]->consulter_transfert_filiale;
            if($autoriser == 0){
                alert()->error('Oups Désolé', 'Vous n\'êtes pas autorisé à ouvrir cette page !!!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
                $this->redirect('/bienvenue', navigate: true);
            }
        }
        else{
            alert()->error('Oups Désolé', 'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
            $this->redirect('/bienvenue', navigate: true);
        }
        $this->date_debut = date('Y-m-d', strtotime('-2 year'));  // ceci pour affiche toutes les sessions en permanance sur 1 mois par defaut
        $this->date_fin = date('Y-m-d'); 

        $this->date_sortie = date('Y-m-d');
        $this->date_entree = date('Y-m-d');
        $this->nombre_paquets = 1;
        $this->etiquette_transfert = 'Transfert de stock '.date('Y-m-d H:i');
    }

    public function render()
    {
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('id',auth()->user()->societe_id)->get();
        $jourValid = $entite_mod[0]->validite_mod; 
        $mod_multisociete = $entite_mod[0]->mod_multisociete;
        $soldeClient = $entite_mod[0]->solde;
        if($dateJour <= $jourValid){
            if($mod_multisociete == 1){
                $title = 'Listing transferts entre filiale | WamsCo';
                $module = 'Multi-société';
                $title_fils = 'Transfert entre filiale';
                $lien = 'transfert_filiale';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');      
                $dateJour = date('Y-m-d');
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px'); 

                $start = Carbon::parse($this->date_debut)->startOfDay(); //2016-09-29 00:00:00.000000
                $end = Carbon::parse($this->date_fin)->endOfDay();     // 2016-09-29 23:59:59.000000

                if(empty($this->parEntrepot_orig) && empty($this->parEntrepot_desti)){
                    $transfert = TransfertFiliale::where('societe_id',auth()->user()->societe_id)->where('id','like','%'.$this->query.'%')->where('etiquette_transfert','like','%'.$this->parEtiquette.'%')->whereBetween('created_at',[$start, $end])->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage);
                }
                elseif(!empty($this->parEntrepot_orig) && empty($this->parEntrepot_desti)){
                    $transfert = TransfertFiliale::where('societe_id',auth()->user()->societe_id)->where('id','like','%'.$this->query.'%')->where('etiquette_transfert','like','%'.$this->parEtiquette.'%')->where('id_entrepot_origine',$this->parEntrepot_orig)->whereBetween('created_at',[$start, $end])->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage);
                }
                elseif(empty($this->parEntrepot_orig) && empty(!$this->parEntrepot_desti)){
                    $transfert = TransfertFiliale::where('societe_id',auth()->user()->societe_id)->where('id','like','%'.$this->query.'%')->where('etiquette_transfert','like','%'.$this->parEtiquette.'%')->where('id_entrepot_destination',$this->parEntrepot_desti)->whereBetween('created_at',[$start, $end])->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage);
                }
                else{
                    $transfert = TransfertFiliale::where('societe_id',auth()->user()->societe_id)->where('id','like','%'.$this->query.'%')->where('etiquette_transfert','like','%'.$this->parEtiquette.'%')->where('id_entrepot_origine',$this->parEntrepot_orig)->where('id_entrepot_destination',$this->parEntrepot_desti)->whereBetween('created_at',[$start, $end])->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage);
                }
                $transfertCount = $transfert->count();  
                $listedeviseTva = DeviseTva :: where('societe_id',auth()->user()->societe_id)->get(); 
                $listEntrepot = Entrepot::where('societe_mere',auth()->user()->societe_mere)->where('active',1)->orderBy('nom','asc')->get(); 
                                
                $resultat = TransfertFiliale::where('societe_id',auth()->user()->societe_id)->get();  
                $nbreTotalTransfert = $resultat->count();     

                $derniereActivite = TransfertFiliale::where('societe_id',auth()->user()->societe_id)->latest('updated_at')->first();

                $listEntrepotOrigine = Entrepot::where('societe_id',auth()->user()->societe_id)->where('active',1)->orderBy('nom','asc')->get(); 
                $listEntrepotDestination = Entrepot::where('societe',$this->filiale)->where('active',1)->orderBy('nom','asc')->get();
                $liste_entit = Entite::where('societe_mere',auth()->user()->societe_mere)->where('active',1)->get();           
                // $listeProduit = Produit::where('societe',$this->filiale)->where('etat',1)->orderBy('nom_produit','asc')->get();  
                
                $page = 'TransfertFiliale'; // pour evenement lies
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
                return view('livewire.administration.transfert_filiale.transfert-stock-filiale',compact('title_fils','module','lien','dateJour','transfert','transfertCount','listedeviseTva','listEntrepot','nbreTotalTransfert','derniereActivite',
                'listEntrepotOrigine','listEntrepotDestination','liste_entit','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
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
        $this->validate();             
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->transfer_stock_filiale;
            if($autoriser == 1){                  
                                        
                    $entrepot =  Entrepot::where('societe_id',auth()->user()->societe_id)->where('id',$this->entrepot_origine)->first();
                    $nom_entrepot_origine = $entrepot->nom;

                    $entrepot =  Entrepot::where('societe',$this->filiale)->where('id',$this->entrepot_destination)->first();
                    $nom_entrepot_destination = $entrepot->nom;

                    $this->etat = 'Brouillon';
                    $tranfert = TransfertFiliale::create(['filiale_envoi'=>auth()->user()->societe,'filiale_reception'=>$this->filiale,'entrepot_origine'=>$nom_entrepot_origine,'id_entrepot_origine'=>$this->entrepot_origine,
                    'entrepot_destination'=>$nom_entrepot_destination,'id_entrepot_destination'=>$this->entrepot_destination,
                    'date_sortie'=>$this->date_sortie,'date_entree'=>$this->date_entree,'transporteur'=>$this->transporteur,'nombre_paquets'=>$this->nombre_paquets,'code_inventaire'=>$this->code_inventaire,
                    'etiquette_transfert'=>$this->etiquette_transfert,'note'=>$this->note,'etat'=>$this->etat,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                   
                    // ceci recupere le dernier enregistrement cree a l'instant
                    $dernier_id = $tranfert->id;
                    $id_activite = $dernier_id;
                    $page = 'TransfertFiliale';
                    LogActivity::addToLog('Entête transfert De » '.$nom_entrepot_origine.' ('.auth()->user()->societe.') Vers » '.$nom_entrepot_destination.' ('.$this->filiale.') créé', $id_activite, $page);
                    $this->dispatch('alert',                    
                        title:'Veuillez ajouter les produits à transferer!',
                        timer:5000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );  
                    flash ('Entête transfert De » <strong>'.$nom_entrepot_origine.' ('.auth()->user()->societe.') Vers » '.$nom_entrepot_destination.' ('.$this->filiale.')</strong> créé!')->success();  
                    $this->redirect('/detail_transfert_filiale?id='.$dernier_id.'&active=11&champ=1-3', navigate: true);  
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
            $autoriser = $role[0]->supprimer_transfert;
            if($autoriser == 1){  
                if($id){ 
                    TransfertFiliale::where('id',$id)->delete();
                    TransfertFilialeLigne::where('id_transfert',$id)->delete();
                    $page = 'TransfertFiliale';
                    LogActivityModel::where('id_activite',$id)->where('page',$page)->delete();

                    $id_activite = $id;
                    LogActivity::addToLog('Transfert filiale supprimé définitivement', $id_activite, $page);
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
