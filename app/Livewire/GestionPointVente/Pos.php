<?php

namespace App\Livewire\GestionPointVente;

use Livewire\Component;
use Livewire\Attributes\Validate; 
Use Carbon\Carbon;
use App\Helpers\LogActivity;
use Livewire\WithPagination;
use Livewire\WithFileUploads; 
use App\Models\Role;
use App\Models\Produit;
use App\Models\Parametre;
use App\Models\Stock;
use App\Models\Entrepot;
use App\Models\Tier;
use App\Models\Utilisateur;
use App\Models\DeviseTva;
use App\Models\Entite;
use App\Models\SessionPos;
use App\Models\Categorie;
use App\Models\PosFactureClientEntete;
use App\Models\PosFactureClientLigne;
use App\Models\CompteBancaire;
use App\Models\EcritureBancaire;
use App\Models\Reglement;
use App\Models\factureClientEntete;
use App\Models\factureClientLigne;
use App\Models\Mouvement;
use App\Models\Emplacement;
use App\Models\CommandeAttenteEntete;
use App\Models\CommandeAttenteLigne;
use App\Models\SoldeTier;

class Pos extends Component
{
    protected $paginationTheme = 'bootstrap';
    use WithPagination;
    use WithFileUploads;
    
    public $id_pos; 
    public $ref_pos; 
    public $id; 
    public $ids = 0; // pour ouverture edit() cmd
    public $idw = 0; // pour ourverture tier
    public $idPosFClt = 0; // id pos facture client
    
    public $offrir = 'Non';
    public $tva = 0;  
    public $precompte = 0;

    public $quantite_ajoute;   
    public $quantite_base;
    public $prix_achat;   
    public $prix_vente_client;   
    public $remise; 
    public $infos; 
    public $id_produit; 
    public $produit; 
    public $id_entrepot; 
    public $quantite_expediee; 
    
    public $etat_paye; 
    
    // reglement ou paye
    public $nom_client;   
    public $client_id; 
    public $reference;
    public $soldeClientDispo; 
    // public string $codeBarre = '';
    public $date_facturation;   
    public $commentaire;
    public $mode_reglement = 'Espèce';
    public $montantTTC;
    public $montantRecu;
    public $resteApercevoir = 0;
    public $Reste_a_Percevoir = 0; // pour le affiche sur le modal    
    public $montant_reglement;
    public $compte_bancaire;
    public $reglementClient; // affiche les reglement dans modal
    public $reglementClientCount;
    
    public $etat; 
    public $client;  // verifier si le client existe 
    public $etats; // Modal paiement

    public $nomEntrepot;  // affiche nom entrepot     
    public $activer_fidelite;  // affiche point fidelite  
    public $activer_ecran_cuisine;  
    
    public $devise;
    public $confirmer;
    public $confirmation; 
    public $query;
    public $parPage = 5;  
    public $filtre;
    public $chercher;    
    public $recherchePar = 'nom';  // (Recherche par: nom , reference)

    public $montant_point;
    public $objectif_point;

    // creation Tiers    
    public $nom;     
    public $raison_sociale;
    public $type_tiers;  
    public $telephone;
    public $email;
    public $pays ='Cameroon';
    public $ville;
    public $adresse;
    public $code_postal;
    public $site_web;
    public $sexe;
    public $commercial_charge; 
    public $statut = 1;
    // Fin
    public $id_session; 
    public $ref_session;
    
    public $montantPointBD = 0;
    public $pointsNbreBD = 0;

    public $lieu_conso;
    public $date_conso;
    public $adresse_livraison;
    
    public $lieu_consommation; // pour afficher POS    

    public $orderField = 'nom'; 
    public $orderDirection = 'ASC';

    protected $listeners = [
        'dataAjout' => 'onDataAjoutUpdated'
    ];
    public function onDataAjoutUpdated(){
        $this->reset('ids');
    } 
    public function updatingQuery(){ // ceci pour faire revenir a la 1er page lors de la recherche dynamique par les mots dans le champs
        $this->resetPage();
    }  
    public function setOrderField(string $name){
        if($name === $this->orderField){
            $this->orderDirection = $this->orderDirection === 'ASC' ? 'DESC' : 'ASC';
        }
        else{
            $this->orderField = $name;
            $this->reset('orderDirection');
        }
    }   
    public function resetinputFields(){ 
        $this->prix_vente_client = 0;
        $this->quantite_ajoute = 0;
        $this->remise = 0;
    }   
    public function mount(){        
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->pv;
            if($autoriser == 0){
                alert()->error('Oups Désolé', 'Vous n\'êtes pas autorisé à ouvrir cette page !!!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
                $this->redirect('/bienvenue', navigate: true);
            }
             else{                
                $this->id_session = request('id'); // id session pos
                $this->ref_session = request('ref'); // reference session pos
            }
        }
        else{
            alert()->error('Oups Désolé', 'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
            $this->redirect('/bienvenue', navigate: true);
        }  
        $this->lieu_conso = 'Sur place';    
        $this->date_conso = date('Y-m-d H:i'); 
        $this->type_tiers = 'Client';
    }      
    public function render(){        
        
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();
        $jourValid = $entite_mod[0]->validite_mod; 
        $mod_pointe_vente = $entite_mod[0]->mod_pointe_vente; 
        $soldeClient = $entite_mod[0]->solde;
        $this->montantPointBD = $entite_mod[0]->montant_point; // ceci pour point fidelite
        if($dateJour <= $jourValid){
            if($mod_pointe_vente == 1){
                $title = 'Point de vente | WamsCo';
                $module = 'Gestion point vente';
                $title_fils = 'Point de vente';
                $lien = 'pos';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');      
                $dateJour = date('Y-m-d');
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');
                        
                $aller = 0;
                $id = $this->id_session; // id pos
                $ref = $this->ref_session; // reference pos           
                
                $devise_tva = DeviseTva::where('societe',auth()->user()->societe)->orderBy('taux_tva','asc')->get();  

                $produitSimple = Produit::where('societe',auth()->user()->societe)->where('etat',1)->get();
                $listeEntrepot = Entrepot::where('societe',auth()->user()->societe)->where('active',1)->get();
                $categorieProd = Categorie::where('societe',auth()->user()->societe)->where('restaurant','Non')->orderBy('nom_categorie','asc')->get();
                // $categorieProd = Categorie::where('societe',auth()->user()->societe)->orderBy('nom_categorie','asc')->get(); 

                // Parametre
                $verifie = Parametre ::where('societe',auth()->user()->societe)->count();
                if($verifie > 0){
                    
                    $config = Parametre::where('societe',auth()->user()->societe)->limit(1)->get();
                    $id_entrepot = $config[0]->id_entrepot_pv;
                    $this->activer_fidelite = $config[0]->activer_fidelite; 
                    $this->activer_ecran_cuisine = $config[0]->activer_ecran_cuisine;                               

                    $entrep = Entrepot::where('id',$id_entrepot)->first();          
                    $this->nomEntrepot = $entrep->nom;
                }
                else{
                    $id_entrepot = 0;
                    $this->activer_fidelite = 0;
                    $this->activer_ecran_cuisine = 0;                
                    $this->nomEntrepot = '';                
                }            

                if($this->recherchePar == 'nom'){
                    $stockProd = Stock::where('societe',auth()->user()->societe)->where('etat',1)->where('id_entrepot', $id_entrepot)->where('nom_produit','like','%'.$this->query.'%')->where('categorie','like','%'.$this->filtre.'%')->where('nature_produit','!=','Matière première')->where('type_produit','Produit')->orderBy('nom_produit','asc')->get();
                    $produitCount =$stockProd->count(); 
                }
                elseif($this->recherchePar == 'reference'){
                    
                    $stockProd = Stock::where('societe',auth()->user()->societe)->where('etat',1)->where('id_entrepot', $id_entrepot)->where('reference','like','%'.$this->query.'%')->where('categorie','like','%'.$this->filtre.'%')->where('nature_produit','!=','Matière première')->where('type_produit','Produit')->orderBy('nom_produit','asc')->get();
                    $produitCount =$stockProd->count(); 
                }  
                $test_vide = PosFactureClientLigne ::where('user_id',auth()->user()->id)->where('societe',auth()->user()->societe)->count();
                $ligne_cmd = PosFactureClientLigne::where('user_id',auth()->user()->id)->where('societe',auth()->user()->societe)->orderBy('id','asc')->get();                
                $nbreCmd = PosFactureClientLigne ::where('user_id',auth()->user()->id)->where('societe',auth()->user()->societe)->count();           
                $QteCmd = PosFactureClientLigne::where('user_id',auth()->user()->id)->where('societe',auth()->user()->societe)->sum('quantite'); 
                $montant_ttc = PosFactureClientLigne::where('user_id',auth()->user()->id)->where('societe',auth()->user()->societe)->sum('montant_ttc');
                
                $reste_a_percevoir = PosfactureClientEntete::where('user_id',auth()->user()->id)->where('societe',auth()->user()->societe)->sum('reste_a_percevoir');
            
                $test_posFcltEnteteExiste = PosfactureClientEntete::where('user_id',auth()->user()->id)->where('societe',auth()->user()->societe)->limit(1)->count();
                if($test_posFcltEnteteExiste > 0){
                    $this->client = PosfactureClientEntete::where('user_id',auth()->user()->id)->where('societe',auth()->user()->societe)->limit(1)->get();
                    $clients = PosfactureClientEntete::where('user_id',auth()->user()->id)->where('societe',auth()->user()->societe)->limit(1)->get();
                    $idFclt = $clients[0]->id;
                    $code_fact = $clients[0]->code_facture; 
                    $this->etat = $clients[0]->etat;
                    $this->lieu_consommation = $clients[0]->lieu_consommation;
                    $this->adresse_livraison = $clients[0]->adresse_livraison;
                    $this->reglementClient = Reglement::where('societe',auth()->user()->societe)->where('code_facture',$code_fact)->get();
                    $this->reglementClientCount = Reglement::where('societe',auth()->user()->societe)->where('code_facture',$code_fact)->count();
                }
                else{
                    
                    $idFclt = 0;
                    $code_fact = 0;
                    $this->etat = "";
                    // $this->reglementClient = Reglement::where('societe',auth()->user()->societe)->where('id_facture_client_entete',0)->get();
                    $this->reglementClient = Null;
                    $this->reglementClientCount = 0;
                }
                
                // $tier = Tier::where('societe',auth()->user()->societe)->where('etat',1)->where('nom','like','%'.$this->chercher.'%')->where('type_tiers','!=','Fournisseur')->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage);                    
                $tier = Tier::where('societe',auth()->user()->societe)->where('etat',1)->where('nom','like','%'.$this->chercher.'%')->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage);                    
                $tiersCount = $tier->count();  
                $banque = CompteBancaire :: where('societe',auth()->user()->societe)->where('etat',1)->orderBy('nom_compte_bancaire','asc')->get();

                // ceci gere le règlement reçu et reste a payer modal         
                $reste_a_payer = PosfactureClientEntete::where('user_id',auth()->user()->id)->where('societe',auth()->user()->societe)->sum('reste_a_percevoir');
                $this->Reste_a_Percevoir =  ((double)$reste_a_payer - (double)$this->montant_reglement);  

                $emplacement = Emplacement::where('societe',auth()->user()->societe)->orderBy('updated_at', 'DESC')->get(); 
                $cmdAttenteCount = Emplacement::where('societe',auth()->user()->societe)->where('utiliser','Oui')->count();     
                $testVides = Emplacement ::where('societe',auth()->user()->societe)->count(); 
                $utilisateur = Utilisateur::where('societe',auth()->user()->societe)->orderBy('name','asc')->get();

                $deviseTva = DeviseTva :: where('societe',auth()->user()->societe)->limit(1)->orderBy('id','asc')->count(); 
                if($deviseTva == 0){
                    $this->devise = 'FCFA';
                }
                else{
                    $deviseTva = DeviseTva :: where('societe',auth()->user()->societe)->limit(1)->orderBy('id','asc')->get(); 
                    $this->devise = $deviseTva[0]->devise;
                }
                // ceci verifie si une session est ouverte ou pas
                $verifieSession = SessionPos::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->where('etat','En cours')->count();

                $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();          
                $jourValid = $entite_mod[0]->validite_mod; 
                // ceci pour trouver le nombre de jour restant avant expiration
                $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24));
                return view('livewire.gestion-point-vente.pos',compact('title_fils','module','lien','dateJour','devise_tva','produitSimple','listeEntrepot','categorieProd','stockProd','produitCount','test_vide','ligne_cmd','montant_ttc',
                'nbreCmd','QteCmd','reste_a_percevoir','idFclt','code_fact','test_posFcltEnteteExiste','tier','tiersCount','banque','emplacement','cmdAttenteCount','testVides','utilisateur','verifieSession'))->layout('components.layouts.app_pos',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant','aller','id','ref'));
            }
            else{
                $title = 'Bienvenue'; 
                $module = 'Home';
                $title_fils = 'Bienvenue';
                $lien = 'bienvenue';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix'); 
                $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24));  
                toast()->error('Désolé M/Mme <strong>'.auth()->user()->name.'!</strong> <br> Vous n\'avez pas accès à ce module !')->position('top-end')->autoClose(15000)->background('#fff')->width('420px')->padding('5px'); 
                return view('livewire.bienvenue',compact('dateJour','entite_mod'))->layout('components.layouts.app',compact('title','module','title_fils','lien','active','entite_mod','dateJour','soldeClient','nbjoursRestant'));          
            }
        }
        else{
            $title = 'Bienvenue'; 
            $module = 'Home';
            $title_fils = 'Bienvenue';
            $lien = 'bienvenue';
            $active = request('active');
            $champ = request('champ');
            $choix = request('choix'); 
            $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24));  
            toast()->error('Bonjour M/Mme <strong>'.auth()->user()->name.'!</strong> <br> Votre accès a expiré le: <strong>' .date('d-m-Y', strtotime($jourValid)). '</strong>,<br> veuillez renouveller votre abonnement en cliquant sur un module svp !')->position('top-end')->autoClose(50000)->background('#fff')->width('520px')->padding('5px'); 
            return view('livewire.bienvenue',compact('dateJour','entite_mod'))->layout('components.layouts.app',compact('title','module','title_fils','lien','active','entite_mod','dateJour','soldeClient','nbjoursRestant'));          
        }               
    }
    // pour gerer les categories pos
    public function filtres(string $cat){
        $this->filtre = $cat;
    }
    public function filtreAll(){
        $this->filtre = '';
    }
    public function choisir($id){
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->pv;
            if($autoriser == 1){
                // ceci verifie si une session est ouverte ou pas
                $testSessions = SessionPos::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->where('etat','En cours')->count(); 
                if($testSessions > 0){ 
                    // $prod = Stock::where('code_barre', $this->query)->first();  //********** */ pour mettre code_barre a mettre en place  (important)  **************
                    $prod = Stock::where('id',$id)->first();
                    $id_stockProd = $prod->id;
                    $id_entrepot = $prod->id_entrepot;
                    $nom_produit = $prod->nom_produit;        
                    $code_barre = $prod->code_barre;     // code_barre a mettre en place   
                    $id_produit = $prod->id_produit;
                    $reference = $prod->reference;
                    $quantite_stock = $prod->quantite; // quantite stock de la bd
                    $prix_achat_last = $prod->prix_achat_last;
                    $prix_moyen_pondere_achat = $prod->prix_moyen_pondere_achat;                
                    $prix_vente_unitaire = $prod->prix_vente_unitaire;

                    $entrepo = Entrepot::where('id',$id_entrepot)->first();
                    $nom_entrepot = $entrepo->nom; 

                    // pour mouvement
                    $sessions = SessionPos::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->where('etat','En cours')->first(); 
                    $id_session_pos = $sessions->id;
                    $ref_session_pos = $sessions->session_id;

                    $libele_mouvement = 'Expédition ';
                    $code_mouvement = date('YmdHis');
                    $statut = 'POS';
                    $typeProd = 'Produit';

                    if($quantite_stock <= 0){ 
                        $this->dispatch('alert',                    
                            title:'Désolé, le stock est vide !',
                            timer:3000,
                            icon:'error',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        );  
                    }
                    else{
                        $test = PosfactureClientEntete ::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->count();
                        if($test > 0){     
                            $test_paye = PosfactureClientEntete ::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->first();
                            $etat_payes = $test_paye->etat;
                            if($etat_payes == "Brouillon"){
                                $test2 = PosFactureClientLigne ::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->where('id_produit',$id_produit)->where('id_entrepot',$id_entrepot)->count();
                                if($test2 > 0){
                                    
                                    $quantite = 1;   
                                    $cmd = PosFactureClientLigne ::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->where('id_produit',$id_produit)->where('id_entrepot',$id_entrepot)->first();
                                    $id_Fligne = $cmd->id;
                                    $quantite_db = $cmd->quantite;
                                    $remise_db = $cmd->remise;
                                    $tva_db = $cmd->tva;
                                    $precompte_db = $cmd->precompte;
                                    $id_Posfact_cltEntete = $cmd->id_facture_client_entete;
                                    $code_facture = $cmd->code_facture;

                                    // Mise a jour du stock                             
                                    $qteSockFinal2 = $quantite_stock - $quantite;
                                    $valorisation_achat_total2 = $prix_moyen_pondere_achat * $qteSockFinal2;
                                    $valeur_vente_total2 = $prix_vente_unitaire * $qteSockFinal2;
                                    Stock::where('societe',auth()->user()->societe)->where('id',$id_stockProd)->update(['quantite'=>$qteSockFinal2,'valorisation_achat_total'=>$valorisation_achat_total2,'valeur_vente_total'=>$valeur_vente_total2,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);   
                                    //

                                    $quantiteFinal = $quantite_db + $quantite; // nouvelle quantite apres rajout

                                    // ************ Calul ***********
                                    $montant_vente =  $prix_vente_unitaire * $quantiteFinal;                        
                                    $montant_achat_avec_qte = ($prix_moyen_pondere_achat * $quantiteFinal);  

                                    $remiseDetail = $remise_db/100; // valeur remise
                                    $remise_montant = ($prix_vente_unitaire * $quantiteFinal) * $remiseDetail; // montant remise

                                    $montant_remiser_ht = ($prix_vente_unitaire * $quantiteFinal) - $remise_montant; // Montant vente avec remise inclus hors taxe                        
                                    $marge = $montant_remiser_ht - $montant_achat_avec_qte; // Marge avec remise                       

                                    $tvaDetail = $tva_db/100; // valeur de la tva
                                    $tva_montant = $montant_remiser_ht * $tvaDetail;

                                    $precompteDetail = $precompte_db/100; // valeur du precompte
                                    $precompte_montant = $montant_remiser_ht * $precompteDetail;

                                    $montant_ttc = $montant_remiser_ht + $tva_montant + $precompte_montant; // Montant TTC
                                    //************** Fin Calcul ***********//
                                    
                                    $offrir = 'Non';
                                    PosFactureClientLigne::where('id',$id_Fligne)->update(['quantite'=>$quantiteFinal,'quantite_expediee'=>$quantiteFinal,'montant_remise'=>$remise_montant,
                                                        'montant_tva'=>$tva_montant,'montant_precompte'=>$precompte_montant,'montant_ht'=>$montant_remiser_ht,'montant_ttc'=>$montant_ttc,
                                                        'marge'=>$marge,'offrir'=>$offrir,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                     
                                    
                                    $montantHT = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$id_Posfact_cltEntete)->sum('montant_ht');
                                    $montantTTC = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$id_Posfact_cltEntete)->sum('montant_ttc');
                                    $montantRemise = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$id_Posfact_cltEntete)->sum('montant_remise');
                                    $montantTva = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$id_Posfact_cltEntete)->sum('montant_tva');
                                    $montantPrecompte = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$id_Posfact_cltEntete)->sum('montant_precompte');
                                    $marge = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$id_Posfact_cltEntete)->sum('marge');

                                    // Montant TTC en arrondi en + ou en - 
                                    PosFactureClientEntete::find($id_Posfact_cltEntete)->update(['montant_ht'=>$montantHT,'montant_remise'=>$montantRemise,'montant_tva'=>$montantTva,'montant_precompte'=>$montantPrecompte,'montant_ttc'=>number_format($montantTTC,0,',',''),
                                                            'reste_a_percevoir'=>number_format($montantTTC,0,',',''),'marge'=>$marge,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                                    Mouvement::create(['id_entrepot'=>$id_entrepot,'nom_produit'=>$nom_produit,'id_produit'=>$id_produit,'reference'=>$reference,'quantite'=>-$quantite,'libele_mouvement'=>$libele_mouvement.$code_facture,
                                    'code_mouvement'=>$code_mouvement,'statut'=>$statut,'entrepot'=>$nom_entrepot,'origine'=>$ref_session_pos,'id_session_pos'=>$id_session_pos,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                                    
                                    $id_activite = $id_session_pos;
                                    $page = 'SessionPos';
                                    $ref_POS = $ref_session_pos;
                                    LogActivity::addToLog('Produit ajouté au panier ('.$ref_POS.')', $id_activite, $page);
                                }
                                else{ 
                                                    
                                    $date_facturation = date('Y-m-d');
                                    $date_echeance = date('Y-m-d');
                                    $mode_reglement = 'Espèce';
                                    $compte_bancaire = '';
                                    $note = '';
                                    $etat = 'Brouillon';
                                    $montant_ht = 0;
                                    $montant_remise = 0;
                                    $montant_tva = 0;
                                    $montant_precompte = 0;
                                    $montant_ttc = 0;
                                    $marge = 0;
                                    $montant_recu = 0;
                                    $reste_a_percevoir = 0;
                                    $quantite = 1; 
                                    $remise = 0;
                                    $tva = 0;
                                    $precompte = 0;
                                    $offrir = 'Non';
                                    $etat = 'Brouillon';                            
                                
                                    
                                    // ************ Calul ***********
                                    $montant_vente =  $prix_vente_unitaire * $quantite;                        
                                    $montant_achat_avec_qte = ($prix_moyen_pondere_achat * $quantite);  

                                    $remiseDetail = $remise/100; // valeur remise
                                    $remise_montant = ($prix_vente_unitaire * $quantite) * $remiseDetail; // montant remise

                                    $montant_remiser_ht = ($prix_vente_unitaire * $quantite) - $remise_montant; // Montant vente avec remise inclus hors taxe                        
                                    $marge = $montant_remiser_ht - $montant_achat_avec_qte; // Marge avec remise                       

                                    $tvaDetail = $tva/100; // valeur de la tva
                                    $tva_montant = $montant_remiser_ht * $tvaDetail;

                                    $precompteDetail = $precompte/100; // valeur du precompte
                                    $precompte_montant = $montant_remiser_ht * $precompteDetail;

                                    $montant_ttc = $montant_remiser_ht + $tva_montant + $precompte_montant; // Montant TTC
                                    //************** Fin Calcul ***********//
                                    
                                    $quantite_expediee = 0;

                                    // Mise a jour du stock                             
                                        $qteSockFinal = $quantite_stock - $quantite;
                                        $valorisation_achat_total = $prix_moyen_pondere_achat * $qteSockFinal;
                                        $valeur_vente_total = $prix_vente_unitaire * $qteSockFinal;
                                        Stock::where('societe',auth()->user()->societe)->where('id',$id_stockProd)->update(['quantite'=>$qteSockFinal,'valorisation_achat_total'=>$valorisation_achat_total,'valeur_vente_total'=>$valeur_vente_total,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);   
                                    //

                                    $recup = PosfactureClientEntete ::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->first();
                                    $id_fPosEntete = $recup->id;
                                    $code_facture = $recup->code_facture;
                                    $nom_client = $recup->nom_client;
                                    $id_client = $recup->id_client;

                                    PosFactureClientLigne::create(['code_facture'=>$code_facture,'id_facture_client_entete'=>$id_fPosEntete,'produit'=>$nom_produit,'id_produit'=>$id_produit,'reference'=>$reference,'type_produit'=>$typeProd,'prix_achat'=>$prix_moyen_pondere_achat,
                                                        'prix_vente'=>$prix_vente_unitaire,'quantite'=>$quantite,'quantite_expediee'=>$quantite,'reste_a_expedier'=>$quantite_expediee,'remise'=>$remise,'montant_remise'=>$remise_montant,
                                                        'tva'=>$tva,'montant_tva'=>$tva_montant,'precompte'=>$precompte,'montant_precompte'=>$precompte_montant,'montant_ht'=>$montant_remiser_ht,'montant_ttc'=>$montant_ttc,'marge'=>$marge,'id_entrepot'=>$id_entrepot,
                                                        'nom_client'=>$nom_client,'id_client'=>$id_client,'offrir'=>$offrir,'etat'=>$etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                     
                                    
                                        $montantHT = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$id_fPosEntete)->sum('montant_ht');
                                        $montantTTC = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$id_fPosEntete)->sum('montant_ttc');
                                        $montantRemise = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$id_fPosEntete)->sum('montant_remise');
                                        $montantTva = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$id_fPosEntete)->sum('montant_tva');
                                        $montantPrecompte = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$id_fPosEntete)->sum('montant_precompte');
                                        $marge = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$id_fPosEntete)->sum('marge');

                                        // Montant TTC en arrondi en + ou en - 
                                        PosFactureClientEntete::find($id_fPosEntete)->update(['montant_ht'=>$montantHT,'montant_remise'=>$montantRemise,'montant_tva'=>$montantTva,'montant_precompte'=>$montantPrecompte,'montant_ttc'=>number_format($montantTTC,0,',',''),
                                        'reste_a_percevoir'=>number_format($montantTTC,0,',',''),'marge'=>$marge,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                                        Mouvement::create(['id_entrepot'=>$id_entrepot,'nom_produit'=>$nom_produit,'id_produit'=>$id_produit,'reference'=>$reference,'quantite'=>-$quantite,'libele_mouvement'=>$libele_mouvement.$code_facture,
                                        'code_mouvement'=>$code_mouvement,'statut'=>$statut,'entrepot'=>$nom_entrepot,'origine'=>$ref_session_pos,'id_session_pos'=>$id_session_pos,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 

                                        $id_activite = $id_session_pos;
                                        $page = 'SessionPos';
                                        $ref_POS = $ref_session_pos;
                                        LogActivity::addToLog('Produit ajouté au panier ('.$ref_POS.')', $id_activite, $page);
                                }
                            }
                            else{
                                $this->dispatch('alert',                    
                                    title:'Désolé, vous ne pouvez plus selectionner un produit (paiement encours)!',
                                    timer:5000,
                                    icon:'error',
                                    toast:true,
                                    showConfirmButton: false,
                                    position:'top-end',
                                );  
                            }
                        }
                        else{   
                            
                            $test_count = PosfactureClientEntete ::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->count();
                            if($test_count != 0){
                                $test_paye = PosfactureClientEntete ::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->first();
                                $etat_paye = $test_paye->etat;                            
                            }
                            else{
                                $etat_paye = "Brouillon";
                            }  

                            if($etat_paye == "Brouillon"){
                            
                                $client = '';  
                                $id_client = 0;                     
                                $date_facturation = date('Y-m-d');
                                $date_echeance = date('Y-m-d');
                                $mode_reglement = 'Espèce';
                                $compte_bancaire = '';
                                $note = '';
                                $etat = 'Brouillon';
                                $montant_ht = 0;
                                $montant_remise = 0;
                                $montant_tva = 0;
                                $montant_precompte = 0;
                                $montant_ttc = 0;
                                $marge = 0;
                                $montant_recu = 0;
                                $reste_a_percevoir = 0;
                                $quantite = 1; 
                                $remise = 0;
                                $tva = 0;
                                $precompte = 0;
                                $offrir = 'Non';
                                $etat = 'Brouillon';

                                $dates = date('dmy/His');
                                $length = 2;
                                $token = bin2hex(random_bytes($length));
                                $token_ok = 'POS/'.$dates;
                                // $token_ok = 'FACT/'.$dates.'/'.$token;
                                $lieu_conso = 'Sur place'; // pour lieu consommation
                                $date_conso = date('Y-m-d H:i'); // pour Date consommation
                                PosFactureClientEntete :: create(['code_facture'=>$token_ok,'nom_client'=>$client,'id_client'=>$id_client,'date_facturation'=>$date_facturation,'date_echeance'=>$date_echeance,
                                            'montant_ht'=>$montant_ht,'montant_remise'=>$montant_remise,'montant_tva'=>$montant_tva,'montant_precompte'=>$montant_precompte,'montant_ttc'=>$montant_ttc,'marge'=>$marge,
                                            'montant_recu'=>$montant_recu,'reste_a_percevoir'=>$reste_a_percevoir,'mode_reglement'=>$mode_reglement,'compte_bancaire'=>$compte_bancaire,'note'=>$note,'etat'=>$etat,
                                            'lieu_consommation'=>$lieu_conso,'date_consommation'=>$date_conso,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                                
                                // ceci recupere le dernier enregistrement cree a l'instant
                                $dernier_id = PosFactureClientEntete::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id; 
                                
                                // ************ Calul ***********
                                $montant_vente =  $prix_vente_unitaire * $quantite;                        
                                $montant_achat_avec_qte = ($prix_moyen_pondere_achat * $quantite);  

                                $remiseDetail = $remise/100; // valeur remise
                                $remise_montant = ($prix_vente_unitaire * $quantite) * $remiseDetail; // montant remise

                                $montant_remiser_ht = ($prix_vente_unitaire * $quantite) - $remise_montant; // Montant vente avec remise inclus hors taxe                        
                                $marge = $montant_remiser_ht - $montant_achat_avec_qte; // Marge avec remise                       

                                $tvaDetail = $tva/100; // valeur de la tva
                                $tva_montant = $montant_remiser_ht * $tvaDetail;

                                $precompteDetail = $precompte/100; // valeur du precompte
                                $precompte_montant = $montant_remiser_ht * $precompteDetail;

                                $montant_ttc = $montant_remiser_ht + $tva_montant + $precompte_montant; // Montant TTC
                                //************** Fin Calcul ***********//
                                
                                $quantite_expediee = 0;

                                // Mise a jour du stock                             
                                    $qteSockFinal = $quantite_stock - $quantite;
                                    $valorisation_achat_total = $prix_moyen_pondere_achat * $qteSockFinal;
                                    $valeur_vente_total = $prix_vente_unitaire * $qteSockFinal;
                                    Stock::where('societe',auth()->user()->societe)->where('id',$id_stockProd)->update(['quantite'=>$qteSockFinal,'valorisation_achat_total'=>$valorisation_achat_total,'valeur_vente_total'=>$valeur_vente_total,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);   
                                //

                                PosFactureClientLigne::create(['code_facture'=>$token_ok,'id_facture_client_entete'=>$dernier_id,'produit'=>$nom_produit,'id_produit'=>$id_produit,'reference'=>$reference,'type_produit'=>$typeProd,'prix_achat'=>$prix_moyen_pondere_achat,
                                                    'prix_vente'=>$prix_vente_unitaire,'quantite'=>$quantite,'quantite_expediee'=>$quantite,'reste_a_expedier'=>$quantite_expediee,'remise'=>$remise,'montant_remise'=>$remise_montant,
                                                    'tva'=>$tva,'montant_tva'=>$tva_montant,'precompte'=>$precompte,'montant_precompte'=>$precompte_montant,'montant_ht'=>$montant_remiser_ht,'montant_ttc'=>$montant_ttc,'marge'=>$marge,'id_entrepot'=>$id_entrepot,
                                                    'nom_client'=>$client,'id_client'=>$id_client,'offrir'=>$offrir,'etat'=>$etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                     
                                
                                    $montantHT = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$dernier_id)->sum('montant_ht');
                                    $montantTTC = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$dernier_id)->sum('montant_ttc');
                                    $montantRemise = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$dernier_id)->sum('montant_remise');
                                    $montantTva = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$dernier_id)->sum('montant_tva');
                                    $montantPrecompte = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$dernier_id)->sum('montant_precompte');
                                    $marge = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$dernier_id)->sum('marge');

                                    // Montant TTC en arrondi en + ou en - 
                                    PosFactureClientEntete::find($dernier_id)->update(['montant_ht'=>$montantHT,'montant_remise'=>$montantRemise,'montant_tva'=>$montantTva,'montant_precompte'=>$montantPrecompte,'montant_ttc'=>number_format($montantTTC,0,',',''),
                                                        'reste_a_percevoir'=>number_format($montantTTC,0,',',''),'marge'=>$marge,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                                                        
                                    Mouvement::create(['id_entrepot'=>$id_entrepot,'nom_produit'=>$nom_produit,'id_produit'=>$id_produit,'reference'=>$reference,'quantite'=>-$quantite,'libele_mouvement'=>$libele_mouvement.$token_ok,
                                    'code_mouvement'=>$code_mouvement,'statut'=>$statut,'entrepot'=>$nom_entrepot,'origine'=>$ref_session_pos,'id_session_pos'=>$id_session_pos,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                                    
                                    $id_activite = $id_session_pos;
                                    $page = 'SessionPos';
                                    $ref_POS = $ref_session_pos;
                                    LogActivity::addToLog('Produit ajouté au panier ('.$ref_POS.')', $id_activite, $page);
                            }
                            else{
                                $this->dispatch('alert',                    
                                    title:'Désolé, vous ne pouvez plus selectionner un produit (paiement encours)!',
                                    timer:5000,
                                    icon:'error',
                                    toast:true,
                                    showConfirmButton: false,
                                    position:'top-end',
                                );  
                            }
                        }   
                    } 
                }
                else{
                    $this->dispatch('alert',                    
                        title:'Désolé, veuillez créer une session svp!',
                        timer:7000,
                        icon:'error',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );
                } 
            }else{ 
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
    public function ScanProduit ($id){
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->pv;
            if($autoriser == 1){
                // ceci verifie si une session est ouverte ou pas
                $testSessions = SessionPos::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->where('etat','En cours')->count(); 
                if($testSessions > 0){ 
                    $testProd = Stock::where('code_barre', $this->query)->count();  //********** */ pour mettre le code_barre en place  (important)  **************
                    if($testProd > 0){                    
                        // $prod = Stock::where('id',$id)->first();
                        $prod = Stock::where('code_barre', $this->query)->first();  //********** */ pour mettre le code_barre en place  (important)  **************
                        $id_stockProd = $prod->id;
                        $id_entrepot = $prod->id_entrepot;
                        $nom_produit = $prod->nom_produit;        
                        $code_barre = $prod->code_barre;     // code_barre a mettre en place   
                        $id_produit = $prod->id_produit;
                        $reference = $prod->reference;
                        $quantite_stock = $prod->quantite; // quantite stock de la bd
                        $prix_achat_last = $prod->prix_achat_last;
                        $prix_moyen_pondere_achat = $prod->prix_moyen_pondere_achat;                
                        $prix_vente_unitaire = $prod->prix_vente_unitaire;

                        $entrepo = Entrepot::where('id',$id_entrepot)->first();
                        $nom_entrepot = $entrepo->nom; 

                        // pour mouvement
                        $sessions = SessionPos::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->where('etat','En cours')->first(); 
                        $id_session_pos = $sessions->id;
                        $ref_session_pos = $sessions->session_id;

                        $libele_mouvement = 'Expédition ';
                        $code_mouvement = date('YmdHis');
                        $statut = 'POS';
                        $typeProd = 'Produit';

                        if($quantite_stock <= 0){ 
                            $this->dispatch('alert',                    
                                title:'Désolé, le stock est vide !',
                                timer:3000,
                                icon:'error',
                                toast:true,
                                showConfirmButton: false,
                                position:'top-end',
                            );  
                        }
                        else{
                            $test = PosfactureClientEntete ::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->count();
                            if($test > 0){     
                                $test_paye = PosfactureClientEntete ::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->first();
                                $etat_payes = $test_paye->etat;
                                if($etat_payes == "Brouillon"){
                                    $test2 = PosFactureClientLigne ::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->where('id_produit',$id_produit)->where('id_entrepot',$id_entrepot)->count();
                                    if($test2 > 0){
                                        
                                        $quantite = 1;   
                                        $cmd = PosFactureClientLigne ::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->where('id_produit',$id_produit)->where('id_entrepot',$id_entrepot)->first();
                                        $id_Fligne = $cmd->id;
                                        $quantite_db = $cmd->quantite;
                                        $remise_db = $cmd->remise;
                                        $tva_db = $cmd->tva;
                                        $precompte_db = $cmd->precompte;
                                        $id_Posfact_cltEntete = $cmd->id_facture_client_entete;
                                        $code_facture = $cmd->code_facture;

                                        // Mise a jour du stock                             
                                        $qteSockFinal2 = $quantite_stock - $quantite;
                                        $valorisation_achat_total2 = $prix_moyen_pondere_achat * $qteSockFinal2;
                                        $valeur_vente_total2 = $prix_vente_unitaire * $qteSockFinal2;
                                        Stock::where('societe',auth()->user()->societe)->where('id',$id_stockProd)->update(['quantite'=>$qteSockFinal2,'valorisation_achat_total'=>$valorisation_achat_total2,'valeur_vente_total'=>$valeur_vente_total2,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);   
                                        //

                                        $quantiteFinal = $quantite_db + $quantite; // nouvelle quantite apres rajout

                                        // ************ Calul ***********
                                        $montant_vente =  $prix_vente_unitaire * $quantiteFinal;                        
                                        $montant_achat_avec_qte = ($prix_moyen_pondere_achat * $quantiteFinal);  

                                        $remiseDetail = $remise_db/100; // valeur remise
                                        $remise_montant = ($prix_vente_unitaire * $quantiteFinal) * $remiseDetail; // montant remise

                                        $montant_remiser_ht = ($prix_vente_unitaire * $quantiteFinal) - $remise_montant; // Montant vente avec remise inclus hors taxe                        
                                        $marge = $montant_remiser_ht - $montant_achat_avec_qte; // Marge avec remise                       

                                        $tvaDetail = $tva_db/100; // valeur de la tva
                                        $tva_montant = $montant_remiser_ht * $tvaDetail;

                                        $precompteDetail = $precompte_db/100; // valeur du precompte
                                        $precompte_montant = $montant_remiser_ht * $precompteDetail;

                                        $montant_ttc = $montant_remiser_ht + $tva_montant + $precompte_montant; // Montant TTC
                                        //************** Fin Calcul ***********//
                                        
                                        $offrir = 'Non';
                                        PosFactureClientLigne::where('id',$id_Fligne)->update(['quantite'=>$quantiteFinal,'quantite_expediee'=>$quantiteFinal,'montant_remise'=>$remise_montant,
                                                            'montant_tva'=>$tva_montant,'montant_precompte'=>$precompte_montant,'montant_ht'=>$montant_remiser_ht,'montant_ttc'=>$montant_ttc,
                                                            'marge'=>$marge,'offrir'=>$offrir,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                     
                                        
                                        $montantHT = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$id_Posfact_cltEntete)->sum('montant_ht');
                                        $montantTTC = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$id_Posfact_cltEntete)->sum('montant_ttc');
                                        $montantRemise = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$id_Posfact_cltEntete)->sum('montant_remise');
                                        $montantTva = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$id_Posfact_cltEntete)->sum('montant_tva');
                                        $montantPrecompte = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$id_Posfact_cltEntete)->sum('montant_precompte');
                                        $marge = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$id_Posfact_cltEntete)->sum('marge');

                                        // Montant TTC en arrondi en + ou en - 
                                        PosFactureClientEntete::find($id_Posfact_cltEntete)->update(['montant_ht'=>$montantHT,'montant_remise'=>$montantRemise,'montant_tva'=>$montantTva,'montant_precompte'=>$montantPrecompte,'montant_ttc'=>number_format($montantTTC,0,',',''),
                                                                'reste_a_percevoir'=>number_format($montantTTC,0,',',''),'marge'=>$marge,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                                        Mouvement::create(['id_entrepot'=>$id_entrepot,'nom_produit'=>$nom_produit,'id_produit'=>$id_produit,'reference'=>$reference,'quantite'=>-$quantite,'libele_mouvement'=>$libele_mouvement.$code_facture,
                                        'code_mouvement'=>$code_mouvement,'statut'=>$statut,'entrepot'=>$nom_entrepot,'origine'=>$ref_session_pos,'id_session_pos'=>$id_session_pos,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                                        
                                        $id_activite = $id_session_pos;
                                        $page = 'SessionPos';
                                        $ref_POS = $ref_session_pos;
                                        LogActivity::addToLog('Produit ajouté au panier ('.$ref_POS.')', $id_activite, $page);
                                    }
                                    else{ 
                                                        
                                        $date_facturation = date('Y-m-d');
                                        $date_echeance = date('Y-m-d');
                                        $mode_reglement = 'Espèce';
                                        $compte_bancaire = '';
                                        $note = '';
                                        $etat = 'Brouillon';
                                        $montant_ht = 0;
                                        $montant_remise = 0;
                                        $montant_tva = 0;
                                        $montant_precompte = 0;
                                        $montant_ttc = 0;
                                        $marge = 0;
                                        $montant_recu = 0;
                                        $reste_a_percevoir = 0;
                                        $quantite = 1; 
                                        $remise = 0;
                                        $tva = 0;
                                        $precompte = 0;
                                        $offrir = 'Non';
                                        $etat = 'Brouillon';                            
                                    
                                        
                                        // ************ Calul ***********
                                        $montant_vente =  $prix_vente_unitaire * $quantite;                        
                                        $montant_achat_avec_qte = ($prix_moyen_pondere_achat * $quantite);  

                                        $remiseDetail = $remise/100; // valeur remise
                                        $remise_montant = ($prix_vente_unitaire * $quantite) * $remiseDetail; // montant remise

                                        $montant_remiser_ht = ($prix_vente_unitaire * $quantite) - $remise_montant; // Montant vente avec remise inclus hors taxe                        
                                        $marge = $montant_remiser_ht - $montant_achat_avec_qte; // Marge avec remise                       

                                        $tvaDetail = $tva/100; // valeur de la tva
                                        $tva_montant = $montant_remiser_ht * $tvaDetail;

                                        $precompteDetail = $precompte/100; // valeur du precompte
                                        $precompte_montant = $montant_remiser_ht * $precompteDetail;

                                        $montant_ttc = $montant_remiser_ht + $tva_montant + $precompte_montant; // Montant TTC
                                        //************** Fin Calcul ***********//
                                        
                                        $quantite_expediee = 0;

                                        // Mise a jour du stock                             
                                            $qteSockFinal = $quantite_stock - $quantite;
                                            $valorisation_achat_total = $prix_moyen_pondere_achat * $qteSockFinal;
                                            $valeur_vente_total = $prix_vente_unitaire * $qteSockFinal;
                                            Stock::where('societe',auth()->user()->societe)->where('id',$id_stockProd)->update(['quantite'=>$qteSockFinal,'valorisation_achat_total'=>$valorisation_achat_total,'valeur_vente_total'=>$valeur_vente_total,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);   
                                        //

                                        $recup = PosfactureClientEntete ::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->first();
                                        $id_fPosEntete = $recup->id;
                                        $code_facture = $recup->code_facture;
                                        $nom_client = $recup->nom_client;
                                        $id_client = $recup->id_client;

                                        PosFactureClientLigne::create(['code_facture'=>$code_facture,'id_facture_client_entete'=>$id_fPosEntete,'produit'=>$nom_produit,'id_produit'=>$id_produit,'reference'=>$reference,'type_produit'=>$typeProd,'prix_achat'=>$prix_moyen_pondere_achat,
                                                            'prix_vente'=>$prix_vente_unitaire,'quantite'=>$quantite,'quantite_expediee'=>$quantite,'reste_a_expedier'=>$quantite_expediee,'remise'=>$remise,'montant_remise'=>$remise_montant,
                                                            'tva'=>$tva,'montant_tva'=>$tva_montant,'precompte'=>$precompte,'montant_precompte'=>$precompte_montant,'montant_ht'=>$montant_remiser_ht,'montant_ttc'=>$montant_ttc,'marge'=>$marge,'id_entrepot'=>$id_entrepot,
                                                            'nom_client'=>$nom_client,'id_client'=>$id_client,'offrir'=>$offrir,'etat'=>$etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                     
                                        
                                            $montantHT = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$id_fPosEntete)->sum('montant_ht');
                                            $montantTTC = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$id_fPosEntete)->sum('montant_ttc');
                                            $montantRemise = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$id_fPosEntete)->sum('montant_remise');
                                            $montantTva = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$id_fPosEntete)->sum('montant_tva');
                                            $montantPrecompte = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$id_fPosEntete)->sum('montant_precompte');
                                            $marge = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$id_fPosEntete)->sum('marge');

                                            // Montant TTC en arrondi en + ou en - 
                                            PosFactureClientEntete::find($id_fPosEntete)->update(['montant_ht'=>$montantHT,'montant_remise'=>$montantRemise,'montant_tva'=>$montantTva,'montant_precompte'=>$montantPrecompte,'montant_ttc'=>number_format($montantTTC,0,',',''),
                                            'reste_a_percevoir'=>number_format($montantTTC,0,',',''),'marge'=>$marge,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                                            Mouvement::create(['id_entrepot'=>$id_entrepot,'nom_produit'=>$nom_produit,'id_produit'=>$id_produit,'reference'=>$reference,'quantite'=>-$quantite,'libele_mouvement'=>$libele_mouvement.$code_facture,
                                            'code_mouvement'=>$code_mouvement,'statut'=>$statut,'entrepot'=>$nom_entrepot,'origine'=>$ref_session_pos,'id_session_pos'=>$id_session_pos,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 

                                            $id_activite = $id_session_pos;
                                            $page = 'SessionPos';
                                            $ref_POS = $ref_session_pos;
                                            LogActivity::addToLog('Produit ajouté au panier ('.$ref_POS.')', $id_activite, $page);
                                    }
                                }
                                else{
                                    $this->dispatch('alert',                    
                                        title:'Désolé, vous ne pouvez plus selectionner un produit (paiement encours)!',
                                        timer:5000,
                                        icon:'error',
                                        toast:true,
                                        showConfirmButton: false,
                                        position:'top-end',
                                    );  
                                }
                            }
                            else{   
                                
                                $test_count = PosfactureClientEntete ::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->count();
                                if($test_count != 0){
                                    $test_paye = PosfactureClientEntete ::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->first();
                                    $etat_paye = $test_paye->etat;                            
                                }
                                else{
                                    $etat_paye = "Brouillon";
                                }  

                                if($etat_paye == "Brouillon"){
                                
                                    $client = '';  
                                    $id_client = 0;                     
                                    $date_facturation = date('Y-m-d');
                                    $date_echeance = date('Y-m-d');
                                    $mode_reglement = 'Espèce';
                                    $compte_bancaire = '';
                                    $note = '';
                                    $etat = 'Brouillon';
                                    $montant_ht = 0;
                                    $montant_remise = 0;
                                    $montant_tva = 0;
                                    $montant_precompte = 0;
                                    $montant_ttc = 0;
                                    $marge = 0;
                                    $montant_recu = 0;
                                    $reste_a_percevoir = 0;
                                    $quantite = 1; 
                                    $remise = 0;
                                    $tva = 0;
                                    $precompte = 0;
                                    $offrir = 'Non';
                                    $etat = 'Brouillon';

                                    $dates = date('dmy/His');
                                    $length = 2;
                                    $token = bin2hex(random_bytes($length));
                                    $token_ok = 'POS/'.$dates;
                                    // $token_ok = 'FACT/'.$dates.'/'.$token;
                                    $lieu_conso = 'Sur place'; // pour lieu consommation
                                    $date_conso = date('Y-m-d H:i'); // pour Date consommation
                                    PosFactureClientEntete :: create(['code_facture'=>$token_ok,'nom_client'=>$client,'id_client'=>$id_client,'date_facturation'=>$date_facturation,'date_echeance'=>$date_echeance,
                                                'montant_ht'=>$montant_ht,'montant_remise'=>$montant_remise,'montant_tva'=>$montant_tva,'montant_precompte'=>$montant_precompte,'montant_ttc'=>$montant_ttc,'marge'=>$marge,
                                                'montant_recu'=>$montant_recu,'reste_a_percevoir'=>$reste_a_percevoir,'mode_reglement'=>$mode_reglement,'compte_bancaire'=>$compte_bancaire,'note'=>$note,'etat'=>$etat,
                                                'lieu_consommation'=>$lieu_conso,'date_consommation'=>$date_conso,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                                    
                                    // ceci recupere le dernier enregistrement cree a l'instant
                                    $dernier_id = PosFactureClientEntete::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id; 
                                    
                                    // ************ Calul ***********
                                    $montant_vente =  $prix_vente_unitaire * $quantite;                        
                                    $montant_achat_avec_qte = ($prix_moyen_pondere_achat * $quantite);  

                                    $remiseDetail = $remise/100; // valeur remise
                                    $remise_montant = ($prix_vente_unitaire * $quantite) * $remiseDetail; // montant remise

                                    $montant_remiser_ht = ($prix_vente_unitaire * $quantite) - $remise_montant; // Montant vente avec remise inclus hors taxe                        
                                    $marge = $montant_remiser_ht - $montant_achat_avec_qte; // Marge avec remise                       

                                    $tvaDetail = $tva/100; // valeur de la tva
                                    $tva_montant = $montant_remiser_ht * $tvaDetail;

                                    $precompteDetail = $precompte/100; // valeur du precompte
                                    $precompte_montant = $montant_remiser_ht * $precompteDetail;

                                    $montant_ttc = $montant_remiser_ht + $tva_montant + $precompte_montant; // Montant TTC
                                    //************** Fin Calcul ***********//
                                    
                                    $quantite_expediee = 0;

                                    // Mise a jour du stock                             
                                        $qteSockFinal = $quantite_stock - $quantite;
                                        $valorisation_achat_total = $prix_moyen_pondere_achat * $qteSockFinal;
                                        $valeur_vente_total = $prix_vente_unitaire * $qteSockFinal;
                                        Stock::where('societe',auth()->user()->societe)->where('id',$id_stockProd)->update(['quantite'=>$qteSockFinal,'valorisation_achat_total'=>$valorisation_achat_total,'valeur_vente_total'=>$valeur_vente_total,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);   
                                    //

                                    PosFactureClientLigne::create(['code_facture'=>$token_ok,'id_facture_client_entete'=>$dernier_id,'produit'=>$nom_produit,'id_produit'=>$id_produit,'reference'=>$reference,'type_produit'=>$typeProd,'prix_achat'=>$prix_moyen_pondere_achat,
                                                        'prix_vente'=>$prix_vente_unitaire,'quantite'=>$quantite,'quantite_expediee'=>$quantite,'reste_a_expedier'=>$quantite_expediee,'remise'=>$remise,'montant_remise'=>$remise_montant,
                                                        'tva'=>$tva,'montant_tva'=>$tva_montant,'precompte'=>$precompte,'montant_precompte'=>$precompte_montant,'montant_ht'=>$montant_remiser_ht,'montant_ttc'=>$montant_ttc,'marge'=>$marge,'id_entrepot'=>$id_entrepot,
                                                        'nom_client'=>$client,'id_client'=>$id_client,'offrir'=>$offrir,'etat'=>$etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                     
                                    
                                        $montantHT = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$dernier_id)->sum('montant_ht');
                                        $montantTTC = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$dernier_id)->sum('montant_ttc');
                                        $montantRemise = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$dernier_id)->sum('montant_remise');
                                        $montantTva = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$dernier_id)->sum('montant_tva');
                                        $montantPrecompte = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$dernier_id)->sum('montant_precompte');
                                        $marge = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$dernier_id)->sum('marge');

                                        // Montant TTC en arrondi en + ou en - 
                                        PosFactureClientEntete::find($dernier_id)->update(['montant_ht'=>$montantHT,'montant_remise'=>$montantRemise,'montant_tva'=>$montantTva,'montant_precompte'=>$montantPrecompte,'montant_ttc'=>number_format($montantTTC,0,',',''),
                                                            'reste_a_percevoir'=>number_format($montantTTC,0,',',''),'marge'=>$marge,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                                                            
                                        Mouvement::create(['id_entrepot'=>$id_entrepot,'nom_produit'=>$nom_produit,'id_produit'=>$id_produit,'reference'=>$reference,'quantite'=>-$quantite,'libele_mouvement'=>$libele_mouvement.$token_ok,
                                        'code_mouvement'=>$code_mouvement,'statut'=>$statut,'entrepot'=>$nom_entrepot,'origine'=>$ref_session_pos,'id_session_pos'=>$id_session_pos,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                                        
                                        $id_activite = $id_session_pos;
                                        $page = 'SessionPos';
                                        $ref_POS = $ref_session_pos;
                                        LogActivity::addToLog('Produit ajouté au panier ('.$ref_POS.')', $id_activite, $page);
                                }
                                else{
                                    $this->dispatch('alert',                    
                                        title:'Désolé, vous ne pouvez plus selectionner un produit (paiement encours)!',
                                        timer:5000,
                                        icon:'error',
                                        toast:true,
                                        showConfirmButton: false,
                                        position:'top-end',
                                    );  
                                }
                            }   
                        } 
                    }
                    else{
                        $this->dispatch('alert',                    
                            title:'Désolé, ce produit n\'a pas de code barre ou n\'existe pas !',
                            timer:7000,
                            icon:'error',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        );
                    }
                }
                else{
                    $this->dispatch('alert',                    
                        title:'Désolé, veuillez créer une session svp!',
                        timer:7000,
                        icon:'error',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );
                } 
            }else{ 
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
    public function confirmerDelete($id){ 
        $this->confirmer = $id;      
    } 
    public function supprimer(int $id, int $id_prod, int $id_entrepot){        
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->pv;
            if($autoriser == 1){      
                if($id){   
                    
                    $stockAtuel = Stock :: where('societe',auth()->user()->societe)->where('id_entrepot',$id_entrepot)->where('id_produit',$id_prod)->first(); 
                    $id_stockProd = $stockAtuel->id;
                    $QteStockActuel = $stockAtuel->quantite;
                    $prix_moyen_pondere_achat = $stockAtuel->prix_moyen_pondere_achat;
                    $prix_vente_unitaire = $stockAtuel->prix_vente_unitaire;
                    $id_entrepot = $stockAtuel->id_entrepot;
                    $nom_produit = $stockAtuel->nom_produit;        
                    $id_produit = $stockAtuel->id_produit;
                    $reference = $stockAtuel->reference;

                    $entrepo = Entrepot::where('id',$id_entrepot)->first();
                    $nom_entrepot = $entrepo->nom;    

                    $libele_mouvement = 'Retour expédition ';
                    $code_mouvement = date('YmdHis');
                    $statut = 'POS';

                    $quantitActuel = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id',$id)->first();
                    $qte_ligne = $quantitActuel->quantite;
                    $code_facture = $quantitActuel->code_facture;
                    $id_Posfact_cltEntete = $quantitActuel->id_facture_client_entete;

                     // Mise a jour du stock                             
                     $qteSockFinal = $QteStockActuel + $qte_ligne;
                     $valorisation_achat_total = $prix_moyen_pondere_achat * $qteSockFinal;
                     $valeur_vente_total = $prix_vente_unitaire * $qteSockFinal;
                     Stock::where('societe',auth()->user()->societe)->where('id',$id_stockProd)->update(['quantite'=>$qteSockFinal,'valorisation_achat_total'=>$valorisation_achat_total,'valeur_vente_total'=>$valeur_vente_total,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);   
                    //                  
                    
                    $testLigne =  PosFactureClientLigne::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->count();
                    if($testLigne == 1){ 
                       PosFactureClientLigne::where('id',$id)->delete();
                       PosFactureClientEntete::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->delete();
                    }
                    else{ 
                       PosFactureClientLigne::where('id',$id)->delete();
                    }
                   
                    $montantHT = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$id_Posfact_cltEntete)->sum('montant_ht');
                    $montantTTC = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$id_Posfact_cltEntete)->sum('montant_ttc');
                    $montantRemise = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$id_Posfact_cltEntete)->sum('montant_remise');
                    $montantTva = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$id_Posfact_cltEntete)->sum('montant_tva');
                    $montantPrecompte = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$id_Posfact_cltEntete)->sum('montant_precompte');
                    $marge = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$id_Posfact_cltEntete)->sum('marge');
                    
                    $testEntete =  PosFactureClientEntete::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->count();
                    if($testEntete > 0){
                        // Mise a jour PosFactureClientEntete
                         PosFactureClientEntete::find($id_Posfact_cltEntete)->update(['montant_ht'=>$montantHT,'montant_remise'=>$montantRemise,'montant_tva'=>$montantTva,'montant_precompte'=>$montantPrecompte,'montant_ttc'=>number_format($montantTTC,0,',',''),
                         'reste_a_percevoir'=>number_format($montantTTC,0,',',''),'marge'=>$marge,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                    }

                    $sessions = SessionPos::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->where('etat','En cours')->first(); 
                    $id_session_pos = $sessions->id;
                    $ref_session_pos = $sessions->session_id;

                    Mouvement::create(['id_entrepot'=>$id_entrepot,'nom_produit'=>$nom_produit,'id_produit'=>$id_produit,'reference'=>$reference,'quantite'=>$qte_ligne,'libele_mouvement'=>$libele_mouvement.$code_facture,
                                    'code_mouvement'=>$code_mouvement,'statut'=>$statut,'entrepot'=>$nom_entrepot,'origine'=>$ref_session_pos,'id_session_pos'=>$id_session_pos,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);      
                    
                    $id_activite = $id_session_pos;
                    $page = 'SessionPos';
                    $ref_POS = $ref_session_pos;
                    LogActivity::addToLog('Produit retiré du panier ('.$ref_POS.')', $id_activite, $page); 
                    // session()->flash('msg-success','Suppression effectuée'); 
                    $this->dispatch('alert',                    
                        title:'Suppression effectuée',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );                                            
                } 
            }
            else{ 
                $this->dispatch('alert',                    
                    title:'Désolé, vous n\'êtes pas autorisé à effectuer cette opération !',
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
    public function edit($id){  
        $cmd = PosFactureClientLigne ::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->where('id',$id)->first();
        $this->ids = $cmd->id;
        $this->quantite_ajoute = $cmd->quantite;
        $this->quantite_base = $cmd->quantite; // normal
        $this->prix_achat = $cmd->prix_achat;        
        $this->prix_vente_client = $cmd->prix_vente;  
        $this->remise = $cmd->remise; 
        $this->tva = $cmd->tva;
        $this->precompte = $cmd->precompte;
        $this->offrir = $cmd->offrir;
        $this->infos = $cmd->infos;
        $this->id_entrepot = $cmd->id_entrepot;            
        $this->id_produit = $cmd->id_produit;
        $this->produit = $cmd->produit;  
        $this->quantite_expediee = $cmd->quantite_expediee;  
        $this->reference = $cmd->reference; 
    }
    public function ajouterdata(){
        $this->validate([                   
            'prix_vente_client'=>'required|numeric',
            'quantite_ajoute'=>'required|numeric', 
            'remise'=>'required|numeric', 
            'tva'=>'required|numeric', 
            'precompte'=>'required|numeric', 
            'offrir'=>'required|max:3', 
            'infos'=>'max:255', 
        ]);    
        
        $test_paye = PosfactureClientEntete ::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->first();
        $etat_paye = $test_paye->etat;
        if($etat_paye == "Brouillon"){

            $prod = Stock::where('societe',auth()->user()->societe)->where('id_produit',$this->id_produit)->where('id_entrepot',$this->id_entrepot)->first();
            $id_stockProd = $prod->id;  
            $id_entrepot = $prod->id_entrepot;  
            $nom_produit = $prod->nom_produit;      
            $id_produit = $prod->id_produit;        
            $reference = $prod->reference;
            $quantite_stock = $prod->quantite; // quantite stock de la bd
            $prix_achat_last = $prod->prix_achat_last;
            $prix_moyen_pondere_achat = $prod->prix_moyen_pondere_achat;                
            $prix_vente_unitaire = $prod->prix_vente_unitaire;
            $prix_vente_min = $prod->prix_vente_min;

            $entrepo = Entrepot::where('id',$id_entrepot)->first();
            $nom_entrepot = $entrepo->nom;
            // pour movement
            $sessions = SessionPos::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->where('etat','En cours')->first(); 
            $id_session_pos = $sessions->id;
            $ref_session_pos = $sessions->session_id;

            $libele_mouvement = 'Expédition ';
            $code_mouvement = date('YmdHis');
            $statut = 'POS';
             
            $qteAjout_moin_qteStock = $this->quantite_ajoute - $this->quantite_base;  

            if($this->offrir == 'Non'){                     
                        
                if($qteAjout_moin_qteStock > $quantite_stock){   
                    $this->dispatch('alert',                    
                        title:'Désolé, la quantité demandée('.$this->quantite_ajoute.') est supérieure au stock disponible('.$quantite_stock.') !',
                        timer:5000,
                        icon:'error',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-start',
                    );   
                }        
                elseif($this->prix_vente_client < $prix_vente_min){  
                    $this->dispatch('alert',                    
                        title:'Désolé, prix vente ('.$this->prix_vente_client.') demandé est inférieure au prix de vente min !',
                        timer:5000,
                        icon:'error',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-start',
                    ); 
                }
                elseif($this->quantite_ajoute < -1){
                    $this->dispatch('alert',                    
                        title:'Désolé, la quantité retirable est de -1 !',
                        timer:5000,
                        icon:'error',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    ); 
                    $this->quantite_ajoute = 0;
                    $this->dispatch('dataAjout');
                }        
                else{                
                       
                    $offrir = 'Non';                                 
                    $quantiteFinal = $this->quantite_base + $qteAjout_moin_qteStock; // nouvelle quantite apres rajout                   
                    
                    // ************ Calul ***********
                    $montant_vente =  $this->prix_vente_client * $quantiteFinal;                        
                    $montant_achat_avec_qte = ($prix_moyen_pondere_achat * $quantiteFinal);  

                    $remiseDetail = $this->remise/100; // valeur remise
                    $remise_montant = ($this->prix_vente_client * $quantiteFinal) * $remiseDetail; // montant remise

                    $montant_remiser_ht = ($this->prix_vente_client * $quantiteFinal) - $remise_montant; // Montant vente avec remise inclus hors taxe                        
                    $marge = $montant_remiser_ht - $montant_achat_avec_qte; // Marge avec remise                       

                    $tvaDetail = $this->tva/100; // valeur de la tva
                    $tva_montant = $montant_remiser_ht * $tvaDetail;

                    $precompteDetail = $this->precompte/100; // valeur du precompte
                    $precompte_montant = $montant_remiser_ht * $precompteDetail;

                    $montant_ttc = $montant_remiser_ht + $tva_montant + $precompte_montant; // Montant TTC
                    //************** Fin Calcul ***********//
                    
                    if($montant_remiser_ht < $montant_achat_avec_qte){
                        $this->dispatch('alert',                    
                            title:'Désolé, La reduction ('.$this->remise.'%) est trop grande par rapport à votre marge!',
                            timer:5000,
                            icon:'error',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        ); 
                        $this->quantite_ajoute = 0; 
                        $this->dispatch('dataAjout'); 
                    }
                    else{ 
                        
                        // Mise a jour du stock    
                        $quantite = $qteAjout_moin_qteStock; // quantite ajoute ou retire
                        $qteSockFinal = $quantite_stock - $quantite;
                        $valorisation_achat_total = $prix_moyen_pondere_achat * $qteSockFinal;
                        $valeur_vente_total = $prix_vente_unitaire * $qteSockFinal;
                        Stock::where('societe',auth()->user()->societe)->where('id',$id_stockProd)->update(['quantite'=>$qteSockFinal,'valorisation_achat_total'=>$valorisation_achat_total,'valeur_vente_total'=>$valeur_vente_total,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);   
                        //

                        $recup = PosfactureClientEntete ::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->first();
                        $id_fPosEntete = $recup->id;
                        $code_facture = $recup->code_facture;
                        $nom_client = $recup->nom_client;
                        $id_client = $recup->id_client;

                        $quantite_expediee = $this->quantite_expediee + $quantite;
                        $reste_a_expedier = 0;
                        PosFactureClientLigne::where('id',$this->ids)->update(['prix_vente'=>$this->prix_vente_client,'quantite'=>$quantiteFinal,'quantite_expediee'=>$quantite_expediee,'reste_a_expedier'=>$reste_a_expedier,
                        'remise'=>$this->remise,'montant_remise'=>$remise_montant,'tva'=>$this->tva,'montant_tva'=>$tva_montant,'precompte'=>$this->precompte,'montant_precompte'=>$precompte_montant,
                        'montant_ht'=>$montant_remiser_ht,'montant_ttc'=>$montant_ttc,'marge'=>$marge,'nom_client'=>$nom_client,'id_client'=>$id_client,'offrir'=>$offrir,'infos'=>$this->infos,
                        'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                     
                                
                        $montantHT = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$id_fPosEntete)->sum('montant_ht');
                        $montantTTC = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$id_fPosEntete)->sum('montant_ttc');
                        $montantRemise = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$id_fPosEntete)->sum('montant_remise');
                        $montantTva = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$id_fPosEntete)->sum('montant_tva');
                        $montantPrecompte = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$id_fPosEntete)->sum('montant_precompte');
                        $marge = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$id_fPosEntete)->sum('marge');

                        // Montant TTC en arrondi en + ou en - 
                        PosFactureClientEntete::find($id_fPosEntete)->update(['montant_ht'=>$montantHT,'montant_remise'=>$montantRemise,'montant_tva'=>$montantTva,'montant_precompte'=>$montantPrecompte,'montant_ttc'=>number_format($montantTTC,0,',',''),
                        'reste_a_percevoir'=>number_format($montantTTC,0,',',''),'marge'=>$marge,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                        
                        Mouvement::create(['id_entrepot'=>$id_entrepot,'nom_produit'=>$nom_produit,'id_produit'=>$id_produit,'reference'=>$reference,'quantite'=>-$quantite,'libele_mouvement'=>$libele_mouvement.$code_facture,
                                    'code_mouvement'=>$code_mouvement,'statut'=>$statut,'entrepot'=>$nom_entrepot,'origine'=>$ref_session_pos,'id_session_pos'=>$id_session_pos,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);  

                        $this->resetinputFields();  
                        $this->dispatch('dataAjout');
                        $id_activite = $id_session_pos;
                        $page = 'SessionPos';
                        $ref_POS = $ref_session_pos;
                        LogActivity::addToLog('Données ('.$ref_POS.') modifiée', $id_activite, $page);
                        $this->dispatch('alert',                    
                            title:'Modification ('.$this->produit.') enregistrée!',
                            timer:5000,
                            icon:'success',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        );            
                    }                                    
                }                       
            }
            elseif($this->offrir == 'Oui'){
            
                if($qteAjout_moin_qteStock > $quantite_stock){   
                    $this->dispatch('alert',                    
                        title:'Désolé, la quantité demandée('.$this->quantite_ajoute.') est supérieure au stock disponible('.$quantite_stock.') !',
                        timer:5000,
                        icon:'error',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-start',
                    );     
                }            
                elseif($this->quantite_ajoute < -1){
                    $this->dispatch('alert',                    
                        title:'Désolé, la quantité retirable est de -1 !',
                        timer:5000,
                        icon:'error',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-start',
                    );  
                    $this->quantite_ajoute = 0;
                    $this->dispatch('dataAjout');
                }        
                else{

                    // recuperer le prix de vente pour l'utiser plus bas
                    $prix_vente_initial = $this->prix_vente_client;

                    $offrir = 'Oui';   
                    // ceci met le prix de vente a zero : les totaux seront a zero
                    $this->prix_vente_client = 0;

                    $quantiteFinal = $this->quantite_base + $qteAjout_moin_qteStock; // nouvelle quantite apres rajout
                    
                    // ************ Calul ***********
                    $montant_vente =  $this->prix_vente_client * $quantiteFinal;                        
                    $montant_achat_avec_qte = ($prix_moyen_pondere_achat * $quantiteFinal);  

                    $remiseDetail = $this->remise/100; // valeur remise
                    $remise_montant = ($this->prix_vente_client * $quantiteFinal) * $remiseDetail; // montant remise

                    $montant_remiser_ht = ($this->prix_vente_client * $quantiteFinal) - $remise_montant; // Montant vente avec remise inclus hors taxe                        
                    $marge = $montant_remiser_ht - $montant_achat_avec_qte; // Marge avec remise                       

                    $tvaDetail = $this->tva/100; // valeur de la tva
                    $tva_montant = $montant_remiser_ht * $tvaDetail;

                    $precompteDetail = $this->precompte/100; // valeur du precompte
                    $precompte_montant = $montant_remiser_ht * $precompteDetail;

                    $montant_ttc = $montant_remiser_ht + $tva_montant + $precompte_montant; // Montant TTC
                    //************** Fin Calcul ***********//


                    // Mise a jour du stock    
                    $quantite = $qteAjout_moin_qteStock; // quantite ajoute ou retire
                    $qteSockFinal = $quantite_stock - $quantite;
                    $valorisation_achat_total = $prix_moyen_pondere_achat * $qteSockFinal;
                    $valeur_vente_total = $prix_vente_unitaire * $qteSockFinal;
                    Stock::where('societe',auth()->user()->societe)->where('id',$id_stockProd)->update(['quantite'=>$qteSockFinal,'valorisation_achat_total'=>$valorisation_achat_total,'valeur_vente_total'=>$valeur_vente_total,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);   
                    //

                    $recup = PosfactureClientEntete ::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->first();
                    $id_fPosEntete = $recup->id;
                    $code_facture = $recup->code_facture;
                    $nom_client = $recup->nom_client;
                    $id_client = $recup->id_client;

                    $quantite_expediee = $this->quantite_expediee + $quantite;
                    $reste_a_expedier = 0;

                    //ceci pour ne pas mettre une marge negative
                    $marge_final = 0;

                    PosFactureClientLigne::where('id',$this->ids)->update(['prix_vente'=>$prix_vente_initial,'quantite'=>$quantiteFinal,'quantite_expediee'=>$quantite_expediee,'reste_a_expedier'=>$reste_a_expedier,
                    'remise'=>$this->remise,'montant_remise'=>$remise_montant,'tva'=>$this->tva,'montant_tva'=>$tva_montant,'precompte'=>$this->precompte,'montant_precompte'=>$precompte_montant,
                    'montant_ht'=>$montant_remiser_ht,'montant_ttc'=>$montant_ttc,'marge'=>$marge_final,'nom_client'=>$nom_client,'id_client'=>$id_client,'offrir'=>$offrir,'infos'=>$this->infos,
                    'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                     
                            
                    $montantHT = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$id_fPosEntete)->sum('montant_ht');
                    $montantTTC = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$id_fPosEntete)->sum('montant_ttc');
                    $montantRemise = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$id_fPosEntete)->sum('montant_remise');
                    $montantTva = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$id_fPosEntete)->sum('montant_tva');
                    $montantPrecompte = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$id_fPosEntete)->sum('montant_precompte');
                    $marge = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$id_fPosEntete)->sum('marge');

                    // Montant TTC en arrondi en + ou en - 
                    PosFactureClientEntete::find($id_fPosEntete)->update(['montant_ht'=>$montantHT,'montant_remise'=>$montantRemise,'montant_tva'=>$montantTva,'montant_precompte'=>$montantPrecompte,'montant_ttc'=>number_format($montantTTC,0,',',''),
                    'reste_a_percevoir'=>number_format($montantTTC,0,',',''),'marge'=>$marge_final,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                    
                    Mouvement::create(['id_entrepot'=>$id_entrepot,'nom_produit'=>$nom_produit,'id_produit'=>$id_produit,'reference'=>$reference,'quantite'=>-$quantite,'libele_mouvement'=>$libele_mouvement.$code_facture,
                                    'code_mouvement'=>$code_mouvement,'statut'=>$statut,'entrepot'=>$nom_entrepot,'origine'=>$ref_session_pos,'id_session_pos'=>$id_session_pos,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);  

                    $this->resetinputFields();  
                    $this->dispatch('dataAjout');
                    $id_activite = $id_session_pos;
                    $page = 'SessionPos';
                    $ref_POS = $ref_session_pos;
                    LogActivity::addToLog('Données ('.$ref_POS.') modifiée', $id_activite, $page);
                    $this->dispatch('alert',                    
                        title:'Modification ('.$this->produit.') enregistrée!',
                        timer:5000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );                             
                }
            }
        }
        else{
            $this->dispatch('alert',                    
                title:'Désolé, vous ne pouvez plus modifier (paiement encours)!',
                timer:5000,
                icon:'error',
                toast:true,
                showConfirmButton: false,
                position:'top-end',
            );  
        }       
    }
    public function prendre(int $id){
        $tiers = Tier::where('societe',auth()->user()->societe)->where('id',$id)->first();
        $id_tier = $tiers->id;
        $nom_tier = $tiers->nom;

        PosFactureClientEntete::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->update(['nom_client'=>$nom_tier,'id_client'=>$id_tier,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
        PosFactureClientLigne::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->update(['nom_client'=>$nom_tier,'id_client'=>$id_tier,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
        $this->dispatch('alert',                    
            title:$nom_tier.' sélectionné!',
            timer:5000,
            icon:'success',
            toast:true,
            showConfirmButton: false,
            position:'top-end',
        );  
        $sessions = SessionPos::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->where('etat','En cours')->first(); 
        $this->id_pos = $sessions->id;
        $this->ref_pos = $sessions->session_id; 
        $this->dispatch('fermerTier');  
        $this->redirect('/pos?id='.$this->id_pos.'&ref='.$this->ref_pos, navigate: true); // ceci permet d'actualiser la page (important)
    }
    public function enregistrer(){       

        $this->dispatch('alert',                    
            title:'client crée!',
            timer:5000,
            icon:'success',
            toast:true,
            showConfirmButton: false,
            position:'top-end',
        );   
        $this->dispatch('fermerCreateTier');  
    }
    public function AffichePaie(){
        $comptes = PosFactureClientEntete::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->first(); 
        $this->idPosFClt = $comptes->id;
        $this->client_id = $comptes->id_client;
        $this->nom_client = $comptes->nom_client;
        $this->date_facturation = $comptes->date_facturation;
        $this->reference = $comptes->code_facture;
        $this->montantTTC = $comptes->montant_ttc;
        $this->montantRecu = $comptes->montant_recu;
        $this->resteApercevoir = $this->montantTTC - $this->montantRecu;
        $this->Reste_a_Percevoir = $this->resteApercevoir;
        $this->etats = $this->etat;        

        $sessions = SessionPos::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->where('etat','En cours')->first(); 
        $this->id_pos = $sessions->id;
        $this->ref_pos = $sessions->session_id;

        $tiercltCount = Tier::where('societe',auth()->user()->societe)->where('id',$this->client_id)->count(); 
        if($tiercltCount > 0){
            $tierclt = Tier::where('societe',auth()->user()->societe)->where('id',$this->client_id)->first();         
            $this->soldeClientDispo = $tierclt->solde;
        }
    }
    public function coller(){
        $comptes = PosFactureClientEntete::where('societe',auth()->user()->societe)->where('id',$this->idPosFClt)->first();               
        $montantTTC = $comptes->montant_ttc;
        $montantRecu = $comptes->montant_recu;
        $this->montant_reglement = $montantTTC - $montantRecu;               
    } 
    public function payer(){
        $this->validate([                   
            'mode_reglement'=>'required',
            'compte_bancaire'=>'required|numeric', //recupere id
            'commentaire'=>'max:250', 
            'montant_reglement'=>'required|numeric', 
        ]);  
        // ceci verifie si une session est ouverte ou pas
        $testSessions = SessionPos::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->where('etat','En cours')->count(); 
        if($testSessions > 0){ 

            if(!empty($this->nom_client)){
                if($this->resteApercevoir > 0){
                                
                    if($this->montant_reglement >= $this->resteApercevoir){
                                
                        // Compte bancaire
                        $CompteBq = CompteBancaire::where('societe',auth()->user()->societe)->where('id',$this->compte_bancaire)->first();   
                        $nom_compte_bancaire = $CompteBq->nom_compte_bancaire;

                        // Ecriture bancaire
                        $ref_ecritureBq = date('ymd-His');
                        $description = 'Règlement client';
                        $date_valeur = date('Y-m-d');
                        $date_operation = date('Y-m-d');
                        $debit = 0; 
                        $solde = 0;  
                        $type_paiement = 'ReglementClientNonOk';  
                        $statut = 'En cours';            
                        EcritureBancaire::create(['id_compte_bancaire'=>$this->compte_bancaire,'id_type_paiement'=>$this->compte_bancaire,'nom_compte_bancaire'=>$nom_compte_bancaire,'reference'=>$ref_ecritureBq,'description'=>$description,
                                        'date_operation'=>$date_operation,'date_valeur'=>$date_valeur,'type_operation'=>$this->mode_reglement,'debit'=>$debit,'credit'=>number_format($this->resteApercevoir,0,',',''),'solde'=>$solde,
                                        'type_paiement'=>$type_paiement,'id_facture_client_entete'=>$this->idPosFClt,'code_facture'=>$this->reference,'id_tiers'=>$this->client_id,'tiers'=>$this->nom_client,'statut'=>$statut,
                                        'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                    

                        // ceci recupere le dernier enregistrement cree a l'instant
                        $dernier_id = EcritureBancaire::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id; 

                        // ceci calcul le solde
                        $soldeCredit = EcritureBancaire::where('societe',auth()->user()->societe)->where('id_compte_bancaire',$this->compte_bancaire)->sum('credit');
                        $soldeDebit = EcritureBancaire::where('societe',auth()->user()->societe)->where('id_compte_bancaire',$this->compte_bancaire)->sum('debit');  
                        $solde = $soldeCredit - $soldeDebit;
                        CompteBancaire::where('societe',auth()->user()->societe)->where('id',$this->compte_bancaire)->update(['solde'=>$solde,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                        // Reglement 
                        $refReglement = 'PAY'.date('ymd-His');
                        Reglement::create(['ref_reglement'=>$refReglement,'id_facture_client_entete'=>$this->idPosFClt,'code_facture'=>$this->reference,'id_client'=>$this->client_id,'nom_client'=>$this->nom_client,'id_ecriture_bancaire'=>$dernier_id,'ecriture_bancaire'=>$ref_ecritureBq,
                                        'mode_reglement'=>$this->mode_reglement,'compte_bancaire'=>$nom_compte_bancaire,'id_compte_bancaire'=>$this->compte_bancaire,'date_reglement'=>$date_operation,'id_session_pos'=>$this->id_pos,'ref_session_pos'=>$this->ref_pos,
                                        'commentaire'=>$this->commentaire,'montant_regler'=>number_format($this->resteApercevoir,0,',',''),'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                        $dejaRegler = Reglement::where('societe',auth()->user()->societe)->where('code_facture',$this->reference)->sum('montant_regler');                            

                        // Facture entete
                        $etat ='Payée';
                        $reste_a_percevoir = 0;
                        PosFactureClientEntete::find($this->idPosFClt)->update(['montant_recu'=>number_format($dejaRegler,0,',',''),'reste_a_percevoir'=>number_format($reste_a_percevoir,0,',',''),'mode_reglement'=>$this->mode_reglement,
                                            'note'=>$this->commentaire,'etat'=>$etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                        PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->idPosFClt)->update(['etat'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                       
                        // Point fidelite
                        if($this->activer_fidelite == 1){  
                            if($this->montantPointBD <= 0){
                                $this->montantPointBD = 1; // pour eviter division par zero
                                $nbrePts = number_format($this->montant_reglement / $this->montantPointBD,0,'','');
                            }
                            else{
                                $nbrePts = number_format($this->montant_reglement / $this->montantPointBD,0,'','');
                            }

                            $Tiers = Tier ::where('societe',auth()->user()->societe)->where('id',$this->client_id)->get();                            
                            $this->pointsNbreBD = $Tiers[0]->nombre_point; 

                            $nbrePtsTotal = $nbrePts + $this->pointsNbreBD;
                            Tier::where('societe',auth()->user()->societe)->where('id',$this->client_id)->update(['nombre_point'=>$nbrePtsTotal]);
                        }

                        $id_activite = $this->id_pos;
                        $page = 'SessionPos';
                        $ref_POS = $this->ref_pos;
                        LogActivity::addToLog('Paiement '.$nom_compte_bancaire.' » '.$this->montant_reglement.' '.$this->devise.' facture ('.$this->reference.') ajouté', $id_activite, $page);
                        $this->dispatch('alert',                    
                            title:'Paiement ('.$this->resteApercevoir.' '.$this->devise.') enregistré !',
                            timer:5000,
                            icon:'success',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        );  
                        $this->montant_reglement = ''; 
                        $this->dispatch('fermerPosPaie');
                        $this->redirect('/pos?id='.$this->id_pos.'&ref='.$this->ref_pos, navigate: true); // ceci permet d'actualiser la page (important)
                    }
                    else{
                    
                        // Compte bancaire
                        $CompteBq = CompteBancaire::where('societe',auth()->user()->societe)->where('id',$this->compte_bancaire)->first();   
                        $nom_compte_bancaire = $CompteBq->nom_compte_bancaire;

                        // Ecriture bancaire
                        $ref_ecritureBq = date('ymd-His');
                        $description = 'Règlement client';
                        $date_valeur = date('Y-m-d');
                        $date_operation = date('Y-m-d');
                        $debit = 0;   
                        $solde = 0;  
                        $type_paiement = 'ReglementClientNonOk'; 
                        $statut = 'En cours';              
                        EcritureBancaire::create(['id_compte_bancaire'=>$this->compte_bancaire,'id_type_paiement'=>$this->compte_bancaire,'nom_compte_bancaire'=>$nom_compte_bancaire,'reference'=>$ref_ecritureBq,'description'=>$description,
                                        'date_operation'=>$date_operation,'date_valeur'=>$date_valeur,'type_operation'=>$this->mode_reglement,'debit'=>$debit,'credit'=>number_format($this->montant_reglement,0,',',''),'solde'=>$solde,
                                        'type_paiement'=>$type_paiement,'id_facture_client_entete'=>$this->idPosFClt,'code_facture'=>$this->reference,'id_tiers'=>$this->client_id,'tiers'=>$this->nom_client,'statut'=>$statut,
                                        'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                    
                        
                        // ceci recupere le dernier enregistrement cree a l'instant
                        $dernier_id = EcritureBancaire::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id; 

                        // ceci calcul le solde
                        $soldeCredit = EcritureBancaire::where('societe',auth()->user()->societe)->where('id_compte_bancaire',$this->compte_bancaire)->sum('credit');
                        $soldeDebit = EcritureBancaire::where('societe',auth()->user()->societe)->where('id_compte_bancaire',$this->compte_bancaire)->sum('debit');  
                        $solde = $soldeCredit - $soldeDebit;
                        CompteBancaire::where('societe',auth()->user()->societe)->where('id',$this->compte_bancaire)->update(['solde'=>$solde,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                        
                        // Reglement 
                        $refReglement = 'PAY'.date('ymd-His');
                        Reglement::create(['ref_reglement'=>$refReglement,'id_facture_client_entete'=>$this->idPosFClt,'code_facture'=>$this->reference,'id_client'=>$this->client_id,'nom_client'=>$this->nom_client,'id_ecriture_bancaire'=>$dernier_id,'ecriture_bancaire'=>$ref_ecritureBq,
                                        'mode_reglement'=>$this->mode_reglement,'compte_bancaire'=>$nom_compte_bancaire,'id_compte_bancaire'=>$this->compte_bancaire,'date_reglement'=>$date_operation,'id_session_pos'=>$this->id_pos,'ref_session_pos'=>$this->ref_pos,
                                        'commentaire'=>$this->commentaire,'montant_regler'=>number_format($this->montant_reglement,0,',',''),'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                                        
                        $dejaRegle = Reglement::where('societe',auth()->user()->societe)->where('code_facture',$this->reference)->sum('montant_regler');

                        // Facture entete
                        $etat ='Commencée';
                        $reste = $this->resteApercevoir - $this->montant_reglement;
                        PosFactureClientEntete::find($this->idPosFClt)->update(['montant_recu'=>number_format($dejaRegle,0,',',''),'reste_a_percevoir'=>number_format($reste,0,',',''),
                                            'note'=>$this->commentaire,'etat'=>$etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                        PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->idPosFClt)->update(['etat'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                        // Point fidelite
                        if($this->activer_fidelite == 1){  
                            if($this->montantPointBD <= 0){
                                $this->montantPointBD = 1; // pour eviter division par zero
                                $nbrePts = number_format($this->montant_reglement / $this->montantPointBD,0,'','');
                            }
                            else{
                                $nbrePts = number_format($this->montant_reglement / $this->montantPointBD,0,'','');
                            }
                            
                            $Tiers = Tier ::where('societe',auth()->user()->societe)->where('id',$this->client_id)->get();                            
                            $this->pointsNbreBD = $Tiers[0]->nombre_point; 

                            $nbrePtsTotal = $nbrePts + $this->pointsNbreBD;
                            Tier::where('societe',auth()->user()->societe)->where('id',$this->client_id)->update(['nombre_point'=>$nbrePtsTotal]);
                        }
                        
                        $id_activite = $this->id_pos;
                        $page = 'SessionPos';
                        $ref_POS = $this->ref_pos;
                        LogActivity::addToLog('Paiement '.$nom_compte_bancaire.' » '.$this->montant_reglement.' '.$this->devise.' facture ('.$this->reference.') ajouté', $id_activite, $page);
                        $this->dispatch('alert',                    
                            title:'Paiement ('.$this->montant_reglement.' '.$this->devise.') enregistré !',
                            timer:5000,
                            icon:'success',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        );      
                        $this->montant_reglement = '';                        
                        $this->dispatch('fermerPosPaie');
                        $this->redirect('/pos?id='.$this->id_pos.'&ref='.$this->ref_pos, navigate: true); // ceci permet d'actualiser la page (important)
                    }

                }
                else{

                    $this->dispatch('alert',                    
                        title:'Désolé, vous ne pouvez plus effectuer de paiement ('.$this->resteApercevoir.' '.$this->devise.')',
                        timer:5000,
                        icon:'error',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    ); 
                } 
            } 
            else{

                $this->dispatch('alert',                    
                    title:'Désolé, veuillez sélectionner un client svp!',
                    timer:5000,
                    icon:'warning',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                ); 
            } 
        } 
        else{
            $this->dispatch('alert',                    
                title:'Désolé, veuillez créer une session svp!',
                timer:7000,
                icon:'error',
                toast:true,
                showConfirmButton: false,
                position:'top-end',
            );
        }      
    }
    public function payerAvecSolde(){
        $this->validate([                   
            'mode_reglement'=>'required',
            'compte_bancaire'=>'required|numeric', //recupere id
            'commentaire'=>'max:250', 
            'montant_reglement'=>'required|numeric', 
        ]);  
        // ceci verifie si une session est ouverte ou pas
        $testSessions = SessionPos::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->where('etat','En cours')->count(); 
        if($testSessions > 0){ 

            if(!empty($this->nom_client)){
                if($this->resteApercevoir > 0){

                    $tierclt = Tier::where('societe',auth()->user()->societe)->where('id',$this->client_id)->first(); 
                    $nom = $tierclt->nom; 
                    $code_tier =$tierclt->code_tier; 
                    $soldeClient = $tierclt->solde;
                    $raison_sociale = $tierclt->raison_sociale;
                    $telephone = $tierclt->telephone;
                    $adresse = $tierclt->adresse;
                    $ville = $tierclt->ville;
                    $pays =$tierclt->pays;
                    $email =$tierclt->email;
                                
                    if($this->montant_reglement >= $this->resteApercevoir){
                        if($this->montant_reglement <= $soldeClient){  

                            $soldeRestant = $soldeClient - $this->montant_reglement;  
                            Tier::where('societe',auth()->user()->societe)->where('id',$this->client_id)->update(['solde'=>$soldeRestant,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                            $credit = 0;
                            $designation = 'Facturation client » '.$this->reference;
                            SoldeTier::create(['id_tier'=>$this->client_id,'nom_tier'=>$nom,'code_tier'=>$code_tier,'raison_sociale'=>$raison_sociale,'designation'=>$designation,'debit'=>$this->montant_reglement,'credit'=>$credit,
                                    'pays'=>$pays,'ville'=>$ville,'adresse'=>$adresse,'telephone'=>$telephone,'email'=>$email,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                        
                            // Compte bancaire
                            $CompteBq = CompteBancaire::where('societe',auth()->user()->societe)->where('id',$this->compte_bancaire)->first();   
                            $nom_compte_bancaire = $CompteBq->nom_compte_bancaire;

                            // Ecriture bancaire
                            $ref_ecritureBq = date('ymd-His');
                            $description = 'Règlement client';
                            $date_valeur = date('Y-m-d');
                            $date_operation = date('Y-m-d');
                            $debit = 0; 
                            $solde = 0;  
                            $type_paiement = 'ReglementClientNonOk';  
                            $statut = 'En cours';            
                            EcritureBancaire::create(['id_compte_bancaire'=>$this->compte_bancaire,'id_type_paiement'=>$this->compte_bancaire,'nom_compte_bancaire'=>$nom_compte_bancaire,'reference'=>$ref_ecritureBq,'description'=>$description,
                                            'date_operation'=>$date_operation,'date_valeur'=>$date_valeur,'type_operation'=>$this->mode_reglement,'debit'=>$debit,'credit'=>number_format($this->resteApercevoir,0,',',''),'solde'=>$solde,
                                            'type_paiement'=>$type_paiement,'id_facture_client_entete'=>$this->idPosFClt,'code_facture'=>$this->reference,'id_tiers'=>$this->client_id,'tiers'=>$this->nom_client,'statut'=>$statut,
                                            'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                    

                            // ceci recupere le dernier enregistrement cree a l'instant
                            $dernier_id = EcritureBancaire::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id; 

                            // ceci calcul le solde
                            $soldeCredit = EcritureBancaire::where('societe',auth()->user()->societe)->where('id_compte_bancaire',$this->compte_bancaire)->sum('credit');
                            $soldeDebit = EcritureBancaire::where('societe',auth()->user()->societe)->where('id_compte_bancaire',$this->compte_bancaire)->sum('debit');  
                            $solde = $soldeCredit - $soldeDebit;
                            CompteBancaire::where('societe',auth()->user()->societe)->where('id',$this->compte_bancaire)->update(['solde'=>$solde,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                            // Reglement 
                            $refReglement = 'PAY'.date('ymd-His');
                            Reglement::create(['ref_reglement'=>$refReglement,'id_facture_client_entete'=>$this->idPosFClt,'code_facture'=>$this->reference,'id_client'=>$this->client_id,'nom_client'=>$this->nom_client,'id_ecriture_bancaire'=>$dernier_id,'ecriture_bancaire'=>$ref_ecritureBq,
                                            'mode_reglement'=>$this->mode_reglement,'compte_bancaire'=>$nom_compte_bancaire,'id_compte_bancaire'=>$this->compte_bancaire,'date_reglement'=>$date_operation,'id_session_pos'=>$this->id_pos,'ref_session_pos'=>$this->ref_pos,
                                            'commentaire'=>$this->commentaire,'montant_regler'=>number_format($this->resteApercevoir,0,',',''),'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                            $dejaRegler = Reglement::where('societe',auth()->user()->societe)->where('code_facture',$this->reference)->sum('montant_regler');                            

                            // Facture entete
                            $etat ='Payée';
                            $reste_a_percevoir = 0;
                            PosFactureClientEntete::find($this->idPosFClt)->update(['montant_recu'=>number_format($dejaRegler,0,',',''),'reste_a_percevoir'=>number_format($reste_a_percevoir,0,',',''),'mode_reglement'=>$this->mode_reglement,
                                                'note'=>$this->commentaire,'etat'=>$etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                            PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->idPosFClt)->update(['etat'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                        
                            // Point fidelite
                            if($this->activer_fidelite == 1){  
                                if($this->montantPointBD <= 0){
                                    $this->montantPointBD = 1; // pour eviter division par zero
                                    $nbrePts = number_format($this->montant_reglement / $this->montantPointBD,0,'','');
                                }
                                else{
                                    $nbrePts = number_format($this->montant_reglement / $this->montantPointBD,0,'','');
                                }

                                $Tiers = Tier ::where('societe',auth()->user()->societe)->where('id',$this->client_id)->get();                            
                                $this->pointsNbreBD = $Tiers[0]->nombre_point; 

                                $nbrePtsTotal = $nbrePts + $this->pointsNbreBD;
                                Tier::where('societe',auth()->user()->societe)->where('id',$this->client_id)->update(['nombre_point'=>$nbrePtsTotal]);
                            }

                            $id_activite = $this->id_pos;
                            $page = 'SessionPos';
                            $ref_POS = $this->ref_pos;
                            LogActivity::addToLog('Paiement avec solde '.$nom_compte_bancaire.' » '.$this->montant_reglement.' '.$this->devise.' facture ('.$this->reference.') ajouté', $id_activite, $page);
                            $this->dispatch('alert',                    
                                title:'Paiement ('.$this->resteApercevoir.' '.$this->devise.') enregistré !',
                                timer:5000,
                                icon:'success',
                                toast:true,
                                showConfirmButton: false,
                                position:'top-end',
                            );  
                            $this->montant_reglement = ''; 
                            $this->dispatch('fermerPosPaie');
                            $this->redirect('/pos?id='.$this->id_pos.'&ref='.$this->ref_pos, navigate: true); // ceci permet d'actualiser la page (important)
                        }
                        else{
                            $this->dispatch('alert',                    
                                title:'Désolé, le montant règlement ('.$this->montant_reglement.' '.$this->devise.') est supérieur à votre solde disponible ('.$soldeClient.' '.$this->devise.')',
                                timer:7000,
                                icon:'error',
                                toast:true,
                                showConfirmButton: false,
                                position:'top-end',
                            ); 
                        }
                    }
                    else{
                    
                        if($this->montant_reglement <= $soldeClient){

                            $soldeRestant = $soldeClient - $this->montant_reglement;  
                            Tier::where('societe',auth()->user()->societe)->where('id',$this->client_id)->update(['solde'=>$soldeRestant,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                            $credit = 0;
                            $designation = 'Facturation client » '.$this->reference;
                            SoldeTier::create(['id_tier'=>$this->client_id,'nom_tier'=>$nom,'code_tier'=>$code_tier,'raison_sociale'=>$raison_sociale,'designation'=>$designation,'debit'=>$this->montant_reglement,'credit'=>$credit,
                                    'pays'=>$pays,'ville'=>$ville,'adresse'=>$adresse,'telephone'=>$telephone,'email'=>$email,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                            // Compte bancaire
                            $CompteBq = CompteBancaire::where('societe',auth()->user()->societe)->where('id',$this->compte_bancaire)->first();   
                            $nom_compte_bancaire = $CompteBq->nom_compte_bancaire;

                            // Ecriture bancaire
                            $ref_ecritureBq = date('ymd-His');
                            $description = 'Règlement client';
                            $date_valeur = date('Y-m-d');
                            $date_operation = date('Y-m-d');
                            $debit = 0;   
                            $solde = 0;  
                            $type_paiement = 'ReglementClientNonOk'; 
                            $statut = 'En cours';              
                            EcritureBancaire::create(['id_compte_bancaire'=>$this->compte_bancaire,'id_type_paiement'=>$this->compte_bancaire,'nom_compte_bancaire'=>$nom_compte_bancaire,'reference'=>$ref_ecritureBq,'description'=>$description,
                                            'date_operation'=>$date_operation,'date_valeur'=>$date_valeur,'type_operation'=>$this->mode_reglement,'debit'=>$debit,'credit'=>number_format($this->montant_reglement,0,',',''),'solde'=>$solde,
                                            'type_paiement'=>$type_paiement,'id_facture_client_entete'=>$this->idPosFClt,'code_facture'=>$this->reference,'id_tiers'=>$this->client_id,'tiers'=>$this->nom_client,'statut'=>$statut,
                                            'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                    
                            
                            // ceci recupere le dernier enregistrement cree a l'instant
                            $dernier_id = EcritureBancaire::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id; 

                            // ceci calcul le solde
                            $soldeCredit = EcritureBancaire::where('societe',auth()->user()->societe)->where('id_compte_bancaire',$this->compte_bancaire)->sum('credit');
                            $soldeDebit = EcritureBancaire::where('societe',auth()->user()->societe)->where('id_compte_bancaire',$this->compte_bancaire)->sum('debit');  
                            $solde = $soldeCredit - $soldeDebit;
                            CompteBancaire::where('societe',auth()->user()->societe)->where('id',$this->compte_bancaire)->update(['solde'=>$solde,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                            
                            // Reglement 
                            $refReglement = 'PAY'.date('ymd-His');
                            Reglement::create(['ref_reglement'=>$refReglement,'id_facture_client_entete'=>$this->idPosFClt,'code_facture'=>$this->reference,'id_client'=>$this->client_id,'nom_client'=>$this->nom_client,'id_ecriture_bancaire'=>$dernier_id,'ecriture_bancaire'=>$ref_ecritureBq,
                                            'mode_reglement'=>$this->mode_reglement,'compte_bancaire'=>$nom_compte_bancaire,'id_compte_bancaire'=>$this->compte_bancaire,'date_reglement'=>$date_operation,'id_session_pos'=>$this->id_pos,'ref_session_pos'=>$this->ref_pos,
                                            'commentaire'=>$this->commentaire,'montant_regler'=>number_format($this->montant_reglement,0,',',''),'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                                            
                            $dejaRegle = Reglement::where('societe',auth()->user()->societe)->where('code_facture',$this->reference)->sum('montant_regler');

                            // Facture entete
                            $etat ='Commencée';
                            $reste = $this->resteApercevoir - $this->montant_reglement;
                            PosFactureClientEntete::find($this->idPosFClt)->update(['montant_recu'=>number_format($dejaRegle,0,',',''),'reste_a_percevoir'=>number_format($reste,0,',',''),
                                                'note'=>$this->commentaire,'etat'=>$etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                            PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->idPosFClt)->update(['etat'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                            // Point fidelite
                            if($this->activer_fidelite == 1){  
                                if($this->montantPointBD <= 0){
                                    $this->montantPointBD = 1; // pour eviter division par zero
                                    $nbrePts = number_format($this->montant_reglement / $this->montantPointBD,0,'','');
                                }
                                else{
                                    $nbrePts = number_format($this->montant_reglement / $this->montantPointBD,0,'','');
                                }
                                
                                $Tiers = Tier ::where('societe',auth()->user()->societe)->where('id',$this->client_id)->get();                            
                                $this->pointsNbreBD = $Tiers[0]->nombre_point; 

                                $nbrePtsTotal = $nbrePts + $this->pointsNbreBD;
                                Tier::where('societe',auth()->user()->societe)->where('id',$this->client_id)->update(['nombre_point'=>$nbrePtsTotal]);
                            }
                            
                            $id_activite = $this->id_pos;
                            $page = 'SessionPos';
                            $ref_POS = $this->ref_pos;
                            LogActivity::addToLog('Paiement avec solde '.$nom_compte_bancaire.' » '.$this->montant_reglement.' '.$this->devise.' facture ('.$this->reference.') ajouté', $id_activite, $page);
                            $this->dispatch('alert',                    
                                title:'Paiement ('.$this->montant_reglement.' '.$this->devise.') enregistré !',
                                timer:5000,
                                icon:'success',
                                toast:true,
                                showConfirmButton: false,
                                position:'top-end',
                            );      
                            $this->montant_reglement = '';                        
                            $this->dispatch('fermerPosPaie');
                            $this->redirect('/pos?id='.$this->id_pos.'&ref='.$this->ref_pos, navigate: true); // ceci permet d'actualiser la page (important)
                        }
                        else{
                            $this->dispatch('alert',                    
                                title:'Désolé, le montant règlement ('.$this->montant_reglement.' '.$this->devise.') est supérieur à votre solde disponible ('.$soldeClient.' '.$this->devise.')',
                                timer:7000,
                                icon:'error',
                                toast:true,
                                showConfirmButton: false,
                                position:'top-end',
                            ); 
                        }
                    }

                }
                else{

                    $this->dispatch('alert',                    
                        title:'Désolé, vous ne pouvez plus effectuer de paiement ('.$this->resteApercevoir.' '.$this->devise.')',
                        timer:5000,
                        icon:'error',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    ); 
                } 
            } 
            else{

                $this->dispatch('alert',                    
                    title:'Désolé, veuillez sélectionner un client svp!',
                    timer:5000,
                    icon:'warning',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                ); 
            } 
        } 
        else{
            $this->dispatch('alert',                    
                title:'Désolé, veuillez créer une session svp!',
                timer:7000,
                icon:'error',
                toast:true,
                showConfirmButton: false,
                position:'top-end',
            );
        }      
    }    
    public function encompte(){        
        $this->validate([                   
            'mode_reglement'=>'required',
            'commentaire'=>'max:250', 
            // 'compte_bancaire'=>'required|numeric', //recupere id
            // 'montant_reglement'=>'required|numeric', 
        ]);  
        // ceci verifie si une session est ouverte ou pas
        $testSessions = SessionPos::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->where('etat','En cours')->count(); 
        if($testSessions > 0){ 

            if(!empty($this->nom_client)){  

                // Facture
                if($this->etats == "Commencée"){

                    // appel la fonction NewCommande
                    $this->NewCommande();
                }
                else{
                    $etat ='Impayée';
                    $dejaRegle = 0;                        
                    $reste = $this->montantTTC;
                    PosFactureClientEntete::find($this->idPosFClt)->update(['montant_recu'=>number_format($dejaRegle,0,',',''),'reste_a_percevoir'=>number_format($reste,0,',',''),
                                        'note'=>$this->commentaire,'etat'=>$etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                    PosFactureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->idPosFClt)->update(['etat'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                    
                    // appel la fonction NewCommande
                    $this->NewCommande();
                }                
        
                $id_activite = $this->id_pos;
                $page = 'SessionPos';
                $ref_POS = $this->ref_pos;
                LogActivity::addToLog('Paiement facture mis en compte ('.$ref_POS.') ajouté', $id_activite, $page);
                $this->dispatch('alert',                    
                    title:'Paiement facture mis en compte enregistré !',
                    timer:5000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                );      
                $this->montant_reglement = '';                        
                $this->dispatch('fermerPosPaie');
                $this->redirect('/pos?id='.$this->id_pos.'&ref='.$this->ref_pos, navigate: true); // ceci permet d'actualiser la page (important)                                    
            } 
            else{

                $this->dispatch('alert',                    
                    title:'Désolé, veuillez sélectionner un client svp!',
                    timer:5000,
                    icon:'warning',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                ); 
            } 
        } 
        else{
            $this->dispatch('alert',                    
                title:'Désolé, veuillez créer une session svp!',
                timer:7000,
                icon:'error',
                toast:true,
                showConfirmButton: false,
                position:'top-end',
            );
        }
    }
    // supprimer le paiement
    public function confirmationDelete($id){
        $this->confirmation = $id;        
    } 
    public function effacer(int $id, int $id_cpteBq){
        // $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        // if($test > 0){ 
        //     $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
        //     $autoriser = $role[0]->supprimer_categorie;
        //     if($autoriser == 1){   
                if($id){
                    
                    $MontantRegler = Reglement::where('societe',auth()->user()->societe)->where('id',$id)->sum('montant_regler');
                    $factClient = PosFactureClientEntete::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->first();
                    $idfclt = $factClient->id;
                    $montant_ttc = $factClient->montant_ttc;
                    $reste_a_percevoir = $factClient->reste_a_percevoir;
                    $montant_recu = $factClient->montant_recu;
                    $id_client = $factClient->id_client;

                    $montantRecu_ok = $montant_recu - $MontantRegler;

                    if($montantRecu_ok > 0 && $montantRecu_ok < $montant_ttc){
                        $etat = 'Commencée';
                    }
                    elseif($montantRecu_ok == $montant_ttc){
                        $etat = 'Payée';
                    }
                    else{
                        $etat = 'Brouillon';
                    }
                    $reste = $reste_a_percevoir + $MontantRegler;
                    PosFactureClientEntete::find($idfclt)->update(['montant_recu'=>number_format($montantRecu_ok,0,',',''),'reste_a_percevoir'=>number_format($reste,0,',',''),
                    'etat'=>$etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                    
                    PosFactureClientLigne::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->update(['etat'=>$etat]);

                    $Regler = Reglement::where('societe',auth()->user()->societe)->where('id',$id)->first();
                    $id_regle = $Regler->id_ecriture_bancaire;
                    
                    Reglement::where('id',$id)->delete();
                    EcritureBancaire::where('id',$id_regle)->delete();

                    // ceci calcul le solde                    
                    $soldeCredit = EcritureBancaire::where('societe',auth()->user()->societe)->where('id_compte_bancaire',$id_cpteBq)->sum('credit');
                    $soldeDebit = EcritureBancaire::where('societe',auth()->user()->societe)->where('id_compte_bancaire',$id_cpteBq)->sum('debit');  
                    $solde = $soldeCredit - $soldeDebit;
                    CompteBancaire::where('societe',auth()->user()->societe)->where('id',$id_cpteBq)->update(['solde'=>$solde,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                    // Point fidelite
                    if($this->activer_fidelite == 1){  
                        if($this->montantPointBD <= 0){
                            $this->montantPointBD = 1; // pour eviter division par zero
                            $nbrePts = number_format($MontantRegler / $this->montantPointBD,0,'','');
                        }
                        else{
                            $nbrePts = number_format($MontantRegler / $this->montantPointBD,0,'','');
                        }
                        
                        $Tiers = Tier ::where('societe',auth()->user()->societe)->where('id',$id_client)->get();                            
                        $pointsNbreBD = $Tiers[0]->nombre_point; 

                        $nbrePtsTotal = $pointsNbreBD - $nbrePts;
                        Tier::where('societe',auth()->user()->societe)->where('id',$id_client)->update(['nombre_point'=>$nbrePtsTotal]);
                    }

                    $sessions = SessionPos::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->where('etat','En cours')->first(); 
                    $this->id_pos = $sessions->id;
                    $this->ref_pos = $sessions->session_id;

                    $id_activite = $this->id_pos;
                    $page = 'SessionPos';
                    $ref_POS = $this->ref_pos;
                    LogActivity::addToLog('Ligne règlement ('.$ref_POS.') Supprimée', $id_activite, $page);
                    $this->dispatch('alert',                    
                        title:'Suppression effectuée!',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );      
                    // $this->redirect('/nouveau_fact_clt?id='.$idfclt.'&ref='.$this->reference, navigate: true);                         
                }  
            // }
            // else{  
            //     $this->dispatch('alert',                    
            //         title:'Vous n\'êtes pas autorisé à effectuer cette opération!',
            //         timer:3000,
            //         icon:'error',
            //         toast:false,
            //         showConfirmButton: true,
            //         position:'center',
            //     );  
            // } 
        // }
        // else{ 
        //     $this->dispatch('alert',                    
        //         title:'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!',
        //         timer:3000,
        //         icon:'error',
        //         toast:false,
        //         showConfirmButton: true,
        //         position:'center',
        //     );  
        // }   
    } 
    public function NewCommande(){
        // ceci verifie si une session est ouverte ou pas
        $testSessions = SessionPos::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->where('etat','En cours')->count(); 
        if($testSessions > 0){ 
                   
            $sessions = SessionPos::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->where('etat','En cours')->first(); 
            $id_session_pos = $sessions->id;
            $ref_session_pos = $sessions->session_id;
            PosFactureClientEntete::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->update(['id_session_pos'=>$id_session_pos,'ref_session_pos'=>$ref_session_pos]);
                        
            // copier la table PosFactureClientEntete dans factureClientEntete
            $PosenteteFactClient = PosFactureClientEntete::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->get(); 
            foreach($PosenteteFactClient as $PosenteteFactClients){
                // creation et copie Facture Client Entete
                factureClientEntete::create([
                    'code_facture'=>$PosenteteFactClients->code_facture,                
                    'nom_client'=>$PosenteteFactClients->nom_client,
                    'id_client'=>$PosenteteFactClients->id_client,
                    'date_facturation'=>$PosenteteFactClients->date_facturation,
                    'date_echeance'=>$PosenteteFactClients->date_echeance,
                    'mode_reglement'=>$PosenteteFactClients->mode_reglement,
                    'note'=>$PosenteteFactClients->note,
                    'montant_ht'=>$PosenteteFactClients->montant_ht,
                    'montant_remise'=>$PosenteteFactClients->montant_remise,
                    'montant_tva'=>$PosenteteFactClients->montant_tva,
                    'montant_precompte'=>$PosenteteFactClients->montant_precompte,
                    'montant_ttc'=>$PosenteteFactClients->montant_ttc,
                    'marge'=>$PosenteteFactClients->marge,
                    'montant_recu'=>$PosenteteFactClients->montant_recu,
                    'reste_a_percevoir'=>$PosenteteFactClients->reste_a_percevoir,
                    'etat'=>$PosenteteFactClients->etat,
                    'etat_expedi'=>'Clôturée',
                    'id_session_pos'=>$PosenteteFactClients->id_session_pos,
                    'ref_session_pos'=>$PosenteteFactClients->ref_session_pos,
                    'lieu_consommation'=>$PosenteteFactClients->lieu_consommation,
                    'date_consommation'=>$PosenteteFactClients->date_consommation,
                    'adresse_livraison'=>$PosenteteFactClients->adresse_livraison,
                    'societe'=>auth()->user()->societe,
                    'nom_user'=>auth()->user()->name,
                    'user_id'=>auth()->user()->id]);
            }
        
            $dernier_id = factureClientEntete::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id;

            $PosligneFactClient = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->get(); 
            foreach($PosligneFactClient as $PosligneFactClients){
                // creation et copie FactureClientLigne
                factureClientLigne::create([ 
                    'code_facture'=>$PosligneFactClients->code_facture,                
                    'id_facture_client_entete'=>$dernier_id,
                    'produit'=>$PosligneFactClients->produit,
                    'id_produit'=>$PosligneFactClients->id_produit,
                    'reference'=>$PosligneFactClients->reference,                    
                    'type_produit'=>$PosligneFactClients->type_produit,
                    'id_entrepot'=>$PosligneFactClients->id_entrepot,
                    'prix_achat'=>$PosligneFactClients->prix_achat,
                    'prix_vente'=>$PosligneFactClients->prix_vente,
                    'quantite'=>$PosligneFactClients->quantite,
                    'quantite_expediee'=>$PosligneFactClients->quantite_expediee,
                    'reste_a_expedier'=>$PosligneFactClients->reste_a_expedier,
                    'remise'=>$PosligneFactClients->remise,
                    'montant_remise'=>$PosligneFactClients->montant_remise,
                    'tva'=>$PosligneFactClients->tva,
                    'montant_tva'=>$PosligneFactClients->montant_tva,
                    'precompte'=>$PosligneFactClients->precompte,
                    'montant_precompte'=>$PosligneFactClients->montant_precompte,
                    'montant_ht'=>$PosligneFactClients->montant_ht,
                    'montant_ttc'=>$PosligneFactClients->montant_ttc,
                    'marge'=>$PosligneFactClients->marge,
                    'nom_client'=>$PosligneFactClients->nom_client,
                    'id_client'=>$PosligneFactClients->id_client,                
                    'offrir'=>$PosligneFactClients->offrir,
                    'infos'=>$PosligneFactClients->infos,
                    'etat'=>$PosligneFactClients->etat,
                    'user_id'=>auth()->user()->id,
                    'nom_user'=>auth()->user()->name,
                    'societe'=>auth()->user()->societe]);
            }

            $PosenteteFactClt = PosFactureClientEntete::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->first(); 
            $code_facture = $PosenteteFactClt->code_facture;

            Reglement::where('societe',auth()->user()->societe)->where('code_facture',$code_facture)->update(['id_facture_client_entete'=>$dernier_id]);
            $type_paiement = 'ReglementClient';  
            $statut = 'Confirmer';  
            EcritureBancaire::where('societe',auth()->user()->societe)->where('code_facture',$code_facture)->update(['id_facture_client_entete'=>$dernier_id,
            'type_paiement'=>$type_paiement,'statut'=>$statut]);            
            
            // Mise a jour solde_cloture_theorique dans Session Pos
            $montant_recus = Reglement::where('societe',auth()->user()->societe)->where('id_session_pos',$id_session_pos)->sum('montant_regler'); 
            $solde_initial = SessionPos::where('societe',auth()->user()->societe)->where('id',$id_session_pos)->sum('solde_initial'); 
            $solde_final = $montant_recus + $solde_initial;
            SessionPos::where('societe',auth()->user()->societe)->where('id',$id_session_pos)->update(['solde_cloture_theorique'=>$solde_final]);

            // *** Tres imoptant: Update table factureClientEntete avec montant_recu pour etre sur ***
            $montant_recuPosenteteFactClient = PosFactureClientEntete::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->sum('montant_recu');
            factureClientEntete::where('societe',auth()->user()->societe)->where('id',$dernier_id)->update(['montant_recu'=>$montant_recuPosenteteFactClient]);

             // Suppression
            PosFactureClientEntete::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->delete(); 
            PosFactureClientLigne::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->delete(); 

            $id_activite = $id_session_pos;
            $id_activite2 = $dernier_id;
            $page = 'SessionPos';
            $page2 = 'factureClient';
            $ref_POS = $ref_session_pos;
            LogActivity::addToLog('Commande ('.$ref_POS.') traitée', $id_activite, $page);
            LogActivity::addToLog('Paiement » '.$montant_recuPosenteteFactClient.' '.$this->devise.' facture ('.$code_facture.') ajouté', $id_activite2, $page2);
            $this->dispatch('alert',                    
                title:'Enregistré avec succès!',
                timer:3000,
                icon:'success',
                toast:true,
                showConfirmButton: false,
                position:'top-end',
            );
        }
        else{
            $this->dispatch('alert',                    
                title:'Désolé, veuillez créer une session svp!',
                timer:7000,
                icon:'error',
                toast:true,
                showConfirmButton: false,
                position:'top-end',
            );
        }  
    }
    public function CreationEmplacement (){       
        
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_emplacement;
            if($autoriser == 1){                
                $utiliser = 'Non';
                $length = 2;
                $token = bin2hex(random_bytes($length));
                $token_ok = 'Clt '.$token;
                Emplacement :: create(['nom_emplacement'=>$token_ok,'description'=>$token_ok,'utiliser'=>$utiliser,'societe'=>auth()->user()->societe,'user_id'=>auth()->user()->id,'nom_user'=>auth()->user()->name]);
                
                // ceci recupere le dernier enregistrement cree a l'instant
                $dernier_id = Emplacement::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id; 
                $id_activite = $dernier_id;                
                $page = 'Emplacement';
                LogActivity::addToLog('Emplacement ('.$token.') créé', $id_activite, $page);
                $this->resetinputFields();
                $this->dispatch('alert',                    
                    title:'Emplacement ('.$token_ok.') enregistré!',
                    timer:3000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                ); 
            }
            else{  
                $this->dispatch('alert',                    
                    title:'Vous n\'êtes pas autorisé à effectuer cette opération!',
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
    public function charge(){
        // laisser cette fonction vide: permet de mettre a jour le modal apres modif de donnees dans back office (tres important)
             
    }
    public function attente(int $idx){
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->gerer_cmd_attente;
            if($autoriser == 1){
                $test = PosFactureClientEntete ::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->count();
                if($test > 0){   
                    $ici = Emplacement::where('id',$idx)->first();
                    $lieu = $ici->nom_emplacement;            
                    $encours = $ici->utiliser;
                    
                    if($encours == 'Non'){
                       
                        $utiliser = 'Oui';                        
                        Emplacement::find($idx)->update(['utiliser'=>$utiliser]);
                                        
                        // copier la table PosFactureClientEntete dans CommandeAttenteEntete
                        $PosenteteFactClient = PosFactureClientEntete::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->get(); 
                        foreach($PosenteteFactClient as $PosenteteFactClients){
                            // creation et copie Facture Client Entete
                            CommandeAttenteEntete::create([
                                'emplacement_id'=>$idx,                
                                'code_facture'=>$PosenteteFactClients->code_facture,                
                                'nom_client'=>$PosenteteFactClients->nom_client,
                                'id_client'=>$PosenteteFactClients->id_client,
                                'date_facturation'=>$PosenteteFactClients->date_facturation,
                                'date_echeance'=>$PosenteteFactClients->date_echeance,
                                'mode_reglement'=>$PosenteteFactClients->mode_reglement,
                                'note'=>$PosenteteFactClients->note,
                                'montant_ht'=>$PosenteteFactClients->montant_ht,
                                'montant_remise'=>$PosenteteFactClients->montant_remise,
                                'montant_tva'=>$PosenteteFactClients->montant_tva,
                                'montant_precompte'=>$PosenteteFactClients->montant_precompte,
                                'montant_ttc'=>$PosenteteFactClients->montant_ttc,
                                'marge'=>$PosenteteFactClients->marge,
                                'montant_recu'=>$PosenteteFactClients->montant_recu,
                                'reste_a_percevoir'=>$PosenteteFactClients->reste_a_percevoir,
                                'etat'=>$PosenteteFactClients->etat,
                                'etat_expedi'=>'Clôturée',
                                'id_session_pos'=>$PosenteteFactClients->id_session_pos,
                                'ref_session_pos'=>$PosenteteFactClients->ref_session_pos,
                                'lieu_consommation'=>$PosenteteFactClients->lieu_consommation,
                                'date_consommation'=>$PosenteteFactClients->date_consommation,
                                'adresse_livraison'=>$PosenteteFactClients->adresse_livraison,
                                'societe'=>auth()->user()->societe,
                                'nom_user'=>auth()->user()->name,
                                'user_id'=>auth()->user()->id]);
                        }

                        $PosligneFactClient = PosFactureClientLigne::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->get(); 
                        foreach($PosligneFactClient as $PosligneFactClients){
                            // creation et copie CommandeAttenteLigne
                            CommandeAttenteLigne::create([ 
                                'emplacement_id'=>$idx,    
                                'code_facture'=>$PosligneFactClients->code_facture,                
                                'id_facture_client_entete'=>$PosligneFactClients->id_facture_client_entete,
                                'produit'=>$PosligneFactClients->produit,
                                'id_produit'=>$PosligneFactClients->id_produit,
                                'reference'=>$PosligneFactClients->reference,                                
                                'id_entrepot'=>$PosligneFactClients->id_entrepot,
                                'prix_achat'=>$PosligneFactClients->prix_achat,
                                'prix_vente'=>$PosligneFactClients->prix_vente,
                                'quantite'=>$PosligneFactClients->quantite,
                                'quantite_expediee'=>$PosligneFactClients->quantite_expediee,
                                'reste_a_expedier'=>$PosligneFactClients->reste_a_expedier,
                                'remise'=>$PosligneFactClients->remise,
                                'montant_remise'=>$PosligneFactClients->montant_remise,
                                'tva'=>$PosligneFactClients->tva,
                                'montant_tva'=>$PosligneFactClients->montant_tva,
                                'precompte'=>$PosligneFactClients->precompte,
                                'montant_precompte'=>$PosligneFactClients->montant_precompte,
                                'montant_ht'=>$PosligneFactClients->montant_ht,
                                'montant_ttc'=>$PosligneFactClients->montant_ttc,
                                'marge'=>$PosligneFactClients->marge,
                                'nom_client'=>$PosligneFactClients->nom_client,
                                'id_client'=>$PosligneFactClients->id_client,                
                                'offrir'=>$PosligneFactClients->offrir,
                                'infos'=>$PosligneFactClients->infos,
                                'etat'=>$PosligneFactClients->etat,
                                'user_id'=>auth()->user()->id,
                                'nom_user'=>auth()->user()->name,
                                'societe'=>auth()->user()->societe]);
                        }               

                        $utiliateur = CommandeAttenteEntete ::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->where('emplacement_id',$idx)->limit(1)->get();  
                        $nomUtilisateur = $utiliateur[0]->nom_user; 
                        $lieu_consommation = $utiliateur[0]->lieu_consommation; 
                        $date_consommation = $utiliateur[0]->date_consommation; 
                        $adresse_livraison = $utiliateur[0]->adresse_livraison; 

                        Emplacement::find($idx)->update(['nom_user'=>$nomUtilisateur,'non_caissiere'=>auth()->user()->name,
                                        'lieu_consommation'=>$lieu_consommation,'date_consommation'=>$date_consommation,'adresse_livraison'=>$adresse_livraison,]); 
                        
                        // Suppression 
                        PosFactureClientEntete::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->delete(); 
                        PosFactureClientLigne::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->delete(); 
                    
                        $sessions = SessionPos::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->where('etat','En cours')->first(); 
                        $id_pos = $sessions->id;
                        $ref_pos = $sessions->session_id; 

                        $id_activite = $id_pos;
                        $page = 'SessionPos';
                        $ref_POS = $ref_pos;
                        LogActivity::addToLog('Commande ('.$ref_POS.') mise en attente', $id_activite, $page);
                        $this->dispatch('alert',                    
                            title:'Commande mise en attente <br/> dans » '.$lieu,
                            timer:5000,
                            icon:'success',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        );  
                        $this->redirect('/pos?id='.$id_pos.'&ref='.$ref_pos, navigate: true); // ceci permet d'actualiser la page (important)
                    }
                    else{  
                        $this->dispatch('alert',                    
                            title:'Cet emplacement » '.$lieu.' est encours d\'utilisation!',
                            timer:5000,
                            icon:'warning',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        );                             
                    }
                }else{ 
                    $this->dispatch('alert',                    
                        title:'Oups, veuillez ajouter un produit dans le panier svp!',
                        timer:5000,
                        icon:'error',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    ); 
                }
            }
            else{  
                $this->dispatch('alert',                    
                    title:'Vous n\'êtes pas autorisé à effectuer cette opération!',
                    timer:3000,
                    icon:'error',
                    toast:true,
                    showConfirmButton: false,
                    position:'center',
                );  
            } 
        }
        else{ 
            $this->dispatch('alert',                    
                title:'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!',
                timer:3000,
                icon:'error',
                toast:false,
                showConfirmButton: true,
                position:'center',
            );  
        } 
    }
    public function reinitialiser(){ 
        Emplacement::where('societe',auth()->user()->societe)->where('utiliser','Non')->update(['non_caissiere'=>'','nom_user'=>'','statut'=>'','lieu_consommation'=>'','date_consommation'=>'','adresse_livraison'=>'']);        
        $id_activite = 0;               
        $page = 'Emplacement';
        LogActivity::addToLog('Champs mise en attente effacés', $id_activite, $page);
        $this->dispatch('alert',                    
            title:'Reinitialisation effectuée!',
            timer:5000,
            icon:'success',
            toast:true,
            showConfirmButton: false,
            position:'top-end',
        );         
    }
    // Reprendre une commande en attente
    public function reprendreCmd(int $idz){
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->gerer_cmd_attente;
            if($autoriser == 1){ 
                $test = PosFactureClientEntete ::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->count();
                if(!$test > 0){   

                    $ici = Emplacement::where('id',$idz)->first();
                    $lieu = $ici->nom_emplacement;            

                    $teste = CommandeAttenteEntete ::where('societe',auth()->user()->societe)->where('emplacement_id',$idz)->count();
                    if($teste > 0){                 

                        // Changer user_id du nouveau utilisateur: ceci permet que tous les users peuvent Reprendre ou cloturer la commande en attente de tout le monde
                        CommandeAttenteEntete::where('emplacement_id',$idz)->update(['user_id'=>auth()->user()->id]);
                        CommandeAttenteLigne::where('emplacement_id',$idz)->update(['user_id'=>auth()->user()->id]);

                        // copier la table CommandeAttenteEntete dans PosFactureClientEntete
                        $PosenteteFactClient = CommandeAttenteEntete ::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->where('emplacement_id', $idz)->get(); 
                        foreach($PosenteteFactClient as $PosenteteFactClients){
                            // creation et copie Facture Client Entete
                            PosFactureClientEntete ::create([                                   
                                'code_facture'=>$PosenteteFactClients->code_facture,                
                                'nom_client'=>$PosenteteFactClients->nom_client,
                                'id_client'=>$PosenteteFactClients->id_client,
                                'date_facturation'=>$PosenteteFactClients->date_facturation,
                                'date_echeance'=>$PosenteteFactClients->date_echeance,
                                'mode_reglement'=>$PosenteteFactClients->mode_reglement,
                                'note'=>$PosenteteFactClients->note,
                                'montant_ht'=>$PosenteteFactClients->montant_ht,
                                'montant_remise'=>$PosenteteFactClients->montant_remise,
                                'montant_tva'=>$PosenteteFactClients->montant_tva,
                                'montant_precompte'=>$PosenteteFactClients->montant_precompte,
                                'montant_ttc'=>$PosenteteFactClients->montant_ttc,
                                'marge'=>$PosenteteFactClients->marge,
                                'montant_recu'=>$PosenteteFactClients->montant_recu,
                                'reste_a_percevoir'=>$PosenteteFactClients->reste_a_percevoir,
                                'etat'=>$PosenteteFactClients->etat,
                                'etat_expedi'=>'Clôturée',
                                'id_session_pos'=>$PosenteteFactClients->id_session_pos,
                                'ref_session_pos'=>$PosenteteFactClients->ref_session_pos,
                                'lieu_consommation'=>$PosenteteFactClients->lieu_consommation,
                                'date_consommation'=>$PosenteteFactClients->date_consommation,
                                'adresse_livraison'=>$PosenteteFactClients->adresse_livraison,
                                'societe'=>auth()->user()->societe,
                                'nom_user'=>auth()->user()->name,
                                'user_id'=>auth()->user()->id]);
                        }
        
                        $dernier_id = PosFactureClientEntete::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id;

                        $PosligneFactClient = CommandeAttenteLigne::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->where('emplacement_id', $idz)->get(); 
                        foreach($PosligneFactClient as $PosligneFactClients){
                            // creation et copie PosFactureClientLigne
                            PosFactureClientLigne::create([                         
                                'code_facture'=>$PosligneFactClients->code_facture,     
                                'id_facture_client_entete'=>$dernier_id, 
                                'produit'=>$PosligneFactClients->produit,
                                'id_produit'=>$PosligneFactClients->id_produit,
                                'reference'=>$PosligneFactClients->reference,                                
                                'id_entrepot'=>$PosligneFactClients->id_entrepot,
                                'prix_achat'=>$PosligneFactClients->prix_achat,
                                'prix_vente'=>$PosligneFactClients->prix_vente,
                                'quantite'=>$PosligneFactClients->quantite,
                                'quantite_expediee'=>$PosligneFactClients->quantite_expediee,
                                'reste_a_expedier'=>$PosligneFactClients->reste_a_expedier,
                                'remise'=>$PosligneFactClients->remise,
                                'montant_remise'=>$PosligneFactClients->montant_remise,
                                'tva'=>$PosligneFactClients->tva,
                                'montant_tva'=>$PosligneFactClients->montant_tva,
                                'precompte'=>$PosligneFactClients->precompte,
                                'montant_precompte'=>$PosligneFactClients->montant_precompte,
                                'montant_ht'=>$PosligneFactClients->montant_ht,
                                'montant_ttc'=>$PosligneFactClients->montant_ttc,
                                'marge'=>$PosligneFactClients->marge,
                                'nom_client'=>$PosligneFactClients->nom_client,
                                'id_client'=>$PosligneFactClients->id_client,                
                                'offrir'=>$PosligneFactClients->offrir,
                                'infos'=>$PosligneFactClients->infos,
                                'etat'=>$PosligneFactClients->etat,
                                'user_id'=>auth()->user()->id,
                                'nom_user'=>auth()->user()->name,
                                'societe'=>auth()->user()->societe]);
                        }    
                        
                        $PosenteteFactClt = PosFactureClientEntete::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->first(); 
                        $code_facture = $PosenteteFactClt->code_facture;
                        Reglement::where('societe',auth()->user()->societe)->where('code_facture',$code_facture)->update(['id_facture_client_entete'=>$dernier_id]);

                        // Suppression table
                        CommandeAttenteEntete::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->where('emplacement_id', $idz)->delete(); 
                        CommandeAttenteLigne::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->where('emplacement_id', $idz)->delete(); 
                        
                        $utiliser = 'Non';
                        // $statut = 'En attente';  
                        $statut = '';  
                        Emplacement::find($idz)->update(['utiliser'=>$utiliser,'statut'=>$statut,'non_caissiere'=>'','nom_user'=>'','lieu_consommation'=>'','date_consommation'=>'','adresse_livraison'=>'']);
                        
                        $sessions = SessionPos::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->where('etat','En cours')->first(); 
                        $id_pos = $sessions->id;
                        $ref_pos = $sessions->session_id; 

                        $id_activite = $id_pos;
                        $page = 'SessionPos';
                        $ref_POS = $ref_pos;
                        LogActivity::addToLog('Reprise commande ('.$ref_POS.') mise en attente', $id_activite, $page);
                        $this->dispatch('alert',                    
                            title:'La commande est disponible!',
                            timer:5000,
                            icon:'success',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        ); 
                        $this->redirect('/pos?id='.$id_pos.'&ref='.$ref_pos, navigate: true); // ceci permet d'actualiser la page (important)
                    }
                    else{
                        $this->dispatch('alert',                    
                            title:'Désolé, il n\'y a pas de commande associée » '.$lieu.'!',
                            timer:5000,
                            icon:'warning',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        ); 
                    }            
                }else{ 
                    $this->dispatch('alert',                    
                        title:'Désolé, vous ne pouvez pas reprendre cette commande avec une autre en cours !',
                        timer:5000,
                        icon:'error',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    ); 
                }
            }
            else{  
                $this->dispatch('alert',                    
                    title:'Vous n\'êtes pas autorisé à effectuer cette opération!',
                    timer:3000,
                    icon:'error',
                    toast:true,
                    showConfirmButton: false,
                    position:'center',
                );  
            } 
        }
        else{ 
            $this->dispatch('alert',                    
                title:'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!',
                timer:3000,
                icon:'error',
                toast:false,
                showConfirmButton: true,
                position:'center',
            );  
        }
    }
    // public function envoyerCuisine(int $idt){
    //     dd($idt);
    // }
     // Gestion point de fidelite
    public function affichPoint(){        
        $entit = Entite::where('enseigne',auth()->user()->societe)->first();        
        $this->montant_point = $entit->montant_point;
        $this->objectif_point = $entit->objectif_point;
    }
    public function valideFidelite(){
        $this->validate([
            'montant_point'=>'required|numeric',
            'objectif_point'=>'required|numeric',                           
        ]);
        
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_fidelite;
            if($autoriser == 1){                     
                Entite::where('enseigne',auth()->user()->societe)->update(['montant_point'=>$this->montant_point,'objectif_point'=>$this->objectif_point]);
                Tier::where('societe',auth()->user()->societe)->update(['objectif_point'=>$this->objectif_point]);
                $id_activite = 0;
                $page = 'Tiers';
                LogActivity::addToLog('Ajout montant correspondant 1 pts fidelite', $id_activite, $page);
                $this->dispatch('alert',                    
                    title:'Point de fidélité <br>('.$this->montant_point.' '.$this->devise.' pour 1 point) <br> Enregistré !',
                    timer:5000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                );               
            }
            else{ 
                $this->dispatch('alert',                    
                    title:'Vous n\'êtes pas autorisé à effectuer cette opération !',
                    timer:5000,
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
                timer:5000,
                icon:'error',
                toast:true,
                showConfirmButton: false,
                position:'top-end',
            );
        }
    }
    public function creerTier(){
        $this->validate([
            'nom'=>'required',
            'raison_sociale'=>'max:250',                           
            'type_tiers'=>'required',                           
            'telephone'=>'required',                           
            // 'email'=>'required',                           
            // 'pays'=>'required',                           
            'ville'=>'required',                           
            // 'adresse'=>'required',                           
            // 'code_postal'=>'required',                           
            // 'site_web'=>'required',                           
            'sexe'=>'required',                           
            // 'commercial_charge'=>'required',                           
            'statut'=>'required|numeric',                               
        ]);
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){      
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_tier;
            if($autoriser == 1){                   
                $validation = 'Non Confirmé';
                $paiement = 'En attente';

                $test_point = Tier::where('societe',auth()->user()->societe)->count();
                if($test_point > 0){
                    $objectifPoint = Tier::where('societe',auth()->user()->societe)->get();
                    $objectif_point = $objectifPoint[0]->objectif_point;
                }
                else{
                    $objectif_point = 0;
                }
                $solde = 0;
                Tier::create(['nom'=>$this->nom,'raison_sociale'=>$this->raison_sociale,'solde'=>$solde,'type_tiers'=>$this->type_tiers,'etat'=>$this->statut,'telephone'=>$this->telephone,
                    'adresse'=>$this->adresse,'code_postal'=>$this->code_postal,'ville'=>$this->ville,'pays'=>$this->pays,'email'=>$this->email,'site_web'=>$this->site_web,
                    'validation'=>$validation,'paiement'=>$paiement,'commercial_charge'=>$this->commercial_charge,'sexe'=>$this->sexe,'objectif_point'=>$objectif_point,
                    'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);  

                    // ceci recupere le dernier enregistrement cree a l'instant
                    $dernier_id = Tier::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id; 

                    $sessions = SessionPos::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->where('etat','En cours')->first(); 
                    $id_pos = $sessions->id;
                    $ref_pos = $sessions->session_id; 

                    // Affecter automatique le nom client cree au PosFactureClientEntete et PosFactureClientLigne
                    PosFactureClientEntete::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->update(['nom_client'=>$this->nom,'id_client'=>$dernier_id]);
                    PosFactureClientLigne::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->update(['nom_client'=>$this->nom,'id_client'=>$dernier_id]);

                    $id_activite = $dernier_id;
                    $page = 'Tiers';
                    LogActivity::addToLog('Tier » '.$this->nom.' créé', $id_activite, $page);
                    
                    $this->dispatch('alert',                    
                        title:'Tiers ('.$this->nom.') enregistré!',
                        timer:5000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );                     
                    $this->redirect('/pos?id='.$id_pos.'&ref='.$ref_pos, navigate: true); // ceci permet d'actualiser la page (important)                   
            }
            else{                 
                $this->dispatch('alert',                    
                    title:'Vous n\'êtes pas autorisé à effectuer cette opération!',
                    timer:3000,
                    icon:'error',
                    toast:false,
                    showConfirmButton: true,
                    position:'center',
                );  
            } 
        }
        else{             
            $this->dispatch('alert',                    
                title:'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!',
                timer:3000,
                icon:'error',
                toast:false,
                showConfirmButton: true,
                position:'center',
            ); 
        }   
    }
    public function affiChoixConso(){
        $clients = PosfactureClientEntete::where('user_id',auth()->user()->id)->where('societe',auth()->user()->societe)->limit(1)->get();        
        $this->lieu_conso = $clients[0]->lieu_consommation;
        $this->date_conso = $clients[0]->date_consommation;
        $this->adresse_livraison = $clients[0]->adresse_livraison;
    }
    public function validerLieu(){
        $this->validate([
            'lieu_conso'=>'required',                                  
        ]);

        if($this->lieu_conso == 'Livraison'){
            $this->validate([
                'lieu_conso'=>'required',
                'date_conso'=>'required',                           
                'adresse_livraison'=>'required|max:255',                                  
            ]);
        }
        else{
           $this->validate([
                'lieu_conso'=>'required',
                'date_conso'=>'required',                           
                'adresse_livraison'=>'max:255',                                  
            ]); 
        }
        
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){      
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->pv;
            if($autoriser == 1){  
                if($this->lieu_conso == 'Livraison'){           
                    PosFactureClientEntete::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->update(['lieu_consommation'=>$this->lieu_conso,'date_consommation'=>date('Y-m-d H:i', strtotime($this->date_conso)),'adresse_livraison'=>$this->adresse_livraison]);
                }
                else{
                    $adresse_livraison = '';
                    PosFactureClientEntete::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->update(['lieu_consommation'=>$this->lieu_conso,'date_consommation'=>date('Y-m-d H:i', strtotime($this->date_conso)),'adresse_livraison'=>$adresse_livraison]);
                }
                
                $this->dispatch('alert',                    
                    title:'Lieu consommation enregistré!',
                    timer:3000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                );   
                $sessions = SessionPos::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->where('etat','En cours')->first(); 
                $id_pos = $sessions->id;
                $ref_pos = $sessions->session_id;                   
                $this->redirect('/pos?id='.$id_pos.'&ref='.$ref_pos, navigate: true);
            }
            else{                 
                $this->dispatch('alert',                    
                    title:'Vous n\'êtes pas autorisé à effectuer cette opération!',
                    timer:3000,
                    icon:'error',
                    toast:false,
                    showConfirmButton: true,
                    position:'center',
                );  
            } 
        }
        else{             
            $this->dispatch('alert',                    
                title:'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!',
                timer:3000,
                icon:'error',
                toast:false,
                showConfirmButton: true,
                position:'center',
            ); 
        }
    }    
}
