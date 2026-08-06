<div wire:ignore.self class="modal fade" id="createInventaireModal" tabindex="-1" data-bs-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header py-2" style="background-color: #56585d; color:#ffffff;">
                <h5 class="modal-title" id="exampleModalCenteredScrollableTitle"><i class="fa fa-plus-circle"></i> Nouveau transfert <span class="text-vert"></span> </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-0 px-0">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-1 border-end border-bottom rounded-bottom">
                            <div class="hauteur_ecran">
                                <div class="row pt-3 mb-2 px-0">                                            
                                    <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">                                                                                              
                                        <div class="row mb-3">
                                            {{-- <label for="libelle" class="col-lg-2 col-md-5 col-sm-3 fw-bold fs-5 col-form-label">Libellé</label> --}}
                                            <div class="col-lg-10 col-md-10 col-sm-10">
                                                <input type="text" wire:model="libelle" placeholder="Libellé de l'inventaire" class="form-control bordure fs-3 w-100 px-0 @error('libelle') is-invalid @enderror" id="libelle">
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                @error('libelle') <span class="text-danger">{{$message}}</span> @enderror 
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <label for="reference" class="col-lg-3 col-md-3 col-sm-3 fw-bold col-form-label">Référence</label>
                                            <div class="col-lg-9 col-md-9 col-sm-9">
                                                <input type="text" wire:model="reference" placeholder="Ex: INV09/2025" class="form-control bordure w-50 @error('reference') is-invalid @enderror" id="reference">
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                @error('reference') <span class="text-danger">{{ $message }}</span> @enderror 
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <label for="entrepot" class="col-lg-3 col-md-3 col-sm-3 fw-bold col-form-label">Entrepôt</label>
                                            <div class="col-lg-9 col-md-9 col-sm-9">                                                        
                                                <select id="entrepot" wire:model.live="entrepot" class="form-control form-select bordure w-auto @error('entrepot') is-invalid @enderror">
                                                    <option value=""></option>	
                                                    @foreach($listEntrepot as $listEntrepots)
                                                        <option value="{{$listEntrepots->id}}">{{$listEntrepots->nom}}</option>
                                                    @endforeach	
                                                </select> 
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                @error('entrepot') <span class="text-danger">{{ $message }}</span> @enderror 
                                            </div>
                                        </div>  
                                        <div class="row mb-1">
                                            <label for="date_inventaire" class="col-lg-3 col-md-3 col-sm-3 fw-bold col-form-label">Date</label>
                                            <div class="col-lg-9 col-md-9 col-sm-9">
                                                <input type="date" wire:model="date_inventaire" class="form-control bordure w-auto @error('date_inventaire') is-invalid @enderror" id="date_inventaire">
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                @error('date_inventaire') <span class="text-danger">{{ $message }}</span> @enderror 
                                            </div>
                                        </div>                                
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div id="note" class="tab-pane active">
                                            <div class="row mt-2"> 
                                                <label for="date_inventaire" class="col-lg-3 col-md-3 col-sm-3 fw-bold col-form-label">Note</label> 
                                                <div class="col-sm-12">                                                        
                                                    <textarea rows="4" wire:model="note" class="form-control bordure px-0 @error('note') is-invalid @enderror" id="note" placeholder="Ajouter une note..."></textarea>
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('note') <span class="text-danger">{{ $message }}</span> @enderror 
                                                </div>
                                            </div>                                                                
                                        </div>                                            
                                    </div>                                       
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