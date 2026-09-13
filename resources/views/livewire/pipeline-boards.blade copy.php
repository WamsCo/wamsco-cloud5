<div>
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
                    <div class="card-bodyk px-1 border-top rounded-3" style="background-color: #f6f9fb;">
                        <div class="container-fluid pt-0 px-0">
                            <div class="row px-0 py-0">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <main class="w_content">
                                        <div class="w_kanban_renderer w_renderer d-flexk user-select-none w_kanban_grouped align-content-stretch">
                                               {{-- Style --}}
                                                <style>
                                                    /* --- Animation carte déplacée --- */
                                                    .card.dragging {
                                                        opacity: .4;
                                                        transform: rotate(1deg);
                                                    }
                                                    .card:hover {
                                                        /* transform: scale(1.03); */
                                                        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                                                        transition: .2s;
                                                    }

                                                    .card.moved {
                                                        animation: movedHighlight .3s ease-out;
                                                    }

                                                    @keyframes movedHighlight {
                                                        0% { background-color: #fff3cd; transform: scale(1.05); }
                                                        100% { background-color: white; transform: scale(1); }
                                                    }

                                                    .slide-in {
                                                        animation: slideIn 0.25s ease-out;
                                                    }

                                                    @keyframes slideIn {
                                                        from { opacity: 0; transform: translateY(10px); }
                                                        to   { opacity: 1; transform: translateY(0); }
                                                    }

                                                    /* --- Colonne survolée (drop highlight) --- */
                                                    .dropzone.over {
                                                        border-color: #0d6efd !important;
                                                        box-shadow: 0 0 0 3px rgba(13,110,253, .2);
                                                    }
                                                </style>
                                               {{-- fin style --}}
                                                @php
                                                    // 🎨 Couleurs personnalisées par colonnes
                                                    $colors = [
                                                        1 => '#e3f2fd', // bleu clair
                                                        2 => '#fff3cd', // jaune clair
                                                        3 => '#ffe5d1', // orange clair
                                                        4 => '#e9f7ef', // vert clair
                                                    ];
                                                @endphp
                                               {{-- code --}}
                                                <div class="row g-3">
                                                    @foreach ($steps as $step)
                                                        <div class="col-md-12 col-lg-4">
                                                            <div class="border rounded p-3 shadow-sm"
                                                                style="background-color: {{ $colors[$step] }}">
                                                                <h5 class="fw-bold mb-3">Étape {{ $step }}</h5>
                                                                <!-- ZONE DE DROP -->
                                                                <div class="dropzone min-vh-50 p-2 border rounded bg-white" ondrop="dropHandler(event, {{ $step }})" ondragover="dragOver(event)" ondragleave="dragLeave(event)" style="min-height: 300px;">
                                                                    @foreach ($columns[$step] ?? [] as $task)
                                                                        <div class="card mb-2 shadow-sm slide-in pointer" wire:click.prevent="ok()" draggable="true" ondragstart="dragStart(event, {{ $task->id }})" ondragend="dragEnd(event)" wire:key="task-{{ $task->id }}" style="cursor: grab;">
                                                                            <div class="card-body py-2">
                                                                                <strong>{{ $task->title }}</strong>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                               {{-- Fin code --}}

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
    {{-- <div class="d-flex">
        <span class="text-truncate"><i class="fa fa-phone text-danger"></i> {{$nom_vendeur}}</span>
    </div>     --}}
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

        function dropHandler(event, newStep) {
            event.preventDefault();

            const dropzone = event.target.closest(".dropzone");
            dropzone.classList.remove('over');

            const cards = [...dropzone.querySelectorAll('.card')];
            const targetCard = event.target.closest('.card');
            let newPosition = cards.indexOf(targetCard);
            if (newPosition < 0) newPosition = cards.length;

            @this.call('moveTask', draggedTask, newStep, newPosition);

            setTimeout(() => {
                const moved = dropzone.querySelector(`.card[wire\\:key="task-${draggedTask}"]`);
                if (moved) moved.classList.add('moved');
            }, 60);
        }
    </script>
</div>
