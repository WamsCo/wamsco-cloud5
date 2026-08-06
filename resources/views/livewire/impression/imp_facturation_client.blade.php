<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>{{$title}}</title>
        <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="ERP WamsCo-cloud est une solution pour les PME,PMI,etc">
        <meta name="author" content="WamsCo">
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="assets/css/bootstrap.min.css"> 
        <link rel="stylesheet" type="text/css" href="assets/css/default/wamsco_fact.css">
        <link rel="shortcut icon" href="assets/img/logo/favicon.ico"> 
        <link href="fontawesome-6.1.1/css/all.min.css" rel="stylesheet" />
        <script type="text/javascript">window.print()</script>
    </head>
    <body class="facture">
        <div class="container largeur"> 
            {{-- entete --}}
            {{-- <div style="display:flex; margin-top:4px;">
                <div>
                    @foreach($entite as $entites)
                        <img style="width: 100%;" src="storage/{{$entites->logo_fact_entete}}" alt=""/>   
                    @endforeach
                </div>                
            </div> --}}
            {{-- Logo et facture --}}
             <div style="display:flex; justify-content:space-between; margin-bottom:10px; margin-top:4px;">
                <div style="width:20%; display:flex; justify-content:center; flex-direction:column;padding: 0 4px;">
                    @foreach($entite as $entites)
                        <img style="max-width: 100px; padding:0px; max-height: 72px;border-radius: 5px;" src="storage/{{$entites->logo}}" alt="logo"/>
                    @endforeach
                </div>
                <div style="width:80%; display:flex; justify-content:space-between; gap:10px;padding: 0 4px;">
                    <div style="display:flex; flex-direction:column;">
                        {{-- @if($client != "Client ordinaire") --}}
                            <span style="font-size: 23px; font-weight: 700;">Facture</span>
                            <span class="fact" style="font-size: 13px;">Imprimer le: <span class="donnees">{{date('d/m/Y, H:i:s', strtotime($dateJour))}}</span></span>
                            <span class="fact" style="font-size: 13px;">Par: <span class="donnees">{{substr(auth()->user()->name,0,15)}}</span></span>
                        {{-- @endif --}}
                    </div>
                    <div style="display:flex; flex-direction:column;">
                        <span class="fact_num">Facture N°: {{$code_fact}}</span>
                        <span class="fact" style="font-size: 13px;padding-top: 6px;">Date facturation: <span class="donnees">{{date('d/m/Y, H:i:s', strtotime($date_vente))}}</span></span>
                        <span class="fact" style="font-size: 13px;">Caissier-e: <span class="donnees">{{substr($nom_user,0,15)}}</span></span>
                        @if($code_commande)
                            <span class="fact_numk" style="font-weight: bold;font-size: 12px;color: #27852f;">Réf. commande: {{$code_commande}}</span>
                        @endif
                    </div>
                </div>
             </div>

             <div style="display:flex; justify-content:space-between; gap:10px;">
                <div style="width:50%; display:flex; flex-direction:column;border:1px dotted;border-radius: 5px;">
                    @foreach($entite as $entites)      
                        <div style="border-bottom:1px dotted; font-size: 16px; font-weight: 700; padding:5px 5px;">
                            <span style="font-weight: 800; color: #274085;">{{substr($entites->raison_sociale,0,32) > substr($entites->raison_sociale,0,31) ? substr($entites->raison_sociale,0,32).'...': $entites->raison_sociale}}</span> 
                        </div>
                        <div style="display:flex; flex-direction:column; padding:0 5px;">
                            <span style="font-weight: 600;">{{$entites->ville}} / {{$entites->pays}}</span> 
                            @if(!empty($entites->adresse)) 
                                <span style="font-weight: 600;">Adresse : {{$entites->adresse}}</span>                                                      
                            @endif                           
                            <span style="font-weight: 600;">Tél : {{$entites->telephone}}</span>                
                            <span style="font-weight: 600;">Email : {{$entites->email}}</span> 
                            @if(!empty($entites->registre_com)) 
                                <span style="font-weight: 600;">RCCM: {{$entites->registre_com}}</span>                                     
                            @endif 
                            @if(!empty($entites->niu)) 
                                <span style="font-weight: 600;">NIU: {{$entites->niu}}</span>                                     
                            @endif  
                        </div>
                    @endforeach
                </div>
                <div style="width:50%; display:flex; flex-direction:column; border:1px dotted;border-radius: 5px;">
                    <div style="border-bottom:1px dotted;font-size: 16px; font-weight: 700; padding:5px 5px;">
                        Client
                    </div>
                    <div style="display:flex; flex-direction:column; padding:0 5px;">
                        <span class="fact"> 
                            @if($sexe == "Masculin")
                                <span class="donnees">M. {{substr($client,0,50)}}</span>
                            @else
                                <span class="">Mme {{substr($client,0,50)}}</span>
                            @endif
                        </span>                        
                        @if($clientTel)
                            <span class="fact">Tél: <span class="donnees">{{$clientTel}}</span></span>
                        @endif
                        @if($clientEmail)
                            <span class="fact">Email: <span class="donnees">{{$clientEmail}}</span></span>
                        @endif
                        @if(!empty($clientVille) or !empty($clientPays))
                            <span class="fact">Ville/Pays: <span class="donnees">{{$clientVille}}-{{$clientPays}}</span></span>
                        @endif
                        @if(!empty($adresse))
                            <span class="fact">Adresse: <span class="donnees">{{$adresse}}</span></span>
                        @endif
                        @if(!empty($adresse_livraison)) 
                            <span class="fact">Adresse livraison: <span class="donnees">{{$adresse_livraison}}</span></span>                                     
                        @endif
                        @if($activer_fidelite == 1)
                            <span class="fact">Fidélité: <span class="donnees" style="color: #ff0000;">{{number_format($nombre_point,0,' ',' ')}} </span>Points</span>
                        @endif
                    </div>
                </div>                
             </div>
             @if($note)
                <div class="mx-1 mt-2">  
                    <span style="font-weight: 600;color: #ff0000;">Important: </span><span style="white-space: pre-line;">{{ $note }}</span>
                </div>
            @endif
            {{-- Informations Facture --}}            
            <table class="table table-striped" style="margin-top:10px;">                
                <thead>                                    
                    <tr> 
                        <th>Désignation</th>
                        <th class="tete_fact">Quantité</th>                                                                       
                        <th class="tete_fact">Prix</th>                         
                        <th class="tete_fact">Remise</th> 
                        @if($montant_tva > 0)                   
                            <th class="tete_fact">TVA</th>
                        @endif
                        @if($montant_precompte > 0)                   
                            <th class="tete_fact">Précompte</th> 
                        @endif         
                        <th class="tete_fact">Montant HT</th> 
                        <th class="tete_fact">Sous-total</th>  
                    </tr>  
                </thead>                    
                <tbody>
                    @foreach($listeProd as $listeProds)                                       
                        <tr> 
                            <td class="">{{$listeProds->produit}} - {{$listeProds->reference}}
                                @if($listeProds->infos)
                                    ({{$listeProds->infos}})
                                @endif
                            </td> 
                            <td class="fact_detail">{{$listeProds->quantite}}</td> 
                            <td class="fact_detail">{{$listeProds->prix_vente}}</td>
                            <td class="fact_detail">{{$listeProds->remise}}%</td>
                            @if($montant_tva > 0)                                
                                <td class="fact_detail">{{$listeProds->tva}}%</td> 
                            @endif
                            @if($montant_precompte > 0) 
                                <td class="fact_detail">{{$listeProds->precompte}}%</td>                 
                            @endif                  
                            <td class="fact_detail">{{number_format($listeProds->montant_ht,0,' ',' ')}}</td>                 
                            <td class="fact_detail">
                                @if($listeProds->offrir == "Oui")
                                    <span style="font-weight: 700;">Offert</span>
                                @else
                                    {{number_format($listeProds->montant_ttc,0,' ',' ')}}
                                @endif 
                            </td>                 
                        </tr>
                    @endforeach
                </tbody>                                
            </table>
            {{-- Debut des Totaux --}}
            <div style="display: flex; justify-content: space-between;margin-top:10px; margin-bottom: 14px; gap:5px;">
                <div style="width:50%; display:flex; flex-direction:column; padding:0 4px;">
                    <span style="border-bottom: 2px solid;width: 100%; display: block;font-size: 17px;font-weight: 700;text-transform: uppercase;">Informations de paiement</span>
                    <span style="">Statut de la Facture: 
                        @if($statut_facture == "Payée")
                            <span class="donnees text-success">{{$statut_facture}}</span>
                        @elseif($statut_facture == "Commencée")
                            <span class="donnees text-danger">{{$statut_facture}}</span>
                        @else
                            <span class="donnees">{{$statut_facture}}</span>
                         @endif
                    </span>          
                    @if($statut_facture != "Non payé" && $statut_facture != "Brouillon")
                        @foreach($reglement as $reglements)
                            <div style="display: flex; justify-content: space-between; font-size: 14px;">
                                <div style="font-style: italic;">
                                    Payé le {{date('d/m/Y H:i:s', strtotime($reglements->created_at))}} ({{$reglements->mode_reglement}})
                                </div>
                                <div style="font-style: italic; font-weight:600">
                                    {{number_format($reglements->montant_regler,0,' ',' ')}} {{$devise}}
                                </div>
                            </div>
                        @endforeach
                    @endif                    
                </div>
                <div style="width:50%; display:flex; flex-direction:column; padding: 4px 40px; background:#e3e3e3">
                    <span style="display: flex;flex-direction: row; justify-content: space-between; align-items: center;font-weight: 500;">Nombre d'articles: <span class="donnees" style="font-weight: 700;">{{$qteTotal}}</span></span>
                    <span style="display: flex;flex-direction: row; justify-content: space-between; align-items: center;font-weight: 500;">Montant Total: <span class="donnees" style="font-weight: 700;">{{number_format($montant_ht + $montant_remise,0,' ',' ')}} {{$devise}}</span></span>
                    <span style="display: flex;flex-direction: row; justify-content: space-between; align-items: center;font-weight: 500;">Remise: <span class="donnees" style="font-weight: 700;">{{number_format($montant_remise,0,' ',' ')}} {{$devise}}</span></span>
                    @if($montant_tva > 0) 
                        <span style="display: flex;flex-direction: row; justify-content: space-between; align-items: center;font-weight: 500;">TVA: <span class="donnees" style="font-weight: 700;">{{number_format($montant_tva,0,' ',' ')}} {{$devise}}</span></span>
                    @endif
                    @if($montant_precompte > 0) 
                        <span style="display: flex;flex-direction: row; justify-content: space-between; align-items: center;font-weight: 500;">Précompte: <span class="donnees" style="font-weight: 700;">{{number_format($montant_precompte,0,' ',' ')}} {{$devise}}</span></span>
                    @endif
                    <span style="display: flex;flex-direction: row; justify-content: space-between; align-items: center;font-weight: 500;margin-top: 10px;">Total: <span class="donnees" style="font-weight: 700;color: #2c299a;border-top: 1px solid;">{{number_format($montant_ttc,0,' ',' ')}} {{$devise}}</span></span>
                    <div style="border-top: 1px solid;display: flex;justify-content: space-between;">                        
                        <div style="font-weight: 600">Montant dû :</div>
                        <div><strong style="color: #ff5757; ">{{number_format($reste_a_percevoir,0,' ',' ')}} {{$devise}}</strong></div>
                    </div>
                </div>
            </div>
            <div style="text-align:center; font-size:16px; font-weight:600; color: #000000;">{{$montant_lettres}} {{$devise}}</div>            
            <div style="font-size: 15px;color: #274085;">  
                @foreach($entite as $entites)                  
                    @if($entites->condition_vente)                    
                        NB: {{$entites->condition_vente}}
                    @endif 
                @endforeach
            </div>

            <div style="display:flex;justify-content: space-between;padding: 12px 40px;font-weight: 600;">
                <div>
                    Signature Client
                </div>
                <div>
                    Signature Caissier-e
                </div>
            </div>            
            {{-- footer --}}
            <div style="position: fixed; left: 0;right: 0; bottom: 0; text-align: center;"> 
                <div class="bientot fw-semibold" style="font-size: 11px;">By www.wamsco-cloud.net</div>
            </div>
            {{-- <div style="position: fixed; left: 0;right: 0; bottom: 0; text-align: center;"> 
                @foreach($entite as $entites)
                    <img style="width: 100%;" src="storage/{{$entites->logo_fact_pied}}" alt=""/>  
                @endforeach 
            </div>        --}}
        </div>
    </body>
</html>
