<div>
    <div class="mod-content">
        <div class="row gx-4">
            <div class="col-sm-12">
                <div class="card mb-4 mt-2 bg-2k">
                    <div class="card-header border-bottom">
                        <h5 class="card-title text-danger pt-2">Modification {{$this->nom_compte_bancaire }} <span class="text-bleu">» {{$this->libele_paiement}}</span></h5>
                    </div>
                    <div class="card-body">
                        <div class="table-outerk">  
                            <form>
                                <div class="row">
                                    <div class="col-sm-8">
                                        <div class="row mb-1">
                                            <label for="reference" class="col-sm-3 fw-bold col-form-label">Réference</label>
                                            <div class="col-sm-9">
                                                <span class="form-control sans_bordure fw-bold fs-6 text-vert">{{$this->reference}}</span>
                                                {{-- <input type="text" wire:model="reference" placeholder="Ex: PD00-01" readonly class="form-control sans_bordure w-50 @error('reference') is-invalid @enderror" id="reference"> --}}
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                @error('reference') <span class="text-danger">{{ $message }}</span> @enderror 
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <label for="date_paiement" class="col-sm-3 fw-bold col-form-label">Date paiement</label>
                                            <div class="col-sm-9">
                                                <input type="date" wire:model="date_paiement" class="form-control bordure w-50 @error('date_paiement') is-invalid @enderror" id="date_paiement">
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                @error('date_paiement') <span class="text-danger">{{ $message }}</span> @enderror 
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <label for="date_valeur" class="col-sm-3 fw-bold col-form-label">Date valeur</label>
                                            <div class="col-sm-9">
                                                <input type="date" wire:model="date_valeur"  class="form-control bordure w-50 @error('date_valeur') is-invalid @enderror" id="date_valeur">
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                @error('date_valeur') <span class="text-danger">{{ $message }}</span> @enderror 
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <label for="libele_paiement" class="col-sm-3 fw-bold col-form-label">Libellé</label>
                                            <div class="col-sm-9">
                                                <input type="text" wire:model="libele_paiement" placeholder="Ex: Achat ordinateur" style=" color: #393b83;" class="form-control fw-semibold bordure w-50 @error('libele_paiement') is-invalid @enderror" id="libele_paiement">
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                @error('libele_paiement') <span class="text-danger">{{ $message }}</span> @enderror 
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <label for="montant" class="col-sm-3 fw-bold col-form-label">Montant</label>
                                            <div class="col-sm-9">
                                                <span class="form-control sans_bordure fw-bold fs-6 text-bleu">{{number_format($this->montant,0,',',' ')}} <span class="fw-semibold text-bleu" style="font-size: 9px">{{$this->devise}}</span></span>
                                                {{-- <input type="text" wire:model="montant" placeholder="Ex: 10000" readonly style=" color: #393b83;" class="form-control fw-semibold sans_bordure w-25 @error('montant') is-invalid @enderror" id="montant"> --}}
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                @error('montant') <span class="text-danger">{{ $message }}</span> @enderror 
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <label for="nom_compte_bancaire" class="col-sm-3 fw-bold col-form-label">Compte bancaire</label>
                                            <div class="col-sm-9">
                                                <span class="form-control sans_bordure fw-bold fs-6 text-bleu">{{$this->nom_compte_bancaire}}</span>
                                                {{-- <input type="text" wire:model="nom_compte_bancaire" placeholder="Ex: Bicec" readonly class="form-control fw-semibold sans_bordure w-25 @error('nom_compte_bancaire') is-invalid @enderror" id="nom_compte_bancaire"> --}}
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                @error('nom_compte_bancaire') <span class="text-danger">{{ $message }}</span> @enderror 
                                            </div>
                                        </div>  
                                        <div class="row mb-1">
                                            <label for="mode_reglement" class="col-sm-3 fw-bold col-form-label">Mode de règlement</label>
                                            <div class="col-sm-9">                                                        
                                                <select id="type_compte" wire:model="mode_reglement" class="form-control form-select bordure w-auto @error('mode_reglement') is-invalid @enderror" id="mode_reglement">
                                                    <option value=""></option>	
                                                    <option value="Carte bancaire">Carte bancaire</option>	
                                                    <option value="Chèque">Chèque</option>
                                                    <option value="Espèce">Espèce</option>
                                                    <option value="Ordre de prélèvement">Ordre de prélèvement</option>
                                                    <option value="Virement bancaire">Virement bancaire</option>
                                                    <option value="Versement bancaire">Versement bancaire</option>
                                                    <option value="Orange Money">Orange Money</option>
                                                    <option value="MTN Mobile Money">MTN Mobile Money</option>
                                                </select> 
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                @error('mode_reglement') <span class="text-danger">{{ $message }}</span> @enderror 
                                            </div>
                                        </div>  
                                        <div class="row mb-1">
                                            <label for="numero_cheque_virement" class="col-sm-4 col-form-label">Numéro (Chèque/Virement N°)</label>
                                            <div class="col-sm-8">
                                                <input type="text" wire:model="numero_cheque_virement" class="form-control bordure w-75 @error('numero_cheque_virement') is-invalid @enderror" id="numero_cheque_virement">
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                @error('numero_cheque_virement') <span class="text-danger">{{ $message }}</span> @enderror 
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <label for="emetteur" class="col-sm-4 col-form-label">Émetteur (Émetteur du chèque/virement)</label>
                                            <div class="col-sm-8">
                                                <input type="text" wire:model="emetteur" class="form-control bordure w-75 @error('emetteur') is-invalid @enderror" id="emetteur">
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                @error('emetteur') <span class="text-danger">{{ $message }}</span> @enderror 
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <label for="nom_banque" class="col-sm-3 col-form-label">Nom de la banque</label>
                                            <div class="col-sm-9">
                                                <input type="text" wire:model="nom_banque" class="form-control bordure w-50 @error('nom_banque') is-invalid @enderror" id="nom_banque">
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                @error('nom_banque') <span class="text-danger">{{ $message }}</span> @enderror 
                                            </div>
                                        </div>                                 
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="row mt-3">
                                            <label for="note" class="col-sm-3 col-form-label">Note</label>
                                            <div class="col-sm-9">                                                        
                                                <textarea rows="5" wire:model="note" class="form-control @error('note') is-invalid @enderror" id="note" placeholder="Message..."></textarea>
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                @error('note') <span class="text-danger">{{ $message }}</span> @enderror 
                                            </div>
                                        </div>
                                    </div>                                            
                                </div> 
                                <div class="pt-3 pb-1 border-top">
                                    <button type="submit" wire:click.prevent="update()" class="btn btn-sm btn-secondary fw-bold"><i class="fa fa-check"></i> Modifier</button>
                                    <a href="#" wire:click="onPaieUpdated()" class="btn btn-sm btn-danger"><i class="fa fa-close"></i> Fermer</a>
                                </div> 
                            </form> 
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
    </div>    
</div>
