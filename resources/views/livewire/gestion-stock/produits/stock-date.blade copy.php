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
                                <h6><a href="{{asset('#')}}" class="btn btn-sm btn-success mt-2 d-none d-md-block">Exporter Excel</a></h6>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
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
                    <div class="card-body no_bordure">
                        <div class="table-outer">
                            <div class="table-responsive border-top rounded-0"> 
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
        </div>    
    </div>    
</div>


