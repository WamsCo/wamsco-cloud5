<?php

namespace App\Livewire\GestionCommande\Fournisseur;

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
use App\Models\CommandeFournisseurEntete;
use App\Models\CommandeFournisseurLigne;
use App\Models\Reglement_fourni;
use App\Models\EcritureBancaire;
use App\Models\ReceptionFournisseurEntete;
use App\Models\ReceptionFournisseurLigne;
use App\Models\factureFournisseurEntete;
use App\Models\factureFournisseurLigne;


class NouvCommandeFournisseur extends Component
{
    protected $paginationTheme = 'bootstrap';
    use WithPagination;
    use WithFileUploads;

    public $id; 
    public $ids; // important pour Update
    public $idx; // important pour creer reception 
    public $ouvre = 0;
    public $ouverture = 0;

    #[Validate('required|max:255')]
    public $fournisseur;
    public $ids_fournisseur; 
    public $fournisseur_id; // pour l'ajout dans ligne facture
    
    public $reference;

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

    // pour recherche fournisseur
    public $parNomTier; // Pour la recherche
    public $records;
    public $recordCount;
    public $showdiv = false;

    public $activer_fidelite;
    public $etat;
    public $etat_reception; // etat expedition
    public $code_facture;

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
            $this->autoriser = $role[0]->voir_marge;
            $autoriser = $role[0]->consulter_com_fourni;
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
        $entite_mod = Entite::where('enseigne',auth()->user()->societe)->get();
        $jourValid = $entite_mod[0]->validite_mod;
        $mod_cmd = $entite_mod[0]->mod_cmd; 
        $soldeClient = $entite_mod[0]->solde;
        if($dateJour <= $jourValid){
            if($mod_cmd == 1){
                $title = 'Commande fournisseur | WamsCo';
                $module = 'Gestion commande';
                $title_fils = 'Commande fournisseur';
                $lien = 'listing_cmd_clt';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');      
                $dateJour = date('Y-m-d');
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');

                $this->id = request('id'); // id entete facture
                $this->ref_fact = request('ref'); // reference facture
                //     // ceci au chargement de la page
                $test_facture = CommandeFournisseurEntete::where('societe',auth()->user()->societe)->where('id',$this->id)->count();    
                if($test_facture > 0){
                    $compte = CommandeFournisseurEntete::where('societe',auth()->user()->societe)->where('id',$this->id)->first();               
                    $this->ids = $compte->id;
                    $this->fournisseur_id = $compte->id_fournisseur;
                    $this->fournisseur = $compte->nom_fournisseur;
                    $this->reference = $compte->code_commande; // reference commande
                    $this->code_facture = $compte->code_facture; // code_facture     
                    $this->idx = $compte->id_facture_fournisseur_entete; // id_commande_fournisseur_entete important pour creer reception  
                    $this->date_commande = $compte->date_commande;
                    $this->date_livraison = $compte->date_livraison;                    
                    $this->mode_reglement = $compte->mode_reglement; 
                    $this->condition_reglement = $compte->condition_reglement;
                    $this->note = $compte->note;
                    $this->etat = $compte->etat;
                    $this->montant_recu = $compte->montant_recu;
                    $this->etat_reception = $compte->etat_reception;
                }              
                            
                $factFournisseur_ligne = CommandeFournisseurLigne::where('societe',auth()->user()->societe)->where('id_commande_fournisseur_entete',$this->ids)->orderBy($this->orderField, $this->orderDirection)->get();
                $factFournisseurLigneCount = $factFournisseur_ligne->count();
                
                $montantHT = $factFournisseur_ligne->sum('montant_ht');
                $montantTTC = $factFournisseur_ligne->sum('montant_ttc');
                $montantRemise = $factFournisseur_ligne->sum('montant_remise');
                $montantTva = $factFournisseur_ligne->sum('montant_tva');
                $montantPrecompte = $factFournisseur_ligne->sum('montant_precompte');
                $montantMarge = $factFournisseur_ligne->sum('marge');
                $prixAchat = $factFournisseur_ligne->sum('prix_achat');
                $prixVente = $factFournisseur_ligne->sum('prix_vente');
                // $prixRevient = $montantHT - $montantMarge; // important            
                $prixRevient = $montantHT; 
                
                if($factFournisseurLigneCount > 0){
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

                // Parametre
                $test_vide = Parametre ::where('societe',auth()->user()->societe)->count();
                if($test_vide > 0){                
                    $config = Parametre::where('societe',auth()->user()->societe)->limit(1)->get();
                    $id_entrepot = $config[0]->id_entrepot_fctfourni;               
                }
                else{
                    $id_entrepot = 0;
                }             

                $produit = Stock::where('societe',auth()->user()->societe)->where('id_entrepot',$id_entrepot)->get(); 
                
                $page = 'CommandeFournisseur'; // Pour evenement lie
                $log = LogActivityModel::where('user_societe',auth()->user()->societe)->where('id_activite', $this->ids)->where('page', $page)->limit(22)->orderBy('id','desc')->get();
                $logCount = $log->count();
                
                /// pour afficher les produits en stock
                if($this->type_produit == 'Produit'){            
                    $produit_stock = DB::table('stocks')
                                    ->select('id','nom_produit','reference','id_produit','type_produit','nature_produit','categorie',DB::raw('sum(quantite) as quantites, sum(valorisation_achat_total) as valorisationAchatTotal ,sum(valeur_vente_total) as valeurVentetotal, max(limite_stock_alerte) as limite_stock_alerte ,max(updated_at) as updated_at')) // Supposons que vous voulez la dernière date
                                    ->where('societe',auth()->user()->societe)                            
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
                    return view('livewire.gestion-commande.fournisseur.nouv-commande-fournisseur',compact('title_fils','module','lien','dateJour','factFournisseur_ligne','factFournisseurLigneCount','produit_stock','produit_stockCount','qteStockTotal','valAchatTotal','valVenteTotal','log','logCount','taxe','montantHT','montantTTC','montantRemise','montantTva','montantPrecompte','montantMarge','tauxMarge',
                            'prixVente','prixRevient'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
                }
                if($this->type_produit == 'Service'){  
                    return view('livewire.gestion-commande.fournisseur.nouv-commande-fournisseur',compact('title_fils','module','lien','dateJour','factFournisseur_ligne','factFournisseurLigneCount','service_produit','service_produitCount','log','logCount','taxe','montantHT','montantTTC','montantRemise','montantTva','montantPrecompte','montantMarge','tauxMarge',
                            'prixVente','prixRevient'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
                }
                else{ 
                    return view('livewire.gestion-commande.fournisseur.nouv-commande-fournisseur',compact('title_fils','module','lien','dateJour','factFournisseur_ligne','factFournisseurLigneCount','produit_stock','produit_stockCount','qteStockTotal','valAchatTotal','valVenteTotal','service_produit','service_produitCount','log','logCount','taxe','montantHT','montantTTC','montantRemise','montantTva','montantPrecompte','montantMarge','tauxMarge',
                            'prixVente','prixRevient'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
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
        if(!empty($this->fournisseur)){
            if(ctype_alpha($this->fournisseur)){ // ctype_alpha: cette fonction permet de savoir si le caractere ou mot est une lettre  
                $this->records = Tier::where('etat',1)->where('societe',auth()->user()->societe)->where('nom','like','%'.$this->fournisseur.'%')->where('type_tiers','Fournisseur')->orderBy('nom','asc')->limit(15)->get(); 
                $this->recordCount = Tier::where('etat',1)->where('societe',auth()->user()->societe)->where('nom','like','%'.$this->fournisseur.'%')->where('type_tiers','Fournisseur')->count();
                $this->showdiv = true;
            }
            else{
                $this->records = Tier::where('etat',1)->where('societe',auth()->user()->societe)->where('telephone','like','%'.$this->fournisseur.'%')->where('type_tiers','Fournisseur')->orderBy('nom','asc')->limit(15)->get(); 
                $this->recordCount = Tier::where('etat',1)->where('societe',auth()->user()->societe)->where('telephone','like','%'.$this->fournisseur.'%')->where('type_tiers','Fournisseur')->count(); 
                $this->showdiv = true;
            }        
        }
        else{
            $this->showdiv = false;
        }
    }
    public function ajouterTier($id = 0){
        $record = Tier::where('id', $id)->first();
        $this->fournisseur = $record->nom;
        $this->ids_fournisseur = $record->id;
        $this->showdiv = false;
    }
    public function update(){
        $this->validate();        
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_com_fourni;
            if($autoriser == 1){  
                    
                    $test_tiers = Tier ::where('societe',auth()->user()->societe)->where('id',$this->fournisseur_id)->count();
                    if($test_tiers == 0){
                        if(!empty($this->ids_fournisseur)){                           
                            
                            CommandeFournisseurEntete::find($this->ids)->update(['nom_fournisseur'=>$this->fournisseur,'id_fournisseur'=>$this->ids_fournisseur,'reference'=>$this->reference,'date_commande'=>$this->date_commande,'date_livraison'=>$this->date_livraison,
                            'mode_reglement'=>$this->mode_reglement,'condition_reglement'=>$this->condition_reglement,'note'=>$this->note,'societe'=>auth()->user()->societe,
                            'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                            
                            $id_activite = $this->ids;
                            $page = 'CommandeFournisseur';
                            LogActivity::addToLog('Entête commande » '.$this->fournisseur.' modifiée', $id_activite, $page); 
                            $this->dispatch('alert',                    
                                title:$this->fournisseur.' modifié(e)!',
                                timer:3000,
                                icon:'success',
                                toast:true,
                                showConfirmButton: false,
                                position:'top-end',
                            );  
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
                        if(empty($this->ids_fournisseur)){
                            
                            $test_tier_nom = Tier ::where('societe',auth()->user()->societe)->where('id',$this->fournisseur_id)->first();
                            $nom = $test_tier_nom->nom;

                            if($nom == $this->fournisseur){                                 
                                
                                // fournisseur_id de la CommandeFournisseurEntete
                                CommandeFournisseurEntete::find($this->ids)->update(['nom_fournisseur'=>$this->fournisseur,'id_fournisseur'=>$this->fournisseur_id,'reference'=>$this->reference,'date_commande'=>$this->date_commande,'date_livraison'=>$this->date_livraison,
                                'mode_reglement'=>$this->mode_reglement,'condition_reglement'=>$this->condition_reglement,'note'=>$this->note,'societe'=>auth()->user()->societe,
                                'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                                
                                $id_activite = $this->ids;
                                $page = 'CommandeFournisseur';
                                LogActivity::addToLog('Entête commande » '.$this->fournisseur.' modifiée', $id_activite, $page);
                                $this->dispatch('alert',                    
                                    title:$this->fournisseur.' modifié(e)!',
                                    timer:3000,
                                    icon:'success',
                                    toast:true,
                                    showConfirmButton: false,
                                    position:'top-end',
                                ); 
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
                            
                            $test_tier_nom = Tier ::where('societe',auth()->user()->societe)->where('id',$this->ids_fournisseur)->first();
                            $nom = $test_tier_nom->nom;   

                            if($this->ids_fournisseur != $this->fournisseur_id){ 
                                if($nom == $this->fournisseur){ 
                                                                       
                                    // ids_fournisseur de ajouterTier
                                    CommandeFournisseurEntete::find($this->ids)->update(['nom_fournisseur'=>$this->fournisseur,'id_fournisseur'=>$this->ids_fournisseur,'reference'=>$this->reference,'date_commande'=>$this->date_commande,'date_livraison'=>$this->date_livraison,
                                    'mode_reglement'=>$this->mode_reglement,'condition_reglement'=>$this->condition_reglement,'note'=>$this->note,'societe'=>auth()->user()->societe,
                                    'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                                    
                                    $id_activite = $this->ids;
                                    $page = 'CommandeFournisseur';
                                    LogActivity::addToLog('Entête commande » '.$this->fournisseur.' modifiée', $id_activite, $page);
                                    $this->dispatch('alert',                    
                                        title:$this->fournisseur.' modifié(e)!',
                                        timer:3000,
                                        icon:'success',
                                        toast:true,
                                        showConfirmButton: false,
                                        position:'top-end',
                                    ); 
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
        $compte = CommandeFournisseurEntete::where('societe',auth()->user()->societe)->where('id',$this->ids)->first(); 
        $this->fournisseur_id = $compte->id_fournisseur;
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
            'fournisseur'=>'required',
            'date_commande'=>'required', // important pour forcer utilisateur a remplir
            'date_livraison'=>'required',    // important pour forcer utilisateur a remplir
            'mode_reglement'=>'required',   // important pour forcer utilisateur a remplir
            'condition_reglement'=>'max:255',  // important pour forcer utilisateur a remplir
            
        ]);    
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_com_fourni;
            if($autoriser == 1){            
                if($this->quantite > 0 && $this->prix_vente > 0){           
                        
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

                    $quantite_recue = 0;
                    $typeProd = 'Produit';
                    if($this->offrir == 'Non'){ 
                        CommandeFournisseurLigne::create(['code_commande'=>$this->reference,'id_commande_fournisseur_entete'=>$this->ids,'produit'=>$this->nom_produit,'id_produit'=>$this->id_produit,'type_produit'=>$typeProd,'prix_achat'=>$this->prix_moyen_pondere_achat,
                                        'prix_vente'=>$this->prix_vente,'quantite'=>$this->quantite,'quantite_recue'=>$quantite_recue,'reste_a_recevoir'=>$this->quantite,'remise'=>$this->remise,'montant_remise'=>$remise_montant,
                                        'tva'=>$this->tva,'montant_tva'=>$tva_montant,'precompte'=>$this->precompte,'montant_precompte'=>$precompte_montant,'montant_ht'=>$montant_remiser_ht,'montant_ttc'=>$montant_ttc,'marge'=>$marge,'id_entrepot'=>$this->id_entrepot,
                                        'nom_fournisseur'=>$this->fournisseur,'id_fournisseur'=>$this->fournisseur_id,'offrir'=>$this->offrir,'etat'=>$this->etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                     
                    
                        $montantHT = CommandeFournisseurLigne::where('societe',auth()->user()->societe)->where('id_commande_fournisseur_entete',$this->ids)->sum('montant_ht');
                        $montantTTC = CommandeFournisseurLigne::where('societe',auth()->user()->societe)->where('id_commande_fournisseur_entete',$this->ids)->sum('montant_ttc');
                        $montantRemise = CommandeFournisseurLigne::where('societe',auth()->user()->societe)->where('id_commande_fournisseur_entete',$this->ids)->sum('montant_remise');
                        $montantTva = CommandeFournisseurLigne::where('societe',auth()->user()->societe)->where('id_commande_fournisseur_entete',$this->ids)->sum('montant_tva');
                        $montantPrecompte = CommandeFournisseurLigne::where('societe',auth()->user()->societe)->where('id_commande_fournisseur_entete',$this->ids)->sum('montant_precompte');
                        $marge = CommandeFournisseurLigne::where('societe',auth()->user()->societe)->where('id_commande_fournisseur_entete',$this->ids)->sum('marge');

                        // Montant TTC en arrondi en + ou en - 
                        CommandeFournisseurEntete::find($this->ids)->update(['montant_ht'=>$montantHT,'montant_remise'=>$montantRemise,'montant_tva'=>$montantTva,'montant_precompte'=>$montantPrecompte,'montant_ttc'=>number_format($montantTTC,0,',',''),
                                            'marge'=>$marge,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                    }
                    elseif($this->offrir == 'Oui'){

                        // les totaux seront a zero                                       
                        $remise_montant = 0;
                        $tva_montant = 0;
                        $precompte_montant = 0;
                        $montant_remiser_ht = 0;
                        $montant_ttc = 0;
                        $marge = 0;
                        CommandeFournisseurLigne::create(['code_commande'=>$this->reference,'id_commande_fournisseur_entete'=>$this->ids,'produit'=>$this->nom_produit,'id_produit'=>$this->id_produit,'type_produit'=>$typeProd,'prix_achat'=>$this->prix_moyen_pondere_achat,
                                        'prix_vente'=>$this->prix_vente,'quantite'=>$this->quantite,'quantite_recue'=>$quantite_recue,'reste_a_recevoir'=>$this->quantite,'remise'=>$this->remise,'montant_remise'=>$remise_montant,
                                        'tva'=>$this->tva,'montant_tva'=>$tva_montant,'precompte'=>$this->precompte,'montant_precompte'=>$precompte_montant,'montant_ht'=>$montant_remiser_ht,'montant_ttc'=>$montant_ttc,'marge'=>$marge,'id_entrepot'=>$this->id_entrepot,
                                        'nom_fournisseur'=>$this->fournisseur,'id_fournisseur'=>$this->fournisseur_id,'offrir'=>$this->offrir,'etat'=>$this->etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                     
                    
                        $montantHT = CommandeFournisseurLigne::where('societe',auth()->user()->societe)->where('id_commande_fournisseur_entete',$this->ids)->sum('montant_ht');
                        $montantTTC = CommandeFournisseurLigne::where('societe',auth()->user()->societe)->where('id_commande_fournisseur_entete',$this->ids)->sum('montant_ttc');
                        $montantRemise = CommandeFournisseurLigne::where('societe',auth()->user()->societe)->where('id_commande_fournisseur_entete',$this->ids)->sum('montant_remise');
                        $montantTva = CommandeFournisseurLigne::where('societe',auth()->user()->societe)->where('id_commande_fournisseur_entete',$this->ids)->sum('montant_tva');
                        $montantPrecompte = CommandeFournisseurLigne::where('societe',auth()->user()->societe)->where('id_commande_fournisseur_entete',$this->ids)->sum('montant_precompte');
                        $marge = CommandeFournisseurLigne::where('societe',auth()->user()->societe)->where('id_commande_fournisseur_entete',$this->ids)->sum('marge');

                        // Montant TTC en arrondi en + ou en - 
                        CommandeFournisseurEntete::find($this->ids)->update(['montant_ht'=>$montantHT,'montant_remise'=>$montantRemise,'montant_tva'=>$montantTva,'montant_precompte'=>$montantPrecompte,'montant_ttc'=>number_format($montantTTC,0,',',''),
                                            'marge'=>$marge,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                    }
                    $id_activite = $this->ids;
                    $page = 'CommandeFournisseur';
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
                    // $this->redirect('/nouveau_cmd_fourni?id='.$this->ids.'&ref='.$this->reference.'&active=6&champ=2-1&choix=1', navigate: true);                    
                }
                else{
                    $this->dispatch('alert',                    
                        title:'Désolé, ajoutez une quantité ou prix supérieure à 0',
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
            'fournisseur'=>'required',
            'date_commande'=>'required', // important pour forcer utilisateur a remplir
            'date_livraison'=>'required',    // important pour forcer utilisateur a remplir
            'mode_reglement'=>'required',   // important pour forcer utilisateur a remplir
            'condition_reglement'=>'max:255',  // important pour forcer utilisateur a remplir
            
        ]);    
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_com_fourni;
            if($autoriser == 1){            
                if($this->quantite > 0 && $this->prix_vente > 0){           
                        
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

                    $quantite_recue = 0;
                    $typeProd = 'Service';
                    if($this->offrir == 'Non'){ 
                        CommandeFournisseurLigne::create(['code_commande'=>$this->reference,'id_commande_fournisseur_entete'=>$this->ids,'produit'=>$this->nom_produit,'id_produit'=>$this->id_produit,'type_produit'=>$typeProd,'prix_achat'=>$this->prix_moyen_pondere_achat,
                                        'prix_vente'=>$this->prix_vente,'quantite'=>$this->quantite,'quantite_recue'=>$quantite_recue,'reste_a_recevoir'=>$this->quantite,'remise'=>$this->remise,'montant_remise'=>$remise_montant,
                                        'tva'=>$this->tva,'montant_tva'=>$tva_montant,'precompte'=>$this->precompte,'montant_precompte'=>$precompte_montant,'montant_ht'=>$montant_remiser_ht,'montant_ttc'=>$montant_ttc,'marge'=>$marge,'id_entrepot'=>$this->id_entrepot,
                                        'nom_fournisseur'=>$this->fournisseur,'id_fournisseur'=>$this->fournisseur_id,'offrir'=>$this->offrir,'etat'=>$this->etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                     
                    
                        $montantHT = CommandeFournisseurLigne::where('societe',auth()->user()->societe)->where('id_commande_fournisseur_entete',$this->ids)->sum('montant_ht');
                        $montantTTC = CommandeFournisseurLigne::where('societe',auth()->user()->societe)->where('id_commande_fournisseur_entete',$this->ids)->sum('montant_ttc');
                        $montantRemise = CommandeFournisseurLigne::where('societe',auth()->user()->societe)->where('id_commande_fournisseur_entete',$this->ids)->sum('montant_remise');
                        $montantTva = CommandeFournisseurLigne::where('societe',auth()->user()->societe)->where('id_commande_fournisseur_entete',$this->ids)->sum('montant_tva');
                        $montantPrecompte = CommandeFournisseurLigne::where('societe',auth()->user()->societe)->where('id_commande_fournisseur_entete',$this->ids)->sum('montant_precompte');
                        $marge = CommandeFournisseurLigne::where('societe',auth()->user()->societe)->where('id_commande_fournisseur_entete',$this->ids)->sum('marge');

                        // Montant TTC en arrondi en + ou en - 
                        CommandeFournisseurEntete::find($this->ids)->update(['montant_ht'=>$montantHT,'montant_remise'=>$montantRemise,'montant_tva'=>$montantTva,'montant_precompte'=>$montantPrecompte,'montant_ttc'=>number_format($montantTTC,0,',',''),
                                            'marge'=>$marge,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                    }
                    elseif($this->offrir == 'Oui'){

                        // les totaux seront a zero                                       
                        $remise_montant = 0;
                        $tva_montant = 0;
                        $precompte_montant = 0;
                        $montant_remiser_ht = 0;
                        $montant_ttc = 0;
                        $marge = 0;
                        CommandeFournisseurLigne::create(['code_commande'=>$this->reference,'id_commande_fournisseur_entete'=>$this->ids,'produit'=>$this->nom_produit,'id_produit'=>$this->id_produit,'type_produit'=>$typeProd,'prix_achat'=>$this->prix_moyen_pondere_achat,
                                        'prix_vente'=>$this->prix_vente,'quantite'=>$this->quantite,'quantite_recue'=>$quantite_recue,'reste_a_recevoir'=>$this->quantite,'remise'=>$this->remise,'montant_remise'=>$remise_montant,
                                        'tva'=>$this->tva,'montant_tva'=>$tva_montant,'precompte'=>$this->precompte,'montant_precompte'=>$precompte_montant,'montant_ht'=>$montant_remiser_ht,'montant_ttc'=>$montant_ttc,'marge'=>$marge,'id_entrepot'=>$this->id_entrepot,
                                        'nom_fournisseur'=>$this->fournisseur,'id_fournisseur'=>$this->fournisseur_id,'offrir'=>$this->offrir,'etat'=>$this->etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                     
                    
                        $montantHT = CommandeFournisseurLigne::where('societe',auth()->user()->societe)->where('id_commande_fournisseur_entete',$this->ids)->sum('montant_ht');
                        $montantTTC = CommandeFournisseurLigne::where('societe',auth()->user()->societe)->where('id_commande_fournisseur_entete',$this->ids)->sum('montant_ttc');
                        $montantRemise = CommandeFournisseurLigne::where('societe',auth()->user()->societe)->where('id_commande_fournisseur_entete',$this->ids)->sum('montant_remise');
                        $montantTva = CommandeFournisseurLigne::where('societe',auth()->user()->societe)->where('id_commande_fournisseur_entete',$this->ids)->sum('montant_tva');
                        $montantPrecompte = CommandeFournisseurLigne::where('societe',auth()->user()->societe)->where('id_commande_fournisseur_entete',$this->ids)->sum('montant_precompte');
                        $marge = CommandeFournisseurLigne::where('societe',auth()->user()->societe)->where('id_commande_fournisseur_entete',$this->ids)->sum('marge');

                        // Montant TTC en arrondi en + ou en - 
                        CommandeFournisseurEntete::find($this->ids)->update(['montant_ht'=>$montantHT,'montant_remise'=>$montantRemise,'montant_tva'=>$montantTva,'montant_precompte'=>$montantPrecompte,'montant_ttc'=>number_format($montantTTC,0,',',''),
                                            'marge'=>$marge,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                    }
                    $id_activite = $this->ids;
                    $page = 'CommandeFournisseur';
                    LogActivity::addToLog('Produit ('.$this->nom_produit.') à commander ajouté', $id_activite, $page);  
                    $this->dispatch('alert', 
                        title:'Service » '.$this->quantite.' <strong>'.$this->nom_produit.'</strong> ajouté(s)!', 
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );  
                    $this->resetinputFields();
                    // $this->redirect('/nouveau_cmd_fourni?id='.$this->ids.'&ref='.$this->reference.'&active=6&champ=2-1&choix=1', navigate: true);                    
                }
                else{
                    $this->dispatch('alert',                    
                        title:'Désolé, ajoutez une quantité ou prix supérieure à 0',
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
        $this->redirect('/nouveau_cmd_fourni?id='.$this->ids.'&ref='.$this->reference.'&active=6&champ=2-1&choix=1', navigate: true);
    }
    // Ceci supprime la ligne de produit
    public function confirmerDelete($id){
        $this->confirmer = $id;        
    } 
    public function supprimer($id){
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_com_fourni;
            if($autoriser == 1){   
                if($id){
                    
                    CommandeFournisseurLigne::where('id',$id)->delete();

                    $montantHT = CommandeFournisseurLigne::where('societe',auth()->user()->societe)->where('id_commande_fournisseur_entete',$this->ids)->sum('montant_ht');
                    $montantTTC = CommandeFournisseurLigne::where('societe',auth()->user()->societe)->where('id_commande_fournisseur_entete',$this->ids)->sum('montant_ttc');
                    $montantRemise = CommandeFournisseurLigne::where('societe',auth()->user()->societe)->where('id_commande_fournisseur_entete',$this->ids)->sum('montant_remise');
                    $montantTva = CommandeFournisseurLigne::where('societe',auth()->user()->societe)->where('id_commande_fournisseur_entete',$this->ids)->sum('montant_tva');
                    $montantPrecompte = CommandeFournisseurLigne::where('societe',auth()->user()->societe)->where('id_commande_fournisseur_entete',$this->ids)->sum('montant_precompte');
                    $marge = CommandeFournisseurLigne::where('societe',auth()->user()->societe)->where('id_commande_fournisseur_entete',$this->ids)->sum('marge');
                                        
                    CommandeFournisseurEntete::find($this->ids)->update(['montant_ht'=>number_format($montantHT,0,',',''),'montant_remise'=>$montantRemise,'montant_tva'=>$montantTva,
                                        'montant_ttc'=>number_format($montantTTC,0,',',''),'montant_precompte'=>$montantPrecompte,'marge'=>$marge,'societe'=>auth()->user()->societe,
                                        'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                    
                    
                    $id_activite = $this->ids;
                    $page = 'CommandeFournisseur';
                    LogActivity::addToLog('Ligne de commande fourni. supprimé', $id_activite, $page);
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
            $autoriser = $role[0]->modifier_com_fourni;
            if($autoriser == 1){ 
                $etat = 'Validée';
                CommandeFournisseurEntete::find($this->ids)->update(['etat'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);      
                CommandeFournisseurLigne::where('id_commande_fournisseur_entete',$this->ids)->update(['etat'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                $id_activite = $this->ids;
                $page = 'CommandeFournisseur';
                LogActivity::addToLog('Commande fourni. validée', $id_activite, $page);
                $this->dispatch('alert',                    
                    title:'Commande sous la référence <strong>'.$this->reference.'</strong> validée!',
                    timer:53000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                );                     
                $this->redirect('/nouveau_cmd_fourni?id='.$this->ids.'&active=6&champ=2-1&choix=1', navigate: true);  
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
        CommandeFournisseurEntete::find($this->ids)->update(['etat'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);      
        CommandeFournisseurLigne::where('id_commande_fournisseur_entete',$this->ids)->update(['etat'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
        
        $id_activite = $this->ids;
        $page = 'CommandeFournisseur';
        LogActivity::addToLog('Commande fourni en brouillon', $id_activite, $page);
        $this->dispatch('alert',                    
            title:'Commande sous la référence <strong>'.$this->reference.'</strong> retournée au brouillon!',
            timer:53000,
            icon:'success',
            toast:true,
            showConfirmButton: false,
            position:'top-end',
        );       
        $this->redirect('/nouveau_cmd_fourni?id='.$this->ids.'&active=6&champ=2-1&choix=1', navigate: true);        
    }
    public function precedant(){ 
        $testPrecedant = CommandeFournisseurEntete::where('societe',auth()->user()->societe)->where('id','<',$this->ids)->orderBy('id','desc')->count();
        if($testPrecedant > 0){ 
            $precedant = CommandeFournisseurEntete::where('societe',auth()->user()->societe)->where('id','<',$this->ids)->orderBy('id','desc')->first();        
            $previous = $precedant->id; 
            $this->redirect('/nouveau_cmd_fourni?id='.$previous.'&ref='.$this->reference.'&active=6&champ=2-1&choix=1', navigate: true);              
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
        
        $testSuivant = CommandeFournisseurEntete::where('societe',auth()->user()->societe)->where('id','>',$this->ids)->orderBy('id','asc')->count();
        if($testSuivant > 0){
            $suivant = CommandeFournisseurEntete::where('societe',auth()->user()->societe)->where('id','>',$this->ids)->orderBy('id','asc')->first();
            $next = $suivant->id;             
            $this->redirect('/nouveau_cmd_fourni?id='.$next.'&ref='.$this->reference.'&active=6&champ=2-1&choix=1', navigate: true);                     
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
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->supprimer_com_fourni;
            if($autoriser == 1){
                // suppression definitive et redirection
                $page = 'CommandeFournisseur';
                CommandeFournisseurEntete::where('id',$this->ids)->delete(); 
                CommandeFournisseurLigne::where('id_commande_fournisseur_entete',$this->ids)->delete(); 
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
                $this->redirect('/listing_cmd_fourni?active=6&champ=2-1&choix=1', navigate: true);                
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
    public function reception(){ 
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_reception;
            if($autoriser == 1){ 
                $test_recption = ReceptionFournisseurEntete::where('societe',auth()->user()->societe)->where('id_commande_fournisseur_entete',$this->ids)->count();
                if($this->idx == NULL){
                    $this->idx = 0;
                } 
                $test_recption2 = ReceptionFournisseurEntete::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->idx)->count();
                if($test_recption == 0 && $test_recption2 == 0){
                    
                    $etat = 'Brouillon';
                    $dates = date('dmy/His');
                    $length = 2;
                    $token = bin2hex(random_bytes($length));
                    $token_ok = 'RCP/'.$dates;
                    // $token_ok = 'RCP/'.$dates.'/'.$token;

                        // copier la table CommandeFournisseurEntete dans ReceptionFournisseurEntete
                    $enteteCmdFournisseur = CommandeFournisseurEntete::where('societe',auth()->user()->societe)->where('id',$this->ids)->get(); 
                    foreach($enteteCmdFournisseur as $enteteCmdFournisseurs){
                        // creation et copie entete Reception Fournisseur Entete
                        ReceptionFournisseurEntete::create([                        
                            'code_reception'=>$token_ok,
                            'code_commande'=>$enteteCmdFournisseurs->code_commande,
                            'id_commande_fournisseur_entete'=>$enteteCmdFournisseurs->id,
                            'nom_fournisseur'=>$enteteCmdFournisseurs->nom_fournisseur,
                            'id_fournisseur'=>$enteteCmdFournisseurs->id_fournisseur,
                            'date_facturation'=>$enteteCmdFournisseurs->date_commande,
                            'date_echeance'=>$enteteCmdFournisseurs->date_livraison,
                            'note'=>$enteteCmdFournisseurs->note,
                            'montant_ht'=>$enteteCmdFournisseurs->montant_ht,
                            'montant_remise'=>$enteteCmdFournisseurs->montant_remise,
                            'montant_tva'=>$enteteCmdFournisseurs->montant_tva,
                            'montant_precompte'=>$enteteCmdFournisseurs->montant_precompte,
                            'montant_ttc'=>$enteteCmdFournisseurs->montant_ttc,
                            'marge'=>$enteteCmdFournisseurs->marge,
                            'montant_recu'=>$enteteCmdFournisseurs->montant_recu,
                            'reste_a_percevoir'=>$enteteCmdFournisseurs->reste_a_percevoir,
                            'etat'=>$etat,
                            'etat_facture'=>$enteteCmdFournisseurs->etat,
                            'societe'=>auth()->user()->societe,
                            'nom_user'=>auth()->user()->name,
                            'user_id'=>auth()->user()->id]);
                    }
                
                    $ligneCmdFournisseur = CommandeFournisseurLigne::where('societe',auth()->user()->societe)->where('id_commande_fournisseur_entete',$this->ids)->where('type_produit','Produit')->get(); 
                    foreach($ligneCmdFournisseur as $ligneCmdFournisseurs){
                        // creation et copie entete Reception Fournisseur Ligne
                        ReceptionFournisseurLigne::create([ 
                            'code_reception'=>$token_ok,
                            'code_commande'=>$ligneCmdFournisseurs->code_commande,
                            'id_commande_fournisseur_entete'=>$ligneCmdFournisseurs->id_commande_fournisseur_entete,
                            'nom_fournisseur'=>$ligneCmdFournisseurs->nom_fournisseur,
                            'id_fournisseur'=>$ligneCmdFournisseurs->id_fournisseur,
                            'produit'=>$ligneCmdFournisseurs->produit,
                            'id_produit'=>$ligneCmdFournisseurs->id_produit,
                            'prix_achat'=>$ligneCmdFournisseurs->prix_achat,
                            'prix_vente'=>$ligneCmdFournisseurs->prix_vente,
                            'quantite'=>$ligneCmdFournisseurs->quantite,
                            'quantite_recue'=>$ligneCmdFournisseurs->quantite_recue,
                            'reste_a_recevoir'=>$ligneCmdFournisseurs->reste_a_recevoir,
                            'remise'=>$ligneCmdFournisseurs->remise,
                            'montant_remise'=>$ligneCmdFournisseurs->montant_remise,
                            'tva'=>$ligneCmdFournisseurs->tva,
                            'montant_tva'=>$ligneCmdFournisseurs->montant_tva,
                            'precompte'=>$ligneCmdFournisseurs->precompte,
                            'montant_precompte'=>$ligneCmdFournisseurs->montant_precompte,
                            'montant_ht'=>$ligneCmdFournisseurs->montant_ht,
                            'montant_ttc'=>$ligneCmdFournisseurs->montant_ttc,
                            'marge'=>$ligneCmdFournisseurs->marge,
                            'id_entrepot'=>$ligneCmdFournisseurs->id_entrepot,
                            'nom_fournisseur'=>$ligneCmdFournisseurs->nom_fournisseur,
                            'id_fournisseur'=>$ligneCmdFournisseurs->id_fournisseur,
                            'offrir'=>$ligneCmdFournisseurs->offrir,
                            'etat'=>$etat,
                            'etat_facture'=>$ligneCmdFournisseurs->etat, 
                            'user_id'=>auth()->user()->id,
                            'nom_user'=>auth()->user()->name,
                            'societe'=>auth()->user()->societe]);
                    }
                    
                    $enteteExpeFournisseur = ReceptionFournisseurEntete::where('societe',auth()->user()->societe)->where('id_commande_fournisseur_entete',$this->ids)->first();
                    $id_recpt = $enteteExpeFournisseur->id;
                    $code_reception = $enteteExpeFournisseur->code_reception;

                    CommandeFournisseurEntete::where('societe',auth()->user()->societe)->where('id',$this->ids)->update(['etat_reception'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                    factureFournisseurEntete::where('societe',auth()->user()->societe)->where('id',$this->idx)->update(['etat_reception'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                    
                    $this->redirect('/detail_reception_fourni?id='.$id_recpt.'&ref='.$code_reception.'&active=6&champ=2-1&choix=2', navigate: true);

                    $this->dispatch('alert',                    
                        title:'Réception commande ('.$this->reference.') creée!',
                        timer:5000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    ); 
                }
                else{
                    $this->dispatch('alert',                    
                        title:'Désolé, une réception est déja encours pour cette commande !',
                        timer:5000,
                        icon:'warning',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    ); 
                    $enteteExpeFournisseur = ReceptionFournisseurEntete::where('societe',auth()->user()->societe)->where('id_commande_fournisseur_entete',$this->ids)->first();
                    $test_recption2 = ReceptionFournisseurEntete::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->idx)->first();

                    if($enteteExpeFournisseur){
                        $id_recpt = $enteteExpeFournisseur->id;
                        $code_reception = $enteteExpeFournisseur->code_reception;                
                    }
                    else{
                        $id_recpt = $test_recption2->id;
                        $code_reception = $test_recption2->code_reception;                
                    } 
                    $this->redirect('/detail_reception_fourni?id='.$id_recpt.'&ref='.$code_reception.'&active=6&champ=2-1&choix=2', navigate: true);
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
    public function creerFacture(){ 
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_fact_fourni;
            if($autoriser == 1){ 
                $test_facture = factureFournisseurEntete::where('societe',auth()->user()->societe)->where('id_commande_fournisseur_entete',$this->ids)->count();
                if($test_facture == 0){            
                    $etat = 'Brouillon';
                    $dates = date('dmy/His');
                    $length = 2;
                    $token = bin2hex(random_bytes($length));
                    $token_ok = 'SFACT/'.$dates;
                    // $token_ok = 'SFACT/'.$dates.'/'.$token;
                
                        // copier la table CommandeFournisseurEntete dans factureFournisseurEntete
                    $enteteCmdFournisseur = CommandeFournisseurEntete::where('societe',auth()->user()->societe)->where('id',$this->ids)->get(); 
                    foreach($enteteCmdFournisseur as $enteteCmdFournisseurs){
                        // creation et copie entete Facture fournisseur Entete
                        factureFournisseurEntete::create([                        
                            'code_facture'=>$token_ok,
                            'code_commande'=>$enteteCmdFournisseurs->code_commande,
                            'id_commande_fournisseur_entete'=>$enteteCmdFournisseurs->id,
                            'nom_fournisseur'=>$enteteCmdFournisseurs->nom_fournisseur,
                            'id_fournisseur'=>$enteteCmdFournisseurs->id_fournisseur,
                            'date_facturation'=>$enteteCmdFournisseurs->date_commande,
                            'date_echeance'=>$enteteCmdFournisseurs->date_livraison,
                            'mode_reglement'=>'Espèce', // Espèce par defaut
                            'note'=>$enteteCmdFournisseurs->note,
                            'montant_ht'=>$enteteCmdFournisseurs->montant_ht,
                            'montant_remise'=>$enteteCmdFournisseurs->montant_remise,
                            'montant_tva'=>$enteteCmdFournisseurs->montant_tva,
                            'montant_precompte'=>$enteteCmdFournisseurs->montant_precompte,
                            'montant_ttc'=>$enteteCmdFournisseurs->montant_ttc,
                            'marge'=>$enteteCmdFournisseurs->marge,
                            'montant_recu'=>$enteteCmdFournisseurs->montant_recu,
                            'reste_a_percevoir'=>$enteteCmdFournisseurs->reste_a_percevoir,
                            'etat'=>$etat,
                            'etat_reception'=>$enteteCmdFournisseurs->etat_reception,
                            'societe'=>auth()->user()->societe,
                            'nom_user'=>auth()->user()->name,
                            'user_id'=>auth()->user()->id]);
                    }
                    // ceci recupere le dernier enregistrement cree a l'instant
                    $dernier_id = factureFournisseurEntete::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id; 
                
                    $ligneCmdFournisseur = CommandeFournisseurLigne::where('societe',auth()->user()->societe)->where('id_commande_fournisseur_entete',$this->ids)->get(); 
                    foreach($ligneCmdFournisseur as $ligneCmdFournisseurs){
                        // creation et copie entete Facture fournisseur Ligne
                        factureFournisseurLigne::create([ 
                            'code_facture'=>$token_ok,
                            'id_facture_fournisseur_entete'=> $dernier_id,
                            // 'code_commande'=>$ligneCmdFournisseurs->code_commande,
                            // 'id_commande_fournisseur_entete'=>$ligneCmdFournisseurs->id_commande_fournisseur_entete,
                            'nom_fournisseur'=>$ligneCmdFournisseurs->nom_fournisseur,
                            'id_fournisseur'=>$ligneCmdFournisseurs->id_fournisseur,
                            'produit'=>$ligneCmdFournisseurs->produit,
                            'id_produit'=>$ligneCmdFournisseurs->id_produit,
                            'type_produit'=>$ligneCmdFournisseurs->type_produit,
                            'prix_achat'=>$ligneCmdFournisseurs->prix_achat,
                            'prix_vente'=>$ligneCmdFournisseurs->prix_vente,
                            'quantite'=>$ligneCmdFournisseurs->quantite,
                            'quantite_recue'=>$ligneCmdFournisseurs->quantite_recue,
                            'reste_a_recevoir'=>$ligneCmdFournisseurs->reste_a_recevoir,
                            'remise'=>$ligneCmdFournisseurs->remise,
                            'montant_remise'=>$ligneCmdFournisseurs->montant_remise,
                            'tva'=>$ligneCmdFournisseurs->tva,
                            'montant_tva'=>$ligneCmdFournisseurs->montant_tva,
                            'precompte'=>$ligneCmdFournisseurs->precompte,
                            'montant_precompte'=>$ligneCmdFournisseurs->montant_precompte,
                            'montant_ht'=>$ligneCmdFournisseurs->montant_ht,
                            'montant_ttc'=>$ligneCmdFournisseurs->montant_ttc,
                            'marge'=>$ligneCmdFournisseurs->marge,
                            'id_entrepot'=>$ligneCmdFournisseurs->id_entrepot,                   
                            'offrir'=>$ligneCmdFournisseurs->offrir,
                            'etat'=>$etat,
                            'user_id'=>auth()->user()->id,
                            'nom_user'=>auth()->user()->name,
                            'societe'=>auth()->user()->societe]);
                    }
                    CommandeFournisseurEntete::where('societe',auth()->user()->societe)->where('id',$this->ids)->update(['etat_fact'=>$etat,'code_facture'=>$token_ok,'id_facture_fournisseur_entete'=> $dernier_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                    ReceptionFournisseurEntete::where('societe',auth()->user()->societe)->where('id_commande_fournisseur_entete',$this->ids)->update(['etat_facture'=>$etat,'code_facture'=>$token_ok,'id_facture_fournisseur_entete'=> $dernier_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                    ReceptionFournisseurLigne::where('societe',auth()->user()->societe)->where('id_commande_fournisseur_entete',$this->ids)->update(['code_facture'=>$token_ok,'id_facture_fournisseur_entete'=> $dernier_id,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                    $this->redirect('/nouveau_fact_fourni?id='.$dernier_id.'&ref='.$token_ok.'&active=7&champ=2-1&choix=1', navigate: true);            

                    $this->dispatch('alert',                    
                        title:'Facture fournisseur creée!',
                        timer:5000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    ); 
                }
                else{
                    $this->dispatch('alert',                    
                        title:'Désolé, une facture est déja encours pour cette commande !',
                        timer:5000,
                        icon:'warning',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    ); 
                    $enteteFactFournisseur = factureFournisseurEntete::where('societe',auth()->user()->societe)->where('id_commande_fournisseur_entete',$this->ids)->first();
                    $id_fact = $enteteFactFournisseur->id;
                    $code_fact = $enteteFactFournisseur->code_facture;
                    $this->redirect('/nouveau_fact_fourni?id='.$id_fact.'&ref='.$code_fact.'&active=7&champ=2-1&choix=1', navigate: true);
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
    public function detailFact(int $idx, $codeFact_cmd){ 
        // ceci au chargement de la page
        $test_facture = factureFournisseurEntete::where('societe',auth()->user()->societe)->where('id',$idx)->count();    
        if($test_facture > 0){
            $compte = factureFournisseurEntete::where('societe',auth()->user()->societe)->where('id',$idx)->first();               
            $this->ids = $compte->id;           
            $this->reference = $compte->code_facture; // reference facture
            $this->redirect('/nouveau_fact_fourni?id='.$idx.'&ref='.$this->reference.'&active=7&champ=2-1&choix=1', navigate: true);
        }  
        else{
            $this->dispatch('alert',                    
            title:'Désolé, cette facture n\'existe pas!',
                timer:5000,
                icon:'warning',
                toast:true,
                showConfirmButton: false,
                position:'top-end',
            );  
            flash ('Désolé, cette facture <strong>('.$codeFact_cmd.')</strong> n\'existe pas!')->error();
            $this->redirect('/listing_fact_fourni?active=7&champ=2-1&choix=1', navigate: true);
        }
    }
}
