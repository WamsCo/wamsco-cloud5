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
                                                    <span class="d-block fw-bold text-secondary" style="font-size: 11px;">Nombre Total produits</span>
                                                    <span class="d-block fw-semibold text-dark" style="font-size: 13px;">{{$nbreTotalProduit}}</span>
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
                                                    <span class="d-block fw-bold text-secondary" style="font-size: 11px;">Produit(s) avec Tva</span>
                                                    <span class="d-block fw-bold text-dark mt-1" style="font-size: 14px;">{{number_format($NbreTVA,0,',',' ')}} <span style="font-size: 10px;"></span> </span>
                                                </div>
                                            </div>                                            

                                            <div class="d-flex align-items-center px-3 border-end flex-fill">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 44px; height: 44px; background-color: #fff3e0; color: #fd7e14;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                    </svg>
                                                </div>
                                                <div class="text-nowrap">
                                                    <span class="fw-bold text-secondary" style="font-size: 11px;">Produit(s) désactivé(s)</span>
                                                    <span class="d-block fw-bold text-dark mt-1" style="font-size: 14px;">{{$NbreProdOff}} <span style="font-size: 10px;"></span></span>
                                                </div>
                                            </div>   
                                            
                                            <div class="d-flex align-items-center px-3 border-end flex-fill">                                                
                                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 44px; height: 44px; background-color: #eaf2ff; color: #0d6efd;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                                    </svg>
                                                </div>
                                                <div class="text-nowrap">
                                                    <span class="d-block fw-bold text-secondary" style="font-size: 11px;">Produit(s) sans Code barre</span>
                                                    <span class="d-block fw-bold text-dark mt-1" style="font-size: 13px;">{{$NbreProdSansCodeBarre}}</span>
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
                                            <a class="nav-link active" data-bs-toggle="tab" href="#home" title="Liste des produits"><i class="fas fa-cube text-bleu"></i> {{$title_fils}} (<span class="text-vert">{{$produitCount}}</span>)</a>                                                
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
                                            <button href="#" data-bs-toggle="modal" data-bs-target="#createProduitModal" title="Cliquez pour voir les détails" data-toggle="tooltip" class="btn btn-sm btn-danger ms-auto"><i class="fa fa-plus-circle"></i> Nouveau Produit</button>
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
                                                        <div class="d-flex align-items-center justify-content-between gap-1 mb-1">
                                                            <div class="">
                                                                <select name="" wire:model.lazy="parPage" id="par_page" class="form-control form-select bordure w-auto"> 
                                                                    @for($i = 20; $i <= 100; $i += 20)
                                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                                    @endfor        
                                                                </select> 
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between gap-1">
                                                                <a href="{{asset('exporter-produits?categorie='.$this->parCat.'&nature='.$this->parNature.'&produit='.$this->query)}}" class="btn btn-sm btn-success mt-2 d-none d-md-block mb-1"><i class="fa-solid fa-file-export"></i> Exporter Excel</a>
                                                                <a href="#" data-bs-toggle="modal" data-bs-target="#importerProduitsModal" class="btn btn-sm btn-warning mt-2 d-none d-md-block"><i class="fa-solid fa fa-upload"></i>  Importer Excel</a>
                                                                <a href="{{asset('storage/manuel_users/Excel_Produit_WamsCo.xlsx')}}" download="Excel_Produit_WamsCo" class="btn btn-sm btn-secondary mt-2 d-none d-md-block"><i class="fa-solid fa fa-download"></i>  Télécharger Modèle</a>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center justify-content-between gap-1">
                                                            <div>
                                                                <label for="parCat" class="sr-only">Recherche</label>
                                                                <input type="search" wire:model.live="parCat" id="parCat" class="form-control bordure" placeholder="Rechercher catégorie">
                                                            </div>
                                                            <div>
                                                                <label for="parNature" class="sr-only">Recherche</label>
                                                                <input type="search" wire:model.live="parNature" id="parNature" class="form-control bordure" placeholder="Rechercher nature">
                                                            </div>
                                                            <div>
                                                                <label for="query" class="sr-only">Recherche</label>
                                                                <input type="search" wire:model.live="query" id="query" class="form-control bordure" placeholder="Rechercher produit">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="table-responsive border-top">
                                                        <table class="table table-striped table-hover text-nowrap m-0"> 
                                                            <thead>
                                                                <tr>
                                                                    <th class="fond_entete_table">Image</th>
                                                                    <th class="fond_entete_table pointer" wire:click="setOrderField('nom_produit')">Produit <i class="fa fa-arrow-down-short-wide"></i></th>   
                                                                    <th class="fond_entete_table pointer" wire:click="setOrderField('reference')">Référence <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                                    <th class="fond_entete_table pointer" wire:click="setOrderField('code_barre')">Code barre <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                                    <th class="fond_entete_table pointer" wire:click="setOrderField('type_produit')">Type <i class="fa fa-arrow-down-short-wide"></i></th>                     
                                                                    <th class="fond_entete_table text-end pointer" wire:click="setOrderField('prix_achat')">Prix achat (PMP) <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                                    <th class="fond_entete_table text-end pointer" wire:click="setOrderField('prix_vente')">Prix vente <i class="fa fa-arrow-down-short-wide"></i></th>  
                                                                    <th class="fond_entete_table text-end pointer" wire:click="setOrderField('tva')">Tva <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                                    <th class="fond_entete_table pointer" wire:click="setOrderField('categorie')">Catégorie <i class="fa fa-arrow-down-short-wide"></i></th>
                                                                    <th class="fond_entete_table pointer" wire:click="setOrderField('nature_produit')">Nature <i class="fa fa-arrow-down-short-wide"></i></th>                     
                                                                    <th class="fond_entete_table pointer" wire:click="setOrderField('date_peremption')">Péremption <i class="fa fa-arrow-down-short-wide"></i></th>  
                                                                    <th class="fond_entete_table pointer" wire:click="setOrderField('etat')">Etat <i class="fa fa-arrow-down-short-wide"></i></th>                     
                                                                    <th class="fond_entete_table pointer" wire:click="setOrderField('created_at')">Date <i class="fa fa-arrow-down-short-wide"></i></th>
                                                                    <th class="fond_entete_table pointer"></th>
                                                                    <th class="fond_entete_table pointer"></th>
                                                                    <th class="fond_entete_table pointer text-end"></th> 
                                                                </tr>
                                                            </thead>                                       
                                                            <tbody>
                                                                @foreach($produit as $produits) 
                                                                    <tr>                                            
                                                                        @if($produits->image != null)
                                                                            <td><a class="text-bleu" href="detail_product?id={{$produits->id}}&active=4&champ=1-1&choix=2" wire:navigate><img class="img_produit" src="storage/{{$produits->image}}"/></a></td>
                                                                        @else
                                                                            <td><a class="text-bleu" href="detail_product?id={{$produits->id}}&active=4&champ=1-1&choix=2" wire:navigate><img class="img_produit" src="storage/default/image.png"/></a></td>
                                                                        @endif                                                                   
                                                                        <td class="fw-bold"><a class="text-bleu" href="detail_product?id={{$produits->id}}&active=4&champ=1-1&choix=2" wire:navigate><i class="fa fa-cube"></i> {{Str::limit($produits->nom_produit, 52)}}</a></td>                                                                      
                                                                        <td class=""><span class="">{{$produits->reference}}</span></td> 
                                                                        <td class=""><span class="">{{$produits->code_barre}}</span></td> 
                                                                        <td class="fw-semibold">
                                                                            @if($produits->type_produit == "Service")
                                                                                <span style="color:#224285;"> {{$produits->type_produit}}</span>
                                                                            @else 
                                                                                <span style="color:#525151;">{{$produits->type_produit}}</span>
                                                                            @endif
                                                                        </td>  
                                                                        <td class="text-center fw-semibold">{{number_format($produits->prix_achat,1,',',' ')}}</td>                              
                                                                        <td class="text-center fw-semibold">{{number_format($produits->prix_vente,0,' ',' ')}}</td> 
                                                                        <td class="text-end fw-semibold">
                                                                            @if($produits->tva > 0)
                                                                                <span style="color:#44b522;"> {{$produits->tva}}%</span>
                                                                            @else 
                                                                                <span style="color:#525151;">{{$produits->tva}}%</span>
                                                                            @endif
                                                                        </td>
                                                                        <td class="fw-semibold">{{$produits->categorie}}</td> 
                                                                        <td class="">
                                                                            @if($produits->nature_produit == "Matière première")
                                                                                <span style="color:#051e03; font-weight: 600;"> {{$produits->nature_produit}}</span>
                                                                            @else 
                                                                                <span style="color:#707070; font-weight: 600;">{{$produits->nature_produit}}</span>
                                                                            @endif
                                                                        </td> 
                                                                        @if($nbjoursRestant = round((strtotime($produits->date_peremption) - strtotime($dateJour))/(60*60*24)) >= 60)
                                                                            <td class="text-vert fs-10px fw-bold"><i class="fa fa-thumbs-up"></i> Reste {{round((strtotime($produits->date_peremption) - strtotime($dateJour))/(60*60*24))}} Jours</td>
                                                                        @elseif($nbjoursRestant = round((strtotime($produits->date_peremption) - strtotime($dateJour))/(60*60*24)) < 60 && $nbjoursRestant = round((strtotime($produits->date_peremption) - strtotime($dateJour))/(60*60*24)) > 30)
                                                                            <td class="text-primary fs-10px fw-bold"><i class="fa fa-exclamation-triangle"></i> {{round((strtotime($produits->date_peremption) - strtotime($dateJour))/(60*60*24))}} Jours avant péremption</td>
                                                                        @elseif($nbjoursRestant = round((strtotime($produits->date_peremption) - strtotime($dateJour))/(60*60*24)) <= 30 && $nbjoursRestant = round((strtotime($produits->date_peremption) - strtotime($dateJour))/(60*60*24)) >= 0) 
                                                                            @if(round((strtotime($produits->date_peremption) - strtotime($dateJour))/(60*60*24)) == 0)
                                                                                <td class="text-info fs-10px fw-bold"><i class="fa fa-exclamation-triangle"></i> Dernier Jour</td>
                                                                            @else
                                                                                <td class="text-warning fs-10px fw-bold"><i class="fa fa-exclamation-triangle"></i> Plus que {{round((strtotime($produits->date_peremption) - strtotime($dateJour))/(60*60*24))}} Jours</td>
                                                                            @endif            
                                                                        @elseif($nbjoursRestant = round((strtotime($produits->date_peremption) - strtotime($dateJour))/(60*60*24)) < 0 && $produits->date_peremption != null)                      
                                                                            <td class=""><span class="blink badge bg-danger"><i class="fa fa-thumbs-down"></i> Produit périmé</span></td>
                                                                        @else
                                                                            <td class="fs-10px fw-bold"><span class=""><i class="fa fa-exclamation-triangle"></i> Date non définie</span></td>
                                                                        @endif
                                                                        @if($produits->etat == 1)
                                                                            <td class="pointer text-center" wire:click.prevent="changeEtat({{$produits->id}}, '{{$produits->etat}}')" title="Cliquez pour changer l'état"><span class="badge bg-success"><i class="fa fa-check-circle"></i></span></td>
                                                                        @else
                                                                            <td class="pointer text-center" wire:click.prevent="changeEtat({{$produits->id}}, '{{$produits->etat}}')" title="Cliquez pour changer l'état"><span class="badge bg-danger"><i class="fa fa-times-circle blink"></i></span></td>
                                                                        @endif                                                                
                                                                        <td class="">{{date('d-m-Y H:i:s', strtotime($produits->created_at))}}</td>
                                                                        <td class=""><a href="detail_product?id={{$produits->id}}&active=4&champ=1-1&choix=2" wire:navigate class="btn-outline-muted pointer"><i class="fa fa-pencil" title="Cliquez pour modifier" data-bs-toggle="tooltip" data-bs-placement="top"></i></a></td>
                                                                        <td class=""><a href="#" wire:click="dupliquer({{$produits->id}})" class="btn-outline-muted pointer"><i class="fa fa-clone" title="Cliquez pour dupliquer" data-bs-toggle="tooltip" data-bs-placement="top"></i></a></td>
                                                                        <td class="taille_icon text-end">
                                                                            @if($confirmer === $produits->id)
                                                                                <a wire:click.prevent="supprimer({{$produits->id}})"><i class="fa fa-trash bg-red text-white w-32 px-1 py-1 rounded border pointer" style="font-size: 8px;" title="Cliquez pour confirmer la suppression" data-bs-toggle="tooltip" data-bs-placement="top"> Confirmer?</i></a>
                                                                            @else
                                                                                <a wire:click.prevent="confirmerDelete({{$produits->id}})" class="btn-outline-muted pointer"><i class="fa fa-trash" title="Cliquez pour supprimer" data-bs-toggle="tooltip" data-bs-placement="top"></i></a>
                                                                            @endif
                                                                        </td> 
                                                                    </tr>
                                                                @endforeach                                                                
                                                            </tbody>
                                                        </table>
                                                        <div class="bloc_pagination">{{$produit->links()}}</div> 
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
    {{-- Modal --}}
    @include('livewire.gestion-stock.produits.create_produits')
    @include('livewire.gestion-stock.produits.importation_produits')    
</div>

