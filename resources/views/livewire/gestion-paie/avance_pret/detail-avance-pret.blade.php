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
                                <button type="submit" wire:click.prevent="update()" class="btn btn-sm btn-secondary fw-bold"><i class="fa fa-check"></i> Modifier</button>                           
                                <a href="{{asset('liste_avance?active=15&champ=2-1&choix=2')}}" wire:navigate class="btn btn-sm btn-danger" title="Cliquez pour fermer" data-toggle="tooltip"><i class="fa fa-close"></i> Fermer</a>
                                @if($confirmer === $this->ids)                                                               
                                    <a wire:click.prevent="supprimer({{$this->ids}})" class="btn btn-sm btn-outline-danger bg-danger text-white blink" style="font-size:11px;"  title="Cliquez pour confirmer la suppression" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-trash"> Confirmer ?</i></a>
                                @else
                                    <a wire:click.prevent="confirmerDelete({{$this->ids}})" class="btn btn-sm btn-outline-muted"  title="Cliquez pour supprimer" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-trash"></i></a>
                                @endif 
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between gap-1">                                                       
                           <div class="d-flex align-items-center justify-content-between gap-1"> 
                                {{-- <a href="impcmdclt-pdf?id={{$this->ids}}&code={{$this->reference}}&format=ticket" class="btn btn-sm btn-default" title="Cliquez pour imprimer" data-toggle="tooltip" target="_blank" style="color: #161a1d;"><i class="fa fa-print"></i>Ticket</a> --}}
                                {{-- <a href="impcmdclt-pdf?id={{$this->ids}}&code={{$this->reference}}&format=A4" target="_blank" class="btn btn-sm btn-default" title="Cliquez pour imprimer" data-toggle="tooltip"><i class="fa fa-print"></i>A4</a> --}}
                            </div>                            
                            <div class="d-flex align-items-center justify-content-between gap-1">                            
                                <a href="{{asset('liste_avance?active=15&champ=2-1&choix=2')}}" wire:navigate class="btn btn-sm btn-bleu d-none d-md-block ms-auto"><i class="fa fa-chevron-left"></i> Retour liste</a>
                                <button class="btn btn-sm btn-default new_color" wire:click.prevent="precedant({{$this->ids}})" title="Précédant" data-toggle="tooltip"><i class="fa fa-chevron-left"></i></button>
                                <a href="#" class="btn btn-sm btn-default new_color" wire:click.prevent="suivant({{$this->ids}})" title="Suivant" data-toggle="tooltip"><i class="fa fa-chevron-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-header d-flex align-items-center justify-content-between py-1">
                        <div class="d-flex align-items-center justify-content-between gap-1">
                            <div class="d-flex align-items-center justify-content-between gap-1"> 
                                <a href="nouveau_avance?active=15&champ=2-1&choix=1" wire:navigate class="btn btn-sm btn-default text-bleu border fw-semibold ms-auto"><i class="fa fa-plus-circle"></i> Nouvelle avance</a>
                                {{-- <button type="submit" wire:click.prevent="valider()" class="btn btn-sm btn-default text-bleu fw-bold"><i class="fa fa-plus-circle"></i>  Nouveau Ticket</button>                                                                --}}
                                {{-- <button type="submit" wire:click.prevent="brouillon()" class="btn btn-sm btn-default text-bleu fw-bold"><i class="fa fa-recycle"></i> Retour Brouillon</button> --}}
                                {{-- <a href="#" wire:click.prevent="expedition()" class="btn btn-sm btn-white text-bleu border fw-semibold ms-auto" title="Cliquez pour créer une expedition" data-toggle="tooltip"><i class="fas fa-dolly"></i> Créer expédition</a> --}}
                            </div> 
                        </div>                            
                        <div class="d-flex align-items-center justify-content-between gap-1">
                            <div class="statusref">
                                @if($etat == 1)
                                    <span class="badge bg-success" title="Etat » Activer"><i class="fa fa-check-circle"></i> Activer</span>                                 
                                @elseif($etat == 0)
                                    <span class="badge bg-danger" title="Etat » Désactiver"><i class="fa fa-times-circle blink"></i> Désactiver</span>  
                                @endif
                            </div>
                        </div>
                    </div>  
                    <div class="card-body no_bordure border-top taille_ecran">
                        <div class="row mx-0 pt-0">
                            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12 px-1 mb-1 border-end border-bottom rounded-bottom">
                                <div class="hauteur_ecran">
                                    <div class="row pt-1 px-2 pb-2">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label for="nom_opportunite" class="col-md-5 col-sm-3 fw-semibold col-form-label">Détails avance ou prêt » <span class="text-danger">AV-{{$this->ids}}</span> </label>
                                            <div class="row mb-3">
                                                <div class="col-lg-10 col-md-10 col-sm-10">
                                                    <input type="search" name="utilisateur" wire:model.live="utilisateur" wire:keyup="searchResult" placeholder="Rechercher par nom A-Z ou numéro 0-9" class="form-control bordure fs-3 px-0 w-100 @error('client') is-invalid @enderror" id="client"/>
                                                    <div class="bloc_search_client" style="position: absolute;">                       
                                                        @if($showdiv)
                                                            @if($recordCount > 0)
                                                                <table class="table table-hover mb-0 text-nowrap w-75" style="overflow: auto; background:#f7f7f7;">                                                        
                                                                    <tbody>
                                                                        @if(!empty($records))                                                                
                                                                            @foreach($records as $record)
                                                                                <tr>                                         
                                                                                    <td class="pointer fw-semibold" wire:click="ajouterTier({{$record->id}})"><i class="fas fa-user-circle"></i> {{Str::limit($record->name, 52)}} » <span style="color: #8fbc8f; font-weight:600">{{Str::limit($record->telephone, 42)}}</span></td>   
                                                                                </tr>                                        
                                                                            @endforeach
                                                                            <tr>                                         
                                                                                <td class="pointer fw-semibold text-danger"><a href="nouveau_utilisateur?active=12&champ=1-1" target="_blank" class="text-danger"><i class="fas fa-user-plus"></i> Créer utilisateur</a></td>  
                                                                            </tr> 
                                                                        @endif                               
                                                                    </tbody>                                        
                                                                </table>
                                                                @endif	
                                                        @endif	
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('utilisateur') <span class="text-danger">{{$message}}</span> @enderror 
                                                </div>
                                            </div>                                             
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <div class="row mb-1">
                                                <label for="type_pret" class="col-lg-4 col-md-5 col-sm-3 fw-bold col-form-label">Type de prêt</label>
                                                <div class="col-lg-8 col-md-7 col-sm-9">                                                        
                                                    <select id="type_pret" wire:model="type_pret" class="form-control form-select bordure w-75 @error('type_pret') is-invalid @enderror">
                                                        <option value=""></option>
                                                        <option value="Avance Salaire">Avance Salaire</option>   
                                                        <option value="Crédit Scolaire">Crédit Scolaire</option>	
                                                        <option value="Notes de Débit">Notes de Débit</option>	
                                                        <option value="Prêt Exceptionnel">Prêt Exceptionnel</option>	
                                                        <option value="Assurance Maladie">Assurance Maladie</option>	
                                                    </select> 
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('type_pret') <span class="text-danger">{{ $message }}</span> @enderror 
                                                </div>
                                            </div>
                                            <div class="row mb-1">
                                                <label for="libelle" class="col-lg-4 col-md-5 col-sm-3 fw-bold col-form-label">Libellé</label>
                                                <div class="col-lg-8 col-md-7 col-sm-9">
                                                    <input type="text" wire:model="libelle" placeholder="Ex: Crédit Scolaire" class="form-control bordure w-100 @error('libelle') is-invalid @enderror" id="libelle">
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('libelle') <span class="text-danger">{{ $message }}</span> @enderror 
                                                </div>
                                            </div>
                                                <div class="row mb-1">
                                                <label for="montant" class="col-lg-4 col-md-5 col-sm-3 fw-bold col-form-label">Montant</label>
                                                <div class="col-lg-8 col-md-7 col-sm-9">
                                                    <input type="number" wire:model="montant" min="0" placeholder="Ex: 35000" class="form-control bordure w-75 @error('montant') is-invalid @enderror" id="montant">
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('montant') <span class="text-danger">{{ $message }}</span> @enderror 
                                                </div>
                                            </div>
                                            <div class="row mb-1">
                                                <label for="nombre_tranche" class="col-lg-5 col-md-5 col-sm-3 fw-bold col-form-label">Nombre tranche</label>
                                                <div class="col-lg-7 col-md-7 col-sm-9">
                                                    <input type="number" wire:model="nombre_tranche" min="0" placeholder="Ex: 2" class="form-control bordure w-50 @error('nombre_tranche') is-invalid @enderror" id="nombre_tranche">
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('nombre_tranche') <span class="text-danger">{{ $message }}</span> @enderror 
                                                </div>
                                            </div>                                                                                                
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">  
                                            <div class="row mb-1">
                                                <label for="telephone" class="col-lg-4 col-md-4 col-sm-3 fw-semibold col-form-label">Téléphone</label>
                                                <div class="col-lg-8 col-md-8 col-sm-9">
                                                    <input type="text" wire:model="telephone" placeholder="" class="form-control bordure w-100 @error('telephone') is-invalid @enderror" id="telephone">
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('telephone') <span class="text-danger">{{ $message }}</span> @enderror 
                                                </div>
                                            </div>
                                            <div class="row mb-1">
                                                <label for="mode_reglement" class="col-lg-5 col-md-5 col-sm-4 fw-bold col-form-label">Mode de règlement</label>
                                                <div class="col-lg-7 col-md-7 col-sm-8">                                                        
                                                    <select id="mode_reglement" wire:model="mode_reglement" class="form-control form-select bordure w-75 @error('mode_reglement') is-invalid @enderror" id="mode_reglement">
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
                                                <label for="date_paiement" class="col-lg-4 col-md-4 col-sm-4 col-form-label fw-semibold">Date paiement</label>
                                                <div class="col-lg-8 col-md-8 col-sm-8">
                                                    <input type="date" wire:model="date_paiement" class="form-control bordure text-danger w-75 @error('date_paiement') is-invalid @enderror" id="date_paiement">
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('date_paiement') <span class="text-danger">{{ $message }}</span> @enderror 
                                                </div>
                                            </div>
                                            <div class="row mb-1">
                                                <label for="inputPassword3" class="col-sm-3 col-form-label">Etat</label>
                                                <div class="col-sm-9">
                                                    <div class="card-body">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" wire:model.live="etat" value="1" name="inlineRadioOptions" id="inlineRadio1" value="option1">
                                                            <label class="form-check-label" for="inlineRadio1">Activer</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" wire:model.live="etat" value="0" name="inlineRadioOptions" id="inlineRadio2" value="option2" checked="">
                                                            <label class="form-check-label" for="inlineRadio2">Désactiver</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('etat') <span class="text-danger">{{ $message }}</span> @enderror 
                                                </div>
                                            </div>                                                                                                                              
                                        </div>                                       
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 px-0">
                                            <div class="table-responsive mt-2">
                                                <div class="table-responsive mt-1"> 
                                                    <ul class="nav nav-tabs border-0" role="tablist">
                                                        <li class="nav-item">
                                                            <a class="nav-link active" data-bs-toggle="tab" href="#home">Note</a>
                                                        </li>
                                                        {{-- <li class="nav-item">
                                                            <a class="nav-link" data-bs-toggle="tab" href="#autre">Autres</a>
                                                        </li> --}}
                                                    </ul>
                                                    <div class="tab-content table-responsive border-topk px-3" style="border-top-style: dashed; border-top-width: 1px;border-top-color: #0e04043d;">
                                                        <div id="home" class="tab-pane active">
                                                            <div class="row mt-2">
                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                    <div class="row mb-1">
                                                                        {{-- <label for="note" class="col-lg-2 col-md-3 col-sm-4 col-form-label">note</label> --}}
                                                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                                                            <textarea rows="5" id="note" wire:model.defer="note" class="form-control bordure px-0 w-100 @error('note') is-invalid @enderror" placeholder="Ajouter une note..."></textarea>
                                                                        </div>
                                                                        <div class="d-flex justify-content-start">
                                                                            @error('note') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                        </div>
                                                                    </div>                                                                  
                                                                </div>                                                                                                                                        
                                                            </div>                                                                
                                                        </div>
                                                        {{-- <div id="autre" class="tab-pane">                                                                        
                                                        </div> --}}
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
    {{-- Modal --}}
   {{-- @if($this->type_produit == 'Service')
        @include('livewire.gestion-facturation.client.selection_ligne_service_facture')
   @else
        @include('livewire.gestion-facturation.client.selection_ligne_produit_facture') 
   @endif  --}}
</div>





