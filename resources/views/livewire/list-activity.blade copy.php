<div>
    <div id="content" class="app-content" wire:poll.visible.30s>
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
                        <h5 class="card-title text-bleu"><i class="fa fa-list text-danger"></i> {{$title_fils}} ({{$logCount}} / {{$logTotal}})</h5>
                         @if($confirmer === 1)                                                               
                            <a wire:click.prevent="effacerall()" class="btn btn-sm btn-white text-danger fw-bold ms-auto blink" title="Cliquez pour confirmer la suppression" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-trash"> Confirmer ?</i></a>
                         @else
                            <a wire:click.prevent="confirmerDelete(1)" class="btn btn-sm btn-white text-bleu fw-bold ms-auto"  title="Cliquez pour supprimer" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-trash text-danger"></i> Supprimer tout</a>
                         @endif
                    </div>
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="">
                            {{-- <select name="" wire:model.lazy="parPage" id="par_page" class="form-select form-select-sm bordure"> 
                                @for($i = 20; $i <= 100; $i += 20)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor        
                            </select>  --}}
                        </div>
                        <div>
                            {{-- <label for="query" class="sr-only">Recherche</label>
                            <input type="search" wire:model.live="query" id="query" class="form-control bordure" placeholder="Rechercher"> --}}
                        </div>
                    </div>
                    <div class="card-body no_bordure">
                        <div class="table-outer">
                            <div class="table-responsive border-top rounded-0">   
                                <table class="table table-striped table-hover text-nowrap m-0">
                                    <thead>
                                    <tr>
                                        <th class="fond_entete_table"></th>
                                        <th class="fond_entete_table">Réf.</th>
                                        <th class="fond_entete_table">Titre</th>
                                        <th class="fond_entete_table">Page</th>
                                        <th class="fond_entete_table">Par</th>
                                        <th class="fond_entete_table">Sociéte</th>
                                        <th class="fond_entete_table">Date</th>
                                        <th class="fond_entete_table">Method</th>
                                        <th class="fond_entete_table">IP</th>
                                        <th class="fond_entete_table">User ID</th>
                                        <th class="fond_entete_table">URL</th>
                                        {{-- <th width="300px">Technologie</th> --}}
                                        <th class="fond_entete_table"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @if($log->count())
                                            @foreach($log as $logs)
                                                <tr>
                                                    <td><input type="checkbox" x-model="selection" value="{{$logs->id}}" class="form-check-input" id="flexSwitchCheckChecked" checked=""/></td>                                                    
                                                    <td class="fw-semibold"><i class="fas fa-calendar-alt text-danger"></i> {{$logs->id}}</td>
                                                    <td class="fw-bold text-bleu">
                                                        @if($logs->subject == "Connexion application")
                                                            <span class="text-vert">{{$logs->subject}}</span>
                                                        @elseif($logs->subject == "Deconnexion du systeme")
                                                            <span class="text-danger">{{$logs->subject}}</span>
                                                        @else
                                                            {{-- <span class="" title="{{$logs->subject}}">{!! substr($logs->subject,0,52) > substr($logs->subject,0,51) ? substr($logs->subject,0,52).'...': $logs->subject !!}</span> --}}
                                                            <span class="" title="{!!$logs->subject!!}">{!! Str::limit($logs->subject, 56) !!}</span>
                                                        @endif
                                                    </td>
                                                    <td class="fw-semibold">{{$logs->page}}</td>                                                    
                                                    <td class="fw-semibold">
                                                        <a href="detail_user?id={{$logs->user_id}}&active=12&champ=1-1" wire:navigate>
                                                            @if($logs->profil != null)  
                                                                <img class="rounded-3" style="width: 22px;height: 22px;" src="storage/{{$logs->profil}}">
                                                            @else       
                                                                <img class="rounded-3" style="width: 22px;height: 22px;" src="storage/default/user_man.png">                     
                                                            @endif                                                              
                                                            {{$logs->user_email}}
                                                        </a>
                                                    </td>
                                                    <td class="text-info fw-bold">{{$logs->user_societe}}</td>
                                                    <td>{{date('d-m-Y H:i:s', strtotime($logs->created_at))}}</td>
                                                    <td class="text-center"><label class="badge bg-dark">{{$logs->method}}</label></td>
                                                    <td class="text-danger fw-semibold">{{ $logs->ip}}</td>
                                                    <td class="text-center fw-semibold">{{$logs->user_id}}</td>
                                                    <td class="">{{$logs->url}}</td>
                                                    {{-- <td class="text-success">{{ $logs->agent}}</td> --}}                                            
                                                    <td>
                                                        <a wire:click="supprimer({{$logs->id}})" class="btn btn-outline-muted btn-xs"  title="Cliquez pour supprimer" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-trash"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>                                                         
                                <div class="bloc_pagination" >{{$log->links()}}</div> 
                            </div>                        
                        </div>
                    </div>
                </div>
            </div>
        </div>    
    </div>
</div>
