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
                <div class="card mb-0 bg-2k">
                    @foreach($user as $users)
                        <div class="container-fluid py-2 px-2">
                            <div class="row">
                                <!-- SIDEBAR -->
                                <div class="col-lg-3">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-body border border-light-subtle rounded-3 bg-white position-relative custom-scroll pe-2" style="height: calc(100vh - 131px); overflow-y: auto;">
                                            <div class="text-centerk">                                                
                                                <div class="d-flex align-items-centerk">                                                    
                                                    <div class="">
                                                        <div class="photorefk">
                                                            @if($users->profil != null)
                                                                <td><img class="img_detail_prod rounded-circle border" src="storage/{{$users->profil}}" wire:click.prevent="edit({{$users->id}})" data-bs-toggle="modal" data-bs-target="#imageProfilModal"/></td>
                                                            @else
                                                                <td><img class="img_detail_prod rounded-circle border" src="storage/default/user_man.png" wire:click.prevent="edit({{$users->id}})" data-bs-toggle="modal" data-bs-target="#imageProfilModal"/></td>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="ms-2">
                                                        <h4 class="mb-0 fw-bold" title="{{$users->name}}">@if($users->titre){{$users->titre}}. @endif {{Str::limit($users->name.'', 42) ?? 'Inconu(e)'}}</h4>
                                                        <div class="text-success fw-bold"><span class="text-success fw-semibold">{{$users->type_user}}</span></div>
                                                        @if($users->matricule)
                                                            <div class="fw-semibold" title="Matricule">» <i class="fas fa-user-circle text-bleu"></i> <span class="text-bleu fw-semibold">{{$users->matricule}}</span></div>
                                                        @endif
                                                        @if($users->echelon)
                                                            <div class="fw-semibold">Catégorie / Echelon » <span class="text-bleu fw-semibold">{{$users->categorie}} @if($users->echelon)/@endif {{$users->echelon}}</span></div>
                                                        @endif                                                         
                                                        {{-- <br><small class="text-muted">REF-001</small><br> --}}
                                                        {{-- @if($users->date_valide)
                                                            <div class="fw-semibold">Validité » <span class="text-bleu fw-semibold"><i class="fas fa-calendar-alt"></i> {{date('d-m-Y', strtotime($users->date_valide))}}
                                                                @php
                                                                    $nbjoursRestant = round((strtotime($users->date_valide) - strtotime($dateJour))/(60*60*24));
                                                                @endphp 
                                                                @if($nbjoursRestant > 0 )
                                                                    (<span class="text-vert">{{$nbjoursRestant}} Jours</span>)
                                                                @elseif($nbjoursRestant == 0 )
                                                                    (<span class="text-warning" style="font-size: 10px;">Dernier jour</span>)
                                                                @else
                                                                    (<span class="text-danger" style="font-size: 10px;">Dépassée</span>)
                                                                @endif   </span>
                                                            </div>
                                                        @endif --}}
                                                        <div class="text-muted">
                                                            @if($users->etat == 1)
                                                                <span class="badge bg-success py-0" title="Activer">Activer</span>
                                                            @else
                                                                <span class="badge bg-danger py-0" title="Désactiver">Désactiver</span>
                                                            @endif
                                                        </div>
                                                    </div>                                                    
                                                </div>                                                
                                            </div>
                                            <hr class="my-1">
                                            <div class="d-flex gap-2 mb-1">
                                                <button href="#" wire:click.prevent="edit({{$users->id}})" data-bs-toggle="modal" data-bs-target="#imageMonCompteModal" title="Cliquez pour modifier image" data-toggle="tooltip" class="btn btn-sm btn-white text-danger flex-fill fw-semibold ms-auto"><i class="fas fa-camera"></i> Editer</button>
                                                <button href="#" wire:click.prevent="edit({{$users->id}})" data-bs-toggle="modal" data-bs-target="#updateCompteUserModal" title="Cliquez pour modifier" data-toggle="tooltip" class="btn btn-sm btn-outline-secondary flex-fill fw-semibold ms-auto"><i class="fa fa-pen"></i> Modifier</button>
                                            </div> 
                                            <button href="{{asset('bienvenue?active=1')}}" wire:navigate class="btn btn-sm btn-outline-success w-100 mb-4"><i class="fa fa-close"></i> Fermer</button>
                                            <h5 class="fw-bold mb-3" title="{{$users->name}}">À propos {{Str::limit($users->name.'', 42) ?? 'Inconu(e)'}}</h5>
                                            <div class="table-responsive">
                                                <table class="table table-borderless table text-nowrap m-0">
                                                    <tr>
                                                        <td class="text-muted fw-semibold">Raison sociale <i class="fas fa-info-circle" title="Nom officiel d'une entreprise" data-bs-toggle="tooltip" data-bs-placement="top" style=" vertical-align: middle; cursor: help"></i></td>
                                                        <td class="fw-semibold text-vert">{{$users->societe}} (@if($users->societe_mere == "Inconnue") <span class="blink text-danger" data-toggle="tooltip" title="Société mère">{{$users->societe_mere}}</span> @else <span class="text-bleu" data-toggle="tooltip" title="Société mère">{{$users->societe_mere}}</span> @endif)</td> 
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted fw-semibold">Rôle</td>
                                                        <td class="text-primary fw-semibold">{{ $users->type_user }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted fw-semibold">Téléphone</td>
                                                        <td>{{ $users->telephone }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted fw-semibold">Email</td>
                                                        <td>{{ $users->email }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted fw-semibold">Sexe</td>
                                                        <td class="fw-semibold @if($users->sexe == "Masculin") text-success @else text-info @endif">{{ $users->sexe }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted fw-semibold">Type d'employé <i class="fas fa-user-circle"></i></td>
                                                        <td class="fw-bold">{{$users->type_employe}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted fw-semibold">Lieu de travail <i class="fas fa-university"></i></td>
                                                        <td class="fw-semibold">{{$users->lieu_travail}}</td>
                                                    </tr>                                                    
                                                    <tr>
                                                        <td class="text-muted fw-semibold">Adresse de travail</td>
                                                        <td>{{ $users->adresse_travail }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted fw-semibold">Mode de paimenent</td>
                                                        <td>{{ $users->mode_paiement }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted fw-semibold">N° CNPS</td>
                                                        <td>{{ $users->cnps }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted fw-semibold">DIPE</td>
                                                        <td>{{ $users->dipe }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted fw-semibold">Note</td>
                                                        <td>{{ $users->note_interne }}</td>
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
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 44px; height: 44px; background-color: #e8f5e9; color: #198754;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                                                        </svg>
                                                    </div>
                                                    <div class="text-nowrap">
                                                        <span class="d-block fw-bold text-secondary" style="font-size: 11px;">Dernière activité</span>
                                                        <span class="d-block fw-semibold text-dark" style="font-size: 13px;">{{ $users->updated_at->format('d/m/Y : H:i:s') }}</span>
                                                        {{-- <span class="d-block text-muted" style="font-size: 11px; margin-top: -2px;">GMT+1</span> --}}
                                                    </div>
                                                </div>                                                                                              

                                                <div class="d-flex align-items-center px-3 border-end flex-fill">
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 44px; height: 44px; background-color: #fff3e0; color: #fd7e14;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                        </svg>
                                                    </div>
                                                    <div class="text-nowrap">
                                                        <span class="fw-bold text-secondary" style="font-size: 11px;">Factures</span>
                                                        <span class="d-block fw-bold text-dark mt-1" style="font-size: 14px;">{{$factClientEnteteCount}}</span>
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
                                                        <span class="d-block fw-bold text-dark mt-1" style="font-size: 14px;">{{number_format($factClientEnteteTTC,0,',',' ')}} <span style="font-size: 10px;">{{$this->devise}}</span></span>
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
                                                        <span class="d-block fw-bold text-secondary" style="font-size: 11px;">Marge</span>
                                                        <span class="d-block fw-bold text-dark mt-1" style="font-size: 14px;">{{number_format($factClientEnteteMarge,0,',',' ')}} <span style="font-size: 10px;">{{$this->devise}}</span></span>
                                                    </div>
                                                </div>

                                                <div class="d-flex align-items-center px-3 flex-fill">
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 44px; height: 44px; background-color: #ffebee; color: #dc3545;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v3" />
                                                        </svg>
                                                    </div>
                                                    <div class="text-nowrap">
                                                        <span class="d-block fw-bold text-secondary" style="font-size: 11px;">Créance</span>
                                                        <span class="d-block fw-bold text-dark mt-1" style="font-size: 14px;">{{number_format($factClientEnteteResteApercevoir,0,',',' ')}} <span style="font-size: 10px;">{{$this->devise}}</span></span>
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
                                                        <span class="d-block fw-bold text-dark mt-1" style="font-size: 13px;">{{$users->nom_user}}</span>
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
                                                        <span class="d-block fw-semibold text-dark" style="font-size: 13px;">{{ $users->created_at->format('d/m/Y : H:i:s') }}</span>
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
                                                <a class="nav-link" data-bs-toggle="tab" href="#facture">Facture ({{$factCltEntCount}})</a>
                                            </li>
                                             <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#commande">Commande ({{$cmdClientCount}})</a>
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
                                                <a href="{{asset('bienvenue?active=1')}}" wire:navigate class="btn btn-sm btn-bleu d-none d-md-block ms-auto"><i class="fa fa-home"></i> Accueil</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-content">
                                        <div id="home" class="container-fluid tab-pane active pas_bordure">  
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12">   
                                                    <div class="card shadow-sm border border-light-subtle rounded-3 bg-white p-2">
                                                        <h6 class="fw-bold text-bleu mb-1"><i class="fas fa-user-circle" style=" color: #393b83;" title="Souscription"></i> {{$title_fils}}</h6>
                                                        <div class="position-relative custom-scroll pe-3" style="height: calc(100vh - 307px); overflow-y: auto;">  
                                                            <div class="hauteur_ecran">   
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="tab-content">
                                                                            <div id="home" class="container-fluid tab-pane active pas_bordure"> 
                                                                                <div class="row">
                                                                                    <div class="col-sm-6">
                                                                                        <div class="table-responsive">   
                                                                                            <table class="table text-nowrap m-0">                                        
                                                                                                <tbody>
                                                                                                    <tr>                                                                     
                                                                                                        <td class="titlefield text-muted fw-semibold">Nom & Prénom</td>                                                         
                                                                                                        <td class="fw-semibold">@if($users->titre){{$users->titre}}. @endif{{$users->name}}</td>         
                                                                                                    </tr> 
                                                                                                    <tr>                                                                     
                                                                                                        <td class="titlefield text-muted fw-semibold">Email <i class="fas fa-info-circle" title="Email permet de vous connecter de manière unique" data-bs-toggle="tooltip" data-bs-placement="top" style=" vertical-align: middle; cursor: help"></i></td>                                                         
                                                                                                        <td class="fw-bold">{{$users->email}} <span class="fw-semibold">@if($users->confirmer == 1) <span class="badge bg-success"><i class="fa-solid fa-thumbs-up"></i> Confirmer</span> @else <span class="badge bg-danger blink"><i class="fa-solid fa-thumbs-down"></i> Non-confirmer</span> @endif</span></td>         
                                                                                                    </tr>
                                                                                                    <tr>                                                                     
                                                                                                        <td class="titlefield text-muted fw-semibold">Rôle</td>                                                         
                                                                                                        <td class="fw-semibold">{{$users->type_user}}</td>         
                                                                                                    </tr>
                                                                                                    <tr>                                                                     
                                                                                                        <td class="titlefield text-muted fw-semibold">Téléphone</td>                                                         
                                                                                                        <td class="fw-semibold">{{$users->telephone}}</td>         
                                                                                                    </tr>
                                                                                                    <tr>       
                                                                                                        @php
                                                                                                            $nbjoursRestant = round((strtotime($users->date_valide) - strtotime($dateJour))/(60*60*24));
                                                                                                        @endphp                                                              
                                                                                                        <td class="titlefield text-muted fw-semibold">Validité</td>                                                         
                                                                                                        <td class="fw-semibold"><i class="fas fa-calendar-alt"></i> {{date('d-m-Y', strtotime($users->date_valide))}}
                                                                                                            @if($nbjoursRestant > 0 )
                                                                                                                (<span class="text-vert">{{$nbjoursRestant}} Jours</span>)
                                                                                                            @elseif($nbjoursRestant == 0 )
                                                                                                                (<span class="text-warning" style="font-size: 10px;">Dernier jour</span>)
                                                                                                            @else
                                                                                                                (<span class="text-danger" style="font-size: 10px;">Dépassée</span>)
                                                                                                            @endif                                                                    
                                                                                                        </td>         
                                                                                                    </tr>
                                                                                                    <tr>                                                                     
                                                                                                        <td class="titlefield text-muted fw-semibold">Sexe</td>                                                         
                                                                                                        <td class="fw-semibold">
                                                                                                            @if($users->sexe == "Masculin")
                                                                                                                <span class="text-success" style="font-size: 11px">{{$users->sexe}}</span>
                                                                                                            @else 
                                                                                                                <span class="text-danger" style="font-size: 11px">{{$users->sexe}}</span>
                                                                                                            @endif                                                                        
                                                                                                        </td>         
                                                                                                    </tr>
                                                                                                    <tr>                                                                     
                                                                                                        <td class="titlefield text-muted fw-semibold">Salarié</td>                                                         
                                                                                                        <td class="fw-semibold">
                                                                                                            @if($users->salarie == 1)
                                                                                                                <span class="text-vert" style="font-size: 11px">Oui</span>
                                                                                                            @else 
                                                                                                                <span class="text-bleu" style="font-size: 11px">Non</span>
                                                                                                            @endif
                                                                                                        </td>         
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
                                                                                                        <td class="titlefield text-muted fw-semibold">Société</td>                                                         
                                                                                                        <td class="fw-semibold text-vert">{{$users->societe}} (@if($users->societe_mere == "Inconnue") <span class="blink text-danger" data-toggle="tooltip" title="Société mère">{{$users->societe_mere}}</span> @else <span class="text-bleu" data-toggle="tooltip" title="Société mère">{{$users->societe_mere}}</span> @endif)</td>         
                                                                                                    </tr>                                                                   
                                                                                                    <tr>                                                                     
                                                                                                        <td class="titlefield text-muted fw-semibold">Poste de travail | Départ.</td>                                                         
                                                                                                        <td class="fw-semibold">{{$users->poste_travail}} @if($users->departement)|@endif {{$users->departement}}</td>         
                                                                                                    </tr>
                                                                                                    <tr>                                                                     
                                                                                                        <td class="titlefield text-muted fw-semibold">Nationalité</td>                                                         
                                                                                                        <td class="fw-semibold">{{$users->nationalite}}</td>         
                                                                                                    </tr>                                                                
                                                                                                    <tr>                                                                     
                                                                                                        <td class="titlefield text-muted fw-semibold">Catégorie / Echelon</td>                                                         
                                                                                                        <td class="fw-semibold">{{$users->categorie}} @if($users->echelon)/@endif {{$users->echelon}}</td>         
                                                                                                    </tr>
                                                                                                    <tr>                                                                     
                                                                                                        <td class="titlefield text-muted fw-semibold">Horaire journalier</td>                                                         
                                                                                                        <td class="fw-semibold"><span class="text-bleu" style="font-size: 11px">{{$users->horaire_journalier}}@if($users->horaire_journalier)H @endif</span></td>         
                                                                                                    </tr>
                                                                                                    <tr>                                                                     
                                                                                                        <td class="titlefield text-muted fw-semibold">Date de début du contrat</td>                                                         
                                                                                                        <td class="fw-semibold"><i class="fas fa-calendar-alt"></i> @if($users->date_debut_contrat) {{date('d-m-Y', strtotime($users->date_debut_contrat))}} @else Non défini @endif</td>         
                                                                                                    </tr>
                                                                                                    <tr>                                                                     
                                                                                                        <td class="titlefield text-muted fw-semibold">État civil</td>                                                         
                                                                                                        <td class="fw-semibold">{{$users->etat_civil}}</td>         
                                                                                                    </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="table-responsive"> 
                                                                                    <div class="d-flex align-items-center justify-content-between gap-3 pt-3 px-2">
                                                                                        <div class="">
                                                                                            <h5 class="text-bleu"><i class="fas fa-info-circle" style=" color: #393b83;" title="Informations RH"></i> Informations RH</h5>
                                                                                        </div>
                                                                                        <div>                                                                                                                        
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="table-responsive border-top rounded-0">
                                                                                        <table class="table table-striped table-hover text-nowrap m-0"> 
                                                                                            <thead>
                                                                                                <tr>
                                                                                                    <th class="fond_entete_table">Matricule</th>   
                                                                                                    <th class="fond_entete_table">Mode paiement</th> 
                                                                                                    <th class="fond_entete_table">RIB</th> 
                                                                                                    <th class="fond_entete_table">Numéro compte</th> 
                                                                                                    <th class="fond_entete_table">N.I.U</th> 
                                                                                                    <th class="fond_entete_table">CNI</th> 
                                                                                                    <th class="fond_entete_table">Type d'employé</th> 
                                                                                                    <th class="fond_entete_table">Type de contrat</th> 
                                                                                                    <th class="fond_entete_table">Type de salaire</th> 
                                                                                                    <th class="fond_entete_table">Début contrat</th>
                                                                                                    <th class="fond_entete_table">Date</th>
                                                                                                </tr>
                                                                                            </thead>                                       
                                                                                            <tbody>
                                                                                                @foreach($user as $users) 
                                                                                                <tr> 
                                                                                                    <td class="fw-semibold">@if($users->matricule)<i class="fas fa-user-circle text-bleu"></i>@endif {{$users->matricule}}</td> 
                                                                                                    <td class="fw-semibold">@if($users->mode_paiement)<i class="fas fa-file-invoice-dollar"></i>@endif {{$users->mode_paiement}}</a></td>                                                                      
                                                                                                    <td class="fw-semibold">{{$users->rib}}</td>
                                                                                                    <td class="fw-semibold">{{$users->numero_compte}}</td>
                                                                                                    <td class="fw-semibold">{{$users->niu}}</td>
                                                                                                    <td class="fw-semibold">{{$users->cni}}</td>
                                                                                                    <td class="fw-semibold">{{$users->type_employe}}</td>
                                                                                                    <td class="fw-semibold text-danger">{{$users->type_contrat}}</td>
                                                                                                    <td class="fw-semibold">{{$users->type_salaire}}</td>                                                                    
                                                                                                    <td class="fw-semibold">@if($users->date_debut_contrat) {{date('d-m-Y', strtotime($users->date_debut_contrat))}} @endif</td>
                                                                                                    <td class="fw-semibold">{{date('d-m-Y H:i:s', strtotime($users->created_at))}}</td>
                                                                                                </tr> 
                                                                                                @endforeach 
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>
                                                                                    <div class="d-flex align-items-center justify-content-between gap-3 pt-4 px-2">
                                                                                        <div class="">
                                                                                            <h5 class="text-bleu"><i class="fas fa-users text-danger" style=" color: #393b83;" title="Mouvement de stock"></i> Urgence</h5>
                                                                                        </div>
                                                                                        <div>                                                                                                                        
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="table-responsive border-top rounded-0">
                                                                                        <table class="table table-striped table-hover text-nowrap m-0"> 
                                                                                            <thead>
                                                                                                <tr>
                                                                                                    <th class="fond_entete_table">Nom du conjoint(e)</th>   
                                                                                                    <th class="fond_entete_table">Persone à contacter (urgence)</th> 
                                                                                                    <th class="fond_entete_table">Téléphone (urgence)</th> 
                                                                                                    <th class="fond_entete_table">Note</th> 
                                                                                                    <th class="fond_entete_table">Modif.</th>
                                                                                                    <th class="fond_entete_table">Auteur</th>   
                                                                                                </tr>
                                                                                            </thead>                                       
                                                                                            <tbody>
                                                                                                @foreach($user as $users) 
                                                                                                    <tr>
                                                                                                        <td class="fw-semibold">@if($users->nom_conjoint)<i class="fas fa-user-circle text-danger"></i>@endif {{$users->nom_conjoint}}</td> 
                                                                                                        <td class="fw-semibold">@if($users->persone_contact_urgence)<i class="fa fa-user-circle text-danger"></i>@endif {{$users->persone_contact_urgence}}</a></td>                                                                      
                                                                                                        <td class="fw-semibold">@if($users->telephone_urgence)<i class="fa fa-phone text-danger"></i>@endif {{$users->telephone_urgence}}</td>
                                                                                                        <td class="fw-semibold">{{$users->note_interne}}</td>                                                                                    
                                                                                                        <td class="fw-semibold">{{date('d-m-Y H:i:s', strtotime($users->updated_at))}}</td>
                                                                                                        <td class="fw-semibold">{{$users->nom_user}}</td>                                                                                               
                                                                                                    </tr>
                                                                                                @endforeach
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>
                                                                                </div>                                           
                                                                            </div>                                                                                       
                                                                            <div id="evenement" class="container-fluid tab-pane fade pas_bordure">  
                                                                                <div class="row">
                                                                                    <div class="col-sm-6"> 
                                                                                        <div class="table-responsive">  
                                                                                            <table class="table text-nowrap m-0">                                        
                                                                                                <tbody>
                                                                                                    <tr>                                                                     
                                                                                                        <td class="titlefield text-muted fw-semibold">Créé par</td>                                                         
                                                                                                        <td class="fw-semibold text-bleu"><i class="fa fa-user-circle"></i> {{$users->nom_user}}</td>         
                                                                                                    </tr> 
                                                                                                    <tr>                                                                     
                                                                                                        <td class="titlefield text-muted fw-semibold">Date création</td>                                                         
                                                                                                        <td class="fw-semibold">{{date('d-m-Y H:i:s', strtotime($users->created_at))}}</td>         
                                                                                                    </tr>                                                                            
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-sm-6"> 
                                                                                        <div class="table-responsive">  
                                                                                            <table class="table text-nowrap m-0">                                        
                                                                                                <tbody>                                                                            
                                                                                                    @if($users->nom_user_modif)
                                                                                                        <tr>                                                                     
                                                                                                            <td class="titlefield text-muted fw-semibold">Modifié par</td>                                                         
                                                                                                            <td class="fw-semibold text-bleu"><i class="fa fa-user-circle"></i> {{$users->nom_user_modif}}</td>         
                                                                                                        </tr>
                                                                                                    @endif
                                                                                                    <tr>                                                                     
                                                                                                        <td class="titlefield text-muted fw-semibold">Date de dernière modification</td>                                                         
                                                                                                        <td class="fw-semibold">{{date('d-m-Y H:i:s', strtotime($users->updated_at))}}</td>         
                                                                                                    </tr>
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
                                                        </div>                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="facture" class="container-fluid tab-pane fade pas_bordure">  
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12">   
                                                    <div class="card shadow-sm border border-light-subtle rounded-3 bg-white p-2">
                                                        <h6 class="fw-bold text-bleu mb-1"><i class="fas fa-file-invoice-dollar" style=" color: #393b83;" title="Mouvement de stock"></i> Les dernières factures <span class="text-vert">{{$users->nom}}</span> ({{$factCltEntCount}})</h6>
                                                        <div class="position-relative custom-scroll pe-3" style="height: calc(100vh - 307px); overflow-y: auto;">
                                                            <div class="hauteur_ecran">    
                                                                <div class="table-responsive border-top">
                                                                    <table class="table table-striped table-hover text-nowrap m-0"> 
                                                                        <thead>
                                                                            <tr>
                                                                                <th class="fond_entete_table pointer">Référence</th>   
                                                                                <th class="fond_entete_table pointer">Client</th> 
                                                                                <th class="fond_entete_table pointer">Facturation</th> 
                                                                                <th class="fond_entete_table pointer">Règlement</th> 
                                                                                <th class="fond_entete_table pointer text-end">Montant TTC</th> 
                                                                                <th class="fond_entete_table pointer text-end">Reçu</th> 
                                                                                <th class="fond_entete_table pointer text-end">Créance</th> 
                                                                                <th class="fond_entete_table pointer text-end">Marge</th>                                                            
                                                                                <th class="fond_entete_table pointer">Etat</th>
                                                                                <th class="fond_entete_table pointer">Date</th>
                                                                            </tr>
                                                                        </thead>                                       
                                                                        <tbody>
                                                                            @foreach($factClient_entete as $factClient_entetes) 
                                                                            <tr> 
                                                                                <td class="fw-semibold pointer" title="Cliquez pour voir les détails de cette facture" data-bs-toggle="tooltip" data-bs-placement="top" style=" vertical-align: middle; cursor: help"><a href="nouveau_fact_clt?id={{$factClient_entetes->id}}&ref={{$factClient_entetes->code_facture}}&active=7&champ=1-1&choix=1" wire:navigate class="text-bleu"><i class="fas fa-file-invoice-dollar text-bleu" aria-hidden="true"></i> {{$factClient_entetes->code_facture}}</a></td> 
                                                                                <td class="fw-semibold" title="Voir toutes ses factures" data-bs-toggle="tooltip" data-bs-placement="top" style=" vertical-align: middle; cursor: help"><a href="listing_fact_clt?nom_client={{$factClient_entetes->nom_client}}&date_debut={{date('Y-m-d', strtotime('-1 year'))}}&date_fin={{date('Y-m-d')}}&active=7&champ=1-1&choix=1" wire:navigate class="text-bleu">@if($factClient_entetes->nom_client) <i class="fa fa-user-circle"></i> @endif {{substr($factClient_entetes->nom_client,0,52) > substr($factClient_entetes->nom_client,0,51) ? substr($factClient_entetes->nom_client,0,52).'...': $factClient_entetes->nom_client}}</a></td>                                                                      
                                                                                <td class="">{{date('d-m-Y', strtotime($factClient_entetes->date_facturation))}}</td>
                                                                                <td class="">{{$factClient_entetes->mode_reglement}}</td>
                                                                                <td class="fw-semibold text-end">{{number_format($factClient_entetes->montant_ttc,0,',',' ')}}</td>
                                                                                <td class="fw-semibold text-end">{{number_format($factClient_entetes->montant_recu,0,',',' ')}}</td>
                                                                                <td class="fw-semibold text-end">{{number_format($factClient_entetes->reste_a_percevoir,0,',',' ')}}</td>
                                                                                <td class="fw-semibold text-end">{{number_format($factClient_entetes->marge,0,',',' ')}}</td>
                                                                                <td class="text-start">
                                                                                    @if($factClient_entetes->etat == "Brouillon")
                                                                                        <span class="badge bg-secondary py-1" title="Facture {{$factClient_entetes->etat}}"><i class="fa fa-box"></i> {{$factClient_entetes->etat}}</span>
                                                                                    @elseif($factClient_entetes->etat == "Impayée")
                                                                                        <span class="badge bg-danger py-1" title="Facture {{$factClient_entetes->etat}}"><i class="fa fa-times-circle"></i> {{$factClient_entetes->etat}}</span>
                                                                                    @elseif($factClient_entetes->etat == "Payée")
                                                                                        <span class="badge bg-success py-1" title="Facture {{$factClient_entetes->etat}}"><i class="fa fa-check-circle"></i> {{$factClient_entetes->etat}}</span>
                                                                                    @elseif($factClient_entetes->etat == "Commencée")
                                                                                        <span class="badge bg-info py-1" title="Facture {{$factClient_entetes->etat}}"><i class="fa fa-recycle"></i> {{$factClient_entetes->etat}}</span>
                                                                                    @endif                                                 
                                                                                </td> 
                                                                                <td class="">{{date('d-m-Y H:i:s', strtotime($factClient_entetes->created_at))}}</td>
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
                                        <div id="commande" class="container-fluid tab-pane fade pas_bordure">  
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12">   
                                                    <div class="card shadow-sm border border-light-subtle rounded-3 bg-white p-2">
                                                        <h6 class="fw-bold text-bleu mb-1"><i class="fas fa-file-invoice-dollar" style=" color: #393b83;" title="Mouvement de stock"></i> Les dernières commandes ({{$cmdClientCount}})</h6>
                                                        <div class="position-relative custom-scroll pe-3" style="height: calc(100vh - 307px); overflow-y: auto;">
                                                            <div class="hauteur_ecran">    
                                                                <div class="table-responsive border-top">
                                                                    <table class="table table-striped table-hover text-nowrap m-0"> 
                                                                        <thead>
                                                                            <tr>
                                                                                <th class="fond_entete_table pointer">Référence</th>   
                                                                                <th class="fond_entete_table pointer">Client</th> 
                                                                                <th class="fond_entete_table pointer">Commande</th> 
                                                                                <th class="fond_entete_table pointer">Livraison</th> 
                                                                                <th class="fond_entete_table pointer">Règlement</th> 
                                                                                <th class="fond_entete_table pointer text-end">Montant TTC</th> 
                                                                                <th class="fond_entete_table pointer text-end">Reçu</th> 
                                                                                <th class="fond_entete_table pointer text-end">Créance</th> 
                                                                                <th class="fond_entete_table pointer text-end">Marge</th> 
                                                                                <th class="fond_entete_table pointer">Etat</th>        
                                                                                <th class="fond_entete_table pointer">Facturé</th> 
                                                                                <th class="fond_entete_table pointer">Expédition</th>        
                                                                                <th class="fond_entete_table pointer">Date</th>
                                                                            </tr>
                                                                        </thead>                                       
                                                                        <tbody>
                                                                            @foreach($cmd_client as $cmd_clients) 
                                                                                <tr>
                                                                                    <td class="fw-semibold pointer" title="Cliquez pour voir les détails de cette commande" data-bs-toggle="tooltip" data-bs-placement="top" style=" vertical-align: middle; cursor: help"><a href="nouveau_cmd_clt?id={{$cmd_clients->id}}&ref={{$cmd_clients->code_commande}}&active=6&champ=1-1&choix=2" wire:navigate><i class="fas fa-file-invoice text-danger" aria-hidden="true"></i> {{$cmd_clients->code_commande}}</a></td> 
                                                                                    <td class="fw-semibold" title="Voir toutes ses commandes" data-bs-toggle="tooltip" data-bs-placement="top" style=" vertical-align: middle; cursor: help"><a href="listing_cmd_clt?nom_client={{$cmd_clients->nom_client}}&date_debut={{date('Y-m-d', strtotime('-1 year'))}}&date_fin={{date('Y-m-d')}}&active=6&champ=1-1&choix=2" wire:navigate class="text-bleu">@if($cmd_clients->nom_client) <i class="fa fa-user-circle"></i> @endif {{substr($cmd_clients->nom_client,0,52) > substr($cmd_clients->nom_client,0,51) ? substr($cmd_clients->nom_client,0,52).'...': $cmd_clients->nom_client}}</a></td>                                                                      
                                                                                    <td class="">{{date('d-m-Y', strtotime($cmd_clients->date_commande))}}</td>
                                                                                    <td class="">{{date('d-m-Y', strtotime($cmd_clients->date_livraison))}}</td>
                                                                                    <td class="">{{$cmd_clients->mode_reglement}}</td>
                                                                                    <td class="fw-semibold text-end">{{number_format($cmd_clients->montant_ttc,0,',',' ')}}</td>
                                                                                    <td class="fw-semibold text-end">{{number_format($cmd_clients->montant_recu,0,',',' ')}}</td>
                                                                                    <td class="fw-semibold text-end">{{number_format($cmd_clients->reste_a_percevoir,0,',',' ')}}</td>
                                                                                    <td class="fw-semibold text-end">{{number_format($cmd_clients->marge,0,',',' ')}}</td>
                                                                                    <td class="text-start">
                                                                                        @if($cmd_clients->etat == "Brouillon")
                                                                                            <span class="badge bg-secondary py-1" title="Commande {{$cmd_clients->etat}}"><i class="fa fa-box"></i> {{$cmd_clients->etat}}</span>
                                                                                        @elseif($cmd_clients->etat == "Impayée")
                                                                                            <span class="badge bg-danger py-1" title="Commande {{$cmd_clients->etat}}"><i class="fa fa-times-circle"></i> {{$cmd_clients->etat}}</span>
                                                                                        @elseif($cmd_clients->etat == "Payée")
                                                                                            <span class="badge bg-success py-1" title="Commande {{$cmd_clients->etat}}"><i class="fa fa-check-circle"></i> {{$cmd_clients->etat}}</span>
                                                                                        @elseif($cmd_clients->etat == "Validée")
                                                                                            <span class="badge bg-success py-1" title="Commande {{$cmd_clients->etat}}"><i class="fa fa-check"></i> {{$cmd_clients->etat}}</span>
                                                                                        @endif                                                 
                                                                                    </td>  
                                                                                    <td class="fw-semibold">
                                                                                        @if($cmd_clients->nbre_facture > 0)
                                                                                            <span class="badge bg-info" title="Facture non créée"><i class="fas fa-file-invoice-dollar"></i> Oui</span>
                                                                                        @else
                                                                                            <span class="badge bg-warning" title="Facture non créée"><i class="fas fa-file-invoice-dollar"></i> Non</span>
                                                                                        @endif
                                                                                    </td>                                             
                                                                                    <td class="text-start">
                                                                                        @if($cmd_clients->etat_expedi == "Brouillon")
                                                                                        <span class="badge bg-secondary py-1" title="Expédition {{$cmd_clients->etat_expedi}}"><i class="fas fa-dolly"></i> {{$cmd_clients->etat_expedi}}</span>
                                                                                        @elseif($cmd_clients->etat_expedi == "Clôturée")
                                                                                            <span class="badge bg-danger py-1" title="Expédition {{$cmd_clients->etat_expedi}}"><i class="fas fa-dolly"></i> {{$cmd_clients->etat_expedi}}</span>
                                                                                        @elseif($cmd_clients->etat_expedi == "Partiel")
                                                                                            <span class="badge bg-primary py-1" title="Expédition {{$cmd_clients->etat_expedi}}"><i class="fas fa-dolly"></i> {{$cmd_clients->etat_expedi}}</span>
                                                                                        @elseif($cmd_clients->etat_expedi == "")
                                                                                            <span class="badge bg-light text-dark py-1" title="Expédition non créée"><i class="fas fa-dolly"></i> Non créée</span>
                                                                                        @endif   
                                                                                    </td>                                            
                                                                                    <td class="">{{date('d-m-Y H:i:s', strtotime($cmd_clients->created_at))}}</td>
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
                                        <div id="activite" class="container-fluid tab-pane fade pas_bordure">
                                            <div class="card shadow-sm">  
                                                <div class="card shadow-sm border border-light-subtle rounded-3 bg-white p-2">    
                                                    <h6 class="fw-bold text-bleu mb-3"><i class="fas fa-calendar-alt" style=" color: #393b83;" title="Derniers événements liés"></i> @if($logCount > 1) Activités récentes @else Activité récente @endif ({{$logCount}})</h6>
                                                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                                                        <div class="d-flex gap-2 align-items-center">
                                                            <div class="input-group input-group-sm border rounded-2" style="width: 240px; background: #fff;">
                                                                <input type="search" wire:model.live="activite" id="activite" class="form-control border-0 shadow-none bg-transparent" placeholder="Rechercher une activité..." style="font-size: 13px;">
                                                                <button class="btn border-0 text-muted shadow-none d-flex align-items-center" type="button">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 14px; height: 14px;">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                                                    </svg>
                                                                </button>
                                                            </div>                                                            
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
                                                    <div class="position-relative custom-scroll pe-2" style="height: calc(100vh - 423px); overflow-y: auto;">  
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
                                                    <div class="alert mt-3 mb-0 d-flex justify-content-between align-items-center rounded-2 px-3 py-2" style="background-color: #f4f8ff; border: 1px solid #cce0ff;">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#0d6efd" style="width: 18px; height: 18px;">
                                                                <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm8.706-1.442c1.146-.573 2.437.463 2.126 1.732l-1.162 4.646c-.23.916.634 1.56 1.393 1.144a.75.75 0 01.684 1.326c-1.63 1.09-3.7-.068-3.136-1.92l1.162-4.646c.15-.6-.35-1.12-1-.74a.75.75 0 01-.767-1.302zM12 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" />
                                                            </svg>
                                                            <span class="text-dark" style="font-size: 13px;">
                                                                <span class="fw-bold">Voir plus d'utilisateur,</span> 
                                                                <span class="text-muted ms-1">centralisez la gestion de vos collaborateurs au même endroit.</span>
                                                            </span>
                                                        </div>
                                                        <a href="{{asset('utilisateurs?active=12&champ=1-1')}}" wire:navigate class="text-decoration-none fw-semibold text-primary" style="font-size: 13px;">Voir plus</a>
                                                    </div>
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
    @include('livewire.administration.users.update_mon_compte') 
    @include('livewire.administration.users.image_mon_compte') 
</div>