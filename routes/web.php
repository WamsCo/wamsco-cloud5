<?php

use Illuminate\Support\Facades\Route;
use App\Helpers\LogActivity;
use App\Livewire\Connexion\Login; 
use App\Livewire\Connexion\ForgotPassword; 
use App\Livewire\Connexion\Inscription;
use App\Livewire\Bienvenue; 
use App\Livewire\TableauBord; 
use App\Livewire\Parametres;
use App\Livewire\Agenda\Calendar;
use App\Livewire\GestionStock\Entrepots;
use App\Livewire\GestionStock\DetailEntrepots;
use App\Livewire\GestionStock\Categories;
use App\Livewire\GestionStock\Produits;
use App\Livewire\GestionStock\DetailProduit;
use App\Livewire\Administration\DeviseTvas;
use App\Livewire\Administration\Utilisateurs;
use App\Livewire\Administration\DetailUtilisateur;
use App\Livewire\Administration\MonCompte;
use App\Livewire\Administration\Departements;
use App\Livewire\Administration\PosteTravails;
use App\Livewire\Administration\Entites;
use App\Livewire\Administration\DetailEntite;
use App\Livewire\Administration\MultiSociete;
use App\Livewire\Administration\Roles;
use App\Livewire\Administration\NouveauRole;
use App\Livewire\Administration\DetailRole;
use App\Livewire\Administration\TransfertStockFiliale;
use App\Livewire\Administration\DetailTransfertFiliale;
use App\Livewire\Administration\SoldeClients;
use App\Livewire\Administration\ChoixPlan;
use App\Livewire\Administration\Abonnement;
use App\Livewire\Administration\HistoriquePaieClt;
use App\Livewire\Tiers\Tiers; 
use App\Livewire\Tiers\DetailTiers; 
use App\Livewire\Tiers\SoldeTiers; 
use App\Livewire\CRM\Pipeline;
use App\Livewire\CRM\DetailPipeline; 
use App\Livewire\CRM\Etapes; 
use App\Livewire\ListActivity;
// use App\Http\Controllers\exporterProduit; // pour generer fichier excel csv
use App\Http\Controllers\ExportExcelToutController; // pour generer fichier .xlsx (excel) et .csv
use App\Http\Controllers\ImporterExcelToutController; // pour importer fichier .xlsx (excel) et .csv
use App\Livewire\GestionStock\Stocks;
use App\Livewire\GestionStock\StockDate;
use App\Livewire\GestionStock\Mouvements;
use App\Livewire\GestionStock\Transferts;
use App\Livewire\GestionStock\DetailTransfert;
use App\Livewire\GestionStock\Inventaires;
use App\Livewire\GestionStock\DetailInventaire;
use App\Livewire\GestionBanque\CompteBanqueCaisses;
use App\Livewire\GestionBanque\DetailCompteBanqueCaisses;
use App\Livewire\GestionBanque\EcritureBancaireCaisses;
use App\Livewire\GestionBanque\PaiementDivers;
use App\Livewire\GestionBanque\UpdatePaiementDivers;
use App\Livewire\GestionBanque\VirementInterne;
use App\Livewire\GestionFacturation\Client\FactureClient;
use App\Livewire\GestionFacturation\Client\NouvFactureClient;
use App\Livewire\GestionFacturation\Client\Reglements;
use App\Livewire\GestionFacturation\Client\LigneFacture;
use App\Livewire\GestionFacturation\Fournisseur\FactureFournisseur;
use App\Livewire\GestionFacturation\Fournisseur\NouvFactureFournisseur;
use App\Livewire\GestionFacturation\Fournisseur\Reglementss;
use App\Livewire\GestionFacturation\Fournisseur\LigneFactureFourni;
use App\Livewire\GestionCommande\Client\ExpeditionClient;
use App\Livewire\GestionCommande\Client\DetailExpeditionClient;
use App\Livewire\GestionCommande\Client\LigneCommande;
use App\Livewire\GestionCommande\Client\ProformaClient;
use App\Livewire\GestionCommande\Client\NouvProformaClient;
use App\Livewire\GestionCommande\Fournisseur\ReceptionFournisseur;
use App\Livewire\GestionCommande\Fournisseur\DetailReceptionFournisseur;
use App\Livewire\GestionCommande\Client\CommandeClient;
use App\Livewire\GestionCommande\Client\NouvCommandeClient;
use App\Livewire\GestionCommande\Fournisseur\CommandeFournisseur;
use App\Livewire\GestionCommande\Fournisseur\NouvCommandeFournisseur;
use App\Livewire\GestionCommande\Fournisseur\LigneCommandeFourni;
use App\Livewire\GestionPointVente\Sessions;
use App\Livewire\GestionPointVente\DetailSession;
use App\Livewire\GestionPointVente\Pos;
use App\Livewire\GestionPointVente\Emplacements; 
use App\Livewire\GestionPointVente\Restaurant\SessionRestaurant;
use App\Livewire\GestionPointVente\Restaurant\DetailSessionRestau; 
use App\Livewire\GestionPointVente\Restaurant\TableRestaurant;
use App\Livewire\GestionPointVente\Restaurant\EspaceRestaurant; 
use App\Livewire\GestionPointVente\Restaurant\PipelineRestaurant;
use App\Livewire\GestionPointVente\Restaurant\PosRestaurant;
use App\Livewire\GestionPointVente\Restaurant\Cuisine;
use App\Livewire\GestionPointVente\Restaurant\CuisinePreparer;
use App\Livewire\GestionPointVente\Restaurant\CuisineEncours;
use App\Livewire\GestionPointVente\Restaurant\CuisineTerminer; 
use App\Livewire\GestionPointVente\Restaurant\CommandeAttente;

use App\Livewire\Fabrication\ListeNomenclature; 
use App\Livewire\Fabrication\DetailNomenclature; 
use App\Livewire\Fabrication\ListeOrdreFabrication; 
use App\Livewire\Fabrication\DetailOrdreFabrication;
use App\Livewire\GestionTicket\ListeTicket;
use App\Livewire\GestionTicket\DetailsTicket;
use App\Livewire\GestionTache\EtapeTaches;
use App\Livewire\GestionTache\Taches;
use App\Livewire\GestionTache\DetailTache;

use App\Livewire\GestionPaie\CategoriePaies;
use App\Livewire\GestionPaie\GrilleSalariales;
use App\Livewire\GestionPaie\NouveauAvancePret;
use App\Livewire\GestionPaie\AvancePrets;
use App\Livewire\GestionPaie\DetailAvancePret;

use App\Livewire\GestionCommercial\ListeSouscription;
use App\Livewire\GestionCommercial\DetailReglementCom;

use App\Http\Controllers\PDFController; // impression
use App\Http\Controllers\LangController; 
use App\Livewire\Site\AccueilSite; 
use App\Livewire\Site\Fonctionnalite; 
use App\Livewire\Site\Prix; 

use App\Livewire\PipelineBoard;


// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/deconnexion', function () {
    if(auth()->guest()){
        $title = 'Connexion';  
        flash ('Veuillez vous reconnecter svp!')->warning();           
        return redirect('/connexion');   
    }     
    else{
        \Log::info("Deconnexion du systeme [".auth()->user()->email."]");
        $id_activite = 0; 
        $page = 'Deconnexion';
        LogActivity::addToLog('Deconnexion du systeme', $id_activite, $page);
        auth()->logout();
        flash ('Vous êtes maintenant déconnecté')->success();
        return redirect('/connexion'); 
    }    
});
// ceci pour la page d'error 404 
Route::fallback(function() {
    $title = '404 Page introuvable';    
    return view('livewire.page_404', compact('title'));
 });

// Donnees Connexion
Route::get('/connexion',Login::class);
Route::get('/forgot_password',ForgotPassword::class);

// Donnees Inscription
Route::get('/inscription',Inscription::class); 

// Tous les lien de l'application sont virifier si est logue ou pas

Route::group(['middleware' => 'verifierUse'], function (){
    
    // Donnees Agenda
    Route::get('calendrier',Calendar::class);
    
    Route::get('/bienvenue',Bienvenue::class);
    Route::get('/tableau_bord',TableauBord::class);
    
    // Module Gestion de stock
    Route::get('/entrepot',Entrepots::class);
    Route::get('/detail_entrepot',DetailEntrepots::class);
    Route::get('/categorie',Categories::class);
    Route::get('/produit',Produits::class);
    Route::get('/detail_product',DetailProduit::class);
    Route::get('/stock',Stocks::class);
    Route::get('/stock_a_date',StockDate::class);    
    Route::get('/mouvements',Mouvements::class);
    Route::get('/transferts',Transferts::class);
    Route::get('/detail_transfert',DetailTransfert::class);
    Route::get('/inventaires',Inventaires::class);
    Route::get('/detail_inventaire',DetailInventaire::class);
    
    // Module Banque & Caisse
    Route::get('/banque_caisse',CompteBanqueCaisses::class);
    Route::get('/detail_banque',DetailCompteBanqueCaisses::class);
    Route::get('/listing_ecriture',EcritureBancaireCaisses::class);
    Route::get('/listing_paie_divers',PaiementDivers::class);
    Route::get('/update_paie_divers',UpdatePaiementDivers::class);    
    Route::get('/virement_interne',VirementInterne::class);
    
    // Module Facturation client
    Route::get('/listing_fact_clt',FactureClient::class);
    Route::get('/nouveau_fact_clt',NouvFactureClient::class);
    Route::get('/list_reglement_clt',Reglements::class);
    Route::get('/ligne_facture',LigneFacture::class);

    // Module Facturation Fournisseur
    Route::get('/listing_fact_fourni',FactureFournisseur::class);
    Route::get('/nouveau_fact_fourni',NouvFactureFournisseur::class);
    Route::get('/list_reglement_fourni',Reglementss::class);
    Route::get('/ligne_facture_fourni',LigneFactureFourni::class);

    // Module commande client
    Route::get('/listing_cmd_clt',CommandeClient::class);
    Route::get('/nouveau_cmd_clt',NouvCommandeClient::class);
    Route::get('/listing_expedition_clt',ExpeditionClient::class);
    Route::get('/detail_expedition_clt',DetailExpeditionClient::class);
    Route::get('/ligne_cmd',LigneCommande::class);
    Route::get('/proforma',ProformaClient::class);
    Route::get('/nouveau_prof_clt',NouvProformaClient::class);    
    

    // Module commande fournisseur
    Route::get('/listing_cmd_fourni',CommandeFournisseur::class);
    Route::get('/nouveau_cmd_fourni',NouvCommandeFournisseur::class);
    Route::get('/listing_reception_fourni',ReceptionFournisseur::class);
    Route::get('/detail_reception_fourni',DetailReceptionFournisseur::class);
    Route::get('/ligne_cmd_fourni',LigneCommandeFourni::class);
    
    // Module Administration
    Route::get('/devise',DeviseTvas::class);
    Route::get('/utilisateurs',Utilisateurs::class);
    Route::get('/detail_user',DetailUtilisateur::class);
    Route::get('/departement',Departements::class);
    Route::get('/poste_travail',PosteTravails::class);
    Route::get('/entite',Entites::class);
    Route::get('/detail_entite',DetailEntite::class);
    Route::get('/role_privillege',Roles::class);   
    Route::get('/nouveau_role',NouveauRole::class);   
    Route::get('/detail_role',DetailRole::class);   
    Route::get('/mon_compte',MonCompte::class);   
    
    Route::get('/solde_clients',SoldeClients::class); 
    Route::get('/choix_plan',ChoixPlan::class); 
    Route::get('/paiement',Abonnement::class); 
    Route::get('/historique_paie_clt',HistoriquePaieClt::class); 
   
    // MultiSociete
    Route::get('/liste_societe',MultiSociete::class);  
    Route::get('/transfert_filiale',TransfertStockFiliale::class); 
    Route::get('/detail_transfert_filiale',DetailTransfertFiliale::class);

    // Module Gestion Tiers
    Route::get('/listing-tiers',Tiers::class); 
    Route::get('/detail_tier',DetailTiers::class);
    Route::get('/solde_tiers',SoldeTiers::class);

    // Module Gestion CRM
    Route::get('/pipeline_tiers',Pipeline::class); 
    Route::get('/detail_pipeline',DetailPipeline::class); 
    Route::get('/etapes_pipeline',Etapes::class);    

    // journal d'activite du systeme
    Route::get('logActivity', ListActivity::class); 
    Route::get('supprimer_log', ListActivity::class);

    // Module point de vente
    Route::get('/pos_sessions',Sessions::class);
    Route::get('/detail_pos_session',DetailSession::class);
    Route::get('/pos',Pos::class);
    Route::get('/emplacement',Emplacements::class);
    // Module restaurant
    Route::get('/restau_sessions',SessionRestaurant::class);
    Route::get('/detail_restau_session',DetailSessionRestau::class);
    Route::get('/table_restau',TableRestaurant::class);
    Route::get('/espace_restau',EspaceRestaurant::class);
    Route::get('/pipeline_restau',PipelineRestaurant::class);
    Route::get('/pos_restau',PosRestaurant::class);
    
    
    Route::get('/cuisine',Cuisine::class);
    Route::get('/cuisine_prepare',CuisinePreparer::class);
    Route::get('/cuisine_encours',CuisineEncours::class);
    Route::get('/cuisine_terminer',CuisineTerminer::class);
    Route::get('/cmd_attente',CommandeAttente::class);
    

    // Module Fabrication 
    Route::get('/listing_nomencla',ListeNomenclature::class); 
    Route::get('/detail_nomencla',DetailNomenclature::class); 
    
    Route::get('/listing_ordre',ListeOrdreFabrication::class); 
    Route::get('/detail_ordre_fab',DetailOrdreFabrication::class);
    
    // Gestion des Tickets
    Route::get('/liste_ticket',ListeTicket::class);
    Route::get('/detail_ticket',DetailsTicket::class);

    // Gestion des Taches  
    Route::get('/etapes_taches',EtapeTaches::class);    
    Route::get('/taches',Taches::class);    
    Route::get('/detail_tache',DetailTache::class);    
    
    // Gestion Paie
    Route::get('/liste_categorie',CategoriePaies::class);    
    Route::get('/grille_salaire',GrilleSalariales::class);    
    Route::get('/nouveau_avance',NouveauAvancePret::class);    
    Route::get('/liste_avance',AvancePrets::class);    
    Route::get('/detail_avance',DetailAvancePret::class);    
    
    // Gestion Commerciale
    Route::get('/liste_souscription',ListeSouscription::class);  
    Route::get('/details_regle_com',DetailReglementCom::class);      
    
     // Module configuration
     Route::get('/config',Parametres::class); 

    // Exporter un fichier Excel (.xlsx et .csv)
    Route::get("exporter-tiers", [ExportExcelToutController::class, 'export_tiers']);
    Route::get("exporter-produits", [ExportExcelToutController::class, 'export_produits']);
    Route::get("exporter-stocks", [ExportExcelToutController::class, 'export_stock_prods']);
    Route::get("exporter-utilisateurs", [ExportExcelToutController::class, 'export_users']);
    Route::get("exporter-entites", [ExportExcelToutController::class, 'export_entites']);
    Route::get("exporter-sessions", [ExportExcelToutController::class, 'export_sessions']);
    Route::get("exporter-sessions_restau", [ExportExcelToutController::class, 'export_sessions_restau']);    
    Route::get("exporter-emplacement", [ExportExcelToutController::class, 'export_emplacements']);
    Route::get("exporter-cmd_client", [ExportExcelToutController::class, 'export_cmd_client']);
    Route::get("exporter-cmd_client_ligne", [ExportExcelToutController::class, 'export_cmd_client_ligne']);    
    Route::get("exporter-cmd_fourni", [ExportExcelToutController::class, 'export_cmd_fourni']);
    Route::get("exporter-cmd_fourni_ligne", [ExportExcelToutController::class, 'export_cmd_fourni_ligne']);
    

    Route::get("exporter-exp_client", [ExportExcelToutController::class, 'export_exp_client']);
    Route::get("exporter-recpt_fourni", [ExportExcelToutController::class, 'export_recep_fourni']);
    Route::get("exporter-fact_client", [ExportExcelToutController::class, 'export_fact_client']);
    Route::get("exporter-fact_fourni", [ExportExcelToutController::class, 'export_fact_fourni']);
    Route::get("exporter-regle_client", [ExportExcelToutController::class, 'export_regle_client']);
    Route::get("exporter-regle_fourni", [ExportExcelToutController::class, 'export_regle_fourni']);
    Route::get("exporter-compte_banq", [ExportExcelToutController::class, 'export_compte_banq']);
    Route::get("exporter-ecriture_banq", [ExportExcelToutController::class, 'export_ecriture_banq']);
    Route::get("exporter-paie_divers", [ExportExcelToutController::class, 'export_paie_divers']);
    Route::get("exporter-grille-salariale", [ExportExcelToutController::class, 'export_grille_salariales']);
    Route::get("exporter-mouvements", [ExportExcelToutController::class, 'export_mouvements']);  
    Route::get("exporter-entrepots", [ExportExcelToutController::class, 'export_entrepots']);  
      
    
    // Importer un fichier Excel (.xlsx et .csv)
    Route::post("importer_tier", [ImporterExcelToutController::class, 'import_tier'])->name('importer_tier');
    Route::post("importer_produit", [ImporterExcelToutController::class, 'import_produit'])->name('importer_produit');
    Route::post("importer_grille_salariale", [ImporterExcelToutController::class, 'import_grille_salariale'])->name('importer_grille_salariale');


    Route::get('facturationfourni-pdf', [PDFController::class, 'facturationFourniPDF']); // imprime format A4 et ticket facturation    
    Route::get('facturationclt-pdf', [PDFController::class, 'facturationClientPDF']); // imprime format A4 et ticket facturation
    Route::get('impcmdclt-pdf', [PDFController::class, 'impressionCmdClientPDF']); // imprime format A4 et ticket commande
    Route::get('impproforcmdclt-pdf', [PDFController::class, 'impressionProforCmdClientPDF']); // imprime format A4 et ticket commande    
    Route::get('imprecutier-pdf', [PDFController::class, 'impressionRecuTierPDF']); // imprime format A4 et ticket recu Tier    
    
});

// *****************  Site web ***********************************************

Route::get('/',AccueilSite::class);
Route::get('lang/change', [LangController::class, 'change'])->name('changeLang');
Route::get('/fonctionnalite',Fonctionnalite::class);
Route::get('/prix',Prix::class);
