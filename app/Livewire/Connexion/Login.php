<?php

namespace App\Livewire\Connexion;

use Livewire\Component;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Licence;
use App\Models\Utilisateur;
use App\Models\Entite;
use App\Helpers\LogActivity;

class Login extends Component
{
    public $email;
    public $password;
    public $name;    

    private function resetInputFields(){
        $this->name = '';
        $this->email = '';
        $this->password = '';
    }  

    public function render()
    { 
        $title = 'Connexion | WamsCo';
        $demo = request('demo');  
        $email = request('email');  
        $nom_utilisateur = request('user');

        if($email){
            $verifierUser = Utilisateur::where('email', $email)->count(); 
            if($verifierUser > 0) {
                $confirmer = 1;
                $uti = Utilisateur::where('email',$email)->first();
                $id_user =  $uti->id;

                Utilisateur :: where('email', $email)->update(['confirmer'=>$confirmer]);

                $id_activite = $id_user;
                $page = 'Utilisateur';
                LogActivity::addToLog('Email » '.$email.' a été confirmé avec succès', $id_activite, $page);

                flash ('M./Mme <strong>'.$nom_utilisateur.'</strong>, votre email a été confirmé avec succès. Veuillez vous connecter svp!')->success();
                return view('livewire.connexion.login',compact('demo'))->layout('components.layouts.connexion',compact('title'));
            }
            else{
                flash ('<strong>Désolé</strong>, cette adresse email (<strong>'.$email.'</strong>) n\'existe plus ou pas dans notre système !')->error();
                return view('livewire.connexion.login',compact('demo'))->layout('components.layouts.connexion',compact('title'));
            }
        }
        else{
            return view('livewire.connexion.login',compact('demo'))->layout('components.layouts.connexion',compact('title'));
        }
    }
    public function login()
    {      
        $licence = Licence ::where('id',1)->get();
        $licenceBD = $licence[0]->licence;
        $WDate_evaluation = $licence[0]->evaluation;
        $WDate_main = $licence[0]->main;
        $licenceOK ="a28ba79w40-1419-wc-oss19";
        $WamsCo = "WamsCo";
        $dateJour = date('Y-m-d');

        if($licenceBD == $WamsCo){
            if($dateJour <= $WDate_evaluation){ 

                $this->validate(['email'=>'required|email','password'=>'required|min:8']);                    
                $resultat = auth()->attempt(['email'=>$this->email,'password'=>$this->password]);
                if($resultat){
                    if(auth()->user()->confirmer == 1){
                        if(auth()->user()->date_valide >= $dateJour and auth()->user()->etat == 1){ 
                            toast()->success('Bienvenue M/Mme '.auth()->user()->name.'! Votre version d\'évaluation expire le: ' .date('d-m-Y', strtotime($WDate_evaluation)). ', veuillez activer votre licence!!!')->position('bottom-right')->autoClose(9000)->background('#fff')->width('420px')->padding('5px'); 
                            \Log::info("Connexion effectuée [".auth()->user()->email."]");
                            $id_activite = 0;
                            $page = 'Connexion';
                            LogActivity::addToLog('Connexion application', $id_activite, $page);
                            return redirect('/bienvenue?active=1');                        
                        }
                        else{
                            auth()->logout(); // deconnecter user (tres important)
                            flash ('Désolé, Vous n\'êtes plus autorisé à vous connecter!')->error(); 
                            return back();
                        }
                    }
                    else{
                        flash ('Désolé, M./Mme <strong>'.auth()->user()->name.'</strong>, un e-mail de confirmation vous a été envoyé!')->error();
                        return back();
                    }
                 }
                 else{                     
                    flash ('Vos identifiants (email ou password) sont incorrects')->error(); 
                    return back();
                 }
            }
            else{
                flash ('Vous n\'avez plus access, votre version d\'évaluation est expirée! Contactez votre fournisseur (+237 654 258 009 ou 655 423 118)')->error();             
                return back();
            }             
        }
        elseif($licenceBD == $licenceOK){
                if($dateJour <= $WDate_main){ 

                    $this->validate(['email'=>'required|email','password'=>'required|min:8']);                    
                    $resultat = auth()->attempt(['email'=>$this->email,'password'=>$this->password]);
                    if($resultat){ 
                        // $societe = Entite::where('enseigne',auth()->user()->societe)->first();
                        $societe = Entite::where('id',auth()->user()->societe_id)->first();
                        $active = $societe->active;
                        if($active == 1){
                            if(auth()->user()->confirmer == 1){                            
                                if(auth()->user()->date_valide >= $dateJour and auth()->user()->etat == 1){
                                    toast()->success('Bienvenue M/Mme '.auth()->user()->name.', content de vous revoir!')->position('bottom-right')->autoClose(10000)->background('#fff')->width('420px')->padding('5px'); 
                                    \Log::info("Connexion effectuée [".auth()->user()->email."]");
                                    $id_activite = 0;
                                    $page = 'Connexion';
                                    LogActivity::addToLog('Connexion application', $id_activite, $page);                                
                                    return $this->redirect('/bienvenue?active=1', navigate: false);
                                }
                                else{
                                    auth()->logout(); // deconnecter user (tres important)
                                    flash ('Désolé, Vous n\'êtes plus autorisé à vous connecter!')->error(); 
                                    return back();
                                } 
                            }
                            else{
                                flash ('Désolé, M./Mme <strong>'.auth()->user()->name.'</strong>, un e-mail de confirmation vous a été envoyé!')->error();
                                return back();
                            }
                        }
                        else{
                            flash ('<strong>Désolé, votre compte a été désactivé.</strong> Veuillez contacter votre fournisseur!')->error();
                            return back();
                        }
                    }
                    else{  
                        flash ('Vos identifiants (email ou password) sont incorrects')->error(); 
                        return back();
                     }
                }
                else{
                    flash ('Veuillez effectuer une maintenance du systeme svp!!!')->error();              
                    return back();
                }
        }
        else{                               
            flash ('Violation des contraintes d\'integrité !!!')->error();   
            return back();           
        } 
          
    }
}
