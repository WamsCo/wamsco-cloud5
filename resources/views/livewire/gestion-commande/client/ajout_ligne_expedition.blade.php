    
<td valign="middle" class="text-bleu">  
    <div class="row">  
        <div class="col-sm-12">  
            <label for="nom_produit" class="col-md-12 col-sm-12 fw-bold col-form-label">{{$this->nom_produit}}</label>
        </div>
    </div>  
</td>
<td class="text-muted text-bleu fw-semibold">
    <div class="row">
        <div class="col-sm-12">  
            <label for="type" class="col-md-12 col-sm-12 fw-bold col-form-label">Type</label>
            <div class="col-sm-12">
                <label for="type" class="col-md-12 text-muted col-form-label">{{$type}}</label>                                                        
            </div>
        </div>
    </div>
</td>                                                         
<td class="text-center text-bleu">
    <div class="row">
        <div class="col-sm-12">  
            <label for="nom_produit" class="col-md-12 col-sm-12 fw-bold col-form-label">Qté com.</label>
            <div class="col-sm-12">
                <label for="type" class="col-md-12 text-muted col-form-label">{{$quantite_cmde}}</label>                                                        
            </div>
        </div>
    </div>
</td> 
<td class="text-center text-bleu">
    <div class="row">
        <label for="reste_A_expedier" class="col-md-12 col-sm-12 fw-bold col-form-label text-start">Qté. à expédier </label>
        <div class="col-sm-12">                                                        
            <input type="number" wire:model="reste_A_expedier" placeholder="Ex: 2" min="0" style="width: 95px;" class="form-control bordure @error('reste_A_expedier') is-invalid @enderror" id="reste_A_expedier">
        </div>
        <div class="d-flex justify-content-start">
            @error('reste_A_expedier') <span class="text-danger">{{ $message }}</span> @enderror 
        </div>
    </div>
</td> 
<td class="text-center text-bleu">
    <div class="row">
        <div class="col-sm-12">  
            <label for="nom_produit" class="col-md-12 col-sm-12 fw-bold col-form-label">Reste à expédier</label>
            <div class="col-sm-12">
                <label for="type" class="col-md-12 text-muted col-form-label">{{$resteExpedier}}</label>                                                        
            </div>
        </div>
    </div>
</td>
<td colspan="3" valign="middle" class="text-center fw-semibold">
    <div class="">
        <a href="#"  wire:click.prevent="AjouterLigneExpe()" class="btn btn-outline-success btn-sm"  title="Cliquez pour ajouter" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ri-check-line"></i> Ajouter</a>
        <a wire:click="onDataOpen()" class="btn btn-sm btn-white" title="Cliquez pour annuler"><i class="fa fa-close text-danger"></i></a>
    </div>
</td>

   