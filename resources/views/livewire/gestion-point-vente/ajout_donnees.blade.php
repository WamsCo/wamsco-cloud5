<td colspan="9">
    <div class="row">
        <div class="col-md-4">
            <label for="prix_vente_client" class="col-md-12 fw-semibold">Prix</label>            
                <input type="text" name="prix_vente_client" wire:model="prix_vente_client" placeholder="Ex 1000" @if($offrir == "Oui") readonly @endif class="form-control bordure fw-bold @error('prix_vente_client') is-invalid @enderror"/>
                @error('prix_vente_client')<div class="text-danger invalid-feedback">{{ $message }}</div>@enderror  
        </div>
        <div class="col-md-4">
            <label for="quantite_ajoute" class="col-md-12 fw-semibold">Quantité</label>
            <input type="number" min="0" name="quantite_ajoute" wire:model="quantite_ajoute" placeholder="Ex: 12" class="form-control bordure fw-bold @error('quantite_ajoute') is-invalid @enderror"/>
            @error('quantite_ajoute')<div class="text-danger invalid-feedback">{{ $message }}</div>@enderror      
        </div>
        <div class="col-md-4">
            <label for="remise" class="col-md-12 fw-semibold">Remise en % (Ex: 2.5)</label>
            <input type="number" min="0" name="remise" wire:model="remise" placeholder="Ex: 2.5" class="form-control bordure fw-bold @error('remise') is-invalid @enderror"/>
            @error('remise')<div class="text-danger invalid-feedback">{{ $message }}</div>@enderror      
        </div>
        <div class="col-md-4">
            <label for="tva" class="col-md-12 fw-semibold">Tva en %</label>
            <select name="tva" wire:model="tva" class="form-control form-select bordure fw-bold @error('tva') is-invalid @enderror" style="color:rgb(114, 114, 114); font-weight: 600;">
                <option value="0">0%</option>
                @foreach($devise_tva as $devise_tvas)
                    @if($devise_tvas->taxe == "Tva")
                        <option value="{{$devise_tvas->taux_tva}}">{{$devise_tvas->taux_tva}}% ({{substr($devise_tvas->taxe,0,15)}})</option>
                    @endif
                @endforeach 												
            </select>
            @error('tva')<div class="text-danger invalid-feedback">{{ $message }}</div>@enderror      
        </div>
        <div class="col-md-4">
            <label for="precompte" class="col-md-12 fw-semibold">Précompte en %</label>
            <select name="precompte" wire:model="precompte" class="form-control form-select bordure fw-bold @error('precompte') is-invalid @enderror" style="color:rgb(114, 114, 114); font-weight: 600;">
                <option value="0">0%</option>
                @foreach($devise_tva as $devise_tvas)
                    @if($devise_tvas->taxe == "Précompte")
                        <option value="{{$devise_tvas->taux_tva}}">{{$devise_tvas->taux_tva}}% ({{substr($devise_tvas->taxe,0,15)}})</option>
                    @endif
                @endforeach 												
            </select>
            @error('precompte')<div class="text-danger invalid-feedback">{{ $message }}</div>@enderror      
        </div>
        <div class="col-md-4">
            <label for="offrir" class="col-md-12 fw-semibold">Offrir ?</label>
            <select name="offrir" wire:model="offrir" class="form-control form-select bordure fw-bold @error('offrir') is-invalid @enderror" style="color: rgb(40, 167, 69); font-weight: 600;">
                <option value="Non">Non</option>
                <option value="Oui">Oui</option>                												
            </select>
            @error('offrir')<div class="text-danger invalid-feedback">{{ $message }}</div>@enderror      
        </div>
        <div class="col-md-12">
            <div class="input-group mt-3">
                <textarea type="text" wire:model="infos" class="form-control bordure @error('infos') is-invalid @enderror" id="floatingInput" placeholder="Note"></textarea>
                @error('infos')<div class="text-danger invalid-feedback">{{ $message }}</div>@enderror
            </div>	 
        </div>     
        <div class="profile-header-info">
            <p class="mb-2"></p>
            <a href="#" class="btn btn-sm btn-secondary" title="Cliquez pour enregistrer" data-toggle="tooltip" wire:click.prevent="ajouterdata()"><i class="fa fa-check"></i> Valider</a>
            <a wire:click="onDataAjoutUpdated()" class="btn btn-sm btn-red" title="Cliquez pour annuler"><i class="fa fa-close"></i></a>
        </div> 
    </div>
</td>
    
   