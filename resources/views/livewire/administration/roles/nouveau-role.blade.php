
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
                                                    <span class="fa fa-cog fa-spin" style=" color: #ff5252;" title="No photo"></span>
                                                </div>
                                            </div>
                                            <div class="ms-2">
                                                <h4 class="mb-0 fw-bold" title="{{$title_fils}}">{{Str::limit($title_fils.'', 42) ?? 'Inconu(e)'}}</h4>
                                                <div class="text-primary fw-bold">{{ $this->societe }}</div>
                                                <span class="badge bg-success py-0" title="Activer">Activer</span>
                                                {{-- <br><small class="text-muted">REF-001</small><br> --}}
                                            </div>                                                   
                                        </div>                                                
                                    </div>
                                    <hr class="my-1">
                                    <div class="d-flex gap-2 mb-1">
                                        <button href="{{asset('role_privillege?active=12&champ=1-2')}}" wire:navigate class="btn btn-sm btn-outline-success flex-fill fw-semibold ms-auto"><i class="fa fa-close"></i> Fermer</button>
                                        <button wire:click.prevent="store()" title="Cliquez pour enregistrer" data-toggle="tooltip" class="btn btn-sm btn-outline-secondary flex-fill fw-semibold ms-auto"><i class="fa fa-save"></i> Enregistrer</button>
                                    </div> 
                                    <h5 class="fw-bold mb-3 mt-3" title="">Rôle & privillèges </h5>
                                    <div class="table-responsivek">
                                        <div class="row">
                                            <div class="col-sm-12 col-12">
                                                <div class="row mb-2" style="overflow: auto;">                                        
                                                    <div class="col-sm-12 col-12">                                            
                                                        <div class="row mb-2">
                                                            <label for="nom" class="col-md-12 col-sm-12 fw-bold col-form-label">Rôle</label>
                                                            <div class="col-md-12 col-sm-12">
                                                                <input type="text" wire:model="nom"  placeholder="Rôle » Ex: Comptable" class="form-control bordure @error('nom') is-invalid @enderror" id="nom">
                                                            </div>
                                                            <div class="d-flex justify-content-start">
                                                                @error('nom') <span class="text-danger">{{ $message }}</span> @enderror 
                                                            </div>
                                                        </div>                                            
                                                    </div>
                                                    <div class="col-sm-12 col-12">                                            
                                                        <div class="row mb-2">
                                                            <label for="description" class="col-sm-12 col-form-label">Description</label>
                                                            <div class="col-sm-12">                                                        
                                                                <textarea rows="2" wire:model="description" class="form-control bordure @error('description') is-invalid @enderror" id="description" placeholder="Description du rôle..."></textarea>
                                                            </div>
                                                            <div class="d-flex justify-content-start">
                                                                @error('description') <span class="text-danger">{{ $message }}</span> @enderror 
                                                            </div>
                                                        </div>                                      
                                                    </div>
                                                    <div class="col-sm-12 col-12">
                                                        <div class="row mb-2">
                                                            <label for="societe" class="col-sm-12 col-form-label">Société</label>
                                                            <div class="col-sm-12">                                                        
                                                                <select id="societe" wire:model="societe" class="form-control form-select bordure w-100 @error('societe') is-invalid @enderror">
                                                                    {{-- <option value=""></option>    --}}
                                                                    @foreach ($entite as $entites )
                                                                        <option value="{{$entites->enseigne}}">{{$entites->enseigne}}</option> 
                                                                    @endforeach  
                                                                </select> 
                                                            </div>                                                            
                                                            <div class="d-flex justify-content-start">
                                                                @error('societe') <span class="text-danger">{{ $message }}</span> @enderror 
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- <table class="table table-borderless table text-nowrap m-0">
                                            <tr>
                                                <td class="text-muted fw-semibold">Raison sociale <i class="fas fa-info-circle" title="Nom officiel d'une entreprise" data-bs-toggle="tooltip" data-bs-placement="top" style=" vertical-align: middle; cursor: help"></i></td>
                                                <td class="fw-semibold text-vert"></td> 
                                            </tr>
                                            <tr>
                                                <td class="text-muted fw-semibold">Rôle</td>
                                                <td class="text-primary fw-semibold"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted fw-semibold">Téléphone</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted fw-semibold">Email</td>
                                                <td></td>
                                            </tr>
                                            
                                        </table> --}}
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
                                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 44px; height: 44px; background-color: #f3e5f5; color: #6f42c1;">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                                </svg>
                                            </div>
                                            <div class="text-nowrap">
                                                <span class="d-block fw-bold text-secondary" style="font-size: 11px;">Créé par</span>
                                                <span class="d-block fw-bold text-dark mt-1" style="font-size: 13px;">{{Str::limit($this->auteur, 42)}}</span>
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
                                                {{-- <span class="d-block fw-semibold text-dark" style="font-size: 13px;">{{ $this->created_at->format('d/m/Y : H:i:s') }}</span> --}}
                                                <span class="d-block text-muted" style="font-size: 11px; margin-top: -2px;">GMT+1</span>
                                            </div>
                                        </div>

                                        {{-- <div class="d-flex align-items-center px-3 border-end flex-fill">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 44px; height: 44px; background-color: #fff3e0; color: #fd7e14;">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                </svg>
                                            </div>
                                            <div class="text-nowrap">
                                                <span class="fw-bold text-secondary" style="font-size: 11px;">Factures</span>
                                                <span class="d-block fw-bold text-dark mt-1" style="font-size: 14px;">{{$factClientEnteteCount}}</span>
                                            </div>
                                        </div> --}}

                                        {{-- <div class="d-flex align-items-center px-3 border-end flex-fill">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 44px; height: 44px; background-color: #e0f7fa; color: #0dcaf0;">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5h16.5a1.5 1.5 0 011.5 1.5v11.25a1.5 1.5 0 01-1.5 1.5H3.75a1.5 1.5 0 01-1.5-1.5V6a1.5 1.5 0 011.5-1.5zm10.5 7.5a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                                </svg>
                                            </div>
                                            <div class="text-nowrap">
                                                <span class="d-block fw-bold text-secondary" style="font-size: 11px;">Montant TTC</span>
                                                <span class="d-block fw-bold text-dark mt-1" style="font-size: 14px;">{{number_format($factClientEnteteTTC,0,',',' ')}} <span style="font-size: 10px;">{{$this->devise}}</span></span>
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
                            <div class="card-header d-flex align-items-center justify-content-between border-0 px-0">
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs margin_ajuste border-0" role="tablist">
                                    <li class="nav-item">                                                
                                        <a class="nav-link active" data-bs-toggle="tab" href="#home">Vue d'ensemble</a>                                                
                                    </li>                                        
                                    <li class="nav-item">                                                
                                        <a class="nav-link" data-bs-toggle="tab" href="#activite">Activités récentes</a>                                                
                                    </li>      
                                </ul>
                                <!-- Tab panes -->                        
                                <div class="d-flex align-items-center justify-content-between gap-1">
                                    <div class="d-flex align-items-center justify-content-between gap-1"> 
                                        {{-- <a href="facturationcltkk-pdf?code=" class="btn btn-sm btn-default" title="Cliquez pour imprimer" data-toggle="tooltip" style="color: #161a1d;"><i class="fa fa-print"></i>Ticket</a> --}}
                                        {{-- <a href="facturationclt-pdf?code=Fact/191224/a0adc871" class="btn btn-sm btn-default" title="Cliquez pour imprimer" data-toggle="tooltip" target="blank"><i class="fa fa-print"></i>A4</a> --}}
                                    </div>                            
                                    <div class="d-flex align-items-center justify-content-between gap-1">                            
                                        <a href="{{asset('role_privillege?active=12&champ=1-2')}}" wire:navigate class="btn btn-sm btn-bleu d-none d-md-block ms-auto"><i class="fa fa-chevron-left"></i> Retour liste</a>
                                        {{-- <button class="btn btn-sm btn-default" @if($this->ids) wire:click.prevent="precedant({{$this->ids}})" @else wire:click.prevent="precedant({{$this->id}})" @endif title="Précédant" data-toggle="tooltip"><i class="fa fa-chevron-left"></i></button> --}}
                                        {{-- <a href="#" class="btn btn-sm btn-default" @if($this->ids) wire:click.prevent="suivant({{$this->ids}})" @else wire:click.prevent="suivant({{$this->id}})" @endif title="Suivant" data-toggle="tooltip"><i class="fa fa-chevron-right"></i></a> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="tab-content">
                                <div id="home" class="container-fluid tab-pane active pas_bordure">  
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12">   
                                            <div class="card shadow-sm border border-light-subtle rounded-3 bg-white p-2">
                                                <h6 class="fw-bold text-bleu mb-1"><i class="fa fa-cog fa-spin" style=" color: #393b83;" title="Souscription"></i> {{$title_fils}}</h6>
                                                <div class="position-relative custom-scroll pe-3" style="height: calc(100vh - 290px); overflow-y: auto;">  
                                                    <div class="hauteur_ecran">   
                                                        @foreach($entite_mod as $entite_mods)
                                                            <div class="row">
                                                                @if($entite_mods->mod_gestion_tier == 1 && $dateJour <= $entite_mods->validite_mod)                                                                                     
                                                                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                                                                        <div class="bloc_role rounded ml-1">
                                                                            <h6 class="text-center text-bleu pt-2 pb-2" style="background: #d9d9d9 !important;">Gestion des Tiers | CRM</h6>
                                                                            <div class="titre_role">Tiers</div>
                                                                            <hr class="trait">
                                                                            <div class="roles responsives">
                                                                                <div class="bloc_partiel_role"> 
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="consultier"  wire:model="consulter_tier">
                                                                                        <label class="form-check-label" for="consultier">Consulter les tiers</label>
                                                                                    </div>
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="ctier"  wire:model="creer_tier">
                                                                                        <label class="form-check-label" for="ctier">Créer un tier</label>
                                                                                    </div>
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="mtier"  wire:model="modifier_tier">
                                                                                        <label class="form-check-label" for="mtier">Modifier un tier</label>
                                                                                    </div>
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="suptier"  wire:model="supprimer_tier">
                                                                                        <label class="form-check-label" for="suptier">Supprimer un tier</label>
                                                                                    </div>
                                                                                </div>
                                                                                @if($entite_mods->mod_crm == 1 && $dateJour <= $entite_mods->validite_mod)
                                                                                    <div class="titre_role">Opportunité CRM</div>
                                                                                    <hr class="trait">
                                                                                    <div class="bloc_partiel_role">
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="consoppo"  wire:model="consulter_opportunite">
                                                                                            <label class="form-check-label" for="consoppo">Consulter les opportunités</label>
                                                                                        </div>
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="coppo"  wire:model="creer_opportunite">
                                                                                            <label class="form-check-label" for="coppo">Créer une opportunité</label>
                                                                                        </div>
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="detoppo"  wire:model="detail_opportunite">
                                                                                            <label class="form-check-label" for="detoppo">Voir les détails opportunité</label>
                                                                                        </div>                                                                      
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="modoppo"  wire:model="modifier_opportunite">
                                                                                            <label class="form-check-label" for="modoppo">Modifier une opportunité</label>
                                                                                        </div>
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="supoppo"  wire:model="supprimer_opportunite">
                                                                                            <label class="form-check-label" for="supoppo">Supprimer une opportunité</label>
                                                                                        </div>                                                                    
                                                                                    </div>                                                                    
                                                                                    <div class="titre_role">Etape CRM</div>
                                                                                    <hr class="trait">
                                                                                    <div class="bloc_partiel_role">
                                                                                        <div class="form-check form-switch mb-3">
                                                                                                <input class="form-check-input" type="checkbox" id="consetap"  wire:model="consulter_etape">
                                                                                                <label class="form-check-label" for="consetap">Consulter les étapes</label>
                                                                                        </div>
                                                                                        <div class="form-check form-switch mb-3">
                                                                                                <input class="form-check-input" type="checkbox" id="creeretap"  wire:model="creer_etape">
                                                                                                <label class="form-check-label" for="creeretap">Créer étapes</label>
                                                                                        </div>                                                                      
                                                                                        <div class="form-check form-switch mb-3">
                                                                                                <input class="form-check-input" type="checkbox" id="modetap"  wire:model="modifier_etape">
                                                                                                <label class="form-check-label" for="modetap">Modifier étapes</label>
                                                                                        </div>
                                                                                        <div class="form-check form-switch mb-3">
                                                                                                <input class="form-check-input" type="checkbox" id="suppetap"  wire:model="supprimer_etape">
                                                                                                <label class="form-check-label" for="suppetap">Supprimer étapes</label>
                                                                                        </div>                                                                    
                                                                                    </div> 
                                                                                @endif
                                                                            </div>													  		
                                                                        </div>
                                                                    </div>
                                                                @endif                                
                                                                @if($entite_mods->mod_gestion_stock == 1 && $dateJour <= $entite_mods->validite_mod)
                                                                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                                                                        <div class="bloc_role rounded">
                                                                            <h6 class="text-center text-bleu pt-2 pb-2" style="background: #d9d9d9 !important;">Gestion de Stock</h6>
                                                                            <div class="titre_role">Produit</div>
                                                                            <hr class="trait">
                                                                            <div class="roles responsives">
                                                                                <div class="bloc_partiel_role">
                                                                                    {{-- <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="tbstock2"  wire:model="tablobord_stock">
                                                                                        <label class="form-check-label" for="tbstock2">Tableau bord</label>
                                                                                    </div> --}}
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="consprod"  wire:model="consulter_produit">
                                                                                        <label class="form-check-label" for="consprod">Consulter les produits / stocks</label>
                                                                                    </div>
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="cprod"  wire:model="creer_produit">
                                                                                        <label class="form-check-label" for="cprod">Créer produit</label>
                                                                                    </div>    
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="mprod"  wire:model="modifier_produit">
                                                                                        <label class="form-check-label" for="mprod">Modifier produit</label>
                                                                                    </div>
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="sprod"  wire:model="supprimer_produit">
                                                                                        <label class="form-check-label" for="sprod">Supprimer produit</label>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="titre_role">Catégorie</div>
                                                                                <hr class="trait">
                                                                                <div class="bloc_partiel_role">	
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="conscat"  wire:model="consulter_categorie">
                                                                                        <label class="form-check-label" for="conscat">Consulter les catégories</label>
                                                                                    </div>
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="ccat"  wire:model="creer_categorie">
                                                                                        <label class="form-check-label" for="ccat">Créer catégorie</label>
                                                                                    </div>
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="mcat"  wire:model="modifier_categorie">
                                                                                        <label class="form-check-label" for="mcat">Modifier catégorie</label>
                                                                                    </div>    
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="scat"  wire:model="supprimer_categorie">
                                                                                        <label class="form-check-label" for="scat">Supprimer catégorie</label>
                                                                                    </div> 
                                                                                </div>
                                                                                <div class="titre_role">Magasin</div>
                                                                                <hr class="trait">
                                                                                <div class="bloc_partiel_role">	
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="mstock"  wire:model="mouvement_stock">
                                                                                        <label class="form-check-label" for="mstock">Mouvement de stock</label>
                                                                                    </div> 
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="corstock"  wire:model="correction_stock">
                                                                                        <label class="form-check-label" for="corstock">Correction du stock</label>
                                                                                    </div> 	
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="consentr"  wire:model="consulter_entrepot">
                                                                                        <label class="form-check-label" for="consentr">Consulter les magasins</label>
                                                                                    </div>
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="centr"  wire:model="creer_entrepot">
                                                                                        <label class="form-check-label" for="centr">Créer magasin</label>
                                                                                    </div>
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="mentr"  wire:model="modifier_entrepot">
                                                                                        <label class="form-check-label" for="mentr">Modifier magasin</label>
                                                                                    </div>    
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="sentr"  wire:model="supprimer_entrepot">
                                                                                        <label class="form-check-label" for="sentr">Supprimer magasin</label>
                                                                                    </div> 
                                                                                </div>
                                                                                <div class="titre_role">Transfert</div>
                                                                                <hr class="trait">
                                                                                <div class="bloc_partiel_role">	
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="constra"  wire:model="consulter_transfert">
                                                                                        <label class="form-check-label" for="constra">Consulter les transferts</label>
                                                                                    </div>
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="ctra"  wire:model="creer_transfert">
                                                                                        <label class="form-check-label" for="ctra">Créer transfert</label>
                                                                                    </div>
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="mtra"  wire:model="modifier_transfert">
                                                                                        <label class="form-check-label" for="mtra">Modifier/Changer état transfert</label>
                                                                                    </div>    
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="suptra"  wire:model="supprimer_transfert">
                                                                                        <label class="form-check-label" for="suptra">Supprimer transfert</label>
                                                                                    </div> 
                                                                                </div>
                                                                                <div class="titre_role">Inventaire</div>
                                                                                <hr class="trait">
                                                                                <div class="bloc_partiel_role">	
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="consinv"  wire:model="consulter_inventaire">
                                                                                        <label class="form-check-label" for="consinv">Consulter les inventaires</label>
                                                                                    </div>
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="cinv"  wire:model="creer_inventaire">
                                                                                        <label class="form-check-label" for="cinv">Créer inventaire</label>
                                                                                    </div>  
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="modinv"  wire:model="modifier_inventaire">
                                                                                        <label class="form-check-label" for="modinv">Modifier/Génerer mvt inventaire</label>
                                                                                    </div>
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="supinv"  wire:model="supprimer_inventaire">
                                                                                        <label class="form-check-label" for="supinv">Supprimer inventaire</label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div> 
                                                                @endif                                                               
                                                                @if($entite_mods->mod_pointe_vente == 1 && $dateJour <= $entite_mods->validite_mod)
                                                                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12 px-1">
                                                                        <div class="bloc_role rounded">
                                                                            <h6 class="text-center text-bleu pt-2 pb-2" style="background: #d9d9d9;">Gestion Point de vente</h6>
                                                                            <div class="titre_role">Point de vente</div>
                                                                            <hr class="trait">
                                                                            <div class="roles responsives">
                                                                                <div class="bloc_partiel_role">
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="tbpv"  wire:model="tablobord_pv">
                                                                                        <label class="form-check-label" for="tbpv">Tableau bord</label>
                                                                                    </div>
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="conssess"  wire:model="consulter_session_pv">
                                                                                        <label class="form-check-label" for="conssess">Consulter les sessions pv</label>
                                                                                    </div>                                                                                                                             
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="cssesion"  wire:model="creer_session">
                                                                                        <label class="form-check-label" for="cssesion">Créer une nouvelle session</label>
                                                                                    </div>
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="voirssaut"  wire:model="voir_session_autre">
                                                                                        <label class="form-check-label" for="voirssaut">Voir session des autres</label>
                                                                                    </div> 
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="pv"  wire:model="pv">
                                                                                        <label class="form-check-label" for="pv">Point vente</label>
                                                                                    </div> 
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="gerecmdat"  wire:model="gerer_cmd_attente">
                                                                                        <label class="form-check-label" for="gerecmdat">Gérer les cmd en attente</label>
                                                                                    </div> 
                                                                                    <div class="titre_role">Fidélité</div>
                                                                                    <hr class="trait">
                                                                                    <div class="bloc_partiel_role">                                                                        
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="modifidel"  wire:model="modifier_fidelite">
                                                                                            <label class="form-check-label" for="modifidel">Modifier objectif</label>
                                                                                        </div>  			
                                                                                    </div>
                                                                                    <div class="titre_role">Emplacement</div>
                                                                                    <hr class="trait">
                                                                                    <div class="bloc_partiel_role">                                                                        
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="consempla"  wire:model="consulter_emplacement">
                                                                                            <label class="form-check-label" for="consempla">Consulter les emplacements</label>
                                                                                        </div>
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="cempla"  wire:model="creer_emplacement">
                                                                                            <label class="form-check-label" for="cempla">Créer emplacement</label>
                                                                                        </div>  
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="modempla"  wire:model="modifier_emplacement">
                                                                                            <label class="form-check-label" for="modempla">Modifier emplacement</label>
                                                                                        </div>
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="supempla"  wire:model="supprimer_emplacement">
                                                                                            <label class="form-check-label" for="supempla">Supprimer emplacement</label>
                                                                                        </div>			
                                                                                    </div> 
                                                                                </div>
                                                                            </div>	
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                @if($entite_mods->mod_restaurant == 1 && $dateJour <= $entite_mods->validite_mod)
                                                                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                                                                        <div class="bloc_role rounded">
                                                                            <h6 class="text-center text-bleu pt-2 pb-2" style="background: #d9d9d9;">Gestion Restaurant</h6>
                                                                            <div class="titre_role">Point de vente restaurant</div>
                                                                            <hr class="trait">
                                                                            <div class="roles responsives">
                                                                                <div class="bloc_partiel_role">                                                        
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="conssess_res"  wire:model="consulter_session_restau">
                                                                                        <label class="form-check-label" for="conssess_res">Consulter les sessions restau</label>
                                                                                    </div>                                                                                                                             
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="cssesion_res"  wire:model="creer_session_restau">
                                                                                        <label class="form-check-label" for="cssesion_res">Créer une nouvelle session</label>
                                                                                    </div>
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="pass_res"  wire:model="passe_cmd_restau">
                                                                                        <label class="form-check-label" for="pass_res">Passer une commande</label>
                                                                                    </div> 
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="vcmdres"  wire:model="voir_cmd_autre_restau">
                                                                                        <label class="form-check-label" for="vcmdres">Voir commande des autres</label>
                                                                                    </div> 
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="eff_paie_res"  wire:model="eff_paie_restau">
                                                                                        <label class="form-check-label" for="eff_paie_res">Effectuer un paiement</label>
                                                                                    </div> 
                                                                                    <div class="titre_role">Cuisine</div>
                                                                                    <hr class="trait">
                                                                                    <div class="bloc_partiel_role">	                                                            
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="voircuisine" wire:model="voir_ecran_cuisine">
                                                                                            <label class="form-check-label" for="voircuisine">Voir écran cuisine</label>
                                                                                        </div>
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="statcmdcuis"  wire:model="changer_statut_cmd_cuisine">
                                                                                            <label class="form-check-label" for="statcmdcuis">Changer statut cmd cuisine</label>
                                                                                        </div>                                                            
                                                                                    </div> 
                                                                                    <div class="titre_role">Espace restaurant</div>
                                                                                    <hr class="trait">
                                                                                    <div class="bloc_partiel_role">                                                                        
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="consesp"  wire:model="consulter_espace">
                                                                                            <label class="form-check-label" for="consesp">Consulter les espaces</label>
                                                                                        </div>
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="cesp"  wire:model="creer_espace">
                                                                                            <label class="form-check-label" for="cesp">Créer espaces</label>
                                                                                        </div>  
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="modesp"  wire:model="modifier_espace">
                                                                                            <label class="form-check-label" for="modesp">Modifier espaces</label>
                                                                                        </div>
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="supesp"  wire:model="supprimer_espace">
                                                                                            <label class="form-check-label" for="supesp">Supprimer espaces</label>
                                                                                        </div>  			
                                                                                    </div>
                                                                                    <div class="titre_role">Table</div>
                                                                                    <hr class="trait">
                                                                                    <div class="bloc_partiel_role">                                                                        
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="constable"  wire:model="consulter_table">
                                                                                            <label class="form-check-label" for="constable">Consulter liste Table</label>
                                                                                        </div>
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="ctable"  wire:model="creer_table">
                                                                                            <label class="form-check-label" for="ctable">Créer Table</label>
                                                                                        </div>  
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="modtable"  wire:model="modifier_table">
                                                                                            <label class="form-check-label" for="modtable">Modifier Table</label>
                                                                                        </div>
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="suptable"  wire:model="supprimer_table">
                                                                                            <label class="form-check-label" for="suptable">Supprimer Table</label>
                                                                                        </div>			
                                                                                    </div>
                                                                                </div>
                                                                            </div>	
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                @if($entite_mods->mod_cmd == 1 && $dateJour <= $entite_mods->validite_mod)
                                                                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                                                                        <div class="bloc_role rounded">
                                                                            <h6 class="text-center text-bleu pt-2 pb-2" style="background: #d9d9d9;">Gestion Commande</h6>
                                                                            <div class="titre_role">Commande client</div>
                                                                            <hr class="trait">
                                                                            <div class="roles responsives">
                                                                                <div class="bloc_partiel_role">
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="concmd"  wire:model="consulter_commande">
                                                                                        <label class="form-check-label" for="concmd">Consulter les com. clients</label>
                                                                                    </div>                                                                                                                             
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="ccmd"  wire:model="creer_commande">
                                                                                        <label class="form-check-label" for="ccmd">Créer com. client</label>
                                                                                    </div>
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="mcmd"  wire:model="modifier_commande">
                                                                                        <label class="form-check-label" for="mcmd">Modifier com. client</label>
                                                                                    </div> 
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="supcmd"  wire:model="supprimer_commande">
                                                                                        <label class="form-check-label" for="supcmd">Supprimer com. client</label>
                                                                                    </div>                                                       
                                                                                    <div class="titre_role">Expédition</div>
                                                                                    <hr class="trait">
                                                                                    <div class="bloc_partiel_role">	
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="consexp"  wire:model="consulter_expedition">
                                                                                            <label class="form-check-label" for="consexp">Consulter les expéditions clients</label>
                                                                                        </div> 
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="crexp"  wire:model="creer_expedition">
                                                                                            <label class="form-check-label" for="crexp">Créer expédition com. client</label>
                                                                                        </div> 
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="supexp"  wire:model="supprimer_expedition">
                                                                                            <label class="form-check-label" for="supexp">Supprimer expédition com. client</label>
                                                                                        </div>  		
                                                                                    </div> 
                                                                                    <div class="titre_role">Commande fournisseur</div>
                                                                                    <hr class="trait">
                                                                                    <div class="bloc_partiel_role">	
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="concmdF"  wire:model="consulter_com_fourni">
                                                                                            <label class="form-check-label" for="concmdF">Consulter les com. fournisseurs</label>
                                                                                        </div>                                                                                                                             
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="ccmdF"  wire:model="creer_com_fourni">
                                                                                            <label class="form-check-label" for="ccmdF">Créer com. fournisseur</label>
                                                                                        </div>
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="mcmdF"  wire:model="modifier_com_fourni">
                                                                                            <label class="form-check-label" for="mcmdF">Modifier com. fournisseur</label>
                                                                                        </div> 
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="supcmdF"  wire:model="supprimer_com_fourni">
                                                                                            <label class="form-check-label" for="supcmdF">Supprimer com. fournisseur</label>
                                                                                        </div> 
                                                                                    </div> 
                                                                                    <div class="titre_role">Réception</div>
                                                                                    <hr class="trait">
                                                                                    <div class="bloc_partiel_role">                                                                        
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="consrecep"  wire:model="consulter_reception">
                                                                                            <label class="form-check-label" for="consrecep">Consulter les réceptions fournisseurs</label>
                                                                                        </div> 
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="crrecep"  wire:model="creer_reception">
                                                                                            <label class="form-check-label" for="crrecep">Créer réception com. fournisseur</label>
                                                                                        </div> 
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="suprecep"  wire:model="supprimer_reception">
                                                                                            <label class="form-check-label" for="suprecep">Supprimer réception com. fournisseur</label>
                                                                                        </div>  			
                                                                                    </div>
                                                                                </div>
                                                                            </div>	
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                @if($entite_mods->mod_facturation == 1 && $dateJour <= $entite_mods->validite_mod)
                                                                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                                                                        <div class="bloc_role rounded">
                                                                            <h6 class="text-center text-bleu pt-2 pb-2" style="background: #d9d9d9;">Facturation</h6>
                                                                            <div class="titre_role">Facturation client</div>
                                                                            <hr class="trait">
                                                                            <div class="roles responsives">
                                                                                <div class="bloc_partiel_role">
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="confact"  wire:model="consulter_facture">
                                                                                        <label class="form-check-label" for="confact">Consulter les factures clients</label>
                                                                                    </div>                                                                                                                             
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="cfact"  wire:model="creer_facture">
                                                                                        <label class="form-check-label" for="cfact">Créer facture client</label>
                                                                                    </div>
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="mfact"  wire:model="modifier_facture">
                                                                                        <label class="form-check-label" for="mfact">Modifier facture client</label>
                                                                                    </div> 
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="supfact"  wire:model="supprimer_facture">
                                                                                        <label class="form-check-label" for="supfact">Supprimer facture client</label>
                                                                                    </div>                                                       
                                                                                    <div class="titre_role">Règlement client</div>
                                                                                    <hr class="trait">
                                                                                    <div class="bloc_partiel_role">	
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="consregle"  wire:model="consulter_reglement">
                                                                                            <label class="form-check-label" for="consregle">Consulter les règlements clients</label>
                                                                                        </div> 
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="cregle"  wire:model="creer_reglement">
                                                                                            <label class="form-check-label" for="cregle">Effectuer règlement client</label>
                                                                                        </div> 
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="supregle"  wire:model="supprimer_reglement">
                                                                                            <label class="form-check-label" for="supregle">Supprimer règlement client</label>
                                                                                        </div>  		
                                                                                    </div> 
                                                                                    <div class="titre_role">Facturation fournisseur</div>
                                                                                    <hr class="trait">
                                                                                    <div class="bloc_partiel_role">	
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="confactF"  wire:model="consulter_fact_fourni">
                                                                                            <label class="form-check-label" for="confactF">Consulter les factures fournisseurs</label>
                                                                                        </div>                                                                                                                             
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="cfactF"  wire:model="creer_fact_fourni">
                                                                                            <label class="form-check-label" for="cfactF">Créer facture fournisseur</label>
                                                                                        </div>
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="mfactF"  wire:model="modifier_fact_fourni">
                                                                                            <label class="form-check-label" for="mfactF">Modifier facture fournisseur</label>
                                                                                        </div> 
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="supfactF"  wire:model="supprimer_fact_fourni">
                                                                                            <label class="form-check-label" for="supfactF">Supprimer facture fournisseur</label>
                                                                                        </div> 
                                                                                    </div> 
                                                                                    <div class="titre_role">Règlement Fournisseur</div>
                                                                                    <hr class="trait">
                                                                                    <div class="bloc_partiel_role">                                                                        
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="consregleF"  wire:model="consulter_reglement_fourni">
                                                                                            <label class="form-check-label" for="consregleF">Consulter les règlements fournisseurs</label>
                                                                                        </div> 
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="cregleF"  wire:model="creer_reglement_fourni">
                                                                                            <label class="form-check-label" for="cregleF">Créer règlement fournisseur</label>
                                                                                        </div> 
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="supregleF"  wire:model="supprimer_reglement_fourni">
                                                                                            <label class="form-check-label" for="supregleF">Supprimer règlement fournisseur</label>
                                                                                        </div>  			
                                                                                    </div>
                                                                                </div>
                                                                            </div>	
                                                                        </div>
                                                                    </div>
                                                                @endif                                    
                                                                @if($entite_mods->mod_banque_caisse == 1 && $dateJour <= $entite_mods->validite_mod)
                                                                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                                                                        <div class="bloc_role rounded">
                                                                            <h6 class="text-center text-bleu pt-2 pb-2" style="background: #d9d9d9;">Gestion Banque & Caisse</h6>
                                                                            <div class="titre_role">Comptes bancaires</div>
                                                                            <hr class="trait">
                                                                            <div class="roles responsives">
                                                                                <div class="bloc_partiel_role">                                                        
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="conscpte" wire:model="consulter_compte">
                                                                                        <label class="form-check-label" for="conscpte">Consulter les comptes</label>
                                                                                    </div> 
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="crcpte" wire:model="creer_compte">
                                                                                        <label class="form-check-label" for="crcpte">Créer compte</label>
                                                                                    </div>                                                                                                                            
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="modcpte" wire:model="modifier_compte">
                                                                                        <label class="form-check-label" for="modcpte">Modifier compte</label>
                                                                                    </div>
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="supcpte" wire:model="supprimer_compte">
                                                                                        <label class="form-check-label" for="supcpte">Supprimer compte</label>
                                                                                    </div> 
                                                                                    <div class="titre_role">Écritures bancaires</div>
                                                                                    <hr class="trait">
                                                                                    <div class="bloc_partiel_role">	
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="consecrit" wire:model="consulter_ecriture">
                                                                                            <label class="form-check-label" for="consecrit">Consulter les ecritures</label>
                                                                                        </div>    
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="mecrit"  wire:model="mod_ecriture">
                                                                                            <label class="form-check-label" for="mecrit">Modifier écritures</label>
                                                                                        </div>
                                                                                    </div> 
                                                                                    <div class="titre_role">Paiement divers</div>
                                                                                    <hr class="trait">
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="conspaie" wire:model="consulter_paie_divers">
                                                                                        <label class="form-check-label" for="conspaie">Consulter paiement divers</label>
                                                                                    </div> 
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="crpaie" wire:model="creer_paie_divers">
                                                                                        <label class="form-check-label" for="crpaie">Créer paiement divers</label>
                                                                                    </div>                                                                                                                            
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="modpaie" wire:model="modifier_paie_divers">
                                                                                        <label class="form-check-label" for="modpaie">Modifier paiement divers</label>
                                                                                    </div>
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="suppaie" wire:model="supprimer_paie_divers">
                                                                                        <label class="form-check-label" for="suppaie">Supprimer paiement divers</label>
                                                                                    </div>   
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="voirmarg" wire:model="voir_marge">
                                                                                        <label class="form-check-label" for="voirmarg">Voir les marges</label>
                                                                                    </div>                                                                                                                                                       
                                                                                    <div class="titre_role">Virement interne</div>
                                                                                    <hr class="trait">
                                                                                    <div class="bloc_partiel_role">	
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="eff" wire:model="effectuer_vire_interne">
                                                                                            <label class="form-check-label" for="eff">Effectuer un virement interne</label>
                                                                                        </div>		
                                                                                    </div> 
                                                                                </div>
                                                                            </div>	
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                @if($entite_mods->mod_fabrication == 1 && $dateJour <= $entite_mods->validite_mod)
                                                                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                                                                        <div class="bloc_role rounded">
                                                                            <h6 class="text-center text-bleu pt-2 pb-2" style="background: #d9d9d9 !important;">Gestion Fabrication</h6>
                                                                            <div class="titre_role">Nomenclature</div>
                                                                            <hr class="trait">
                                                                            <div class="roles responsives">
                                                                                <div class="bloc_partiel_role">
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="lnomem"  wire:model.defer="liste_nomencla">
                                                                                        <label class="form-check-label" for="lnomem">Liste nomenclature</label>
                                                                                    </div>
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="cnomem"  wire:model.defer="creer_nomencla">
                                                                                        <label class="form-check-label" for="cnomem">Créer nomenclature</label>
                                                                                    </div>    
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="mnomem"  wire:model.defer="modifier_nomencla">
                                                                                        <label class="form-check-label" for="mnomem">Modifier nomenclature</label>
                                                                                    </div>
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="snomem"  wire:model.defer="supprimer_nomencla">
                                                                                        <label class="form-check-label" for="snomem">Supprimer nomenclature</label>
                                                                                    </div>
                                                                                </div>                                                                
                                                                                <div class="titre_role">Ordres de fabrication</div>
                                                                                <hr class="trait">
                                                                                <div class="bloc_partiel_role">	
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="lof" wire:model.defer="liste_ordre_fab">
                                                                                        <label class="form-check-label" for="lof">Liste ordres fabrication</label>
                                                                                    </div>
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="cof" wire:model.defer="creer_ordre_fab">
                                                                                        <label class="form-check-label" for="cof">Créer ordres fabrication</label>
                                                                                    </div>
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="mof" wire:model.defer="modifier_ordre_fab">
                                                                                        <label class="form-check-label" for="mof">Modifier ordres fabrication</label>
                                                                                    </div>    
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="sof" wire:model.defer="supprimer_ordre_fab">
                                                                                        <label class="form-check-label" for="sof">Supprimer ordres fabrication</label>
                                                                                    </div> 
                                                                                </div>
                                                                                <div class="titre_role">Composant</div>
                                                                                <hr class="trait">
                                                                                <div class="bloc_partiel_role">		
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="acompo" wire:model.defer="ajouter_composant">
                                                                                        <label class="form-check-label" for="acompo">Ajouter composant</label>
                                                                                    </div>
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="scompo" wire:model.defer="supprimer_composant">
                                                                                        <label class="form-check-label" for="scompo">Supprimer composant</label>
                                                                                    </div> 
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>  
                                                                @endif
                                                                @if($entite_mods->mod_ticket == 1 && $dateJour <= $entite_mods->validite_mod)
                                                                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                                                                        <div class="bloc_role rounded">
                                                                            <h6 class="text-center text-bleu pt-2 pb-2" style="background: #d9d9d9 !important;">Gestion Ticket</h6>
                                                                            <div class="titre_role">Ticket</div>
                                                                            <hr class="trait">
                                                                            <div class="roles responsives">
                                                                                <div class="bloc_partiel_role">
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="ltick"  wire:model.defer="consulter_ticket">
                                                                                        <label class="form-check-label" for="ltick">Consulter les tickets</label>
                                                                                    </div>
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="ctick"  wire:model.defer="creer_ticket">
                                                                                        <label class="form-check-label" for="ctick">Créer ticket</label>
                                                                                    </div>    
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="mtick"  wire:model.defer="modifier_ticket">
                                                                                        <label class="form-check-label" for="mtick">Modifier ticket</label>
                                                                                    </div>
                                                                                    @if(auth()->user()->societe == "Administration")
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="stick"  wire:model.defer="supprimer_ticket">
                                                                                            <label class="form-check-label" for="stick">Supprimer ticket</label>
                                                                                        </div>
                                                                                    @endif
                                                                                </div>                                                                
                                                                                {{-- <div class="titre_role">Ordres de fabrication</div> --}}
                                                                                {{-- <hr class="trait"> --}}
                                                                                {{-- <div class="bloc_partiel_role">	
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="lof" wire:model.defer="liste_ordre_fab">
                                                                                        <label class="form-check-label" for="lof">Liste ordres fabrication</label>
                                                                                    </div>
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="cof" wire:model.defer="creer_ordre_fab">
                                                                                        <label class="form-check-label" for="cof">Créer ordres fabrication</label>
                                                                                    </div>
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="mof" wire:model.defer="modifier_ordre_fab">
                                                                                        <label class="form-check-label" for="mof">Modifier ordres fabrication</label>
                                                                                    </div>    
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="sof" wire:model.defer="supprimer_ordre_fab">
                                                                                        <label class="form-check-label" for="sof">Supprimer ordres fabrication</label>
                                                                                    </div> 
                                                                                </div> --}}
                                                                                {{-- <div class="titre_role">Composant</div> --}}
                                                                                {{-- <hr class="trait"> --}}
                                                                                {{-- <div class="bloc_partiel_role">		
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="acompo" wire:model.defer="ajouter_composant">
                                                                                        <label class="form-check-label" for="acompo">Ajouter composant</label>
                                                                                    </div>
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="scompo" wire:model.defer="supprimer_composant">
                                                                                        <label class="form-check-label" for="scompo">Supprimer composant</label>
                                                                                    </div> 
                                                                                </div> --}}
                                                                            </div>
                                                                        </div>
                                                                    </div>  
                                                                @endif
                                                                @if($entite_mods->mod_tache == 1 && $dateJour <= $entite_mods->validite_mod)                                                                                     
                                                                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                                                                        <div class="bloc_role rounded ml-1">
                                                                            <h6 class="text-center text-bleu pt-2 pb-2" style="background: #d9d9d9 !important;">Gestion des Tâches | Etape</h6>
                                                                            <div class="titre_role">Tâches</div>
                                                                            <hr class="trait">
                                                                            <div class="roles responsives">
                                                                                <div class="bloc_partiel_role"> 
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="consultach"  wire:model="consulter_tache">
                                                                                        <label class="form-check-label" for="consultach">Consulter les tâches</label>
                                                                                    </div>
                                                                                    <div class="d-flex justify-content-start">
                                                                                        @error('consulter_tier') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                                    </div>
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="ctach"  wire:model="creer_tache">
                                                                                        <label class="form-check-label" for="ctach">Créer une tâche</label>
                                                                                    </div>
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="detatach"  wire:model="detail_tache">
                                                                                        <label class="form-check-label" for="detatach">Voir les détails tâches</label>
                                                                                    </div>
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="mtach"  wire:model="modifier_tache">
                                                                                        <label class="form-check-label" for="mtach">Modifier une tâche</label>
                                                                                    </div>
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="suptach"  wire:model="supprimer_tache">
                                                                                        <label class="form-check-label" for="suptach">Supprimer une tâche</label>
                                                                                    </div>
                                                                                </div>                                                                                                                           
                                                                                <div class="titre_role">Etape Tâche</div>
                                                                                <hr class="trait">
                                                                                <div class="bloc_partiel_role">
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="consetap_tach"  wire:model="consulter_etapeTache">
                                                                                        <label class="form-check-label" for="consetap_tach">Consulter les étapes</label>
                                                                                    </div>
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="creeretap_tach"  wire:model="creer_etapeTache">
                                                                                        <label class="form-check-label" for="creeretap_tach">Créer étapes</label>
                                                                                    </div>                                                                      
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="modetap_tach"  wire:model="modifier_etapeTache">
                                                                                        <label class="form-check-label" for="modetap_tach">Modifier étapes</label>
                                                                                    </div>
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="suppetap_tach"  wire:model="supprimer_etapeTache">
                                                                                        <label class="form-check-label" for="suppetap_tach">Supprimer étapes</label>
                                                                                    </div>                                                                
                                                                                </div> 
                                                                            </div>
                                                                        </div>													  		
                                                                    </div>                                            
                                                                @endif
                                                                @if(auth()->user()->societe == "Administration")
                                                                    @if($entite_mods->mod_gestion_commercial == 1 && $dateJour <= $entite_mods->validite_mod)
                                                                        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                                                                            <div class="bloc_role rounded mr-1">
                                                                                <h6 class="text-center text-bleu pt-2 pb-2" style="background: #d9d9d9 !important;">Gestion Commerciale</h6>
                                                                                <div class="titre_role">Souscription</div>
                                                                                <hr class="trait">
                                                                                <div class="roles responsives">
                                                                                    <div class="bloc_partiel_role">
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="conssousc"  wire:model="consulter_souscription">
                                                                                            <label class="form-check-label" for="conssousc">Consulter les souscriptions</label>
                                                                                        </div>
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="creersousc"  wire:model="creer_souscription">
                                                                                            <label class="form-check-label" for="creersousc">Effectuer une souscription</label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>	
                                                                            </div>
                                                                        </div>
                                                                    @endif  
                                                                @endif  
                                                                @if($entite_mods->mod_multisociete == 1 && $dateJour <= $entite_mods->validite_mod)
                                                                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                                                                        <div class="bloc_role rounded mr-1">
                                                                            <h6 class="text-center text-bleu pt-2 pb-2" style="background: #d9d9d9 !important;">Gestion Multi-sociétés</h6>
                                                                            <div class="titre_role">Société</div>
                                                                            <hr class="trait">
                                                                            <div class="roles responsives">
                                                                                <div class="bloc_partiel_role">
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="conssoc"  wire:model="consulter_societe">
                                                                                        <label class="form-check-label" for="conssoc">Consulter les sociétés</label>
                                                                                    </div>
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="creersoc"  wire:model="creer_societe">
                                                                                        <label class="form-check-label" for="creersoc">Créer société</label>
                                                                                    </div>                                                                      
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="modsoc"  wire:model="modifier_societe">
                                                                                        <label class="form-check-label" for="modsoc">Modifier société</label>
                                                                                    </div>
                                                                                    {{-- <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="changsoc"  wire:model="changer_filiale">
                                                                                        <label class="form-check-label" for="changsoc">Passer d'une filiale a l'autre</label>
                                                                                    </div>   --}}
                                                                                    <div class="titre_role">Société autorisée</div>
                                                                                    <hr class="trait">
                                                                                    <div class="bloc_partiel_role">
                                                                                        @foreach ($entiteFiliale as $entiteFiliales )
                                                                                            <div class="form-check form-switch mb-3">
                                                                                                <input class="form-check-input" type="checkbox" id="{{$entiteFiliales->id}}" wire:model="acces_entite.{{$entiteFiliales->id}}">
                                                                                                <label class="form-check-label text-vert fw-semibold" for="{{$entiteFiliales->id}}">{{$entiteFiliales->enseigne}}</label>
                                                                                            </div>
                                                                                        @endforeach 
                                                                                    </div>                                                                  
                                                                                    <div class="titre_role">Transfert stock</div>
                                                                                    <hr class="trait">
                                                                                    <div class="bloc_partiel_role">
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="constrans"  wire:model="consulter_transfert_filiale">
                                                                                            <label class="form-check-label" for="constrans">Consulter transfert entre filiale</label>
                                                                                        </div>
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="transtock"  wire:model="transfer_stock_filiale">
                                                                                            <label class="form-check-label" for="transtock">Transférer un stock entre filiale</label>
                                                                                        </div>                                                                  
                                                                                    </div> 
                                                                                </div>
                                                                            </div>	
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                @if($entite_mods->mod_administration == 1 && $dateJour <= $entite_mods->validite_mod)      
                                                                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                                                                        <div class="bloc_role rounded mr-1">
                                                                            <h6 class="text-center text-bleu pt-2 pb-2" style="background: #d9d9d9 !important;">Paramètres</h6>
                                                                            <div class="titre_role">Entité</div>
                                                                            <hr class="trait">
                                                                            <div class="roles responsives">
                                                                                <div class="bloc_partiel_role">		
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="consentite"  wire:model="consulter_entite">
                                                                                        @if(auth()->user()->societe == "Administration")
                                                                                            <label class="form-check-label" for="consentite">Consulter les entités</label>
                                                                                        @else
                                                                                            <label class="form-check-label" for="consentite">Voir entité</label>
                                                                                        @endif
                                                                                    </div>
                                                                                    @if(auth()->user()->societe == "Administration")
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="centite"  wire:model="creer_entite">
                                                                                            <label class="form-check-label" for="centite">Créer entité (Administration)</label>
                                                                                        </div>
                                                                                    @endif
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="mentite"  wire:model="modifier_entite">
                                                                                        <label class="form-check-label" for="mentite">Modifier infos entité</label>
                                                                                    </div> 
                                                                                    @if(auth()->user()->societe == "Administration")
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="sentite"  wire:model="supprimer_entite">
                                                                                            <label class="form-check-label" for="sentite">Supprimer entité (Administration)</label>
                                                                                        </div>
                                                                                    @endif
                                                                                    @if(auth()->user()->societe == "Administration")
                                                                                        <div class="form-check form-switch mb-3">
                                                                                            <input class="form-check-input" type="checkbox" id="actcompte"  wire:model="activer_compte_enite">
                                                                                            <label class="form-check-label" for="actcompte">Activer Compte (Administration)</label>
                                                                                        </div>
                                                                                    @endif 
                                                                                </div>
                                                                                <div class="titre_role">Utilisateur</div>
                                                                                <hr class="trait">
                                                                                <div class="bloc_partiel_role">	
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="consuser"  wire:model="consulter_user">
                                                                                        <label class="form-check-label" for="consuser">Consulter les utilisateurs</label>
                                                                                    </div>
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="cuser"  wire:model="creer_user">
                                                                                        <label class="form-check-label" for="cuser">Créer utilisateur</label>
                                                                                    </div>    
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="muser"  wire:model="modifier_user">
                                                                                        <label class="form-check-label" for="muser">Modifier utilisateur</label>
                                                                                    </div>
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="suser"  wire:model="supprimer_user">
                                                                                        <label class="form-check-label" for="suser">Supprimer utilisateur</label>
                                                                                    </div> 
                                                                                </div>
                                                                                <div class="titre_role">Rôle</div>
                                                                                <hr class="trait">
                                                                                <div class="bloc_partiel_role">		
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="consrole"  wire:model="consulter_role">
                                                                                        <label class="form-check-label" for="consrole">Consulter les rôles</label>
                                                                                    </div>
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="crole"  wire:model="creer_role">
                                                                                        <label class="form-check-label" for="crole">Créer rôle</label>
                                                                                    </div>    
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="mrole"  wire:model="modifier_role">
                                                                                        <label class="form-check-label" for="mrole">Modifier rôle</label>
                                                                                    </div>
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="srole"  wire:model="supprimer_role">
                                                                                        <label class="form-check-label" for="srole">Supprimer rôle</label>
                                                                                    </div> 
                                                                                </div> 
                                                                                <div class="titre_role">Département / Poste</div>
                                                                                <hr class="trait">
                                                                                <div class="bloc_partiel_role">		
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="consdepart"  wire:model="consulter_depart_poste">
                                                                                        <label class="form-check-label" for="consdepart">Consulter départ. / Poste</label>
                                                                                    </div>
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="cdepart"  wire:model="creer_depart_poste">
                                                                                        <label class="form-check-label" for="cdepart">Créer départ. / Poste</label>
                                                                                    </div>    
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="mdepart"  wire:model="modifier_depart_poste">
                                                                                        <label class="form-check-label" for="mdepart">Modifier départ. / Poste</label>
                                                                                    </div>
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="sdepart"  wire:model="supprimer_depart_poste">
                                                                                        <label class="form-check-label" for="sdepart">Supprimer départ. / Poste</label>
                                                                                    </div> 
                                                                                </div> 
                                                                                <div class="titre_role">Configuration</div>
                                                                                <hr class="trait">
                                                                                <div class="bloc_partiel_role">		
                                                                                    <div class="form-check form-switch mb-3">
                                                                                        <input class="form-check-input" type="checkbox" id="config"  wire:model="configurer">
                                                                                        <label class="form-check-label" for="config">Modifier configuration</label>
                                                                                    </div>
                                                                                </div>                                                        
                                                                            </div>
                                                                        </div>
                                                                    </div> 
                                                                @endif  
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>                                                        
                                            </div>
                                        </div>
                                    </div>
                                </div>                                      
                                <div id="activite" class="container-fluid tab-pane fade pas_bordure">
                                    <div class="card shadow-sm">  
                                        <div class="card shadow-sm border border-light-subtle rounded-3 bg-white p-2">    
                                            <h6 class="fw-bold text-bleu mb-3"><i class="fas fa-calendar-alt" style=" color: #393b83;" title="Derniers événements liés"></i> @if($logCount > 1) Activités récentes @else Activité récente @endif ({{$logCount}})</h6>
                                            <div class="position-relative custom-scroll pe-2" style="height: calc(100vh - 302px); overflow-y: auto;">  
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


