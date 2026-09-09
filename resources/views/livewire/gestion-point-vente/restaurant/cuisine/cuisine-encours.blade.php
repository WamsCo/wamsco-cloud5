<div>
    <div class="pos dvh-100 d-flex flex-column position-fixed w-100" wire:poll.visible.20s> 
        {{-- Debut chargement --}}        
        <div wire:loading class="chargement_pos">
            <label for=""></label>
            <img src="storage/default/circle_loading.gif" width="64" height="64" style="background: #28282800; margin-left: -3px;">
        </div>
        {{-- Fin chargement --}} 
        <div class="pos-content flex-grow-1 overflow-auto" style="background-color: #7c7f89">
            <div class="card" style="box-shadow: 0 0;">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title d-none d-md-block d-lg-block"><i class="fa fa-tv"></i> Cuisine</h5>
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs margin_ajuste border-0" role="tablist">                        
                        <li class="nav-item">
                            <a class="nav-link" style="font-size: 13px;" href="{{asset('cuisine?active=5&champ=1-3')}}" wire:navigate>Toutes ({{$emplacement_allCount}})</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" style="font-size: 13px;" href="{{asset('cuisine_prepare?active=5&champ=1-3')}}" wire:navigate>A préparer ({{$emplacement_prepaCount}})</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" style="font-size: 13px;" href="{{asset('cuisine_encours?active=5&champ=1-3')}}" wire:navigate>En cours ({{$emplacement_encourCount}})</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" style="font-size: 13px;" href="{{asset('cuisine_terminer?active=5&champ=1-3')}}" wire:navigate>Terminer ({{$emplacement_terminerCount}})</a>
                        </li>
                    </ul>
                    <h5 class="card-title d-none d-md-block"></h5>
                    <!-- Tab panes --> 
                </div>
                <div class="card-body no_bordure" style="background-color: #7c7f89">                                
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsivek">                                   
                                <div class="tab-content">                                    
                                    <div id="encours" class="container-fluid px-0">                                                   
                                        <div class="card_complete_cmd position-relative d-grid flex-grow-1 h-100 gap-3 text-reset p-3 overflow-auto" style="zoom:1">
                                            @foreach($emplacement_encour as $emplacement_encours) 
                                                <section class="position-relative w-100 mx-auto mb-auto overflow-hidden rounded entete_card_cuine_color">
                                                    <div class="">
                                                        <div class="d-flex flex-nowrap align-items-center justify-content-between px-1 pb-1 fw-bold gap-1 entete_card_cuine_color">
                                                            <div class="text-break" style="max-width: 200px;" title="{{$emplacement_encours->nom_table}}">{{Str::limit($emplacement_encours->nom_table, 15)}} (<span class="text-danger" title="{{$emplacement_encours->nom_espace}}">{{Str::limit($emplacement_encours->nom_espace, 12)}}</span>)</div>
                                                            <div class="flex-shrink-0 ps-2 text-end">
                                                                <i class="fa fa-user-circle pe-1" aria-hidden="true"></i><span title="Auteur » {{$emplacement_encours->non_caissiere}}">{{Str::limit($emplacement_encours->non_caissiere, 15)}}</span>
                                                            </div>
                                                        </div>
                                                        <div class="d-grid p-2 bg-100">
                                                            <div class=" d-flex justify-content-between align-items-center">
                                                                <div class="py-1 px-2 rounded-pill fw-semibold" style="background-color: #d94724 !important; color: #ffffff">{{$emplacement_encours->statut}}</div>
                                                                <div class="d-flex gap-0 align-items-center justify-content-center">
                                                                    <span class="px-2 py-1 rounded-pill fw-semibold" style="background-color: @if($emplacement_encours->lieu_consommation == "A emporter") #3b0da6; @elseif($emplacement_encours->lieu_consommation == "Livraison") #0a9682; @else #a63c96; @endif; color: #ffffff;">{{$emplacement_encours->lieu_consommation}}</span>
                                                                </div>
                                                                {{-- <div class="">
                                                                    <div class="rounded-pill py-1 px-0 fw-semibold text-bg-danger">
                                                                        <div class="py-1 px-2 rounded-pill pointer" wire:click.prevent="terminer({{$emplacement_encours->id}})" style="background-color: #f0f0f0 !important; color: #5f5c5c"><i class="fas fa-thumbs-up text-success blink" aria-hidden="true"></i> <span>Terminer ?</span></div>
                                                                    </div>
                                                                </div> --}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="w-100 bg-white overflow-auto">
                                                        @foreach($cmdAttenteLigne as $cmdAttenteLignes)
                                                            @if($emplacement_encours->reference == $cmdAttenteLignes->ref_table)
                                                                <section class="py-1 border-0 text-800 pointer" wire:click.prevent="barrerProd({{$cmdAttenteLignes->id}},'{{$cmdAttenteLignes->statut_cuisine}}')">
                                                                    <div class="d-flex pe-2 @if($cmdAttenteLignes->statut_cuisine == "Barrer") text-decoration-line-through text-muted @endif">                                                                                    
                                                                            <div class="px-2 text-center text-muted">
                                                                                <div class="qte_pro">{{$cmdAttenteLignes->quantite}}x</div>
                                                                            </div>
                                                                            <div class="flex-grow-1 fw-semibold">{{$cmdAttenteLignes->produit}}</div>
                                                                        </div>
                                                                    <div class="">
                                                                        {{-- <div class="d-flex flex-wrap gap-1 pt-2 ms-2 bg-opacity-75"></div> --}}
                                                                    </div>
                                                                </section>
                                                            @endif
                                                        @endforeach                                                                
                                                    </div>
                                                    <div class="cmd_card_footer bg-100">
                                                        <div class="d-flex pt-1">
                                                             @if($emplacement_encours->statut == "En cours")
                                                                <button wire:click.prevent="terminer('{{$emplacement_encours->id}}')" class="btn btn-lg btn-outline-dark fw-semibold flex-fill py-2 rounded" title="Cliquez pour changer le statut de la commande" type="button">
                                                                    <span><i class="fas fa-thumbs-up text-success blink" aria-hidden="true"></i> Terminer ?</span>
                                                                </button> 
                                                            @endif                                                                   
                                                        </div>
                                                    </div>
                                                </section>
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
    {{-- Modal --}}
</div>



    
    

