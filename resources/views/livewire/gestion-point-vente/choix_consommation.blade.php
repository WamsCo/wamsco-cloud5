<div wire:ignore.self class="modal fade" id="afficheChoixConsoModal" tabindex="-1" data-bs-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">    
    <form enctype="multipart/form-data"> 
            @csrf                   
        <div class="modal-dialog modal-sm">
            <div class="modal-content">             
                <div class="modal-header py-1" style="padding-top: 0px; padding-bottom: 0px; background-color: #56585d; color:#ffffff;">                    
                    <h3 class="titre_modal_fidelite text-white"><i class="fas fa-location"></i> Lieu de consommation</h3>
                    <div wire:loading.delay style="position: absolute;left: 110px;">
						<label for=""></label>
						<img src="storage/default/circle_loading.gif" width="64" height="64" style="margin-left: -3px;">
					</div>
                </div>
                <div class="modal-body">                                                         
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <label for="lieu_conso" class="control-label label_connexion">Sélectionner un lieu</label>
                        <div class="row mb-1">
                            <div class="col-sm-12">                                                        
                                <select id="lieu_conso" wire:model.live="lieu_conso" class="form-control form-select pointer bordure w-100 @error('lieu_conso') is-invalid @enderror">
                                    <option value=""></option>
                                    <option value="Sur place">Sur place</option>
                                    <option value="A emporter">A emporter</option>                                       
                                    <option value="Livraison">Livraison</option>	
                                </select>
                            </div>
                            <div class="d-flex justify-content-start">
                                @error('lieu_conso') <span class="text-danger">{{ $message }}</span> @enderror 
                            </div>
                        </div>                        
                        <label for="date_conso" class="control-label label_connexion">Date</label>
                        <div class="row mb-1">
                            <div class="col-sm-12">
                                <input type="datetime-local" wire:model="date_conso" class="form-control bordure @error('date_conso') is-invalid @enderror" id="date_conso">
                            </div>
                            <div class="d-flex justify-content-start">
                                @error('date_conso') <span class="text-danger">{{ $message }}</span> @enderror 
                            </div>
                        </div>
                        @if($lieu_conso == "Livraison")
                            <label for="adresse_livraison" class="control-label label_connexion">Adresse de livrason</label>
                            <div class="row mb-1">
                                <div class="col-sm-12">
                                    <textarea rows="1" wire:model="adresse_livraison" class="form-control bordure @error('adresse_livraison') is-invalid @enderror" id="adresse_livraison" placeholder="Ex: Douala Bonapriso..."></textarea>
                                </div>
                                <div class="d-flex justify-content-start">
                                    @error('adresse_livraison') <span class="text-danger">{{ $message }}</span> @enderror 
                                </div>
                            </div>
                        @endif                        									
                    </div> 
                </div>
                <div class="d-flex d-flex justify-content-center gap-1 pt-1 pb-1" style="background-color: #56585d; color:#ffffff;">                    
                    <button class="btn btn-secondary btn-sm" wire:click.prevent="validerLieu()" title="Cliquez pour valider" data-toggle="tooltip"><i class="fa fa-check"></i> Valider</button>
                    <a href="#" class="btn btn-danger btn-sm" data-bs-dismiss="modal" title="Cliquez pour annuler" data-toggle="tooltip"><i class="fa fa-close"></i></a>
                </div>              
            </div>
        </div>
    </form>   
</div> 