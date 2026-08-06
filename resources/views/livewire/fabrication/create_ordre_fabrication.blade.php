<div wire:ignore.self class="modal fade" id="createOrdreModal" tabindex="-1" data-bs-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
        <div class="modal-header py-2" style="background-color: #56585d; color:#ffffff;">
            <h5 class="modal-title" id="exampleModalCenteredScrollableTitle"><i class="fa fa-plus-circle"></i> Nouvelle Ordre de fabrication <span class="text-vert"></span> </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body py-0 px-0">
            <div class="container-fluid">
                <div class="row pt-1 px-2 pb-0">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="libelle" class="col-md-5 col-sm-3 fw-bold col-form-label">Nomenclature</label>
                        <div class="row mb-2">
                            <div class="col-lg-10 col-md-10 col-sm-10">
                                <select id="nomenclatures" wire:model.live="nomenclatures" class="form-control form-select fw-bold bordure w-75 @error('nomenclatures') is-invalid @enderror">
                                    <option value=""></option>	
                                    @foreach($listeNomenclatur as $listeNomenclaturs)
                                        <option value="{{$listeNomenclaturs->id}}">{{$listeNomenclaturs->code}} » {{Str::limit($listeNomenclaturs->libelle, 50)}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="d-flex justify-content-start">
                                @error('nomenclatures') <span class="text-danger">{{ $message }}</span> @enderror 
                            </div>
                        </div>                                            
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">                                            
                        <div class="row mb-1">
                            <label for="inputPassword3" class="col-sm-3 fw-bold col-form-label">Type</label>
                            <div class="col-sm-9">
                                <div class="card-body">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" wire:model.live="type_nomencla" value="Fabrication" name="inlineRadioOptions" id="inlineRadio1" value="option1">
                                        <label class="form-check-label" for="inlineRadio1">Fabrication</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" wire:model.live="type_nomencla" value="Déassemblage" name="inlineRadioOptions" id="inlineRadio2" value="option2" checked="">
                                        <label class="form-check-label" for="inlineRadio2">Déassemblage</label>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-start">
                                    @error('type_nomencla') <span class="text-danger">{{ $message }}</span> @enderror 
                                </div>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <label for="produit_a_fabrique" class="col-sm-3 col-form-label">Produit à fabriquer</label>
                            <div class="col-sm-9"> 
                                <label class="form-check-label fw-bold text-vert pt-2" for="produit_a_fabrique">{{$produit_a_fabrique}}</label>
                            </div>
                            <div class="d-flex justify-content-start">
                                @error('produit_a_fabrique') <span class="text-danger">{{ $message }}</span> @enderror 
                            </div>
                        </div> 
                        <div class="row mb-1">
                            <label for="libelle" class="col-sm-3 col-form-label">Libellé</label>
                            <div class="col-sm-9">
                                <input type="text" wire:model.defer="libelle" placeholder="Ex: Fabrication pain" class="form-control bordure w-50 @error('libelle') is-invalid @enderror" id="libelle">
                            </div>
                            <div class="d-flex justify-content-start">
                                @error('libelle') <span class="text-danger">{{ $message }}</span> @enderror 
                            </div>
                        </div>
                        <div class="row mb-1">
                            <label for="quantite" class="col-sm-3 fw-bold col-form-label">Quantité à fabriquer</label>
                            <div class="col-sm-4">
                                <input type="number" wire:model.defer="quantite" placeholder="Ex: 5" min="1" class="form-control bordure w-50 @error('quantite') is-invalid @enderror" id="quantite">
                            </div>
                            <div class="col-sm-4">                                                        
                                <select id="unite_mesure" wire:model.defer ="unite_mesure" class="form-control form-select bordure w-50 @error('unite_mesure') is-invalid @enderror">
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
                                @error('quantite') <span class="text-danger">{{ $message }}</span> @enderror 
                            </div>
                        </div>
                        <div class="row mb-1">
                            <label for="duree" class="col-sm-3 fw-bold col-form-label">Durée estimée (H:min)</label>
                            <div class="col-sm-9">
                                <input type="time" wire:model.defer="duree" min="1" class="form-control bordure w-auto @error('duree') is-invalid @enderror" id="duree">
                            </div>
                            <div class="d-flex justify-content-start">
                                @error('duree') <span class="text-danger">{{ $message }}</span> @enderror 
                            </div>
                        </div>
                        <div class="row mb-1">
                            <label for="entrepot_fabrication" class="col-sm-3 fw-bold col-form-label">Entrepôt de fabrication</label>
                            <div class="col-sm-9">   
                                <select id="entrepot_fabrication" wire:model.defer="entrepot_fabrication" class="form-control form-select bordure w-auto @error('entrepot_fabrication') is-invalid @enderror">
                                    <option value=""></option>	
                                    @foreach($entrepot as $entrepots)
                                        <option value="{{$entrepots->id}}">{{Str::limit($entrepots->nom, 52)}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="d-flex justify-content-start">
                                @error('entrepot_fabrication') <span class="text-danger">{{ $message }}</span> @enderror 
                            </div>
                        </div> 
                        <div class="row mb-1">
                            <label for="date_entree" class="col-sm-3 fw-bold col-form-label">Date début effectif</label>
                            <div class="col-sm-9">
                                <input type="datetime-local" wire:model.defer="date_entree" class="form-control bordure w-auto @error('date_entree') is-invalid @enderror" id="date_entree">
                            </div>
                            <div class="d-flex justify-content-start">
                                @error('date_entree') <span class="text-danger">{{ $message }}</span> @enderror 
                            </div>
                        </div>
                        <div class="row mb-1">
                            <label for="date_sortie" class="col-sm-3 fw-bold col-form-label">Date de fin effectif</label>
                            <div class="col-sm-9">
                                <input type="datetime-local" wire:model.defer="date_sortie" class="form-control bordure w-auto @error('date_sortie') is-invalid @enderror" id="date_sortie">
                            </div>
                            <div class="d-flex justify-content-start">
                                @error('date_sortie') <span class="text-danger">{{ $message }}</span> @enderror 
                            </div>
                        </div>
                        <div class="row mb-1">
                            <label for="tiers" class="col-sm-3 col-form-label">Tiers</label>
                            <div class="col-sm-9">   
                                <select id="tiers" wire:model.defer="tiers" class="form-control form-select bordure w-auto @error('tiers') is-invalid @enderror">
                                    <option value=""></option>	
                                    @foreach($tier as $tiers)
                                        <option value="{{$tiers->id}}">{{Str::limit($tiers->nom, 52)}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="d-flex justify-content-start">
                                @error('tiers') <span class="text-danger">{{ $message }}</span> @enderror 
                            </div>
                        </div> 
                        <div class="row mb-1">
                            <label for="responsable" class="col-sm-3 col-form-label">Responsable</label>
                            <div class="col-sm-9">   
                                <select id="responsable" wire:model.defer="responsable" class="form-control form-select bordure w-auto @error('responsable') is-invalid @enderror">
                                    <option value=""></option>	
                                    @foreach($utilisateur as $utilisateurs)
                                        <option value="{{$utilisateurs->id}}">{{Str::limit($utilisateurs->name, 52)}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="d-flex justify-content-start">
                                @error('responsable') <span class="text-danger">{{ $message }}</span> @enderror 
                            </div>
                        </div>                                                                               
                    </div>                                        
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="row mb-1 mt-2">
                            <label for="note" class="col-sm-2 col-form-label" style="font-weight: 400;">Note</label>
                            <div class="col-sm-10">                                                        
                                <textarea rows="2" wire:model="note" class="form-control bordure @error('note') is-invalid @enderror" id="note" placeholder="Ajouter une note..."></textarea>
                            </div>
                            <div class="d-flex justify-content-start">
                                @error('note') <span class="text-danger">{{ $message }}</span> @enderror 
                            </div>
                        </div>                                            
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 px-0">
                        @if($this->nomenclatures > 0)
                            <div class="table-responsive mt-2">
                                <div class="table-responsive mt-1"> 
                                    <ul class="nav nav-tabs border-0" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#composants">Composants (<span class="text-vert">{{$ComposantCount}}</span>)</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content table-responsive px-0" style="border-top-style: dashed; border-top-width: 1px;border-top-color: #0e04043d;">
                                        <div id="composants" class="tab-pane active">                               
                                            <table class="table table-striped text-nowrap m-0"> 
                                                <thead>
                                                    <tr>
                                                        <th class="fond_entete_table">Composant</th>
                                                        <th class="fond_entete_table pointer text-center" wire:click="setOrderField('id_entrepot')">Entrepôt <i class="fa fa-arrow-down-short-wide"></i></th>
                                                        <th class="fond_entete_table pointer text-center" wire:click="setOrderField('quantite')">Quantité <i class="fa fa-arrow-down-short-wide"></i></th>
                                                        <th class="fond_entete_table text-center">Consommé</th>
                                                        <th class="fond_entete_table text-center">Unité</th>
                                                        <th class="fond_entete_table text-end">Coût total <i class="fas fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" style="vertical-align: middle; cursor: help" aria-label="Le coût de production de cette nomenclature basé sur chaque quantité et produit à consommer (le Prix moyen pondéré (PMP) : prix d'achat)" data-bs-original-title="Le coût de production de cette nomenclature basé sur chaque quantité et produit à consommer (le Prix moyen pondéré (PMP) : prix d'achat)"></i></th>
                                                        <th class="fond_entete_table pointer"></th>
                                                        <th class="fond_entete_table pointer"></th>
                                                        <th class="fond_entete_table pointer"></th>
                                                    </tr>
                                                </thead>                                       
                                                <tbody>
                                                    @foreach($listeComposant as $listeComposants)
                                                        <tr> 
                                                            <td class="fw-bold"><a href="detail_product?id={{$listeComposants->composant_id}}&active=4&champ=1-1&choix=2" wire:navigate class="text-bleu fw-bold">{{$listeComposants->composant}}</a></td>                                                         
                                                            <td class="text-center fw-bold"><a href="detail_entrepot?id={{$listeComposants->id_entrepot}}&active=4&champ=3-1&choix=2" wire:navigate class="text-bleu fw-bold">{{$listeComposants->nom_entrepot}}</a></td> 
                                                            <td class="text-center fw-bold">{{$listeComposants->quantite}}</td> 
                                                            <td class="text-center fw-bold">{{$listeComposants->quantite_consommer}}</td> 
                                                            <td class="text-center fw-bold">{{$listeComposants->unite}}</td> 
                                                            <td class="fw-bold text-end">{{$listeComposants->cout}}</td>
                                                            <td class=""></td>
                                                            <td class=""></td>
                                                            <td class="text-end fw-semibold">                                                                    
                                                                @if($confirmer === $listeComposants->id)                                                               
                                                                    <a wire:click.prevent="supprimer({{$listeComposants->id}})" class="btn btn-outline-danger btn-xs bg-danger text-white blink" style="font-size: 8px;" title="Cliquez pour confirmer la suppression" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-trash"> ?</i></a>
                                                                @else
                                                                    <a wire:click.prevent="confirmerDelete({{$listeComposants->id}})" class="btn-outline-muted btn-xs"  title="Cliquez pour supprimer" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-trash pointer"></i></a>
                                                                @endif
                                                            </td>  
                                                        </tr> 
                                                    @endforeach 
                                                    @if($ouvre === 1) 
                                                        <tr>                                                         
                                                            @include('livewire.fabrication.ajout_ligne_composant_ordreFab')     
                                                        </tr>
                                                    @endif
                                                    <tr style="font-size: 12px;">
                                                        <td colspan="9">
                                                            @if($this->nomenclatures > 0)
                                                                <a href="#" class="btn btn-sm btn-white text-green-100" wire:click.prevent="ajoutLigne(1)" title="Cliquez pour ajouter" data-toggle="tooltip"> <i class="fa fa-plus"></i> Ajouter une ligne</a>
                                                                <a wire:click="onDataAjout()" class="btn btn-sm btn-white" title="Cliquez pour annuler"><i class="fa fa-close text-danger"></i></a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>                                                            
                                        </div>
                                    </div>                    
                                </div>
                            </div>
                        @endif
                    </div>                                        
                </div>
            </div>
        </div>
        <div class="modal-footer py-1" style="background-color: #56585d; color:#ffffff;">
            <button type="submit" wire:click.prevent="creation()" class="btn btn-sm btn-secondary fw-semibold"><i class="fas fa-save"></i> Créer</button>
            <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal"><i class="fa fa-close"></i> Fermer</button>
        </div>
    </div>
    </div>
</div>