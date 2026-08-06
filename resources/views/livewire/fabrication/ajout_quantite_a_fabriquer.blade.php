<div>
	<div wire:ignore.self class="modal fade" id="qteAproduireModal" tabindex="-1"  data-bs-backdrop="static" role="dialog" aria-labelledby="slidemodalLabel" aria-hidden="true">
		<form enctype="multipart/form-data">							
			<div class="modal-dialog modal-sm">
				<div class="modal-content">             
					<div class="modal-header p-1" style="background-color: #56585d; color:#ffffff;">
						<h3 class="modal-title fs-5"><i class="fas fa-edit text-danger"></i> Modifier la quantité à produire</h3>
					</div>
					<div class="modal-body py-1">   
                        <div class="card-body p-0">                            
                            <div class="m-0 text-center">
                                <div class="row mt-3">
                                    <label for="qte_a_produire" class="col-sm-6 col-form-label">Quantité à produire</label>
                                    <div class="col-sm-6">
                                        <input type="number" wire:model="qte_a_produire" placeholder="Ex: 2" min="1" class="form-control bordure w-100 @error('qte_a_produire') is-invalid @enderror" id="qte_a_produire">
                                    </div>
                                    <div class="d-flex justify-content-start">
                                        @error('qte_a_produire') <span class="text-danger">{{ $message }}</span> @enderror 
                                    </div>
                                </div>
                            </div>
                        </div>
					</div>
                    <div class="d-flex align-items-center justify-content-flex-end modal-footer py-1" style="background-color: #56585d; color:#ffffff;">					
                        <div class="">
                            <button type="submit" wire:click.prevent="quantiteAproduire()" class="btn btn-sm btn-secondary fw-bold" title="Cliquez pour enregistrer" data-toggle="tooltip"><i class="fa fa-check"></i> Valider</button>
                            <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal"><i class="fa fa-close"></i></button>
                        </div>
                    </div>
				</div>
			</div>
		</form>   
	</div>
</div>

