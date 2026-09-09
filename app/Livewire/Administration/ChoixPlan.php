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

class ChoixPlan extends Component
{
    public $devise;

    public function render()
    {
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('id',auth()->user()->societe_id)->get();
        $jourValid = $entite_mod[0]->validite_mod; 
        $soldeClient = $entite_mod[0]->solde;            
        $title = 'Choix Plan '.auth()->user()->societe.' | WamsCo';
        $module = 'Paramètres';
        $title_fils = 'Choix Plan '. auth()->user()->societe;
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
        return view('livewire.administration.abonnement.choix-plan',compact('title_fils','module','lien','dateJour','soldeClient','derniereActivite','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));                       
    }
}
