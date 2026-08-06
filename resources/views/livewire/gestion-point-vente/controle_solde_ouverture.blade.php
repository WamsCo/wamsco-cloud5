<div wire:ignore.self class="modal fade" id="ouvertureSessionModal" tabindex="-1" data-bs-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
    <form enctype="multipart/form-data">							
        <div class="modal-dialog modal-md">
            <div class="modal-content">             
                <div class="modal-header p-1">
                    <h3 class="modal-title fs-5"><i class="fa fa-money-bill"></i>  Contrôle à l'ouverture </h3>
                </div>
                <div class="modal-body">   
                    <div class="card-body">
                        <div class="row mb-4">
                            <label for="montant" class="col-sm-4 fw-bold col-form-label">Espèces à l'ouverture</label>
                            <div class="col-sm-8">
                                <input type="number" wire:model="montant" min="0" class="form-control bordure @error('montant') is-invalid @enderror" id="montant">
                            </div>
                            <div class="d-flex justify-content-start">
                                @error('montant') <span class="text-danger">{{ $message }}</span> @enderror 
                            </div>
                        </div>
                        <div class="row mb-0">
                            <label for="note" class="col-sm-4 fw-semibold col-form-label">Note d'ouverture</label>
                            <div class="col-sm-8">                                                        
                                <textarea rows="1" wire:model="note" class="form-control bordure @error('note') is-invalid @enderror" id="note" placeholder="Ajouter une note..."></textarea>
                            </div>
                            <div class="d-flex justify-content-start">
                                @error('note') <span class="text-danger">{{ $message }}</span> @enderror 
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between modal-footer">					
                    <div class="">
                        <button type="submit" wire:click.prevent="ouvertureCaisse()" class="btn btn-sm btn-secondary fw-bold" title="Cliquez pour enregistrer" data-toggle="tooltip"><i class="fa fa-check"></i> Ouvrir la caisse</button>
                        <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal"><i class="fa fa-close"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

