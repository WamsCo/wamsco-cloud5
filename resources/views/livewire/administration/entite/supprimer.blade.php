<div wire:ignore.self class="modal fade" id="supprimerModal" tabindex="-1" role="dialog" aria-labelledby="slidemodalLabel" aria-hidden="true">
    <form enctype="multipart/form-data"> 
        @csrf                   
        <div class="modal-dialog modal-sm">
            <div class="modal-content">             
                <div class="modal-header py-2" style="background-color: #56585d; color:#ffffff;">
                    <h5 class="modal-title" id="exampleModalCenteredScrollableTitle">Société <span class="text-bleu"></span> </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body new_color">                                                         
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="fw-semibold pb-2">
                            <span class="text-danger">NB:</span> Cette suppression efface toutes les informations de cette entités dans le systeme (action irrevessible)
                        </div>
                        <label class="control-label label_connexion">Societé</label>
                        <div class="input-group mb-2">															 
                            <div class="input-group-prepend">
                                <span class="input-group-text label_modif"><i class="fas fa-university green"></i></span>
                            </div>
                            <select name="enseigne" wire:model.defer="enseigne" class="form-control form-select fw-bold bordure @error('enseigne') is-invalid @enderror" >												
                                <option value=""> Sélectionnez enseigne</option>                                
                                @foreach ($entite as $entites)
                                    <option  value="{{$entites->enseigne}}">{{$entites->enseigne}}</option> 
                                @endforeach  
                            </select> 
                            @error('enseigne')<div class="text-danger invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <label class="control-label label_connexion">Mot de passe</label><br/>
                        <div class="input-group mb-1">							 
                            <div class="input-group-prepend">
                                <span class="input-group-text label_modif"><i class="fas fa-podcast green"></i></span>
                            </div>
                            <input type="password" wire:model.defer="password" class="form-control bordure @error('password') is-invalid @enderror" placeholder="Mot de passe">                            
                            @error('password')<div class="text-danger invalid-feedback">{{ $message }}</div>@enderror 
                        </div>										
                    </div> 
                </div>
                <div class="modal-footer py-1" style="background-color: #56585d; color:#ffffff;">                    
                    <button type="submit" wire:click.prevent="supprimer()" class="btn btn-sm btn-secondary fw-bold"><i class="fa fa-check"></i> Supprimer</button>
                    <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal"><i class="fa fa-close"></i></button>
                </div>              
            </div>
        </div>
    </form>   
</div> 