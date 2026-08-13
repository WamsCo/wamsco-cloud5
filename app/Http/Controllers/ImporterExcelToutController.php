<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Spatie\SimpleExcel\SimpleExcelReader;
use App\Models\Produit;
use App\Models\Categorie;
use App\Models\Tier;
use App\Models\Stock;
use App\Models\Entrepot;
use App\Models\GrilleSalariale;
use App\Helpers\LogActivity;
use App\Models\LogActivity as LogActivityModel;

class ImporterExcelToutController extends Controller
{	
    public function import_tier(Request $request){		
		$request->validate([
			'fichier_tiers'=>'required|bail|file|mimes:xlsx',   
		]);
		// on deplace le fichier uploade vers "public" pour le lire
		$fichier = $request->fichier_tiers->move(public_path(), $request->fichier_tiers->hashName());

		// $reader: l'instance Spatie\SimpleExcel\SimpleExcelReader
		$reader = SimpleExcelReader::create($fichier);
		
		// On recupere le contenu (les lignes) du fichier et on le convertir en tableau
	    $donnees = json_decode($reader->getRows());		
		$i = 0;
		// On insere toutes les lignes dans la base de donnees 		
		if(!empty($donnees)){				
			foreach($donnees as $donnee){	                							
				// on teste s'il la colonne existe avec isset() 
				if(isset($donnee->nom) && isset($donnee->type_tiers) && isset($donnee->telephone) && isset($donnee->sexe) && isset($donnee->ville) && isset($donnee->pays)){				
					$test_tier = Tier::where('societe',auth()->user()->societe)->where('nom', $donnee->nom)->count();
					if($test_tier == 0){
						$tiers = new Tier;
						$tiers->nom = $donnee->nom; 
						$tiers->type_tiers = $donnee->type_tiers;
						$tiers->telephone = $donnee->telephone;
						$tiers->etat = 0;				
						$tiers->ville = $donnee->ville;
						$tiers->pays = $donnee->pays;
						$tiers->sexe = $donnee->sexe;
						$tiers->nombre_point = 0;			
						$tiers->retrait_point = 0;			
						$tiers->objectif_point = 0;	
						$tiers->solde = 0;		
						$tiers->nom_user = auth()->user()->email;
						$tiers->user_id = auth()->user()->id;								
						$tiers->societe = auth()->user()->societe;			
						$tiers->save();	
						$i++; 						
					}					
				}
				else{
					$reader->close();
					File::delete($fichier); // ceci efface le fichier a la fin de operation
					flash ('Désolé, votre fichier comporte une erreur. Téléchargez le modèle svp ! (<strong>NB: Ne pas modifier le nom des colonnes.</strong>)')->error();
					$id_activite = 0;
					$page = 'Erreur Importation';
					LogActivity::addToLog('Erreur Importation', $id_activite, $page);   
					return back();
				} 				              
			}                        
            $reader->close();
			File::delete($fichier); // ceci efface le fichier a la fin de operation
			if($i > 0){
				flash ('Importation (<strong>'.$i.'</strong>) effectuée avec <strong>succès !</strong>')->success();
			}
			else{
				flash ('Aucune importation (<strong>'.$i.'</strong>) effectuée dans ce fichier: <strong>Les élements existent déja !</strong>')->warning();
			}
			$id_activite = 0;
			$page = 'Tiers';
			LogActivity::addToLog('Importation Excel tiers', $id_activite, $page);   
            return back();	
		}
		else{
			$reader->close();
			File::delete($fichier); // ceci efface le fichier a la fin de operation
			flash ('<strong>Désolé</strong>, votre fichier est <strong>vide</strong> !')->warning();
			return back();
		}
	}
	public function import_produit(Request $request){
		$request->validate([
			'fichier_produit' => 'required|file|mimes:xlsx',
		]);

		$fichier = $request->file('fichier_produit')->move(public_path(), $request->file('fichier_produit')->hashName());
		$reader = SimpleExcelReader::create($fichier);
		$rows = $reader->getRows();
		$nbImport = 0;
		try {
			DB::beginTransaction();
				foreach ($rows as $row) {

					if (!isset($row['nom_produit']) || !isset($row['reference']) || !isset($row['categorie']) || !isset($row['fournisseur']) || !isset($row['prix_achat']) || !isset($row['prix_vente_min']) ||
						!isset($row['prix_vente']) ||  !isset($row['limite_stock_alerte']) || !isset($row['nom_magasin']) ) 
					{
						throw new \Exception(
							"Le fichier Excel ne respecte pas le modèle d'importation."
						);
					}

					// ENTREPOT

					$entrepot = Entrepot::firstOrCreate(
						[
							'societe' => auth()->user()->societe,
							'nom'     => trim($row['nom_magasin']),
						],
						[
							'description'  => trim($row['nom_magasin']),
							'active'       => 1,
							'nom_user'     => auth()->user()->name,
							'user_id'      => auth()->id(),
							'societe_mere' => auth()->user()->societe_mere,
						]
					);

					// CATEGORIE
					Categorie::firstOrCreate(
						[
							'societe'       => auth()->user()->societe,
							'nom_categorie' => trim($row['categorie']),
						],

						[
							'description' => trim($row['categorie']),
							'restaurant'      => 'Non',
							'nom_user'    => auth()->user()->name,
							'user_id'     => auth()->id(),
						]
					);

					// FOURNISSEUR
					Tier::firstOrCreate(
						[
							'societe' => auth()->user()->societe,
							'nom'     => trim($row['fournisseur']),
						],
						[
							'raison_sociale' => trim($row['fournisseur']),
							'telephone'       => '6',
							'type_tiers'      => 'Fournisseur',
							'sexe'            => 'Masculin',
							'pays'            => 'Cameroun',
							'etat'            => 1,
							'nombre_point'    => 0,
							'retrait_point'   => 0,
							'objectif_point'  => 0,
							'solde'           => 0,
							'nom_user'        => auth()->user()->name,
							'user_id'         => auth()->id(),
						]
					);

					// PRODUIT
					$produit = Produit::where('societe', auth()->user()->societe)->where('nom_produit', trim($row['nom_produit']))->first();
					if ($produit) {
						continue;
					}
					$produit = Produit::create([
						'nom_produit' => trim($row['nom_produit']),
						'reference' => trim($row['reference']),
						'categorie' => trim($row['categorie']),
						'fournisseur' => trim($row['fournisseur']),
						'prix_achat' => $row['prix_achat'],
						'prix_vente_min' => $row['prix_vente_min'],
						'prix_vente' => $row['prix_vente'],
						'entrepot' => $entrepot->id,
						'limite_stock_alerte' => $row['limite_stock_alerte'],
						'etat' => 1,
						'type_produit' => 'Produit',
						'nature_produit' => 'Manufacturé',
						'tva' => 0,
						'nom_user' => auth()->user()->name,
						'user_id' => auth()->id(),
						'societe' => auth()->user()->societe,

					]);						

					Stock::create([
						'id_entrepot' => $entrepot->id,
						'nom_produit' => $produit->nom_produit,
						'id_produit' => $produit->id,
						'reference' => $produit->reference,
						'categorie' => $produit->categorie,
						'type_produit' => 'Produit',
						'nature_produit' => 'Manufacturé',
						'quantite' => 0,
						'prix_achat_last' => $produit->prix_achat,
						'prix_moyen_pondere_achat' => $produit->prix_achat,
						'valorisation_achat_total' => 0,
						'prix_vente_unitaire' => $produit->prix_vente,
						'prix_vente_min' => $produit->prix_vente_min,
						'valeur_vente_total' => 0,
						'limite_stock_alerte' => $produit->limite_stock_alerte,
						'etat' => 1,
						'societe' => auth()->user()->societe,
						'nom_user' => auth()->user()->name,
						'user_id' => auth()->id(),
					]);
					$nbImport++;
				}

				DB::commit();
				$reader->close();
				File::delete($fichier);
				if ($nbImport > 0) {
					flash("Importation (<strong>{$nbImport}</strong>) effectuée avec <strong>succès !</strong>")->success();
				} 
				else {
					flash("Aucun produit importé.<br>Tous les produits existent déjà.")->warning();
				}
				LogActivity::addToLog('Importation Excel produits',0,'Produits');
				return back();
		} catch (\Exception $e) {
				DB::rollBack();
				$reader->close();
				File::delete($fichier);
				Log::error($e);
				flash("<strong>Erreur :</strong> ".$e->getMessage())->error();
				LogActivity::addToLog('Erreur Importation',0,'Erreur Importation');
				return back();
		}
	}	
	public function import_grille_salariale(Request $request){		
		$request->validate([
			'fichier_grille_salariale'=>'required|bail|file|mimes:xlsx',   
		]);
		// on deplace le fichier uploade vers "public" pour le lire
		$fichier = $request->fichier_grille_salariale->move(public_path(), $request->fichier_grille_salariale->hashName());

		// $reader: l'instance Spatie\SimpleExcel\SimpleExcelReader
		$reader = SimpleExcelReader::create($fichier);
		
		// On recupere le contenu (les lignes) du fichier et on le convertir en tableau
	    $donnees = json_decode($reader->getRows());		
		$i = 0;
		// On insere toutes les lignes dans la base de donnees 		
		if(!empty($donnees)){				
			foreach($donnees as $donnee){	                							
				// on teste s'il la colonne existe avec isset() 
				if(isset($donnee->categorie) && isset($donnee->echelon) && isset($donnee->salaire_base)){				
					$test_grille = GrilleSalariale::where('societe',auth()->user()->societe)->where('categorie', $donnee->categorie)->where('echelon', $donnee->echelon)->count();
					if($test_grille == 0){
						$grille = new GrilleSalariale;
						$grille->categorie = $donnee->categorie; 
						$grille->echelon = mb_strtoupper($donnee->echelon, 'UTF-8'); // mette en majuscule
						$grille->salaire_base = $donnee->salaire_base;
						
						$reference = 'CAT-'.$donnee->categorie.''.$donnee->echelon;
						$grille->reference = mb_strtoupper($reference, 'UTF-8');	// mette en majuscule							
						$grille->nom_user = auth()->user()->email;
						$grille->user_id = auth()->user()->id;								
						$grille->societe = auth()->user()->societe;			
						$grille->save();	
						$i++; 						
					}					
				}
				else{
					$reader->close();
					File::delete($fichier); // ceci efface le fichier a la fin de operation
					flash ('Désolé, votre fichier comporte une erreur. Téléchargez le modèle svp ! (<strong>NB: Ne pas modifier le nom des colonnes.</strong>)')->error();
					$id_activite = 0;
					$page = 'Erreur Importation';
					LogActivity::addToLog('Erreur Importation Grille Salariale', $id_activite, $page);   
					return back();
				} 				              
			}                        
            $reader->close();
			File::delete($fichier); // ceci efface le fichier a la fin de operation
			if($i > 0){
				flash ('Importation (<strong>'.$i.'</strong>) effectuée avec <strong>succès !</strong>')->success();
			}
			else{
				flash ('Aucune importation (<strong>'.$i.'</strong>) effectuée dans ce fichier: <strong>Les élements existent déja !</strong>')->warning();
			}
			$id_activite = 0;
			// $page = 'Importation Grille Salariale Excel';
			$page = 'GrilleSalariale';
			LogActivity::addToLog('Importation Excel Grille Salariale', $id_activite, $page);   
            return back();	
		}
		else{
			$reader->close();
			File::delete($fichier); // ceci efface le fichier a la fin de operation
			flash ('<strong>Désolé</strong>, votre fichier est <strong>vide</strong> !')->warning();
			return back();
		}
	}
	public function import_produitOld(Request $request){		
		$request->validate([
			'fichier_produit'=>'required|bail|file|mimes:xlsx',   
		]);
		// on deplace le fichier uploade vers "public" pour le lire
		$fichier = $request->fichier_produit->move(public_path(), $request->fichier_produit->hashName());

		// $reader: l'instance Spatie\SimpleExcel\SimpleExcelReader
		$reader = SimpleExcelReader::create($fichier);
		
		// On recupere le contenu (les lignes) du fichier et on le convertir en tableau
	    $donnees = json_decode($reader->getRows());		
		$i = 0;
		// On insere toutes les lignes dans la base de donnees 		
		if(!empty($donnees)){				
			foreach($donnees as $donnee){	                							
				// on teste s'il la colonne existe avec isset() 
				if(isset($donnee->nom_produit) && isset($donnee->reference) && isset($donnee->categorie) && isset($donnee->fournisseur) && isset($donnee->prix_achat) && 
				   isset($donnee->prix_vente_min) && isset($donnee->prix_vente) && isset($donnee->limite_stock_alerte) && isset($donnee->nom_magasin)){					   

				   // creer les differents entrepots
					$test_entrep = Entrepot::where('societe',auth()->user()->societe)->where('nom', $donnee->nom_magasin)->count();
					if($test_entrep == 0){
						$entrep = new Entrepot;
						$entrep->nom = $donnee->nom_magasin;
						$entrep->description = $donnee->nom_magasin;
						$entrep->active = 1;
						$entrep->nom_user = auth()->user()->email;		
						$entrep->user_id = auth()->user()->id;			
						$entrep->societe = auth()->user()->societe;			
						$entrep->societe_mere = auth()->user()->societe_mere;			
						$entrep->save();
						
						// ceci recupere le dernier enregistrement cree a l'instant
						$dernier_id = $entrep->id;
						// $dernier_id = Entrepot::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id; 
					}

					// $entrep = Entrepot::where('societe',auth()->user()->societe)->first();
                	// $entrepo_id =  $entrep->id;

					$test_prod = Produit::where('societe',auth()->user()->societe)->where('nom_produit', $donnee->nom_produit)->count();
					if($test_prod == 0){		

						$produit = new Produit;
						$produit->nom_produit = $donnee->nom_produit;
						$produit->reference = $donnee->reference;
						$produit->categorie = $donnee->categorie;
						$produit->fournisseur = $donnee->fournisseur; 
						$produit->prix_achat = $donnee->prix_achat;
						$produit->prix_vente_min = $donnee->prix_vente_min;
						$produit->prix_vente = $donnee->prix_vente;
						$produit->entrepot = $dernier_id; // id entrepot						
						$produit->limite_stock_alerte = $donnee->limite_stock_alerte;
						$produit->etat = 1;							
						$produit->type_produit = 'Produit';
						$produit->nature_produit = 'Manufacturé';
						$produit->tva = 0;			
						$produit->nom_user = auth()->user()->name;
						$produit->user_id = auth()->user()->id;								
						$produit->societe = auth()->user()->societe;								
						$produit->save();
						
						// ceci recupere le dernier enregistrement cree a l'instant
						$dernier_id = $produit->id;
						// $dernier_id_prod = Produit::where('societe',auth()->user()->societe)->where('user_id',auth()->user()->id)->latest()->first()->id; 						
						
						// Creation entrepot dans stock						
						$quantite = 0;
						$valorisation_achat_total = 0;
						$valeur_vente_total = 0;
						// $limite_stock_alerte_bd = 5;
						Stock::create(['id_entrepot'=>$dernier_id,'nom_produit'=>$donnee->nom_produit,'id_produit'=>$dernier_id_prod,'reference'=>$donnee->reference,'categorie'=>$donnee->categorie,
						'type_produit'=>'Produit','nature_produit'=>'Manufacturé','quantite'=>$quantite,
						'prix_achat_last'=>$donnee->prix_achat, 'prix_moyen_pondere_achat'=>$donnee->prix_achat,'valorisation_achat_total'=>$valorisation_achat_total,'prix_vente_unitaire'=>$donnee->prix_vente,
						'prix_vente_min'=>$donnee->prix_vente_min,'valeur_vente_total'=>$valeur_vente_total,
						'limite_stock_alerte'=>$donnee->limite_stock_alerte,'etat'=>1,'societe'=>auth()->user()->societe,'nom_user'=>auth()->user()->name,'user_id'=>auth()->user()->id]);						
						
						// creer les differentes categorie
						$test_cat = Categorie::where('societe',auth()->user()->societe)->where('nom_categorie', $donnee->categorie)->count();
						if($test_cat == 0){
							$categor = new Categorie;
							$categor->nom_categorie = $donnee->categorie;
							$categor->description = $donnee->categorie;
							$categor->nom_user = auth()->user()->name;	
							$categor->user_id = auth()->user()->id;		
							$categor->societe = auth()->user()->societe;			
							$categor->save();
						}

						// creer les differentes Tier
						$test_tier = Tier::where('societe',auth()->user()->societe)->where('nom', $donnee->fournisseur)->count();
						if($test_tier == 0){
							$tiers = new Tier;
							$tiers->nom = $donnee->fournisseur;
							$tiers->raison_sociale = $donnee->fournisseur;							
							$tiers->telephone = '6';							
							$tiers->type_tiers = 'Fournisseur';
							$tiers->sexe = 'Masculin';
							$tiers->pays = 'Cameroun';
							$tiers->etat = 1;
							$tiers->nombre_point = 0;			
							$tiers->retrait_point = 0;			
							$tiers->objectif_point = 0;	
							$tiers->solde = 0;							
							$tiers->nom_user = auth()->user()->name;		
							$tiers->user_id = auth()->user()->id;		
							$tiers->societe = auth()->user()->societe;			
							$tiers->save();
						}
						$i++; 
					}									
				}
				else{
					$reader->close();
					File::delete($fichier); // ceci efface le fichier a la fin de operation
					flash ('Désolé, votre fichier comporte une erreur. Téléchargez le modèle svp ! (<strong>NB: Ne pas modifier le nom des colonnes.</strong>)')->error();
					$id_activite = 0;
					$page = 'Erreur Importation';
					LogActivity::addToLog('Erreur Importation', $id_activite, $page);   
					return back();
				} 				              
			}                        
            $reader->close();
			File::delete($fichier); // ceci efface le fichier a la fin de operation
			if($i > 0){
				flash ('Importation (<strong>'.$i.'</strong>) effectuée avec <strong>succès !</strong>')->success();
			}
			else{
				flash ('Aucune importation (<strong>'.$i.'</strong>) effectuée dans ce fichier: <strong>Les élements existent déja !</strong>')->warning();
			}
			$id_activite = 0;
			$page = 'Produits';
			LogActivity::addToLog('Importation Excel produits', $id_activite, $page);   
            return back();	
		}
		else{
			$reader->close();
			File::delete($fichier); // ceci efface le fichier a la fin de operation
			flash ('<strong>Désolé</strong>, votre fichier est <strong>vide</strong> !')->warning();
			return back();
		}
	}
}
