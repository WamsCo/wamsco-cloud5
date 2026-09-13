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
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between border-bottom">
                        <div class="d-flex align-items-center justify-content-between gap-1">                                                       
                            <div class="d-flex align-items-center justify-content-between gap-1">                            
                                <button class="btn btn-sm btn-secondary" wire:click.prevent="store()" title="Cliquez pour modifier" data-toggle="tooltip"><i class="fa fa-check"></i> Enregistrer</button>
                                <a href="{{asset('listing_paie_divers?active=8&champ=1-3')}}" wire:navigate class="btn btn-sm btn-danger" title="Cliquez pour fermer" data-toggle="tooltip"><i class="fa fa-close"></i> Fermer</a>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between gap-1">                                                       
                            <div class="d-flex align-items-center justify-content-between gap-1">                            
                                <a href="{{asset('listing_paie_divers?active=8&champ=1-3')}}" wire:navigate class="btn btn-sm btn-bleu d-none d-md-block ms-auto"><i class="fa fa-chevron-left"></i> Retour liste</a>
                            </div>
                        </div>
                    </div>  
                    <div class="card-body no_bordure taille_ecran_trans">
                        <div class="row mx-0 pt-0">
                            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12 px-1 mb-1 border-end border-bottom rounded-bottom">
                                <div class="hauteur_ecran">
                                    <div class="row pt-1 px-2 pb-2">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label for="libele_paiement" class="col-md-5 col-sm-3 fw-semibold col-form-label">Paiement divers</label>
                                            <div class="row mb-1">
                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                    <div class="row mb-1">
                                                        {{-- <label for="libele_paiement" class="col-sm-2 fw-bold col-form-label">Libellé</label> --}}
                                                        <div class="col-lg-10 col-md-10 col-sm-10">
                                                            <input type="text" wire:model="libele_paiement" placeholder="Ex: Achat ordinateur" style=" color: #393b83;" class="form-control fw-semibold bordure fs-3 px-0 w-100 @error('libele_paiement') is-invalid @enderror" id="libele_paiement">
                                                        </div>
                                                        <div class="d-flex justify-content-start">
                                                            @error('libele_paiement') <span class="text-danger">{{ $message }}</span> @enderror 
                                                        </div>
                                                    </div>                                                    
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('client') <span class="text-danger">{{$message}}</span> @enderror 
                                                </div>
                                            </div>                                            
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12"> 
                                            <div class="row mb-1">
                                                <label for="reference" class="col-lg-3 col-md-3 col-sm-4 fw-bold col-form-label">Réference</label>
                                                <div class="col-lg-9 col-md-9 col-sm-8">
                                                    <input type="text" wire:model="reference" placeholder="Ex: PD00-01" class="form-control bordure w-75 @error('reference') is-invalid @enderror" id="reference">
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('reference') <span class="text-danger">{{ $message }}</span> @enderror 
                                                </div>
                                            </div>                                           
                                            <div class="row mb-1">
                                                <label for="date_paiement" class="col-lg-5 col-md-5 col-sm-4 fw-bold col-form-label">Date paiement</label>
                                                <div class="col-lg-7 col-md-7 col-sm-8">
                                                    <input type="date" wire:model="date_paiement" class="form-control bordure w-50 @error('date_paiement') is-invalid @enderror" id="date_paiement">
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('date_paiement') <span class="text-danger">{{ $message }}</span> @enderror 
                                                </div>
                                            </div>
                                            <div class="row mb-1">
                                                <label for="date_valeur" class="col-lg-5 col-md-5 col-sm-4 fw-bold col-form-label">Date valeur</label>
                                                <div class="col-lg-7 col-md-7 col-sm-8">
                                                    <input type="date" wire:model="date_valeur"  class="form-control bordure w-50 @error('date_valeur') is-invalid @enderror" id="date_valeur">
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('date_valeur') <span class="text-danger">{{ $message }}</span> @enderror 
                                                </div>
                                            </div>  
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <div class="row mb-1">
                                                <label for="nom_compte_bancaire" class="col-lg-5 col-md-5 col-sm-4 fw-bold col-form-label">Compte bancaire</label>
                                                <div class="col-lg-7 col-md-7 col-sm-8">                                                        
                                                    <select id="nom_compte_bancaire" wire:model="nom_compte_bancaire" class="form-control form-select bordure w-75 @error('nom_compte_bancaire') is-invalid @enderror" id="nom_compte_bancaire">
                                                        <option value=""></option>	
                                                        @foreach($compteBancaire as $compteBancaires)
                                                            <option value="{{$compteBancaires->id}}">{{$compteBancaires->nom_compte_bancaire}}</option>	
                                                        @endforeach
                                                    </select> 
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('nom_compte_bancaire') <span class="text-danger">{{ $message }}</span> @enderror 
                                                </div>
                                            </div>
                                            <div class="row mb-1">
                                                <label for="mode_reglement" class="col-lg-5 col-md-5 col-sm-4 fw-bold col-form-label">Mode de règlement</label>
                                                <div class="col-lg-7 col-md-7 col-sm-8">                                                        
                                                    <select id="mode_reglement" wire:model="mode_reglement" class="form-control form-select bordure w-100 @error('mode_reglement') is-invalid @enderror" id="mode_reglement">
                                                        <option value=""></option>	
                                                        <option value="Carte bancaire">Carte bancaire</option>	
                                                        <option value="Chèque">Chèque</option>
                                                        <option value="Espèce">Espèce</option>
                                                        <option value="Ordre de prélèvement">Ordre de prélèvement</option>
                                                        <option value="Virement bancaire">Virement bancaire</option>
                                                        <option value="Versement bancaire">Versement bancaire</option>
                                                        <option value="Orange Money">Orange Money</option>
                                                        <option value="MTN Mobile Money">MTN Mobile Money</option>
                                                    </select> 
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('mode_reglement') <span class="text-danger">{{ $message }}</span> @enderror 
                                                </div>
                                            </div>
                                            <div class="row mb-1">
                                                <label for="sens" class="col-lg-3 col-md-3 col-sm-3 fw-bold col-form-label">Sens <i class="fas fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" style="vertical-align: middle; cursor: help" title="{{$infos}}"></i></label>
                                                <div class="col-lg-9 col-md-9 col-sm-9">                                                        
                                                    <select id="sens" wire:model="sens" class="form-control fw-semibold form-select text-danger bordure w-50 @error('sens') is-invalid @enderror">
                                                        <option value=""></option>	
                                                        <option value="Débit">Débit (Retirer)</option>
                                                        <option value="Crédit">Crédit (Ajouter)</option>
                                                    </select> 
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('sens') <span class="text-danger">{{ $message }}</span> @enderror 
                                                </div>
                                            </div> 
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="row mb-1">
                                                <label for="montant" class="col-lg-3 col-md-3 col-sm-4 fw-bold col-form-label">Montant <span class="fw-semibold text-bleu" style="font-size: 10px">{{$this->devise}}</span></label>
                                                <div class="col-lg-9 col-md-9 col-sm-8">
                                                    <input type="text" wire:model="montant" placeholder="Ex: 10000" style=" color: #393b83;font-size: 13px;" class="form-control fw-semibold bordure w-25 @error('montant') is-invalid @enderror" id="montant">
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('montant') <span class="text-danger">{{ $message }}</span> @enderror 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="row mb-1">
                                                <label for="note" class="col-sm-2 col-form-label" style="font-weight: 400;">Note</label>
                                                <div class="col-sm-10">                                                        
                                                    <textarea rows="2" wire:model="note" class="form-control bordure @error('note') is-invalid @enderror" id="note" placeholder="Ajouter une note..."></textarea>
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('note') <span class="text-danger">{{ $message }}</span> @enderror 
                                                </div>
                                            </div>                                            
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="table-responsive mt-2"> 
                                                <ul class="nav nav-tabs border-0" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" data-bs-toggle="tab" href="#home">Chèque </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-bs-toggle="tab" href="#autre">Autre</a>
                                                    </li>
                                                </ul>
                                                <div class="tab-content table-responsive border-topk rounded-0" style="border-top-style: dashed; border-top-width: 1px;border-top-color: #0e04043d;">
                                                    <div id="home" class="tab-pane active">                               
                                                         <div class="row mt-2">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <div class="w_horizontal_separator mt-3 mb-3 text-bleu text-uppercase fw-bold small w-25">Autre Information</div>
                                                                <div class="row mb-1">
                                                                    <label for="numero_cheque_virement" class="col-lg-5 col-md-5 col-sm-5 col-form-label">Numéro (Chèque/Virement N°)</label>
                                                                    <div class="col-lg-7 col-md-7 col-sm-7">
                                                                        <input type="text" wire:model="numero_cheque_virement" placeholder="Ex: A5644" class="form-control bordure w-75 @error('numero_cheque_virement') is-invalid @enderror" id="numero_cheque_virement">
                                                                    </div>
                                                                    <div class="d-flex justify-content-start">
                                                                        @error('numero_cheque_virement') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-1">
                                                                    <label for="emetteur" class="col-lg-5 col-md-5 col-sm-5 col-form-label">Émetteur (Émetteur du chèque/virement)</label>
                                                                    <div class="col-lg-7 col-md-7 col-sm-7">
                                                                        <input type="text" wire:model="emetteur" placeholder="Ex: John Doe" class="form-control bordure w-75 @error('emetteur') is-invalid @enderror" id="emetteur">
                                                                    </div>
                                                                    <div class="d-flex justify-content-start">
                                                                        @error('emetteur') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-1">
                                                                    <label for="nom_banque" class="col-lg-5 col-md-5 col-sm-4 col-form-label">Banque (Banque du chèque)</label>
                                                                    <div class="col-lg-7 col-md-7 col-sm-8">
                                                                        <input type="text" wire:model="nom_banque" placeholder="Nom de la banque" class="form-control bordure w-75 @error('nom_banque') is-invalid @enderror" id="nom_banque">
                                                                    </div>
                                                                    <div class="d-flex justify-content-start">
                                                                        @error('nom_banque') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                    </div>
                                                                </div>                                                                                                                       
                                                            </div>
                                                         </div>                                           
                                                    </div>
                                                    <div id="autre" class="tab-pane">              
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


