<?php

namespace App\Livewire\Administration;

use Livewire\Component;
use Livewire\Attributes\Validate;  
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use App\Helpers\LogActivity;
use App\Models\LogActivity as LogActivityModel;
use App\Models\Entite;
use App\Models\DeviseTva;
use App\Models\Role;
use App\Models\soldeClient;

class Abonnement extends Component
{
    public $designation;
    public $prix;
    public $prix_user = 5000; // prix par utilisateur
    public $prix_societe;
    public $quantite = 1;
    public $quantite_userStand = 3;
    public $quantite_userIndep = 1;
    public $quantite_saisie;
    public $quantite_societe = 1;
    public $quantite_societeStand = 1;
    public $prixTotalAbon;
    public $prixTotalUser;
    public $prixTotalSociete;
    public $solde;    
    public $plan;
    public $abonnement;

    public $approuver;

    public function render()
    {
        $plan = request('plan');
        $abonnement = request('abonnement');
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();
        $jourValid = $entite_mod[0]->validite_mod; 
        $soldeClient = $entite_mod[0]->solde;
             
        $title = 'Abonnement '. auth()->user()->societe.' | WamsCo';
        $module = 'Paramètres';
        $title_fils = 'Abonnement '. auth()->user()->societe;
        $lien = 'abonnement_clients';
        $active = request('active');
        $champ = request('champ');
        $choix = request('choix');      
        $dateJour = date('Y-m-d');
        toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');

        $derniereActivite = soldeClient::where('id_enseigne',auth()->user()->societe_id)->latest('updated_at')->first();  

        $page = 'Abonnement'; // Pour evenement lie
        $log = LogActivityModel::where('societe_id',auth()->user()->societe_id)->where('page', $page)->limit(50)->orderBy('id','desc')->get();
        $logCount = $log->count();

        if($plan == 'Independant' && $abonnement == 'Mois'){
            $this->designation = 'Abonnement Independant Mensuel WamsCo Cloud';
            $this->abonnement = $abonnement;
            $this->plan = $plan;
            $this->prix = 10000;
            $this->quantite_saisie = 1;
            $this->prix_societe = $this->prix * 0.85;
        }
        elseif($plan == 'Standard' && $abonnement == 'Mois') { 
            $this->designation = 'Abonnement Standard Mensuel WamsCo Cloud';
            $this->abonnement = $abonnement;
            $this->plan = $plan;
            $this->prix = 20000;
            $this->quantite_saisie = 3;
            $this->prix_societe = $this->prix * 0.85;
        }
        elseif($plan == 'Independant' && $abonnement == 'An') { 
            $this->designation = 'Abonnement Independant Annuel WamsCo Cloud';
            $this->abonnement = $abonnement;
            $this->plan = $plan;
            $this->prix = 100000;
            $this->quantite_saisie = 1;
            $this->prix_societe = $this->prix * 0.85;
        }
        elseif($plan == 'Standard' && $abonnement == 'An') { 
            $this->designation = 'Abonnement Standard Annuel WamsCo Cloud';
            $this->abonnement = $abonnement;
            $this->plan = $plan;
            $this->prix = 200000;
            $this->quantite_saisie = 3;
            $this->prix_societe = $this->prix * 0.85;
        }
        
        if(!empty($this->quantite) && !empty($this->quantite_saisie) && !empty($this->quantite_societe))
        {           
            // Prix total nombre abonnement Mois ou An
            $this->prixTotalAbon = $this->prix * $this->quantite;

            if($this->plan == 'Standard'){
                // Prix total nombre utilisateurs
                $prixTotalUserCal = ($this->quantite_saisie - $this->quantite_userStand) * $this->prix_user;
                $this->prixTotalUser = $prixTotalUserCal * $this->quantite;
            }
            else{
                // Prix total nombre utilisateurs
                $prixTotalUserCal = ($this->quantite_saisie - $this->quantite_userIndep) * $this->prix_user;
                $this->prixTotalUser = $prixTotalUserCal * $this->quantite;
            }       

            // Prix total nombre societe
            $prixTotalSocieteCal = ($this->quantite_societe - $this->quantite_societeStand) * $this->prix_societe;
            $this->prixTotalSociete = $prixTotalSocieteCal * $this->quantite;

            $this->solde = $this->prixTotalAbon + $this->prixTotalUser + $this->prixTotalSociete;           
        }

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
        return view('livewire.administration.abonnement.abonnement',compact('title_fils','module','lien','dateJour','soldeClient','derniereActivite','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));                       
    }
    public function validePaie($id){  
        $this->approuver = $id;      
    } 
    public function confirmerAbonnement(string $plan, string $abon){
        $this->validate([
            'quantite'=>'required|numeric|min:1',
            'quantite_saisie'=>'required|numeric|min:1',
            'quantite_societe'=>'required|numeric|min:1',
        ]);  
        // $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        // if($test > 0){
        //     $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
        //     $autoriser = $role[0]->transfer_stock_filiale;
        //     if($autoriser == 1){  
                
                $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get(); 
                $jourValid = $entite_mod[0]->validite_mod;                    
                $soldeClient = $entite_mod[0]->solde;
                $validite_mod = $entite_mod[0]->validite_mod; 
                $id = $entite_mod[0]->id;
                $enseigne = $entite_mod[0]->enseigne;
                $societe_mere = $entite_mod[0]->societe_mere;
                $raison_sociale = $entite_mod[0]->raison_sociale;
                $ville = $entite_mod[0]->ville;
                $pays = $entite_mod[0]->pays;
                $adresse = $entite_mod[0]->adresse;
                $telephone = $entite_mod[0]->telephone;
                $email = $entite_mod[0]->email;       
                $registre_commerce = $entite_mod[0]->registre_com;       
                $responsable_societe = $entite_mod[0]->responsable_societe;                    
                $old_image = $entite_mod[0]->logo;   
                        
                if($plan == 'Standard' && $abon == 'An'){                                        

                    if($this->solde <=  $soldeClient){                       

                        // recuper le jour, mois et annee
                        $jourMoisBD = date('m-d', strtotime($validite_mod)); // dans la bd
                        $anneesold = date('Y', strtotime($validite_mod));
                        $anneeNew = $anneesold + $this->quantite;
                        $dateJour = date('m-d');
                        
                        if($dateJour >= $jourMoisBD){
                            $anneesValider = $anneeNew.'-'.$dateJour; // date bd est depassee
                        }
                        else{
                            $anneesValider = $anneeNew.'-'.$jourMoisBD; // date bd n'est pas encore depassee
                        }
                        $soldeRestant = $soldeClient - $this->solde;
                        $periode = 'Standard-Annuel';                       
                        Entite::where('id',auth()->user()->societe_id)->update(['solde'=>$soldeRestant,'validite_mod'=>$anneesValider,'nbre_user_max'=>$this->quantite_saisie,
                        'nombre_societe'=>$this->quantite_societe,'periode'=>$periode,'nom_user_modif'=>auth()->user()->email,'user_id_modif'=>auth()->user()->id]);
                        
                        $credit = 0;
                        soldeClient::create(['enseigne'=>$enseigne,'id_enseigne'=>$id,'societe_mere'=>$societe_mere,'raison_sociale'=>$raison_sociale,'designation'=>'Abonnement '.$periode,'debit'=>$this->solde,'credit'=>$credit,
                                            'responsable_societe'=>$responsable_societe,'pays'=>$pays,'ville'=>$ville,'adresse'=>$adresse,'telephone'=>$telephone,'email'=>$email,'registre_com'=>$registre_commerce,'logo'=>$old_image,
                                            'nom_user'=>auth()->user()->email,'user_id'=>auth()->user()->id]);                        

                        $id_activite = 0; 
                        $page = 'Abonnement';
                        LogActivity::addToLog('Abonnement '.$periode.' validé avec succes', $id_activite, $page);  
                        $this->dispatch('alert',                    
                            title:'Abonnement annuel validé avec succes!',
                            timer:10000,
                            icon:'success',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        );  
                        $this->redirect('/paiement?plan=Standard&abonnement=An', navigate: true);
                    }
                    else{
                        $this->dispatch('alert',                    
                            title:'Désolé, votre solde est insuffisant ('.number_format($soldeClient,0,',',' ').' FCFA), veuillez recharger votre compte svp!',
                            timer:10000,
                            icon:'warning',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        );  
                    }                   
                }
                elseif($plan == 'Standard' && $abon == 'Mois'){
                    
                    if($this->solde <=  $soldeClient){                       

                        // recuper le jour, mois et annee
                        // dd($jourValider = date('Y-m-d', strtotime('+120 days')));
                        // dd($moisValider = date('Y-m-d', strtotime('+'.$this->quantite.'month')));
                        // dd($moisValider = date('Y-m-d', strtotime('+'.$this->quantite.'year’')));
                        $jourMoisAnneeBD = date('Y-m-d', strtotime($validite_mod));
                        $dateJour = date('Y-m-d');

                        if($dateJour >= $jourMoisAnneeBD){                           
                             $moisValider = date('Y-m-d', strtotime('+'.$this->quantite.'month'));
                        }
                        else{ 
                            // nbre de mois correspond a combie de jour / et mets jour pour optenir le nbre mois.
                            $DateNbreMoisChoisir = date('Y-m-d', strtotime('+'.$this->quantite.'month'));
                            // ceci pour trouver le nombre de jour restant avant expiration
                            $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24));
                            $moisValider = date('Y-m-d',strtotime("+$nbjoursRestant days", strtotime($DateNbreMoisChoisir)));
                        }                        
                        $soldeRestant = $soldeClient - $this->solde;
                        $periode = 'Standard-Mois';                        
                        Entite::where('id',auth()->user()->societe_id)->update(['solde'=>$soldeRestant,'validite_mod'=>$moisValider,'nbre_user_max'=>$this->quantite_saisie,
                        'nombre_societe'=>$this->quantite_societe,'periode'=>$periode,'nom_user_modif'=>auth()->user()->email,'user_id_modif'=>auth()->user()->id]); 
                        
                         $credit = 0;
                         soldeClient::create(['enseigne'=>$enseigne,'id_enseigne'=>$id,'societe_mere'=>$societe_mere,'raison_sociale'=>$raison_sociale,'designation'=>'Abonnement '.$periode,'debit'=>$this->solde,'credit'=>$credit,
                                            'responsable_societe'=>$responsable_societe,'pays'=>$pays,'ville'=>$ville,'adresse'=>$adresse,'telephone'=>$telephone,'email'=>$email,'registre_com'=>$registre_commerce,'logo'=>$old_image,
                                            'nom_user'=>auth()->user()->email,'user_id'=>auth()->user()->id]);    

                        $id_activite = 0; 
                        $page = 'Abonnement';
                        LogActivity::addToLog('Abonnement '.$periode.' validé avec succes', $id_activite, $page);
                        $this->dispatch('alert',                    
                            title:'Abonnement validé avec succes!',
                            timer:10000,
                            icon:'success',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        );  
                        $this->redirect('/paiement?plan=Standard&abonnement=Mois', navigate: true);
                    }
                    else{
                        $this->dispatch('alert',                    
                            title:'Désolé, votre solde est insuffisant ('.number_format($soldeClient,0,',',' ').' FCFA), veuillez recharger votre compte svp!',
                            timer:10000,
                            icon:'warning',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        );  
                    }     
                } 
                elseif($plan == 'Independant' && $abon == 'An'){
                    
                    if($this->solde <=  $soldeClient){                       

                        $jourMoisBD = date('m-d', strtotime($validite_mod)); // dans la bd
                        $anneesold = date('Y', strtotime($validite_mod));
                        $anneeNew = $anneesold + $this->quantite;
                        $dateJour = date('m-d');
                        
                        if($dateJour >= $jourMoisBD){
                            $anneesValider = $anneeNew.'-'.$dateJour; // date bd est depassee
                        }
                        else{
                            $anneesValider = $anneeNew.'-'.$jourMoisBD; // date bd n'est pas encore depassee
                        }
                        
                        $soldeRestant = $soldeClient - $this->solde;
                        $periode = 'Independant-An';                        
                        Entite::where('id',auth()->user()->societe_id)->update(['solde'=>$soldeRestant,'validite_mod'=>$anneesValider,'nbre_user_max'=>$this->quantite_saisie,
                        'nombre_societe'=>$this->quantite_societe,'periode'=>$periode,'nom_user_modif'=>auth()->user()->email,'user_id_modif'=>auth()->user()->id]); 
                        
                        $credit = 0;
                        soldeClient::create(['enseigne'=>$enseigne,'id_enseigne'=>$id,'societe_mere'=>$societe_mere,'raison_sociale'=>$raison_sociale,'designation'=>'Abonnement '.$periode,'debit'=>$this->solde,'credit'=>$credit,
                                            'responsable_societe'=>$responsable_societe,'pays'=>$pays,'ville'=>$ville,'adresse'=>$adresse,'telephone'=>$telephone,'email'=>$email,'registre_com'=>$registre_commerce,'logo'=>$old_image,
                                            'nom_user'=>auth()->user()->email,'user_id'=>auth()->user()->id]);    

                        $id_activite = 0; 
                        $page = 'Abonnement';
                        LogActivity::addToLog('Abonnement '.$periode.' validé avec succes', $id_activite, $page);
                        $this->dispatch('alert',                    
                            title:'Abonnement validé avec succes!',
                            timer:10000,
                            icon:'success',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        );  
                        $this->redirect('/paiement?plan=Independant&abonnement=An', navigate: true);
                    }
                    else{
                        $this->dispatch('alert',                    
                            title:'Désolé, votre solde est insuffisant ('.number_format($soldeClient,0,',',' ').' FCFA), veuillez recharger votre compte svp!',
                            timer:10000,
                            icon:'warning',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        );  
                    }     
                }
                elseif($plan == 'Independant' && $abon == 'Mois'){                    
                    
                    if($this->solde <=  $soldeClient){                       

                        // recuper le jour, mois et annee
                        $jourMoisAnneeBD = date('Y-m-d', strtotime($validite_mod));
                        $dateJour = date('Y-m-d');

                        if($dateJour >= $jourMoisAnneeBD){                            
                             $moisValider = date('Y-m-d', strtotime('+'.$this->quantite.'month'));
                        }
                        else{
                            // nbre de mois correspond a combie de jour / et mets jour pour optenir le nbre mois.
                            $DateNbreMoisChoisir = date('Y-m-d', strtotime('+'.$this->quantite.'month'));
                            // ceci pour trouver le nombre de jour restant avant expiration
                            $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24));
                            $moisValider = date('Y-m-d',strtotime("+$nbjoursRestant days", strtotime($DateNbreMoisChoisir)));
                        }
                       
                        $soldeRestant = $soldeClient - $this->solde;
                        $periode = 'Independant-Mois';                        
                        Entite::where('id',auth()->user()->societe_id)->update(['solde'=>$soldeRestant,'validite_mod'=>$moisValider,'nbre_user_max'=>$this->quantite_saisie,
                        'nombre_societe'=>$this->quantite_societe,'periode'=>$periode,'nom_user_modif'=>auth()->user()->email,'user_id_modif'=>auth()->user()->id]);
                        
                        $credit = 0;
                        soldeClient::create(['enseigne'=>$enseigne,'id_enseigne'=>$id,'societe_mere'=>$societe_mere,'raison_sociale'=>$raison_sociale,'designation'=>'Abonnement '.$periode,'debit'=>$this->solde,'credit'=>$credit,
                                            'responsable_societe'=>$responsable_societe,'pays'=>$pays,'ville'=>$ville,'adresse'=>$adresse,'telephone'=>$telephone,'email'=>$email,'registre_com'=>$registre_commerce,'logo'=>$old_image,
                                            'nom_user'=>auth()->user()->email,'user_id'=>auth()->user()->id]);  

                        $id_activite = 0; 
                        $page = 'Abonnement';
                        LogActivity::addToLog('Abonnement '.$periode.' validé avec succes', $id_activite, $page);   
                        $this->dispatch('alert',                    
                            title:'Abonnement validé avec succes!',
                            timer:10000,
                            icon:'success',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        );  
                        $this->redirect('/paiement?plan=Independant&abonnement=Mois', navigate: true);
                    }
                    else{
                        $this->dispatch('alert',                    
                            title:'Désolé, votre solde est insuffisant ('.number_format($soldeClient,0,',',' ').' FCFA), veuillez recharger votre compte svp!',
                            timer:10000,
                            icon:'warning',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        );  
                    }     
                }
            // }
            // else{                 
            //     $this->dispatch('alert',                    
            //         title:'Vous n\'êtes pas autorisé à effectuer cette opération!',
            //         timer:3000,
            //         icon:'error',
            //         toast:false,
            //         showConfirmButton: true,
            //         position:'center',
            //     ); 
            // }
        // }
        // else{             
        //     $this->dispatch('alert',                    
        //         title:'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!',
        //         timer:3000,
        //         icon:'error',
        //         toast:false,
        //         showConfirmButton: true,
        //         position:'center',
        //     ); 
        // }  
    } 
}
