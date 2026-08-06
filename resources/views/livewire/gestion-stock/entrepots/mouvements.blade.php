<div x-data="{selection: @entangle('selection').defer}">
    <div id="content" class="app-content" wire:poll.visible.20s>  
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
                                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 44px; height: 44px; background-color: #f3e5f5; color: #6f42c1;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                                    </svg>
                                                </div>
                                                <div class="text-nowrap">
                                                    <span class="d-block fw-bold text-secondary" style="font-size: 11px;">Total mouvements</span>
                                                    <span class="d-block fw-semibold text-dark" style="font-size: 13px;">{{number_format($TotalMouvement,0,',',' ')}}</span>
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
                                                    <span class="d-block fw-bold text-secondary" style="font-size: 11px;">Total quantités</span>
                                                    <span class="d-block fw-bold text-dark mt-1" style="font-size: 14px;">{{number_format($quantite_total,0,',',' ')}} <span style="font-size: 10px;"></span></span>
                                                </div>
                                            </div>                                            

                                            <div class="d-flex align-items-center px-3 border-end flex-fill">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 44px; height: 44px; background-color: #fff3e0; color: #fd7e14;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                    </svg>
                                                </div>
                                                <div class="text-nowrap">
                                                    {{-- <span class="fw-bold text-secondary" style="font-size: 11px;">Valorisation achat(PMP)</span> --}}
                                                    {{-- <span class="d-block fw-bold text-dark mt-1" style="font-size: 14px;">{{number_format($valorisation_achat_total,1,',',' ')}} <span style="font-size: 10px;">{{$this->devise}}</span></span> --}}
                                                </div>
                                            </div>   
                                            
                                            {{-- <div class="d-flex align-items-center px-3 border-end flex-fill">                                                
                                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 44px; height: 44px; background-color: #eaf2ff; color: #0d6efd;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                                    </svg>
                                                </div>
                                                <div class="text-nowrap">
                                                    <span class="d-block fw-bold text-secondary" style="font-size: 11px;">Valeur à la vente</span>
                                                    <span class="d-block fw-bold text-dark mt-1" style="font-size: 14px;">{{number_format($valeur_vente_total,1,',',' ')}} <span style="font-size: 10px;">{{$this->devise}}</span></span>
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
                                            <a class="nav-link active" data-bs-toggle="tab" href="#home"><i class="fas fa-users text-bleu"></i> {{$title_fils}} (<span class="text-vert">{{$mouvCountAfficher}}</span>)</a>                                                
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
                                            {{-- <a href="nouveau_entrepot?active=4&champ=3-1&choix=1" wire:navigate class="btn btn-sm btn-danger ms-auto"><i class="fa fa-plus-circle"></i> Nouveau magasin</a> --}}
                                            <a href="{{asset('produit?active=4&champ=1-1&choix=2')}}"  wire:navigate title="Cliquez pour retourner" data-toggle="tooltip" class="btn btn-sm btn-bleu d-none d-md-block ms-auto"><i class="fa fa-chevron-left"></i> Retour liste</a>
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
                                                                    @for($i = 20; $i <= 200; $i += 20)
                                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                                    @endfor        
                                                                </select> 
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between gap-1">
                                                                <a href="{{asset('exporter-mouvements?date_debut='.$this->date_debut.'&date_fin='.$this->date_fin.'&ref='.$this->query.'&mag='.$this->ParMag)}}" class="btn btn-sm btn-success mt-2 d-none d-md-block mb-1"><i class="fa-solid fa-file-export"></i> Exporter Excel</a>
                                                                {{-- <h6><a href="{{asset('exporter-mouvement')}}" class="btn btn-sm btn-success mt-2 d-none d-md-block"><i class="fa-solid fa-file-export"></i> Exporter Excel</a></h6> --}}
                                                                {{-- <h6><a href="#" data-bs-toggle="modal" data-bs-target="#importerTiersModal" class="btn btn-sm btn-warning mt-2 d-none d-md-block"><i class="fa-solid fa fa-upload"></i>  Importer Excel</a></h6> --}}
                                                                {{-- <h6><a href="{{asset('storage/manuel_users/Excel_Tier_WamsCo.xlsx')}}" download="Excel_Tier_WamsCo" class="btn btn-sm btn-secondary mt-2 d-none d-md-block"><i class="fa-solid fa fa-download"></i>  Télécharger Modèle</a></h6> --}}
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center justify-content-between gap-1">
                                                            <div class="d-flex align-items-center justify-content-between gap-1">
                                                                <div class="form-group">                        
                                                                    <input type="date" name="date_debut" wire:model.lazy="date_debut" class="form-control bordure"/>
                                                                </div>
                                                                <div class="form-group">                       
                                                                    <input type="date" name="date_debut" wire:model.lazy="date_fin" class="form-control bordure"/>                            
                                                                </div>
                                                                <div class="form-group">
                                                                    <button class="btn btn-black" wire:click.prevent="trouver()"><i class="fa fa-check"></i></button>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <label for="ParMag" class="sr-only">Recherche</label>
                                                                <input type="search" wire:model.live="ParMag" id="ParMag" class="form-control bordure" placeholder="Rechercher entrepôt">
                                                            </div>
                                                            <div>
                                                                <label for="query" class="sr-only">Recherche</label>
                                                                <input type="search" wire:model.live="query" id="query" class="form-control bordure" placeholder="Rechercher référence">
                                                            </div>                                                            
                                                        </div>
                                                    </div>
                                                    <div class="table-responsive border-top">
                                                        <table class="table table-striped table-hover text-nowrap m-0"> 
                                                            <thead>
                                                                <tr>
                                                                    <th class="fond_entete_table pointer" wire:click="setOrderField('nom_produit')">Date <i class="fa fa-arrow-down-short-wide"></i></th>   
                                                                    <th class="fond_entete_table pointer" wire:click="setOrderField('reference')">Réf. produit <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                                    <th class="fond_entete_table pointer" wire:click="setOrderField('entrepot')">Entrepôt <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                                    <th class="fond_entete_table text-start pointer" wire:click="setOrderField('code_mouvement')">Code Inv./Mouv. <i class="fa fa-arrow-down-short-wide"></i></th>
                                                                    <th class="fond_entete_table text-start pointer" wire:click="setOrderField('libele_mouvement')">Libellé du mouvement <i class="fa fa-arrow-down-short-wide"></i></th>  
                                                                    <th class="fond_entete_table text-start pointer" wire:click="setOrderField('nom_user')">Auteur <i class="fa fa-arrow-down-short-wide"></i></th>
                                                                    <th class="fond_entete_table text-start pointer" wire:click="setOrderField('origine')">Origine <i class="fa fa-arrow-down-short-wide"></i></th>
                                                                    <th class="fond_entete_table text-center pointer" wire:click="setOrderField('quantite')">Qté <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                                </tr>
                                                            </thead>                                       
                                                            <tbody>
                                                                @if($mouvCountAfficher > 0)
                                                                    @foreach($mouvement as $mouvements) 
                                                                        <tr>                                                                     
                                                                            <td class="fw-semibold">{{date('d/m/Y H:i:s', strtotime($mouvements->created_at))}}</td>  
                                                                            <td class="text-bleu fw-semibold"><a href="detail_product?id={{$mouvements->id_produit}}&active=4&champ=1-1&choix=2" wire:navigate class="text-bleu"><i class="fa fa-cube"></i> {{$mouvements->reference}}</a></td>  
                                                                            <td class="text-bleu fw-semibold"><a href="detail_entrepot?id={{$mouvements->id_entrepot}}&active=4&champ=3-1&choix=2" wire:navigate class="text-bleu"><i class="fas fa-box-open"></i> {{$mouvements->entrepot}}</a></td>  
                                                                            <td class="">{{$mouvements->code_mouvement}}</td>         
                                                                            <td class="fw-semibold">{{$mouvements->libele_mouvement}}</td>
                                                                            <td class="text-bleu fw-semibold"><a href="detail_user?id={{$mouvements->user_id}}&active=12&champ=1-1" wire:navigate class="text-bleu"><i class="fa fa-user-circle"></i> {{$mouvements->nom_user}}</a></td>  
                                                                            <td class="text-start fw-semibold">
                                                                                <a href="@if($mouvements->statut == "INV")detail_inventaire?id={{$mouvements->id_inventaire}}&champ=3-1&choix=7 
                                                                                    @elseif($mouvements->statut == "EXP")detail_expedition_clt?id={{$mouvements->id_expedition}}&active=6&champ=1-1&choix=3 
                                                                                    @elseif($mouvements->statut == "RCP")detail_reception_fourni?id={{$mouvements->id_reception}}&active=6&champ=2-1&choix=2 
                                                                                    @elseif($mouvements->statut == "POS")detail_pos_session?id={{$mouvements->id_session_pos}}&active=5&champ=1-1 
                                                                                    @elseif($mouvements->statut == "OF")detail_ordre_fab?id={{$mouvements->id_ordre_fab}}&active=9&champ=2-1&choix=2 
                                                                                    @else 
                                                                                    @endif" 
                                                                                    wire:navigate class="text-bleu">{{$mouvements->origine}}
                                                                                </a>
                                                                            </td>
                                                                            <td class="text-center text-vert fw-bold">
                                                                                @if($mouvements->quantite > 0)
                                                                                +{{$mouvements->quantite}}                                                                
                                                                                @else
                                                                                <span class="text-danger">{{$mouvements->quantite}}</span>  
                                                                                @endif
                                                                            </td> 
                                                                        </tr>
                                                                    @endforeach
                                                                    <tr>
                                                                        <td class="text-muted fw-semibold">Total <i class="fas fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" style="vertical-align: middle; cursor: help" title="Total pour cette page"></i></td>
                                                                        <td colspan="6"></td>
                                                                        <td class="text-center fw-semibold" style="color:#006666">{{number_format($mouvSommeAfficher,0,',',' ')}}</td>
                                                                    </tr>
                                                                @else 
                                                                    <tr class="retire">
                                                                        <td colspan="8" class="text-center fw-semibold">
                                                                            <span class="fs-5"><i class="fa fa-people-carry text-bleu"></i> </span> <br>
                                                                            <span class="">Aucun mouvement aujourd'hui</span>
                                                                        </td>                                                                
                                                                    </tr>
                                                                @endif	 
                                                            </tbody>
                                                        </table>
                                                        <div class="bloc_pagination">{{$mouvement->links()}}</div>
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


                {{-- <div class="card mb-4 bg-2k">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title text-bleu"><i class="fas fa-people-carry text-danger" aria-hidden="true"></i> {{$title_fils}} ({{$mouvCountAfficher}})</h5>
                        <a href="nouveau_produit" wire:navigate class="btn btn-sm btn-danger ms-auto"><i class="fa fa-plus-circle"></i> Nouveau Produit</a>
                    </div>
                    <div class="card-header d-flex align-items-center justify-content-between table-responsive">
                        <div class="d-flex align-items-center justify-content-between gap-1">
                            <div class="">
                                <select name="" wire:model.lazy="parPage" id="par_page" class="form-control form-select bordure w-auto"> 
                                    @for($i = 20; $i <= 200; $i += 20)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor        
                                </select> 
                            </div>
                            <div>
                                <h6><a href="{{asset('exporter-produits')}}" class="btn btn-sm btn-success mt-2 d-none d-md-block">Exporter Excel</a></h6>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between gap-1">
                            <div class="d-flex align-items-center justify-content-between gap-1">
                                <div class="form-group">                        
                                    <input type="date" name="date_debut" wire:model.lazy="date_debut" class="form-control bordure"/>
                                </div>
                                <div class="form-group">                       
                                    <input type="date" name="date_debut" wire:model.lazy="date_fin" class="form-control bordure"/>                            
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-black" wire:click.prevent="trouver()"><i class="fa fa-check"></i></button>
                                </div>
                            </div>
                            <div>
                                <label for="ParMag" class="sr-only">Recherche</label>
                                <input type="search" wire:model.live="ParMag" id="ParMag" class="form-control bordure" placeholder="Rechercher entrepôt">
                            </div>
                            <div>
                                <label for="query" class="sr-only">Recherche</label>
                                <input type="search" wire:model.live="query" id="query" class="form-control bordure" placeholder="Rechercher référence">
                            </div>
                        </div>
                    </div>
                    <div class="card-body no_bordure">
                        <div class="table-outer">
                            <div class="table-responsive border-top rounded-0"> 
                                <table class="table table-striped table-hover text-nowrap m-0">
                                    <thead>
                                        <tr>
                                            <th class="fond_entete_table pointer" wire:click="setOrderField('nom_produit')">Date <i class="fa fa-arrow-down-short-wide"></i></th>   
                                            <th class="fond_entete_table pointer" wire:click="setOrderField('reference')">Réf. produit <i class="fa fa-arrow-down-short-wide"></i></th> 
                                            <th class="fond_entete_table pointer" wire:click="setOrderField('entrepot')">Entrepôt <i class="fa fa-arrow-down-short-wide"></i></th> 
                                            <th class="fond_entete_table text-start pointer" wire:click="setOrderField('code_mouvement')">Code Inv./Mouv. <i class="fa fa-arrow-down-short-wide"></i></th>
                                            <th class="fond_entete_table text-start pointer" wire:click="setOrderField('libele_mouvement')">Libellé du mouvement <i class="fa fa-arrow-down-short-wide"></i></th>  
                                            <th class="fond_entete_table text-start pointer" wire:click="setOrderField('nom_user')">Auteur <i class="fa fa-arrow-down-short-wide"></i></th>
                                            <th class="fond_entete_table text-start pointer" wire:click="setOrderField('origine')">Origine <i class="fa fa-arrow-down-short-wide"></i></th>
                                            <th class="fond_entete_table text-center pointer" wire:click="setOrderField('quantite')">Qté <i class="fa fa-arrow-down-short-wide"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($mouvCountAfficher > 0)
                                            @foreach($mouvement as $mouvements) 
                                                <tr>                                                                     
                                                    <td class="fw-semibold">{{date('d/m/Y H:i:s', strtotime($mouvements->created_at))}}</td>  
                                                    <td class="text-bleu fw-semibold"><a href="detail_product?id={{$mouvements->id_produit}}&active=4&champ=1-1&choix=2" wire:navigate class="text-bleu"><i class="fa fa-cube"></i> {{$mouvements->reference}}</a></td>  
                                                    <td class="text-bleu fw-semibold"><a href="detail_entrepot?id={{$mouvements->id_entrepot}}&active=4&champ=3-1&choix=2" wire:navigate class="text-bleu"><i class="fas fa-box-open"></i> {{$mouvements->entrepot}}</a></td>  
                                                    <td class="">{{$mouvements->code_mouvement}}</td>         
                                                    <td class="fw-semibold">{{$mouvements->libele_mouvement}}</td>
                                                    <td class="text-bleu fw-semibold"><a href="detail_user?id={{$mouvements->user_id}}&active=12&champ=1-1" wire:navigate class="text-bleu"><i class="fa fa-user-circle"></i> {{$mouvements->nom_user}}</a></td>  
                                                    <td class="text-start fw-semibold">
                                                        <a href="@if($mouvements->statut == "INV")detail_inventaire?id={{$mouvements->id_inventaire}}&champ=3-1&choix=7 
                                                            @elseif($mouvements->statut == "EXP")detail_expedition_clt?id={{$mouvements->id_expedition}}&active=6&champ=1-1&choix=3 
                                                            @elseif($mouvements->statut == "RCP")detail_reception_fourni?id={{$mouvements->id_reception}}&active=6&champ=2-1&choix=2 
                                                            @elseif($mouvements->statut == "POS")detail_pos_session?id={{$mouvements->id_session_pos}}&active=5&champ=1-1 
                                                            @elseif($mouvements->statut == "OF")detail_ordre_fab?id={{$mouvements->id_ordre_fab}}&active=9&champ=2-1&choix=2 
                                                            @else 
                                                            @endif" 
                                                            wire:navigate class="text-bleu">{{$mouvements->origine}}
                                                        </a>
                                                    </td>
                                                    <td class="text-center text-vert fw-bold">
                                                        @if($mouvements->quantite > 0)
                                                        +{{$mouvements->quantite}}                                                                
                                                        @else
                                                        <span class="text-danger">{{$mouvements->quantite}}</span>  
                                                        @endif
                                                    </td> 
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td class="text-muted fw-semibold">Total <i class="fas fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" style="vertical-align: middle; cursor: help" title="Total pour cette page"></i></td>
                                                <td colspan="6"></td>
                                                <td class="text-center fw-semibold" style="color:#006666">{{number_format($mouvSommeAfficher,0,',',' ')}}</td>
                                            </tr>
                                        @else 
                                            <tr class="retire">
                                                <td colspan="8" class="text-center fw-semibold">
                                                    <span class="fs-5"><i class="fa fa-people-carry text-bleu"></i> </span> <br>
                                                    <span class="">Aucun mouvement aujourd'hui</span>
                                                </td>                                                                
                                            </tr>
                                        @endif	
                                    </tbody>
                                </table>
                                <div class="bloc_pagination">{{$mouvement->links()}}</div> 
                            </div>                        
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>    
    </div>    
</div>


