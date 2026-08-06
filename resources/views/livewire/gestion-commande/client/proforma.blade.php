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
                            <!-- CONTENU -->
                            <div class="col-lg-12">
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
                                                    <span class="d-block fw-bold text-secondary" style="font-size: 11px;">Total proforma</span>
                                                    <span class="d-block fw-semibold text-dark" style="font-size: 13px;">{{$nbreTotalProforma}}</span>
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
                                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 44px; height: 44px; background-color: #e0f7fa; color: #0dcaf0;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5h16.5a1.5 1.5 0 011.5 1.5v11.25a1.5 1.5 0 01-1.5 1.5H3.75a1.5 1.5 0 01-1.5-1.5V6a1.5 1.5 0 011.5-1.5zm10.5 7.5a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                                    </svg>
                                                </div>
                                                <div class="text-nowrap">
                                                    <span class="d-block fw-bold text-secondary" style="font-size: 11px;">Montant TTC</span>
                                                    <span class="d-block fw-bold text-dark mt-1" style="font-size: 14px;">{{number_format($montantTTC_all,0,',',' ')}} <span style="font-size: 10px;">{{$this->devise}}</span></span>
                                                </div>
                                            </div>

                                            <div class="d-flex align-items-center px-3 border-end flex-fill">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 44px; height: 44px; background-color: #fff3e0; color: #fd7e14;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                    </svg>
                                                </div>
                                                <div class="text-nowrap">
                                                    <span class="fw-bold text-secondary" style="font-size: 11px;">Reçu</span>
                                                    <span class="d-block fw-bold text-dark mt-1" style="font-size: 14px;">{{number_format($montantTrecu_all,0,',',' ')}} <span style="font-size: 10px;">{{$this->devise}}</span></span>
                                                </div>
                                            </div>                                            
                                            
                                            @if($autoriser == 1)
                                                <div class="d-flex align-items-center px-3 border-end flex-fill">
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 44px; height: 44px; background-color: #fff8e1; color: #ffc107;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
                                                        </svg>
                                                    </div>
                                                    <div class="text-nowrap">
                                                        <span class="d-block fw-bold text-secondary" style="font-size: 11px;">Marge</span>
                                                        <span class="d-block fw-bold text-dark mt-1" style="font-size: 14px;">{{number_format($montantTmarge_All,0,',',' ')}} <span style="font-size: 10px;">{{$this->devise}}</span></span>
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
                                                    <span class="d-block fw-bold text-secondary" style="font-size: 11px;">Créance</span>
                                                    <span class="d-block fw-bold text-dark mt-1" style="font-size: 14px;">{{number_format($montantCreance_all,0,',',' ')}} <span style="font-size: 10px;">{{$this->devise}}</span></span>
                                                </div>
                                            </div>

                                            {{-- <div class="d-flex align-items-center px-3 border-end flex-fill">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 44px; height: 44px; background-color: #f3e5f5; color: #6f42c1;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                                    </svg>
                                                </div>
                                                <div class="text-nowrap">
                                                    <span class="d-block fw-bold text-secondary" style="font-size: 11px;">Phase du cycle de vie</span>
                                                    <span class="d-block fw-bold text-dark mt-1" style="font-size: 13px;">{{ $tiers->type_tiers }}</span>
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
                                            <a class="nav-link active" data-bs-toggle="tab" href="#home"><i class="fas fa-file-invoice text-bleu"></i> {{$title_fils}} (<span class="text-vert">{{$profClientCount}}</span>)</a>                                                
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
                                            <button class="btn btn-sm btn-danger ms-auto" title="Cliquez pour enregistrer" data-toggle="tooltip" wire:click.prevent="store()"><i class="fa fa-plus-circle"></i> Nouvelle proforma</button>                        
                                            {{-- <button class="btn btn-sm btn-default new_color" @if($this->ids) wire:click.prevent="precedant({{$this->ids}})" @else wire:click.prevent="precedant({{$this->id}})" @endif title="Précédant" data-toggle="tooltip"><i class="fa fa-chevron-left"></i></button> --}}
                                            {{-- <a href="#" class="btn btn-sm btn-default new_color" @if($this->ids) wire:click.prevent="suivant({{$this->ids}})" @else wire:click.prevent="suivant({{$this->id}})" @endif title="Suivant" data-toggle="tooltip"><i class="fa fa-chevron-right"></i></a> --}}
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
                                                                <h6><a href="{{asset('#')}}" class="btn btn-sm btn-success mt-2 d-none d-md-block"><i class="fa-solid fa-file-export"></i> Exporter Excel</a></h6>
                                                                {{-- <h6><a href="#" data-bs-toggle="modal" data-bs-target="#importerTiersModal" class="btn btn-sm btn-warning mt-2 d-none d-md-block"><i class="fa-solid fa fa-upload"></i>  Importer Excel</a></h6> --}}
                                                                {{-- <h6><a href="{{asset('storage/manuel_users/Excel_Tier_WamsCo.xlsx')}}" download="Excel_Tier_WamsCo" class="btn btn-sm btn-secondary mt-2 d-none d-md-block"><i class="fa-solid fa fa-download"></i>  Télécharger Modèle</a></h6> --}}
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center justify-content-between gap-1">
                                                            <div class="card-headerk d-flex align-items-center justify-content-end gap-1">
                                                                <input type="date" wire:model.live="date_debut" class="form-control bordure w-auto">
                                                                <input type="date" wire:model.live="date_fin" class="form-control bordure w-auto">
                                                                {{-- <button class="btn btn-black bordure" title="Cliquez pour valider" wire:click.prevent="valider()"><i class="fa fa-check"></i></button>  --}}
                                                            </div>
                                                            <div class="">
                                                                <select wire:model.live="parEtat" class="form-control form-select bordure w-auto"> 
                                                                    <option value="">Tous les statuts</option>
                                                                    <option value="Validée">Validée</option>
                                                                    <option value="Brouillon">Brouillon</option>
                                                                </select> 
                                                            </div>                            
                                                            <div>
                                                                <label for="query" class="sr-only">Recherche</label>
                                                                <input type="search" wire:model.live="query" id="query" class="form-control bordure" placeholder="Rechercher client">
                                                            </div>
                                                            <div>
                                                                <label for="parCmd" class="sr-only">Recherche</label>
                                                                <input type="search" wire:model.live="parCmd" id="parCmd" class="form-control bordure" placeholder="Rechercher référence">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="table-responsive border-top">
                                                        <table class="table table-striped table-hover text-nowrap m-0"> 
                                                            <thead>
                                                                <tr>
                                                                    <th class="fond_entete_table pointer" wire:click="setOrderField('code_proforma')">Référence <i class="fa fa-arrow-down-short-wide"></i></th>   
                                                                    <th class="fond_entete_table pointer" wire:click="setOrderField('nom_client')">Client <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                                    <th class="fond_entete_table pointer" wire:click="setOrderField('telephone')">Téléphone <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                                    <th class="fond_entete_table pointer" wire:click="setOrderField('date_proforma')">Proforma <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                                    <th class="fond_entete_table pointer" wire:click="setOrderField('date_livraison')">Livraison <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                                    <th class="fond_entete_table pointer" wire:click="setOrderField('mode_reglement')">Règlement <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                                    <th class="fond_entete_table pointer text-end" wire:click="setOrderField('montant_ttc')">Montant TTC <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                                    <th class="fond_entete_table pointer text-end" wire:click="setOrderField('montant_recu')">Reçu <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                                    <th class="fond_entete_table pointer text-end" wire:click="setOrderField('reste_a_percevoir')">Créance <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                                    @if($autoriser == 1)
                                                                        <th class="fond_entete_table pointer text-end" wire:click="setOrderField('marge')">Marge <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                                    @endif
                                                                    <th class="fond_entete_table pointer" wire:click="setOrderField('etat')">Etat <i class="fa fa-arrow-down-short-wide"></i></th>        
                                                                    <th class="fond_entete_table pointer" wire:click="setOrderField('code_commande')">Commande <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                                    <th class="fond_entete_table pointer" wire:click="setOrderField('nom_user')">Auteur <i class="fa fa-arrow-down-short-wide"></i></th>
                                                                    <th class="fond_entete_table pointer" wire:click="setOrderField('updated_at')">Date modif. <i class="fa fa-arrow-down-short-wide"></i></th>
                                                                    <th class="fond_entete_table pointer text-end"></th> 
                                                                </tr>
                                                            </thead>                                       
                                                            <tbody>
                                                                @foreach($profor_client as $profor_clients) 
                                                                    <tr>
                                                                        <td class="fw-semibold pointer"><a href="nouveau_prof_clt?id={{$profor_clients->id}}&ref={{$profor_clients->code_proforma}}&active=6&champ=1-1&choix=1" wire:navigate><i class="fas fa-file-invoice text-danger" aria-hidden="true"></i> {{$profor_clients->code_proforma}}</a></td> 
                                                                        <td class="fw-semibold pointer"><a href="detail_tier?id={{$profor_clients->id_client}}&ref={{$profor_clients->code_proforma}}" wire:navigate class="text-bleu">@if($profor_clients->nom_client) <i class="fa fa-user-circle"></i> @endif {{Str::limit($profor_clients->nom_client, 52)}}</a></td>                                                                      
                                                                        <td class="fw-semibold text-start">{{$profor_clients->telephone}}</td>
                                                                        <td class="">{{date('d-m-Y', strtotime($profor_clients->date_proforma))}}</td>
                                                                        <td class="">{{date('d-m-Y', strtotime($profor_clients->date_livraison))}}</td>
                                                                        <td class="">{{$profor_clients->mode_reglement}}</td>
                                                                        <td class="fw-semibold text-end">{{number_format($profor_clients->montant_ttc,0,',',' ')}}</td>
                                                                        <td class="fw-semibold text-end">{{number_format($profor_clients->montant_recu,0,',',' ')}}</td>
                                                                        <td class="fw-semibold text-end">{{number_format($profor_clients->reste_a_percevoir,0,',',' ')}}</td>
                                                                        @if($autoriser == 1)
                                                                            <td class="fw-semibold text-end">{{number_format($profor_clients->marge,0,',',' ')}}</td>
                                                                        @endif
                                                                        <td class="text-start">
                                                                            @if($profor_clients->etat == "Brouillon")
                                                                                <span class="badge bg-secondary" title="Commande {{$profor_clients->etat}}"><i class="fa fa-box"></i> {{$profor_clients->etat}}</span>
                                                                            @elseif($profor_clients->etat == "Impayée")
                                                                                <span class="badge bg-danger" title="Commande {{$profor_clients->etat}}"><i class="fa fa-times-circle"></i> {{$profor_clients->etat}}</span>
                                                                            @elseif($profor_clients->etat == "Payée")
                                                                                <span class="badge bg-success" title="Commande {{$profor_clients->etat}}"><i class="fa fa-check-circle"></i> {{$profor_clients->etat}}</span>
                                                                            @elseif($profor_clients->etat == "Validée")
                                                                                <span class="badge bg-success" title="Commande {{$profor_clients->etat}}"><i class="fa fa-check"></i> {{$profor_clients->etat}}</span>
                                                                            @endif                                                 
                                                                        </td>  
                                                                        <td class="fw-semibold">
                                                                            @if(!empty($profor_clients->code_commande))
                                                                                <a href="#" class="d-none d-md-block" wire:click.prevent="detailCmd({{$profor_clients->id_commande_client_entete}},'{{$profor_clients->code_commande}}')"><span class="fw-semibold text-bleu" title="Cliquez pour voir les details facture client">@if($profor_clients->code_commande) &nbsp;» @endif {{$profor_clients->code_commande}}</span></a>
                                                                            @else
                                                                                <span class="badge bg-warning" title="Commande non créée"><i class="fas fa-file-invoice-dollar"></i> Non créée</span>
                                                                            @endif
                                                                        </td>
                                                                        <td class="pointer"><a href="detail_user?id={{$profor_clients->user_id}}&active=12&champ=1-1" wire:navigate><i class="fas fa-user-circle text-bleu" aria-hidden="true"></i> {{$profor_clients->nom_user}}</a></td>                               
                                                                        <td class="">{{date('d-m-Y H:i:s', strtotime($profor_clients->updated_at))}}</td>
                                                                        <td class="text-end"><a href="nouveau_prof_clt?id={{$profor_clients->id}}&ref={{$profor_clients->code_proforma}}&active=6&champ=1-1&choix=1" wire:navigate class="btn-outline-muted"  title="Cliquez pour voir ou modifier" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-eye"></i></a></td>
                                                                        {{-- <td class="taille_icon text-end">
                                                                            @if($confirmer === $profor_clients->id)                                                               
                                                                                <a wire:click.prevent="supprimer({{$profor_clients->id}},'{{$profor_clients->code_commande}}')" class="btn btn-outline-danger btn-xs bg-danger text-white blink" style="font-size:8px;"  title="Cliquez pour confirmer la suppression" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-trash"> Confirmer ?</i></a>
                                                                            @else
                                                                                <a wire:click.prevent="confirmerDelete({{$profor_clients->id}})" class="btn btn-outline-muted btn-xs"  title="Cliquez pour supprimer" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-trash"></i></a>
                                                                            @endif
                                                                        </td>  --}}
                                                                    </tr>
                                                                @endforeach 
                                                            </tbody>
                                                            <tr>
                                                                <td class="text-muted fw-semibold">Total <i class="fas fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" style="vertical-align: middle; cursor: help" title="Total pour cette page"></i></td>
                                                                <td colspan="6" class="text-end fw-semibold" style="color:#006666">{{number_format($montantTTC,0,',',' ')}}</td>
                                                                <td class="text-end fw-semibold" style="color:#006666">{{number_format($montantTrecu,0,',',' ')}}</td>
                                                                <td class="text-end fw-semibold" style="color:#006666">{{number_format($montantTreste_Percevoir,0,',',' ')}}</td>
                                                                @if($autoriser == 1)
                                                                    <td class="text-end fw-semibold" style="color:#006666">{{number_format($montantTmarge,0,',',' ')}}</td>
                                                                @endif
                                                                <td colspan="5"></td>
                                                            </tr>
                                                        </table>
                                                        <div class="bloc_pagination">{{$profor_client->links()}}</div>
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
</div>




