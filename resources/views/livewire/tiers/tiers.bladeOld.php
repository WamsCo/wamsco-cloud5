<div x-data="{selection: @entangle('selection').defer}">
    <div id="content" class="app-content"> 
        <div class="profile mb-2">
            <div class="profile-header">        
                <div class="profile-header-cover"></div>      
                <div class="profile-header-content">
                    <div class="profile-header-info">
                        <ol class="breadcrumb float-xl-start">
                            <li class="breadcrumb-item"><a href="bienvenue">Accueil /</a></li>
                            <li class="breadcrumb-item activek"><a href="{{$lien}}" wire:navigate>&nbsp;{{$module}} /</a></li>
                            <li class="breadcrumb-item"><span style="color:yellow">&nbsp;{{$title_fils}}</span></li>                           
                        </ol>
                        {{-- <h1 class="page-header mb-1">{{$title_fils}} » <span style="color:yellow">{{$categoriecount}}</span></h1>   --}}
                    </div> 
                </div> 
            </div>
        </div>
        <div class="row gx-4">
            {{-- Debut chargement --}}        
            <div wire:loading class="chargement">
                <label for=""></label>
                <img src="storage/default/circle_loading.gif" width="64" height="64" style="background: #28282800; margin-left: -3px;">
            </div>
            {{-- Fin chargement --}}
            <div class="col-sm-12">
                <div class="card mb-4 bg-2k">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title text-bleu"><i class="fa fa-users text-danger" aria-hidden="true"></i> {{$title_fils}} ({{$tiersCount}})</h5>
                         <div style="margin-left:16px;"> @include('flash::message')</div>
                        <button href="#" data-bs-toggle="modal" data-bs-target="#createTierModal" title="Cliquez pour voir les détails" data-toggle="tooltip" class="btn btn-sm btn-danger ms-auto"><i class="fa fa-plus-circle"></i> Nouveau tier</button>
                    </div>
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center justify-content-between gap-1">
                            <div class="">
                                <select name="" wire:model.lazy="parPage" id="par_page" class="form-control form-select bordure w-auto"> 
                                    @for($i = 20; $i <= 100; $i += 20)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor        
                                </select> 
                            </div>
                            <div class="d-flex align-items-center justify-content-between gap-1">
                                <h6><a href="{{asset('exporter-tiers')}}" class="btn btn-sm btn-success mt-2 d-none d-md-block"><i class="fa-solid fa-file-export"></i> Exporter Excel</a></h6>
                                <h6><a href="#" data-bs-toggle="modal" data-bs-target="#importerTiersModal" class="btn btn-sm btn-warning mt-2 d-none d-md-block"><i class="fa-solid fa fa-upload"></i>  Importer Excel</a></h6>
                                <h6><a href="{{asset('storage/manuel_users/Excel_Tier_WamsCo.xlsx')}}" download="Excel_Tier_WamsCo" class="btn btn-sm btn-secondary mt-2 d-none d-md-block"><i class="fa-solid fa fa-download"></i>  Télécharger Modèle</a></h6>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between gap-1">
                            <div>                                                     
                                <select wire:model.lazy="parTier" class="form-control form-select bordure w-auto" style="color:#818181">  
                                    <option value="">Tiers [tous]</option>
                                    <option value="Client">Client</option>
                                    <option value="Fournisseur">Fournisseur</option>
                                    <option value="Prospect">Prospect</option>  
                                </select> 
                            </div>
                            <div>
                                <label for="query" class="sr-only">Recherche</label>
                                <input type="search" wire:model.live="query" id="query" class="form-control bordure" placeholder="Rechercher">
                            </div>
                        </div>
                    </div>
                    <div class="card-body no_bordure">
                        <div class="table-outer">
                            <div class="table-responsive border-top rounded-0">  
                                <table class="table table-striped table-hover text-nowrap m-0">
                                    <thead>
                                        <tr>
                                            {{-- <th class=""></th>     --}}
                                            <th class="fond_entete_table pointer" wire:click="setOrderField('nom')">Nom <i class="fa fa-arrow-down-short-wide"></i></th>                                                           
                                            <th class="fond_entete_table pointer" wire:click="setOrderField('code_tier')">Code tier <i class="fa fa-arrow-down-short-wide"></i></th>                                                           
                                            <th class="fond_entete_table pointer" wire:click="setOrderField('raison_sociale')">Raison sociale <i class="fa fa-arrow-down-short-wide"></i></th>                                                           
                                            <th class="fond_entete_table pointer" wire:click="setOrderField('solde')">Solde <i class="fa fa-arrow-down-short-wide"></i></th>                           
                                            <th class="fond_entete_table pointer" wire:click="setOrderField('sexe')">Sexe <i class="fa fa-arrow-down-short-wide"></i></th>
                                            <th class="fond_entete_table pointer" wire:click="setOrderField('type_tiers')">Type <i class="fa fa-arrow-down-short-wide"></i></th>                           
                                            <th class="fond_entete_table pointer" wire:click="setOrderField('telephone')">Téléphone <i class="fa fa-arrow-down-short-wide"></i></th>                           
                                            <th class="fond_entete_table pointer" wire:click="setOrderField('email')">Email <i class="fa fa-arrow-down-short-wide"></i></th>                           
                                            <th class="fond_entete_table pointer text-center" wire:click="setOrderField('etat')">Etat <i class="fa fa-arrow-down-short-wide"></i></th>
                                            @if($activer_fidelite == 1)
                                                <th class="fond_entete_table pointer" wire:click="setOrderField('nombre_point')">Fidélité <i class="fa fa-arrow-down-short-wide"></i></th>
                                            @endif 
                                            <th class="fond_entete_table pointer" wire:click="setOrderField('ville')">Adresse <i class="fa fa-arrow-down-short-wide"></i></th>
                                            <th class="fond_entete_table pointer" wire:click="setOrderField('ville')">Ville <i class="fa fa-arrow-down-short-wide"></i></th>
                                            <th class="fond_entete_table pointer" wire:click="setOrderField('pays')">Pays <i class="fa fa-arrow-down-short-wide"></i></th>
                                            <th class="fond_entete_table pointer" wire:click="setOrderField('updated_at')">Date modif. <i class="fa fa-arrow-down-short-wide"></i></th> 
                                            <th class="fond_entete_table text-end"></th>
                                            @if($activer_fidelite == 1)
                                                <th class="fond_entete_table text-end"></th>
                                            @endif 
                                            <th class="fond_entete_table text-end"></th> 
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tiers as $tier) 
                                            <tr>
                                                {{-- <td>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked="" x-model="selection" value="{{$tier->id}}">                                                    
                                                    </div>
                                                </td> --}}
                                                <td class="fw-bold">                                                    
                                                    <a href="detail_tier?id={{$tier->id}}&active=3&champ=3-2" wire:navigate>
                                                        <span><i class="fas fa-user-circle"></i> {{Str::limit($tier->nom, 42)}}</span>                                                        
                                                    </a>
                                                </td>
                                                 <td class="fw-semibold">                                                    
                                                    <a href="detail_tier?id={{$tier->id}}&active=3&champ=3-2" class="text-muted" wire:navigate>{{$tier->code_tier}}</a>
                                                </td>
                                                <td class="">
                                                    @if($tier->raison_sociale)
                                                        <span style="color: #868686; font-weight: 600;font-size: 12px;">» {{$tier->raison_sociale}}</span>
                                                    @endif
                                                </td>
                                                <td class="fw-semibold text-bleu text-end">{{number_format($tier->solde,0,',',' ')}}</td>  
                                                <td @if($tier->sexe == "Masculin") class="fw-semibold text-success" @elseif($tier->sexe == "Feminin") class="fw-semibold text-danger" @else class="fw-semibold text-info" @endif> 
                                                    {{$tier->sexe}}
                                                </td>    
                                                <td @if($tier->type_tiers == "Client") class="fw-semibold" @elseif($tier->type_tiers == "Prospect") class="text-danger fw-semibold" @else class="text-primary fw-semibold" @endif>
                                                    {{$tier->type_tiers}}
                                                </td>
                                                <td class="">
                                                    <span class="fw-semibold">{{$tier->telephone}}</span>
                                                </td>                                                
                                                <td class="">                                                    
                                                    @if($tier->email) 
                                                        <span style="color: #686868;font-size: 12px;">{{$tier->email}}</span>
                                                    @endif
                                                </td>
                                                <td class="text-center pointer">                                                                        
                                                    @if($tier->etat == 1)
                                                        <span class="badge bg-success" wire:click.prevent="changeEtat({{$tier->id}}, '{{$tier->etat}}')"><i class="fa fa-check"></i></span>
                                                    @else
                                                        <span class="badge bg-danger blink" wire:click.prevent="changeEtat({{$tier->id}}, '{{$tier->etat}}')"><i class="fa fa-times"></i></span>
                                                    @endif 
                                                </td> 
                                                @if($activer_fidelite == 1)
                                                    <td class="pointer" data-bs-toggle="modal" data-bs-target="#afficheFidelitModal" wire:click.prevent="charge({{$tier->id}})">
                                                        <span class="fw-semibold"><i class="fa fa-podcast"></i> Total Point <span @if($tier->nombre_point >= $tier->objectif_point) class="blink fw-bold text-danger" @else class="fw-bold text-info" @endif> {{$tier->nombre_point}}</span>/{{$tier->objectif_point}}</span> 
                                                        @if($tier->retrait_point > 0) 
                                                            <span style="font-weight:500;color:#ababab;font-size: 12px;">» Retrait <span @if($tier->retrait_point > 0) class="fw-semibold text-warning" @else style="color: #ffffff;" @endif>{{$tier->retrait_point}}</span></span>
                                                        @endif
                                                    </td>  
                                                @endif                                            
                                                <td class="">
                                                    @if($tier->adresse) 
                                                        <span style="color: #222222;font-size: 12px;">{{$tier->adresse}}</span>
                                                    @endif
                                                </td> 
                                                <td class="">
                                                    <span style="font-weight: 600;color: #222222;">{{$tier->ville}}</span>                                                    
                                                </td>
                                                <td class="">
                                                    @if($tier->pays) 
                                                        <span style="color:#8a8a8b;font-size: 12px;">{{$tier->pays}}</span>
                                                    @endif
                                                </td>                                                                                                
                                                <td class="">{{date('d-m-Y H:i:s', strtotime($tier->updated_at))}}</td>
                                                @if($activer_fidelite == 1)
                                                    <td class="pointer"><a data-bs-toggle="modal" data-bs-target="#afficheFidelitModal" wire:click.prevent="charge({{$tier->id}})"><i class="fa fa-podcast" title="Cliquez pour afficher point de fidélité" data-toggle="tooltip"></i></a></td>
                                                @endif 
                                                <td class=""><a href="detail_tier?id={{$tier->id}}&active=3&champ=3-2" wire:navigate><i class="fa fa-pencil" title="Cliquez pour modifier" data-toggle="tooltip"></i></a></td>
                                                <td class="taille_icon text-end">
                                                    @if($confirmer === $tier->id)                                                               
                                                        <a wire:click.prevent="supprimer({{$tier->id}},'{{$tier->type_entrepots}}')" class="btn btn-outline-danger btn-xs bg-danger text-white blink"  title="Cliquez pour confirmer la suppression" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-trash"> Confirmer?</i></a>
                                                    @else
                                                        <a wire:click.prevent="confirmerDelete({{$tier->id}})" class="btn btn-outline-muted btn-xs"  title="Cliquez pour supprimer" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-trash"></i></a>
                                                    @endif
                                                </td> 
                                            </tr>
                                        @endforeach
                                        <tr>                                                                     
                                            <td class="text-muted fw-semibold">Total <i class="fas fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" style="vertical-align: middle; cursor: help" title="Total pour cette page"></i></td>                                                       
                                            <td colspan="2"></td> 
                                            <td class="text-end fw-bold text-bleu">{{number_format($SoldeTotal,0,',',' ')}}</td> 
                                            <td colspan="13"></td>  
                                        </tr> 
                                    </tbody>
                                </table>
                                <div class="bloc_pagination">{{$tiers->links()}}</div> 
                            </div>                        
                        </div>
                    </div>
                </div>
            </div>
        </div>    
    </div>
    {{-- Modal --}}
    @include('livewire.tiers.create_tiers')
    @include('livewire.tiers.point_fidelite_modif') 
    @include('livewire.tiers.importation_tiers')
</div>

