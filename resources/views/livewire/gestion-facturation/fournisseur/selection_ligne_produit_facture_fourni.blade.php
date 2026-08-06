<div>
    <div wire:ignore.self class="modal fade" id="SelectLigneProdFactModal" tabindex="-1" data-bs-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
	    <form enctype="multipart/form-data">							
			<div class="modal-dialog modal-xl">
				<div class="modal-content">             
					<div class="modal-header py-2" style="background-color: #56585d; color:#ffffff;">
                        <h3 class="modal-title fs-5"><i class="fa fa-cube"></i> {{$this->type_produit}} ajouté (<span class="text-warning">{{$factFournisseurLigneCount}}</span>)</h3>
					</div>
					<div class="modal-body no_bordure"> 
                        <div class="m-0 pb-2">                               
                            <div class="d-flex align-items-center justify-content-between gap-1">
                                <div class="">
                                    <select name="" wire:model.lazy="parPage" id="par_page" class="form-control form-select bordure w-auto"> 
                                        @for($i = 10; $i <= 100; $i += 10)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor        
                                    </select> 
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
                        </div>
                        <div>
                            <div class="table-outer">
                                <div class="table-responsive border rounded-1">  
                                    <table class="table table-striped table-hover text-nowrap m-0">
                                        <thead>
                                            <tr>
                                                <th class="fond_entete_table pointer" wire:click="setOrderField('nom_produit')">Produit (<span class="text-info">{{$produit_stockCount}}</span>)</th>   
                                                <th class="fond_entete_table text-end pointer" wire:click="setOrderField('quantites')">Quantité</i></th>
                                                <th class="fond_entete_table text-end pointer" wire:click="setOrderField('valorisationAchatTotal')">Valorisation achat(PMP)</th>  
                                                <th class="fond_entete_table text-end pointer" wire:click="setOrderField('prix_vente_unitaire')">Prix vente unitaire</th>
                                                <th class="fond_entete_table text-end pointer" wire:click="setOrderField('valeurVentetotal')">Valeur à la vente</th>
                                                <th class="fond_entete_table pointer" wire:click="setOrderField('categorie')">Catégorie</th>
                                                <th class="fond_entete_table pointer" wire:click="setOrderField('nature_produit')">Nature</th>     
                                                <th class="fond_entete_table text-start pointer" wire:click="setOrderField('created_at')">Date modif.</th>
                                                <th class="fond_entete_table text-start pointer"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($produit_stock as $produit_stocks) 
                                            <tr>                                       
                                                <td class="fw-bold pointer" wire:click="afficheLigne({{$produit_stocks->id}})">
                                                    <i class="fa fa-cube"></i> {{substr($produit_stocks->nom_produit,0,52) > substr($produit_stocks->nom_produit,0,51) ? substr($produit_stocks->nom_produit,0,52).'...': $produit_stocks->nom_produit}}
                                                </td>
                                                <td class="text-end fw-semibold pointer" wire:click="afficheLigne({{$produit_stocks->id}})">                                                
                                                    @if($produit_stocks->quantites > $produit_stocks->limite_stock_alerte)
                                                        <span class="text-success fw-bold">{{$produit_stocks->quantites}}</span>
                                                    @elseif($produit_stocks->quantites > 0 && $produit_stocks->quantites <= $produit_stocks->limite_stock_alerte)
                                                        <span class="text-info fw-bold" title="Stock inférieur ou égale au stock d'alerte ({{$produit_stocks->limite_stock_alerte}})"><i class="fas fa-exclamation-triangle text-danger"></i> {{$produit_stocks->quantites}}</span>
                                                    @else
                                                        <span class="blink text-danger fw-bold">{{$produit_stocks->quantites}}</span>
                                                    @endif
                                                </td>  
                                                <td class="text-end fw-semibold pointer" wire:click="afficheLigne({{$produit_stocks->id}})">{{number_format($produit_stocks->valorisationAchatTotal,0,',',' ')}}</td> 
                                                <td class="text-end fw-semibold pointer" wire:click="afficheLigne({{$produit_stocks->id}})">{{number_format($produit_stocks->prixVenteUnitaire,0,',',' ')}}</td> 
                                                <td class="text-end fw-semibold pointer" wire:click="afficheLigne({{$produit_stocks->id}})">{{number_format($produit_stocks->valeurVentetotal,0,',',' ')}}</td>
                                                <td class="fw-semibold pointer" wire:click="afficheLigne({{$produit_stocks->id}})">{{$produit_stocks->categorie}}</td> 
                                                <td class="pointer" wire:click="afficheLigne({{$produit_stocks->id}})">
                                                    @if($produit_stocks->nature_produit == "Matière première")
                                                        <span style="color:#051e03; font-weight: 600;"> {{$produit_stocks->nature_produit}}</span>
                                                    @else 
                                                        <span style="color:#707070; font-weight: 600;">{{$produit_stocks->nature_produit}}</span>
                                                    @endif
                                                </td>  
                                                <td class="text-start pointer" wire:click="afficheLigne({{$produit_stocks->id}})">{{date('d-m-Y H:i:s', strtotime($produit_stocks->updated_at))}}</td> 
                                                <td class="text-start pointer"></td> 
                                            </tr>                                              
                                            @if($ouverture === $produit_stocks->id) 
                                                <tr>                                                         
                                                    @include('livewire.gestion-facturation.fournisseur.ajout_ligne_produit_facture_fourni')     
                                                </tr>
                                            @endif
                                            @endforeach
                                            <tr>
                                                <td class="text-muted fw-semibold">Total <i class="fas fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" style="vertical-align: middle; cursor: help" title="Total pour cette page"></i></td>
                                                <td class="text-end fw-semibold" style="color:#006666">{{number_format($qteStockTotal,0,',',' ')}}</td>
                                                <td class="text-end fw-semibold" style="color:#006666">{{number_format($valAchatTotal,0,',',' ')}}</td>
                                                <td class="text-end fw-semibold" style="color:#006666">{{number_format($valPrixVenteUnitaire,0,',',' ')}}</td>
                                                <td class="text-end fw-semibold @if($valVenteTotal > 0) text-vert @else text-danger @endif">{{number_format($valVenteTotal,0,',',' ')}}</td>
                                                <td colspan="5" class="text-end fw-semibold"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="bloc_pagination">{{$produit_stock->links()}}</div> 
                                </div>                        
                            </div>
                        </div>
					</div>
                    <div class="d-flex align-items-center justify-content-between modal-footer py-1" style="background-color: #56585d; color:#ffffff;">
                        {{-- <button type="button" data-bs-toggle="modal" data-bs-target="#CreationClientModal" wire:click.prevent="charge()" class="btn btn-secondary fw-bold" title="Cliquez pour enregistrer" data-toggle="tooltip"><i class="fa fa-user-plus"></i> Créer client</button> --}}
                        <a href="produit?active=4&champ=1-1&choix=2" target="_blank" class="btn btn-sm btn-secondary" title="Cliquez pour créer un produit"><i class="fa fa-cube"></i> Créer @if($this->type_produit == 'Produit') produit @else service @endif</i></a>
                        @if($this->fournisseur_id > 0)
                            <button type="button" class="btn btn-sm btn-danger" wire:click.prevent="fermerModal()"><i class="fa fa-times-circle"></i> Fermer</button>
                        @else
                            <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> Fermer</button>
                        @endif
                    </div>
				</div>
			</div>
		</form>   
	</div>
</div>

