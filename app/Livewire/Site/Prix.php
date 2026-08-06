<?php

namespace App\Livewire\Site;

use Livewire\Component;

class Prix extends Component
{
    public function render()
    {
        if(auth()->guest()){

            $title = 'Nos Tarifs | WamsCo';
            return view('livewire.site.prix')->layout('components.layouts.app_site', compact('title'));     
        } 
        else{ 
            $title = 'Nos Tarifs | WamsCo';
            return view('livewire.site.prix')->layout('components.layouts.app_site', compact('title')); 
        } 
    }
}
