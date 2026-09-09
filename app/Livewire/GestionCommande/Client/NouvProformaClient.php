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
// use App\Models\Entrepot;
use App\Models\Parametre;
use App\Models\Produit;
use App\Models\Tier;
use App\Models\Utilisateur;
use App\Models\DeviseTva;
use App\Models\Entite;
use App\Models\Stock;
use App\Models\CompteBancaire;
use App\Models\ProformaClientEntete;
use App\Models\ProformaClientLigne;
use App\Models\Reglement;
use App\Models\EcritureBancaire;
use App\Models\ExpeditionClientEntete;
use App\Models\ExpeditionClientLigne;
use App\Models\CommandeClientEntete;
use App\Models\CommandeClientLigne;

class NouvProformaClient extends Component
{
    protected $paginationTheme = 'bootstrap';
    use WithPagination;
    use WithFileUploads;

    public $id; 
    public $ids; // important pour Update
    public $idp; // important pour creer expedition
    
    public $ouvre = 0;
    public $ouverture = 0;

    #[Validate('required|max:255')]
    public $client;
    public $ids_client; 
    public $client_id; // pour l'ajout dans ligne facture
    public $telephone;

    public $reference;
    public $referenceProd;

    #[Validate('required')]
    public $date_proforma;

    #[Validate('required')]
    public $date_livraison;

    #[Validate('required')]
    public $mode_reglement;

    #[Validate('max:255')]
    public $condition_reglement;

    #[Validate('max:255')]
    public $note;

    public $autoriser; // pour gerer les marges   
    public $ref_cmd; //reference commande

    // pour recherche client
    public $parNomTier; // Pour la recherche
    public $records;
    public $recordCount;
    public $showdiv = false;

    public $activer_fidelite;
    public $etat;
    public $etat_cmd; // etat expedition
    public $code_commande;
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
                $title = 'Proforma client | WamsCo';
                $module = 'Gestion commande';
                $title_fils = 'Proforma client';
                $lien = 'profoma?active=6&champ=1-1&choix=1';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');      
                $dateJour = date('Y-m-d');
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');

                $this->id = request('id'); // id entete facture
                $this->ref_cmd = request('ref'); // reference facture
                //     // ceci au chargement de la page
                $test_facture = ProformaClientEntete::where('societe_id',auth()->user()->societe_id)->where('id',$this->id)->count();    
                if($test_facture > 0){
                    $compte = ProformaClientEntete::where('societe_id',auth()->user()->societe_id)->where('id',$this->id)->first();               
                    $this->ids = $compte->id;
                    $this->idp = $compte->id_commande_client_entete; // id_commande_client_entete important  
                    $this->client_id = $compte->id_client;
                    $this->client = $compte->nom_client;
                    $this->telephone = $compte->telephone; 
                    $this->reference = $compte->code_proforma; // reference proforma
                    $this->code_commande = $compte->code_commande; // code_facture                
                    $this->date_proforma = $compte->date_proforma;
                    $this->date_livraison = $compte->date_livraison;                    
                    $this->mode_reglement = $compte->mode_reglement; 
                    $this->condition_reglement = $compte->condition_reglement;
                    $this->note = $compte->note;
                    $this->etat = $compte->etat;
                    $this->montant_recu = $compte->montant_recu;
                    $this->etat_cmd = $compte->etat_cmd;
                    $this->auteur = $compte->nom_user;
                    $this->created_at = $compte->created_at;
                    $this->updated_at = $compte->updated_at;                    
                }              
                       
                $tier = Tier::where('societe_id',auth()->user()->societe_id)->where('id',$this->client_id)->get();   

                $profClient_ligne = ProformaClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_proforma_client_entete',$this->ids)->orderBy($this->orderField, $this->orderDirection)->get();
                $factClientLigneCount = $profClient_ligne->count();

                $montantHT = $profClient_ligne->sum('montant_ht');
                $montantTTC = $profClient_ligne->sum('montant_ttc');
                $montantRemise = $profClient_ligne->sum('montant_remise');
                $montantTva = $profClient_ligne->sum('montant_tva');
                $montantPrecompte = $profClient_ligne->sum('montant_precompte');
                $montantMarge = $profClient_ligne->sum('marge');
                $prixAchat = $profClient_ligne->sum('prix_achat');
                $prixVente = $profClient_ligne->sum('prix_vente');
                // $prixRevient = $prixVente - $prixAchat;
                $prixRevient = $montantHT - $montantMarge; // important           

                // Parametre
                $test_vide = Parametre ::where('societe_id',auth()->user()->societe_id)->count();
                if($test_vide > 0){                
                    $config = Parametre::where('societe_id',auth()->user()->societe_id)->limit(1)->get();
                    $id_entrepot = $config[0]->id_entrepot_fctclt;               
                }
                else{
                    $id_entrepot = 0;
                }             

                $produit = Stock::where('societe_id',auth()->user()->societe_id)->where('id_entrepot',$id_entrepot)->get();
                $cmdCltEntete = CommandeClientEntete::where('societe_id',auth()->user()->societe_id)->where('id',$this->idp)->orderBy('id','desc')->get(); 

                $page = 'ProformaClient'; // Pour evenement lie
                $log = LogActivityModel::where('user_societe',auth()->user()->societe)->where('id_activite', $this->ids)->where('page', $page)->limit(50)->orderBy('id','desc')->get();
                $logCount = $log->count();

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
                }
                else{
                    
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
                    return view('livewire.gestion-commande.client.nouv-proforma-client',compact('title_fils','module','lien','dateJour','tier','profClient_ligne','factClientLigneCount','produit_stock','produit_stockCount','qteStockTotal','valAchatTotal','valVenteTotal','cmdCltEntete','log','logCount',
                            'taxe','montantHT','montantTTC','montantRemise','montantTva','montantPrecompte','montantMarge','prixVente','prixRevient'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
                }
                if($this->type_produit == 'Service'){ 
                    return view('livewire.gestion-commande.client.nouv-proforma-client',compact('title_fils','module','lien','dateJour','tier','profClient_ligne','factClientLigneCount','service_produit','service_produitCount','cmdCltEntete','log','logCount',
                            'taxe','montantHT','montantTTC','montantRemise','montantTva','montantPrecompte','montantMarge','prixVente','prixRevient'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
                }
                else{ 
                    return view('livewire.gestion-commande.client.nouv-proforma-client',compact('title_fils','module','lien','dateJour','tier','profClient_ligne','factClientLigneCount','produit_stock','produit_stockCount','qteStockTotal','valAchatTotal','valVenteTotal','service_produit','service_produitCount','cmdCltEntete','log','logCount',
                            'taxe','montantHT','montantTTC','montantRemise','montantTva','montantPrecompte','montantMarge','prixVente','prixRevient'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
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
                                                       
                            ProformaClientEntete::find($this->ids)->update(['nom_client'=>$this->client,'id_client'=>$this->ids_client,'telephone'=>$this->telephone,'reference'=>$this->reference,'date_proforma'=>$this->date_proforma,'date_livraison'=>$this->date_livraison,
                            'mode_reglement'=>$this->mode_reglement,'condition_reglement'=>$this->condition_reglement,'note'=>$this->note,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,
                            'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                            
                            $id_activite = $this->ids;
                            $page = 'ProformaClient';
                            LogActivity::addToLog('Entête proforma » '.$this->client.' modifié', $id_activite, $page);  
                            $this->dispatch('alert',                    
                                title:$this->client.' modifié(e)!',
                                timer:3000,
                                icon:'success',
                                toast:true,
                                showConfirmButton: false,
                                position:'top-end',
                            ); 
                            flash ('Entête proforma » <strong>'.$this->client.'</strong> modifié')->success(); 
                            $this->redirect('/nouveau_prof_clt?id='.$this->ids.'&ref='.$this->reference.'&active=6&champ=1-1&choix=1', navigate: true);  
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
                                
                                // client_id de la ProformaClientEntete
                                ProformaClientEntete::find($this->ids)->update(['nom_client'=>$this->client,'id_client'=>$this->client_id,'telephone'=>$this->telephone,'reference'=>$this->reference,'date_proforma'=>$this->date_proforma,'date_livraison'=>$this->date_livraison,
                                'mode_reglement'=>$this->mode_reglement,'condition_reglement'=>$this->condition_reglement,'note'=>$this->note,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,
                                'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                                
                                $id_activite = $this->ids;
                                $page = 'ProformaClient';
                                LogActivity::addToLog('Entête proforma » '.$this->client.' modifié', $id_activite, $page);  
                                $this->dispatch('alert',                    
                                    title:$this->client.' modifié(e)!',
                                    timer:3000,
                                    icon:'success',
                                    toast:true,
                                    showConfirmButton: false,
                                    position:'top-end',
                                ); 
                                flash ('Entête proforma » <strong>'.$this->client.'</strong> modifié')->success(); 
                                $this->redirect('/nouveau_prof_clt?id='.$this->ids.'&ref='.$this->reference.'&active=6&champ=1-1&choix=1', navigate: true); 
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
                                    ProformaClientEntete::find($this->ids)->update(['nom_client'=>$this->client,'id_client'=>$this->ids_client,'telephone'=>$this->telephone,'reference'=>$this->reference,'date_proforma'=>$this->date_proforma,'date_livraison'=>$this->date_livraison,
                                    'mode_reglement'=>$this->mode_reglement,'condition_reglement'=>$this->condition_reglement,'note'=>$this->note,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,
                                    'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                                    
                                    $id_activite = $this->ids;
                                    $page = 'ProformaClient';
                                    LogActivity::addToLog('Entête proforma » '.$this->client.' modifié', $id_activite, $page);  
                                    $this->dispatch('alert',                    
                                        title:$this->client.' modifié(e)!',
                                        timer:3000,
                                        icon:'success',
                                        toast:true,
                                        showConfirmButton: false,
                                        position:'top-end',
                                    ); 
                                    flash ('Entête proforma » <strong>'.$this->client.'</strong> modifié')->success(); 
                                    $this->redirect('/nouveau_prof_clt?id='.$this->ids.'&ref='.$this->reference.'&active=6&champ=1-1&choix=1', navigate: true); 
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
        $compte = ProformaClientEntete::where('societe_id',auth()->user()->societe_id)->where('id',$this->ids)->first(); 
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
            'date_proforma'=>'required', // important pour forcer utilisateur a remplir
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
                            $typeProd = 'Produit';
                            if($this->offrir == 'Non'){ 
                                ProformaClientLigne::create(['code_proforma'=>$this->reference,'id_proforma_client_entete'=>$this->ids,'produit'=>$this->nom_produit,'id_produit'=>$this->id_produit,'reference'=>$this->referenceProd,'type_produit'=>$typeProd,'prix_achat'=>$this->prix_moyen_pondere_achat,
                                                'prix_vente'=>$this->prix_vente,'quantite'=>$this->quantite,'quantite_expediee'=>$quantite_expediee,'reste_a_expedier'=>$this->quantite,'remise'=>$this->remise,'montant_remise'=>$remise_montant,
                                                'tva'=>$this->tva,'montant_tva'=>$tva_montant,'precompte'=>$this->precompte,'montant_precompte'=>$precompte_montant,'montant_ht'=>$montant_remiser_ht,'montant_ttc'=>$montant_ttc,'marge'=>$marge,'id_entrepot'=>$this->id_entrepot,
                                                'nom_client'=>$this->client,'id_client'=>$this->client_id,'offrir'=>$this->offrir,'etat'=>$this->etat,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                     
                            
                                $montantHT = ProformaClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_proforma_client_entete',$this->ids)->sum('montant_ht');
                                $montantTTC = ProformaClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_proforma_client_entete',$this->ids)->sum('montant_ttc');
                                $montantRemise = ProformaClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_proforma_client_entete',$this->ids)->sum('montant_remise');
                                $montantTva = ProformaClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_proforma_client_entete',$this->ids)->sum('montant_tva');
                                $montantPrecompte = ProformaClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_proforma_client_entete',$this->ids)->sum('montant_precompte');
                                $marge = ProformaClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_proforma_client_entete',$this->ids)->sum('marge');

                                // Montant TTC en arrondi en + ou en - 
                                ProformaClientEntete::find($this->ids)->update(['montant_ht'=>$montantHT,'montant_remise'=>$montantRemise,'montant_tva'=>$montantTva,'montant_precompte'=>$montantPrecompte,'montant_ttc'=>number_format($montantTTC,0,',',''),
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
                                ProformaClientLigne::create(['code_proforma'=>$this->reference,'id_proforma_client_entete'=>$this->ids,'produit'=>$this->nom_produit,'id_produit'=>$this->id_produit,'reference'=>$this->referenceProd,'type_produit'=>$typeProd,'prix_achat'=>$this->prix_moyen_pondere_achat,
                                                'prix_vente'=>$this->prix_vente,'quantite'=>$this->quantite,'quantite_expediee'=>$quantite_expediee,'reste_a_expedier'=>$this->quantite,'remise'=>$this->remise,'montant_remise'=>$remise_montant,
                                                'tva'=>$this->tva,'montant_tva'=>$tva_montant,'precompte'=>$this->precompte,'montant_precompte'=>$precompte_montant,'montant_ht'=>$montant_remiser_ht,'montant_ttc'=>$montant_ttc,'marge'=>$marge,'id_entrepot'=>$this->id_entrepot,
                                                'nom_client'=>$this->client,'id_client'=>$this->client_id,'offrir'=>$this->offrir,'etat'=>$this->etat,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                     
                            
                                $montantHT = ProformaClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_proforma_client_entete',$this->ids)->sum('montant_ht');
                                $montantTTC = ProformaClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_proforma_client_entete',$this->ids)->sum('montant_ttc');
                                $montantRemise = ProformaClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_proforma_client_entete',$this->ids)->sum('montant_remise');
                                $montantTva = ProformaClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_proforma_client_entete',$this->ids)->sum('montant_tva');
                                $montantPrecompte = ProformaClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_proforma_client_entete',$this->ids)->sum('montant_precompte');
                                $marge = ProformaClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_proforma_client_entete',$this->ids)->sum('marge');

                                // Montant TTC en arrondi en + ou en - 
                                ProformaClientEntete::find($this->ids)->update(['montant_ht'=>$montantHT,'montant_remise'=>$montantRemise,'montant_tva'=>$montantTva,'montant_precompte'=>$montantPrecompte,'montant_ttc'=>number_format($montantTTC,0,',',''),
                                                    'marge'=>$marge,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                            }
                            $id_activite = $this->ids;
                            $page = 'ProformaClient';
                            LogActivity::addToLog('Produit ('.$this->nom_produit.') à commander ajouté', $id_activite, $page); 
                            $this->dispatch('alert',                    
                                title:'Produit ajouté !',
                                timer:3000,
                                icon:'success',
                                toast:true,
                                showConfirmButton: false,
                                position:'top-end',
                            );  
                            $this->resetinputFields();
                            // $this->redirect('/nouveau_prof_clt?id='.$this->ids.'&ref='.$this->reference.'&active=6&champ=1-1&choix=1', navigate: true);
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
            'date_proforma'=>'required', // important pour forcer utilisateur a remplir
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
                                ProformaClientLigne::create(['code_proforma'=>$this->reference,'id_proforma_client_entete'=>$this->ids,'produit'=>$this->nom_produit,'id_produit'=>$this->id_produit,'reference'=>$this->referenceProd,'type_produit'=>$typeProd,'prix_achat'=>$this->prix_moyen_pondere_achat,
                                                'prix_vente'=>$this->prix_vente,'quantite'=>$this->quantite,'quantite_expediee'=>$quantite_expediee,'reste_a_expedier'=>$this->quantite,'remise'=>$this->remise,'montant_remise'=>$remise_montant,
                                                'tva'=>$this->tva,'montant_tva'=>$tva_montant,'precompte'=>$this->precompte,'montant_precompte'=>$precompte_montant,'montant_ht'=>$montant_remiser_ht,'montant_ttc'=>$montant_ttc,'marge'=>$marge,'id_entrepot'=>$this->id_entrepot,
                                                'nom_client'=>$this->client,'id_client'=>$this->client_id,'offrir'=>$this->offrir,'etat'=>$this->etat,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                     
                            
                                $montantHT = ProformaClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_proforma_client_entete',$this->ids)->sum('montant_ht');
                                $montantTTC = ProformaClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_proforma_client_entete',$this->ids)->sum('montant_ttc');
                                $montantRemise = ProformaClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_proforma_client_entete',$this->ids)->sum('montant_remise');
                                $montantTva = ProformaClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_proforma_client_entete',$this->ids)->sum('montant_tva');
                                $montantPrecompte = ProformaClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_proforma_client_entete',$this->ids)->sum('montant_precompte');
                                $marge = ProformaClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_proforma_client_entete',$this->ids)->sum('marge');

                                // Montant TTC en arrondi en + ou en - 
                                ProformaClientEntete::find($this->ids)->update(['montant_ht'=>$montantHT,'montant_remise'=>$montantRemise,'montant_tva'=>$montantTva,'montant_precompte'=>$montantPrecompte,'montant_ttc'=>number_format($montantTTC,0,',',''),
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
                                ProformaClientLigne::create(['code_proforma'=>$this->reference,'id_proforma_client_entete'=>$this->ids,'produit'=>$this->nom_produit,'id_produit'=>$this->id_produit,'reference'=>$this->referenceProd,'type_produit'=>$typeProd,'prix_achat'=>$this->prix_moyen_pondere_achat,
                                                'prix_vente'=>$this->prix_vente,'quantite'=>$this->quantite,'quantite_expediee'=>$quantite_expediee,'reste_a_expedier'=>$this->quantite,'remise'=>$this->remise,'montant_remise'=>$remise_montant,
                                                'tva'=>$this->tva,'montant_tva'=>$tva_montant,'precompte'=>$this->precompte,'montant_precompte'=>$precompte_montant,'montant_ht'=>$montant_remiser_ht,'montant_ttc'=>$montant_ttc,'marge'=>$marge,'id_entrepot'=>$this->id_entrepot,
                                                'nom_client'=>$this->client,'id_client'=>$this->client_id,'offrir'=>$this->offrir,'etat'=>$this->etat,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                     
                            
                                $montantHT = ProformaClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_proforma_client_entete',$this->ids)->sum('montant_ht');
                                $montantTTC = ProformaClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_proforma_client_entete',$this->ids)->sum('montant_ttc');
                                $montantRemise = ProformaClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_proforma_client_entete',$this->ids)->sum('montant_remise');
                                $montantTva = ProformaClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_proforma_client_entete',$this->ids)->sum('montant_tva');
                                $montantPrecompte = ProformaClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_proforma_client_entete',$this->ids)->sum('montant_precompte');
                                $marge = ProformaClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_proforma_client_entete',$this->ids)->sum('marge');

                                // Montant TTC en arrondi en + ou en - 
                                ProformaClientEntete::find($this->ids)->update(['montant_ht'=>$montantHT,'montant_remise'=>$montantRemise,'montant_tva'=>$montantTva,'montant_precompte'=>$montantPrecompte,'montant_ttc'=>number_format($montantTTC,0,',',''),
                                                    'marge'=>$marge,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                            }
                            $id_activite = $this->ids;
                            $page = 'ProformaClient';
                            LogActivity::addToLog('Service ('.$this->nom_produit.') à commander ajouté', $id_activite, $page); 
                            $this->dispatch('alert',                    
                                title:'Service ajouté !',
                                timer:3000,
                                icon:'success',
                                toast:true,
                                showConfirmButton: false,
                                position:'top-end',
                            );  
                            $this->resetinputFields();
                            // $this->redirect('/nouveau_prof_clt?id='.$this->ids.'&ref='.$this->reference.'&active=6&champ=1-1&choix=1', navigate: true);
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
        $this->redirect('/nouveau_prof_clt?id='.$this->ids.'&ref='.$this->reference.'&active=6&champ=1-1&choix=1', navigate: true);
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
                    
                    ProformaClientLigne::where('id',$id)->delete();

                    $montantHT = ProformaClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_proforma_client_entete',$this->ids)->sum('montant_ht');
                    $montantTTC = ProformaClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_proforma_client_entete',$this->ids)->sum('montant_ttc');
                    $montantRemise = ProformaClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_proforma_client_entete',$this->ids)->sum('montant_remise');
                    $montantTva = ProformaClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_proforma_client_entete',$this->ids)->sum('montant_tva');
                    $montantPrecompte = ProformaClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_proforma_client_entete',$this->ids)->sum('montant_precompte');
                    $marge = ProformaClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_proforma_client_entete',$this->ids)->sum('marge');
                                        
                    ProformaClientEntete::find($this->ids)->update(['montant_ht'=>number_format($montantHT,0,',',''),'montant_remise'=>$montantRemise,'montant_tva'=>$montantTva,
                                        'montant_precompte'=>$montantPrecompte,'marge'=>$marge,'societe'=>auth()->user()->societe,'societe_id'=>auth()->user()->societe_id,
                                        'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                    
                    
                    $id_activite = $this->ids;
                    $page = 'ProformaClient';
                    LogActivity::addToLog('Ligne de commande Proforma client supprimé', $id_activite, $page);
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
                ProformaClientEntete::find($this->ids)->update(['etat'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);      
                ProformaClientLigne::where('id_proforma_client_entete',$this->ids)->update(['etat'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                $id_activite = $this->ids;
                $page = 'ProformaClient';
                LogActivity::addToLog('Proforma client validée', $id_activite, $page);
                
                $this->dispatch('alert',                    
                    title:'Proforma sous la référence <strong>'.$this->reference.'</strong> validée!',
                    timer:3000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                );                     
                $this->redirect('/nouveau_prof_clt?id='.$this->ids.'&active=6&champ=1-1&choix=1', navigate: true);  
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
        ProformaClientEntete::find($this->ids)->update(['etat'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);      
        ProformaClientLigne::where('id_proforma_client_entete',$this->ids)->update(['etat'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
        $id_activite = $this->ids;
        $page = 'ProformaClient';
        LogActivity::addToLog('Proforma client en brouillon', $id_activite, $page);
        $this->dispatch('alert',                    
            title:'Proforma sous la référence <strong>'.$this->reference.'</strong> retournée au brouillon!',
            timer:3000,
            icon:'success',
            toast:true,
            showConfirmButton: false,
            position:'top-end',
        );       
        $this->redirect('/nouveau_prof_clt?id='.$this->ids.'&active=6&champ=1-1&choix=1', navigate: true);        
    } 
    public function precedant(){ 
        $testPrecedant = ProformaClientEntete::where('societe_id',auth()->user()->societe_id)->where('id','<',$this->ids)->orderBy('id','desc')->count();
        if($testPrecedant > 0){ 
            $precedant = ProformaClientEntete::where('societe_id',auth()->user()->societe_id)->where('id','<',$this->ids)->orderBy('id','desc')->first();        
            $previous = $precedant->id; 
            $this->redirect('/nouveau_prof_clt?id='.$previous.'&ref='.$this->reference.'&active=6&champ=1-1&choix=1', navigate: true);              
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
        
        $testSuivant = ProformaClientEntete::where('societe_id',auth()->user()->societe_id)->where('id','>',$this->ids)->orderBy('id','asc')->count();
        if($testSuivant > 0){
            $suivant = ProformaClientEntete::where('societe_id',auth()->user()->societe_id)->where('id','>',$this->ids)->orderBy('id','asc')->first();
            $next = $suivant->id;             
            $this->redirect('/nouveau_prof_clt?id='.$next.'&ref='.$this->reference.'&active=6&champ=1-1&choix=1', navigate: true);                     
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
                $page = 'ProformaClient';
                ProformaClientEntete::where('id',$this->ids)->delete(); 
                ProformaClientLigne::where('id_proforma_client_entete',$this->ids)->delete(); 
                LogActivityModel::where('id_activite',$this->ids)->where('page',$page)->delete();

                $id_activite = $this->ids;
                LogActivity::addToLog('Proforma client supprimée définitivement', $id_activite, $page);  
                $this->dispatch('alert',                    
                    title:'Proforma supprimée avec succes!',
                    timer:5000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                );
                $this->redirect('/proforma?active=6&champ=1-1&choix=1', navigate: true);                
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
    public function creerCommande(){ 
        $test = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe_id',auth()->user()->societe_id)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_facture;
            if($autoriser == 1){
                $test_facture = CommandeClientEntete::where('societe_id',auth()->user()->societe_id)->where('id_proforma_client_entete',$this->ids)->count();
                if($test_facture == 0){
                    
                    $etat = 'Brouillon';
                    $dates = date('dmy/His');
                    $length = 2;
                    $token = bin2hex(random_bytes($length));
                    $token_ok = 'CMD/'.$dates;
                    // $token_ok = 'FACT/'.$dates.'/'.$token;
                
                        // copier la table CommandeClientEntete dans CommandeClientEntete
                    $enteteProfClient = ProformaClientEntete::where('societe_id',auth()->user()->societe_id)->where('id',$this->ids)->get(); 
                    foreach($enteteProfClient as $enteteProfClients){
                        // creation et copie entete commande Client Entete
                        CommandeClientEntete::create([                        
                            'code_commande'=>$token_ok,
                            'code_proforma'=>$enteteProfClients->code_proforma,
                            'id_proforma_client_entete'=>$enteteProfClients->id,
                            'nom_client'=>$enteteProfClients->nom_client,
                            'id_client'=>$enteteProfClients->id_client,
                            'date_commande'=>$enteteProfClients->date_proforma,
                            'date_livraison'=>$enteteProfClients->date_livraison,
                            'telephone'=>$enteteProfClients->telephone,
                            'mode_reglement'=>'Espèce', // Espèce par defaut
                            'note'=>$enteteProfClients->note,
                            'montant_ht'=>$enteteProfClients->montant_ht,
                            'montant_remise'=>$enteteProfClients->montant_remise,
                            'montant_tva'=>$enteteProfClients->montant_tva,
                            'montant_precompte'=>$enteteProfClients->montant_precompte,
                            'montant_ttc'=>$enteteProfClients->montant_ttc,
                            'marge'=>$enteteProfClients->marge,
                            'montant_recu'=>$enteteProfClients->montant_recu,
                            'reste_a_percevoir'=>$enteteProfClients->reste_a_percevoir,
                            'etat'=>$etat,
                            // 'etat_expedi'=>$enteteProfClients->etat_expedi,
                            'societe'=>auth()->user()->societe,
                            'societe_id'=>auth()->user()->societe_id,
                            'nom_user'=>auth()->user()->name,
                            'user_id'=>auth()->user()->id]);
                    }
                    // ceci recupere le dernier enregistrement cree a l'instant
                    $dernier_id = CommandeClientEntete::where('societe_id',auth()->user()->societe_id)->where('user_id',auth()->user()->id)->latest()->first()->id; 
                
                    $ligneCmdClient = ProformaClientLigne::where('societe_id',auth()->user()->societe_id)->where('id_proforma_client_entete',$this->ids)->get(); 
                    foreach($ligneCmdClient as $ligneCmdClients){
                        // creation et copie entete Expedition Client Ligne
                        CommandeClientLigne::create([ 
                            'code_commande'=>$token_ok,
                            'id_commande_client_entete'=> $dernier_id,
                            // 'code_commande'=>$ligneCmdClients->code_commande,
                            // 'id_commande_client_entete'=>$ligneCmdClients->id_commande_client_entete,
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
                            'user_id'=>auth()->user()->id,
                            'nom_user'=>auth()->user()->name,
                            'societe_id'=>auth()->user()->societe_id,
                            'societe'=>auth()->user()->societe]);
                    }
                    ProformaClientEntete::where('societe_id',auth()->user()->societe_id)->where('id',$this->ids)->update(['etat_cmd'=>$etat,'code_commande'=>$token_ok,'id_commande_client_entete'=> $dernier_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                    // ExpeditionClientEntete::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$this->ids)->update(['etat_facture'=>$etat,'code_facture'=>$token_ok,'id_facture_client_entete'=> $dernier_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                    
                    $this->redirect('/nouveau_cmd_clt?id='.$dernier_id.'&ref='.$token_ok.'&active=6&champ=1-1&choix=2', navigate: true);            
                    $id_activite = $dernier_id;
                    $page = 'CommandeClient';
                    LogActivity::addToLog('Commande ('.$token_ok.') client créée', $id_activite, $page);  
                    $this->dispatch('alert',                    
                        title:'Commande client créée!',
                        timer:5000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    ); 
                }
                else{
                    $this->dispatch('alert',                    
                        title:'<strong>Désolé, Cette proforma est déjà associée à une commande.</strong> Pour la modifier, vous devez d’abord supprimer la commande existante, puis relancer la création!',
                        timer:15000,
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
    public function detailCmd(int $idx, $codeCmd_prof){
        // ceci au chargement de la page
        $test_facture = CommandeClientEntete::where('societe_id',auth()->user()->societe_id)->where('id',$idx)->count();    
        if($test_facture > 0){
            $compte = CommandeClientEntete::where('societe_id',auth()->user()->societe_id)->where('id',$idx)->first();               
            $this->ids = $compte->id;           
            $this->reference = $compte->code_facture; // reference commande
            $this->redirect('/nouveau_cmd_clt?id='.$idx.'&ref='.$this->reference.'&active=6&champ=1-1&choix=2', navigate: true);
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
            flash ('Désolé, cette commande <strong>('.$codeCmd_prof.')</strong> n\'existe pas!')->error();
            $this->redirect('/listing_cmd_clt?active=6&champ=1-1&choix=2', navigate: true);
        }
    }   
}
