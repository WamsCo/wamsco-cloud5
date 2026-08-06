<div>
    <div class="row gx-4 pt-0">
        <div class="col-sm-12">
            <div class="card card_correction">
                <div class="card-header card-header_correction">
                    <h5 class="card-title text-danger"><i class="far fa-file"></i> Correction de stock <span class="text-bleu">» {{$nom_produit}}</span></h5>
                </div>
                <div class="card-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12 col-sm-12">
                                <div class="row">
                                    <div class="col-sm-6 col-12">                                            
                                        <form>
                                            <div class="row mb-1">
                                                <label for="nom_entrepot" class="col-sm-4 fw-bold col-form-label">Entrepôt</label>
                                                <div class="col-sm-8">                                                        
                                                    <select id="nom_entrepot" wire:model.live="nom_entrepot" class="form-control form-select bordure w-100 @error('nom_entrepot') is-invalid @enderror">
                                                        <option value=""></option>   
                                                        @foreach($stock as $stocks)
                                                            @foreach($listEntrepot as $listEntrepots)  
                                                                @if($stocks->id_entrepot == $listEntrepots->id)                                                                                                                                                           
                                                                    <option value="{{$listEntrepots->id}}">{{$listEntrepots->nom}} - Stock total » {{$stocks->quantite}}</option>
                                                                @endif
                                                            @endforeach
                                                        @endforeach
                                                    </select> 
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('nom_entrepot') <span class="text-danger">{{ $message }}</span> @enderror 
                                                </div>
                                            </div>
                                            <div class="row mb-1">
                                                <label for="prix_achats" class="col-sm-6 col-form-label">Prix d'achat unitaire</label>
                                                <div class="col-sm-6">
                                                    <input type="text" wire:model="prix_achats" placeholder="Ex: 1000" @if($sens_stock == "Supprimer") readonly @endif class="form-control @if($sens_stock == "Supprimer") bg-light @endif bordure w-75 @error('prix_achats') is-invalid @enderror" id="prix_achats">
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('prix_achats') <span class="text-danger">{{ $message }}</span> @enderror 
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
                <div class="gap-2 px-2 py-2">
                    <button type="submit" wire:click="corriger()" class="btn btn-xs btn-secondary fw-bold"><i class="fa fa-check"></i> Enregistrer</button>
                    <button wire:click="onDataAjout()" class="btn btn-xs btn-danger" data-bs-dismiss="modal"><i class="fa fa-close"></i> Fermer</button>
                </div>
            </div>
        </div>
    </div>
</div>

















        
        
  