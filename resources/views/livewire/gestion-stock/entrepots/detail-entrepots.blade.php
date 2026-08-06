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
                    @foreach($entrepot as $entrepots)
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
                                                            <span class="fas fa-box-open  em080" style=" color: #ff5252;" title="No photo"></span>
                                                        </div>
                                                    </div>
                                                    <div class="ms-2">
                                                        <h4 class="mb-0 fw-bold" title="{{$entrepots->nom}}">{{Str::limit($entrepots->nom.'', 42) ?? 'Inconu(e)'}}</h4>
                                                        <div class="text-primary fw-bold">{{ $entrepots->reference }}</div>
                                                        @if($entrepots->code_barre)
                                                            <span class="text-bleu">Code-barres »</span> <span class="text-danger fw-semibold">{{$entrepots->code_barre}}</span>
                                                        @endif
                                                        {{-- <br><small class="text-muted">REF-001</small><br> --}}
                                                        <div class="text-muted">
                                                            @if($entrepots->active == 1)
                                                                <span class="badge bg-success py-0" title="Activer">Activer</span>
                                                            @else
                                                                <span class="badge bg-danger py-0" title="Désactiver">Désactiver </span>
                                                            @endif
                                                        </div>
                                                    </div>                                                    
                                                </div>                                                
                                            </div>
                                            <hr>
                                            <div class="d-flex gap-2 mb-3">
                                                <a href="{{asset('entrepot?active=4&champ=3-1&choix=2')}}" title="Cliquez pour modifier image" data-toggle="tooltip" class="btn btn-sm btn-outline-success flex-fill fw-semibold ms-auto"><i class="fa fa-close"></i> Fermer</a>
                                                <button href="#" wire:click.prevent="edit({{$entrepots->id}})" data-bs-toggle="modal" data-bs-target="#modifierEntrepotModal" title="Cliquez pour modifier" data-toggle="tooltip" class="btn btn-sm btn-outline-secondary flex-fill fw-semibold ms-auto"><i class="fa fa-pen"></i> Modifier</button>
                                            </div>
                                            {{-- <button href="{{asset('entrepot?active=4&champ=3-1&choix=2')}}" wire:navigate class="btn btn-sm btn-outline-success w-100 mb-4"><i class="fa fa-close"></i> Fermer</button> --}}
                                            <h5 class="fw-bold mb-3">À propos {{Str::limit($entrepots->nom.'', 42) ?? 'Inconu(e)'}}</h5>
                                            <table class="table table-borderless">                                                
                                                <tr>
                                                    <td class="text-muted fw-semibold">Téléphone</td>
                                                    <td class="text-muted">{{$entrepots->telephone}}</td> 
                                                </tr>
                                                @if($entrepots->email)
                                                <tr>
                                                    <td class="text-muted fw-semibold">Email</td>
                                                    <td class="text-muted">{{$entrepots->email}}</td>
                                                </tr> 
                                                @endif                                                
                                                <tr>
                                                    <td class="text-muted fw-semibold">Ville</td>
                                                    <td class="text-muted">{{ $entrepots->ville }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted fw-semibold">Pays</td>
                                                    <td class="text-muted">{{$entrepots->pays}}</td>
                                                </tr>
                                                @if($entrepots->adresse)
                                                <tr>
                                                    <td class="text-muted fw-semibold">Adresse</td>
                                                    <td class="text-muted">{{$entrepots->adresse}}</td>
                                                </tr>
                                                @endif 
                                                @if($entrepots->code_postal)
                                                <tr>
                                                    <td class="text-muted fw-semibold">Postal</td>
                                                    <td class="text-muted">{{$entrepots->code_postal}}</td>
                                                </tr>
                                                @endif 
                                                @if($entrepots->entrepot_parent)
                                                <tr>
                                                    <td class="text-muted fw-semibold">Parent</td>
                                                    <td class="text-muted">{{$entrepots->entrepot_parent}}</td> 
                                                </tr> 
                                                @endif  
                                                @if($entrepots->description)                                              
                                                    <tr>
                                                        <td class="text-muted fw-semibold">Description</td>
                                                        <td class="text-muted">{{$entrepots->description}}</td>
                                                    </tr>
                                                @endif                                             
                                            </table>
                                            <h5 class="fw-bold mb-3">Prix</h5>
                                            <table class="table table-borderless"> 
                                                <tr>
                                                    <td class="text-muted fw-semibold">Valorisation achat (PMP)</td>                                                         
                                                    <td class="text-primary fw-semibold">{{number_format($valorisation_achat_total,1,',',' ')}} <span class="text-bleu" style="font-size: 11px">{{$devise}}</span></td>
                                                </tr>                                                
                                               <tr>
                                                    <td class="text-muted fw-semibold">Nbre produits</td>
                                                    <td class="text-muted fw-semibold">{{$stockCount}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted fw-semibold">Total produits <i class="fas fa-info-circle" title="Les quantités sont affichées avec un arrondi automatique (+ ou -)" data-bs-toggle="tooltip" data-bs-placement="top" style=" vertical-align: middle; cursor: help"></i></td>                                                         
                                                    <td class="text-muted">{{$stockPieceCount}}</td> 
                                                </tr> 
                                            </table>
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
                                                        <span class="d-block fw-semibold text-dark" style="font-size: 13px;">{{ $entrepots->created_at->format('d/m/Y : H:i:s') }}</span>
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
                                                        <span class="d-block fw-semibold text-dark" style="font-size: 13px;">{{ $entrepots->updated_at->format('d/m/Y : H:i:s') }}</span>
                                                        {{-- <span class="d-block text-muted" style="font-size: 11px; margin-top: -2px;">GMT+1</span> --}}
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
                                                        <span class="d-block fw-bold text-secondary" style="font-size: 11px;">Nbre de pièces</span>
                                                        <span class="d-block fw-bold text-dark mt-1" style="font-size: 14px;">{{number_format($stockPieceCount,0,',',' ')}}</span>
                                                    </div>
                                                </div>                                                

                                                <div class="d-flex align-items-center px-3 border-end flex-fill">
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 44px; height: 44px; background-color: #fff3e0; color: #fd7e14;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                        </svg>
                                                    </div>
                                                    <div class="text-nowrap">
                                                        <span class="fw-bold text-secondary" style="font-size: 11px;">Valorisation achat(PMP)</span>
                                                        <span class="d-block fw-bold text-dark mt-1" style="font-size: 14px;">{{number_format($valorisation_achat_total,2,',',' ')}} <span style="font-size: 10px;">{{$this->devise}}</span></span>
                                                    </div>
                                                </div>

                                                <div class="d-flex align-items-center px-3 border-end flex-fill">
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 44px; height: 44px; background-color: #e0f7fa; color: #0dcaf0;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5h16.5a1.5 1.5 0 011.5 1.5v11.25a1.5 1.5 0 01-1.5 1.5H3.75a1.5 1.5 0 01-1.5-1.5V6a1.5 1.5 0 011.5-1.5zm10.5 7.5a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                                        </svg>
                                                    </div>
                                                    <div class="text-nowrap">
                                                        <span class="d-block fw-bold text-secondary" style="font-size: 11px;">Valeur à la vente</span>
                                                        <span class="d-block fw-bold text-dark mt-1" style="font-size: 14px;">{{number_format($valeurVenteTotal,2,',',' ')}} <span style="font-size: 10px;">{{$this->devise}}</span></span>
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
                                                        <span class="d-block fw-bold text-dark mt-1" style="font-size: 13px;">{{Str::limit($entrepots->nom_user, 22)}}</span>
                                                    </div>
                                                </div>

                                                <div class="d-flex align-items-center px-3 flex-fill">
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 44px; height: 44px; background-color: #ffebee; color: #dc3545;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v3" />
                                                        </svg>
                                                    </div>
                                                    <div class="text-nowrap">
                                                        <span class="d-block fw-bold text-secondary" style="font-size: 11px;">Limite stock alerte</span>
                                                        <span class="d-block fw-bold text-dark mt-1" style="font-size: 14px;">{{number_format($entrepots->limite_stock_alerte,0,',',' ')}}</span>
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
                                                <a class="nav-link" data-bs-toggle="tab" href="#mouvements">Mouvements ({{$mouvCountAfficher}}/{{$mouvementCount}})</a>
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
                                                <a href="entrepot?active=4&champ=3-1&choix=2" wire:navigate class="btn btn-sm btn-bleu d-none d-md-block ms-auto"><i class="fa fa-chevron-left"></i> Retour liste</a>
                                                <button class="btn btn-sm btn-default new_color" @if($this->id) wire:click.prevent="precedant({{$this->id}})" @else wire:click.prevent="precedant({{$this->id_entrepot}})" @endif title="Précédant" data-toggle="tooltip"><i class="fa fa-chevron-left"></i></button>
                                                <a href="#" class="btn btn-sm btn-default new_color" @if($this->id) wire:click.prevent="suivant({{$this->id}})" @else wire:click.prevent="suivant({{$this->id_entrepot}})" @endif title="Suivant" data-toggle="tooltip"><i class="fa fa-chevron-right"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-content">
                                        <div id="home" class="container-fluid tab-pane active pas_bordure">
                                            <div class="card shadow-sm">  
                                                <div class="card shadow-sm border border-light-subtle rounded-3 bg-white p-2">    
                                                    <h6 class="fw-bold text-bleu mb-1"><i class="fas fa-box-open" style=" color: #393b83;" title="Derniers événements liés"></i> Fiche magasin</h6>
                                                    <div class="position-relative custom-scroll pe-3" style="height: calc(100vh - 357px); overflow-y: auto;">  
                                                        {{-- <div class="position-absolute border-start" style="left: 17px; top: 10px; bottom: 0; border-color: #dee2e6 !important; border-width: 2px !important; z-index: 1;"></div> --}}
                                                        <div class="row">
                                                            <div class="col-sm-6"> 
                                                                <div class="table-responsive">  
                                                                    <table class="table text-nowrap m-0">                                        
                                                                        <tbody>                                                                            
                                                                            <tr>                                                                     
                                                                                <td class="titlefield text-muted fw-semibold">Nombre de produits</td>                                                         
                                                                                <td class="fw-semibold">{{$stockCount}}</td>         
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="titlefield text-muted fw-semibold">Nombre total de produits <i class="fas fa-info-circle" title="Les quantités sont affichées avec un arrondi automatique (+ ou -)" data-bs-toggle="tooltip" data-bs-placement="top" style=" vertical-align: middle; cursor: help"></i></td>                                                         
                                                                                <td class="fw-semibold">{{$stockPieceCount}}</td> 
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
                                                                                    <td class="titlefield text-muted fw-semibold">Valorisation achat (PMP)</td>                                                         
                                                                                    <td class="fw-semibold">{{number_format($valorisation_achat_total,1,',',' ')}} <span class="text-bleu" style="font-size: 11px">{{$devise}}</span></td>         
                                                                                </tr>                                                                                 
                                                                                @if(!empty($entrepots->description))
                                                                                    <tr>                                                                     
                                                                                        <td class="titlefield text-muted fw-semibold">Description</td>                                                         
                                                                                        <td class="fw-semibold">
                                                                                            {{substr($entrepots->description,0,58) > substr($entrepots->description,0,57) ? substr($entrepots->description,0,58).'...': $entrepots->description}}
                                                                                        </td>         
                                                                                    </tr> 
                                                                                @endif                                             
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>                                                      
                                                        <div class="tab-content">
                                                            <div class="container-fluid tab-pane active pas_bordure">                                           
                                                                <div class="d-flex align-items-center justify-content-between gap-3 pt-2 px-3">
                                                                    <div class="">
                                                                        {{-- <a href="update_entrepot?id={{$this->id}}&active=4&champ=3-1&choix=2" wire:navigate class="btn btn-sm btn-white text-bleu fw-bold ms-auto"><i class="fa fa-chevron-left"></i> Modifier magasin</a> --}}
                                                                    </div>
                                                                    <div class="d-flex align-items-center justify-content-between gap-2 py-3">
                                                                        <a href="#" data-bs-toggle="modal" data-bs-target="#correctionStockModal" wire:click.prevent="ajuster({{$entrepots->id}})" class="btn btn-sm btn-secondary fw-semibold ms-auto"><i class="fa fa-check"></i> Corriger le stock</a>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-lg-12 col-md-6 col-xs-12 mb-2">
                                                                        <div class="d-flex align-items-center justify-content-between gap-3 pt-2">
                                                                            <div class="px-3">
                                                                                <h5 class="text-bleu"><i class="fas fa-box-open" style=" color: #393b83;" title="Mouvement de stock"></i> Stock ({{$stockCount}})</h5>
                                                                            </div>
                                                                            <div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="table-responsive border-top"> 
                                                                            <table class="table table-striped table-hover text-nowrap m-0"> 
                                                                                <thead>
                                                                                    <tr>
                                                                                    <th class="fond_entete_table">Produits</th>
                                                                                    <th class="fond_entete_table">Libellé</th>
                                                                                    <th class="fond_entete_table">Nature</th>
                                                                                    <th class="fond_entete_table text-end">Dernier prix</th>
                                                                                    <th class="fond_entete_table"><i class="fas fa-info-circle" title="Les quantités sont affichées avec un arrondi automatique (+ ou -)" data-bs-toggle="tooltip" data-bs-placement="top" style=" vertical-align: middle; cursor: help"></i> Nbre de pièces</th>
                                                                                    <th class="fond_entete_table text-end">Prix moyen pondéré</th>
                                                                                    <th class="fond_entete_table text-end">Valorisation achat(PMP)</th>
                                                                                    <th class="fond_entete_table text-end">Prix de vente unitaire</th>
                                                                                    <th class="fond_entete_table text-end">Valeur à la vente	</th>
                                                                                    <th class="fond_entete_table">Dernière modif.</th>
                                                                                    </tr>
                                                                                </thead>                                       
                                                                                <tbody>
                                                                                    @foreach($stock as $stocks) 
                                                                                    <tr> 
                                                                                        <td class="text-muted text-bleu"><a href="detail_product?id={{$stocks->id_produit}}&active=4&champ=1-1&choix=2" wire:navigate class="text-bleu fw-bold">{{$stocks->reference}}</a></td>                                                         
                                                                                        <td class="text-muted"><a href="detail_product?id={{$stocks->id_produit}}&active=4&champ=1-1&choix=2" wire:navigate class="fw-semibold">{{$stocks->nom_produit}}</a></td>                                                         
                                                                                        <td class="">
                                                                                            @if($stocks->nature_produit == "Matière première")
                                                                                                <span style="color:#051e03; font-weight: 600;"> {{$stocks->nature_produit}}</span>
                                                                                            @else 
                                                                                                <span style="color:#707070; font-weight: 600;">{{$stocks->nature_produit}}</span>
                                                                                            @endif
                                                                                        </td>     
                                                                                        <td class="text-end fw-semibold">{{number_format($stocks->prix_achat_last,0,',',' ')}}</td>         
                                                                                        <td class="text-center fw-semibold @if($stocks->quantite <= 0) text-danger @endif">{{$stocks->quantite}}</td>         
                                                                                        <td class="text-end fw-semibold">{{number_format($stocks->prix_moyen_pondere_achat,2,',',' ')}}</td>         
                                                                                        <td class="text-end text-vert fw-semibold">{{number_format($stocks->valorisation_achat_total,2,',',' ')}}</td>         
                                                                                        <td class="text-end fw-semibold">{{number_format($stocks->prix_vente_unitaire,0,',',' ')}}</td>      
                                                                                        <td class="text-end fw-semibold">{{number_format($stocks->valeur_vente_total,2,',',' ')}}</td>      
                                                                                        <td class="">{{date('d-m-Y H:i:s', strtotime($stocks->updated_at))}}</td>      
                                                                                    </tr> 
                                                                                    @endforeach 
                                                                                    <tr>                                                                     
                                                                                        <td class="text-muted fw-semibold">Total</td>                                                         
                                                                                        <td class=""></td>         
                                                                                        <td class=""></td>         
                                                                                        <td class=""></td>         
                                                                                        <td class="text-center fw-bold text-bleu">{{$stockPieceCount}}</td>         
                                                                                        <td class=""></td>         
                                                                                        <td class="text-end fw-bold text-bleu">{{number_format($valorisation_achat_total,2,',',' ')}}</td>      
                                                                                        <td class=""></td>      
                                                                                        <td class="text-end fw-bold text-bleu">{{number_format($valeurVenteTotal,2,',',' ')}}</td>      
                                                                                        <td class=""></td>    
                                                                                    </tr> 
                                                                                </tbody>
                                                                            </table> 
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
                                                                <span class="fw-bold">Voir plus d'entrepots,</span> 
                                                                <span class="text-muted ms-1">entreposez tous vos stocks dans differents magasins.</span>
                                                            </span>
                                                        </div>
                                                        <a href="{{asset('entrepot?active=4&champ=3-1&choix=2')}}" wire:navigate class="text-decoration-none fw-semibold text-primary" style="font-size: 13px;">Voir plus</a>
                                                    </div>
                                                </div>                                                
                                            </div>                                            
                                        </div>
                                        <div id="mouvements" class="container-fluid tab-pane fade pas_bordure">  
                                            <div class="card shadow-sm">  
                                                <div class="card shadow-sm border border-light-subtle rounded-3 bg-white p-0">    
                                                    <h6 class="fw-bold text-bleu px-2 py-2"><i class="fas fa-people-carry" style=" color: #393b83;" title="Mouvement de stock"></i> Liste des mouvements de stock ({{$mouvCountAfficher}}/{{$mouvementCount}})</h6>
                                                    <div class="position-relative custom-scroll pe-3" style="height: calc(100vh - 360px); overflow-y: auto;"> 
                                                        <div class="table-responsive">                                                            
                                                            <div class="table-responsive border-top rounded-0">
                                                                <table class="table table-striped table-hover text-nowrap m-0"> 
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="fond_entete_table">Date</th>
                                                                            <th class="fond_entete_table">Réf. produit</th>
                                                                            <th class="fond_entete_table">Code Inv./Mouv.</th>
                                                                            <th class="fond_entete_table">Libellé du mouvement</th>
                                                                            <th class="fond_entete_table">Origine</th>
                                                                            <th class="fond_entete_table text-center">Qté</th>
                                                                        </tr>
                                                                    </thead>                                       
                                                                    <tbody>
                                                                        @foreach($mouvement as $mouvements) 
                                                                            <tr>    
                                                                                <td class=""><a href="mouvements?dates={{date('Y-m-d', strtotime($mouvements->created_at))}}&active=4&champ=3-1&choix=3" wire:navigate class="text-muted fw-semibold" title="clique pour voir mouvements">{{date('d/m/Y H:i:s', strtotime($mouvements->created_at))}}</a></td>      
                                                                                <td class="text-bleu"><a href="detail_product?id={{$mouvements->id_produit}}&active=4&champ=1-1&choix=2" wire:navigate class="text-bleu fw-bold">{{$mouvements->reference}}</a></td>                                                         
                                                                                <td class=""><a href="mouvements?dates={{date('Y-m-d', strtotime($mouvements->created_at))}}&active=4&champ=3-1&choix=3" wire:navigate class="text-muted fw-semibold" title="clique pour voir mouvements">{{$mouvements->code_mouvement}}</a></td>
                                                                                <td class="">{{$mouvements->libele_mouvement}}</td>      
                                                                                <td class="text-start fw-semibold"><a href="@if($mouvements->statut == "INV")detail_inventaire?id={{$mouvements->id_inventaire}} @elseif($mouvements->statut == "EXP")detail_expedition_clt?id={{$mouvements->id_expedition}} @endif" wire:navigate class="text-bleu">{{$mouvements->origine}}</a></td>   
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
                                                    <div class="alert mt-3 mb-0 d-flex justify-content-between align-items-center rounded-2 px-3 py-2" style="background-color: #f4f8ff; border: 1px solid #cce0ff;">
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
                                                    <div class="position-relative custom-scroll pe-2" style="height: calc(100vh - 309px); overflow-y: auto;">  
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
                    @endforeach 
                </div>
            </div>
        </div>
    </div>  
    {{-- Modal --}}
    @include('livewire.gestion-stock.entrepots.correction_stock') 
    @include('livewire.gestion-stock.entrepots.update_entrepot')
</div>
