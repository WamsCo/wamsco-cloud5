<div wire:ignore.self class="modal fade" id="createTicketModal" tabindex="-1" data-bs-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
        <div class="modal-header py-2" style="background-color: #56585d; color:#ffffff;">
            <h5 class="modal-title" id="exampleModalCenteredScrollableTitle"><i class="fa fa-plus-circle"></i> Nouveau ticket <span class="text-vert"></span> </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body py-0 px-0">
            <div class="container-fluid">
                <div class="row pt-1 px-2 pb-2">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="nom_ticket" class="col-md-5 col-sm-3 fw-semibold col-form-label">Nouveau ticket</label>
                        <div class="row mb-1">
                            {{-- <label for="nom_ticket" class="col-lg-2 col-md-5 col-sm-3 fw-bold fs-5 col-form-label">Opportunité</label> --}}
                            <div class="col-lg-10 col-md-10 col-sm-10">
                                <input type="text" wire:model="nom_ticket" placeholder="par ex. Problème de facturation" class="form-control bordure fs-3 w-100 px-0 @error('nom_ticket') is-invalid @enderror" id="nom_ticket">
                            </div>
                            <div class="d-flex justify-content-start">
                                @error('nom_ticket') <span class="text-danger">{{$message}}</span> @enderror 
                            </div>
                        </div>                                            
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">                                                                                   
                        <div class="row mb-1">
                            <label for="reference" class="col-lg-4 col-md-5 col-sm-3 fw-bold col-form-label">Référence</label>
                            <div class="col-lg-8 col-md-7 col-sm-9">
                                <input type="text" wire:model="reference" placeholder="Ex: TK-001" class="form-control bordure w-100 @error('reference') is-invalid @enderror" id="reference">
                            </div>
                            <div class="d-flex justify-content-start">
                                @error('reference') <span class="text-danger">{{ $message }}</span> @enderror 
                            </div>
                        </div>
                        <div class="row mb-1">
                            <label for="type_demande" class="col-lg-4 col-md-5 col-sm-3 fw-bold col-form-label">Type demande</label>
                            <div class="col-lg-8 col-md-7 col-sm-9">                                                        
                                <select id="type_demande" wire:model="type_demande" class="form-control form-select bordure w-100 @error('type_demande') is-invalid @enderror">
                                    <option value=""></option>
                                    <option value="Support Technique">Support Technique</option>   
                                    <option value="Question ou bug">Question ou bug</option>	
                                    <option value="Formation">Formation</option>
                                    <option value="Question commerciale">Question commerciale</option>
                                    <option value="Demande d'aide fonctionnelle">Demande d'aide fonctionnelle</option>
                                    <option value="Demande de changement ou d'amélioration">Demande de changement ou d'amélioration</option>	
                                </select> 
                            </div>
                            <div class="d-flex justify-content-start">
                                @error('type_demande') <span class="text-danger">{{ $message }}</span> @enderror 
                            </div>
                        </div>
                        <div class="row mb-1">
                            <label for="assignation" class="col-lg-4 col-md-5 col-sm-3 fw-bold col-form-label">Assigné à</label>
                            <div class="col-lg-8 col-md-7 col-sm-9">                                                        
                                <select id="assignation" wire:model="assignation" class="form-control form-select bordure w-100 @error('assignation') is-invalid @enderror">
                                    <option value=""></option>    
                                    @foreach($listUser as $listUsers)
                                        <option value="{{$listUsers->id}}">{{$listUsers->name}}</option>
                                    @endforeach	
                                </select> 
                            </div>
                            <div class="d-flex justify-content-start">
                                @error('assignation') <span class="text-danger">{{ $message }}</span> @enderror 
                            </div>
                        </div>
                        <div class="row mb-1">
                            <label for="priorite" class="col-lg-4 col-md-5 col-sm-3 fw-bold col-form-label">Priorité</label>
                            <div class="col-lg-8 col-md-7 col-sm-9">                                                        
                                <select id="priorite" wire:model="priorite" class="form-control form-select bordure w-50 @error('priorite') is-invalid @enderror">
                                    <option value=""></option>   
                                    <option value="Faible">Faible</option>   
                                    <option value="Moyen">Moyen</option> 
                                    <option value="Élevé">Élevé</option> 
                                    <option value="Urgent">Urgent</option> 
                                </select> 
                            </div>
                            <div class="d-flex justify-content-start">
                                @error('priorite') <span class="text-danger">{{ $message }}</span> @enderror 
                            </div>
                        </div>
                        @if(auth()->user()->societe == "Administration")
                            <div class="row mb-1">
                                <label for="societe" class="col-lg-4 col-md-5 col-sm-3 fw-bold col-form-label">Entité</label>
                                <div class="col-lg-8 col-md-7 col-sm-9">                                                        
                                    <select id="societe" wire:model="societe" class="form-control form-select bordure w-75 @error('societe') is-invalid @enderror">
                                        <option value=""></option> 
                                        @foreach($liste_entite as $liste_entites)
                                            <option value="{{$liste_entites->enseigne}}">{{$liste_entites->enseigne}}</option>
                                        @endforeach
                                    </select> 
                                </div>
                                <div class="d-flex justify-content-start">
                                    @error('societe') <span class="text-danger">{{ $message }}</span> @enderror 
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">                                               
                        <div class="row mb-1">
                            <label for="nom_user" class="col-lg-3 col-md-3 col-sm-4 fw-semibold col-form-label">Créé par</label>
                            <div class="col-lg-9 col-md-9 col-sm-8">                                                        
                                <select id="nom_user" wire:model="nom_user" class="form-control form-select bordure w-75 @error('nom_user') is-invalid @enderror">
                                    <option value="{{$this->nom_user}}">{{$this->nom_user}}</option>
                                </select> 
                            </div>
                            <div class="d-flex justify-content-start">
                                @error('nom_user') <span class="text-danger">{{ $message }}</span> @enderror 
                            </div>
                        </div>
                        <div class="row mb-1">
                            <label for="telephone_user" class="col-lg-4 col-md-4 col-sm-3 fw-semibold col-form-label">Téléphone</label>
                            <div class="col-lg-8 col-md-8 col-sm-9">
                                <input type="text" wire:model="telephone_user" placeholder="" class="form-control bordure w-100 @error('telephone_user') is-invalid @enderror" id="telephone_user">
                            </div>
                            <div class="d-flex justify-content-start">
                                @error('telephone_user') <span class="text-danger">{{ $message }}</span> @enderror 
                            </div>
                        </div>                                                                                                                             
                    </div>                                       
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 px-0">
                        <div class="table-responsive mt-2">
                            <div class="table-responsive mt-1"> 
                                <ul class="nav nav-tabs border-0" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#home">Description</a>
                                    </li>
                                    {{-- <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#autre">Autres</a>
                                    </li> --}}
                                </ul>
                                <div class="tab-content table-responsive border-topk px-3" style="border-top-style: dashed; border-top-width: 1px;border-top-color: #0e04043d;">
                                    <div id="home" class="tab-pane active">
                                        <div class="row mt-2">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="row mb-1">
                                                    {{-- <label for="description" class="col-lg-2 col-md-3 col-sm-4 col-form-label">Description</label> --}}
                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                        <textarea rows="5" id="description" wire:model.defer="description" class="form-control bordure px-0 w-100 @error('description') is-invalid @enderror" placeholder="Ajouter des détails sur ce ticket..."></textarea>
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('description') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>                                                                  
                                            </div>                                                                                                                                        
                                        </div>                                                                
                                    </div>
                                    {{-- <div id="autre" class="tab-pane">                                                                        
                                    </div> --}}
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