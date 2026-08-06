<?php

namespace App\Livewire\GestionFacturation\Client;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate; 
use App\Helpers\LogActivity;
use App\Models\LogActivity as LogActivityModel;
use Livewire\WithPagination;
use Livewire\WithFileUploads; 
use App\Models\Role;
use App\Models\Parametre;
use App\Models\Produit;
use App\Models\Tier;
use App\Models\Utilisateur;
use App\Models\DeviseTva;
use App\Models\Entite;
use App\Models\Stock;
use App\Models\CompteBancaire;
use App\Models\factureClientEntete;
use App\Models\factureClientLigne;
use App\Models\Reglement;
use App\Models\EcritureBancaire;
use App\Models\ExpeditionClientEntete;
use App\Models\ExpeditionClientLigne;
use App\Models\ExpeditionClientLignePartiel;
use App\Models\CommandeClientEntete;
use App\Models\SessionPos;
use App\Models\Entrepot;
use App\Models\Mouvement;
use App\Models\SoldeTier;

class NouvFactureClient extends Component
{
    protected $paginationTheme = 'bootstrap';
    use WithPagination;
    use WithFileUploads;

    public $id; 
    public $ids; // important pour Update
    public $idz; // important pour creer expedition  
    public $id_cmd_clt_entete; 
    public $id_expedi_clt_entete;     
      
    public $ouvre = 0;
    public $ouverture = 0;

    #[Validate('required|max:255')]
    public $client;
    public $ids_client; 
    public $client_id; // pour l'ajout dans ligne facture
    public $telephone;

    public $reference;
    public $referenceProd;  
    public $soldeClientDispo;  
    
    #[Validate('required')]
    public $date_facturation;

    #[Validate('required')]
    public $date_echeance;

    #[Validate('required')]
    public $mode_reglement;

    #[Validate('required')]
    public $compte_bancaire;

    #[Validate('max:255')]
    public $note;    
    
    public $ref_fact; //reference facture
    public $code_commande; 
    // pour recherche client
    public $parNomTier; // Pour la recherche
    public $records;
    public $recordCount;
    public $showdiv = false;

    public $activer_fidelite;
    public $etat;
    public $etat_expedi; // etat expedition

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
    public $date;
    public $date_reglement;
    public $num_cheq_virement;
    public $emeteur;
    public $banque_cheque;
    public $commentaire;

    public $montant_recu;
    public $reste_a_percevoir;
    public $montant_reglement;
    
    public $quantite_bd;
    public $prix_vente_min;     
    
    public $devise;
    public $confirmer;
    public $confirmation; 
    public $approuver;
    
    public $id_session_pos;
    public $ref_session_pos; 
    
    public $auteur;
    public $created_at;
    public $updated_at;

    public $query;
    public $parNature;
    public $parCat; 
    public $parPage = 10;     

    public $orderField = 'id'; 
    public $orderDirection = 'ASC';    
    
    public function onDataAjout(){
        $this->reset('ouvre');
    } 
    public function onDataOuverture(){
        $this->reset('ouverture');
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
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();            
            $autoriser = $role[0]->consulter_facture;
            if($autoriser == 0){
                alert()->error('Oups Désolé', 'Vous n\'êtes pas autorisé à ouvrir cette page !!!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
                $this->redirect('/bienvenue', navigate: true);
            }
        }
        else{
            alert()->error('Oups Désolé', 'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!')->position('center')->autoClose(5000)->background('#fff')->width('460px')->padding('5px');
            $this->redirect('/bienvenue', navigate: true);
        }        
        $this->date_facturation = date('Y-m-d'); 
        $this->date_echeance = date('Y-m-d'); 
    }    
    public function render(){
    
        $dateJour = date('Y-m-d');            
        $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();
        $jourValid = $entite_mod[0]->validite_mod; 
        $mod_facturation = $entite_mod[0]->mod_facturation; 
        $soldeClient = $entite_mod[0]->solde;
        if($dateJour <= $jourValid){
            if($mod_facturation == 1){
                $title = 'Facture client | WamsCo';
                $module = 'Gestion facturation';
                $title_fils = 'Facture client';
                $lien = 'listing_fact_clt?active=7&champ=1-1&choix=1';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');      
                $dateJour = date('Y-m-d');
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');

                $this->id = request('id'); // id entete facture
                $this->ref_fact = request('ref'); // reference facture
                //     // ceci au chargement de la page
                $test_facture = factureClientEntete::where('societe',auth()->user()->societe)->where('id',$this->id)->count();    
                if($test_facture > 0){
                    $compte = factureClientEntete::where('societe',auth()->user()->societe)->where('id',$this->id)->first();               
                    $this->ids = $compte->id;
                    $this->reference = $compte->code_facture; // reference facture
                    $this->id_cmd_clt_entete = $compte->id_commande_client_entete;                    
                    $this->code_commande = $compte->code_commande;
                    $this->idz = $compte->id_commande_client_entete; // id_commande_client_entete important pour creer expedition                                    
                    $this->id_expedi_clt_entete = $compte->id_expedition_client_entete;
                    $this->client_id = $compte->id_client;
                    $this->client = $compte->nom_client;
                    $this->telephone = $compte->telephone;
                    $this->date_facturation = $compte->date_facturation;
                    $this->date_echeance = $compte->date_echeance;                    
                    $this->mode_reglement = $compte->mode_reglement; 
                    $this->compte_bancaire = $compte->id_compte_bancaire;
                    $this->note = $compte->note;
                    $this->etat = $compte->etat;
                    $this->montant_recu = $compte->montant_recu;
                    $this->etat_expedi = $compte->etat_expedi;
                    $this->id_session_pos = $compte->id_session_pos;
                    $this->ref_session_pos = $compte->ref_session_pos;
                    $this->auteur = $compte->nom_user;
                    $this->created_at = $compte->created_at;
                    $this->updated_at = $compte->updated_at;
                }  
                $tier = Tier::where('societe',auth()->user()->societe)->where('id',$this->client_id)->get(); 
                $banque = CompteBancaire :: where('societe',auth()->user()->societe)->where('etat',1)->orderBy('nom_compte_bancaire','asc')->get();  
                
                $factClient_ligne = factureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->orderBy($this->orderField, $this->orderDirection)->get();
                $factClientLigneCount = $factClient_ligne->count();

                $montantHT = $factClient_ligne->sum('montant_ht');
                $montantTTC = $factClient_ligne->sum('montant_ttc');
                $montantRemise = $factClient_ligne->sum('montant_remise');
                $montantTva = $factClient_ligne->sum('montant_tva');
                $montantPrecompte = $factClient_ligne->sum('montant_precompte');            
                            
                $reglementClient = Reglement::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->get();
                $dejaRegler = Reglement::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->sum('montant_regler');
                $reglemtCount = $reglementClient->count();
                
                $this->reste_a_percevoir = number_format($montantTTC - $dejaRegler,0,',','');               

                // Parametre
                $test_vide = Parametre ::where('societe',auth()->user()->societe)->count();
                if($test_vide > 0){                
                    $config = Parametre::where('societe',auth()->user()->societe)->limit(1)->get();
                    $id_entrepot = $config[0]->id_entrepot_fctclt;               
                }
                else{
                    $id_entrepot = 0;
                }             
                
                // pour afficher les produits en stock
                if($this->type_produit == 'Produit'){            
                    $produit_stock = DB::table('stocks')
                                    ->select('id','nom_produit','reference','id_produit','type_produit','nature_produit','categorie',DB::raw('sum(quantite) as quantites, sum(valorisation_achat_total) as valorisationAchatTotal ,sum(valeur_vente_total) as valeurVentetotal, max(limite_stock_alerte) as limite_stock_alerte ,max(updated_at) as updated_at')) // Supposons que vous voulez la dernière date
                                    ->where('societe',auth()->user()->societe)                            
                                    ->where('id_entrepot',$id_entrepot)
                                    ->where('type_produit','Produit')
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
                    $service_produit = Produit::where('societe',auth()->user()->societe)
                    ->where('type_produit','Service')
                    ->where('nom_produit','like','%'.$this->query.'%')
                    ->where('nature_produit','like','%'.$this->parNature.'%')
                    ->where('categorie','like','%'.$this->parCat.'%')
                    ->orderBy('nom_produit', 'ASC')
                    ->paginate($this->parPage);
                    $service_produitCount = $service_produit->count();
                }else{
                    
                    $produit_stock = Stock::where('societe',auth()->user()->societe)->paginate($this->parPage);
                    $produit_stockCount = 0;
                    $qteStockTotal = 0;
                    $valAchatTotal = 0;
                    $valVenteTotal = 0;
                    $service_produit = Produit::where('societe',auth()->user()->societe)->paginate($this->parPage);
                    $service_produitCount = 0;
                }
                
                $taxe = DeviseTva::where('societe',auth()->user()->societe)->orderBy('taux_tva','asc')->get(); 
                $cmdCltEntete = CommandeClientEntete::where('societe',auth()->user()->societe)->where('id',$this->id_cmd_clt_entete)->orderBy('id','desc')->get();                               

                if(substr($this->reference,0,3) == 'POS'){
                    $expCltEntete = []; // On affiche un tableau vide
                }
                else{                    
                    $expCltEntete = ExpeditionClientEntete::where('societe',auth()->user()->societe)->where('id',$this->id_expedi_clt_entete)->orderBy('id','desc')->get();              
                }  

                $page = 'factureClient'; // Pour evenement lie
                $log = LogActivityModel::where('user_societe',auth()->user()->societe)->where('id_activite', $this->ids)->where('page', $page)->limit(50)->orderBy('id','desc')->get();
                $logCount = $log->count();

                $deviseTva = DeviseTva :: where('societe',auth()->user()->societe)->limit(1)->orderBy('id','asc')->count(); 
                if($deviseTva == 0){
                    $this->devise = 'FCFA';
                }
                else{
                    $deviseTva = DeviseTva :: where('societe',auth()->user()->societe)->limit(1)->orderBy('id','asc')->get(); 
                    $this->devise = $deviseTva[0]->devise;
                }
                $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();          
                $jourValid = $entite_mod[0]->validite_mod; 
                // ceci pour trouver le nombre de jour restant avant expiration
                $nbjoursRestant = round((strtotime($jourValid) - strtotime($dateJour))/(60*60*24));

                if($this->type_produit == 'Produit'){  
                    return view('livewire.gestion-facturation.client.nouv-facture-client',compact('title_fils','module','lien','dateJour','tier','banque','cmdCltEntete','expCltEntete','factClient_ligne',
                    'factClientLigneCount','produit_stock','produit_stockCount','qteStockTotal','valAchatTotal','valVenteTotal','taxe','log','logCount',
                    'montantHT','montantTTC','montantRemise','montantTva', 'montantPrecompte','reglementClient','dejaRegler','reglemtCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
                }
                if($this->type_produit == 'Service'){  
                    return view('livewire.gestion-facturation.client.nouv-facture-client',compact('title_fils','module','lien','dateJour','tier','banque','cmdCltEntete','expCltEntete','factClient_ligne',
                    'factClientLigneCount','service_produit','service_produitCount','taxe','log','logCount',
                    'montantHT','montantTTC','montantRemise','montantTva', 'montantPrecompte','reglementClient','dejaRegler','reglemtCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
                }
                else{ 
                    return view('livewire.gestion-facturation.client.nouv-facture-client',compact('title_fils','module','lien','dateJour','tier','banque','cmdCltEntete','expCltEntete','factClient_ligne',
                    'factClientLigneCount','produit_stock','produit_stockCount','qteStockTotal','valAchatTotal','valVenteTotal','service_produit','service_produitCount','taxe','log','logCount',
                    'montantHT','montantTTC','montantRemise','montantTva', 'montantPrecompte','reglementClient','dejaRegler','reglemtCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
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
                $this->records = Tier::where('etat',1)->where('societe',auth()->user()->societe)->where('nom','like','%'.$this->client.'%')->orderBy('nom','asc')->limit(8)->get(); 
                $this->recordCount = Tier::where('etat',1)->where('societe',auth()->user()->societe)->where('nom','like','%'.$this->client.'%')->count();
                $this->showdiv = true;
            }
            else{
                $this->records = Tier::where('etat',1)->where('societe',auth()->user()->societe)->where('telephone','like','%'.$this->client.'%')->orderBy('nom','asc')->limit(8)->get(); 
                $this->recordCount = Tier::where('etat',1)->where('societe',auth()->user()->societe)->where('telephone','like','%'.$this->client.'%')->count(); 
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
        $this->telephone = $record->telephone;
        $this->ids_client = $record->id;
        $this->showdiv = false;
    }
    public function update(){
        $this->validate();        
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_facture;
            if($autoriser == 1){  
                    
                    $test_tiers = Tier ::where('societe',auth()->user()->societe)->where('id',$this->client_id)->count();
                    if($test_tiers == 0){
                        if(!empty($this->ids_client)){
                            // recupere le nom du compte bancaire via son id : $this->compte_bancaire
                            $compteBaq = CompteBancaire::where('societe',auth()->user()->societe)->where('id',$this->compte_bancaire)->first(); 
                            $nom_compte_bancaire = $compteBaq->nom_compte_bancaire;
                            
                            factureClientEntete::find($this->ids)->update(['nom_client'=>$this->client,'id_client'=>$this->ids_client,'telephone'=>$this->telephone,'reference'=>$this->reference,'date_facturation'=>$this->date_facturation,'date_echeance'=>$this->date_echeance,
                            'mode_reglement'=>$this->mode_reglement,'compte_bancaire'=>$nom_compte_bancaire,'id_compte_bancaire'=>$this->compte_bancaire,'note'=>$this->note,'societe'=>auth()->user()->societe,
                            'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                            
                            $id_activite = $this->ids;
                            $page = 'factureClient';
                            LogActivity::addToLog('Entête facture » '.$this->client.' modifiée', $id_activite, $page);   
                            $this->dispatch('alert',                    
                                title:$this->client.' modifié(e)!',
                                timer:3000,
                                icon:'success',
                                toast:true,
                                showConfirmButton: false,
                                position:'top-end',
                            );  
                            flash ('Entête facture » <strong>'.$this->client.'</strong> modifiée')->success(); 
                            $this->redirect('/nouveau_fact_clt?id='.$this->ids.'&ref='.$this->reference.'&active=7&champ=1-1&choix=1', navigate: true);
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
                            
                            $test_tier_nom = Tier ::where('societe',auth()->user()->societe)->where('id',$this->client_id)->first();
                            $nom = $test_tier_nom->nom;

                            if($nom == $this->client){ 

                                $compteBaq = CompteBancaire::where('societe',auth()->user()->societe)->where('id',$this->compte_bancaire)->first(); 
                                $nom_compte_bancaire = $compteBaq->nom_compte_bancaire;
                                
                                // client_id de la factureClientEntete
                                factureClientEntete::find($this->ids)->update(['nom_client'=>$this->client,'id_client'=>$this->client_id,'telephone'=>$this->telephone,'reference'=>$this->reference,'date_facturation'=>$this->date_facturation,'date_echeance'=>$this->date_echeance,
                                'mode_reglement'=>$this->mode_reglement,'compte_bancaire'=>$nom_compte_bancaire,'id_compte_bancaire'=>$this->compte_bancaire,'note'=>$this->note,'societe'=>auth()->user()->societe,
                                'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                                
                                $id_activite = $this->ids;
                                $page = 'factureClient';
                                LogActivity::addToLog('Entête facture » '.$this->client.' modifiée', $id_activite, $page);  
                                $this->dispatch('alert',                    
                                    title:$this->client.' modifié(e)!',
                                    timer:3000,
                                    icon:'success',
                                    toast:true,
                                    showConfirmButton: false,
                                    position:'top-end',
                                );
                                flash ('Entête facture » <strong>'.$this->client.'</strong> modifiée')->success(); 
                                $this->redirect('/nouveau_fact_clt?id='.$this->ids.'&ref='.$this->reference.'&active=7&champ=1-1&choix=1', navigate: true); 
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
                            
                            $test_tier_nom = Tier ::where('societe',auth()->user()->societe)->where('id',$this->ids_client)->first();
                            $nom = $test_tier_nom->nom;   

                            if($this->ids_client != $this->client_id){ 
                                if($nom == $this->client){ 

                                    $compteBaq = CompteBancaire::where('societe',auth()->user()->societe)->where('id',$this->compte_bancaire)->first(); 
                                    $nom_compte_bancaire = $compteBaq->nom_compte_bancaire;
                                    
                                    // ids_client de ajouterTier
                                    factureClientEntete::find($this->ids)->update(['nom_client'=>$this->client,'id_client'=>$this->ids_client,'telephone'=>$this->telephone,'reference'=>$this->reference,'date_facturation'=>$this->date_facturation,'date_echeance'=>$this->date_echeance,
                                    'mode_reglement'=>$this->mode_reglement,'compte_bancaire'=>$nom_compte_bancaire,'id_compte_bancaire'=>$this->compte_bancaire,'note'=>$this->note,'societe'=>auth()->user()->societe,
                                    'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                                    
                                    $id_activite = $this->ids;
                                    $page = 'factureClient';
                                    LogActivity::addToLog('Entête facture » '.$this->client.' modifiée', $id_activite, $page);   
                                    $this->dispatch('alert',                    
                                        title:$this->client.' modifié(e)!',
                                        timer:3000,
                                        icon:'success',
                                        toast:true,
                                        showConfirmButton: false,
                                        position:'top-end',
                                    ); 
                                    flash ('Entête facture » <strong>'.$this->client.'</strong> modifiée')->success(); 
                                    $this->redirect('/nouveau_fact_clt?id='.$this->ids.'&ref='.$this->reference.'&active=7&champ=1-1&choix=1', navigate: true);
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
    public function enregistrerNote(){  
            $this->validate([            
            'note'=>'max:255',            
        ]);    
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_facture;
            if($autoriser == 1){  
                factureClientEntete::find($this->ids)->update(['note'=>$this->note,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                $id_activite = $this->ids;
                $page = 'factureClient';
                LogActivity::addToLog('Note facture modifiée', $id_activite, $page);   
                $this->dispatch('alert',                    
                    title:'Note modifiée !',
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
        $compte = factureClientEntete::where('societe',auth()->user()->societe)->where('id',$this->ids)->first(); 
        $this->client_id = $compte->id_client;              
    }
    public function afficheLigne(int $idf){
        $this->ouverture = $idf;
        // $this->choix_produit = $idf;         
        $testChoix = Stock::where('societe',auth()->user()->societe)->where('id',$idf)->count();
        if($testChoix > 0){
            // ceci permet d'afficher la quantite entrepot origine
            $choixProd = Stock::where('societe',auth()->user()->societe)->where('id',$idf)->get();
            $this->prix_moyen_pondere_achat = $choixProd[0]->prix_moyen_pondere_achat;
            $this->prix_vente = $choixProd[0]->prix_vente_unitaire;
            $this->quantite_bd = $choixProd[0]->quantite;
            $this->id_produit = $choixProd[0]->id_produit;
            $this->nom_produit = $choixProd[0]->nom_produit;
            $this->referenceProd = $choixProd[0]->reference;
            $this->id_entrepot = $choixProd[0]->id_entrepot;
            // avoir le prix_vente_min 
            $prod = Produit::where('societe',auth()->user()->societe)->where('id',$this->id_produit)->first();
            $this->prix_vente_min = $prod->prix_vente_min;
        }
    }
    public function afficheLigneService(int $ide){
        $this->ouverture = $ide;
        $this->choix_produit = $ide;         
        $testChoix = Produit::where('societe',auth()->user()->societe)->where('id',$ide)->count();
        if($testChoix > 0){
            // ceci permet d'afficher la quantite entrepot origine
            $choixProd = Produit::where('societe',auth()->user()->societe)->where('id',$ide)->get();
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
            'date_facturation'=>'required', // important pour forcer utilisateur a remplir
            'date_echeance'=>'required',    // important pour forcer utilisateur a remplir
            'mode_reglement'=>'required',   // important pour forcer utilisateur a remplir
            // 'compte_bancaire'=>'required',  // important pour forcer utilisateur a remplir
            
        ]);    
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_facture;
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
                                    
                                    $quantite_expediee = $this->quantite; // ceci permet de gerer expedition() plus bas
                                    $reste_a_expedier = 0; 
                                    $typeProd = 'Produit';
                                    if($this->offrir == 'Non'){ 
                                        factureClientLigne::create(['code_facture'=>$this->reference,'id_facture_client_entete'=>$this->ids,'produit'=>$this->nom_produit,'id_produit'=>$this->id_produit,'reference'=>$this->referenceProd,'type_produit'=>$typeProd,'prix_achat'=>$this->prix_moyen_pondere_achat,
                                                        'prix_vente'=>$this->prix_vente,'quantite'=>$this->quantite,'quantite_expediee'=>$quantite_expediee,'reste_a_expedier'=>$reste_a_expedier,'remise'=>$this->remise,'montant_remise'=>$remise_montant,
                                                        'tva'=>$this->tva,'montant_tva'=>$tva_montant,'precompte'=>$this->precompte,'montant_precompte'=>$precompte_montant,'montant_ht'=>$montant_remiser_ht,'montant_ttc'=>$montant_ttc,'marge'=>$marge,'id_entrepot'=>$this->id_entrepot,
                                                        'nom_client'=>$this->client,'id_client'=>$this->client_id,'offrir'=>$this->offrir,'etat'=>$this->etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                     
                                    
                                        $montantHT = factureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->sum('montant_ht');
                                        $montantTTC = factureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->sum('montant_ttc');
                                        $montantRemise = factureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->sum('montant_remise');
                                        $montantTva = factureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->sum('montant_tva');
                                        $montantPrecompte = factureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->sum('montant_precompte');
                                        $marge = factureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->sum('marge');

                                        // Montant TTC en arrondi en + ou en - 
                                        factureClientEntete::find($this->ids)->update(['montant_ht'=>$montantHT,'montant_remise'=>$montantRemise,'montant_tva'=>$montantTva,'montant_precompte'=>$montantPrecompte,'montant_ttc'=>number_format($montantTTC,0,',',''),
                                        'reste_a_percevoir'=>number_format($montantTTC,0,',',''),'marge'=>$marge,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                                    }
                                    elseif($this->offrir == 'Oui'){

                                        // les totaux seront a zero                                       
                                        $remise_montant = 0;
                                        $tva_montant = 0;
                                        $precompte_montant = 0;
                                        $montant_remiser_ht = 0;
                                        $montant_ttc = 0;
                                        $marge = 0;
                                        factureClientLigne::create(['code_facture'=>$this->reference,'id_facture_client_entete'=>$this->ids,'produit'=>$this->nom_produit,'id_produit'=>$this->id_produit,'reference'=>$this->referenceProd,'type_produit'=>$typeProd,'prix_achat'=>$this->prix_moyen_pondere_achat,
                                                        'prix_vente'=>$this->prix_vente,'quantite'=>$this->quantite,'quantite_expediee'=>$quantite_expediee,'reste_a_expedier'=>$reste_a_expedier,'remise'=>$this->remise,'montant_remise'=>$remise_montant,
                                                        'tva'=>$this->tva,'montant_tva'=>$tva_montant,'precompte'=>$this->precompte,'montant_precompte'=>$precompte_montant,'montant_ht'=>$montant_remiser_ht,'montant_ttc'=>$montant_ttc,'marge'=>$marge,'id_entrepot'=>$this->id_entrepot,
                                                        'nom_client'=>$this->client,'id_client'=>$this->client_id,'offrir'=>$this->offrir,'etat'=>$this->etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                     
                                    
                                        $montantHT = factureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->sum('montant_ht');
                                        $montantTTC = factureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->sum('montant_ttc');
                                        $montantRemise = factureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->sum('montant_remise');
                                        $montantTva = factureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->sum('montant_tva');
                                        $montantPrecompte = factureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->sum('montant_precompte');
                                        $marge = factureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->sum('marge');

                                        // Montant TTC en arrondi en + ou en - 
                                        factureClientEntete::find($this->ids)->update(['montant_ht'=>$montantHT,'montant_remise'=>$montantRemise,'montant_tva'=>$montantTva,'montant_precompte'=>$montantPrecompte,'montant_ttc'=>number_format($montantTTC,0,',',''),
                                        'reste_a_percevoir'=>number_format($montantTTC,0,',',''),'marge'=>$marge,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                                    }
                                    $id_activite = $this->ids;
                                    $page = 'factureClient';
                                    LogActivity::addToLog('Produit ('.$this->nom_produit.') à facturer ajouté', $id_activite, $page);  
                                    $this->dispatch('alert',                    
                                        title:'Produit » '.$this->quantite.' <strong>'.$this->nom_produit.'</strong> ajouté(s)!',
                                        timer:5000,
                                        icon:'success',
                                        toast:true,
                                        showConfirmButton: false,
                                        position:'top-end',
                                    );  
                                    $this->resetinputFields();
                                    // $this->redirect('/nouveau_fact_clt?id='.$this->ids.'&ref='.$this->reference.'&active=6&champ=1-1&choix=2', navigate: true);
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
            'date_facturation'=>'required', // important pour forcer utilisateur a remplir
            'date_echeance'=>'required',    // important pour forcer utilisateur a remplir
            'mode_reglement'=>'required',   // important pour forcer utilisateur a remplir
            // 'compte_bancaire'=>'required',  // important pour forcer utilisateur a remplir
            
        ]);    
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_facture;
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
                                    
                                    $quantite_expediee = $this->quantite; // ceci permet de gerer expedition() plus bas
                                    $reste_a_expedier = 0;
                                    $typeProd = 'Service';
                                    if($this->offrir == 'Non'){ 
                                        factureClientLigne::create(['code_facture'=>$this->reference,'id_facture_client_entete'=>$this->ids,'produit'=>$this->nom_produit,'id_produit'=>$this->id_produit,'reference'=>$this->referenceProd,'type_produit'=>$typeProd,'prix_achat'=>$this->prix_moyen_pondere_achat,
                                                        'prix_vente'=>$this->prix_vente,'quantite'=>$this->quantite,'quantite_expediee'=>$quantite_expediee,'reste_a_expedier'=>$reste_a_expedier,'remise'=>$this->remise,'montant_remise'=>$remise_montant,
                                                        'tva'=>$this->tva,'montant_tva'=>$tva_montant,'precompte'=>$this->precompte,'montant_precompte'=>$precompte_montant,'montant_ht'=>$montant_remiser_ht,'montant_ttc'=>$montant_ttc,'marge'=>$marge,'id_entrepot'=>$this->id_entrepot,
                                                        'nom_client'=>$this->client,'id_client'=>$this->client_id,'offrir'=>$this->offrir,'etat'=>$this->etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                     
                                    
                                        $montantHT = factureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->sum('montant_ht');
                                        $montantTTC = factureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->sum('montant_ttc');
                                        $montantRemise = factureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->sum('montant_remise');
                                        $montantTva = factureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->sum('montant_tva');
                                        $montantPrecompte = factureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->sum('montant_precompte');
                                        $marge = factureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->sum('marge');

                                        // Montant TTC en arrondi en + ou en - 
                                        factureClientEntete::find($this->ids)->update(['montant_ht'=>$montantHT,'montant_remise'=>$montantRemise,'montant_tva'=>$montantTva,'montant_precompte'=>$montantPrecompte,'montant_ttc'=>number_format($montantTTC,0,',',''),
                                        'reste_a_percevoir'=>number_format($montantTTC,0,',',''),'marge'=>$marge,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                                    }
                                    elseif($this->offrir == 'Oui'){

                                        // les totaux seront a zero                                       
                                        $remise_montant = 0;
                                        $tva_montant = 0;
                                        $precompte_montant = 0;
                                        $montant_remiser_ht = 0;
                                        $montant_ttc = 0;
                                        $marge = 0;
                                        factureClientLigne::create(['code_facture'=>$this->reference,'id_facture_client_entete'=>$this->ids,'produit'=>$this->nom_produit,'id_produit'=>$this->id_produit,'reference'=>$this->referenceProd,'type_produit'=>$typeProd,'prix_achat'=>$this->prix_moyen_pondere_achat,
                                                        'prix_vente'=>$this->prix_vente,'quantite'=>$this->quantite,'quantite_expediee'=>$quantite_expediee,'reste_a_expedier'=>$reste_a_expedier,'remise'=>$this->remise,'montant_remise'=>$remise_montant,
                                                        'tva'=>$this->tva,'montant_tva'=>$tva_montant,'precompte'=>$this->precompte,'montant_precompte'=>$precompte_montant,'montant_ht'=>$montant_remiser_ht,'montant_ttc'=>$montant_ttc,'marge'=>$marge,'id_entrepot'=>$this->id_entrepot,
                                                        'nom_client'=>$this->client,'id_client'=>$this->client_id,'offrir'=>$this->offrir,'etat'=>$this->etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                     
                                    
                                        $montantHT = factureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->sum('montant_ht');
                                        $montantTTC = factureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->sum('montant_ttc');
                                        $montantRemise = factureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->sum('montant_remise');
                                        $montantTva = factureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->sum('montant_tva');
                                        $montantPrecompte = factureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->sum('montant_precompte');
                                        $marge = factureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->sum('marge');

                                        // Montant TTC en arrondi en + ou en - 
                                        factureClientEntete::find($this->ids)->update(['montant_ht'=>$montantHT,'montant_remise'=>$montantRemise,'montant_tva'=>$montantTva,'montant_precompte'=>$montantPrecompte,'montant_ttc'=>number_format($montantTTC,0,',',''),
                                        'reste_a_percevoir'=>number_format($montantTTC,0,',',''),'marge'=>$marge,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                                    }
                                    $id_activite = $this->ids;
                                    $page = 'factureClient';
                                    LogActivity::addToLog('Service ('.$this->nom_produit.') à facturer ajouté', $id_activite, $page);  
                                    $this->dispatch('alert',                    
                                        title:'Service » '.$this->quantite.' <strong>'.$this->nom_produit.'</strong> ajouté(s)!',
                                        timer:5000,
                                        icon:'success',
                                        toast:true,
                                        showConfirmButton: false,
                                        position:'top-end',
                                    );  
                                    $this->resetinputFields();
                                    // $this->redirect('/nouveau_fact_clt?id='.$this->ids.'&ref='.$this->reference.'&active=6&champ=1-1&choix=2', navigate: true);
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
        $this->redirect('/nouveau_fact_clt?id='.$this->ids.'&ref='.$this->reference.'&active=7&champ=2-1&choix=1', navigate: true);
    }
    public function confirmerDelete($id){
        $this->confirmer = $id;        
    } 
    public function supprimer($id){
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_facture;
            if($autoriser == 1){   
                if($id){
                    
                    factureClientLigne::where('id',$id)->delete();

                    $montantHT = factureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->sum('montant_ht');
                    $montantTTC = factureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->sum('montant_ttc');
                    $montantRemise = factureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->sum('montant_remise');
                    $montantTva = factureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->sum('montant_tva');
                    $montantPrecompte = factureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->sum('montant_precompte');
                    $marge = factureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->sum('marge');

                    $dejaRegler = Reglement::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->sum('montant_regler');
                    $reste_a_percevoir = $montantTTC - $dejaRegler;
                    
                    factureClientEntete::find($this->ids)->update(['montant_ht'=>number_format($montantHT,0,',',''),'montant_remise'=>$montantRemise,'montant_tva'=>$montantTva,
                                        'montant_precompte'=>$montantPrecompte,'marge'=>$marge,'montant_ttc'=>number_format($reste_a_percevoir,0,',',''),'societe'=>auth()->user()->societe,
                                        'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                    
                    
                    $id_activite = $this->ids;
                    $page = 'factureClient';
                    LogActivity::addToLog('Ligne de facture client supprimé', $id_activite, $page);
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
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_facture;
            if($autoriser == 1){
                $etat = 'Impayée';
                $statut = 0;  // tres important pour permettre a la commande de passer a une nouvelle facture au niveau de creer commande
                factureClientEntete::find($this->ids)->update(['etat'=>$etat,'statut'=>$statut,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);      
                factureClientLigne::where('id_facture_client_entete',$this->ids)->update(['etat'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                // ExpeditionClientEntete::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->update(['etat_facture'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                
                $etats = 'Validée';
                $dates = date('dmy/His');
                // $length = 2;
                // $token = bin2hex(random_bytes($length));          
                $token_ok = 'FACT-EXP/'.$dates;
                ExpeditionClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->delete();
                
                // Creer ligne expedition dans commande                
                $ligneFactClient = factureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->get(); 
                foreach($ligneFactClient as $ligneFactClients){
                    // creation et copie entete Expedition Client Ligne
                    ExpeditionClientLigne::create([ 
                        'code_expedition'=>$token_ok,
                        'code_facture'=>$ligneFactClients->code_facture,
                        'id_facture_client_entete'=>$ligneFactClients->id_facture_client_entete,
                        'nom_client'=>$ligneFactClients->nom_client,
                        'id_client'=>$ligneFactClients->id_client,
                        'produit'=>$ligneFactClients->produit,
                        'id_produit'=>$ligneFactClients->id_produit,
                        'reference'=>$ligneFactClients->reference,
                        'type_produit'=>$ligneFactClients->type_produit,                        
                        'prix_achat'=>$ligneFactClients->prix_achat,
                        'prix_vente'=>$ligneFactClients->prix_vente,
                        'quantite'=>$ligneFactClients->quantite,
                        'quantite_expediee'=>$ligneFactClients->quantite_expediee,
                        // 'quantite_expediee'=>0,
                        'reste_a_expedier'=>$ligneFactClients->reste_a_expedier,
                        // 'quantite_facturee'=>$ligneFactClients->quantite_expediee,
                        // 'reste_a_facturer'=>$ligneFactClients->reste_a_expedier, 
                        'remise'=>$ligneFactClients->remise,
                        'montant_remise'=>$ligneFactClients->montant_remise,
                        'tva'=>$ligneFactClients->tva,
                        'montant_tva'=>$ligneFactClients->montant_tva,
                        'precompte'=>$ligneFactClients->precompte,
                        'montant_precompte'=>$ligneFactClients->montant_precompte,
                        'montant_ht'=>$ligneFactClients->montant_ht,
                        'montant_ttc'=>$ligneFactClients->montant_ttc,
                        'marge'=>$ligneFactClients->marge,
                        'id_entrepot'=>$ligneFactClients->id_entrepot,
                        'offrir'=>$ligneFactClients->offrir,
                        'etat'=>$etats,                        
                        'etat_facture'=>$ligneFactClients->etat, 
                        'user_id'=>auth()->user()->id,
                        'nom_user'=>auth()->user()->name,
                        'societe'=>auth()->user()->societe]);
                }
                //  Fin     
                
                $id_activite = $this->ids;
                $page = 'factureClient';
                LogActivity::addToLog('Facture ('.$this->reference.') client validée', $id_activite, $page);
                $this->dispatch('alert',                    
                    title:'Facture sous la référence <strong>'.$this->reference.'</strong> validée!',
                    timer:53000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                );                     
                $this->redirect('/nouveau_fact_clt?id='.$this->ids.'&active=6&champ=1-1&choix=2', navigate: true);
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
        factureClientEntete::find($this->ids)->update(['etat'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);      
        factureClientLigne::where('id_facture_client_entete',$this->ids)->update(['etat'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
        ExpeditionClientEntete::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->update(['etat_facture'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
        $id_activite = $this->ids;
        $page = 'factureClient';
        LogActivity::addToLog('Facture ('.$this->reference.') client en brouillon', $id_activite, $page);
        $this->dispatch('alert',                    
            title:'Facture sous la référence <strong>'.$this->reference.'</strong> retournée au brouillon!',
            timer:53000,
            icon:'success',
            toast:true,
            showConfirmButton: false,
            position:'top-end',
        );       
        $this->redirect('/nouveau_fact_clt?id='.$this->ids.'&active=6&champ=1-1&choix=2', navigate: true);        
    } 
    public function afficheRegler(){
        $comptes = factureClientEntete::where('societe',auth()->user()->societe)->where('id',$this->ids)->first(); 
                $this->date_reglement = $comptes->date_facturation;
                $this->reference = $comptes->code_facture;
                $client_id = $comptes->id_client;
                $montant_ttc = $comptes->montant_ttc;
                $montant_recu = $comptes->montant_recu;
                $this->reste_a_percevoir = $montant_ttc - $montant_recu;

        $tiercltCount = Tier::where('societe',auth()->user()->societe)->where('id',$client_id)->count(); 
        if($tiercltCount > 0){
            $tierclt = Tier::where('societe',auth()->user()->societe)->where('id',$client_id)->first(); 
            $this->soldeClientDispo = $tierclt->solde;
        }
    } 
    public function coller(){
        $comptes = factureClientEntete::where('societe',auth()->user()->societe)->where('id',$this->ids)->first();               
                $montant_ttc = $comptes->montant_ttc;
                $montant_recu = $comptes->montant_recu;
                $this->montant_reglement = number_format($montant_ttc - $montant_recu,0,',','');               
    } 
    public function payer(){ 
        $this->validate([
            'date_reglement'=>'required|max:255',
            'mode_reglement'=>'required|max:255',
            'compte_bancaire'=>'required|max:255', //recupere id
            'num_cheq_virement'=>'max:50',
            'emeteur'=>'max:200',
            'banque_cheque'=>'max:200',
            'commentaire'=>'max:255',
            'montant_reglement'=>'required|numeric',
        ]);
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_reglement;
            if($autoriser == 1){ 
        
                    $compte = factureClientEntete::where('societe',auth()->user()->societe)->where('id',$this->ids)->first();               
                    // $this->ids = $compte->id;
                    $client_id = $compte->id_client;
                    $client = $compte->nom_client;

                    if($this->reste_a_percevoir > 0){
                        
                        if($this->montant_reglement >= $this->reste_a_percevoir){
                            
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
                            $type_paiement = 'ReglementClient';    
                            $statut = 'Confirmer';           
                            EcritureBancaire::create(['id_compte_bancaire'=>$this->compte_bancaire,'id_type_paiement'=>$this->compte_bancaire,'nom_compte_bancaire'=>$nom_compte_bancaire,'reference'=>$ref_ecritureBq,'description'=>$description,
                                            'date_operation'=>$date_operation,'date_valeur'=>$date_valeur,'type_operation'=>$this->mode_reglement,'debit'=>$debit,'credit'=>number_format($this->reste_a_percevoir,0,',',''),'solde'=>$solde,
                                            'type_paiement'=>$type_paiement,'id_facture_client_entete'=>$this->ids,'code_facture'=>$this->reference,'id_tiers'=>$client_id,'tiers'=>$client,'statut'=>$statut,
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
                            Reglement::create(['ref_reglement'=>$refReglement,'id_facture_client_entete'=>$this->ids,'code_facture'=>$this->reference,'id_client'=>$this->client_id,'nom_client'=>$this->client,'id_ecriture_bancaire'=>$dernier_id,'ecriture_bancaire'=>$ref_ecritureBq,
                                            'mode_reglement'=>$this->mode_reglement,'compte_bancaire'=>$nom_compte_bancaire,'id_compte_bancaire'=>$this->compte_bancaire,'date_reglement'=>$this->date_reglement,
                                            'num_cheq_virement'=>$this->num_cheq_virement,'emetteur_cheq_virement'=>$this->emeteur,'banque_cheq_virement'=>$this->banque_cheque,'commentaire'=>$this->commentaire,
                                            'montant_regler'=>number_format($this->reste_a_percevoir,0,',',''),'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                            $dejaRegler = Reglement::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->sum('montant_regler');                            

                            // Facture entete
                            $etat ='Payée';
                            $resteApercevoir = 0;
                            factureClientEntete::find($this->ids)->update(['montant_recu'=>number_format($dejaRegler,0,',',''),'reste_a_percevoir'=>number_format($resteApercevoir,0,',',''),
                                                'etat'=>$etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                            factureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->update(['etat'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                            ExpeditionClientEntete::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->update(['etat_facture'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                            
                            // Mise a jour Solde final
                            if($this->id_session_pos){
                                $montant_recu = factureClientEntete::where('societe',auth()->user()->societe)->where('id_session_pos',$this->id_session_pos)->sum('montant_recu');
                                $solde_initial = SessionPos::where('societe',auth()->user()->societe)->where('id',$this->id_session_pos)->sum('solde_initial');                                 
                                $solde_final = $montant_recu + $solde_initial;
                                SessionPos::where('societe',auth()->user()->societe)->where('id',$this->id_session_pos)->update(['solde_final'=>$solde_final,'solde_cloture_theorique'=>$solde_final,]);
                            }
                            // Fin Solde final

                            $id_activite = $this->ids;
                            $page = 'factureClient';
                            LogActivity::addToLog('Paiement '.$nom_compte_bancaire.' » '.$this->montant_reglement.' '.$this->devise.' facture ('.$this->reference.') ajouté', $id_activite, $page);
                            $this->dispatch('alert',                    
                                title:'Paiement ('.$this->reste_a_percevoir.') enregistré !',
                                timer:5000,
                                icon:'success',
                                toast:true,
                                showConfirmButton: false,
                                position:'top-end',
                            );  
                            $this->dispatch('fermerPayer');
                            $this->redirect('/nouveau_fact_clt?id='.$this->ids.'&ref='.$this->reference.'&active=7&champ=1-1&choix=1', navigate: true); // ceci permet d'actualiser ou mettre a jour les donnees du modal sans fermer 
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
                            $type_paiement = 'ReglementClient';    
                            $statut = 'Confirmer';           
                            EcritureBancaire::create(['id_compte_bancaire'=>$this->compte_bancaire,'id_type_paiement'=>$this->compte_bancaire,'nom_compte_bancaire'=>$nom_compte_bancaire,'reference'=>$ref_ecritureBq,'description'=>$description,
                                            'date_operation'=>$date_operation,'date_valeur'=>$date_valeur,'type_operation'=>$this->mode_reglement,'debit'=>$debit,'credit'=>number_format($this->montant_reglement,0,',',''),'solde'=>$solde,
                                            'type_paiement'=>$type_paiement,'id_facture_client_entete'=>$this->ids,'code_facture'=>$this->reference,'id_tiers'=>$client_id,'tiers'=>$client,'statut'=>$statut,
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
                            Reglement::create(['ref_reglement'=>$refReglement,'id_facture_client_entete'=>$this->ids,'code_facture'=>$this->reference,'id_client'=>$this->client_id,'nom_client'=>$this->client,'id_ecriture_bancaire'=>$dernier_id,'ecriture_bancaire'=>$ref_ecritureBq,
                                            'mode_reglement'=>$this->mode_reglement,'compte_bancaire'=>$nom_compte_bancaire,'id_compte_bancaire'=>$this->compte_bancaire,'date_reglement'=>$this->date_reglement,
                                            'num_cheq_virement'=>$this->num_cheq_virement,'emetteur_cheq_virement'=>$this->emeteur,'banque_cheq_virement'=>$this->banque_cheque,'commentaire'=>$this->commentaire,
                                            'montant_regler'=>number_format($this->montant_reglement,0,',',''),'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                                            
                            $dejaRegler = Reglement::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->sum('montant_regler');

                            // Facture entete
                            $etat ='Commencée';
                            $reste = $this->reste_a_percevoir - $this->montant_reglement;
                            factureClientEntete::find($this->ids)->update(['montant_recu'=>number_format($dejaRegler,0,',',''),'reste_a_percevoir'=>number_format($reste,0,',',''),
                                                'etat'=>$etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                            factureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->update(['etat'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                            ExpeditionClientEntete::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->update(['etat_facture'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 

                            // Mise a jour Solde final
                            if($this->id_session_pos){
                                $montant_recu = factureClientEntete::where('societe',auth()->user()->societe)->where('id_session_pos',$this->id_session_pos)->sum('montant_recu');
                                $solde_initial = SessionPos::where('societe',auth()->user()->societe)->where('id',$this->id_session_pos)->sum('solde_initial');                                 
                                $solde_final = $montant_recu + $solde_initial;
                                SessionPos::where('societe',auth()->user()->societe)->where('id',$this->id_session_pos)->update(['solde_final'=>$solde_final,'solde_cloture_theorique'=>$solde_final,]);
                            }
                            // Fin Solde final

                            $id_activite = $this->ids;
                            $page = 'factureClient';
                            LogActivity::addToLog('Paiement '.$nom_compte_bancaire.' » '.$this->montant_reglement.' '.$this->devise.' facture ('.$this->reference.') ajouté', $id_activite, $page);
                            $this->dispatch('alert',                    
                                title:'Paiement ('.$this->montant_reglement.') enregistré !',
                                timer:5000,
                                icon:'success',
                                toast:true,
                                showConfirmButton: false,
                                position:'top-end',
                            );                              
                            $this->dispatch('fermerPayer');
                            $this->redirect('/nouveau_fact_clt?id='.$this->ids.'&ref='.$this->reference.'&active=7&champ=1-1&choix=1', navigate: true); // ceci permet d'actualiser ou mettre a jour les donnees du modal sans fermer 
                        }

                    }
                    else{

                        $this->dispatch('alert',                    
                            title:'Désolé, vous ne pouvez plus ou pas effectuer de paiement ('.$this->reste_a_percevoir.' '.$this->devise.')',
                            timer:5000,
                            icon:'error',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        ); 
                    }                 
            }
            else{                 
                $this->dispatchBrowserEvent('swal', [
                    'title' => 'Vous n\'êtes pas autorisé à effectuer cette opération !!!',
                    'timer'=>3000,
                    'icon'=>'error',
                    'toast'=>false,
                    'position'=>'center'
                ]);   
            } 
        }
        else{             
            $this->dispatchBrowserEvent('swal', [
                'title' => 'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!',
                'timer'=>3000,
                'icon'=>'error',
                'toast'=>false,
                'position'=>'center'
            ]);
        }     
    }
    public function payerAvecSolde(){
        $this->validate([
            'date_reglement'=>'required|max:255',
            'mode_reglement'=>'required|max:255',
            'compte_bancaire'=>'required|max:255', //recupere id
            'num_cheq_virement'=>'max:50',
            'emeteur'=>'max:200',
            'banque_cheque'=>'max:200',
            'commentaire'=>'max:255',
            'montant_reglement'=>'required|numeric',
        ]);
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_reglement;
            if($autoriser == 1){ 
        
                    $compte = factureClientEntete::where('societe',auth()->user()->societe)->where('id',$this->ids)->first();               
                    $client_id = $compte->id_client;
                    $client = $compte->nom_client;

                    $tierclt = Tier::where('societe',auth()->user()->societe)->where('id',$client_id)->first(); 
                    $nom = $tierclt->nom;  
                    $code_tier =$tierclt->code_tier;
                    $soldeClient = $tierclt->solde;
                    $raison_sociale = $tierclt->raison_sociale;
                    $telephone = $tierclt->telephone;
                    $adresse = $tierclt->adresse;
                    $ville = $tierclt->ville;
                    $pays =$tierclt->pays;
                    $email =$tierclt->email;
                    

                    if($this->reste_a_percevoir > 0){
                        
                        if($this->montant_reglement >= $this->reste_a_percevoir){
                            
                            if($this->montant_reglement <= $soldeClient){
                                $soldeRestant = $soldeClient - $this->reste_a_percevoir;
                                Tier::where('societe',auth()->user()->societe)->where('id',$client_id)->update(['solde'=>$soldeRestant,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                                $credit = 0;
                                $designation = 'Facturation client » '.$this->reference;
                                SoldeTier::create(['id_tier'=>$client_id,'nom_tier'=>$nom,'code_tier'=>$code_tier,'raison_sociale'=>$raison_sociale,'designation'=>$designation,'debit'=>$this->montant_reglement,'credit'=>$credit,
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
                                $type_paiement = 'ReglementClient';    
                                $statut = 'Confirmer';           
                                EcritureBancaire::create(['id_compte_bancaire'=>$this->compte_bancaire,'id_type_paiement'=>$this->compte_bancaire,'nom_compte_bancaire'=>$nom_compte_bancaire,'reference'=>$ref_ecritureBq,'description'=>$description,
                                                'date_operation'=>$date_operation,'date_valeur'=>$date_valeur,'type_operation'=>$this->mode_reglement,'debit'=>$debit,'credit'=>number_format($this->reste_a_percevoir,0,',',''),'solde'=>$solde,
                                                'type_paiement'=>$type_paiement,'id_facture_client_entete'=>$this->ids,'code_facture'=>$this->reference,'id_tiers'=>$client_id,'tiers'=>$client,'statut'=>$statut,
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
                                Reglement::create(['ref_reglement'=>$refReglement,'id_facture_client_entete'=>$this->ids,'code_facture'=>$this->reference,'id_client'=>$this->client_id,'nom_client'=>$this->client,'id_ecriture_bancaire'=>$dernier_id,'ecriture_bancaire'=>$ref_ecritureBq,
                                                'mode_reglement'=>$this->mode_reglement,'compte_bancaire'=>$nom_compte_bancaire,'id_compte_bancaire'=>$this->compte_bancaire,'date_reglement'=>$this->date_reglement,
                                                'num_cheq_virement'=>$this->num_cheq_virement,'emetteur_cheq_virement'=>$this->emeteur,'banque_cheq_virement'=>$this->banque_cheque,'commentaire'=>$this->commentaire,
                                                'montant_regler'=>number_format($this->reste_a_percevoir,0,',',''),'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                                $dejaRegler = Reglement::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->sum('montant_regler');                            

                                // Facture entete
                                $etat ='Payée';
                                $resteApercevoir = 0;
                                factureClientEntete::find($this->ids)->update(['montant_recu'=>number_format($dejaRegler,0,',',''),'reste_a_percevoir'=>number_format($resteApercevoir,0,',',''),
                                                    'etat'=>$etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                                factureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->update(['etat'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                                ExpeditionClientEntete::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->update(['etat_facture'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                                
                                // Mise a jour Solde final
                                if($this->id_session_pos){
                                    $montant_recu = factureClientEntete::where('societe',auth()->user()->societe)->where('id_session_pos',$this->id_session_pos)->sum('montant_recu');
                                    $solde_initial = SessionPos::where('societe',auth()->user()->societe)->where('id',$this->id_session_pos)->sum('solde_initial');                                 
                                    $solde_final = $montant_recu + $solde_initial;
                                    SessionPos::where('societe',auth()->user()->societe)->where('id',$this->id_session_pos)->update(['solde_final'=>$solde_final,'solde_cloture_theorique'=>$solde_final,]);
                                }
                                // Fin Solde final

                                $id_activite = $this->ids;
                                $page = 'factureClient';
                                LogActivity::addToLog('Paiement avec solde '.$nom_compte_bancaire.' » '.$this->montant_reglement.' '.$this->devise.' facture ('.$this->reference.') ajouté', $id_activite, $page);
                                $this->dispatch('alert',                    
                                    title:'Paiement ('.$this->reste_a_percevoir.') enregistré !',
                                    timer:5000,
                                    icon:'success',
                                    toast:true,
                                    showConfirmButton: false,
                                    position:'top-end',
                                );  
                                $this->dispatch('fermerPayer');
                                $this->redirect('/nouveau_fact_clt?id='.$this->ids.'&ref='.$this->reference.'&active=7&champ=1-1&choix=1', navigate: true); // ceci permet d'actualiser ou mettre a jour les donnees du modal sans fermer 
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
                                Tier::where('societe',auth()->user()->societe)->where('id',$client_id)->update(['solde'=>$soldeRestant,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                                $credit = 0;
                                $designation = 'Facturation client » '.$this->reference;
                                SoldeTier::create(['id_tier'=>$client_id,'nom_tier'=>$nom,'code_tier'=>$code_tier,'raison_sociale'=>$raison_sociale,'designation'=>$designation,'debit'=>$this->montant_reglement,'credit'=>$credit,
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
                                $type_paiement = 'ReglementClient';    
                                $statut = 'Confirmer';           
                                EcritureBancaire::create(['id_compte_bancaire'=>$this->compte_bancaire,'id_type_paiement'=>$this->compte_bancaire,'nom_compte_bancaire'=>$nom_compte_bancaire,'reference'=>$ref_ecritureBq,'description'=>$description,
                                                'date_operation'=>$date_operation,'date_valeur'=>$date_valeur,'type_operation'=>$this->mode_reglement,'debit'=>$debit,'credit'=>number_format($this->montant_reglement,0,',',''),'solde'=>$solde,
                                                'type_paiement'=>$type_paiement,'id_facture_client_entete'=>$this->ids,'code_facture'=>$this->reference,'id_tiers'=>$client_id,'tiers'=>$client,'statut'=>$statut,
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
                                Reglement::create(['ref_reglement'=>$refReglement,'id_facture_client_entete'=>$this->ids,'code_facture'=>$this->reference,'id_client'=>$this->client_id,'nom_client'=>$this->client,'id_ecriture_bancaire'=>$dernier_id,'ecriture_bancaire'=>$ref_ecritureBq,
                                                'mode_reglement'=>$this->mode_reglement,'compte_bancaire'=>$nom_compte_bancaire,'id_compte_bancaire'=>$this->compte_bancaire,'date_reglement'=>$this->date_reglement,
                                                'num_cheq_virement'=>$this->num_cheq_virement,'emetteur_cheq_virement'=>$this->emeteur,'banque_cheq_virement'=>$this->banque_cheque,'commentaire'=>$this->commentaire,
                                                'montant_regler'=>number_format($this->montant_reglement,0,',',''),'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                                                
                                $dejaRegler = Reglement::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->sum('montant_regler');

                                // Facture entete
                                $etat ='Commencée';
                                $reste = $this->reste_a_percevoir - $this->montant_reglement;
                                factureClientEntete::find($this->ids)->update(['montant_recu'=>number_format($dejaRegler,0,',',''),'reste_a_percevoir'=>number_format($reste,0,',',''),
                                                    'etat'=>$etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                                factureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->update(['etat'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                                ExpeditionClientEntete::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->update(['etat_facture'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 

                                // Mise a jour Solde final
                                if($this->id_session_pos){
                                    $montant_recu = factureClientEntete::where('societe',auth()->user()->societe)->where('id_session_pos',$this->id_session_pos)->sum('montant_recu');
                                    $solde_initial = SessionPos::where('societe',auth()->user()->societe)->where('id',$this->id_session_pos)->sum('solde_initial');                                 
                                    $solde_final = $montant_recu + $solde_initial;
                                    SessionPos::where('societe',auth()->user()->societe)->where('id',$this->id_session_pos)->update(['solde_final'=>$solde_final,'solde_cloture_theorique'=>$solde_final,]);
                                }
                                // Fin Solde final

                                $id_activite = $this->ids;
                                $page = 'factureClient';
                                LogActivity::addToLog('Paiement avec solde '.$nom_compte_bancaire.' » '.$this->montant_reglement.' '.$this->devise.' facture ('.$this->reference.') ajouté', $id_activite, $page);
                                $this->dispatch('alert',                    
                                    title:'Paiement ('.$this->montant_reglement.') enregistré !',
                                    timer:5000,
                                    icon:'success',
                                    toast:true,
                                    showConfirmButton: false,
                                    position:'top-end',
                                );                              
                                $this->dispatch('fermerPayer');
                                $this->redirect('/nouveau_fact_clt?id='.$this->ids.'&ref='.$this->reference.'&active=7&champ=1-1&choix=1', navigate: true); // ceci permet d'actualiser ou mettre a jour les donnees du modal sans fermer 
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
                            title:'Désolé, vous ne pouvez plus ou pas effectuer de paiement ('.$this->reste_a_percevoir.' '.$this->devise.')',
                            timer:5000,
                            icon:'error',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        ); 
                    }                 
            }
            else{                 
                $this->dispatchBrowserEvent('swal', [
                    'title' => 'Vous n\'êtes pas autorisé à effectuer cette opération !!!',
                    'timer'=>3000,
                    'icon'=>'error',
                    'toast'=>false,
                    'position'=>'center'
                ]);   
            } 
        }
        else{             
            $this->dispatchBrowserEvent('swal', [
                'title' => 'Désolé, vous n\'avez pas de privillège, veuillez contacter un administrateur!',
                'timer'=>3000,
                'icon'=>'error',
                'toast'=>false,
                'position'=>'center'
            ]);
        }     
    }
    public function confirmationDelete($id){
        $this->confirmation = $id;        
    } 
    public function effacer(int $id, int $id_cpteBq){
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->supprimer_reglement;
            if($autoriser == 1){   
                if($id){

                    $MontantRegler = Reglement::where('societe',auth()->user()->societe)->where('id',$id)->sum('montant_regler');
                    $factClient = factureClientEntete::where('societe',auth()->user()->societe)->where('id',$this->ids)->first();
                    $montant_ttc = $factClient->montant_ttc;
                    $reste_a_percevoir = $factClient->reste_a_percevoir;
                    $montant_recu = $factClient->montant_recu;

                    $montantRecu_ok = $montant_recu - $MontantRegler;

                    if($montantRecu_ok > 0 && $montantRecu_ok < $montant_ttc){
                        $etat = 'Commencée';
                    }
                    elseif($montantRecu_ok == $montant_ttc){
                        $etat = 'Payée';
                    }
                    else{
                        $etat = 'Impayée';
                    }
                    $reste = $reste_a_percevoir + $MontantRegler;
                    factureClientEntete::find($this->ids)->update(['montant_recu'=>number_format($montantRecu_ok,0,',',''),'reste_a_percevoir'=>number_format($reste,0,',',''),
                    'etat'=>$etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                    $Regler = Reglement::where('societe',auth()->user()->societe)->where('id',$id)->first();
                    $id_regle = $Regler->id_ecriture_bancaire;
                    
                    Reglement::where('id',$id)->delete();
                    EcritureBancaire::where('id',$id_regle)->delete();

                    // ceci calcul le solde                    
                    $soldeCredit = EcritureBancaire::where('societe',auth()->user()->societe)->where('id_compte_bancaire',$id_cpteBq)->sum('credit');
                    $soldeDebit = EcritureBancaire::where('societe',auth()->user()->societe)->where('id_compte_bancaire',$id_cpteBq)->sum('debit');  
                    $solde = $soldeCredit - $soldeDebit;
                    CompteBancaire::where('societe',auth()->user()->societe)->where('id',$id_cpteBq)->update(['solde'=>$solde,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                    ExpeditionClientEntete::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->update(['etat_facture'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                    
                    $id_activite = $this->ids;
                    $page = 'factureClient';
                    LogActivity::addToLog('Ligne règlement facture client supprimé', $id_activite, $page);
                    $this->dispatch('alert',                    
                        title:'Suppression effectuée!',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );  
                    flash ('Le règlement a été supprimé!')->success();    
                    $this->redirect('/nouveau_fact_clt?id='.$this->ids.'&ref='.$this->reference.'&active=6&champ=1-1&choix=2', navigate: true);                         
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
    public function precedant(){ 
        $testPrecedant = factureClientEntete::where('societe',auth()->user()->societe)->where('id','<',$this->ids)->orderBy('id','desc')->count();
        if($testPrecedant > 0){ 
            $precedant = factureClientEntete::where('societe',auth()->user()->societe)->where('id','<',$this->ids)->orderBy('id','desc')->first();        
            $previous = $precedant->id; 
            $this->redirect('/nouveau_fact_clt?id='.$previous.'&ref='.$this->reference.'&active=7&champ=1-1&choix=1', navigate: true);              
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
        
        $testSuivant = factureClientEntete::where('societe',auth()->user()->societe)->where('id','>',$this->ids)->orderBy('id','asc')->count();
        if($testSuivant > 0){
            $suivant = factureClientEntete::where('societe',auth()->user()->societe)->where('id','>',$this->ids)->orderBy('id','asc')->first();
            $next = $suivant->id;             
            $this->redirect('/nouveau_fact_clt?id='.$next.'&ref='.$this->reference.'&active=7&champ=1-1&choix=1', navigate: true);                     
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
    // laisser ceci comme ca
    public function expedition(){ 
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_expedition;
            if($autoriser == 1){       
                $test_expedi = ExpeditionClientEntete::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->count();
                if($test_expedi == 0){            
                    $etat = 'Clôturée';
                    $etat_cmd = 'Validée';
                    $dates = date('dmy/His');
                    $length = 2;
                    $token = bin2hex(random_bytes($length));
                    $token_ok = 'EXP/'.$dates;
                    // $token_ok = 'EXP/'.$dates.'/'.$token;

                    // Creation entete ExpeditionClientEntete
                    $enteteFactClient = factureClientEntete::where('societe',auth()->user()->societe)->where('id',$this->ids)->get(); 
                    foreach($enteteFactClient as $enteteFactClients){
                        // creation et copie entete Expedition Client Entete
                        ExpeditionClientEntete::create([                        
                            'code_expedition'=>$token_ok,
                            'id_facture_client_entete'=>$enteteFactClients->id,
                            'code_facture'=>$enteteFactClients->code_facture,
                            'code_commande'=>$enteteFactClients->code_commande,
                            'id_commande_client_entete'=>$enteteFactClients->id_commande_client_entete,
                            'nom_client'=>$enteteFactClients->nom_client,
                            'id_client'=>$enteteFactClients->id_client,
                            'date_facturation'=>$enteteFactClients->date_facturation,
                            'date_echeance'=>$enteteFactClients->date_echeance,
                            'note'=>$enteteFactClients->note,
                            'montant_ht'=>$enteteFactClients->montant_ht,
                            'montant_remise'=>$enteteFactClients->montant_remise,
                            'montant_tva'=>$enteteFactClients->montant_tva,
                            'montant_precompte'=>$enteteFactClients->montant_precompte,
                            'montant_ttc'=>$enteteFactClients->montant_ttc,
                            'marge'=>$enteteFactClients->marge,
                            'montant_recu'=>$enteteFactClients->montant_recu,
                            'reste_a_percevoir'=>$enteteFactClients->reste_a_percevoir,
                            'etat'=>$etat,
                            'etat_cmd'=>$etat_cmd,                            
                            'etat_facture'=>$enteteFactClients->etat,
                            'societe'=>auth()->user()->societe,
                            'nom_user'=>auth()->user()->name,
                            'user_id'=>auth()->user()->id]);

                        $dernier_id = ExpeditionClientEntete::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id;
                        // Expedition ligne partiel
                        $test_ligPart = ExpeditionClientLignePartiel::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->count(); 
                        if($test_ligPart > 0){
                            ExpeditionClientLignePartiel::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->delete();
                        }
                        $ligneFactClient = factureClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->get(); 
                        foreach($ligneFactClient as $ligneFactClients){
                            
                            $id_exp = $ligneFactClients->id;            
                            $quantites = $ligneFactClients->quantite;            
                            $quantite_expediee = $ligneFactClients->quantite_expediee;
                            $id_produit = $ligneFactClients->id_produit;
                            $id_entrepot = $ligneFactClients->id_entrepot;
                            $id_fact_clt_entete = $ligneFactClients->id_facture_client_entete;
                            $type_produit = $ligneFactClients->type_produit;                            
                            
                            if($type_produit == 'Produit'){
                                $stockTrouver = Stock::where('societe',auth()->user()->societe)->where('id_entrepot',$id_entrepot)->where('id_produit',$id_produit)->first();
                                $nom_produit = $stockTrouver->nom_produit;                                
                                $reference = $stockTrouver->reference;
                                $qteSockFinal = $stockTrouver->quantite - $quantite_expediee;
                                $valorisation_achat_total = $stockTrouver->prix_moyen_pondere_achat * $qteSockFinal;
                                $valeur_vente_total = $stockTrouver->prix_vente_unitaire * $qteSockFinal;

                                Stock::where('societe',auth()->user()->societe)->where('id_entrepot',$id_entrepot)->where('id_produit',$id_produit)->update(['quantite'=>$qteSockFinal,'valorisation_achat_total'=>$valorisation_achat_total,'valeur_vente_total'=>$valeur_vente_total,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);   
                             } 
                           
                            factureClientEntete::where('societe',auth()->user()->societe)->where('id',$this->ids)->update(['id_expedition_client_entete'=>$dernier_id,'code_expedition'=>$token_ok,'etat_expedi'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                                                        
                            if($this->idz){
                                $exped = ExpeditionClientLigne::where('societe',auth()->user()->societe)->where('id_commande_client_entete',$this->idz)->where('id_produit',$id_produit)->first();
                                // $qte_expediBD = $exped->quantite_expediee;                                
                                $qte_expediBD = $exped ? $exped->quantite_expediee : 0; 
                            }                            
                            else{
                                $qte_expediBD = 0;
                            }                                             
                            
                            $quantite_expedieeOk = $quantite_expediee + $qte_expediBD;
                            $reste_a_expediers = $quantites - $quantite_expedieeOk; 

                            
                            if($this->idz){                          
                                ExpeditionClientLigne::where('societe',auth()->user()->societe)->where('id_commande_client_entete',$this->idz)->where('id_produit',$id_produit)->update(['quantite_expediee'=>$quantite_expedieeOk,'reste_a_expedier'=>$reste_a_expediers,
                                                                                                                                                                                         'etat'=>$etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,
                                                                                                                                                                                         'user_id'=>auth()->user()->id]);   
                            }
                            elseif($this->ids){ 
                                
                                ExpeditionClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->where('id_produit',$id_produit)->update(['quantite_expediee'=>$quantite_expedieeOk,'reste_a_expedier'=>$reste_a_expediers,
                                                                                                                                                                                        'etat'=>$etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,
                                                                                                                                                                                        'user_id'=>auth()->user()->id]);   
                            }

                            if($type_produit == 'Produit'){
                                $Entrepo = Entrepot::where('societe',auth()->user()->societe)->where('id',$id_entrepot)->first();                    
                                $nom_entrepot = $Entrepo->nom; 
                            }

                            // Verifier l'etat et valider (Clôturée ou Partiel)
                            if($this->idz){
                                $charge = ExpeditionClientLigne::where('societe',auth()->user()->societe)->where('id_commande_client_entete',$this->idz)->get();
                                $QteCmderTotal = $charge->sum('quantite');
                                $QteExpedieeTotal = $charge->sum('quantite_expediee');
                                if($QteCmderTotal == $QteExpedieeTotal){
                                    $etat = 'Clôturée'; 
                                    CommandeClientEntete::where('societe',auth()->user()->societe)->where('id',$this->idz)->update(['etat_expedi'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                                }
                                else{
                                    $etat = 'Partiel'; 
                                    CommandeClientEntete::where('societe',auth()->user()->societe)->where('id',$this->idz)->update(['etat_expedi'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                                }
                            }
                            elseif($this->ids){
                                $charge = ExpeditionClientLigne::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->get();
                                $QteCmderTotal = $charge->sum('quantite');
                                $QteExpedieeTotal = $charge->sum('quantite_expediee');
                                if($QteCmderTotal == $QteExpedieeTotal){
                                    $etat = 'Clôturée'; 
                                    CommandeClientEntete::where('societe',auth()->user()->societe)->where('id',$this->idz)->update(['etat_expedi'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                                }
                                else{
                                    $etat = 'Partiel'; 
                                    CommandeClientEntete::where('societe',auth()->user()->societe)->where('id',$this->idz)->update(['etat_expedi'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                                }
                            }                            
                            // Fin


                            $libele_mouvement = 'Expédition';           
                            $code_mouvement = date('YmdHis');
                            $statut = 'EXP';
                            
                            if($type_produit == 'Produit'){
                                Mouvement::create(['id_entrepot'=>$id_entrepot,'nom_produit'=>$nom_produit,'id_produit'=>$id_produit,'reference'=>$reference,'quantite'=>-$quantite_expediee,'libele_mouvement'=>$libele_mouvement,
                                'code_mouvement'=>$code_mouvement,'statut'=>$statut,'origine'=>$token_ok,'id_expedition'=>$dernier_id,'entrepot'=>$nom_entrepot,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                            }                         
                            // creation et copie entete Expedition Client Ligne
                            ExpeditionClientLignePartiel::create([
                                'id_expedition_client_entete'=>$dernier_id, 
                                'code_expedition'=>$token_ok,                    
                                'code_facture'=>$ligneFactClients->code_facture,
                                'id_facture_client_entete'=>$ligneFactClients->id_facture_client_entete,
                                
                                // ceci vient de enteteFactClients
                                'code_commande'=>$enteteFactClients->code_commande,
                                'id_commande_client_entete'=>$enteteFactClients->id_commande_client_entete,

                                'produit'=>$ligneFactClients->produit,
                                'id_produit'=>$ligneFactClients->id_produit,
                                'reference'=>$ligneFactClients->reference,
                                'type_produit'=>$ligneFactClients->type_produit,
                                'prix_achat'=>$ligneFactClients->prix_achat,
                                'prix_vente'=>$ligneFactClients->prix_vente,
                                'quantite'=>$ligneFactClients->quantite, 
                                'quantite_total_expediee'=>$quantite_expedieeOk, // quantite total expediee
                                'quantite_expediee'=>$quantite_expedieeOk,
                                'reste_a_expedier'=>$reste_a_expediers,
                                'remise'=>$ligneFactClients->remise,
                                'montant_remise'=>$ligneFactClients->montant_remise,
                                'tva'=>$ligneFactClients->tva,
                                'montant_tva'=>$ligneFactClients->montant_tva,
                                'precompte'=>$ligneFactClients->precompte,
                                'montant_precompte'=>$ligneFactClients->montant_precompte,
                                'montant_ht'=>$ligneFactClients->montant_ht,
                                'montant_ttc'=>$ligneFactClients->montant_ttc,
                                'marge'=>$ligneFactClients->marge,
                                'id_entrepot'=>$ligneFactClients->id_entrepot,
                                'nom_client'=>$ligneFactClients->nom_client,
                                'id_client'=>$ligneFactClients->id_client,
                                'offrir'=>$ligneFactClients->offrir,
                                'etat'=>$etat,
                                'etat_facture'=>$ligneFactClients->etat, 
                                'user_id'=>auth()->user()->id,
                                'nom_user'=>auth()->user()->name,
                                'societe'=>auth()->user()->societe]);
                        } 
                    }
                    $id_activite = $dernier_id;
                    $page = 'ExpeditionClient';
                    LogActivity::addToLog('Expédition cloturée', $id_activite, $page); 
                    $this->dispatch('alert',                    
                        title:'Expédition effectuée et cloturée avec succes!',
                        timer:5000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );
                    $this->redirect('/detail_expedition_clt?id='.$dernier_id.'&ref='.$token_ok.'&active=6&champ=1-1&choix=3', navigate: true);
                }
                else{
                    $this->dispatch('alert',                    
                        title:'Désolé, cette expédition a été cloturée!',
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
    public function confirmerEcraser($id){  
        $this->approuver = $id;      
    } 
    public function ecraser(){       
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->supprimer_facture;
            if($autoriser == 1){ 
                $reglementClient = Reglement::where('societe',auth()->user()->societe)->where('id_facture_client_entete',$this->ids)->count();  
                if($reglementClient == 0){                  
                        // suppression definitive et redirection
                        $page = 'factureClient';
                        $vide = '';
                        $nulle = NULL;
                        ExpeditionClientEntete::where('societe',auth()->user()->societe)->where('id',$this->id_expedi_clt_entete)->update(['code_facture'=>$vide,'id_facture_client_entete'=>$nulle,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                     
                        $VerifiCompte = CommandeClientEntete::where('societe',auth()->user()->societe)->where('id',$this->idz)->count();               
                        if($VerifiCompte > 0){
                            $compte = CommandeClientEntete::where('societe',auth()->user()->societe)->where('id',$this->idz)->first();               
                            $nbre_facture = $compte->nbre_facture; 
                            $NbrefactClt = $nbre_facture - 1;
                        }
                        else{
                            $NbrefactClt = 0;
                        }
                        CommandeClientEntete::where('societe',auth()->user()->societe)->where('id',$this->idz)->update(['nbre_facture'=>$NbrefactClt,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 

                        ExpeditionClientLigne::where('id_facture_client_entete',$this->ids)->delete(); 
                        factureClientEntete::where('id',$this->ids)->delete(); 
                        factureClientLigne::where('id_facture_client_entete',$this->ids)->delete();
                        LogActivityModel::where('id_activite',$this->ids)->where('page',$page)->delete();

                        $id_activite = $this->ids;
                        LogActivity::addToLog('Facture client supprimée définitivement', $id_activite, $page); 
                        $this->dispatch('alert',                    
                            title:'Facture supprimée avec succes!',
                            timer:5000,
                            icon:'success',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        );                         
                        flash ('La <strong>facture client</strong> a été supprimée!')->success(); 
                        $this->redirect('/listing_fact_clt?active=7&champ=1-1&choix=1', navigate: true);
                }
                else{
                    $this->dispatch('alert',                    
                        title:'Désolé, veuillez détacher le(s) paiement(s) avant de supprimer!',
                        timer:6000,
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
    public function detailCmd(int $id, $codeFact_cmd){
        // ceci au chargement de la page
        $test_facture = CommandeClientEntete::where('societe',auth()->user()->societe)->where('id',$id)->count();    
        if($test_facture > 0){
            $compte = CommandeClientEntete::where('societe',auth()->user()->societe)->where('id',$id)->first();               
            $this->ids = $compte->id;           
            $this->reference = $compte->code_commande; // reference commande
            $this->redirect('/nouveau_cmd_clt?id='.$id.'&ref='.$this->reference.'&active=6&champ=1-1&choix=2', navigate: true);
        }  
        else{
            $this->dispatch('alert',                    
            title:'Désolé, cette commande n\'existe pas!',
                timer:5000,
                icon:'warning',
                toast:true,
                showConfirmButton: false,
                position:'top-end',
            );  
            flash ('Désolé, cette commande <strong>('.$codeFact_cmd.')</strong> n\'existe pas!')->error();
            $this->redirect('/listing_cmd_clt?active=6&champ=1-1&choix=2', navigate: true);
        }
    }
}
