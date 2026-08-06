<div wire:ignore.self class="modal fade" id="createNomenclatureModal" tabindex="-1" data-bs-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
        <div class="modal-header py-2" style="background-color: #56585d; color:#ffffff;">
            <h5 class="modal-title" id="exampleModalCenteredScrollableTitle"><i class="fa fa-plus-circle"></i> Nouvelle nomenclature <span class="text-vert"></span> </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body py-0 px-0">
            <div class="container-fluid">
                <div class="row pt-1 px-2 pb-0">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="libelle" class="col-md-5 col-sm-3 fw-semibold col-form-label">Nomenclature</label>
                        <div class="row mb-2">
                            <div class="col-lg-10 col-md-10 col-sm-10">
                                <input type="text" name="libelle" wire:model.defer="libelle" placeholder="Ex: Fabrication du pain" class="form-control bordure fs-3 px-0 w-100 @error('libelle') is-invalid @enderror" id="libelle"/>
                            </div>
                                <div class="d-flex justify-content-start">
                                @error('libelle') <span class="text-danger">{{ $message }}</span> @enderror 
                            </div>
                        </div>                                            
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">  
                        <div class="row mb-1">
                            <label for="inputPassword3" class="col-sm-3 fw-bold col-form-label">Type</label>
                            <div class="col-sm-9">
                                <div class="card-body">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" wire:model.live="type_nomencla" value="Fabrication" name="inlineRadioOptions" id="inlineRadio1" value="option1">
                                        <label class="form-check-label" for="inlineRadio1">Fabrication</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" wire:model.live="type_nomencla" value="Déassemblage" name="inlineRadioOptions" id="inlineRadio2" value="option2" checked="">
                                        <label class="form-check-label" for="inlineRadio2">Déassemblage</label>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-start">
                                    @error('type_nomencla') <span class="text-danger">{{ $message }}</span> @enderror 
                                </div>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <label for="produit_a_fabrique" class="col-sm-4 fw-bold col-form-label">Produit à fabriquer</label>
                            <div class="col-sm-8">   
                                <select id="produit_a_fabrique" wire:model.defer="produit_a_fabrique" class="form-control form-select bordure w-auto @error('produit_a_fabrique') is-invalid @enderror">
                                    <option value=""></option>	
                                    @foreach($produit as $produits)
                                        <option value="{{$produits->id}}">{{$produits->nom_produit}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="d-flex justify-content-start">
                                @error('produit_a_fabrique') <span class="text-danger">{{ $message }}</span> @enderror 
                            </div>
                        </div>
                        <div class="">                                            
                            <form>
                                <div class="row mb-1">
                                    <label for="quantite" class="col-sm-4 fw-bold col-form-label">Quantité à fabriquer</label>
                                    <div class="col-sm-4">
                                        <input type="number" wire:model.defer="quantite" placeholder="Ex: 5" min="1" class="form-control bordure w-50 @error('quantite') is-invalid @enderror" id="quantite">
                                    </div>
                                    <div class="col-sm-4">                                                        
                                        <select id="unite_mesure" wire:model.defer ="unite_mesure" class="form-control form-select bordure w-75 @error('unite_mesure') is-invalid @enderror">
                                            <option value="Unité(s)">Unité(s)</option>
                                            <option value="Kg">Kg</option>
                                            <option value="g">g</option>
                                            <option value="Km">Km</option>
                                            <option value="m²">m²</option>
                                            <option value="m³">m³</option>
                                            <option value="L">L</option>
                                            <option value="cL">cL</option>
                                            <option value="mL">mL</option>
                                        </select> 
                                    </div>                                                    
                                    <div class="d-flex justify-content-start">
                                        @error('quantite') <span class="text-danger">{{ $message }}</span> @enderror 
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="row mb-1">
                            <label for="duree" class="col-sm-5 fw-bold col-form-label">Durée estimée (H:min)</label>
                            <div class="col-sm-7">
                                <input type="time" wire:model.defer="duree" min="1" class="form-control bordure w-auto @error('duree') is-invalid @enderror" id="duree">
                            </div>
                            <div class="d-flex justify-content-start">
                                @error('duree') <span class="text-danger">{{ $message }}</span> @enderror 
                            </div>
                        </div>
                        <div class="row mb-1">
                            <label for="entrepot_fabrication" class="col-sm-5 fw-bold col-form-label">Entrepôt de fabrication</label>
                            <div class="col-sm-7">   
                                <select id="entrepot_fabrication" wire:model.defer="entrepot_fabrication" class="form-control form-select bordure w-auto @error('entrepot_fabrication') is-invalid @enderror">
                                    <option value=""></option>	
                                    @foreach($entrepot as $entrepots)
                                        <option value="{{$entrepots->id}}">{{$entrepots->nom}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="d-flex justify-content-start">
                                @error('entrepot_fabrication') <span class="text-danger">{{ $message }}</span> @enderror 
                            </div>
                        </div>
                        <div class="row mb-1">
                            <label for="inputPassword3" class="col-sm-3 col-form-label">Etat</label>
                            <div class="col-sm-9">
                                <div class="card-body">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" wire:model.live="etat" value="1" name="inlineRadioOptionsEtat" id="inlineRadio3" value="option3">
                                        <label class="form-check-label" for="inlineRadio3">Activer</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" wire:model.live="etat" value="0" name="inlineRadioOptionsEtat" id="inlineRadio4" value="option4" checked="">
                                        <label class="form-check-label" for="inlineRadio4">Désactiver</label>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-start">
                                @error('etat') <span class="text-danger">{{ $message }}</span> @enderror 
                            </div>
                        </div>                                            
                    </div>                                        
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="row mb-1 mt-2">
                            <label for="description" class="col-sm-2 col-form-label" style="font-weight: 400;">Note</label>
                            <div class="col-sm-10">                                                        
                                <textarea rows="2" wire:model="description" class="form-control bordure @error('description') is-invalid @enderror" id="description" placeholder="Ajouter une note..."></textarea>
                            </div>
                            <div class="d-flex justify-content-start">
                                @error('description') <span class="text-danger">{{ $message }}</span> @enderror 
                            </div>
                        </div>                                            
                    </div>                                                                                
                </div>
            </div>
        </div>
        <div class="modal-footer py-1" style="background-color: #56585d; color:#ffffff;">
            <button type="submit" wire:click.prevent="store()" class="btn btn-sm btn-secondary fw-semibold"><i class="fas fa-save"></i> Enregistrer</button>
            <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal"><i class="fa fa-close"></i> Fermer</button>
        </div>
    </div>
    </div>
</div>