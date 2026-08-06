<div>
    <div wire:ignore.self class="modal fade" id="SelectLigneFacturationModal" tabindex="-1" data-bs-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
	   <form enctype="multipart/form-data">							
			<div class="modal-dialog modal-lg">
				<div class="modal-content">             
					<div class="modal-header py-2" style="background-color: #56585d; color:#ffffff;">
						<h3 class="modal-title fs-5"><i class="fa fa-money-bill"></i> Facturation (<span class="text-warning">{{$recepClientLigneCount}}</span>) » Expédition</h3>
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
                                    {{-- <div>
                                        <label for="parCat" class="sr-only">Recherche</label>
                                        <input type="search" wire:model.live="parCat" id="parCat" class="form-control bordure" placeholder="Rechercher catégorie">
                                    </div> --}}
                                    {{-- <div>
                                        <label for="parNature" class="sr-only">Recherche</label>
                                        <input type="search" wire:model.live="parNature" id="parNature" class="form-control bordure" placeholder="Rechercher nature">
                                    </div> --}}
                                    <div>
                                        <label for="ParProduit" class="sr-only">Recherche</label>
                                        <input type="search" wire:model.live="ParProduit" id="ParProduit" class="form-control bordure" placeholder="Rechercher produit">
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
                                                <th class="fond_entete_table">Description</th>
                                                <th class="fond_entete_table">Type</th>
                                                <th class="fond_entete_table text-center">Qté commandée</th>
                                                <th class="fond_entete_table text-center">Qté. recue</th>
                                                <th class="fond_entete_table text-center">Reste à recevoir</th>
                                                <th class="fond_entete_table text-start">Entrepôt (Stock) <i class="fas fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" style="vertical-align: middle; cursor: help" title="Choisissez le magassin d'expédition dans les paramètres"></i></th> 
                                                <th class="fond_entete_table text-end"></th> 
                                                <th class="fond_entete_table text-end"></th> 
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recepClient_ligne as $recepClient_lignes)                                                    
                                                <tr>
                                                    <td class="text-bleu fw-bold pointer" wire:click="afficheLigneExpedition({{$recepClient_lignes->id}})">
                                                        <i class="fa fa-cube"></i> {{Str::limit($recepClient_lignes->produit, 32)}} - <span class="text-muted">{{Str::limit($recepClient_lignes->reference, 22)}}</span>
                                                    </td>
                                                    <td class="text-muted fw-semibold pointer" wire:click="afficheLigneExpedition({{$recepClient_lignes->id}})">{{$recepClient_lignes->type_produit}}</td>         
                                                    <td class="text-center fw-bold pointer" wire:click="afficheLigneExpedition({{$recepClient_lignes->id}})">{{$recepClient_lignes->quantite}}</td>         
                                                    <td class="text-center fw-semibold @if($recepClient_lignes->quantite_recue > 0) text-vert @endif">
                                                        @if(!empty($recepClient_lignes->quantite_recue))
                                                            <span>{{$recepClient_lignes->quantite_recue}}</span>
                                                        @else 
                                                            <span>0</span>
                                                        @endif
                                                    </td>         
                                                    <td class="text-center fw-semibold">{{$recepClient_lignes->reste_a_recevoir}}</td>  
                                                    @php
                                                        foreach($ListeEntrepot as $ListeEntrepots) 
                                                        { 
                                                            if($recepClient_lignes->id_entrepot == $ListeEntrepots->id) { 
                                                                $nom_entrepot = $ListeEntrepots->nom;                                          
                                                            } 
                                                        }    
                                                        foreach($stockProd as $stockProds){
                                                            if($recepClient_lignes->id_produit == $stockProds->id_produit && $recepClient_lignes->id_entrepot == $stockProds->id_entrepot){
                                                                $quantite_produit = $stockProds->quantite;
                                                                $limite_stock_alerte = $stockProds->limite_stock_alerte; 
                                                            }
                                                        }  
                                                    @endphp                                                                
                                                    <td class="text-start fw-semibold">
                                                        @if($recepClient_lignes->type_produit == "Produit")
                                                            <a href="detail_entrepot?id={{$recepClient_lignes->id_entrepot}}&active=4&champ=3-1&choix=2" class="text-bleu" wire:navigate>{{$nom_entrepot}} 
                                                                @if($quantite_produit > $limite_stock_alerte)
                                                                    (<span class="text-success fw-bold">{{$quantite_produit}}</span>)
                                                                @elseif($quantite_produit > 0 && $quantite_produit <= $limite_stock_alerte)
                                                                    (<span class="text-info fw-bold" title="Stock inférieur ou égale au stock d'alerte ({{$limite_stock_alerte}})"><i class="fas fa-exclamation-triangle text-danger"></i> {{$quantite_produit}}</span>)
                                                                @else
                                                                    (<span class="blink text-danger fw-bold">{{$quantite_produit}}</span>)
                                                                @endif
                                                            </a>
                                                        @endif
                                                    </td>
                                                    <td colspan="2" class="text-end fw-semibold"></td>                                                    
                                                </tr>                                                                                         
                                                @if($open === $recepClient_lignes->id) 
                                                    <tr>                                                         
                                                        @include('livewire.gestion-commande.fournisseur.ajout_ligne_facturation')     
                                                    </tr>
                                                @endif
                                            @endforeach                                            
                                        </tbody>
                                    </table>
                                    <div class="bloc_pagination">{{$recepClient_ligne->links()}}</div> 
                                </div>                        
                            </div>
                        </div>
					</div>
                    <div class="d-flex align-items-center justify-content-betweenk modal-footer py-1" style="background-color: #56585d; color:#ffffff;">                        
                        @if($this->fournisseur_id)
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

