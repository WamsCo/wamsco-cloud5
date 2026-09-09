<?php

namespace App\Livewire\Tiers;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate; 
use App\Models\Utilisateur;
use App\Models\Tier;
use App\Models\Role;
use App\Models\Entite;
use App\Helpers\LogActivity;
use App\Models\LogActivity as LogActivityModel;
use App\Models\DeviseTva;
use App\Models\factureClientEntete;
use App\Models\factureClientLigne;
use App\Models\CommandeClientEntete;
use App\Models\CommandeClientLigne;
use App\Models\SoldeTier;
use App\Models\Opportunite;

class DetailTiers extends Component
{
    public $id; // Edit et update
    public $ids;
    public $devise;
    public $confirmer;    

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

    public $montant_recu;    
    public $sens;
    public $solde;
    public $compte;    

    public $societe;
    public $societe_id;
    public $code_tier; 
    
    public $activite; 
    public $query; 
        
    
    // #[Validate('required|image|mimes:jpeg,jpg,png,gif|max:2048')]
    // public $logo;

    public function mount(){  
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
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
        $this->ids = request('id'); // id tier  tres important de le mettre ici.
    }
    public function render(){
        
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('id',auth()->user()->societe_id)->get();
        $jourValid = $entite_mod[0]->validite_mod; 
        $mod_gestion_tier = $entite_mod[0]->mod_gestion_tier; 
        $soldeClient = $entite_mod[0]->solde;
        if($dateJour <= $jourValid){
            if($mod_gestion_tier == 1){  
                $menu = 'Tiers';
                $title = 'Détails Tier | WamsCo';
                $module = 'Gestion tiers';
                $title_fils = 'Détail tiers (Prospect, Client, Fournisseur)';
                $lien = 'listing-tiers?active=3&champ=3-2';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix'); 
                $dateJour = date('Y-m-d');
                // $menuModule = request('module');
                $utilisateur = Utilisateur::where('societe_id',auth()->user()->societe_id)->orderBy('name','asc')->get();
                $tier = Tier::where('societe_id',auth()->user()->societe_id)->where('id',$this->ids)->get();           
                $tiersCount = Tier::where('societe_id',auth()->user()->societe_id)->count(); 
                $page = 'Tiers'; // pour evenement lies
                
                // ceci au chargement de la page
                $tier = Tier::where('societe_id',auth()->user()->societe_id)->where('id',$this->ids)->get();      
                $tiersCount = $tier->count(); 
                // Facture
                $factClient_entete = factureClientEntete::where('societe_id',auth()->user()->societe_id)->where('id_client',$this->ids)->orderBy('id','DESC')->limit(15)->get();
                $factCltEntCount = $factClient_entete->count();   

                $factClient_all = factureClientEntete::where('societe_id',auth()->user()->societe_id)->where('id_client',$this->ids)->orderBy('id', 'DESC')->get();
                $factClientEnteteCount = $factClient_all->count(); 
                $factClientEnteteMarge = $factClient_all->sum('marge'); 
                $factClientEnteteTTC = $factClient_all->sum('montant_ttc'); 
                $factClientEnteteResteApercevoir = $factClient_all->sum('reste_a_percevoir');                               
                // commande
                $cmd_client = CommandeClientEntete::where('societe_id',auth()->user()->societe_id)->where('id_client',$this->ids)->orderBy('id', 'DESC')->limit(10)->get();
                $cmdClientCount = $cmd_client->count();

                $log = LogActivityModel::where('societe_id',auth()->user()->societe_id)->where('id_activite', $this->ids)->where('page', $page)->where('subject','like','%'.$this->activite.'%')->limit(50)->orderBy('id','desc')->get();
                $logCount = $log->count();                

                $deviseTva = DeviseTva :: where('societe_id',auth()->user()->societe_id)->limit(1)->orderBy('id','asc')->count();             
                if($deviseTva == 0){
                    $this->devise = 'FCFA';
                }
                else{
                    $deviseTva = DeviseTva :: where('societe_id',auth()->user()->societe_id)->limit(1)->orderBy('id','asc')->get(); 
                    $this->devise = $deviseTva[0]->devise;                
                }                    
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');    
                $entite_mod = Entite::where('id',auth()->user()->societe_id)->get(); 
                $jourValid = $entite_mod[0]->validite_mod; 
                // ceci pour trouver le nombre de jour restant avant expiration
                $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24));
                return view('livewire.tiers.detail-tiers',compact('title_fils','module','lien','dateJour','utilisateur','tier','tiersCount','log','logCount','factClient_entete','factCltEntCount','factClientEnteteCount','factClientEnteteMarge','factClientEnteteTTC',
                'factClientEnteteResteApercevoir','cmd_client','cmdClientCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant')); 
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
            return view('livewire.bienvenue',compact('dateJour','entite_mod'))->layout('components.layouts.app',compact('title','module','title_fils','lien','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));          
        }
    }    
    public function edit(int $id){
        $tiers = Tier::where('id',$id)->first();
        $this->id = $tiers->id;
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
        $this->solde =$tiers->solde;  
        $this->societe =$tiers->societe;  
        $this->societe_id =$tiers->societe_id;         
        $this->code_tier =$tiers->code_tier;  
                 
    }
    public function update(){        
        $this->validate();       
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_tier;
            if($autoriser == 1){   
                if($this->id){ 
                    if($this->code_tier){
                        if($this->type_tiers == 'Fournisseur'){
                            
                            $newCode = Str::replace('CUS', 'SUP', $this->code_tier);
                        }
                        else{
                            $newCode = Str::replace('SUP', 'CUS', $this->code_tier);
                        }
                        
                        Tier::find($this->id)->update(['nom'=>$this->nom,'code_tier'=>$newCode,'raison_sociale'=>$this->raison_sociale,'type_tiers'=>$this->type_tiers,'etat'=>$this->etat,'telephone'=>$this->telephone,
                        'adresse'=>$this->adresse,'code_postal'=>$this->code_postal,'ville'=>$this->ville,'pays'=>$this->pays,'email'=>$this->email,'site_web'=>$this->site_web,
                        'commercial_charge'=>$this->commercial_charge,'sexe'=>$this->sexe,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user_modif'=>auth()->user()->name]); 

                        // Mise a jour
                        SoldeTier::where('id_tier',$this->id)->update(['nom_tier'=>$this->nom,'code_tier'=>$newCode,'raison_sociale'=>$this->raison_sociale,
                        'telephone'=>$this->telephone,'adresse'=>$this->adresse,'ville'=>$this->ville,'pays'=>$this->pays,'email'=>$this->email,
                        'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name]);
                    }
                    else{
                        $dates = date('dmy-His');
                        $length = 2;
                        $token = bin2hex(random_bytes($length));

                        if($this->type_tiers == 'Fournisseur'){
                            $token_ok = 'SUP-'.$dates;
                        }
                        else{
                            $token_ok = 'CUS-'.$dates;
                        }

                        Tier::find($this->id)->update(['nom'=>$this->nom,'code_tier'=>$token_ok,'raison_sociale'=>$this->raison_sociale,'type_tiers'=>$this->type_tiers,'etat'=>$this->etat,'telephone'=>$this->telephone,
                        'adresse'=>$this->adresse,'code_postal'=>$this->code_postal,'ville'=>$this->ville,'pays'=>$this->pays,'email'=>$this->email,'site_web'=>$this->site_web,
                        'commercial_charge'=>$this->commercial_charge,'sexe'=>$this->sexe,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user_modif'=>auth()->user()->name]); 
                        
                        // Mise a jour
                        SoldeTier::where('id_tier',$this->id)->update(['nom_tier'=>$this->nom,'code_tier'=>$token_ok,'raison_sociale'=>$this->raison_sociale,
                        'telephone'=>$this->telephone,'adresse'=>$this->adresse,'ville'=>$this->ville,'pays'=>$this->pays,'email'=>$this->email,
                        'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name]);
                    }                    
                    
                    factureClientEntete::where('id_client',$this->id)->update(['nom_client'=>$this->nom]);
                    factureClientLigne::where('id_client',$this->id)->update(['nom_client'=>$this->nom]);
                    CommandeClientEntete::where('id_client',$this->id)->update(['nom_client'=>$this->nom]);
                    CommandeClientLigne::where('id_client',$this->id)->update(['nom_client'=>$this->nom]);
                   
                    $id_activite = $this->id;
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
                    // $this->dispatch('tiersUpdate');
                    $this->redirect('/detail_tier?id='.$this->id.'&active=3&champ=3-2', navigate: true);
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

        $this->id = $id;      
        $this->confirmer = $id;      
    } 
    public function supprimer($id){ 

        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->supprimer_tier;
            if($autoriser == 1){   
                if($id){
                    $test_opport = Opportunite::where('societe_id',auth()->user()->societe_id)->where('id_client',$id)->count();
                    if($test_opport == 0){
                        Tier::where('id',$id)->delete();
                        SoldeTier::where('id_tier',$id)->delete();                                         
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
                        $this->redirect('/listing-tiers?active=3&champ=3-2', navigate: true); 
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
    public function precedant(int $id){ 
        $testPrecedant = Tier::where('societe_id',auth()->user()->societe_id)->where('id','<',$id)->orderBy('id','desc')->count();
        if($testPrecedant > 0){ 
            $precedant = Tier::where('societe_id',auth()->user()->societe_id)->where('id','<',$id)->orderBy('id','desc')->first();        
            $previous = $precedant->id; 
            $this->redirect('/detail_tier?id='.$previous.'&active=3&champ=3-2', navigate: true);              
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
            $this->redirect('/detail_tier?id='.$id.'&active=3&champ=3-2&', navigate: true);  // ceci evite une erreur
        }    
    }    
    public function suivant(int $id){    
        
        $testSuivant = Tier::where('societe_id',auth()->user()->societe_id)->where('id','>',$id)->orderBy('id','asc')->count();
        if($testSuivant > 0){
            $suivant = Tier::where('societe_id',auth()->user()->societe_id)->where('id','>',$id)->orderBy('id','asc')->first();
            $next = $suivant->id;             
            $this->redirect('/detail_tier?id='.$next.'&active=3&champ=3-2', navigate: true);                     
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
            $this->redirect('/detail_tier?id='.$id.'&active=3&champ=3-2', navigate: true); // ceci evite une erreur
        } 
    } 
    public function soldeTier(){ 
       
        $this->redirect('/solde_tiers?id='.$this->ids.'&active=3&champ=3-2&', navigate: true);  // ceci evite une erreur 
    } 
    public function rechargeCredit(){        
        $this->validate([                      
            'montant_recu'=>'required|numeric', 
            'compte'=>'required|max:50',             
            'sens'=>'required|max:6', 
                     
        ]);
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count(); 
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_tier;
            if($autoriser == 1){
                if($this->id){  
                    if($this->sens == 'Crédit'){
                        $designation = 'Versement en compte';
                        $solde_final = $this->solde + $this->montant_recu;  
                        $debit = 0;
                        SoldeTier::create(['id_tier'=>$this->id,'nom_tier'=>$this->nom,'code_tier'=>$this->code_tier,'raison_sociale'=>$this->raison_sociale,'designation'=>$designation,'debit'=>$debit,'credit'=>$this->montant_recu,
                                            'compte'=>$this->compte,'pays'=>$this->pays,'ville'=>$this->ville,'adresse'=>$this->adresse,
                                            'telephone'=>$this->telephone,'email'=>$this->email,'societe'=>$this->societe,'societe_id'=>$this->societe_id,
                                            'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                        Tier::find($this->id)->update(['solde'=>$solde_final,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                        
                    }
                    elseif($this->sens == 'Débit'){
                        $designation = 'Retrait en compte';
                        $solde_final = $this->solde - $this->montant_recu;
                        $credit = 0;
                        SoldeTier::create(['id_tier'=>$this->id,'nom_tier'=>$this->nom,'code_tier'=>$this->code_tier,'raison_sociale'=>$this->raison_sociale,'designation'=>$designation,'debit'=>$this->montant_recu,'credit'=>$credit,
                                            'compte'=>$this->compte,'pays'=>$this->pays,'ville'=>$this->ville,'adresse'=>$this->adresse,
                                            'telephone'=>$this->telephone,'email'=>$this->email,'societe'=>$this->societe,'societe_id'=>$this->societe_id,
                                            'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                        Tier::find($this->id)->update(['solde'=>$solde_final,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                    }
                    else{
                       $this->dispatch('alert',                    
                            title:'Désolé, veuillez selectionner un choix valable (Crédit / Débit)!',
                            timer:3000,
                            icon:'error',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        );  
                    } 
                    $id_activite = $this->id;
                    $page = 'Tiers';
                    LogActivity::addToLog($this->nom.' » '.$this->sens.' compte ('.$this->compte.') '.$this->montant_recu.' '.$this->devise.' (T='.$solde_final.' '.$this->devise.')',$id_activite, $page); // ceci recense les activites dans le systeme 
                    $this->dispatch('alert',                    
                        title:'Solde '.$this->nom.' ajouté',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    ); 
                    flash ('Recharge '.$this->nom.' » <strong>'.$this->montant_recu.' '.$this->devise.'</strong> ('.$this->compte.') ajoutée!')->success(); 
                    $this->redirect('/detail_tier?id='.$this->id.'&active=3&champ=3-2', navigate: true);  
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
}
