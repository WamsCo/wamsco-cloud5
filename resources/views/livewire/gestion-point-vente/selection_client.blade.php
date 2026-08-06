
<div wire:ignore.self class="modal fade" id="SelectClientModal" tabindex="-1" data-bs-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header py-2" style="background-color: #56585d;color:#ffffff;">
                <h5 class="modal-title" id="exampleModalCenteredScrollableTitle"><i class="far fa-fusers" style=""></i> Clients (<span class="text-warning">{{$tiersCount}}</span>)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{-- Debut chargement --}}        
				<div wire:loading class="chargement_pos">
					<label for=""></label>
					<img src="storage/default/circle_loading.gif" width="64" height="64" style="background: #28282800; margin-left: -3px;">
				</div>
            {{-- Fin chargement --}}
            <div class="modal-body py-0 px-0">
                <div class="card-header px-1 py-0">
                    <div class="d-flex align-items-center justify-content-between gap-1">
                        <div class="">
                            <select name="" wire:model.lazy="parPage" id="par_page" class="form-control form-select bordure w-auto"> 
                                @for($i = 5; $i <= 100; $i += 5)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor        
                            </select> 
                        </div>
                        <div>
                            <label for="chercher" class="sr-only">Recherche</label>
                            <input type="search" wire:model.live="chercher" id="chercher" class="form-control bordure" placeholder="Rechercher">
                        </div>
                    </div>   
                </div>   
                <div class="card-body px-0 py-0">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="table-responsive borderk rounded-0 px-0">  
                                <table class="table table-striped table-hover text-nowrap m-0">
                                    <thead>
                                        <tr>
                                            <th class="fond_entete_table pointer" wire:click="setOrderField('nom')">Nom <i class="fa fa-arrow-down-short-wide"></i></th>                                                           
                                            <th class="fond_entete_table pointer" wire:click="setOrderField('solde')">Solde <i class="fa fa-arrow-down-short-wide"></i></th>                                                           
                                            <th class="fond_entete_table pointer" wire:click="setOrderField('type_tiers')">Type <i class="fa fa-arrow-down-short-wide"></i></th>                           
                                            <th class="fond_entete_table">Téléphone</th>
                                            @if($activer_fidelite == 1)
                                                <th class="fond_entete_table pointer" wire:click="setOrderField('nombre_point')">Fidélité <i class="fa fa-arrow-down-short-wide"></i></th>
                                            @endif 
                                            <th class="fond_entete_table pointer" wire:click="setOrderField('ville')">Ville <i class="fa fa-arrow-down-short-wide"></i></th>
                                            <th class="fond_entete_table pointer" wire:click="setOrderField('sexe')">Sexe <i class="fa fa-arrow-down-short-wide"></i></th>
                                            {{-- <th class="fond_entete_table pointer" wire:click="setOrderField('etat')">Etat <i class="fa fa-arrow-down-short-wide"></i></th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tier as $tiers) 
                                        <tr>                                                
                                            <td class="fw-bold pointer" wire:click="prendre({{$tiers->id}})">
                                                <a href="#" class="">
                                                    <span><i class="fas fa-user-circle"></i> {{Str::limit($tiers->nom, 42)}}</span> 
                                                    @if($tiers->raison_sociale)
                                                        <br><span style="color: #868686; font-weight: 600;font-size: 12px;">» {{$tiers->raison_sociale}}</span>
                                                    @endif
                                                </a>
                                            </td> 
                                            <td class="fw-semibold text-bleu text-end">{{number_format($tiers->solde,0,',',' ')}}</td>   
                                            <td 
                                                @if($tiers->type_tiers == "Client") class="fw-semibold" @elseif($tiers->type_tiers == "Prospect") class="text-danger fw-semibold" @else class="text-primary fw-semibold" @endif> {{$tiers->type_tiers}}
                                                @if($tiers->pays) 
                                                    <br><span style="color:#8a8a8b;font-size: 12px;">{{$tiers->pays}}</span>
                                                @endif
                                            </td>
                                            <td class="pointer" wire:click.prevent="prendre({{$tiers->id}})">
                                                <span class="fw-semibold">{{$tiers->telephone}}</span>
                                                @if($tiers->email) 
                                                    <br><span style="color: #686868;font-size: 12px;">{{$tiers->email}}</span>
                                                @endif
                                            </td>
                                            @if($activer_fidelite == 1)
                                                <td class="pointer" wire:click.prevent="prendre({{$tiers->id}})">
                                                    <span class="fw-semibold"><i class="fa fa-podcast"></i> Points <span @if($tiers->nombre_point >= $tiers->objectif_point) class="blink fw-bold text-danger" @else class="fw-bold text-info" @endif> {{$tiers->nombre_point}}</span>/{{$tiers->objectif_point}}</span> 
                                                    @if($tiers->retrait_point > 0) 
                                                        <br> <span style="font-weight:500;color:#ababab;font-size: 12px;">» Retrait <span @if($tiers->retrait_point > 0) class="fw-semibold text-warning" @else style="color: #ffffff;" @endif>{{$tiers->retrait_point}}</span></span>
                                                    @endif
                                                </td>  
                                            @endif   
                                            <td class="pointer" wire:click.prevent="prendre({{$tiers->id}})">
                                                <span style="font-weight: 600;color: #222222;">{{$tiers->ville}}</span> 
                                                @if($tiers->adresse) 
                                                    <br><span style="color: #373737;font-size: 12px;">{{$tiers->adresse}}</span>
                                                @endif
                                            </td> 
                                            <td  
                                                @if($tiers->sexe == "Masculin") class="fw-semibold text-success" @elseif($tiers->sexe == "Feminin") class="fw-semibold text-danger" @else class="fw-semibold text-info" @endif> {{$tiers->sexe}}
                                            </td>  
                                            {{-- <td class="text-center">                                                                        
                                                @if($tiers->etat == 1)
                                                    <span class="badge bg-success"><i class="fa fa-check"></i></span>
                                                @else
                                                    <span class="badge bg-danger blink"><i class="fa fa-times"></i></span>
                                                @endif 
                                            </td>  --}}
                                        </tr>
                                        @if($idw === $tiers->id) 
                                            <tr style="white-space: initial;">          
                                                @include('livewire.tiers.update')     
                                            </tr>                                            
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="bloc_pagination">{{$tier->links()}}</div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex align-items-center justify-content-between py-1" style="background-color: #56585d; color:#ffffff;">
                <button type="button" data-bs-toggle="modal" data-bs-target="#CreationClientModal" wire:click.prevent="charge()" class="btn btn-sm btn-secondary fw-bold" title="Cliquez pour enregistrer" data-toggle="tooltip"><i class="fa fa-user-plus"></i> Créer client</button>
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> Fermer</button>
            </div>
        </div>
    </div>
</div>