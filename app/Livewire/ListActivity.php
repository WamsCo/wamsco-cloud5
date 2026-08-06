<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Validate; 
use App\Helpers\LogActivity;
use Livewire\WithPagination;
use App\Models\Entite;
use App\Models\Role;

class ListActivity extends Component
{
    protected $listeners = [
        'refreshComponent' => '$refresh'
    ];
    protected $paginationTheme = 'bootstrap';    // ceci pour pagination avec les chiffres tres important
    use WithPagination;
    public $confirmer; 
    public array $selection = [];
    // public $parPage = 20;

    public function mount(){ 
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_entite;
            if($autoriser == 0){
                alert()->error('Oups Désolé', 'Vous n\'êtes pas autorisé à ouvrir cette page !!!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
                $this->redirect('/bienvenue', navigate: true);
            }
        }
        else{
            alert()->error('Oups Désolé', 'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
            $this->redirect('/bienvenue', navigate: true);
        }             
    }
    public function render()
    {                  
        $title = 'Journal d\'activités | WamsCo';
        $module = 'Activités';
        $title_fils = 'Activités';
        $lien = 'logActivity';
        $active = request('active');
        $champ = request('champ');
        $dateJour = date('Y-m-d');
        $background = 'background4';
        toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');       
        $log = LogActivity::logActivityLists();
        $logCount = LogActivity::logActivityLists()->count();
        $logTotal = LogActivity::logActivityLists()->total();
        
        $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get(); 
        $jourValid = $entite_mod[0]->validite_mod; 
        $soldeClient = $entite_mod[0]->solde;
        // ceci pour trouver le nombre de jour restant avant expiration
        $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24));
        return view('livewire.list-activity',compact('title_fils','module','lien','dateJour','log','logCount','logTotal'))->layout('components.layouts.app',compact('title','active','champ','entite_mod','dateJour','soldeClient','nbjoursRestant')); 
    }
    public function supprimer(int $id){        
        
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->supprimer_entite;
            if($autoriser == 1){
                \App\Models\LogActivity::find($id)->delete();
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
                    title:'Vous n\'êtes pas autorisé à effectuer cette opération !!!',
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
    public function deleteLog(array $ids){        
        \App\Models\LogActivity::destroy($ids);
        $this->selection = [];
        $this->dispatch('alert',                    
            title:'Suppression effectuée!',
            timer:3000,
            icon:'success',
            toast:true,
            showConfirmButton: false,
            position:'top-end',
        ); 
    } 
    public function confirmerDelete($id){
        $this->confirmer = $id;        
    } 
    public function effacerall(){          
        // ceci supprime  toute la table
        // \App\Models\LogActivity::truncate();

        // $this->redirect('/logActivity?active=12&champ=1-9', navigate: true);
        $this->dispatch('alert',                    
            // title:'Suppression totale effectuée!',
            title:'<strong>Historique des données... </strong> Impossible de tout supprimer d\'un coup !',
            timer:10000,
            icon:'warning',
            toast:true,
            showConfirmButton: false,
            position:'top-end',
        );    
    } 
}
