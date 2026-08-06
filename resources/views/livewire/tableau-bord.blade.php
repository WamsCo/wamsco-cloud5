<div>
    <div id="content" class="app-content" wire:poll.visible.60s>   
      <div class="row py-2">
        <div class="col-md-6 col-sm-12">
          <div class="d-flex align-items-center justify-content-between fw-bold" style="font-size: 18px;">{{$title_fils}}</div>
      </div>
        <div class="col-md-6 col-sm-12">
            <div class="card-headerk d-flex align-items-center justify-content-end gap-1">
              <input type="date" wire:model.live="date_debut" class="form-control bordure w-auto" style="background: #f6f9fb;">
              <input type="date" wire:model.live="date_fin" class="form-control bordure w-auto" style="background: #f6f9fb;">
              {{-- <button class="btn btn-black bordure" title="Cliquez pour valider" wire:click.prevent="valider()"><i class="fa fa-check"></i></button>  --}}
            </div>
        </div>
      </div>  
      <div class="row">
          {{-- Debut chargement --}}        
          <div wire:loading class="chargement">
              <label for=""></label>
              <img src="storage/default/circle_loading.gif" width="64" height="64" style="background: #28282800; margin-left: -3px;">
          </div>
          {{-- Fin chargement --}}
          <div class="col-xl-6">
              <div class="card border-0 mb-3 overflow-hidden bg-gray-800 text-white">
                  <div class="card-body">
                      <div class="row">
                          <div class="col-xl-7 col-lg-8">
                              <div class="mb-3 text-gray-500">
                                  <b>TOTAL SALES</b>
                                  <span class="ms-2">
                                  <i class="fa fa-info-circle" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-title="Total des ventes" data-bs-placement="top" data-bs-content="Net des ventes effectués en cours."></i>
                                  </span>
                              </div>
                              <div class="d-flex mb-1">
                                  <h2 class="mb-0"><span data-animation="number" data-value="{{number_format($vente,0,' ',' ')}}">{{number_format($vente,0,' ',' ')}}</span><small class="text-white mx-1">{{$devise}}</small></h2>
                                  <div class="ms-auto mt-n1 mb-n1"> {{--<div id="total-sales-sparkline"></div>--}} </div>
                              </div>
                              <div class="mb-3 text-gray-500">
                                  <i class="fa fa-caret-up"></i> Marge <span data-animation="number" data-value="{{number_format($marge,0,' ',' ')}}">{{number_format($marge,0,' ',' ')}}</span> <small class="text-gray-500" style="font-size: 12px">{{$devise}}</small>
                              </div>
                              <hr class="bg-white bg-opacity-50" />
                              <div class="row text-truncate">
                                  <div class="col-6">
                                      <div class=" text-gray-500">Vente moyen</div>
                                      <div class="fs-18px mb-5px fw-bold" data-animation="number" data-value="{{number_format($cmd_moyen,0,' ',' ')}}">{{number_format($cmd_moyen,0,' ',' ')}} <small class="text-gray-500" style="font-size: 12px">{{$devise}}</small></div>
                                      <div class="progress h-5px rounded-3 bg-gray-900 mb-5px">
                                          <div class="progress-bar progress-bar-striped rounded-right bg-teal" data-animation="width" data-value="{{$cmd_moyen_bar}}%" style="width: {{$cmd_moyen_bar}}%"></div>
                                      </div>
                                  </div>
                                  <div class="col-6">
                                      <div class=" text-gray-500">Remise</div>
                                      <div class="fs-18px mb-5px fw-bold"><span data-animation="number" data-value="{{number_format($remise,0,' ',' ')}}">{{number_format($remise,0,' ',' ')}} <small class="text-gray-500" style="font-size: 12px">{{$devise}}</small></span></div>
                                      <div class="progress h-5px rounded-3 bg-gray-900 mb-5px">
                                          <div class="progress-bar progress-bar-striped rounded-right" data-animation="width" data-value="{{$remise_bar}}%" style="width: {{$remise_bar}}%"></div>
                                      </div>
                                  </div>
                              </div>
                          </div> 
                          <div class="col-xl-5 col-lg-4 align-items-center d-flex justify-content-center">
                              <img src="storage/default/construires.png" height="150px" class="d-none d-lg-block" />
                          </div>
                      </div>
                  </div>
              </div>
          </div>
          <div class="col-xl-6">
              <div class="row">
                  <div class="col-sm-6">
                      <div class="card border-0 text-truncate mb-3 bg-gray-800 text-white">
                          <div class="card-body">
                              <div class="mb-3 text-gray-500">
                              <b class="mb-3">INVENTAIRE</b>
                              <span class="ms-2"><i class="fa fa-info-circle" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-title="Inventaire" data-bs-placement="top" data-bs-content="Somme de tous les produits en stock." data-original-title="" title=""></i></span>
                              </div>
                              <div class="d-flex align-items-center mb-1">
                                  <h2 class="text-white mb-0"><span data-animation="number" data-value="{{number_format($inventaire,0,' ',' ')}}">{{number_format($inventaire,0,' ',' ')}}</span> <small class="text-gray-500" style="font-size: 12px">{{$devise}}</small></h2>
                                  <div class="ms-auto">
                                  <div id="conversion-rate-sparkline"></div>
                                  </div>
                              </div>
                              <div class="mb-4 text-gray-500 ">
                                  <i class="fa fa-caret-down"></i> <span class="text-warning text-bold" data-animation="number" data-value="{{$utilisateur}}">{{$utilisateur}}</span> Utilisateurs
                              </div>
                              <div class="d-flex mb-2">
                                  <div class="d-flex align-items-center">
                                      <i class="fa fa-circle text-red fs-8px me-2"></i>Produits <span class="w-50px text-end ps-2 fw-bold" data-animation="number" data-value="{{$produitCount}}">{{$produitCount}}</span>
                                  </div>
                                  <div class="d-flex align-items-center ms-auto">
                                      <div class="text-gray-500 small"> Ecritures</div>
                                      <div class="w-50px text-end ps-2 fw-bold"><i class="fa fa-caret-up"></i> <span data-animation="number" data-value="{{$ecritureCount}}">{{$ecritureCount}}</span></div>
                                  </div>
                              </div>
                              <div class="d-flex mb-2">
                                  <div class="d-flex align-items-center">
                                          <i class="fa fa-circle text-warning fs-8px me-2"></i> En rupture stock Mag @if($produit_videMag != 0) &nbsp;<span class="blink badge badge-danger" style="font-size: 70%;">{{$produit_videMag}}</span>@else<b>&nbsp;{{$produit_videMag}}</b>@endif
                                  </div>
                                  <div class="d-flex align-items-center ms-auto">
                                  <div class="text-gray-500 small">Comptes</div>
                                  <div class="w-50px text-end ps-2 fw-bold"><i class="fa fa-caret-up"></i> <span data-animation="number" data-value="{{$banqueCount}}">{{$banqueCount}}</span></div>
                                  </div>
                              </div>
                              <div class="d-flex">
                                  <div class="d-flex align-items-center">
                                      <i class="fa fa-circle text-lime fs-8px me-2"></i> Fournisseurs <span class="w-50px text-end ps-2 fw-bold" data-animation="number" data-value="{{$fourniCount}}">{{$fourniCount}}</span>
                                  </div>
                                  <div class="d-flex align-items-center ms-auto">
                                  <div class="text-gray-500 small">Paie divers</div>
                                  <div class="w-50px text-end ps-2 fw-bold"><i class="fa fa-caret-up"></i> <span data-animation="number" data-value="{{$paiementDivCount}}">{{$paiementDivCount}}</span></div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
                  <div class="col-sm-6">
                      <div class="card border-0 text-truncate mb-3 bg-gray-800 text-white">
                          <div class="card-body">
                              <div class="mb-3 text-gray-500">
                                  <b class="mb-3">VOS CLIENTS VOUS DOIVENT</b>
                                  <span class="ms-2"><i class="fa fa-info-circle" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-title="Ils vous doivent" data-bs-placement="top" data-bs-content="Somme des ventes effectuées qui sont en attente de paiement." data-original-title="" title=""></i></span>
                              </div>
                              <div class="d-flex align-items-center mb-1">
                                  <h2 class="text-white mb-0"><span data-animation="number" data-value="{{number_format($ResteAPayer,0,' ',' ')}}">{{number_format($ResteAPayer,0,' ',' ')}}</span><small class="text-gray-500" style="font-size: 12px">{{$devise}}</small></h2>
                                  <div class="ms-auto">
                                  <div id="store-session-sparkline"></div>
                                  </div>
                              </div>
                              <div class="mb-4 text-gray-500 ">
                                  <i class="fa fa-caret-up"></i> <span data-animation="number" data-value="{{$entite_count}}">{{$entite_count}}</span> entité(s)
                              </div>
                              <div class="d-flex mb-2">
                                  <div class="d-flex align-items-center">
                                      <i class="fa fa-circle text-teal fs-8px me-2"></i>Clients
                                  </div>
                                  <div class="d-flex align-items-center ms-auto">
                                      {{-- <div class="text-gray-500 small"><i class="fa fa-caret-up"></i> <span data-animation="number" data-value="25.7">0.00</span>%</div> --}}
                                      <div class="w-50px text-end ps-2 fw-bold"><span data-animation="number" data-value="{{$client}}">{{$client}}</span></div>
                                  </div>
                              </div>
                              <div class="d-flex mb-2">
                                  <div class="d-flex align-items-center">
                                      <i class="fa fa-circle text-blue fs-8px me-2"></i>Catégories
                                  </div>
                                  <div class="d-flex align-items-center ms-auto">
                                      {{-- <div class="text-gray-500 small"><i class="fa fa-caret-up"></i> <span data-animation="number" data-value="16.0">0.00</span>%</div> --}}
                                      <div class="w-50px text-end ps-2 fw-bold"><span data-animation="number" data-value="{{$categorieCount}}">{{$categorieCount}}</span></div>
                                  </div>
                              </div>
                              <div class="d-flex">
                                  <div class="d-flex align-items-center">
                                      <i class="fa fa-circle text-cyan fs-8px me-2"></i>Com. client
                                  </div>
                                  <div class="d-flex align-items-center ms-auto">
                                      {{-- <div class="text-gray-500 small"><i class="fa fa-caret-up"></i> <span data-animation="number" data-value="7.9">0.00</span>%</div> --}}
                                      <div class="w-50px text-end ps-2 fw-bold"><span data-animation="number" data-value="{{$cmdClientCount}}">{{$cmdClientCount}}</span></div>
                                  </div>
                              </div>
                          </div>
                      </div>  
  
                      {{-- <div class="card border-0 text-truncate mb-3 bg-gray-800 text-white">
                          <div class="card-body">
                              <div class="mb-3 text-gray-500"><b class="mb-3">VOS CLIENTS VOUS DOIVENT</b>
                                  <span class="ms-2"><i class="fa fa-info-circle" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-title="Ils vous doivent" data-bs-placement="top" data-bs-content="Somme des ventes effectuées qui sont en attente de paiement." data-original-title="" title=""></i></span>
                              </div>
                              <div class="d-flex align-items-center mb-1">
                                  <h2 class="text-white mb-0"><span data-animation="number">{{number_format($ResteAPayer,0,' ',' ')}}</span> <small class="text-gray-500" style="font-size: 12px">{{$devise}}</small></h2>
                                  <div class="ms-auto">
                                  <div id="store-session-sparkline"></div>
                                  </div>
                              </div>
                              <div class="mb-4 text-gray-500 ">
                                  <i class="fa fa-caret-up"></i> <span data-animation="number" data-value="9.5">{{$entite_count}}</span> entité(s)
                              </div>
                              <div class="d-flex mb-2">
                                  <div class="d-flex align-items-center"><i class="fa fa-circle text-teal fs-8px me-2"></i> Clients</div>
                                  <div class="d-flex align-items-center ms-auto">
                                      <div class="w-50px text-end ps-2 fw-bold"><span data-animation="number">{{$client}}</span></div>
                                  </div>
                              </div>
                              <div class="d-flex mb-2">
                                  <div class="d-flex align-items-center"><i class="fa fa-circle text-blue fs-8px me-2"></i> Catégories</div>
                                  <div class="d-flex align-items-center ms-auto">
                                      <div class="w-50px text-end ps-2 fw-bold"><span data-animation="number">{{$categorieCount}}</span></div>
                                  </div>
                              </div>
                              <div class="d-flex">
                                  <div class="d-flex align-items-center"><i class="fa fa-circle text-cyan fs-8px me-2"></i> Com. client</div>
                                  <div class="d-flex align-items-center ms-auto">
                                      <div class="w-50px text-end ps-2 fw-bold"><span data-animation="number">{{$cmdClientCount}}</span></div>
                                  </div>
                              </div>
                          </div>
                      </div> --}}
                  </div>
              </div>
          </div>
      </div>
      <div class="row">
          <div class="col-xl-4 col-lg-6">
              <div class="card border-0 mb-3 bg-gray-900 text-white">
                  <div class="card-body" style="background: no-repeat bottom right; background-image: url(storage/default/img-1.svg); background-size: auto 90%;">
                      <div class="mb-3 text-gray-500 ">
                      <b>LES DERNIERES FACTURES</b>
                      <span class="text-gray-500 ms-2"><i class="fa fa-info-circle" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-title="Dernières factures" data-bs-placement="top" data-bs-content="Total dernières factures clients effectuées et le montant total de toutes les factures."></i></span>
                      </div>
                      <h3 class="mb-10px"><span data-animation="number" data-value="{{number_format($factCltEnteTTC_partiel,0,',',' ')}}">{{number_format($factCltEnteTTC_partiel,0,',',' ')}}</span><small class="mx-1 text-white">{{$devise}}</small></h3>
                      <div class="text-gray-500 mb-1px"><i class="fa fa-caret-up"></i> <span data-animation="number" data-value="{{number_format($factCltEnteTTC_all,0,',',' ')}}">{{number_format($factCltEnteTTC_all,0,',',' ')}}</span><small class="mx-1">{{$devise}}</small></div>
                  </div>
                  <div class="widget-list rounded-bottom dark-mode">
                      @foreach($factClient_entete as $factClient_entetes)
                      <a href="nouveau_fact_clt?id={{$factClient_entetes->id}}&ref={{$factClient_entetes->code_facture}}&active=7&champ=1-1&choix=1" wire:navigate class="widget-list-item rounded-0 pt-3px">
                          <div class="widget-list-media icon">
                              <i class="fa fa-file-invoice-dollar bg-indigo text-white"></i>
                          </div>
                          <div class="widget-list-content">
                              <div class="widget-list-title">{{$factClient_entetes->code_facture}} » 
                                  @if($factClient_entetes->etat == "Brouillon")
                                      <span class="py-1" style="color:#c2c2c2;" title="Facture {{$factClient_entetes->etat}}"><i class="fa fa-box"></i> {{$factClient_entetes->etat}}</span>
                                  @elseif($factClient_entetes->etat == "Impayée")
                                      <span class="text-danger py-1 blink" title="Facture {{$factClient_entetes->etat}}"><i class="fa fa-times-circle"></i> {{$factClient_entetes->etat}}</span>
                                  @elseif($factClient_entetes->etat == "Payée")
                                      <span class="text-success py-1" title="Facture {{$factClient_entetes->etat}}"><i class="fa fa-check-circle"></i> {{$factClient_entetes->etat}}</span>
                                  @elseif($factClient_entetes->etat == "Commencée")
                                      <span class="text-info py-1 blink" title="Facture {{$factClient_entetes->etat}}"><i class="fa fa-recycle"></i> {{$factClient_entetes->etat}}</span>
                                  @endif 
                              </div>
                          </div>
                          <div class="widget-list-action text-nowrap text-gray-500">
                              <span data-animation="number" data-value="{{number_format($factClient_entetes->montant_ttc,0,',',' ')}}">{{number_format($factClient_entetes->montant_ttc,0,',',' ')}}</span><small class="mx-1">{{$devise}}</small>
                          </div>
                      </a>
                      @endforeach
                  </div>
              </div>
          </div>
          <div class="col-xl-4 col-lg-6">
              <div class="card border-0 mb-2 bg-gray-800 text-white">
                  <div class="card-body">
                      <div class="mb-3 text-gray-500">
                          <b>LES DERNIERES PRODUITS CREES</b>
                          <span class="ms-2 "><i class="fa fa-info-circle" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-title="Dernières produits" data-bs-placement="top" data-bs-content="Les dernières produits crées."></i></span>
                      </div>
                      @foreach($produit as $produits)
                      <div class="d-flex align-items-center mb-14px">
                          <div class="widget-img rounded-3 me-10px bg-white p-3px w-30px">
                              @if($produits->image != null)
                                  <div class="h-100 w-100" style="background: url(storage/{{$produits->image}}) center no-repeat; background-size: auto 100%;"></div>
                              @else
                                  <div class="h-100 w-100" style="background: url(storage/default/image.png) center no-repeat; background-size: auto 100%;"></div>
                              @endif   
                          </div>
                          <div class="text-truncate">
                              <div><a class="text-white" href="detail_product?id={{$produits->id}}&active=4&champ=1-1&choix=2" wire:navigate><i class="fa fa-cube"></i> {{Str::limit($produits->nom_produit, 22)}}</a></div>
                              <div class="text-gray-500"><span data-animation="number" data-value="{{number_format($produits->prix_vente,0,' ',' ')}}">{{number_format($produits->prix_vente,0,' ',' ')}}</span> <small>{{$devise}}</small></div>
                          </div>
                          <div class="ms-auto text-end">
                              <div class="fs-13px"> 
                                  @if($nbjoursRestant = round((strtotime($produits->date_peremption) - strtotime($dateJour))/(60*60*24)) >= 60)
                                      <span class="text-vert fs-10px fw-bold"><i class="fa fa-thumbs-up"></i> Reste {{round((strtotime($produits->date_peremption) - strtotime($dateJour))/(60*60*24))}} Jours</span>
                                  @elseif($nbjoursRestant = round((strtotime($produits->date_peremption) - strtotime($dateJour))/(60*60*24)) < 60 && $nbjoursRestant = round((strtotime($produits->date_peremption) - strtotime($dateJour))/(60*60*24)) > 30)
                                      <span class="text-primary fs-10px fw-bold"><i class="fa fa-exclamation-triangle"></i> {{round((strtotime($produits->date_peremption) - strtotime($dateJour))/(60*60*24))}} Jours péremption</span>
                                  @elseif($nbjoursRestant = round((strtotime($produits->date_peremption) - strtotime($dateJour))/(60*60*24)) <= 30 && $nbjoursRestant = round((strtotime($produits->date_peremption) - strtotime($dateJour))/(60*60*24)) >= 0) 
                                      @if(round((strtotime($produits->date_peremption) - strtotime($dateJour))/(60*60*24)) == 0)
                                          <span class="text-info fs-10px fw-bold"><i class="fa fa-exclamation-triangle"></i> Dernier Jour</span>
                                      @else
                                          <span class="text-warning fs-10px fw-bold"><i class="fa fa-exclamation-triangle"></i> Plus que {{round((strtotime($produits->date_peremption) - strtotime($dateJour))/(60*60*24))}} Jours</span>
                                      @endif            
                                  @elseif($nbjoursRestant = round((strtotime($produits->date_peremption) - strtotime($dateJour))/(60*60*24)) < 0 && $produits->date_peremption != null)                      
                                      <span class=""><span class="blink badge bg-danger"><i class="fa fa-thumbs-down"></i> Produit périmé</span></span>
                                  @else
                                      <span class="fs-10px fw-bold"><span class=""><i class="fa fa-exclamation-triangle"></i> Date non définie</span></span>
                                  @endif
                              </div>
                              <div class="text-gray-500 fs-10px">{{$produits->categorie}}</div>
                          </div>
                      </div>
                      @endforeach
                  </div>
              </div>
          </div>
          <div class="col-xl-4 col-lg-6">
              <div class="card border-0 mb-3 bg-gray-800 text-white">
                  <div class="card-body">
                      <div class="mb-3 text-gray-500 ">
                          <b>INFORMATION UTILE</b>
                          <span class="ms-2"><i class="fa fa-info-circle" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-title="Information utile" data-bs-placement="top" data-bs-content="Ce sont vos informations, elles figurent également sur toutes vos factures."></i></span>
                      </div>
                      @foreach($entit as $entits)
                          <div class="row align-items-center pb-1px">
                              <div class="col-4">
                              <div class="h-100px d-flex align-items-center justify-content-center">
                              <img src="storage/default/img-2.svg" class="mw-100 mh-100" />
                              </div>
                              </div>
                              <div class="col-8">
                                  <div class="mb-2px text-truncate">{{$entits->email}}</div>
                                  <div class="mb-2px  text-gray-500  small">{{$entits->telephone}}</div>
                                  <div class="d-flex align-items-center mb-2px">
                                      <div class="flex-grow-1">
                                          <div class="progress h-5px rounded-pill bg-white bg-opacity-10">
                                              <div class="progress-bar progress-bar-striped bg-indigo" data-animation="width" data-value="80%" style="width: 80%"></div>
                                          </div>
                                      </div>
                                      <div class="ms-2 small w-30px text-center"><span data-animation="number" data-value="80">80</span>%</div>
                                  </div>
                                  <div class="text-gray-500 small mb-15px text-truncate">{{$entits->pays}} | {{$entits->ville}}</div>
                                  @if(auth()->user()->societe =="Administration")
                                      <a href="detail_entite?id={{$entits->id}}" class="btn btn-xs btn-indigo fs-10px ps-2 pe-2">Voir les détails</a>
                                  @else
                                      <a href="entite?id={{$entits->id}}" class="btn btn-xs btn-indigo fs-10px ps-2 pe-2">Voir les détails</a>
                                  @endif
                              </div>
                          </div>
                          <hr class=" bg-white bg-opacity-20 mt-20px mb-20px" />
                          <div class="row align-items-center">
                              <div class="col-4">
                                  <div class="h-100px d-flex align-items-center justify-content-center">
                                  <img src="storage/default/img-3.svg" class="mw-100 mh-100" />
                                  </div>
                              </div>
                              <div class="col-8">
                                  <div class="mb-2px text-truncate">Raison sociale</div>
                                  <div class="mb-2px  text-gray-500  small">{{$entits->raison_sociale}}</div>
                                  <div class="d-flex align-items-center mb-2px">
                                      <div class="flex-grow-1">
                                          <div class="progress h-5px rounded-pill bg-white bg-opacity-10">
                                              <div class="progress-bar progress-bar-striped bg-warning" data-animation="width" data-value="60%" style="width: 60%"></div>
                                          </div>
                                      </div>
                                      <div class="ms-2 small w-30px text-center"><span data-animation="number" data-value="60">60</span>%</div>
                                  </div>
                                  <div class="text-gray-500 small mb-15px text-truncate">RCM / NIU : {{$entits->registre_com}}</div>
                                  @if(auth()->user()->societe =="Administration")
                                      <a href="detail_entite?id={{$entits->id}}" class="btn btn-xs btn-warning fs-10px ps-2 pe-2">Voir les détails</a>
                                  @else 
                                      <a href="entite?id={{$entits->id}}" class="btn btn-xs btn-warning fs-10px ps-2 pe-2">Voir les détails</a>
                                  @endif
                              </div>
                          </div>
                      @endforeach
                  </div>
              </div>
          </div>
      </div>
      <div class="row">
        <div class="col-xl-6 col-lg-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-3 bg-dark border-top-3">
                <!-- Graphique ventes facture client -->
                <div class="bg-whitek p-2 rounded-3 shadow">
                    <h5 class="text-lg text-gray-500 mb-1 fs-6">VENTES MENSUELLES</h5>
                    <div id="salesChart"></div>
                </div>                
            </div>
        </div>
        <div class="col-xl-6 col-lg-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-3 bg-dark">                
                <!-- Graphique ventes facture fournisseur -->
                <div class="bg-whitek p-2 rounded shadow">
                    <h5 class="text-lg text-gray-500 mb-1 fs-6">PAIEMENT FOURNISSEURS</h5>
                    <div id="ventesChart"></div>
                </div>
            </div>            
        </div> 
        <div class="col-xl-12 col-lg-12">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-3 bg-dark">                
                <!-- Graphique ventes facture fournisseur -->
                <div class="bg-whitek p-2 rounded shadow">
                    <h5 class="text-lg text-gray-500 mb-1 fs-6">TOP PRODUITS (CA & Quantités) - {{ $currentMonthName }}</h5>
                    <div id="topProductsQtyCAChart"></div>
                </div>
                <style>
                    .apexcharts-toolbar {
                        background: #acacac;
                        padding: 4px;
                        border-radius: 6px;
                    }
                </style>
            </div>            
        </div>
             
      </div>
      {{-- <div class="row">
          <div class="col-xl-8 col-lg-6">
              <div class="card border-0 mb-3 bg-gray-800 text-white">
                  <div class="card-body">
                    <div class="mb-3 text-gray-500 "><b>VISITORS ANALYTICS</b> <span class="ms-2"><i class="fa fa-info-circle" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-title="Top products with units sold" data-bs-placement="top" data-bs-content="Products with the most individual units sold. Includes orders from all sales channels." data-original-title="" title=""></i></span></div>
                    <div class="row">
                        <div class="col-xl-3 col-4">
                            <h3 class="mb-1"><span data-animation="number" data-value="127.1">0</span>K</h3>
                            <div>New Visitors</div>
                            <div class="text-gray-500 small text-truncate"><i class="fa fa-caret-up"></i> <span data-animation="number" data-value="25.5">0.00</span>% from previous 7 days</div>
                        </div>
                        <div class="col-xl-3 col-4">
                            <h3 class="mb-1"><span data-animation="number" data-value="179.9">0</span>K</h3>
                            <div>Returning Visitors</div>
                            <div class="text-gray-500 small text-truncate"><i class="fa fa-caret-up"></i> <span data-animation="number" data-value="5.33">0.00</span>% from previous 7 days</div>
                        </div>
                        <div class="col-xl-3 col-4">
                            <h3 class="mb-1"><span data-animation="number" data-value="766.8">0</span>K</h3>
                            <div>Total Page Views</div>
                            <div class="text-gray-500 small text-truncate"><i class="fa fa-caret-up"></i> <span data-animation="number" data-value="0.html">0.00</span>% from previous 7 days</div>
                        </div>
                    </div>
                  </div>
                  <div class="card-body p-0">
                      <div style="height: 269px">
                          <div id="visitors-line-chart" class="widget-chart-full-width dark-mode" style="height: 254px"></div>
                      </div>
                  </div>
              </div>
          </div>
  
  
          <div class="col-xl-4 col-lg-6">
  
          <div class="card border-0 mb-3 bg-gray-800 text-white">
          <div class="card-body">
          <div class="mb-2 text-gray-500">
          <b>SESSION BY LOCATION</b>
          <span class="ms-2"><i class="fa fa-info-circle" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-title="Total sales" data-bs-placement="top" data-bs-content="Net sales (gross sales minus discounts and returns) plus taxes and shipping. Includes orders from all sales channels."></i></span>
          </div>
          <div id="visitors-map" class="mb-2" style="height: 200px"></div>
          <div>
          <div class="d-flex align-items-center text-white mb-2">
          <div class="widget-img widget-img-xs rounded bg-dark me-2 w-40px" style="background-image: url(assets/img/flag/us.jpg)"></div>
          <div class="d-flex w-100">
          <div>United States</div>
          <div class="ms-auto text-gray-500"><span data-animation="number" data-value="39.85">0.00</span>%</div>
          </div>
          </div>
          <div class="d-flex align-items-center text-white mb-2">
          <div class="widget-img widget-img-xs rounded bg-dark me-2 w-40px" style="background-image: url(assets/img/flag/cn.jpg)"></div>
          <div class="d-flex w-100">
          <div>China</div>
          <div class="ms-auto text-gray-500"><span data-animation="number" data-value="14.23">0.00</span>%</div>
          </div>
          </div>
          <div class="d-flex align-items-center text-white mb-2">
          <div class="widget-img widget-img-xs rounded bg-dark me-2 w-40px" style="background-image: url(assets/img/flag/de.jpg)"></div>
          <div class="d-flex w-100">
          <div>Germany</div>
          <div class="ms-auto text-gray-500"><span data-animation="number" data-value="12.83">0.00</span>%</div>
          </div>
          </div>
          <div class="d-flex align-items-center text-white mb-2">
          <div class="widget-img widget-img-xs rounded bg-dark me-2 w-40px" style="background-image: url(assets/img/flag/fr.jpg)"></div>
          <div class="d-flex w-100">
          <div>France</div>
          <div class="ms-auto text-gray-500"><span data-animation="number" data-value="11.14">0.00</span>%</div>
          </div>
          </div>
          <div class="d-flex align-items-center text-white mb-0">
          <div class="widget-img widget-img-xs rounded bg-dark me-2 w-40px" style="background-image: url(assets/img/flag/jp.jpg)"></div>
          <div class="d-flex w-100">
          <div>Japan</div>
          <div class="ms-auto text-gray-500"><span data-animation="number" data-value="10.75">0.00</span>%</div>
          </div>
          </div>
          </div>
          </div>
          </div>
  
          </div>
  
      </div> --}}
    </div>
</div>


<script>
    document.addEventListener("livewire:init", () => {

        // Graphique ventes facture client
        let salesChart = new ApexCharts(
            document.querySelector("#salesChart"),
            {
                chart: {
                    type: 'area',
                    height: 350,
                    background: '#2d353c'
                },
                
                theme: {
                    mode: 'dark'
                },

                series: [{
                    name: 'Ventes',
                    data: @json($sales)
                }],

                xaxis: {
                    categories: @json($months),
                    labels: {
                            style: {
                                colors: [
                                    // '#3B82F6',
                                    // '#EF4444',
                                    // '#10B981',
                                    // '#F59E0B',
                                    // '#8B5CF6',
                                    // '#EC4899',
                                    // '#14B8A6',
                                    // '#F97316',
                                    // '#6366F1',
                                    // '#84CC16',
                                    // '#06B6D4',
                                    // '#D946EF'
                                ],
                                fontSize: '13px',
                                fontWeight: 500
                            }
                        }
                },

                colors: ['#3B82F6'] 
            }
        );
        salesChart.render();

        // Graphique ventes facture fournisseur
        let ventesChart = new ApexCharts(
            document.querySelector("#ventesChart"),
            {
                chart: {
                    type: 'line',
                    height: 350,
                    background: '#2d353c'
                },

                theme: {
                    mode: 'dark'
                },

                series: [{
                    name: 'Factures',
                    data: @json($ventes)
                }],

                xaxis: {
                    categories: @json($months),
                    labels: {
                            style: {
                                colors: [
                                    // '#3B82F6',
                                    // '#EF4444',
                                    // '#10B981',
                                    // '#F59E0B',
                                    // '#8B5CF6',
                                    // '#EC4899',
                                    // '#14B8A6',
                                    // '#F97316',
                                    // '#6366F1',
                                    // '#84CC16',
                                    // '#06B6D4',
                                    // '#D946EF'
                                ],
                                fontSize: '13px',
                                fontWeight: 500
                            }
                        }
                },

                colors: ['#10B981']               
            }
        );
        ventesChart.render();
    });
</script>
<script>
    document.addEventListener("livewire:init", () => {

        let chart = new ApexCharts(
            document.querySelector("#topProductsQtyCAChart"),
            {
                chart: {
                    type: 'bar',
                    height: 380,
                    stacked: false,
                    background: '#2d353c',

                    toolbar: {
                        show: true,
                        tools: {
                            download: true,
                            selection: true,
                            zoom: true,
                            zoomin: true,
                            zoomout: true,
                            pan: true,
                            reset: true
                        }
                    }
                },
                legend: {
                    position: 'bottom',
                    labels: {
                        colors: '#fff'
                    }
                },

                theme: {
                    mode: 'dark'
                },

                series: [
                    {
                        name: 'Quantité vendue',
                        type: 'column',
                        data: @json($productQty)
                    },
                    {
                        name: 'Chiffre d\'affaires',
                        type: 'line',
                        data: @json($productCA)
                    }
                ],

                stroke: {
                    width: [0, 3]
                },

                xaxis: {
                    categories: @json($productNames),
                    labels: {
                        style: {
                            fontSize: '13px',
                            fontWeight: 500
                        }
                    }
                },

                yaxis: [
                    {
                        title: {
                            text: 'Quantité'
                        }
                    },
                    {
                        opposite: true,
                        title: {
                            text: 'Chiffre d\'affaires'
                        },
                        labels: {
                            formatter: function (val) {
                                return val.toLocaleString() + " FCFA";
                            }
                        }
                    }
                ],

                colors: ['#10B981', '#F59E0B'],

                tooltip: {
                    shared: true,
                    intersect: false
                }
            }
        );

        chart.render();
    });
</script>


