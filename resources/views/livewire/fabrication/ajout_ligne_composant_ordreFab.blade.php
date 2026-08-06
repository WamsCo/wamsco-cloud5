    
<td class="px-3">  
    <div class="row mt-3">  
        <div class="col-sm-12">                                                        
            <select id="choix_composant" wire:model.live="choix_composant" style="width: 150px;" class="form-control form-select fw-semibold bordure @error('choix_composant') is-invalid @enderror">
                <option value=""></option>	
                @foreach($composant_produit as $composant_produits)
                    <option value="{{$composant_produits->id}}">{{substr($composant_produits->nom_produit,0,32) > substr($composant_produits->nom_produit,0,31) ? substr($composant_produits->nom_produit,0,32).'...': $composant_produits->nom_produit}} [{{$composant_produits->reference}}] </span></option>
                @endforeach	
            </select> 
        </div>
        <div class="d-flex justify-content-start">
            @error('choix_composant') <span class="text-danger">{{ $message }}</span> @enderror 
        </div>   
    </div>  
</td>
<td class="px-3">  
    <div class="row mt-3">  
        <div class="col-sm-12">                                                        
            <select id="magasin" wire:model.defer="magasin" class="form-control form-select fw-semibold bordure @error('magasin') is-invalid @enderror">
                <option value=""></option>	
                @foreach($stock as $stocks)
                    @foreach($listEntrepot as $listEntrepots)  
                        @if($stocks->id_entrepot == $listEntrepots->id)                                                                                                                                                           
                            <option value="{{$listEntrepots->id}}">{{$listEntrepots->nom}} - Stock total » {{$stocks->quantite}}</option>
                        @endif
                    @endforeach
                @endforeach                
            </select> 
        </div>
        <div class="d-flex justify-content-start">
            @error('magasin') <span class="text-danger">{{ $message }}</span> @enderror 
        </div>   
    </div>  
</td>                                                          
<td class="text-center">
    <div class="row mt-3">
        <div class="col-sm-12">
            <input type="number" wire:model="quantite_composant" placeholder="Ex: 5" min="1" class="form-control bordure @error('quantite_composant') is-invalid @enderror" id="quantite_composant">
        </div>
        <div class="d-flex justify-content-start">
            @error('quantite_composant') <span class="text-danger">{{ $message }}</span> @enderror 
        </div>
    </div>
</td>
<td class="text-center">
    <div class="row mt-3">
        <div class="col-sm-12">
            <label for="note" class="col-sm-2 col-form-label" style="font-weight: 400;">&nbsp;</label>
        </div>
    </div>
</td>
<td class="px-3">  
    <div class="row mt-3">  
        <div class="col-sm-12">                                                        
            <select id="unite" wire:model.defer ="unite" class="form-control form-select bordure w-auto @error('unite') is-invalid @enderror">
                <option value="Unité(s)">Unité(s)</option>
                <option value="Kg">Kg</option>
                <option value="g">g</option>
                <option value="Km">Km</option>
                <option value="m²">m²</option>
                <option value="m³">m³</option>
                <option value="L">L</option>
                <option value="cL">cL</option>
                <option value="mL">mL</option>
            </select> 
        </div>
        <div class="d-flex justify-content-start">
            @error('unite') <span class="text-danger">{{ $message }}</span> @enderror 
        </div>   
    </div>  
</td>
<td colspan="2" class="text-end fw-semibold">
    <div class="mt-3">
        <a href="#" wire:click.prevent="ajoutComposant()" class="btn btn-outline-success btn-sm"  title="Cliquez pour ajouter" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ri-check-line"></i> Ajouter</a>
    </div>
</td> 
<td class="text-center">
    <div class="row mt-3">
        <div class="col-sm-12">
            <label for="note" class="col-sm-2 col-form-label" style="font-weight: 400;">&nbsp;</label>
        </div>
    </div>
</td>
<td class="text-center">
    <div class="row mt-3">
        <div class="col-sm-12">
            <label for="note" class="col-sm-2 col-form-label" style="font-weight: 400;">&nbsp;</label>
        </div>
    </div>
</td>
   