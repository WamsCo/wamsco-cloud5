    
<td valign="middle" class="px-3 text-bleu">  
    <div class="row mt-3">  
        <div class="col-sm-12">  
            <label for="nom_produit" class="col-md-12 col-sm-12 fw-bold col-form-label">{{$this->nom_produit}}</label>
        </div>
    </div>  
</td>
<td class="text-muted text-bleu fw-semibold">
    <div class="row mt-3">
        <label for="prix_vente" class="col-md-12 col-sm-12 fw-bold col-form-label text-start">Prix vente</label>
        <div class="col-sm-12">                                                        
            <input type="number" wire:model="prix_vente" placeholder="Ex: 10000" class="form-control bordure @error('prix_vente') is-invalid @enderror" id="prix_vente">
        </div>
        <div class="d-flex justify-content-start">
            @error('prix_vente') <span class="text-danger">{{ $message }}</span> @enderror 
        </div>
    </div>
</td>                                                         
<td class="text-center text-bleu">
    <div class="row mt-3">
        <label for="quantite" class="col-md-12 col-sm-12 fw-bold col-form-label text-start">Quantité</label>
        <div class="col-sm-12">
            <input type="number" wire:model="quantite" placeholder="Ex: 5" min="0" style="width: 95px;" class="form-control bordure @error('quantite') is-invalid @enderror" id="quantite">
        </div>
        <div class="d-flex justify-content-start">
            @error('quantite') <span class="text-danger">{{ $message }}</span> @enderror 
        </div>
    </div>
</td> 
<td class="text-center text-bleu">
    <div class="row mt-3">
        <label for="remise" class="col-md-12 col-sm-12 fw-bold col-form-label text-start">Remise</label>
        <div class="col-sm-12">                                                        
            <input type="number" wire:model="remise" placeholder="Ex: 2.5%" min="0" style="width: 95px;" class="form-control bordure @error('remise') is-invalid @enderror" id="remise">
        </div>
        <div class="d-flex justify-content-start">
            @error('remise') <span class="text-danger">{{ $message }}</span> @enderror 
        </div>
    </div>
</td> 
<td class="text-center text-bleu">
    <div class="row mt-3">
        <label for="tva" class="col-md-12 col-sm-12 fw-bold col-form-label text-start">TVA</label>
        <div class="col-sm-12">                                                        
            <select id="tva" wire:model="tva" style="width: auto;" class="form-control form-select fw-semibold bordure @error('tva') is-invalid @enderror">               	
                <option value="0">0</option>	
                @foreach($taxe as $taxes)
                    @if($taxes->taxe == "Tva")
                        <option value="{{$taxes->taux_tva}}">{{$taxes->taux_tva}}%</option>
                    @endif
                @endforeach	
            </select> 
        </div>
        <div class="d-flex justify-content-start">
            @error('tva') <span class="text-danger">{{ $message }}</span> @enderror 
        </div>
    </div>
</td>  
<td class="text-center text-bleu">
    <div class="row mt-3">
        <label for="precompte" class="col-md-12 col-sm-12 fw-bold col-form-label text-start">Précompte</label>
        <div class="col-sm-12">                                                        
            <select id="precompte" wire:model="precompte" class="form-control form-select fw-semibold bordure @error('precompte') is-invalid @enderror">
                <option value="0">0</option>	
                @foreach($taxe as $taxes)
                    @if($taxes->taxe == "Précompte")
                        <option value="{{$taxes->taux_tva}}">{{$taxes->taux_tva}}%</option>
                    @endif
                @endforeach	
            </select> 
        </div>
        <div class="d-flex justify-content-start">
            @error('precompte') <span class="text-danger">{{ $message }}</span> @enderror 
        </div>
    </div>
</td>  
<td class="text-center text-bleu">
    <div class="row mt-3">
        <label for="offrir" class="col-md-12 col-sm-12 fw-bold col-form-label text-start">Offrir</label>
        <div class="col-sm-12">                                                        
            <select id="offrir" wire:model="offrir" style="width: auto;" class="form-control form-select fw-semibold bordure @error('offrir') is-invalid @enderror">
                <option value="Non">Non</option>                
                <option value="Oui">Oui</option>                
            </select> 
        </div>
        <div class="d-flex justify-content-start">
            @error('offrir') <span class="text-danger">{{ $message }}</span> @enderror 
        </div>
    </div>
</td>         
<td valign="middle" class="text-start fw-semibold">
    <div class="mt-3">
        @if($this->type_produit == 'Produit')
            <a href="#"  wire:click.prevent="ajouter()" class="btn btn-outline-success btn-sm"  title="Cliquez pour ajouter" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ri-check-line"></i> Ajouter</a>
        @elseif($this->type_produit == 'Service')
            <a href="#"  wire:click.prevent="ajouterService('Service')" class="btn btn-outline-success btn-sm"  title="Cliquez pour ajouter" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ri-check-line"></i> Ajouter</a>
        @endif
        <a wire:click="onDataOuverture()" class="btn btn-sm btn-white" title="Cliquez pour annuler"><i class="fa fa-close text-danger"></i></a>
    </div>
</td> 
   