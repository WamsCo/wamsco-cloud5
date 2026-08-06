<div>
    <div id="content" class="app-content">
        <div class="profile mb-2">
            <div class="profile-header">        
                <div class="profile-header-cover"></div>      
                <div class="profile-header-content">
                    <div class="profile-header-info">
                        <ol class="breadcrumb float-xl-start">
                            <li class="breadcrumb-item d-none d-md-block"><a href="bienvenue">Accueil /</a></li>
                            <li class="breadcrumb-item active"><a href="{{$lien}}" wire:navigate>&nbsp;{{$module}} /</a></li>
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
                                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 44px; height: 44px; background-color: #ffebee; color: #dc3545;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v3" />
                                                    </svg>
                                                </div>
                                                <div class="text-nowrap">
                                                    <span class="d-block fw-bold text-secondary" style="font-size: 11px;">Votre solde</span>
                                                    <span class="d-block fw-semibold text-dark" style="font-size: 14px;"><span class="fw-bold text-vert">{{number_format($soldeClient,0,',',' ')}}</span> <span style="font-size: 10px;">{{$this->devise}}</span></span>
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
                                                        {{-- {{ optional($derniereActivite?->updated_at)->diffForHumans() ?? 'Aucune activité' }} --}}
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
                                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 44px; height: 44px; background-color: #fff3e0; color: #fd7e14;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                    </svg>
                                                </div>
                                                <div class="text-nowrap">
                                                    <span class="fw-bold text-secondary" style="font-size: 11px;"> Mode de Paiement</span>
                                                    <span class="d-block fw-bold text-dark mt-1" style="font-size: 14px;">Paiement en FCFA <span style="font-size: 10px;"></span></span>
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
                                            <a class="nav-link active" data-bs-toggle="tab" href="#home" title="Liste des roles"><i class="fa fa-cog fa-spin text-bleu"></i> {{$title_fils}}</a>                                                
                                        </li>  
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#activite">Activités récentes (<span class="text-vert">{{$logCount}}</span>)</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="historique_paie_clt?active=12&champ=1-8" wire:navigate><i class="fa fa-list"></i> Voir l'historique de vos abonnements.</a> 
                                        </li>
                                    </ul>
                                    <!-- Tab panes -->                        
                                    <div class="d-flex align-items-center justify-content-between gap-1">
                                        {{-- <div class="d-flex align-items-center justify-content-between gap-1"> 
                                            <a href="facturationclt-pdf?code=Fact/191224/a0adc871" class="btn btn-sm btn-default" title="Cliquez pour imprimer" data-toggle="tooltip" target="blank"><i class="fa fa-print"></i>A4</a>
                                        </div>                            
                                        <div class="d-flex align-items-center justify-content-between gap-1">   
                                            <a href="#" class="btn btn-sm btn-danger ms-auto" title=" Cliquez pour ajouter" data-bs-toggle="modal" data-bs-target="#ajoutPosteModal"><i class="fa fa-plus-circle"></i> Nouveau poste</a>                         
                                        </div> --}}
                                    </div>
                                </div>
                                <div class="tab-content position-relative custom-scroll px-3" style="height: calc(100vh - 256px); overflow-y: auto;">
                                    <div id="home" class="container-fluid tab-pane active pas_bordure">  
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12">   
                                                <div class="table-responsive px-4">
                                                    <div class="hauteur_ecran">
                                                        <div class="row px-2">                                                                         
                                                            <div class="col-sm-12">
                                                                <div class="cardk mb-4">
                                                                    {{-- <div class="card-header d-flex align-items-center justify-content-between border-bottom">
                                                                        <h5 class="card-title text-bleu"><i class="fa fa-cog fa-spin text-danger" aria-hidden="true"></i> Prolongez votre abonnement</h5>
                                                                        <a href="listing_fact_clt" wire:navigate class="btn btn-secondary d-none d-md-block ms-auto py-1"><i class="fa fa-chevron-left"></i> Retour liste</a>
                                                                    </div> --}}
                                                                    <div class="card-body">
                                                                        <div class="container-fluid pt-0 px-0">
                                                                            <div class="row">
                                                                                <div class="col-md-12 col-sm-6">
                                                                                    <div class="table-responsive mt-1"> 
                                                                                        <ul class="nav nav_choix_plan nav-tabs margin_ajuste border-0 pb-1" role="tablist">
                                                                                            <li class="nav-item">
                                                                                                <a class="nav-link active pay-annuel" data-bs-toggle="tab" href="#home" style="">Paiement Mensuel</a>
                                                                                            </li>
                                                                                            <li class="nav-item">
                                                                                                <a class="nav-link pay-mois" data-bs-toggle="tab" href="#reste">Paiement Annuel</a>
                                                                                            </li>
                                                                                        </ul>
                                                                                        <div class="tab-content table-responsive border-0 rounded-1">
                                                                                            <div id="home" class="tab-pane active">                               
                                                                                                <div class="bloc_deux_plan pb-3">
                                                                                                    <div class="plan_independant">
                                                                                                        <div class="entete_plan">
                                                                                                            <h2 class="plan_titre">Indépendant</h2>
                                                                                                            <div style="text-align: center;">
                                                                                                                <div class="plan_gest_prix">
                                                                                                                    <div class="plan_prix_normal">10 000 FCFA</div>
                                                                                                                    <div class="plan_prix_barre">15 000 FCFA</div>
                                                                                                                </div>
                                                                                                                <div class="plan_user">01 Utilisateur / Mois</div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="corp_plan">
                                                                                                            <ul class="list-group">
                                                                                                                <li class="list-group-item fw-semibold"><span class="fw-bold">1</span>&nbsp;&nbsp;  Société</li>
                                                                                                                <li class="list-group-item fw-semibold"><span class="fw-bold">1</span>&nbsp;&nbsp;  Utilisateur / Mois</li>
                                                                                                                <li class="list-group-item fw-semibold"><span class="fw-bold">1</span>&nbsp;&nbsp;  Point de vente</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #fb868b"></i> Gestion des clients / Prospects (illimité)</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #fb868b"></i> Gestion des Fournisseurs (illimité)</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #fb868b"></i> Gestion des produits et services (illimité)</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #fb868b"></i> Documents de ventes (illimité) <i class="fas fa-info-circle" title="Commandes client, Factures client, Bon de livraison" data-bs-toggle="tooltip" data-bs-placement="top" style=" vertical-align: middle; cursor: help"></i></li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #fb868b"></i> Documents d'achats (illimité) <i class="fas fa-info-circle" title="Commandes fournisseur, Factures fournisseur, Bon de réception" data-bs-toggle="tooltip" data-bs-placement="top" style=" vertical-align: middle; cursor: help"></i></li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #fb868b"></i> Documents au format PDF / Ticket / Excel</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #fb868b"></i> Trésorerie en temps réel <i class="fas fa-info-circle" title="Règlements clients, Règlements fournisseurs" data-bs-toggle="tooltip" data-bs-placement="top" style=" vertical-align: middle; cursor: help"></i></li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #fb868b"></i> Gestion Financières <i class="fas fa-info-circle" title="Comptes bancaires, Écritures bancaires, Paiement divers et Virement interne" data-bs-toggle="tooltip" data-bs-placement="top" style=" vertical-align: middle; cursor: help"></i></li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #fb868b"></i> Gestion de stock et inventaire (illimité) <i class="fas fa-info-circle" title="Entrepôts (illimité), Mouvements, Transferts entre entrepôts, Inventaire etc." data-bs-toggle="tooltip" data-bs-placement="top" style=" vertical-align: middle; cursor: help"></i></li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #fb868b"></i> Statistiques</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #fb868b"></i> Gestion CRM</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #fb868b"></i> Gestion de la fabrication</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #fb868b"></i> Gestion des tâches</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #fb868b"></i> Gestion des tickets</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #fb868b"></i> Ecran cuisine (Restaurant)</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #fb868b"></i> Gestion des employés</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #fb868b"></i> Gestion des accès et privillèges</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #fb868b"></i> Gestion multi-société (-15% du prix)</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #fb868b"></i> Support client et aide à la prise en main</li>
                                                                                                            </ul>
                                                                                                        </div>
                                                                                                        <div>
                                                                                                            <a class="dash-button plan_btn_demarer" href="paiement?plan=Independant&abonnement=Mois" wire:navigate><i class="fa fa-check"></i> Démarer maintenant <span class="hidden-word"> </span></a>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="plan_standard">
                                                                                                        <div class="entete_plan">
                                                                                                            <h2 class="plan_titre">Standard</h2>
                                                                                                            <div style="text-align: center;">
                                                                                                                <div class="plan_gest_prix">
                                                                                                                    <div class="plan_prix_normal_telecharge">20 000 FCFA</div>
                                                                                                                    <div class="plan_prix_barre">25 000 FCFA</div>
                                                                                                                </div>
                                                                                                                <div class="plan_user">03 Utilisateurs / Mois</div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="corp_plan">
                                                                                                            <ul class="list-group">
                                                                                                                <li class="list-group-item fw-semibold"><span class="fw-bold">1</span>&nbsp;&nbsp;  Société</li>
                                                                                                                <li class="list-group-item fw-semibold"><span class="fw-bold">3</span>&nbsp;&nbsp;  Utilisateurs / Mois</li>
                                                                                                                <li class="list-group-item fw-semibold"><span class="fw-bold">3</span>&nbsp;&nbsp;  Points de vente</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #c26af6"></i> Gestion des clients / Prospects (illimité)</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #c26af6"></i> Gestion des Fournisseurs (illimité)</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #c26af6"></i> Gestion des produits et services (illimité)</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #c26af6"></i> Documents de ventes (illimité) <i class="fas fa-info-circle" title="Commandes client, Factures client, Bon de livraison" data-bs-toggle="tooltip" data-bs-placement="top" style=" vertical-align: middle; cursor: help"></i></li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #c26af6"></i> Documents d'achats (illimité) <i class="fas fa-info-circle" title="Commandes fournisseur, Factures fournisseur, Bon de réception" data-bs-toggle="tooltip" data-bs-placement="top" style=" vertical-align: middle; cursor: help"></i></li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #c26af6"></i> Documents au format PDF / Ticket / Excel</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #c26af6"></i> Trésorerie en temps réel <i class="fas fa-info-circle" title="Règlements clients, Règlements fournisseurs" data-bs-toggle="tooltip" data-bs-placement="top" style=" vertical-align: middle; cursor: help"></i></li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #c26af6"></i> Gestion Financières <i class="fas fa-info-circle" title="Comptes bancaires, Écritures bancaires, Paiement divers et Virement interne" data-bs-toggle="tooltip" data-bs-placement="top" style=" vertical-align: middle; cursor: help"></i></li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #c26af6"></i> Gestion de stock et inventaire (illimité) <i class="fas fa-info-circle" title="Entrepôts (illimité), Mouvements, Transferts entre entrepôts, Inventaire etc." data-bs-toggle="tooltip" data-bs-placement="top" style=" vertical-align: middle; cursor: help"></i></li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #c26af6"></i> Statistiques</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #c26af6"></i> Gestion CRM</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #c26af6"></i> Gestion de la fabrication</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #c26af6"></i> Gestion des tâches</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #c26af6"></i> Gestion des tickets</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #c26af6"></i> Ecran cuisine (Restaurant)</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #c26af6"></i> Gestion des employés</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #c26af6"></i> Gestion des accès et privillèges</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #c26af6"></i> Gestion multi-société (-15% du prix)</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #c26af6"></i> Support client et aide à la prise en main</li>                                                                
                                                                                                            </ul>
                                                                                                        </div>
                                                                                                        <div>
                                                                                                            <a class="dash-button plan_btn_demarer" href="paiement?plan=Standard&abonnement=Mois" wire:navigate><i class="fa fa-check"></i> Démarer maintenant</a>
                                                                                                        </div>
                                                                                                    </div> 
                                                                                                </div>                                          
                                                                                            </div>
                                                                                            <div id="reste" class="tab-pane">                               
                                                                                                <div class="bloc_deux_plan pb-3">
                                                                                                    <div class="plan_independant_an">
                                                                                                        <div class="entete_plan">
                                                                                                            <h2 class="plan_titre">Indépendant <span class="text-vert fw-semibold" style="font-size: 14px;">(Economisez 2 mois)</span></h2>
                                                                                                            <div style="text-align: center;">
                                                                                                                <div class="plan_gest_prix">
                                                                                                                    <div class="plan_prix_normal_an">100 000 FCFA</div>
                                                                                                                    <div class="plan_prix_barre">120 000 FCFA</div>
                                                                                                                </div>
                                                                                                                <div class="plan_user">01 Utilisateur / An</div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="corp_plan">
                                                                                                            <ul class="list-group">
                                                                                                                <li class="list-group-item fw-semibold"><span class="fw-bold">1</span>&nbsp;&nbsp;  Société</li>
                                                                                                                <li class="list-group-item fw-semibold"><span class="fw-bold">1</span>&nbsp;&nbsp;  Utilisateur / An</li>
                                                                                                                <li class="list-group-item fw-semibold"><span class="fw-bold">1</span>&nbsp;&nbsp;  Point de vente</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #1fb100"></i> Gestion des clients / Prospects (illimité)</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #1fb100"></i> Gestion des Fournisseurs (illimité)</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #1fb100"></i> Gestion des produits et services (illimité)</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #1fb100"></i> Documents de ventes (illimité) <i class="fas fa-info-circle" title="Commandes client, Factures client, Bon de livraison" data-bs-toggle="tooltip" data-bs-placement="top" style=" vertical-align: middle; cursor: help"></i></li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #1fb100"></i> Documents d'achats (illimité) <i class="fas fa-info-circle" title="Commandes fournisseur, Factures fournisseur, Bon de réception" data-bs-toggle="tooltip" data-bs-placement="top" style=" vertical-align: middle; cursor: help"></i></li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #1fb100"></i> Documents au format PDF / Ticket / Excel</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #1fb100"></i> Trésorerie en temps réel <i class="fas fa-info-circle" title="Règlements clients, Règlements fournisseurs" data-bs-toggle="tooltip" data-bs-placement="top" style=" vertical-align: middle; cursor: help"></i></li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #1fb100"></i> Gestion Financières <i class="fas fa-info-circle" title="Comptes bancaires, Écritures bancaires, Paiement divers et Virement interne" data-bs-toggle="tooltip" data-bs-placement="top" style=" vertical-align: middle; cursor: help"></i></li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #1fb100"></i> Gestion de stock et inventaire (illimité) <i class="fas fa-info-circle" title="Entrepôts (illimité), Mouvements, Transferts entre entrepôts, Inventaire etc." data-bs-toggle="tooltip" data-bs-placement="top" style=" vertical-align: middle; cursor: help"></i></li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #1fb100"></i> Statistiques</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #1fb100"></i> Gestion CRM</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #1fb100"></i> Gestion de la fabrication</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #1fb100"></i> Gestion des tâches</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #1fb100"></i> Gestion des tickets</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #1fb100"></i> Ecran cuisine (Restaurant)</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #1fb100"></i> Gestion des employés</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #1fb100"></i> Gestion des accès et privillèges</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #1fb100"></i> Gestion multi-société (-15% du prix)</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #1fb100"></i> Support client et aide à la prise en main</li>                                                               
                                                                                                            </ul>
                                                                                                        </div>
                                                                                                        <div>
                                                                                                            <a class="dash-button plan_btn_demarer" href="paiement?plan=Independant&abonnement=An" wire:navigate><i class="fa fa-check"></i> Démarer maintenant <span class="hidden-word"> </span></a>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="plan_independant">
                                                                                                        <div class="entete_plan">
                                                                                                            <h2 class="plan_titre">Standard <span class="text-danger fw-semibold" style="font-size: 14px;">(Economisez 2 mois)</span></h2>
                                                                                                            <div style="text-align: center;">
                                                                                                                <div class="plan_gest_prix">
                                                                                                                    <div class="plan_prix_normal">200 000 FCFA</div>
                                                                                                                    <div class="plan_prix_barre">240 000 FCFA</div>
                                                                                                                </div>
                                                                                                                <div class="plan_user">03 Utilisateurs / An</div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="corp_plan">
                                                                                                            <ul class="list-group">
                                                                                                                <li class="list-group-item fw-semibold"><span class="fw-bold">1</span>&nbsp;&nbsp;  Société</li>
                                                                                                                <li class="list-group-item fw-semibold"><span class="fw-bold">3</span>&nbsp;&nbsp;  Utilisateurs / An</li>
                                                                                                                <li class="list-group-item fw-semibold"><span class="fw-bold">3</span>&nbsp;&nbsp;  Points de vente</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #fb868b"></i> Gestion des clients / Prospects (illimité)</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #fb868b"></i> Gestion des Fournisseurs (illimité)</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #fb868b"></i> Gestion des produits et services (illimité)</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #fb868b"></i> Documents de ventes (illimité) <i class="fas fa-info-circle" title="Commandes client, Factures client, Bon de livraison" data-bs-toggle="tooltip" data-bs-placement="top" style=" vertical-align: middle; cursor: help"></i></li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #fb868b"></i> Documents d'achats (illimité) <i class="fas fa-info-circle" title="Commandes fournisseur, Factures fournisseur, Bon de réception" data-bs-toggle="tooltip" data-bs-placement="top" style=" vertical-align: middle; cursor: help"></i></li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #fb868b"></i> Documents au format PDF / Ticket / Excel</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #fb868b"></i> Trésorerie en temps réel <i class="fas fa-info-circle" title="Règlements clients, Règlements fournisseurs" data-bs-toggle="tooltip" data-bs-placement="top" style=" vertical-align: middle; cursor: help"></i></li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #fb868b"></i> Gestion Financières <i class="fas fa-info-circle" title="Comptes bancaires, Écritures bancaires, Paiement divers et Virement interne" data-bs-toggle="tooltip" data-bs-placement="top" style=" vertical-align: middle; cursor: help"></i></li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #fb868b"></i> Gestion de stock et inventaire (illimité) <i class="fas fa-info-circle" title="Entrepôts (illimité), Mouvements, Transferts entre entrepôts, Inventaire etc." data-bs-toggle="tooltip" data-bs-placement="top" style=" vertical-align: middle; cursor: help"></i></li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #fb868b"></i> Statistiques</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #fb868b"></i> Gestion CRM</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #fb868b"></i> Gestion de la fabrication</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #fb868b"></i> Gestion des tâches</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #fb868b"></i> Gestion des tickets</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #fb868b"></i> Ecran cuisine (Restaurant)</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #fb868b"></i> Gestion des employés</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #fb868b"></i> Gestion des accès et privillèges</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #fb868b"></i> Gestion multi-société (-15% du prix)</li>
                                                                                                                <li class="list-group-item fw-semibold"><i class="fa fa-check-circle" style="color: #fb868b"></i> Support client et aide à la prise en main</li>                                                                
                                                                                                            </ul>
                                                                                                        </div>
                                                                                                        <div>
                                                                                                            <a class="dash-button plan_btn_demarer" href="paiement?plan=Standard&abonnement=An" wire:navigate><i class="fa fa-check"></i> Démarer maintenant</a>
                                                                                                        </div>
                                                                                                    </div> 
                                                                                                </div>                                          
                                                                                            </div>
                                                                                        </div> 
                                                                                        <hr class="borderline" />
                                                                                        <div class="moyen_paiement">
                                                                                            <div class="col-md-12" style="display: flex;justify-content: center;" data-scroll-index="6"> 
                                                                                                <img src="site/default/files/image/modePaiement.jpg" style="width: 50%;" title="Moyen de paiement disponible.">                                        
                                                                                            </div>
                                                                                        </div>
                                                                                        <hr class="borderline"/>
                                                                                        <div class="block-pricing support">
                                                                                            <div style="font-size: 14px; font-weight:600;margin-top:15px;" class="blockTextTwo">
                                                                                                <div>Nous sommes disponible pour une assistance 24h/24 et 7j/7 via 
                                                                                                    <a href="https://api.whatsapp.com/send?phone=+237654258009&text=Bonjour WamsCo, nous souhaiterons avoir une assistance sur le paiement de votre application. Merci." target="_blank" title="cliquez pour envoyer un message whatsapp" rel="noopener"><i class="fab fa-whatsapp blink" style="color:green;font-weight:bold;"></i> <span class="text-bleu">+237 654 258 009</span> <i class="fa fa-phone"></i></a>
                                                                                                    <p class="supportExplanation"></p>
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
                                    </div>
                                    <div id="activite" class="container-fluid tab-pane fade pas_bordure">
                                        <div class="card shadow-sm">  
                                            <div class="card shadow-sm border border-light-subtle rounded-3 bg-white p-2">    
                                                <h6 class="fw-bold text-bleu mb-3"><i class="fas fa-calendar-alt" style=" color: #393b83;" title="Derniers événements liés"></i> @if($logCount > 1) Activités récentes @else Activité récente @endif ({{$logCount}})</h6>
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

