<div>
	<div wire:ignore.self class="modal fade" id="imageMonCompteModal" tabindex="-1" role="dialog" aria-labelledby="slidemodalLabel" aria-hidden="true">
		<form enctype="multipart/form-data">							
			<div class="modal-dialog modal-sm">
				<div class="modal-content">             
					<div class="modal-header py-2" style="background-color: #56585d; color:#ffffff;">
						<h3 class="modal-title fs-5"><i class="fa fa-camera"></i> Image</h3>
					</div>
					<div class="modal-body">   
                        <div class="card-body">
                            <div class="m-0">
                              <label for="image" class="form-label">Sélectionnez une image (300x300)</label>
                              <div class="text-center p-3">
                                    @if($profil != null)  
                                        <img class="w-full h-32 rounded-3" style="width: 72px;border-radius: 50%;" src="storage/{{$profil}}">
                                    @else       
                                        <img class="w-full h-32 rounded-3" style="width: 72px;border-radius: 50%;" src="storage/default/user_man.png">                     
                                    @endif 
                              </div>
                              <input type="file" wire:model.defer="profils" class="form-control @error('profils') is-invalid @enderror"  id="profils">
                              @error('profils')<div class="text-danger invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
					</div>
                    <div class="d-flex align-items-center justify-content-between modal-footer py-1" style="background-color: #56585d; color:#ffffff;">					
                        <div class="">
                            <button type="submit" wire:click.prevent="valider_img()" class="btn btn-sm btn-secondary fw-bold" title="Cliquez pour enregistrer" data-toggle="tooltip"><i class="fa fa-check"></i> Valider</button>
                            <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal"><i class="fa fa-close"></i></button>
                        </div>
                    </div>
				</div>
			</div>
		</form>   
	</div>
</div>

