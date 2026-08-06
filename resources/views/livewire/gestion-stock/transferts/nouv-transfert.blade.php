<div wire:ignore.self class="modal fade" id="createTransfertModal" tabindex="-1" data-bs-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
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
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">                                                                                              
                                        <div class="row mb-1 mt-3">
                                            <label for="entrepot_origine" class="col-lg-5 col-md-5 col-sm-4 fw-bold col-form-label">Entrepôt origine</label>
                                            <div class="col-lg-7 col-md-7 col-sm-8">                                                        
                                                <select id="entrepot_origine" wire:model.live="entrepot_origine" class="form-control form-select bordure w-auto @error('entrepot_origine') is-invalid @enderror">
                                                    <option value=""></option>	
                                                    @foreach($listEntrepot as $listEntrepots)
                                                        <option value="{{$listEntrepots->id}}">{{$listEntrepots->nom}}</option>
                                                    @endforeach	
                                                </select> 
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                @error('entrepot_origine') <span class="text-danger">{{ $message }}</span> @enderror 
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <label for="entrepot_destination" class="col-lg-5 col-md-5 col-sm-4 fw-bold col-form-label">Entrepôt destination</label>
                                            <div class="col-lg-7 col-md-7 col-sm-8">                                                        
                                                @if($entrepot_origine)
                                                    <select id="entrepot_destination" wire:model.live="entrepot_destination" class="form-control form-select bordure w-auto @error('entrepot_destination') is-invalid @enderror">
                                                        <option value=""></option>	
                                                        @foreach($listEntrepot as $listEntrepots)
                                                            @if($entrepot_origine != $listEntrepots->id)
                                                                <option value="{{$listEntrepots->id}}">{{$listEntrepots->nom}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select> 
                                                @endif	
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                @error('entrepot_destination') <span class="text-danger">{{ $message }}</span> @enderror 
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <label for="date_sortie" class="col-lg-4 col-md-4 col-sm-3 fw-bold col-form-label">Date de sortie</label>
                                            <div class="col-lg-8 col-md-8 col-sm-9">
                                                <input type="date" wire:model="date_sortie" class="form-control bordure w-auto @error('date_sortie') is-invalid @enderror" id="date_sortie">
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                @error('date_sortie') <span class="text-danger">{{ $message }}</span> @enderror 
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <label for="date_entree" class="col-lg-4 col-md-4 col-sm-3 fw-bold col-form-label">Date d'entrée</label>
                                            <div class="col-lg-8 col-md-8 col-sm-9">
                                                <input type="date" wire:model="date_entree" class="form-control bordure w-auto @error('date_entree') is-invalid @enderror" id="date_entree">
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                @error('date_entree') <span class="text-danger">{{ $message }}</span> @enderror 
                                            </div>
                                        </div>                                                  
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">                                                
                                        <div class="row mb-1">
                                            <label for="transporteur" class="col-lg-4 col-md-4 col-sm-3 col-form-label">Transporteur</label>
                                            <div class="col-lg-8 col-md-8 col-sm-9">
                                                <input type="text" wire:model="transporteur"  placeholder="Ex: Marie Jean" class="form-control bordure w-75 @error('transporteur') is-invalid @enderror" id="transporteur">
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                @error('transporteur') <span class="text-danger">{{ $message }}</span> @enderror 
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <label for="nombre_paquets" class="col-lg-4 col-md-5 col-sm-4 fw-bold col-form-label">Nb. de paquets</label>
                                            <div class="col-lg-8 col-md-8 col-sm-9">
                                                <input type="number" wire:model="nombre_paquets" min="1" placeholder="Ex: 01" class="form-control bordure w-50 @error('nombre_paquets') is-invalid @enderror" id="nombre_paquets">
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                @error('nombre_paquets') <span class="text-danger">{{ $message }}</span> @enderror 
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <label for="code_inventaire" class="col-lg-4 col-md-4 col-sm-3 col-form-label">Code inventaire</label>
                                            <div class="col-lg-8 col-md-8 col-sm-9">
                                                <input type="text" wire:model="code_inventaire" placeholder="Ex: INV01" class="form-control bordure w-100 @error('code_inventaire') is-invalid @enderror" id="code_inventaire">
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                @error('code_inventaire') <span class="text-danger">{{ $message }}</span> @enderror 
                                            </div>
                                        </div>                                                                                    
                                    </div>                                        
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row mb-1">
                                            <label for="etiquette_transfert" class="col-lg-3 col-md-3 col-sm-4 fw-bold col-form-label">Etiquette du transfert</label>
                                            <div class="col-lg-9 col-md-9 col-sm-8">
                                                <input type="text" wire:model="etiquette_transfert" placeholder="Ex: Transfert de stock" class="form-control bordure w-75 @error('etiquette_transfert') is-invalid @enderror" id="etiquette_transfert">
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                @error('etiquette_transfert') <span class="text-danger">{{ $message }}</span> @enderror 
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row mb-1">
                                            <label for="note" class="col-lg-4 col-md-5 col-sm-3 col-form-label">Note</label>
                                            <div class="col-lg-12 col-md-12 col-sm-12">                                                        
                                                <textarea rows="2" wire:model="note" class="form-control bordure" id="note" placeholder="Ajouter une note..."></textarea>
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
            <div class="modal-footer py-1" style="background-color: #56585d; color:#ffffff;">
                <button type="submit" wire:click.prevent="store()" class="btn btn-sm btn-secondary fw-semibold"><i class="fas fa-save"></i> Enregistrer</button>
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal"><i class="fa fa-close"></i> Fermer</button>
            </div>
        </div>
    </div>
</div>