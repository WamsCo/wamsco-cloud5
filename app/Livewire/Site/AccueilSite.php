<?php

namespace App\Livewire\Site;

use Livewire\Component;

class AccueilSite extends Component
{
    public function render()
    {
        if(auth()->guest()){
            $title = 'WamsCo Cloud | WamsCo';
            // $macAddr = exec('getmac'); // ceci recupere l'adresse Mac de la machine
            return view('livewire.site.accueil-site')->layout('components.layouts.app_site', compact('title'));  
        } 
        else{ 
            $title = 'WamsCo Cloud | WamsCo';
            // $macAddr = exec('getmac');  // ceci recupere l'adresse Mac de la machine
            return view('livewire.site.accueil-site')->layout('components.layouts.app_site', compact('title')); 
        }
    }
}
