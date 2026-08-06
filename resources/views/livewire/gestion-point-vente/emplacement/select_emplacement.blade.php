<div wire:ignore.self class="modal fade" id="selectEmplacementModal" tabindex="-1" data-bs-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content">
			<div class="">
				<div class="modal-header py-2 modal_tete" style="border-radius: .0rem; background-color: #56585d; color:#ffffff;">
					<h5 class="modal-title mod_titre" id="exampleModalLabel"><i class="fas fa-puzzle-piece"></i> Commande en attente ou clôturée »<span class="noir_fonce"></span></h5>
					<button type="button" class="btn-close mod_close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				{{-- Debut chargement --}}        
				<div wire:loading class="chargement_pos">
					<label for=""></label>
					<img src="storage/default/circle_loading.gif" width="64" height="64" style="background: #28282800; margin-left: -3px;">
				</div>
				{{-- Fin chargement --}}				
				<div class="modal-body">					
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">								
							<div class="card card-secondary card-outline responsives new_color">
								<div class="table-responsive">
									<table class="table text-nowrap mb-0">															
										<tbody class="affiachat_prod">                                                            
											<tr>
												<td class="fw-bold">Date</td>
												<td class="text-bleu fw-bold">{{date('d-m-Y H:i:s')}}</td>
												<td class="fw-bold">Société</td>
												<td class="">{{auth()->user()->societe}}</td>   
											</tr>                                            
											<tr>
												<td class="fw-bold">Responsable</td>
												<td class="">{{auth()->user()->name}}</td></td> 
												<td class="fw-bold">Commande en attente</td>
												<td class="text-bleu fw-bold">{{$cmdAttenteCount}}</td> 
											</tr>																												                            
										</tbody>
									</table>											
								</div>
							</div>									
						</div>												    
					</div>
					<div class="tab-content" id="custom-tabs-four-tabContent">
						<div class="tab-pane fade show active" id="infos" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
							<div class="row">																						
								<div class="col-md-12 col-sm-12 col-xs-12 mt-2">
									<div class="card card-secondary card-outline mb-0 table-responsive border rounded-3" style="height: 40.7em;overflow: auto;">
										<table class="table table-hover text-nowrap m-0">
											<thead class="entete_table">
												<tr>
													<th class="fond_entete_table text-start">Date</th>
													<th class="fond_entete_table text-start">Opération</th>
													<th class="fond_entete_table text-start">Utilisation</th>													
													<th class="fond_entete_table text-start">Consommation</th>
													<th class="fond_entete_table text-start">Rendez-vous</th>
													<th class="fond_entete_table text-start">Livraison</th>
													<th class="fond_entete_table">Serveur-se &nbsp; <span class="pointer" wire:click.prevent="reinitialiser()"><i class="fa fa-trash text-danger" title="Cliquez pour effacer" data-toggle="tooltip"></i></span></th>	
												</tr>
											</thead>
											<tbody>	
												@if($testVides > 0)	
													@foreach($emplacement as $emplacements)
														<tr class="corps_table">	
															<td class="fw-semibold">{{date('d/m/Y H:i:s', strtotime($emplacements->updated_at))}}</td>	
															@if(!$test_vide > 0 )
																@if($emplacements->utiliser == "Oui")
																		<td class="fond_bleu pointer fw-semibold text-warning" wire:click.prevent="reprendreCmd({{$emplacements->id}})" title="Cliquez pour reprendre la commande en attente"><span style="color:#4b4949"><i class="fa fa-puzzle-piece"></i> {{substr($emplacements->nom_emplacement,0,32) > substr($emplacements->nom_emplacement,0,31) ? substr($emplacements->nom_emplacement,0,32).'...': $emplacements->nom_emplacement}} »</span> Clôturer <span style="color: #838383">/</span> Ajouter ?</td>
																@else
																		<td class="fond_bleu pointer" wire:click.prevent="attente({{$emplacements->id}})" title="Cliquez pour mettre la commande en attente"><span style="color:#4b4949"><i class="fa fa-puzzle-piece"></i> {{substr($emplacements->nom_emplacement,0,32) > substr($emplacements->nom_emplacement,0,31) ? substr($emplacements->nom_emplacement,0,32).'...': $emplacements->nom_emplacement}} »</span> Mettre en attente !</td>
																@endif
															@else
																@if($emplacements->utiliser == "Oui")
																	<td class="fond_bleu pointer fw-semibold" wire:click.prevent="reprendreCmd({{$emplacements->id}})" title="Cliquez pour reprendre la commande en attente"><span style="color:#4b4949"><i class="fa fa-puzzle-piece"></i> {{substr($emplacements->nom_emplacement,0,32) > substr($emplacements->nom_emplacement,0,31) ? substr($emplacements->nom_emplacement,0,32).'...': $emplacements->nom_emplacement}} »</span> Clôturer !</td>
																@else
																	<td class="fond_bleu pointer fw-semibold text-warning" wire:click.prevent="attente({{$emplacements->id}})" title="Cliquez pour mettre la commande en attente"><span style="color:#4b4949"><i class="fa fa-puzzle-piece"></i> {{substr($emplacements->nom_emplacement,0,32) > substr($emplacements->nom_emplacement,0,31) ? substr($emplacements->nom_emplacement,0,32).'...': $emplacements->nom_emplacement}} »</span> Mettre en attente ?</td>
																@endif																
															@endif	
															
															@if($emplacements->utiliser == "Oui")
																<td class="text-danger"><i class="fa fa-cog fa-spin"></i> Encours</td>
															@else
																<td class="text-green"><i class="fa fa-check-circle"></i> Disponible</td> 
															@endif		
															<td class="fw-semibold" style="color: @if($emplacements->lieu_consommation == "A emporter") #3b0da6; @elseif($emplacements->lieu_consommation == "Livraison") #0a9682; @else #a63c96; @endif">@if($emplacements->lieu_consommation)<i class="fa fa-check-circle"></i>@endif {{$emplacements->lieu_consommation}}</td>                                  
															<td class="fw-semibold" style="color: @if($emplacements->lieu_consommation == "A emporter") #3b0da6; @elseif($emplacements->lieu_consommation == "Livraison") #0a9682; @else #a63c96; @endif">@if(date('d-m-Y H:i', strtotime($emplacements->date_consommation)) != "01-01-1970 01:00")<i class="fa fa-calendar"></i> {{date('d-m-Y H:i', strtotime($emplacements->date_consommation))}} @endif</td>                                  
															<td class="fw-semibold" style="color: #0a9682;">@if($emplacements->lieu_consommation == "Livraison")<i class="fa fa-location"></i>@endif {{$emplacements->adresse_livraison}}</td>  
															<td class="fw-semibold">@if($emplacements->nom_user)<i class="fa fa-user-circle text-muted"></i>@endif {{substr($emplacements->nom_user,0,42) > substr($emplacements->nom_user,0,41) ? substr($emplacements->nom_user,0,42).'...': $emplacements->nom_user}} @if($emplacements->non_caissiere) [<span style="color:#ff5a00; font-weight:bold;">{{substr($emplacements->non_caissiere,0,15)}}</span>(Modifié)] @endif</td>
														</tr>
													@endforeach
												@else
													<tr>
														<td colspan="10" class="label_pv text-center blink text-warning">Pas de données emplacement enregistrées!</td>
													</tr>
												@endif	
											</tbody>														
										</table>
									</div>
								</div>                     
							</div>                               
						</div>								  					
					</div>					
				</div>
				<div class="modal-footer pt-0 pb-0"> 					
					{{-- <button type="button" class="btn btn-default annul" title="Cliquez pour fermer" data-bs-dismiss="modal"><i class="fa fa-close"></i></button> --}}
				</div>
			</div>
		</div>
	</div>
</div>