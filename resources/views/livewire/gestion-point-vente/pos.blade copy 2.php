<div class="row gx-4">
    <div class="col-sm-12">
        @include('flash::message')
        <div class="card mb-0 bg-2k">
            <div class="container-fluid px-0">
                {{-- <div class="pos dvh-100 d-flex flex-column position-fixed w-100" wire:poll.visible.20s>  --}}
                <div class="pos dvh-100 d-flex flex-column position-fixed w-100"> 
                    {{-- Debut chargement --}}        
                    <div wire:loading class="chargement_pos">
                        <label for=""></label>
                        <img src="storage/default/circle_loading.gif" width="64" height="64" style="background: #28282800; margin-left: -3px;">
                    </div>
                    {{-- Fin chargement --}}    
                    <div class="pos-topheader position-relative navbar-height d-flex align-items-center justify-content-between p-2 m-0 bg-view border-bottom">
                        <div class="pos-leftheader d-flex align-items-center gap-2">                
                            @if($activer_fidelite == 1)
                                <a href="#" class="btn btn-sm btn-white d-none d-md-block" data-bs-toggle="modal" data-bs-target="#afficheFideliteModal" wire:click.prevent="affichPoint()()" title="Cliquez pour voir les details point de fidélité" data-toggle="tooltip"><i class="fa fa-podcast"></i> Point</a>
                            @endif
                            <a href="#" class="btn btn-sm btn-white" wire:click.prevent="CreationEmplacement()" title="Cliquez pour ajouter un emplacement" data-toggle="tooltip"><i class="fa fa-plus"></i></a>
                            @if($verifieSession > 0)
                                <a href="#" class="btn btn-sm btn-white" data-bs-toggle="modal" data-bs-target="#selectEmplacementModal" wire:click.prevent="charge()" title="Cliquez pour mettre la commande en attente ou clôturer" data-toggle="tooltip"><i class="fa fa-navicon"></i> Commandes @if($cmdAttenteCount > 0)<span class="blink nbr_attente_new">{{$cmdAttenteCount}}</span>@endif</a>
                            @endif
                            <a href="detail_pos_session?id={{$this->id_session}}&ref={{$this->ref_session}}&active=5&champ=1-1" class="btn btn-sm btn-white" wire:navigate title="Cliquez pour retourner à la session {{$this->ref_session}}" data-toggle="tooltip"><i class="fa fa-refresh"></i> Backend</a>
                        </div>       
                        {{-- <div class="pos-centerheader d-none d-lg-flex position-absolute top-50 start-50 translate-middle w-auto z-1 gap-2 fw-bold">
                            <span class="border-0 text-bleu" title="Nom du Serveur-se"><i class="fa fa-user-circle"></i> {{Str::limit(auth()->user()->name, 20)}}</span> /
                            <span class="border-0" title="Nombre de produit"><i class="fa fa-cube"></i> Produit(s) » <span class="border-0 text-vert">{{$produitCount}}</span> » @if($nomEntrepot) <span title="Nom entrepôt">{{$nomEntrepot}}</span> @else <span class="text-danger blink">Veuillez sélectionner un entrepôt</span> @endif</span>
                        </div> --}}
                        <div class="pos-centerheader d-none d-lg-flex position-absolute top-50 start-50 translate-middle w-auto z-1 gap-2 fw-bold">
                            <span class="border-0 text-bleu" title="Nom du Serveur-se"><i class="fa fa-user-circle"></i> {{Str::limit(auth()->user()->name, 20)}}</span>      
                            @if(!empty($this->filtre))<span class="border-0" title="Nombre de produit"> / &nbsp; <i class="fa fa-cube"></i> Produit(s) » <span class="border-0 text-vert"> {{$produitCount}} @endif</span>»@if($nomEntrepot) <span title="Nom entrepôt » {{$nomEntrepot}}">{{$nomEntrepot}}</span> @else <span class="text-danger blink">Veuillez sélectionner un entrepôt</span> @endif</span>
                        </div>
                        <div class="pos-rightheader d-flex justify-content-end ms-auto gap-1">            
                            <div>
                                <select wire:model.lazy="recherchePar" class="form-control form-select bordure w-auto d-none d-md-flex" style="color:#818181">  
                                    <option value="nom">Rech. par Nom</option>
                                    <option value="reference">Rech. par Référence</option>
                                </select>
                            </div>
                            <div>
                                <label for="query" class="sr-only">Recherche</label>
                                <input type="search" wire:model.live="query" wire:keydown.enter="ScanProduit" autofocus id="query" class="form-control bordure" placeholder="Recherche produit">
                            </div>  
                        </div>           
                    </div>
                    <div class="pos-content flex-grow-1 overflow-auto">
                        <div class="block_pos h-100">
                            <div class="leftpane d-flex flex-column pb-2 border-endk">                
                                @if($test_vide > 0)
                                    <div class="px-0 choix_produit">
                                        <table class="col table table-hover text-nowrap" style="max-width: 100%;box-shadow: rgba(0, 0, 0, 0.35) 0px 1px 3px;">
                                        {{-- <table class="col table table-hover text-nowrap"> --}}
                                            <thead style="background: #ffffff !important; color: rgb(0, 0, 0) !important; font-size: 12px;">  
                                                <tr style="position: sticky;top: 0;background: #ffffff;">
                                                    <th colspan="6">Produit</th>                                                        
                                                    <th class="text-end">Total</th> 
                                                    <th class="text-end"></th> 
                                                    <th class=""></th>       
                                                </tr>
                                            </thead>
                                            <tbody> 
                                                @foreach($ligne_cmd as $ligne_cmds)                           
                                                    <tr>
                                                        <td colspan="4">
                                                            <div style="cursor: pointer;" wire:click.prevent="edit({{$ligne_cmds->id}})">
                                                                {{-- <span style="font-size: 12px;font-weight: 700;">{{substr($ligne_cmds->produit,0,52) > substr($ligne_cmds->produit,0,51) ? substr($ligne_cmds->produit,0,52).'...': $ligne_cmds->produit}}</span><br> --}}
                                                                <span style="font-size: 12px;font-weight: 700;">{{Str::limit($ligne_cmds->produit, 42)}} - {{Str::limit($ligne_cmds->reference, 20)}}</span><br>
                                                                <span style="padding-left: 10px;font-size: 12px;font-weight: 700;color: #28a745 ;">{{$ligne_cmds->quantite}}</span> Article(s) à <span style="font-weight: 700;">{{number_format($ligne_cmds->prix_vente,0,' ',' ')}}</span> {{$devise}} / Unité<br>
                                                                @if($ligne_cmds->remise > 0)
                                                                    <span style="padding-left: 10px;">Avec <span style="font-size: 12px;font-weight: 700;color: #ffc107">{{$ligne_cmds->remise}}%</span> de remise</span><br>
                                                                @endif
                                                                @if($ligne_cmds->montant_tva > 0)
                                                                    <span style="padding-left: 10px;"><span style="font-size: 12px;font-weight: 600;">{{number_format($ligne_cmds->montant_tva,0,' ',' ')}} {{$devise}} </span>de Tva</span><br>
                                                                @endif
                                                                @if($ligne_cmds->montant_precompte > 0)
                                                                    <span style="padding-left: 10px;"><span style="font-size: 12px;font-weight: 600;">{{number_format($ligne_cmds->montant_precompte,0,' ',' ')}} {{$devise}} </span>de Précompte</span><br>
                                                                @endif
                                                                @if(!empty($ligne_cmds->infos))
                                                                    <span style="padding-left: 10px; font-weight: 600;">Note: <span style="font-size: 12px;font-weight: 500;white-space:normal;"> {{Str::limit($ligne_cmds->infos, 141)}}</span></span>
                                                                @endif
                                                            </div>                                                                        
                                                        </td>                                                                   
                                                        <td colspan="3" class="taille text-end" wire:click.prevent="edit({{$ligne_cmds->id}})"> 
                                                            @if($ligne_cmds->offrir == "Oui")
                                                                <span style="padding-left: 10px;font-size: 12px;font-weight: 700;color: #ff0002;">Offert</span>
                                                            @else
                                                                <span style="font-size: 12px;font-weight: 700;">{{number_format($ligne_cmds->montant_ttc,0,' ',' ')}}</span> {{$devise}}<br>
                                                            @endif
                                                        </td>
                                                        <td class="taille text-end pointer" wire:click.prevent="edit({{$ligne_cmds->id}})"><i class="fa fa-pencil text-white-900"></i></td>
                                                        <td class="taille">
                                                            @if($this->etat == "Brouillon")
                                                                @if($confirmer === $ligne_cmds->id)
                                                                    <a wire:click.prevent="supprimer({{$ligne_cmds->id}}, {{$ligne_cmds->id_produit}}, {{$ligne_cmds->id_entrepot}})" style="font-size: 9px;"><i class="fa fa-trash-alt bg-red text-white w-32 px-1 py-1 rounded-1 pointer blink" title="Confirmer la suppression" data-toggle="tooltip"> ?</i></a>
                                                                @else
                                                                    <a wire:click.prevent="confirmerDelete({{$ligne_cmds->id}})"><i class="fa fa-trash-alt pointer supprimer" title="Cliquez pour supprimer" data-toggle="tooltip"></i></a>
                                                                @endif
                                                            @endif
                                                        </td>                                                                                                                     
                                                    </tr>
                                                    @if($ids === $ligne_cmds->id) 
                                                        <tr>  
                                                            @include('livewire.gestion-point-vente.ajout_donnees')     
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="px-2">
                                        <table class="col table table-hoverl text-nowrap">
                                            <thead style="background: #ffffff !important; color: rgb(0, 0, 0) !important; font-size: 12px;">  
                                                <tr style="position: sticky;top: 0;background: #ffffff;">
                                                    <th colspan="6">Produit</th>                                                        
                                                    <th class="text-end">Total</th> 
                                                    <th class="text-end"></th> 
                                                    <th class=""></th>       
                                                </tr>
                                            </thead>
                                            <tbody>                            
                                                <tr>
                                                    <td colspan="9" class="retire text-center fw-bold">
                                                        <span class="img_favi"><i class="fa fa-shopping-cart"></i> </span> <br>
                                                        <span class="titre_cmd_vide">Le panier est vide</span>
                                                    </td>                                                  
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                                <div class="pads px-0 bloc_paie">                    
                                    <div class="affichTotauxPv"> 
                                        @if($reglementClientCount > 0)                          
                                            <div class="test">
                                                @foreach($reglementClient as $reglementClients)                                    
                                                    <div class="payer_le fw-semibold">
                                                        <div style="display: flex;justify-content: space-between;">
                                                            <div style="display:flex; justify-content:space-between; width:90%">
                                                                <div style="font-style: italic; font-weight:700; color:#7a7a7a;">Payé le {{date('d-m-Y H:i', strtotime($reglementClients->created_at))}} ({{$reglementClients->mode_reglement}})</div>
                                                                <div style="color:#7a7a7a; font-weight:700;">{{number_format($reglementClients->montant_regler,0,',',' ')}} {{$devise}}</div>
                                                            </div>
                                                            <div class="taille">
                                                                @if($confirmation === $reglementClients->id)
                                                                    <a wire:click.prevent="effacer({{$reglementClients->id}}, {{$reglementClients->id_compte_bancaire}})" style="font-size: 9px;"><i class="fa fa-trash-alt bg-red text-white w-32 px-1 py-1 rounded-1 pointer blink" title="Confirmer la suppression" data-toggle="tooltip"> ?</i></a>
                                                                @else
                                                                    <a wire:click.prevent="confirmationDelete({{$reglementClients->id}})"><i class="fa fa-trash-alt pointer supprimer" title="Cliquez pour supprimer" data-toggle="tooltip"></i></a>
                                                                @endif
                                                            </div>    
                                                        </div>
                                                    </div>                                   
                                                @endforeach
                                            </div> 
                                        @endif                          
                                        <div class="d-flex justify-content-between"> 
                                            <div class="montPV">Produits » <span class="text-info fw-semibold">{{$nbreCmd}}</span></div>
                                            <div class="montPV">Quantité » <span class="text-blue fw-semibold">{{$QteCmd}}</span></div>
                                        </div>
                                        <div class="d-flex justify-content-between"> 
                                            <div class="montPV">Total</div>
                                            <div class="montPV"><span class="text-vert">{{number_format($montant_ttc,0,' ',' ')}} <span class="h6">{{$devise}}</span></span></div>
                                        </div>
                                        <div class="d-flex justify-content-between"> 
                                            <div class="prixPv">Montant dû</div>
                                            <div class="prixPv"><span class="text-danger">{{number_format($reste_a_percevoir,0,' ',' ')}} <span class="h6">{{$devise}}</span></span></div>
                                        </div>
                                    </div>
                                    {{-- <div class="d-flex justify-content-between gap-2 w-100 py-2 px-1 flex-wrap"> --}}
                                    <div class="d-flex gap-2 w-100 py-2 px-1 flex-wrap">
                                        <button class="btn btn-light btn-lg lh-lg text-truncate w-auto rounded-1 px-2 @if($test_posFcltEnteteExiste == 0) d-none @endif">
                                            @if($test_posFcltEnteteExiste != 0)
                                                @foreach($client as $clients)
                                                    @if(!empty($clients->nom_client))
                                                        <div class="text-black text-action" data-bs-toggle="modal" data-bs-target="#SelectClientModal" wire:click.prevent="charge()" title="Cliquez pour selectionner le client" data-toggle="tooltip"><i class="fa fa-user-plus"></i> {{Str::limit($clients->nom_client, 20)}}</div>
                                                    @else 
                                                        <div class="text-black text-action" data-bs-toggle="modal" data-bs-target="#SelectClientModal" wire:click.prevent="charge()" title="Cliquez pour selectionner le client" data-toggle="tooltip"><i class="fa fa-user-plus blink"></i> Client</div>
                                                    @endif
                                                @endforeach
                                            @else 
                                                @if($test_vide > 0)
                                                    <div class="text-black text-action" data-bs-toggle="modal" data-bs-target="#SelectClientModal" wire:click.prevent="charge()" title="Cliquez pour selectionner le client" data-toggle="tooltip"><i class="fa fa-user-plus blink"></i> Client</div>
                                                @endif
                                            @endif
                                        </button>
                                        @if($test_vide > 0)
                                            <a href="facturationclt-pdf?id={{$idFclt}}&code={{$code_fact}}&format=A4&pos=pv" class="btn btn-light btn-lg lh-lg text-black flex-shrink-0 rounded-1 px-2" title="Cliquez pour imprimer A4" data-toggle="tooltip" target="_blank"><i class="fa fa-print"></i>A4</a>
                                            <a href="facturationclt-pdf?id={{$idFclt}}&code={{$code_fact}}&format=ticket&pos=pv" class="btn btn-light btn-lg lh-lg text-black flex-shrink-0 rounded-1 px-2" title="Cliquez pour imprimer ticket" data-toggle="tooltip" target="_blank" style="color: #161a1d;"><i class="fa fa-print"></i>Ticket</a>
                                            <button class="btn btn-light btn-lg lh-lg text-black w-auto rounded-1 px-2" data-bs-toggle="modal" data-bs-target="#afficheChoixConsoModal" wire:click="affiChoixConso()">{{$this->lieu_consommation}}</button>
                                        @endif
                                        {{-- <button class="btn btn-light btn-lg flex-shrink-0 ms-auto text-black rounded-1 d-none d-lg-block"><i class="fa fa-fw fa-ellipsis-v" aria-hidden="true"></i></button> --}}
                                    </div>
                                    <div class="d-flex flex-column gap-2">
                                        <div class="d-flex flex-column gap-2">
                                            <div class="validation d-flex gap-2 px-1">
                                                @if($test_vide > 0)
                                                    @if($this->etat == "Payée" || $this->etat == "Impayée")
                                                        <button class="button btn btn-secondary btn-lg py-3 d-flex align-items-center justify-content-center flex-fill rounded-1" wire:click="NewCommande()" title="Cliquez pour passer à une nouvelle commmande" data-toggle="tooltip">Enregistrer commande</button>
                                                    @else
                                                        <button class="button btn btn-secondary btn-lg py-3 d-flex align-items-center justify-content-center flex-fill rounded-1" wire:click="AffichePaie()" data-bs-toggle="modal" data-bs-target="#saissieReglementPosModal" title="Cliquez pour effectuer le paiement" data-toggle="tooltip">Paiement</button>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>                            
                            <div class="rightpane d-flex flex-column flex-grow-1 bg-100">
                                <!-- Catégories -->
                                <div class="px-1 py-1 gap-2 bg-view choix_catg">
                                    <a href="#" class="btn btn-sm btn-black my-1"
                                    wire:click.prevent="filtreAll()"
                                    title="Cliquez pour afficher tous les produits"
                                    data-toggle="tooltip">
                                        <i class="fa fa-refresh"></i>
                                    </a>
                                    @foreach($categorieProd as $categorieProds)
                                        <a href="#" class="btn btn-sm btn-black my-1"
                                        wire:click.prevent="filtres('{{$categorieProds->nom_categorie}}')"
                                        title="Cliquez pour filtrer les catégories"
                                        data-toggle="tooltip">
                                            <i class="fa fa-clone"></i>
                                            {{ substr($categorieProds->nom_categorie, 0, 22) > substr($categorieProds->nom_categorie, 0, 21)
                                                ? substr($categorieProds->nom_categorie, 0, 22).'...'
                                                : $categorieProds->nom_categorie }}
                                        </a>
                                    @endforeach
                                </div>
                                <!-- Zone produits avec scroll -->
                                <div class="px-1 py-1 gap-2">
                                    <div class="bloc_select_prod" style="height: calc(100vh - 200px); overflow-y: auto;">
                                        @foreach($categorieProd as $categorieProds)
                                            @foreach($stockProd as $stockProds)
                                                @if($categorieProds->nom_categorie == $stockProds->categorie)
                                                    <article class="product" wire:click.prevent="choisir({{$stockProds->id}})">
                                                        <div class="product-img">
                                                            @if($stockProds->image != null)
                                                                <img src="storage/{{$stockProds->image}}" class="" alt="">
                                                            @else
                                                                <img src="storage/default/image.png" class="" alt="">
                                                            @endif
                                                            <span class="price-tag">
                                                                {{$stockProds->prix_vente_unitaire}} {{$devise}}
                                                            </span>
                                                            @if($stockProds->quantite > $stockProds->limite_stock_alerte)

                                                                <span class="quantite-tag badge badge-success">
                                                                    {{$stockProds->quantite}}
                                                                </span>

                                                            @elseif($stockProds->quantite <= $stockProds->limite_stock_alerte
                                                                && $stockProds->quantite != 0)

                                                                <span class="quantite-tag badge badge-info">
                                                                    {{$stockProds->quantite}}
                                                                </span>

                                                            @else

                                                                <span class="quantite-tag blink badge badge-danger">
                                                                    {{$stockProds->quantite}}
                                                                </span>

                                                            @endif

                                                        </div>

                                                        <div class="product-name">
                                                            {{$stockProds->nom_produit}}
                                                        </div>

                                                    </article>

                                                @endif

                                            @endforeach
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
    @include('livewire.gestion-point-vente.selection_client') 
    @include('livewire.gestion-point-vente.creation_client') 
    @include('livewire.gestion-point-vente.saisie_paiement_pos') 
    @include('livewire.gestion-point-vente.emplacement.select_emplacement') 
    @include('livewire.gestion-point-vente.point_fidelite') 
    @include('livewire.gestion-point-vente.choix_consommation')
</div>



    
    
