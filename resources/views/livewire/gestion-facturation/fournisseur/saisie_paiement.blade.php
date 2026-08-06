<div wire:ignore.self class="modal fade" id="saissieReglementModal" tabindex="-1" data-bs-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
        <div class="modal-header py-2" style="background-color: #56585d; color:#ffffff;">
            <h5 class="modal-title" id="exampleModalCenteredScrollableTitle"><i class="far fa-file" style=""></i> Saisie d'un règlement reçu du fournisseur <span class="text-bleu"></span> </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div> 
        <div class="modal-body py-0 px-0">
            <div class="card-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="row mb-1">
                                <div class="col-lg-10 col-md-10 col-sm-10">
                                    <span class="form-control bordure fs-3 text-bleu w-100 px-0"><i class="fa fa-user-circle"></i> {{$this->fournisseur}}</span>
                                </div>
                                <div class="d-flex justify-content-start">
                                    @error('nom') <span class="text-danger">{{$message}}</span> @enderror 
                                </div>
                            </div>                                            
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">                                                                                   
                            <div class="row mb-1">
                                <label for="raison_sociale" class="col-lg-4 col-md-5 col-sm-3 fw-bold col-form-label">Réference</label>
                                <div class="col-lg-8 col-md-7 col-sm-9">
                                    <span class="form-control sans_bordure fw-bold fs-6 text-vert">{{$this->reference}}</span>
                                </div>
                                <div class="d-flex justify-content-start">
                                    @error('raison_sociale') <span class="text-danger">{{ $message }}</span> @enderror 
                                </div>
                            </div>
                            <div class="row mb-1">
                                <label for="date_reglement" class="col-lg-4 col-md-5 col-sm-3 fw-bold col-form-label">Date</label>
                                <div class="col-lg-8 col-md-7 col-sm-9">
                                    <input type="date" wire:model="date_reglement" class="form-control bordure w-75 @error('date_reglement') is-invalid @enderror" id="date_reglement">
                                </div>
                                <div class="d-flex justify-content-start">
                                    @error('date_reglement') <span class="text-danger">{{ $message }}</span> @enderror 
                                </div>
                            </div>                            
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="row mb-1">
                                <label for="mode_reglement" class="col-lg-4 col-md-5 col-sm-4 fw-bold col-form-label">Règlement</label>
                                <div class="col-lg-8 col-md-7 col-sm-8">                                                        
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
                                <label for="nom_produit" class="col-lg-5 col-md-5 col-sm-4 fw-bold col-form-label">Compte à créditer</label>
                                <div class="col-lg-7 col-md-7 col-sm-8">                                                        
                                    <select id="compte_bancaire" wire:model.live="compte_bancaire" class="form-control form-select bordure w-100 @error('compte_bancaire') is-invalid @enderror">
                                        <option value=""></option>
                                        @foreach($banque as $banques)
                                            <option value="{{$banques->id}}">{{$banques->nom_compte_bancaire}}</option>	
                                        @endforeach
                                    </select> 
                                </div>
                                <div class="d-flex justify-content-start">
                                    @error('compte_bancaire') <span class="text-danger">{{ $message }}</span> @enderror 
                                </div>
                            </div>                                  
                        </div>                                                               
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mt-2 border-top">
                            <div class="row">
                                <div class="col-sm-6 col-12">                                            
                                    <form>
                                        <div class="row mt-2 mb-0">
                                            <label for="quantite" class="col-sm-4 col-form-label">Montant TTC</label>                                            
                                            <div class="col-sm-8">
                                                <span class="form-control sans_bordure fw-bold fs-6 text-danger">{{number_format($montantTTC,0,',',' ')}} <span class="text-bleu" style="font-size: 09px">{{$this->devise}}</span></span>
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                @error('quantite') <span class="text-danger">{{ $message }}</span> @enderror 
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <label for="code_mouvement" class="col-sm-4 col-form-label">Reçu</label>
                                            <div class="col-sm-8">
                                                <span class="form-control sans_bordure fw-bold fs-6 text-vert">{{number_format($montant_recu,0,',',' ')}} <span class="text-bleu" style="font-size: 09px">{{$this->devise}}</span></span>
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                @error('code_mouvement') <span class="text-danger">{{ $message }}</span> @enderror 
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-sm-6 col-12">                                            
                                    <form>
                                        <div class="row mt-2 mb-0">
                                            <label for="quantite" class="col-sm-5 col-form-label">Reste à percevoir</label>                                            
                                            <div class="col-sm-7">
                                                <span class="form-control sans_bordure fw-bold fs-6 text-bleu">{{number_format($reste_a_percevoir,0,',',' ')}} <span class="text-bleu" style="font-size: 09px">{{$this->devise}}</span></span>
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                @error('quantite') <span class="text-danger">{{ $message }}</span> @enderror 
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <label for="montant_reglement" wire:click.prevent="coller()" class="col-sm-5 col-form-label pointer" title="Cliquez pour saisir le montant automatiquement" data-toggle="tooltip">Montant règlement <i class="fas fa-file-invoice-dollar text-danger" title="Cliquez pour saisir le montant automatiquement" data-toggle="tooltip"></i></label>
                                            <div class="col-sm-7">
                                                <input type="text" wire:model="montant_reglement" placeholder="" class="form-control bordure w-auto @error('montant_reglement') is-invalid @enderror" id="montant_reglement">
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                @error('montant_reglement') <span class="text-danger">{{ $message }}</span> @enderror 
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 px-0">
                            <div class="table-responsive mt-2">
                                <div class="table-responsive mt-1"> 
                                    <ul class="nav nav-tabs border-0" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#Compta">RAS</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#Cheque">Chèque</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content table-responsive border-topk px-3" style="border-top-style: dashed; border-top-width: 1px;border-top-color: #0e04043d;">
                                        <div id="Compta" class="tab-pane active">          
                                        </div>
                                        <div id="Cheque" class="tab-pane">
                                            <div class="row mt-2">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="w_horizontal_separator mt-4 mb-3 text-bleu text-uppercase fw-bolder small w-25">Autre Information</div>
                                                    <div class="row mb-1">
                                                        <label for="num_cheq_virement" class="col-lg-5 col-md-5 col-sm-5 col-form-label">Numéro (Chèque/Virement N°)</label>
                                                        <div class="col-lg-7 col-md-7 col-sm-7">
                                                            <input type="text" wire:model="num_cheq_virement" placeholder="Ex: A5644" class="form-control bordure w-100 @error('num_cheq_virement') is-invalid @enderror" id="num_cheq_virement">
                                                        </div>
                                                        <div class="d-flex justify-content-start">
                                                            @error('num_cheq_virement') <span class="text-danger">{{ $message }}</span> @enderror 
                                                        </div>
                                                    </div>
                                                    <div class="row mb-1">
                                                        <label for="emeteur" class="col-lg-5 col-md-5 col-sm-5 col-form-label">Émetteur (Émetteur du chèque/virement)</label>
                                                        <div class="col-lg-7 col-md-7 col-sm-7">
                                                            <input type="text" wire:model="emeteur" placeholder="Ex: John Doe" class="form-control bordure w-100 @error('emeteur') is-invalid @enderror" id="emeteur">
                                                        </div>
                                                        <div class="d-flex justify-content-start">
                                                            @error('emeteur') <span class="text-danger">{{ $message }}</span> @enderror 
                                                        </div>
                                                    </div>
                                                    <div class="row mb-1">
                                                        <label for="banque_cheque" class="col-lg-5 col-md-5 col-sm-4 col-form-label">Banque (Banque du chèque)</label>
                                                        <div class="col-lg-7 col-md-7 col-sm-8">
                                                            <input type="text" wire:model="banque_cheque" placeholder="Nom de la banque" class="form-control bordure w-100 @error('banque_cheque') is-invalid @enderror" id="banque_cheque">
                                                        </div>
                                                        <div class="d-flex justify-content-start">
                                                            @error('banque_cheque') <span class="text-danger">{{ $message }}</span> @enderror 
                                                        </div>
                                                    </div>
                                                    <div class="row mb-4">
                                                        <label for="commentaire" class="col-lg-4 col-md-5 col-sm-4 col-form-label">Commentaires</label>
                                                        <div class="col-lg-8 col-md-7 col-sm-8">                                                        
                                                            <textarea rows="2" wire:model="commentaire" class="form-control bordure @error('commentaire') is-invalid @enderror" id="commentaire" placeholder="Ajouter un commentaire..."></textarea>
                                                        </div>
                                                        <div class="d-flex justify-content-start">
                                                            @error('commentaire') <span class="text-danger">{{ $message }}</span> @enderror 
                                                        </div>
                                                    </div>                                                                                                                        
                                                </div>
                                            </div>                                                                
                                        </div>                                        
                                    </div>                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer py-1" style="background-color: #56585d; color:#ffffff;">
            <button type="submit" wire:click.prevent="payer()" class="btn btn-sm btn-secondary fw-bold"><i class="fa fa-check"></i> Valider</button>
            <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal"><i class="fa fa-close"></i> Fermer</button>
        </div>
    </div>
    </div>
</div>