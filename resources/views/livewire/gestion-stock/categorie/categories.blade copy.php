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
                <div class="card mb-0 bg-2k">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title text-bleu"><i class="fa fa-chart-pie text-danger" aria-hidden="true"></i> {{$title_fils}} ({{$categoriecount}})</h5>
                        <a href="#" class="btn btn-sm btn-danger ms-auto" title=" Cliquez pour ajouter" data-bs-toggle="modal" data-bs-target="#ajoutCategorieModal"><i class="fa fa-plus-circle"></i> Nouvelle catégorie</a>
                        {{-- <a href="nouveau_entrepot" wire:navigate class="btn btn-danger ms-auto">Nouvelle catégorie</a> --}}
                    </div>
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="">
                            <select name="" wire:model.lazy="parPage" id="par_page" class="form-control form-select bordure w-auto"> 
                                @for($i = 20; $i <= 100; $i += 20)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor        
                            </select> 
                        </div>
                        <div>
                            <label for="query" class="sr-only">Recherche</label>
                            <input type="search" wire:model.live="query" id="query" class="form-control bordure" placeholder="Rechercher">
                        </div>
                    </div>
                    <div class="card-body no_bordure border-top taille_ecran"> 
                    <div class="row mx-0 pt-0">
                        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12 mb-1 pt-3 border-end border-bottom rounded-bottom">
                            <div class="hauteur_ecran">
                                <div class="row pt-0 px-2">
                                    @foreach($categorie as $categories)	
                                        <div class="col-lg-4 col-md-6 col-xs-12 mb-2 pointer" wire:click.prevent="edit('{{$categories->id}}')" data-bs-toggle="modal" data-bs-target="#updateCategorieModal" title="Cliquez pour voir les détails" data-toggle="tooltip">                            
                                            <div class="row rounded border list_achat">                                        
                                                <div class="col-md-8 col-sm-8 col-xs-8 mt-2 mb-2"> 
                                                    <strong class="text-bleu title_color truncate_ok"><i class="fa fa-chart-pie"></i> {{Str::limit($categories->nom_categorie, 22)}}</strong>
                                                </div>                                        
                                                <div class="col-md-4 col-sm-4 col-xs-4 mt-2 mb-2">                                                
                                                    <span class="description_cat">{{Str::limit($categories->description, 14)}}</span>                        
                                                </div>
                                                <div class="col-md-8 col-sm-8 col-xs-8 mb-2"> 
                                                    <span class="date_achat">{{date('d-m-Y H:i:s', strtotime($categories->created_at))}} <i class="fa fa-calendar-alt light"></i> <i class="fa fa-check-circle @if($categories->restaurant == "Oui") text-vert @else text-secondary @endif"title="Pour restaurant @if($categories->restaurant == "Oui") » Oui @else » Non @endif"></i></span>
                                                </div>
                                                <div class="col-md-4 col-sm-4 col-xs-4 mb-2">
                                                    <span class="entite_cat truncate_ok" title="{{$categories->nom_user}}">{{Str::limit($categories->nom_user, 11)}}</span>
                                                </div>                                        
                                            </div>                                                                
                                        </div> 
                                    @endforeach
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
                        <div class="bloc_pagination" >{{$categorie->links()}}</div> 
                    </div>
                </div>
            </div>    
        </div>
    </div>
    {{-- Modal --}}
    @include('livewire.gestion-stock.categorie.create')
    @include('livewire.gestion-stock.categorie.update') 
</div>

