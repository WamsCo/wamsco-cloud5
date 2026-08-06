<div wire:ignore.self class="modal fade" id="updateEmplacementModal" tabindex="-1" data-bs-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content new_color">
			<div class="">
				<div class="modal-header py-2 modal_tete" style="border-radius: .0rem; background-color: #56585d; color:#ffffff;">
				<h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-table"></i> Modifier emplacement</h5>
				<button type="button" class="btn-close mod_close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body modal_corps">
					<div class="card entete_form new_color">	
						<div class="card-body border">					
							<div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12">								
									<div class="nom_desig">
										<div class="row mb-1">
                                            <label for="nom_emplacement" class="col-sm-4 col-form-label fw-bold">Nom emplacement</label>
                                            <div class="col-sm-8">
                                                <input type="text" wire:model="nom_emplacement" placeholder="Ex: Table 1" class="form-control bordure @error('nom_emplacement') is-invalid @enderror" id="nom_emplacement">
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                @error('nom_emplacement') <span class="text-danger">{{ $message }}</span> @enderror 
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <label for="description" class="col-sm-4 col-form-label">Description</label>
                                            <div class="col-sm-8">                                                        
                                                <textarea rows="2" wire:model="description" class="form-control bordure" id="description" placeholder="Description..."></textarea>
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                @error('description') <span class="text-danger">{{ $message }}</span> @enderror 
                                            </div>
                                        </div>								                               
									</div>									
								</div>												    
							</div>						    
						</div>
					</div>
				</div>
				<div class="modal-footer pt-0 pb-0 modal_peids" style="background-color: #56585d; color:#ffffff;"> 
					<button class="btn btn-sm btn-secondary fw-bold" title="Cliquez pour enregistrer" data-toggle="tooltip" wire:click.prevent="update()"><i class="fa fa-save"></i> Modifier</button>              
					<button type="button" class="btn btn-sm btn-danger" title="Cliquez pour fermer" data-bs-dismiss="modal"><i class="fa fa-close"></i></button>
					@if($confirmer === $ids)
						<button type="button" wire:click.prevent="supprimer({{$ids}})" class="btn btn-sm bg-danger text-white" title="Confirmez la suppression"><i class="fa fa-trash"> Confirmer?</i></button>
					@else
						<button type="button" wire:click.prevent="confirmerDelete({{$ids}})" class="btn btn-sm btn-default btn_delete" title="Cliquez pour supprimer"><i class="fa fa-trash"></i></button> 
					@endif	        
				</div>
			</div>
		</div>
	</div>
</div>