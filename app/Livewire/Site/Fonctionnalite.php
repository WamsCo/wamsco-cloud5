<?php

namespace App\Livewire\Site;

use Livewire\Component;

class Fonctionnalite extends Component
{
    public function render()
    {
        if(auth()->guest()){

            $title = 'Nos fonctionnalités | WamsCo';
            return view('livewire.site.fonctionnalite')->layout('components.layouts.app_site', compact('title'));     
        } 
        else{ 
            $title = 'Nos fonctionnalités | WamsCo';
            return view('livewire.site.fonctionnalite')->layout('components.layouts.app_site', compact('title')); 
        } 
    }
}
