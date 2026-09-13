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
                <div class="card mb-4 bg-2k">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title text-bleu"><i class="fa fa-box-open text-danger" aria-hidden="true"></i> {{$title_fils}} ({{$stockCount}})</h5>
                        <a href="nouveau_produit?active=4&champ=1-1&choix=1" wire:navigate class="btn btn-sm btn-danger ms-auto"><i class="fa fa-plus-circle"></i> Nouveau Produit</a>
                    </div>
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center justify-content-between gap-1">
                            <div class="">
                                <select name="" wire:model.lazy="parPage" id="par_page" class="form-control form-select bordure w-auto"> 
                                    @for($i = 20; $i <= 100; $i += 20)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor        
                                </select> 
                            </div>
                            <div>
                                <h6><a href="{{asset('exporter-stocks')}}" class="btn btn-sm btn-success mt-2 d-none d-md-block">Exporter Excel</a></h6>
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
                    <div class="card-body no_bordure">
                        <div class="table-outer">
                            <div class="table-responsive border rounded-0">  
                                <table class="table table-striped table-hover text-nowrap m-0">
                                    <thead>
                                        <tr>
                                            {{-- <th class="fond_entete_table">Image</th> --}}
                                            <th class="fond_entete_table pointer" wire:click="setOrderField('nom_produit')">Produit <i class="fa fa-arrow-down-short-wide"></i></th>   
                                            <th class="fond_entete_table pointer" wire:click="setOrderField('reference')">Référence <i class="fa fa-arrow-down-short-wide"></i></th> 
                                            <th class="fond_entete_table pointer" wire:click="setOrderField('code_barre')">Code barre <i class="fa fa-arrow-down-short-wide"></i></th> 
                                            <th class="fond_entete_table pointer" wire:click="setOrderField('categorie')">Catégorie <i class="fa fa-arrow-down-short-wide"></i></th>
                                            <th class="fond_entete_table pointer" wire:click="setOrderField('type_produit')">Type <i class="fa fa-arrow-down-short-wide"></i></th>                     
                                            <th class="fond_entete_table pointer" wire:click="setOrderField('nature_produit')">Nature <i class="fa fa-arrow-down-short-wide"></i></th>     
                                            <th class="fond_entete_table text-end pointer" wire:click="setOrderField('quantites')">Quantité<i class="fa fa-arrow-down-short-wide"></i></th>
                                            <th class="fond_entete_table text-end pointer" wire:click="setOrderField('valorisationAchatTotal')">Valorisation achat(PMP) <i class="fa fa-arrow-down-short-wide"></i></th>  
                                            <th class="fond_entete_table text-end pointer" wire:click="setOrderField('valeurVentetotal')">Valeur à la vente <i class="fa fa-arrow-down-short-wide"></i></th>
                                            <th class="fond_entete_table text-start pointer" wire:click="setOrderField('created_at')">Date modif. <i class="fa fa-arrow-down-short-wide"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($totalStock as $totalStocks) 
                                        <tr>
                                            {{-- @if($totalStocks->image != null)
                                                <td><img class="img_produit" src="storage/{{$totalStocks->image}}" data-bs-toggle="modal" data-bs-target="#updateProduitModal" wire:click.prevent="edit({{$totalStocks->id_produit}})"/></td>
                                            @else
                                                <td><img class="img_produit" src="storage/default/image.png" data-bs-toggle="modal" data-bs-target="#updateProduitModal" wire:click.prevent="edit({{$totalStocks->id_produit}})"/></td>
                                            @endif --}}
                                            <td class="fw-bold"><a href="detail_product?id={{$totalStocks->id_produit}}&active=4&champ=1-1&choix=2" wire:navigate class="text-bleu"><i class="fa fa-cube"></i> {{substr($totalStocks->nom_produit,0,52) > substr($totalStocks->nom_produit,0,51) ? substr($totalStocks->nom_produit,0,52).'...': $totalStocks->nom_produit}}</a></td>                                                                      
                                            <td class="">{{$totalStocks->reference}}</td> 
                                            <td class="">{{$totalStocks->code_barre}}</td> 
                                            <td class="fw-semibold">{{$totalStocks->categorie}}</td> 
                                            <td class="fw-semibold">
                                                @if($totalStocks->type_produit == "Service")
                                                    <span style="color:#224285;"> {{$totalStocks->type_produit}}</span>
                                                @else 
                                                    <span style="color:#525151;">{{$totalStocks->type_produit}}</span>
                                                @endif
                                            </td> 
                                            <td class="">
                                                @if($totalStocks->nature_produit == "Matière première")
                                                    <span style="color:#051e03; font-weight: 600;"> {{$totalStocks->nature_produit}}</span>
                                                @else 
                                                    <span style="color:#707070; font-weight: 600;">{{$totalStocks->nature_produit}}</span>
                                                @endif
                                            </td> 
                                            <td class="text-end fw-semibold">                                                
                                                @if($totalStocks->quantites > $totalStocks->limite_stock_alerte)
                                                    <span class="text-success fw-bold">{{$totalStocks->quantites}}</span>
                                                @elseif($totalStocks->quantites > 0 && $totalStocks->quantites <= $totalStocks->limite_stock_alerte)
                                                    <span class="text-info fw-bold" title="Stock inférieur ou égale au stock d'alerte ({{$totalStocks->limite_stock_alerte}})"><i class="fas fa-exclamation-triangle text-danger"></i> {{$totalStocks->quantites}}</span>
                                                @else
                                                    <span class="blink text-danger fw-bold">{{$totalStocks->quantites}}</span>
                                                @endif
                                            </td>  
                                            <td class="text-end fw-semibold">{{number_format($totalStocks->valorisationAchatTotal,2,',',' ')}}</td> 
                                            <td class="text-end fw-semibold">{{number_format($totalStocks->valeurVentetotal,2,',',' ')}}</td> 
                                            <td class="text-start">{{date('d-m-Y H:i:s', strtotime($totalStocks->updated_at))}}</td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td class="text-muted fw-semibold">Total <i class="fas fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" style="vertical-align: middle; cursor: help" title="Total pour cette page"></i></td>
                                            <td colspan="5" class="text-end fw-semibold" style="color:#006666">{{$qteStockTotal}}</td>
                                            <td class="text-end fw-semibold" style="color:#006666">{{number_format($valAchatTotal,2,',',' ')}}</td>
                                            <td class="text-end fw-semibold @if($valVenteTotal > 0) text-vert @else text-danger @endif">{{number_format($valVenteTotal,2,',',' ')}}</td>
                                            <td colspan="7"></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="bloc_pagination">{{$totalStock->links()}}</div> 
                            </div>                        
                        </div>
                    </div>
                </div>
            </div>
        </div>    
    </div>    
</div>


