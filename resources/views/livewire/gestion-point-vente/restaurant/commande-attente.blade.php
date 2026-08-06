<div>
    <div class="pos dvh-100 d-flex flex-column position-fixed w-100" wire:poll.visible.10s> 
        {{-- Debut chargement --}}        
        <div wire:loading class="chargement_pos">
            <label for=""></label>
            <img src="storage/default/circle_loading.gif" width="64" height="64" style="background: #28282800; margin-left: -3px;">
        </div>
        {{-- Fin chargement --}} 
        <div class="pos-contentk flex-grow-1 overflow-auto" style="background-color: #f6f9fb">
            <div class="card" style="box-shadow: 0 0;">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title d-none d-md-block d-lg-block"><i class="fa fa-tv"></i> Commandes</h5>
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs border-0" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link fs-5" wire:click.prevent="Tables({{$this->id_session_posRes}},'{{$this->ref_session_posRes}}')"><i class="fa fa-table"></i> Tables</button>
                        </li>
                        <li class="nav-item">
                            <button  class="nav-link fs-5 active" wire:click.prevent="CmdAttente({{$this->id_session_posRes}},'{{$this->ref_session_posRes}}')"><i class="fa fa-cutlery"></i> Commandes ({{$cmdCount}})</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link fs-5" wire:click.prevent="backSession({{$this->id_session_posRes}},'{{$this->ref_session_posRes}}')"><i class="fa fa-cash-register"></i> Session</button>
                        </li>
                    </ul>
                    <h5 class="card-title d-none d-md-block"></h5>
                    <!-- Tab panes --> 
                </div>                            
                <div class="card-body px-0 py-0 border-top" style="background-color: #f6f9fb;">
                    <div class="container-fluid pt-0 px-0">
                        <div class="row px-0 py-0">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="card-header d-flex flex-wrap align-items-center justify-content-between bg-white">
                                    <div class="d-flex align-items-center justify-content-between gap-1">
                                        <div class="">
                                            <select name="" wire:model.lazy="parPage" id="par_page" class="form-control form-select bordure w-auto"> 
                                                @for($i = 10; $i <= 100; $i += 10)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor        
                                            </select> 
                                        </div>
                                        <div>
                                            {{-- <h6><a href="{{asset('exporter-cmd')}}" class="btn btn-sm btn-success mt-2">Exporter Excel</a></h6> --}}
                                        </div>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        {{-- <div class="card-headerk d-flex align-items-center justify-content-end gap-1">
                                            <input type="date" wire:model.live="date_debut" class="form-control bordure w-auto">
                                            <input type="date" wire:model.live="date_fin" class="form-control bordure w-auto">
                                            <button class="btn btn-black bordure" title="Cliquez pour valider" wire:click.prevent="valider()"><i class="fa fa-check"></i></button> 
                                        </div> --}}
                                        {{-- <div class="">
                                            <select wire:model.live="parEtat" class="form-control form-select bordure w-auto"> 
                                                <option value="">Tous les statuts</option>
                                                <option value="Payée">Payée</option>
                                                <option value="Impayée">Impayée</option>
                                                <option value="Commencée">Commencée</option>
                                                <option value="Brouillon">Brouillon</option>
                                            </select> 
                                        </div>  --}}
                                        {{-- <div>
                                            <select wire:model.live="parUser" class="form-control form-select bordure w-auto"> 
                                                <option value="">Tous les utilisateurs</option>
                                                @foreach($utilisat as $utilisats)
                                                    <option value={{$utilisats->id}}>{{$utilisats->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>  --}}
                                        <div>
                                            <label for="parTable" class="sr-only">Recherche</label>
                                            <input type="search" wire:model.live="parTable" id="parTable" class="form-control bordure" placeholder="Rechercher table">
                                        </div>                             
                                        <div>
                                            <label for="query" class="sr-only">Recherche</label>
                                            <input type="search" wire:model.live="query" id="query" class="form-control bordure" placeholder="Rechercher client">
                                        </div>
                                    </div>                        
                                </div>
                                 <div class="card-body no_bordure">
                                    <div class="table-outer">
                                        <div class="table-responsive border-top rounded-0">  
                                            <table class="table table-striped table-hover text-nowrap m-0 bg-white">
                                                <thead>
                                                    <tr>
                                                        <th class="fond_entete_table pointer" wire:click="setOrderField('created_at')">Date <i class="fa fa-arrow-down-short-wide"></i></th>
                                                        <th class="fond_entete_table pointer" wire:click="setOrderField('nom_table')">Table <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                        <th class="fond_entete_table pointer" wire:click="setOrderField('en_cuisine')">Cuisine <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                        <th class="fond_entete_table pointer" wire:click="setOrderField('lieu_consommation')">Consommation <i class="fa fa-arrow-down-short-wide"></i></th>
                                                        <th class="fond_entete_table pointer" wire:click="setOrderField('nom_client')">Client <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                        <th class="fond_entete_table pointer" wire:click="setOrderField('ref_session_pos')">Session <i class="fa fa-arrow-down-short-wide"></i></th>   
                                                        <th class="fond_entete_table pointer" wire:click="setOrderField('mode_reglement')">Règlement <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                        <th class="fond_entete_table pointer text-end" wire:click="setOrderField('montant_remise')">Remise <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                        <th class="fond_entete_table pointer text-end" wire:click="setOrderField('montant_ttc')">Montant TTC <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                        <th class="fond_entete_table pointer text-end" wire:click="setOrderField('montant_recu')">Montant Reçu <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                        <th class="fond_entete_table pointer" wire:click="setOrderField('etat')">Paiement <i class="fa fa-arrow-down-short-wide"></i></th>        
                                                        <th class="fond_entete_table pointer" wire:click="setOrderField('adresse_livraison')">Adresse livraison <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                        <th class="fond_entete_table pointer" wire:click="setOrderField('date_consommation')">Date livraison <i class="fa fa-arrow-down-short-wide"></i></th> 
                                                        <th class="fond_entete_table pointer" wire:click="setOrderField('nom_user')">Auteur <i class="fa fa-arrow-down-short-wide"></i></th>        
                                                        <th class="fond_entete_table pointer text-end"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($cmd as $cmds) 
                                                    <tr>
                                                        <td class="fw-bold text-bleu pointer" wire:click.prevent="reprendreCmd('{{$cmds->ref_table}}','{{$cmds->nom_table}}','{{$cmds->id_session_pos}}','{{$cmds->ref_session_pos}}')"><i class="fas fa-calendar-alt text-danger" aria-hidden="true"></i> {{date('d-m-Y H:i:s', strtotime($cmds->created_at))}}</td> 
                                                        <td class="fw-bold pointer" wire:click.prevent="reprendreCmd('{{$cmds->ref_table}}','{{$cmds->nom_table}}','{{$cmds->id_session_pos}}','{{$cmds->ref_session_pos}}')"><i class="fa fa-cutlery text-bleu" aria-hidden="true"></i> {{$cmds->nom_table}}</td> 
                                                        <td class="fw-semibold text-start">
                                                            @if($cmds->en_cuisine == "Oui")  
                                                                <span class="text-vert"><i class="fa fa-thumbs-up"></i> {{$cmds->en_cuisine}}</span>
                                                            @else 
                                                                <span class="text-secondary"><i class="fa fa-thumbs-down"></i> {{$cmds->en_cuisine}}</span>                                                            
                                                            @endif  
                                                        </td>
                                                        <td class="fw-semibold"><i class="fa fa-check-circle text-secondary"></i> {{$cmds->lieu_consommation}}</td>
                                                        <td class="fw-semibold pointer"><a href="detail_tier?id={{$cmds->id_client}}&active=3&champ=3-2" wire:navigate class="text-bleu">@if($cmds->nom_client) <i class="fa fa-user-circle"></i> @endif {{Str::limit($cmds->nom_client, 52)}}</a></td>                                                                      
                                                        <td class="fw-semibold pointer text-bleu"><a href="detail_restau_session?id={{$cmds->id_session_pos}}&ref={{$cmds->ref_session_pos}}&active=5&champ=2-1&choix=1" wire:navigate class="text-bleu"> {{$cmds->ref_session_pos}}</a></td>                                                                      
                                                        <td class="">{{$cmds->mode_reglement}}</td>
                                                        <td class="fw-semibold text-end">{{number_format($cmds->montant_remise,0,',',' ')}}</td>
                                                        <td class="fw-semibold text-end">{{number_format($cmds->montant_ttc,0,',',' ')}}</td>
                                                        <td class="fw-semibold text-end">{{number_format($cmds->montant_recu,0,',',' ')}}</td>
                                                        <td class="text-start">
                                                            @if($cmds->etat == "Brouillon")
                                                                <span class="badge bg-secondary" title="Facture {{$cmds->etat}}"><i class="fa fa-box"></i> {{$cmds->etat}}</span>
                                                            @elseif($cmds->etat == "Impayée")
                                                                <span class="badge bg-warning" title="Facture {{$cmds->etat}}"><i class="fa fa-times-circle"></i> {{$cmds->etat}}</span>
                                                            @elseif($cmds->etat == "Payée")
                                                                <span class="badge bg-success" title="Facture {{$cmds->etat}}"><i class="fa fa-check-circle"></i> {{$cmds->etat}}</span>
                                                            @elseif($cmds->etat == "Commencée")
                                                                <span class="badge bg-info" title="Facture {{$cmds->etat}}"><i class="fa fa-recycle"></i> {{$cmds->etat}}</span>
                                                            @endif                                                 
                                                        </td>
                                                        <td class="">{{$cmds->adresse_livraison}}</td>
                                                        <td class="">{{date('d-m-Y H:i:s', strtotime($cmds->date_consommation))}}</td>
                                                        <td class="fw-semibold text-muted pointer"><a href="detail_user?id={{$cmds->user_id}}&active=12&champ=1-1" wire:navigate><i class="fas fa-user-circle text-bleu" aria-hidden="true"></i> {{$cmds->nom_user}}</a></td>                                            
                                                        <td class="text-end pointer" title="Cliquez pour charger la commande" wire:click.prevent="reprendreCmd('{{$cmds->ref_table}}','{{$cmds->nom_table}}','{{$cmds->id_session_pos}}','{{$cmds->ref_session_pos}}')"><i class="fa fa-pencil"></i></td> 
                                                        {{-- <td class="taille_icon text-end">
                                                            @if($confirmer === $cmds->id)                                                               
                                                                <a wire:click.prevent="supprimer({{$cmds->id}},'{{$cmds->code_facture}}')" class="btn btn-outline-danger btn-xs bg-danger text-white blink" style="font-size:8px;"  title="Cliquez pour confirmer la suppression" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-trash"> Confirmer ?</i></a>
                                                            @else
                                                                <a wire:click.prevent="confirmerDelete({{$cmds->id}})" class="btn btn-outline-muted btn-xs"  title="Cliquez pour supprimer" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-trash"></i></a>
                                                            @endif
                                                        </td>  --}}
                                                    </tr>
                                                    @endforeach
                                                    <tr>
                                                        <td class="text-muted fw-semibold">Total <i class="fas fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" style="vertical-align: middle; cursor: help" title="Total pour cette page"></i></td>
                                                        <td colspan="7" class="text-end fw-semibold" style="color:#006666">{{number_format($montantRemise,0,',',' ')}}</td>
                                                        <td class="text-end fw-semibold" style="color:#006666">{{number_format($montantTTC ,0,',',' ')}}</td>                                                        
                                                        <td class="text-end fw-semibold" style="color:#006666">{{number_format($montantRecu ,0,',',' ')}}</td>
                                                        <td colspan="5"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <div class="bloc_pagination">{{$cmd->links()}}</div> 
                                        </div>                        
                                    </div>
                                </div>
                            </div>                             
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>    
</div>