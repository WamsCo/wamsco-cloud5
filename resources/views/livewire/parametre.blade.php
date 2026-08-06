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
                                            <button href="{{asset('bienvenue?active=1')}}" wire:navigate class="btn btn-sm btn-outline-success flex-fill fw-semibold ms-auto"><i class="fa fa-close"></i> Fermer</button>
                                            <button wire:click.prevent="storePv()" title="Cliquez pour enregistrer" data-toggle="tooltip" class="btn btn-sm btn-outline-secondary flex-fill fw-semibold ms-auto"><i class="fa fa-save"></i> Enregistrer</button>
                                        </div> 
                                        {{-- <h5 class="fw-bold mb-3" title="">À propos </h5>
                                        <div class="table-responsive">
                                            <table class="table table-borderless table text-nowrap m-0">
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
                                                
                                            </table>
                                        </div> --}}
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
                                                    <span class="d-block fw-semibold text-dark" style="font-size: 13px;">{{ $this->created_at->format('d/m/Y : H:i:s') }}</span>
                                                    {{-- <span class="d-block text-muted" style="font-size: 11px; margin-top: -2px;">GMT+1</span> --}}
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
                                            <a href="{{asset('pos_sessions?active=5&champ=1-1')}}" wire:navigate class="btn btn-sm btn-bleu d-none d-md-block ms-auto"><i class="fa fa-chevron-left"></i> Point de vente</a>
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
                                                            <div class="row">
                                                                 <div class="col-lg-12 col-md-12 col-sm-12">
                                                                    <div class="mb-2"> 
                                                                        <div class="card-body px-1">
                                                                            <div class="container-fluid pt-0 px-0 border-bottom">
                                                                                <div class="row">
                                                                                    <div class="col-md-6 col-sm-12">
                                                                                        <div class="row">
                                                                                            <div class="col-sm-12 col-12">
                                                                                                <form> 
                                                                                                    <div class="text-muted fw-semibold pb-0">
                                                                                                        <i class="fas fa-dolly text-danger"></i> Entrepôt déstockage des produits sur le point de vente.
                                                                                                    </div>
                                                                                                    <div class="row mb-3">
                                                                                                        <label for="entrepot" class="col-sm-4 fw-bold col-form-label">Entrepôts Pv</label>
                                                                                                        <div class="col-sm-8">                                                        
                                                                                                            <select id="entrepot" wire:model="entrepot" class="form-control form-select bordure w-100 @error('entrepot') is-invalid @enderror">                                                           
                                                                                                                <option value=""></option>
                                                                                                                @foreach($listEntrepot as $listEntrepots)
                                                                                                                    @foreach ($SommeParProduit as $SommeParProduits)
                                                                                                                        @if($SommeParProduits->id_entrepot == $listEntrepots->id)
                                                                                                                            <option value="{{$listEntrepots->id}}">{{$listEntrepots->nom}} - Stock total » {{number_format($SommeParProduits->total_quantite,0,',',' ')}}</option>
                                                                                                                        @endif
                                                                                                                    @endforeach
                                                                                                                @endforeach  
                                                                                                            </select> 
                                                                                                        </div>
                                                                                                        <div class="d-flex justify-content-start">
                                                                                                            @error('entrepot') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                                                        </div>
                                                                                                    </div> 
                                                                                                </form>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-6 col-sm-12">
                                                                                        <div class="row">
                                                                                            <div class="col-sm-12 col-12">
                                                                                                <form> 
                                                                                                    <div class="text-muted fw-semibold pb-0">
                                                                                                        <i class="fas fa-dolly text-danger"></i> Entrepôt déstockage des produits au restaurant.
                                                                                                    </div>
                                                                                                    <div class="row mb-3">
                                                                                                        <label for="entrepot_restau" class="col-sm-5 fw-bold col-form-label">Entrepôts Restaurant</label>
                                                                                                        <div class="col-sm-7">                                                        
                                                                                                            <select id="entrepot_restau" wire:model="entrepot_restau" class="form-control form-select bordure w-100 @error('entrepot_restau') is-invalid @enderror">                                                           
                                                                                                                <option value=""></option>
                                                                                                                @foreach($listEntrepot as $listEntrepots)
                                                                                                                    @foreach ($SommeParProduit as $SommeParProduits)
                                                                                                                        @if($SommeParProduits->id_entrepot == $listEntrepots->id)
                                                                                                                            <option value="{{$listEntrepots->id}}">{{$listEntrepots->nom}} - Stock total » {{number_format($SommeParProduits->total_quantite,0,',',' ')}}</option>
                                                                                                                        @endif
                                                                                                                    @endforeach
                                                                                                                @endforeach  
                                                                                                            </select> 
                                                                                                        </div>
                                                                                                        <div class="d-flex justify-content-start">
                                                                                                            @error('entrepot_restau') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                                                        </div>
                                                                                                    </div> 
                                                                                                </form>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-6 col-sm-12">
                                                                                        <div class="row">
                                                                                            <div class="col-sm-12 col-12"> 
                                                                                                <div class="text-muted fw-semibold pb-0">
                                                                                                    <i class="fa fa-podcast text-danger"></i> Points fidélités sur le point de vente.
                                                                                                </div> 
                                                                                                <div class="row mb-3">
                                                                                                    <label for="inputPassword3" class="col-sm-4 fw-bold col-form-label py-3">Point fidélité</label>
                                                                                                    <div class="col-sm-8">
                                                                                                        <div class="card-body">
                                                                                                            <div class="form-check form-check-inline">
                                                                                                            <input class="form-check-input pointer" type="radio" wire:model="activer_fidelite" value="1" name="inlineRadioOptions" id="inlineRadio1" value="option1">
                                                                                                            <label class="form-check-label pointer" for="inlineRadio1">Activer</label>
                                                                                                            </div>
                                                                                                            <div class="form-check form-check-inline">
                                                                                                            <input class="form-check-input pointer" type="radio" wire:model="activer_fidelite" value="0" name="inlineRadioOptions" id="inlineRadio2" value="option2" checked="">
                                                                                                            <label class="form-check-label pointer" for="inlineRadio2">Désactiver</label>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="d-flex justify-content-start">
                                                                                                            @error('activer_fidelite') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>                                         
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-6 col-sm-12">
                                                                                        <div class="row">
                                                                                            <div class="col-sm-12 col-12"> 
                                                                                                <div class="text-muted fw-semibold pb-0">
                                                                                                    <i class="fas fa-file-invoice text-info"></i> Système d'affichage des commandes en cuisine.
                                                                                                </div> 
                                                                                                <div class="row mb-3">
                                                                                                    <label for="inputPassword4" class="col-sm-4 fw-bold col-form-label py-3">Envoyer en cuisine</label>
                                                                                                    <div class="col-sm-8">
                                                                                                        <div class="card-body">
                                                                                                            <div class="form-check form-check-inline">
                                                                                                            <input class="form-check-input pointer" type="radio" wire:model="activer_ecran_cuisine" value="1" name="inlineRadioOptions2" id="inlineRadio3" value="option3">
                                                                                                            <label class="form-check-label pointer" for="inlineRadio3">Activer</label>
                                                                                                            </div>
                                                                                                            <div class="form-check form-check-inline">
                                                                                                            <input class="form-check-input pointer" type="radio" wire:model="activer_ecran_cuisine" value="0" name="inlineRadioOptions2" id="inlineRadio4" value="option4" checked="">
                                                                                                            <label class="form-check-label pointer" for="inlineRadio4">Désactiver</label>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="d-flex justify-content-start">
                                                                                                            @error('activer_ecran_cuisine') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>                                         
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-6 col-sm-12">
                                                                                        <div class="row">
                                                                                            <div class="col-sm-12 col-12"> 
                                                                                                <div class="text-muted fw-semibold pb-0">
                                                                                                    <i class="fa fa-paper-plane text-info"></i> Activer ou désactiver le système d'envoi d'email.
                                                                                                </div> 
                                                                                                <div class="row mb-3">
                                                                                                    <label for="inputPassword4" class="col-sm-4 fw-bold col-form-label py-3">Envoi d'email</label>
                                                                                                    <div class="col-sm-8">
                                                                                                        <div class="card-body">
                                                                                                            <div class="form-check form-check-inline">
                                                                                                            <input class="form-check-input pointer" type="radio" wire:model="envoi_mail" value="1" name="inlineRadioOptions5" id="inlineRadio5" value="option5">
                                                                                                            <label class="form-check-label pointer" for="inlineRadio5">Activer</label>
                                                                                                            </div>
                                                                                                            <div class="form-check form-check-inline">
                                                                                                            <input class="form-check-input pointer" type="radio" wire:model="envoi_mail" value="0" name="inlineRadioOptions6" id="inlineRadio6" value="option6" checked="">
                                                                                                            <label class="form-check-label pointer" for="inlineRadio6">Désactiver</label>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="d-flex justify-content-start">
                                                                                                            @error('envoi_mail') <span class="text-danger">{{ $message }}</span> @enderror 
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
                                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                                    <div class="mb-2">
                                                                        <div class="card-headerk d-flex align-items-center justify-content-between border-bottom py-1">
                                                                            <h6 class="card-title text-bleu"><i class="fa fa-cog fa-spin text-danger" aria-hidden="true"></i> Facturation | Commande</h6>
                                                                            <a href="listing_fact_clt?active=7&champ=1-1&choix=1" wire:navigate class="btn btn-sm btn-secondary d-none d-md-block ms-auto py-1"><i class="fa fa-chevron-left"></i> Retour facture</a>
                                                                        </div>
                                                                        <div class="card-body">
                                                                            <div class="container-fluid pt-0 px-1 border-bottom">
                                                                                <div class="row">
                                                                                    <div class="col-md-6 col-sm-12">
                                                                                        <div class="row">
                                                                                            <div class="col-sm-12 col-12">
                                                                                                <form> 
                                                                                                    <div class="text-muted fw-semibold pb-0">
                                                                                                        <i class="fas fa-file-invoice-dollar text-bleu"></i> Entrepôt déstockage des produits pour la facturation et commande client.
                                                                                                    </div>
                                                                                                    <div class="row mb-3">
                                                                                                        <label for="entrepot_client" class="col-sm-4 fw-bold col-form-label">Entrepôt client</label>
                                                                                                        <div class="col-sm-8">                                                        
                                                                                                            <select id="entrepot_client" wire:model="entrepot_client" class="form-control form-select bordure w-100 @error('entrepot_client') is-invalid @enderror">
                                                                                                                <option value=""></option>
                                                                                                                @foreach($listEntrepot as $listEntrepots)
                                                                                                                    @foreach ($SommeParProduit as $SommeParProduits)
                                                                                                                        @if($SommeParProduits->id_entrepot == $listEntrepots->id)
                                                                                                                            <option value="{{$listEntrepots->id}}">{{$listEntrepots->nom}} - Stock total » {{number_format($SommeParProduits->total_quantite,0,',',' ')}}</option>
                                                                                                                        @endif
                                                                                                                    @endforeach
                                                                                                                @endforeach 
                                                                                                            </select> 
                                                                                                        </div>
                                                                                                        <div class="d-flex justify-content-start">
                                                                                                            @error('entrepot_client') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </form>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-6 col-sm-12">
                                                                                        <div class="row">
                                                                                            <div class="col-sm-12 col-12">
                                                                                                <form> 
                                                                                                    <div class="text-muted fw-semibold pb-0">
                                                                                                        <i class="fas fa-file-invoice-dollar text-bleu"></i> Entrepôt déstockage des produits pour la facturation et commande fournisseur.
                                                                                                    </div>
                                                                                                    <div class="row mb-1">
                                                                                                        <label for="entrepot_fournisseur" class="col-sm-5 fw-bold col-form-label">Entrepôt fournisseur</label>
                                                                                                        <div class="col-sm-7">                                                        
                                                                                                            <select id="entrepot_fournisseur" wire:model="entrepot_fournisseur" class="form-control form-select bordure w-100 @error('entrepot_fournisseur') is-invalid @enderror">
                                                                                                                <option value=""></option>	
                                                                                                                @foreach($listEntrepot as $listEntrepots)
                                                                                                                    @foreach ($SommeParProduit as $SommeParProduits)
                                                                                                                        @if($SommeParProduits->id_entrepot == $listEntrepots->id)
                                                                                                                            <option value="{{$listEntrepots->id}}">{{$listEntrepots->nom}} - Stock total » {{number_format($SommeParProduits->total_quantite,0,',',' ')}}</option>
                                                                                                                        @endif
                                                                                                                    @endforeach
                                                                                                                @endforeach 	
                                                                                                            </select>  
                                                                                                        </div>
                                                                                                        <div class="d-flex justify-content-start">
                                                                                                            @error('entrepot_fournisseur') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                                                        </div>
                                                                                                    </div> 
                                                                                                </form>
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
</div>
