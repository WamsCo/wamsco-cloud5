<div wire:ignore.self class="modal fade" id="createPaieDiversModal" tabindex="-1" data-bs-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
        <div class="modal-header py-2" style="background-color: #56585d; color:#ffffff;">
            <h5 class="modal-title" id="exampleModalCenteredScrollableTitle"><i class="fa fa-plus-circle"></i> Nouveau produit <span class="text-vert"></span> </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body py-0 px-0">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="libele_paiement" class="col-md-5 col-sm-3 fw-semibold col-form-label">Paiement divers</label>
                        <div class="row mb-1">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="row mb-1">
                                    {{-- <label for="libele_paiement" class="col-sm-2 fw-bold col-form-label">Libellé</label> --}}
                                    <div class="col-lg-10 col-md-10 col-sm-10">
                                        <input type="text" wire:model="libele_paiement" placeholder="Ex: Achat ordinateur" style=" color: #393b83;" class="form-control fw-semibold bordure fs-3 px-0 w-100 @error('libele_paiement') is-invalid @enderror" id="libele_paiement">
                                    </div>
                                    <div class="d-flex justify-content-start">
                                        @error('libele_paiement') <span class="text-danger">{{ $message }}</span> @enderror 
                                    </div>
                                </div>                                                    
                            </div>
                            <div class="d-flex justify-content-start">
                                @error('client') <span class="text-danger">{{$message}}</span> @enderror 
                            </div>
                        </div>                                            
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12"> 
                        <div class="row mb-1">
                            <label for="reference" class="col-lg-3 col-md-3 col-sm-4 fw-bold col-form-label">Réference</label>
                            <div class="col-lg-9 col-md-9 col-sm-8">
                                <input type="text" wire:model="reference" placeholder="Ex: PD00-01" class="form-control bordure w-75 @error('reference') is-invalid @enderror" id="reference">
                            </div>
                            <div class="d-flex justify-content-start">
                                @error('reference') <span class="text-danger">{{ $message }}</span> @enderror 
                            </div>
                        </div>                                           
                        <div class="row mb-1">
                            <label for="date_paiement" class="col-lg-5 col-md-5 col-sm-4 fw-bold col-form-label">Date paiement</label>
                            <div class="col-lg-7 col-md-7 col-sm-8">
                                <input type="date" wire:model="date_paiement" class="form-control bordure w-50 @error('date_paiement') is-invalid @enderror" id="date_paiement">
                            </div>
                            <div class="d-flex justify-content-start">
                                @error('date_paiement') <span class="text-danger">{{ $message }}</span> @enderror 
                            </div>
                        </div>
                        <div class="row mb-1">
                            <label for="date_valeur" class="col-lg-5 col-md-5 col-sm-4 fw-bold col-form-label">Date valeur</label>
                            <div class="col-lg-7 col-md-7 col-sm-8">
                                <input type="date" wire:model="date_valeur"  class="form-control bordure w-50 @error('date_valeur') is-invalid @enderror" id="date_valeur">
                            </div>
                            <div class="d-flex justify-content-start">
                                @error('date_valeur') <span class="text-danger">{{ $message }}</span> @enderror 
                            </div>
                        </div>  
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="row mb-1">
                            <label for="nom_compte_bancaire" class="col-lg-5 col-md-5 col-sm-4 fw-bold col-form-label">Compte bancaire</label>
                            <div class="col-lg-7 col-md-7 col-sm-8">                                                        
                                <select id="nom_compte_bancaire" wire:model="nom_compte_bancaire" class="form-control form-select bordure w-75 @error('nom_compte_bancaire') is-invalid @enderror" id="nom_compte_bancaire">
                                    <option value=""></option>	
                                    @foreach($compteBancaire as $compteBancaires)
                                        <option value="{{$compteBancaires->id}}">{{$compteBancaires->nom_compte_bancaire}}</option>	
                                    @endforeach
                                </select> 
                            </div>
                            <div class="d-flex justify-content-start">
                                @error('nom_compte_bancaire') <span class="text-danger">{{ $message }}</span> @enderror 
                            </div>
                        </div>
                        <div class="row mb-1">
                            <label for="mode_reglement" class="col-lg-5 col-md-5 col-sm-4 fw-bold col-form-label">Mode de règlement</label>
                            <div class="col-lg-7 col-md-7 col-sm-8">                                                        
                                <select id="mode_reglement" wire:model="mode_reglement" class="form-control form-select bordure w-100 @error('mode_reglement') is-invalid @enderror" id="mode_reglement">
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
                            <label for="sens" class="col-lg-3 col-md-3 col-sm-3 fw-bold col-form-label">Sens <i class="fas fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" style="vertical-align: middle; cursor: help" title="{{$infos}}"></i></label>
                            <div class="col-lg-9 col-md-9 col-sm-9">                                                        
                                <select id="sens" wire:model="sens" class="form-control fw-semibold form-select text-danger bordure w-50 @error('sens') is-invalid @enderror">
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
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="row mb-1">
                            <label for="montant" class="col-lg-3 col-md-3 col-sm-4 fw-bold col-form-label">Montant <span class="fw-semibold text-bleu" style="font-size: 10px">{{$this->devise}}</span></label>
                            <div class="col-lg-9 col-md-9 col-sm-8">
                                <input type="text" wire:model="montant" placeholder="Ex: 10000" style=" color: #393b83;font-size: 13px;" class="form-control fw-semibold bordure w-25 @error('montant') is-invalid @enderror" id="montant">
                            </div>
                            <div class="d-flex justify-content-start">
                                @error('montant') <span class="text-danger">{{ $message }}</span> @enderror 
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="row mb-1">
                            <label for="note" class="col-sm-2 col-form-label" style="font-weight: 400;">Note</label>
                            <div class="col-sm-10">                                                        
                                <textarea rows="2" wire:model="note" class="form-control bordure @error('note') is-invalid @enderror" id="note" placeholder="Ajouter une note..."></textarea>
                            </div>
                            <div class="d-flex justify-content-start">
                                @error('note') <span class="text-danger">{{ $message }}</span> @enderror 
                            </div>
                        </div>                                            
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive mt-2"> 
                            <ul class="nav nav-tabs border-0" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#cheque">Chèque </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#autre">Autre</a>
                                </li>
                            </ul>
                            <div class="tab-content table-responsive border-topk rounded-0" style="border-top-style: dashed; border-top-width: 1px;border-top-color: #0e04043d;">
                                <div id="cheque" class="tab-pane">                               
                                        <div class="row mt-2">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="w_horizontal_separator mt-3 mb-3 text-bleu text-uppercase fw-bold small w-25">Autre Information</div>
                                            <div class="row mb-1">
                                                <label for="numero_cheque_virement" class="col-lg-5 col-md-5 col-sm-5 col-form-label">Numéro (Chèque/Virement N°)</label>
                                                <div class="col-lg-7 col-md-7 col-sm-7">
                                                    <input type="text" wire:model="numero_cheque_virement" placeholder="Ex: A5644" class="form-control bordure w-75 @error('numero_cheque_virement') is-invalid @enderror" id="numero_cheque_virement">
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('numero_cheque_virement') <span class="text-danger">{{ $message }}</span> @enderror 
                                                </div>
                                            </div>
                                            <div class="row mb-1">
                                                <label for="emetteur" class="col-lg-5 col-md-5 col-sm-5 col-form-label">Émetteur (Émetteur du chèque/virement)</label>
                                                <div class="col-lg-7 col-md-7 col-sm-7">
                                                    <input type="text" wire:model="emetteur" placeholder="Ex: John Doe" class="form-control bordure w-75 @error('emetteur') is-invalid @enderror" id="emetteur">
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('emetteur') <span class="text-danger">{{ $message }}</span> @enderror 
                                                </div>
                                            </div>
                                            <div class="row mb-1">
                                                <label for="nom_banque" class="col-lg-5 col-md-5 col-sm-4 col-form-label">Banque (Banque du chèque)</label>
                                                <div class="col-lg-7 col-md-7 col-sm-8">
                                                    <input type="text" wire:model="nom_banque" placeholder="Nom de la banque" class="form-control bordure w-75 @error('nom_banque') is-invalid @enderror" id="nom_banque">
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('nom_banque') <span class="text-danger">{{ $message }}</span> @enderror 
                                                </div>
                                            </div>                                                                                                                       
                                        </div>
                                        </div>                                           
                                </div>
                                <div id="autre" class="tab-pane active">              
                                </div>
                            </div>                    
                        </div>
                    </div>                        
                </div>
            </div>
        </div>
        <div class="modal-footer py-1" style="background-color: #56585d; color:#ffffff;">
            <button type="submit" wire:click.prevent="store()" class="btn btn-sm btn-secondary fw-semibold"><i class="fas fa-save"></i> Enregistrer</button>
            <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal"><i class="fa fa-close"></i> Fermer</button>
        </div>
    </div>
    </div>
</div>