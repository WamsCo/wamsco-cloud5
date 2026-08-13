<div class="row gx-4">
    <div class="col-sm-12">
        @include('flash::message')
        <div class="card mb-0 bg-2k">
            <div class="container-fluid px-0">
                <div class="pos dvh-100 d-flex flex-column position-fixed w-100" wire:poll.visible.30s> 
                    {{-- Debut chargement --}}        
                    <div wire:loading class="chargement_pos">
                        <label for=""></label>
                        <img src="storage/default/circle_loading.gif" width="64" height="64" style="background: #28282800; margin-left: -3px;">
                    </div>                   
                    <div class="pos-topheader bg-view border-bottom">                        
                        <div class="pos-leftheader">
                            @if($activer_fidelite == 1)
                                <a href="#" class="btn btn-sm btn-white pos-action-btn d-none d-md-inline-flex" data-bs-toggle="modal" data-bs-target="#afficheFideliteModal" wire:click.prevent="affichPoint()" title="Voir les détails des points de fidélité"><i class="fa fa-podcast"></i> <span>Point</span></a>
                            @endif
                            {{-- Ajouter emplacement --}}
                            <a href="#" class="btn btn-sm btn-white pos-action-btn" wire:click.prevent="CreationEmplacement()" title="Ajouter un emplacement"><i class="fa fa-plus"></i> <span class="d-none d-lg-inline">Emplacement</span></a>
                            {{-- Commandes --}}
                            @if($verifieSession > 0)
                                <a href="#" class="btn btn-sm btn-white pos-action-btn" data-bs-toggle="modal" data-bs-target="#selectEmplacementModal" wire:click.prevent="charge()" title="Mettre la commande en attente ou clôturer">
                                    <i class="fa fa-navicon"></i><span class="d-none d-lg-inline"> Commandes</span>
                                    @if($cmdAttenteCount > 0)<span class="blink nbr_attente_new">{{ $cmdAttenteCount }}</span>@endif
                                </a>
                            @endif
                            {{-- Backend --}}
                            <a href="detail_pos_session?id={{ $this->id_session }}&ref={{ $this->ref_session }}&active=5&champ=1-1" class="btn btn-sm btn-white pos-action-btn" wire:navigate title="Retourner à la session {{ $this->ref_session }}"><i class="fa fa-refresh"></i> <span class="d-none d-lg-inline">Backend</span></a>
                        </div>                       
                        <div class="pos-centerheader">
                            <span class="pos-user text-bleu"><i class="fa fa-user-circle"></i> {{ Str::limit(auth()->user()->name, 20) }}</span>
                            @if(!empty($this->filtre))
                                <span class="pos-info-separator"> / </span><span class="pos-products"><i class="fa fa-cube"></i> Produit(s) <strong>{{ $produitCount }}</strong></span>
                            @endif
                            <span class="pos-separator">»</span>
                            @if($nomEntrepot)
                                <span class="pos-entrepot text-black" title="Nom entrepôt : {{ $nomEntrepot }}"><i class="fa fa-building"></i> {{ Str::limit($nomEntrepot, 25) }}</span>
                            @else
                                <span class="text-danger blink pos-warning"><i class="fa fa-warning"></i>Veuillez sélectionner un entrepôt</span>
                            @endif
                        </div>                        
                        <div class="pos-rightheader">                           
                            <select wire:model.lazy="recherchePar" class="form-control form-select bordure pos-search-type d-none d-md-block">
                                <option value="nom">Rech. par Nom</option>
                                <option value="reference">Rech. par Référence</option>
                            </select>
                            {{-- Recherche produit --}}
                            <div class="pos-search">
                                <label for="query" class="visually-hidden">Recherche produit</label>
                                <i class="fa fa-search pos-search-icon"></i> <input type="search" wire:model.live="query" wire:keydown.enter="ScanProduit" autofocus id="query" class="form-control bordure pos-search-input" placeholder="Recherche produit">
                            </div>
                        </div>
                    </div>
                    <div class="pos-content flex-grow-1 overflow-auto">
                        <div class="block_pos h-100">                              
                            <div class="pos-cart-layout">                                
                                <section class="pos-cart-section">
                                    @if($test_vide > 0)
                                        {{-- En-tête panier --}}
                                        <div class="pos-cart-header">
                                            <div class="pos-cart-title"><i class="fa fa-shopping-cart"></i> <span>Panier</span><span class="pos-cart-count">{{ $nbreCmd }}</span></div>
                                            <div class="pos-cart-header-right">{{ $QteCmd }} article(s)</div>
                                        </div>
                                        {{-- Liste produits --}}
                                        <div class="pos-products-list" style="background: #f9fafb;">
                                            @foreach($ligne_cmd as $ligne_cmds)
                                                <article class="pos-product-card">  
                                                    <div class="pos-product-main" wire:click.prevent="edit({{$ligne_cmds->id}})">
                                                        <div class="pos-product-title">{{ $ligne_cmds->produit }}</div>
                                                        @if(!empty($ligne_cmds->reference))
                                                            <div class="pos-product-reference">Réf. : {{ $ligne_cmds->reference }}</div>
                                                        @endif
                                                        <div class="pos-product-meta">
                                                            <span class="pos-qty">{{ $ligne_cmds->quantite }}</span>
                                                            <span>× {{ number_format($ligne_cmds->prix_vente,0,' ',' ') }} {{ $devise }}</span>
                                                        </div>
                                                        @if($ligne_cmds->remise > 0)
                                                            <div class="pos-product-detail">
                                                                <i class="fa fa-tag"></i> Remise <strong>{{ $ligne_cmds->remise }}%</strong>
                                                            </div>
                                                        @endif
                                                        @if($ligne_cmds->montant_tva > 0)
                                                            <div class="pos-product-detail">
                                                                <i class="fa fa-percent"></i> TVA : <strong>{{ number_format($ligne_cmds->montant_tva,0,' ',' ') }} {{ $devise }}</strong>
                                                            </div>
                                                        @endif
                                                        @if($ligne_cmds->montant_precompte > 0)
                                                            <div class="pos-product-detail">
                                                                <i class="fa fa-calculator"></i> Précompte : <strong>{{ number_format($ligne_cmds->montant_precompte,0,' ',' ') }} {{ $devise }}</strong>
                                                            </div>
                                                        @endif
                                                        @if(!empty($ligne_cmds->infos))
                                                            <div class="pos-product-note"><strong>Note :</strong>{{ $ligne_cmds->infos }}</div>
                                                        @endif
                                                    </div>  
                                                    <div class="pos-product-total"
                                                        wire:click.prevent="edit({{$ligne_cmds->id}})">
                                                        @if($ligne_cmds->offrir == "Oui")
                                                            <span class="pos-free-product">Offert</span>
                                                        @else
                                                            <strong>{{ number_format($ligne_cmds->montant_ttc,0,' ',' ') }}</strong>
                                                            <small>{{ $devise }}</small>
                                                        @endif
                                                    </div>                                                    
                                                    <div class="pos-product-actions">
                                                        <button type="button" class="pos-edit-btn" wire:click.prevent="edit({{$ligne_cmds->id}})" title="Modifier"><i class="fa fa-pencil"></i></button>
                                                        @if($this->etat == "Brouillon")
                                                            @if($confirmer === $ligne_cmds->id)
                                                                <button type="button" class="pos-confirm-delete blink btn-xs" wire:click.prevent="supprimer({{$ligne_cmds->id}},{{$ligne_cmds->id_produit}},{{$ligne_cmds->id_entrepot}})"><i class="fa fa-trash"> ?</i></button>
                                                            @else
                                                                <button type="button" class="pos-delete-btn" wire:click.prevent="confirmerDelete({{$ligne_cmds->id}})" title="Supprimer"><i class="fa fa-trash-alt"></i></button>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </article>
                                                @if($ids === $ligne_cmds->id)
                                                    <div class="pos-product-edit">
                                                        @include('livewire.gestion-point-vente.ajout_donnees')
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @else      
                                        <div class="pos-empty">
                                            <div class="pos-empty-icon">
                                                <i class="fa fa-shopping-cart"></i>
                                            </div>
                                            <h4>Le panier est vide</h4>
                                            <p>Sélectionnez un produit pour commencer</p>
                                        </div>
                                    @endif
                                </section>
                                <section class="pos-bottom-section">   
                                    @if($reglementClientCount > 0)
                                        <div class="pos-payments">
                                            <div class="pos-section-label"><i class="fa fa-credit-card"></i> Paiements</div>
                                            @foreach($reglementClient as $reglementClients)
                                                <div class="pos-payment">
                                                    <div class="pos-payment-left">
                                                        <div class="pos-payment-date">
                                                            {{ date('d-m-Y H:i',strtotime($reglementClients->created_at))}}
                                                        </div>
                                                        <div class="pos-payment-mode">{{ $reglementClients->mode_reglement }}</div>
                                                    </div>
                                                    <div class="pos-payment-amount">{{ number_format($reglementClients->montant_regler,0,',',' ') }} {{ $devise }}</div>
                                                    @if($confirmation === $reglementClients->id)
                                                        <button type="button" class="pos-confirm-delete" style="font-size: 8px;" wire:click.prevent="effacer({{$reglementClients->id}},{{$reglementClients->id_compte_bancaire}})"><i class="fa fa-trash blink"> ?</i></button>
                                                    @else
                                                        <button type="button" class="pos-delete-btn" wire:click.prevent="confirmationDelete({{$reglementClients->id}})"><i class="fa fa-trash"></i></button>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    <div class="pos-summary">
                                        {{-- <div class="pos-summary-line"><span>Produits</span><strong>{{ $nbreCmd }}</strong></div> --}}
                                        {{-- <div class="pos-summary-line"><span>Quantité</span><strong>{{ $QteCmd }}</strong></div> --}}
                                        <div class="pos-summary-line pos-total-line">
                                            <span>Total</span>
                                            <strong>{{ number_format($montant_ttc, 0,' ', ' ') }}<small>{{ $devise }}</small></strong>
                                        </div>
                                        <div class="pos-summary-line pos-due-line"><span>Montant dû</span>
                                            <strong>{{ number_format($reste_a_percevoir, 0,' ',' ') }}<small>{{ $devise }}</small></strong>
                                        </div>
                                    </div>                                
                                    <div class="pos-tools">
                                        {{-- CLIENT --}}
                                        @if($test_posFcltEnteteExiste != 0)
                                            @foreach($client as $clients)
                                                <button type="button" class="pos-toolk btn btn-sm btn-outline-secondary flex-fill fw-semibold ms-auto" data-bs-toggle="modal" data-bs-target="#SelectClientModal" wire:click.prevent="charge()">
                                                    <i class="fa fa-user-plus blink"></i>
                                                    @if(!empty($clients->nom_client))
                                                        {{ Str::limit($clients->nom_client, 20) }}
                                                    @else
                                                        Client
                                                    @endif
                                                </button>
                                            @endforeach
                                        @elseif($test_vide > 0)
                                            <button type="button" class="pos-toolk btn btn-sm btn-outline-secondary flex-fill fw-semibold ms-auto" data-bs-toggle="modal" data-bs-target="#SelectClientModal" wire:click.prevent="charge()">
                                                <i class="fa fa-user-plus"></i>Client
                                            </button>
                                        @endif
                                        @if($test_vide > 0)
                                            {{-- A4 --}}
                                            <a href="facturationclt-pdf?id={{$idFclt}}&code={{$code_fact}}&format=A4&pos=pv" target="_blank" class="pos-toolk btn btn-sm btn-outline-secondary flex-fill fw-semibold ms-auto"><i class="fa fa-print"></i> A4</a>
                                            {{-- TICKET --}}
                                            <a href="facturationclt-pdf?id={{$idFclt}}&code={{$code_fact}}&format=ticket&pos=pv" target="_blank" class="pos-toolk btn btn-sm btn-outline-secondary flex-fill fw-semibold ms-auto"><i class="fa fa-print"></i> Ticket</a>
                                           {{-- LIEU --}}
                                            <button type="button" class="pos-toolk pos-location btn btn-sm btn-outline-secondary flex-fill fw-semibold ms-auto" data-bs-toggle="modal" data-bs-target="#afficheChoixConsoModal" wire:click="affiChoixConso()">
                                                <i class="fa fa-map-marker"></i> {{ $this->lieu_consommation }}
                                            </button>
                                        @endif
                                    </div>    
                                    @if($test_vide > 0)
                                        <div class="pos-payment-button">
                                            @if($this->etat == "Payée" || $this->etat == "Impayée")
                                                <button type="button" class="pos-primary-action" wire:click="NewCommande()"><i class="fa fa-check-circle"></i> Enregistrer la commande</button>
                                            @else
                                                <button type="button" class="pos-primary-action" wire:click="AffichePaie()" data-bs-toggle="modal" data-bs-target="#saissieReglementPosModal"><i class="fa fa-money"></i> Paiement</button>
                                            @endif
                                        </div>
                                    @endif
                                </section>
                            </div>  
                            <div class="rightpane d-flex flex-column flex-grow-1 bg-100">
                                <!-- Catégories -->  
                                <div class="choix_catg bg-view px-2 py-1">
                                    <div class="categories-wrapper">
                                        {{-- Bouton gauche --}}
                                        <button type="button" class="category-nav category-nav-left bg-secondary" onclick="scrollCategories(-300)" aria-label="Catégories précédentes"><i class="fa fa-chevron-left"></i></button>
                                        {{-- Zone des catégories --}}
                                        <div class="categories-container" id="categoriesContainer">
                                            {{-- Toutes les catégories --}}
                                            <a href="#" class="btn btn-sm btn-black categorie-btn" wire:click.prevent="filtreAll()" title="Afficher tous les produits"><i class="fa fa-refresh me-1"></i> Tous</a>
                                            {{-- Catégories --}}
                                            @foreach($categorieProd as $categorieProds)
                                                <a href="#" class="btn btn-sm btn-black categorie-btn" wire:click.prevent="filtres(@js($categorieProds->nom_categorie))" title="Filtrer par {{ $categorieProds->nom_categorie }}">
                                                    <i class="fa fa-clone me-1"></i> {{Str::limit($categorieProds->nom_categorie, 22)}}
                                                </a>
                                            @endforeach
                                        </div>
                                        {{-- Bouton droit --}}
                                        <button type="button" class="category-nav category-nav-right bg-secondary" onclick="scrollCategories(300)" aria-label="Catégories suivantes"><i class="fa fa-chevron-right"></i></button>
                                    </div>
                                </div>
                                <!-- Zone produits avec scroll -->
                                <div class="px-1 py-1 gap-2">
                                    <div class="bloc_select_prod" style="height: calc(100vh - 153px); overflow-y: auto;">
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
                                                                <span class="quantite-tag badge badge-success">{{$stockProds->quantite}}</span>
                                                            @elseif($stockProds->quantite <= $stockProds->limite_stock_alerte && $stockProds->quantite != 0)
                                                                <span class="quantite-tag badge badge-info">{{$stockProds->quantite}}</span>
                                                            @else
                                                                <span class="quantite-tag blink badge badge-danger">{{$stockProds->quantite}}</span>
                                                            @endif
                                                        </div>
                                                        <div class="product-name">{{$stockProds->nom_produit}}</div>
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

{{-- pour sroller les categogies --}}
<script>
    function scrollCategories(distance) {
        const container = document.getElementById('categoriesContainer');
        if (!container) {
            return;
        }
        container.scrollBy({
            left: distance,
            behavior: 'smooth'
        });
    }
</script>

    
    
