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
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title text-bleu"><i class="fa fa-shapes text-danger"></i> {{$title_fils}} » <span class="text-vert">{{$code}}</span>&nbsp;&nbsp;</h5>
                        <div class="d-flex align-items-center justify-content-between gap-1">
                            <div class="d-flex align-items-center justify-content-between gap-1"> 
                                {{-- <a href="facturationclt-pdf?id={{$this->ids}}&code={{$this->reference}}&format=ticket" class="btn btn-sm btn-default" title="Cliquez pour imprimer" data-toggle="tooltip" target="_blank" style="color: #161a1d;"><i class="fa fa-print"></i>Ticket</a> --}}
                                {{-- <a href="facturationclt-pdf?id={{$this->ids}}&code={{$this->reference}}&format=A4" class="btn btn-sm btn-default" title="Cliquez pour imprimer" data-toggle="tooltip" target="_blank"><i class="fa fa-print"></i>A4</a> --}}
                            </div>                            
                            <div class="d-flex align-items-center justify-content-between gap-1">                            
                                <a href="listing_nomencla?active=9&champ=1-1&choix=2" wire:navigate class="btn btn-sm btn-bleu d-none d-md-block ms-auto"><i class="fa fa-chevron-left"></i> Retour liste</a>
                                <button class="btn btn-sm btn-default new_color" wire:click.prevent="precedant({{$this->ids}})" title="Précédant" data-toggle="tooltip"><i class="fa fa-chevron-left"></i></button>
                                <a href="#" class="btn btn-sm btn-default new_color" wire:click.prevent="suivant({{$this->ids}})" title="Suivant" data-toggle="tooltip"><i class="fa fa-chevron-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body no_bordure border-top rounded-3 taille_ecran_trans">
                        <div class="row mx-0 pt-0">
                            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12 px-0 mb-0 border-end border-bottomk rounded-bottom">  
                                <form>
                                    <div class="row px-3">
                                        <div class="col-sm-8"> 
                                            <div class="row mb-1 mt-3">
                                                <label for="libelle" class="col-sm-3 fw-bold col-form-label">Libellé</label>
                                                <div class="col-sm-9">
                                                    <input type="text" wire:model.defer="libelle" placeholder="Ex: Fabrication pain" class="form-control bordure w-50 @error('libelle') is-invalid @enderror" id="libelle">
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('libelle') <span class="text-danger">{{ $message }}</span> @enderror 
                                                </div>
                                            </div> 
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
                                                <label for="produit_a_fabrique" class="col-sm-4 fw-bold col-form-label">Produit à fabriquer</label>
                                                <div class="col-sm-8">   
                                                    <select id="produit_a_fabrique" wire:model.defer="produit_a_fabrique" class="form-control form-select bordure w-auto @error('produit_a_fabrique') is-invalid @enderror">
                                                        <option value=""></option>	
                                                        @foreach($produit as $produits)
                                                            <option value="{{$produits->id}}">{{$produits->nom_produit}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('produit_a_fabrique') <span class="text-danger">{{ $message }}</span> @enderror 
                                                </div>
                                            </div>
                                            <div class="">                                            
                                                <form>
                                                    <div class="row mb-1">
                                                        <label for="quantite" class="col-sm-4 fw-bold col-form-label">Quantité à fabriquer</label>
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
                                                </form>
                                            </div>
                                            <div class="row mb-1">
                                                <label for="duree" class="col-sm-5 fw-bold col-form-label">Durée estimée (H:min)</label>
                                                <div class="col-sm-7">
                                                    <input type="time" wire:model.defer="duree" min="1" class="form-control bordure w-auto @error('duree') is-invalid @enderror" id="duree">
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('duree') <span class="text-danger">{{ $message }}</span> @enderror 
                                                </div>
                                            </div>
                                            <div class="row mb-1">
                                                <label for="entrepot_fabrication" class="col-sm-5 fw-bold col-form-label">Entrepôt de fabrication</label>
                                                <div class="col-sm-7">   
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
                                                <label for="inputPassword3" class="col-sm-3 col-form-label">Etat</label>
                                                <div class="col-sm-9">
                                                    <div class="card-body">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" wire:model.live="etat" value="1" name="inlineRadioOptionsEtat" id="inlineRadio3" value="option3">
                                                            <label class="form-check-label" for="inlineRadio3">Activer</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" wire:model.live="etat" value="0" name="inlineRadioOptionsEtat" id="inlineRadio4" value="option4" checked="">
                                                            <label class="form-check-label" for="inlineRadio4">Désactiver</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('etat') <span class="text-danger">{{ $message }}</span> @enderror 
                                                </div>
                                            </div>                                   
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="row mt-3">
                                                <label for="description" class="col-sm-5 text-end col-form-label">Description</label>
                                                <div class="col-sm-7">                                                        
                                                    <textarea rows="5" wire:model="description" class="form-control @error('description') is-invalid @enderror" id="description" placeholder="description..."></textarea>
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('description') <span class="text-danger">{{ $message }}</span> @enderror 
                                                </div>
                                            </div>
                                        </div>                                            
                                    </div> 
                                </form>                 
                                <div class="border-top border-bottomk py-3 px-3">
                                    <button  wire:click="update()" class="btn btn-secondary btn-sm fw-bold"><i class="fa fa-check"></i> Enregistrer</button>
                                    <a href="{{asset('listing_nomencla?active=9&champ=1-1&choix=2')}}" wire:navigate class="btn btn-sm btn-danger btn-sm"><i class="fa fa-close"></i> Fermer</a>
                                    @if($approuver === 3)                                                               
                                        <a wire:click.prevent="ecraser()" class="btn btn-outline-dark btn-sm bg-dark text-white blink"  title="Cliquez pour confirmer la suppression" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-trash"> Confirmer ?</i></a>
                                    @else
                                        <a wire:click.prevent="confirmerEcraser(3)" class="btn btn-outline-muted btn-sm"  title="Cliquez pour supprimer" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-trash"></i></a>
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="table-responsive mt-2 mb-0">
                                            {{-- <h5 class="py-3" style=" color: #393b83;"><i class="fas fa-dolly text-bleu" title="Mouvement de stock"></i> Ligne composant <span></span></h5> --}}
                                            <div class="table-responsive mt-1"> 
                                                <ul class="nav nav-tabs margin_ajuste border-0 pb-1" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" data-bs-toggle="tab" href="#home">Composants</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#autre">Autre</a>
                                                    </li>
                                                </ul>
                                                <div class="tab-content table-responsive border-top rounded-end">
                                                    <div id="home" class="tab-pane active">                               
                                                        <table class="table table-striped text-nowrap m-0"> 
                                                            <thead>
                                                                <tr>
                                                                    <th class="fond_entete_table pointer" wire:click="setOrderField('composant')">Composant (<span class="text-vert">{{$listeComposantCount}}</span>) <i class="fa fa-arrow-down-short-wide"></i></th>
                                                                    <th class="fond_entete_table pointer text-center" wire:click="setOrderField('quantite')">Quantité <i class="fa fa-arrow-down-short-wide"></i></th>
                                                                    <th class="fond_entete_table pointer text-center" wire:click="setOrderField('unite')">Unité <i class="fa fa-arrow-down-short-wide"></i></th>
                                                                    <th class="fond_entete_table pointer text-end" wire:click="setOrderField('cout')">Coût total <i class="fas fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" style="vertical-align: middle; cursor: help" title="Le coût de production de cette nomenclature est basé sur chaque quantité et produit à consommer (le Prix moyen pondéré (PMP) : prix d'achat)"></i> <i class="fa fa-arrow-down-short-wide"></i></th>
                                                                    <th class="fond_entete_table pointer"></th>
                                                                </tr>
                                                                </thead>                                       
                                                            <tbody>
                                                                @foreach($listeComposant as $listeComposants)
                                                                    <tr> 
                                                                        <td class="fw-bold"><a href="detail_product?id={{$listeComposants->composant_id}}&active=4&champ=1-1&choix=2" wire:navigate class="text-bleu fw-bold">{{$listeComposants->composant}}</a></td>                                                         
                                                                        <td class="text-center fw-bold">{{$listeComposants->quantite}}</td> 
                                                                        <td class="text-center fw-bold">{{$listeComposants->unite}}</td> 
                                                                        <td class="fw-bold text-end">{{number_format($listeComposants->cout,0,',',' ')}}</td>
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
                                                                        @include('livewire.fabrication.ajout_ligne_composant')     
                                                                    </tr>
                                                                @endif
                                                                {{-- @if($etat == 'Brouillon') --}}
                                                                    <tr style="font-size: 12px;">
                                                                        <td colspan="10">
                                                                            {{-- @if($etat_expedi != 'Clôturée') --}}
                                                                                <a href="#" class="btn btn-sm btn-white text-green-100" wire:click.prevent="ajoutLigne(1)" title="Cliquez pour ajouter" data-toggle="tooltip"> <i class="fa fa-plus"></i> Ajouter une ligne</a>
                                                                                <a wire:click="onDataAjout()" class="btn btn-sm btn-white" title="Cliquez pour annuler"><i class="fa fa-close text-danger"></i></a>
                                                                            {{-- @endif --}}
                                                                        </td>
                                                                    </tr>
                                                                {{-- @endif --}}
                                                            </tbody>
                                                        </table>                                           
                                                    </div>
                                                    <div id="autre" class="tab-pane">                               
                                                        <table class="table table-striped table-hover text-nowrap m-0"> 
                                                            <thead>
                                                                <tr>
                                                                </tr>
                                                            </thead>                                       
                                                            <tbody>                                                        
                                                                <tr>                                                             
                                                                </tr> 
                                                            </tbody>
                                                        </table>                                           
                                                    </div>
                                                </div>                    
                                            </div>
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


