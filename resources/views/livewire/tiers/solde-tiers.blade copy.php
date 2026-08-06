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
                    <div class="card-header d-flex align-items-center justify-content-between gap-1">
                        <h5 class="card-title text-bleu"><i class="fas fa-file-invoice-dollar text-danger" aria-hidden="true"></i> {{$title_fils}} ({{$soldeTier_count}})</h5>
                        <div class="d-flex align-items-center justify-content-between gap-1">
                            {{-- <a href="#" wire:click.prevent="edit()" data-bs-toggle="modal" data-bs-target="#soldeTiersModal" title="Cliquez pour recharger le compte client" data-toggle="tooltip" class="btn btn-sm btn-danger fw-semibold ms-auto"><i class="fas fa-file-invoice-dollar"></i> Recharge</a> --}}
                            <a wire:click.prevent="detailTier()" class="btn btn-sm btn-bleu d-nonef d-md-block ms-auto"><i class="fa fa-chevron-left"></i> Retour liste</a>
                        </div>
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
                            <div>                                
                                <h6>
                                    {{-- <a href="{{asset('exporter-entites')}}" class="btn btn-sm btn-success mt-2 d-none d-md-block">Exporter Excel</a> --}}
                                </h6>                                
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between gap-1">
                            <div>                                                     
                                
                            </div>
                            <div>
                                <label for="query" class="sr-only">Recherche</label>
                                <input type="search" wire:model.live="query" id="query" class="form-control bordure" placeholder="Rechercher désignation">
                            </div>
                        </div>
                    </div>
                    <div class="card-body no_bordure">
                        <div class="table-outer">
                            <div class="table-responsive border-top rounded-0">  
                                <table class="table table-striped table-hover text-nowrap m-0">
                                    <thead>
                                        <tr>
                                            {{-- <th class="fond_entete_table pointer"></th>   --}}
                                            <th class="fond_entete_table pointer" wire:click="setOrderField('id')">Réf. <i class="fa fa-arrow-down-short-wide"></i></th>                                 
                                            <th class="fond_entete_table pointer text-center" wire:click="setOrderField('designation')"><i class="fa fa-print"></i></th>                                          
                                            <th class="fond_entete_table pointer" wire:click="setOrderField('nom_tier')">Client <i class="fa fa-arrow-down-short-wide"></i></th>                                 
                                            <th class="fond_entete_table pointer text-start" wire:click="setOrderField('designation')">Désignation <i class="fa fa-arrow-down-short-wide"></i></th>                                 
                                            <th class="fond_entete_table pointer text-start" wire:click="setOrderField('compte')">Compte <i class="fa fa-arrow-down-short-wide"></i></th>                                 
                                            <th class="fond_entete_table pointer text-end" wire:click="setOrderField('debit')">Débit <i class="fa fa-arrow-down-short-wide"></i></th>                                 
                                            <th class="fond_entete_table pointer text-end" wire:click="setOrderField('credit')">Crédit <i class="fa fa-arrow-down-short-wide"></i></th>                                 
                                            <th class="fond_entete_table pointer text-center">Solde</th>                                 
                                            <th class="fond_entete_table pointer" wire:click="setOrderField('ville')">Ville <i class="fa fa-arrow-down-short-wide"></i></th> 
                                            <th class="fond_entete_table pointer" wire:click="setOrderField('pays')">Pays <i class="fa fa-arrow-down-short-wide"></i></th>
                                            <th class="fond_entete_table pointer" wire:click="setOrderField('telephone')">Téléphone <i class="fa fa-arrow-down-short-wide"></i></th>                                 
                                            <th class="fond_entete_table pointer text-start" wire:click="setOrderField('email')">email <i class="fa fa-arrow-down-short-wide"></i></th>                                        
                                            <th class="fond_entete_table pointer text-start" wire:click="setOrderField('nom_user')">Auteur <i class="fa fa-arrow-down-short-wide"></i></th>                                        
                                            <th class="fond_entete_table pointer" wire:click="setOrderField('created_at')">Création <i class="fa fa-arrow-down-short-wide"></i></th>  
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($liste_soldeTier as $liste_soldeTiers) 
                                        <tr> 
                                            <td class="fw-semibold">#{{$liste_soldeTiers->id}}</td>                                          
                                            <td class="text-center">
                                                @if($liste_soldeTiers->designation == "Versement en compte" || $liste_soldeTiers->designation == "Retrait en compte") 
                                                    <a href="imprecutier-pdf?id={{$liste_soldeTiers->id}}&code={{$liste_soldeTiers->code_tier}}&format=A4" target="_blank" class="btn btn-sm py-0" title="Cliquez pour imprimer" data-toggle="tooltip"><i class="fa fa-print"></i></a>
                                                @endif
                                            </td>
                                            <td class="fw-bold"><a class="text-bleu" href="detail_tier?id={{$liste_soldeTiers->id_tier}}&active=3&champ=3-2" wire:navigate><i class="fa fa-university"></i> {{Str::limit($liste_soldeTiers->nom_tier, 52)}}</a></td>                                                                      
                                            <td class="fw-semibold">{{$liste_soldeTiers->designation}}</td> 
                                            <td class="fw-semibold">{{$liste_soldeTiers->compte}}</td> 
                                            <td class="fw-semibold text-end text-danger">{{number_format($liste_soldeTiers->debit,0,',',' ')}}</td> 
                                            <td class="fw-semibold text-end text-vert">{{number_format($liste_soldeTiers->credit,0,',',' ')}}</td>                                           
                                            <td class="fw-semibold text-center">-</td> 
                                            <td class="fw-semibold">{{$liste_soldeTiers->ville}}</td> 
                                            <td class="fw-semibold">{{$liste_soldeTiers->pays}}</td> 
                                            <td class="">{{$liste_soldeTiers->telephone}}</td>
                                            <td class="fw-semibold">{{$liste_soldeTiers->email}}</td> 
                                            <td class="fw-semibold"><a href="detail_user?id={{$liste_soldeTiers->user_id}}&active=12&champ=1-1" wire:navigate><i class="fas fa-user-circle text-danger"></i> {{$liste_soldeTiers->nom_user}}</a></td> 
                                            <td class="">{{date('d-m-Y H:i:s',strtotime($liste_soldeTiers->created_at))}}</td>
                                        </tr>                                        
                                        @endforeach
                                        <tr>                                                                     
                                            <td class="text-muted fw-semibold">Total <i class="fas fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" style="vertical-align: middle; cursor: help" title="Total pour cette page"></i></td>                                                        
                                            <td colspan="4"></td> 
                                            <td class="text-end fw-bold text-bleu">{{number_format($montantDebit,0,',',' ')}}</td>         
                                            <td class="text-end fw-bold text-bleu">{{number_format($montantCredit,0,',',' ')}}</td> 
                                            <td class="text-end fw-bold text-vert text-center">{{number_format($soldes,0,',',' ')}} <span class="text-bleu" style="font-size: 09px">{{$this->devise}}</span></td> 
                                            <td colspan="6"></td>  
                                        </tr> 
                                    </tbody>
                                </table>
                                <div class="bloc_pagination">{{$liste_soldeTier->links()}}</div> 
                            </div>                        
                        </div>
                    </div>
                </div>
            </div>
        </div>    
    </div>     
</div>



