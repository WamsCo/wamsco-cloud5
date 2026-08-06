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
                                                <div class="">
                                                    <div class="photoref">
                                                        <span class="fas fa-cash-register" style=" color: #ff5252;" title="No photo"></span>
                                                    </div>
                                                </div>
                                                <div class="ms-2">
                                                    <h5 class="mb-0 fw-bold" title="» {{$this->nom_point_vente}}">{{Str::limit($this->nom_point_vente, 31) ?? 'Inconu(e)'}}</h5>
                                                    <small class="text-muted py-2 mx-3">» {{$session_id}}</small><br>
                                                    <div class="text-muted py-2">
                                                        <div class="statusref">
                                                            @if($etat == "En cours")
                                                                <span class="badge bg-success py-1" title="Session {{$etat}}"><i class="fa fa-refresh"></i> {{$etat}}</span>
                                                            @elseif($etat == "Contrôle à l'ouverture")
                                                                <span class="badge bg-secondary py-1" title="Session {{$etat}}"><i class="fa fa-money-bill"></i> {{$etat}}</span>
                                                            @elseif($etat == "Clôturée")
                                                                <span class="badge bg-danger py-1" title="Session {{$etat}}"><i class="fa fa-check-circle"></i> {{$etat}}</span>
                                                            @elseif($etat == "Commencée")
                                                                <span class="badge bg-info py-1" title="Session {{$etat}}"><i class="fa fa-recycle"></i> {{$etat}}</span>
                                                            @endif   
                                                        </div>
                                                    </div>
                                                </div>                                                    
                                            </div>                                                
                                        </div>
                                        <hr class="mb-2">
                                        <div class="d-flex gap-2">
                                            @if($etat != "Contrôle à l'ouverture")
                                                <a href="{{asset('restau_sessions?active=5&champ=2-1&choix=1')}}" title="Cliquez pour fermer" data-toggle="tooltip" class="btn btn-sm btn-outline-danger flex-fill fw-semibold mb-2"><i class="fa fa-close"></i> Fermer</a>
                                            @endif 
                                        </div>
                                        <div class="d-flex gap-2 mb-3 border-bottom pt-0 pb-2">
                                            @if($etat == "Contrôle à l'ouverture")
                                                <button href="{{asset('restau_sessions?active=5&champ=2-1&choix=1')}}" wire:navigate class="btn btn-sm btn-outline-danger flex-fill" title="Cliquez pour fermer"><i class="fa fa-close"></i> Fermer</button>
                                            @endif 
                                            @if(!empty($date_ouverture))
                                                @if($etat != "Clôturée")
                                                    <a href="pipeline_restau?id={{$ids}}&ref={{$session_id}}" wire:navigate class="btn btn-sm btn-outline-green fw-semibold flex-fill"><i class="fa fa-refresh fa-spin"></i> Continuer la vente</a>
                                                @endif
                                            @else 
                                                @if($etat != "Clôturée")
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#ouvertureSessionModal" title="Cliquez pour ouvrir la session" class="btn btn-sm btn-outline-green fw-semibold flex-fill"><i class="fa fa-check"></i> Démarrer la vente</a>
                                                @endif
                                            @endif
                                            @if($this->verifie_id == auth()->user()->id)
                                                @if($etat == "En cours")
                                                    @if($confirmer === 1)    
                                                        <a href="#" wire:click.prevent="closeSession({{$this->ids}})" class="btn btn-sm btn-danger fw-semibold ms-auto blink"><i class="fa fa-ban"></i> Confirmer ?</a>
                                                    @else
                                                        <a href="#" wire:click.prevent="confirmerCloture(1)" class="btn btn-sm btn-outline-dark fw-semibold ms-auto"><i class="fa fa-ban"></i> Clôturer la session</a>
                                                    @endif
                                                @endif
                                            @endif                                           
                                        </div>
                                        <h5 class="fw-bold mb-3">Session » N° {{$session_id}}</h5>
                                        <div class="table-responsive">
                                            <table class="table table-borderless text-nowrap m-0">                                                
                                                <tr>
                                                    <td class="text-muted fw-semibold">Ouvert par</td>
                                                    <td class="text-muted">{{$this->nom_user}}</td> 
                                                </tr>                                            
                                                <tr>
                                                    <td class="text-muted fw-semibold">Solde initial <i class="fas fa-file-invoice-dollar"></i></td>
                                                    <td class="text-primary fw-semibold">{{ number_format($this->solde_initial,0,',',' ') }} <span class="text-bleu" style="font-size: 11px">{{$devise}}</span></td>
                                                </tr>  
                                                <tr>
                                                    <td class="text-muted fw-semibold">Solde clôture théorique <i class="fas fa-file-invoice-dollar"></i></td>
                                                    <td class="text-primary fw-semibold">{{ number_format($this->solde_cloture_theorique,0,',',' ') }} <span class="text-bleu" style="font-size: 11px">{{$devise}}</span></td>
                                                </tr> 
                                                <tr>
                                                    <td class="text-muted fw-semibold">Date d'ouverture</td>
                                                    <td class="text-primary fw-semibold"> @if(!empty($date_ouverture)) {{date('d/m/Y H:i:s', strtotime($date_ouverture))}} @else @endif</td> 
                                                </tr>
                                                <tr>
                                                    <td class="text-muted fw-semibold">Date de fermeture</td>
                                                    <td style="font-weight: 600; color:#ff5b57;">@if(!empty($date_fermeture)) {{date('d/m/Y H:i:s', strtotime($date_fermeture))}} @else @endif</td> 
                                                </tr>  
                                                <tr>
                                                    <td class="text-muted fw-semibold">Note</td>
                                                    <td class="text-muted">{{$this->note}}</td>
                                                </tr>                                                                                       
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

                                            <div class="d-flex align-items-center px-3 border-end flex-fill">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 44px; height: 44px; background-color: #e8f5e9; color: #198754;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                                                    </svg>
                                                </div>
                                                <div class="text-nowrap">
                                                    <span class="d-block fw-bold text-secondary" style="font-size: 11px;">Dernière activité</span>
                                                    <span class="d-block fw-semibold text-dark" style="font-size: 13px;">{{ $this->updated_at->format('d/m/Y : H:i:s') }}</span>
                                                    {{-- <span class="d-block text-muted" style="font-size: 11px; margin-top: -2px;">GMT+1</span> --}}
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
                                                    <span class="d-block fw-bold text-dark mt-1" style="font-size: 13px;">{{Str::limit($this->nom_user, 22)}}</span>
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
                                                    <span class="d-block fw-bold text-secondary" style="font-size: 11px;"></span>
                                                    {{-- <span class="d-block fw-bold text-dark mt-1" style="font-size: 14px;">{{number_format($stockPieceCount,0,',',' ')}}</span> --}}
                                                </div>
                                            </div>                                                

                                            {{-- <div class="d-flex align-items-center px-3 border-end flex-fill">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 44px; height: 44px; background-color: #fff3e0; color: #fd7e14;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                    </svg>
                                                </div>
                                                <div class="text-nowrap">
                                                    <span class="fw-bold text-secondary" style="font-size: 11px;">Valorisation achat(PMP)</span>
                                                    <span class="d-block fw-bold text-dark mt-1" style="font-size: 14px;">{{number_format($valorisation_achat_total,2,',',' ')}} <span style="font-size: 10px;">{{$this->devise}}</span></span>
                                                </div>
                                            </div> --}}

                                            {{-- <div class="d-flex align-items-center px-3 border-end flex-fill">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 44px; height: 44px; background-color: #e0f7fa; color: #0dcaf0;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5h16.5a1.5 1.5 0 011.5 1.5v11.25a1.5 1.5 0 01-1.5 1.5H3.75a1.5 1.5 0 01-1.5-1.5V6a1.5 1.5 0 011.5-1.5zm10.5 7.5a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                                    </svg>
                                                </div>
                                                <div class="text-nowrap">
                                                    <span class="d-block fw-bold text-secondary" style="font-size: 11px;">Valeur à la vente</span>
                                                    <span class="d-block fw-bold text-dark mt-1" style="font-size: 14px;">{{number_format($valeurVenteTotal,2,',',' ')}} <span style="font-size: 10px;">{{$this->devise}}</span></span>
                                                </div>
                                            </div> --}}

                                            {{-- <div class="d-flex align-items-center px-3 flex-fill">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 44px; height: 44px; background-color: #ffebee; color: #dc3545;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v3" />
                                                    </svg>
                                                </div>
                                                <div class="text-nowrap">
                                                    <span class="d-block fw-bold text-secondary" style="font-size: 11px;">Limite stock alerte</span>
                                                    <span class="d-block fw-bold text-dark mt-1" style="font-size: 14px;">{{number_format($entrepots->limite_stock_alerte,0,',',' ')}}</span>
                                                </div>
                                            </div> --}}

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
                                            <a class="nav-link" data-bs-toggle="tab" href="#mouvements">Mouvements ({{$mouvCountAfficher}})</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#activites">Activités récentes</a>
                                        </li>   
                                    </ul>
                                    <!-- Tab panes -->                        
                                    <div class="d-flex align-items-center justify-content-between gap-1">
                                        <div class="d-flex align-items-center justify-content-between gap-1"> 
                                            {{-- <a href="facturationcltkk-pdf?code=" class="btn btn-sm btn-default" title="Cliquez pour imprimer" data-toggle="tooltip" style="color: #161a1d;"><i class="fa fa-print"></i>Ticket</a> --}}
                                            {{-- <a href="facturationclt-pdf?code=Fact/191224/a0adc871" class="btn btn-sm btn-default" title="Cliquez pour imprimer" data-toggle="tooltip" target="blank"><i class="fa fa-print"></i>A4</a> --}}
                                        </div>                            
                                        <div class="d-flex align-items-center justify-content-between gap-1">                            
                                            <a href="{{asset('restau_sessions?active=5&champ=2-1&choix=1')}}" wire:navigate class="btn btn-sm btn-bleu d-none d-md-block ms-auto"><i class="fa fa-chevron-left"></i> Retour liste</a>
                                            <button class="btn btn-sm btn-default new_color" @if($this->ids) wire:click.prevent="precedant({{$this->ids}})" @else wire:click.prevent="precedant({{$this->ids}})" @endif title="Précédant" data-toggle="tooltip"><i class="fa fa-chevron-left"></i></button>
                                            <a href="#" class="btn btn-sm btn-default new_color"  @if($this->ids) wire:click.prevent="suivant({{$this->ids}})" @else wire:click.prevent="suivant({{$this->ids}})" @endif title="Suivant" data-toggle="tooltip"><i class="fa fa-chevron-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-content">
                                    <div id="home" class="container-fluid tab-pane active pas_bordure">
                                        <div class="card shadow-sm">  
                                            <div class="card shadow-sm border border-light-subtle rounded-3 bg-white p-2">    
                                                <h6 class="fw-bold text-bleu mb-1"><i class="fas fa-cash-register" style=" color: #393b83;" title="Derniers événements liés"></i> Fiche session</h6>
                                                <div class="position-relative custom-scroll pe-3" style="height: calc(100vh - 342px); overflow-y: auto;">  
                                                    {{-- <div class="position-absolute border-start" style="left: 17px; top: 10px; bottom: 0; border-color: #dee2e6 !important; border-width: 2px !important; z-index: 1;"></div> --}}
                                                    <div class="hauteur_ecran">
                                                        <div class="row">
                                                            <div class="col-sm-6"> 
                                                                <div class="table-responsive">  
                                                                    <table class="table text-nowrap m-0">                                        
                                                                        <tbody>                                            
                                                                            <tr>                                                                     
                                                                                <td class="titlefield text-muted fw-semibold">Ouvert par </td>                                                         
                                                                                <td style="font-weight: 600; color:#707070;">{{$nom_user}}</td>         
                                                                            </tr> 
                                                                            <tr>                                                                     
                                                                                <td class="titlefield text-muted fw-semibold">Solde initial</td>  
                                                                                <td style="font-weight: 600; color:#707070;">{{number_format($solde_initial,1,',',' ')}} <span class="text-bleu fw-bold" style="font-size: 9px;">{{$devise}}</span></td>   
                                                                            </tr> 
                                                                            @if(!empty($solde_final)) 
                                                                                <tr>                                                                     
                                                                                    <td class="titlefield text-muted fw-semibold">Solde final</td>  
                                                                                    <td style="font-weight: 600; color:#707070;">{{number_format($solde_final,1,',',' ')}} <span class="text-bleu fw-bold" style="font-size: 9px;">{{$devise}}</span></td> 
                                                                                </tr>
                                                                            @else
                                                                                <tr>                                                                     
                                                                                    <td class="titlefield text-muted fw-semibold">Solde clôture théorique</td>  
                                                                                    <td style="font-weight: 600; color:#707070;">{{number_format($solde_cloture_theorique,1,',',' ')}} <span class="text-bleu fw-bold" style="font-size: 9px;">{{$devise}}</span></td> 
                                                                                </tr>
                                                                            @endif                                            
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="table-responsive">   
                                                                    <table class="table text-nowrap m-0">                                        
                                                                        <tbody>
                                                                            <tr>                                                                     
                                                                                <td class="titlefield text-muted fw-semibold">Date d'ouverture</td>              
                                                                                <td style="font-weight: 600; color:#707070;"> @if(!empty($date_ouverture)) {{date('d/m/Y H:i:s', strtotime($date_ouverture))}} @else @endif</td> 
                                                                            </tr> 
                                                                            <tr>                                                                     
                                                                                <td class="titlefield text-muted fw-semibold">Date de fermeture</td> 
                                                                                <td style="font-weight: 600; color:#ff5b57;">@if(!empty($date_fermeture)) {{date('d/m/Y H:i:s', strtotime($date_fermeture))}} @else @endif</td> 
                                                                            </tr> 
                                                                            <tr>                                                                     
                                                                                <td class="titlefield text-muted fw-semibold">Note</td> 
                                                                                <td style="font-weight: 600; color:#707070;">{{substr($note,0,72) > substr($note,0,71) ? substr($note,0,72).'...': $note}}</td> 
                                                                            </tr>                                        
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>                                
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="table-responsivek">                                   
                                                                    <div class="tab-content">
                                                                        <div id="home" class="container-fluid tab-pane active pas_bordure">                                           
                                                                            <div class="d-flex align-items-center justify-content-between gap-3 py-3 px-3">
                                                                                <div class="">                                                    
                                                                                    <h6 class="text-bleu"><i class="fas fa-file-invoice" style=" color: #393b83;" title="Commandes effectuées"></i> Commandes ({{$factClientEnteteCount}})</h6>
                                                                                </div>
                                                                                {{-- <div class="d-flex align-items-center justify-content-between gap-2 py-3"> 
                                                                                    @if($this->verifie_id == auth()->user()->id)                                                  
                                                                                        @if(!empty($date_ouverture))
                                                                                            @if($etat != "Clôturée")
                                                                                                <a href="pos?id={{$ids}}&ref={{$session_id}}" wire:navigate class="btn btn-sm btn-secondary ms-auto"><i class="fa fa-refresh"></i> Continuer la vente</a>
                                                                                            @endif
                                                                                        @else 
                                                                                            @if($etat != "Clôturée")
                                                                                                <a href="#" data-bs-toggle="modal" data-bs-target="#ouvertureSessionModal" title="Cliquez pour ouvrir la session" class="btn btn-sm btn-success ms-auto"><i class="fa fa-check"></i> Démarrer la vente</a>
                                                                                            @endif
                                                                                        @endif
                                                                                        @if($etat == "En cours")
                                                                                            @if($confirmer === 1)    
                                                                                                <a href="#" wire:click.prevent="closeSession({{$this->ids}})" class="btn btn-sm btn-danger fw-semibold ms-auto blink"><i class="fa fa-ban"></i> Confirmer ?</a>
                                                                                            @else
                                                                                                <a href="#" wire:click.prevent="confirmerCloture(1)" class="btn btn-sm btn-danger fw-semibold ms-auto"><i class="fa fa-ban"></i> Cloturer la session</a>
                                                                                            @endif
                                                                                        @endif
                                                                                    @endif
                                                                                </div> --}}
                                                                            </div>
                                                                            <div class="table-responsive border-top rounded-0"> 
                                                                                <table class="table table-striped table-hover text-nowrap m-0"> 
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th class="fond_entete_table pointer" wire:click="setOrderField('code_facture')">Référence <i class="fa fa-arrow-down-short-wide"></i></th>   
                                                                                            <th class="fond_entete_table pointer" wire:click="setOrderField('nom_client')">Client <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                                                            <th class="fond_entete_table pointer" wire:click="setOrderField('date_facturation')">Facturation <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                                                            <th class="fond_entete_table pointer" wire:click="setOrderField('mode_reglement')">Règlement <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                                                            <th class="fond_entete_table pointer text-end" wire:click="setOrderField('montant_ttc')">Montant TTC <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                                                            <th class="fond_entete_table pointer text-end" wire:click="setOrderField('montant_recu')">Reçu <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                                                            <th class="fond_entete_table pointer text-end" wire:click="setOrderField('reste_a_percevoir')">Créance <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                                                            @if($autoriser == 1)
                                                                                                <th class="fond_entete_table pointer text-end" wire:click="setOrderField('marge')">Marge <i class="fa fa-arrow-down-short-wide"></i></th>                                                            
                                                                                            @endif
                                                                                            <th class="fond_entete_table pointer" wire:click="setOrderField('etat')">Etat <i class="fa fa-arrow-down-short-wide"></i></th>
                                                                                            <th class="fond_entete_table pointer" wire:click="setOrderField('created_at')">Date <i class="fa fa-arrow-down-short-wide"></i></th>
                                                                                            <th class="fond_entete_table pointer text-end"></th>
                                                                                        </tr>
                                                                                    </thead>                                       
                                                                                    <tbody>
                                                                                        @foreach($factClient_entete as $factClient_entetes) 
                                                                                        <tr> 
                                                                                            <td class="fw-semibold pointer"><a href="nouveau_fact_clt?id={{$factClient_entetes->id}}&ref={{$factClient_entetes->code_facture}}&active=7&champ=1-1&choix=1" wire:navigate><i class="fas fa-file-invoice-dollar text-danger" aria-hidden="true"></i> {{$factClient_entetes->code_facture}}</a></td> 
                                                                                            <td class="fw-semibold pointer"><a href="detail_tier?id={{$factClient_entetes->id_client}}&ref={{$factClient_entetes->code_facture}}&active=3&champ=3-2" wire:navigate class="text-bleu">@if($factClient_entetes->nom_client) <i class="fa fa-user-circle"></i> @endif {{substr($factClient_entetes->nom_client,0,52) > substr($factClient_entetes->nom_client,0,51) ? substr($factClient_entetes->nom_client,0,52).'...': $factClient_entetes->nom_client}}</a></td>                                                                      
                                                                                            <td class="">{{date('d-m-Y', strtotime($factClient_entetes->date_facturation))}}</td>
                                                                                            <td class="fw-semibold">{{$factClient_entetes->mode_reglement}}</td>
                                                                                            <td class="fw-semibold text-end">{{number_format($factClient_entetes->montant_ttc,0,',',' ')}}</td>
                                                                                            <td class="fw-semibold text-end">{{number_format($factClient_entetes->montant_recu,0,',',' ')}}</td>
                                                                                            <td class="fw-semibold text-end">{{number_format($factClient_entetes->reste_a_percevoir,0,',',' ')}}</td>
                                                                                            @if($autoriser == 1)
                                                                                                <td class="fw-semibold text-end">{{number_format($factClient_entetes->marge,0,',',' ')}}</td>
                                                                                            @endif 
                                                                                            <td class="text-start">
                                                                                                @if($factClient_entetes->etat == "Brouillon")
                                                                                                    <span class="badge bg-secondary py-1" title="Facture {{$factClient_entetes->etat}}"><i class="fa fa-box"></i> {{$factClient_entetes->etat}}</span>
                                                                                                @elseif($factClient_entetes->etat == "Impayée")
                                                                                                    <span class="badge bg-warning py-1" title="Facture {{$factClient_entetes->etat}}"><i class="fa fa-times-circle"></i> {{$factClient_entetes->etat}}</span>
                                                                                                @elseif($factClient_entetes->etat == "Payée")
                                                                                                    <span class="badge bg-success py-1" title="Facture {{$factClient_entetes->etat}}"><i class="fa fa-check-circle"></i> {{$factClient_entetes->etat}}</span>
                                                                                                @elseif($factClient_entetes->etat == "Commencée")
                                                                                                    <span class="badge bg-info py-1" title="Facture {{$factClient_entetes->etat}}"><i class="fa fa-recycle"></i> {{$factClient_entetes->etat}}</span>                                                                
                                                                                                @endif                                                 
                                                                                            </td> 
                                                                                            <td class="">{{date('d-m-Y H:i:s', strtotime($factClient_entetes->created_at))}}</td>
                                                                                            <td class="text-end"><a href="nouveau_fact_clt?id={{$factClient_entetes->id}}&ref={{$factClient_entetes->code_facture}}&active=7&champ=1-1&choix=1" wire:navigate class="btn-outline-muted pointer" title="Cliquez pour voir les details" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-eye"></i></a></td>
                                                                                            {{-- <td class="taille_icon text-end">
                                                                                                @if($confirmer === $factClient_entetes->id)                                                               
                                                                                                    <a wire:click.prevent="supprimer({{$factClient_entetes->id}},'{{$factClient_entetes->code_facture}}')" class="btn btn-outline-danger btn-xs bg-danger text-white blink" style="font-size:8px;"  title="Cliquez pour confirmer la suppression" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-trash"> Confirmer ?</i></a>
                                                                                                @else
                                                                                                    <a wire:click.prevent="confirmerDelete({{$factClient_entetes->id}})" class="btn btn-outline-muted btn-xs"  title="Cliquez pour supprimer" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-trash"></i></a>
                                                                                                @endif
                                                                                            </td>  --}}
                                                                                        </tr> 
                                                                                        @endforeach 
                                                                                        <tr>                                                                     
                                                                                            <td class="text-muted fw-semibold">Total</td>                                                         
                                                                                            <td colspan="3"></td> 
                                                                                            <td class="text-end fw-semibold text-bleu">{{number_format($montantTTC,0,',',' ')}}</td>         
                                                                                            <td class="text-end fw-semibold text-bleu">{{number_format($montantRecu,0,',',' ')}}</td> 
                                                                                            <td class="text-end fw-semibold text-bleu">{{number_format($reste_a_percevoir,0,',',' ')}}</td>      
                                                                                            @if($autoriser == 1)
                                                                                                <td class="text-end fw-semibold text-bleu">{{number_format($marge,0,',',' ')}}</td>
                                                                                            @endif       
                                                                                            <td colspan="3"></td>  
                                                                                        </tr> 
                                                                                    </tbody>
                                                                                </table> 
                                                                            </div>                                                                                              
                                                                        </div>
                                                                        <div id="menu1" class="container-fluid tab-pane fade pas_bordure"><br>
                                                                            <div class="d-flex align-items-center justify-content-between gap-3 py-3 px-3">
                                                                                <div class="">
                                                                                    <h5 class="text-bleu"><i class="fas fa-people-carry" style=" color: #393b83;" title="Mouvement de stock"></i> Liste des mouvements de stock ({{$mouvCountAfficher}})</h5>
                                                                                </div>
                                                                                <div>
                                                                                    {{-- @foreach($entrepot as $entrepots)
                                                                                        <a href="#" data-bs-toggle="modal" data-bs-target="#correctionStockModal" wire:click.prevent="ajuster({{$entrepots->id}})" class="btn btn-secondary fw-semibold ms-auto"><i class="fa fa-check"></i> Corriger le stock</a>
                                                                                    @endforeach  --}}
                                                                                </div>
                                                                            </div>
                                                                            <div class="table-responsive border-top rounded-0"> 
                                                                                <table class="table table-striped table-hover text-nowrap m-0"> 
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th class="fond_entete_table">Date</th>
                                                                                            <th class="fond_entete_table">Réf. produit</th>
                                                                                            <th class="fond_entete_table">Entrepôt</th> 
                                                                                            <th class="fond_entete_table">Code Inv./Mouv.</th>
                                                                                            <th class="fond_entete_table">Libellé du mouvement</th>
                                                                                            <th class="fond_entete_table">Origine</th>
                                                                                            <th class="fond_entete_table text-center">Qté</th>
                                                                                        </tr>
                                                                                    </thead>                                       
                                                                                    <tbody>
                                                                                        @foreach($mouvement as $mouvements) 
                                                                                            <tr>    
                                                                                                <td class=""><a href="mouvements?dates={{date('Y-m-d', strtotime($mouvements->created_at))}}" wire:navigate class="text-bleu fw-bold" title="clique pour voir mouvements">{{date('d/m/Y H:i:s', strtotime($mouvements->created_at))}}</a></td>      
                                                                                                <td class="text-bleu"><a href="detail_product?id={{$mouvements->id_produit}}" wire:navigate class="text-bleu fw-bold">{{$mouvements->reference}}</a></td> 
                                                                                                <td class="text-bleu fw-semibold"><a href="detail_entrepot?id={{$mouvements->id_entrepot}}" wire:navigate class="text-bleu">{{$mouvements->entrepot}}</a></td>                                                          
                                                                                                <td class=""><a href="mouvements?dates={{date('Y-m-d', strtotime($mouvements->created_at))}}" wire:navigate class="text-bleu fw-bold" title="clique pour voir mouvements">{{$mouvements->code_mouvement}}</a></td>
                                                                                                <td class="">{{$mouvements->libele_mouvement}}</td>                                                              
                                                                                                <td class="text-start fw-semibold">
                                                                                                    <a href="@if($mouvements->statut == "INV")detail_inventaire?id={{$mouvements->id_inventaire}} 
                                                                                                            @elseif($mouvements->statut == "EXP")detail_expedition_clt?id={{$mouvements->id_expedition}} 
                                                                                                            @elseif($mouvements->statut == "RCP")detail_reception_fourni?id={{$mouvements->id_reception}} 
                                                                                                            @else
                                                                                                            @endif" 
                                                                                                            wire:navigate class="text-bleu">{{$mouvements->origine}}
                                                                                                    </a>
                                                                                                </td>   
                                                                                                <td class="text-center text-vert fw-semibold">
                                                                                                    @if($mouvements->quantite > 0)
                                                                                                        +{{$mouvements->quantite}}                                                                
                                                                                                    @else
                                                                                                    <span class="text-danger">{{$mouvements->quantite}}</span>  
                                                                                                    @endif
                                                                                                </td>      
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
                                                </div> 
                                                <div class="alert mt-3 mb-0 d-flex justify-content-between align-items-center rounded-2 px-3 py-2" style="background-color: #f4f8ff; border: 1px solid #cce0ff;">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#0d6efd" style="width: 18px; height: 18px;">
                                                            <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm8.706-1.442c1.146-.573 2.437.463 2.126 1.732l-1.162 4.646c-.23.916.634 1.56 1.393 1.144a.75.75 0 01.684 1.326c-1.63 1.09-3.7-.068-3.136-1.92l1.162-4.646c.15-.6-.35-1.12-1-.74a.75.75 0 01-.767-1.302zM12 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" />
                                                        </svg>
                                                        <span class="text-dark" style="font-size: 13px;">
                                                            <span class="fw-bold">Voir plus de session restau.,</span> 
                                                            <span class="text-muted ms-1">un aperçu de vos sessions y compris les informations sur les transactions.</span>
                                                        </span>
                                                    </div>
                                                    <a href="{{asset('restau_sessions?active=5&champ=2-1&choix=1')}}" wire:navigate class="text-decoration-none fw-semibold text-primary" style="font-size: 13px;">Voir plus</a>
                                                </div>
                                            </div>                                                
                                        </div>                                            
                                    </div> 
                                    <div id="mouvements" class="container-fluid tab-pane fade pas_bordure">  
                                        <div class="card shadow-sm">  
                                            <div class="card shadow-sm border border-light-subtle rounded-3 bg-white p-0">    
                                                <h6 class="fw-bold text-bleu px-2 py-2"><i class="fas fa-people-carry" style=" color: #393b83;" title="Mouvement de stock"></i> Liste des mouvements de stock ({{$mouvCountAfficher}})</h6>
                                                <div class="position-relative custom-scroll pe-3" style="height: calc(100vh - 347px); overflow-y: auto;"> 
                                                    <div class="table-responsive">                                                            
                                                        <div class="table-responsive border-top rounded-0">
                                                            <table class="table table-striped table-hover text-nowrap m-0"> 
                                                                <thead>
                                                                    <tr>
                                                                        <th class="fond_entete_table">Date</th>
                                                                        <th class="fond_entete_table">Réf. produit</th>
                                                                        <th class="fond_entete_table">Entrepôt</th> 
                                                                        <th class="fond_entete_table">Code Inv./Mouv.</th>
                                                                        <th class="fond_entete_table">Libellé du mouvement</th>
                                                                        <th class="fond_entete_table">Origine</th>
                                                                        <th class="fond_entete_table text-center">Qté</th>
                                                                    </tr>
                                                                </thead>                                       
                                                                <tbody>
                                                                    @foreach($mouvement as $mouvements) 
                                                                    <tr>    
                                                                        <td class=""><a href="mouvements?dates={{date('Y-m-d', strtotime($mouvements->created_at))}}" wire:navigate class="text-bleu fw-bold" title="clique pour voir mouvements">{{date('d/m/Y H:i:s', strtotime($mouvements->created_at))}}</a></td>      
                                                                        <td class="text-bleu"><a href="detail_product?id={{$mouvements->id_produit}}" wire:navigate class="text-bleu fw-bold">{{$mouvements->reference}}</a></td> 
                                                                        <td class="text-bleu fw-semibold"><a href="detail_entrepot?id={{$mouvements->id_entrepot}}" wire:navigate class="text-bleu">{{$mouvements->entrepot}}</a></td>                                                          
                                                                        <td class=""><a href="mouvements?dates={{date('Y-m-d', strtotime($mouvements->created_at))}}" wire:navigate class="text-bleu fw-bold" title="clique pour voir mouvements">{{$mouvements->code_mouvement}}</a></td>
                                                                        <td class="">{{$mouvements->libele_mouvement}}</td>                                                              
                                                                        <td class="text-start fw-semibold">
                                                                            <a href="@if($mouvements->statut == "INV")detail_inventaire?id={{$mouvements->id_inventaire}} 
                                                                                    @elseif($mouvements->statut == "EXP")detail_expedition_clt?id={{$mouvements->id_expedition}} 
                                                                                    @elseif($mouvements->statut == "RCP")detail_reception_fourni?id={{$mouvements->id_reception}} 
                                                                                    @else
                                                                                    @endif" 
                                                                                    wire:navigate class="text-bleu">{{$mouvements->origine}}
                                                                            </a>
                                                                        </td>   
                                                                        <td class="text-center text-vert fw-semibold">
                                                                            @if($mouvements->quantite > 0)
                                                                                +{{$mouvements->quantite}}                                                                
                                                                            @else
                                                                            <span class="text-danger">{{$mouvements->quantite}}</span>  
                                                                            @endif
                                                                        </td>      
                                                                    </tr> 
                                                                @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div> 
                                                </div> 
                                                <div class="alert d-flex justify-content-between align-items-center rounded-2 m-2" style="background-color: #f4f8ff; border: 1px solid #cce0ff;">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#0d6efd" style="width: 18px; height: 18px;">
                                                            <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm8.706-1.442c1.146-.573 2.437.463 2.126 1.732l-1.162 4.646c-.23.916.634 1.56 1.393 1.144a.75.75 0 01.684 1.326c-1.63 1.09-3.7-.068-3.136-1.92l1.162-4.646c.15-.6-.35-1.12-1-.74a.75.75 0 01-.767-1.302zM12 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" />
                                                        </svg>
                                                        <span class="text-dark" style="font-size: 13px;">
                                                            <span class="fw-bold">Voir plus de mouvements de stock,</span> 
                                                            <span class="text-muted ms-1">dans vos differents magasins.</span>
                                                        </span>
                                                    </div>
                                                    <a href="{{asset('mouvements?active=4&champ=3-1&choix=3')}}" wire:navigate class="text-decoration-none fw-semibold text-primary" style="font-size: 13px;">Voir plus</a>
                                                </div>
                                            </div>                                                
                                        </div>
                                    </div>                                                                              
                                    <div id="activites" class="container-fluid tab-pane fade pas_bordure">
                                        <div class="card shadow-sm">  
                                            <div class="card shadow-sm border border-light-subtle rounded-3 bg-white p-2">    
                                                <h6 class="fw-bold text-bleu mb-1"><i class="fas fa-calendar-alt" style=" color: #393b83;" title="Derniers événements liés"></i> @if($logCount > 1) Activités récentes @else Activité récente @endif ({{$logCount}})</h6>
                                                <div class="d-flex justify-content-between align-items-center mb-1 flex-wrap gap-3">
                                                    <div class="d-flex gap-2 align-items-center">
                                                        {{-- <div class="input-group input-group-sm border rounded-2" style="width: 240px; background: #fff;">
                                                            <input type="search" wire:model.live="activite" id="activite" class="form-control border-0 shadow-none bg-transparent" placeholder="Rechercher une activité..." style="font-size: 13px;">
                                                            <button class="btn border-0 text-muted shadow-none d-flex align-items-center" type="button">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 14px; height: 14px;">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                                                </svg>
                                                            </button>
                                                        </div> --}}
                                                        {{-- <button class="btn btn-sm btn-outline-secondary bg-white border-light-subtle shadow-sm d-flex align-items-center gap-1" type="button" style="font-size: 13px;">
                                                            Ajouter une activité
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 12px; height: 12px;">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                                            </svg>
                                                        </button> --}}
                                                    </div>
                                                    {{-- <div class="d-flex align-items-center gap-2">
                                                        <span class="text-dark fw-medium" style="font-size: 13px;">Filtrer :</span>
                                                        <button class="btn btn-sm btn-outline-secondary bg-white border-light-subtle shadow-sm d-flex align-items-center gap-1" type="button" style="font-size: 13px;">
                                                            Tout
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 12px; height: 12px;">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                                            </svg>
                                                        </button>
                                                    </div> --}}
                                                </div>
                                                <div class="position-relative custom-scroll pe-2" style="height: calc(100vh - 294px); overflow-y: auto;">  
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

                                                    {{-- <div class="d-flex mb-3 position-relative" style="z-index: 2;">
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 bg-white" style="width: 36px; height: 36px;">
                                                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background-color: #fff3e0; color: #f57c00;">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 16px; height: 16px;">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        <div class="card border border-light-subtle shadow-sm flex-grow-1 ms-3">
                                                            <div class="card-body p-2 d-flex justify-content-between align-items-center">
                                                                <div>
                                                                    <div class="d-flex align-items-center gap-1">
                                                                        <span class="fw-bold text-dark" style="font-size: 13px;">Remarque</span>
                                                                        <span class="text-muted" style="font-size: 12px;">par WamsCo WamsCo</span>
                                                                        <span class="text-primary d-flex align-items-center gap-1 ms-1" style="font-size: 12px;">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 12px; height: 12px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 01-.923 1.785A5.969 5.969 0 006 21c1.282 0 2.47-.402 3.445-1.069.5-.34 1.148-.285 1.64.107A8.38 8.38 0 0012 20.25z" /></svg>
                                                                            1
                                                                        </span>
                                                                    </div>
                                                                    <div class="text-secondary mt-1" style="font-size: 13px;">je suis une note 12</div>
                                                                </div>
                                                                <div class="d-flex align-items-center gap-3">
                                                                    <span class="text-muted text-nowrap" style="font-size: 11px;">9 juin 2026 à 23:25 GMT+1</span>
                                                                    <button class="btn btn-sm btn-link text-muted p-0 text-decoration-none">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16" style="width: 16px; height: 16px;"><path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/></svg>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div> --}}

                                                    {{-- <div class="d-flex mb-3 position-relative" style="z-index: 2;">
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 bg-white" style="width: 36px; height: 36px;">
                                                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background-color: #f1f3f5; color: #6c757d;">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16" style="width: 14px; height: 14px;">
                                                                    <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555ZM0 4.697v7.104l5.803-3.558L0 4.697ZM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757Zm3.436-.586L16 11.801V4.697l-5.803 3.546Z"/>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        <div class="card border border-light-subtle shadow-sm flex-grow-1 ms-3">
                                                            <div class="card-body p-2 d-flex justify-content-between align-items-center">
                                                                <div>
                                                                    <div class="d-flex align-items-center gap-1">
                                                                        <span class="fw-bold text-dark" style="font-size: 13px;">Email envoyé</span>
                                                                        <span class="text-muted" style="font-size: 12px;">par WamsCo WamsCo</span>
                                                                    </div>
                                                                    <div class="text-secondary mt-1" style="font-size: 13px;">Envoi du devis n°DEV/110925/215844</div>
                                                                </div>
                                                                <div class="d-flex align-items-center gap-3">
                                                                    <span class="text-muted text-nowrap" style="font-size: 11px;">8 juin 2026 à 16:15 GMT+1</span>
                                                                    <button class="btn btn-sm btn-link text-muted p-0 text-decoration-none">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16" style="width: 16px; height: 16px;"><path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/></svg>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div> --}}

                                                    {{-- <div class="d-flex mb-1 position-relative" style="z-index: 2;">
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 bg-white" style="width: 36px; height: 36px;">
                                                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background-color: #eaf2ff; color: #0d6efd;">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 15px; height: 15px;">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        <div class="card border border-light-subtle shadow-sm flex-grow-1 ms-3">
                                                            <div class="card-body p-2 d-flex justify-content-between align-items-center">
                                                                <div>
                                                                    <div class="d-flex align-items-center gap-1">
                                                                        <span class="fw-bold text-dark" style="font-size: 13px;">Réunion planifiée</span>
                                                                        <span class="text-muted" style="font-size: 12px;">par WamsCo WamsCo</span>
                                                                    </div>
                                                                    <div class="text-secondary mt-1" style="font-size: 13px;">Réunion avec le client pour suivi du dossier</div>
                                                                </div>
                                                                <div class="d-flex align-items-center gap-3">
                                                                    <span class="text-muted text-nowrap" style="font-size: 11px;">7 juin 2026 à 10:00 GMT+1</span>
                                                                    <button class="btn btn-sm btn-link text-muted p-0 text-decoration-none">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16" style="width: 16px; height: 16px;"><path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/></svg>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div> --}}
                                                </div> 
                                                {{-- <div class="alert mt-3 mb-0 d-flex justify-content-between align-items-center rounded-2 px-3 py-2" style="background-color: #f4f8ff; border: 1px solid #cce0ff;">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#0d6efd" style="width: 18px; height: 18px;">
                                                            <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm8.706-1.442c1.146-.573 2.437.463 2.126 1.732l-1.162 4.646c-.23.916.634 1.56 1.393 1.144a.75.75 0 01.684 1.326c-1.63 1.09-3.7-.068-3.136-1.92l1.162-4.646c.15-.6-.35-1.12-1-.74a.75.75 0 01-.767-1.302zM12 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" />
                                                        </svg>
                                                        <span class="text-dark" style="font-size: 13px;">
                                                            <span class="fw-bold">Voir plus de entrepots,</span> 
                                                            <span class="text-muted ms-1">centralisez les informations de tous vos contacts stratégiques.</span>
                                                        </span>
                                                    </div>
                                                    <a href="{{asset('produit?active=4&champ=1-1&choix=2')}}" wire:navigate class="text-decoration-none fw-semibold text-primary" style="font-size: 13px;">Voir plus</a>
                                                </div> --}}
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
    @include('livewire.gestion-point-vente.controle_solde_ouverture') 
</div>

