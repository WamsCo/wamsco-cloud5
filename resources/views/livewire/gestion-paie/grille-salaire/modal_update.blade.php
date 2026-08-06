<div wire:ignore.self class="modal fade" id="updateGrilleModal" tabindex="-1" role="dialog" aria-labelledby="slidemodalLabel" aria-hidden="true">
    <form enctype="multipart/form-data"> 
            @csrf                   
        <div class="modal-dialog modal-sm modal_wamsco_catg">
            <div class="modal-content">             
                <div class="modal-header py-0" style="background-color: #56585d; color:#ffffff;">                    
                    <h5 class="pt-2"><i class="fas fa-file-invoice-dollar"></i> Nouvelle Grille salariale </h5>                    
                </div>
                <div class="modal-body">                                                         
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="nom_desig">
                            <div class="row mb-1">
                                <label for="categorie" class="col-sm-5 col-form-label fw-bold" style="font-weight: 400;">Catégorie</label>
                                <div class="col-sm-7">                                                        
                                    <select id="categorie" wire:model.defer="categorie" class="form-control form-select bordure w-auto @error('categorie') is-invalid @enderror">
                                        <option value=""></option>  
                                        @for($i = 1; $i <= 12; $i += 1)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor	
                                    </select> 
                                </div>
                                <div class="d-flex justify-content-start">
                                    @error('categorie') <span class="text-danger">{{ $message }}</span> @enderror 
                                </div>
                            </div>
                            <div class="row mb-1">
                                <label for="echelon" class="col-sm-5 col-form-label fw-bold" style="font-weight: 400;">Echelon</label>
                                <div class="col-sm-7">                                                        
                                    <select id="echelon" wire:model.defer="echelon" class="form-control form-select bordure w-auto @error('echelon') is-invalid @enderror">
                                        <option value=""></option>                                           	
                                        <option value="A">A</option>   	
                                        <option value="B">B</option>   	
                                        <option value="C">C</option>   	
                                        <option value="D">D</option>   	
                                        <option value="E">E</option>   	
                                        <option value="F">F</option>   	
                                        <option value="G">G</option>   	
                                    </select> 
                                </div>
                                <div class="d-flex justify-content-start">
                                    @error('echelon') <span class="text-danger">{{ $message }}</span> @enderror 
                                </div>
                            </div>
                            <div class="row mb-1">
                                <label for="salaire_base" class="col-sm-5 col-form-label fw-bold">Salaire de base</label>
                                <div class="col-sm-7">
                                    <input type="number" wire:model.defer="salaire_base" placeholder="Ex: 75000" min="0" class="form-control bordure @error('salaire_base') is-invalid @enderror" id="salaire_base">
                                </div>
                                <div class="d-flex justify-content-start">
                                    @error('salaire_base') <span class="text-danger">{{ $message }}</span> @enderror 
                                </div>
                            </div>
                             						                               
                        </div>								
                    </div> 
                </div>
                <div class="modal-footer pt-0 pb-0 modal_peids" style="background-color: #56585d; color:#ffffff;"> 
					<button class="btn btn-sm btn-secondary fw-bold" title="Cliquez pour enregistrer" data-toggle="tooltip" wire:click.prevent="update()"><i class="fa fa-check"></i> Valider</button>              
					<button type="button" class="btn btn-sm btn-danger" title="Cliquez pour fermer" data-bs-dismiss="modal"><i class="fa fa-close"></i></button> 
                    @if($confirmer === $ids)
						<button type="button" wire:click.prevent="supprimer({{$ids}})" class="btn btn-sm bg-danger text-white" title="Confirmez la suppression"><i class="fa fa-trash"> Confirmer?</i></button>
					@else
						<button type="button" wire:click.prevent="confirmerDelete({{$ids}})" class="btn btn-sm btn-default btn_delete" title="Cliquez pour supprimer"><i class="fa fa-trash"></i></button> 
					@endif       
				</div>
            </div>
        </div>
    </form>   
</div> 