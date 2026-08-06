<div>
	<div wire:ignore.self class="modal fade" id="afficheConsoOFModal" tabindex="-1"  data-bs-backdrop="static" role="dialog" aria-labelledby="slidemodalLabel" aria-hidden="true">
		<form enctype="multipart/form-data">							
			<div class="modal-dialog modal-md">
				<div class="modal-content">             
					<div class="modal-header p-2">
						<h3 class="modal-title fs-5"><i class="fas fa-refresh text-danger"></i> Consommation ({{$detail_consoCount}})</h3>
					</div>
					<div class="modal-body py-1 px-0">   
                        <div class="card-body no_bordure border-topk rounded-3">
                            <div class="m-0 text-center">
                              <label for="nom_produit" class="form-label text-vert text-center fs-3">{{$nom_composant}}</label>
                              <div class="text-center table-responsive border-top border-bottomk">
                                <table class="table table-striped table-hover text-nowrap m-0"> 
                                    <thead>
                                        <tr>
                                            @if($this->type_nomencla == "Fabrication")
                                                <th class="text-center fond_entete_table">Quantité consommée</th>
                                            @else
                                                <th class="text-center fond_entete_table">Quantité produite</th>
                                            @endif
                                            <th class="text-start fond_entete_table">Entrepôt</th>
                                            <th class="text-start fond_entete_table">Date</th>
                                        </tr>
                                        </thead>                                       
                                    <tbody>
                                        @if($detail_consoCount > 0)
                                            @foreach($detail_conso as $detail_consos)
                                                <tr> 
                                                    <td class="text-center fw-semibold">{{$detail_consos->quantite_consommer}} {{$detail_consos->unite}}</td>  
                                                    <td class="text-start"><a href="detail_entrepot?id={{$detail_consos->entrepot_id}}&active=4&champ=3-1&choix=2" wire:navigate class="text-bleu fw-semibold">{{$detail_consos->entrepot_conso}}</a></td> 
                                                    <td class="text-start">{{date('d-m-Y H:i:s', strtotime($detail_consos->created_at))}}</td>  
                                                </tr>
                                            @endforeach
                                            <tr> 
                                                <td class="text-center fw-bold text-bleu">Total » {{number_format($detail_consoTotal,0,',',' ')}}</td> 
                                                <td colspan="2" class="text-end fw-bold"></td> 
                                                {{-- <td class="text-end"></td>  --}}
                                            </tr>  
                                        @else
                                            <tr> 
                                                <td colspan="3" class="retire text-center fw-bold">
                                                    <span class=""><i class="fa fa-refresh fa-spin"></i> Désolé, pas de consommation trouvée</span>
                                                </td>   
                                            </tr>
                                        @endif         
                                    </tbody>
                                </table>
                              </div>                             
                            </div>                            
                        </div>
					</div>
                    <div class="d-flex align-items-center justify-content-flex-end modal-footer py-0 border-top border-bottom">					
                        {{-- <button type="submit" wire:click.prevent="()" class="btn btn-secondary fw-bold" title="Cliquez pour enregistrer" data-toggle="tooltip"><i class="fa fa-check"></i> Valider</button> --}}
                        <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal"><i class="fa fa-close"></i></button>
                    </div>
				</div>
			</div>
		</form>   
	</div>
</div>

