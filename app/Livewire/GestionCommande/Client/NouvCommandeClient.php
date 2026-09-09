<?php

namespace App\Livewire\GestionCommande\Client;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate; 
use App\Helpers\LogActivity;
use App\Models\LogActivity as LogActivityModel;
use Livewire\WithPagination;
use Livewire\WithFileUploads; 
use App\Models\Role;
use App\Models\Entrepot;
use App\Models\Parametre;
use App\Models\Produit;
use App\Models\Tier;
use App\Models\Utilisateur;
use App\Models\DeviseTva;
use App\Models\Entite;
use App\Models\Stock;
use App\Models\CompteBancaire;
use App\Models\CommandeClientEntete;
use App\Models\CommandeClientLigne;
use App\Models\Reglement;
use App\Models\EcritureBancaire;
use App\Models\ExpeditionClientEntete;
use App\Models\ExpeditionClientLigne;
use App\Models\factureClientEntete;
use App\Models\factureClientLigne;
use App\Models\ProformaClientEntete;
use App\Models\Mouvement;
use App\Models\ExpeditionClientLignePartiel;
use App\Models\FactExpeditionClientLigne;

class NouvCommandeClient extends Component
{
    protected $paginationTheme = 'bootstrap';
    use WithPagination;
    use WithFileUploads;

    public $id; 
    public $ids; // important pour Update
    public $idp; // important pour creer expedition
    public $id_prof;
    
    public $ouvre = 0;
    public $ouverture = 0;
    public $open = 0;

    #[Validate('required|max:255')]
    public $client;
    public $ids_client; 
    public $client_id; // pour l'ajout dans ligne facture
    
    public $identif; // id expedition ligne
    public $telephone;

    public $reference;
    public $referenceProd;
    
    #[Validate('required')]
    public $date_commande;

    #[Validate('required')]
    public $date_livraison;

    #[Validate('required')]
    public $mode_reglement;

    #[Validate('max:255')]
    public $condition_reglement;

    #[Validate('max:255')]
    public $note;

    public $autoriser; // pour gerer les marges   
    public $ref_fact; //reference facture

    // pour recherche client
    public $parNomTier; // Pour la recherche
    public $records;
    public $recordCount;
    public $showdiv = false;

    public $activer_fidelite;
    public $etat;
    public $etat_expedi; // etat expedition
    public $code_facture;
    public $code_proforma;

    // pour ligne facture
    public $choix_produit;
    public $prix_moyen_pondere_achat = 0; // prix achat
    public $prix_vente = 0;
    public $quantite = 0;
    public $tva = 0;
    public $remise = 0;
    public $precompte = 0;
    public $offrir = 'Non';
    public $nom_produit;
    public $id_produit;
    public $id_entrepot;
    public $type_produit;    

    // reglement ou paye
    public $date_;
    public $date_reglement;
    public $num_cheq_virement;
    public $emeteur;
    public $banque_cheque;
    public $commentaire;

    public $montant_recu;
    public $montant_reglement;
    
    public $quantite_bd;
    public $prix_vente_min;     
    
    public $devise;
    public $confirmer;
    public $confirmation; 
    public $approuver;
    
    public $query;
    public $parNature;
    public $parCat; 
    public $parPage = 10; 
    
    // Expedition
    public $type; 
    public $quantite_cmde; 
    public $reste_A_expedier = 0; 
    public $resteExpedier = 0;
    
    // Facturation 
    public $reste_A_facturer;
    public $resteFacturer;
    
    public $montant_remise; 
    public $montant_tva; 
    public $montant_precompte; 
    public $montant_ht; 
    public $montant_ttc; 
    public $marge; 
    public $nom_client; 
    public $id_client; 
    public $etat_facture;
    
    public $prix_achat;
    public $ParProduit;    
    public $verifi;
    public $nbre_facture;  
    
    public $auteur;
    public $created_at;
    public $updated_at;

    public $orderField = 'produit'; 
    public $orderDirection = 'ASC';    
    
    public function onDataAjout(){
        $this->reset('ouvre');
    } 
    public function onDataOuverture(){
        $this->reset('ouverture');
    } 
    public function onDataOpen(){
        $this->reset('open');
    }
    public function ajoutLigne(int $idd){
        $this->ouvre = $idd;
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
        // $this->choix_produit = '';      
        // $this->prix_vente = 0;      
        $this->quantite = 0;     
        $this->remise = 0;     
        $this->tva = 0;     
        $this->precompte = 0;     
        $this->offrir = 'Non';
    }
    public function mount(){
        
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $this->autoriser = $role[0]->voir_marge;
            $autoriser = $role[0]->consulter_commande;
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
    public function render(){
    
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('id',auth()->user()->societe_id)->get();
        $jourValid = $entite_mod[0]->validite_mod;
        $mod_cmd = $entite_mod[0]->mod_cmd;
        $soldeClient = $entite_mod[0]->solde; 
        if($dateJour <= $jourValid){
            if($mod_cmd == 1){
                $title = 'Commande client | WamsCo';
                $module = 'Gestion commande';
                $title_fils = 'Commande client';
                $lien = 'listing_cmd_clt';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');      
                $dateJour = date('Y-m-d');
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');

                $this->id = request('id'); // id entete facture
                $this->ref_fact = request('ref'); // reference facture
                //     // ceci au chargement de la page
                $test_facture = CommandeClientEntete::where('societe_id',auth()->user()->societe_id)->where('id',$this->id)->count();    
                if($test_facture > 0){
                    $compte = CommandeClientEntete::where('societe_id',auth()->user()->societe_id)->where('id',$this->id)->first();               
                    $this->ids = $compte->id;
                    $this->idp = $compte->id_facture_client_entete; // id_facture_client_entete important pour creer expedition 
                    $this->client_id = $compte->id_client;
                    $this->client = $compte->nom_client;
                    $this->telephone = $compte->telephone;
                    $this->reference = $compte->code_commande; // reference commande
                    $this->code_facture = $compte->code_facture; // code_facture                
                    $this->date_commande = $compte->date_commande;
                    $this->date_livraison = $compte->date_livraison;                    
                    $this->mode_reglement = $compte->mode_reglement; 
                    $this->condition_reglement = $compte->condition_reglement;
                    $this->note = $compte->note;
                    $this->etat = $compte->etat;
                    $this->montant_recu = $compte->montant_recu;
                    $this->etat_expedi = $compte->etat_expedi;
                    $this->id_prof = $compte->id_proforma_client_entete;
                    $this->code_proforma = $compte->code_proforma;
                    $this->nbre_facture = $compte->nbre_facture;
                    $this->auteur = $compte->nom_user;
                    $this->created_at = $compte->created_at;
                    $this->updated_at = $compte->updated_at;
                    
                }      
                
                $tier = Tier::where('societe_id',auth()->user()->societe_id)->where('id',$this->client_id)->get(); 

                $ListeEntrepot = Entrepot::where('societe_id',auth()->user()->societe_id)->get();
                $stockProd = Stock::where('societe_id',auth()->user()->societe_id)->get();

                $factClient_ligne = CommandeClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->orderBy($this->orderField, $this->orderDirection)->get();
                $factClientLigneCount = $factClient_ligne->count();                

                $montantHT = $factClient_ligne->sum('montant_ht');
                $montantTTC = $factClient_ligne->sum('montant_ttc');
                $montantRemise = $factClient_ligne->sum('montant_remise');
                $montantTva = $factClient_ligne->sum('montant_tva');
                $montantPrecompte = $factClient_ligne->sum('montant_precompte');
                $montantMarge = $factClient_ligne->sum('marge');
                $prixAchat = $factClient_ligne->sum('prix_achat');
                $prixVente = $factClient_ligne->sum('prix_vente');
                // $prixRevient = $prixVente - $prixAchat;
                $prixRevient = $montantHT - $montantMarge; // important
                
                if($factClientLigneCount > 0){
                    if($prixAchat > 0){
                        $tauxMarge = ($montantMarge/$prixAchat) * 100;
                    }               
                    else{
                        $tauxMarge = ($montantMarge/$montantMarge) * 100; // au cas ou prix achat = 0 
                    }
                }
                else{
                    $tauxMarge = 0;
                }

                if(!empty($this->ParProduit)){
                    $expeClient_ligne = ExpeditionClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->where('produit','like','%'.$this->ParProduit.'%')->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage);
                }
                else{
                    $expeClient_ligne = ExpeditionClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->orderBy($this->orderField, $this->orderDirection)->paginate($this->parPage);
                }
                $expeClientLigneCount = $expeClient_ligne->count();

                if($this->ids){
                    $expCltEntete = ExpeditionClientEntete::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->orderBy('id','desc')->get(); 
                }
                elseif($this->idp){
                    $expCltEntete = ExpeditionClientEntete::where('societe_id',auth()->user()->societe_id)->where('id_facture_client_entete',$this->idp)->orderBy('id','desc')->get(); 
                }
                else{
                    $expCltEntete = []; // On affiche un tableau vide
                }

                $factCltEntete = factureClientEntete::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->orderBy('id','desc')->get(); 
                $proforCltEntete = ProformaClientEntete::where('societe_id',auth()->user()->societe_id)->where('id',$this->id_prof)->orderBy('id','desc')->get();
                
                $page = 'CommandeClient'; // Pour evenement lie
                $log = LogActivityModel::where('user_societe',auth()->user()->societe)->where('id_activite', $this->ids)->where('page', $page)->limit(50)->orderBy('id','desc')->get();
                $logCount = $log->count();

                // Parametre
                $test_vide = Parametre ::where('societe_id',auth()->user()->societe_id)->count();
                if($test_vide > 0){                
                    $config = Parametre::where('societe_id',auth()->user()->societe_id)->limit(1)->get();
                    $id_entrepot = $config[0]->id_entrepot_fctclt;               
                }
                else{
                    $id_entrepot = 0;
                }             

                // pour afficher les produits en stock
                if($this->type_produit == 'Produit'){            
                    $produit_stock = DB::table('stocks')
                                    ->select('id','nom_produit','reference','id_produit','type_produit','nature_produit','categorie',DB::raw('sum(quantite) as quantites, sum(valorisation_achat_total) as valorisationAchatTotal ,sum(valeur_vente_total) as valeurVentetotal, max(limite_stock_alerte) as limite_stock_alerte ,max(updated_at) as updated_at')) // Supposons que vous voulez la dernière date
                                    ->where('societe_id',auth()->user()->societe_id)                            
                                    ->where('id_entrepot',$id_entrepot)
                                    ->where('type_produit','Produit')
                                    // ->where('type_produit',$this->type_produit)
                                    ->where('nom_produit','like','%'.$this->query.'%')
                                    ->where('nature_produit','like','%'.$this->parNature.'%')
                                    ->where('categorie','like','%'.$this->parCat.'%')
                                    ->orderBy('nom_produit', 'ASC')
                                    ->groupBy('id','nom_produit','reference','id_produit','type_produit','nature_produit','categorie') // Si 'reference' et 'created_at' sont uniques par produit, vous pouvez les enlever du groupBy
                                    ->paginate($this->parPage);
                    $produit_stockCount = $produit_stock->count();
                    $qteStockTotal = $produit_stock->sum('quantites');
                    $valAchatTotal = $produit_stock->sum('valorisationAchatTotal');
                    $valVenteTotal = $produit_stock->sum('valeurVentetotal'); 
                }
                elseif($this->type_produit == 'Service'){
                    // pour afficher les services
                    $service_produit = Produit::where('societe_id',auth()->user()->societe_id)
                    ->where('type_produit','Service')
                    ->where('nom_produit','like','%'.$this->query.'%')
                    ->where('nature_produit','like','%'.$this->parNature.'%')
                    ->where('categorie','like','%'.$this->parCat.'%')
                    ->orderBy('nom_produit', 'ASC')
                    ->paginate($this->parPage);
                    $service_produitCount = $service_produit->count();
                }else{
                    
                    $produit_stock = Stock::where('societe_id',auth()->user()->societe_id)->paginate($this->parPage);
                    $produit_stockCount = 0;
                    $qteStockTotal = 0;
                    $valAchatTotal = 0;
                    $valVenteTotal = 0;
                    $service_produit = Produit::where('societe_id',auth()->user()->societe_id)->paginate($this->parPage);
                    $service_produitCount = 0;
                }
                
                $taxe = DeviseTva::where('societe_id',auth()->user()->societe_id)->orderBy('taux_tva','asc')->get();

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

                if($this->type_produit == 'Produit'){  
                    return view('livewire.gestion-commande.client.nouv-commande-client',compact('title_fils','module','lien','dateJour','stockProd','tier','ListeEntrepot','factClient_ligne','factClientLigneCount','expeClient_ligne','expeClientLigneCount','produit_stock','produit_stockCount','qteStockTotal','valAchatTotal','valVenteTotal','taxe','montantHT','montantTTC','montantRemise','montantTva','montantPrecompte','montantMarge','tauxMarge',
                            'prixVente','prixRevient','expCltEntete','factCltEntete','proforCltEntete','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
                }
                if($this->type_produit == 'Service'){  
                    return view('livewire.gestion-commande.client.nouv-commande-client',compact('title_fils','module','lien','dateJour','stockProd','tier','ListeEntrepot','factClient_ligne','factClientLigneCount','expeClient_ligne','expeClientLigneCount','service_produit','service_produitCount','taxe','montantHT','montantTTC','montantRemise','montantTva','montantPrecompte','montantMarge','tauxMarge',
                            'prixVente','prixRevient','expCltEntete','factCltEntete','proforCltEntete','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
                }
                else{ 
                    return view('livewire.gestion-commande.client.nouv-commande-client',compact('title_fils','module','lien','dateJour','stockProd','tier','ListeEntrepot','factClient_ligne','factClientLigneCount','expeClient_ligne','expeClientLigneCount','produit_stock','produit_stockCount','qteStockTotal','valAchatTotal','valVenteTotal','service_produit','service_produitCount','taxe','montantHT','montantTTC','montantRemise','montantTva','montantPrecompte','montantMarge','tauxMarge',
                            'prixVente','prixRevient','expCltEntete','factCltEntete','proforCltEntete','log','logCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
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
    public function searchResult(){ 
        if(!empty($this->client)){
            if(ctype_alpha($this->client)){ // ctype_alpha: cette fonction permet de savoir si le caractere ou mot est une lettre  
                $this->records = Tier::where('etat',1)->where('societe_id',auth()->user()->societe_id)->where('nom','like','%'.$this->client.'%')->orderBy('nom','asc')->limit(8)->get(); 
                $this->recordCount = Tier::where('etat',1)->where('societe_id',auth()->user()->societe_id)->where('nom','like','%'.$this->client.'%')->count();
                $this->showdiv = true;
            }
            else{
                $this->records = Tier::where('etat',1)->where('societe_id',auth()->user()->societe_id)->where('telephone','like','%'.$this->client.'%')->orderBy('nom','asc')->limit(8)->get(); 
                $this->recordCount = Tier::where('etat',1)->where('societe_id',auth()->user()->societe_id)->where('telephone','like','%'.$this->client.'%')->count(); 
                $this->showdiv = true;
            }        
        }
        else{
            $this->showdiv = false;
        }
    }
    public function ajouterTier($id = 0){
        $record = Tier::where('id', $id)->first();
        $this->client = $record->nom;
        $this->ids_client = $record->id;
        $this->telephone = $record->telephone;
        $this->showdiv = false;
    }
    public function update(){
        $this->validate();        
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_commande;
            if($autoriser == 1){  
                    
                    $test_tiers = Tier ::where('societe_id',auth()->user()->societe_id)->where('id',$this->client_id)->count();
                    if($test_tiers == 0){
                        if(!empty($this->ids_client)){
                            // recupere le nom du compte bancaire via son id : $this->compte_bancaire
                                                       
                            CommandeClientEntete::find($this->ids)->update(['nom_client'=>$this->client,'id_client'=>$this->ids_client,'telephone'=>$this->telephone,'reference'=>$this->reference,'date_commande'=>$this->date_commande,'date_livraison'=>$this->date_livraison,
                            'mode_reglement'=>$this->mode_reglement,'condition_reglement'=>$this->condition_reglement,'note'=>$this->note,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,
                            'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                            
                            $id_activite = $this->ids;
                            $page = 'CommandeClient';
                            LogActivity::addToLog('Entête commande » '.$this->client.' modifiée', $id_activite, $page);   
                            $this->dispatch('alert',                    
                                title:$this->client.' modifié(e)!',
                                timer:3000,
                                icon:'success',
                                toast:true,
                                showConfirmButton: false,
                                position:'top-end',
                            );
                            flash ('Entête commande » <strong>'.$this->client.'</strong> modifiée')->success(); 
                            $this->redirect('/nouveau_cmd_clt?id='.$this->ids.'&ref='.$this->reference.'&active=6&champ=2-1&choix=1', navigate: true);  
                        }
                        else{
                            $this->dispatch('alert',                    
                            title:'Désolé, ce nom n\'existe pas! <br> Sélectionnez ou créez un autre',
                                timer:5000,
                                icon:'warning',
                                toast:true,
                                showConfirmButton: false,
                                position:'top-end',
                            ); 
                        }
                    }
                    elseif($test_tiers >= 0){ 
                        if(empty($this->ids_client)){
                            
                            $test_tier_nom = Tier ::where('societe_id',auth()->user()->societe_id)->where('id',$this->client_id)->first();
                            $nom = $test_tier_nom->nom;

                            if($nom == $this->client){ 

                                // $compteBaq = CompteBancaire::where('societe_id',auth()->user()->societe_id)->where('id',$this->compte_bancaire)->first(); 
                                // $nom_compte_bancaire = $compteBaq->nom_compte_bancaire;
                                
                                // client_id de la CommandeClientEntete
                                CommandeClientEntete::find($this->ids)->update(['nom_client'=>$this->client,'id_client'=>$this->client_id,'telephone'=>$this->telephone,'reference'=>$this->reference,'date_commande'=>$this->date_commande,'date_livraison'=>$this->date_livraison,
                                'mode_reglement'=>$this->mode_reglement,'condition_reglement'=>$this->condition_reglement,'note'=>$this->note,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,
                                'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                                
                                $id_activite = $this->ids;
                                $page = 'CommandeClient';
                                LogActivity::addToLog('Entête commande » '.$this->client.' modifiée', $id_activite, $page); 
                                $this->dispatch('alert',                    
                                    title:$this->client.' modifié(e)!',
                                    timer:3000,
                                    icon:'success',
                                    toast:true,
                                    showConfirmButton: false,
                                    position:'top-end',
                                ); 
                                flash ('Entête commande » <strong>'.$this->client.'</strong> modifiée')->success(); 
                                $this->redirect('/nouveau_cmd_clt?id='.$this->ids.'&ref='.$this->reference.'&active=6&champ=2-1&choix=1', navigate: true);
                            } 
                            else{
                                $this->dispatch('alert',                    
                                    title:'Désolé, ce nom n\'existe pas! <br> Sélectionnez ou créez un autre',
                                    timer:5000,
                                    icon:'error',
                                    toast:true,
                                    showConfirmButton: false,
                                    position:'center',
                                );  
                            } 
                        }
                        else{
                            
                            $test_tier_nom = Tier ::where('societe_id',auth()->user()->societe_id)->where('id',$this->ids_client)->first();
                            $nom = $test_tier_nom->nom;   

                            if($this->ids_client != $this->client_id){ 
                                if($nom == $this->client){ 

                                    // $compteBaq = CompteBancaire::where('societe_id',auth()->user()->societe_id)->where('id',$this->compte_bancaire)->first(); 
                                    // $nom_compte_bancaire = $compteBaq->nom_compte_bancaire;
                                    
                                    // ids_client de ajouterTier
                                    CommandeClientEntete::find($this->ids)->update(['nom_client'=>$this->client,'id_client'=>$this->ids_client,'telephone'=>$this->telephone,'reference'=>$this->reference,'date_commande'=>$this->date_commande,'date_livraison'=>$this->date_livraison,
                                    'mode_reglement'=>$this->mode_reglement,'condition_reglement'=>$this->condition_reglement,'note'=>$this->note,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,
                                    'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                                    
                                    $id_activite = $this->ids;
                                    $page = 'CommandeClient';
                                    LogActivity::addToLog('Entête commande » '.$this->client.' modifiée', $id_activite, $page); 
                                    $this->dispatch('alert',                    
                                        title:$this->client.' modifié(e)!',
                                        timer:3000,
                                        icon:'success',
                                        toast:true,
                                        showConfirmButton: false,
                                        position:'top-end',
                                    ); 
                                    flash ('Entête commande » <strong>'.$this->client.'</strong> modifiée')->success(); 
                                    $this->redirect('/nouveau_cmd_clt?id='.$this->ids.'&ref='.$this->reference.'&active=6&champ=2-1&choix=1', navigate: true);
                                } 
                                else{
                                    $this->dispatch('alert',                    
                                        title:'Désolé, ce nom n\'existe pas! <br> Sélectionnez ou créez un autre',
                                        timer:5000,
                                        icon:'error',
                                        toast:true,
                                        showConfirmButton: false,
                                        position:'center',
                                    );  
                                } 
                            } 
                            else{
                        
                                $this->dispatch('alert',                    
                                    title:'Désolé, ce nom est déja selectionné!',
                                    timer:5000,
                                    icon:'warning',
                                    toast:true,
                                    showConfirmButton: false,
                                    position:'center',
                                );  
                            } 
                        }
                    }
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
    public function charge(string $typeProd){
        // laisser cette fonction vide: permet de mettre a jour le modal apres modif de donnees dans back office (tres important) 
        $this->type_produit = $typeProd;
        $this->parCat = '';
        $this->parNature = '';
        $this->query = '';
        $compte = CommandeClientEntete::where('societe_id',auth()->user()->societe_id)->where('id',$this->ids)->first(); 
        $this->client_id = $compte->id_client; 
    }
    public function afficheLigne(int $idf){
        $this->ouverture = $idf;
        // $this->choix_produit = $idf;         
        $testChoix = Stock::where('societe_id',auth()->user()->societe_id)->where('id',$idf)->count();
        if($testChoix > 0){
            // ceci permet d'afficher la quantite entrepot origine
            $choixProd = Stock::where('societe_id',auth()->user()->societe_id)->where('id',$idf)->get();
            $this->prix_moyen_pondere_achat = $choixProd[0]->prix_moyen_pondere_achat;
            $this->prix_vente = $choixProd[0]->prix_vente_unitaire;
            $this->quantite_bd = $choixProd[0]->quantite;
            $this->id_produit = $choixProd[0]->id_produit;
            $this->nom_produit = $choixProd[0]->nom_produit;
            $this->referenceProd = $choixProd[0]->reference;
            $this->id_entrepot = $choixProd[0]->id_entrepot;
            // avoir le prix_vente_min 
            $prod = Produit::where('societe_id',auth()->user()->societe_id)->where('id',$this->id_produit)->first();
            $this->prix_vente_min = $prod->prix_vente_min;
        }
    }
    public function afficheLigneService(int $ide){
        $this->ouverture = $ide;
        $this->choix_produit = $ide;         
        $testChoix = Produit::where('societe_id',auth()->user()->societe_id)->where('id',$ide)->count();
        if($testChoix > 0){
            // ceci permet d'afficher la quantite entrepot origine
            $choixProd = Produit::where('societe_id',auth()->user()->societe_id)->where('id',$ide)->get();
            $this->id_produit = $choixProd[0]->id;
            $this->prix_moyen_pondere_achat = $choixProd[0]->prix_achat;
            $this->prix_vente = $choixProd[0]->prix_vente;
            $this->prix_vente_min = $choixProd[0]->prix_vente_min;
            $this->nom_produit = $choixProd[0]->nom_produit;
            $this->referenceProd = $choixProd[0]->reference;
            // $this->id_entrepot = $choixProd[0]->id_entrepot;
        }
    } 
    public function ajouter(){
        $this->validate([
            // 'choix_produit'=>'required',
            'quantite'=>'required|numeric',
            'prix_vente'=>'required|numeric',
            'remise'=>'required|numeric',
            'tva'=>'required|numeric',
            'precompte'=>'required|numeric',
            'offrir'=>'required|max:3',
            'client'=>'required',
            'date_commande'=>'required', // important pour forcer utilisateur a remplir
            'date_livraison'=>'required',    // important pour forcer utilisateur a remplir
            'mode_reglement'=>'required',   // important pour forcer utilisateur a remplir
            'condition_reglement'=>'max:255',  // important pour forcer utilisateur a remplir
            
        ]);    
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_commande;
            if($autoriser == 1){                         
                
                if($this->quantite > 0){
                    
                    if($this->quantite_bd <= 0){  
                        $this->dispatch('alert',                    
                            title:'Désolé, le stock est vide !!!',
                            timer:5000,
                            icon:'error',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        );  
                    }
                    elseif($this->quantite > $this->quantite_bd){   
                        $this->dispatch('alert',                    
                            title:'Désolé, la quantité demandée ('.$this->quantite.') est supérieure au stock disponible ('.$this->quantite_bd.') !',
                            timer:5000,
                            icon:'error',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        );      
                    }        
                    elseif($this->prix_vente < $this->prix_vente_min){ 
                        $this->dispatch('alert',                    
                            title:'Désolé, prix vente ('.$this->prix_vente.') demandé est inférieure au prix de vente min!',
                            timer:5000,
                            icon:'error',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        ); 
                    }
                    else{
                        
                            // ************ Calul ***********
                            $montant_vente =  $this->prix_vente * $this->quantite;                        
                            $montant_achat_avec_qte = ($this->prix_moyen_pondere_achat * $this->quantite);  

                            $remiseDetail = $this->remise/100; // valeur remise
                            $remise_montant = ($this->prix_vente * $this->quantite) * $remiseDetail; // montant remise

                            $montant_remiser_ht = ($this->prix_vente * $this->quantite) - $remise_montant; // Montant vente avec remise inclus hors taxe                        
                            $marge = $montant_remiser_ht - $montant_achat_avec_qte; // Marge avec remise                       

                            $tvaDetail = $this->tva/100; // valeur de la tva
                            $tva_montant = $montant_remiser_ht * $tvaDetail;

                            $precompteDetail = $this->precompte/100; // valeur du precompte
                            $precompte_montant = $montant_remiser_ht * $precompteDetail;

                            $montant_ttc = $montant_remiser_ht + $tva_montant + $precompte_montant; // Montant TTC
                            //************** Fin Calcul ***********//

                            $quantite_expediee = 0;
                            $typeProd = 'Produit';
                            if($this->offrir == 'Non'){ 
                                CommandeClientLigne::create(['code_commande'=>$this->reference,'id_commande_client_entete'=>$this->ids,'produit'=>$this->nom_produit,'id_produit'=>$this->id_produit,'reference'=>$this->referenceProd,'type_produit'=>$typeProd,'prix_achat'=>$this->prix_moyen_pondere_achat,
                                                'prix_vente'=>$this->prix_vente,'quantite'=>$this->quantite,'quantite_expediee'=>$quantite_expediee,'reste_a_expedier'=>$this->quantite,'remise'=>$this->remise,'montant_remise'=>$remise_montant,
                                                'tva'=>$this->tva,'montant_tva'=>$tva_montant,'precompte'=>$this->precompte,'montant_precompte'=>$precompte_montant,'montant_ht'=>$montant_remiser_ht,'montant_ttc'=>$montant_ttc,'marge'=>$marge,'id_entrepot'=>$this->id_entrepot,
                                                'nom_client'=>$this->client,'id_client'=>$this->client_id,'offrir'=>$this->offrir,'etat'=>$this->etat,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                     
                            
                                $montantHT = CommandeClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->sum('montant_ht');
                                $montantTTC = CommandeClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->sum('montant_ttc');
                                $montantRemise = CommandeClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->sum('montant_remise');
                                $montantTva = CommandeClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->sum('montant_tva');
                                $montantPrecompte = CommandeClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->sum('montant_precompte');
                                $marge = CommandeClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->sum('marge');

                                // Montant TTC en arrondi en + ou en - 
                                CommandeClientEntete::find($this->ids)->update(['montant_ht'=>$montantHT,'montant_remise'=>$montantRemise,'montant_tva'=>$montantTva,'montant_precompte'=>$montantPrecompte,
                                                    'montant_ttc'=>number_format($montantTTC,0,',',''),'marge'=>$marge,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                            }
                            elseif($this->offrir == 'Oui'){

                                // les totaux seront a zero                                       
                                $remise_montant = 0;
                                $tva_montant = 0;
                                $precompte_montant = 0;
                                $montant_remiser_ht = 0;
                                $montant_ttc = 0;
                                $marge = 0;
                                CommandeClientLigne::create(['code_commande'=>$this->reference,'id_commande_client_entete'=>$this->ids,'produit'=>$this->nom_produit,'id_produit'=>$this->id_produit,'reference'=>$this->referenceProd,'type_produit'=>$typeProd,'prix_achat'=>$this->prix_moyen_pondere_achat,
                                                'prix_vente'=>$this->prix_vente,'quantite'=>$this->quantite,'quantite_expediee'=>$quantite_expediee,'reste_a_expedier'=>$this->quantite,'remise'=>$this->remise,'montant_remise'=>$remise_montant,
                                                'tva'=>$this->tva,'montant_tva'=>$tva_montant,'precompte'=>$this->precompte,'montant_precompte'=>$precompte_montant,'montant_ht'=>$montant_remiser_ht,'montant_ttc'=>$montant_ttc,'marge'=>$marge,'id_entrepot'=>$this->id_entrepot,
                                                'nom_client'=>$this->client,'id_client'=>$this->client_id,'offrir'=>$this->offrir,'etat'=>$this->etat,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                     
                            
                                $montantHT = CommandeClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->sum('montant_ht');
                                $montantTTC = CommandeClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->sum('montant_ttc');
                                $montantRemise = CommandeClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->sum('montant_remise');
                                $montantTva = CommandeClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->sum('montant_tva');
                                $montantPrecompte = CommandeClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->sum('montant_precompte');
                                $marge = CommandeClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->sum('marge');

                                // Montant TTC en arrondi en + ou en - 
                                CommandeClientEntete::find($this->ids)->update(['montant_ht'=>$montantHT,'montant_remise'=>$montantRemise,'montant_tva'=>$montantTva,'montant_precompte'=>$montantPrecompte,'montant_ttc'=>number_format($montantTTC,0,',',''),
                                                    'marge'=>$marge,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                            }
                            $id_activite = $this->ids;
                            $page = 'CommandeClient';
                            LogActivity::addToLog('Produit ('.$this->nom_produit.') à commander ajouté', $id_activite, $page);  
                            $this->dispatch('alert', 
                                title:'Produit » '.$this->quantite.' <strong>'.$this->nom_produit.'</strong> ajouté(s)!', 
                                timer:3000,
                                icon:'success',
                                toast:true,
                                showConfirmButton: false,
                                position:'top-end',
                            );  
                            $this->resetinputFields();
                            // $this->redirect('/nouveau_cmd_clt?id='.$this->ids.'&ref='.$this->reference, navigate: true);
                    }
                }
                else{
                    $this->dispatch('alert',                    
                        title:'Désolé, ajoutez une quantité supérieure à 0',
                        timer:10000,
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
    public function ajouterService(){
        $this->validate([
            // 'choix_produit'=>'required',
            'quantite'=>'required|numeric',
            'prix_vente'=>'required|numeric',
            'remise'=>'required|numeric',
            'tva'=>'required|numeric',
            'precompte'=>'required|numeric',
            'offrir'=>'required|max:3',
            'client'=>'required',
            'date_commande'=>'required', // important pour forcer utilisateur a remplir
            'date_livraison'=>'required',    // important pour forcer utilisateur a remplir
            'mode_reglement'=>'required',   // important pour forcer utilisateur a remplir
            'condition_reglement'=>'max:255',  // important pour forcer utilisateur a remplir
            
        ]);    
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_commande;
            if($autoriser == 1){  
                        
                if($this->quantite > 0){ 
                        
                    if($this->prix_vente < $this->prix_vente_min){ 
                        $this->dispatch('alert',                    
                            title:'Désolé, prix vente ('.$this->prix_vente.') demandé est inférieure au prix de vente min!',
                            timer:5000,
                            icon:'error',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        ); 
                    }
                    else{
                        
                            // ************ Calul ***********
                            $montant_vente =  $this->prix_vente * $this->quantite;                        
                            $montant_achat_avec_qte = ($this->prix_moyen_pondere_achat * $this->quantite);  

                            $remiseDetail = $this->remise/100; // valeur remise
                            $remise_montant = ($this->prix_vente * $this->quantite) * $remiseDetail; // montant remise

                            $montant_remiser_ht = ($this->prix_vente * $this->quantite) - $remise_montant; // Montant vente avec remise inclus hors taxe                        
                            $marge = $montant_remiser_ht - $montant_achat_avec_qte; // Marge avec remise                       

                            $tvaDetail = $this->tva/100; // valeur de la tva
                            $tva_montant = $montant_remiser_ht * $tvaDetail;

                            $precompteDetail = $this->precompte/100; // valeur du precompte
                            $precompte_montant = $montant_remiser_ht * $precompteDetail;

                            $montant_ttc = $montant_remiser_ht + $tva_montant + $precompte_montant; // Montant TTC
                            //************** Fin Calcul ***********//

                            $quantite_expediee = 0;
                            $typeProd = 'Service';
                            if($this->offrir == 'Non'){ 
                                CommandeClientLigne::create(['code_commande'=>$this->reference,'id_commande_client_entete'=>$this->ids,'produit'=>$this->nom_produit,'id_produit'=>$this->id_produit,'reference'=>$this->referenceProd,'type_produit'=>$typeProd,'prix_achat'=>$this->prix_moyen_pondere_achat,
                                                'prix_vente'=>$this->prix_vente,'quantite'=>$this->quantite,'quantite_expediee'=>$quantite_expediee,'reste_a_expedier'=>$this->quantite,'remise'=>$this->remise,'montant_remise'=>$remise_montant,
                                                'tva'=>$this->tva,'montant_tva'=>$tva_montant,'precompte'=>$this->precompte,'montant_precompte'=>$precompte_montant,'montant_ht'=>$montant_remiser_ht,'montant_ttc'=>$montant_ttc,'marge'=>$marge,'id_entrepot'=>$this->id_entrepot,
                                                'nom_client'=>$this->client,'id_client'=>$this->client_id,'offrir'=>$this->offrir,'etat'=>$this->etat,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                     
                            
                                $montantHT = CommandeClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->sum('montant_ht');
                                $montantTTC = CommandeClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->sum('montant_ttc');
                                $montantRemise = CommandeClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->sum('montant_remise');
                                $montantTva = CommandeClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->sum('montant_tva');
                                $montantPrecompte = CommandeClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->sum('montant_precompte');
                                $marge = CommandeClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->sum('marge');

                                // Montant TTC en arrondi en + ou en - 
                                CommandeClientEntete::find($this->ids)->update(['montant_ht'=>$montantHT,'montant_remise'=>$montantRemise,'montant_tva'=>$montantTva,'montant_precompte'=>$montantPrecompte,'montant_ttc'=>number_format($montantTTC,0,',',''),
                                                    'marge'=>$marge,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                            }
                            elseif($this->offrir == 'Oui'){

                                // les totaux seront a zero                                       
                                $remise_montant = 0;
                                $tva_montant = 0;
                                $precompte_montant = 0;
                                $montant_remiser_ht = 0;
                                $montant_ttc = 0;
                                $marge = 0;
                                CommandeClientLigne::create(['code_commande'=>$this->reference,'id_commande_client_entete'=>$this->ids,'produit'=>$this->nom_produit,'id_produit'=>$this->id_produit,'reference'=>$this->referenceProd,'type_produit'=>$typeProd,'prix_achat'=>$this->prix_moyen_pondere_achat,
                                                'prix_vente'=>$this->prix_vente,'quantite'=>$this->quantite,'quantite_expediee'=>$quantite_expediee,'reste_a_expedier'=>$this->quantite,'remise'=>$this->remise,'montant_remise'=>$remise_montant,
                                                'tva'=>$this->tva,'montant_tva'=>$tva_montant,'precompte'=>$this->precompte,'montant_precompte'=>$precompte_montant,'montant_ht'=>$montant_remiser_ht,'montant_ttc'=>$montant_ttc,'marge'=>$marge,'id_entrepot'=>$this->id_entrepot,
                                                'nom_client'=>$this->client,'id_client'=>$this->client_id,'offrir'=>$this->offrir,'etat'=>$this->etat,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                     
                            
                                $montantHT = CommandeClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->sum('montant_ht');
                                $montantTTC = CommandeClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->sum('montant_ttc');
                                $montantRemise = CommandeClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->sum('montant_remise');
                                $montantTva = CommandeClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->sum('montant_tva');
                                $montantPrecompte = CommandeClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->sum('montant_precompte');
                                $marge = CommandeClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->sum('marge');

                                // Montant TTC en arrondi en + ou en - 
                                CommandeClientEntete::find($this->ids)->update(['montant_ht'=>$montantHT,'montant_remise'=>$montantRemise,'montant_tva'=>$montantTva,'montant_precompte'=>$montantPrecompte,'montant_ttc'=>number_format($montantTTC,0,',',''),
                                                    'marge'=>$marge,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                            }
                            $id_activite = $this->ids;
                            $page = 'CommandeClient';
                            LogActivity::addToLog('Service ('.$this->nom_produit.') à commander ajouté', $id_activite, $page);  
                            $this->dispatch('alert',  
                                title:'Service » '.$this->quantite.' <strong>'.$this->nom_produit.'</strong> ajouté(s)!',
                                timer:3000,
                                icon:'success',
                                toast:true,
                                showConfirmButton: false,
                                position:'top-end',
                            );  
                            $this->resetinputFields();
                            // $this->redirect('/nouveau_cmd_clt?id='.$this->ids.'&ref='.$this->reference, navigate: true);
                    }
                }
                else{
                    $this->dispatch('alert',                    
                        title:'Désolé, ajoutez une quantité supérieure à 0',
                        timer:10000,
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
    public function fermerModal(){
        $this->redirect('/nouveau_cmd_clt?id='.$this->ids.'&ref='.$this->reference.'&active=6&champ=1-1&choix=2', navigate: true);
    }
    // Ceci supprime la ligne de produit
    public function confirmerDelete($id){
        $this->confirmer = $id;        
    } 
    public function supprimer($id){
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_commande;
            if($autoriser == 1){   
                if($id){
                    
                    CommandeClientLigne::where('id',$id)->delete();

                    $montantHT = CommandeClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->sum('montant_ht');
                    $montantTTC = CommandeClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->sum('montant_ttc');
                    $montantRemise = CommandeClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->sum('montant_remise');
                    $montantTva = CommandeClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->sum('montant_tva');
                    $montantPrecompte = CommandeClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->sum('montant_precompte');
                    $marge = CommandeClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->sum('marge');
                                        
                    CommandeClientEntete::find($this->ids)->update(['montant_ht'=>number_format($montantHT,0,',',''),'montant_remise'=>$montantRemise,'montant_tva'=>$montantTva,
                                        'montant_ttc'=>number_format($montantTTC,0,',',''),'montant_precompte'=>$montantPrecompte,'marge'=>$marge,'societe'=>auth()->user()->societe,
                                        'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                    
                    
                    $id_activite = $this->ids;
                    $page = 'CommandeClient';
                    LogActivity::addToLog('Ligne de commande client supprimé', $id_activite, $page);
                    $this->dispatch('alert',                    
                        title:'Suppression effectuée!',
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
    public function valider(){
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_commande;
            if($autoriser == 1){  
                $etat = 'Validée';
                $vider = NULL;
                CommandeClientEntete::find($this->ids)->update(['etat'=>$etat,'etat_expedi'=>$vider,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);      
                CommandeClientLigne::where('id_commande_client_entete',$this->ids)->update(['etat'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                ProformaClientEntete::where('id',$this->id_prof)->update(['etat_cmd'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                
                
                $dates = date('dmy/His');
                // $length = 2;
                // $token = bin2hex(random_bytes($length));          
                $token_ok = 'CMD-EXP/'.$dates;
                ExpeditionClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->delete();
                ExpeditionClientEntete::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->delete();
                
                // Creer ligne expedition dans commande                
                $ligneCmdClient = CommandeClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->get(); 
                foreach($ligneCmdClient as $ligneCmdClients){
                    // creation et copie entete Expedition Client Ligne
                    ExpeditionClientLigne::create([ 
                        'code_expedition'=>$token_ok,
                        'code_commande'=>$ligneCmdClients->code_commande,
                        'id_commande_client_entete'=>$ligneCmdClients->id_commande_client_entete,
                        'nom_client'=>$ligneCmdClients->nom_client,
                        'id_client'=>$ligneCmdClients->id_client,
                        'produit'=>$ligneCmdClients->produit,
                        'id_produit'=>$ligneCmdClients->id_produit,
                        'reference'=>$ligneCmdClients->reference,
                        'type_produit'=>$ligneCmdClients->type_produit,                        
                        'prix_achat'=>$ligneCmdClients->prix_achat,
                        'prix_vente'=>$ligneCmdClients->prix_vente,
                        'quantite'=>$ligneCmdClients->quantite,
                        'quantite_expediee'=>$ligneCmdClients->quantite_expediee,
                        'reste_a_expedier'=>$ligneCmdClients->reste_a_expedier,
                        'remise'=>$ligneCmdClients->remise,
                        'montant_remise'=>$ligneCmdClients->montant_remise,
                        'tva'=>$ligneCmdClients->tva,
                        'montant_tva'=>$ligneCmdClients->montant_tva,
                        'precompte'=>$ligneCmdClients->precompte,
                        'montant_precompte'=>$ligneCmdClients->montant_precompte,
                        'montant_ht'=>$ligneCmdClients->montant_ht,
                        'montant_ttc'=>$ligneCmdClients->montant_ttc,
                        'marge'=>$ligneCmdClients->marge,
                        'id_entrepot'=>$ligneCmdClients->id_entrepot,
                        'offrir'=>$ligneCmdClients->offrir,
                        'etat'=>$etat,                        
                        'etat_facture'=>$ligneCmdClients->etat, 
                        'user_id'=>auth()->user()->id,
                        'nom_user'=>auth()->user()->name,
                        'societe_id'=>auth()->user()->societe_id,
                        'societe'=>auth()->user()->societe]);
                }
                //  Fin
                
                $id_activite = $this->ids;
                $page = 'CommandeClient';
                LogActivity::addToLog('Commande client validée', $id_activite, $page);
                $this->dispatch('alert',                    
                    title:'Commande sous la référence <strong>'.$this->reference.'</strong> validée!',
                    timer:3000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                );   
                flash ('Commande sous la référence <strong>'.$this->reference.'</strong> validée!')->success();   
                $this->redirect('/nouveau_cmd_clt?id='.$this->ids.'&ref='.$this->reference.'&active=6&champ=1-1&choix=2', navigate: true); 
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
    public function brouillon(){
        
        $etat = 'Brouillon';
        CommandeClientEntete::find($this->ids)->update(['etat'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);      
        CommandeClientLigne::where('id_commande_client_entete',$this->ids)->update(['etat'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
        ProformaClientEntete::where('id',$this->id_prof)->update(['etat_cmd'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
        
        $id_activite = $this->ids;
        $page = 'CommandeClient';
        LogActivity::addToLog('Commande client en brouillon', $id_activite, $page);
        $this->dispatch('alert',                    
            title:'Commande sous la référence <strong>'.$this->reference.'</strong> retournée au brouillon!',
            timer:3000,
            icon:'success',
            toast:true,
            showConfirmButton: false,
            position:'top-end',
        );  
        flash ('Commande sous la référence <strong>'.$this->reference.'</strong> retournée au brouillon!')->warning();     
        $this->redirect('/nouveau_cmd_clt?id='.$this->ids, navigate: true);        
    }  
    public function precedant(){ 
        $testPrecedant = CommandeClientEntete::where('societe_id',auth()->user()->societe_id)->where('id','<',$this->ids)->orderBy('id','desc')->count();
        if($testPrecedant > 0){ 
            $precedant = CommandeClientEntete::where('societe_id',auth()->user()->societe_id)->where('id','<',$this->ids)->orderBy('id','desc')->first();        
            $previous = $precedant->id; 
            $this->redirect('/nouveau_cmd_clt?id='.$previous.'&ref='.$this->reference.'&active=6&champ=1-1&choix=2', navigate: true);              
        }  
        else{
            $this->dispatch('alert',                    
                title:'Désolé, Fin enregistrements',
                timer:3000,
                icon:'warning',
                toast:true,
                showConfirmButton: false,
                position:'top-end',
            ); 
        }    
    }    
    public function suivant(){    
        
        $testSuivant = CommandeClientEntete::where('societe_id',auth()->user()->societe_id)->where('id','>',$this->ids)->orderBy('id','asc')->count();
        if($testSuivant > 0){
            $suivant = CommandeClientEntete::where('societe_id',auth()->user()->societe_id)->where('id','>',$this->ids)->orderBy('id','asc')->first();
            $next = $suivant->id;             
            $this->redirect('/nouveau_cmd_clt?id='.$next.'&ref='.$this->reference.'&active=6&champ=1-1&choix=2', navigate: true);                     
        }  
        else{
            $this->dispatch('alert',                    
                title:'Désolé, Fin enregistrements',
                timer:3000,
                icon:'warning',
                toast:true,
                showConfirmButton: false,
                position:'top-end',
            ); 
        } 
    }
    // ceci supprime toute la commande 
    public function confirmerEcraser($id){ 
        $this->approuver = $id;      
    } 
    public function ecraser(){      
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->supprimer_commande;
            if($autoriser == 1){                  
                // suppression definitive et redirection
                $page = 'CommandeClient';
                if(empty($this->etat_expedi)){ 
                    CommandeClientEntete::where('id',$this->ids)->delete(); 
                    CommandeClientLigne::where('id_commande_client_entete',$this->ids)->delete(); 
                    ExpeditionClientEntete::where('id_commande_client_entete',$this->ids)->delete(); 
                    ExpeditionClientLigne::where('id_commande_client_entete',$this->ids)->delete(); 
                    ExpeditionClientLignePartiel::where('id_commande_client_entete',$this->ids)->delete(); 
                    LogActivityModel::where('id_activite',$this->ids)->where('page',$page)->delete();
                    $id_activite = $this->ids;
                    LogActivity::addToLog('Commande client supprimée définitivement', $id_activite, $page);  
                    $this->dispatch('alert',                    
                        title:'Commande supprimée avec succes!',
                        timer:5000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );
                    flash ('La commande client a été supprimée!')->success();
                    $this->redirect('/listing_cmd_clt?active=6&champ=1-1&choix=2', navigate: true); 
                }
                else{
                    $this->dispatch('alert',                    
                        title:'Désolé, Impossible de supprimer cette commande : une ou plusieurs expéditions ou factures y sont associées!',
                        timer:10000,
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
    public function chargeExpedition(string $typeProd){
        // laisser cette fonction vide: permet de mettre a jour le modal apres modif de donnees dans back office (tres important) 
        // $this->type_produit = 'Expédition';  
    }
    public function chargeFacturation(string $typeProd){
        // laisser cette fonction vide: permet de mettre a jour le modal apres modif de donnees dans back office (tres important) 
        // $this->type_produit = 'Facturation';  
    }
    public function afficheLigneExpedition(int $idg){
        $this->open = $idg;
        $testChoix = ExpeditionClientLigne::where('societe_id',auth()->user()->societe_id)->where('id',$idg)->count();
        if($testChoix > 0){
            // ceci permet d'afficher la quantite entrepot origine
            $choixExp = ExpeditionClientLigne::where('societe_id',auth()->user()->societe_id)->where('id',$idg)->first();
            $this->identif = $choixExp->id;
            $this->type = $choixExp->type_produit;
            $this->nom_produit = $choixExp->produit;
            $this->id_produit = $choixExp->id_produit;
            $this->referenceProd = $choixExp->reference;            
            $this->type_produit = $choixExp->type_produit;
            
            $this->prix_achat = $choixExp->prix_achat;
            $this->prix_vente = $choixExp->prix_vente;
            
            $this->quantite_cmde = $choixExp->quantite;
            $this->quantite_expediee = $choixExp->quantite_expediee;
            // Pour expedition
            $this->reste_A_expedier = $choixExp->reste_a_expedier;
            $this->resteExpedier = $choixExp->reste_a_expedier;
            // pour facturation
            $this->reste_A_facturer = $choixExp->reste_a_expedier;
            $this->resteFacturer = $choixExp->reste_a_expedier;

            $this->remise = $choixExp->remise; 
            $this->montant_remise = $choixExp->montant_remise; 
            $this->tva = $choixExp->tva; 
            $this->montant_tva = $choixExp->montant_tva; 
            $this->precompte = $choixExp->precompte; 
            $this->montant_precompte = $choixExp->montant_precompte; 
            $this->montant_ht = $choixExp->montant_ht; 
            $this->montant_ttc = $choixExp->montant_ttc; 
            $this->marge = $choixExp->marge; 
            $this->id_entrepot = $choixExp->id_entrepot; 
            $this->nom_client = $choixExp->nom_client; 
            $this->id_client = $choixExp->id_client; 
            $this->offrir = $choixExp->offrir; 
            $this->etat = $choixExp->etat; 
            $this->etat_facture = $choixExp->etat_facture;  
        }
    }    
    public function AjouterLigneExpe(){
        $this->validate([            
            'reste_A_expedier'=>'required|numeric|min:0.5',           
        ]);
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_expedition;
            if($autoriser == 1){                
                if($this->reste_A_expedier  <= $this->resteExpedier){   
                    // $etat = 'Brouillon';
                    // $length = 2;
                    // $token = bin2hex(random_bytes($length));
                    $dates = date('dmy/His');
                    $token_ok = 'EXP/'.$dates;            
                    // dd($this->ids);
                    $verifieExp = ExpeditionClientEntete::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->where('statut',1)->count(); 
                    if($verifieExp > 0){
                        
                        $Exp = ExpeditionClientEntete::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->where('statut',1)->first(); 
                        $id_codeExpedit = $Exp->id;
                        $codeExpedit = $Exp->code_expedition;

                        $ligneExpedition = ExpeditionClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->where('id',$this->identif)->get(); 
                        foreach($ligneExpedition as $ligneExpeditions){ 
                                
                            $id_exp = $ligneExpeditions->id;            
                            $quantite_stock = $this->reste_A_expedier;            
                            $code_expedition = $ligneExpeditions->code_expedition;
                            $quantite_expediee = $ligneExpeditions->quantite_expediee + $this->reste_A_expedier;
                            $resteAexpedier = $ligneExpeditions->reste_a_expedier;                    
                            $id_fact_clt_entete = $ligneExpeditions->id_facture_client_entete;
                                                        
                            if($this->type_produit == 'Produit'){ 
                                $stockTrouver = Stock::where('societe_id',auth()->user()->societe_id)->where('id_entrepot',$this->id_entrepot)->where('id_produit',$this->id_produit)->first();
                                $this->reference = $stockTrouver->reference;
                                $qteSockFinal = $stockTrouver->quantite - $quantite_stock;
                                $valorisation_achat_total = $stockTrouver->prix_moyen_pondere_achat * $qteSockFinal;
                                $valeur_vente_total = $stockTrouver->prix_vente_unitaire * $qteSockFinal;
                                $reste_a_expedier = $resteAexpedier - $this->reste_A_expedier;
                            }
                            else{
                                $stockTrouver = Produit::where('societe_id',auth()->user()->societe_id)->where('id',$this->id_produit)->first();
                                $this->reference = $stockTrouver->reference;
                                $reste_a_expedier = $resteAexpedier - $this->reste_A_expedier;
                            } 

                            if($this->type_produit == 'Produit'){
                                Stock::where('societe_id',auth()->user()->societe_id)->where('id_entrepot',$this->id_entrepot)->where('id_produit',$this->id_produit)->update(['quantite'=>$qteSockFinal,'valorisation_achat_total'=>$valorisation_achat_total,'valeur_vente_total'=>$valeur_vente_total,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);   
                            }                                    
                            ExpeditionClientLigne::where('id',$id_exp)->update(['quantite_expediee'=>$quantite_expediee,'reste_a_expedier'=>$reste_a_expedier,
                            'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);   
                        
                            if($this->type_produit == 'Produit'){
                                $Entrepo = Entrepot::where('societe_id',auth()->user()->societe_id)->where('id',$this->id_entrepot)->first();                    
                                $nom_entrepot = $Entrepo->nom;
                            } 

                            $libele_mouvement = 'Expédition';           
                            $code_mouvement = date('YmdHis');
                            $statut = 'EXP';
                            
                            if($this->type_produit == 'Produit'){
                                Mouvement::create(['id_entrepot'=>$this->id_entrepot,'nom_produit'=>$this->nom_produit,'id_produit'=>$this->id_produit,'reference'=>$this->reference,'quantite'=>-$quantite_stock,'libele_mouvement'=>$libele_mouvement,
                                'code_mouvement'=>$code_mouvement,'statut'=>$statut,'origine'=>$codeExpedit,'id_expedition'=>$id_codeExpedit,'entrepot'=>$nom_entrepot,'societe'=>auth()->user()->societe,
                                'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                            }                                   

                            // Verifier l'etat et valider (Clôturée ou Partiel)
                            $charge = ExpeditionClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->where('id_produit',$this->id_produit)->get();
                            $QteCmderTotal = $charge->sum('quantite');
                            $QteExpedieeTotal = $charge->sum('quantite_expediee');
                            // dd($QteCmderTotal,'=',$QteExpedieeTotal);
                            if($QteCmderTotal == $QteExpedieeTotal){
                                $etatExp = 'Clôturée'; 
                                ExpeditionClientLigne::where('id',$id_exp)->update(['etat'=>$etatExp,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                            }
                            else{
                                $etatExp = 'Partiel'; 
                                ExpeditionClientLigne::where('id',$id_exp)->update(['etat'=>$etatExp,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                            }
                            // Fin                            

                            // ************ Calul ***********
                            if($ligneExpeditions->offrir == 'Non'){
                                // $montant_vente =  $this->prix_vente * $this->quantite;                        
                                $montant_achat_avec_qte = ($this->prix_achat * $quantite_expediee);  

                                $remiseDetail = $this->remise/100; // valeur remise
                                $remise_montant = ($this->prix_vente * $quantite_expediee) * $remiseDetail; // montant remise

                                $montant_remiser_ht = ($this->prix_vente * $quantite_expediee) - $remise_montant; // Montant vente avec remise inclus hors taxe                        
                                $marge = $montant_remiser_ht - $montant_achat_avec_qte; // Marge avec remise                       

                                $tvaDetail = $this->tva/100; // valeur de la tva
                                $tva_montant = $montant_remiser_ht * $tvaDetail;

                                $precompteDetail = $this->precompte/100; // valeur du precompte
                                $precompte_montant = $montant_remiser_ht * $precompteDetail;

                                $montant_ttc = $montant_remiser_ht + $tva_montant + $precompte_montant; // Montant TTC
                            }
                            else{
                                // les totaux seront a zero                                       
                                $remise_montant = 0;
                                $tva_montant = 0;
                                $precompte_montant = 0;
                                $montant_remiser_ht = 0;
                                $montant_ttc = 0;
                                $marge = 0;   
                            }
                            //************** Fin Calcul ***********//

                            // Expedition ligne partiel
                            $test_ligPart = ExpeditionClientLignePartiel::where('societe_id',auth()->user()->societe_id)->where('code_expedition',$codeExpedit)->where('id_produit',$this->id_produit)->count(); 
                            if($test_ligPart > 0){
                                ExpeditionClientLignePartiel::where('societe_id',auth()->user()->societe_id)->where('code_expedition',$codeExpedit)->where('id_produit',$this->id_produit)->delete();
                            }                            
                            ExpeditionClientLignePartiel::create([ 
                                'id_expedition_client_entete'=>$id_codeExpedit,   // id                   
                                'code_expedition'=>$codeExpedit,                   
                                'code_facture'=>$ligneExpeditions->code_facture,
                                'id_facture_client_entete'=>$ligneExpeditions->id_facture_client_entete,
                                'code_commande'=>$ligneExpeditions->code_commande,
                                'id_commande_client_entete'=>$ligneExpeditions->id_commande_client_entete,
                                'produit'=>$ligneExpeditions->produit,
                                'id_produit'=>$ligneExpeditions->id_produit,
                                'reference'=>$ligneExpeditions->reference,                                
                                'type_produit'=>$this->type_produit,
                                'prix_achat'=>$ligneExpeditions->prix_achat,
                                'prix_vente'=>$ligneExpeditions->prix_vente,
                                'quantite'=>$ligneExpeditions->quantite,
                                'quantite_total_expediee'=>$quantite_expediee, 
                                'quantite_expediee'=>$quantite_expediee, 
                                'reste_a_expedier'=>$reste_a_expedier, 
                                'remise'=>$ligneExpeditions->remise,
                                'montant_remise'=>$remise_montant,
                                'tva'=>$ligneExpeditions->tva,
                                'montant_tva'=>$tva_montant,
                                'precompte'=>$ligneExpeditions->precompte,
                                'montant_precompte'=>$precompte_montant,
                                'montant_ht'=>$montant_remiser_ht,
                                'montant_ttc'=>$montant_ttc,
                                'marge'=>$marge,
                                'id_entrepot'=>$ligneExpeditions->id_entrepot,
                                'nom_client'=>$ligneExpeditions->nom_client,
                                'id_client'=>$ligneExpeditions->id_client,
                                'offrir'=>$ligneExpeditions->offrir,
                                'etat'=>$etatExp,                                
                                'etat_facture'=>$ligneExpeditions->etat_facture, 
                                'user_id'=>auth()->user()->id,
                                'nom_user'=>auth()->user()->name,
                                'societe_id'=>auth()->user()->societe_id,
                                'societe'=>auth()->user()->societe]);                            
                            // Fin Expedition ligne partiel
                                
                            // Verifier l'etat et valider (Clôturée ou Partiel)
                                $charge = ExpeditionClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->get();
                                $QteCmderTotal = $charge->sum('quantite');
                                $QteExpedieeTotal = $charge->sum('quantite_expediee');
                                if($QteCmderTotal == $QteExpedieeTotal){
                                    $etat = 'Clôturée'; 
                                    $statut = 0; 
                                }
                                else{
                                    $etat = 'Partiel';
                                    $statut = 1; 
                                }
                                // Fin  
                                $expeClient_ligne = ExpeditionClientLignePartiel::where('societe_id',auth()->user()->societe_id)->where('id_expedition_client_entete',$id_codeExpedit)->get();
                                $montantHT = $expeClient_ligne->sum('montant_ht');
                                $montantTTC = $expeClient_ligne->sum('montant_ttc');
                                $marge = $expeClient_ligne->sum('marge');
                                $montantRemise = $expeClient_ligne->sum('montant_remise');
                                $montantTva = $expeClient_ligne->sum('montant_tva');
                                $montantPrecompte = $expeClient_ligne->sum('montant_precompte');                                

                                CommandeClientEntete::where('societe_id',auth()->user()->societe_id)->where('id',$this->ids)->update(['etat_expedi'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                                ExpeditionClientEntete::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->where('code_expedition',$codeExpedit)->update(['etat'=>$etatExp,'statut'=>$statut, 
                                                              'montant_ht'=>$montantHT,'marge'=>$marge,'montant_ttc'=>$montantTTC,'montant_remise'=>$montantRemise,'montant_tva'=>$montantTva,'montant_precompte'=>$montantPrecompte,                                                             
                                                              'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                            
                            
                            $id_activite = $id_codeExpedit;
                            $page = 'ExpeditionClient';
                            LogActivity::addToLog('Expédition partielle » (-'.$this->reste_A_expedier .') '.$this->nom_produit, $id_activite, $page); 
                            $this->dispatch('alert',                    
                                title:'Expédition partielle » (-'.$this->reste_A_expedier .') '.$this->nom_produit.' effectuée avec succes!',
                                timer:5000,
                                icon:'success',
                                toast:true,
                                showConfirmButton: false,
                                position:'top-end',
                            );
                            $this->redirect('/nouveau_cmd_clt?id='.$this->ids.'&ref='.$this->reference.'&active=6&champ=1-1&choix=2', navigate: true);
                        }                     
                    }
                    else{                          
                        
                        // Creation entete et ajout ligne
                        $enteteCmdClient = CommandeClientEntete::where('societe_id',auth()->user()->societe_id)->where('id',$this->ids)->get();
                        foreach($enteteCmdClient as $enteteCmdClients){
                            // creation et copie entete Expedition Client Entete
                            $expCltEntet = ExpeditionClientEntete::create([  
                                'code_expedition'=>$token_ok,
                                'code_commande'=>$enteteCmdClients->code_commande,
                                'id_commande_client_entete'=>$enteteCmdClients->id,
                                'nom_client'=>$enteteCmdClients->nom_client,
                                'id_client'=>$enteteCmdClients->id_client,
                                'date_commande'=>$enteteCmdClients->date_commande,
                                'date_echeance'=>$enteteCmdClients->date_livraison,
                                'note'=>$enteteCmdClients->note,
                                'montant_ht'=>$enteteCmdClients->montant_ht,
                                'montant_remise'=>$enteteCmdClients->montant_remise,
                                'montant_tva'=>$enteteCmdClients->montant_tva,
                                'montant_precompte'=>$enteteCmdClients->montant_precompte,
                                'montant_ttc'=>$enteteCmdClients->montant_ttc,
                                'marge'=>$enteteCmdClients->marge,
                                'montant_recu'=>$enteteCmdClients->montant_recu,
                                'reste_a_percevoir'=>$enteteCmdClients->reste_a_percevoir,
                                'statut'=>1, // tres important pour cette fonction
                                'etat'=>'Brouillon',
                                'etat_cmd'=>$enteteCmdClients->etat,
                                'societe'=>auth()->user()->societe,
                                'societe_id'=>auth()->user()->societe_id,
                                'nom_user'=>auth()->user()->name,
                                'user_id'=>auth()->user()->id]);
                        
                            $dernier_id =  $expCltEntet->id;

                            CommandeClientEntete::find($this->ids)->update(['code_expedition'=>$token_ok,'id_expedition_entete'=>$dernier_id,
                            'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                            

                            $ligneExpedition = ExpeditionClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->where('id',$this->identif)->get(); 
                            foreach($ligneExpedition as $ligneExpeditions){ 
                                    
                                $id_exp = $ligneExpeditions->id;            
                                $quantite_stock = $this->reste_A_expedier;            
                                $code_expedition = $ligneExpeditions->code_expedition;
                                $quantite_expediee = $ligneExpeditions->quantite_expediee + $this->reste_A_expedier;
                                $resteAexpedier = $ligneExpeditions->reste_a_expedier;                    
                                $id_fact_clt_entete = $ligneExpeditions->id_facture_client_entete;
                                                           
                                if($this->type_produit == 'Produit'){ 
                                    $stockTrouver = Stock::where('societe_id',auth()->user()->societe_id)->where('id_entrepot',$this->id_entrepot)->where('id_produit',$this->id_produit)->first();
                                    $this->reference = $stockTrouver->reference;
                                    $qteSockFinal = $stockTrouver->quantite - $quantite_stock;
                                    $valorisation_achat_total = $stockTrouver->prix_moyen_pondere_achat * $qteSockFinal;
                                    $valeur_vente_total = $stockTrouver->prix_vente_unitaire * $qteSockFinal;
                                    $reste_a_expedier = $resteAexpedier - $this->reste_A_expedier ;
                                }
                                else{
                                    $stockTrouver = Produit::where('societe_id',auth()->user()->societe_id)->where('id',$this->id_produit)->first();
                                    $this->reference = $stockTrouver->reference;
                                    $reste_a_expedier = $resteAexpedier - $this->reste_A_expedier ;
                                }                              
                                
                                if($this->type_produit == 'Produit'){
                                    Stock::where('societe_id',auth()->user()->societe_id)->where('id_entrepot',$this->id_entrepot)->where('id_produit',$this->id_produit)->update(['quantite'=>$qteSockFinal,'valorisation_achat_total'=>$valorisation_achat_total,'valeur_vente_total'=>$valeur_vente_total,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);   
                                }                                    
                                ExpeditionClientLigne::where('id',$id_exp)->update(['quantite_expediee'=>$quantite_expediee,'reste_a_expedier'=>$reste_a_expedier,
                                'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);   
                            
                                if($this->type_produit == 'Produit'){
                                    $Entrepo = Entrepot::where('societe_id',auth()->user()->societe_id)->where('id',$this->id_entrepot)->first();                    
                                    $nom_entrepot = $Entrepo->nom;
                                } 

                                $libele_mouvement = 'Expédition';           
                                $code_mouvement = date('YmdHis');
                                $statut = 'EXP';                                
                                if($this->type_produit == 'Produit'){
                                    Mouvement::create(['id_entrepot'=>$this->id_entrepot,'nom_produit'=>$this->nom_produit,'id_produit'=>$this->id_produit,'reference'=>$this->reference,'quantite'=>-$quantite_stock,'libele_mouvement'=>$libele_mouvement,
                                    'code_mouvement'=>$code_mouvement,'statut'=>$statut,'origine'=>$token_ok,'id_expedition'=>$dernier_id,'entrepot'=>$nom_entrepot,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,
                                    'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                                }    
                                
                                // Verifier l'etat et valider (Clôturée ou Partiel)
                                $charge = ExpeditionClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->where('id_produit',$this->id_produit)->get();
                                $QteCmderTotal = $charge->sum('quantite');
                                $QteExpedieeTotal = $charge->sum('quantite_expediee');
                                // dd($QteCmderTotal,'=',$QteExpedieeTotal);
                                if($QteCmderTotal == $QteExpedieeTotal){
                                    $etatExp = 'Clôturée'; 
                                    ExpeditionClientLigne::where('id',$id_exp)->update(['etat'=>$etatExp,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                                }
                                else{
                                    $etatExp = 'Partiel'; 
                                    ExpeditionClientLigne::where('id',$id_exp)->update(['etat'=>$etatExp,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                                }
                                // // Fin

                                // ************ Calul ***********
                                if($ligneExpeditions->offrir == 'Non'){ 
                                    // $montant_vente =  $this->prix_vente * $this->quantite;                        
                                    $montant_achat_avec_qte = ($this->prix_achat * $this->reste_A_expedier);  

                                    $remiseDetail = $this->remise/100; // valeur remise
                                    $remise_montant = ($this->prix_vente * $this->reste_A_expedier) * $remiseDetail; // montant remise

                                    $montant_remiser_ht = ($this->prix_vente * $this->reste_A_expedier) - $remise_montant; // Montant vente avec remise inclus hors taxe                        
                                    $marge = $montant_remiser_ht - $montant_achat_avec_qte; // Marge avec remise                       

                                    $tvaDetail = $this->tva/100; // valeur de la tva
                                    $tva_montant = $montant_remiser_ht * $tvaDetail;

                                    $precompteDetail = $this->precompte/100; // valeur du precompte
                                    $precompte_montant = $montant_remiser_ht * $precompteDetail;

                                    $montant_ttc = $montant_remiser_ht + $tva_montant + $precompte_montant; // Montant TTC
                                }
                                else{
                                    // les totaux seront a zero                                       
                                    $remise_montant = 0;
                                    $tva_montant = 0;
                                    $precompte_montant = 0;
                                    $montant_remiser_ht = 0;
                                    $montant_ttc = 0;
                                    $marge = 0;  
                                }
                                        //************** Fin Calcul ***********//

                                // Expedition ligne partiel
                                $test_ligPart = ExpeditionClientLignePartiel::where('societe_id',auth()->user()->societe_id)->where('code_expedition',$token_ok)->where('id_produit',$this->id_produit)->count(); 
                                if($test_ligPart > 0){
                                    ExpeditionClientLignePartiel::where('societe_id',auth()->user()->societe_id)->where('code_expedition',$token_ok)->where('id_produit',$this->id_produit)->delete();
                                }                            
                                ExpeditionClientLignePartiel::create([ 
                                    'id_expedition_client_entete'=>$dernier_id,   // id                   
                                    'code_expedition'=>$token_ok,  // code_expedition                   
                                    'code_facture'=>$ligneExpeditions->code_facture,
                                    'id_facture_client_entete'=>$ligneExpeditions->id_facture_client_entete,
                                    'code_commande'=>$ligneExpeditions->code_commande,
                                    'id_commande_client_entete'=>$ligneExpeditions->id_commande_client_entete,
                                    'produit'=>$ligneExpeditions->produit,
                                    'id_produit'=>$ligneExpeditions->id_produit,
                                    'reference'=>$ligneExpeditions->reference,                                    
                                    'type_produit'=>$this->type_produit,
                                    'prix_achat'=>$ligneExpeditions->prix_achat,
                                    'prix_vente'=>$ligneExpeditions->prix_vente,
                                    'quantite'=>$ligneExpeditions->quantite,
                                    'quantite_total_expediee'=>$quantite_expediee, // quantite total expediee
                                    'quantite_expediee'=>$this->reste_A_expedier, // quantite_expediee
                                    'reste_a_expedier'=>$reste_a_expedier, // reste_a_expedier
                                    'remise'=>$ligneExpeditions->remise,
                                    'montant_remise'=>$remise_montant,
                                    'tva'=>$ligneExpeditions->tva,
                                    'montant_tva'=>$tva_montant,
                                    'precompte'=>$ligneExpeditions->precompte,
                                    'montant_precompte'=>$precompte_montant,
                                    'montant_ht'=>$montant_remiser_ht,
                                    'montant_ttc'=>$montant_ttc,
                                    'marge'=>$marge,
                                    'id_entrepot'=>$ligneExpeditions->id_entrepot,
                                    'nom_client'=>$ligneExpeditions->nom_client,
                                    'id_client'=>$ligneExpeditions->id_client,
                                    'offrir'=>$ligneExpeditions->offrir,
                                    'etat'=>$etatExp,
                                    'etat_facture'=>$ligneExpeditions->etat_facture, 
                                    'user_id'=>auth()->user()->id,
                                    'nom_user'=>auth()->user()->name,
                                    'societe_id'=>auth()->user()->societe_id,
                                    'societe'=>auth()->user()->societe]);                            
                                // Fin Expedition ligne partiel

                                $expeClient_ligne = ExpeditionClientLignePartiel::where('societe_id',auth()->user()->societe_id)->where('id_expedition_client_entete',$dernier_id)->get();
                                $montantHT = $expeClient_ligne->sum('montant_ht');
                                $montantTTC = $expeClient_ligne->sum('montant_ttc');
                                $marge = $expeClient_ligne->sum('marge');
                                $montantRemise = $expeClient_ligne->sum('montant_remise');
                                $montantTva = $expeClient_ligne->sum('montant_tva');
                                $montantPrecompte = $expeClient_ligne->sum('montant_precompte');

                                // Verifier l'etat et valider (Clôturée ou Partiel)
                                $charge = ExpeditionClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->where('code_expedition',$code_expedition)->get();
                                $QteCmderTotal = $charge->sum('quantite');
                                $QteExpedieeTotal = $charge->sum('quantite_expediee');
                                if($QteCmderTotal == $QteExpedieeTotal){
                                    $etat = 'Clôturée'; 
                                }
                                else{
                                    $etat = 'Partiel'; 
                                }
                                // Fin

                                CommandeClientEntete::where('societe_id',auth()->user()->societe_id)->where('id',$this->ids)->update(['etat_expedi'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                                ExpeditionClientEntete::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->where('code_expedition',$token_ok)->update(['etat'=>$etatExp,
                                                              'montant_ht'=>$montantHT,'marge'=>$marge,'montant_ttc'=>$montantTTC,'montant_remise'=>$montantRemise,'montant_tva'=>$montantTva,'montant_precompte'=>$montantPrecompte,
                                                              'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 

                                $id_activite = $dernier_id;
                                $page = 'ExpeditionClient';
                                LogActivity::addToLog('Expédition partielle » (-'.$this->reste_A_expedier .') '.$this->nom_produit, $id_activite, $page); 
                                $this->dispatch('alert',                    
                                    title:'Expédition partielle » (-'.$this->reste_A_expedier .') '.$this->nom_produit.' effectuée avec succes!',
                                    timer:5000,
                                    icon:'success',
                                    toast:true,
                                    showConfirmButton: false,
                                    position:'top-end',
                                );
                                $this->redirect('/nouveau_cmd_clt?id='.$this->ids.'&ref='.$this->reference.'&active=6&champ=1-1&choix=2', navigate: true);
                            }
                        }                                                
                    }
                }
                else{
                    $this->dispatch('alert',                    
                        title:'Désolé, le reste à expédier » '.$this->resteExpedier,
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
    public function AjouterLigneFact(){
        $this->validate([            
            'reste_A_facturer'=>'required|numeric|min:0.5',           
        ]);
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_expedition;
            if($autoriser == 1){                
                if($this->reste_A_facturer  <= $this->resteFacturer){   
                    // $etat = 'Brouillon';
                    // $length = 2;
                    // $token = bin2hex(random_bytes($length));
                    $dates = date('dmy/His');
                    $token_ok = 'FACT/'.$dates;            

                    $verifieFact = factureClientEntete::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->where('statut',1)->count(); 
                    if($verifieFact > 0){ 
                        
                        $Fact = factureClientEntete::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->where('statut',1)->first(); 
                        $id_codeFact = $Fact->id;
                        $codeFact = $Fact->code_facture;

                        $ligneExpedition = ExpeditionClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->where('id',$this->identif)->get(); 
                        foreach($ligneExpedition as $ligneExpeditions){ 
                                
                                // ************ Calul ***********
                                if($ligneExpeditions->offrir == 'Non'){

                                    $montant_achat_avec_qte = ($this->prix_achat * $this->reste_A_facturer);  

                                    $remiseDetail = $this->remise/100; // valeur remise
                                    $remise_montant = ($this->prix_vente * $this->reste_A_facturer) * $remiseDetail; // montant remise

                                    $montant_remiser_ht = ($this->prix_vente * $this->reste_A_facturer) - $remise_montant; // Montant vente avec remise inclus hors taxe                        
                                    $marge = $montant_remiser_ht - $montant_achat_avec_qte; // Marge avec remise                       

                                    $tvaDetail = $this->tva/100; // valeur de la tva
                                    $tva_montant = $montant_remiser_ht * $tvaDetail;

                                    $precompteDetail = $this->precompte/100; // valeur du precompte
                                    $precompte_montant = $montant_remiser_ht * $precompteDetail;

                                    $montant_ttc = $montant_remiser_ht + $tva_montant + $precompte_montant; // Montant TTC
                                }
                                else{
                                    // les totaux seront a zero                                       
                                    $remise_montant = 0;
                                    $tva_montant = 0;
                                    $precompte_montant = 0;
                                    $montant_remiser_ht = 0;
                                    $montant_ttc = 0;
                                    $marge = 0;
                                }
                                //************** Fin Calcul ***********//
                                // Facture ligne partiel
                                $test_ligPart = factureClientLigne::where('societe_id',auth()->user()->societe_id)->where('code_facture',$codeFact)->where('id_produit',$this->id_produit)->count(); 
                                if($test_ligPart > 0){
                                    factureClientLigne::where('societe_id',auth()->user()->societe_id)->where('code_facture',$codeFact)->where('id_produit',$this->id_produit)->delete();
                                }
                                 factureClientLigne::create([  
                                    'code_facture'=>$codeFact,
                                    'id_facture_client_entete'=>$id_codeFact,
                                    'nom_client'=>$ligneExpeditions->nom_client,
                                    'id_client'=>$ligneExpeditions->id_client,
                                    'produit'=>$ligneExpeditions->produit,
                                    'id_produit'=>$ligneExpeditions->id_produit,
                                    'reference'=>$ligneExpeditions->reference,
                                    'type_produit'=>$ligneExpeditions->type_produit,                            
                                    'prix_achat'=>$ligneExpeditions->prix_achat,
                                    'prix_vente'=>$ligneExpeditions->prix_vente,
                                    'quantite'=>$ligneExpeditions->quantite,
                                    'quantite_expediee'=>$this->reste_A_facturer,
                                    'reste_a_expedier'=>$ligneExpeditions->reste_a_expedier - $this->reste_A_facturer,
                                    'remise'=>$ligneExpeditions->remise,
                                    'montant_remise'=>$remise_montant,
                                    'tva'=>$ligneExpeditions->tva,
                                    'montant_tva'=>$tva_montant,
                                    'precompte'=>$ligneExpeditions->precompte,
                                    'montant_precompte'=>$precompte_montant,
                                    'montant_ht'=>$montant_remiser_ht,
                                    'montant_ttc'=>$montant_ttc,
                                    'marge'=>$marge,
                                    'id_entrepot'=>$ligneExpeditions->id_entrepot,                   
                                    'offrir'=>$ligneExpeditions->offrir,
                                    'etat'=>'Brouillon',
                                    'user_id'=>auth()->user()->id,
                                    'nom_user'=>auth()->user()->name,
                                    'societe_id'=>auth()->user()->societe_id,
                                    'societe'=>auth()->user()->societe]);                                  

                                // $statut = 0; 
                                $factClient_ligne = factureClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_facture_client_entete',$id_codeFact)->get();
                                $montantHT = $factClient_ligne->sum('montant_ht');
                                $montantTTC = $factClient_ligne->sum('montant_ttc');
                                $marge = $factClient_ligne->sum('marge');
                                $montantRemise = $factClient_ligne->sum('montant_remise');
                                $montantTva = $factClient_ligne->sum('montant_tva');
                                $montantPrecompte = $factClient_ligne->sum('montant_precompte');                             

                                CommandeClientEntete::where('societe_id',auth()->user()->societe_id)->where('id',$this->ids)->update(['etat_fact'=>'Brouillon','nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                                factureClientEntete::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->where('code_facture',$codeFact)->update(['etat'=>'Brouillon',
                                                              'montant_ht'=>$montantHT,'marge'=>$marge,'montant_ttc'=>$montantTTC,'montant_remise'=>$montantRemise,'montant_tva'=>$montantTva,'montant_precompte'=>$montantPrecompte,
                                                              'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                             
                            
                            
                            $id_activite = $id_codeFact;
                            $page = 'factureClient';
                            LogActivity::addToLog('Facturation partielle » (-'.$this->reste_A_facturer .') '.$this->nom_produit, $id_activite, $page); 
                            $this->dispatch('alert',                    
                                title:'Facturation partielle » (-'.$this->reste_A_facturer .') '.$this->nom_produit.' effectuée avec succes!',
                                timer:5000,
                                icon:'success',
                                toast:true,
                                showConfirmButton: false,
                                position:'top-end',
                            );
                            $this->redirect('/nouveau_cmd_clt?id='.$this->ids.'&ref='.$this->reference.'&active=6&champ=1-1&choix=2', navigate: true);
                        }                     
                    }
                    else{                          
                        
                        // Creation entete et ajout ligne
                        $enteteCmdClient = CommandeClientEntete::where('societe_id',auth()->user()->societe_id)->where('id',$this->ids)->get();
                        foreach($enteteCmdClient as $enteteCmdClients){
                            // creation et copie entete Expedition Client Entete
                            $factCltEntet = factureClientEntete::create([ 
                                'code_facture'=>$token_ok,
                                'code_commande'=>$enteteCmdClients->code_commande,
                                'id_commande_client_entete'=>$enteteCmdClients->id,
                                'nom_client'=>$enteteCmdClients->nom_client,
                                'id_client'=>$enteteCmdClients->id_client,
                                'telephone'=>$enteteCmdClients->telephone,
                                'date_facturation'=>$enteteCmdClients->date_commande,
                                'date_echeance'=>$enteteCmdClients->date_livraison,
                                'mode_reglement'=>'Espèce', // Espèce par defaut
                                'note'=>$enteteCmdClients->note,
                                'montant_ht'=>$enteteCmdClients->montant_ht,
                                'montant_remise'=>$enteteCmdClients->montant_remise,
                                'montant_tva'=>$enteteCmdClients->montant_tva,
                                'montant_precompte'=>$enteteCmdClients->montant_precompte,
                                'montant_ttc'=>$enteteCmdClients->montant_ttc,
                                'marge'=>$enteteCmdClients->marge,
                                'montant_recu'=>0,
                                'reste_a_percevoir'=>$enteteCmdClients->montant_ttc,
                                'statut'=>1, // tres important pour cette fonction
                                'etat'=>'Brouillon',
                                'etat_expedi'=>'',                         
                                'societe'=>auth()->user()->societe,
                                'societe_id'=>auth()->user()->societe_id,
                                'nom_user'=>auth()->user()->name,
                                'user_id'=>auth()->user()->id]);
                        
                            $dernier_id = $factCltEntet->id;
                            $NbrefactClt = factureClientEntete::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->count();

                            CommandeClientEntete::find($this->ids)->update(['nbre_facture'=>$NbrefactClt,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                            

                            $ligneExpedition = ExpeditionClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->where('id',$this->identif)->get(); 
                            foreach($ligneExpedition as $ligneExpeditions){ 
                            
                                 // ************ Calul ***********
                                if($ligneExpeditions->offrir == 'Non'){

                                    $montant_achat_avec_qte = ($this->prix_achat * $this->reste_A_facturer);  

                                    $remiseDetail = $this->remise/100; // valeur remise
                                    $remise_montant = ($this->prix_vente * $this->reste_A_facturer) * $remiseDetail; // montant remise

                                    $montant_remiser_ht = ($this->prix_vente * $this->reste_A_facturer) - $remise_montant; // Montant vente avec remise inclus hors taxe                        
                                    $marge = $montant_remiser_ht - $montant_achat_avec_qte; // Marge avec remise                       

                                    $tvaDetail = $this->tva/100; // valeur de la tva
                                    $tva_montant = $montant_remiser_ht * $tvaDetail;

                                    $precompteDetail = $this->precompte/100; // valeur du precompte
                                    $precompte_montant = $montant_remiser_ht * $precompteDetail;

                                    $montant_ttc = $montant_remiser_ht + $tva_montant + $precompte_montant; // Montant TTC
                                }
                                else{
                                    // les totaux seront a zero                                       
                                    $remise_montant = 0;
                                    $tva_montant = 0;
                                    $precompte_montant = 0;
                                    $montant_remiser_ht = 0;
                                    $montant_ttc = 0;
                                    $marge = 0;
                                }
                                //************** Fin Calcul ***********//

                                // Facture ligne partiel
                                $test_ligPart = factureClientLigne::where('societe_id',auth()->user()->societe_id)->where('code_facture',$token_ok)->where('id_produit',$this->id_produit)->count(); 
                                if($test_ligPart > 0){
                                    factureClientLigne::where('societe_id',auth()->user()->societe_id)->where('code_facture',$token_ok)->where('id_produit',$this->id_produit)->delete();
                                }                            
                                factureClientLigne::create([  
                                    'code_facture'=>$token_ok,
                                    'id_facture_client_entete'=> $dernier_id,
                                    'code_commande'=>$ligneExpeditions->code_commande,
                                    'id_commande_client_entete'=>$ligneExpeditions->id_commande_client_entete,
                                    'nom_client'=>$ligneExpeditions->nom_client,
                                    'id_client'=>$ligneExpeditions->id_client,
                                    'produit'=>$ligneExpeditions->produit,
                                    'id_produit'=>$ligneExpeditions->id_produit,
                                    'reference'=>$ligneExpeditions->reference,
                                    'type_produit'=>$ligneExpeditions->type_produit,                            
                                    'prix_achat'=>$ligneExpeditions->prix_achat,
                                    'prix_vente'=>$ligneExpeditions->prix_vente,
                                    'quantite'=>$ligneExpeditions->quantite,
                                    'quantite_expediee'=>$this->reste_A_facturer,                                    
                                    'reste_a_expedier'=>$ligneExpeditions->reste_a_expedier - $this->reste_A_facturer,                                    
                                    'remise'=>$ligneExpeditions->remise,
                                    'montant_remise'=>$remise_montant,
                                    'tva'=>$ligneExpeditions->tva,
                                    'montant_tva'=>$tva_montant,
                                    'precompte'=>$ligneExpeditions->precompte,
                                    'montant_precompte'=>$precompte_montant,
                                    'montant_ht'=>$montant_remiser_ht,
                                    'montant_ttc'=>$montant_ttc,
                                    'marge'=>$marge,
                                    'id_entrepot'=>$ligneExpeditions->id_entrepot,                   
                                    'offrir'=>$ligneExpeditions->offrir,
                                    'etat'=>'Brouillon',
                                    'user_id'=>auth()->user()->id,
                                    'nom_user'=>auth()->user()->name,
                                    'societe_id'=>auth()->user()->societe_id,
                                    'societe'=>auth()->user()->societe]);


                                $factClient_ligne = factureClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_facture_client_entete',$dernier_id)->get();
                                $montantHT = $factClient_ligne->sum('montant_ht');
                                $montantTTC = $factClient_ligne->sum('montant_ttc');
                                $marge = $factClient_ligne->sum('marge');
                                $montantRemise = $factClient_ligne->sum('montant_remise');
                                $montantTva = $factClient_ligne->sum('montant_tva');
                                $montantPrecompte = $factClient_ligne->sum('montant_precompte');
                                
                                CommandeClientEntete::where('societe_id',auth()->user()->societe_id)->where('id',$this->ids)->update(['etat_fact'=>'Brouillon','nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                                factureClientEntete::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->where('code_facture',$token_ok)->update(['etat'=>'Brouillon',
                                                              'montant_ht'=>$montantHT,'marge'=>$marge,'montant_ttc'=>$montantTTC,'montant_remise'=>$montantRemise,'montant_tva'=>$montantTva,'montant_precompte'=>$montantPrecompte,
                                                              'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 

                                $id_activite = $dernier_id;
                                $page = 'factureClient';
                                LogActivity::addToLog('Facturation partielle » (-'.$this->reste_A_facturer .') '.$this->nom_produit, $id_activite, $page); 
                                $this->dispatch('alert',                    
                                    title:'Facturation partielle » (-'.$this->reste_A_facturer .') '.$this->nom_produit.' effectuée avec succes!',
                                    timer:5000,
                                    icon:'success',
                                    toast:true,
                                    showConfirmButton: false,
                                    position:'top-end',
                                );
                                $this->redirect('/nouveau_cmd_clt?id='.$this->ids.'&ref='.$this->reference.'&active=6&champ=1-1&choix=2', navigate: true);
                            }
                        }                                                
                    }
                }
                else{
                    $this->dispatch('alert',                    
                        title:'Désolé, le reste à expédier » '.$this->resteFacturer,
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
    public function detailProf(int $idx, $codeProforma){
        // ceci au chargement de la page
        $test_proforma = ProformaClientEntete::where('societe_id',auth()->user()->societe_id)->where('id',$idx)->count();    
        if($test_proforma > 0){
            $compte = ProformaClientEntete::where('societe_id',auth()->user()->societe_id)->where('id',$idx)->first();               
            // $this->ids = $compte->id;           
            $this->reference = $compte->code_proforma; // reference proforma
            $this->redirect('/nouveau_prof_clt?id='.$idx.'&ref='.$this->reference.'&active=6&champ=1-1&choix=1', navigate: true);
        }  
        else{
            $this->dispatch('alert',                    
            title:'Désolé, cette proforma n\'existe pas!',
                timer:5000,
                icon:'warning',
                toast:true,
                showConfirmButton: false,
                position:'top-end',
            );  
            flash ('Désolé, cette proforma <strong>('.$codeProforma.')</strong> n\'existe pas!')->error();
            $this->redirect('/proforma?active=6&champ=1-1&choix=1', navigate: true);
        }
    }
}
