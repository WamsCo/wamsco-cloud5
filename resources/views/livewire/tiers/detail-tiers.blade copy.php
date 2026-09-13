<div>
    <div id="content" class="app-content">  
        <div class="profile mb-2">
            <div class="profile-header">        
                <div class="profile-header-cover"></div>      
                <div class="profile-header-content">
                    <div class="profile-header-info">
                        <ol class="breadcrumb float-xl-start">
                            <li class="breadcrumb-item"><a href="bienvenue">Accueil /</a></li>
                            <li class="breadcrumb-item activek"><a href="{{$lien}}" wire:navigate>&nbsp;{{$module}} /</a></li>
                            <li class="breadcrumb-item d-none d-md-block"><span style="color:yellow">&nbsp;{{$title_fils}}</span></li>
                        </ol>
                        {{-- <h1 class="page-header mb-1">{{$title_fils}} » <span style="color:yellow">{{$categoriecount}}</span></h1>   --}}
                    </div> 
                </div> 
            </div>
        </div>
        <div class="row gx-4">
            {{-- Debut chargement --}}        
            <div wire:loading class="chargement">
                <label for=""></label>
                <img src="storage/default/circle_loading.gif" width="64" height="64" style="background: #28282800; margin-left: -3px;">
            </div>
            {{-- Fin chargement --}}
            <div class="col-sm-12">
                @include('flash::message')
                <div class="card mb-0 bg-2k">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs margin_ajuste border-0" role="tablist">
                            <li class="nav-item">
                                @foreach($tier as $tiers)
                                    <a class="nav-link active" data-bs-toggle="tab" href="#home">{{$tiers->type_tiers}}</a>
                                @endforeach
                            </li>                            
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#evenement">Événements</a>
                            </li> 
                            <li class="nav-item">
                                <a class="nav-link pointer" wire:click.prevent="soldeTier()">Solde</a>
                            </li>                           
                        </ul>
                        <!-- Tab panes -->                        
                        <div class="d-flex align-items-center justify-content-between gap-1">
                            <div class="d-flex align-items-center justify-content-between gap-1"> 
                                {{-- <a href="facturationcltkk-pdf?code=" class="btn btn-sm btn-default" title="Cliquez pour imprimer" data-toggle="tooltip" style="color: #161a1d;"><i class="fa fa-print"></i>Ticket</a> --}}
                                {{-- <a href="facturationclt-pdf?code=Fact/191224/a0adc871" class="btn btn-sm btn-default" title="Cliquez pour imprimer" data-toggle="tooltip" target="blank"><i class="fa fa-print"></i>A4</a> --}}
                            </div>                            
                            <div class="d-flex align-items-center justify-content-between gap-1">                            
                                <a href="listing-tiers?active=3&champ=3-2" wire:navigate class="btn btn-sm btn-bleu d-none d-md-block ms-auto"><i class="fa fa-chevron-left"></i> Retour liste</a>
                                <button class="btn btn-sm btn-default new_color" @if($this->ids) wire:click.prevent="precedant({{$this->ids}})" @else wire:click.prevent="precedant({{$this->id}})" @endif title="Précédant" data-toggle="tooltip"><i class="fa fa-chevron-left"></i></button>
                                <a href="#" class="btn btn-sm btn-default new_color" @if($this->ids) wire:click.prevent="suivant({{$this->ids}})" @else wire:click.prevent="suivant({{$this->id}})" @endif title="Suivant" data-toggle="tooltip"><i class="fa fa-chevron-right"></i></a>
                            </div>
                        </div>
                    </div>
                    @foreach($tier as $tiers)
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <div class="">
                                    <div class="photoref">
                                        {{-- @if($tiers->image != null)
                                            <td><img class="img_detail_prod" src="storage/{{$tiers->image}}" data-bs-toggle="modal" data-bs-target="#imagetierModal" wire:click.prevent="edit({{$tiers->id}})"/></td>
                                        @else --}}
                                            <td><img class="img_detail_prod" src="storage/default/user_man.png" data-bs-toggle="modal" data-bs-target="#imagetierModal"/></td>
                                        {{-- @endif --}}
                                    </div>
                                </div>
                                <div>
                                    <h5 class="text-bleu">{{$tiers->nom}}</h5>
                                    <span class="text-success fw-semibold">{{$tiers->type_tiers}}  @if($tiers->code_tier)» <i class="fas fa-user-circle text-bleu"></i> <span class="text-bleu fw-semibold" title="Code {{$tiers->type_tiers}}">{{$tiers->code_tier}}</span>@endif</span>
                                </div>
                            </div>
                            <div>
                                <div class="statusref">
                                    @if($tiers->etat == 1)
                                        <span class="badge bg-success py-1" title="Activer">Activer</span>
                                    @else
                                        <span class="badge bg-danger py-1" title="Désactiver">Désactiver </span>
                                    @endif
                                </div>
                            </div>
                        </div>                    
                        <div class="card-body no_bordure border-top rounded-3 taille_ecran_session">
                            <div class="row mx-0 pt-0">
                                <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12 px-0 mb-1 border-end border-bottom rounded-bottom">                        
                                    <div class="row">
                                        <div class="col-md-12">                                                                               
                                            <div class="tab-content">
                                                <div id="home" class="container-fluid tab-pane active pas_bordure"> 
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="table-responsive">   
                                                                <table class="table text-nowrap m-0">                                        
                                                                    <tbody>
                                                                        <tr>                                                                     
                                                                            <td class="titlefield text-muted fw-semibold">Nom</td>                                                         
                                                                            <td class="fw-semibold">{{$tiers->nom}}</td>         
                                                                        </tr> 
                                                                        <tr>                                                                     
                                                                            <td class="titlefield text-muted fw-semibold">Raison sociale <i class="fas fa-info-circle" title="Nom officiel d'une entreprise" data-bs-toggle="tooltip" data-bs-placement="top" style=" vertical-align: middle; cursor: help"></i></td>                                                         
                                                                            <td class="fw-semibold">{{$tiers->raison_sociale}}</td>         
                                                                        </tr>
                                                                        <tr>                                                                     
                                                                            <td class="titlefield text-muted fw-semibold">Type du tiers</td>                                                         
                                                                            <td class="fw-semibold">{{$tiers->type_tiers}}</td>         
                                                                        </tr>
                                                                        <tr>                                                                     
                                                                            <td class="titlefield text-muted fw-semibold">Téléphone</td>                                                         
                                                                            <td class="fw-semibold">{{$tiers->telephone}}</td>         
                                                                        </tr>
                                                                        <tr>                                                                     
                                                                            <td class="titlefield text-muted fw-semibold">Email</td>                                                         
                                                                            <td class="fw-semibold">{{$tiers->email}}</td>         
                                                                        </tr>
                                                                        <tr>                                                                     
                                                                            <td class="titlefield text-muted fw-semibold">Sexe</td>                                                         
                                                                            <td class="fw-semibold"><span class="text-bleu" style="font-size: 11px">{{$tiers->sexe}}</span></td>         
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="table-responsive">   
                                                                <table class="table text-nowrap m-0">                                        
                                                                    <tbody>                                                                  
                                                                        <tr>                                                                     
                                                                            <td class="titlefield text-muted fw-semibold">Solde <i class="fas fa-file-invoice-dollar"></i></td> 
                                                                            <td class="fw-semibold">{{number_format($tiers->solde,0,' ',' ')}} <span class="text-bleu" style="font-size: 10px">{{$devise}}</span></td> 
                                                                        </tr>                                                                
                                                                        <tr>                                                                     
                                                                            <td class="titlefield text-muted fw-semibold">Ville | Pays</td>                                                         
                                                                            <td class="fw-semibold">{{$tiers->ville}} @if($tiers->pays)|@endif {{$tiers->pays}}</td>         
                                                                        </tr>
                                                                        <tr>                                                                     
                                                                            <td class="titlefield text-muted fw-semibold">Adresse</td>                                                         
                                                                            <td class="fw-semibold">{{$tiers->adresse}}</td>         
                                                                        </tr>
                                                                        <tr>                                                                     
                                                                            <td class="titlefield text-muted fw-semibold">Code postal</td>                                                         
                                                                            <td class="fw-semibold"><span class="text-bleu" style="font-size: 11px">{{$tiers->code_postal}}</span></td>         
                                                                        </tr>
                                                                        <tr>                                                                     
                                                                            <td class="titlefield text-muted fw-semibold">Site web</td>                                                         
                                                                            <td class="fw-semibold">{{$tiers->site_web}}</td>         
                                                                        </tr>
                                                                        <tr>                                                                     
                                                                            <td class="titlefield text-muted fw-semibold">Affecter commercial</td>                                                         
                                                                            <td class="fw-semibold">{{$tiers->commercial_charge}}</td>         
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>                                                                                      
                                                    <div class="d-flex align-items-center justify-content-between gap-3 py-3">
                                                        <div class="">
                                                        {{-- <a href="#" wire:click.prevent="edit({{$tiers->id}})" class="btn text-danger border fw-semibold ms-auto" data-bs-toggle="modal" data-bs-target="#imagetierModal" title="Cliquez pour modifier image" data-toggle="tooltip"><i class="fa fa-camera"></i> Ajouter / Modifier</a> --}}
                                                        </div>
                                                        <div class="d-flex align-items-center justify-content-between gap-2 py-2 px-3">
                                                            <a href="#" wire:click.prevent="edit({{$tiers->id}})" data-bs-toggle="modal" data-bs-target="#soldeTiersModal" title="Cliquez pour recharger le compte client" data-toggle="tooltip" class="btn btn-sm btn-white text-danger fw-semibold ms-auto"><i class="fas fa-file-invoice-dollar"></i> Recharge</a>
                                                            <a href="#" wire:click.prevent="edit({{$tiers->id}})" data-bs-toggle="modal" data-bs-target="#updateTierModal" title="Cliquez pour voir les détails" data-toggle="tooltip" class="btn btn-sm btn-secondary fw-semibold ms-auto">Modifier <i class="fa fa-chevron-right"></i></a>
                                                            <a href="nouveau_tiers?active=3&champ=3-1" wire:navigate class="btn btn-sm btn-danger ms-auto"><i class="fa fa-plus-circle"></i> Nouveau tier</a>
                                                            @if($confirmer === $tiers->id)                                                               
                                                                <a wire:click.prevent="supprimer({{$tiers->id}})" class="btn btn-sm btn-outline-danger bg-danger text-white blink" style="font-size:11px;"  title="Cliquez pour confirmer la suppression" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-trash"> Confirmer ?</i></a>
                                                            @else
                                                                <a wire:click.prevent="confirmerDelete({{$tiers->id}})" class="btn btn-sm btn-outline-muted"  title="Cliquez pour supprimer" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-trash"></i></a>
                                                            @endif                                                       
                                                        </div>
                                                    </div> 
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-xs-12">                                                   
                                                            <div class="d-flex align-items-center justify-content-between gap-3 pt-4 px-3">
                                                                <div class="">
                                                                    <h5 class="text-bleu"><i class="fas fa-file-invoice-dollar" style=" color: #393b83;" title="Mouvement de stock"></i> Les dernières factures <span class="text-vert">{{$tiers->nom}}</span> ({{$factClientEnteteCount}})</h5>
                                                                </div>
                                                                <div>                                                                                                                        
                                                                </div>
                                                            </div>
                                                            <div class="table-responsive border-top">
                                                                <table class="table table-striped table-hover text-nowrap m-0"> 
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="fond_entete_table pointer">Référence</th>   
                                                                            <th class="fond_entete_table pointer">Client</th> 
                                                                            <th class="fond_entete_table pointer">Facturation</th> 
                                                                            <th class="fond_entete_table pointer">Règlement</th> 
                                                                            <th class="fond_entete_table pointer text-end">Montant TTC</th> 
                                                                            <th class="fond_entete_table pointer text-end">Reçu</th> 
                                                                            <th class="fond_entete_table pointer text-end">Créance</th> 
                                                                            <th class="fond_entete_table pointer text-end">Marge</th>                                                            
                                                                            <th class="fond_entete_table pointer">Etat</th>
                                                                            <th class="fond_entete_table pointer">Date</th>
                                                                        </tr>
                                                                    </thead>                                       
                                                                    <tbody>
                                                                        @foreach($factClient_entete as $factClient_entetes) 
                                                                        <tr> 
                                                                            <td class="fw-semibold pointer" title="Cliquez pour voir les détails de cette facture" data-bs-toggle="tooltip" data-bs-placement="top" style=" vertical-align: middle; cursor: help"><a href="nouveau_fact_clt?id={{$factClient_entetes->id}}&ref={{$factClient_entetes->code_facture}}&active=7&champ=1-1&choix=1" wire:navigate class="text-bleu"><i class="fas fa-file-invoice-dollar text-bleu" aria-hidden="true"></i> {{$factClient_entetes->code_facture}}</a></td> 
                                                                            <td class="fw-semibold" title="Voir toutes ses factures" data-bs-toggle="tooltip" data-bs-placement="top" style=" vertical-align: middle; cursor: help"><a href="listing_fact_clt?nom_client={{$factClient_entetes->nom_client}}&date_debut={{date('Y-m-d', strtotime('-1 year'))}}&date_fin={{date('Y-m-d')}}&active=7&champ=1-1&choix=1" wire:navigate class="text-bleu">@if($factClient_entetes->nom_client) <i class="fa fa-user-circle"></i> @endif {{substr($factClient_entetes->nom_client,0,52) > substr($factClient_entetes->nom_client,0,51) ? substr($factClient_entetes->nom_client,0,52).'...': $factClient_entetes->nom_client}}</a></td>                                                                      
                                                                            <td class="">{{date('d-m-Y', strtotime($factClient_entetes->date_facturation))}}</td>
                                                                            <td class="">{{$factClient_entetes->mode_reglement}}</td>
                                                                            <td class="fw-semibold text-end">{{number_format($factClient_entetes->montant_ttc,0,',',' ')}}</td>
                                                                            <td class="fw-semibold text-end">{{number_format($factClient_entetes->montant_recu,0,',',' ')}}</td>
                                                                            <td class="fw-semibold text-end">{{number_format($factClient_entetes->reste_a_percevoir,0,',',' ')}}</td>
                                                                            <td class="fw-semibold text-end">{{number_format($factClient_entetes->marge,0,',',' ')}}</td>
                                                                            <td class="text-start">
                                                                                @if($factClient_entetes->etat == "Brouillon")
                                                                                    <span class="badge bg-secondary py-1" title="Facture {{$factClient_entetes->etat}}"><i class="fa fa-box"></i> {{$factClient_entetes->etat}}</span>
                                                                                @elseif($factClient_entetes->etat == "Impayée")
                                                                                    <span class="badge bg-danger py-1" title="Facture {{$factClient_entetes->etat}}"><i class="fa fa-times-circle"></i> {{$factClient_entetes->etat}}</span>
                                                                                @elseif($factClient_entetes->etat == "Payée")
                                                                                    <span class="badge bg-success py-1" title="Facture {{$factClient_entetes->etat}}"><i class="fa fa-check-circle"></i> {{$factClient_entetes->etat}}</span>
                                                                                @elseif($factClient_entetes->etat == "Commencée")
                                                                                    <span class="badge bg-info py-1" title="Facture {{$factClient_entetes->etat}}"><i class="fa fa-recycle"></i> {{$factClient_entetes->etat}}</span>
                                                                                @endif                                                 
                                                                            </td> 
                                                                            <td class="">{{date('d-m-Y H:i:s', strtotime($factClient_entetes->created_at))}}</td>
                                                                        </tr> 
                                                                        @endforeach 
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between gap-3 pt-4 px-3">
                                                                <div class="">
                                                                    <h5 class="text-bleu"><i class="fas fa-file-invoice-dollar" style=" color: #393b83;" title="Mouvement de stock"></i> Les dernières commandes <span class="text-vert">{{$tiers->nom}}</span> ({{$cmdClientCount}})</h5>                                                                </div>
                                                                <div>                                                                                                                        
                                                                </div>
                                                            </div>                                                        
                                                            <div class="table-responsive border-top">
                                                                <table class="table table-striped table-hover text-nowrap m-0"> 
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="fond_entete_table pointer" wire:click="setOrderField('code_commande')">Référence <i class="fa fa-arrow-down-short-wide"></i></th>   
                                                                            <th class="fond_entete_table pointer" wire:click="setOrderField('nom_client')">Client <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                                            <th class="fond_entete_table pointer" wire:click="setOrderField('date_commande')">Commande <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                                            <th class="fond_entete_table pointer" wire:click="setOrderField('date_livraison')">Livraison <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                                            <th class="fond_entete_table pointer" wire:click="setOrderField('mode_reglement')">Règlement <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                                            <th class="fond_entete_table pointer text-end" wire:click="setOrderField('montant_ttc')">Montant TTC <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                                            <th class="fond_entete_table pointer text-end" wire:click="setOrderField('montant_recu')">Reçu <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                                            <th class="fond_entete_table pointer text-end" wire:click="setOrderField('reste_a_percevoir')">Créance <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                                            <th class="fond_entete_table pointer text-end" wire:click="setOrderField('marge')">Marge <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                                            <th class="fond_entete_table pointer" wire:click="setOrderField('etat')">Etat <i class="fa fa-arrow-down-short-wide"></i></th>        
                                                                            <th class="fond_entete_table pointer" wire:click="setOrderField('nbre_facture')">Facturé <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                                            <th class="fond_entete_table pointer" wire:click="setOrderField('etat_expedi')">Expédition <i class="fa fa-arrow-down-short-wide"></i></th>        
                                                                            <th class="fond_entete_table pointer" wire:click="setOrderField('updated_at')">Date<i class="fa fa-arrow-down-short-wide"></i></th>
                                                                        </tr>
                                                                    </thead>                                       
                                                                    <tbody>
                                                                        @foreach($cmd_client as $cmd_clients) 
                                                                            <tr>
                                                                                <td class="fw-semibold pointer" title="Cliquez pour voir les détails de cette commande" data-bs-toggle="tooltip" data-bs-placement="top" style=" vertical-align: middle; cursor: help"><a href="nouveau_cmd_clt?id={{$cmd_clients->id}}&ref={{$cmd_clients->code_commande}}&active=6&champ=1-1&choix=2" wire:navigate><i class="fas fa-file-invoice text-danger" aria-hidden="true"></i> {{$cmd_clients->code_commande}}</a></td> 
                                                                                <td class="fw-semibold" title="Voir toutes ses commandes" data-bs-toggle="tooltip" data-bs-placement="top" style=" vertical-align: middle; cursor: help"><a href="listing_cmd_clt?nom_client={{$cmd_clients->nom_client}}&date_debut={{date('Y-m-d', strtotime('-1 year'))}}&date_fin={{date('Y-m-d')}}&active=6&champ=1-1&choix=2" wire:navigate class="text-bleu">@if($cmd_clients->nom_client) <i class="fa fa-user-circle"></i> @endif {{substr($cmd_clients->nom_client,0,52) > substr($cmd_clients->nom_client,0,51) ? substr($cmd_clients->nom_client,0,52).'...': $cmd_clients->nom_client}}</a></td>                                                                      
                                                                                <td class="">{{date('d-m-Y', strtotime($cmd_clients->date_commande))}}</td>
                                                                                <td class="">{{date('d-m-Y', strtotime($cmd_clients->date_livraison))}}</td>
                                                                                <td class="">{{$cmd_clients->mode_reglement}}</td>
                                                                                <td class="fw-semibold text-end">{{number_format($cmd_clients->montant_ttc,0,',',' ')}}</td>
                                                                                <td class="fw-semibold text-end">{{number_format($cmd_clients->montant_recu,0,',',' ')}}</td>
                                                                                <td class="fw-semibold text-end">{{number_format($cmd_clients->reste_a_percevoir,0,',',' ')}}</td>
                                                                                <td class="fw-semibold text-end">{{number_format($cmd_clients->marge,0,',',' ')}}</td>
                                                                                <td class="text-start">
                                                                                    @if($cmd_clients->etat == "Brouillon")
                                                                                        <span class="badge bg-secondary py-1" title="Commande {{$cmd_clients->etat}}"><i class="fa fa-box"></i> {{$cmd_clients->etat}}</span>
                                                                                    @elseif($cmd_clients->etat == "Impayée")
                                                                                        <span class="badge bg-danger py-1" title="Commande {{$cmd_clients->etat}}"><i class="fa fa-times-circle"></i> {{$cmd_clients->etat}}</span>
                                                                                    @elseif($cmd_clients->etat == "Payée")
                                                                                        <span class="badge bg-success py-1" title="Commande {{$cmd_clients->etat}}"><i class="fa fa-check-circle"></i> {{$cmd_clients->etat}}</span>
                                                                                    @elseif($cmd_clients->etat == "Validée")
                                                                                        <span class="badge bg-success py-1" title="Commande {{$cmd_clients->etat}}"><i class="fa fa-check"></i> {{$cmd_clients->etat}}</span>
                                                                                    @endif                                                 
                                                                                </td>  
                                                                                <td class="fw-semibold">
                                                                                    @if($cmd_clients->nbre_facture > 0)
                                                                                        <span class="badge bg-info" title="Facture non créée"><i class="fas fa-file-invoice-dollar"></i> Oui</span>
                                                                                    @else
                                                                                        <span class="badge bg-warning" title="Facture non créée"><i class="fas fa-file-invoice-dollar"></i> Non</span>
                                                                                    @endif
                                                                                </td>                                             
                                                                                <td class="text-start">
                                                                                    @if($cmd_clients->etat_expedi == "Brouillon")
                                                                                    <span class="badge bg-secondary py-1" title="Expédition {{$cmd_clients->etat_expedi}}"><i class="fas fa-dolly"></i> {{$cmd_clients->etat_expedi}}</span>
                                                                                    @elseif($cmd_clients->etat_expedi == "Clôturée")
                                                                                        <span class="badge bg-danger py-1" title="Expédition {{$cmd_clients->etat_expedi}}"><i class="fas fa-dolly"></i> {{$cmd_clients->etat_expedi}}</span>
                                                                                    @elseif($cmd_clients->etat_expedi == "Partiel")
                                                                                        <span class="badge bg-primary py-1" title="Expédition {{$cmd_clients->etat_expedi}}"><i class="fas fa-dolly"></i> {{$cmd_clients->etat_expedi}}</span>
                                                                                    @elseif($cmd_clients->etat_expedi == "")
                                                                                        <span class="badge bg-light text-dark py-1" title="Expédition non créée"><i class="fas fa-dolly"></i> Non créée</span>
                                                                                    @endif   
                                                                                </td>                                            
                                                                                <td class="">{{date('d-m-Y H:i:s', strtotime($cmd_clients->created_at))}}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>                                                        
                                                    </div>
                                                </div>                                                                                       
                                                <div id="evenement" class="container-fluid tab-pane fade pas_bordure">  
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-xs-12">   
                                                            <table class="table text-nowrap m-0">                                        
                                                                <tbody>
                                                                        <tr>                                                                     
                                                                            <td class="titlefield text-muted fw-semibold">Créé par</td>                                                         
                                                                            <td class="fw-semibold text-bleu"><i class="fa fa-user-circle"></i> {{$tiers->nom_user}}</td>         
                                                                        </tr> 
                                                                        <tr>                                                                     
                                                                            <td class="titlefield text-muted fw-semibold">Date création</td>                                                         
                                                                            <td class="fw-semibold">{{date('d-m-Y H:i:s', strtotime($tiers->created_at))}}</td>         
                                                                        </tr>                                                                        
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="col-sm-6">   
                                                            <table class="table text-nowrap m-0">                                        
                                                                <tbody>
                                                                    @if($tiers->nom_user_modif)
                                                                        <tr>                                                                     
                                                                            <td class="titlefield text-muted fw-semibold">Modifié par</td>                                                         
                                                                            <td class="fw-semibold text-bleu"><i class="fa fa-user-circle"></i> {{$tiers->nom_user_modif}}</td>         
                                                                        </tr>
                                                                    @endif
                                                                    <tr>                                                                     
                                                                        <td class="titlefield text-muted fw-semibold">Date de dernière modification</td>                                                         
                                                                        <td class="fw-semibold">{{date('d-m-Y H:i:s', strtotime($tiers->updated_at))}}</td>         
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 px-0 pt-3 mb-1 rounded-bottom">
                                    <div class="hauteur_ecran">
                                        <div class="d-flex align-items-center justify-content-between px-2">
                                            <div class="">
                                                <h5 class="text-bleu"><i class="fas fa-calendar-alt" style=" color: #393b83;" title="Derniers événements liés"></i> @if($logCount > 1) Les {{$logCount}} derniers événements liés @else L'événement lié @endif</h5>
                                            </div>
                                            <div>                                                                                                                        
                                            </div>
                                        </div>
                                        <div class="table-responsive border-top rounded-0">
                                            @foreach($log as $logs)
                                                <div class="d-flex flex-shrink-0 gap-2 px-2 py-2">
                                                        <div class="d-flex flex-shrink-0 align-items-center flex-column bg-inherit align-items-start">
                                                            <div class="position-relative bg-inherit rounded-3">
                                                                @if($logs->profil != null)  
                                                                    <a href="detail_user?id={{$logs->user_id}}&active=12&champ=1-1" wire:navigate>
                                                                        <img class="image_log object-fit-cover rounded-3 cursor-pointer" src="storage/{{$logs->profil}}">
                                                                    </a>
                                                                @else       
                                                                    <a href="detail_user?id={{$logs->user_id}}&active=12&champ=1-1" wire:navigate>
                                                                        <img class="image_log object-fit-cover rounded-3 cursor-pointer" src="storage/default/user_man.png"> 
                                                                    </a>                    
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="d-flex flex-column flex-wrap align-items-baseline lh-1 gap-2">
                                                            <div>
                                                                <strong class="me-1">
                                                                    <a href="detail_user?id={{$logs->user_id}}&active=12&champ=1-1" wire:navigate>{{$logs->user_email}} &nbsp; <span class="fw-500">{{date('d-m-Y H:i:s', strtotime($logs->created_at))}}</span></a>
                                                                </strong>
                                                            </div>
                                                            <div class="">
                                                              <span><i class="fas fa-calendar-alt text-danger"></i> {{$logs->id}}</span> &nbsp; <span><i class="fas fa-university text-dangerg"></i> {{$logs->user_societe}}</span>
                                                            </div>
                                                            <div class="text-bleu fw-semibold">
                                                                {{$logs->subject}}
                                                            </div>
                                                        </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @include('livewire.tiers.update_tiers') 
    @include('livewire.tiers.modal_solde_tiers')  
    {{-- @include('livewire.gestion-stock.tiers.imageupdate')  --}}
</div>