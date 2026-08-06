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
                        <h5 class="card-title text-bleu"><i class="fa fa-avance text-danger"></i> {{$title_fils}} ({{$avanceCount}})</h5>
                        <a href="{{asset('nouveau_avance?active=15&champ=2-1&choix=1')}}" wire:navigate class="btn btn-sm btn-danger ms-auto"><i class="fa fa-plus-circle"></i> Nouveau avance</a>
                    </div>
                    <div class="card-header d-flex align-items-center justify-content-between px-0">
                        <div class="d-flex align-items-center justify-content-between gap-1">
                            <div class="">
                                <select name="" wire:model.lazy="parPage" id="par_page" class="form-control form-select bordure w-auto"> 
                                    @for($i = 20; $i <= 100; $i += 20)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor        
                                </select> 
                            </div>
                            <div>
                                <h6><a href="{{asset('#')}}" class="btn btn-sm btn-success mt-2 d-none d-md-block">Exporter Excel</a></h6>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                             <div class="card-headerk d-flex align-items-center justify-content-end gap-1">
                                <input type="date" wire:model.live="date_debut" class="form-control bordure w-auto">
                                <input type="date" wire:model.live="date_fin" class="form-control bordure w-auto">
                                {{-- <button class="btn btn-black bordure" title="Cliquez pour valider" wire:click.prevent="valider()"><i class="fa fa-check"></i></button>  --}}
                            </div>                            
                            <div class="">
                                <select wire:model.live="parSalarie" class="form-control form-select bordure w-100"> 
                                    <option value="">Par salarié (Tous)</option>
                                    @foreach($liste_user as $liste_users)
                                        <option value="{{$liste_users->id}}">{{$liste_users->name}}</option>
                                    @endforeach
                                </select> 
                            </div>
                            <div>
                                <label for="query" class="sr-only">Recherche</label>
                                <input type="search" wire:model.live="query" id="query" class="form-control bordure" placeholder="Rechercher avance...">
                            </div>                           
                            <div>
                                <label for="parLibelle" class="sr-only">Recherche</label>
                                <input type="search" wire:model.live="parLibelle" id="parLibelle" class="form-control bordure" placeholder="Rechercher Libellé">
                            </div>
                        </div>
                    </div>
                    <div class="card-body no_bordure">
                        <div class="table-outer">
                            <div class="table-responsive border-top rounded-0">  
                                <table class="table table-striped table-hover text-nowrap m-0">
                                    <thead>
                                        <tr>
                                            <th class="fond_entete_table text-start pointer" wire:click="setOrderField('id')">Réf.<i class="fa fa-arrow-down-short-wide"></i></th> 
                                            <th class="fond_entete_table text-start pointer" wire:click="setOrderField('type_demande')">Type de prêt <i class="fa fa-arrow-down-short-wide"></i></th> 
                                            <th class="fond_entete_table text-start pointer" wire:click="setOrderField('libelle')">Libellé <i class="fa fa-arrow-down-short-wide"></i></th>  
                                            <th class="fond_entete_table text-start pointer" wire:click="setOrderField('salarie')">Salarié <i class="fa fa-arrow-down-short-wide"></i></th> 
                                            <th class="fond_entete_table text-start pointer" wire:click="setOrderField('telephone')">Téléphone <i class="fa fa-arrow-down-short-wide"></i></th> 
                                            <th class="fond_entete_table text-start pointer" wire:click="setOrderField('nom_avance')">Date du prêt <i class="fa fa-arrow-down-short-wide"></i></th>   
                                            <th class="fond_entete_table text-end pointer" wire:click="setOrderField('montant')">Montant <i class="fa fa-arrow-down-short-wide"></i></th> 
                                            <th class="fond_entete_table text-center pointer" wire:click="setOrderField('nombre_tranche')">Nbre Tranche <i class="fa fa-arrow-down-short-wide"></i></th>                     
                                            <th class="fond_entete_table text-start pointer" wire:click="setOrderField('mode_reglement')">Mode règlement <i class="fa fa-arrow-down-short-wide"></i></th>    
                                            <th class="fond_entete_table text-start pointer" wire:click="setOrderField('date_paiement')">Date paiement <i class="fa fa-arrow-down-short-wide"></i></th>                     
                                            <th class="fond_entete_table text-end pointer" wire:click="setOrderField('montant_deja_preleve')">Déja prélève <i class="fa fa-arrow-down-short-wide"></i></th>   
                                            <th class="fond_entete_table text-start pointer" wire:click="setOrderField('etat')">Statut <i class="fa fa-arrow-down-short-wide"></i></th>   
                                            <th class="fond_entete_table text-start pointer" wire:click="setOrderField('societe')">Société <i class="fa fa-arrow-down-short-wide"></i></th>
                                            <th class="fond_entete_table text-start pointer" wire:click="setOrderField('nom_user')">Créé par <i class="fa fa-arrow-down-short-wide"></i></th>                     
                                            {{-- <th class="fond_entete_table"></th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($avance as $avances) 
                                        <tr>  
                                            <td class="text-start"><a href="detail_avance?id={{$avances->id}}&active=15&champ=2-1&choix=2" class="text-secondary fw-bold" wire:navigate>AV-{{$avances->id}}</a></td>                                             
                                            <td class="text-start"><a href="detail_avance?id={{$avances->id}}&active=15&champ=2-1&choix=2" class="text-bleu fw-bold" wire:navigate><i class="fa-solid fa-comments"></i> {{$avances->type_pret}}</a></td>                                             
                                            <td class="text-start"><a href="detail_avance?id={{$avances->id}}&active=15&champ=2-1&choix=2" class="text-bleu fw-semibold" wire:navigate>{{$avances->libelle}}</a></td>                                             
                                            <td class="fw-semibold"><a class="fw-semibold" href="detail_user?id={{$avances->id_salarie}}&active=12&champ=1-1" wire:navigate><i class="fa fa-user-circle"></i> {{Str::limit($avances->salarie, 52)}}</a></td>
                                            <td class="text-start"><a href="detail_user?id={{$avances->id_salarie}}&active=12&champ=1-1" class="fw-semibold" wire:navigate><i class="fa-solid fa-phone"></i> {{$avances->telephone}}</a></td>                                             
                                            <td class="text-start"><a href="detail_avance?id={{$avances->id}}&active=15&champ=2-1&choix=2" class="fw-semibold" wire:navigate><i class="fa fa-calendar-alt"></i> {{date('d-m-Y H:i:s', strtotime($avances->created_at))}}</a></td>
                                            <td class="text-end fw-bold">{{number_format($avances->montant,1,',',' ')}}</td>
                                            <td class="text-center fw-semibold">{{$avances->nombre_tranche}}</td>
                                            <td class="text-start"><a href="detail_avance?id={{$avances->id}}&active=15&champ=2-1&choix=2" class="text-bleu fw-semibold" wire:navigate><i class="fa fa-university"></i> {{$avances->mode_reglement}}</a></td> 
                                            <td class="text-start"><a href="detail_avance?id={{$avances->id}}&active=15&champ=2-1&choix=2" class="fw-semibold" wire:navigate><i class="fa fa-calendar-alt"></i> {{$avances->date_paiement}}</a></td> 
                                            <td class="text-end text-vert fw-bold">{{number_format($avances->montant_deja_preleve,1,',',' ')}}</td>
                                            @if($avances->etat == 1)
                                                <td class="pointer text-center" wire:click.prevent="changeEtat({{$avances->id}}, '{{$avances->etat}}')" title="Cliquez pour changer l'état"><span class="badge bg-success"><i class="fa fa-check-circle"></i></span></td>
                                            @else
                                                <td class="pointer text-center" wire:click.prevent="changeEtat({{$avances->id}}, '{{$avances->etat}}')" title="Cliquez pour changer l'état"><span class="badge bg-danger"><i class="fa fa-times-circle blink"></i></span></td>
                                            @endif
                                            <td class="text-start"><i class="fa fa-home"></i> {{$avances->societe}}</td>
                                            <td class="text-start"><a href="detail_user?id={{$avances->user_id}}&active=12&champ=1-1" class="text-bleu fw-semibold" wire:navigate><i class="fa fa-university"></i> {{$avances->nom_user}}</a></td>                                            
                                            {{-- <td class="taille_icon">
                                                @if($confirmer === $avances->id)                                                               
                                                    <a wire:click.prevent="supprimer({{$avances->id}})" class="btn btn-outline-danger btn-xs bg-danger text-white blink"  title="Cliquez pour confirmer la suppression" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-trash"> Confirmer?</i></a>
                                                @else
                                                    <a wire:click.prevent="confirmerDelete({{$avances->id}})" class="btn btn-outline-muted btn-xs"  title="Cliquez pour supprimer" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-trash"></i></a>
                                                @endif
                                            </td> --}}
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="bloc_pagination">{{$avance->links()}}</div> 
                            </div>                        
                        </div>
                    </div>
                </div>
            </div>
        </div>    
    </div>    
</div>


