<div>
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
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center justify-content-between gap-1">                                                       
                            <div class="d-flex align-items-center justify-content-between gap-1">                            
                                <button class="btn btn-sm btn-secondary" wire:click.prevent="store()" title="Cliquez pour modifier" data-toggle="tooltip"><i class="fa fa-check"></i> Créer</button>
                                <a href="{{asset('liste_ticket?active=13&champ=1-2')}}" class="btn btn-sm btn-danger" title="Cliquez pour fermer" data-toggle="tooltip"><i class="fa fa-close"></i> Fermer</a>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between gap-1">                                                       
                            <div class="d-flex align-items-center justify-content-between gap-1">                            
                                <a href="{{asset('liste_ticket?active=13&champ=1-2')}}" class="btn btn-sm btn-bleu d-none d-md-block ms-auto"><i class="fa fa-chevron-left"></i> Retour liste</a>
                                {{-- <button class="btn btn-sm btn-default new_color" wire:click.prevent="precedant({{$this->ids}})" title="Précédant" data-toggle="tooltip"><i class="fa fa-chevron-left"></i></button> --}}
                                {{-- <a href="#" class="btn btn-sm btn-default new_color" wire:click.prevent="suivant({{$this->ids}})" title="Suivant" data-toggle="tooltip"><i class="fa fa-chevron-right"></i></a> --}}
                            </div>
                        </div>
                    </div>
                    <div class="card-body no_bordure border-top taille_ecran_trans">                        
                        <div class="container-fluid pt-0 px-0">
                            <div class="row mx-0 pt-0">
                                <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12 mb-1 border-end border-bottom rounded-bottom">
                                    <div class="hauteur_ecran">
                                        <div class="row pt-1 px-2 pb-2">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label for="nom_ticket" class="col-md-5 col-sm-3 fw-semibold col-form-label">Nouveau ticket</label>
                                                <div class="row mb-1">
                                                    {{-- <label for="nom_ticket" class="col-lg-2 col-md-5 col-sm-3 fw-bold fs-5 col-form-label">Opportunité</label> --}}
                                                    <div class="col-lg-10 col-md-10 col-sm-10">
                                                        <input type="text" wire:model="nom_ticket" placeholder="par ex. Problème de facturation" class="form-control bordure fs-3 w-100 px-0 @error('nom_ticket') is-invalid @enderror" id="nom_ticket">
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('nom_ticket') <span class="text-danger">{{$message}}</span> @enderror 
                                                    </div>
                                                </div>                                            
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">                                                                                   
                                                <div class="row mb-1">
                                                    <label for="reference" class="col-lg-4 col-md-5 col-sm-3 fw-bold col-form-label">Référence</label>
                                                    <div class="col-lg-8 col-md-7 col-sm-9">
                                                        <input type="text" wire:model="reference" placeholder="Ex: TK-001" class="form-control bordure w-100 @error('reference') is-invalid @enderror" id="reference">
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('reference') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <label for="type_demande" class="col-lg-4 col-md-5 col-sm-3 fw-bold col-form-label">Type demande</label>
                                                    <div class="col-lg-8 col-md-7 col-sm-9">                                                        
                                                        <select id="type_demande" wire:model="type_demande" class="form-control form-select bordure w-100 @error('type_demande') is-invalid @enderror">
                                                            <option value=""></option>
                                                            <option value="Support Technique">Support Technique</option>   
                                                            <option value="Question ou bug">Question ou bug</option>	
                                                            <option value="Demande d'aide fonctionnelle">Demande d'aide fonctionnelle</option>	
                                                        </select> 
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('type_demande') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <label for="assignation" class="col-lg-4 col-md-5 col-sm-3 fw-bold col-form-label">Assigné à</label>
                                                    <div class="col-lg-8 col-md-7 col-sm-9">                                                        
                                                        <select id="assignation" wire:model="assignation" class="form-control form-select bordure w-100 @error('assignation') is-invalid @enderror">
                                                            <option value=""></option>    
                                                            @foreach($listUser as $listUsers)
                                                                <option value="{{$listUsers->id}}">{{$listUsers->name}}</option>
                                                            @endforeach	
                                                        </select> 
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('assignation') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <label for="priorite" class="col-lg-4 col-md-5 col-sm-3 fw-bold col-form-label">Priorité</label>
                                                    <div class="col-lg-8 col-md-7 col-sm-9">                                                        
                                                        <select id="priorite" wire:model="priorite" class="form-control form-select bordure w-50 @error('priorite') is-invalid @enderror">
                                                            <option value=""></option>   
                                                            <option value="Faible">Faible</option>   
                                                            <option value="Moyen">Moyen</option> 
                                                            <option value="Élevé">Élevé</option> 
                                                            <option value="Urgent">Urgent</option> 
                                                        </select> 
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('priorite') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>
                                                @if(auth()->user()->societe == "Administration")
                                                    <div class="row mb-1">
                                                        <label for="societe" class="col-lg-4 col-md-5 col-sm-3 fw-bold col-form-label">Entité</label>
                                                        <div class="col-lg-8 col-md-7 col-sm-9">                                                        
                                                            <select id="societe" wire:model="societe" class="form-control form-select bordure w-75 @error('societe') is-invalid @enderror">
                                                                <option value=""></option> 
                                                                @foreach($liste_entite as $liste_entites)
                                                                    <option value="{{$liste_entites->enseigne}}">{{$liste_entites->enseigne}}</option>
                                                                @endforeach
                                                            </select> 
                                                        </div>
                                                        <div class="d-flex justify-content-start">
                                                            @error('societe') <span class="text-danger">{{ $message }}</span> @enderror 
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">                                               
                                                <div class="row mb-1">
                                                    <label for="nom_user" class="col-lg-3 col-md-3 col-sm-4 fw-semibold col-form-label">Créé par</label>
                                                    <div class="col-lg-9 col-md-9 col-sm-8">                                                        
                                                        <select id="nom_user" wire:model="nom_user" class="form-control form-select bordure w-75 @error('nom_user') is-invalid @enderror">
                                                            <option value="{{$this->nom_user}}">{{$this->nom_user}}</option>
                                                        </select> 
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('nom_user') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <label for="telephone_user" class="col-lg-4 col-md-4 col-sm-3 fw-semibold col-form-label">Téléphone</label>
                                                    <div class="col-lg-8 col-md-8 col-sm-9">
                                                        <input type="text" wire:model="telephone_user" placeholder="" class="form-control bordure w-100 @error('telephone_user') is-invalid @enderror" id="telephone_user">
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('telephone_user') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>                                                                                                                             
                                            </div>                                       
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 px-0">
                                                <div class="table-responsive mt-2">
                                                    <div class="table-responsive mt-1"> 
                                                        <ul class="nav nav-tabs border-0" role="tablist">
                                                            <li class="nav-item">
                                                                <a class="nav-link active" data-bs-toggle="tab" href="#home">Description {{$description}}</a>
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
                                                                            {{-- <label for="description" class="col-lg-2 col-md-3 col-sm-4 col-form-label">Description</label> --}}
                                                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                                                <textarea rows="5" id="description" wire:model.defer="description" class="form-control bordure px-0 w-100 @error('description') is-invalid @enderror" placeholder="Ajouter des détails sur ce ticket..."></textarea>
                                                                            </div>
                                                                            <div class="d-flex justify-content-start">
                                                                                @error('description') <span class="text-danger">{{ $message }}</span> @enderror 
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
        </div>
    </div>    
</div>

