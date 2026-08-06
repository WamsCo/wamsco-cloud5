    
<td class="px-3">  
    <div class="row mt-3" style="width: 163px;">  
        <div class="col-sm-12"> 
            <label class="col-form-label"><a href="detail_entrepot?id={{$id_entrepot}}" target="_blank">{{substr($nameEntrepot,0,32) > substr($nameEntrepot,0,31) ? substr($nameEntrepot,0,32).'...': $nameEntrepot}}</a></label>
        </div>
        <div class="d-flex justify-content-start">
            @error('entrepot') <span class="text-danger">{{ $message }}</span> @enderror 
        </div>   
    </div>  
</td>
<td class="text-bleu fw-semibold">
    <div class="row mt-3">  
        <div class="col-sm-12">                                                        
            <select id="choix_produit" wire:model.live="choix_produit" class="form-control form-select bordure w-auto @error('choix_produit') is-invalid @enderror">
                <option value=""></option>	
                @foreach($produit_stock as $produit_stocks)
                    <option value="{{$produit_stocks->id}}">{{substr($produit_stocks->nom_produit,0,32) > substr($produit_stocks->nom_produit,0,31) ? substr($produit_stocks->nom_produit,0,32).'...': $produit_stocks->nom_produit}} [{{$produit_stocks->reference}}]</option>
                @endforeach	
            </select> 
        </div>
        <div class="d-flex justify-content-start">
            @error('choix_produit') <span class="text-danger">{{ $message }}</span> @enderror 
        </div>   
    </div>  
</td>  
<td class="text-center">
    <div class="row mt-3">
        <label class="fw-bold col-form-label">{{$quantiteInitialEntrepot}}</label>
    </div>
</td>                                                        
<td class="text-center" style="width: 150px;">
    <div class="row mt-3">
        <div class="col-sm-12">
            <input type="number" wire:model="quantite_reelle" placeholder="Ex: 5" class="form-control bordure @error('quantite_reelle') is-invalid @enderror" id="quantite_reelle">
        </div>
        <div class="d-flex justify-content-start">
            @error('quantite_reelle') <span class="text-danger">{{ $message }}</span> @enderror 
        </div>
    </div>
</td>     
<td colspan="2" class="text-end fw-semibold">
    <div class="mt-3">
        <a href="#"  wire:click.prevent="ajouter()" class="btn btn-outline-success btn-sm"  title="Cliquez pour ajouter" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ri-check-line"></i> Ajouter</a>
    </div>
</td> 
   