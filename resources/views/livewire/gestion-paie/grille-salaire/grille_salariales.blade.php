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
                        <h5 class="card-title text-bleu"><i class="fa fa-table text-danger" aria-hidden="true"></i> {{$title_fils}} ({{$grilleCount}})</h5>
                        <div class="d-flex gap-2">
                            <div> @include('flash::message')</div>
                           <div>
                                <button class="btn btn-sm btn-danger ms-auto" title="Cliquez pour ajouter" data-toggle="tooltip" data-bs-toggle="modal" data-bs-target="#createGrilleModal"><i class="fa fa-plus-circle"></i> Nouvelle base</button>
                           </div>
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
                            <div class="d-flex align-items-center justify-content-between gap-1">
                                <h6><a href="{{asset('exporter-grille-salariale')}}" class="btn btn-sm btn-success mt-2 d-none d-md-block"><i class="fa-solid fa-file-export"></i> Exporter Excel</a></h6>
                                <h6><a href="#" data-bs-toggle="modal" data-bs-target="#importerGrilleSalarialeModal" class="btn btn-sm btn-warning mt-2 d-none d-md-block"><i class="fa-solid fa fa-upload"></i> Importer Excel</a></h6>
                                <h6><a href="{{asset('storage/manuel_users/Excel_Grille_salariale_WamsCo.xlsx')}}" download="Excel_Grille_salariale_WamsCo" class="btn btn-sm btn-secondary mt-2 d-none d-md-block"><i class="fa-solid fa fa-download"></i> Télécharger Modèle</a></h6>
                            </div>
                        </div>
                        <div>
                            <label for="query" class="sr-only">Recherche</label>
                            <input type="search" wire:model.live="query" id="query" class="form-control bordure" placeholder="Rechercher cat.">
                        </div>
                    </div>
                    <div class="card-body no_bordure border-top taille_ecran"> 
                    <div class="row mx-0 pt-0">
                        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12 mb-1 pt-0 px-0 border-end border-bottom rounded-bottom">
                            <div class="hauteur_ecran">
                                <div class="row pt-0 px-0">
                                    <div class="table-outer mb-2">
                                        <div class="table-responsive border-topk rounded-0">  
                                            <table class="table table-striped table-hover text-nowrap m-0">
                                                <thead>
                                                    <tr>
                                                        <th class="fond_entete_table pointer" wire:click="setOrderField('id')">Réf. <i class="fa fa-arrow-down-short-wide"></i></th>   
                                                        <th class="fond_entete_table pointer text-center" wire:click="setOrderField('categorie')">Catégorie <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                        <th class="fond_entete_table pointer text-center" wire:click="setOrderField('echelon')">Echelon <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                        <th class="fond_entete_table pointer text-end" wire:click="setOrderField('salaire_base')">Salaire de base <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                        <th class="fond_entete_table pointer" wire:click="setOrderField('nom_user')">Créer par <i class="fa fa-arrow-down-short-wide"></i></th>    
                                                        <th class="fond_entete_table pointer text-start" wire:click="setOrderField('created_at')">Date <i class="fa fa-arrow-down-short-wide"></i></th>
                                                        <th class="fond_entete_table pointer text-end"></th>
                                                        <th class="fond_entete_table pointer text-end"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($grille as $grilles) 
                                                        <tr>
                                                            <td class="fw-bold pointer text-bleu" wire:click.prevent="edit('{{$grilles->id}}')" data-bs-toggle="modal" data-bs-target="#updateGrilleModal" title="Cliquez pour voir les détails" data-toggle="tooltip"><i class="fa fa-user-circle text-danger" aria-hidden="true"></i> {{$grilles->reference}}</td> 
                                                            <td class="fw-bold pointer text-bleu text-center" wire:click.prevent="edit('{{$grilles->id}}')" data-bs-toggle="modal" data-bs-target="#updateGrilleModal" title="Cliquez pour voir les détails" data-toggle="tooltip">{{$grilles->categorie}}</td> 
                                                            <td class="fw-bold text-center">{{$grilles->echelon}}</td>
                                                            <td class="text-end" style="font-weight: 700; color:#707070;">{{number_format($grilles->salaire_base,1,',',' ')}}</td>
                                                            <td class="fw-semibold pointer"><a href="detail_user?id={{$grilles->user_id}}&active=12&champ=1-1" wire:navigate>{{Str::limit($grilles->nom_user, 52)}}</a></td>                                                                      
                                                            <td class="fw-bold">{{date('d-m-Y H:i:s', strtotime($grilles->created_at))}}</td>
                                                            <td class="text-end pointer" wire:click.prevent="edit('{{$grilles->id}}')" data-bs-toggle="modal" data-bs-target="#updateGrilleModal" title="Cliquez pour voir les détails" data-toggle="tooltip"><i class="fa fa-pencil"></i></a></td>
                                                            <td class="taille_icon text-end">
                                                                @if($confirmer === $grilles->id)                                                               
                                                                    <a wire:click.prevent="supprimer({{$grilles->id}},'{{$grilles->code_facture}}')" class="btn btn-outline-danger btn-xs bg-danger text-white" style="font-size:8px;"  title="Cliquez pour confirmer la suppression" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-trash"> Confirmer ?</i></a>
                                                                @else
                                                                    <a wire:click.prevent="confirmerDelete({{$grilles->id}})" class="btn btn-outline-muted btn-xs"  title="Cliquez pour supprimer" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-trash"></i></a>
                                                                @endif
                                                            </td> 
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <div class="bloc_pagination">{{$grille->links()}}</div> 
                                        </div>                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 px-0 mb-1 pt-3 rounded-bottom">
                            <div class="hauteur_ecran">
                                <div class="d-flex align-items-center justify-content-between px-2">
                                    <div class="">
                                        <h5 class="text-bleu"><i class="fas fa-calendar-alt" style=" color: #393b83;" title="Derniers événements liés"></i> @if($logCount > 1) Les {{$logCount}} derniers événements @else L'événement @endif</h5>
                                    </div>
                                    <div>                                                                                                                        
                                    </div>
                                </div>
                                <div class="table-responsive border-top rounded-0">  
                                    @foreach($log as $logs)
                                        <div class="d-flex flex-shrink-0 gap-2 px-2 py-2">
                                            <div class="d-flex flex-shrink-0 align-items-center flex-column bg-inherit align-items-start">
                                                <div class="position-relative bg-inherit rounded-3">
                                                    @if($logs->profil != null)  
                                                        <a href="detail_user?id={{$logs->user_id}}&active=12&champ=1-1" wire:navigate>
                                                            <img class="image_log object-fit-cover rounded-3 cursor-pointer" src="storage/{{$logs->profil}}">
                                                        </a>
                                                    @else       
                                                        <a href="detail_user?id={{$logs->user_id}}&active=12&champ=1-1" wire:navigate>
                                                            <img class="image_log object-fit-cover rounded-3 cursor-pointer" src="storage/default/user_man.png"> 
                                                        </a>                    
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column flex-wrap align-items-baseline lh-1 gap-2">
                                                <div>
                                                    <strong class="me-1">
                                                        <a href="detail_user?id={{$logs->user_id}}&active=12&champ=1-1" wire:navigate>{{$logs->user_email}} &nbsp; <span class="fw-500">{{date('d-m-Y H:i:s', strtotime($logs->created_at))}}</span></a>
                                                    </strong>
                                                </div>
                                                <div class="">
                                                    <span><i class="fas fa-calendar-alt text-danger"></i> {{$logs->id}}</span> &nbsp; <span><i class="fas fa-university text-dangerg"></i> {{$logs->user_societe}}</span>
                                                </div>
                                                <div class="text-bleu fw-semibold">
                                                    {{$logs->subject}}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
    </div> 
    {{-- Modal --}}
    @include('livewire.gestion-paie.grille-salaire.modal_create')
    @include('livewire.gestion-paie.grille-salaire.modal_update')
    @include('livewire.gestion-paie.grille-salaire.importation_grille_salariale')
</div>



