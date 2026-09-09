<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Spatie\SimpleExcel\SimpleExcelReader;
use Carbon\Carbon;
use App\Helpers\LogActivity;
use App\Models\LogActivity as LogActivityModel;
use App\Models\Tier;
use App\Models\Produit;
use App\Models\Stock;
use App\Models\Utilisateur;
use App\Models\Entite;
use App\Models\SessionPos;
use App\Models\SessionRestau;
use App\Models\Emplacement;
use App\Models\CommandeClientLigne;
use App\Models\CommandeClientEntete;
use App\Models\ExpeditionClientLigne;
use App\Models\ExpeditionClientEntete;
use App\Models\ExpeditionClientLignePartiel;
use App\Models\CommandeFournisseurEntete;
use App\Models\CommandeFournisseurLigne;
use App\Models\ReceptionFournisseurLignePartiel;
use App\Models\ReceptionFournisseurEntete;
use App\Models\ReceptionFournisseurLigne;
use App\Models\factureClientEntete;
use App\Models\factureClientLigne;
use App\Models\factureFournisseurLigne;
use App\Models\Reglement;
use App\Models\Reglement_fourni;
use App\Models\CompteBancaire;
use App\Models\EcritureBancaire;
use App\Models\PaiementDiver;
use App\Models\GrilleSalariale;
use App\Models\Mouvement;
use App\Models\Entrepot;


class ExportExcelToutController extends Controller
{
    public function export_tiers (Request $request) {
       
    	// 1. Validation des informations du formulaire
    	// $this->validate($request, [ 
    	// 	'name' => 'bail|required|string',
    	// 	'extension' => 'bail|required|string|in:xlsx,csv'
    	// ]);

		$id_activite = 0;
		$page = 'Tiers';
		LogActivity::addToLog('Exportation Excel Tiers', $id_activite, $page);
		
    	// 2. Le nom du fichier avec l'extension : .xlsx ou .csv
    	// $file_name = $request->name.".".$request->extension;
    	$file_name = auth()->user()->societe.'_Tiers.xlsx';

    	// 3. On récupère données de la table "tiers"
    	$tiers = Tier::where('societe_id',auth()->user()->societe_id)->get();

    	// 4. $writer : Objet Spatie\SimpleExcel\SimpleExcelWriter
    	$writer = SimpleExcelWriter::streamDownload($file_name);

 		// 5. On insère toutes les lignes au fichier Excel $file_name
    	$writer->addRows($tiers->toArray());
        $writer->toBrowser();
    }
	public function export_produits (Request $request) {
		$categorie = request('categorie');
		$nature = request('nature');
		$produit = request('produit');

		$id_activite = 0;
		$page = 'Produits';
		LogActivity::addToLog('Exportation Excel Produit', $id_activite, $page);

    	//  on peut choir l'extension .csv ou .xlsx (excel)    	
    	$file_name = auth()->user()->societe.'_Produits.xlsx';
    	$produits = Produit::where('societe_id',auth()->user()->societe_id)->where('nature_produit','like','%'.$nature.'%')->where('categorie','like','%'.$categorie.'%')->where('nom_produit','like','%'.$produit.'%')->get();
    	$writer = SimpleExcelWriter::streamDownload($file_name);
    	$writer->addRows($produits->toArray());
        $writer->toBrowser();
    }
	public function export_stock_prods (Request $request) { 
		$categorie = request('categorie');
		$nature = request('nature');
		$produit = request('produit');

		$id_activite = 0;
		$page = 'Stock';
		LogActivity::addToLog('Exportation Excel Stock',$id_activite, $page);

    	//  on peut choir l'extension .csv ou .xlsx (excel)    	
    	$file_name = auth()->user()->societe.'_Stock.xlsx';
    	$stocks = Stock::where('societe_id',auth()->user()->societe_id)->where('nature_produit','like','%'.$nature.'%')->where('categorie','like','%'.$categorie.'%')->where('nom_produit','like','%'.$produit.'%')->get();
    	$writer = SimpleExcelWriter::streamDownload($file_name);
    	$writer->addRows($stocks->toArray());
        $writer->toBrowser();
    }	
	public function export_users (Request $request) { 

		$id_activite = 0;
		$page = 'Utilisateur';
		LogActivity::addToLog('Exportation Excel users', $id_activite, $page);

    	//  on peut choir l'extension .csv ou .xlsx (excel)    	
    	$file_name = auth()->user()->societe.'_Utilisateur.xlsx';
    	$users = Utilisateur::where('societe_id',auth()->user()->societe_id)->get();
    	$writer = SimpleExcelWriter::streamDownload($file_name);
    	$writer->addRows($users->toArray());
        $writer->toBrowser();
    }
	public function export_entites (Request $request) { 

		$id_activite = 0;
		$page = 'Entite';
		LogActivity::addToLog('Exportation Excel entites', $id_activite, $page);

    	//  on peut choir l'extension .csv ou .xlsx (excel)    	
    	$file_name = auth()->user()->societe.'_Entite.xlsx';
    	$entites = Entite::get();
    	$writer = SimpleExcelWriter::streamDownload($file_name);
    	$writer->addRows($entites->toArray());
        $writer->toBrowser();
    }
	public function export_sessions (Request $request) { 

		$date_debut = request('date_debut');
		$date_fin = request('date_fin');
		$user_id = request('user');
		
		$start = Carbon::parse($date_debut)->startOfDay(); //2016-09-29 00:00:00.000000
		$end = Carbon::parse($date_fin)->endOfDay();     // 2016-09-29 23:59:59.000000

		$id_activite = 0;
		$page = 'SessionPos';
		LogActivity::addToLog('Exportation Excel sessions', $id_activite, $page);

    	//  on peut choir l'extension .csv ou .xlsx (excel)    	
    	$file_name = auth()->user()->societe.'_Session_Pos.xlsx';
		if(empty($user_id)){
			$sessions = SessionPos::where('societe_id',auth()->user()->societe_id)->whereBetween('created_at',[$start, $end])->get();
		}
		else{
			$sessions = SessionPos::where('societe_id',auth()->user()->societe_id)->where('user_id', $user_id)->whereBetween('created_at',[$start, $end])->get();
		}
    	$writer = SimpleExcelWriter::streamDownload($file_name);
    	$writer->addRows($sessions->toArray());
        $writer->toBrowser();
    }
	public function export_sessions_restau (Request $request) { 

		$date_debut = request('date_debut');
		$date_fin = request('date_fin');
		$user_id = request('user');
		
		$start = Carbon::parse($date_debut)->startOfDay(); //2016-09-29 00:00:00.000000
		$end = Carbon::parse($date_fin)->endOfDay();     // 2016-09-29 23:59:59.000000

		$id_activite = 0;
		$page = 'SessionRestau';
		LogActivity::addToLog('Exportation Excel sessions restau', $id_activite, $page);

    	//  on peut choir l'extension .csv ou .xlsx (excel)    	
    	$file_name = auth()->user()->societe.'_Session_Restau.xlsx';
		if(empty($user_id)){
			$sessions = SessionRestau::where('societe_id',auth()->user()->societe_id)->whereBetween('created_at',[$start, $end])->get();
		}
		else{
			$sessions = SessionRestau::where('societe_id',auth()->user()->societe_id)->where('user_id', $user_id)->whereBetween('created_at',[$start, $end])->get();
		}
    	$writer = SimpleExcelWriter::streamDownload($file_name);
    	$writer->addRows($sessions->toArray());
        $writer->toBrowser();
    }
	public function export_emplacements (Request $request) {
		
		$id_activite = 0;
		$page = 'Emplacement';
		LogActivity::addToLog('Exportation Excel emplacement', $id_activite, $page);
		
    	//  on peut choir l'extension .csv ou .xlsx (excel)    	
    	$file_name = auth()->user()->societe.'_Emplacement.xlsx';
    	$emplacement = Emplacement::where('societe_id',auth()->user()->societe_id)->get();
    	$writer = SimpleExcelWriter::streamDownload($file_name);
    	$writer->addRows($emplacement->toArray());
        $writer->toBrowser();
    }
	public function export_cmd_client (Request $request) { 

		$date_debut = request('date_debut');
		$date_fin = request('date_fin');
		$statut = request('statut');
		$client = request('client');
		$ref = request('ref');

		$start = Carbon::parse($date_debut)->startOfDay(); //2016-09-29 00:00:00.000000
		$end = Carbon::parse($date_fin)->endOfDay();     // 2016-09-29 23:59:59.000000

		$id_activite = 0;
		$page = 'CommandeClient';
		LogActivity::addToLog('Exportation Excel commande client', $id_activite, $page);

    	//  on peut choir l'extension .csv ou .xlsx (excel)    	
    	$file_name = auth()->user()->societe.'_Commande_client.xlsx';
    	// $cmdClient = CommandeClientLigne::where('societe_id',auth()->user()->societe_id)->get();
		if(!empty($statut)){
			$cmdClient = CommandeClientEntete::where('societe_id',auth()->user()->societe_id)->where('nom_client','like','%'.$client.'%')->where('code_commande','like','%'.$ref.'%')->where('etat',$statut)->whereBetween('created_at',[$start, $end])->get();
		}
		else{
			$cmdClient = CommandeClientEntete::where('societe_id',auth()->user()->societe_id)->where('nom_client','like','%'.$client.'%')->where('code_commande','like','%'.$ref.'%')->whereBetween('created_at',[$start, $end])->get();
		}
    	$writer = SimpleExcelWriter::streamDownload($file_name);
    	$writer->addRows($cmdClient->toArray());
        $writer->toBrowser();
    }
	public function export_cmd_client_ligne (Request $request) { 

		$date_debut = request('date_debut');
		$date_fin = request('date_fin');
		$produit_id = request('produit');
		$client = request('client');
		$ref = request('ref');

		$start = Carbon::parse($date_debut)->startOfDay(); //2016-09-29 00:00:00.000000
		$end = Carbon::parse($date_fin)->endOfDay();     // 2016-09-29 23:59:59.000000

		$id_activite = 0;
		$page = 'CommandeClient';
		LogActivity::addToLog('Exportation Excel commande client ligne', $id_activite, $page);

    	//  on peut choir l'extension .csv ou .xlsx (excel)    	
    	$file_name = auth()->user()->societe.'_Commande_client_ligne.xlsx';
		if(!empty($produit_id)){
			$cmdClient = CommandeClientLigne::where('societe_id',auth()->user()->societe_id)->where('nom_client','like','%'.$client.'%')->where('code_commande','like','%'.$ref.'%')->where('id_produit',$produit_id)->whereBetween('created_at',[$start, $end])->get();
		}
		else{
			$cmdClient = CommandeClientLigne::where('societe_id',auth()->user()->societe_id)->where('nom_client','like','%'.$client.'%')->where('code_commande','like','%'.$ref.'%')->whereBetween('created_at',[$start, $end])->get();
		}
    	$writer = SimpleExcelWriter::streamDownload($file_name);
    	$writer->addRows($cmdClient->toArray());
        $writer->toBrowser();
    }
	public function export_exp_client (Request $request) { 
		$date_debut = request('date_debut');
		$date_fin = request('date_fin');
		$client = request('client');
		$statut = request('statut');
		$ref = request('ref');

		$start = Carbon::parse($date_debut)->startOfDay(); //2016-09-29 00:00:00.000000
		$end = Carbon::parse($date_fin)->endOfDay();     // 2016-09-29 23:59:59.000000

		$id_activite = 0;
		$page = 'ExpeditionClient';
		LogActivity::addToLog('Exportation Excel expedition client', $id_activite, $page);

    	//  on peut choir l'extension .csv ou .xlsx (excel)    	
    	$file_name = auth()->user()->societe.'_Expedition_client.xlsx';
    	// $expClient = ExpeditionClientLigne::where('societe_id',auth()->user()->societe_id)->get();
		if(!empty($statut)){
			$expClient = ExpeditionClientLignePartiel::where('societe_id',auth()->user()->societe_id)->where('nom_client','like','%'.$client.'%')->where('code_expedition','like','%'.$ref.'%')->where('etat',$statut)->whereBetween('created_at',[$start, $end])->get();
		}
		else{
			$expClient = ExpeditionClientLignePartiel::where('societe_id',auth()->user()->societe_id)->where('nom_client','like','%'.$client.'%')->where('code_expedition','like','%'.$ref.'%')->whereBetween('created_at',[$start, $end])->get();
		}
    	$writer = SimpleExcelWriter::streamDownload($file_name);
    	$writer->addRows($expClient->toArray());
        $writer->toBrowser();
    }
	public function export_cmd_fourni (Request $request) { 
		$date_debut = request('date_debut');
		$date_fin = request('date_fin');
		$statut = request('statut');
		$fournisseur = request('fournisseur');
		$ref = request('ref');

		$start = Carbon::parse($date_debut)->startOfDay(); //2016-09-29 00:00:00.000000
		$end = Carbon::parse($date_fin)->endOfDay();     // 2016-09-29 23:59:59.000000

		$id_activite = 0;
		$page = 'CommandeFournisseur';
		LogActivity::addToLog('Exportation Excel commande fournisseur', $id_activite, $page);

    	//  on peut choir l'extension .csv ou .xlsx (excel)    	
    	$file_name = auth()->user()->societe.'_Commande_fournisseur.xlsx';
    	// $cmdFourni = CommandeFournisseurLigne::where('societe_id',auth()->user()->societe_id)->get();

		if(!empty($statut)){ 
			$cmdFourni = CommandeFournisseurEntete::where('societe_id',auth()->user()->societe_id)->where('nom_fournisseur','like','%'.$fournisseur.'%')->where('code_commande','like','%'.$ref.'%')->where('etat',$statut)->whereBetween('created_at',[$start, $end])->get();
		}
		else{
			$cmdFourni = CommandeFournisseurEntete::where('societe_id',auth()->user()->societe_id)->where('nom_fournisseur','like','%'.$fournisseur.'%')->where('code_commande','like','%'.$ref.'%')->whereBetween('created_at',[$start, $end])->get();
		}

    	$writer = SimpleExcelWriter::streamDownload($file_name);
    	$writer->addRows($cmdFourni->toArray());
        $writer->toBrowser();
    }
	public function export_cmd_fourni_ligne (Request $request) { 
		$date_debut = request('date_debut');
		$date_fin = request('date_fin');
		$produit_id = request('produit');
		$fournisseur = request('fournisseur');
		$ref = request('ref');

		$start = Carbon::parse($date_debut)->startOfDay(); //2016-09-29 00:00:00.000000
		$end = Carbon::parse($date_fin)->endOfDay();     // 2016-09-29 23:59:59.000000

		$id_activite = 0;
		$page = 'CommandeFournisseur';
		LogActivity::addToLog('Exportation Excel commande fournisseur ligne', $id_activite, $page);

    	//  on peut choir l'extension .csv ou .xlsx (excel)    	
    	$file_name = auth()->user()->societe.'_Commande_fournisseur_ligne.xlsx';
		if(!empty($produit_id)){ 
			$cmdFourni = CommandeFournisseurLigne::where('societe_id',auth()->user()->societe_id)->where('nom_fournisseur','like','%'.$fournisseur.'%')->where('code_commande','like','%'.$ref.'%')->where('id_produit',$produit_id)->whereBetween('created_at',[$start, $end])->get();
		}
		else{
			$cmdFourni = CommandeFournisseurLigne::where('societe_id',auth()->user()->societe_id)->where('nom_fournisseur','like','%'.$fournisseur.'%')->where('code_commande','like','%'.$ref.'%')->whereBetween('created_at',[$start, $end])->get();
		}

    	$writer = SimpleExcelWriter::streamDownload($file_name);
    	$writer->addRows($cmdFourni->toArray());
        $writer->toBrowser();
    }
	public function export_recep_fourni (Request $request) { 
		$date_debut = request('date_debut');
		$date_fin = request('date_fin');
		$fournisseur = request('fournisseur');
		$statut = request('statut');
		$ref = request('ref');

		$start = Carbon::parse($date_debut)->startOfDay(); //2016-09-29 00:00:00.000000
		$end = Carbon::parse($date_fin)->endOfDay();     // 2016-09-29 23:59:59.000000

		$id_activite = 0;
		$page = 'ReceptionFournisseur';
		LogActivity::addToLog('Exportation Excel reception fournisseur', $id_activite, $page);

    	//  on peut choir l'extension .csv ou .xlsx (excel)    	
    	$file_name = auth()->user()->societe.'_Reception_fournisseur.xlsx';
    	// $recepFourni = ReceptionFournisseurLigne::where('societe_id',auth()->user()->societe_id)->get();
		if(!empty($statut)){
			$recepFourni = ReceptionFournisseurLignePartiel::where('societe_id',auth()->user()->societe_id)->where('nom_fournisseur','like','%'.$fournisseur.'%')->where('code_reception','like','%'.$ref.'%')->where('etat',$statut)->whereBetween('created_at',[$start, $end])->get();
		}
		else{
			$recepFourni = ReceptionFournisseurLignePartiel::where('societe_id',auth()->user()->societe_id)->where('nom_fournisseur','like','%'.$fournisseur.'%')->where('code_reception','like','%'.$ref.'%')->whereBetween('created_at',[$start, $end])->get();
		}
    	$writer = SimpleExcelWriter::streamDownload($file_name);
    	$writer->addRows($recepFourni->toArray());
        $writer->toBrowser();
    }
	public function export_fact_client (Request $request) { 

		$date_debut = request('date_debut');
		$date_fin = request('date_fin');
		$statut = request('statut');
		$client = request('client');
		$ref = request('ref');
		$user = request('user');

		$start = Carbon::parse($date_debut)->startOfDay(); //2016-09-29 00:00:00.000000
		$end = Carbon::parse($date_fin)->endOfDay();     // 2016-09-29 23:59:59.000000
		
		$id_activite = 0;
		$page = 'factureClient';
		LogActivity::addToLog('Exportation Excel Facture client', $id_activite, $page);

    	//  on peut choir l'extension .csv ou .xlsx (excel)    	
    	$file_name = auth()->user()->societe.'_Facture_client.xlsx';
    	// $factClient = factureClientLigne::where('societe_id',auth()->user()->societe_id)->get(); 

		if(empty($statut) && empty($user)){
			$factClient = factureClientEntete::where('societe_id',auth()->user()->societe_id)->where('nom_client','like','%'.$client.'%')->where('code_facture','like','%'.$ref.'%')->whereBetween('created_at',[$start, $end])->get();
		}
		elseif(!empty($statut) && empty($user)){
			$factClient = factureClientEntete::where('societe_id',auth()->user()->societe_id)->where('nom_client','like','%'.$client.'%')->where('code_facture','like','%'.$ref.'%')->where('etat',$statut)->whereBetween('created_at',[$start, $end])->get();
		}
		elseif(empty($statut) && !empty($user)){
			$factClient = factureClientEntete::where('societe_id',auth()->user()->societe_id)->where('nom_client','like','%'.$client.'%')->where('code_facture','like','%'.$ref.'%')->where('user_id',$user)->whereBetween('created_at',[$start, $end])->get();
		}
		else{ 
			$factClient = factureClientEntete::where('societe_id',auth()->user()->societe_id)->where('nom_client','like','%'.$client.'%')->where('code_facture','like','%'.$ref.'%')->where('etat',$statut)->where('user_id',$user)->whereBetween('created_at',[$start, $end])->get();
		}
    	$writer = SimpleExcelWriter::streamDownload($file_name);
    	$writer->addRows($factClient->toArray());
        $writer->toBrowser();
    }
	public function export_fact_fourni (Request $request) {
		
		$id_activite = 0;
		$page = 'factureFournisseur';
		LogActivity::addToLog('Exportation Excel facture fournisseur', $id_activite, $page);

    	//  on peut choir l'extension .csv ou .xlsx (excel)    	
    	$file_name = auth()->user()->societe.'_Facture_fournisseur.xlsx';
    	$factFourni = factureFournisseurLigne::where('societe_id',auth()->user()->societe_id)->get();
    	$writer = SimpleExcelWriter::streamDownload($file_name);
    	$writer->addRows($factFourni->toArray());
        $writer->toBrowser();
    }	
	public function export_regle_client (Request $request) { 

		$id_activite = 0;
		$page = 'Reglement';
		LogActivity::addToLog('Exportation Excel reglement client', $id_activite, $page);

    	//  on peut choir l'extension .csv ou .xlsx (excel)    	
    	$file_name = auth()->user()->societe.'_Reglement_client.xlsx';
    	$regleClient = Reglement::where('societe_id',auth()->user()->societe_id)->get();
    	$writer = SimpleExcelWriter::streamDownload($file_name);
    	$writer->addRows($regleClient->toArray());
        $writer->toBrowser();
    }
	public function export_regle_fourni (Request $request) { 

		$id_activite = 0;
		$page = 'Reglement_fourni';
		LogActivity::addToLog('Exportation Excel reglement fournisseur', $id_activite, $page);

    	//  on peut choir l'extension .csv ou .xlsx (excel)    	
    	$file_name = auth()->user()->societe.'_Reglement_fournisseur.xlsx';
    	$regleFourni = Reglement_fourni::where('societe_id',auth()->user()->societe_id)->get();
    	$writer = SimpleExcelWriter::streamDownload($file_name);
    	$writer->addRows($regleFourni->toArray());
        $writer->toBrowser();
    }
	public function export_compte_banq (Request $request) { 

		$id_activite = 0;
		$page = 'CompteBancaire';
		LogActivity::addToLog('Exportation Excel compte bancaire', $id_activite, $page);

    	//  on peut choir l'extension .csv ou .xlsx (excel)    	
    	$file_name = auth()->user()->societe.'_Compte_bancaire.xlsx';
    	$comptBanq = CompteBancaire::where('societe_id',auth()->user()->societe_id)->get();
    	$writer = SimpleExcelWriter::streamDownload($file_name);
    	$writer->addRows($comptBanq->toArray());
        $writer->toBrowser();
    }	
	public function export_ecriture_banq (Request $request) { 

		$id_activite = 0;
		$page = 'EcritureBancaire';
		LogActivity::addToLog('Exportation Excel ecriture bancaire', $id_activite, $page);

    	//  on peut choir l'extension .csv ou .xlsx (excel)    	
    	$file_name = auth()->user()->societe.'_Ecriture_bancaire.xlsx';
    	$ecrisBanq = EcritureBancaire::where('societe_id',auth()->user()->societe_id)->get();
    	$writer = SimpleExcelWriter::streamDownload($file_name);
    	$writer->addRows($ecrisBanq->toArray());
        $writer->toBrowser();
    }
	public function export_paie_divers (Request $request) { 

		$id_activite = 0;
		$page = 'PaiementDiver';
		LogActivity::addToLog('Exportation Excel paiement divers', $id_activite, $page);

    	//  on peut choir l'extension .csv ou .xlsx (excel)    	
    	$file_name = auth()->user()->societe.'_Paiement_divers.xlsx';
    	$paieDivers = PaiementDiver::where('societe_id',auth()->user()->societe_id)->get();
    	$writer = SimpleExcelWriter::streamDownload($file_name);
    	$writer->addRows($paieDivers->toArray());
        $writer->toBrowser();		
    }
	public function export_grille_salariales (Request $request) { 

		$id_activite = 0;
		$page = 'GrilleSalariale';
		LogActivity::addToLog('Exportation Excel Grille salariales', $id_activite, $page);
    	
		//  on peut choir l'extension .csv ou .xlsx (excel)    	
    	$file_name = auth()->user()->societe.'_Grille_salariales.xlsx';
    	$grille = GrilleSalariale::where('societe_id',auth()->user()->societe_id)->get();
    	$writer = SimpleExcelWriter::streamDownload($file_name);
    	$writer->addRows($grille->toArray());
        $writer->toBrowser();		
    }
	public function export_mouvements (Request $request) { 
		$date_debut = request('date_debut');
		$date_fin = request('date_fin');
		$ref = request('ref');
		$mag = request('mag');

		$start = Carbon::parse($date_debut)->startOfDay(); //2016-09-29 00:00:00.000000
		$end = Carbon::parse($date_fin)->endOfDay();     // 2016-09-29 23:59:59.000000

		$id_activite = 0;
		$page = 'Mouvement';
		LogActivity::addToLog('Exportation Excel Mouvements', $id_activite, $page);
    	
		//  on peut choir l'extension .csv ou .xlsx (excel)    	
    	$file_name = auth()->user()->societe.'_Mouvements.xlsx';
    	$mouv = Mouvement::where('societe_id',auth()->user()->societe_id)->whereBetween('created_at',[$start, $end])->where('reference','like','%'.$ref.'%')->where('entrepot','like','%'.$mag.'%')->get();
    	$writer = SimpleExcelWriter::streamDownload($file_name);
    	$writer->addRows($mouv->toArray());
        $writer->toBrowser();		
    }
	public function export_entrepots (Request $request) { 
		$nom = request('nom');
		$id_activite = 0;
		$page = 'Entrepot';
		LogActivity::addToLog('Exportation Excel Entrepots', $id_activite, $page);
    	
		//  on peut choir l'extension .csv ou .xlsx (excel)    	
    	$file_name = auth()->user()->societe.'_Entrepot.xlsx';
    	$entrep = Entrepot::where('societe_id',auth()->user()->societe_id)->where('nom','like','%'.$nom.'%')->get();
    	$writer = SimpleExcelWriter::streamDownload($file_name);
    	$writer->addRows($entrep->toArray());
        $writer->toBrowser();		
    }
}
