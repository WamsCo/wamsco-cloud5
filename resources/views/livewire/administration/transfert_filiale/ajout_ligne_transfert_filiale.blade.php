    
<td class="px-3">  
    <div class="row mt-3">  
        <div class="col-sm-12">                                                        
            <select id="choix_produit" wire:model.live="choix_produit" class="form-control form-select fw-semibold bordure @error('choix_produit') is-invalid @enderror">
                <option value=""></option>	
                @foreach($produit_stock as $produit_stocks)
                    <option value="{{$produit_stocks->id}}">{{substr($produit_stocks->nom_produit,0,32) > substr($produit_stocks->nom_produit,0,31) ? substr($produit_stocks->nom_produit,0,32).'...': $produit_stocks->nom_produit}} [{{$produit_stocks->reference}}] - Stocks » {{$produit_stocks->quantite}}</option>
                @endforeach	
            </select> 
        </div>
        <div class="d-flex justify-content-start">
            @error('choix_produit') <span class="text-danger">{{$message}}</span> @enderror 
        </div>   
    </div>  
</td>
<td class="px-3">  
    <div class="row mt-3">  
        <div class="col-sm-12">                                                        
            <select id="choix_produit_desti" wire:model.live="choix_produit_desti" class="form-control form-select fw-semibold bordure @error('choix_produit_desti') is-invalid @enderror">
                <option value=""></option>	
                @foreach($produit_stock_desti as $produit_stock_destis)
                    <option value="{{$produit_stock_destis->id}}">{{substr($produit_stock_destis->nom_produit,0,32) > substr($produit_stock_destis->nom_produit,0,31) ? substr($produit_stock_destis->nom_produit,0,32).'...': $produit_stock_destis->nom_produit}} [{{$produit_stock_destis->reference}}] - Stocks » {{$produit_stock_destis->quantite}}</option>
                @endforeach	
            </select> 
        </div>
        <div class="d-flex justify-content-start">
            @error('choix_produit_desti') <span class="text-danger">{{ $message }}</span> @enderror 
        </div>   
    </div>  
</td>
<td class="text-muted text-bleu fw-semibold">
    <div class="row mt-3">
        <div class="col-sm-12">                                                        
            <textarea rows="1" wire:model="message" class="form-control bordure @error('message') is-invalid @enderror" id="message" placeholder="Message..."></textarea>
        </div>
        <div class="d-flex justify-content-start">
            @error('message') <span class="text-danger">{{ $message }}</span> @enderror 
        </div>
    </div>
</td>                                                         
<td class="text-center" style="width: 150px;">
    <div class="row mt-3">
        <div class="col-sm-12">
            <input type="number" wire:model="quantite" placeholder="Ex: 5" class="form-control bordure @error('quantite') is-invalid @enderror" id="quantite">
        </div>
        <div class="d-flex justify-content-start">
            @error('quantite') <span class="text-danger">{{ $message }}</span> @enderror 
        </div>
    </div>
</td> 
{{-- <td class="text-center">
    <div class="row mt-3">
        <label class="fw-bold col-form-label">{{$quantiteEntrepotOrigine}}</label>
    </div>
</td>  --}}
{{-- <td class="text-center">
    <div class="row mt-3">
        <label for="nombre_paquets" class="fw-bold col-form-label">{{$quantiteEntrepotDestinataire}}</label>
    </div>
</td>             --}}
<td colspan="2" class="text-end fw-semibold">
    <div class="mt-3">
        <a href="#"  wire:click.prevent="ajouter()" class="btn btn-outline-success btn-sm"  title="Cliquez pour ajouter" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ri-check-line"></i> Ajouter</a>
    </div>
</td> 
   