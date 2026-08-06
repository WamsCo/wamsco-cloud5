    
<td class="px-3 pt-0">  
    <div class="row mt-3">  
        <div class="col-sm-12">    
            <input type="text" wire:model="nom_sous_tache" placeholder="Par ex: Réparation onduleur bureau" class="form-control bordure @error('nom_sous_tache') is-invalid @enderror" id="nom_sous_tache">
        </div>
    </div>
    <div class="d-flex justify-content-start">
        @error('nom_sous_tache') <span class="text-danger">{{ $message }}</span> @enderror 
    </div> 
</td>                                                         
<td class="text-start pt-0">
    <div class="row mt-3">  
        @foreach($user as $users) 
            @if($users->id == $this->ids_utilisateur)
                <div class="d-flex justify-content-start align-items-center flex-shrink-0 gap-2 px-2">
                    <div class="position-relative bg-inherit rounded-3">
                        @if($users->profil != null)  
                            <a href="detail_user?id={{$users->user_id}}&active=12&champ=1-1" wire:navigate>
                                <img class="image_log object-fit-cover rounded-3 cursor-pointer" style="width: 24px; height:24px;" src="storage/{{$users->profil}}">
                            </a>
                        @else       
                            <a href="detail_user?id={{$users->user_id}}&active=12&champ=1-1" wire:navigate>
                                <img class="image_log object-fit-cover rounded-3 cursor-pointer" style="width: 24px; height:24px;" src="storage/default/user_man.png"> 
                            </a>                    
                        @endif
                    </div>
                    <div>
                        <strong class="me-1">
                            <label for="nombre_paquets" class="fw-bold col-form-label">{{$this->utilisateur}}</label>
                        </strong>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</td> 
<td class="text-start pt-0">
    <div class="row mt-3">
        <label for="nombre_paquets" class="fw-bold col-form-label"></label>
    </div>
</td>
<td class="text-start pt-0">
    <div class="row mt-3">
        <label for="nombre_paquets" class="fw-bold col-form-label"></label>
    </div>
</td>            
<td colspan="2" class="text-end fw-semibold pt-0">
    <div class="mt-3">
        <a href="#"  wire:click.prevent="ajouter()" class="btn btn-outline-success btn-sm"  title="Cliquez pour ajouter" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ri-check-line"></i> Ajouter</a>
    </div>
</td> 
   