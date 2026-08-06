<?php

namespace App\Livewire\GestionPointVente\Restaurant;

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
use App\Models\TableRestau;
use App\Models\RestauCommandeAttenteLigne;



class Cuisine extends Component
{
    use WithPagination;
    use WithFileUploads;

    public function mount(){        
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $this->autoriser = $role[0]->voir_marge;
            $autoriser = $role[0]->voir_ecran_cuisine;
            if($autoriser == 0){
                alert()->error('Oups Désolé', 'Vous n\'êtes pas autorisé à ouvrir cette page !!!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
                $this->redirect('/bienvenue', navigate: true);
            }            
        }
        else{
            alert()->error('Oups Désolé', 'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
            $this->redirect('/bienvenue', navigate: true);
        }        
        // $this->date_facturation = date('Y-m-d'); 
        // $this->date_echeance = date('Y-m-d'); 
    }
    public function render()
    {
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();
        $jourValid = $entite_mod[0]->validite_mod; 
        $mod_cuisine = $entite_mod[0]->mod_cuisine; 
        $soldeClient = $entite_mod[0]->solde;
        if($dateJour <= $jourValid){
            if($mod_cuisine == 1){
                $title = 'Écran de cuisine | WamsCo';
                $module = 'Gestion point vente';
                $title_fils = 'Session Pos';
                $lien = 'pos_sessions';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');      
                $dateJour = date('Y-m-d');
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');

                $aller = -3;

                // statut qui affiche tout
                $emplacement = TableRestau::where('societe', auth()->user()->societe)->where('utiliser','Oui')
                ->where(function ($query) {
                    $query->where('statut','A préparer')
                        ->orWhere('statut','En cours')
                        ->orWhere('statut','Terminer');
                })->orderBy('updated_at','DESC')->get();
                $emplacement_allCount = $emplacement->count();

                // // statut A préparer
                $emplacement_prepaCount = TableRestau::where('societe',auth()->user()->societe)->where('utiliser','Oui')->where('statut','A préparer')->orderBy('updated_at', 'DESC')->count(); 

                // statut en cours
                $emplacement_encourCount = TableRestau::where('societe',auth()->user()->societe)->where('utiliser','Oui')->where('statut','En cours')->orderBy('updated_at', 'DESC')->count(); 

                // // statut terminer
                $emplacement_terminerCount = TableRestau::where('societe',auth()->user()->societe)->where('utiliser','Oui')->where('statut','Terminer')->orderBy('updated_at', 'DESC')->count(); 

                $cmdAttenteLigne = RestauCommandeAttenteLigne::where('societe',auth()->user()->societe)->get();            

                // $page = 'SessionPos'; // Pour evenement lie
                // $log = LogActivityModel::where('user_societe',auth()->user()->societe)->where('id_activite', $this->ids)->where('page', $page)->limit(10)->orderBy('id','desc')->get();
                // $logCount = $log->count();            
                // $taxe = DeviseTva::where('societe',auth()->user()->societe)->orderBy('taux_tva','asc')->get();

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
                return view('livewire.gestion-point-vente.restaurant.cuisine.cuisine_pos',compact('title_fils','module','lien','dateJour','emplacement','emplacement_allCount','emplacement_prepaCount','emplacement_encourCount',
                'emplacement_terminerCount','cmdAttenteLigne'))->layout('components.layouts.app_pos',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant','aller',));
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
    public function preparation(int $id){ 
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->changer_statut_cmd_cuisine;
            if($autoriser == 1){
                $statut = 'En cours'; 
                TableRestau::find($id)->update(['statut'=>$statut]);
                $this->dispatch('alert',                    
                    title:'Commande en cours',
                    timer:5000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                ); 
                // $this->redirect('/cuisine?active=5&champ=1-3', navigate: true); // ceci permet d'actualiser la page (important) 
            }
            else{  
                $this->dispatch('alert',                    
                    title:'Vous n\'êtes pas autorisé à effectuer cette opération!',
                    timer:3000,
                    icon:'error',
                    toast:true,
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
                showConfirmButton: true,
                position:'center',
            );  
        }
    }
    // cette fonction permet de barrer le produit deja prepare ou pret (tres important)
    public function barrerProd(int $id, string $etats){
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->changer_statut_cmd_cuisine;
            if($autoriser == 1){
                if($etats == 'Barrer'){
                    $statut_cuisine = ''; 
                    RestauCommandeAttenteLigne::find($id)->update(['statut_cuisine'=>$statut_cuisine]);
                }
                else{
                $statut_cuisine = 'Barrer'; 
                RestauCommandeAttenteLigne::find($id)->update(['statut_cuisine'=>$statut_cuisine]); 
                }
            }
            else{  
                $this->dispatch('alert',                    
                    title:'Vous n\'êtes pas autorisé à effectuer cette opération!',
                    timer:3000,
                    icon:'error',
                    toast:true,
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
                showConfirmButton: true,
                position:'center',
            );  
        }
    }
    public function terminer(int $ids){
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->changer_statut_cmd_cuisine;
            if($autoriser == 1){
                $statut = 'Terminer'; 
                TableRestau::find($ids)->update(['statut'=>$statut]);
                $this->dispatch('alert',                    
                    title:'Commande terminée',
                    timer:5000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                ); 
            }
            else{  
                $this->dispatch('alert',                    
                    title:'Vous n\'êtes pas autorisé à effectuer cette opération!',
                    timer:3000,
                    icon:'error',
                    toast:true,
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
                showConfirmButton: true,
                position:'center',
            );  
        }
    }
    public function reinitialiser(string $idx){ 
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->changer_statut_cmd_cuisine;
            if($autoriser == 1){
                $statut = 'A préparer'; 
                $statut_cuisine = ''; 
                TableRestau::where('reference',$idx)->update(['statut'=>$statut]);
                RestauCommandeAttenteLigne::where('ref_table',$idx)->update(['statut_cuisine'=>$statut_cuisine]);
                $this->dispatch('alert',                    
                    title:'Commande à préparer',
                    timer:5000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                ); 
            }
            else{  
                $this->dispatch('alert',                    
                    title:'Vous n\'êtes pas autorisé à effectuer cette opération!',
                    timer:3000,
                    icon:'error',
                    toast:true,
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
                showConfirmButton: true,
                position:'center',
            );  
        }
    }
    public function barrerAllProd(string $idz){  
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->changer_statut_cmd_cuisine;
            if($autoriser == 1){       
                $statut_cuisine = 'Barrer'; 
                RestauCommandeAttenteLigne::where('ref_table',$idz)->update(['statut_cuisine'=>$statut_cuisine]);
        }
            else{  
                $this->dispatch('alert',                    
                    title:'Vous n\'êtes pas autorisé à effectuer cette opération!',
                    timer:3000,
                    icon:'error',
                    toast:true,
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
                showConfirmButton: true,
                position:'center',
            );  
        }            
    }
}
