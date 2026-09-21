<div>
    {{-- <div id="content" class="app-content" wire:poll.visible.30s> --}}
    <div id="content" class="app-content">
        <div class="profile mb-2">
            <div class="profile-header">        
                <div class="profile-header-cover"></div>      
                <div class="profile-header-content">
                    <div class="profile-header-info">
                        <ol class="breadcrumb float-xl-start">
                            <li class="breadcrumb-item"><a href="bienvenue?active=1">Accueil /</a></li>
                            <li class="breadcrumb-item activek"><a href="{{$lien}}" wire:navigate>&nbsp;{{$module}} /</a></li>
                            <li class="breadcrumb-item d-none d-md-block"><span style="color:yellow">&nbsp;{{$title_fils}}</span></li>
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
                        <div>
                            <a href="#" class="btn btn-sm btn-outline-secondary flex-fill fw-semibold ms-auto" title="Cliquez pour ajouter" data-bs-toggle="modal" data-bs-target="#creerOppotuniteModal" data-toggle="tooltip"><i class="fa fa-star"></i> Nouveau</a>
                            {{-- <a href="#" class="btn btn-sm btn-default" title="Cliquez pour imprimer" data-toggle="tooltip" target="blank"><i class="fa fa-print"></i>A4</a> --}}
                        </div>                      
                        <div class="d-flex align-items-center justify-content-between gap-1">
                            <div class="d-flex align-items-center justify-content-between gap-1"> 
                                {{-- <a href="facturationclt-pdf?code=Fact/191224/a0adc871" class="btn btn-sm btn-default" title="Cliquez pour imprimer" data-toggle="tooltip" target="blank"><i class="fa fa-print"></i>A4</a> --}}
                            </div>                            
                            <div class="d-flex align-items-center justify-content-between gap-1">                            
                                <a href="listing-tiers?active=3&champ=3-2" wire:navigate class="btn btn-sm btn-bleu ms-auto"><i class="fa fa-chevron-left"></i> Retour liste</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-header w_contenu px-3 py-0">
                        <div class="d-flex align-items-center justify-content-between gap-3 pt-0 pb-1 px-0">
                            <div class="d-flex align-items-center justify-content-between gap-1 mb-1">
                                <div class="">
                                    {{-- <select name="" wire:model.lazy="parPage" id="par_page" class="form-control form-select bordure w-auto"> 
                                        @for($i = 20; $i <= 100; $i += 20)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor        
                                    </select>  --}}
                                </div>
                                <div class="d-flex align-items-center justify-content-between gap-1">
                                    {{-- <h6><a href="{{asset('storage/manuel_users/Excel_Tier_WamsCo.xlsx')}}" download="Excel_Tier_WamsCo" class="btn btn-sm btn-secondary mt-2 d-none d-md-block"><i class="fa-solid fa fa-download"></i>  Télécharger Modèle</a></h6> --}}
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between gap-1">
                                <div class="card-headerk d-flex align-items-center justify-content-end gap-1">
                                    <input type="date" wire:model.live="date_debut" class="form-control bordure w-auto">
                                    <input type="date" wire:model.live="date_fin" class="form-control bordure w-auto">
                                </div>
                                <div class="">
                                    <select wire:model.live="parSource" class="form-control form-select bordure">
                                        <option value="">Toutes les sources</option>	
                                        <option value="Site web">Site web</option>
                                        <option value="WhatsApp">WhatsApp</option>
                                        <option value="Facebook">Facebook</option>                               
                                        <option value="TikTok">TikTok</option>                               
                                        <option value="Instagram">Instagram</option>                               
                                        <option value="Google">Google</option>                               
                                        <option value="Google Maps">Google Maps</option>                               
                                        <option value="Email">Email</option>
                                        <option value="Commercial">Commercial</option>
                                        <option value="Partenaire">Partenaire</option>                               
                                    </select>                                    
                                </div> 
                                @if(auth()->user()->type_user == "Administrateur")
                                    <div>
                                        <select wire:model.live="parUser" class="form-control form-select bordure w-auto"> 
                                            <option value="">Tous les commerciaux</option>
                                            @foreach($utilisat as $utilisats)
                                                <option value={{$utilisats->id}}>{{$utilisats->name}}</option>
                                            @endforeach
                                        </select>
                                    </div> 
                                @endif 
                                <div>
                                    <label for="parCap" class="sr-only">Recherche</label>
                                    <input type="search" wire:model.live="parCap" id="parCap" class="form-control bordure" placeholder="Rech. campagne">
                                </div>                            
                                <div>
                                    <label for="parSec" class="sr-only">Recherche</label>
                                    <input type="search" wire:model.live="parSec" id="parSec" class="form-control bordure" placeholder="Rech. secteur activité">
                                </div>                             
                                <div>
                                    <label for="query" class="sr-only">Recherche</label>
                                    <input type="search" wire:model.live="query" id="query" class="form-control bordure" placeholder="Rech. ville">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-3 py-0 border-top" style="background-color: #f6f9fb;">
                        <div class="container-fluid pt-0 px-0">
                            <div class="row px-0 py-0">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <main class="w_contenu position-relative custom-scrollk px-0" style="height: calc(100vh - 194px); overflow-y: auto;">
                                        <div class="w_kanban_renderer w_renderer d-flex user-select-none w_kanban_grouped align-content-stretch gap-1">                                            
                                            @foreach($etape as $etapes)
                                            {{-- <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6"> --}}
                                            <div class="w_kanban_group w_group_draggable">
                                                <div class="w_kanban_header top-0 z-1 py-0 pt-print-0">
                                                    @php                                                                       
                                                        $nombre = $this->getTotalParOpportunite($etapes->id);                                                                 
                                                        $total_montant = $this->getTotalParEtape($etapes->id); 
                                                    @endphp
                                                    <div class="w_kanban_header_title position-relative d-flex lh-lg">
                                                        <div class="flex-grow-1 min-w-0 mw-100 gap-1 d-flex fs-4 fw-bold align-top text-900">
                                                            <span class="text-truncate" title="Etape » {{$etapes->nom_etape}}"><span class="fs-6 text-bleu" title="Nombre opportunité(s) » {{$nombre}}">{{$nombre}} » </span>{{$etapes->nom_etape}}</span>
                                                        </div>
                                                        <div class="w_group_config d-print-none d-flex">
                                                            <button class="btn px-2 py-0 o-dropdown dropdown-toggle dropdown" tabindex="-1" aria-expanded="false"><i class="fa fa-gear opacity-50 opacity-100-hover" role="img" aria-label="Paramètres" title="Paramètres"></i>
                                                            </button>
                                                        </div>
                                                        <button class="w_kanban_quick_add d-print-none btn pe-2 me-n2" wire:click.prevent="opportuniter({{$etapes->id}})" data-bs-toggle="modal" data-bs-target="#creerOppotuniteModal">
                                                            <i class="fa fa-plus opacity-75 fw-bold" role="img" aria-label="Ajout rapide" title="Ajout rapide"></i>
                                                        </button>
                                                    </div>                                                    
                                                    <div class="w_kanban_counter position-relative d-flex align-items-center justify-content-between mb-2">
                                                        <div class="w_column_progress progress bg-300 w-50">
                                                            <div class="cursor-pointer bg-success" aria-valuemin="0" aria-label="Barre de progression" data-tooltip-delay="0" style="width: {{$nombre}}%;" aria-valuemax="1" aria-valuenow="1" data-tooltip="1 Autre"></div>
                                                        </div>
                                                        <div class="ms-auto"></div>                                                                
                                                        <div class="w_animated_number ms-2 text-900 text-nowrap cursor-default" data-tooltip="Revenu attendu"><b>{{number_format($total_montant,0,',',' ')}} {{$this->devise}}</b></div>
                                                    </div>
                                                    <!-- ZONE DE DROP -->
                                                    <div class="dropzone min-vh-50 p-0 border rounded bg-white ecran_pipeline" ondrop="dropHandler(event, {{ $etapes->id }})" ondragover="dragOver(event)" ondragleave="dragLeave(event)">
                                                        @foreach ($opportuniter[$etapes->id] ?? [] as $task)                                                                
                                                            <div class="card rounded-0 p-0 shadow-sm slide-in w_kanban_record d-flex cursor-pointer @if($etapes->opacite == "Oui") opacity-50 @endif @if($dateJour > $task->date_cloture) bg-danger_opportunite @endif" wire:click.prevent="voirDetail({{$task->id}})" draggable="true" ondragstart="dragStart(event, {{ $task->id }})" ondragend="dragEnd(event)" wire:key="task-{{ $task->id }}" style="cursor: grab;">
                                                                <div class="card-body card-pipeline py-2">
                                                                    <span class="fw-bold fs-5">{{$task->nom_opportunite}}</span> <br/>
                                                                    <div class="w_field_widget fw-semibold">
                                                                        <span class="badge badge-dark" title="Secteur d'activité » {{$task->secteur_activite}}">{{Str::limit($task->secteur_activite,42)}}</span> <span class="badge badge-success" title="Ville société » {{$task->ville}}">{{Str::limit($task->ville,32)}}</span> <span class="badge badge-info" title="Probabilité succès » {{$task->probabilite}}">{{$task->probabilite}}</span>
                                                                    </div>
                                                                    <div class="w_field_widget fw-semibold">
                                                                        <i class="fa fa-money-bill text-bleu"></i> <span>{{number_format($task->montant_attendu,0,',',' ')}} {{$this->devise}}</span>
                                                                    </div>                                                                   
                                                                    <div class="d-flex">
                                                                        <span class="text-truncate fw-bold"><i class="fa fa-user-circle text-danger"></i> {{$task->client}}</span>
                                                                    </div>
                                                                    <div class="d-flex">
                                                                        <span class="text-truncate"><i class="fa fa-phone text-danger"></i> {{$task->telephone_contact}}</span>
                                                                    </div>
                                                                    @if($task->email_contact)
                                                                        <div class="d-flex">
                                                                            <span class="text-truncate"><i class="fa fa-envelope text-danger"></i> {{$task->email_contact}}</span>
                                                                        </div>
                                                                    @endif  
                                                                    @if($task->campagne)
                                                                        <div class="d-flex">
                                                                            <span class="" title="Campagne » {{$task->campagne}}"><i class="fa fa-bullhorn text-danger"></i> {{Str::limit($task->campagne,36)}}</span>
                                                                        </div>
                                                                    @endif 
                                                                    <footer class="pt-1">  
                                                                        @php
                                                                            foreach($user as $users){
                                                                                if($task->vendeur == $users->id){                                                                                    
                                                                                    $profils = $users->profil;
                                                                                    $nom_vendeur = $users->name;
                                                                                    $phone_vendeur = $users->telephone;
                                                                                    $type_user = $users->type_user;                                                                                
                                                                                }
                                                                            } 
                                                                            // ceci Fait la meme chose en mieux que le foreach($user as $users) ...
                                                                             $noteTask = $note->where('opportunite_id', $task->id)->where('statut','!=','Terminer')->sortByDesc('created_at')->first(); // le dernier
                                                                        @endphp
                                                                        <div class="d-flex" title="Type d'activité planifiée">
                                                                            <span class="">
                                                                                @if($noteTask?->type_activite == "Note")
                                                                                    <i class="fa fa-commenting" style="color: #198754;"></i>                       
                                                                                @elseif($noteTask?->type_activite == "Appel")
                                                                                    <i class="fa fa-phone" style="color: #198754;"></i>                                                                                    
                                                                                @elseif($noteTask?->type_activite == "Email")
                                                                                    <i class="fa fa-envelope" style="color: #198754;"></i>                                                                            
                                                                                @elseif($noteTask?->type_activite == "Rendez-vous")
                                                                                    <i class="fa fa-calendar" style="color: #198754;"></i>    
                                                                                @elseif($noteTask?->type_activite == "Tâche")
                                                                                    <i class="fa fa-tasks" style="color: #198754;"></i>                                                                            
                                                                                @else
                                                                                    <i class="fa fa-edit text-danger"></i>
                                                                                @endif
                                                                            </span>
                                                                            <div class="">
                                                                                <span class="fw-bold text-dangerk" style="color: #198754;">&nbsp;{{$noteTask->type_activite ?? '' }}</span> » 
                                                                                <span class="fw-semibold text-bleu">
                                                                                    @if($noteTask)
                                                                                      <span title="{{$noteTask->sujet}}">{{Str::limit($noteTask->sujet,72)}}</span>
                                                                                    @else
                                                                                        <span class="blink">Aucune activité</span>
                                                                                    @endif
                                                                                </span>
                                                                            </div>
                                                                        </div>                                                                                                                                          
                                                                        <div class="ms-auto d-flex justify-content-between align-items-center">
                                                                            <div class="fw-semibold"><i class="fa fa-calendar-alt text-bleu"></i> {{date('d-m-Y H:i:s', strtotime($task->created_at))}}</div>
                                                                            <div class="d-flex">
                                                                                <span class="badge @if($task->priorite == "Très élevé") badge-danger blink @elseif($task->priorite == "Haute") badge-warning text-black @elseif($task->priorite == "Moyenne") badge-success @else badge-info @endif py-0"><i class="fa fa-battery-half"></i> {{$task->priorite}}</span>
                                                                            </div>                                                                        
                                                                            <div class="image_user">                                                                           
                                                                                @if($profils != null)
                                                                                    <img class="rounded-circle" src="storage/{{$profils}}" title="{{$type_user}} » {{$nom_vendeur}} @if($phone_vendeur)» {{$phone_vendeur}} @endif" alt="Profil"/>
                                                                                @else
                                                                                    <img class="rounded-circle" src="storage/default/user_man.png" title="{{$type_user}} » {{$nom_vendeur}} @if($phone_vendeur)» {{$phone_vendeur}} @endif" alt="Profil"/>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </footer>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>                                                
                                            @endforeach                                                                                     
                                        </div>                                        
                                    </main>
                                </div>                             
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>    
    {{-- Modal --}}
    @include('livewire.crm.article_crm') 
    <script>
        let draggedTask = null;
        function dragStart(event, id) {
            draggedTask = id;
            event.target.classList.add('dragging');
        }
        function dragEnd(event) {
            event.target.classList.remove('dragging');
        }
        function dragOver(event) {
            event.preventDefault();
            event.target.closest(".dropzone")?.classList.add('over');
        }
        function dragLeave(event) {
            event.target.closest(".dropzone")?.classList.remove('over');
        }
        function dropHandler(event, newEtape) {
            event.preventDefault();

            const dropzone = event.target.closest(".dropzone");
            dropzone.classList.remove('over');

            const cards = [...dropzone.querySelectorAll('.card')];
            const targetCard = event.target.closest('.card');
            let newPosition = cards.indexOf(targetCard);
            if (newPosition < 0) newPosition = cards.length;

            @this.call('moveTask', draggedTask, newEtape, newPosition);

            setTimeout(() => {
                const moved = dropzone.querySelector(`.card[wire\\:key="task-${draggedTask}"]`);
                if (moved) moved.classList.add('moved');
            }, 60);
        }
    </script>
</div>
