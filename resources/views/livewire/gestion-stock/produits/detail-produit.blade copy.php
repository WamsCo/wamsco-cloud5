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
                @include('flash::message')
                <div class="card mb-0 bg-2k">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs margin_ajuste border-0" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#home">Produit</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#vente">Prix</a>
                            </li>
                            {{-- <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#achat">Prix d'achat</a>
                            </li> --}}
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#stock">Stock</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#evenement">Événements</a>
                            </li>
                        </ul>
                        <!-- Tab panes -->                        
                        <div class="d-flex align-items-center justify-content-between gap-1">
                            <div class="d-flex align-items-center justify-content-between gap-1"> 
                                {{-- <a href="facturationcltkk-pdf?code=" class="btn btn-sm btn-default" title="Cliquez pour imprimer" data-toggle="tooltip" style="color: #161a1d;"><i class="fa fa-print"></i>Ticket</a> --}}
                                {{-- <a href="facturationclt-pdf?code=Fact/191224/a0adc871" class="btn btn-sm btn-default" title="Cliquez pour imprimer" data-toggle="tooltip" target="blank"><i class="fa fa-print"></i>A4</a> --}}
                            </div>                            
                            <div class="d-flex align-items-center justify-content-between gap-1">                            
                                <a href="produit?active=4&champ=1-1&choix=2" wire:navigate class="btn btn-sm btn-bleu d-none d-md-block ms-auto"><i class="fa fa-chevron-left"></i> Retour liste</a>
                                <button class="btn btn-sm btn-default new_color" @if($this->ids) wire:click.prevent="precedant({{$this->ids}})" @else wire:click.prevent="precedant({{$this->id}})" @endif title="Précédant" data-toggle="tooltip"><i class="fa fa-chevron-left"></i></button>
                                <a href="#" class="btn btn-sm btn-default new_color" @if($this->ids) wire:click.prevent="suivant({{$this->ids}})" @else wire:click.prevent="suivant({{$this->id}})" @endif title="Suivant" data-toggle="tooltip"><i class="fa fa-chevron-right"></i></a>
                            </div>
                        </div>
                    </div>
                    @foreach($produit as $produits)
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <div class="">
                                    <div class="photoref">
                                        @if($produits->image != null)
                                            <td><img class="img_detail_prod" src="storage/{{$produits->image}}" data-bs-toggle="modal" data-bs-target="#imageProduitModal" wire:click.prevent="edit({{$produits->id}})"/></td>
                                        @else
                                            <td><img class="img_detail_prod" src="storage/default/image.png" data-bs-toggle="modal" data-bs-target="#imageProduitModal" wire:click.prevent="edit({{$produits->id}})"/></td>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <h5 class="text-bleu">{{$produits->nom_produit}}</h5>
                                    <span class="text-success">{{$produits->reference}}</span> <br>
                                    @if($produits->code_barre)
                                        <span class="text-bleu">Code-barres »</span> <span class="text-danger fw-semibold">{{$produits->code_barre}}</span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <div class="statusref">
                                    @if($produits->etat == 1)
                                        <span class="badge bg-success py-1" title="Activer">Activer</span>
                                    @else
                                        <span class="badge bg-danger py-1" title="Désactiver">Désactiver </span>
                                    @endif
                                </div>
                            </div>
                        </div>                    
                        <div class="card-body no_bordure border-top taille_ecran_session">
                            <div class="row mx-0 pt-0">
                                <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12 px-0 mb-1 border-end border-bottom rounded-bottom">                        
                                    <div class="row">
                                        <div class="col-md-12">                                  
                                            <div class="tab-content">
                                                <div id="home" class="container-fluid tab-pane fade pas_bordure"> 
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="table-responsive">   
                                                                <table class="table text-nowrap m-0">                                        
                                                                    <tbody>
                                                                        <tr>                                                                     
                                                                            <td class="titlefield text-muted fw-semibold">Type</td>                                                         
                                                                            <td class="fw-semibold">{{$produits->type_produit}}</td>         
                                                                        </tr> 
                                                                        <tr>                                                                     
                                                                            <td class="titlefield text-muted fw-semibold">Nature de produit <i class="fas fa-info-circle" title="Matière première ou produit fabriqué ou les deux à la fois." data-bs-toggle="tooltip" data-bs-placement="top" style=" vertical-align: middle; cursor: help"></i></td>                                                         
                                                                            <td class="fw-semibold">{{$produits->nature_produit}}</td>         
                                                                        </tr>
                                                                        <tr>                                                                     
                                                                            <td class="titlefield text-muted fw-semibold">Catégories</td>                                                         
                                                                            <td class="fw-semibold">{{$produits->categorie}}</td>         
                                                                        </tr>
                                                                        <tr>                                                                     
                                                                            <td class="titlefield text-muted fw-semibold">Fournisseur</td>                                                         
                                                                            <td class="fw-semibold">{{$produits->fournisseur}}</td>         
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="table-responsive">   
                                                                <table class="table text-nowrap m-0">                                        
                                                                    <tbody>                                                                  
                                                                        <tr>                                                                     
                                                                            <td class="titlefield text-muted fw-semibold">Pays</td>                                                         
                                                                            <td class="fw-semibold">{{$produits->pays_origine}}</td>         
                                                                        </tr>
                                                                        <tr>                                                                     
                                                                            <td class="titlefield text-muted fw-semibold">Date péremption</td>   
                                                                            @if($nbjoursRestant = round((strtotime($produits->date_peremption) - strtotime($dateJour))/(60*60*24)) >= 60)                                                      
                                                                                <td class="text-vert fw-bold"><i class="fa fa-thumbs-up"></i> Reste {{round((strtotime($produits->date_peremption) - strtotime($dateJour))/(60*60*24))}} Jours</td>
                                                                            @elseif($nbjoursRestant = round((strtotime($produits->date_peremption) - strtotime($dateJour))/(60*60*24)) < 60 && $nbjoursRestant = round((strtotime($produits->date_peremption) - strtotime($dateJour))/(60*60*24)) > 30) 
                                                                                <td class="text-primary fw-semibold"><i class="fa fa-exclamation-triangle"></i> {{round((strtotime($produits->date_peremption) - strtotime($dateJour))/(60*60*24))}} Jours avant péremption</td>
                                                                            @elseif($nbjoursRestant = round((strtotime($produits->date_peremption) - strtotime($dateJour))/(60*60*24)) <= 30 && $nbjoursRestant = round((strtotime($produits->date_peremption) - strtotime($dateJour))/(60*60*24)) >= 0) 
                                                                                @if(round((strtotime($produits->date_peremption) - strtotime($dateJour))/(60*60*24)) == 0)
                                                                                    <td class="text-info fw-semibold"><i class="fa fa-exclamation-triangle"></i> Dernier Jour</td>
                                                                                @else
                                                                                    <td class="text-warning fw-semibold"><i class="fa fa-exclamation-triangle"></i> Plus que {{round((strtotime($produits->date_peremption) - strtotime($dateJour))/(60*60*24))}} Jours</td>
                                                                                @endif            
                                                                            @elseif($nbjoursRestant = round((strtotime($produits->date_peremption) - strtotime($dateJour))/(60*60*24)) < 0 && $produits->date_peremption != null)                      
                                                                                <td class="font-13"><span class="blink badge bg-danger"><i class="fa fa-thumbs-down"></i> Produit périmé</span></td>
                                                                            @else
                                                                                <td class="font-13 fw-semibold" style="color: #5f5f5f;"><i class="fa fa-exclamation-triangle"></i> Date non définie</td>
                                                                            @endif      
                                                                        </tr>
                                                                        <tr>                                                                     
                                                                            <td class="titlefield text-muted fw-semibold">Responsable achat</td>                                                         
                                                                            <td class="fw-semibold">{{$produits->responsable_achat}}</td>         
                                                                        </tr>
                                                                        <tr>                                                                     
                                                                            <td class="titlefield text-muted fw-semibold">Description</td>                                                         
                                                                            <td class="fw-semibold">{{Str::limit($produits->description, 40)}}</td>         
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="table-responsive">                                         
                                                        <div class="d-flex align-items-center justify-content-between gap-3 py-3">
                                                            <div class="px-3">
                                                                <a href="#" wire:click.prevent="edit({{$produits->id}})" class="btn btn-sm text-danger border fw-semibold ms-auto" data-bs-toggle="modal" data-bs-target="#imageProduitModal" title="Cliquez pour modifier image" data-toggle="tooltip"><i class="fa fa-camera"></i> Ajouter / Modifier</a>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between gap-2 py-2 px-3">
                                                                <a href="#" wire:click.prevent="edit({{$produits->id}})" data-bs-toggle="modal" data-bs-target="#updateProduitModal" title="Cliquez pour voir les détails" data-toggle="tooltip" class="btn btn-sm btn-secondary fw-semibold ms-auto">Modifier <i class="fa fa-chevron-right"></i></a>
                                                                <a href="nouveau_produit?active=4&champ=1-1&choix=1" wire:navigate class="btn btn-sm btn-danger ms-auto"><i class="fa fa-plus-circle"></i> Nouveau Produit</a>
                                                            </div>
                                                        </div>                                                   
                                                        <div class="d-flex align-items-center justify-content-between gap-3 py-3 px-3">
                                                            <div class="">
                                                                <h5 class="text-bleu"><i class="fas fa-people-carry" style=" color: #393b83;" title="Mouvement de stock"></i> Liste des mouvements de stock ({{$mouvCountAfficher}}/{{$mouvementCount}})</h5>
                                                            </div>
                                                            <div>                                                                                                                        
                                                            </div>
                                                        </div>
                                                        <div class="table-responsive border-top rounded-0">
                                                            <table class="table table-striped table-hover text-nowrap m-0"> 
                                                                <thead>
                                                                    <tr>
                                                                        <th class="fond_entete_table">Date</th>
                                                                        <th class="fond_entete_table">Réf. produit</th>
                                                                        <th class="fond_entete_table">Code Inv./Mouv.</th>
                                                                        <th class="fond_entete_table">Libellé du mouvement</th>
                                                                        <th class="fond_entete_table">Origine</th>
                                                                        <th class="fond_entete_table text-center">Qté</th>
                                                                    </tr>
                                                                </thead>                                       
                                                                <tbody>
                                                                    @foreach($mouvement as $mouvements) 
                                                                        @foreach($listEntrepot as $listEntrepots) 
                                                                            @if($mouvements->id_entrepot == $listEntrepots->id)
                                                                            <tr>                                                                     
                                                                                <td class=""><a href="mouvements?dates={{date('Y-m-d', strtotime($mouvements->created_at))}}&active=4&champ=3-1&choix=3" wire:navigate class="text-bleu fw-bold" title="clique pour voir mouvements">{{date('d/m/Y H:i:s', strtotime($mouvements->created_at))}}</a></td>  
                                                                                @foreach($produit as $produits)  
                                                                                    <td class="text-bleu">{{$produits->reference}}</td>                                                         
                                                                                @endforeach    
                                                                                <td class=""><a href="mouvements?dates={{date('Y-m-d', strtotime($mouvements->created_at))}}&active=4&champ=3-1&choix=3" wire:navigate class="text-bleu fw-bold" title="clique pour voir mouvements">{{$mouvements->code_mouvement}}</a></td>          
                                                                                <td class="fw-semibold">{{$mouvements->libele_mouvement}}</td>  
                                                                                <td class="text-start fw-semibold">
                                                                                    <a href="@if($mouvements->statut == "INV")detail_inventaire?id={{$mouvements->id_inventaire}}&champ=3-1&choix=7 
                                                                                            @elseif($mouvements->statut == "EXP")detail_expedition_clt?id={{$mouvements->id_expedition}}&active=6&champ=1-1&choix=3 
                                                                                            @elseif($mouvements->statut == "RCP")detail_reception_fourni?id={{$mouvements->id_reception}}&active=6&champ=2-1&choix=2
                                                                                            @elseif($mouvements->statut == "POS")detail_pos_session?id={{$mouvements->id_session_pos}}&active=5&champ=1-1
                                                                                            @elseif($mouvements->statut == "OF")detail_ordre_fab?id={{$mouvements->id_ordre_fab}}&active=9&champ=2-1&choix=2 
                                                                                            @endif" 
                                                                                            wire:navigate class="text-bleu">{{$mouvements->origine}}
                                                                                    </a>
                                                                                </td>  
                                                                                <td class="text-center text-vert fw-semibold">
                                                                                    @if($mouvements->quantite > 0)
                                                                                        +{{$mouvements->quantite}}                                                                
                                                                                    @else
                                                                                    <span class="text-danger">{{$mouvements->quantite}}</span>  
                                                                                    @endif
                                                                                </td>      
                                                                            </tr>
                                                                            @endif 
                                                                        @endforeach
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>                                           
                                                </div>
                                                <div id="vente" class="container-fluid tab-pane fade pas_bordure">  
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="table-responsive">   
                                                                <table class="table text-nowrap m-0">                                        
                                                                    <tbody>
                                                                            <tr>                                                                     
                                                                                <td class="titlefield text-muted fw-semibold">Prix de vente</td>                                                         
                                                                                <td class="fw-semibold text-vert">{{number_format($produits->prix_vente,0,',',' ')}} <span class="text-bleu" style="font-size: 11px">{{$devise}}</span></td>         
                                                                            </tr> 
                                                                            <tr>                                                                     
                                                                                <td class="titlefield text-muted fw-semibold">Prix de vente min.</td>                                                         
                                                                                <td class="fw-semibold">{{number_format($produits->prix_vente_min,0,',',' ')}} <span class="text-bleu" style="font-size: 11px">{{$devise}}</span></td>         
                                                                            </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="table-responsive">   
                                                                <table class="table text-nowrap m-0">                                        
                                                                    <tbody>
                                                                            <tr>                                                                     
                                                                                <td class="titlefield text-muted fw-semibold">Taux de taxe par défaut</td>                                                         
                                                                                <td class="fw-semibold">{{$produits->tva}} <span class="text-bleu" style="font-size: 12px">%</span></td>         
                                                                            </tr> 
                                                                            <tr>                                                                     
                                                                                <td class="titlefield text-muted fw-semibold">Prix moyen pondéré Achat (PMP) <i class="fas fa-info-circle" title="Le prix unitaire moyen que nous avons dû dépenser pour obtenir 1 unité de produit dans notre stock." data-bs-toggle="tooltip" data-bs-placement="top" style=" vertical-align: middle; cursor: help"></i></td>                                                         
                                                                                <td class="fw-semibold">{{number_format($produits->prix_achat,2,',',' ')}}<span class="text-bleu" style="font-size: 11px"> {{$devise}}</span></td>         
                                                                            </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="table-responsive">                                         
                                                        <div class="d-flex align-items-center justify-content-between gap-3 py-4">
                                                            <div class="px-3">
                                                                <h5 class="text-bleu"><i class="fas fa-recycle" style=" color: #393b83;" title="Mouvement de stock"></i> Historique des prix précédents ({{$listePrixVenteCount}})</h5>
                                                            </div>
                                                            <div class="px-3">
                                                                <a href="#" wire:click.prevent="edit('{{$produits->id}}')" data-bs-toggle="modal" data-bs-target="#updateProduitModal" title="Cliquez pour voir les détails" data-toggle="tooltip" class="btn btn-sm btn-secondary fw-semibold ms-auto"><i class="fa fa-money-check-alt"></i> Modifier prix</a>
                                                            </div>
                                                        </div>
                                                        <div class="table-responsive border-top rounded-0">
                                                            <table class="table table-striped table-hover text-nowrap m-0"> 
                                                                <thead>
                                                                    <tr>
                                                                        <th class="fond_entete_table">Pratiqués à partir du</th>
                                                                        <th class="fond_entete_table text-center">Base de prix</th>
                                                                        <th class="fond_entete_table text-center">Taux de taxe par défaut</th>
                                                                        <th class="fond_entete_table text-end">Prix d'achat(PMP)</th>
                                                                        <th class="fond_entete_table text-end">Prix de vente</th>
                                                                        <th class="fond_entete_table text-end">Prix de vente min. HT</th>
                                                                        <th class="fond_entete_table">Modifié par</th>
                                                                        <th class="fond_entete_table text-end"></th>
                                                                    </tr>
                                                                </thead>                                       
                                                                <tbody>
                                                                    @foreach($listePrixVente as $listePrixVentes) 
                                                                        <tr>                                                                     
                                                                            <td class="fw-semibold">{{date('d/m/Y H:i:s', strtotime($listePrixVentes->created_at))}}</td>                                                         
                                                                            <td class="text-center">{{$listePrixVentes->base_prix}}</td>                                                         
                                                                            <td class="text-center fw-semibold">{{$listePrixVentes->taux_taxe}}%</td>                                                         
                                                                            <td class="text-end text-bleu fw-semibold">{{number_format($listePrixVentes->prix_achat,2,',',' ')}}</td>                                                         
                                                                            <td class="text-end text-vert fw-semibold">{{number_format($listePrixVentes->prix_vente,0,',',' ')}}</td>                                                         
                                                                            <td class="text-end fw-semibold">{{number_format($listePrixVentes->prix_vente_min,0,',',' ')}}</td>                                                         
                                                                            <td class="">{{$listePrixVentes->nom_user}}</td> 
                                                                            <td class="taille_icon text-end">
                                                                                <a wire:click.prevent="supprimerPrix({{$listePrixVentes->id}}, {{$listePrixVentes->id_produit}})" class="btn btn-outline-muted btn-xs"  title="Cliquez pour supprimer" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-trash"></i></a>
                                                                            </td> 
                                                                        </tr> 
                                                                    @endforeach 
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>                                           
                                                </div>                                            
                                                <div id="stock" class="container-fluid tab-pane active pas_bordure">  
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="table-responsive">   
                                                                <table class="table text-nowrap m-0">                                        
                                                                    <tbody>
                                                                            <tr>                                                                     
                                                                                <td class="titlefield text-muted fw-semibold">Type</td>                                                         
                                                                                <td class="fw-semibold">{{$produits->type_produit}}</td>         
                                                                            </tr> 
                                                                            <tr>                                                                     
                                                                                <td class="titlefield text-muted fw-semibold">Nature de produit <i class="fas fa-info-circle" title="Matière première ou produit fabriqué ou les deux à la fois." data-bs-toggle="tooltip" data-bs-placement="top" style=" vertical-align: middle; cursor: help"></i></td>                                                         
                                                                                <td class="fw-semibold">{{$produits->nature_produit}}</td>         
                                                                            </tr>
                                                                            <tr>                                                                     
                                                                                <td class="titlefield text-muted fw-semibold">Prix moyen pondéré (PMP) <i class="fas fa-info-circle" title="Le prix unitaire moyen que nous avons dû dépenser pour obtenir 1 unité de produit dans notre stock." data-bs-toggle="tooltip" data-bs-placement="top" style=" vertical-align: middle; cursor: help"></i></td>                                                         
                                                                                <td class="fw-semibold">{{number_format($produits->prix_achat,2,',',' ')}} <span class="text-bleu" style="font-size: 11px">{{$devise}}</span></td>         
                                                                            </tr>
                                                                            <tr>                                                                     
                                                                                <td class="titlefield text-muted fw-semibold">Prix de vente </td>                                                         
                                                                                <td class="fw-semibold text-vert">{{number_format($produits->prix_vente,0,',',' ')}} <span class="text-bleu" style="font-size: 11px">{{$devise}}</span></td>         
                                                                            </tr>
                                                                            <tr>                                                                     
                                                                                <td class="titlefield text-muted fw-semibold">Prix de vente min.</td>                                                         
                                                                                <td class="fw-semibold">{{number_format($produits->prix_vente_min,0,',',' ')}} <span class="text-bleu" style="font-size: 11px">{{$devise}}</span></td>         
                                                                            </tr>
                                                                            {{-- <tr>                                                                     
                                                                                <td class="titlefield text-muted fw-semibold"></td>                                                         
                                                                                <td class="fw-semibold"><span class="text-bleu" style="font-size: 11px"></span></td>         
                                                                            </tr> --}}
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6"> 
                                                            <div class="table-responsive">  
                                                                <table class="table text-nowrap m-0">                                        
                                                                    <tbody>
                                                                        <tr>                                                                     
                                                                            <td class="titlefield text-muted fw-semibold">Valorisation achat(PMP) </td>                                                         
                                                                            <td class="fw-semibold">{{number_format($valorisation_achat_total,1,',',' ')}} <span class="text-bleu" style="font-size: 11px">{{$devise}}</span></td>         
                                                                        </tr>
                                                                        @if($produits->type_produit == "Produit")
                                                                            <tr>                                                                     
                                                                                <td class="titlefield text-muted fw-semibold">Stock <i class="fas fa-info-circle" title="Les quantités sont affichées avec un arrondi automatique (+ ou -)" data-bs-toggle="tooltip" data-bs-placement="top" style=" vertical-align: middle; cursor: help"></i></td>                                                         
                                                                                <td class="fw-semibold">{{$stockPieceCount}}<span class="text-bleu" style="font-size: 11px"></span></td>         
                                                                            </tr>
                                                                        @endif
                                                                        <tr>                                                                     
                                                                            <td class="titlefield text-muted fw-semibold">Limite stock pour alerte</td>                                                         
                                                                            <td class="fw-semibold">{{$produits->limite_stock_alerte}}</td>         
                                                                        </tr> 
                                                                        <tr>                                                                     
                                                                            <td class="titlefield text-muted fw-semibold">Dernier mouvement</td>                                                         
                                                                            <td class="fw-semibold">{{date('d-m-Y H:i:s', strtotime($produits->updated_at))}}</td>         
                                                                        </tr>
                                                                        <tr>                                                                     
                                                                            <td class="titlefield text-muted fw-semibold">Date péremption</td>   
                                                                            @if($nbjoursRestant = round((strtotime($produits->date_peremption) - strtotime($dateJour))/(60*60*24)) >= 60)                                                      
                                                                                <td class="text-vert fw-bold"><i class="fa fa-thumbs-up"></i> Reste {{round((strtotime($produits->date_peremption) - strtotime($dateJour))/(60*60*24))}} Jours</td>
                                                                            @elseif($nbjoursRestant = round((strtotime($produits->date_peremption) - strtotime($dateJour))/(60*60*24)) < 60 && $nbjoursRestant = round((strtotime($produits->date_peremption) - strtotime($dateJour))/(60*60*24)) > 30) 
                                                                                <td class="text-primary fw-semibold"><i class="fa fa-exclamation-triangle"></i> {{round((strtotime($produits->date_peremption) - strtotime($dateJour))/(60*60*24))}} Jours avant péremption</td>
                                                                            @elseif($nbjoursRestant = round((strtotime($produits->date_peremption) - strtotime($dateJour))/(60*60*24)) <= 30 && $nbjoursRestant = round((strtotime($produits->date_peremption) - strtotime($dateJour))/(60*60*24)) >= 0) 
                                                                                @if(round((strtotime($produits->date_peremption) - strtotime($dateJour))/(60*60*24)) == 0)
                                                                                    <td class="text-info fw-semibold"><i class="fa fa-exclamation-triangle"></i> Dernier Jour</td>
                                                                                @else
                                                                                    <td class="text-warning fw-semibold"><i class="fa fa-exclamation-triangle"></i> Plus que {{round((strtotime($produits->date_peremption) - strtotime($dateJour))/(60*60*24))}} Jours</td>
                                                                                @endif            
                                                                            @elseif($nbjoursRestant = round((strtotime($produits->date_peremption) - strtotime($dateJour))/(60*60*24)) < 0 && $produits->date_peremption != null)                      
                                                                                <td class=""><span class="blink badge bg-danger"><i class="fa fa-thumbs-down"></i> Produit périmé</span></td>
                                                                            @else
                                                                                <td class="text-secondary fw-semibold"><span class=""><i class="fa fa-exclamation-triangle"></i> Date non définie</span></td>
                                                                            @endif      
                                                                        </tr>
                                                                        {{-- <tr>                                                                     
                                                                            <td class="titlefield text-muted fw-semibold"></td>                                                         
                                                                            <td class="fw-semibold"><span class="text-bleu" style="font-size: 11px"></span></td>         
                                                                        </tr> --}}
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if($ouvre === $id)
                                                        <div>
                                                            @include('livewire.gestion-stock.produits.correction_stock_entrepot')
                                                        </div> 
                                                    @endif
                                                    <div class="table-responsivek">                                                                                            
                                                        <div class="d-flex align-items-center justify-content-between gap-3 py-4">
                                                            <div class="px-3">
                                                                {{-- <h5 class="text-bleu"><i class="fas fa-box-open" style=" color: #393b83;" title="Mouvement de stock"></i> Liste des entrepots de stock ({{$stockCount}})</h5> --}}
                                                            </div>
                                                            <div class="px-3">
                                                                @if($produits->type_produit == "Produit")
                                                                    <a href="#" wire:click.prevent="ajuster({{$produits->id}})" class="btn btn-sm btn-secondary fw-semibold ms-auto"><i class="fa fa-check"></i> Corriger le stock</a>
                                                                @endif
                                                                <a href="nouveau_produit?active=4&champ=1-1&choix=1" wire:navigate class="btn btn-sm btn-danger ms-auto"><i class="fa fa-plus-circle"></i> Nouveau Produit</a>
                                                                @if($approuver === $produits->id)                                                               
                                                                    <a wire:click.prevent="supprimer({{$produits->id}})" class="btn btn-outline-dark btn-sm bg-dark text-white blink"  title="Cliquez pour confirmer la suppression" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-trash"> Confirmer ?</i></a>
                                                                @else
                                                                    <a wire:click.prevent="confirmerDelete({{$produits->id}})" class="btn btn-outline-muted btn-sm"  title="Cliquez pour supprimer" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-trash"></i></a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-12 col-md-12 col-xs-12">
                                                                <div class="d-flex align-items-center justify-content-between gap-3 pt-4 px-3">
                                                                    <div class="">
                                                                        <h5 class="text-bleu"><i class="fas fa-box-open" style=" color: #393b83;" title="Mouvement de stock"></i> Liste des entrepots de stock ({{$stockCount}})</h5>
                                                                    </div>
                                                                    <div>
                                                                    </div>
                                                                </div>
                                                                <div class="table-responsive border-top">
                                                                    <table class="table table-striped table-hover text-nowrap m-0"> 
                                                                        <thead>
                                                                            <tr>
                                                                                <th class="fond_entete_table">Entrepôt</th>
                                                                                <th class="fond_entete_table">Produits</th>
                                                                                <th class="fond_entete_table"><i class="fas fa-info-circle" title="Les quantités sont affichées avec un arrondi automatique (+ ou -)" data-bs-toggle="tooltip" data-bs-placement="top" style=" vertical-align: middle; cursor: help"></i> Nbre de pièces</th>
                                                                                <th class="fond_entete_table text-end">Prix moyen pondéré</th>
                                                                                <th class="fond_entete_table text-end">Valorisation achat(PMP)</th>
                                                                                <th class="fond_entete_table text-end">Prix vente unitaire</th>
                                                                                <th class="fond_entete_table text-end">Valeur à la vente</th>
                                                                                <th class="fond_entete_table">Date</th>
                                                                            </tr>
                                                                        </thead>                                       
                                                                        <tbody>
                                                                            @foreach($stock as $stocks) 
                                                                                @foreach($listEntrepot as $listEntrepots) 
                                                                                    @if($stocks->id_entrepot == $listEntrepots->id)                                                                        
                                                                                            <tr>                                                                     
                                                                                                <td class="text-muted text-bleu fw-bold"><a href="detail_entrepot?id={{$stocks->id_entrepot}}&active=4&champ=3-1&choix=2" wire:navigate class="text-bleu">{{$listEntrepots->nom}}</a></td>                                                         
                                                                                                @foreach($produit as $produits) 
                                                                                                    <td class="">{{$produits->reference}}</td>  
                                                                                                @endforeach          
                                                                                                <td class="text-center fw-semibold">{{$stocks->quantite}}</td>         
                                                                                                <td class="text-end fw-semibold">{{number_format($stocks->prix_moyen_pondere_achat,2,',',' ')}}</td>         
                                                                                                <td class="text-end text-vert fw-semibold">{{number_format($stocks->valorisation_achat_total,2,',',' ')}}</td>         
                                                                                                <td class="text-end fw-semibold">{{number_format($stocks->prix_vente_unitaire,0,',',' ')}}</td>      
                                                                                                <td class="text-end fw-semibold">{{number_format($stocks->valeur_vente_total,2,',',' ')}}</td>      
                                                                                                <td class="">{{date('d-m-Y H:i:s', strtotime($stocks->updated_at))}}</td>        
                                                                                            </tr>                                                                         
                                                                                    @endif
                                                                                @endforeach 
                                                                            @endforeach 
                                                                            <tr>                                                                     
                                                                                <td class="text-muted fw-semibold">Total</td>                                                         
                                                                                <td class=""></td>         
                                                                                {{-- <td class="text-center fw-bold text-bleu">{{number_format($stockPieceCount,1,',',' ')}}</td>          --}}
                                                                                <td class="text-center fw-bold text-bleu">{{$stockPieceCount}}</td>         
                                                                                <td class="text-center text-bleu"></td>
                                                                                <td class="text-end fw-bold text-bleu">{{number_format($valorisation_achat_total,2,',',' ')}}</td>      
                                                                                <td class="text-center text-bleu"></td> 
                                                                                <td class="text-end fw-bold text-bleu">{{number_format($valeurVenteTotal,2,',',' ')}}</td>      
                                                                                <td class=""></td>    
                                                                            </tr> 
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>                                                                                          
                                                </div>
                                                <div id="evenement" class="container-fluid tab-pane fade pas_bordure">  
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="table-responsive">   
                                                                <table class="table text-nowrap m-0">                                        
                                                                    <tbody>
                                                                        <tr>                                                                     
                                                                            <td class="titlefield text-muted fw-semibold">Créé par</td>                                                         
                                                                            <td class="fw-semibold text-bleu"><i class="fa fa-user-circle"></i> {{$produits->nom_user}}</td>         
                                                                        </tr> 
                                                                        <tr>                                                                     
                                                                            <td class="titlefield text-muted fw-semibold">Date création</td>                                                         
                                                                            <td class="fw-semibold">{{date('d-m-Y H:i:s', strtotime($produits->created_at))}}</td>         
                                                                        </tr>                                                                            
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="table-responsive">   
                                                                <table class="table text-nowrap m-0">                                        
                                                                    <tbody>
                                                                        @if($produits->nom_user_modif)
                                                                            <tr>                                                                     
                                                                                <td class="titlefield text-muted fw-semibold">Modifié par</td>                                                         
                                                                                <td class="fw-semibold text-bleu"><i class="fa fa-user-circle"></i> {{$produits->nom_user_modif}}</td>         
                                                                            </tr>
                                                                        @endif
                                                                        <tr>                                                                     
                                                                            <td class="titlefield text-muted fw-semibold">Date de dernière modification</td>                                                         
                                                                            <td class="fw-semibold">{{date('d-m-Y H:i:s', strtotime($produits->updated_at))}}</td>         
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- <div class="table-responsive">                                         
                                                        <div class="d-flex align-items-center justify-content-between gap-3 py-3">
                                                            <div class="">
                                                                <h5 class="text-bleu">Liste des mouvements de stock (12)</h5>
                                                            </div>
                                                            <div>
                                                                <a href="entrepot" wire:navigate class="btn btn-secondary fw-semibold ms-auto"><i class="fa fa-check"></i> Corriger le stock</a>
                                                            </div>
                                                        </div>
                                                        <table class="table truncate m-0"> 
                                                            <thead>
                                                                <tr>
                                                                <th>Produits</th>
                                                                <th>Libellé</th>
                                                                <th>Nombre de pièces</th>
                                                                <th>Prix moyen pondéré</th>
                                                                <th>Valorisation achat(PMP)</th>
                                                                <th>Prix de vente unitaire</th>
                                                                <th>Valeur à la vente	</th>
                                                                <th>Created</th>
                                                                </tr>
                                                            </thead>                                       
                                                            <tbody>
                                                                <tr>                                                                     
                                                                    <td class="text-muted fw-semibold">Description</td>                                                         
                                                                    <td class="fw-semibold"></td>         
                                                                    <td class="fw-semibold"></td>         
                                                                    <td class="fw-semibold"></td>         
                                                                    <td class="fw-semibold"></td>         
                                                                    <td class="fw-semibold"></td>         
                                                                    <td class="fw-semibold"></td>         
                                                                    <td class="fw-semibold"></td>         
                                                                </tr> 
                                                            </tbody>
                                                        </table>
                                                    </div>    --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 px-0 pt-3 mb-1 rounded-bottom">
                                    <div class="hauteur_ecran">
                                        <div class="d-flex align-items-center justify-content-between px-2">
                                            <div class="">
                                                <h5 class="text-bleu"><i class="fas fa-calendar-alt" style=" color: #393b83;" title="Derniers événements liés"></i> @if($logCount > 1) Les {{$logCount}} derniers événements liés @else L'événement lié @endif</h5>
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
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @include('livewire.gestion-stock.produits.update_produits') 
    @include('livewire.gestion-stock.produits.imageupdate') 
</div>

