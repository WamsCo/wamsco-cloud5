<div wire:ignore.self class="modal fade" id="afficheFidelitModal" tabindex="-1" role="dialog" aria-labelledby="slidemodalLabel" aria-hidden="true">
    <form enctype="multipart/form-data"> 
            @csrf                   
        <div class="modal-dialog modal-sm modal_wamsco_catg">
            <div class="modal-content">             
                <div class="modal-header" style="padding-top: 0px; padding-bottom: 0px;">                    
                    <h5 class="py-2"><i class="fas fa-podcast"></i> Point de fidélité</h5>
                    <div wire:loading.delay style="position: absolute;left: 198px;">
						<label for=""></label>
						<img src="storage/default/circle_loading.gif" width="64" height="64" style="margin-left: -3px;">
					</div>
                </div>
                <div class="modal-body">                                                         
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="nom_desig">
                            <div class="row mb-1">
                                <label for="nombre_point" class="col-sm-5 col-form-label">Total Point</label>
                                <div class="col-sm-7">
                                    <input type="text" wire:model="nombre_point" readonly placeholder="Ex: 800" style="color: #ff5252; font-weight: 600;" class="form-control bordure @error('nombre_point') is-invalid @enderror" id="nom_categorie">
                                </div>
                                <div class="d-flex justify-content-start">
                                    @error('nombre_point') <span class="text-danger">{{ $message }}</span> @enderror 
                                </div>
                            </div>
                            <div class="row mb-1">
                                <label for="retrait_point" class="col-sm-5 col-form-label fw-bold">Retirer point</label>
                                <div class="col-sm-7">
                                    <input type="number" wire:model="retrait_point" placeholder="Ex: 800" min="0" class="form-control bordure @error('retrait_point') is-invalid @enderror" id="nom_categorie">
                                </div>
                                <div class="d-flex justify-content-start">
                                    @error('retrait_point') <span class="text-danger">{{ $message }}</span> @enderror 
                                </div>
                            </div>						                               
                        </div>								
                    </div> 
                </div>
                <div class="modal-footer pt-0 pb-0 modal_peids"> 
					<button class="btn btn-secondary fw-bold" title="Cliquez pour enregistrer" data-toggle="tooltip" wire:click.prevent="RetraitPoint()"><i class="fa fa-check"></i> Valider</button>              
					<button type="button" class="btn btn-danger" title="Cliquez pour fermer" data-bs-dismiss="modal"><i class="fa fa-close"></i></button>        
				</div>
            </div>
        </div>
    </form>   
</div> 