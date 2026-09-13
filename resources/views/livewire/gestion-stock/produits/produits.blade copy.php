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
                <div class="card mb-4 bg-2k">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title text-bleu"><i class="fa fa-cube text-danger" aria-hidden="true"></i> {{$title_fils}} ({{$produitCount}})</h5>
                        <div style="margin-left:16px;"></div>
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
                            <div class="d-flex align-items-center justify-content-between gap-1">
                                <h6><a href="{{asset('exporter-produits')}}" class="btn btn-sm btn-success mt-2 d-none d-md-block">Exporter Excel</a></h6>
                                <h6><a href="#" data-bs-toggle="modal" data-bs-target="#importerProduitsModal" class="btn btn-sm btn-warning mt-2 d-none d-md-block"><i class="fa-solid fa fa-upload"></i>  Importer Excel</a></h6>
                                <h6><a href="{{asset('storage/manuel_users/Excel_Produit_WamsCo.xlsx')}}" download="Excel_Produit_WamsCo" class="btn btn-sm btn-secondary mt-2 d-none d-md-block"><i class="fa-solid fa fa-download"></i>  Télécharger Modèle</a></h6>
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
                            <div class="table-responsive border-top rounded-0">  
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
                                            {{-- <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" title="Cliquez pour sélectionner l'élément à supprimer" checked="" x-model="selection" value={{$produits->id}}>                                                    
                                                </div>
                                            </td> --}}
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
                                            <td class=""><a href="detail_product?id={{$produits->id}}&active=4&champ=1-1&choix=2" wire:navigate><i class="fa fa-pencil" title="Cliquez pour modifier" data-toggle="tooltip"></i></a></td>
                                            <td class=""><a href="#" class="doc_lien" wire:click="dupliquer({{$produits->id}})"><i class="fa fa-clone" title="Cliquez pour dupliquer" data-toggle="tooltip"></i></a></td>
                                            <td class="taille_icon text-end">
                                                @if($confirmer === $produits->id)
                                                    <a wire:click.prevent="supprimer({{$produits->id}})"><i class="fa fa-trash bg-red text-white w-32 px-1 py-1 rounded border pointer" title="Cliquez pour confirmer la suppression" data-toggle="tooltip"> Confirmer?</i></a>
                                                @else
                                                    <a wire:click.prevent="confirmerDelete({{$produits->id}})" class="btn btn-outline-muted btn-xs"><i class="fa fa-trash" title="Cliquez pour supprimer" data-toggle="tooltip"></i></a>
                                                @endif
                                            </td> 
                                        </tr>
                                        {{-- @if($ids === $produits->id) 
                                            <tr style="white-space: initial;">          
                                                @include('livewire.gestion-stock.produit.update')     
                                            </tr>
                                        @endif  --}}
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="bloc_pagination">{{$produit->links()}}</div> 
                            </div>                        
                        </div>
                    </div>
                </div>
            </div>
        </div>    
    </div>
    {{-- Modal --}}
    @include('livewire.gestion-stock.produits.importation_produits')    
</div>

