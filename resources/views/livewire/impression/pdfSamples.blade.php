<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
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
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: dejavusans;
            font-size: 10px;
            color: #222;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .titre {
            font-size: 22px;
            font-weight: bold;
        }

        .logo {
            color: #2448a6;
            font-size: 20px;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .mt10 {
            margin-top: 10px;
        }

        .mt20 {
            margin-top: 20px;
        }

        .bloc {
            border: 1px solid #999;
            border-radius: 3px;
        }

        .bloc-title {
            background: #f5f5f5;
            padding: 5px;
            font-size: 13px;
            font-weight: bold;
            border-bottom: 1px solid #999;
        }

        .bloc-content {
            padding: 6px;
            line-height: 20px;
        }

        .red {
            color: #d60000;
            font-weight: bold;
        }

        .blue {
            color: #2346b8;
            font-weight: bold;
        }

        thead {
            background: #efefef;
        }

        th {
            padding: 7px;
            border-bottom: 1px solid #999;
            font-size: 11px;
        }

        td {
            padding: 6px;
            border-bottom: 1px solid #DDD;
        }

        .ligne {
            background: #fafafa;
        }
    </style>
</head>

<body>

    <!-- ===========================
    ENTETE
    ============================ -->
    <table>
        <tr>            
            <td width="18%">
                @foreach($entite as $entites)
                    <img style="max-width: 100px; padding:0px; max-height: 72px;border-radius: 5px;" src="storage/{{$entites->logo}}" alt="logo"/>
                @endforeach                
            </td>
            <td width="42%">
                <div class="titrek">
                    <span style="font-size: 23px; font-weight: 700;">Facture</span>
                </div>
                <br>
                Imprimer le : {{ now()->format('d/m/Y H:i:s') }}
                <br>
                Par : <b>{{ auth()->user()->name }}</b>
            </td>
            <td width="40%" class="text-right">
                <span style="font-size:18px;" class="blue">
                    Facture N° : {{-- {{ $facture->numero }} --}}
                </span>
                <br><br>
                Date facturation : {{-- <b>{{ $facture->created_at->format('d/m/Y H:i') }}</b> --}}
                <br>
                Vendeur : <b>{{ auth()->user()->name }}</b>
            </td>
        </tr>
    </table>

    <br>

    <!-- ===========================
    SOCIETE / CLIENT
    ============================ -->
    <table>
        <tr>
            <td width="49%">
                <div class="bloc">
                    <div class="bloc-title">
                        {{-- {{ $societe->enseigne }} --}}
                    </div>
                    <div class="bloc-content">
                        {{-- <b>{{ $societe->ville }}</b> --}}
                        <br>
                        Adresse : {{-- {{ $societe->adresse }} --}}
                        <br>
                        Tél : {{-- {{ $societe->telephone }} --}}
                        <br>
                        Email : {{-- {{ $societe->email }} --}}
                        <br>
                        RCCM : {{-- {{ $societe->rccm }} --}}
                        <br>
                        NIU : {{-- {{ $societe->niu }} --}}
                    </div>
                </div>
            </td>

            <td width="2%"></td>

            <td width="49%">
                <div class="bloc">
                    <div class="bloc-title">Client</div>
                    <div class="bloc-content">
                        <b>{{-- {{ $facture->client->nom }} --}}</b>
                        <br>
                        Tél : {{-- {{ $facture->client->telephone }} --}}
                        <br>
                        Ville/Pays : {{-- {{ $facture->client->ville }} --}}
                        <br>
                        Fidélité : 
                        <span class="red">
                            {{-- {{ $facture->client->point }} --}} Points
                        </span>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <br>
    <span class="red">Important :</span> je suis une facture No : {{-- {{ $facture->numero }} --}}
    <br><br>

    <!-- ===========================
    TABLEAU
    ============================ -->
    <table>
        <thead>
            <tr>
                <th width="38%">Désignation</th>
                <th width="8%" align="center">Qté</th>
                <th width="10%" align="right">PU</th>
                <th width="8%" align="right">Remise</th>
                <th width="8%" align="right">TVA</th>
                <th width="10%" align="right">Précompte</th>
                <th width="9%" align="right">Montant HT</th>
                <th width="9%" align="right">Sous-total</th>
            </tr>
        </thead>
        <tbody>
            {{-- @foreach($facture->lignes as $ligne) --}}
            <tr>
                <td>{{-- {{ $ligne->designation }} --}}</td>
                <td align="center">{{-- {{ $ligne->quantite }} --}}</td>
                <td align="right">{{-- {{ number_format($ligne->prix,0,',',' ') }} --}}</td>
                <td align="right">{{-- {{ $ligne->remise }} % --}}</td>
                <td align="right">{{-- {{ $ligne->tva }} % --}}</td>
                <td align="right">{{-- {{ $ligne->precompte }} % --}}</td>
                <td align="right">{{-- {{ number_format($ligne->montant_ht,0,',',' ') }} --}}</td>
                <td align="right">
                    <b>{{-- {{ number_format($ligne->total,0,',',' ') }} --}}</b>
                </td>
            </tr>
            {{-- @endforeach --}}
        </tbody>
    </table>
    
    <br><br>

    <table width="100%">
        <tr>
            <!-- ===========================
            PAIEMENT
            =========================== -->
            <td width="55%" valign="top">
                <div style="font-size:17px;font-weight:bold;border-bottom:2px solid #222;padding-bottom:4px;">
                    INFORMATIONS DE PAIEMENT
                </div>
                <br>
                <table>
                    <tr>
                        <td width="45%">Statut de la facture</td>
                        <td width="55%">
                            <b style="color:green;font-size:13px;">{{-- {{ $facture->etat }} --}}</b>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <br>
                            <i>
                                Payé le {{-- {{ $facture->date_paiement }} --}}
                                {{-- @if($facture->mode_paiement) --}}
                                    {{-- ({{ $facture->mode_paiement }}) --}}
                                {{-- @endif --}}
                            </i>
                        </td>
                    </tr>
                </table>
            </td>

            <!-- ===========================
            TOTAUX
            =========================== -->
            <td width="45%" valign="top">
                <table>
                    <tr>
                        <td>Nombre d'articles :</td>
                        <td align="right">
                            <b>{{-- {{ $facture->lignes->count() }} --}}</b>
                        </td>
                    </tr>
                    <tr>
                        <td>Montant HT :</td>
                        <td align="right">
                            <b>{{-- {{ number_format($facture->montant_ht,0,',',' ') }} --}} FCFA</b>
                        </td>
                    </tr>
                    <tr>
                        <td>Remise :</td>
                        <td align="right">
                            <b>{{-- {{ number_format($facture->remise,0,',',' ') }} --}} FCFA</b>
                        </td>
                    </tr>
                    <tr>
                        <td>TVA :</td>
                        <td align="right">
                            <b>{{-- {{ number_format($facture->tva,0,',',' ') }} --}} FCFA</b>
                        </td>
                    </tr>
                    <tr>
                        <td>Précompte :</td>
                        <td align="right">
                            <b>{{-- {{ number_format($facture->precompte,0,',',' ') }} --}} FCFA</b>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><hr></td>
                    </tr>
                    <tr>
                        <td style="font-size:16px;font-weight:bold;">TOTAL :</td>
                        <td align="right">
                            <span style="font-size:18px;color:#233eb6;font-weight:bold;">
                                {{-- {{ number_format($facture->total,0,',',' ') }} --}} FCFA
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td>Montant dû :</td>
                        <td align="right">
                            <span style="font-size:16px;color:#d60000;font-weight:bold;">
                                {{-- {{ number_format($facture->reste,0,',',' ') }} --}} FCFA
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <br><br>

    <!-- ===========================
    MONTANT EN LETTRES
    ============================ -->
    <div style="text-align:center;font-size:16px;font-weight:bold;">
        {{-- {{ $montantLettre }} --}} FCFA
    </div>

    <br>
    <div style="color:#2448a6;font-size:11px;">
        NB : Les marchandises vendues ne sont ni reprises ni échangées.
    </div>

    <br><br><br>

    <!-- ===========================
    SIGNATURES
    ============================ -->
    <table>
        <tr>
            <td width="40%" align="center"><b>Signature Client</b></td>
            <td width="20%"></td>
            <td width="40%" align="center"><b>Signature Vendeur</b></td>
        </tr>
    </table>

</body>
</html>