<div wire:ignore.self class="modal fade" id="afficheFideliteModal" tabindex="-1" data-bs-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">    
    <form enctype="multipart/form-data"> 
            @csrf                   
        <div class="modal-dialog modal-sm">
            <div class="modal-content">             
                <div class="modal-header py-1" style="padding-top: 0px; padding-bottom: 0px; background-color: #56585d; color:#ffffff;">                    
                    <h3 class="titre_modal_fidelite text-white"><i class="fas fa-podcast"></i> Point de fidélité</h3>
                    <div wire:loading.delay style="position: absolute;left: 198px;">
						<label for=""></label>
						<img src="storage/default/circle_loading.gif" width="64" height="64" style="margin-left: -3px;">
					</div>
                </div>
                <div class="modal-body">                                                         
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <label class="control-label label_connexion">Montant correspondant à <span style="color: #ff0000; font-weight: 600;">1</span> point</label><br/>
                        <div class="input-group mb-3">							 
                            <div class="input-group-prepend">
                                <span class="input-group-text label_modif"><i class="fas fa-money-bill"></i></span>
                            </div>
                            <input type="number" wire:model.defer="montant_point" class="form-control recherche marge new_color @error('montant_point') is-invalid @enderror" placeholder="Ex: 1000">                            
                            <div class="input-group-prepend">
                                <span class="input-group-text label_modif" style="padding: 9px;">{{$devise}}</span>
                            </div>
                            @error('montant_point')<div class="text-danger invalid-feedback">{{ $message }}</div>@enderror 
                        </div>

                        <label class="control-label label_connexion">Objectif à atteindre en point</label><br/>
                        <div class="input-group mb-1">                           
                            <input type="number" wire:model.defer="objectif_point" class="form-control recherche marge new_color @error('objectif_point') is-invalid @enderror" placeholder="Ex: 800">                            
                            <div class="input-group-prepend">
                                <span class="input-group-text label_modif">Points &nbsp;<i class="fas fa-podcast"></i></span>
                            </div>
                            @error('objectif_point')<div class="text-danger invalid-feedback">{{ $message }}</div>@enderror 
                        </div>									
                    </div> 
                </div>
                <div class="d-flex d-flex justify-content-center gap-1 pt-1 pb-1" style="background-color: #56585d; color:#ffffff;">                    
                    <button class="btn btn-sm btn-secondary" wire:click.prevent="valideFidelite()" title="Cliquez pour valider" data-toggle="tooltip"><i class="fa fa-check"></i> Valider</button>
                    <a href="#" class="btn btn-sm btn-danger" data-bs-dismiss="modal" title="Cliquez pour annuler" data-toggle="tooltip"><i class="fa fa-close"></i></a>
                </div>              
            </div>
        </div>
    </form>   
</div> 