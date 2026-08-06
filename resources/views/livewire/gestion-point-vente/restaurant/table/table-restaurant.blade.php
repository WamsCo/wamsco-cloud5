<div x-data="{selection: @entangle('selection').defer}" wire:poll.visible.30s>
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
                                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 44px; height: 44px; background-color: #e0f7fa; color: #0dcaf0;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5h16.5a1.5 1.5 0 011.5 1.5v11.25a1.5 1.5 0 01-1.5 1.5H3.75a1.5 1.5 0 01-1.5-1.5V6a1.5 1.5 0 011.5-1.5zm10.5 7.5a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                                    </svg>
                                                </div>
                                                <div class="text-nowrap">
                                                    <span class="d-block fw-bold text-secondary" style="font-size: 11px;">Total transfert</span>
                                                    <span class="d-block fw-semibold text-dark" style="font-size: 13px;">{{number_format($nbreTotalEspaceRestau,0,',',' ')}}</span>
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
                                        </div>
                                    </div>
                                </div>                                    
                                <!-- ONGLETS -->
                                <div class="card-header d-flex align-items-center justify-content-between border-0 px-1">
                                    <!-- Nav tabs -->
                                    <ul class="nav nav-tabs margin_ajuste border-0" role="tablist">
                                        <li class="nav-item">                                                
                                            <a class="nav-link active" data-bs-toggle="tab" href="#home" title="Liste des tieres : Prospects, Clients, Fournisseurs"><i class="fas fa-users text-bleu"></i> {{$title_fils}} (<span class="text-vert">{{$tableCount}}</span>)</a>                                                
                                        </li>  
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#facture">Activités récentes (<span class="text-vert">{{$logCount}}</span>)</a>
                                        </li>   
                                    </ul>
                                    <!-- Tab panes -->                        
                                    <div class="d-flex align-items-center justify-content-between gap-1">
                                        <div class="d-flex align-items-center justify-content-between gap-1"> 
                                        </div>                            
                                        <div class="d-flex align-items-center justify-content-between gap-1">                            
                                            <button class="btn btn-sm btn-danger ms-auto" title="Cliquez pour ajouter" data-toggle="tooltip" data-bs-toggle="modal" data-bs-target="#createTableModal"><i class="fa fa-plus-circle"></i> Nouvelle table</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-content position-relative custom-scroll px-3" style="height: calc(100vh - 256px); overflow-y: auto;">
                                    <div id="home" class="container-fluid tab-pane active pas_bordure">  
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 px-0">   
                                                <div class="card shadow-sm border border-light-subtle rounded-3 bg-white px-0">
                                                    <div class="d-flex align-items-center justify-content-between gap-3 pt-2 px-2">
                                                        <div class="d-flex align-items-center justify-content-between gap-1 mb-1">
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
                                                            <div>
                                                                <label for="query" class="sr-only">Recherche</label>
                                                                <input type="search" wire:model.live="query" id="query" class="form-control bordure" placeholder="Rechercher table">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="table-responsive border-top">
                                                        <table class="table table-striped table-hover text-nowrap m-0"> 
                                                            <thead>
                                                                <tr>
                                                                    <th class="fond_entete_table pointer" wire:click="setOrderField('nom_table')">Table <i class="fa fa-arrow-down-short-wide"></i></th>   
                                                                    <th class="fond_entete_table pointer" wire:click="setOrderField('reference')">Référence <i class="fa fa-arrow-down-short-wide"></i></th>   
                                                                    <th class="fond_entete_table pointer" wire:click="setOrderField('nom_espace')">Espace <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                                    <th class="fond_entete_table pointer" wire:click="setOrderField('utiliser')">Utiliser <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                                    <th class="fond_entete_table pointer" wire:click="setOrderField('ref_session_restau')">Session <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                                    @if($activer_ecran_cuisine > 0)
                                                                        <th class="fond_entete_table pointer text-start" wire:click="setOrderField('statut')">Statut cuisine <i class="fa fa-arrow-down-short-wide"></i></th>
                                                                        <th class="fond_entete_table pointer text-start" wire:click="setOrderField('lieu_consommation')">Consommation <i class="fa fa-arrow-down-short-wide"></i></th>
                                                                        <th class="fond_entete_table pointer text-start" wire:click="setOrderField('date_consommation')">Rendez-vous <i class="fa fa-arrow-down-short-wide"></i></th>
                                                                        <th class="fond_entete_table pointer text-start" wire:click="setOrderField('adresse_livraison')">Livraison <i class="fa fa-arrow-down-short-wide"></i></th>
                                                                    @endif
                                                                    <th class="fond_entete_table pointer" wire:click="setOrderField('description')">Description <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                                    <th class="fond_entete_table pointer" wire:click="setOrderField('non_caissiere')">Par <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                                    {{-- <th class="fond_entete_table pointer" wire:click="setOrderField('nom_user')">Créer par <i class="fa fa-arrow-down-short-wide"></i></th>     --}}
                                                                    <th class="fond_entete_table pointer text-start" wire:click="setOrderField('updated_at')">Date <i class="fa fa-arrow-down-short-wide"></i></th>
                                                                    <th class="fond_entete_table pointer text-end"></th>
                                                                    <th class="fond_entete_table pointer text-end"></th> 
                                                                </tr>
                                                            </thead>                                       
                                                            <tbody>
                                                                @foreach($table as $tables) 
                                                                    <tr>
                                                                        <td class="fw-bold pointer text-bleu" wire:click.prevent="edit('{{$tables->id}}')" data-bs-toggle="modal" data-bs-target="#updateTableModal" title="Cliquez pour voir les détails" data-toggle="tooltip"><i class="fa fa-table text-danger" aria-hidden="true"></i> {{$tables->nom_table}}</td> 
                                                                        <td class="">{{$tables->reference}}</td>
                                                                        <td style="font-weight: 600; color:#707070;" class="fw-bold text-start">{{$tables->nom_espace}}</td>
                                                                        <td style="font-weight: 600; color:#707070;" class="fw-bold text-start">
                                                                            @if($tables->utiliser == "Oui")
                                                                                <span class="text-danger"><i class="fa fa-refresh fa-spin"></i> Occupée</span>
                                                                            @else 
                                                                                <span class="text-green"><i class="fa fa-check-circle"></i> Disponible</span>
                                                                            @endif
                                                                        </td>
                                                                        <td style="font-weight: 600; color:#6b2a65;" class="fw-bold text-start">{{$tables->ref_session_restau}}</td>
                                                                        @if($activer_ecran_cuisine > 0)  
                                                                            @if($tables->statut == "Terminer")
                                                                                <td class="fw-semibold" style="color: #08a316;"><i class="fa fa-thumbs-up"></i> {{$tables->statut}}</td>
                                                                            @elseif($tables->statut == "A préparer")
                                                                                <td class="" style="color: #ff5a00;"><i class="fa fa-spinner fa-spin"></i> {{$tables->statut}}</td>
                                                                            @elseif($tables->statut == "En cours")
                                                                                <td class="" style="color: #456bf2;"><i class="fa fa-hand"></i> {{$tables->statut}}</td>
                                                                            @else
                                                                                <td class=""></td>
                                                                            @endif                                     
                                                                            <td class="fw-semibold" style="color: @if($tables->lieu_consommation == "A emporter") #3b0da6; @elseif($tables->lieu_consommation == "Livraison") #0a9682; @else #a63c96; @endif">@if($tables->lieu_consommation)<i class="fa fa-check-circle"></i>@endif {{$tables->lieu_consommation}}</td>                                  
                                                                            <td class="fw-semibold" style="color: @if($tables->lieu_consommation == "A emporter") #3b0da6; @elseif($tables->lieu_consommation == "Livraison") #0a9682; @else #a63c96; @endif">@if(date('d-m-Y H:i', strtotime($tables->date_consommation)) != "01-01-1970 01:00")<i class="fa fa-calendar-alt"></i> {{date('d-m-Y H:i', strtotime($tables->date_consommation))}}@endif</td>                                  
                                                                            <td class="fw-semibold" style="color: #0a9682;">@if($tables->lieu_consommation == "Livraison")<i class="fa fa-location"></i>@endif {{$tables->adresse_livraison}}</td>                                  
                                                                        @endif   
                                                                        <td class="">{{Str::limit($tables->description, 52)}}</td>
                                                                        <td class="pointer"><a href="detail_user?id={{$tables->id_caissiere}}&active=12&champ=1-1" wire:navigate class="text-muted fw-semibold">@if($tables->non_caissiere)<i class="fa fa-user-circle" aria-hidden="true"></i>@endif {{Str::limit($tables->non_caissiere, 52)}}</a></td> 
                                                                        {{-- <td class="fw-semibold pointer"><i class="fa fa-user-circle text-bleu" aria-hidden="true"></i> {{Str::limit($tables->nom_user, 52)}}</td> --}}
                                                                        <td class="text-muted fw-semibold">{{date('d-m-Y H:i:s', strtotime($tables->updated_at))}}</td>
                                                                        <td class="text-end pointer" wire:click.prevent="edit('{{$tables->id}}')" data-bs-toggle="modal" data-bs-target="#updateTableModal" title="Cliquez pour voir les détails" data-toggle="tooltip"><i class="fa fa-pencil btn-outline-muted"></i></a></td>
                                                                        <td class="taille_icon text-end">
                                                                            @if($confirmer === $tables->id)                                                               
                                                                                <a wire:click.prevent="supprimer({{$tables->id}})" class="btn-outline-danger btn-xs bg-danger text-white" style="font-size:8px;" style="font-size: 8px;"  title="Cliquez pour confirmer la suppression" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-trash"> Confirmer ?</i></a>
                                                                            @else
                                                                                <a wire:click.prevent="confirmerDelete({{$tables->id}})" class="btn-outline-muted btn-xs"  title="Cliquez pour supprimer" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-trash"></i></a>
                                                                            @endif
                                                                        </td> 
                                                                    </tr>
                                                                @endforeach 
                                                            </tbody>
                                                        </table>
                                                        <div class="bloc_pagination">{{$table->links()}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="facture" class="container-fluid tab-pane fade pas_bordure">
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
    @include('livewire.gestion-point-vente.restaurant.table.creation_table')
    @include('livewire.gestion-point-vente.restaurant.table.update_table')    
</div>


