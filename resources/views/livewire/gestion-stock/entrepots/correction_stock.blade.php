<div wire:ignore.self class="modal fade" id="correctionStockModal" tabindex="-1" data-bs-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
        <div class="modal-header py-2" style="background-color: #56585d;color:#ffffff;">
        <h5 class="modal-title" id="exampleModalCenteredScrollableTitle"><i class="far fa-file" style=""></i> Correction de stock <span class="text-bleu"></span> </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="card-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 col-sm-12">
                            <div class="row">
                                <div class="col-sm-6 col-12">                                            
                                    <form>
                                        <div class="row mb-1">
                                            <label for="nom_produit" class="col-sm-3 fw-bold col-form-label">Produit</label>
                                            <div class="col-sm-9">                                                        
                                                <select id="nom_produit" wire:model.live="nom_produit" class="form-control form-select bordure w-100 @error('nom_produit') is-invalid @enderror">
                                                    <option value=""></option> 
                                                        @foreach($produit as $produits)
                                                            @foreach ($SommeParProduit as $SommeParProduits)
                                                                @if($SommeParProduits->id_produit == $produits->id)
                                                                    <option value="{{$produits->id}}">{{$produits->nom_produit}} - {{number_format($produits->prix_achat,0,',',' ')}} {{$devise}} » Stock {{$SommeParProduits->total_quantite}}</option>
                                                                @endif
                                                            @endforeach
                                                        @endforeach                                                       
                                                </select> 
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                @error('nom_produit') <span class="text-danger">{{ $message }}</span> @enderror 
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <label for="prix_achat" class="col-sm-6 col-form-label">Prix d'achat unitaire</label>
                                            <div class="col-sm-6">
                                                <input type="text" wire:model="prix_achat" placeholder="Ex: 1000" @if($sens_stock == "Supprimer") readonly @endif class="form-control @if($sens_stock == "Supprimer") bg-light @endif bordure w-75 @error('prix_achat') is-invalid @enderror" id="prix_achat">
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                @error('prix_achat') <span class="text-danger">{{ $message }}</span> @enderror 
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <label for="libele_mouvement" class="col-sm-5 col-form-label">Libellé mouvement</label>
                                            <div class="col-sm-7">
                                                <input type="text" wire:model="libele_mouvement" placeholder="Ex: Correction du stock" class="form-control bordure @error('libele_mouvement') is-invalid @enderror" id="libele_mouvement">
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                @error('libele_mouvement') <span class="text-danger">{{ $message }}</span> @enderror 
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-sm-6 col-12">                                            
                                    <form>
                                        <div class="row mb-1">
                                            <label for="quantite" class="col-sm-4 col-form-label">Nbre de pièces</label>
                                            <div class="col-sm-4">                                                        
                                                <select id="sens_stock" wire:model.live ="sens_stock" class="form-control form-select bordure w-100 @error('sens_stock') is-invalid @enderror">
                                                    <option value="Ajouter">Ajouter</option>
                                                    <option value="Supprimer">Supprimer</option>
                                                </select> 
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="number" wire:model="quantite" placeholder="Ex: 10" min="1" class="form-control bordure @error('quantite') is-invalid @enderror" id="quantite">
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                @error('quantite') <span class="text-danger">{{ $message }}</span> @enderror 
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <label for="code_mouvement" class="col-sm-5 col-form-label">Code mouvement</label>
                                            <div class="col-sm-7">
                                                <input type="text" wire:model="code_mouvement" placeholder="Ex: 20240916154837" class="form-control bordure w-100 @error('code_mouvement') is-invalid @enderror" id="code_mouvement">
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                @error('code_mouvement') <span class="text-danger">{{ $message }}</span> @enderror 
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer py-1" style="background-color: #56585d;color:#ffffff;">
            <button type="submit" wire:click="corriger()" class="btn btn-sm btn-secondary fw-bold"><i class="fa fa-check"></i> Enregistrer</button>
            <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal"><i class="fa fa-close"></i> Fermer</button>
        </div>
    </div>
    </div>
</div>