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
        <div class="bloc_facture_mini">
            <div style="display: flex;flex-direction: column;">                    
                @foreach($entite as $entites) 
                    <div class="panel-heading_neugau titre">{{substr($entites->raison_sociale,0,32) > substr($entites->raison_sociale,0,31) ? substr($entites->raison_sociale,0,32).'...': $entites->raison_sociale}}</div>
                    {{-- <div class="entete_fact_mini">Ville: <span class="data_fact_mini">{{$entites->ville}} / {{$entites->pays}}</span></div> --}}    
                    @if(!empty($entites->ville))          
                        <div class="entete_fact_mini">Adresse: <span class="data_fact_mini">{{$entites->adresse}} / {{$entites->ville}}</span></div>  
                    @endif          
                    <div class="entete_fact_mini">Tél: <span class="data_fact_mini">{{$entites->telephone}}</span></div>
                    {{-- <div class="entete_fact_mini">Email: <span class="data_fact_mini">{{$entites->email}}</span></div> --}}
                    @if(!empty($entites->registre_com))  
                        <div class="entete_fact_mini">RCCM: <span class="data_fact_mini">{{$entites->registre_com}}</span></div>
                    @endif
                    @if(!empty($entites->niu))  
                        <div class="entete_fact_mini">NIU: <span class="data_fact_mini">{{$entites->niu}}</span></div>
                    @endif  
                @endforeach                          
                <div class="entete_fact_mini" style="border-top: 1px dotted;margin-top: 3px;padding-top: 2px;">Facture N°: <span style="font-weight: 600;" >#{{$code_fact}}</span></div> 
                @if($code_commande)
                    <div class="entete_fact_mini" style="font-weight: bold;color: #27852f;font-size: 12px;">Réf. commande: <span style="font-weight: 600;" >#{{$code_commande}}</span></div> 
                @endif
                <div class="entete_fact_mini">Date facturation: <span class="data_fact_mini">{{date('d/m/Y, H:i:s', strtotime($date_vente))}}</span></div>  
                <div class="entete_fact_mini">Fournisseur: 
                    @if($sexe == "Masculin")
                        <span class="donnees">M. {{substr($client,0,50)}}</span>
                    @else
                        <span class="">Mme {{substr($client,0,50)}}</span>
                    @endif                   
                </div>
                <div class="entete_fact_mini">Caissier-e: <span class="data_fact_mini">@if(empty($serveur)) {{substr($nom_user,0,15)}} @else {{substr($serveur,0,15)}} @endif</span></div> 
                {{-- <div class="entete_fact_mini">Imprimer par: <span class="data_fact_mini">{{substr(auth()->user()->name,0,15)}}</span></div>  --}}
                @if(!empty($adresse_livraison)) 
                     <div class="entete_fact_mini">Adresse livraison: <span class="data_fact_mini">{{$adresse_livraison}}</span></div>                                      
                @endif
                @if($activer_fidelite == 1)
                    <div class="entete_fact_mini">Fidélité: <span class="data_fact_mini" style="color: #ff0000;">{{$nombre_point}}</span> Points</div>
                @endif           
            </div> 
            @if($note)
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div style="font-size: 14px; padding-top: 3px;">  
                        <span style="font-weight: 600;color: #ff0000;">Important: </span>{{$note}}
                    </div>
                </div>                     
            @endif  
            <hr class="ligne"> 
            <div class="">  
                <table class="table wamsco">
                    <thead>
                        <tr>                                           
                            <th class="mini">Désignation</th> 
                            <th class="droite mini">Qté</th>                                                                              
                            <th class="droite mini">Prix</th>                            
                            <th class="droite mini">Total</th>                                                           
                        </tr> 
                    </thead>
                    @foreach($listeProd as $listeProds)
                        <tbody>
                            <tr class="">                                                    
                                <td class="mini">{{$listeProds->produit}} - {{$listeProds->reference}} @if ($listeProds->remise) (-{{$listeProds->remise}}%) @endif
                                    @if($listeProds->infos)
                                        ({{$listeProds->infos}})
                                    @endif
                                </td> 
                                <td class="droite mini">{{$listeProds->quantite}}</td>                                                       
                                <td class="droite mini">{{$listeProds->prix_vente}}</td>                                                            
                                <td class="droite mini">
                                    @if($listeProds->offrir == "Oui")
                                        <span style="font-weight: 700;">Offert</span>
                                    @else
                                        {{number_format($listeProds->prix_vente * $listeProds->quantite,0,' ',' ')}}
                                    @endif
                                </td>                                                 
                            </tr>
                        </tbody>
                    @endforeach
                    <tfoot>                  
                        <tr class="fact_detail2 mini"> 
                            <th>Sous-Total</th>                       
                            <th class="droite mini">{{$NbreProd}}</th>                                                                                    
                            <th class="droite mini">{{$TotalPvente}}</th>
                            <th class="droite mini">{{number_format($montant_ht + $montant_remise,0,' ',' ')}}</th>                   
                        </tr>
                        @if($montant_remise > 0)                                                                 
                            <tr class="noir"> 
                                <th class="mini">Remise en {{$devise}}</th>                                      
                                <th colspan="3" class="droite mini">{{number_format($montant_remise,0,' ',' ')}}</th> 
                            </tr> 
                        @endif             
                        @if($montant_tva > 0)  
                            <tr class="noir">                                                                 
                                <th class="mini">Tva en {{$devise}}</th>                                       
                                <th colspan="3" class="droite mini">{{number_format($montant_tva,0,' ',' ')}}</th>              
                            </tr> 
                        @endif
                        @if($montant_precompte > 0)
                            <tr class="noir">                                                                 
                                <th class="mini">Précompte en {{$devise}}</th>                                   
                                <th colspan="3" class="droite mini">{{number_format($montant_precompte,0,' ',' ')}}</th>              
                            </tr> 
                        @endif
                        <tr style="font-size: 13px; color: #274085;border-bottom: 1px dotted;">                                                                 
                            <th>TOTAL en {{$devise}}</th>                                
                            <th colspan="3" class="montantotal droite">{{number_format($montant_ttc,0,' ',' ')}}</th>              
                        </tr>
                        @if($reglement != "Non payé")
                            <tr>
                                <td colspan="4">
                                    <div style="text-align:center; font-size:13px; font-weight:600; color: #000000;">{{$montant_lettres}} {{$devise}}</div>
                                    <span style="border-bottom: 1px solid;width: 100%;font-size: 13px;font-weight: 700;text-transform: uppercase;margin-bottom: 6px;">Informations de paiement</span>
                                   <div class="titre_fact_mini py-1">Statut de la Facture: 
                                        @if($statut_facture == "Payée")
                                            <span class="donnees text-success">{{$statut_facture}}</span>
                                        @elseif($statut_facture == "Commencée")
                                            <span class="donnees text-danger">{{$statut_facture}}</span>
                                        @else
                                            <span class="donnees">{{$statut_facture}}</span>
                                        @endif                    
                                    </div>
                                    @foreach($reglement as $reglements)
                                        <div style="display: flex; justify-content: space-between; font-size: 13px;padding: 5px 0;">
                                            <div style="font-style: italic;">
                                                Payé le {{date('d/m/Y', strtotime($reglements->created_at))}} ({{$reglements->mode_reglement}})
                                            </div>
                                            <div style="font-style: italic;">
                                                {{number_format($reglements->montant_regler,0,' ',' ')}} {{$devise}}
                                            </div>
                                        </div>
                                    @endforeach                                    
                                </td>
                            </tr>
                            <tr style="border-top: 1px dotted;"> 
                                <th style="font-size: 13px;color: #274085;">Montant dû :</th> 
                                <th colspan="2" style="color: #ff0042; font-size:13px;font-weight: 700;text-align: right;">{{number_format($reste_a_percevoir,0,' ',' ')}}</th> 
                                <th class="droite mini">{{$devise}}</th>
                            </tr>
                        @endif
                    </tfoot>
                </table>
                <div class="merci">                               
                    <div class="col-md-12 col-sm-12 col-xs-12 bientot">
                        {{-- @foreach($entite as $entites) 
                            <div style="font-size: 14px;color: #274085;">  
                                {{$entites->condition_vente}}
                            </div>
                        @endforeach --}}
                    </div>           
                    {{-- <div class="col-md-12 col-sm-12 col-xs-12 bientot">Merci de votre visite</div>  --}}
                    <div class="col-md-12 col-sm-12 col-xs-12 bientot">A Bientôt</div>
                    <div class="col-md-12 col-sm-12 col-xs-12 bientot fw-semibold" style="font-size: 9px;">By www.wamsco-cloud.net</div>           
                </div>
            </div>
        </div>
    </body>
</html>
