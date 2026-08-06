<div>
	<div wire:ignore.self class="modal fade" id="fabriPartielModal" tabindex="-1"  data-bs-backdrop="static" role="dialog" aria-labelledby="slidemodalLabel" aria-hidden="true">
		<form enctype="multipart/form-data">							
			<div class="modal-dialog modal-sm">
				<div class="modal-content">             
					<div class="modal-header p-1">
						<h3 class="modal-title fs-5"><i class="fas fa-copy text-danger"></i> @if($type_nomencla == "Fabrication") Consommation @else Production @endif partielle</h3>
					</div>
					<div class="modal-body py-1">   
                        <div class="card-body p-0">
                            <div class="m-0 text-center">
                              <label for="nom_produit" class="form-label text-vert text-center fs-3">{{$composant}}</label>
                              <div class="text-center table-responsive border rounded-1">
                                <table class="table table-striped table-hover text-nowrap m-0"> 
                                    <thead>
                                        <tr>
                                            @if($this->type_nomencla == "Fabrication")
                                                <th class="text-center fond_entete_table">A consommer</th>
                                                <th class="text-center fond_entete_table">Consommé</th>
                                            @else
                                                <th class="text-center fond_entete_table">A produire</th>
                                                <th class="text-center fond_entete_table">Produire</th>
                                            @endif
                                            <th class="text-center fond_entete_table">Reste</th>
                                        </tr>
                                        </thead>                                       
                                    <tbody>
                                        <tr> 
                                            <td class="text-center fw-semibold">{{$quantiteCompos}}</td>      
                                            <td class="text-center fw-semibold">{{$quantiteConsommer}}</td>  
                                            <td class="text-center fw-semibold">{{$resteAconsommer}}</td>  
                                        </tr>          
                                    </tbody>
                                </table>
                              </div>                             
                            </div>
                            <div class="m-0 text-center">
                                <div class="row mt-3">
                                    @if($this->type_nomencla == "Fabrication")
                                        <label for="reste_a_consommer" class="col-sm-6 col-form-label">Reste à consommer</label>
                                    @else
                                        <label for="reste_a_consommer" class="col-sm-6 col-form-label">Reste à produire</label>
                                    @endif
                                    <div class="col-sm-6">
                                        <input type="number" wire:model="reste_a_consommer" placeholder="Ex: 2" min="0" class="form-control bordure w-100 @error('reste_a_consommer') is-invalid @enderror" id="reste_a_consommer">
                                    </div>
                                    <div class="d-flex justify-content-start">
                                        @error('reste_a_consommer') <span class="text-danger">{{ $message }}</span> @enderror 
                                    </div>
                                </div>
                                <div class="row mt-3 mb-1">
                                    <label for="choix_entrepot" class="col-sm-3 fw-bold col-form-label">Entrepôt</label>
                                    <div class="col-sm-9">   
                                        <select id="choix_entrepot" wire:model.defer="choix_entrepot" class="form-control form-select bordure w-100 @error('choix_entrepot') is-invalid @enderror">
                                            <option value=""></option>	
                                            @foreach($entrepot as $entrepots)
                                                @foreach($stock_entrepot as $stock_entrepots)
                                                    @if($entrepots->id == $stock_entrepots->id_entrepot)
                                                        <option value="{{$entrepots->id}}">{{$entrepots->nom}} - Stock » {{$stock_entrepots->quantite}}</option>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="d-flex justify-content-start">
                                        @error('choix_entrepot') <span class="text-danger">{{ $message }}</span> @enderror 
                                    </div>
                                </div> 
                            </div>
                        </div>
					</div>
                    <div class="d-flex align-items-center justify-content-flex-end py-1 modal-footer">					
                        <div class="">
                            <button type="submit" wire:click.prevent="fabricationPartiel()" class="btn btn-sm btn-secondary fw-bold" title="Cliquez pour enregistrer" data-toggle="tooltip"><i class="fa fa-check"></i> Valider</button>
                            <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal"><i class="fa fa-close"></i></button>
                        </div>
                    </div>
				</div>
			</div>
		</form>   
	</div>
</div>

