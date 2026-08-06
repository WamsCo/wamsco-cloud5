<?php

namespace App\Livewire;

use Livewire\Component;
use RealRashid\SweetAlert\Facades\Alert;
use Carbon\Carbon;
use App\Helpers\LogActivity;
use App\Models\Entite;
use App\Models\DeviseTva;
// use App\Models\Role;

class Bienvenue extends Component
{       
    // public function mount(){
    //     $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
    //     if($test > 0){
    //         $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
    //         $autoriser = $role[0]->tablobord_pv;
    //         if($autoriser == 0){
    //             alert()->error('Oups Désolé', 'Vous n\'êtes pas autorisé à ouvrir cette page !!!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
    //             $this->redirect('/bienvenue', navigate: true);
    //         }
    //     }
    //     else{
    //         alert()->error('Oups Désolé', 'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
    //         $this->redirect('/bienvenue', navigate: true);
    //     } 
    //     $this->date_debut = date('Y-m-d');
    //     $this->date_fin = date('Y-m-d');  
    // }
    public function render()
    {
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();
        $jourValid = $entite_mod[0]->validite_mod; 
        $soldeClient = $entite_mod[0]->solde; 
        if($dateJour <= $jourValid){
            $active = request('active');  
            $champ = request('champ');
            $title = 'Bienvenue | WamsCo';
            $module = 'Home';
            $title_fils = 'Bienvenue';
            $lien = 'bienvenue';
            $dateJour = date('Y-m-d');             
             
            $deviseTva = DeviseTva :: where('societe',auth()->user()->societe)->limit(1)->orderBy('id','asc')->count(); 
            if($deviseTva == 0){
                $this->devise = 'FCFA';
            }
            else{
                $deviseTva = DeviseTva :: where('societe',auth()->user()->societe)->limit(1)->orderBy('id','asc')->get(); 
                $this->devise = $deviseTva[0]->devise;
            }
            $id_activite = 0;
            $page = 'Bienvenue';
            LogActivity::addToLog('Bloc modules', $id_activite, $page);    
            $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get(); 
            $jourValid = $entite_mod[0]->validite_mod; 
            $soldeClient = $entite_mod[0]->solde; 
            // ceci pour trouver le nombre de jour restant avant expiration
            $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24));  

            //ceci permet de creer le lien symboliqiue dans le cas d'appli avec setup
            // \File::link(storage_path('app/public'), public_path('storage'));             
            return view('livewire.bienvenue',compact('dateJour','title_fils','entite_mod',))->layout('components.layouts.app',compact('title','module','title_fils','lien','active','champ','entite_mod','dateJour','soldeClient','nbjoursRestant'));  
        }
        else{
            $title = 'Bienvenue  | WamsCo'; 
            $module = 'Home';
            $title_fils = 'Bienvenue';
            $lien = 'bienvenue';
            $active = request('active');
            $champ = request('champ');
            $choix = request('choix');  
            $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24)); 
            // alert()->error(...);
            toast()->error('Bonjour M/Mme <strong>'.auth()->user()->name.'!</strong><br> Votre accès a <strong>expiré le: ' .date('d-m-Y', strtotime($jourValid)). '.</strong> <br>Veuillez renouveller votre abonnement en cliquant sur un module svp !')->position('center')->autoClose(50000)->background('#fff')->width('520px')->padding('5px'); 
            return view('livewire.bienvenue',compact('dateJour','entite_mod'))->layout('components.layouts.app',compact('title','module','title_fils','lien','active','entite_mod','dateJour','soldeClient','nbjoursRestant')); 
        }
    }
}
