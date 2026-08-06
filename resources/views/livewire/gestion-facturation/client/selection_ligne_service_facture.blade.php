<div>
    <div wire:ignore.self class="modal fade" id="SelectLigneServiceFactModal" tabindex="-1" data-bs-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
	    <form enctype="multipart/form-data">							
			<div class="modal-dialog modal-xl">
				<div class="modal-content">             
					<div class="modal-header py-2" style="background-color: #56585d; color:#ffffff;">
						<h3 class="modal-title fs-5"><i class="fa fa-refresh"></i> {{$this->type_produit}} ajouté (<span class="text-warning">{{$factClientLigneCount}}</span>)</h3>
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
                                                <th class="fond_entete_table">Service (<span class="text-info">{{$service_produitCount}}</span>)</th>   
                                                <th class="fond_entete_table text-end pointer">Prix</i></th>
                                                <th class="fond_entete_table">Référence</th>
                                                <th class="fond_entete_table">Catégorie</th>
                                                <th class="fond_entete_table">Nature</th>     
                                                <th class="fond_entete_table text-start">Date modif.</th>
                                                <th colspan="2" class="fond_entete_table text-start"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($service_produit as $service_produits) 
                                            <tr>                                       
                                                <td class="fw-bold pointer" wire:click="afficheLigneService({{$service_produits->id}})">
                                                    <i class="fa fa-refresh"></i> {{Str::limit($service_produits->nom_produit, 32)}} - <span class="text-muted">{{Str::limit($service_produits->reference, 22)}}</span>
                                                </td>  
                                                <td class="text-end fw-semibold pointer" wire:click="afficheLigneService({{$service_produits->id}})">
                                                    <span class="text-bleu fw-bold">{{$service_produits->prix_vente}}</span>
                                                </td>                                                                     
                                                <td class="fw-semibold pointer" wire:click="afficheLigneService({{$service_produits->id}})">{{$service_produits->reference}}</td> 
                                                <td class="fw-semibold pointer" wire:click="afficheLigneService({{$service_produits->id}})">{{$service_produits->categorie}}</td> 
                                                <td class="pointer" wire:click="afficheLigneService({{$service_produits->id}})">
                                                    @if($service_produits->nature_produit == "Matière première")
                                                        <span style="color:#051e03; font-weight: 600;"> {{$service_produits->nature_produit}}</span>
                                                    @else 
                                                        <span style="color:#707070; font-weight: 600;">{{$service_produits->nature_produit}}</span>
                                                    @endif
                                                </td>                                                                                            
                                                <td class="text-start pointer" wire:click="afficheLigneService({{$service_produits->id}})">{{date('d-m-Y H:i:s', strtotime($service_produits->updated_at))}}</td> 
                                                <td  colspan="2" class="text-start pointer"></td> 
                                            </tr>                                              
                                            @if($ouverture === $service_produits->id) 
                                                <tr>                                                         
                                                    @include('livewire.gestion-facturation.client.ajout_ligne_produit_facture')     
                                                </tr>
                                            @endif
                                            @endforeach                                            
                                        </tbody>
                                    </table>
                                    <div class="bloc_pagination">{{$service_produit->links()}}</div> 
                                </div>                        
                            </div>
                        </div>
					</div>
                    <div class="d-flex align-items-center justify-content-between modal-footer py-1" style="background-color: #56585d; color:#ffffff;">
                        {{-- <button type="button" data-bs-toggle="modal" data-bs-target="#CreationClientModal" wire:click.prevent="charge()" class="btn btn-secondary fw-bold" title="Cliquez pour enregistrer" data-toggle="tooltip"><i class="fa fa-user-plus"></i> Créer client</button> --}}
                        <a href="produit?active=4&champ=1-1&choix=2" target="_blank" class="btn btn-sm btn-secondary" title="Cliquez pour créer un produit"><i class="fa fa-refresh"></i> Créer @if($this->type_produit == 'Produit') produit @else service @endif</i></a>
                        @if($this->client_id)
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

