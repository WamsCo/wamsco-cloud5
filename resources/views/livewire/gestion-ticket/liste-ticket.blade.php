<div x-data="{selection: @entangle('selection').defer}">
    <div id="content" class="app-content" wire:poll.visible.30s>
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
                            <!-- CONTENU -->
                            <div class="col-lg-12">
                                <!-- KPI -->
                                <div class="card shadow-sm border border-light-subtle rounded-3 bg-white">
                                    <div class="card-body p-2">
                                        <div class="d-flex align-items-center justify-content-between flex-nowrap overflow-auto py-2">
                                            
                                            <div class="d-flex align-items-center px-3 border-end flex-fill">                                                
                                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 44px; height: 44px; background-color: #e0f7fa; color: #0dcaf0;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5h16.5a1.5 1.5 0 011.5 1.5v11.25a1.5 1.5 0 01-1.5 1.5H3.75a1.5 1.5 0 01-1.5-1.5V6a1.5 1.5 0 011.5-1.5zm10.5 7.5a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                                    </svg>
                                                </div>
                                                <div class="text-nowrap">
                                                    <span class="d-block fw-bold text-secondary" style="font-size: 11px;">Total Ticket</span>
                                                    <span class="d-block fw-semibold text-dark" style="font-size: 13px;">Résolu : <span class="text-danger">{{number_format($nbreTotalResolu,0,',',' ')}}</span> / {{number_format($nbreTotalTicket,0,',',' ')}}</span>
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
                                                    <span class="d-block fw-semibold text-dark" style="font-size: 13px;">
                                                        @if($derniereActivite)
                                                            <span class="text-bleu">{{ $derniereActivite->updated_at->diffForHumans() }}</span>
                                                        @else
                                                            <span class="text-danger">Aucune activité</span>
                                                        @endif
                                                    </span>
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
                                                    <span class="d-block fw-bold text-secondary" style="font-size: 11px;"></span>
                                                    {{-- <span class="d-block fw-bold text-dark mt-1" style="font-size: 14px;"> <span style="font-size: 10px;">{{$this->devise}}</span></span> --}}
                                                </div>
                                            </div>                                            

                                            {{-- <div class="d-flex align-items-center px-3 border-end flex-fill">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 44px; height: 44px; background-color: #fff3e0; color: #fd7e14;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                    </svg>
                                                </div>
                                                <div class="text-nowrap">
                                                    <span class="fw-bold text-secondary" style="font-size: 11px;">Point de fidelité : Objectifs</span>
                                                    <span class="d-block fw-bold text-dark mt-1" style="font-size: 14px;"> <span style="font-size: 10px;">Pts</span></span>
                                                </div>
                                            </div>    --}}
                                            
                                            {{-- <div class="d-flex align-items-center px-3 border-end flex-fill">                                                
                                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 44px; height: 44px; background-color: #eaf2ff; color: #0d6efd;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                                    </svg>
                                                </div>
                                                <div class="text-nowrap">
                                                    <span class="d-block fw-bold text-secondary" style="font-size: 11px;">Phase du cycle de vie</span>
                                                    <span class="d-block fw-bold text-dark mt-1" style="font-size: 13px;"></span>
                                                </div>
                                            </div> --}}

                                            {{-- <div class="d-flex align-items-center px-3 border-end flex-fill">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 44px; height: 44px; background-color: #fff8e1; color: #ffc107;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
                                                    </svg>
                                                </div>
                                                <div class="text-nowrap">
                                                    <span class="d-block fw-bold text-secondary" style="font-size: 11px;">Marge</span>
                                                    <span class="d-block fw-bold text-dark mt-1" style="font-size: 14px;">{{number_format($factClientEnteteMarge,0,',',' ')}} <span style="font-size: 10px;">{{$this->devise}}</span></span>
                                                </div>
                                            </div> --}}

                                            {{-- <div class="d-flex align-items-center px-3 flex-fill">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 44px; height: 44px; background-color: #ffebee; color: #dc3545;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v3" />
                                                    </svg>
                                                </div>
                                                <div class="text-nowrap">
                                                    <span class="d-block fw-bold text-secondary" style="font-size: 11px;">Créance</span>
                                                    <span class="d-block fw-bold text-dark mt-1" style="font-size: 14px;">{{number_format($factClientEnteteResteApercevoir,0,',',' ')}} <span style="font-size: 10px;">{{$this->devise}}</span></span>
                                                </div>
                                            </div> --}}

                                        </div>
                                    </div>
                                </div>                                    
                                <!-- ONGLETS -->
                                <div class="card-header d-flex align-items-center justify-content-between border-0 px-1">
                                    <!-- Nav tabs -->
                                    <ul class="nav nav-tabs margin_ajuste border-0" role="tablist">
                                        <li class="nav-item">                                                
                                            <a class="nav-link active" data-bs-toggle="tab" href="#home" title="Listing {{$title_fils}}"><i class="fas fa-university text-bleu"></i> {{$title_fils}} (<span class="text-vert">{{$ticketCount}}</span>)</a>                                                
                                        </li>  
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#activite">Activités récentes (<span class="text-vert">{{$logCount}}</span>)</a>
                                        </li>                                             
                                        {{-- <li class="nav-item">
                                            <a class="nav-link pointer" wire:click.prevent="soldeTier()">Haut</a>
                                        </li>          --}}
                                    </ul>
                                    <!-- Tab panes -->                        
                                    <div class="d-flex align-items-center justify-content-between gap-1">
                                        <div class="d-flex align-items-center justify-content-between gap-1"> 
                                            {{-- <a href="facturationcltkk-pdf?code=" class="btn btn-sm btn-default" title="Cliquez pour imprimer" data-toggle="tooltip" style="color: #161a1d;"><i class="fa fa-print"></i>Ticket</a> --}}
                                            {{-- <a href="facturationclt-pdf?code=Fact/191224/a0adc871" class="btn btn-sm btn-default" title="Cliquez pour imprimer" data-toggle="tooltip" target="blank"><i class="fa fa-print"></i>A4</a> --}}
                                        </div>                            
                                        <div class="d-flex align-items-center justify-content-between gap-1"> 
                                            <button data-bs-toggle="modal" data-bs-target="#createTicketModal" title="Cliquez pour créer un ticket" data-toggle="tooltip" class="btn btn-sm btn-danger ms-auto"><i class="fa fa-plus-circle"></i> Nouveau Ticket</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-content position-relative custom-scroll px-3" style="height: calc(100vh - 256px); overflow-y: auto;">
                                    <div id="home" class="container-fluid tab-pane active pas_bordure">  
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 px-0">   
                                                <div class="card shadow-sm border border-light-subtle rounded-3 bg-white px-0">
                                                    <div class="d-flex align-items-center justify-content-between gap-3 pt-2 px-2">
                                                        <div class="d-flex align-items-center justify-content-between gap-1">
                                                            <div class="">
                                                                <select name="" wire:model.lazy="parPage" id="par_page" class="form-control form-select bordure w-auto"> 
                                                                    @for($i = 20; $i <= 100; $i += 20)
                                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                                    @endfor        
                                                                </select> 
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between gap-1">
                                                                {{-- <h6><a href="{{asset('#')}}" class="btn btn-sm btn-success mt-2 d-none d-md-block"><i class="fa-solid fa-file-export"></i> Exporter Excel</a></h6> --}}
                                                                {{-- <h6><a href="#" data-bs-toggle="modal" data-bs-target="#importerTiersModal" class="btn btn-sm btn-warning mt-2 d-none d-md-block"><i class="fa-solid fa fa-upload"></i>  Importer Excel</a></h6> --}}
                                                                {{-- <h6><a href="{{asset('storage/manuel_users/Excel_Tier_WamsCo.xlsx')}}" download="Excel_Tier_WamsCo" class="btn btn-sm btn-secondary mt-2 d-none d-md-block"><i class="fa-solid fa fa-download"></i>  Télécharger Modèle</a></h6> --}}
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center justify-content-between gap-1">
                                                            <div class="card-headerk d-flex align-items-center justify-content-end gap-1">
                                                                <input type="date" wire:model.live="date_debut" class="form-control bordure w-auto">
                                                                <input type="date" wire:model.live="date_fin" class="form-control bordure w-auto">
                                                            </div>
                                                            @if(auth()->user()->societe == "Administration")
                                                                <div class="">
                                                                    <select wire:model.live="parAuteur" class="form-control form-select bordure w-100"> 
                                                                        <option value="">Créé par (Tous)</option>
                                                                        @foreach($liste_user as $liste_users)
                                                                            <option value="{{$liste_users->id}}">{{$liste_users->name}}</option>
                                                                        @endforeach
                                                                    </select> 
                                                                </div> 
                                                                <div class="">
                                                                    <select wire:model.live="parSociete" class="form-control form-select bordure w-100"> 
                                                                        <option value="">Société (Tous)</option>
                                                                        @foreach($liste_entite as $liste_entites)
                                                                            <option value="{{$liste_entites->enseigne}}">{{$liste_entites->enseigne}}</option>
                                                                        @endforeach
                                                                    </select> 
                                                                </div>
                                                            @endif 
                                                            <div>
                                                                <label for="query" class="sr-only">Recherche</label>
                                                                <input type="search" wire:model.live="query" id="query" class="form-control bordure" placeholder="Rechercher ticket">
                                                            </div>                           
                                                            <div>
                                                                <label for="parRef" class="sr-only">Recherche</label>
                                                                <input type="search" wire:model.live="parRef" id="parRef" class="form-control bordure" placeholder="Rechercher réf.">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="table-responsive border-top">
                                                        <table class="table table-striped table-hover text-nowrap m-0"> 
                                                            <thead>
                                                                <tr>
                                                                    <th class="fond_entete_table text-start pointer" wire:click="setOrderField('id')">Réference <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                                    <th class="fond_entete_table text-start pointer" wire:click="setOrderField('nom_ticket')">Ticket <i class="fa fa-arrow-down-short-wide"></i></th>   
                                                                    <th class="fond_entete_table text-start pointer" wire:click="setOrderField('type_demande')">Type demande <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                                    <th class="fond_entete_table text-start pointer" wire:click="setOrderField('priorite')">Priorité <i class="fa fa-arrow-down-short-wide"></i></th>  
                                                                    {{-- <th class="fond_entete_table text-start pointer" wire:click="setOrderField('etiquettes')">Etiquettes <i class="fa fa-arrow-down-short-wide"></i></th>     --}}
                                                                    <th class="fond_entete_table text-start pointer" wire:click="setOrderField('assignation')">Assignation <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                                    <th class="fond_entete_table text-center pointer" wire:click="setOrderField('progression')">Progression <i class="fa fa-arrow-down-short-wide"></i></th>                     
                                                                    <th class="fond_entete_table text-start pointer" wire:click="setOrderField('statut')">Statut <i class="fa fa-arrow-down-short-wide"></i></th>   
                                                                    <th class="fond_entete_table text-start pointer" wire:click="setOrderField('societe')">Société <i class="fa fa-arrow-down-short-wide"></i></th>
                                                                    <th class="fond_entete_table text-start pointer" wire:click="setOrderField('nom_user')">Créé par <i class="fa fa-arrow-down-short-wide"></i></th>                     
                                                                    <th class="fond_entete_table text-start pointer" wire:click="setOrderField('source')">Source <i class="fa fa-arrow-down-short-wide"></i></th>                     
                                                                    <th class="fond_entete_table text-start pointer" wire:click="setOrderField('created_at')">Date création <i class="fa fa-arrow-down-short-wide"></i></th>   
                                                                    {{-- <th class="fond_entete_table text-start pointer" wire:click="setOrderField('date_cloture')">Date clôture <i class="fa fa-arrow-down-short-wide"></i></th>    --}}
                                                                    @if(auth()->user()->societe == "Administration")
                                                                        <th class="fond_entete_table"></th>
                                                                    @endif 
                                                                </tr>
                                                            </thead>                                       
                                                            <tbody>
                                                                @foreach($ticket as $tickets) 
                                                                    <tr>  
                                                                        <td class="text-start"><a href="detail_ticket?id={{$tickets->id}}&active=13&champ=1-2" class="text-bleu fw-bold" wire:navigate><i class="fa fa-ticket"></i> {{$tickets->reference}}</a></td>                                             
                                                                        <td class="fw-semibold"><a class="fw-semibold" href="detail_ticket?id={{$tickets->id}}&active=13&champ=1-2" wire:navigate><i class="fa-solid fa-comments"></i> {{substr($tickets->nom_ticket,0,52) > substr($tickets->nom_ticket,0,51) ? substr($tickets->nom_ticket,0,52).'...': $tickets->nom_ticket}}</a></td>
                                                                        <td class="text-start"><a href="detail_ticket?id={{$tickets->id}}&active=13&champ=1-2" class="text-bleu fw-semibold" wire:navigate>{{$tickets->type_demande}}</a></td>                                             
                                                                        @if($tickets->priorite == "Faible")
                                                                            <td class="pointer text-start"><span class="badge bg-success"><i class="fa fa-battery-empty"></i> {{$tickets->priorite}}</span></td>                                          
                                                                        @elseif($tickets->priorite == "Moyen")
                                                                            <td class="pointer text-start"><span class="badge bg-info"><i class="fa fa-battery-half"></i> {{$tickets->priorite}}</span></td>
                                                                        @elseif($tickets->priorite == "Élevé")
                                                                            <td class="pointer text-start"><span class="badge bg-warning"><i class="fa fa-battery-half"></i> {{$tickets->priorite}}</span></td>
                                                                        @elseif($tickets->priorite == "Urgent")
                                                                            <td class="pointer text-start"><span class="badge bg-danger"><i class="fa fa-battery-full"></i> {{$tickets->priorite}}</span></td>
                                                                        @endif 
                                                                        @if(auth()->user()->societe == "Administration")
                                                                            <td class="text-start">
                                                                                <a href="detail_user?id={{$tickets->assignation_id}}&active=12&champ=1-1" class="text-bleu fw-semibold" wire:navigate>
                                                                                    <i class="fa fa-user-circle"></i> {{$tickets->assignation}}
                                                                                </a>
                                                                            </td> 
                                                                        @else 
                                                                            <td class="text-start"><a href="detail_ticket?id={{$tickets->id}}&active=13&champ=1-2" class="text-bleu fw-semibold" wire:navigate><i class="fa fa-user-circle"></i> {{$tickets->assignation}}</a></td> 
                                                                        @endif
                                                                        <td class="fw-semibold" style="text-align:center; font-size:9px;"> <span>{{$tickets->progression}}%</span>
                                                                            <div class="progress h-5px rounded-3 bg-gray-900 mb-5px">
                                                                                <div class="progress-bar progress-bar-striped rounded-right bg-teal" wire:ignore data-animation="width" data-value="{{$tickets->progression}}%" style="width: 100%"></div>
                                                                            </div>
                                                                        </td> 
                                                                        @if($tickets->statut == "Nouveau")
                                                                            <td class="text-start"><span class="badge bg-info"><i class="fa-solid fa-star"></i> {{$tickets->statut}}</span></td>                                          
                                                                        @elseif($tickets->statut == "En cours")
                                                                            <td class="text-start"><span class="badge bg-dark"><i class="fa fa-refresh"></i> {{$tickets->statut}}</span></td>
                                                                        @elseif($tickets->statut == "En attente")
                                                                            <td class="text-start"><span class="badge bg-primary"><i class="fa-solid fa-hand-dots"></i> {{$tickets->statut}}</span></td>
                                                                        @elseif($tickets->statut == "Résolu")
                                                                        <td class="text-start"><span class="badge bg-success"><i class="fa-solid fa-thumbs-up"></i> {{$tickets->statut}}</span></td>
                                                                        @elseif($tickets->statut == "Annulé")
                                                                        <td class="text-start"><span class="badge bg-danger"><i class="fa fa-thumbs-down"></i> {{$tickets->statut}}</span></td>
                                                                        @endif 
                                                                        <td class="text-start fw-bold text-bleu"><i class="fa-solid fa-university"></i> {{$tickets->societe}}</td> 
                                                                        <td class="text-start">{{$tickets->nom_user}}</td>                                             
                                                                        <td class="text-start fw-semibold text-bleu">@if($tickets->source)<i class="fas fa-question-circle"></i>@endif {{$tickets->source}}</td> 
                                                                        <td class="text-start"><a href="detail_ticket?id={{$tickets->id}}&active=13&champ=1-2" class="fw-semibold" wire:navigate><i class="fa fa-calendar-alt"></i> {{date('d-m-Y H:i:s', strtotime($tickets->created_at))}}</a></td>
                                                                        {{-- <td class="text-start">{{date('d-m-Y', strtotime($tickets->date_cloture))}}</td> --}}
                                                                        @if(auth()->user()->societe == "Administration")
                                                                            <td class="taille_icon">
                                                                                @if($confirmer === $tickets->id)                                                               
                                                                                    <a wire:click.prevent="supprimer({{$tickets->id}})" class="btn btn-outline-danger btn-xs bg-danger text-white blink" style="font-size: 8px;" title="Cliquez pour confirmer la suppression" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-trash"> Confirmer?</i></a>
                                                                                @else
                                                                                    <a wire:click.prevent="confirmerDelete({{$tickets->id}})" class="btn-outline-muted btn-xs pointer" title="Cliquez pour supprimer" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-trash"></i></a>
                                                                                @endif
                                                                            </td>
                                                                        @endif 
                                                                    </tr>
                                                                @endforeach  
                                                            </tbody>
                                                        </table>
                                                        <div class="bloc_pagination">{{$ticket->links()}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="activite" class="container-fluid tab-pane fade pas_bordure">
                                        <div class="card shadow-sm">  
                                            <div class="card shadow-sm border border-light-subtle rounded-3 bg-white p-2">    
                                                <h6 class="fw-bold text-bleu mb-3"><i class="fas fa-calendar-alt" style=" color: #393b83;" title="Derniers événements liés"></i> @if($logCount > 1) Activités récentes @else Activité récente @endif ({{$logCount}})</h6>
                                                {{-- <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                                                    <div class="d-flex gap-2 align-items-center">
                                                        <div class="input-group input-group-sm border rounded-2" style="width: 240px; background: #fff;">
                                                            <input type="search" wire:model.live="activite" id="activite" class="form-control border-0 shadow-none bg-transparent" placeholder="Rechercher une activité..." style="font-size: 13px;">
                                                            <button class="btn border-0 text-muted shadow-none d-flex align-items-center" type="button">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 14px; height: 14px;">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                                                </svg>
                                                            </button>
                                                        </div> 
                                                    </div>
                                                </div> --}}
                                                <div class="position-relative custom-scroll pe-2" style="height: calc(100vh - 304px); overflow-y: auto;">  
                                                    <div class="position-absolute border-start" style="left: 17px; top: 10px; bottom: 0; border-color: #dee2e6 !important; border-width: 2px !important; z-index: 1;"></div>
                                                    @foreach($log as $logs)
                                                    <div class="d-flex mb-3 position-relative" style="z-index: 2;">
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 bg-white" style="width: 36px; height: 36px;">
                                                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background-color: #f5e8e8; color: #198754;">
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
    @include('livewire.gestion-ticket.create_ticket')   
</div>

