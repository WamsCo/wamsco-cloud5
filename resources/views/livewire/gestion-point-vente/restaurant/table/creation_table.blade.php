<div wire:ignore.self class="modal fade" id="createTableModal" tabindex="-1" data-bs-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">    
    <form enctype="multipart/form-data"> 
            @csrf                   
        <div class="modal-dialog modal-sm">
            <div class="modal-content">             
                <div class="modal-header py-1" style="padding-top: 0px; padding-bottom: 0px; background-color: #56585d; color:#ffffff;">                    
                    <h3 class="titre_modal_fidelite text-white"><i class="fas fa-check-circle"></i> Ajouter une table</h3>                    
                </div>
                <div class="modal-body">                    
                    <div class="row mb-1 mt-2">
                        {{-- <label for="nom_table" class="col-sm-1 fw-bold col-form-label"><i class="fa fa-check-circle"></i></label> --}}
                        <div class="col-sm-12">
                            <input type="text" wire:model="nom_table" placeholder="Par ex: Table 1" class="form-control bordure w-100 @error('nom_table') is-invalid @enderror" id="nom_table">
                        </div>
                        <div class="d-flex justify-content-start">
                            @error('nom_table') <span class="text-danger">{{ $message }}</span> @enderror 
                        </div>
                    </div>
                    <div class="row mb-1">
                        {{-- <label for="description" class="col-sm-4 col-form-label">Description</label> --}}
                        <div class="col-sm-12">                                                        
                            <textarea rows="1" wire:model="description" class="form-control bordure" id="description" placeholder="Ajouter une description..."></textarea>
                        </div>
                        <div class="d-flex justify-content-start">
                            @error('description') <span class="text-danger">{{ $message }}</span> @enderror 
                        </div>
                    </div> 
                    <div class="row mb-1">
                        {{-- <label for="evolution" class="col-sm-3 fw-bold col-form-label">Espace</label> --}}
                        <div class="col-sm-12">                                                        
                            <select id="evolution" wire:model="evolution" class="form-control form-select bordure w-100 @error('evolution') is-invalid @enderror" id="evolution">
                                <option value="">Selectionner l'espace</option>	
                                @foreach($espace as $espaces)
                                    <option value="{{$espaces->id}}">{{$espaces->nom_espace}}</option>
                                @endforeach	                              
                            </select> 
                        </div>
                        <div class="d-flex justify-content-start">
                            @error('evolution') <span class="text-danger">{{ $message }}</span> @enderror 
                        </div>
                    </div>                                       
                </div>
                <div class="d-flex d-flex justify-content-center gap-1 pt-1 pb-1" style="background-color: #56585d; color:#ffffff;">                    
                    <button class="btn btn-sm btn-secondary" wire:click.prevent="store()" title="Cliquez pour valider" data-toggle="tooltip"><i class="fa fa-save"></i> Enregistrer</button>
                    <a href="#" class="btn btn-sm btn-danger" data-bs-dismiss="modal" title="Cliquez pour annuler" data-toggle="tooltip"><i class="fa fa-close"></i></a>
                </div>              
            </div>
        </div>
    </form>   
</div> 