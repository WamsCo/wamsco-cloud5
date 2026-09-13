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
                @include('flash::message')
                <div class="card mb-0">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center justify-content-between gap-1">                                                       
                            <div class="d-flex align-items-center justify-content-between gap-1"> 
                               <button  wire:click="creation()" class="btn btn-secondary btn-sm fw-bold"><i class="fa fa-check"></i> Créer</button>
                                <a href="{{asset('listing_ordre?active=9&champ=2-1&choix=2')}}" wire:navigate class="btn btn-danger btn-sm"><i class="fa fa-close"></i> Fermer</a>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between gap-1"> 
                            <div class="d-flex align-items-center justify-content-between gap-1">                            
                                <a href="listing_cmd_clt?active=6&champ=1-1&choix=2" wire:navigate class="btn btn-sm btn-bleu d-none d-md-block ms-auto"><i class="fa fa-chevron-left"></i> Retour liste</a>
                            </div>
                        </div>
                    </div>                   
                    <div class="card-body no_bordure border-top taille_ecran_trans">
                        <div class="row mx-0 pt-0">
                            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12 px-1 mb-1 border-end border-bottom rounded-bottom">
                                <div class="hauteur_ecran">
                                    <div class="row pt-1 px-2 pb-0">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label for="libelle" class="col-md-5 col-sm-3 fw-bold col-form-label">Nomenclature</label>
                                            <div class="row mb-2">
                                                <div class="col-lg-10 col-md-10 col-sm-10">
                                                    <select id="nomenclatures" wire:model.live="nomenclatures" class="form-control form-select fw-bold bordure w-75 @error('nomenclatures') is-invalid @enderror">
                                                        <option value=""></option>	
                                                        @foreach($listeNomenclatur as $listeNomenclaturs)
                                                            <option value="{{$listeNomenclaturs->id}}">{{$listeNomenclaturs->code}} » {{Str::limit($listeNomenclaturs->libelle, 50)}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('nomenclatures') <span class="text-danger">{{ $message }}</span> @enderror 
                                                </div>
                                            </div>                                            
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">                                            
                                            <div class="row mb-1">
                                                <label for="inputPassword3" class="col-sm-3 fw-bold col-form-label">Type</label>
                                                <div class="col-sm-9">
                                                    <div class="card-body">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" wire:model.live="type_nomencla" value="Fabrication" name="inlineRadioOptions" id="inlineRadio1" value="option1">
                                                            <label class="form-check-label" for="inlineRadio1">Fabrication</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" wire:model.live="type_nomencla" value="Déassemblage" name="inlineRadioOptions" id="inlineRadio2" value="option2" checked="">
                                                            <label class="form-check-label" for="inlineRadio2">Déassemblage</label>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('type_nomencla') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-1">
                                                <label for="produit_a_fabrique" class="col-sm-3 col-form-label">Produit à fabriquer</label>
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
                                                <label for="quantite" class="col-sm-3 fw-bold col-form-label">Quantité à fabriquer</label>
                                                <div class="col-sm-4">
                                                    <input type="number" wire:model.defer="quantite" placeholder="Ex: 5" min="1" class="form-control bordure w-50 @error('quantite') is-invalid @enderror" id="quantite">
                                                </div>
                                                <div class="col-sm-4">                                                        
                                                    <select id="unite_mesure" wire:model.defer ="unite_mesure" class="form-control form-select bordure w-50 @error('unite_mesure') is-invalid @enderror">
                                                        <option value="Unité(s)">Unité(s)</option>
                                                        <option value="Kg">Kg</option>
                                                        <option value="g">g</option>
                                                        <option value="Km">Km</option>
                                                        <option value="m²">m²</option>
                                                        <option value="m³">m³</option>
                                                        <option value="L">L</option>
                                                        <option value="cL">cL</option>
                                                        <option value="mL">mL</option>
                                                    </select> 
                                                </div>                                                    
                                                <div class="d-flex justify-content-start">
                                                    @error('quantite') <span class="text-danger">{{ $message }}</span> @enderror 
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
                                                            <option value="{{$entrepots->id}}">{{Str::limit($entrepots->nom, 52)}}</option>
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
                                                            <option value="{{$tiers->id}}">{{Str::limit($tiers->nom, 52)}}</option>
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
                                                            <option value="{{$utilisateurs->id}}">{{Str::limit($utilisateurs->name, 52)}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('responsable') <span class="text-danger">{{ $message }}</span> @enderror 
                                                </div>
                                            </div>                                                                               
                                        </div>                                        
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="row mb-1 mt-2">
                                                <label for="note" class="col-sm-2 col-form-label" style="font-weight: 400;">Note</label>
                                                <div class="col-sm-10">                                                        
                                                    <textarea rows="2" wire:model="note" class="form-control bordure @error('note') is-invalid @enderror" id="note" placeholder="Ajouter une note..."></textarea>
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('note') <span class="text-danger">{{ $message }}</span> @enderror 
                                                </div>
                                            </div>                                            
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 px-0">
                                            @if($this->nomenclatures > 0)
                                                <div class="table-responsive mt-2">
                                                    <div class="table-responsive mt-1"> 
                                                        <ul class="nav nav-tabs border-0" role="tablist">
                                                            <li class="nav-item">
                                                                <a class="nav-link active" data-bs-toggle="tab" href="#home">Composants (<span class="text-vert"></span>)</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" data-bs-toggle="tab" href="#autre">Autre</a>
                                                            </li>
                                                        </ul>
                                                        <div class="tab-content table-responsive px-0" style="border-top-style: dashed; border-top-width: 1px;border-top-color: #0e04043d;">
                                                            <div id="home" class="tab-pane active">                               
                                                                <table class="table table-striped text-nowrap m-0"> 
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="fond_entete_table pointer" wire:click="setOrderField('composant')">Composant <i class="fa fa-arrow-down-short-wide"></i></th>
                                                                            <th class="fond_entete_table pointer text-center" wire:click="setOrderField('quantite')">Quantité <i class="fa fa-arrow-down-short-wide"></i></th>
                                                                            <th class="fond_entete_table pointer text-center" wire:click="setOrderField('quantite_consommer')">Consommé <i class="fa fa-arrow-down-short-wide"></i></th>
                                                                            <th class="fond_entete_table pointer text-center" wire:click="setOrderField('unite')">Unité <i class="fa fa-arrow-down-short-wide"></i></th>
                                                                            <th class="fond_entete_table pointer text-end" wire:click="setOrderField('cout')">Coût total <i class="fas fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" style="vertical-align: middle; cursor: help" aria-label="Le coût de production de cette nomenclature basé sur chaque quantité et produit à consommer (le Prix moyen pondéré (PMP) : prix d'achat)" data-bs-original-title="Le coût de production de cette nomenclature basé sur chaque quantité et produit à consommer (le Prix moyen pondéré (PMP) : prix d'achat)"></i> <i class="fa fa-arrow-down-short-wide"></i></th>
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
                                                                                <td class="text-center fw-bold">{{$listeComposants->quantite_consommer}}</td> 
                                                                                <td class="text-center fw-bold">{{$listeComposants->unite}}</td> 
                                                                                <td class="fw-bold text-end">{{$listeComposants->cout}}</td>
                                                                                <td class=""></td>
                                                                                <td class=""></td>
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
                                                                            <td colspan="10">
                                                                                @if($this->nomenclatures > 0)
                                                                                    <a href="#" class="btn btn-sm btn-white text-green-100" wire:click.prevent="ajoutLigne(1)" title="Cliquez pour ajouter" data-toggle="tooltip"> <i class="fa fa-plus"></i> Ajouter une ligne</a>
                                                                                    <a wire:click="onDataAjout()" class="btn btn-sm btn-white" title="Cliquez pour annuler"><i class="fa fa-close text-danger"></i></a>
                                                                                @endif
                                                                            </td>
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
                                            <h5 class="text-bleu"><i class="fas fa-calendar-alt" style=" color: #393b83;" title="Derniers événements liés"></i> @if($logCount > 1) Les {{$logCount}} derniers événements liés @else L'événement lié @endif</h5>
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
</div>



