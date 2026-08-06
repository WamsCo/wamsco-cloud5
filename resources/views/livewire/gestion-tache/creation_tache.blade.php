<div wire:ignore.self class="modal fade" id="creerOppotuniteModal" tabindex="-1" data-bs-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">    
    <form enctype="multipart/form-data"> 
            @csrf                   
        <div class="modal-dialog modal-sm">
            <div class="modal-content">             
                <div class="modal-header py-1" style="padding-top: 0px; padding-bottom: 0px; background-color: #56585d; color:#ffffff;">                    
                    <h3 class="titre_modal_fidelite text-white"><i class="fas fa-check-circle"></i> Tâche</h3>                    
                </div>
                <div class="modal-body">
                    <div class="row mb-1">
                        <label for="utilisateur" class="col-md-1 col-sm-3 fw-bold col-form-label"><i class="fa fa-user"></i></label>
                        <div class="col-sm-11">    
                            <input type="search" name="utilisateur" wire:model.live="utilisateur" wire:keyup="searchResult" placeholder="Commencez à écrire... A-Z ou 0-9" class="form-control bordure w-100 @error('utilisateur') is-invalid @enderror" id="utilisateur"/>
                            <div class="bloc_search_client" style="position: absolute; left: 0;">                       
                                @if($showdiv)
                                    @if($recordCount > 0)
                                        <table class="table table-hover mb-0 text-nowrap w-75" style="overflow: auto; background:#f7f7f7;">                                                        
                                            <tbody>
                                                @if(!empty($records))                                                                
                                                    @foreach($records as $record)
                                                        <tr>                                         
                                                            <td class="pointer fw-semibold" wire:click="ajouterTier({{$record->id}})"><i class="fas fa-user-circle"></i> {{Str::limit($record->name, 22)}} » <span style="color: #8fbc8f; font-weight:600">{{Str::limit($record->telephone, 22)}}</span></td>   
                                                        </tr>                                        
                                                    @endforeach
                                                    <tr>                                         
                                                        {{-- <td class="pointer fw-semibold text-danger" wire:click="ajouterTier({{$record->id}})"><i class="fas fa-user-plus"></i> Créer client</td>   --}}
                                                        <td class="pointer fw-semibold text-danger"><a href="nouveau_utilisateur?active=12&champ=1-1" target="_blank" class="text-danger"><i class="fas fa-user-plus"></i> Créer utilisateur</a></td>  
                                                    </tr> 
                                                @endif                               
                                            </tbody>                                        
                                        </table>
                                    @endif	
                                @endif	
                            </div>
                        </div>
                        <div class="d-flex justify-content-start">
                            @error('utilisateur') <span class="text-danger">{{ $message }}</span> @enderror 
                        </div>
                    </div>
                    <div class="row mb-1 mt-2">
                        <label for="nom_tache" class="col-sm-1 fw-bold col-form-label"><i class="fa fa-check-circle"></i></label>
                        <div class="col-sm-11">
                            <input type="text" wire:model="nom_tache" placeholder="Par ex: Réparation ordinateur" class="form-control bordure w-100 @error('nom_tache') is-invalid @enderror" id="nom_tache">
                        </div>
                        <div class="d-flex justify-content-start">
                            @error('nom_tache') <span class="text-danger">{{ $message }}</span> @enderror 
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