<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
Use Carbon\Carbon;
use App\Helpers\LogActivity;
use App\Models\LogActivity as LogActivityModel;
// use PDF;
use App\Models\Entite;
use App\Models\DeviseTva;
use App\Models\Produit;
use App\Models\Tier;
use App\Models\Role;
use App\Models\factureClientEntete;
use App\Models\factureClientLigne;
use App\Models\Reglement;
use App\Models\Reglement_fourni;
use App\Models\PosFactureClientEntete;
use App\Models\PosFactureClientLigne;
use App\Models\CommandeClientEntete;
use App\Models\CommandeClientLigne;
use App\Models\ProformaClientEntete;
use App\Models\ProformaClientLigne;
use App\Models\RestauPosfactureClientEntete;
use App\Models\RestauPosFactureClientLigne;
use Illuminate\Support\Number;
use App\Models\SoldeTier;
use TCPDF;
use App\Models\factureFournisseurEntete;
use App\Models\factureFournisseurLigne;
// use Elibyy\TCPDF\Facades\TCPDF;



class PDFController extends Controller
{
    /**
     * Convertit un montant numérique en lettres.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */


    // public function index(Request $request)
    // {
    //     $entites = Entite::where('enseigne',auth()->user()->societe)->where('active',1)->get();

    //     $filename = 'demo.pdf';
    //     $data = [
    //         'title' => 'Generate PDF using Laravel 12*+454G6 TCPDF - wans!',
    //         'contenu' => 'Ceci est le contenu de mon 45 PDF généré avec Laravel.',
    //         'date' => now()->format('d/m/Y'),
    //         'entite' =>$entites,
    //     ];  

    //     // $html = view('livewire.impression.pdfSample')->make('pdfSample', $data)->render();
    //     $html = view()->make('livewire.impression.pdfSamples', $data)->render();
    //     $pdf = new TCPDF('P','mm','A4');

    //     $pdf::SetPrintHeader(true);
    //     $pdf::SetPrintFooter(true);
    //     $pdf::SetTitle('WamsCo');
    //     $pdf::AddPage();
    //     $pdf::writeHTML($html, true, false, true, false, '');
    //     $pdf::Output(public_path($filename), 'F'); // F = creer et telechrge PDF | I = creer et affiche directement PDF       

    //     return response()->download(public_path($filename));

    // }
     // ***** Facturation fournisseur ********************
    // public function Old(Request $request){
    //     // Récupération des données
    //     $entites = Entite::where('enseigne', auth()->user()->societe)->where('active', 1)->get();
    //     $data = [
    //         'title'   => 'WamsCo Cloud5',
    //         'contenu' => 'Ceci est le contenu de mon PDF généré avec Laravel.',
    //         'dateJour'    => date('d-m-Y H:i:s'),
    //         'entite'  => $entites,
    //     ];

    //     // Génération de la vue HTML
    //     $html = view('livewire.impression.pdfSamples', $data)->render();

    //     // Création du PDF
    //     $pdf = new TCPDF(
    //         'P',        // Portrait
    //         'mm',       // Millimètre
    //         'A4',       // Format
    //         true,
    //         'UTF-8',
    //         false
    //     );

    //     // Informations
    //     $pdf->SetCreator('WamsCo');
    //     $pdf->SetAuthor('WamsCo');
    //     $pdf->SetTitle('WamsCo Cloud');
    //     $pdf->SetSubject('Document PDF');

    //     // Marges
    //     $pdf->SetMargins(10, 10, 10);
    //     $pdf->SetAutoPageBreak(true, 10);

    //     // Désactiver l'en-tête/pied si besoin
    //     $pdf->setPrintHeader(false);
    //     $pdf->setPrintFooter(false);

    //     // Nouvelle page
    //     $pdf->AddPage();

    //     // Écriture du HTML
    //     $pdf->writeHTML($html, true, false, true, false, '');

    //     // Retourner le PDF dans le navigateur
    //     return response($pdf->Output('wamsco.pdf', 'S'))->header('Content-Type', 'application/pdf')->header('Content-Disposition', 'inline; filename="wamsco.pdf"');
    // }
   
    public function facturationFourniPDF(){  
        
        // 1. Définir la langue en français pour le composant Number
        Number::useLocale('fr');

        $id_fact = request('id');         
        $code_fact = request('code');  
        $format = request('format');  
        $pos = request('pos');              
                
        $dateJour = date('d-m-Y H:i:s');

        $entite = Entite::where('id',auth()->user()->societe_id)->where('active',1)->get();
        $title = 'Facture | '.$entite[0]->raison_sociale;
        $activer_fidelite = $entite[0]->activer_fidelite;        
       
        $Fact = factureFournisseurEntete::where('societe_id',auth()->user()->societe_id)->where('id',$id_fact)->limit(1)->get(); 
        $id_fournisseur = $Fact[0]->id_fournisseur;
        $date_vente = $Fact[0]->created_at;
        $nom_user = $Fact[0]->nom_user;
        $statut_facture = $Fact[0]->etat;            
        $note = $Fact[0]->note; 
        $code_fact = $Fact[0]->code_facture; 
        $adresse_livraison = $Fact[0]->adresse_livraison;         
        $code_commande = $Fact[0]->code_commande;
        
        if($id_fournisseur != 0){
            $clientTier= Tier ::where('societe_id',auth()->user()->societe_id)->where('id',$id_fournisseur)->get(); 
            $client = $clientTier[0]->nom;
            $clientEmail = $clientTier[0]->email;
            $clientTel = $clientTier[0]->telephone;
            $clientVille = $clientTier[0]->ville;
            $clientPays = $clientTier[0]->pays;
            $nombre_point = $clientTier[0]->nombre_point;
            $sexe = $clientTier[0]->sexe;
            $adresse = $clientTier[0]->adresse;
        }
        else{
            $clientEmail = '';
            $clientTel = '';
            $clientVille = '';
            $clientPays = '';
            $client = '';
            $nombre_point = 0;
            $sexe = '';
            $adresse = '';
        }        

        $listeProd = factureFournisseurLigne ::where('societe_id',auth()->user()->societe_id)->where('id_facture_fournisseur_entete',$id_fact)->orderBy('id','asc')->get(); 
        $montant_ht = $listeProd->sum('montant_ht');  
        $montant_remise = $listeProd->sum('montant_remise');  
        $montant_tva = $listeProd->sum('montant_tva');  
        $montant_precompte = $listeProd->sum('montant_precompte');  
        $montant_ttc = $listeProd->sum('montant_ttc');  
        $qteTotal = $listeProd->sum('quantite'); 
        // Pour ticket
        $NbreProd = $listeProd->sum('quantite');
        $TotalPvente = $listeProd->sum('prix_vente'); 
        
        $reglement = Reglement_fourni ::where('societe_id',auth()->user()->societe_id)->where('id_facture_fournisseur_entete',$id_fact)->orderBy('id','asc')->get(); 
        $reste_a_percevoir = factureFournisseurEntete::where('societe_id',auth()->user()->societe_id)->where('id',$id_fact)->sum('reste_a_percevoir');  
        
        // mettre en lettre
        // $montant = $montant_ttc;  
        $montant = number_format($montant_ttc,0,'','');        
        if (!is_numeric($montant)) {
            return response()->json([
                'error' => 'Le montant fourni doit etre un nombre valide.'
            ], 400);
        }
        // 4. Conversion du montant en lettres
        $montantEnLettres = Number::spell($montant);
        $montant_lettres = ucfirst($montantEnLettres);
               
        $deviseTva = DeviseTva :: where('societe_id',auth()->user()->societe_id)->limit(1)->orderBy('id','asc')->count();             
        if($deviseTva == 0){
            $devise = 'FCFA';
        }
        else{
            $deviseTva = DeviseTva :: where('societe_id',auth()->user()->societe_id)->limit(1)->orderBy('id','asc')->get(); 
            $devise = $deviseTva[0]->devise;                
        } 
        if($format == 'A4'){
            $id_activite = 0;
            $page = 'Imprimer facture A4';
            LogActivity::addToLog('Imprimer facture client A4', $id_activite, $page);
            return view('livewire.impression.imp_facturation_fournis', compact('title','dateJour','entite','devise','activer_fidelite','id_fact','code_fact','adresse_livraison','code_commande','date_vente','nom_user','note',
            'statut_facture','client','clientEmail','clientTel','clientVille','clientPays','nombre_point','sexe','adresse','listeProd','montant_ht','montant_remise','montant_tva','montant_precompte'
            ,'qteTotal','montant_ttc','montant_lettres','reste_a_percevoir','reglement'));
        }
        else{
            $id_activite = 0;
            $page = 'Imprimer facture ticket';
            LogActivity::addToLog('Imprimer facture client ticket', $id_activite , $page);
            return view('livewire.impression.imp_facturation_fournis_ticket', compact('title','dateJour','entite','devise','activer_fidelite','id_fact','code_fact','adresse_livraison','code_commande','date_vente','nom_user','note',
            'statut_facture','client','clientEmail','clientTel','clientVille','clientPays','nombre_point','sexe','adresse','listeProd','montant_ht','montant_remise','montant_tva','montant_precompte'
            ,'qteTotal','montant_ttc','montant_lettres','reste_a_percevoir','reglement','NbreProd','TotalPvente'));
        }
        
    } 
     // ***** Facturation Client ********************
    public function facturationClientPDF(){  

        // 1. Définir la langue en français pour le composant Number
        Number::useLocale('fr');

        $id_fact = request('id');         
        $code_fact = request('code');  
        $format = request('format');  
        $pos = request('pos');              
                
        $dateJour = date('d-m-Y H:i:s');

        $entite = Entite::where('id',auth()->user()->societe_id)->where('active',1)->get();
        $title = 'Facture | '.$entite[0]->raison_sociale;
        $activer_fidelite = $entite[0]->activer_fidelite;
        
        if($pos == 'pv'){ 
            $Fact = PosFactureClientEntete::where('societe_id',auth()->user()->societe_id)->where('id',$id_fact)->limit(1)->get();
        }
        elseif($pos == 'pv_restau'){ 
            $Fact = RestauPosfactureClientEntete::where('societe_id',auth()->user()->societe_id)->where('id',$id_fact)->limit(1)->get();
        }
        else{ 
            $Fact = factureClientEntete::where('societe_id',auth()->user()->societe_id)->where('id',$id_fact)->limit(1)->get(); 
        }
        $client_id = $Fact[0]->id_client;
        $date_vente = $Fact[0]->created_at;
        $nom_user = $Fact[0]->nom_user;
        $statut_facture = $Fact[0]->etat;            
        $note = $Fact[0]->note; 
        $code_fact = $Fact[0]->code_facture; 
        $adresse_livraison = $Fact[0]->adresse_livraison; 
        if($pos == 'pv'){ 
            // $code_commande = $Fact[0]->code_facture; // juste au cas ou 
            $code_commande = '';
        }
        elseif($pos == 'pv_restau'){
            // $code_commande = $Fact[0]->code_facture;
            $code_commande = '';
        }
        else{ 
            $code_commande = $Fact[0]->code_commande;
        }
        
        if($client_id != 0){
            $clientTier= Tier ::where('societe_id',auth()->user()->societe_id)->where('id',$client_id)->get(); 
            $client = $clientTier[0]->nom;
            $clientEmail = $clientTier[0]->email;
            $clientTel = $clientTier[0]->telephone;
            $clientVille = $clientTier[0]->ville;
            $clientPays = $clientTier[0]->pays;
            $nombre_point = $clientTier[0]->nombre_point;
            $sexe = $clientTier[0]->sexe;
            $adresse = $clientTier[0]->adresse;
        }
        else{
            $clientEmail = '';
            $clientTel = '';
            $clientVille = '';
            $clientPays = '';
            $client = '';
            $nombre_point = 0;
            $sexe = '';
            $adresse = '';
        } 

        if($pos == 'pv'){ 

            $listeProd = PosFactureClientLigne ::where('societe_id',auth()->user()->societe_id)->where('id_facture_client_entete',$id_fact)->orderBy('id','asc')->get(); 
            $montant_ht = $listeProd->sum('montant_ht');  
            $montant_remise = $listeProd->sum('montant_remise');  
            $montant_tva = $listeProd->sum('montant_tva');  
            $montant_precompte = $listeProd->sum('montant_precompte');  
            $montant_ttc = $listeProd->sum('montant_ttc');  
            $qteTotal = $listeProd->sum('quantite');  
            $reglement = Reglement ::where('societe_id',auth()->user()->societe_id)->where('id_facture_client_entete',$id_fact)->orderBy('id','asc')->get(); 
            $reste_a_percevoir = PosFactureClientEntete::where('societe_id',auth()->user()->societe_id)->where('id',$id_fact)->sum('reste_a_percevoir'); 
            
            // Pour ticket
            $NbreProd = $listeProd->sum('quantite');
            $TotalPvente = $listeProd->sum('prix_vente');
            
            // mettre en lettre
            $montant = $montant_ttc;            
            if (!is_numeric($montant)) {
                return response()->json([
                    'error' => 'Le montant fourni doit etre un nombre valide.'
                ], 400);
            }
            // 4. Conversion du montant en lettres
            $montantEnLettres = Number::spell($montant);
            $montant_lettres = ucfirst($montantEnLettres);
        }
        elseif($pos == 'pv_restau'){
            $listeProd = RestauPosFactureClientLigne ::where('societe_id',auth()->user()->societe_id)->where('id_facture_client_entete',$id_fact)->orderBy('id','asc')->get(); 
            $montant_ht = $listeProd->sum('montant_ht');  
            $montant_remise = $listeProd->sum('montant_remise');  
            $montant_tva = $listeProd->sum('montant_tva');  
            $montant_precompte = $listeProd->sum('montant_precompte');  
            $montant_ttc = $listeProd->sum('montant_ttc');  
            $qteTotal = $listeProd->sum('quantite');  
            $reglement = Reglement ::where('societe_id',auth()->user()->societe_id)->where('id_facture_client_entete',$id_fact)->orderBy('id','asc')->get(); 
            $reste_a_percevoir = RestauPosfactureClientEntete::where('societe_id',auth()->user()->societe_id)->where('id',$id_fact)->sum('reste_a_percevoir'); 
            
            // Pour ticket
            $NbreProd = $listeProd->sum('quantite');
            $TotalPvente = $listeProd->sum('prix_vente');
            
            // mettre en lettre
            $montant = $montant_ttc;            
            if (!is_numeric($montant)) {
                return response()->json([
                    'error' => 'Le montant fourni doit etre un nombre valide.'
                ], 400);
            }
            // 4. Conversion du montant en lettres
            $montantEnLettres = Number::spell($montant);
            $montant_lettres = ucfirst($montantEnLettres);
        }
        else{ 

            $listeProd = factureClientLigne ::where('societe_id',auth()->user()->societe_id)->where('id_facture_client_entete',$id_fact)->orderBy('id','asc')->get(); 
            $montant_ht = $listeProd->sum('montant_ht');  
            $montant_remise = $listeProd->sum('montant_remise');  
            $montant_tva = $listeProd->sum('montant_tva');  
            $montant_precompte = $listeProd->sum('montant_precompte');  
            $montant_ttc = $listeProd->sum('montant_ttc');  
            $qteTotal = $listeProd->sum('quantite'); 
            // Pour ticket
            $NbreProd = $listeProd->sum('quantite');
            $TotalPvente = $listeProd->sum('prix_vente'); 
            
            $reglement = Reglement ::where('societe_id',auth()->user()->societe_id)->where('id_facture_client_entete',$id_fact)->orderBy('id','asc')->get(); 
            $reste_a_percevoir = factureClientEntete::where('societe_id',auth()->user()->societe_id)->where('id',$id_fact)->sum('reste_a_percevoir');  
            
            // mettre en lettre
            $montant = $montant_ttc;            
            if (!is_numeric($montant)) {
                return response()->json([
                    'error' => 'Le montant fourni doit etre un nombre valide.'
                ], 400);
            }
            // 4. Conversion du montant en lettres
            $montantEnLettres = Number::spell($montant);
            $montant_lettres = ucfirst($montantEnLettres);
        }         

        $deviseTva = DeviseTva :: where('societe_id',auth()->user()->societe_id)->limit(1)->orderBy('id','asc')->count();             
        if($deviseTva == 0){
            $devise = 'FCFA';
        }
        else{
            $deviseTva = DeviseTva :: where('societe_id',auth()->user()->societe_id)->limit(1)->orderBy('id','asc')->get(); 
            $devise = $deviseTva[0]->devise;                
        } 
        if($format == 'A4'){
            $id_activite = 0;
            $page = 'Imprimer facture A4';
            LogActivity::addToLog('Imprimer facture client A4', $id_activite, $page);
            return view('livewire.impression.imp_facturation_client', compact('title','dateJour','entite','devise','activer_fidelite','id_fact','code_fact','adresse_livraison','code_commande','date_vente','nom_user','note',
            'statut_facture','client','clientEmail','clientTel','clientVille','clientPays','nombre_point','sexe','adresse','listeProd','montant_ht','montant_remise','montant_tva','montant_precompte'
            ,'qteTotal','montant_ttc','montant_lettres','reste_a_percevoir','reglement'));
        }
        else{
            $id_activite = 0;
            $page = 'Imprimer facture ticket';
            LogActivity::addToLog('Imprimer facture client ticket', $id_activite , $page);
            return view('livewire.impression.imp_facturation_client_ticket', compact('title','dateJour','entite','devise','activer_fidelite','id_fact','code_fact','adresse_livraison','code_commande','date_vente','nom_user','note',
            'statut_facture','client','clientEmail','clientTel','clientVille','clientPays','nombre_point','sexe','adresse','listeProd','montant_ht','montant_remise','montant_tva','montant_precompte'
            ,'qteTotal','montant_ttc','montant_lettres','reste_a_percevoir','reglement','NbreProd','TotalPvente'));
        }
        
    } 
    // **** Impression commande client PDF *********
    public function impressionCmdClientPDF(){         
           
        // 1. Définir la langue en français pour le composant Number
        Number::useLocale('fr');

        $id_cmd = request('id');         
        $code_cmd = request('code');  
        $format = request('format');  
        $pos = request('pos');              
                
        $dateJour = date('d-m-Y H:i:s');

        $entite = Entite::where('id',auth()->user()->societe_id)->where('active',1)->get();
        $title = 'Commande | '.$entite[0]->raison_sociale;
        $activer_fidelite = $entite[0]->activer_fidelite;            
        
        $Cmd = CommandeClientEntete::where('societe_id',auth()->user()->societe_id)->where('id',$id_cmd)->limit(1)->get();            
        $client_id = $Cmd[0]->id_client;
        $date_vente = $Cmd[0]->created_at;
        $nom_user = $Cmd[0]->nom_user;
        $statut_cmd = $Cmd[0]->etat;            
        $note = $Cmd[0]->note; 
        $code_commande = $Cmd[0]->code_commande; 
        $adresse_livraison = $Cmd[0]->adresse_livraison;            
        $date_livraison = $Cmd[0]->date_livraison;            
        $condition_reglement = $Cmd[0]->condition_reglement;            
        $mode_reglement = $Cmd[0]->mode_reglement;            
        $code_proformaOk = $Cmd[0]->code_proforma;            
        
        if($client_id != 0){
            $clientTier= Tier ::where('societe_id',auth()->user()->societe_id)->where('id',$client_id)->get(); 
            $client = $clientTier[0]->nom;
            $clientEmail = $clientTier[0]->email;
            $clientTel = $clientTier[0]->telephone;
            $clientVille = $clientTier[0]->ville;
            $clientPays = $clientTier[0]->pays;
            $nombre_point = $clientTier[0]->nombre_point;
            $sexe = $clientTier[0]->sexe;
            $adresse = $clientTier[0]->adresse;
        }
        else{
            $clientEmail = '';
            $clientTel = '';
            $clientVille = '';
            $clientPays = '';
            $client = '';
            $nombre_point = 0;
            $sexe = '';
            $adresse = '';
        } 

        if($pos == 'pv'){ 

            $listeProd = CommandeClientLigne ::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$id_cmd)->orderBy('id','asc')->get(); 
            $montant_ht = $listeProd->sum('montant_ht');  
            $montant_remise = $listeProd->sum('montant_remise');  
            $montant_tva = $listeProd->sum('montant_tva');  
            $montant_precompte = $listeProd->sum('montant_precompte');  
            $montant_ttc = $listeProd->sum('montant_ttc');  
            $qteTotal = $listeProd->sum('quantite');                  
            // Pour ticket
            $NbreProd = $listeProd->sum('quantite');
            $TotalPvente = $listeProd->sum('prix_vente'); 
            
            // mettre en lettre
            $montant = $montant_ttc;            
            if (!is_numeric($montant)) {
                return response()->json([
                    'error' => 'Le montant fourni doit etre un nombre valide.'
                ], 400);
            }
            // 4. Conversion du montant en lettres
            $montantEnLettres = Number::spell($montant);
            $montant_lettres = ucfirst($montantEnLettres);
        }
        else{ 

            $listeProd = CommandeClientLigne ::where('societe_id',auth()->user()->societe_id)->where('id_commande_client_entete',$id_cmd)->orderBy('id','asc')->get(); 
            $montant_ht = $listeProd->sum('montant_ht');  
            $montant_remise = $listeProd->sum('montant_remise');  
            $montant_tva = $listeProd->sum('montant_tva');  
            $montant_precompte = $listeProd->sum('montant_precompte');  
            $montant_ttc = $listeProd->sum('montant_ttc');  
            $qteTotal = $listeProd->sum('quantite'); 
            // Pour ticket
            $NbreProd = $listeProd->sum('quantite');
            $TotalPvente = $listeProd->sum('prix_vente');   
            
            // mettre en lettre
            $montant = $montant_ttc;            
            if (!is_numeric($montant)) {
                return response()->json([
                    'error' => 'Le montant fourni doit etre un nombre valide.'
                ], 400);
            }
            // 4. Conversion du montant en lettres
            $montantEnLettres = Number::spell($montant);
            $montant_lettres = ucfirst($montantEnLettres);
        }         

        $deviseTva = DeviseTva :: where('societe_id',auth()->user()->societe_id)->limit(1)->orderBy('id','asc')->count();             
        if($deviseTva == 0){
            $devise = 'FCFA';
        }
        else{
            $deviseTva = DeviseTva :: where('societe_id',auth()->user()->societe_id)->limit(1)->orderBy('id','asc')->get(); 
            $devise = $deviseTva[0]->devise;                
        } 
        if($format == 'A4'){
            $code_proforma = 'vide';
            $id_activite = 0;
            $page = 'Imprimer commande A4';
            LogActivity::addToLog('Imprimer commande client A4', $id_activite, $page);
            return view('livewire.impression.imp_commande_client', compact('title','dateJour','entite','devise','activer_fidelite','id_cmd','code_commande','adresse_livraison','date_livraison',
            'condition_reglement','mode_reglement','code_proformaOk','date_vente','nom_user','note',
            'statut_cmd','client','clientEmail','clientTel','clientVille','clientPays','nombre_point','sexe','adresse','listeProd','montant_ht','montant_remise','montant_tva','montant_precompte',
            'qteTotal','montant_ttc','montant_lettres','code_proforma'));
        }
        else{
            $code_proforma = 'vide';
            $id_activite = 0;
            $page = 'Imprimer commande ticket';
            LogActivity::addToLog('Imprimer commande client ticket', $id_activite , $page);
            return view('livewire.impression.imp_commande_client_ticket', compact('title','dateJour','entite','devise','activer_fidelite','id_cmd','code_commande','adresse_livraison','date_livraison',
            'condition_reglement','mode_reglement','code_proformaOk','date_vente','nom_user','note',
            'statut_cmd','client','clientEmail','clientTel','clientVille','clientPays','nombre_point','sexe','adresse','listeProd','montant_ht','montant_remise','montant_tva','montant_precompte',
            'qteTotal','montant_ttc','montant_lettres','NbreProd','TotalPvente','code_proforma'));
        }
        
    } 
    // **** Impression Profoma commande client PDF *********
    public function impressionProforCmdClientPDF(){  

        // 1. Définir la langue en français pour le composant Number
        Number::useLocale('fr');

        $id_cmd = request('id');         
        $code_cmd = request('code');  
        $format = request('format');  
        $pos = request('pos');              
                
        $dateJour = date('d-m-Y H:i:s');

        $entite = Entite::where('id',auth()->user()->societe_id)->where('active',1)->get();
        $title = 'Commande | '.$entite[0]->raison_sociale;
        $activer_fidelite = $entite[0]->activer_fidelite;            
        
        $Cmd = ProformaClientEntete::where('societe_id',auth()->user()->societe_id)->where('id',$id_cmd)->limit(1)->get();            
        $client_id = $Cmd[0]->id_client;
        $date_vente = $Cmd[0]->created_at;
        $nom_user = $Cmd[0]->nom_user;
        $statut_cmd = $Cmd[0]->etat;            
        $note = $Cmd[0]->note; 
        $code_proforma = $Cmd[0]->code_proforma; 
        $adresse_livraison = $Cmd[0]->adresse_livraison;            
        $date_livraison = $Cmd[0]->date_livraison;            
        $condition_reglement = $Cmd[0]->condition_reglement;            
        $mode_reglement = $Cmd[0]->mode_reglement;            
        
        if($client_id != 0){
            $clientTier= Tier ::where('societe_id',auth()->user()->societe_id)->where('id',$client_id)->get(); 
            $client = $clientTier[0]->nom;
            $clientEmail = $clientTier[0]->email;
            $clientTel = $clientTier[0]->telephone;
            $clientVille = $clientTier[0]->ville;
            $clientPays = $clientTier[0]->pays;
            $nombre_point = $clientTier[0]->nombre_point;
            $sexe = $clientTier[0]->sexe;
            $adresse = $clientTier[0]->adresse;
        }
        else{
            $clientEmail = '';
            $clientTel = '';
            $clientVille = '';
            $clientPays = '';
            $client = '';
            $nombre_point = 0;
            $sexe = '';
            $adresse = '';
        } 

        if($pos == 'pv'){ 

            $listeProd = ProformaClientLigne ::where('societe_id',auth()->user()->societe_id)->where('id_proforma_client_entete',$id_cmd)->orderBy('id','asc')->get(); 
            $montant_ht = $listeProd->sum('montant_ht');  
            $montant_remise = $listeProd->sum('montant_remise');  
            $montant_tva = $listeProd->sum('montant_tva');  
            $montant_precompte = $listeProd->sum('montant_precompte');  
            $montant_ttc = $listeProd->sum('montant_ttc');  
            $qteTotal = $listeProd->sum('quantite');                  
            // Pour ticket
            $NbreProd = $listeProd->sum('quantite');
            $TotalPvente = $listeProd->sum('prix_vente');
            
            // mettre en lettre
            $montant = $montant_ttc;            
            if (!is_numeric($montant)) {
                return response()->json([
                    'error' => 'Le montant fourni doit etre un nombre valide.'
                ], 400);
            }
            // 4. Conversion du montant en lettres
            $montantEnLettres = Number::spell($montant);
            $montant_lettres = ucfirst($montantEnLettres);
        }
        else{ 

            $listeProd = ProformaClientLigne ::where('societe_id',auth()->user()->societe_id)->where('id_proforma_client_entete',$id_cmd)->orderBy('id','asc')->get(); 
            $montant_ht = $listeProd->sum('montant_ht');  
            $montant_remise = $listeProd->sum('montant_remise');  
            $montant_tva = $listeProd->sum('montant_tva');  
            $montant_precompte = $listeProd->sum('montant_precompte');  
            $montant_ttc = $listeProd->sum('montant_ttc');  
            $qteTotal = $listeProd->sum('quantite'); 
            // Pour ticket
            $NbreProd = $listeProd->sum('quantite');
            $TotalPvente = $listeProd->sum('prix_vente');   
            
            // mettre en lettre
            $montant = $montant_ttc;            
            if (!is_numeric($montant)) {
                return response()->json([
                    'error' => 'Le montant fourni doit etre un nombre valide.'
                ], 400);
            }
            // 4. Conversion du montant en lettres
            $montantEnLettres = Number::spell($montant);
            $montant_lettres = ucfirst($montantEnLettres);
        }         

        $deviseTva = DeviseTva :: where('societe_id',auth()->user()->societe_id)->limit(1)->orderBy('id','asc')->count();             
        if($deviseTva == 0){
            $devise = 'FCFA';
        }
        else{
            $deviseTva = DeviseTva :: where('societe_id',auth()->user()->societe_id)->limit(1)->orderBy('id','asc')->get(); 
            $devise = $deviseTva[0]->devise;                
        } 
        if($format == 'A4'){   
            $id_activite = 0;
            $page = 'Imprimer proforma A4';
            LogActivity::addToLog('Imprimer proforma client A4', $id_activite, $page);
            return view('livewire.impression.imp_commande_client', compact('title','dateJour','entite','devise','activer_fidelite','id_cmd','code_proforma','adresse_livraison','date_livraison',
            'condition_reglement','mode_reglement','date_vente','nom_user','note',
            'statut_cmd','client','clientEmail','clientTel','clientVille','clientPays','nombre_point','sexe','adresse','listeProd','montant_ht','montant_remise','montant_tva','montant_precompte'
            ,'qteTotal','montant_ttc','montant_lettres'));
        }
        else{
            $id_activite = 0;
            $page = 'Imprimer proforma ticket';
            LogActivity::addToLog('Imprimer proforma client ticket', $id_activite , $page);
            return view('livewire.impression.imp_commande_client_ticket', compact('title','dateJour','entite','devise','activer_fidelite','id_cmd','code_proforma','adresse_livraison','date_livraison',
            'condition_reglement','mode_reglement','date_vente','nom_user','note',
            'statut_cmd','client','clientEmail','clientTel','clientVille','clientPays','nombre_point','sexe','adresse','listeProd','montant_ht','montant_remise','montant_tva','montant_precompte'
            ,'qteTotal','montant_ttc','montant_lettres','NbreProd','TotalPvente'));
        }
        
    } 
    public function impressionRecuTierPDF(){
        // 1. Définir la langue en français pour le composant Number
        Number::useLocale('fr');

        $id = request('id');         
        $code_tier = request('code');  
        $format = request('format'); 

        $dateJour = date('d-m-Y H:i:s');

        $entite = Entite::where('id',auth()->user()->societe_id)->where('active',1)->get();
        $title = 'Tier | '.$entite[0]->raison_sociale;
        $activer_fidelite = $entite[0]->activer_fidelite;  
        $societe = $entite[0]->raison_sociale;  

        $clientTier= SoldeTier ::where('societe_id',auth()->user()->societe_id)->where('id',$id)->get(); 
        $id_solde = $clientTier[0]->id;
        $id_tier = $clientTier[0]->id_tier;
        $client = $clientTier[0]->nom_tier;
        $code_tier = $clientTier[0]->code_tier;
        $clientEmail = $clientTier[0]->email;
        $clientTel = $clientTier[0]->telephone;
        $clientVille = $clientTier[0]->ville;
        $clientPays = $clientTier[0]->pays;
        $sexe = $clientTier[0]->sexe;
        $adresse = $clientTier[0]->adresse;
        $compte = $clientTier[0]->compte;       
        $nom_user = $clientTier[0]->nom_user;       
        // $societe = $clientTier[0]->societe; 
        $date_recu = $clientTier[0]->created_at;       
        $credit = $clientTier[0]->credit;
        $debit = $clientTier[0]->debit;
        $designation = $clientTier[0]->designation; 
        
        // mettre en lettre
        if($credit > 0){
            $montant = $credit; 
        } 
        else{
            $montant = $debit; 
        }
            
        if (!is_numeric($montant)) {
            return response()->json([
                'error' => 'Le montant fourni doit etre un nombre valide.'
            ], 400);
        }  
        // 4. Conversion du montant en lettres
        $montantEnLettres = Number::spell($montant);
        $montant_lettres = ucfirst($montantEnLettres);

        $deviseTva = DeviseTva :: where('societe_id',auth()->user()->societe_id)->limit(1)->orderBy('id','asc')->count();             
        if($deviseTva == 0){
            $devise = 'FCFA';
        }
        else{
            $deviseTva = DeviseTva :: where('societe_id',auth()->user()->societe_id)->limit(1)->orderBy('id','asc')->get(); 
            $devise = $deviseTva[0]->devise;                
        } 
        if($format == 'A4'){
            $code_proforma = 'vide';
            $id_activite = 0;
            $page = 'Imprimer commande A4';
            LogActivity::addToLog('Imprimer commande client A4', $id_activite, $page);
            return view('livewire.impression.imp_recu_client', compact('title','dateJour','entite','devise','id_solde','compte','client','code_tier','clientEmail','clientTel','clientVille','clientPays','date_recu','debit','credit','montant_lettres','designation','nom_user','societe','adresse',));
        }
        else{
            $code_proforma = 'vide';
            $id_activite = 0;
            $page = 'Imprimer commande ticket';
            LogActivity::addToLog('Imprimer commande client ticket', $id_activite , $page);
            return view('livewire.impression.imp_recu_client_ticket', compact('title','dateJour','entite','devise','id_solde','compte','client','code_tier','clientEmail','clientTel','clientVille','clientPays','date_recu','debit','credit','montant_lettres','designation','nom_user','societe','adresse',));
        }





    }
}
