<?php

namespace App\Livewire\Connexion;

use Livewire\Component;
use App\Helpers\LogActivity;
Use Carbon\Carbon;
use App\Mail\PasswordMail;
use Mail;
use App\Models\Utilisateur;
use App\Models\Entite;

class ForgotPassword extends Component
{
   public $email;

    public function render()
    {
        $title = 'Forgot Password | WamsCo'; 
        toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');
        return view('livewire.connexion.forgot-password',compact('title'))->layout('components.layouts.connexion',compact('title'));
    }
     public function send(){
        $this->validate(['email'=>'required|email']);    
        $resultat = Utilisateur::where('email', $this->email)->count();        
        if($resultat > 0){  
            try {
                $length = 5;
                $token = bin2hex(random_bytes($length));
                $password = bcrypt($token);

                $user = Utilisateur::where('email', $this->email)->get();  
                $email = $user[0]->email;
                $name = $user[0]->name;
                $societe = $user[0]->societe;
                $societe_id = $user[0]->societe_id;

                $entite_all = Entite::where('id',$societe_id)->get();
                $logo = $entite_all[0]->logo; 

                $date = date('d-m-Y H:i:s');           
                $body = [
                    'entite'=>$societe,
                    'date'=>$date,              
                    'email'=>$email,
                    'name'=>$name,
                    'password'=>$token, 
                    'url_a'=>'http://wamsco-cloud.net',
                    'logo'=>'https://wamsco-cloud.net/storage/'.$logo,
                ];
                
                Utilisateur::where('email', $this->email)->update(['password'=>$password]);
                Mail::to($this->email)->send(new PasswordMail($body)); 
                flash ('Félicitations M./Mme <strong>'.$name.'</strong>, un email avec votre mot de passe vous a été envoyé à cette adresse: '.$this->email.'!')->success();
                $this->dispatch('alert',                    
                    title:'Félicitations M./Mme <strong>'.$name.'</strong>, un email avec votre mot de passe vous a été envoyé à cette adresse: '.$this->email.'!',
                    timer:15000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                ); 
                $id_activite = 0;
                $page = 'Password changé';
                LogActivity::addToLog('Password changé ['.$this->email.']', $id_activite, $page);
                $this->email ='';            
            } 
            catch (\Exception $e) {                
                flash ('Erreur lors de l\'envoi d\'email : cette adresse ('.$this->email.') semble invalide ou le domaine n\'existe pas !')->error();
                $this->email =''; 
                // return "Erreur technique lors de l'envoi : " . $e->getMessage();
            }
        }
        else{   
            flash ('Désolé, l\'adresse email ('.$this->email.') n\'existe plus ou pas dans notre système !')->error();             
            $this->email ='';
        }  
    }
}
