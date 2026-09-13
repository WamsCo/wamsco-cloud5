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
                               <button  wire:click="store()" class="btn btn-secondary btn-sm fw-bold"><i class="fa fa-check"></i> Valider</button>
                               <a href="{{asset('listing_nomencla?active=9&champ=1-1&choix=2')}}" wire:navigate class="btn btn-danger btn-sm"><i class="fa fa-close"></i> Fermer</a>
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
                                            <label for="libelle" class="col-md-5 col-sm-3 fw-semibold col-form-label">Nomenclature</label>
                                            <div class="row mb-2">
                                                <div class="col-lg-10 col-md-10 col-sm-10">
                                                    <input type="text" name="libelle" wire:model.defer="libelle" placeholder="Ex: Fabrication du pain" class="form-control bordure fs-3 px-0 w-100 @error('libelle') is-invalid @enderror" id="libelle"/>
                                                </div>
                                                 <div class="d-flex justify-content-start">
                                                    @error('libelle') <span class="text-danger">{{ $message }}</span> @enderror 
                                                </div>
                                            </div>                                            
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">  
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
                                                            <select id="unite_mesure" wire:model.defer ="unite_mesure" class="form-control form-select bordure w-75 @error('unite_mesure') is-invalid @enderror">
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
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="row mb-1 mt-2">
                                                <label for="description" class="col-sm-2 col-form-label" style="font-weight: 400;">Note</label>
                                                <div class="col-sm-10">                                                        
                                                    <textarea rows="2" wire:model="description" class="form-control bordure @error('description') is-invalid @enderror" id="description" placeholder="Ajouter une note..."></textarea>
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('description') <span class="text-danger">{{ $message }}</span> @enderror 
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



