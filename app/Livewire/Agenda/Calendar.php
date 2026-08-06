<?php

namespace App\Livewire\Agenda;

use Livewire\Component;
use App\Helpers\LogActivity;
use Illuminate\Support\Arr;
use App\Models\Entite;
use App\Models\Agenda;

class Calendar extends Component
{
    public $agendas = [];

    public function render()
    {   
        $active = request('active');
        $champ = request('champ');
        $choix = request('choix');        
        $title = 'Agenda | WamsCo';
        $dateJour = date('Y-m-d');
        LogActivity::addToLog('Voir Agenda');    
        $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get(); 
        $jourValid = $entite_mod[0]->validite_mod; 
        $soldeClient = $entite_mod[0]->solde;
        // ceci pour trouver le nombre de jour restant avant expiration
        $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24)); 
        toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px'); 

        $this->agendas = json_encode(Agenda::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->get());
        return view('livewire.agenda.calendar')->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));  
    }
    public function eventChange($event)
    {
        $e = Agenda::find($event['id']);
        $e->start = $event['start'];
        if(Arr::exists($event, 'end')) {
            $e->end = $event['end'];
        }
        $e->save();
    }
    
    public function eventAdd($event)
    {        
        $agendas = new Agenda;
        $agendas->title = $event['title'];
        $agendas->start = $event['start'];
        $agendas->end = $event['end'];
        $agendas->societe = auth()->user()->societe;
        $agendas->user_id = auth()->user()->id;
        $agendas->nom_user = auth()->user()->name;
        $agendas->save();
        LogActivity::addToLog('Ajouter Agenda');    
        return redirect('/calendrier?active=9'); // Ceci pour eviter erreur sur eventChange (oblige de recharger la page) 
    }
    public function eventRemove($id)
    {
        Agenda::destroy($id);
        LogActivity::addToLog('Supprimer Agenda');    
    }
}
