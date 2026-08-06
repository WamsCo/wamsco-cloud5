<?php

namespace App\Livewire\GestionFacturation\Fournisseur;

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
use App\Models\factureFournisseurEntete;
use App\Models\factureFournisseurLigne;
use App\Models\Reglement_fourni;
use App\Models\EcritureBancaire;
use App\Models\ReceptionFournisseurEntete;
use App\Models\ReceptionFournisseurLigne;
use App\Models\CommandeFournisseurEntete;
use App\Models\ReceptionFournisseurLignePartiel;
use App\Models\Entrepot;
use App\Models\Mouvement;



class NouvFactureFournisseur extends Component
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
    public $referenceProd;

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

    // pour recherche fournisseur
    public $parNomTier; // Pour la recherche
    public $records;
    public $recordCount;
    public $showdiv = false;

    public $activer_fidelite;
    public $etat;
    public $etat_reception; // etat reception

    // pour ligne facture
    public $choix_produit;
    public $prix_moyen_pondere_achat = 0; // prix achat
    public $prix_vente = 0;
    public $prix_achat = 0;
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
    public $reste_a_percevoir;
    public $montant_reglement;
    
    public $quantite_bd;
    public $prix_vente_min;     
    
    public $devise;
    public $confirmer;
    public $confirmation; 
    public $approuver; 

    public $id_cmd_fourni_entete;
    public $id_recept_fourni_entete; 
    
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
            $autoriser = $role[0]->consulter_fact_fourni;
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
                $title = 'Facture fournisseur | WamsCo';
                $module = 'Gestion facturation';
                $title_fils = 'Facture fournisseur';
                $lien = 'listing_fact_fourni?active=7&champ=2-1&choix=1';
                $active = request('active');
                $champ = request('champ');
                $choix = request('choix');      
                $dateJour = date('Y-m-d');
                toast()->success('Prêt', '')->position('top-right')->autoClose(2000)->background('#fff')->width('220px')->padding('5px');

                $this->id = request('id'); // id entete facture
                $this->ref_fact = request('ref'); // reference facture
                //     // ceci au chargement de la page
                $test_facture = factureFournisseurEntete::where('societe',auth()->user()->societe)->where('id',$this->id)->count();    
                if($test_facture > 0){
                    $compte = factureFournisseurEntete::where('societe',auth()->user()->societe)->where('id',$this->id)->first();               
                    $this->ids = $compte->id;
                    $this->reference = $compte->code_facture; // reference facture
                    $this->id_cmd_fourni_entete = $compte->id_commande_fournisseur_entete; 
                    $this->code_commande = $compte->code_commande;
                    $this->idx = $compte->id_commande_fournisseur_entete; // id_commande_fournisseur_entete important pour creer reception 
                    $this->id_recept_fourni_entete = $compte->id_reception_fournisseur_entete; 
                    $this->fournisseur_id = $compte->id_fournisseur;
                    $this->fournisseur = $compte->nom_fournisseur;
                    $this->date_facturation = $compte->date_facturation;
                    $this->date_echeance = $compte->date_echeance;                    
                    $this->mode_reglement = $compte->mode_reglement; 
                    $this->compte_bancaire = $compte->id_compte_bancaire;
                    $this->note = $compte->note;
                    $this->etat = $compte->etat;
                    $this->montant_recu = $compte->montant_recu;
                    $this->etat_reception = $compte->etat_reception;
                    $this->auteur = $compte->nom_user;
                    $this->created_at = $compte->created_at;
                    $this->updated_at = $compte->updated_at;
                    
                }  
                $tier = Tier::where('societe',auth()->user()->societe)->where('id',$this->fournisseur_id)->get(); 
                $banque = CompteBancaire :: where('societe',auth()->user()->societe)->where('etat',1)->get();  
                
                $factFournisseur_ligne = factureFournisseurLigne::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->orderBy($this->orderField, $this->orderDirection)->get();
                $factFournisseurLigneCount = $factFournisseur_ligne->count();

                $montantHT = $factFournisseur_ligne->sum('montant_ht');
                $montantTTC = $factFournisseur_ligne->sum('montant_ttc');
                $montantRemise = $factFournisseur_ligne->sum('montant_remise');
                $montantTva = $factFournisseur_ligne->sum('montant_tva');
                $montantPrecompte = $factFournisseur_ligne->sum('montant_precompte');
                            
                $reglementFournisseur = Reglement_fourni::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->get();
                $dejaRegler = Reglement_fourni::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->sum('montant_regler');
                $reglemtCount = $reglementFournisseur->count();
                
                $this->reste_a_percevoir = number_format($montantTTC - $dejaRegler,0,',','');

                // Parametre
                $test_vide = Parametre ::where('societe',auth()->user()->societe)->count();
                if($test_vide > 0){                
                    $config = Parametre::where('societe',auth()->user()->societe)->limit(1)->get();
                    $id_entrepot = $config[0]->id_entrepot_fctfourni;               
                }
                else{
                    $id_entrepot = 0;
                } 

                // pour afficher les produits en stock
                if($this->type_produit == 'Produit'){ 
                $produit_stock = DB::table('stocks')
                                ->select('id','nom_produit','reference','id_produit','type_produit','nature_produit','categorie',DB::raw('sum(quantite) as quantites, sum(valorisation_achat_total) as valorisationAchatTotal, sum(prix_vente_unitaire) as prixVenteUnitaire ,sum(valeur_vente_total) as valeurVentetotal, max(limite_stock_alerte) as limite_stock_alerte ,max(updated_at) as updated_at')) // Supposons que vous voulez la dernière date
                                ->where('societe',auth()->user()->societe)
                                ->where('id_entrepot',$id_entrepot)
                                // ->where('type_produit','Produit')
                                ->where('type_produit',$this->type_produit)
                                ->where('nom_produit','like','%'.$this->query.'%')
                                ->where('nature_produit','like','%'.$this->parNature.'%')
                                ->where('categorie','like','%'.$this->parCat.'%')
                                ->orderBy('nom_produit', 'ASC')
                                ->groupBy('id','nom_produit','reference','id_produit','type_produit','nature_produit','categorie') // Si 'reference' et 'created_at' sont uniques par produit, vous pouvez les enlever du groupBy
                                ->paginate($this->parPage);
                $produit_stockCount = $produit_stock->count();
                $qteStockTotal = $produit_stock->sum('quantites');
                $valAchatTotal = $produit_stock->sum('valorisationAchatTotal');
                $valPrixVenteUnitaire = $produit_stock->sum('prixVenteUnitaire');
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
                    $valPrixVenteUnitaire = 0;
                    $valVenteTotal = 0;
                    $service_produit = Produit::where('societe',auth()->user()->societe)->paginate($this->parPage);
                    $service_produitCount = 0;
                }

                $taxe = DeviseTva::where('societe',auth()->user()->societe)->orderBy('taux_tva','asc')->get();
                $cmdFourniEntete = CommandeFournisseurEntete::where('societe',auth()->user()->societe)->where('id',$this->id_cmd_fourni_entete)->orderBy('id','desc')->get();
                $recpCltEntete = ReceptionFournisseurEntete::where('societe',auth()->user()->societe)->where('id',$this->id_recept_fourni_entete)->orderBy('id','desc')->get(); 

                $page = 'factureFournisseur'; // Pour evenement lie
                $log = LogActivityModel::where('user_societe',auth()->user()->societe)->where('id_activite', $this->ids)->where('page', $page)->limit(22)->orderBy('id','desc')->get();
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
                // return view('livewire.gestion-facturation.fournisseur.nouv-facture-fournisseur',compact('title_fils','module','lien','dateJour','banque','factFournisseur_ligne','factFournisseurLigneCount','produit_stock','produit_stockCount','qteStockTotal','valAchatTotal','valVenteTotal','taxe','log','logCount',
                // 'montantHT','montantTTC','montantRemise','montantTva','montantPrecompte','reglementFournisseur','dejaRegler','reglemtCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));

                if($this->type_produit == 'Produit'){  
                    return view('livewire.gestion-facturation.fournisseur.nouv-facture-fournisseur',compact('title_fils','module','lien','dateJour','tier','banque','factFournisseur_ligne','factFournisseurLigneCount','produit_stock','produit_stockCount','qteStockTotal','valAchatTotal','valPrixVenteUnitaire','valVenteTotal','taxe','cmdFourniEntete','recpCltEntete','log','logCount',
                    'montantHT','montantTTC','montantRemise','montantTva','montantPrecompte','reglementFournisseur','dejaRegler','reglemtCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
                }
                if($this->type_produit == 'Service'){  
                    return view('livewire.gestion-facturation.fournisseur.nouv-facture-fournisseur',compact('title_fils','module','lien','dateJour','tier','banque','factFournisseur_ligne','factFournisseurLigneCount','service_produit','service_produitCount','taxe','cmdFourniEntete','recpCltEntete','log','logCount',
                    'montantHT','montantTTC','montantRemise','montantTva','montantPrecompte','reglementFournisseur','dejaRegler','reglemtCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
                }
                else{ 
                    return view('livewire.gestion-facturation.fournisseur.nouv-facture-fournisseur',compact('title_fils','module','lien','dateJour','tier','banque','factFournisseur_ligne','factFournisseurLigneCount','produit_stock','produit_stockCount','qteStockTotal','valAchatTotal','valPrixVenteUnitaire','valVenteTotal','service_produit','service_produitCount','taxe','cmdFourniEntete','recpCltEntete','log','logCount',
                    'montantHT','montantTTC','montantRemise','montantTva','montantPrecompte','reglementFournisseur','dejaRegler','reglemtCount'))->layout('components.layouts.app',compact('title','active','champ','choix','entite_mod','dateJour','soldeClient','nbjoursRestant'));
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
                $this->records = Tier::where('etat',1)->where('societe',auth()->user()->societe)->where('nom','like','%'.$this->fournisseur.'%')->orderBy('nom','asc')->limit(8)->get(); 
                $this->recordCount = Tier::where('etat',1)->where('societe',auth()->user()->societe)->where('nom','like','%'.$this->fournisseur.'%')->count();
                $this->showdiv = true;
            }
            else{
                $this->records = Tier::where('etat',1)->where('societe',auth()->user()->societe)->where('telephone','like','%'.$this->fournisseur.'%')->orderBy('nom','asc')->limit(8)->get(); 
                $this->recordCount = Tier::where('etat',1)->where('societe',auth()->user()->societe)->where('telephone','like','%'.$this->fournisseur.'%')->count(); 
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
            $autoriser = $role[0]->modifier_fact_fourni;
            if($autoriser == 1){  
                    
                    $test_tiers = Tier ::where('societe',auth()->user()->societe)->where('id',$this->fournisseur_id)->count();
                    if($test_tiers == 0){
                        if(!empty($this->ids_fournisseur)){
                            // recupere le nom du compte bancaire via son id : $this->compte_bancaire
                            $compteBaq = CompteBancaire::where('societe',auth()->user()->societe)->where('id',$this->compte_bancaire)->first(); 
                            $nom_compte_bancaire = $compteBaq->nom_compte_bancaire;
                            
                            factureFournisseurEntete::find($this->ids)->update(['nom_fournisseur'=>$this->fournisseur,'id_fournisseur'=>$this->ids_fournisseur,'reference'=>$this->reference,'date_facturation'=>$this->date_facturation,'date_echeance'=>$this->date_echeance,
                            'mode_reglement'=>$this->mode_reglement,'compte_bancaire'=>$nom_compte_bancaire,'id_compte_bancaire'=>$this->compte_bancaire,'note'=>$this->note,'societe'=>auth()->user()->societe,
                            'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                            
                            $id_activite = $this->ids;
                            $page = 'factureFournisseur';
                            LogActivity::addToLog('Entête facture » '.$this->fournisseur.' fourni. modifiée', $id_activite, $page);  
                            $this->dispatch('alert',                    
                                title:$this->fournisseur.' modifié(e)!',
                                timer:3000,
                                icon:'success',
                                toast:true,
                                showConfirmButton: false,
                                position:'top-end',
                            );  
                            flash ('Entête facture » <strong>'.$this->fournisseur.'</strong> modifiée')->success(); 
                            $this->redirect('/nouveau_fact_fourni?id='.$this->ids.'&ref='.$this->reference.'&active=7&champ=2-1&choix=1', navigate: true);
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

                                $compteBaq = CompteBancaire::where('societe',auth()->user()->societe)->where('id',$this->compte_bancaire)->first(); 
                                $nom_compte_bancaire = $compteBaq->nom_compte_bancaire;
                                
                                // fournisseur_id de la factureFournisseurEntete
                                factureFournisseurEntete::find($this->ids)->update(['nom_fournisseur'=>$this->fournisseur,'id_fournisseur'=>$this->fournisseur_id,'reference'=>$this->reference,'date_facturation'=>$this->date_facturation,'date_echeance'=>$this->date_echeance,
                                'mode_reglement'=>$this->mode_reglement,'compte_bancaire'=>$nom_compte_bancaire,'id_compte_bancaire'=>$this->compte_bancaire,'note'=>$this->note,'societe'=>auth()->user()->societe,
                                'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                                
                                $id_activite = $this->ids;
                                $page = 'factureFournisseur';
                                LogActivity::addToLog('Entête facture » '.$this->fournisseur.' fourni. modifiée', $id_activite, $page);  
                                $this->dispatch('alert',                    
                                    title:$this->fournisseur.' modifié(e)!',
                                    timer:3000,
                                    icon:'success',
                                    toast:true,
                                    showConfirmButton: false,
                                    position:'top-end',
                                ); 
                                flash ('Entête facture » <strong>'.$this->fournisseur.'</strong> modifiée')->success(); 
                                $this->redirect('/nouveau_fact_fourni?id='.$this->ids.'&ref='.$this->reference.'&active=7&champ=2-1&choix=1', navigate: true);
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

                                    $compteBaq = CompteBancaire::where('societe',auth()->user()->societe)->where('id',$this->compte_bancaire)->first(); 
                                    $nom_compte_bancaire = $compteBaq->nom_compte_bancaire;
                                    
                                    // ids_fournisseur de ajouterTier
                                    factureFournisseurEntete::find($this->ids)->update(['nom_fournisseur'=>$this->fournisseur,'id_fournisseur'=>$this->ids_fournisseur,'reference'=>$this->reference,'date_facturation'=>$this->date_facturation,'date_echeance'=>$this->date_echeance,
                                    'mode_reglement'=>$this->mode_reglement,'compte_bancaire'=>$nom_compte_bancaire,'id_compte_bancaire'=>$this->compte_bancaire,'note'=>$this->note,'societe'=>auth()->user()->societe,
                                    'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                                    
                                    $id_activite = $this->ids;
                                    $page = 'factureFournisseur';
                                    LogActivity::addToLog('Entête facture » '.$this->fournisseur.' fourni. modifiée', $id_activite, $page);  
                                    $this->dispatch('alert',                    
                                        title:$this->fournisseur.' modifié(e)!',
                                        timer:3000,
                                        icon:'success',
                                        toast:true,
                                        showConfirmButton: false,
                                        position:'top-end',
                                    ); 
                                    flash ('Entête facture » <strong>'.$this->fournisseur.'</strong> modifiée')->success(); 
                                    $this->redirect('/nouveau_fact_fourni?id='.$this->ids.'&ref='.$this->reference.'&active=7&champ=2-1&choix=1', navigate: true);
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
     public function enregistrerNote(){  
            $this->validate([            
            'note'=>'max:255',            
        ]);    
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_fact_fourni;
            if($autoriser == 1){  
                factureFournisseurEntete::find($this->ids)->update(['note'=>$this->note,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                $id_activite = $this->ids;
                $page = 'factureFournisseur';
                LogActivity::addToLog('Note facture fourni. modifiée', $id_activite, $page);   
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
        $compte = factureFournisseurEntete::where('societe',auth()->user()->societe)->where('id',$this->ids)->first(); 
        $this->fournisseur_id = $compte->id_fournisseur;
    }
    public function afficheLigne(int $idf){
        $this->ouverture = $idf;
        $this->choix_produit = $idf;         
        $testChoix = Stock::where('societe',auth()->user()->societe)->where('id',$idf)->count();
        if($testChoix > 0){
            // ceci permet d'afficher la quantite entrepot origine
            $choixProd = Stock::where('societe',auth()->user()->societe)->where('id',$idf)->get();
            // $this->id_stock = $choixProd[0]->id;
            $this->prix_moyen_pondere_achat = $choixProd[0]->prix_moyen_pondere_achat;
            $this->prix_vente = $choixProd[0]->prix_vente_unitaire;
            $this->prix_achat = $choixProd[0]->prix_achat_last;
            $this->quantite_bd = $choixProd[0]->quantite;
            $this->id_produit = $choixProd[0]->id_produit;
            $this->nom_produit = $choixProd[0]->nom_produit;
            $this->id_entrepot = $choixProd[0]->id_entrepot;
            $this->referenceProd = $choixProd[0]->reference;
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
            $this->prix_achat = $choixProd[0]->prix_achat;
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
            'prix_achat'=>'required|numeric',
            'remise'=>'required|numeric',
            'tva'=>'required|numeric',
            'precompte'=>'required|numeric',
            'offrir'=>'required|max:3',
            'fournisseur'=>'required',
            'date_facturation'=>'required', // important pour forcer utilisateur a remplir
            'date_echeance'=>'required',    // important pour forcer utilisateur a remplir
            'mode_reglement'=>'required',   // important pour forcer utilisateur a remplir
            // 'compte_bancaire'=>'required',  // important pour forcer utilisateur a remplir            
        ]);    
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_fact_fourni;
            if($autoriser == 1){                        
                if($this->quantite > 0 && $this->prix_achat > 0){ 
                        
                    // ************ Calul ***********
                    $montant_vente =  $this->prix_achat * $this->quantite;                        
                    $montant_achat_avec_qte = ($this->prix_moyen_pondere_achat * $this->quantite);  

                    $remiseDetail = $this->remise/100; // valeur remise
                    $remise_montant = ($this->prix_achat * $this->quantite) * $remiseDetail; // montant remise

                    $montant_remiser_ht = ($this->prix_achat * $this->quantite) - $remise_montant; // Montant vente avec remise inclus hors taxe                        
                    $marge = $montant_remiser_ht - $montant_achat_avec_qte; // Marge avec remise                       

                    $tvaDetail = $this->tva/100; // valeur de la tva
                    $tva_montant = $montant_remiser_ht * $tvaDetail;

                    $precompteDetail = $this->precompte/100; // valeur du precompte
                    $precompte_montant = $montant_remiser_ht * $precompteDetail;

                    $montant_ttc = $montant_remiser_ht + $tva_montant + $precompte_montant; // Montant TTC
                    //************** Fin Calcul ***********//
                    
                    $quantite_recue = $this->quantite;
                    $reste_a_recevoir = 0; 
                    $typeProd = 'Produit';
                    if($this->offrir == 'Non'){ 
                        factureFournisseurLigne::create(['code_facture'=>$this->reference,'id_facture_fournisseur_entete'=>$this->ids,'produit'=>$this->nom_produit,'id_produit'=>$this->id_produit,'reference'=>$this->referenceProd,'type_produit'=>$typeProd,'prix_achat'=>$this->prix_achat,
                                        'prix_vente'=>$this->prix_vente,'quantite'=>$this->quantite,'quantite_recue'=>$quantite_recue,'reste_a_recevoir'=>$reste_a_recevoir,'remise'=>$this->remise,'montant_remise'=>$remise_montant,
                                        'tva'=>$this->tva,'montant_tva'=>$tva_montant,'precompte'=>$this->precompte,'montant_precompte'=>$precompte_montant,'montant_ht'=>$montant_remiser_ht,'montant_ttc'=>$montant_ttc,'marge'=>$marge,'id_entrepot'=>$this->id_entrepot,
                                        'nom_fournisseur'=>$this->fournisseur,'id_fournisseur'=>$this->fournisseur_id,'offrir'=>$this->offrir,'etat'=>$this->etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                     
                    
                        $montantHT = factureFournisseurLigne::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->sum('montant_ht');
                        $montantTTC = factureFournisseurLigne::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->sum('montant_ttc');
                        $montantRemise = factureFournisseurLigne::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->sum('montant_remise');
                        $montantTva = factureFournisseurLigne::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->sum('montant_tva');
                        $montantPrecompte = factureFournisseurLigne::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->sum('montant_precompte');
                        $marge = factureFournisseurLigne::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->sum('marge');

                        // Montant TTC en arrondi en + ou en - 
                        factureFournisseurEntete::find($this->ids)->update(['montant_ht'=>$montantHT,'montant_remise'=>$montantRemise,'montant_tva'=>$montantTva,'montant_precompte'=>$montantPrecompte,'montant_ttc'=>number_format($montantTTC,0,',',''),
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
                        factureFournisseurLigne::create(['code_facture'=>$this->reference,'id_facture_fournisseur_entete'=>$this->ids,'produit'=>$this->nom_produit,'id_produit'=>$this->id_produit,'reference'=>$this->referenceProd,'type_produit'=>$typeProd,'prix_achat'=>$this->prix_achat,
                                        'prix_vente'=>$this->prix_vente,'quantite'=>$this->quantite,'quantite_recue'=>$quantite_recue,'reste_a_recevoir'=>$reste_a_recevoir,'remise'=>$this->remise,'montant_remise'=>$remise_montant,
                                        'tva'=>$this->tva,'montant_tva'=>$tva_montant,'precompte'=>$this->precompte,'montant_precompte'=>$precompte_montant,'montant_ht'=>$montant_remiser_ht,'montant_ttc'=>$montant_ttc,'marge'=>$marge,'id_entrepot'=>$this->id_entrepot,
                                        'nom_fournisseur'=>$this->fournisseur,'id_fournisseur'=>$this->fournisseur_id,'offrir'=>$this->offrir,'etat'=>$this->etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                     
                    
                        $montantHT = factureFournisseurLigne::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->sum('montant_ht');
                        $montantTTC = factureFournisseurLigne::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->sum('montant_ttc');
                        $montantRemise = factureFournisseurLigne::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->sum('montant_remise');
                        $montantTva = factureFournisseurLigne::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->sum('montant_tva');
                        $montantPrecompte = factureFournisseurLigne::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->sum('montant_precompte');
                        $marge = factureFournisseurLigne::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->sum('marge');

                        // Montant TTC en arrondi en + ou en - 
                        factureFournisseurEntete::find($this->ids)->update(['montant_ht'=>$montantHT,'montant_remise'=>$montantRemise,'montant_tva'=>$montantTva,'montant_precompte'=>$montantPrecompte,'montant_ttc'=>number_format($montantTTC,0,',',''),
                                            'reste_a_percevoir'=>number_format($montantTTC,0,',',''),'marge'=>$marge,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                    }
                    $id_activite = $this->ids;
                    $page = 'factureFournisseur';
                    LogActivity::addToLog('Produit ('.$this->nom_produit.') à facturer ajouté', $id_activite, $page);
                    $this->dispatch('alert',                    
                        title:'Produit(s) ajouté(s)!',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );  
                    $this->resetinputFields();
                    // $this->redirect('/nouveau_fact_fourni?id='.$this->ids.'&ref='.$this->reference.'&active=7&champ=2-1&choix=1', navigate: true);
                    
                }
                else{
                    $this->dispatch('alert',                    
                        title:'Désolé, ajoutez une quantité ou prix supérieure à 0',
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
            'prix_achat'=>'required|numeric',
            'remise'=>'required|numeric',
            'tva'=>'required|numeric',
            'precompte'=>'required|numeric',
            'offrir'=>'required|max:3',
            'fournisseur'=>'required',
            'date_facturation'=>'required', // important pour forcer utilisateur a remplir
            'date_echeance'=>'required',    // important pour forcer utilisateur a remplir
            'mode_reglement'=>'required',   // important pour forcer utilisateur a remplir
            // 'compte_bancaire'=>'required',  // important pour forcer utilisateur a remplir            
        ]);    
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_fact_fourni;
            if($autoriser == 1){                        
                if($this->quantite > 0 && $this->prix_achat > 0){ 
                        
                    // ************ Calul ***********
                    $montant_vente =  $this->prix_achat * $this->quantite;                        
                    $montant_achat_avec_qte = ($this->prix_moyen_pondere_achat * $this->quantite);  

                    $remiseDetail = $this->remise/100; // valeur remise
                    $remise_montant = ($this->prix_achat * $this->quantite) * $remiseDetail; // montant remise

                    $montant_remiser_ht = ($this->prix_achat * $this->quantite) - $remise_montant; // Montant vente avec remise inclus hors taxe                        
                    $marge = $montant_remiser_ht - $montant_achat_avec_qte; // Marge avec remise                       

                    $tvaDetail = $this->tva/100; // valeur de la tva
                    $tva_montant = $montant_remiser_ht * $tvaDetail;

                    $precompteDetail = $this->precompte/100; // valeur du precompte
                    $precompte_montant = $montant_remiser_ht * $precompteDetail;

                    $montant_ttc = $montant_remiser_ht + $tva_montant + $precompte_montant; // Montant TTC
                    //************** Fin Calcul ***********//

                    $quantite_recue = $this->quantite;
                    $reste_a_recevoir = 0;
                    $typeProd = 'Service';
                    if($this->offrir == 'Non'){ 
                        factureFournisseurLigne::create(['code_facture'=>$this->reference,'id_facture_fournisseur_entete'=>$this->ids,'produit'=>$this->nom_produit,'id_produit'=>$this->id_produit,'reference'=>$this->referenceProd,'type_produit'=>$typeProd,'prix_achat'=>$this->prix_achat,
                                        'prix_vente'=>$this->prix_vente,'quantite'=>$this->quantite,'quantite_recue'=>$quantite_recue,'reste_a_recevoir'=>$reste_a_recevoir,'remise'=>$this->remise,'montant_remise'=>$remise_montant,
                                        'tva'=>$this->tva,'montant_tva'=>$tva_montant,'precompte'=>$this->precompte,'montant_precompte'=>$precompte_montant,'montant_ht'=>$montant_remiser_ht,'montant_ttc'=>$montant_ttc,'marge'=>$marge,'id_entrepot'=>$this->id_entrepot,
                                        'nom_fournisseur'=>$this->fournisseur,'id_fournisseur'=>$this->fournisseur_id,'offrir'=>$this->offrir,'etat'=>$this->etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                     
                    
                        $montantHT = factureFournisseurLigne::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->sum('montant_ht');
                        $montantTTC = factureFournisseurLigne::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->sum('montant_ttc');
                        $montantRemise = factureFournisseurLigne::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->sum('montant_remise');
                        $montantTva = factureFournisseurLigne::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->sum('montant_tva');
                        $montantPrecompte = factureFournisseurLigne::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->sum('montant_precompte');
                        $marge = factureFournisseurLigne::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->sum('marge');

                        // Montant TTC en arrondi en + ou en - 
                        factureFournisseurEntete::find($this->ids)->update(['montant_ht'=>$montantHT,'montant_remise'=>$montantRemise,'montant_tva'=>$montantTva,'montant_precompte'=>$montantPrecompte,'montant_ttc'=>number_format($montantTTC,0,',',''),
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
                        factureFournisseurLigne::create(['code_facture'=>$this->reference,'id_facture_fournisseur_entete'=>$this->ids,'produit'=>$this->nom_produit,'id_produit'=>$this->id_produit,'reference'=>$this->referenceProd,'type_produit'=>$typeProd,'prix_achat'=>$this->prix_achat,
                                        'prix_vente'=>$this->prix_vente,'quantite'=>$this->quantite,'quantite_recue'=>$quantite_recue,'reste_a_recevoir'=>$reste_a_recevoir,'remise'=>$this->remise,'montant_remise'=>$remise_montant,
                                        'tva'=>$this->tva,'montant_tva'=>$tva_montant,'precompte'=>$this->precompte,'montant_precompte'=>$precompte_montant,'montant_ht'=>$montant_remiser_ht,'montant_ttc'=>$montant_ttc,'marge'=>$marge,'id_entrepot'=>$this->id_entrepot,
                                        'nom_fournisseur'=>$this->fournisseur,'id_fournisseur'=>$this->fournisseur_id,'offrir'=>$this->offrir,'etat'=>$this->etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                     
                    
                        $montantHT = factureFournisseurLigne::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->sum('montant_ht');
                        $montantTTC = factureFournisseurLigne::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->sum('montant_ttc');
                        $montantRemise = factureFournisseurLigne::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->sum('montant_remise');
                        $montantTva = factureFournisseurLigne::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->sum('montant_tva');
                        $montantPrecompte = factureFournisseurLigne::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->sum('montant_precompte');
                        $marge = factureFournisseurLigne::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->sum('marge');

                        // Montant TTC en arrondi en + ou en - 
                        factureFournisseurEntete::find($this->ids)->update(['montant_ht'=>$montantHT,'montant_remise'=>$montantRemise,'montant_tva'=>$montantTva,'montant_precompte'=>$montantPrecompte,'montant_ttc'=>number_format($montantTTC,0,',',''),
                                            'reste_a_percevoir'=>number_format($montantTTC,0,',',''),'marge'=>$marge,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                    }
                    $id_activite = $this->ids;
                    $page = 'factureFournisseur';
                    LogActivity::addToLog('Produit ('.$this->nom_produit.') à facturer ajouté', $id_activite, $page);
                    $this->dispatch('alert',                    
                        title:'Produit(s) ajouté(s)!',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );  
                    $this->resetinputFields();
                    // $this->redirect('/nouveau_fact_fourni?id='.$this->ids.'&ref='.$this->reference.'&active=7&champ=2-1&choix=1', navigate: true);
                    
                }
                else{
                    $this->dispatch('alert',                    
                        title:'Désolé, ajoutez une quantité ou prix supérieure à 0',
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
        $this->redirect('/nouveau_fact_fourni?id='.$this->ids.'&ref='.$this->reference.'&active=7&champ=2-1&choix=1', navigate: true);
    }
    public function confirmerDelete($id){
        $this->confirmer = $id;        
    } 
    public function supprimer($id){
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->modifier_fact_fourni;
            if($autoriser == 1){   
                if($id){                    
                    factureFournisseurLigne::where('id',$id)->delete();

                    $montantHT = factureFournisseurLigne::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->sum('montant_ht');
                    $montantTTC = factureFournisseurLigne::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->sum('montant_ttc');
                    $montantRemise = factureFournisseurLigne::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->sum('montant_remise');
                    $montantTva = factureFournisseurLigne::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->sum('montant_tva');
                    $montantPrecompte = factureFournisseurLigne::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->sum('montant_precompte');
                    $marge = factureFournisseurLigne::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->sum('marge');

                    $dejaRegler = Reglement_fourni::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->sum('montant_regler');
                    $reste_a_percevoir = $montantTTC - $dejaRegler;
                    
                    factureFournisseurEntete::find($this->ids)->update(['montant_ht'=>number_format($montantHT,0,',',''),'montant_remise'=>$montantRemise,'montant_tva'=>$montantTva,
                                        'montant_precompte'=>$montantPrecompte,'marge'=>$marge,'montant_ttc'=>number_format($reste_a_percevoir,0,',',''),'societe'=>auth()->user()->societe,
                                        'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                    
                    
                    $id_activite = $this->ids;
                    $page = 'factureFournisseur';
                    LogActivity::addToLog('Ligne de facture fourni. supprimé', $id_activite, $page);
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
            $autoriser = $role[0]->modifier_fact_fourni;
            if($autoriser == 1){
                $etat = 'Impayée';
                $statut = 0;  // tres important pour permettre a la commande de passer a une nouvelle facture au niveau de creer commande
                factureFournisseurEntete::find($this->ids)->update(['etat'=>$etat,'statut'=>$statut,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);      
                factureFournisseurLigne::where('id_facture_fournisseur_entete',$this->ids)->update(['etat'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                // ExpeditionClientEntete::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->update(['etat_facture'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                
                $etats = 'Validée';
                $dates = date('dmy/His');
                // $length = 2;
                // $token = bin2hex(random_bytes($length));          
                $token_ok = 'FACT-RCP/'.$dates;
                ReceptionFournisseurLigne::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->delete();
                
                // Creer ligne expedition dans commande                
                $ligneFactFournisseur = factureFournisseurLigne::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->get(); 
                foreach($ligneFactFournisseur as $ligneFactFournisseurs){
                    // creation et copie entete Expedition Client Ligne
                    ReceptionFournisseurLigne::create([ 
                        'code_reception'=>$token_ok,
                        'code_facture'=>$ligneFactFournisseurs->code_facture,
                        'id_facture_fournisseur_entete'=>$ligneFactFournisseurs->id_facture_fournisseur_entete,
                        'nom_fournisseur'=>$ligneFactFournisseurs->nom_fournisseur,
                        'id_fournisseur'=>$ligneFactFournisseurs->id_fournisseur,
                        'produit'=>$ligneFactFournisseurs->produit,
                        'id_produit'=>$ligneFactFournisseurs->id_produit,
                        'reference'=>$ligneFactFournisseurs->reference,
                        'type_produit'=>$ligneFactFournisseurs->type_produit,                        
                        'prix_achat'=>$ligneFactFournisseurs->prix_achat,
                        'prix_vente'=>$ligneFactFournisseurs->prix_vente,
                        'quantite'=>$ligneFactFournisseurs->quantite,
                        // 'quantite_recue'=>$ligneFactFournisseurs->quantite_recue,
                        'quantite_recue'=>0,
                        'reste_a_recevoir'=>$ligneFactFournisseurs->reste_a_recevoir,
                        // 'quantite_facturee'=>$ligneFactFournisseurs->quantite_recue,
                        // 'reste_a_facturer'=>$ligneFactFournisseurs->reste_a_recevoir, 
                        'remise'=>$ligneFactFournisseurs->remise,
                        'montant_remise'=>$ligneFactFournisseurs->montant_remise,
                        'tva'=>$ligneFactFournisseurs->tva,
                        'montant_tva'=>$ligneFactFournisseurs->montant_tva,
                        'precompte'=>$ligneFactFournisseurs->precompte,
                        'montant_precompte'=>$ligneFactFournisseurs->montant_precompte,
                        'montant_ht'=>$ligneFactFournisseurs->montant_ht,
                        'montant_ttc'=>$ligneFactFournisseurs->montant_ttc,
                        'marge'=>$ligneFactFournisseurs->marge,
                        'id_entrepot'=>$ligneFactFournisseurs->id_entrepot,
                        'offrir'=>$ligneFactFournisseurs->offrir,
                        'etat'=>$etats,                        
                        'etat_facture'=>$ligneFactFournisseurs->etat, 
                        'user_id'=>auth()->user()->id,
                        'nom_user'=>auth()->user()->name,
                        'societe'=>auth()->user()->societe]);
                }
                //  Fin     
                
                $id_activite = $this->ids;
                $page = 'factureFournisseur';
                LogActivity::addToLog('Facture ('.$this->reference.') fournisseur validée', $id_activite, $page);
                $this->dispatch('alert',                    
                    title:'Facture sous la référence <strong>'.$this->reference.'</strong> validée!',
                    timer:53000,
                    icon:'success',
                    toast:true,
                    showConfirmButton: false,
                    position:'top-end',
                );                     
                $this->redirect('/nouveau_fact_fourni?id='.$this->ids.'&active=7&champ=2-1&choix=1', navigate: true);
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
        factureFournisseurEntete::find($this->ids)->update(['etat'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);      
        factureFournisseurLigne::where('id_facture_fournisseur_entete',$this->ids)->update(['etat'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
        ReceptionFournisseurEntete::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->update(['etat_facture'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
        $id_activite = $this->ids;
        $page = 'factureFournisseur';
        LogActivity::addToLog('Facture ('.$this->reference.') fournisseur en brouillon', $id_activite, $page);
        $this->dispatch('alert',                    
            title:'Facture sous la référence <strong>'.$this->reference.'</strong> retournée au brouillon!',
            timer:53000,
            icon:'success',
            toast:true,
            showConfirmButton: false,
            position:'top-end',
        );       
        $this->redirect('/nouveau_fact_fourni?id='.$this->ids, navigate: true);        
    } 
    public function afficheRegler(){
        $comptes = factureFournisseurEntete::where('societe',auth()->user()->societe)->where('id',$this->ids)->first(); 
                $this->date_reglement = $comptes->date_facturation;
                $this->reference = $comptes->code_facture;
                $montant_ttc = $comptes->montant_ttc;
                $montant_recu = $comptes->montant_recu;
                $this->reste_a_percevoir = $montant_ttc - $montant_recu;
    } 
    public function coller(){
        $comptes = factureFournisseurEntete::where('societe',auth()->user()->societe)->where('id',$this->ids)->first();               
                $montant_ttc = $comptes->montant_ttc;
                $montant_recu = $comptes->montant_recu;
                $this->montant_reglement = $montant_ttc - $montant_recu;               
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
            $autoriser = $role[0]->creer_reglement_fourni;
            if($autoriser == 1){         
                    $compte = factureFournisseurEntete::where('societe',auth()->user()->societe)->where('id',$this->ids)->first();               
                    // $this->ids = $compte->id;
                    $fournisseur_id = $compte->id_fournisseur;
                    $fournisseur = $compte->nom_fournisseur;

                    if($this->reste_a_percevoir > 0){
                        
                        if($this->montant_reglement >= $this->reste_a_percevoir){
                                       
                            // Compte bancaire
                            $CompteBq = CompteBancaire::where('societe',auth()->user()->societe)->where('id',$this->compte_bancaire)->first();   
                            $nom_compte_bancaire = $CompteBq->nom_compte_bancaire;

                            // Ecriture bancaire
                            $ref_ecritureBq = date('ymd-His');
                            $description = 'Règlement fournisseur';
                            $date_valeur = date('Y-m-d');
                            $date_operation = date('Y-m-d');
                            $credit = 0; 
                            $solde = 0;  
                            $type_paiement = 'ReglementFournisseur';              
                            EcritureBancaire::create(['id_compte_bancaire'=>$this->compte_bancaire,'id_type_paiement'=>$this->compte_bancaire,'nom_compte_bancaire'=>$nom_compte_bancaire,'reference'=>$ref_ecritureBq,'description'=>$description,
                                            'date_operation'=>$date_operation,'date_valeur'=>$date_valeur,'type_operation'=>$this->mode_reglement,'debit'=>number_format($this->reste_a_percevoir,0,',',''),'credit'=>$credit,'solde'=>$solde,
                                            'type_paiement'=>$type_paiement,'id_facture_fournisseur_entete'=>$this->ids,'code_facture'=>$this->reference,'id_tiers'=>$fournisseur_id,'tiers'=>$fournisseur,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                    

                            // ceci recupere le dernier enregistrement cree a l'instant
                            $dernier_id = EcritureBancaire::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id; 

                            // ceci calcul le solde
                            $soldeCredit = EcritureBancaire::where('societe',auth()->user()->societe)->where('id_compte_bancaire',$this->compte_bancaire)->sum('credit');
                            $soldeDebit = EcritureBancaire::where('societe',auth()->user()->societe)->where('id_compte_bancaire',$this->compte_bancaire)->sum('debit');  
                            $solde = $soldeCredit - $soldeDebit;
                            CompteBancaire::where('societe',auth()->user()->societe)->where('id',$this->compte_bancaire)->update(['solde'=>$solde,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                            // Reglement 
                            $refReglement = 'SPAY'.date('ymd-His');
                            Reglement_fourni::create(['ref_reglement'=>$refReglement,'id_facture_fournisseur_entete'=>$this->ids,'code_facture'=>$this->reference,'id_fournisseur'=>$this->fournisseur_id,'nom_fournisseur'=>$this->fournisseur,'id_ecriture_bancaire'=>$dernier_id,'ecriture_bancaire'=>$ref_ecritureBq,
                                            'mode_reglement'=>$this->mode_reglement,'compte_bancaire'=>$nom_compte_bancaire,'id_compte_bancaire'=>$this->compte_bancaire,'date_reglement'=>$this->date_reglement,
                                            'num_cheq_virement'=>$this->num_cheq_virement,'emetteur_cheq_virement'=>$this->emeteur,'banque_cheq_virement'=>$this->banque_cheque,'commentaire'=>$this->commentaire,
                                            'montant_regler'=>number_format($this->reste_a_percevoir,0,',',''),'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                            $dejaRegler = Reglement_fourni::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->sum('montant_regler');                            

                            // Facture entete
                            $etat ='Payée';
                            $resteApercevoir = 0;
                            factureFournisseurEntete::find($this->ids)->update(['montant_recu'=>number_format($dejaRegler,0,',',''),'reste_a_percevoir'=>number_format($resteApercevoir,0,',',''),
                                                'etat'=>$etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                            factureFournisseurLigne::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->update(['etat'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                            ReceptionFournisseurEntete::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->update(['etat_facture'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 

                            $id_activite = $this->ids;
                            $page = 'factureFournisseur';
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
                            $this->redirect('/nouveau_fact_fourni?id='.$this->ids.'&ref='.$this->reference.'&active=7&champ=2-1&choix=1', navigate: true); // ceci permet d'actualiser ou mettre a jour les donnees du modal sans fermer 
                        }
                        else{
                            
                            // Compte bancaire
                            $CompteBq = CompteBancaire::where('societe',auth()->user()->societe)->where('id',$this->compte_bancaire)->first();   
                            $nom_compte_bancaire = $CompteBq->nom_compte_bancaire;

                            // Ecriture bancaire
                            $ref_ecritureBq = date('ymd-His');
                            $description = 'Règlement fournisseur';
                            $date_valeur = date('Y-m-d');
                            $date_operation = date('Y-m-d');
                            $credit = 0;   
                            $solde = 0;  
                            $type_paiement = 'ReglementFournisseur';               
                            EcritureBancaire::create(['id_compte_bancaire'=>$this->compte_bancaire,'id_type_paiement'=>$this->compte_bancaire,'nom_compte_bancaire'=>$nom_compte_bancaire,'reference'=>$ref_ecritureBq,'description'=>$description,
                                            'date_operation'=>$date_operation,'date_valeur'=>$date_valeur,'type_operation'=>$this->mode_reglement,'debit'=>number_format($this->montant_reglement,0,',',''),'credit'=>$credit,'solde'=>$solde,
                                            'type_paiement'=>$type_paiement,'id_facture_fournisseur_entete'=>$this->ids,'code_facture'=>$this->reference,'id_tiers'=>$fournisseur_id,'tiers'=>$fournisseur,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                    
                            
                            // ceci recupere le dernier enregistrement cree a l'instant
                            $dernier_id = EcritureBancaire::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id; 

                            // ceci calcul le solde
                            $soldeCredit = EcritureBancaire::where('societe',auth()->user()->societe)->where('id_compte_bancaire',$this->compte_bancaire)->sum('credit');
                            $soldeDebit = EcritureBancaire::where('societe',auth()->user()->societe)->where('id_compte_bancaire',$this->compte_bancaire)->sum('debit');  
                            $solde = $soldeCredit - $soldeDebit;
                            CompteBancaire::where('societe',auth()->user()->societe)->where('id',$this->compte_bancaire)->update(['solde'=>$solde,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                            
                            // Reglement 
                            $refReglement = 'SPAY'.date('ymd-His');
                            Reglement_fourni::create(['ref_reglement'=>$refReglement,'id_facture_fournisseur_entete'=>$this->ids,'code_facture'=>$this->reference,'id_fournisseur'=>$this->fournisseur_id,'nom_fournisseur'=>$this->fournisseur,'id_ecriture_bancaire'=>$dernier_id,'ecriture_bancaire'=>$ref_ecritureBq,
                                            'mode_reglement'=>$this->mode_reglement,'compte_bancaire'=>$nom_compte_bancaire,'id_compte_bancaire'=>$this->compte_bancaire,'date_reglement'=>$this->date_reglement,
                                            'num_cheq_virement'=>$this->num_cheq_virement,'emetteur_cheq_virement'=>$this->emeteur,'banque_cheq_virement'=>$this->banque_cheque,'commentaire'=>$this->commentaire,
                                            'montant_regler'=>number_format($this->montant_reglement,0,',',''),'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);
                                            
                            $dejaRegler = Reglement_fourni::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->sum('montant_regler');

                            // Facture entete
                            $etat ='Commencée';
                            $reste = $this->reste_a_percevoir - $this->montant_reglement;
                            factureFournisseurEntete::find($this->ids)->update(['montant_recu'=>number_format($dejaRegler,0,',',''),'reste_a_percevoir'=>number_format($reste,0,',',''),
                                                'etat'=>$etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                            factureFournisseurLigne::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->update(['etat'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                            ReceptionFournisseurEntete::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->update(['etat_facture'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 

                            $id_activite = $this->ids;
                            $page = 'factureFournisseur';
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
                            $this->redirect('/nouveau_fact_fourni?id='.$this->ids.'&ref='.$this->reference.'&active=7&champ=2-1&choix=1', navigate: true); // ceci permet d'actualiser ou mettre a jour les donnees du modal sans fermer 
                        }

                    }
                    else{

                        $this->dispatch('alert',                    
                            title:'Désolé, vous ne pouvez plus effectuer de paiement ('.$this->reste_a_percevoir.' '.$this->devise.')',
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
            $autoriser = $role[0]->supprimer_reglement_fourni;
            if($autoriser == 1){   
                if($id){
                    $MontantRegler = Reglement_fourni::where('societe',auth()->user()->societe)->where('id',$id)->sum('montant_regler');
                    $factFournisseur = factureFournisseurEntete::where('societe',auth()->user()->societe)->where('id',$this->ids)->first();
                    $montant_ttc = $factFournisseur->montant_ttc;
                    $reste_a_percevoir = $factFournisseur->reste_a_percevoir;
                    $montant_recu = $factFournisseur->montant_recu;

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
                    factureFournisseurEntete::find($this->ids)->update(['montant_recu'=>number_format($montantRecu_ok,0,',',''),'reste_a_percevoir'=>number_format($reste,0,',',''),
                    'etat'=>$etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                    $Regler = Reglement_fourni::where('societe',auth()->user()->societe)->where('id',$id)->first();
                    $id_regle = $Regler->id_ecriture_bancaire;
                    
                    Reglement_fourni::where('id',$id)->delete();
                    EcritureBancaire::where('id',$id_regle)->delete();

                    // ceci calcul le solde                    
                    $soldeCredit = EcritureBancaire::where('societe',auth()->user()->societe)->where('id_compte_bancaire',$id_cpteBq)->sum('credit');
                    $soldeDebit = EcritureBancaire::where('societe',auth()->user()->societe)->where('id_compte_bancaire',$id_cpteBq)->sum('debit');  
                    $solde = $soldeCredit - $soldeDebit;
                    CompteBancaire::where('societe',auth()->user()->societe)->where('id',$id_cpteBq)->update(['solde'=>$solde,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);

                    $id_activite = $this->ids;
                    $page = 'factureFournisseur';
                    LogActivity::addToLog('Ligne règlement facture fourni. supprimé', $id_activite, $page);
                    $this->dispatch('alert',                    
                        title:'Suppression effectuée!',
                        timer:3000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );      
                    $this->redirect('/nouveau_fact_fourni?id='.$this->ids.'&ref='.$this->reference.'&active=7&champ=2-1&choix=1', navigate: true);                         
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
        $testPrecedant = factureFournisseurEntete::where('societe',auth()->user()->societe)->where('id','<',$this->ids)->orderBy('id','desc')->count();
        if($testPrecedant > 0){ 
            $precedant = factureFournisseurEntete::where('societe',auth()->user()->societe)->where('id','<',$this->ids)->orderBy('id','desc')->first();        
            $previous = $precedant->id; 
            $this->redirect('/nouveau_fact_fourni?id='.$previous.'&ref='.$this->reference.'&active=7&champ=2-1&choix=1', navigate: true);              
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
        
        $testSuivant = factureFournisseurEntete::where('societe',auth()->user()->societe)->where('id','>',$this->ids)->orderBy('id','asc')->count();
        if($testSuivant > 0){
            $suivant = factureFournisseurEntete::where('societe',auth()->user()->societe)->where('id','>',$this->ids)->orderBy('id','asc')->first();
            $next = $suivant->id;             
            $this->redirect('/nouveau_fact_fourni?id='.$next.'&ref='.$this->reference.'&active=7&champ=2-1&choix=1', navigate: true);                     
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
    public function reception(){ 
        $test = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->count();
        if($test > 0){ 
            $role = Role::where('societe',auth()->user()->societe)->where('nom',auth()->user()->type_user)->get();
            $autoriser = $role[0]->creer_reception;
            if($autoriser == 1){       
                $test_recept = ReceptionFournisseurEntete::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->count();
                if($test_recept == 0){           
                    $etat = 'Clôturée';
                    $etat_cmd = 'Validée';
                    $dates = date('dmy/His');
                    $length = 2;
                    $token = bin2hex(random_bytes($length));
                    $token_ok = 'RCP/'.$dates;
                    // $token_ok = 'RCP/'.$dates.'/'.$token;

                    // Creation entete ReceptionFournisseurEntete
                    $enteteFactFournisseur = factureFournisseurEntete::where('societe',auth()->user()->societe)->where('id',$this->ids)->get(); 
                    foreach($enteteFactFournisseur as $enteteFactFournisseurs){
                        // creation et copie entete Expedition Client Entete
                        ReceptionFournisseurEntete::create([                        
                            'code_reception'=>$token_ok,
                            'id_facture_fournisseur_entete'=>$enteteFactFournisseurs->id,
                            'code_facture'=>$enteteFactFournisseurs->code_facture,
                            'code_commande'=>$enteteFactFournisseurs->code_commande,
                            'id_commande_fournisseur_entete'=>$enteteFactFournisseurs->id_commande_fournisseur_entete,
                            'nom_fournisseur'=>$enteteFactFournisseurs->nom_fournisseur,
                            'id_fournisseur'=>$enteteFactFournisseurs->id_fournisseur,
                            'date_facturation'=>$enteteFactFournisseurs->date_facturation,
                            'date_echeance'=>$enteteFactFournisseurs->date_echeance,
                            'note'=>$enteteFactFournisseurs->note,
                            'montant_ht'=>$enteteFactFournisseurs->montant_ht,
                            'montant_remise'=>$enteteFactFournisseurs->montant_remise,
                            'montant_tva'=>$enteteFactFournisseurs->montant_tva,
                            'montant_precompte'=>$enteteFactFournisseurs->montant_precompte,
                            'montant_ttc'=>$enteteFactFournisseurs->montant_ttc,
                            'marge'=>$enteteFactFournisseurs->marge,
                            'montant_recu'=>$enteteFactFournisseurs->montant_recu,
                            'reste_a_percevoir'=>$enteteFactFournisseurs->reste_a_percevoir,
                            'etat'=>$etat,
                            'etat_cmd'=>$etat_cmd,                            
                            'etat_facture'=>$enteteFactFournisseurs->etat,
                            'societe'=>auth()->user()->societe,
                            'nom_user'=>auth()->user()->name,
                            'user_id'=>auth()->user()->id]);

                        $dernier_id = ReceptionFournisseurEntete::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id;
                        // reception ligne partiel
                        $test_ligPart = ReceptionFournisseurLignePartiel::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->count(); 
                        if($test_ligPart > 0){
                            ReceptionFournisseurLignePartiel::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->delete();
                        }
                        $ligneFactFournisseur = factureFournisseurLigne::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->get(); 
                        foreach($ligneFactFournisseur as $ligneFactFournisseurs){
                            
                            $id_exp = $ligneFactFournisseurs->id;  
                            $quantites = $ligneFactFournisseurs->quantite;           
                            $quantite_recue = $ligneFactFournisseurs->quantite_recue;
                            $id_produit = $ligneFactFournisseurs->id_produit;
                            $id_entrepot = $ligneFactFournisseurs->id_entrepot;
                            $id_fact_fourni_entete = $ligneFactFournisseurs->id_facture_fournisseur_entete;
                            $type_produit = $ligneFactFournisseurs->type_produit;
                            $prix_achat = $ligneFactFournisseurs->prix_achat;
                            
                            if($type_produit == 'Produit'){
                                // CUMP
                                $produit = Produit::where('societe',auth()->user()->societe)->where('id',$id_produit)->first();
                                $id_produit = $produit->id;
                                $prix_achat_bd = $produit->prix_achat;                                    

                                $stoc = Stock::where('societe',auth()->user()->societe)->where('id_entrepot',$id_entrepot)->where('id_produit',$id_produit)->first();                         
                                $id_stock = $stoc->id;
                                $quantite_stock_bd = $stoc->quantite;
                                
                                $valeurInitiale = ($prix_achat_bd * $quantite_stock_bd);
                                $valeurEntree = ($prix_achat * $quantite_recue);
                                $stockGlobal = $quantite_stock_bd + $quantite_recue;
                                $prix_moyen_pondere_achat = ($valeurInitiale + $valeurEntree) / $stockGlobal; //CUMP
                                // Fin CUMP

                                Stock::where('societe',auth()->user()->societe)->where('id_entrepot',$id_entrepot)->where('id_produit',$id_produit)->update(['prix_achat_last'=>$prix_achat,'prix_moyen_pondere_achat'=>$prix_moyen_pondere_achat]);

                                $stockTrouver = Stock::where('societe',auth()->user()->societe)->where('id_entrepot',$id_entrepot)->where('id_produit',$id_produit)->first();
                                $nom_produit = $stockTrouver->nom_produit;
                                // $id_produit = $stockTrouver->id_produit;
                                $reference = $stockTrouver->reference;
                                $qteSockFinal = $stockTrouver->quantite + $quantite_recue;
                                $valorisation_achat_total = $stockTrouver->prix_moyen_pondere_achat * $qteSockFinal;
                                $valeur_vente_total = $stockTrouver->prix_vente_unitaire * $qteSockFinal;

                                Stock::where('societe',auth()->user()->societe)->where('id_entrepot',$id_entrepot)->where('id_produit',$id_produit)->update(['quantite'=>$qteSockFinal,'valorisation_achat_total'=>$valorisation_achat_total,'valeur_vente_total'=>$valeur_vente_total,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);   
                             } 
                           
                            factureFournisseurEntete::where('societe',auth()->user()->societe)->where('id',$this->ids)->update(['id_reception_fournisseur_entete'=>$dernier_id,'code_reception'=>$token_ok,'etat_reception'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);  
                            
                            if($this->idx){
                                $exped = ReceptionFournisseurLigne::where('societe',auth()->user()->societe)->where('id_commande_fournisseur_entete',$this->idx)->where('id_produit',$id_produit)->first();
                                // $qte_receptBD = $exped->quantite_recue;                                
                                $qte_receptBD = $exped ? $exped->quantite_recue : 0; 
                            }                            
                            else{
                                $qte_receptBD = 0;
                            }                                             
                            
                            $quantite_recueOk = $quantite_recue + $qte_receptBD;
                            $reste_a_recevoirs = $quantites - $quantite_recueOk;                            

                            if($this->idx){                          
                                ReceptionFournisseurLigne::where('societe',auth()->user()->societe)->where('id_commande_fournisseur_entete',$this->idx)->where('id_produit',$id_produit)->update(['quantite_recue'=>$quantite_recueOk,'reste_a_recevoir'=>$reste_a_recevoirs,
                                                                                                                                                                                         'etat'=>$etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,
                                                                                                                                                                                         'user_id'=>auth()->user()->id]);   
                            }
                            elseif($this->ids){
                                ReceptionFournisseurLigne::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->where('id_produit',$id_produit)->update(['quantite_recue'=>$quantite_recueOk,'reste_a_recevoir'=>$reste_a_recevoirs,
                                                                                                                                                                                        'etat'=>$etat,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,
                                                                                                                                                                                        'user_id'=>auth()->user()->id]);   
                            }

                            if($type_produit == 'Produit'){
                                $Entrepo = Entrepot::where('societe',auth()->user()->societe)->where('id',$id_entrepot)->first();                    
                                $nom_entrepot = $Entrepo->nom; 
                            }

                            // Verifier l'etat et valider (Clôturée ou Partiel)
                            if($this->idx){
                                $charge = ReceptionFournisseurLigne::where('societe',auth()->user()->societe)->where('id_commande_fournisseur_entete',$this->idx)->get();
                                $QteCmderTotal = $charge->sum('quantite');
                                $QteRecueTotal = $charge->sum('quantite_recue');
                                if($QteCmderTotal == $QteRecueTotal){
                                    $etat = 'Clôturée'; 
                                    CommandeFournisseurEntete::where('societe',auth()->user()->societe)->where('id',$this->idx)->update(['etat_reception'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                                }
                                else{
                                    $etat = 'Partiel'; 
                                    CommandeFournisseurEntete::where('societe',auth()->user()->societe)->where('id',$this->idx)->update(['etat_reception'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                                }
                            }
                            elseif($this->ids){
                                $charge = ReceptionFournisseurLigne::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->get();
                                $QteCmderTotal = $charge->sum('quantite');
                                $QteRecueTotal = $charge->sum('quantite_recue');
                                if($QteCmderTotal == $QteRecueTotal){
                                    $etat = 'Clôturée'; 
                                    CommandeFournisseurEntete::where('societe',auth()->user()->societe)->where('id',$this->idx)->update(['etat_reception'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                                }
                                else{
                                    $etat = 'Partiel'; 
                                    CommandeFournisseurEntete::where('societe',auth()->user()->societe)->where('id',$this->idx)->update(['etat_reception'=>$etat,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                                }
                            }                            
                            // Fin

                            $libele_mouvement = 'Réception';           
                            $code_mouvement = date('YmdHis');
                            $statut = 'RCP';
                            
                            if($type_produit == 'Produit'){
                                Mouvement::create(['id_entrepot'=>$id_entrepot,'nom_produit'=>$nom_produit,'id_produit'=>$id_produit,'reference'=>$reference,'quantite'=>$quantite_recue,'libele_mouvement'=>$libele_mouvement,
                                'code_mouvement'=>$code_mouvement,'statut'=>$statut,'origine'=>$token_ok,'id_reception'=>$dernier_id,'entrepot'=>$nom_entrepot,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 
                            }                         
                            // creation et copie entete Expedition Client Ligne
                            ReceptionFournisseurLignePartiel::create([
                                'id_reception_fournisseur_entete'=>$dernier_id, 
                                'code_reception'=>$token_ok,                    
                                'code_facture'=>$ligneFactFournisseurs->code_facture,
                                'id_facture_fournisseur_entete'=>$ligneFactFournisseurs->id_facture_fournisseur_entete,
                                
                                // ceci vient de enteteFactFournisseurs
                                'code_commande'=>$enteteFactFournisseurs->code_commande,
                                'id_commande_fournisseur_entete'=>$enteteFactFournisseurs->id_commande_fournisseur_entete,

                                'produit'=>$ligneFactFournisseurs->produit,
                                'id_produit'=>$ligneFactFournisseurs->id_produit,
                                'reference'=>$ligneFactFournisseurs->reference,
                                'type_produit'=>$ligneFactFournisseurs->type_produit,
                                'prix_achat'=>$ligneFactFournisseurs->prix_achat,
                                'prix_vente'=>$ligneFactFournisseurs->prix_vente,
                                'quantite'=>$ligneFactFournisseurs->quantite,
                                'quantite_total_recue'=>$quantite_recueOk, // quantite total expediee
                                'quantite_recue'=>$quantite_recueOk,
                                'reste_a_recevoir'=>$reste_a_recevoirs,
                                'remise'=>$ligneFactFournisseurs->remise,
                                'montant_remise'=>$ligneFactFournisseurs->montant_remise,
                                'tva'=>$ligneFactFournisseurs->tva,
                                'montant_tva'=>$ligneFactFournisseurs->montant_tva,
                                'precompte'=>$ligneFactFournisseurs->precompte,
                                'montant_precompte'=>$ligneFactFournisseurs->montant_precompte,
                                'montant_ht'=>$ligneFactFournisseurs->montant_ht,
                                'montant_ttc'=>$ligneFactFournisseurs->montant_ttc,
                                'marge'=>$ligneFactFournisseurs->marge,
                                'id_entrepot'=>$ligneFactFournisseurs->id_entrepot,
                                'nom_fournisseur'=>$ligneFactFournisseurs->nom_fournisseur,
                                'id_fournisseur'=>$ligneFactFournisseurs->id_fournisseur,
                                'offrir'=>$ligneFactFournisseurs->offrir,
                                'etat'=>$etat,
                                'etat_facture'=>$ligneFactFournisseurs->etat, 
                                'user_id'=>auth()->user()->id,
                                'nom_user'=>auth()->user()->name,
                                'societe'=>auth()->user()->societe]);
                        } 
                    }
                    $id_activite = $dernier_id;
                    $page = 'ReceptionFournisseur';
                    LogActivity::addToLog('Réception cloturée', $id_activite, $page); 
                    $this->dispatch('alert',                    
                        title:'Réception effectuée et cloturée avec succes!',
                        timer:5000,
                        icon:'success',
                        toast:true,
                        showConfirmButton: false,
                        position:'top-end',
                    );
                    $this->redirect('/detail_reception_fourni?id='.$dernier_id.'&ref='.$token_ok.'&active=6&champ=2-1&choix=2', navigate: true);
                }
                else{
                    $this->dispatch('alert',                    
                        title:'Désolé, cette réception a été cloturée!',
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
            $autoriser = $role[0]->supprimer_fact_fourni;
            if($autoriser == 1){ 
                $reglementClient = Reglement_fourni::where('societe',auth()->user()->societe)->where('id_facture_fournisseur_entete',$this->ids)->count();  
                if($reglementClient == 0){                  
                        // suppression definitive et redirection
                        $page = 'factureFournisseur';
                        $vide = '';
                        $nulle = NULL;
                        ReceptionFournisseurEntete::where('societe',auth()->user()->societe)->where('id',$this->id_recept_fourni_entete)->update(['code_facture'=>$vide,'id_facture_fournisseur_entete'=>$nulle,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);                     
                        $VerifiCompte = CommandeFournisseurEntete::where('societe',auth()->user()->societe)->where('id',$this->idx)->count();               
                        if($VerifiCompte > 0){
                            $compte = CommandeFournisseurEntete::where('societe',auth()->user()->societe)->where('id',$this->idx)->first();               
                            $nbre_facture = $compte->nbre_facture; 
                            $NbrefactClt = $nbre_facture - 1;
                        }
                        else{
                            $NbrefactClt = 0;
                        }
                        CommandeFournisseurEntete::where('societe',auth()->user()->societe)->where('id',$this->idx)->update(['nbre_facture'=>$NbrefactClt,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]); 

                        ReceptionFournisseurLigne::where('id_facture_fournisseur_entete',$this->ids)->delete(); 
                        factureFournisseurEntete::where('id',$this->ids)->delete(); 
                        factureFournisseurLigne::where('id_facture_fournisseur_entete',$this->ids)->delete();
                        LogActivityModel::where('id_activite',$this->ids)->where('page',$page)->delete();

                        $id_activite = $this->ids;
                        LogActivity::addToLog('Facture fournisseur supprimée définitivement', $id_activite, $page); 
                        $this->dispatch('alert',                    
                            title:'Facture supprimée avec succes!',
                            timer:5000,
                            icon:'success',
                            toast:true,
                            showConfirmButton: false,
                            position:'top-end',
                        );                        
                        flash ('La <strong>facture fourniseur</strong> a été supprimée!')->success(); 
                        $this->redirect('/listing_fact_fourni?active=7&champ=2-1&choix=1', navigate: true);
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
        $test_facture = CommandeFournisseurEntete::where('societe',auth()->user()->societe)->where('id',$id)->count();    
        if($test_facture > 0){
            $compte = CommandeFournisseurEntete::where('societe',auth()->user()->societe)->where('id',$id)->first();               
            $this->ids = $compte->id;           
            $this->reference = $compte->code_commande; // reference commande
            $this->redirect('/nouveau_cmd_fourni?id='.$id.'&ref='.$this->reference.'&active=6&champ=2-1&choix=1', navigate: true);
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
            $this->redirect('/listing_cmd_fourni?active', navigate: true);
        }
    }
}
