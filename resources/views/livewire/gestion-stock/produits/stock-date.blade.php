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
                                <!-- ONGLETS -->
                                <div class="card-header d-flex align-items-center justify-content-between border-0 px-1">
                                    <!-- Nav tabs -->
                                    <ul class="nav nav-tabs margin_ajuste border-0" role="tablist">
                                        <li class="nav-item">                                                
                                            <a class="nav-link active" data-bs-toggle="tab" href="#home" title="Liste des produits"><i class="fas fa-cube text-bleu"></i> {{$title_fils}} (<span class="text-vert">{{$mouvCountAfficher}}</span>)</a>                                                
                                        </li>  
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#facture">Activités récentes (<span class="text-vert">{{$logCount}}</span>)</a>
                                        </li>                                             
                                        {{-- <li class="nav-item">
                                            <a class="nav-link pointer" wire:click.prevent="soldeTier()">Haut</a>
                                        </li> --}}
                                    </ul>
                                    <!-- Tab panes -->                        
                                    <div class="d-flex align-items-center justify-content-between gap-1">
                                        <div class="d-flex align-items-center justify-content-between gap-1"> 
                                            {{-- <a href="facturationclt-pdf?code=Fact/191224/a0adc871" class="btn btn-sm btn-default" title="Cliquez pour imprimer" data-toggle="tooltip" target="blank"><i class="fa fa-print"></i>A4</a> --}}
                                        </div>                            
                                        <div class="d-flex align-items-center justify-content-between gap-1">                            
                                            {{-- <button href="#" data-bs-toggle="modal" data-bs-target="#createProduitModal" title="Cliquez pour voir les détails" data-toggle="tooltip" class="btn btn-sm btn-danger ms-auto"><i class="fa fa-plus-circle"></i> Nouveau Produit</button> --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-content position-relative custom-scroll px-3" style="height: calc(100vh - 180px); overflow-y: auto;">
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
                                                                {{-- <h6><a href="{{asset('exporter-stocks')}}" class="btn btn-sm btn-success mt-2 d-none d-md-block">Exporter Excel</a></h6> --}}
                                                                {{-- <h6><a href="#" data-bs-toggle="modal" data-bs-target="#importerProduitsModal" class="btn btn-sm btn-warning mt-2 d-none d-md-block"><i class="fa-solid fa fa-upload"></i>  Importer Excel</a></h6> --}}
                                                                {{-- <a href="{{asset('storage/manuel_users/Excel_Produit_WamsCo.xlsx')}}" download="Excel_Produit_WamsCo" class="btn btn-sm btn-secondary mt-2 d-none d-md-block"><i class="fa-solid fa fa-download"></i>  Télécharger Modèle</a> --}}
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center justify-content-between gap-1">
                                                            <div class="card-headerk d-flex align-items-center justify-content-end gap-1">
                                                                <input type="datetime-local" wire:model.live="date_debut" class="form-control bordure w-auto">
                                                            </div>
                                                            <div class="d-flex justify-content-start">
                                                                @error('date_debut') <span class="text-danger">{{$message}}</span> @enderror 
                                                            </div>

                                                            <div class="">
                                                                <select wire:model.live="parProduit" class="form-control form-select bordure w-auto"> 
                                                                    <option value="">Tous les produits</option>
                                                                    @foreach($listProduit as $listProduits)
                                                                        <option value={{$listProduits->id}}>{{Str::limit($listProduits->nom_produit, 30)}}</option>
                                                                    @endforeach
                                                                </select> 
                                                            </div> 
                                                            <div>
                                                                <select wire:model.live="parEntrepot" class="form-control form-select bordure w-auto"> 
                                                                    <option value="">Tous les entrepôts</option>
                                                                    @foreach($listEntrepot as $listEntrepots)
                                                                        <option value={{$listEntrepots->id}}>{{Str::limit($listEntrepots->nom,30)}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <button class="btn btn-black" wire:click.prevent="trouver()"><i class="fa fa-check"></i></button>
                                                            </div>    
                                                        </div>
                                                    </div>
                                                    <div class="table-responsive border-top">
                                                        <table class="table table-striped table-hover text-nowrap m-0"> 
                                                            <thead>
                                                                <tr>
                                                                    <th class="fond_entete_table pointer">Réf. produit</th> 
                                                                    <th class="fond_entete_table pointer">Poduit</th> 
                                                                    <th class="fond_entete_table text-center pointer">Stock à date</th>
                                                                    <th class="fond_entete_table text-start pointer">Mouvement</th>  
                                                                    <th class="fond_entete_table text-end pointer">Stock actuel</th> 
                                                                </tr>
                                                            </thead>                                       
                                                            <tbody>
                                                                @if($mouvCountAfficher > 0)
                                                                    @foreach($mouvement as $mouvements) 
                                                                        <tr>                                                                     
                                                                            <td class="text-bleu fw-bold"><a href="detail_product?id={{$mouvements->id_produit}}&active=4&champ=1-1&choix=2" wire:navigate class="text-bleu"><i class="fa fa-cube"></i> {{$mouvements->reference}}</a></td>  
                                                                            <td class="text-bleu fw-semibold"><a href="detail_product?id={{$mouvements->id_produit}}&active=4&champ=1-1&choix=2" wire:navigate class="text-bleu"><i class="fas fa-box-open"></i> {{$mouvements->nom_produit}}</a></td>  
                                                                            <td class="text-center text-vert fw-bold">
                                                                                @if($mouvements->stock_a_date > 0)
                                                                                    {{$mouvements->stock_a_date}}                                                                
                                                                                @else
                                                                                    <span class="text-danger">{{$mouvements->stock_a_date}}</span>  
                                                                                @endif
                                                                            </td>         
                                                                            <td class="fw-semibold text-bleu"><a href="mouvements?debut={{date('Y-m-d', strtotime('-1 year'))}}&requete={{$mouvements->reference}}&active=4&champ=3-1&choix=3" wire:navigate class="text-bleu"><i class="fa fa-people-carry"></i> Mouvements <span class="badge bg-secondary">{{$mouvements->nombreFois}}</span></a></td>
                                                                            <td class="text-end text-bleu fw-bold"><a href="detail_product?id={{$mouvements->id_produit}}&active=4&champ=1-1&choix=2" wire:navigate class="text-bleu">
                                                                                    @if($mouvements->stock_actuel > 0)
                                                                                        {{$mouvements->stock_actuel}}                                                                
                                                                                    @else
                                                                                        <span class="text-danger">{{$mouvements->stock_actuel}}</span>  
                                                                                    @endif
                                                                                </a>
                                                                            </td>  
                                                                        </tr>
                                                                    @endforeach
                                                                @else 
                                                                    <tr class="retire">
                                                                        <td colspan="8" class="text-center fw-semibold">
                                                                            <span class="fs-5"><i class="fa fa-people-carry text-bleu"></i> </span> <br>
                                                                            <span class="">Aucun mouvement</span>
                                                                        </td>                                                                
                                                                    </tr>
                                                                @endif	                                                             
                                                            </tbody>
                                                        </table>
                                                        @if($mouvCountAfficher > 0)
                                                            <div class="bloc_pagination">{{$mouvement->links()}}</div> 
                                                        @endif 
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
                                                <div class="position-relative custom-scroll pe-2" style="height: calc(100vh - 230px); overflow-y: auto;">  
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
                                                {{-- <div class="alert mt-3 mb-0 d-flex justify-content-between align-items-center rounded-2 px-3 py-2" style="background-color: #f4f8ff; border: 1px solid #cce0ff;">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#0d6efd" style="width: 18px; height: 18px;">
                                                            <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm8.706-1.442c1.146-.573 2.437.463 2.126 1.732l-1.162 4.646c-.23.916.634 1.56 1.393 1.144a.75.75 0 01.684 1.326c-1.63 1.09-3.7-.068-3.136-1.92l1.162-4.646c.15-.6-.35-1.12-1-.74a.75.75 0 01-.767-1.302zM12 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" />
                                                        </svg>
                                                        <span class="text-dark" style="font-size: 13px;">
                                                            <span class="fw-bold">Voir plus de tiers,</span> 
                                                            <span class="text-muted ms-1">centralisez les informations de tous vos contacts stratégiques.</span>
                                                        </span>
                                                    </div>
                                                    <a href="{{asset('listing-tiers?active=3&champ=3-2')}}" wire:navigate class="text-decoration-none fw-semibold text-primary" style="font-size: 13px;">Voir plus</a>
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


