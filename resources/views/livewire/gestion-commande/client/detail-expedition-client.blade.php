<div x-data="{selection: @entangle('selection').defer}">
    <div id="content" class="app-content"> 
        <div class="profile mb-2">
            <div class="profile-header">        
                <div class="profile-header-cover"></div>      
                <div class="profile-header-content">
                    <div class="profile-header-info">
                        <ol class="breadcrumb float-xl-start">
                            <li class="breadcrumb-item"><a href="bienvenue">Accueil /</a></li>
                            <li class="breadcrumb-item activek"><a href="{{$lien}}" wire:navigate>&nbsp;{{$module}} /</a></li>
                            <li class="breadcrumb-item"><span style="color:yellow">&nbsp;{{$title_fils}}</span></li>
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
                    <div class="container-fluid py-2 px-2">
                        <div class="row">
                            <!-- SIDEBAR -->
                            <div class="col-lg-3">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body border border-light-subtle rounded-3 bg-white position-relative custom-scroll pe-2" style="height: calc(100vh - 131px); overflow-y: auto;">
                                        <div class="text-centerk">
                                            <div class="d-flex align-items-centerk">
                                                @foreach($tier as $tiers)
                                                <img src="{{ asset('storage/default/user_man.png')}}" width="70" height="70" class="rounded-circle border">
                                                <div class="ms-2">
                                                    <h4 class="mb-0 fw-bold" title="{{$tiers->nom}}">{{Str::limit($tiers->nom.'', 42) ?? 'Inconu(e)'}}</h4>
                                                    <div class="text-success fw-bold">{{ $tiers->type_tiers }}</div>
                                                    <span class="text-success fw-semibold">@if($tiers->code_tier)<i class="fas fa-user-circle text-bleu"></i> <span class="text-bleu fw-semibold" title="Code {{$tiers->type_tiers}}">{{$tiers->code_tier}}</span>@endif</span>
                                                    {{-- <br><small class="text-muted">REF-001</small><br> --}}
                                                    <div class="text-muted">
                                                        @if($tiers->etat == 1)
                                                            <span class="badge bg-success py-0" title="Activer">Activer</span>
                                                        @else
                                                            <span class="badge bg-danger py-0" title="Désactiver">Désactiver </span>
                                                        @endif
                                                    </div>
                                                </div> 
                                                @endforeach                                                   
                                            </div>
                                            <div class="d-flex align-items-center justify-content-end gap-1">
                                                <div class="statusref">
                                                    @if($etat == "Brouillon")
                                                        <span class="badge bg-secondary py-1" title="Expédition {{$etat}}"><i class="fas fa-dolly"></i> {{$etat}}</span>
                                                    @elseif($etat == "Clôturée")
                                                        <span class="badge bg-danger py-1" title="Expédition {{$etat}}"><i class="fas fa-dolly"></i> {{$etat}}</span>
                                                    @elseif($etat == "Partiel")
                                                        <span class="badge bg-primary py-1" title="Expédition {{$etat}}"><i class="fa fa-copy"></i> {{$etat}}</span>  
                                                    @elseif($etat == "Commencée")
                                                        <span class="badge bg-info py-1" title="Expédition {{$etat}}"><i class="fas fa-dolly"></i> {{$etat}}</span>  
                                                    @endif
                                                </div>
                                                <div class="statusref">
                                                    @if($etat_facture == "Brouillon")
                                                    {{-- <span class="badge bg-secondary py-1" title="Facture {{$etat_facture}}"><i class="fa fa-box"></i> {{$etat_facture}}</span>  --}}
                                                    @elseif($etat_facture == "Payée")
                                                        <span class="badge bg-success py-1" title="Facture {{$etat_facture}}"><i class="fa fa-check-circle"></i> {{$etat_facture}}</span>
                                                    @endif
                                                </div>                            
                                            </div>
                                        </div>
                                        <hr class="my-2">
                                        <div class="d-flex gap-1 mb-2">
                                            <a href="listing_expedition_clt?active=6&champ=1-1&choix=3" wire:navigate class="btn btn-sm btn-outline-danger flex-fill" title="Cliquez pour fermer" data-toggle="tooltip"><i class="fa fa-close"></i> Fermer</a>
                                            @if($confirmer === 2)                                                               
                                                <a wire:click.prevent="supprimer()" class="btn btn-sm btn-outline-danger bg-danger text-white blink" style="font-size:11px;"  title="Cliquez pour confirmer la suppression totale" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-trash"> Confirmer ?</i></a>
                                            @else
                                                <a wire:click.prevent="confirmerDelete(2)" class="btn btn-sm btn-outline-muted"  title="Cliquez pour tout supprimer" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-trash"></i></a>
                                            @endif                                
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between gap-1 border-bottom pb-2">
                                            @if($this->etat != "Clôturée")
                                                <a href="#" wire:click.prevent="clotureExpedition" target="_blank" class="btn btn-sm btn-outline-primary flex-fill fw-semibold ms-auto" title="Cliquez pour clôturer l'expédition"><i class="fa fa-dolly"></i> Clôturer l'expédition</a>
                                            @else
                                                <a href="#" wire:click.prevent="creerFacture()" class="btn btn-sm btn-outline-green flex-fill fw-semibold ms-auto" title="Cliquez pour créer une facture" data-toggle="tooltip"><i class="fas fa-file-invoice-dollar"></i> Créer Facture</a>
                                            @endif 
                                        </div> 
                                        {{-- <button href="{{asset('produit?active=4&champ=1-1&choix=2')}}" wire:navigate class="btn btn-sm btn-outline-success w-100 mb-4"><i class="fa fa-close"></i> Fermer</button> --}}
                                        <h5 class="text-bleu pt-2"><i class="fas fa-user-circle" style=" color: #393b83;" title="Derniers événements liés"></i> À propos du client</h5>
                                        <div class="table-responsive">
                                            <table class="table table-borderless text-nowrap m-0">
                                                @foreach($tier as $tiers)
                                                    <tr>
                                                        <td class="text-muted fw-semibold">Raison sociale <i class="fas fa-info-circle" title="Nom officiel d'une entreprise" data-bs-toggle="tooltip" data-bs-placement="top" style=" vertical-align: middle; cursor: help"></i></td>
                                                        <td>{{ $tiers->raison_sociale }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted fw-semibold">Type</td>
                                                        <td>{{ $tiers->type_tiers }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted fw-semibold">Téléphone</td>
                                                        <td>{{ $tiers->telephone }}</td>
                                                    </tr>
                                                    @if($tiers->email)
                                                        <tr>
                                                            <td class="text-muted fw-semibold">Email</td>
                                                            <td>{{ $tiers->email }}</td>
                                                        </tr>
                                                    @endif
                                                    <tr>
                                                        <td class="text-muted fw-semibold">Sexe</td>
                                                        <td class="fw-semibold @if($tiers->sexe == "Masculin") text-success @else text-info @endif">{{ $tiers->sexe }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted fw-semibold">Solde <i class="fas fa-file-invoice-dollar"></i></td>
                                                        <td class="text-primary fw-bold">{{ number_format($tiers->solde,0,',',' ') }}FCFA</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted fw-semibold">Ville | Pays</td>
                                                        <td>{{ $tiers->ville }}|{{ $tiers->pays }}</td>
                                                    </tr>
                                                    @if($tiers->adresse)
                                                        <tr>
                                                            <td class="text-muted fw-semibold">Adresse</td>
                                                            <td>{{ $tiers->adresse }}</td>
                                                        </tr>
                                                    @endif
                                                    @if($tiers->code_postal)
                                                        <tr>
                                                            <td class="text-muted fw-semibold">Code postal</td>
                                                            <td>{{ $tiers->code_postal }}</td>
                                                        </tr>
                                                    @endif
                                                    @if($tiers->site_web)
                                                        <tr>
                                                            <td class="text-muted fw-semibold">Site web</td>
                                                            <td><a href="/{{ $tiers->site_web }}" target="_blank">{{ $tiers->site_web }}</a></td>
                                                        </tr>
                                                    @endif
                                                    @if($tiers->commercial)
                                                        <tr>
                                                            <td class="text-muted fw-semibold">Commercial</td>
                                                            <td>{{ $tiers->commercial }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach                                             
                                            </table>  
                                        </div>                                          
                                        <h5 class="text-bleu"><i class="fas fa-file-invoice" style=" color: #393b83;" title="Derniers événements liés"></i> Objets liés</h5>
                                        <div class="table-responsive border-top rounded-0">                                        
                                            <table class="table table-striped table-hover text-nowrap m-0">
                                                <thead>
                                                <tr>
                                                    <th class="fond_entete_table">Type</th>
                                                    <th class="fond_entete_table">Réf.</th>
                                                    <th class="fond_entete_table text-end">Montant TTC</th>
                                                    <th class="fond_entete_table">État</th> 
                                                    <th class="fond_entete_table">Date</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($cmdCltEntete as $cmdCltEntetes)
                                                    <tr>
                                                        <td class="fw-semibold"><a href="nouveau_cmd_clt?id={{$cmdCltEntetes->id}}&ref={{$cmdCltEntetes->code_commande}}&active=6&champ=1-1&choix=2" wire:navigate>Cmd client</a></td>                                                        
                                                        <td class="fw-semibold"><a href="nouveau_cmd_clt?id={{$cmdCltEntetes->id}}&ref={{$cmdCltEntetes->code_commande}}&active=6&champ=1-1&choix=2" wire:navigate class="text-bleu"><i class="fas fa-file-invoice text-info" aria-hidden="true"></i> {{$cmdCltEntetes->code_commande}}</a></td>                                                        
                                                        <td class="fw-semibold text-end">{{number_format($cmdCltEntetes->montant_ttc,0,',',' ')}} {{$this->devise}}</td>
                                                        <td class="fw-semibold"> 
                                                            @if($cmdCltEntetes->etat == "Brouillon")
                                                                <span class="badge bg-secondary" title="Commande {{$cmdCltEntetes->etat}}"><i class="fa fa-box"></i> {{$cmdCltEntetes->etat}}</span>
                                                            @elseif($cmdCltEntetes->etat == "Validée")
                                                                <span class="badge bg-success" title="Commande {{$cmdCltEntetes->etat}}"><i class="fa fa-check"></i> {{$cmdCltEntetes->etat}}</span>
                                                            @endif                                                               
                                                        </td>
                                                        <td class="fw-semibold"><i class="fas fa-calendar-alt text-danger"></i> {{$cmdCltEntetes->created_at ? date('d-m-Y H:i:s', strtotime($cmdCltEntetes->created_at)): "" }}</td>                                                        
                                                    </tr>
                                                    @endforeach
                                                    @foreach($factCltEntete as $factCltEntetes)
                                                        <tr>
                                                            <td class="fw-semibold"><a href="nouveau_fact_clt?id={{$factCltEntetes->id}}&ref={{$factCltEntetes->code_facture}}&active=7&champ=1-1&choix=1" wire:navigate>Facture client</a></td>
                                                            <td class="fw-semibold"><a href="nouveau_fact_clt?id={{$factCltEntetes->id}}&ref={{$factCltEntetes->code_facture}}&active=7&champ=1-1&choix=1" wire:navigate class="text-bleu"><i class="fa fa-money-bill text-vert" aria-hidden="true"></i> {{$factCltEntetes->code_facture}}</a></td>
                                                            <td class="fw-semibold text-end">{{number_format($factCltEntetes->montant_ttc,0,',',' ')}} {{$this->devise}}</td>
                                                            <td class="fw-semibold"> 
                                                                @if($factCltEntetes->etat == "Brouillon")
                                                                    <span class="badge bg-secondary" title="Facture {{$factCltEntetes->etat}}"><i class="fa fa-box"></i> {{$factCltEntetes->etat}}</span>
                                                                @elseif($factCltEntetes->etat == "Impayée")
                                                                    <span class="badge bg-warning" title="Facture {{$factCltEntetes->etat}}"><i class="fa fa-times-circle"></i> {{$factCltEntetes->etat}}</span>
                                                                @elseif($factCltEntetes->etat == "Payée")
                                                                    <span class="badge bg-success" title="Facture {{$factCltEntetes->etat}}"><i class="fa fa-check-circle"></i> {{$factCltEntetes->etat}}</span>
                                                                @elseif($factCltEntetes->etat == "Commencée")
                                                                    <span class="badge bg-info" title="Facture {{$factCltEntetes->etat}}"><i class="fa fa-recycle"></i> {{$factCltEntetes->etat}}</span>
                                                                @endif                                                                                                                 
                                                            </td>
                                                            <td class="fw-semibold"><i class="fas fa-calendar-alt text-danger"></i> {{$factCltEntetes->created_at ? date('d-m-Y H:i:s', strtotime($factCltEntetes->created_at)): "" }}</td>                                                        
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>  
                                        </div>                                            
                                    </div>
                                </div>
                            </div>
                            <!-- CONTENU -->
                            <div class="col-lg-9">
                                <!-- KPI -->
                                <div class="card shadow-sm border border-light-subtle rounded-3 bg-white">
                                    <div class="card-body p-2">
                                        <div class="d-flex align-items-center justify-content-between flex-nowrap overflow-auto py-2">

                                            <div class="d-flex align-items-center px-3 border-end flex-fill">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 44px; height: 44px; background-color: #e8f5e9; color: #198754;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                                                    </svg>
                                                </div>
                                                <div class="text-nowrap">
                                                    <span class="d-block fw-bold text-secondary" style="font-size: 11px;">Dernière activité ({{$this->updated_at->diffForHumans()}})</span>
                                                    <span class="d-block fw-semibold text-dark" style="font-size: 13px;">{{ $this->updated_at->format('d/m/Y : H:i:s') }}</span>
                                                    {{-- <span class="d-block text-muted" style="font-size: 11px; margin-top: -2px;">GMT+1</span> --}}
                                                </div>
                                            </div>

                                            <div class="d-flex align-items-center px-3 border-end flex-fill">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 44px; height: 44px; background-color: #e0f7fa; color: #0dcaf0;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5h16.5a1.5 1.5 0 011.5 1.5v11.25a1.5 1.5 0 01-1.5 1.5H3.75a1.5 1.5 0 01-1.5-1.5V6a1.5 1.5 0 011.5-1.5zm10.5 7.5a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                                    </svg>
                                                </div>
                                                <div class="text-nowrap">
                                                    <span class="d-block fw-bold text-secondary" style="font-size: 11px;">Montant HT</span>
                                                    <span class="d-block fw-bold text-dark mt-1" style="font-size: 14px;">{{number_format($montantHT,0,',',' ')}} @if($montantRemise) <span style="font-size: 10px;">(Remise : {{number_format($montantRemise,0,',',' ')}})</span> @endif <span style="font-size: 10px;">{{$this->devise}}</span></span>
                                                </div>
                                            </div>

                                            <div class="d-flex align-items-center px-3 border-end flex-fill">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 44px; height: 44px; background-color: #fff8e1; color: #ffc107;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
                                                    </svg>
                                                </div>
                                                <div class="text-nowrap">
                                                    <span class="d-block fw-bold text-secondary" style="font-size: 11px;">Montant TVA</span>
                                                    <span class="d-block fw-bold text-dark mt-1" style="font-size: 14px;">{{number_format($montantTva,0,',',' ')}} <span style="font-size: 10px;">{{$this->devise}}</span></span>
                                                </div>
                                            </div>

                                            @if($montantPrecompte > 0)
                                                <div class="d-flex align-items-center px-3 border-end flex-fill">
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 44px; height: 44px; background-color: #fff3e0; color: #fd7e14;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                        </svg>
                                                    </div>
                                                    <div class="text-nowrap">
                                                        <span class="fw-bold text-secondary" style="font-size: 11px;">Montant Précompte</span>
                                                        <span class="d-block fw-bold text-dark mt-1" style="font-size: 14px;">{{number_format($montantPrecompte,0,',',' ')}} <span style="font-size: 10px;">{{$this->devise}}</span></span>
                                                    </div>
                                                </div> 
                                            @endif                                           

                                            <div class="d-flex align-items-center px-3 flex-fill">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 44px; height: 44px; background-color: #ffebee; color: #dc3545;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v3" />
                                                    </svg>
                                                </div>
                                                <div class="text-nowrap">
                                                    <span class="d-block fw-bold text-secondary" style="font-size: 11px;">Montant TTC <i class="fas fa-info-circle" title="Les montants sont affichés avec un arrondi automatique (+ ou -)" data-bs-toggle="tooltip" data-bs-placement="top" style=" vertical-align: middle; cursor: help"></i></span>
                                                    <span class="d-block fw-bold text-dark mt-1" style="font-size: 14px;">{{number_format($montantTTC,0,',',' ')}} <span style="font-size: 10px;">{{$this->devise}}</span></span>
                                                </div>
                                            </div>

                                            <div class="d-flex align-items-center px-3 border-end flex-fill">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 44px; height: 44px; background-color: #f3e5f5; color: #6f42c1;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                                    </svg>
                                                </div>
                                                <div class="text-nowrap">
                                                    <span class="d-block fw-bold text-secondary" style="font-size: 11px;">Créer par</span>
                                                    <span class="d-block fw-bold text-dark mt-1" style="font-size: 13px;">{{Str::limit($this->auteur, 22)}}</span>
                                                </div>
                                            </div>
                                            
                                            <div class="d-flex align-items-center px-3 border-end flex-fill">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 44px; height: 44px; background-color: #eaf2ff; color: #0d6efd;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                                    </svg>
                                                </div>
                                                <div class="text-nowrap">
                                                    <span class="d-block fw-bold text-secondary" style="font-size: 11px;">Date de création</span>
                                                    <span class="d-block fw-semibold text-dark" style="font-size: 13px;">{{ $this->created_at->format('d/m/Y : H:i:s') }}</span>
                                                    {{-- <span class="d-block text-muted" style="font-size: 11px; margin-top: -2px;">GMT+1</span> --}}
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>                                    
                                <!-- ONGLETS -->
                                <div class="card-header d-flex align-items-center justify-content-between border-0 px-0">
                                    <!-- Nav tabs -->
                                    <ul class="nav nav-tabs margin_ajuste border-0" role="tablist">
                                        <li class="nav-item">                                                
                                            <a class="nav-link active" data-bs-toggle="tab" href="#home">Vue d'ensemble</a>                                                
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#activites">Activités récentes</a>
                                        </li> 
                                    </ul>
                                    <!-- Tab panes -->                        
                                    <div class="d-flex align-items-center justify-content-between gap-1">
                                        <div class="d-flex align-items-center justify-content-between gap-1"> 
                                            {{-- <a href="impcmdclt-pdf?id={{$this->ids}}&code={{$this->reference}}&format=ticket" class="btn btn-sm btn-default" title="Cliquez pour imprimer" data-toggle="tooltip" target="_blank" style="color: #161a1d;"><i class="fa fa-print"></i>Ticket</a> --}}
                                            {{-- <a href="impcmdclt-pdf?id={{$this->ids}}&code={{$this->reference}}&format=A4" target="_blank" class="btn btn-sm btn-default" title="Cliquez pour imprimer" data-toggle="tooltip"><i class="fa fa-print"></i>A4</a> --}}
                                        </div>                            
                                        <div class="d-flex align-items-center justify-content-between gap-1">
                                            <a href="listing_expedition_clt?active=6&champ=1-1&choix=3" wire:navigate class="btn btn-sm btn-bleu d-none d-md-block ms-auto"><i class="fa fa-chevron-left"></i> Retour liste</a>
                                            <button class="btn btn-sm btn-default new_color" wire:click.prevent="precedant()" title="Précédant" data-toggle="tooltip"><i class="fa fa-chevron-left"></i></button>
                                            <a href="#" class="btn btn-sm btn-default new_color" wire:click.prevent="suivant()" title="Suivant" data-toggle="tooltip"><i class="fa fa-chevron-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-content">
                                    <div id="home" class="container-fluid tab-pane active pas_bordure">
                                        <div class="card shadow-sm">  
                                            <div class="card shadow-sm border border-light-subtle rounded-3 bg-white p-2">    
                                                <h6 class="fw-bold text-bleu mb-1"><i class="fas fa-file-invoice" style=" color: #393b83;" title="Derniers événements liés"></i> {{$title_fils}}</h6>
                                                <div class="position-relative custom-scroll pe-3" style="height: calc(100vh - 357px); overflow-y: auto;">  
                                                    <div class="hauteur_ecran">
                                                        <div class="row pt-1 px-2">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <div class="row mb-1">
                                                                    <div class="col-lg-10 col-md-10 col-sm-10">
                                                                        <span class="form-control bordure fs-3 text-bleu w-100 px-0"><i class="fa fa-user-circle"></i> {{$this->client}}</span>
                                                                    </div>
                                                                    <div class="d-flex justify-content-start">
                                                                        @error('client') <span class="text-danger">{{$message}}</span> @enderror 
                                                                    </div>
                                                                </div>                                            
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">                                            
                                                                <div class="row mb-1">
                                                                    <label for="reference" class="col-md-3 col-sm-3 fw-bold col-form-label">Référence</label>
                                                                    <div class="col-md-9 col-sm-9">
                                                                        <span class="form-control sans_bordure fw-bold fs-6 text-vert">{{$this->codeExpedition}}</span>
                                                                    </div>
                                                                    <div class="d-flex justify-content-start">
                                                                        @error('reference') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-1">
                                                                    <label for="date_facturation" class="col-lg-4 col-md-4 col-sm-3 fw-bold col-form-label">Date</label>
                                                                    <div class="col-lg-8 col-md-8 col-sm-9">
                                                                        <span class="form-control sans_bordure fw-bold fs-6">{{date('d-m-Y', strtotime($this->created_at))}}</span>
                                                                    </div>
                                                                    <div class="d-flex justify-content-start">
                                                                        @error('date_facturation') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-1">
                                                                    <label for="date_echeance" class="col-lg-5 col-md-5 col-sm-4 fw-bold col-form-label">Date livraison <span wire:click.prevent="afficherDateLivraison(1)" title="Cliquez pour modifier la date prévue livraison" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fas fa-pencil text-muted pointer"></i></span> </label>
                                                                    <div class="col-lg-7 col-md-7 col-sm-8">
                                                                        <div class="d-flex flex-column">
                                                                            <div>
                                                                                <span class="form-control sans_bordure fw-bold fs-6 text-bleu">{{date('d-m-Y', strtotime($this->date_echeance))}} (<span class="text-muted">Modif: {{date('d-m-Y', strtotime($this->updated_at))}}</span>)</span>
                                                                            </div>
                                                                            <div  class="d-flex gap-1">
                                                                                @if($affiche === 1)
                                                                                    <input type="date" wire:model="date_echeance"  class="form-control bordure w-auto @error('date_echeance') is-invalid @enderror" id="date_echeance">
                                                                                    <a href="#" wire:click.prevent="changeDateLivraison()" class="btn btn-sm btn-outline-muted border fw-semibold" data-bs-toggle="modal" data-bs-target="#saissieReglementModal" title="Cliquez pour modifier solde initial" data-toggle="tooltip"><i class="fas fa-check"></i></a>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-start">
                                                                        @error('date_echeance') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                    </div>
                                                                </div>                                            
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">                                             
                                                                <div class="row mb-1">
                                                                    <label for="methode_expedition" class="col-lg-6 col-md-6 col-sm-4 fw-bold col-form-label">Méthode d'expédition <span wire:click.prevent="afficherMethodeExpedition(1)" title="Cliquez pour modifier la méthode d'expédition" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fas fa-pencil text-muted pointer"></i></span> </label>
                                                                    <div class="col-lg-6 col-md-6 col-sm-8">
                                                                        <div class="d-flex flex-column">
                                                                            <div>
                                                                                <span class="form-control sans_bordure fw-bold fs-6 text-bleu">{{$this->methode_expedition}}</span>
                                                                            </div>
                                                                            <div class="d-flex gap-1">
                                                                                @if($ouvre === 1)
                                                                                    <select id="methode_expedition" wire:model="methode_expedition" class="form-control form-select bordure w-100 @error('methode_expedition') is-invalid @enderror" id="methode_expedition">
                                                                                        <option value=""></option>	
                                                                                        <option value="Transport maritime">Transport maritime</option>	
                                                                                        <option value="Service coursier">Service coursier</option>
                                                                                        <option value="La messagerie">La messagerie</option>
                                                                                        <option value="Le groupage">Le groupage</option>
                                                                                        <option value="L'affrètement">L'affrètement</option>
                                                                                        <option value="Transport aérien">Transport aérien</option>
                                                                                    </select> 
                                                                                    <a href="#" wire:click.prevent="changeMethodeExpedition()" class="btn btn-sm btn-outline-muted border fw-semibold ms-auto" data-bs-toggle="modal" data-bs-target="#saissieReglementModal" title="Cliquez pour modifier solde initial" data-toggle="tooltip"><i class="fas fa-check"></i></a>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-start">
                                                                        @error('methode_expedition') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-1">
                                                                    <label for="numero_suivi" class="col-md-5 col-sm-3 fw-bold col-form-label">Numéro de suivi <span wire:click.prevent="afficherNumeroSuivi(1)" title="Cliquez pour modifier le numéro de suivi" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fas fa-pencil text-muted pointer"></i></span> </label>
                                                                    <div class="col-md-7 col-sm-9">
                                                                        <div class="d-flex flex-column">
                                                                            <div>
                                                                                <span class="form-control sans_bordure fw-bold fs-6 text-bleu">{{$this->numero_suivi}}</span>
                                                                            </div>
                                                                            <div  class="d-flex gap-1">
                                                                                @if($open === 1)
                                                                                    <input type="text" wire:model="numero_suivi" class="form-control bordure w-55 @error('numero_suivi') is-invalid @enderror" id="numero_suivi">
                                                                                    <a href="#" wire:click.prevent="changeNumeroSuivi()" class="btn btn-sm btn-outline-muted border fw-semibold ms-auto" data-bs-toggle="modal" data-bs-target="#saissieReglementModal" title="Cliquez pour modifier solde initial" data-toggle="tooltip"><i class="fas fa-check"></i></a>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-start">
                                                                        @error('numero_suivi') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                    </div>
                                                                </div>  
                                                            </div>                                        
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <div class="row mb-4">
                                                                    <label for="note" class="col-sm-2 fw-bold col-form-label">Note <span wire:click.prevent="afficherNote(1)" title="Cliquez pour modifier la note" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fas fa-pencil text-muted pointer"></i></span> </label>
                                                                    <div class="col-sm-10">
                                                                        <div class="d-flex flex-column w-100">
                                                                            <div class="">
                                                                                <span class="form-control sans_bordure fw-bold fs-6 text-bleu">{{$this->note}}</span>
                                                                            </div>
                                                                            <div class="d-flex gap-1 w-100">
                                                                                @if($sortir === 1)
                                                                                    <textarea rows="2" wire:model="note" class="form-control bordure flex-grow-1 @error('note') is-invalid @enderror" id="note" placeholder="Note public..."></textarea>
                                                                                    <a href="#" wire:click.prevent="changeNote()" class="btn btn-sm btn-outline-muted border fw-semibold ms-auto mt-5" data-bs-toggle="modal" data-bs-target="#saissieReglementModal" title="Cliquez pour modifier la note" data-toggle="tooltip"><i class="fas fa-check"></i></a>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-start">
                                                                        @error('note') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 px-0 mb-2">
                                                                <div class="table-responsive mt-0" style="border-top-style: dashed; border-top-width: 1px;border-top-color: #0e04043d;">
                                                                    <ul class="nav nav-tabs margin_ajuste border-0 pb-3" role="tablist">
                                                                        <li class="nav-item">
                                                                            @if(!empty($this->fact_entete_id))
                                                                                <a class="nav-link" data-bs-toggle="tab" href="#ligne_fact">Ligne de facture (<span class="text-vert">{{$expeClientLigneCount}}</span>)</a>
                                                                            @else
                                                                                <a class="nav-link" data-bs-toggle="tab" href="#ligne_fact">Ligne de commande (<span class="text-vert">{{$expeClientLigneCount}}</span>)</a>
                                                                            @endif
                                                                        </li>
                                                                        <li class="nav-item">
                                                                            <a class="nav-link active" data-bs-toggle="tab" href="#expedi">Expédition (<span class="text-vert">{{$expeClientLigneCount}}</span>)</a>
                                                                        </li>
                                                                    </ul>
                                                                    <div class="tab-content table-responsive border-top rounded-0">
                                                                        <div id="ligne_fact" class="tab-pane">                               
                                                                            <table class="table table-striped text-nowrap m-0"> 
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th class="fond_entete_table pointer" wire:click="setOrderField('produit')">Produit (<span class="text-vert">{{$expeClientLigneCount}}</span>) <i class="fa fa-arrow-down-short-wide"></i></th>
                                                                                        <th class="fond_entete_table pointer text-end" wire:click="setOrderField('type_produit')">Type <i class="fa fa-arrow-down-short-wide"></i></th>
                                                                                        <th class="fond_entete_table pointer text-end" wire:click="setOrderField('prix_vente')">Prix <i class="fa fa-arrow-down-short-wide"></i></th>
                                                                                        <th class="fond_entete_table pointer text-center" wire:click="setOrderField('quantite')">Quantité <i class="fa fa-arrow-down-short-wide"></i></th>
                                                                                        <th class="fond_entete_table pointer text-center" wire:click="setOrderField('remise')">Remise % <i class="fa fa-arrow-down-short-wide"></i></th>
                                                                                        <th class="fond_entete_table pointer text-end" wire:click="setOrderField('tva')">Tva % <i class="fa fa-arrow-down-short-wide"></i></th>
                                                                                        <th class="fond_entete_table pointer text-center" wire:click="setOrderField('precompte')">Précompte % <i class="fa fa-arrow-down-short-wide"></i></th>
                                                                                        <th class="fond_entete_table pointer text-center" wire:click="setOrderField('offrir')">Offrir <i class="fa fa-arrow-down-short-wide"></i></th>
                                                                                        <th class="fond_entete_table pointer text-end" wire:click="setOrderField('montant_ttc')">Total <i class="fa fa-arrow-down-short-wide"></i></th>
                                                                                    </tr>
                                                                                </thead>                                       
                                                                                <tbody>
                                                                                    @foreach($expeClient_ligne as $expeClient_lignes)
                                                                                        <tr> 
                                                                                            <td class="fw-bold"><a href="detail_product?id={{$expeClient_lignes->id_produit}}" wire:navigate class="text-bleu fw-bold">{{Str::limit($expeClient_lignes->produit, 22)}} - <span class="text-muted">{{Str::limit($expeClient_lignes->reference, 22)}}</span></a></td>                                                         
                                                                                            <td class="text-muted fw-semibold">{{$expeClient_lignes->type_produit}}</td>
                                                                                            <td class="fw-bold text-end">{{$expeClient_lignes->prix_vente}}</td>     
                                                                                            <td class="text-center fw-bold">{{$expeClient_lignes->quantite_expediee}}</td>         
                                                                                            <td class="text-center fw-semibold @if($expeClient_lignes->remise > 0) text-vert @endif">{{$expeClient_lignes->remise}}%</td>         
                                                                                            <td class="text-end fw-semibold">{{$expeClient_lignes->tva}}%</td>         
                                                                                            <td class="text-center fw-semibold">{{$expeClient_lignes->precompte}}%</td> 
                                                                                            <td class="text-center fw-semibold @if($expeClient_lignes->offrir == "Oui") text-danger @endif">{{$expeClient_lignes->offrir}}</td> 
                                                                                            <td class="text-end fw-bold">{{number_format($expeClient_lignes->montant_ttc,0,',',' ')}}</td> 
                                                                                        </tr> 
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>                                        
                                                                        </div>
                                                                        <div id="expedi" class="tab-pane active">                               
                                                                            <table class="table table-striped table-hover text-nowrap m-0"> 
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th class="fond_entete_table">Description</th>
                                                                                        <th class="fond_entete_table">Type</th>
                                                                                        <th class="fond_entete_table text-center">Qté commandée</th>
                                                                                        {{-- <th class="fond_entete_table text-center">Qté totale expédiée</th> --}}
                                                                                        <th class="fond_entete_table text-center">Qté. expédiée</th>
                                                                                        <th class="fond_entete_table text-center">Reste à expédier</th>
                                                                                        <th class="fond_entete_table text-start">Entrepôt (Stock) <i class="fas fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" style="vertical-align: middle; cursor: help" title="Choisissez le magassin d'expédition dans les paramètres"></i></th> 
                                                                                        {{-- <th class="fond_entete_table text-end"></th>  --}}
                                                                                    </tr>
                                                                                    </thead>                                       
                                                                                <tbody> 
                                                                                    @foreach($expeClient_ligne as $expeClient_lignes)                                                    
                                                                                        <tr> 
                                                                                            <td class="fw-bold"><a href="detail_product?id={{$expeClient_lignes->id_produit}}&active=4&champ=1-1&choix=2" wire:navigate class="text-bleu fw-bold">{{Str::limit($expeClient_lignes->produit, 22)}} - <span class="text-muted">{{Str::limit($expeClient_lignes->reference, 22)}}</span></a></td>                                                         
                                                                                            <td class="text-muted fw-semibold">{{$expeClient_lignes->type_produit}}</td>
                                                                                            <td class="text-center fw-bold">{{$expeClient_lignes->quantite}}</td>         
                                                                                            {{-- <td class="text-center fw-bold text-danger">{{$expeClient_lignes->quantite_total_expediee}}</td> --}}
                                                                                            <td class="text-center fw-semibold @if($expeClient_lignes->quantite_expediee > 0) text-vert @endif">
                                                                                                @if(!empty($expeClient_lignes->quantite_expediee))
                                                                                                    <span>{{$expeClient_lignes->quantite_expediee}}</span>
                                                                                                @else 
                                                                                                    <span>0</span>
                                                                                                @endif
                                                                                            </td>         
                                                                                            <td class="text-center text-danger fw-semibold">{{$expeClient_lignes->reste_a_expedier}}</td>  
                                                                                                @php
                                                                                                    foreach($ListeEntrepot as $ListeEntrepots) 
                                                                                                    { 
                                                                                                        if($expeClient_lignes->id_entrepot == $ListeEntrepots->id) { 
                                                                                                            $this->nom_entrepot = $ListeEntrepots->nom;                                          
                                                                                                        }                                                                                    
                                                                                                    }    
                                                                                                    foreach($stockProd as $stockProds){
                                                                                                        if($expeClient_lignes->id_produit == $stockProds->id_produit && $expeClient_lignes->id_entrepot == $stockProds->id_entrepot){
                                                                                                            $this->quantite_produit = $stockProds->quantite;
                                                                                                            $this->limite_stock_alerte = $stockProds->limite_stock_alerte; 
                                                                                                        }
                                                                                                    }  
                                                                                                @endphp                                                                
                                                                                            <td class="text-start fw-semibold">
                                                                                                @if($expeClient_lignes->type_produit == "Produit")
                                                                                                    <a href="detail_entrepot?id={{$expeClient_lignes->id_entrepot}}&active=4&champ=3-1&choix=2" class="text-bleu" wire:navigate>{{$this->nom_entrepot}}
                                                                                                        @if($this->quantite_produit > $this->limite_stock_alerte)
                                                                                                            (<span class="text-success fw-bold">{{$this->quantite_produit}}</span>)
                                                                                                        @elseif($this->quantite_produit > 0 && $this->quantite_produit <= $this->limite_stock_alerte)
                                                                                                            (<span class="text-info fw-bold" title="Stock inférieur ou égale au stock d'alerte ({{$this->limite_stock_alerte}})"><i class="fas fa-exclamation-triangle text-danger"></i> {{$this->quantite_produit}}</span>)
                                                                                                        @else
                                                                                                            (<span class="blink text-danger fw-bold">{{$this->quantite_produit}}</span>)
                                                                                                        @endif
                                                                                                    </a>
                                                                                                @endif
                                                                                            </td>
                                                                                            {{-- <td class="text-end">
                                                                                                @if($expeClient_lignes->quantite_expediee > 0)
                                                                                                    <a href="#" wire:click.prevent="annuler({{$expeClient_lignes->id}})" class="btn btn-xs text-muted border fw-semibold ms-auto" data-bs-toggle="modal" data-bs-target="#suppressionPartielModal" title="Cliquez pour expédier partiellement le stock" data-toggle="tooltip" data-bs-placement="top"><i class="fa fa-trash"></i></a>
                                                                                                @endif
                                                                                            </td>  --}}
                                                                                            {{-- <td class="text-end">
                                                                                                <a href="#" wire:click.prevent="editer({{$expeClient_lignes->id}})" class="btn btn-xs text-muted border fw-semibold ms-auto" data-bs-toggle="modal" data-bs-target="#expedionPartielModal" title="Cliquez pour expédier partiellement le stock" data-toggle="tooltip" data-bs-placement="top"><i class="fa fa-copy"></i> Partiel</a>
                                                                                            </td> --}}
                                                                                        </tr> 
                                                                                    @endforeach                                            
                                                                                </tbody>
                                                                            </table>                                           
                                                                        </div>
                                                                    </div> 
                                                                </div>
                                                            </div> 
                                                        </div>
                                                    </div>
                                                </div> 
                                                <div class="alert mt-3 mb-0 d-flex justify-content-between align-items-center rounded-2 px-3 py-2" style="background-color: #f4f8ff; border: 1px solid #cce0ff;">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#0d6efd" style="width: 18px; height: 18px;">
                                                            <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm8.706-1.442c1.146-.573 2.437.463 2.126 1.732l-1.162 4.646c-.23.916.634 1.56 1.393 1.144a.75.75 0 01.684 1.326c-1.63 1.09-3.7-.068-3.136-1.92l1.162-4.646c.15-.6-.35-1.12-1-.74a.75.75 0 01-.767-1.302zM12 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" />
                                                        </svg>
                                                        <span class="text-dark" style="font-size: 13px;">
                                                            <span class="fw-bold">Voir plus d'expédition,</span> 
                                                            <span class="text-muted ms-1">le statut d'un bon de commande.</span>
                                                        </span>
                                                    </div>
                                                    <a href="{{asset('listing_expedition_clt?active=6&champ=1-1&choix=3')}}" wire:navigate class="text-decoration-none fw-semibold text-primary" style="font-size: 13px;">Voir plus</a>
                                                </div>
                                            </div>                                                
                                        </div>                                            
                                    </div>                                     
                                    <div id="activites" class="container-fluid tab-pane fade pas_bordure">
                                        <div class="card shadow-sm">  
                                            <div class="card shadow-sm border border-light-subtle rounded-3 bg-white p-2">    
                                                <h6 class="fw-bold text-bleu mb-3"><i class="fas fa-calendar-alt" style=" color: #393b83;" title="Derniers événements liés"></i> @if($logCount > 1) Activités récentes @else Activité récente @endif ({{$logCount}})</h6>
                                                <div class="position-relative custom-scroll pe-2" style="height: calc(100vh - 368px); overflow-y: auto;">  
                                                    <div class="position-absolute border-start" style="left: 17px; top: 10px; bottom: 0; border-color: #dee2e6 !important; border-width: 2px !important; z-index: 1;"></div>
                                                    @foreach($log as $logs)
                                                    <div class="d-flex mb-3 position-relative" style="z-index: 2;">
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 bg-white" style="width: 36px; height: 36px;">
                                                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background-color: #f5e8e8; color: #198754;">
                                                                {{-- <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16" style="width: 14px; height: 14px;">
                                                                    <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/>
                                                                </svg> --}}
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
                                                        </div>
                                                        <div class="card border border-light-subtle shadow-sm flex-grow-1 ms-3">
                                                            <div class="card-body p-2 d-flex justify-content-between align-items-center">
                                                                <div>
                                                                    <div class="d-flex align-items-center gap-1">
                                                                        {{-- <span class="fw-bold text-dark" style="font-size: 13px;">Appel consigné</span> --}}
                                                                        <span class="text-muted" style="font-size: 13px;"><i class="fas fa-calendar-alt text-danger"></i> {{$logs->id}}</span>
                                                                        <span class="fw-bold text-dark" style="font-size: 12px;"><a href="detail_user?id={{$logs->user_id}}&active=12&champ=1-1" wire:navigate>par {{$logs->user_email}}</a></span>
                                                                    </div>
                                                                    <div class="text-secondary mt-1" style="font-size: 13px;">
                                                                        {{-- {{$logs->subject}} --}}
                                                                        {{-- Protège le contenu avec e() (évite injection HTML) / le dernier mot : créée ou modifiée, Applique <strong> seulement sur ce mot --}}
                                                                        {!! preg_replace('/(créé|modifié)$/u','<strong>$1</strong>',e($logs->subject)) !!} 
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex align-items-center gap-3">
                                                                    <span class="text-muted text-nowrap" style="font-size: 11px;">{{ \Carbon\Carbon::parse($logs->created_at)->locale('fr')->translatedFormat('j F Y \à H:i:s') }}</span>
                                                                    <button class="btn btn-sm btn-link text-muted p-0 text-decoration-none d-flex align-items-center">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16" style="width: 16px; height: 16px;"><path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/></svg>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div> 
                                                <div class="alert mt-3 mb-0 d-flex justify-content-between align-items-center rounded-2 px-3 py-2" style="background-color: #f4f8ff; border: 1px solid #cce0ff;">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#0d6efd" style="width: 18px; height: 18px;">
                                                            <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm8.706-1.442c1.146-.573 2.437.463 2.126 1.732l-1.162 4.646c-.23.916.634 1.56 1.393 1.144a.75.75 0 01.684 1.326c-1.63 1.09-3.7-.068-3.136-1.92l1.162-4.646c.15-.6-.35-1.12-1-.74a.75.75 0 01-.767-1.302zM12 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" />
                                                        </svg>
                                                        <span class="text-dark" style="font-size: 13px;">
                                                            <span class="fw-bold">Voir plus de produits,</span> 
                                                            <span class="text-muted ms-1">centralisez les informations de tous vos contacts stratégiques.</span>
                                                        </span>
                                                    </div>
                                                    <a href="{{asset('produit?active=4&champ=1-1&choix=2')}}" wire:navigate class="text-decoration-none fw-semibold text-primary" style="font-size: 13px;">Voir plus</a>
                                                </div>
                                            </div>                                                
                                        </div>                                            
                                    </div>
                                </div>                                    
                            </div>
                        </div>
                    </div>
                </div>
            </div>   
        </div>    
    </div>
    {{-- Modal --}}
</div>


