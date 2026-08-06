<div wire:ignore.self class="modal fade" id="ajoutEtapeModal" tabindex="-1" data-bs-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content new_color">
			<div class="">
				<div class="modal-header py-2 modal_tete" style="border-radius: .0rem; background-color: #56585d; color:#ffffff;">
				<h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-chart-pie"></i> Nouvelle étape</h5>
				<button type="button" class="btn-close mod_close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body modal_corps">
					<div class="card entete_form new_color">	
						<div class="card-body border">					
							<div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12">								
									<div class="nom_desig">
										<div class="row mb-1">
                                            <label for="nom_etape" class="col-sm-3 col-form-label fw-bold">Étape</label>
                                            <div class="col-sm-9">
                                                <input type="text" wire:model="nom_etape" placeholder="Ex: Gagné" class="form-control bordure @error('nom_etape') is-invalid @enderror" id="nom_etape">
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                @error('nom_etape') <span class="text-danger">{{ $message }}</span> @enderror 
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <label for="description" class="col-sm-3 col-form-label">Description</label>
                                            <div class="col-sm-9">                                                        
                                                <textarea rows="2" wire:model="description" class="form-control bordure" id="description" placeholder="Message..."></textarea>
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
					<button class="btn btn-sm btn-secondary fw-bold" title="Cliquez pour enregistrer" data-toggle="tooltip" wire:click.prevent="store()"><i class="fa fa-check"></i> Créer</button>              
					<button type="button" class="btn btn-sm btn-danger" title="Cliquez pour fermer" data-bs-dismiss="modal"><i class="fa fa-close"></i></button>        
				</div>
			</div>
		</div>
	</div>
</div>