<div wire:ignore.self class="modal fade" id="creerOppotuniteModal" tabindex="-1" data-bs-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">    
    <form enctype="multipart/form-data"> 
            @csrf                   
        <div class="modal-dialog modal-sm">
            <div class="modal-content">             
                <div class="modal-header py-1" style="padding-top: 0px; padding-bottom: 0px; background-color: #56585d; color:#ffffff;">                    
                    <h3 class="titre_modal_fidelite text-white"><i class="fas fa-star"></i> Opportunité</h3>                    
                </div>
                <div class="modal-body">
                    <div class="row mb-1">
                        <label for="client" class="col-md-1 col-sm-3 fw-bold col-form-label"><i class="fa fa-user"></i></label>
                        <div class="col-sm-11">    
                            <input type="search" name="client" wire:model.live="client" wire:keyup="searchResult" placeholder="Commencez à écrire... A-Z ou 0-9" class="form-control bordure w-100 @error('client') is-invalid @enderror" id="client"/>
                            <div class="bloc_search_client" style="position: absolute; left: 0;">                       
                                @if($showdiv)
                                    @if($recordCount > 0)
                                        <table class="table table-hover mb-0 text-nowrap w-75" style="overflow: auto; background:#f7f7f7;">                                                        
                                            <tbody>
                                                @if(!empty($records))                                                                
                                                    @foreach($records as $record)
                                                        <tr>                                         
                                                            <td class="pointer fw-semibold" wire:click="ajouterTier({{$record->id}})"><i class="fas fa-user-circle"></i> {{substr($record->nom,0,22) > substr($record->nom,0,21) ? substr($record->nom,0,22).'...': $record->nom}} » <span style="color: #8fbc8f; font-weight:600">{{substr($record->telephone,0,22) > substr($record->telephone,0,21) ? substr($record->telephone,0,22).'...': $record->telephone}}</span></td>   
                                                        </tr>                                        
                                                    @endforeach
                                                    <tr>                                         
                                                        <td class="pointer fw-semibold text-danger"><a href="listing-tiers?active=3&champ=3-2" target="_blank" class="text-danger"><i class="fas fa-user-plus"></i> Créer client</a></td>  
                                                    </tr> 
                                                @endif                               
                                            </tbody>                                        
                                        </table>
                                    @endif	
                                @endif	
                            </div>
                        </div>
                        <div class="d-flex justify-content-start">
                            @error('client') <span class="text-danger">{{ $message }}</span> @enderror 
                        </div>
                    </div>
                    <div class="row mb-1 mt-2">
                        <label for="nom_opportunite" class="col-sm-1 fw-bold col-form-label"><i class="fa fa-briefcase"></i></label>
                        <div class="col-sm-11">
                            <input type="text" wire:model="nom_opportunite"  placeholder="Nom de l’opportunité" class="form-control bordure w-100 @error('nom_opportunite') is-invalid @enderror" id="nom_opportunite">
                        </div>
                        <div class="d-flex justify-content-start">
                            @error('nom_opportunite') <span class="text-danger">{{ $message }}</span> @enderror 
                        </div>
                    </div> 
                    <div class="row mb-1 mt-2">
                        <label for="email_contact" class="col-sm-1 fw-bold col-form-label"><i class="fa fa-envelope"></i></label>
                        <div class="col-sm-11">
                            <input type="text" wire:model="email_contact"  placeholder="E-mail du contact" class="form-control bordure w-100 @error('email_contact') is-invalid @enderror" id="email_contact">
                        </div>
                        <div class="d-flex justify-content-start">
                            @error('email_contact') <span class="text-danger">{{ $message }}</span> @enderror 
                        </div>
                    </div>
                    <div class="row mb-1 mt-2">
                        <label for="telephone_contact" class="col-sm-1 fw-bold col-form-label"><i class="fa fa-phone"></i></label>
                        <div class="col-sm-11">
                            <input type="text" wire:model="telephone_contact"  placeholder="Téléphone du contact" class="form-control bordure w-100 @error('telephone_contact') is-invalid @enderror" id="telephone_contact">
                        </div>
                        <div class="d-flex justify-content-start">
                            @error('telephone_contact') <span class="text-danger">{{ $message }}</span> @enderror 
                        </div>
                    </div>  
                    <div class="row mb-1 mt-2">
                        <label for="montant_attendu" class="col-sm-3 fw-bold col-form-label"><i class="fa fa-money-bill"></i> {{$this->devise}}</label>
                        <div class="col-sm-9">
                            <input type="number" wire:model="montant_attendu"  placeholder="Montant attendu" class="form-control bordure w-100 @error('montant_attendu') is-invalid @enderror" id="montant_attendu">
                        </div>
                        <div class="d-flex justify-content-start">
                            @error('montant_attendu') <span class="text-danger">{{ $message }}</span> @enderror 
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label for="evolution" class="col-sm-3 fw-bold col-form-label">Étape</label>
                        <div class="col-sm-9">                                                        
                            <select id="evolution" wire:model="evolution" class="form-control form-select bordure w-100 @error('evolution') is-invalid @enderror" id="evolution">
                                <option value=""></option>	
                                @foreach($etape as $etapes)
                                    <option value="{{$etapes->id}}">{{$etapes->nom_etape}}</option>
                                @endforeach	                              
                            </select> 
                        </div>
                        <div class="d-flex justify-content-start">
                            @error('evolution') <span class="text-danger">{{ $message }}</span> @enderror 
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label for="priorite" class="col-sm-3 col-form-label">Priorité</label>
                        <div class="col-sm-9">                                                        
                            <select id="priorite" wire:model="priorite" class="form-control form-select bordure w-100 @error('priorite') is-invalid @enderror" id="priorite">
                                <option value=""></option>	
                                <option value="Faible">Faible</option>	
                                <option value="Haute">Haute</option>
                                <option value="Très élevé">Très élevé</option>                               
                            </select> 
                        </div>
                        <div class="d-flex justify-content-start">
                            @error('priorite') <span class="text-danger">{{ $message }}</span> @enderror 
                        </div>
                    </div>                    
                </div>
                <div class="d-flex d-flex justify-content-center gap-1 pt-1 pb-1" style="background-color: #56585d; color:#ffffff;">                    
                    <button class="btn btn-sm btn-secondary" wire:click.prevent="store()" title="Cliquez pour valider" data-toggle="tooltip"><i class="fa fa-check"></i> Valider</button>
                    <a href="#" class="btn btn-sm btn-danger" data-bs-dismiss="modal" title="Cliquez pour annuler" data-toggle="tooltip"><i class="fa fa-close"></i></a>
                </div>              
            </div>
        </div>
    </form>   
</div> 