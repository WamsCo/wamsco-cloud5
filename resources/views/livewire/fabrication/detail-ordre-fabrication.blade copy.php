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
                <div class="card mb-0 bg-2k">
                    <div class="card-header d-flex align-items-center justify-content-between border-bottom">
                        <h5 class="card-title text-bleu"><i class="fa fa-cubes text-danger"></i> {{$title_fils}} » <span class="text-vert">{{$ref_ordre}}</span>&nbsp;&nbsp;</h5>
                        <div> @include('flash::message')</div>
                        <div class="d-flex align-items-center justify-content-between gap-1">
                            <div class="d-flex align-items-center justify-content-between gap-1">
                                {{-- <a href="facturationclt-pdf?id={{$this->ids}}&code={{$this->reference}}&format=ticket" class="btn btn-sm btn-default" title="Cliquez pour imprimer" data-toggle="tooltip" target="_blank" style="color: #161a1d;"><i class="fa fa-print"></i>Ticket</a> --}}
                                {{-- <a href="facturationclt-pdf?id={{$this->ids}}&code={{$this->reference}}&format=A4" class="btn btn-sm btn-default" title="Cliquez pour imprimer" data-toggle="tooltip" target="_blank"><i class="fa fa-print"></i>A4</a> --}}
                            </div>                            
                            <div class="d-flex align-items-center justify-content-between gap-1">                            
                                <a href="listing_ordre?active=9&champ=2-1&choix=2" wire:navigate class="btn btn-sm btn-bleu d-none d-md-block ms-auto"><i class="fa fa-chevron-left"></i> Retour liste</a>
                                <button class="btn btn-sm btn-default new_color" wire:click.prevent="precedant({{$this->ids}})" title="Précédant" data-toggle="tooltip"><i class="fa fa-chevron-left"></i></button>
                                <a href="#" class="btn btn-sm btn-default new_color" wire:click.prevent="suivant({{$this->ids}})" title="Suivant" data-toggle="tooltip"><i class="fa fa-chevron-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body no_bordure border-topk rounded-3 taille_ecran_trans">
                        <div class="row mx-0 pt-0">
                            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12 px-1 mb-1 border-end border-bottomk rounded-bottom">
                                <div class="hauteur_ecran">
                                    <div class="row pt-3 px-2">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">                                            
                                            <div class="row mb-1">
                                                <label for="nomenclatures" class="col-sm-3 fw-bold col-form-label">Nomenclature</label>
                                                <div class="col-sm-9"> 
                                                     
                                                    {{-- <label class="form-check-label fw-bold pt-2" style="color: #972fa9;" for="nomenclatures">{{$nomenclatures}}</label> --}}
                                                    <label class="form-check-label fw-bold pt-2" style="color: #972fa9;" for="nomenclatures">
                                                        <a href="detail_nomencla?id={{$this->nomencla_id}}&active=9&champ=1-1&choix=2" wire:navigate style="color: #972fa9;" title="Cliquez pour voir la nomenclature" data-toggle="tooltip">{{$nomenclatures}} &nbsp;<i class="fas fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" style="vertical-align: middle; cursor: help" title="Cliquez pour voir la nomenclature"></i></a>
                                                    </label>
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('nomenclatures') <span class="text-danger">{{ $message }}</span> @enderror 
                                                </div>
                                            </div> 
                                            <div class="row mb-1">
                                                <label for="inputPassword3" class="col-sm-3 fw-bold col-form-label">Type</label>
                                                <div class="col-sm-9">
                                                    <div class="card-body">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" wire:model.live="type_nomencla" value="{{$type_nomencla}}" name="inlineRadioOptions" id="inlineRadio1" value="option1">
                                                            <label class="form-check-label" for="inlineRadio1">{{$type_nomencla}}</label>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('type_nomencla') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-1">
                                                <label for="produit_a_fabrique" class="col-sm-3 col-form-label">Produit à @if($type_nomencla == "Fabrication") fabriquer @else déassembler @endif</label>
                                                <div class="col-sm-9"> 
                                                    <label class="form-check-label fw-bold text-vert pt-2" for="produit_a_fabrique">{{$produit_a_fabrique}}</label>
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('produit_a_fabrique') <span class="text-danger">{{ $message }}</span> @enderror 
                                                </div>
                                            </div> 
                                            <div class="row mb-1">
                                                <label for="libelle" class="col-sm-3 col-form-label">Libellé</label>
                                                <div class="col-sm-9">
                                                    <input type="text" wire:model.defer="libelle" placeholder="Ex: Fabrication pain" class="form-control bordure w-50 @error('libelle') is-invalid @enderror" id="libelle">
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('libelle') <span class="text-danger">{{ $message }}</span> @enderror 
                                                </div>
                                            </div>
                                            <div class="row mb-1">
                                                <label for="quantite_recu" class="col-sm-3 fw-bold col-form-label">Quantité</label>
                                                <div class="col-sm-3">
                                                    <input type="number" wire:model.defer="quantite_recu" placeholder="Ex: 5" min="1" @if($statut == "Validé (à fabriquer)") readonly @endif class="form-control bordure w-50 @error('quantite_recu') is-invalid @enderror" id="quantite_fab">
                                                </div>                                               
                                                <div class="col-sm-6"> 
                                                    <label for="duree" class="col-form-label">@if($quantite_fabrique >= $quantite)<span class=""><i class="fa fa-thumbs-up text-vert blink"></i>&nbsp;&nbsp; {{$quantite_fabrique}}&nbsp;&nbsp;</span> @else <span class="">{{$quantite_fabrique}}&nbsp;&nbsp;</span> @endif / <span class="fw-bold text-vert pointer" data-bs-toggle="modal" data-bs-target="#qteAproduireModal" title="Cliquez pour modifier la quantité à produire">&nbsp;&nbsp;{{$quantite}} &nbsp;&nbsp;</span> <span class="">{{$unite_mesure}}&nbsp;&nbsp;</span> <span class="fw-bold">À @if($type_nomencla == "Fabrication") fabriquer @else déassembler @endif</span> </label>
                                                </div>                                                    
                                                <div class="d-flex justify-content-start">
                                                    @error('quantite_recu') <span class="text-danger">{{ $message }}</span> @enderror 
                                                </div>
                                            </div>
                                            <div class="row mb-1">
                                                <label for="duree" class="col-sm-3 fw-bold col-form-label">Durée estimée (H:min)</label>
                                                <div class="col-sm-9">
                                                    <input type="time" wire:model.defer="duree" min="1" class="form-control bordure w-auto @error('duree') is-invalid @enderror" id="duree">
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('duree') <span class="text-danger">{{ $message }}</span> @enderror 
                                                </div>
                                            </div>
                                            <div class="row mb-1">
                                                <label for="entrepot_fabrication" class="col-sm-3 fw-bold col-form-label">Entrepôt de fabrication</label>
                                                <div class="col-sm-9">   
                                                    <select id="entrepot_fabrication" wire:model.defer="entrepot_fabrication" class="form-control form-select bordure w-auto @error('entrepot_fabrication') is-invalid @enderror">
                                                        <option value=""></option>                                                        
                                                        @foreach($entrepot as $entrepots)
                                                                <option value="{{$entrepots->id}}">{{$entrepots->nom}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('entrepot_fabrication') <span class="text-danger">{{ $message }}</span> @enderror 
                                                </div>
                                            </div> 
                                            <div class="row mb-1">
                                                <label for="date_debut" class="col-sm-3 fw-bold col-form-label">Date début effectif</label>
                                                <div class="col-sm-9">
                                                    <input type="datetime-local" wire:model.defer="date_debut" class="form-control bordure w-auto @error('date_debut') is-invalid @enderror" id="date_debut">
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('date_debut') <span class="text-danger">{{ $message }}</span> @enderror 
                                                </div>
                                            </div>
                                            <div class="row mb-1">
                                                <label for="date_fin" class="col-sm-3 fw-bold col-form-label">Date de fin effectif</label>
                                                <div class="col-sm-9">
                                                    <input type="datetime-local" wire:model.defer="date_fin" class="form-control bordure w-auto @error('date_fin') is-invalid @enderror" id="date_fin">
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('date_fin') <span class="text-danger">{{ $message }}</span> @enderror 
                                                </div>
                                            </div>
                                            <div class="row mb-1">
                                                <label for="tiers" class="col-sm-3 col-form-label">Tiers</label>
                                                <div class="col-sm-9">   
                                                    <select id="tiers" wire:model.defer="tiers" class="form-control form-select bordure w-auto @error('tiers') is-invalid @enderror">
                                                        <option value=""></option>	
                                                        @foreach($tier as $tiers)
                                                            <option value="{{$tiers->id}}">{{$tiers->nom}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('tiers') <span class="text-danger">{{ $message }}</span> @enderror 
                                                </div>
                                            </div> 
                                            <div class="row mb-1">
                                                <label for="responsable" class="col-sm-3 col-form-label">Responsable</label>
                                                <div class="col-sm-9">   
                                                    <select id="responsable" wire:model.defer="responsable" class="form-control form-select bordure w-auto @error('responsable') is-invalid @enderror">
                                                        <option value=""></option>	
                                                        @foreach($utilisateur as $utilisateurs)
                                                            <option value="{{$utilisateurs->id}}">{{$utilisateurs->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('responsable') <span class="text-danger">{{ $message }}</span> @enderror 
                                                </div>
                                            </div> 
                                        </div>                                        
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="row mb-4">
                                                <label for="note" class="col-sm-2 col-form-label" style="font-weight: 400;">Note</label>
                                                <div class="col-sm-10">                                                        
                                                    <textarea rows="2" wire:model="note" class="form-control bordure @error('note') is-invalid @enderror" id="note" placeholder="Ajouter une note..."></textarea>
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('note') <span class="text-danger">{{ $message }}</span> @enderror 
                                                </div>
                                            </div>
                                            <div class="border-topk pt-4 pb-2" style="border-top-style: dashed; border-top-width: 1px;border-top-color: #0e04043d;"> 
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="">
                                                        @if($statut == "Validé (à fabriquer)")
                                                            <button wire:click="update()" class="btn btn-secondary btn-sm fw-bold"><i class="fa fa-check"></i> Modifier</button>
                                                        @else
                                                            @if($statut != "Terminé")
                                                                <button wire:click="produire()" class="btn btn-secondary btn-sm fw-bold"><i class="fa fa-check"></i> Produire</button>
                                                                @if($this->quantite_fabrique > 0)
                                                                    <button  wire:click="Cloturer()" class="btn btn-sm text-danger border fw-semibold ms-auto" title="Cliquez pour clôturer"><i class="fa fa-check"></i> Clôturer</button>
                                                                @endif
                                                            @endif
                                                        @endif
                                                        <a href="{{asset('listing_ordre?active=9&champ=2-1&choix=2')}}" wire:navigate class="btn btn-danger btn-sm"><i class="fa fa-close"></i> Fermer</a>
                                                        @if($approuver === 3)                                                               
                                                            <a wire:click.prevent="ecraser()" class="btn btn-outline-dark btn-sm bg-dark text-white blink"  title="Cliquez pour confirmer la suppression" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-trash"> Confirmer ?</i></a>
                                                        @else
                                                            <a wire:click.prevent="confirmerEcraser(3)" class="btn btn-outline-muted btn-sm"  title="Cliquez pour supprimer" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-trash"></i></a>
                                                        @endif
                                                    </div>
                                                    <div class="">
                                                        @if($statut == "Terminé")
                                                            <span title="Statut » {{$statut}}" style="font-weight: 700; text-align:center;color: #1ebf02;"><i class="fa fa-thumbs-up"></i> {{$statut}}</span>
                                                        @elseif($statut == "En cours")
                                                            <span title="Statut » {{$statut}}" style="font-weight: 700; text-align:center;color: #ff8b8b;"><i class="fa fa-refresh fa-spin"></i> {{$statut}}</span>
                                                        @else
                                                            <span title="Statut » {{$statut}}" style="font-weight: 700; text-align:center;color: #00bcd4;">{{$statut}}</span>
                                                        @endif 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 px-0">
                                            @if($this->nomenclatures > 0)
                                                <div class="table-responsive mt-2">
                                                    <div class="table-responsive mt-1"> 
                                                        <ul class="nav nav-tabs margin_ajuste border-0 pb-1" role="tablist">
                                                            <li class="nav-item">
                                                                <a class="nav-link active" data-bs-toggle="tab" href="#home">Composants</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" data-bs-toggle="tab" href="#autre">Autre</a>
                                                            </li>
                                                        </ul>
                                                        <div class="tab-content table-responsive border-top border-bottomk">
                                                            <div id="home" class="tab-pane active">                               
                                                                <table class="table table-striped text-nowrap m-0"> 
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="fond_entete_table pointer" wire:click="setOrderField('composant')">Composant <i class="fa fa-arrow-down-short-wide"></i></th>
                                                                            <th class="fond_entete_table pointer text-center" wire:click="setOrderField('quantite')"> @if($this->type_nomencla == "Fabrication") A consommer @else A produire @endif <i class="fa fa-arrow-down-short-wide"></i></th>
                                                                            <th class="fond_entete_table pointer text-center" wire:click="setOrderField('quantite_consommer')">@if($this->type_nomencla == "Fabrication") Consommé @else Produire @endif <i class="fa fa-arrow-down-short-wide"></i></th>
                                                                            <th class="fond_entete_table pointer text-start" wire:click="setOrderField('unite')">Unité <i class="fa fa-arrow-down-short-wide"></i></th>
                                                                            <th class="fond_entete_table pointer text-end" wire:click="setOrderField('cout')">Coût total <i class="fas fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" style="vertical-align: middle; cursor: help" title="Le coût de production de cette nomenclature est basé sur chaque quantité et produit à consommer (le Prix moyen pondéré (PMP) : prix d'achat)"></i> <i class="fa fa-arrow-down-short-wide"></i></th>
                                                                            <th class="fond_entete_table pointer"></th>
                                                                            <th class="fond_entete_table pointer"></th>
                                                                            <th class="fond_entete_table pointer"></th>
                                                                        </tr>
                                                                        </thead>                                       
                                                                    <tbody>
                                                                        @foreach($listeComposant as $listeComposants)
                                                                            <tr> 
                                                                                <td class="fw-bold"><a href="detail_product?id={{$listeComposants->composant_id}}&active=4&champ=1-1&choix=2" wire:navigate class="text-bleu fw-bold">{{$listeComposants->composant}}</a></td>                                                         
                                                                                <td class="text-center fw-bold">{{$listeComposants->quantite}}</td> 
                                                                                <td class="text-center fw-bold">
                                                                                    @if($listeComposants->quantite_consommer > 0)
                                                                                        <span class="text-end text-vert fw-bold">{{$listeComposants->quantite_consommer}}</span>
                                                                                    @else
                                                                                        <span class="text-end">{{$listeComposants->quantite_consommer}}</span>
                                                                                    @endif
                                                                                </td> 
                                                                                <td class="text-start fw-bold">{{$listeComposants->unite}}</td> 
                                                                                <td class="fw-bold text-end">{{number_format($listeComposants->cout,0,',',' ')}}</td>
                                                                                <td class="text-end">
                                                                                    @if($statut != "Terminé")
                                                                                        <a href="#" wire:click.prevent="editer({{$listeComposants->id}})" class="btn btn-xs text-muted border fw-semibold ms-auto" data-bs-toggle="modal" data-bs-target="#fabriPartielModal" title="Cliquez pour consommer ou produire partiellement le stock" data-toggle="tooltip" data-bs-placement="top"><i class="fa fa-copy"></i> @if($type_nomencla == "Fabrication") Consommé @else Produire @endif</a>
                                                                                    @endif
                                                                                </td>
                                                                                <td class="text-end">
                                                                                    <a href="#" wire:click.prevent="afficheConso({{$listeComposants->composant_id}})" class="btn btn-xs text-muted border fw-semibold ms-auto" data-bs-toggle="modal" data-bs-target="#afficheConsoOFModal" title="Cliquez pour voir les détails consommations effectuées" data-toggle="tooltip" data-bs-placement="top"><i class="fa fa-refresh"></i> </a>
                                                                                </td>
                                                                                <td class="text-end fw-semibold">                                                                    
                                                                                    @if($confirmer === $listeComposants->id)                                                               
                                                                                        <a wire:click.prevent="supprimer({{$listeComposants->id}})" class="btn btn-outline-danger btn-xs bg-danger text-white blink"  title="Cliquez pour confirmer la suppression" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-trash"> ?</i></a>
                                                                                    @else
                                                                                        <a wire:click.prevent="confirmerDelete({{$listeComposants->id}})" class="btn btn-outline-muted btn-xs"  title="Cliquez pour supprimer" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-trash"></i></a>
                                                                                    @endif
                                                                                </td>  
                                                                            </tr> 
                                                                        @endforeach 
                                                                        @if($ouvre === 1) 
                                                                            <tr>                                                         
                                                                                @include('livewire.fabrication.ajout_ligne_composant_ordreFab')     
                                                                            </tr>
                                                                        @endif
                                                                        <tr style="font-size: 12px;">
                                                                            <td colspan="4">
                                                                                @if($this->nomenclatures > 0)
                                                                                    @if($statut != "Terminé")
                                                                                        <a href="#" class="btn btn-sm btn-white text-green-100" wire:click.prevent="ajoutLigne(1)" title="Cliquez pour ajouter" data-toggle="tooltip"> <i class="fa fa-plus"></i> Ajouter une ligne</a>
                                                                                        <a wire:click="onDataAjout()" class="btn btn-sm btn-white" title="Cliquez pour annuler"><i class="fa fa-close text-danger"></i></a>
                                                                                    @endif
                                                                                @endif
                                                                            </td>
                                                                            <td class="text-end fw-bold text-bleu">{{number_format($coutMTotal,0,',',' ')}} <span class="text-bleu" style="font-size: 11px">{{$this->devise}}</span></td>
                                                                            <td colspan="4" class="text-end"></td>
                                                                        </tr>                                                                    
                                                                    </tbody>
                                                                </table>                                           
                                                            </div>
                                                            <div id="autre" class="tab-pane">            
                                                            </div>
                                                        </div>                    
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 px-0 pt-3 mb-1 rounded-bottom">
                                <div class="hauteur_ecran">
                                    <div class="d-flex align-items-center justify-content-between px-2">
                                        <div class="">
                                            <h5 class="text-bleu"><i class="fas fa-calendar-alt" style=" color: #393b83;" title="Derniers événements liés"></i>  Les  derniers événements liés </h5>
                                            {{-- <h5 class="text-bleu"><i class="fas fa-calendar-alt" style=" color: #393b83;" title="Derniers événements liés"></i> @if($logCount > 1) Les {{$logCount}} derniers événements liés @else L'événement lié @endif</h5> --}}
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
    </div>
      {{-- Modal --}}
    @include('livewire.fabrication.ajout_fabrication_partiel')     
    @include('livewire.fabrication.afficher_conso_of')
    @include('livewire.fabrication.ajout_quantite_a_fabriquer')         
</div>


