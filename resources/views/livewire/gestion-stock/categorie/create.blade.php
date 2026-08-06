<div wire:ignore.self class="modal fade" id="ajoutCategorieModal" tabindex="-1" data-bs-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">    
    <form enctype="multipart/form-data"> 
            @csrf                   
        <div class="modal-dialog modal-sm">
            <div class="modal-content">             
                <div class="modal-header py-1" style="padding-top: 0px; padding-bottom: 0px; background-color: #56585d; color:#ffffff;">                    
                    <h4 class="titre_modal_fidelite text-white"><i class="fa fa-chart-pie text-danger"></i> Nouvelle catégorie</h4>                    
                </div>
                <div class="modal-body">
                    <div class="row mb-1">
                        {{-- <label for="nom_categorie" class="col-sm-3 col-form-label fw-bold">Nom</label> --}}
                        <label for="nom_categorie" class="col-sm-1 fw-bold col-form-label"><i class="fa fa-chart-pie text-danger"></i></label>
                        <div class="col-sm-11">
                            <input type="text" wire:model="nom_categorie" placeholder="Ex: Fruit" class="form-control bordure @error('nom_categorie') is-invalid @enderror" id="nom_categorie">
                        </div>
                        <div class="d-flex justify-content-start">
                            @error('nom_categorie') <span class="text-danger">{{ $message }}</span> @enderror 
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label for="description" class="col-sm-1 col-form-label"><i class="fa fa-list text-danger"></i></label>
                        <div class="col-sm-11">                                                        
                            <textarea rows="2" wire:model="description" class="form-control bordure" id="description" placeholder="Ajouter une description..."></textarea>
                        </div>
                        <div class="d-flex justify-content-start">
                            @error('description') <span class="text-danger">{{ $message }}</span> @enderror 
                        </div>
                    </div>                    
                    <div class="row mb-1">
                        <label for="restaurant" class="col-sm-5 fw-bold col-form-label">Restaurant</label>
                        <div class="col-sm-7">                                                        
                            <select id="restaurant" wire:model="restaurant" class="form-control form-select bordure w-100 @error('restaurant') is-invalid @enderror" id="restaurant">
                                <option value=""></option>	
                                <option value="Oui">Oui</option>                                
                                <option value="Non">Non</option>                                
                            </select> 
                        </div>
                        <div class="d-flex justify-content-start">
                            @error('restaurant') <span class="text-danger">{{ $message }}</span> @enderror 
                        </div>
                    </div>                                        
                </div>
                <div class="d-flex d-flex justify-content-center gap-1 pt-1 pb-1" style="background-color: #56585d; color:#ffffff;">                    
                    <button class="btn btn-sm btn-secondary" wire:click.prevent="store()" title="Cliquez pour enregistrer" data-toggle="tooltip"><i class="fa fa-save"></i> Enregistrer</button>
                    <a href="#" class="btn btn-sm btn-danger" data-bs-dismiss="modal" title="Cliquez pour annuler ou fermer" data-toggle="tooltip"><i class="fa fa-close"></i></a>                    
                </div>              
            </div>
        </div>
    </form>   
</div> 