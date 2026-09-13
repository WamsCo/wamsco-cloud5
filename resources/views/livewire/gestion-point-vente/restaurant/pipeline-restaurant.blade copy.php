<div>
    <div id="content" class="app-content" wire:poll.visible.10s>
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
                            <a href="#" class="btn btn-sm btn-secondary fw-bold text-white" title=" Cliquez pour ajouter" data-bs-toggle="modal" data-bs-target="#creerOppotuniteModal" data-toggle="tooltip" style="color: #161a1d;"><i class="fa fa-star"></i> Nouveau</a>
                            {{-- <a href="#" class="btn btn-sm btn-default" title="Cliquez pour imprimer" data-toggle="tooltip" target="blank"><i class="fa fa-print"></i>A4</a> --}}
                        </div>                      
                        <div class="d-flex align-items-center justify-content-between gap-1">
                            <div class="d-flex align-items-center justify-content-between gap-1"> 
                                {{-- <a href="facturationcltkk-pdf?code=" class="btn btn-sm btn-default" title="Cliquez pour imprimer" data-toggle="tooltip" style="color: #161a1d;"><i class="fa fa-print"></i>Ticket</a> --}}
                                {{-- <a href="facturationclt-pdf?code=Fact/191224/a0adc871" class="btn btn-sm btn-default" title="Cliquez pour imprimer" data-toggle="tooltip" target="blank"><i class="fa fa-print"></i>A4</a> --}}
                            </div>                            
                            <div class="d-flex align-items-center justify-content-between gap-1">                            
                                <a href="listing-tiers?active=3&champ=3-2" wire:navigate class="btn btn-sm btn-bleu ms-auto"><i class="fa fa-chevron-left"></i> Retour liste</a>
                                {{-- <button class="btn btn-sm btn-default new_color" @if($this->ids) wire:click.prevent="precedant({{$this->ids}})" @else wire:click.prevent="precedant({{$this->id}})" @endif title="Précédant" data-toggle="tooltip"><i class="fa fa-chevron-left"></i></button>
                                <a href="#" class="btn btn-sm btn-default new_color" @if($this->ids) wire:click.prevent="suivant({{$this->ids}})" @else wire:click.prevent="suivant({{$this->id}})" @endif title="Suivant" data-toggle="tooltip"><i class="fa fa-chevron-right"></i></a> --}}
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-3 py-0 border-top" style="background-color: #f6f9fb;">
                        <div class="container-fluid pt-0 px-0">
                            <div class="row px-0 py-0">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <main class="w_contenu">
                                        <div class="w_kanban_renderer w_renderer d-flex user-select-none w_kanban_grouped align-content-stretch gap-1">                                            
                                            @foreach($espace as $espaces)
                                            {{-- <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6"> --}}
                                            <div class="w_kanban_group w_group_draggable">
                                                <div class="w_kanban_header top-0 z-1 py-0 pt-print-0">
                                                    <div class="w_kanban_header_title position-relative d-flex lh-lg">
                                                        <div class="w_column_title flex-grow-1 min-w-0 mw-100 gap-1 d-flex fs-4 fw-bold align-top text-900">
                                                            <span class="text-truncate" title="{{$espaces->nom_espace}}">{{$espaces->nom_espace}}</span>
                                                        </div>
                                                        <div class="w_group_config d-print-none d-flex">
                                                            <button class="btn px-2 py-0 o-dropdown dropdown-toggle dropdown" tabindex="-1" aria-expanded="false"><i class="fa fa-gear opacity-50 opacity-100-hover" role="img" aria-label="Paramètres" title="Paramètres"></i>
                                                            </button>
                                                        </div>
                                                        <button class="w_kanban_quick_add d-print-none btn pe-2 me-n2" wire:click.prevent="opportuniter({{$espaces->id}})" data-bs-toggle="modal" data-bs-target="#creerOppotuniteModal">
                                                            <i class="fa fa-plus opacity-75 fw-bold" role="img" aria-label="Ajout rapide" title="Ajout rapide"></i>
                                                        </button>
                                                    </div>
                                                    {{-- @php                                                                       
                                                        $nombre = $this->getTotalParOpportunite($espaces->id);                                                                 
                                                        $total_montant = $this->getTotalParEtape($espaces->id); 
                                                    @endphp --}}
                                                    <div class="w_kanban_counter position-relative d-flex align-items-center justify-content-between mb-2">
                                                        <div class="w_column_progress progress bg-300 w-50">
                                                            {{-- <div class="cursor-pointer bg-success" aria-valuemin="0" aria-label="Barre de progression" data-tooltip-delay="0" style="width: {{$nombre}}%;" aria-valuemax="1" aria-valuenow="1" data-tooltip="1 Autre"></div> --}}
                                                            <div class="cursor-pointer bg-success" aria-valuemin="0" aria-label="Barre de progression" data-tooltip-delay="0" style="width: %;" aria-valuemax="1" aria-valuenow="1" data-tooltip="1 Autre"></div>
                                                        </div>
                                                        <div class="ms-auto"></div>                                                                
                                                        {{-- <div class="w_animated_number ms-2 text-900 text-nowrap cursor-default" data-tooltip="Revenu attendu"><b>{{number_format($total_montant,0,',',' ')}} {{$this->devise}}</b></div> --}}
                                                        <div class="w_animated_number ms-2 text-900 text-nowrap cursor-default" data-tooltip="Revenu attendu"><b> {{$this->devise}}</b></div>
                                                    </div>
                                                    <!-- ZONE DE DROP -->
                                                    <div class="dropzone min-vh-50 p-0 border rounded bg-white ecran_pipeline" ondrop="dropHandler(event, {{ $espaces->id }})" ondragover="dragOver(event)" ondragleave="dragLeave(event)">
                                                        @foreach ($opportuniter[$espaces->id] ?? [] as $task)                                                                
                                                            <div class="card rounded-0 p-0 shadow-sm slide-in w_kanban_record d-flex cursor-pointer @if($espaces->opacite == "Oui") opacity-50 @endif @if($dateJour > $task->date_cloture) bg-danger_opportunite @endif" wire:click.prevent="voirDetail({{$task->id}})" draggable="true" ondragstart="dragStart(event, {{ $task->id }})" ondragend="dragEnd(event)" wire:key="task-{{ $task->id }}" style="cursor: grab;">
                                                                <div class="card-body card-pipeline py-2">
                                                                    <span class="fw-bold fs-5">{{$task->nom_espace}}</span> <br/>
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
                                                                    <div name="lead_properties" class="w_field_widget w_field_properties">
                                                                        <div class="w-100 fw-normal text-muted"></div>
                                                                    </div>
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
                                                                        @endphp                                                                  
                                                                        <div class="ms-auto d-flex justify-content-between align-items-center">
                                                                            <div class="fw-semibold"><i class="fa fa-calendar-alt text-bleu"></i> {{date('d-m-Y H:i:s', strtotime($task->created_at))}}</div>
                                                                            <div class="d-flex">
                                                                                <span class="badge @if($task->priorite == "Très élevé") badge-danger blink @elseif($task->priorite == "Haute") badge-success @else badge-info @endif py-0"><i class="fa fa-battery-half"></i> {{$task->priorite}}</span>
                                                                            </div>                                                                        
                                                                            <div class="image_user">                                                                           
                                                                                @if($profils != null)
                                                                                    <img class="rounded-circle" src="storage/{{$profils}}" title="{{$type_user}} » {{$nom_vendeur}} @if($phone_vendeur)» {{$phone_vendeur}} @endif" alt="Profil"/>
                                                                                @else
                                                                                    <img class="rounded-circle" src="storage/default/prof.jpg" title="{{$type_user}} » {{$nom_vendeur}} @if($phone_vendeur)» {{$phone_vendeur}} @endif" alt="Profil"/>
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
    {{-- @include('livewire.crm.article_crm')  --}}
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

