<div>
	<div wire:ignore.self class="modal fade" id="expedionPartielModal" tabindex="-1"  data-bs-backdrop="static" role="dialog" aria-labelledby="slidemodalLabel" aria-hidden="true">
		<form enctype="multipart/form-data">							
			<div class="modal-dialog modal-sm">
				<div class="modal-content">             
					<div class="modal-header p-1">
						<h3 class="modal-title fs-5"><i class="fas fa-dolly text-danger"></i> Expédition partielle = {{$this->ids}}</h3>
					</div>
					<div class="modal-body py-1">   
                        <div class="card-body p-0">
                            <div class="m-0 text-center">
                              <label for="nom_produit" class="form-label text-vert text-center fs-3">{{$nom_produit}}</label>
                              <div class="text-center table-responsive border rounded-1">
                                <table class="table table-striped table-hover text-nowrap m-0"> 
                                    <thead>
                                        <tr>
                                            <th class="text-center fond_entete_table">Qté com.</th>
                                            <th class="text-center fond_entete_table">Qté. expédiée</th>
                                            <th class="text-center fond_entete_table">Reste</th>
                                        </tr>
                                        </thead>                                       
                                    <tbody>
                                        <tr> 
                                            <td class="text-center fw-semibold">{{$quantite_cmd}}</td>      
                                            <td class="text-center fw-semibold">{{$quantiteExpedie}}</td>  
                                            <td class="text-center fw-semibold">{{$resteExpedier}}</td>  
                                        </tr>          
                                    </tbody>
                                </table>
                              </div>                             
                            </div>
                            <div class="m-0 text-center">
                                <div class="row mt-3">
                                    <label for="reste_expedier" class="col-sm-6 col-form-label">Reste à expédier</label>
                                    <div class="col-sm-6">
                                        <input type="number" wire:model="reste_expedier" placeholder="Ex: 2" min="0" class="form-control bordure w-100 @error('reste_expedier') is-invalid @enderror" id="reste_expedier">
                                    </div>
                                    <div class="d-flex justify-content-start">
                                        @error('reste_expedier') <span class="text-danger">{{ $message }}</span> @enderror 
                                    </div>
                                </div>
                            </div>
                        </div>
					</div>
                    <div class="d-flex align-items-center justify-content-between modal-footer">					
                        <div class="">
                            <button type="submit" wire:click.prevent="expeditionPartiel()" class="btn btn-secondary fw-bold" title="Cliquez pour enregistrer" data-toggle="tooltip"><i class="fa fa-check"></i> Valider</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa fa-close"></i></button>
                        </div>
                    </div>
				</div>
			</div>
		</form>   
	</div>
</div>

