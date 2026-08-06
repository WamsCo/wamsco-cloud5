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
            {{-- Logo et facture --}}
             <div style="display:flex; justify-content:space-between; margin-bottom:10px; margin-top:4px;">
                <div style="width:20%; display:flex; justify-content:center; flex-direction:column;padding: 0 4px;">
                    @foreach($entite as $entites)
                        <img style="max-width: 100px; padding:0px; max-height: 72px;border-radius: 5px;" src="storage/{{$entites->logo}}" alt="logo"/>
                    @endforeach
                </div>
                <div style="width:80%; display:flex; justify-content:space-between; gap:10px;padding: 0 4px;">
                    <div style="display:flex; flex-direction:column;">
                        <span style="font-size: 23px; font-weight: 700;">Recu</span>
                        <span class="fact" style="font-size: 13px;">Imprimer le: <span class="donnees">{{date('d/m/Y, H:i:s', strtotime($dateJour))}}</span></span>
                        <span class="fact" style="font-size: 13px;">Par: <span class="donnees">{{Str::limit(auth()->user()->name, 15)}}</span></span>
                    </div>
                    <div style="display:flex; flex-direction:column;">
                        <span class="fact_num">Recu N°: RCE-{{$id_solde}}</span>
                        <span class="fact" style="font-size: 13px;padding-top: 6px;">Date recu: <span class="donnees">{{date('d/m/Y, H:i:s', strtotime($date_recu))}}</span></span>
                        <span class="fact" style="font-size: 13px;">Responsable: <span class="donnees">{{Str::limit($nom_user, 15)}}</span></span>
                    </div>
                </div>
             </div>

             <div style="display:flex; justify-content:space-between; gap:10px;">
                <div style="width:50%; display:flex; flex-direction:column;border:1px dotted;border-radius: 5px;">
                    @foreach($entite as $entites)      
                        <div style="border-bottom:1px dotted; font-size: 16px; font-weight: 700; padding:5px 5px;">
                            <span style="font-weight: 800; color: #274085;">{{Str::limit($entites->raison_sociale, 32)}}</span> 
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
                            <span class="donnees">M/Mme. {{substr($client,0,50)}}</span>
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
                    </div>
                </div>                
             </div>
             
            {{-- Debut des Totaux --}}
            <div style="display: flex; justify-content: space-between;margin-top:10px; margin-bottom: 14px; gap:5px;">
                <div style="width:80%; display:flex; flex-direction:column; padding:0 4px;">
                    <span style="border-bottom: 2px solid;width: 100%; display: block;font-size: 17px;font-weight: 700;text-transform: uppercase;margin-bottom: 6px;">Informations de versement</span>
                    @if($credit > 0)
                        <div style="">Nous soussignés <span class="fw-bold">{{$societe}}</span> certifions avoir reçu du client  <span class="fw-bold">{{$client}}</span>, </br>  Compte N°: <span class="fw-bold">{{$code_tier}}</span>
                            la somme de : <span class="fw-bold">{{number_format($credit,0,'',' ')}} {{$devise}}</span>
                        </div> 
                    @else  
                        <div style="">Nous soussignés <span class="fw-bold">{{$societe}}</span> certifions avoir remis au client  <span class="fw-bold">{{$client}}</span> </br>  Compte N°: <span class="fw-bold">{{$code_tier}}</span>
                            la somme de : <span class="fw-bold">{{number_format($debit,0,'',' ')}} {{$devise}}</span>
                        </div> 
                    @endif
                    <div> Mode de paiement : <span class="text-bleu fw-bold">{{$compte}}</span></div>
                    <div> Motif : <span class="text-bleu">{{$designation}}.</span></div>                 
                   
                </div>                
            </div>
            <div style="text-align:center; font-size:16px;">Montant en lettres : <span style="font-weight:700; color: #002d6f;">{{$montant_lettres}} {{$devise}}</span></div> 
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
        </div>
    </body>
</html>
