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
                    <div class="card-header d-flex align-items-center justify-content-between">
                        {{-- <h5 class="card-title d-none d-md-block">Nom de l'entrepot ici</h5> --}}
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs margin_ajuste border-0" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#home">Magasin</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#menu1">Mouvements de stock</a>
                            </li>
                        </ul>
                        <!-- Tab panes -->                        
                        <div class="d-flex align-items-center justify-content-between gap-1">
                            <div class="d-flex align-items-center justify-content-between gap-1"> 
                                {{-- <a href="facturationcltkk-pdf?code=" class="btn btn-sm btn-default" title="Cliquez pour imprimer" data-toggle="tooltip" style="color: #161a1d;"><i class="fa fa-print"></i>Ticket</a> --}}
                                {{-- <a href="facturationclt-pdf?code=Fact/191224/a0adc871" class="btn btn-sm btn-default" title="Cliquez pour imprimer" data-toggle="tooltip" target="blank"><i class="fa fa-print"></i>A4</a> --}}
                            </div>                            
                            <div class="d-flex align-items-center justify-content-between gap-1">                            
                                <a href="entrepot?active=4&champ=3-1&choix=2" wire:navigate class="btn btn-sm btn-bleu d-none d-md-block ms-auto"><i class="fa fa-chevron-left"></i> Retour liste</a>
                                <button class="btn btn-sm btn-default new_color" @if($this->id) wire:click.prevent="precedant({{$this->id}})" @else wire:click.prevent="precedant({{$this->id_entrepot}})" @endif title="Précédant" data-toggle="tooltip"><i class="fa fa-chevron-left"></i></button>
                                <a href="#" class="btn btn-sm btn-default new_color" @if($this->id) wire:click.prevent="suivant({{$this->id}})" @else wire:click.prevent="suivant({{$this->id_entrepot}})" @endif title="Suivant" data-toggle="tooltip"><i class="fa fa-chevron-right"></i></a>
                            </div>
                        </div>
                    </div>
                    @foreach($entrepot as $entrepots)
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <div class="">
                                    <div class="photoref">
                                        <span class="fas fa-box-open  em080" style=" color: #ff5252;" title="No photo"></span>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="text-bleu">{{$entrepots->nom}}</h5>
                                    <span class="text-success">{{$entrepots->reference}}</span>
                                </div>
                            </div>
                            <div>
                                <div class="statusref">
                                    @if($entrepots->active == 1)
                                        <span class="badge badge-status4 badge-status" title="Activer">Activer</span>
                                    @else
                                        <span class="badge bg-danger badge-status" title="Désactiver">Désactiver </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="card-body no_bordure border-top taille_ecran_session">
                        <div class="row mx-0 pt-0">
                            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12 px-0 mb-1 border-end border-bottom rounded-bottom">
                                <div class="row mt-2">
                                    <div class="col-sm-6"> 
                                        <div class="table-responsive">  
                                            <table class="table text-nowrap m-0">                                        
                                                <tbody>
                                                    @foreach($entrepot as $entrepots) 
                                                        @if(!empty($entrepots->description))
                                                            <tr>                                                                     
                                                                <td class="titlefield text-muted fw-semibold">Description</td>                                                         
                                                                <td class="fw-semibold">
                                                                    {{substr($entrepots->description,0,58) > substr($entrepots->description,0,57) ? substr($entrepots->description,0,58).'...': $entrepots->description}}
                                                                </td>         
                                                            </tr> 
                                                        @endif
                                                    @endforeach 
                                                    <tr>                                                                     
                                                        <td class="titlefield text-muted fw-semibold">Nombre de produits</td>                                                         
                                                        <td class="fw-semibold">{{$stockCount}}</td>         
                                                    </tr>
                                                    <tr>                                                                     
                                                        <td class="titlefield text-muted fw-semibold">Nombre total de produits <i class="fas fa-info-circle" title="Les quantités sont affichées avec un arrondi automatique (+ ou -)" data-bs-toggle="tooltip" data-bs-placement="top" style=" vertical-align: middle; cursor: help"></i></td>                                                         
                                                        <td class="fw-semibold">{{$stockPieceCount}}</td>         
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
                                                            <td class="titlefield text-muted fw-semibold">Valorisation achat (PMP)</td>                                                         
                                                            <td class="fw-semibold">{{number_format($valorisation_achat_total,1,',',' ')}} XAF</td>         
                                                        </tr> 
                                                        <tr>                                                                     
                                                            <td class="titlefield text-muted fw-semibold">Dernier mouvement</td> 
                                                            <td class="fw-semibold">{{date('d/m/Y H:i:s', strtotime($dernierMouvmnt))}}</td> 
                                                        </tr>                                                
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-content">
                                    <div id="home" class="container-fluid tab-pane active pas_bordure">                                           
                                        <div class="d-flex align-items-center justify-content-between gap-3 pt-4 px-3">
                                            <div class="">
                                                <a href="update_entrepot?id={{$this->id}}&active=4&champ=3-1&choix=2" wire:navigate class="btn btn-sm btn-white text-bleu fw-bold ms-auto"><i class="fa fa-chevron-left"></i> Modifier magasin</a>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between gap-2 py-3">
                                                @foreach($entrepot as $entrepots)
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#correctionStockModal" wire:click.prevent="ajuster({{$entrepots->id}})" class="btn btn-sm btn-secondary fw-semibold ms-auto"><i class="fa fa-check"></i> Corriger le stock</a>
                                                @endforeach 
                                                <a href="nouveau_entrepot?active=4&champ=3-1&choix=1" wire:navigate class="btn btn-sm btn-danger ms-auto"><i class="fa fa-plus-circle"></i> Nouveau magasin</a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-6 col-xs-12 mb-2">
                                                <div class="d-flex align-items-center justify-content-between gap-3 pt-4">
                                                    <div class="px-3">
                                                        <h5 class="text-bleu"><i class="fas fa-box-open" style=" color: #393b83;" title="Mouvement de stock"></i> Stock ({{$stockCount}})</h5>
                                                    </div>
                                                    <div>
                                                    </div>
                                                </div>
                                                <div class="table-responsive border-top"> 
                                                    <table class="table table-striped table-hover text-nowrap m-0"> 
                                                        <thead>
                                                            <tr>
                                                            <th class="fond_entete_table">Produits</th>
                                                            <th class="fond_entete_table">Libellé</th>
                                                            <th class="fond_entete_table">Nature</th>
                                                            <th class="fond_entete_table text-end">Dernier prix</th>
                                                            <th class="fond_entete_table"><i class="fas fa-info-circle" title="Les quantités sont affichées avec un arrondi automatique (+ ou -)" data-bs-toggle="tooltip" data-bs-placement="top" style=" vertical-align: middle; cursor: help"></i> Nbre de pièces</th>
                                                            <th class="fond_entete_table text-end">Prix moyen pondéré</th>
                                                            <th class="fond_entete_table text-end">Valorisation achat(PMP)</th>
                                                            <th class="fond_entete_table text-end">Prix de vente unitaire</th>
                                                            <th class="fond_entete_table text-end">Valeur à la vente	</th>
                                                            <th class="fond_entete_table">Dernière modif.</th>
                                                            </tr>
                                                        </thead>                                       
                                                        <tbody>
                                                            @foreach($stock as $stocks) 
                                                            <tr> 
                                                                <td class="text-muted text-bleu"><a href="detail_product?id={{$stocks->id_produit}}&active=4&champ=1-1&choix=2" wire:navigate class="text-bleu fw-bold">{{$stocks->reference}}</a></td>                                                         
                                                                <td class="">{{$stocks->nom_produit}}</td>     
                                                                <td class="">
                                                                    @if($stocks->nature_produit == "Matière première")
                                                                        <span style="color:#051e03; font-weight: 600;"> {{$stocks->nature_produit}}</span>
                                                                    @else 
                                                                        <span style="color:#707070; font-weight: 600;">{{$stocks->nature_produit}}</span>
                                                                    @endif
                                                                </td>     
                                                                <td class="text-end fw-semibold">{{number_format($stocks->prix_achat_last,0,',',' ')}}</td>         
                                                                <td class="text-center fw-semibold @if($stocks->quantite <= 0) text-danger @endif">{{$stocks->quantite}}</td>         
                                                                <td class="text-end fw-semibold">{{number_format($stocks->prix_moyen_pondere_achat,2,',',' ')}}</td>         
                                                                <td class="text-end text-vert fw-semibold">{{number_format($stocks->valorisation_achat_total,2,',',' ')}}</td>         
                                                                <td class="text-end fw-semibold">{{number_format($stocks->prix_vente_unitaire,0,',',' ')}}</td>      
                                                                <td class="text-end fw-semibold">{{number_format($stocks->valeur_vente_total,2,',',' ')}}</td>      
                                                                <td class="">{{date('d-m-Y H:i:s', strtotime($stocks->updated_at))}}</td>      
                                                            </tr> 
                                                            @endforeach 
                                                            <tr>                                                                     
                                                                <td class="text-muted fw-semibold">Total</td>                                                         
                                                                <td class=""></td>         
                                                                <td class=""></td>         
                                                                <td class=""></td>         
                                                                <td class="text-center fw-bold text-bleu">{{$stockPieceCount}}</td>         
                                                                <td class=""></td>         
                                                                <td class="text-end fw-bold text-bleu">{{number_format($valorisation_achat_total,2,',',' ')}}</td>      
                                                                <td class=""></td>      
                                                                <td class="text-end fw-bold text-bleu">{{number_format($valeurVenteTotal,2,',',' ')}}</td>      
                                                                <td class=""></td>    
                                                            </tr> 
                                                        </tbody>
                                                    </table> 
                                                </div>                                          
                                            </div>                                                                                      
                                        </div>                                          
                                    </div>
                                    <div id="menu1" class="container-fluid tab-pane fade pas_bordure"><br>
                                        <div class="d-flex align-items-center justify-content-between gap-3 py-3 px-3">
                                            <div class="px-3">
                                                <h5 class="text-bleu"><i class="fas fa-people-carry" style=" color: #393b83;" title="Mouvement de stock"></i> Liste des mouvements de stock ({{$mouvCountAfficher}}/{{$mouvementCount}})</h5>
                                                </div>
                                                <div>
                                                @foreach($entrepot as $entrepots)
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#correctionStockModal" wire:click.prevent="ajuster({{$entrepots->id}})" class="btn btn-sm btn-secondary fw-semibold ms-auto"><i class="fa fa-check"></i> Corriger le stock</a>
                                                @endforeach 
                                                </div>
                                        </div>
                                        <div class="table-responsive border-top border-bottom border-start rounded-start"> 
                                            <table class="table table-striped table-hover text-nowrap m-0"> 
                                                <thead>
                                                    <tr>
                                                        <th class="fond_entete_table">Date</th>
                                                        <th class="fond_entete_table">Réf. produit</th>
                                                        <th class="fond_entete_table">Code Inv./Mouv.</th>
                                                        <th class="fond_entete_table">Libellé du mouvement</th>
                                                        <th class="fond_entete_table">Origine</th>
                                                        <th class="fond_entete_table text-center">Qté</th>
                                                    </tr>
                                                </thead>                                       
                                                <tbody>
                                                    @foreach($mouvement as $mouvements) 
                                                        <tr>    
                                                            <td class=""><a href="mouvements?dates={{date('Y-m-d', strtotime($mouvements->created_at))}}&active=4&champ=3-1&choix=3" wire:navigate class="text-bleu fw-bold" title="clique pour voir mouvements">{{date('d/m/Y H:i:s', strtotime($mouvements->created_at))}}</a></td>      
                                                            <td class="text-bleu"><a href="detail_product?id={{$mouvements->id_produit}}&active=4&champ=1-1&choix=2" wire:navigate class="text-bleu fw-bold">{{$mouvements->reference}}</a></td>                                                         
                                                            <td class=""><a href="mouvements?dates={{date('Y-m-d', strtotime($mouvements->created_at))}}&active=4&champ=3-1&choix=3" wire:navigate class="text-bleu fw-bold" title="clique pour voir mouvements">{{$mouvements->code_mouvement}}</a></td>
                                                            <td class="">{{$mouvements->libele_mouvement}}</td>      
                                                            <td class="text-start fw-semibold"><a href="@if($mouvements->statut == "INV")detail_inventaire?id={{$mouvements->id_inventaire}} @elseif($mouvements->statut == "EXP")detail_expedition_clt?id={{$mouvements->id_expedition}} @endif" wire:navigate class="text-bleu">{{$mouvements->origine}}</a></td>   
                                                            <td class="text-center text-vert fw-semibold">
                                                                @if($mouvements->quantite > 0)
                                                                    +{{$mouvements->quantite}}                                                                
                                                                @else
                                                                <span class="text-danger">{{$mouvements->quantite}}</span>  
                                                                @endif
                                                            </td>      
                                                        </tr> 
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 px-0 pt-3 mb-1 rounded-bottom">
                                <div class="hauteur_ecran">
                                    <div class="d-flex align-items-center justify-content-between px-2">
                                        <div class="">
                                            <h5 class="text-bleu"><i class="fas fa-calendar-alt" style=" color: #393b83;" title="Derniers événements liés"></i> @if($logCount > 1) Les {{$logCount}} derniers événements liés @else L'événement lié @endif</h5>
                                        </div>
                                        <div>                                                                                                                        
                                        </div>
                                    </div>
                                    <div class="table-responsive border-top rounded-0">  
                                        @foreach($log as $logs)
                                            <div class="d-flex flex-shrink-0 gap-2 px-2 py-2">
                                                <div class="d-flex flex-shrink-0 align-items-center flex-column bg-inherit align-items-start">
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
                                                <div class="d-flex flex-column flex-wrap align-items-baseline lh-1 gap-2">
                                                    <div>
                                                        <strong class="me-1">
                                                            <a href="detail_user?id={{$logs->user_id}}&active=12&champ=1-1" wire:navigate>{{$logs->user_email}} &nbsp; <span class="fw-500">{{date('d-m-Y H:i:s', strtotime($logs->created_at))}}</span></a>
                                                        </strong>
                                                    </div>
                                                    <div class="">
                                                        <span><i class="fas fa-calendar-alt text-danger"></i> {{$logs->id}}</span> &nbsp; <span><i class="fas fa-university text-dangerg"></i> {{$logs->user_societe}}</span>
                                                    </div>
                                                    <div class="text-bleu fw-semibold">
                                                        {{$logs->subject}}
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
    {{-- Modal --}}
    @include('livewire.gestion-stock.entrepots.correction_stock') 
</div>
