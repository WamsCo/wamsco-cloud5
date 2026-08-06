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
                    <div class="container-fluid py-2 px-2">
                        <div class="row">
                            <!-- SIDEBAR -->                               
                            <!-- CONTENU -->
                            <div class="col-lg-12">                                                                 
                                <!-- ONGLETS -->
                                <div class="card-header d-flex align-items-center justify-content-between border-0 px-4">
                                    <!-- Nav tabs -->
                                    <ul class="nav nav-tabs margin_ajuste border-0" role="tablist">
                                        <li class="nav-item">                                                
                                            <a class="nav-link active" data-bs-toggle="tab" href="#home" title="Listing des activités du système"><i class="fa fa-list text-bleu"></i> {{$title_fils}} (<span class="text-vert">{{$logCount}} / {{$logTotal}}</span>)</a>                                                
                                        </li>                                                                                   
                                        {{-- <li class="nav-item">
                                            <a class="nav-link pointer" wire:click.prevent="soldeTier()">Haut</a>
                                        </li> --}}
                                    </ul>
                                    <!-- Tab panes -->                        
                                    <div class="d-flex align-items-center justify-content-between gap-1">
                                        <div class="d-flex align-items-center justify-content-between gap-1"> 
                                            {{-- <a href="facturationcltkk-pdf?code=" class="btn btn-sm btn-default" title="Cliquez pour imprimer" data-toggle="tooltip" style="color: #161a1d;"><i class="fa fa-print"></i>Ticket</a> --}}
                                            {{-- <a href="facturationclt-pdf?code=Fact/191224/a0adc871" class="btn btn-sm btn-default" title="Cliquez pour imprimer" data-toggle="tooltip" target="blank"><i class="fa fa-print"></i>A4</a> --}}
                                        </div>                            
                                        <div class="d-flex align-items-center justify-content-between gap-1">                            
                                            @if($confirmer === 1)                                                               
                                                <a wire:click.prevent="effacerall()" class="btn btn-sm btn-white text-danger fw-bold ms-auto blink" title="Cliquez pour confirmer la suppression" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-trash"> Confirmer ?</i></a>
                                            @else
                                                <a wire:click.prevent="confirmerDelete(1)" class="btn btn-sm btn-white text-bleu fw-bold ms-auto"  title="Cliquez pour supprimer" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-trash text-danger"></i> Supprimer tout</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-content position-relative custom-scroll pe-3" style="height: calc(100vh - 176px); overflow-y: auto;">
                                    <div id="home" class="container-fluid tab-pane active pas_bordure">  
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 px-0">   
                                                <div class="card shadow-sm border border-light-subtle rounded-3 bg-white px-0">
                                                    <div class="table-responsive border-top">
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
                                                        <div class="bloc_pagination">{{$log->links()}}</div>
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
        </div>    
    </div>
</div>
