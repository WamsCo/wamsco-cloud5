<div wire:ignore.self class="modal fade" id="importerProduitsModal" tabindex="-1" data-bs-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">    
    <form method="POST" action="{{route('importer_produit')}}" enctype="multipart/form-data">
            @csrf                   
        <div class="modal-dialog modal-md">
            <div class="modal-content">             
                <div class="modal-header wamsco" style="padding-top: 0px; padding-bottom: 0px;">                    
                    <h3 class="titre_modal_fidelite"><i class="fas fa-box-open"></i> Importer Produits</h3>                    
                </div>
                <div class="modal-body">                                                         
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <label class="control-label">Sélectionnez fichier excel (<span style="color: #ff0000; font-weight: 600;">.xlsx, .csv</span>)</label><br/>
                        <div class="row mt-3">
                            <div class="col-sm-12">
                                <input type="file" name="fichier_produit" class="form-control @error('fichier_produit') is-invalid @enderror"/>
                            </div>
                            <div class="d-flex justify-content-start">
                                @error('fichier_produit') <span class="text-danger">{{ $message }}</span> @enderror 
                            </div>
                        </div>                       								
                    </div> 
                </div>
                <div class="d-flex d-flex justify-content-center gap-1 pt-1 pb-1">                    
                    <button class="btn btn-secondary" type="submit" title="Cliquez pour valider" data-toggle="tooltip"><i class="fa fa-check"></i> Valider</button>
                    <a href="#" class="btn btn-danger" data-bs-dismiss="modal" title="Cliquez pour annuler" data-toggle="tooltip"><i class="fa fa-close"></i></a>
                </div>              
            </div>
        </div>
    </form>   
</div> 