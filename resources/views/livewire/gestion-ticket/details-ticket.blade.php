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
                                                <div class="">
                                                    <div class="photoref">
                                                        <span class="fa fa-ticket" style=" color: #ff5252;" title="No photo"></span>
                                                    </div>
                                                </div>
                                                <div class="ms-2">
                                                    <h5 class="mb-0 fw-bold" title="» {{$this->nom_ticket}}">{{Str::limit($this->nom_ticket, 31) ?? 'Inconu(e)'}}</h5>
                                                    {{-- <small class="text-muted py-2 mx-3">»</small><br> --}}
                                                    <div class="text-muted py-2">  
                                                        {{-- <span class="text-vert fw-bold py-0" title="Activer"><i class="fa fa-check-circle"></i> Activer</span>  --}}
                                                    </div>
                                                    <h6 class="mb-0 fw-bold text-primary" title="» {{$this->type_demande}}">{{Str::limit($this->type_demande, 31) ?? 'Inconu(e)'}}</h6> 
                                                </div>                                                    
                                            </div>                                                
                                        </div>
                                        <div class="d-flex align-items-center justify-content-end gap-1">
                                            <div class="statusref">
                                                @if($priorite == "Faible")
                                                    <span class="badge bg-success" title="Priorité » {{$priorite}}"><i class="fa fa-battery-empty"></i> {{$priorite}}</span>
                                                @elseif($priorite == "Moyen")
                                                    <span class="badge bg-info" title="Priorité » {{$priorite}}"><i class="fa fa-battery-half"></i> {{$priorite}}</span>
                                                @elseif($priorite == "Élevé")
                                                    <span class="badge bg-warning" title="Priorité » {{$priorite}}"><i class="fa fa-battery-half"></i> {{$priorite}}</span>  
                                                @elseif($priorite == "Urgent")
                                                    <span class="badge bg-danger" title="Priorité » {{$priorite}}"><i class="fa fa-battery-full"></i> {{$priorite}}</span>  
                                                @endif
                                            </div>
                                            <div class="statusref">
                                                @if($statut == "Nouveau")
                                                    <span class="badge bg-secondary" title="Statut » {{$statut}}"><i class="fa-solid fa-star"></i> {{$statut}}</span>
                                                @elseif($statut == "En cours")
                                                    <span class="badge bg-dark" title="Statut » {{$statut}}"><i class="fas fa-refresh"></i> {{$statut}} ({{$progression}}%)</span>
                                                @elseif($statut == "En attente")
                                                    <span class="badge bg-primary" title="Statut » {{$statut}}"><i class="fa-solid fa-hand-dots"></i> {{$statut}}</span> 
                                                @elseif($statut == "Résolu")
                                                    <span class="badge bg-success" title="Statut » {{$statut}}"><i class="fa-solid fa-thumbs-up"></i> {{$statut}}</span>
                                                @elseif($statut == "Annulé")
                                                    <span class="badge bg-danger" title="Statut » {{$statut}}"><i class="fa fa-thumbs-down"></i> {{$statut}}</span>
                                                @endif
                                            </div>                            
                                        </div>
                                        <hr>
                                        <div class="d-flex gap-2 mb-1">
                                            <a href="{{asset('liste_ticket?active=13&champ=1-2')}}" wire:navigate title="Cliquez pour fermer" data-toggle="tooltip" class="btn btn-sm btn-outline-success flex-fill fw-semibold ms-auto"><i class="fa fa-close"></i> Fermer</a>
                                            <button type="submit" wire:click.prevent="update()" class="btn btn-sm btn-outline-secondary flex-fill fw-semibold ms-auto"><i class="fa fa-save"></i> Modifier</button>
                                            @if($confirmer === $this->ids)                                                               
                                                <a wire:click.prevent="supprimer({{$this->ids}})" class="btn btn-sm btn-outline-danger bg-danger text-white blink" style="font-size:11px;"  title="Cliquez pour confirmer la suppression" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-trash"> Confirmer ?</i></a>
                                            @else
                                                <a wire:click.prevent="confirmerDelete({{$this->ids}})" class="btn btn-sm btn-outline-muted"  title="Cliquez pour supprimer" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-trash"></i></a>
                                            @endif                                              
                                        </div>
                                        <div class="d-flex gap-2 mb-3 border-bottom py-2">                                           
                                        </div>
                                        <h5 class="fw-bold mb-3" title="» {{$this->nom_ticket}}">{{Str::limit($this->nom_ticket, 31) ?? 'Inconu(e)'}} »</h5>
                                        <div class="table-responsive">
                                            <table class="table table-borderless text-nowrap m-0">                                                
                                                <tr>
                                                    <td class="text-muted fw-semibold">Référence</td>
                                                    <td class="text-green fw-semibold">{{$this->reference}}</td> 
                                                </tr>
                                                <tr>
                                                    <td class="text-muted fw-semibold">Type demande</td>
                                                    <td class="text-primary">{{$this->type_demande}}</td>
                                                </tr>                                                
                                                <tr>
                                                    <td class="text-muted fw-semibold">Assigné à</td>
                                                    <td class="text-primary fw-semibold">{{$this->assignation}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted fw-semibold">Priorité</td>
                                                    <td class="fw-semibold @if($this->priorite =="Faible") text-success @elseif($this->priorite =="Moyen") text-info @elseif($this->priorite =="Élevé") text-warning @elseif($this->priorite =="Urgent") text-danger @endif">{{$this->priorite}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted fw-semibold">Source</td>
                                                    <td class="text-green">{{$this->source}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted fw-semibold">Entité</td>
                                                    <td class="text-muted">{{$this->societe}}</td>    
                                                </tr>
                                                <tr>
                                                    <td class="text-muted fw-semibold">Statut</td>
                                                    <td class="text-muted">{{$this->statut}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted fw-semibold">Progression</td>
                                                    <td class="text-muted"><span class="text-vert">{{$this->progression}}%</span></td>
                                                </tr>
                                                {{-- <tr>
                                                    <td class="text-muted fw-semibold">Description</td>
                                                    <td class="text-muted">{{$this->description}}</td>
                                                </tr> --}}
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
                                            
                                            {{-- <div class="d-flex align-items-center px-3 border-end flex-fill">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 44px; height: 44px; background-color: #fff8e1; color: #ffc107;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
                                                    </svg>
                                                </div>
                                                <div class="text-nowrap">
                                                    <span class="d-block fw-bold text-secondary" style="font-size: 11px;"></span>
                                                    <span class="d-block fw-bold text-dark mt-1" style="font-size: 14px;">{{number_format($stockPieceCount,0,',',' ')}}</span>
                                                </div>
                                            </div> --}}

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
                                            <a href="{{asset('liste_ticket?active=13&champ=1-2')}}" class="btn btn-sm btn-bleu d-none d-md-block ms-auto"><i class="fa fa-chevron-left"></i> Retour liste</a>
                                            <button class="btn btn-sm btn-default new_color" wire:click.prevent="precedant({{$this->ids}})" title="Précédant" data-toggle="tooltip"><i class="fa fa-chevron-left"></i></button>
                                            <a href="#" class="btn btn-sm btn-default new_color" wire:click.prevent="suivant({{$this->ids}})" title="Suivant" data-toggle="tooltip"><i class="fa fa-chevron-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-content">
                                    <div id="home" class="container-fluid tab-pane active pas_bordure">
                                        <div class="card shadow-sm">  
                                            <div class="card shadow-sm border border-light-subtle rounded-3 bg-white p-2">    
                                                <h6 class="fw-bold text-bleu mb-1"><i class="fa fa-ticket" style=" color: #393b83;" title="Derniers événements liés"></i> {{$title_fils}}</h6>
                                                <div class="position-relative custom-scroll pe-3" style="height: calc(100vh - 342px); overflow-y: auto;">  
                                                    <div class="hauteur_ecran">                                                        
                                                        <div class="row pt-1 px-2 pb-2">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                {{-- <label for="nom_opportunite" class="col-md-5 col-sm-3 fw-semibold col-form-label">Détails ticket » <span class="text-danger">{{$this->societe}}</span> </label> --}}
                                                                <div class="row mb-1">
                                                                    {{-- <label for="nom_ticket" class="col-lg-2 col-md-5 col-sm-3 fw-bold fs-5 col-form-label">Opportunité</label> --}}
                                                                    <div class="col-lg-10 col-md-10 col-sm-10">
                                                                        <input type="text" wire:model="nom_ticket" placeholder="par ex. Problème de facturation" class="form-control bordure fs-3 w-100 px-0 @error('nom_ticket') is-invalid @enderror" id="nom_ticket">
                                                                    </div>
                                                                    <div class="d-flex justify-content-start">
                                                                        @error('nom_ticket') <span class="text-danger">{{$message}}</span> @enderror 
                                                                    </div>
                                                                </div>                                            
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">                                            
                                                                <div class="row mb-1">
                                                                    <label for="reference" class="col-md-3 col-sm-3 fw-bold col-form-label">Référence</label>
                                                                    <div class="col-md-9 col-sm-9">
                                                                        <span class="form-control sans_bordure fw-bold fs-6 text-vert">{{$this->reference}}</span>
                                                                    </div>
                                                                    <div class="d-flex justify-content-start">
                                                                        @error('reference') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-1">
                                                                    <label for="type_demande" class="col-lg-4 col-md-5 col-sm-3 fw-bold col-form-label">Type demande</label>
                                                                    <div class="col-lg-8 col-md-7 col-sm-9">                                                        
                                                                        <select id="type_demande" wire:model="type_demande" class="form-control form-select bordure w-100 @error('type_demande') is-invalid @enderror">
                                                                            <option value=""></option>
                                                                            <option value="Support Technique">Support Technique</option>   
                                                                            <option value="Question ou bug">Question ou bug</option>	
                                                                            <option value="Formation">Formation</option>
                                                                            <option value="Question commerciale">Question commerciale</option>
                                                                            <option value="Demande d'aide fonctionnelle">Demande d'aide fonctionnelle</option>
                                                                            <option value="Demande de changement ou d'amélioration">Demande de changement ou d'amélioration</option>
                                                                            	
                                                                        </select> 
                                                                    </div>
                                                                    <div class="d-flex justify-content-start">
                                                                        @error('type_demande') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-1">
                                                                    <label for="assignation" class="col-lg-4 col-md-5 col-sm-3 fw-bold col-form-label">Assigné à</label>
                                                                    <div class="col-lg-8 col-md-7 col-sm-9">                                                        
                                                                        <select id="assignation" wire:model="assignation" class="form-control form-select bordure w-100 @error('assignation') is-invalid @enderror">
                                                                            <option value=""></option> 
                                                                                @foreach($listUser as $listUsers)
                                                                                    <option value="{{$listUsers->id}}">{{$listUsers->name}}</option>
                                                                                @endforeach	
                                                                        </select> 
                                                                    </div>
                                                                    <div class="d-flex justify-content-start">
                                                                        @error('assignation') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                    </div>
                                                                </div>
                                                                @if(auth()->user()->societe == "Administration")
                                                                    <div class="row mb-1">
                                                                        <label for="priorite" class="col-lg-4 col-md-5 col-sm-3 fw-bold col-form-label">Priorité</label>
                                                                        <div class="col-lg-8 col-md-7 col-sm-9">                                                        
                                                                            <select id="priorite" wire:model="priorite" class="form-control @if($this->priorite =="Faible") text-success @elseif($this->priorite =="Moyen") text-info @elseif($this->priorite =="Élevé") text-warning @elseif($this->priorite =="Urgent") text-danger @endif form-select bordure w-50 @error('priorite') is-invalid @enderror">
                                                                                <option value=""></option>   
                                                                                <option value="Faible">Faible</option>   
                                                                                <option value="Moyen">Moyen</option> 
                                                                                <option value="Élevé">Élevé</option> 
                                                                                <option value="Urgent">Urgent</option> 
                                                                            </select> 
                                                                        </div>
                                                                        <div class="d-flex justify-content-start">
                                                                            @error('priorite') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                        </div>
                                                                    </div>
                                                                @else 
                                                                    <div class="row mb-1">
                                                                        <label for="reference" class="col-md-3 col-sm-3 fw-bold col-form-label">Priorité</label>
                                                                        <div class="col-md-9 col-sm-9">
                                                                            <span class="form-control sans_bordure fw-bold fs-6 @if($this->priorite =="Faible") text-success @elseif($this->priorite =="Moyen") text-info @elseif($this->priorite =="Élevé") text-warning @elseif($this->priorite =="Urgent") text-danger @endif">{{$this->priorite}}</span>
                                                                        </div>
                                                                        <div class="d-flex justify-content-start">
                                                                            @error('reference') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                @if(auth()->user()->societe == "Administration")
                                                                    <div class="row mb-1">
                                                                        <label for="source" class="col-lg-4 col-md-5 col-sm-3 fw-bold col-form-label">Source</label>
                                                                        <div class="col-lg-8 col-md-7 col-sm-9">                                                        
                                                                            <select id="source" wire:model="source" class="form-control text-bleu fw-bold form-select bordure w-75 @error('source') is-invalid @enderror">
                                                                                <option value=""></option>   
                                                                                <option value="Système">Système</option>   
                                                                                <option value="whatsApp">WhatsApp</option>   
                                                                                <option value="E-mail">E-mail</option> 
                                                                                <option value="Formulaire">Formulaire</option> 
                                                                                <option value="Téléphone">Téléphone</option> 
                                                                            </select> 
                                                                        </div>
                                                                        <div class="d-flex justify-content-start">
                                                                            @error('source') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-1">
                                                                        <label for="societe" class="col-lg-4 col-md-5 col-sm-3 fw-bold col-form-label">Entité</label>
                                                                        <div class="col-lg-8 col-md-7 col-sm-9">                                                        
                                                                            <select id="societe" wire:model="societe" class="form-control form-select bordure w-75 @error('societe') is-invalid @enderror">
                                                                                <option value=""></option> 
                                                                                @foreach($liste_entite as $liste_entites)
                                                                                    <option value="{{$liste_entites->enseigne}}">{{$liste_entites->enseigne}}</option>
                                                                                @endforeach
                                                                            </select> 
                                                                        </div>
                                                                        <div class="d-flex justify-content-start">
                                                                            @error('societe') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">                                            
                                                                <div class="row mb-1">
                                                                    <label for="nom_user" class="col-lg-3 col-md-3 col-sm-4 fw-semibold col-form-label">Créé par</label>
                                                                    <div class="col-lg-9 col-md-9 col-sm-8">                                                        
                                                                        <select id="nom_user" wire:model="nom_user" class="form-control form-select bordure w-75 @error('nom_user') is-invalid @enderror">
                                                                            <option value="{{$this->nom_user}}">{{$this->nom_user}}</option>
                                                                        </select> 
                                                                    </div>
                                                                    <div class="d-flex justify-content-start">
                                                                        @error('nom_user') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-1">
                                                                    <label for="telephone_user" class="col-lg-4 col-md-4 col-sm-3 fw-semibold col-form-label">Téléphone</label>
                                                                    <div class="col-lg-8 col-md-8 col-sm-9">
                                                                        <input type="text" wire:model="telephone_user" placeholder="" class="form-control bordure w-100 @error('telephone_user') is-invalid @enderror" id="telephone_user">
                                                                    </div>
                                                                    <div class="d-flex justify-content-start">
                                                                        @error('telephone_user') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                    </div>
                                                                </div> 
                                                                @if(auth()->user()->societe == "Administration")
                                                                    <div class="row mb-1">
                                                                        <label for="statut" class="col-lg-3 col-md-3 col-sm-3 fw-bold col-form-label">Statut</label>
                                                                        <div class="col-lg-9 col-md-9 col-sm-9">                                                        
                                                                            <select id="statut" wire:model="statut" class="form-control fw-bold @if($this->statut =="Nouveau") text-secondary @elseif($this->statut =="En cours") text-dark @elseif($this->statut =="En attente") text-primary @elseif($this->statut =="Résolu") text-success @elseif($this->statut =="Annulé") text-danger @endif form-select bordure w-75 @error('priorite') is-invalid @enderror">
                                                                                <option value=""></option>   
                                                                                <option value="Nouveau">Nouveau</option>   
                                                                                <option value="En cours">En cours</option> 
                                                                                <option value="En attente">En attente</option> 
                                                                                <option value="Résolu">Résolu</option> 
                                                                                <option value="Annulé">Annulé</option> 
                                                                            </select> 
                                                                        </div>
                                                                        <div class="d-flex justify-content-start">
                                                                            @error('statut') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                <div class="row mb-1">
                                                                    <label for="progression" class="col-lg-4 col-md-4 col-sm-4 fw-bold col-form-label">Progression <span class="text-vert">{{$progression}}%</span></label>
                                                                    @if(auth()->user()->societe == "Administration")
                                                                        <div class="col-lg-8 col-md-8 col-sm-4">
                                                                            <input type="number" wire:model="progression" min="0" class="form-control bordure w-50 @error('progression') is-invalid @enderror" id="progression"> 
                                                                        </div>
                                                                        <div class="d-flex justify-content-start">
                                                                            @error('progression') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                        </div>
                                                                    @endif
                                                                </div>                                              
                                                            </div>                                        
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 px-0">
                                                                <div class="table-responsive mt-2">
                                                                    <div class="table-responsive mt-1"> 
                                                                        <ul class="nav nav-tabs border-0" role="tablist">
                                                                            <li class="nav-item">
                                                                                <a class="nav-link active" data-bs-toggle="tab" href="#description">Description</a>
                                                                            </li>
                                                                            @if(auth()->user()->societe == "Administration")
                                                                                <li class="nav-item">
                                                                                    <a class="nav-link" data-bs-toggle="tab" href="#notes">Note interne</a>
                                                                                </li>
                                                                            @endif
                                                                        </ul>
                                                                        <div class="tab-content table-responsive border-topk px-3" style="border-top-style: dashed; border-top-width: 1px;border-top-color: #0e04043d;">
                                                                            <div id="description" class="tab-pane active">
                                                                                <div class="row mt-2">
                                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                                        <div class="row mb-1">
                                                                                            {{-- <label for="description" class="col-lg-2 col-md-3 col-sm-4 col-form-label">Description</label> --}}
                                                                                            <div class="col-lg-12 col-md-12 col-sm-12">                                                        
                                                                                                <textarea rows="5" id="description" wire:model="description" class="form-control bordure px-0 w-100 @error('description') is-invalid @enderror" placeholder="Ajouter des détails sur ce ticket..."></textarea>
                                                                                            </div>
                                                                                            <div class="d-flex justify-content-start">
                                                                                                @error('description') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                                            </div>
                                                                                        </div>                                                                  
                                                                                    </div>                                                                                                                                        
                                                                                </div>                                                                
                                                                            </div>
                                                                            @if(auth()->user()->societe == "Administration")
                                                                                <div id="notes" class="tab-pane"> 
                                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                                        <div class="row mb-1">
                                                                                            {{-- <label for="note" class="col-sm-2 col-form-label" style="font-weight: 400;">Note</label> --}}
                                                                                            <div class="col-lg-12 col-md-12 col-sm-12">                                                        
                                                                                                <textarea rows="5" wire:model="note" class="form-control bordure px-0 w-100 @error('note') is-invalid @enderror" id="note" placeholder="Ajouter une note..."></textarea>
                                                                                            </div>
                                                                                            <div class="d-flex justify-content-start">
                                                                                                @error('note') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                                            </div>
                                                                                        </div>                                            
                                                                                    </div>
                                                                                </div>
                                                                            @endif
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
                                                            <span class="fw-bold">Voir plus de ticket,</span> 
                                                            <span class="text-muted ms-1">vous avez une visibilité sur toute vos tickets en instance ou résolu.</span>
                                                        </span>
                                                    </div>
                                                    <a href="{{asset('liste_ticket?active=13&champ=1-2')}}" wire:navigate class="text-decoration-none fw-semibold text-primary" style="font-size: 13px;">Voir plus</a>
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
</div>




