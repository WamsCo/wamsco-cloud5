<?php

namespace App\Livewire\Connexion;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate; 
use App\Helpers\LogActivity;
use Livewire\WithPagination;
use App\Models\Utilisateur;
use App\Models\Role;
use App\Models\Entite;
use App\Models\Categorie;
use App\Models\Entrepot;
use App\Models\Tier;
use App\Models\Produit;
use App\Models\Stock;
use Mail;
use App\Mail\ConfirmationMail;


class Inscription extends Component
{
    // pour creer le role automatique
        public $nom ='Administrateur', $societe, $nom_user , $user_id, $description='Administrateur du systeme'; 
        
        // Tiers
        public $consulter_tier = true, $creer_tier = true, $modifier_tier = true, $supprimer_tier = true; 

        //stock
        public $consulter_produit = true, $creer_produit = true, $modifier_produit = true, $supprimer_produit = true;
        public $consulter_categorie = true, $creer_categorie = true, $modifier_categorie = true, $supprimer_categorie = true;
        public $mouvement_stock = true, $correction_stock = true, $consulter_entrepot = true, $creer_entrepot = true, $modifier_entrepot = true, $supprimer_entrepot = true;    
        public $consulter_transfert = true, $creer_transfert = true, $modifier_transfert = true, $supprimer_transfert = true; 
        public $consulter_inventaire = true, $creer_inventaire = true, $modifier_inventaire = true, $supprimer_inventaire = true; 

        // Point de vente   
        public $tablobord_pv = true, $consulter_session_pv = true, $creer_session = true, $voir_session_autre = true, $pv = true;
        public $gerer_cmd_attente = true, $voir_ecran_cuisine  = true, $changer_statut_cmd_cuisine  = true, $reservation  = true;
        public $modifier_fidelite  = true, $consulter_emplacement = true, $creer_emplacement = true, $modifier_emplacement = true, $supprimer_emplacement = true;

        // Commande
        public $consulter_commande= true, $creer_commande= true, $modifier_commande= true, $supprimer_commande= true, $consulter_expedition= true, $creer_expedition= true, $supprimer_expedition = true;    
        public $consulter_com_fourni= true, $creer_com_fourni= true, $modifier_com_fourni= true, $supprimer_com_fourni= true, $consulter_reception= true, $creer_reception= true, $supprimer_reception = true;
        
        // Facturation
        public $consulter_facture = true, $creer_facture= true, $modifier_facture= true, $supprimer_facture= true, $consulter_reglement= true, $creer_reglement= true, $supprimer_reglement = true;
        public $consulter_fact_fourni = true, $creer_fact_fourni= true, $modifier_fact_fourni= true, $supprimer_fact_fourni= true, $consulter_reglement_fourni= true, $creer_reglement_fourni= true, $supprimer_reglement_fourni = true;
        
        // Banque & Caisse
        public $consulter_compte = true, $creer_compte= true, $modifier_compte= true, $supprimer_compte= true, $consulter_ecriture= true, $mod_ecriture= true, $consulter_paie_divers= true, $creer_paie_divers= true,
               $modifier_paie_divers= true, $supprimer_paie_divers= true, $voir_marge = true ,$effectuer_vire_interne = true;
        
            // Multi societe
        public $consulter_societe= true, $creer_societe= true, $modifier_societe= true, $changer_filiale= true, $transfer_stock_filiale= true, $consulter_transfert_filiale = true;
        
        // Entite
        public $consulter_entite= true, $creer_entite= true, $modifier_entite= true, $supprimer_entite= true, $activer_compte_enite = true;
        
        // Utilisateur
        public $consulter_user= true, $creer_user= true, $modifier_user= true, $supprimer_user = true;
        
        // Role
        public $consulter_role= true, $creer_role= true, $modifier_role= true, $supprimer_role = true;
        
        // Depart / Poste
        public $consulter_depart_poste= true, $creer_depart_poste= true, $modifier_depart_poste= true, $supprimer_depart_poste = true;
        
        // config 
        public $configurer = true;

        // CRM
        public $consulter_opportunite = true, $creer_opportunite = true, $detail_opportunite = true, $modifier_opportunite = true, $supprimer_opportunite = true;                          
        public $consulter_etape = true, $creer_etape = true, $modifier_etape = true, $supprimer_etape = true;

        // Ticket
        public $consulter_ticket = true, $creer_ticket = true, $modifier_ticket = true, $supprimer_ticket = true;
        
        // Taches
        public $consulter_tache = true, $creer_tache = true, $detail_tache = true, $modifier_tache = true, $supprimer_tache = true;                          
        public $consulter_etapeTache = true, $creer_etapeTache = true, $modifier_etapeTache = true, $supprimer_etapeTache = true;

    // fin

    public $nom_utilisateur;
    public $email;
    // public $societe;
    public $telephone;
    public $pays = 'Cameroon';
    public $ville;
    public $password;
    public $password_confirmation;
    public $captcha;

        // Module
    public $mod_pointe_vente = true;
    public $mod_cuisine = false;    
    public $mod_administration = true;
    public $mod_gestion_tier = true;
    public $mod_crm = true;
    public $mod_fabrication = false;
    public $mod_gestion_stock = true;
    public $mod_cmd = true;  
    public $mod_banque_caisse = true;
    public $mod_facturation= true;
    public $mod_multisociete = false;
    public $mod_ticket = true;
    public $mod_tache = true;

    public $validite_mod;
    public $created_at;
    public $nbre_user_max;
    public $nombre_users;

    public $nombre_societe;
    public $montant_paye;
    public $periode;
    public $recommandation;
    public $taux_commission;
    public $montant_commission;
    public $etat_commission;
    public $condition_vente = 'Les marchandises vendues ne sont ni reprises ni échangées.';

    public $choix_plan; // ceci rentre dans periode
    public function render()
    {
        $plan = request('plan');
        if($plan == 'Independant Mensuel'){            
            $this->choix_plan = 'Independant Mensuel';
        }
        elseif($plan == 'Independant Annuel') { 
            $this->choix_plan = 'Independant Annuel';           
        }
        elseif($plan == 'Standard Mensuel') { 
            $this->choix_plan = 'Standard Mensuel';            
        }        
        elseif($plan == 'Standard Annuel') { 
            $this->choix_plan = 'Standard Annuel';            
        }
        elseif($plan == 'Essai Gratuit') { 
            $this->choix_plan = 'Essai Gratuit';            
        }

        if(auth()->guest()){            
            $title = 'Inscription | WamsCo';
            // flash ('Merci de vous s\'inscrire ici !!!')->error(); 
            return view('livewire.connexion.inscription')->layout('components.layouts.connexion', compact('title'));     
        } 
        else{                
            $title = 'Inscription | WamsCo';     
            flash ('Inscription d\'un nouveau compte !!!')->warning(); 
            return view('livewire.connexion.inscription')->layout('components.layouts.connexion', compact('title'));  
        }
    }
    public function store(){ 
        
        $this->validate([ 
            // 'profil'=>=>'required|image|mimes:jpeg,jpg,png,gif|max:2048',  
            'nom_utilisateur'=>'required|max:255',
            'email'=>'required|max:255|email|unique:utilisateurs,email,{$utilisateurs->id}',
            'societe'=>'required|max:100|unique:entites,enseigne,{$entites->id}',
            'telephone'=>'required|max:255',
            'pays'=>'required|max:200',
            'ville'=>'required|max:200',                            
            'password'=>'required|confirmed|min:8|max:255',
            'password_confirmation'=>'required|max:255',
            'choix_plan'=>'required|max:100',
            'captcha'=>'required|numeric|max:255',            
        ]); 
        if($this->captcha == 9){
                try {
                    $dateJour = date('Y-m-d'); 
                    $nbrJour = 14;            
                    $this->validite_mod = date('Y-m-d',strtotime("+$nbrJour days", strtotime($dateJour)));

                    $activer = 1;
                    $dateJour = date('Y-m-d');
                    $nbjoursRestant = round((strtotime($this->validite_mod) - strtotime($dateJour))/(60*60*24));
                    $this->nbre_user_max = 1;
                    $this->nombre_users = 1; 
                    $this->nombre_societe = 1;
                    $this->montant_paye = 0;            
                    $this->recommandation ='contact@wamsco-cloud.net';
                    $this->taux_commission = 0;
                    $this->montant_commission = 0;
                    $this->etat_commission = 'Gratuit';
                    $solde = 0;
                    $sexe = 'Non-défini';
                    $salarie = 0;        
                    
                    // ceci cree le slug : le lien (sous-domaines dynamiques plutard: boutique-abc.wamsco-cloud.net / supermarche-x.wamsco-cloud.net / pharmacie-y.wamsco-cloud.net.)
                    $slug = Str::slug($this->societe);
                    $originalSlug = $slug;
                    $count = 1;

                    while (Entite::where('slug', $slug)->exists()) {
                        $slug = $originalSlug . '-' . $count++;
                    }
                                 
                    $entit = Entite :: create(['solde'=>$solde,'enseigne'=>$this->societe,'societe_mere'=>$this->societe,'raison_sociale'=>$this->societe,'slug'=>$slug,'telephone'=>$this->telephone,'ville'=>$this->ville,
                            'pays'=>$this->pays,'active'=>$activer,'mod_pointe_vente'=>$this->mod_pointe_vente,'mod_cuisine'=>$this->mod_cuisine,'mod_administration'=>$this->mod_administration,'mod_gestion_tier'=>$this->mod_gestion_tier,'mod_crm'=>$this->mod_crm,
                            'mod_fabrication'=>$this->mod_fabrication,'mod_gestion_stock'=>$this->mod_gestion_stock,'mod_banque_caisse'=>$this->mod_banque_caisse,'mod_facturation'=>$this->mod_facturation,
                            'mod_cmd'=>$this->mod_cmd,'mod_multisociete'=>$this->mod_multisociete,'mod_ticket'=>$this->mod_ticket,'mod_tache'=>$this->mod_tache,'validite_mod'=>$this->validite_mod,'jour_restant'=>$nbjoursRestant,'nbre_user_max'=>$this->nbre_user_max,
                            'nombre_users'=>$this->nombre_users,'nombre_societe'=>$this->nombre_societe,'montant_paye'=>$this->montant_paye,'periode'=>$this->choix_plan,'recommandation'=>$this->recommandation,
                            'taux_commission'=>$this->taux_commission,'montant_commission'=>$this->montant_commission,'etat_commission'=>$this->etat_commission,
                            'nom_user'=>$this->email,'user_id'=>0]); 

                    // Recupere dernier Entite creer actuelement
                    $dernier_id = $entit->id;   // plus fiable          

                    // $moisJour = '12-28';
                    // $date_valide = date('Y-'.$moisJour);
                    $nbreMois = 12;
                    $date_valide = date('Y-m-d', strtotime('+'.$nbreMois.'month'));
                    
                    $users = Utilisateur:: create(['email'=>$this->email,'name'=>$this->nom_utilisateur,'telephone'=>$this->telephone,'password'=>bcrypt($this->password),'type_user'=>$this->nom,
                    'date_valide'=>$date_valide,'etat'=>$activer,'sexe'=>$sexe,'salarie'=>$salarie,'societe'=>$this->societe,'societe_id'=>$dernier_id,'societe_mere'=>$this->societe,'societe_mere_id'=>$dernier_id,
                    'nom_user'=>$this->email,'user_id'=>0]);

                    // Recupere dernier utilisateur creer actuelement
                    $idUser = $users->id;  

                    Utilisateur::find($idUser)->update(['user_id'=>$idUser]); // ceci pour avoir id de celui qui a creer : qui est lui meme                    
                    Entite::find($dernier_id)->update(['user_id'=>$idUser,'societe_mere_id'=>$dernier_id,]); 

                    $description_role = $this->description.' '.$this->societe;            
                    Role::create(['nom'=>$this->nom,'societe'=>$this->societe,'societe_id'=>$dernier_id,'description'=>$description_role,'nom_user'=>$this->nom_utilisateur,'user_id'=>$idUser,
                                'consulter_tier'=>$this->consulter_tier,'creer_tier'=>$this->creer_tier,'modifier_tier'=>$this->modifier_tier, 'supprimer_tier'=>$this->supprimer_tier,
                                'consulter_produit'=>$this->consulter_produit,'creer_produit'=>$this->creer_produit,'modifier_produit'=>$this->modifier_produit,'supprimer_produit'=>$this->supprimer_produit,
                                'consulter_categorie'=>$this->consulter_categorie,'creer_categorie'=>$this->creer_categorie,'modifier_categorie'=>$this->modifier_categorie,'supprimer_categorie'=>$this->supprimer_categorie,
                                'mouvement_stock'=>$this->mouvement_stock,'correction_stock'=>$this->correction_stock,'consulter_entrepot'=>$this->consulter_entrepot,'creer_entrepot'=>$this->creer_entrepot,'modifier_entrepot'=>$this->modifier_entrepot,'supprimer_entrepot'=>$this->supprimer_entrepot,                           
                                'consulter_transfert'=>$this->consulter_transfert,'creer_transfert'=>$this->creer_transfert,'modifier_transfert'=>$this->modifier_transfert,'supprimer_transfert'=>$this->supprimer_transfert,
                                'consulter_inventaire'=>$this->consulter_inventaire,'creer_inventaire'=>$this->creer_inventaire,'modifier_inventaire'=>$this->modifier_inventaire,'supprimer_inventaire'=>$this->supprimer_inventaire,
                                'tablobord_pv'=>$this->tablobord_pv,'consulter_session_pv'=>$this->consulter_session_pv,'creer_session'=>$this->creer_session,'voir_session_autre'=>$this->voir_session_autre,'pv'=>$this->pv,
                                'gerer_cmd_attente'=>$this->gerer_cmd_attente,'voir_ecran_cuisine'=>$this->voir_ecran_cuisine,'changer_statut_cmd_cuisine'=>$this->changer_statut_cmd_cuisine,'reservation'=>$this->reservation,
                                'modifier_fidelite'=>$this->modifier_fidelite,'consulter_emplacement'=>$this->consulter_emplacement,'creer_emplacement'=>$this->creer_emplacement,'modifier_emplacement'=>$this->modifier_emplacement,'supprimer_emplacement'=>$this->supprimer_emplacement,
                                'consulter_commande'=>$this->consulter_commande,'creer_commande'=>$this->creer_commande,'modifier_commande'=>$this->modifier_commande,'supprimer_commande'=>$this->supprimer_commande,'consulter_expedition'=>$this->consulter_expedition,'creer_expedition'=>$this->creer_expedition,'supprimer_expedition'=>$this->supprimer_expedition,
                                'consulter_com_fourni'=>$this->consulter_com_fourni,'creer_com_fourni'=>$this->creer_com_fourni,'modifier_com_fourni'=>$this->modifier_com_fourni,'supprimer_com_fourni'=>$this->supprimer_com_fourni,'consulter_reception'=>$this->consulter_reception,'creer_reception'=>$this->creer_reception,'supprimer_reception'=>$this->supprimer_reception,
                                'consulter_facture'=>$this->consulter_facture,'creer_facture'=>$this->creer_facture,'modifier_facture'=>$this->modifier_facture,'supprimer_facture'=>$this->supprimer_facture,'consulter_reglement'=>$this->consulter_reglement,'creer_reglement'=>$this->creer_reglement,'supprimer_reglement'=>$this->supprimer_reglement,
                                'consulter_fact_fourni'=>$this->consulter_fact_fourni,'creer_fact_fourni'=>$this->creer_fact_fourni,'modifier_fact_fourni'=>$this->modifier_fact_fourni,'supprimer_fact_fourni'=>$this->supprimer_fact_fourni,'consulter_reglement_fourni'=>$this->consulter_reglement_fourni,'creer_reglement_fourni'=>$this->creer_reglement_fourni,
                                'supprimer_reglement_fourni'=>$this->supprimer_reglement_fourni,'consulter_compte'=>$this->consulter_compte,'creer_compte'=>$this->creer_compte,'modifier_compte'=>$this->modifier_compte,'supprimer_compte'=>$this->supprimer_compte,'consulter_ecriture'=>$this->consulter_ecriture,'mod_ecriture'=>$this->mod_ecriture,
                                'consulter_paie_divers'=>$this->consulter_paie_divers,'creer_paie_divers'=>$this->creer_paie_divers,'modifier_paie_divers'=>$this->modifier_paie_divers,'supprimer_paie_divers'=>$this->supprimer_paie_divers,'voir_marge'=>$this->voir_marge,'effectuer_vire_interne'=>$this->effectuer_vire_interne,
                                'consulter_societe'=>$this->consulter_societe,'creer_societe'=>$this->creer_societe,'modifier_societe'=>$this->modifier_societe,'changer_filiale'=>$this->changer_filiale,'transfer_stock_filiale'=>$this->transfer_stock_filiale,'consulter_transfert_filiale'=>$this->consulter_transfert_filiale,
                                'consulter_entite'=>$this->consulter_entite,'creer_entite'=>$this->creer_entite,'modifier_entite'=>$this->modifier_entite,'supprimer_entite'=>$this->supprimer_entite,'activer_compte_enite'=>$this->activer_compte_enite,
                                'consulter_user'=>$this->consulter_user,'creer_user'=>$this->creer_user, 'modifier_user'=>$this->modifier_user,'supprimer_user'=>$this->supprimer_user,
                                'consulter_role'=>$this->consulter_role,'creer_role'=>$this->creer_role, 'modifier_role'=>$this->modifier_role,'supprimer_role'=>$this->supprimer_role,   
                                'consulter_depart_poste'=>$this->consulter_depart_poste,'creer_depart_poste'=>$this->creer_depart_poste,'modifier_depart_poste'=>$this->modifier_depart_poste,
                                'supprimer_depart_poste'=>$this->supprimer_depart_poste,'configurer'=>$this->configurer,  
                                'consulter_opportunite'=>$this->consulter_opportunite,'creer_opportunite'=>$this->creer_opportunite,'detail_opportunite'=>$this->detail_opportunite,'modifier_opportunite'=>$this->modifier_opportunite,'supprimer_opportunite'=>$this->supprimer_opportunite,
                                'consulter_etape'=>$this->consulter_etape,'creer_etape'=>$this->creer_etape,'modifier_etape'=>$this->modifier_etape,'supprimer_etape'=>$this->supprimer_etape, 
                                'consulter_ticket'=>$this->consulter_ticket,'creer_ticket'=>$this->creer_ticket,'modifier_ticket'=>$this->modifier_ticket,'supprimer_ticket'=>$this->supprimer_ticket,
                                'consulter_tache'=>$this->consulter_tache,'creer_tache'=>$this->creer_tache,'detail_tache'=>$this->detail_tache,'modifier_tache'=>$this->modifier_tache,'supprimer_tache'=>$this->supprimer_tache,
                                'consulter_etapeTache'=>$this->consulter_etapeTache,'creer_etapeTache'=>$this->creer_etapeTache,'modifier_etapeTache'=>$this->modifier_etapeTache,'supprimer_etapeTache'=>$this->supprimer_etapeTache,
                        ]);  

                    $nom_categorie = 'Non categorise';
                    Categorie :: create(['nom_categorie'=>$nom_categorie,'description'=>$nom_categorie,'societe'=>$this->societe,'societe_id'=>$dernier_id,'nom_user'=>$this->email,'user_id'=>$idUser]);
                    
                    $nom_entrepot = 'Magasin defaut';
                    $reference = 'Magasin-defaut';
                    $entrepots = Entrepot::create(['nom'=>$nom_entrepot,'reference'=>$reference,'active'=>1,'description'=>$nom_entrepot,
                            'email'=>$this->email,'societe'=>$this->societe,'societe_id'=>$dernier_id,'societe_mere'=>$this->societe,'societe_mere_id'=>$dernier_id,'nom_user'=>$this->nom_utilisateur,'user_id'=>$idUser]);
                    
                    $dernier_id_entrepot = $entrepots->id;   // plus fiable et rapide 

                    $nom_tier = 'John Doe';
                    $type_tiers = 'Fournisseur';
                    $pays = 'Cameroon';
                    $sexe = 'Masculin';
                    $nombre_point = 0;
                    $retrait_point = 0;
                    $objectif_point = 0;
                    $solde = 0;
                    Tier::create(['nom'=>$nom_tier,'raison_sociale'=>$nom_tier,'type_tiers'=>$type_tiers,'solde'=>$solde,'etat'=>1,'telephone'=>$this->telephone,'email'=>$this->email,
                                'sexe'=>$sexe,'pays'=>$pays,'nombre_point'=>$nombre_point,'retrait_point'=>$retrait_point,'objectif_point'=>$objectif_point,
                                'societe'=>$this->societe,'societe_id'=>$dernier_id,'nom_user'=>$this->nom_utilisateur,'user_id'=>$idUser]); 
                    
                    $nom_produit = 'Exemple produit';
                    $reference = 'Exemple-P01';
                    $type_produit = 'Produit';
                    $nature_produit = 'Manufacturé';
                    $produits = Produit::create(['nom_produit'=>$nom_produit,'reference'=>$reference,'type_produit'=>$type_produit,'nature_produit'=>$nature_produit,'description'=>$nom_produit,
                                    'categorie'=>$nom_categorie,'entrepot'=>$dernier_id_entrepot,'fournisseur'=>$nom_tier,'prix_achat'=>0,'prix_vente'=>10,'prix_vente_min'=>0,
                                    'tva'=>0,'limite_stock_alerte'=>5,'pays_origine'=>$pays,'etat'=>1,'quantite_pv'=>0, 'montant_total'=>0,'societe'=>$this->societe,'societe_id'=>$dernier_id,
                                    'nom_user'=>$this->nom_utilisateur,'user_id'=>$idUser]);

                    $dernier_id_prod = $produits->id;   // plus fiable et rapide

                    $quantite = 0;
                    $valorisation_achat_total = 0;
                    $valeur_vente_total = 0;
                    $limite_stock_alerte_bd = 5;
                    Stock::create(['id_entrepot'=>$dernier_id_entrepot,'nom_produit'=>$nom_produit,'id_produit'=>$dernier_id_prod,'reference'=>$reference,'categorie'=>$nom_categorie,'type_produit'=>$type_produit,'nature_produit'=>$nature_produit,'quantite'=>$quantite,
                    'prix_achat_last'=>0, 'prix_moyen_pondere_achat'=>0, 'valorisation_achat_total'=>$valorisation_achat_total,'prix_vente_unitaire'=>0,'prix_vente_min'=>0,'valeur_vente_total'=>$valeur_vente_total,
                    'limite_stock_alerte'=>$limite_stock_alerte_bd,'etat'=>1,'societe'=>$this->societe,'societe_id'=>$dernier_id,'nom_user'=>$this->nom_utilisateur,'user_id'=>$idUser]);


                    // ************* debut envoi email ********************
                              
                    $user = Utilisateur::where('id', $idUser)->get();  
                    $email = $user[0]->email;
                    $name = $user[0]->name;
                    $societe = $user[0]->societe;
                    $societe_id = $user[0]->societe_id;
                    $created_at = $user[0]->created_at;                       

                    $entite_all = Entite::where('enseigne','Administration')->get();
                    $logo = $entite_all[0]->logo; 

                    $date = date('d-m-Y H:i:s');           
                    $body = [
                        'entite'=>$societe,
                        'date'=>$date,              
                        'email'=>$email,
                        'name'=>$name,
                        'created_at'=>date('d-m-Y H:i:s', strtotime($created_at)),
                        'lien'=>'http://wamsco-cloud.net/connexion?email='.$email.'&user='.$name.'&active=ok&champ=1-1',
                        'logo'=>'https://wamsco-cloud.net/storage/'.$logo,
                    ];  
                    Mail::to($email)->send(new ConfirmationMail($body)); 
                    // ********** Fin envoi email ************** 

                    flash ('M./Mme <strong>'.$this->nom_utilisateur.'</strong>, votre inscription a été effectuée avec succès. Merci de consulter votre boîte mail pour confirmer!')->success();
                    return redirect('/connexion');
                }
                catch (\Symfony\Component\Mailer\Exception\TransportExceptionInterface $e) 
                {   
                    Entite::where('enseigne',$this->societe)->delete();
                    Utilisateur::where('societe',$this->societe)->delete(); 
                    Role::where('societe',$this->societe)->delete();  
                    Categorie::where('societe',$this->societe)->delete();  
                    Entrepot::where('societe',$this->societe)->delete();  
                    Tier::where('societe',$this->societe)->delete();
                    Produit::where('societe',$this->societe)->delete();
                    Stock ::where('societe',$this->societe)->delete();
                    flash ('Erreur lors de l\'envoi d\'email : cette adresse ('.$email.') semble invalide ou le domaine n\'existe pas !')->error();
                }  
                
        }
        else{
            flash ('Votre resultat est incorrect. Veuillez recommencer svp!')->error();
            return back();
        }
    }
}
