<?php

namespace App\Livewire\Tiers;

use Livewire\Component;
use Livewire\Attributes\Validate; 
use Livewire\WithPagination;
use App\Helpers\LogActivity;
use App\Models\LogActivity as LogActivityModel;
use App\Models\Tier;
use App\Models\Role;
use App\Models\Utilisateur;
use App\Models\Entite;
use App\Models\DeviseTva;
use App\Models\Opportunite;

class Tiers extends Component
{
    protected $paginationTheme = 'bootstrap';
    use WithPagination;

    public $ids = 0; 
    #[Validate('required|max:255')]
    public $nom; 
    
    #[Validate('nullable|max:255')]
    public $raison_sociale;

    #[Validate('required|max:255')]
    public $type_tiers;  
    
    #[Validate('required|max:255')]
    public $telephone;
    public $email;
    #[Validate('required|max:25')] 
    public $pays;
    #[Validate('required|max:25')] 
    public $ville;
    public $adresse;
    public $code_postal;
    public $site_web;
    #[Validate('required|max:25')] 
    public $sexe;

    public $commercial_charge; 

    #[Validate('required|max:25')] 
    public $etat;
    public $date_debut;
    // #[Validate('required|image|mimes:jpeg,jpg,png,gif|max:2048')]
    // public $logo;

    public $nombre_point;   
    public $retrait_point = 0;   
    public $ide; 

    public $devise;  

    public array $selection = [];
    public $activer_fidelite;
    public $confirmer;
    public $query;
    public $parPage = 20;
    public $parTier;

    public $orderField = 'nom'; 
    public $orderDirection = 'ASC'; 
  
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
    // ceci permet de masquer le formulaire apres le update
    protected $listeners = [
        'tiersUpdate' => 'onTierUpdated'
    ];
    public function onTierUpdated(){
        $this->reset('ids');
    }    
    public function mount(){  
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->consulter_tier;
            if($autoriser == 0){
                toast()->error('Oups Désolé', 'Vous n\'êtes pas autorisé à ouvrir cette page!')->position('top-end')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
                $this->redirect('/bienvenue', navigate: true);
            }
        }
        else{
            alert()->error('Oups Désolé', 'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
            $this->redirect('/bienvenue', navigate: true);
        } 
        $this->pays = 'Cameroon'; 
        $this->etat = 1;  
        $this->date_debut =  date('Y-m-d');      
    }
    public function resetinputFields(){ 
        $this->nom ='';
        $this->raison_sociale ='';
        $this->type_tiers ='';
        $this->etat = 1;
        $this->telephone ='';
        $this->adresse ='';
        $this->code_postal ='';
        $this->ville ='';
        $this->pays ='Cameroon';
        $this->email ='';       
        $this->site_web ='';
        $this->commercial_charge ='';          
        $this->sexe;          
    }
    public function render(){               
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();
        $jourValid = $entite_mod[0]->validite_mod;
        $mod_gestion_tier = $entite_mod[0]->mod_gestion_tier;
        $soldeClient = $entite_mod[0]->solde;
        $this->activer_fidelite = $entite_mod[0]->activer_fidelite; 
        if($dateJour <= $jourValid){   
            if($mod_gestion_tier == 1){  
                $title = 'Listing Tiers | WamsCo';
                $module = 'Gestion tiers';
                $title_fils = 'Tiers';
                $lien = 'listing-tiers?active=3&champ=3-2';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');
                $dateJour = date('Y-m-d');
                if(ctype_alpha($this->query)){ // ctype_alpha: cette fonction permet de savoir si le caractere ou mot est une lettre 
                    $tiers = Tier::where('societe',auth()->user()->societe)->where('nom','like','%'.$this->query.'%')->where('type_tiers','like','%'.$this->parTier.'%')->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage);                    
                    $tiersCount = $tiers->count();           
                    $SoldeTotal = $tiers->sum('solde');             
                }
                else{
                    $tiers = Tier::where('societe',auth()->user()->societe)->where('telephone','like','%'.$this->query.'%')->where('type_tiers','like','%'.$this->parTier.'%')->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage);                    
                    $tiersCount = $tiers->count();           
                    $SoldeTotal = $tiers->sum('solde');           
                }

                $resultat = Tier :: where('societe',auth()->user()->societe)->get();  
                $nbreTotalTier = $resultat->count();     
                $SoldeTotalTier = $resultat->sum('solde');

                $derniereActivite = Tier::where('societe',auth()->user()->societe)->latest('updated_at')->first(); 
                
                // Pour éviter une erreur si aucun enregistrement n'existe et rendre le code plus propre
                $nbrePointFidelite = Tier::where('societe', auth()->user()->societe)->value('objectif_point') ?? 0;
                
                $utilisateur = Utilisateur::where('societe',auth()->user()->societe)->orderBy('name','asc')->get();                 
                $deviseTva = DeviseTva :: where('societe',auth()->user()->societe)->limit(1)->orderBy('id','asc')->count();             
                if($deviseTva == 0){
                    $this->devise = 'FCFA';
                }
                else{
                    $deviseTva = DeviseTva :: where('societe',auth()->user()->societe)->limit(1)->orderBy('id','asc')->get(); 
                    $this->devise = $deviseTva[0]->devise;                
                }  

                $page = 'Tiers'; // pour evenement lies
                $log = LogActivityModel::where('user_societe',auth()->user()->societe)->where('page', $page)->limit(20)->orderBy('id','desc')->get();
                $logCount = $log->count();

                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');            
                $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get(); 
                $jourValid = $entite_mod[0]->validite_mod; 
                // ceci pour trouver le nombre de jour restant avant expiration
                $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24));           
                    return view('livewire.tiers.tiers',compact('title_fils','module','lien','dateJour','tiers','tiersCount','SoldeTotal','utilisateur','nbreTotalTier','SoldeTotalTier','derniereActivite','nbrePointFidelite',
                    'log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));                  
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
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){      
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_tier;
            if($autoriser == 1){                   
                $validation = '';
                $paiement = '';
                $dates = date('dmy-His');
                $length = 2;
                $token = bin2hex(random_bytes($length));

                if($this->type_tiers == 'Fournisseur'){
                    $token_ok = 'SUP-'.$dates;
                }
                else{
                    $token_ok = 'CUS-'.$dates;
                }

                $test_point = Tier::where('societe',auth()->user()->societe)->count();
                if($test_point > 0){
                    $objectifPoint = Tier::where('societe',auth()->user()->societe)->get();
                    $objectif_point = $objectifPoint[0]->objectif_point;
                }
                else{
                    $objectif_point = 0;
                }
                $solde = 0;
                Tier::create(['nom'=>$this->nom,'code_tier'=>$token_ok,'raison_sociale'=>$this->raison_sociale,'solde'=>$solde,'type_tiers'=>$this->type_tiers,'etat'=>$this->etat,'telephone'=>$this->telephone,
                    'adresse'=>$this->adresse,'code_postal'=>$this->code_postal,'ville'=>$this->ville,'pays'=>$this->pays,'email'=>$this->email,'site_web'=>$this->site_web,
                    'validation'=>$validation,'paiement'=>$paiement,'date_debut'=>$this->date_debut,'commercial_charge'=>$this->commercial_charge,'sexe'=>$this->sexe,'objectif_point'=>$objectif_point,
                    'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                    
                    
                    // ceci recupere le dernier enregistrement cree a l'instant
                    $dernier_id = Tier::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id; 
                    $id_activite = $dernier_id;
                    $page = 'Tiers';
                    LogActivity::addToLog('Tier » '.$this->nom.' créé', $id_activite, $page);  
                    $this->dispatch('alert',                    
                        title:'Tiers ('.$this->nom.') enregistré!',
                            timer:3000,
                            icon:'success',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                    );  
                    $this->resetinputFields(); 
                    $this->redirect('/detail_tier?id='.$dernier_id, navigate: true);
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
    public function edit(int $id){
        $tiers = Tier::where('id',$id)->first();
        $this->ids = $tiers->id;
        $this->nom = $tiers->nom;
        $this->raison_sociale = $tiers->raison_sociale;
        $this->type_tiers = $tiers->type_tiers;
        $this->etat = $tiers->etat;
        $this->telephone = $tiers->telephone;
        $this->adresse = $tiers->adresse;
        $this->code_postal = $tiers->code_postal;
        $this->ville = $tiers->ville;
        $this->pays =$tiers->pays;
        $this->email =$tiers->email;
        $this->profession =$tiers->profession;
        $this->site_web =$tiers->site_web;       
        $this->commercial_charge =$tiers->commercial_charge;
        $this->sexe =$tiers->sexe;           
    }
    public function update(){
        $this->validate();       
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_tier;
            if($autoriser == 1){   
                if($this->ids){ 
                    Tier::find($this->ids)->update(['nom'=>$this->nom,'raison_sociale'=>$this->raison_sociale,'type_tiers'=>$this->type_tiers,'etat'=>$this->etat,'telephone'=>$this->telephone,
                    'adresse'=>$this->adresse,'code_postal'=>$this->code_postal,'ville'=>$this->ville,'pays'=>$this->pays,'email'=>$this->email,'site_web'=>$this->site_web,
                    'commercial_charge'=>$this->commercial_charge,'sexe'=>$this->sexe,'societe'=>auth()->user()->societe,'nom_user_modif'=>auth()->user()->name]); 
                    
                    $id_activite = $this->ids;
                    $page = 'Tiers';
                    LogActivity::addToLog('Tier » '.$this->nom.' modifié', $id_activite, $page);
                    $this->dispatch('alert',                    
                        title:'Tier ('.$this->nom.') modifié!',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );                     
                //    $this->resetinputFields(); 
                    $this->dispatch('tiersUpdate');
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
                toast:true,
                showConfirmButton: false,
                position:'top-end',
            ); 
        }       
    }
    public function confirmerDelete($id){    

        $this->confirmer = $id;      
    } 
    public function supprimer(int $id, string $type_tier){ 

        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->supprimer_tier;
            if($autoriser == 1){   
                if($id){  
                    $test_opport = Opportunite::where('societe',auth()->user()->societe)->where('id_client',$id)->count();
                    if($test_opport == 0){
                        Tier::where('id',$id)->delete();                    
                        $id_activite = $id;
                        $page = 'Tiers';
                        LogActivity::addToLog('Tier supprimé', $id_activite, $page);
                        $this->dispatch('alert',                    
                            title:'Suppression effectuée!',
                            timer:3000,
                            icon:'success',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        );  
                    }
                    else{                        
                        $this->dispatch('alert',                    
                            title:'Désolé, vous ne pouvez pas supprimer un tier lié à une opportunité!',
                            timer:5000,
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
    public function charge(int $ids){
        $tiers = Tier::where('id',$ids)->first();
        $this->ide = $tiers->id;  
        $this->nom = $tiers->nom;      
        $this->nombre_point = $tiers->nombre_point;         
    }
    public function RetraitPoint(){
        $this->validate([            
            'retrait_point'=>'required|numeric',                       
         ]);   
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){      
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_fidelite;
            if($autoriser == 1){           
                if($this->retrait_point > $this->nombre_point){
                    $this->dispatch('alert',                    
                        title:'Désolé, le retrait est supérieur ('.$this->retrait_point.') au nombre de points total ('.$this->nombre_point.')!',
                        timer:13000,
                        icon:'error',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );                     
                }
                elseif($this->retrait_point == 0){
                    $this->dispatch('alert',                    
                        title:'Désolé, le retrait doit être supérieur 0!',
                        timer:13000,
                        icon:'info',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );
                }
                else{
                    $pointRetire = $this->nombre_point - $this->retrait_point;
                    Tier::where('id',$this->ide)->update(['nombre_point'=>$pointRetire,'retrait_point'=>$this->retrait_point,'nom_user'=>auth()->user()->name]);                    
                                      
                    $id_activite = $this->ide;
                    $page = 'Tiers';
                    LogActivity::addToLog('Retrait '.$this->retrait_point.' point(s) » '.$this->nom, $id_activite, $page);
                    $this->dispatch('alert',                    
                        title:'Retrait '.$this->retrait_point.' point(s) » '.$this->nom.' enregistré!',
                        timer:13000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    ); 
                    $this->nombre_point = $pointRetire;  
                }                
            }
            else{    
                $this->dispatch('alert',                    
                    title:'Vous n\'êtes pas autorisé à effectuer cette opération!',
                    timer:3000,
                    icon:'error',
                    toast:false,
                    showConfirmButton: false,
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
                showConfirmButton: false,
                position:'center',
            );  
        }   
    }
    public function changeEtat(int $id, int $etat){  
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_tier;
            if($autoriser == 1){        
                if($etat == 1){
                    $ferme = 0;
                    Tier::find($id)->update(['etat'=>$ferme,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                    $id_activite = $id;
                    $page = 'Tiers';
                    LogActivity::addToLog('Etat Tier (Fermé)', $id_activite, $page); 
                    $this->dispatch('alert',                    
                        title:'Tier désactivé avec succès!',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );  
                }
                elseif($etat == 0){
                    $ouvert = 1;
                    Tier::find($id)->update(['etat'=>$ouvert,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                    $id_activite = $id;
                    $page = 'Tiers';
                    LogActivity::addToLog('Etat Tier (Ouvert)', $id_activite, $page); 
                    $this->dispatch('alert',                    
                        title:'Tier activé avec succès!',
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
    // public function deleteTiers(array $ids){        
        
    //     $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
    //     if($test > 0){
    //         $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
    //         $autoriser = $role[0]->supprimer_produit;
    //         if($autoriser == 1){            
    //             Tier::destroy($ids);
    //             $this->selection = [];
    //             $this->dispatch('alert',                    
    //                 title:'Suppression effectué!',
    //                     timer:3000,
    //                     icon:'success',
    //                     toast:true,
    //                     showConfirmButton: false,
    //                     position:'top-end',
    //             );                 
    //         }
    //         else{                 
    //             $this->dispatch('alert',                    
    //                 title:'Vous n\'êtes pas autorisé à effectuer cette opération!',
    //                 timer:3000,
    //                 icon:'error',
    //                 toast:false,
    //                 showConfirmButton: true,
    //                 position:'center',
    //             );  
    //         } 
    //     }
    //     else{             
    //         $this->dispatch('alert',                    
    //             title:'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!',
    //             timer:3000,
    //             icon:'error',
    //             toast:false,
    //             showConfirmButton: true,
    //             position:'center',
    //         );
    //     }    
    // }
}
