<div>
    <div class="pos dvh-100 d-flex flex-column position-fixed w-100" wire:poll.visible.10s> 
        {{-- Debut chargement --}}        
        <div wire:loading class="chargement_pos">
            <label for=""></label>
            <img src="storage/default/circle_loading.gif" width="64" height="64" style="background: #28282800; margin-left: -3px;">
        </div>
        {{-- Fin chargement --}} 
        <div class="pos-contentk flex-grow-1 overflow-auto" style="background-color: #f6f9fb">
            <div class="card" style="box-shadow: 0 0;">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title d-none d-md-block d-lg-block"><i class="fa fa-tv"></i> Espace Restaurant (<span class="text-vert">{{$this->ref_session_posRes}}</span>)</h5>
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs border-0" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link fs-5 active" wire:click.prevent="Tables({{$this->id_session_posRes}},'{{$this->ref_session_posRes}}')"><i class="fa fa-table"></i> Tables</button>
                        </li>
                        <li class="nav-item">
                            <button  class="nav-link fs-5" wire:click.prevent="CmdAttente({{$this->id_session_posRes}},'{{$this->ref_session_posRes}}')"><i class="fa fa-cutlery"></i> Commandes ({{$cmdCount}})</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link fs-5" wire:click.prevent="backSession({{$this->id_session_posRes}},'{{$this->ref_session_posRes}}')"><i class="fa fa-cash-register"></i> Session</span></button>
                        </li>                        
                    </ul>
                    <h5 class="card-title d-none d-md-block"></h5>
                    <!-- Tab panes --> 
                </div>                            
                <div class="card-body px-0 py-0 border-top" style="background-color: #f6f9fb;">
                    <div class="container-fluid pt-0 px-0">
                        <div class="row px-0 py-0">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <main class="w_contenu">                                
                                    <div class="w_kanban_renderer w_renderer d-flex user-select-none w_kanban_grouped align-content-stretch gap-1">                                            
                                        @foreach($espace as $espaces)                                        
                                        <div class="w_kanban_group w_group_draggable">
                                            <div class="w_kanban_header top-0 z-1 py-0 pt-print-0">
                                                <div class="w_kanban_header_title position-relative d-flex lh-lg">
                                                    <div class="w_column_title flex-grow-1 min-w-0 mw-100 gap-1 d-flex fs-4 fw-bold align-top text-900">
                                                        <span class="text-truncate" title="{{$espaces->nom_espace}}">{{$espaces->nom_espace}}</span>
                                                    </div>
                                                    <button class="w_kanban_quick_add d-print-none btn pe-2 me-n2" wire:click.prevent="table({{$espaces->id}})" data-bs-toggle="modal" data-bs-target="#createTableModal">
                                                        <i class="fa fa-plus opacity-75 fw-bold" role="img" aria-label="Ajout rapide" title="Ajout rapide"></i>
                                                    </button>
                                                </div>
                                                @php                                                                       
                                                    $nombre = $this->getTotalParOpportunite($espaces->id);                                                                 
                                                    // $total_montant = $this->getTotalParEtape($espaces->id); 
                                                @endphp
                                                <div class="w_kanban_counter position-relative d-flex align-items-center justify-content-between mb-2">
                                                    <div class="w_column_progress progress bg-300 w-50">
                                                        <div class="cursor-pointer bg-success" aria-valuemin="0" aria-label="Barre de progression" data-tooltip-delay="0" style="width: {{$nombre}}%;" aria-valuemax="1" aria-valuenow="1" data-tooltip="1 Autre"></div>
                                                    </div>
                                                    <div class="ms-auto"></div>                                                                
                                                    <div class="w_animated_number ms-2 text-900 text-nowrap cursor-default" data-tooltip="Revenu attendu"><b>{{$nombre}} Table(s)</b></div>
                                                </div>
                                                <!-- ZONE DE DROP -->
                                                <div class="dropzone dropzone_restauk min-vh-50 p-0 border rounded bg-white ecran_pipeline" ondrop="dropHandler(event, {{ $espaces->id }})" ondragover="dragOver(event)" ondragleave="dragLeave(event)">
                                                    @foreach ($opportuniter[$espaces->id] ?? [] as $task)                                                                
                                                        <div class="card rounded-0 p-0 shadow-sm slide-in w_kanban_record d-flex cursor-pointer @if($task->utiliser == "Oui") bg-danger_opportunite @endif" wire:click.prevent="voirTable({{$task->id}})" draggable="true" ondragstart="dragStart(event, {{ $task->id }})" ondragend="dragEnd(event)" wire:key="task-{{ $task->id }}" style="cursor: grab;">
                                                            <div class="card-body card-pipeline py-1">
                                                                <span class="fw-bold fs-5">{{$task->nom_table}}</span> <br/>                                                               
                                                                @if($task->utiliser == "Oui")
                                                                    <span class="text-bleu fw-semibold" title="Numéro session de la commande">{{$task->ref_session_restau}}</span>
                                                                    <span class="text-danger fw-semibold opacity-75" title="Statut de la table"><i class="fa fa-refresh fa-spin"></i> Occupée</span>
                                                                @else 
                                                                    <span class="text-green fw-semibold opacity-75" title="Statut de la table"><i class="fa fa-check-circle"></i> Disponible</span>
                                                                @endif 
                                                                <br/> 
                                                                @if($task->lieu_consommation)
                                                                    <div class="ms-auto d-flex justify-content-between align-items-center">
                                                                        <div class="fw-semibold" title="Lieu de consommation"><i class="fa fa-location text-bleu"></i> {{$task->lieu_consommation}}</div>
                                                                        <div class="fw-semibold">
                                                                            @if($task->statut == "Terminer")
                                                                                <span class="fw-semibold" style="color: #08a316;" title="Satut back office"><i class="fa fa-thumbs-up"></i> {{$task->statut}}</span>
                                                                            @elseif($task->statut == "A préparer")
                                                                                <span class="" style="color: #ff5a00;" title="Statut back office"><i class="fa fa-spinner fa-spin"></i> {{$task->statut}}</span>
                                                                            @elseif($task->statut == "En cours")
                                                                                <span class="" style="color: #456bf2;" title="Statut back office"><i class="fa fa-hand"></i> {{$task->statut}}</span>
                                                                            @else
                                                                                <span class=""></span>
                                                                            @endif
                                                                        </div>                                                                    
                                                                    </div>
                                                                @endif                                                               
                                                                {{-- <div class="w_field_widget fw-semibold">
                                                                    <i class="fa fa-money-bill text-bleu"></i> <span>{{number_format($task->montant_attendu,0,',',' ')}} {{$this->devise}}</span>
                                                                </div> --}}
                                                                {{-- <div class="d-flex">
                                                                    <span class="text-truncate fw-bold"><i class="fa fa-user-circle text-danger"></i> {{$task->client}}</span>
                                                                </div> --}}
                                                                {{-- <div class="d-flex">
                                                                    <span class="text-truncate"><i class="fa fa-phone text-danger"></i> {{$task->telephone_contact}}</span>
                                                                </div> --}}
                                                                {{-- @if($task->email_contact)
                                                                    <div class="d-flex">
                                                                        <span class="text-truncate"><i class="fa fa-envelope text-danger"></i> {{$task->email_contact}}</span>
                                                                    </div>
                                                                @endif   --}}
                                                                <div name="lead_properties" class="w_field_widget w_field_properties">
                                                                    <div class="w-100 fw-normal text-muted"></div>
                                                                </div>
                                                                <footer class="pt-1">  
                                                                    @php
                                                                        foreach($user as $users){
                                                                            if($task->id_caissiere == $users->id){                                                                                    
                                                                                $profils = $users->profil;
                                                                                $nom_vendeur = $users->name;
                                                                                $phone_vendeur = $users->telephone;
                                                                                $type_user = $users->type_user;                                                                                
                                                                            }
                                                                        } 
                                                                    @endphp                                                                  
                                                                    <div class="ms-auto d-flex justify-content-between align-items-center">
                                                                        <div class="fw-semibold" title="Date changement"><i class="fa fa-calendar-alt text-bleu"></i> {{date('d-m-Y H:i:s', strtotime($task->updated_at))}}</div>
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
    {{-- Modal --}}
    @include('livewire.gestion-point-vente.restaurant.table.creation_table')
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