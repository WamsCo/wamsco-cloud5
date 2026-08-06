<div wire:ignore.self class="modal fade" id="soldeTiersModal" tabindex="-1" role="dialog" aria-labelledby="slidemodalLabel" aria-hidden="true">
    <form enctype="multipart/form-data"> 
            @csrf                   
        <div class="modal-dialog modal-sm modal_wamsco_catg">
            <div class="modal-content">             
                <div class="modal-header" style="padding-top: 0px; padding-bottom: 0px; background-color: #56585d; color:#ffffff;">                    
                    <h5 class="py-1"><i class="fas fa-file-invoice-dollar"></i> Recharge (FCFA) </h5>                    
                </div>
                <div class="modal-body">                                                         
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="nom_desig">
                            <div class="row mb-1">
                                <label for="solde" class="col-sm-5 col-form-label">Solde disponible</label>
                                <div class="col-sm-7">
                                    <input type="text" wire:model="solde" readonly placeholder="Ex: 10000" style="color: #ff5252; font-weight: 600;" class="form-control bordure @error('nombre_point') is-invalid @enderror" id="solde">
                                </div>
                                <div class="d-flex justify-content-start">
                                    @error('solde') <span class="text-danger">{{ $message }}</span> @enderror 
                                </div>
                            </div>
                            <div class="row mb-1">
                                <label for="montant_recu" class="col-sm-5 col-form-label fw-bold">Montant reçu</label>
                                <div class="col-sm-7">
                                    <input type="number" wire:model="montant_recu" placeholder="Ex: 10000" min="0" class="form-control bordure @error('montant_recu') is-invalid @enderror" id="montant_recu">
                                </div>
                                <div class="d-flex justify-content-start">
                                    @error('montant_recu') <span class="text-danger">{{ $message }}</span> @enderror 
                                </div>
                            </div>
                            <div class="row mb-1">
                                <label for="compte" class="col-lg-4 col-md-5 col-sm-4 fw-bold col-form-label">Compte</label>
                                <div class="col-lg-8 col-md-7 col-sm-8">                                                        
                                    <select id="compte" wire:model.live="compte" class="form-control form-select bordure w-100 @error('compte') is-invalid @enderror" id="compte">
                                        <option value=""></option>	
                                        <option value="Espèce">Espèce</option>
                                        <option value="Chèque">Chèque</option>
                                        <option value="Orange Money">Orange Money</option>
                                        <option value="MTN Mobile Money">MTN Mobile Money</option>
                                        <option value="Carte bancaire">Carte bancaire</option>	
                                        <option value="Virement bancaire">Virement bancaire</option>
                                        <option value="Versement bancaire">Versement bancaire</option>
                                        <option value="Ordre de prélèvement">Ordre de prélèvement</option>
                                    </select>  
                                </div>
                                <div class="d-flex justify-content-start">
                                    @error('compte') <span class="text-danger">{{ $message }}</span> @enderror 
                                </div>
                            </div>
                            <div class="row mb-1">
                                <label for="sens" class="col-sm-5 col-form-label fw-bold" style="font-weight: 400;">Sens</label>
                                <div class="col-sm-7">                                                        
                                    <select id="sens" wire:model="sens" class="form-control form-select bordure w-auto @error('sens') is-invalid @enderror">
                                        <option value=""></option>                                           	
                                        <option value="Débit">Débit (Retirer)</option>                                           	
                                        <option value="Crédit">Crédit (Ajouter)</option>	
                                    </select> 
                                </div>
                                <div class="d-flex justify-content-start">
                                    @error('sens') <span class="text-danger">{{ $message }}</span> @enderror 
                                </div>
                            </div> 						                               
                        </div>								
                    </div> 
                </div>
                <div class="modal-footer pt-0 pb-0 modal_peids" style="background-color: #56585d; color:#ffffff;"> 
					<button class="btn btn-sm btn-secondary fw-bold" title="Cliquez pour enregistrer" data-toggle="tooltip" wire:click.prevent="rechargeCredit()"><i class="fa fa-check"></i> Valider</button>              
					<button type="button" class="btn btn-sm btn-danger" title="Cliquez pour fermer" data-bs-dismiss="modal"><i class="fa fa-close"></i></button>        
				</div>
            </div>
        </div>
    </form>   
</div> 