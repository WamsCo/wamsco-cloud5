<div>
  <div id="sidebar" class="app-sidebar">
      <div class="app-sidebar-content new_color" data-scrollbar="true" data-height="100%">
          <div class="menu">
              <div class="menu-profile @if($active == 0) active @endif">
                  <a href="javascript:;" class="menu-profile-link" data-toggle="app-sidebar-profile" data-target="#appSidebarProfileMenu">
                      <div class="menu-profile-cover with-shadow"></div>
                      <div class="menu-profile-image">
                          @if(auth()->user()->profil != null)
                              <img src="storage/{{auth()->user()->profil}}" style="object-fit: fill;width: 47px;height: 47px;" alt="Profil" />
                          @else
                              <img src="storage/default/user_man.png" style="object-fit: fill;width: 47px;height: 47px;" alt="Profil" />
                          @endif
                      </div>
                      <div class="menu-profile-info">
                          <div class="d-flex align-items-center">
                              <div class="flex-grow-1">{{Str::limit(auth()->user()->name, 20)}}</div>
                              <div class="menu-caret ms-auto"></div>
                          </div>
                          <small title="{{auth()->user()->type_user}} [{{auth()->user()->societe}}]">{{Str::limit(auth()->user()->type_user, 50)}} [{{Str::limit(auth()->user()->societe, 50)}}]</small>
                      </div>
                  </a>
              </div>
              <div id="appSidebarProfileMenu" class="collapse">
                  <div class="menu-item pt-5px">
                      <a href="mon_compte?id={{auth()->user()->id}}&active=0" class="menu-link">
                          <div class="menu-icon"><i class="fa fa-user"></i></div>
                          <div class="menu-text">Editer Profil</div>
                      </a>
                  </div>
                  {{-- <div class="menu-item">
                      <a href="javascript:;" class="menu-link">
                          <div class="menu-icon"><i class="fa fa-pencil-alt"></i></div>
                          <div class="menu-text"> Feedback</div>
                      </a>
                  </div> --}}
                  <div class="menu-item pb-5px">
                      <a href="https://wamsco-cloud.net" target="_blank" class="menu-link">
                          <div class="menu-icon"><i class="fa fa-question-circle"></i></div>
                          <div class="menu-text"> Aide</div>
                      </a>
                  </div>
                  <div class="menu-divider m-0"></div>
              </div>
              <div class="menu-header">Navigation</div>                          
              <div class="menu-item has-sub @if($active == 1 ) active @endif">
                  <a href="{{asset('bienvenue?active=1')}}" class="menu-link @if($active == 1) active @endif">
                      <div class="menu-icon">
                          <i class="fa fa-home"></i>
                      </div>
                      <div class="menu-text">Accueil</div>
                  </a>
              </div>
              @foreach($entite_mod as $entite_mods)
                {{-- @if($entite_mods->mod_pointe_vente == 1 && $dateJour <= $entite_mods->validite_mod) --}}
                @if($dateJour <= $entite_mods->validite_mod)
                    <div class="menu-item has-sub @if($active == 2 && $champ == "2-1") active @endif">
                        <a href="{{asset('tableau_bord?active=2&champ=2-1')}}" class="menu-link @if($active == 1 ) active @endif">
                            <div class="menu-icon">
                                <i class="fab fa-windows"></i>
                            </div>
                            <div class="menu-text">Tableau de bord</div>
                        </a>
                    </div>
                @endif
              @endforeach
              @foreach($entite_mod as $entite_mods)
                  @if($entite_mods->mod_gestion_tier == 1 && $dateJour <= $entite_mods->validite_mod) 
                      <div class="menu-item has-sub @if($active == 3) active @endif">
                          <a href="javascript:;" class="menu-link">
                              <div class="menu-icon">
                                  <i class="fa fa-user-plus"></i>
                              </div>
                              <div class="menu-text">Gestion Tiers</div>
                              <div class="menu-caret"></div>
                          </a>
                          <div class="menu-submenu">
                              {{-- <div class="menu-item @if($active == 3 && $champ == "3-1") active @endif">
                                  <a href="{{asset('nouveau_tiers?active=3&champ=3-1')}}" class="menu-link">
                                      <div class="menu-text">Nouveau tier</div>
                                  </a>
                              </div> --}}
                              <div class="menu-item @if($active == 3 && $champ == "3-2") active @endif">
                                  <a href="{{asset('listing-tiers?active=3&champ=3-2')}}" class="menu-link">
                                      <div class="menu-text">Consulter tiers</div>
                                  </a>
                              </div>                             
                              @if($entite_mods->mod_crm == 1 && $dateJour <= $entite_mods->validite_mod)
                                <div class="menu-item has-sub @if($active == 3 && $champ == "3-3") active @endif">
                                        <a href="javascript:;" class="menu-link">
                                            <div class="menu-text">CRM</div>
                                            <div class="menu-caret"></div>
                                        </a>
                                        <div class="menu-submenu">
                                            <div class="menu-item @if($active == 3 && $champ == "3-3" && $choix == "1") active @endif">
                                                <a href="{{asset('pipeline_tiers?active=3&champ=3-3&choix=1')}}" class="menu-link">
                                                    <div class="menu-text">Pipeline</div>
                                                </a>
                                            </div>
                                            <div class="menu-item @if($active == 3 && $champ == "3-3" && $choix == "2") active @endif">
                                                <a href="{{asset('etapes_pipeline?active=3&champ=3-3&choix=2')}}" class="menu-link">
                                                    <div class="menu-text">Etape</div>
                                                </a>
                                            </div>
                                        </div>
                                </div>
                              @endif
                              {{-- <div class="menu-item @if($active == 3 && $champ == "3-3") active @endif">
                                  <a href="{{asset('pipeline-tiers?active=3&champ=3-3')}}" class="menu-link">
                                      <div class="menu-text">Pipeline 
                                          <span class="bagde badge-danger px-1 rounded"><small>New</small></span>
                                      </div>
                                  </a>
                              </div> --}}
                          </div>
                      </div>
                  @endif 
                  @if($entite_mods->mod_gestion_stock == 1 && $dateJour <= $entite_mods->validite_mod)            
                    <div class="menu-item has-sub @if($active == 4) active @endif">
                        <a href="javascript:;" class="menu-link">
                            <div class="menu-icon">
                                <i class="fa fa-box-open"></i>
                            </div>
                            <div class="menu-text">Gestion de stock</div>
                            <div class="menu-caret"></div>
                        </a>
                        <div class="menu-submenu">                        
                            <div class="menu-item has-sub @if($active == 4 && $champ == "1-1") active @endif">
                                <a href="javascript:;" class="menu-link">
                                    <div class="menu-text">Produit</div>
                                    <div class="menu-caret"></div>
                                </a>
                                <div class="menu-submenu">
                                    {{-- <div class="menu-item @if($active == 4 && $champ == "1-1" && $choix == "1") active @endif">
                                        <a href="{{asset('nouveau_produit?active=4&champ=1-1&choix=1')}}" class="menu-link">
                                            <div class="menu-text">Nouveau produit</div>
                                        </a>
                                    </div> --}}
                                    <div class="menu-item @if($active == 4 && $champ == "1-1" && $choix == "2") active @endif">
                                        <a href="{{asset('produit?active=4&champ=1-1&choix=2')}}" class="menu-link">
                                            <div class="menu-text">Consulter produits</div>
                                        </a>
                                    </div>  
                                    <div class="menu-item @if($active == 4 && $champ == "1-1" && $choix == "3") active @endif">
                                        <a href="{{asset('stock?active=4&champ=1-1&choix=3')}}" class="menu-link">
                                            <div class="menu-text">Stocks</div>
                                        </a>
                                    </div>
                                    <div class="menu-item @if($active == 4 && $champ == "1-1" && $choix == "4") active @endif">
                                        <a href="{{asset('stock_a_date?active=4&champ=1-1&choix=4')}}" class="menu-link">
                                            <div class="menu-text">Stock à date</div>
                                        </a>
                                    </div>                                                                         
                                </div>
                            </div>
                            <div class="menu-item @if($active == 4 && $champ == "2-1" && $choix == "1") active @endif">
                                <a href="{{asset('categorie?active=4&champ=2-1&choix=1')}}" class="menu-link">
                                    <div class="menu-text">Catégorie</div>
                                </a>
                            </div>                           
                            <div class="menu-item has-sub @if($active == 4 && $champ == "3-1") active @endif">
                                <a href="javascript:;" class="menu-link">
                                    <div class="menu-text">Entrepôts</div>
                                    <div class="menu-caret"></div>
                                </a>
                                <div class="menu-submenu">
                                    <div class="menu-item has-sub @if($active == 4 && $champ == "3-1") active @endif">
                                        <a href="javascript:;" class="menu-link">
                                            <div class="menu-text">Magasin</div>
                                            <div class="menu-caret"></div>
                                        </a>
                                        <div class="menu-submenu">
                                            {{-- <div class="menu-item @if($active == 4 && $champ == "3-1" && $choix == "1") active @endif">
                                                <a href="{{asset('nouveau_entrepot?active=4&champ=3-1&choix=1')}}" class="menu-link">
                                                    <div class="menu-text">Nouveau magasin</div>
                                                </a>
                                            </div> --}}
                                            <div class="menu-item @if($active == 4 && $champ == "3-1" && $choix == "2") active @endif">
                                                <a href="{{asset('entrepot?active=4&champ=3-1&choix=2')}}" class="menu-link">
                                                    <div class="menu-text">Consulter magasins</div>
                                                </a>
                                            </div> 
                                        </div>
                                    </div>
                                    <div class="menu-item @if($active == 4 && $champ == "3-1" && $choix == "3") active @endif">
                                        <a href="{{asset('mouvements?active=4&champ=3-1&choix=3')}}" class="menu-link">
                                            <div class="menu-text">Mouvements</div>
                                        </a>
                                    </div>
                                    <div class="menu-item has-sub @if($active == 4 && $champ == "3-1") active @endif">
                                        <a href="javascript:;" class="menu-link">
                                            <div class="menu-text">Transferts</div>
                                            <div class="menu-caret"></div>
                                        </a>
                                        <div class="menu-submenu">
                                            {{-- <div class="menu-item @if($active == 4 && $champ == "3-1" && $choix == "4") active @endif">
                                                <a href="{{asset('nouveau_tranfert?active=4&champ=3-1&choix=4')}}" class="menu-link">
                                                    <div class="menu-text">Nouveau transfert</div>
                                                </a>
                                            </div> --}}
                                            <div class="menu-item @if($active == 4 && $champ == "3-1" && $choix == "5") active @endif">
                                                <a href="{{asset('transferts?active=4&champ=3-1&choix=5')}}" class="menu-link">
                                                    <div class="menu-text">Consulter transferts</div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="menu-item has-sub @if($active == 4 && $champ == "3-1") active @endif">
                                        <a href="javascript:;" class="menu-link">
                                            <div class="menu-text">Inventaire</div>
                                            <div class="menu-caret"></div>
                                        </a>
                                        <div class="menu-submenu">
                                            {{-- <div class="menu-item @if($active == 4 && $champ == "3-1" && $choix == "6") active @endif">
                                                <a href="{{asset('nouvel_inventaire?active=4&champ=3-1&choix=6')}}" class="menu-link">
                                                    <div class="menu-text">Nouvel Inventaire</div>
                                                </a>
                                            </div> --}}
                                            <div class="menu-item @if($active == 4 && $champ == "3-1" && $choix == "7") active @endif">
                                                <a href="{{asset('inventaires?active=4&champ=3-1&choix=7')}}" class="menu-link">
                                                    <div class="menu-text">Liste inventaires</div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>                      
                        </div>
                    </div> 
                  @endif
                  @if($entite_mods->mod_pointe_vente == 1 && $dateJour <= $entite_mods->validite_mod)
                      <div class="menu-item has-sub @if($active == 5 ) active @endif">
                          <a href="javascript:;" class="menu-link">
                              <div class="menu-icon">
                                  <i class="fa fa-cash-register"></i>
                              </div>
                              <div class="menu-text">Point de vente</div>
                              <div class="menu-caret"></div>
                          </a>
                          <div class="menu-submenu">                                
                              {{-- <div class="menu-item @if($active == 5 && $champ == "1-0") active @endif">
                                  <a href="{{asset('tableau-bord?active=5&champ=1-0')}}" class="menu-link">
                                      <div class="menu-text">Tableau de bord</div>
                                  </a>
                              </div> --}}
                              <div class="menu-item @if($active == 5 && $champ == "1-1") active @endif">
                                  <a href="{{asset('pos_sessions?active=5&champ=1-1')}}" class="menu-link">
                                      <div class="menu-text">Point de vente </div>
                                  </a>
                              </div>
                              <div class="menu-item @if($active == 5 && $champ == "1-2") active @endif">
                                  <a href="{{asset('emplacement?active=5&champ=1-2')}}" class="menu-link">
                                      <div class="menu-text">Emplacement</div>
                                  </a>
                              </div>
                              @if($entite_mods->mod_restaurant == 1 && $dateJour <= $entite_mods->validite_mod)
                                <div class="menu-item has-sub @if($active == 5 && $champ == "2-1") active @endif">
                                    <a href="javascript:;" class="menu-link">
                                        <div class="menu-text">Restaurant</div>
                                        <div class="menu-caret"></div>
                                    </a>
                                    <div class="menu-submenu">
                                        <div class="menu-item @if($active == 5 && $champ == "2-1" && $choix == "1") active @endif">
                                            <a href="{{asset('restau_sessions?active=5&champ=2-1&choix=1')}}" class="menu-link">
                                                <div class="menu-text">Restaurant</div>
                                            </a>
                                        </div>                                      
                                        @if($entite_mods->mod_cuisine == 1 && $dateJour <= $entite_mods->validite_mod) 
                                            <div class="menu-item @if($active == 5 && $champ == "2-1" && $choix == "3") active @endif">
                                                <a href="{{asset('cuisine?active=5&champ=2-1&choix=3')}}" class="menu-link">
                                                    <div class="menu-text">Cuisine</div>
                                                </a>
                                            </div>
                                        @endif
                                        <div class="menu-item @if($active == 5 && $champ == "2-1" && $choix == "4") active @endif">
                                            <a href="{{asset('table_restau?active=5&champ=2-1&choix=4')}}" class="menu-link">
                                                <div class="menu-text">Table</div>
                                            </a>
                                        </div>
                                        <div class="menu-item @if($active == 5 && $champ == "2-1" && $choix == "5") active @endif">
                                            <a href="{{asset('espace_restau?active=5&champ=2-1&choix=5')}}" class="menu-link">
                                                <div class="menu-text">Espace</div>
                                            </a>
                                        </div>                                                                             
                                    </div>
                                </div>
                              @endif
                          </div>
                      </div> 
                  @endif
                  @if($entite_mods->mod_cmd == 1 && $dateJour <= $entite_mods->validite_mod)
                        <div class="menu-item has-sub @if($active == 6) active @endif">
                                <a href="javascript:;" class="menu-link">
                                    <div class="menu-icon">
                                        <i class="fas fa-file-invoice"></i>
                                    </div>
                                    <div class="menu-text">Commande</div>
                                    <div class="menu-caret"></div>
                                </a>
                                <div class="menu-submenu">                        
                                    <div class="menu-item has-sub @if($active == 6 && $champ == "1-1") active @endif">
                                        <a href="javascript:;" class="menu-link">
                                            <div class="menu-text">Com. clients</div>
                                            <div class="menu-caret"></div>
                                        </a>
                                        <div class="menu-submenu">
                                            <div class="menu-item @if($active == 6 && $champ == "1-1" && $choix == "1") active @endif">
                                                <a href="{{asset('proforma?active=6&champ=1-1&choix=1')}}" class="menu-link">
                                                    <div class="menu-text">Proforma</div>
                                                </a>
                                            </div>  
                                            <div class="menu-item  @if($active == 6 && $champ == "1-1" && $choix == "2") active @endif">
                                                <a href="{{asset('listing_cmd_clt?active=6&champ=1-1&choix=2')}}" class="menu-link">
                                                    <div class="menu-text">Liste commandes</div>
                                                </a>
                                            </div> 
                                            <div class="menu-item @if($active == 6 && $champ == "1-1" && $choix == "3") active @endif">
                                                <a href="{{asset('listing_expedition_clt?active=6&champ=1-1&choix=3')}}" class="menu-link">
                                                    <div class="menu-text">Expéditions</div>
                                                </a>
                                            </div> 
                                            <div class="menu-item @if($active == 6 && $champ == "1-1" && $choix == "4") active @endif">
                                                <a href="{{asset('ligne_cmd?active=6&champ=1-1&choix=4')}}" class="menu-link">
                                                    <div class="menu-text">Ligne de commande</div>
                                                </a>
                                            </div>                                     
                                        </div>
                                    </div>
                                    <div class="menu-item has-sub @if($active == 6 && $champ == "2-1") active @endif">
                                        <a href="javascript:;" class="menu-link">
                                            <div class="menu-text">Com. fournisseurs</div>
                                            <div class="menu-caret"></div>
                                        </a>
                                        <div class="menu-submenu">
                                            <div class="menu-item  @if($active == 6 && $champ == "2-1" && $choix == "1") active @endif">
                                                <a href="{{asset('listing_cmd_fourni?active=6&champ=2-1&choix=1')}}" class="menu-link">
                                                    <div class="menu-text">Liste commandes</div>
                                                </a>
                                            </div> 
                                            <div class="menu-item @if($active == 6 && $champ == "2-1" && $choix == "2") active @endif">
                                                <a href="{{asset('listing_reception_fourni?active=6&champ=2-1&choix=2')}}" class="menu-link">
                                                    <div class="menu-text">Réceptions</div>
                                                </a>
                                            </div> 
                                            <div class="menu-item @if($active == 6 && $champ == "2-1" && $choix == "3") active @endif">
                                                <a href="{{asset('ligne_cmd_fourni?active=6&champ=2-1&choix=3')}}" class="menu-link">
                                                    <div class="menu-text">Ligne de commande</div>
                                                </a>
                                            </div>   
                                        </div>
                                    </div>
                                </div>
                        </div>                      
                  @endif
                  @if($entite_mods->mod_facturation == 1 && $dateJour <= $entite_mods->validite_mod)
                        <div class="menu-item has-sub @if($active == 7) active @endif">
                                <a href="javascript:;" class="menu-link">
                                    <div class="menu-icon">
                                        <i class="fa fa-money-bill"></i>
                                    </div>
                                    <div class="menu-text">Facturation</div>
                                    <div class="menu-caret"></div>
                                </a>
                                <div class="menu-submenu">                        
                                    <div class="menu-item has-sub @if($active == 7 && $champ == "1-1") active @endif">
                                        <a href="javascript:;" class="menu-link">
                                            <div class="menu-text">Factures clients</div>
                                            <div class="menu-caret"></div>
                                        </a>
                                        <div class="menu-submenu">
                                            <div class="menu-item  @if($active == 7 && $champ == "1-1" && $choix == "1") active @endif">
                                                <a href="{{asset('listing_fact_clt?active=7&champ=1-1&choix=1')}}" class="menu-link">
                                                    <div class="menu-text">Liste factures</div>
                                                </a>
                                            </div> 
                                            <div class="menu-item @if($active == 7 && $champ == "1-1" && $choix == "2") active @endif">
                                                <a href="{{asset('list_reglement_clt?active=7&champ=1-1&choix=2')}}" class="menu-link">
                                                    <div class="menu-text">Règlements</div>
                                                </a>
                                            </div>
                                            <div class="menu-item @if($active == 7 && $champ == "1-1" && $choix == "3") active @endif">
                                                <a href="{{asset('ligne_facture?active=7&champ=1-1&choix=3')}}" class="menu-link">
                                                    <div class="menu-text">Ligne de facture</div>
                                                </a>
                                            </div>  
                                        </div>
                                    </div>
                                    <div class="menu-item has-sub @if($active == 7 && $champ == "2-1") active @endif">
                                        <a href="javascript:;" class="menu-link">
                                            <div class="menu-text">Factures fournisseurs</div>
                                            <div class="menu-caret"></div>
                                        </a>
                                        <div class="menu-submenu">
                                            <div class="menu-item  @if($active == 7 && $champ == "2-1" && $choix == "1") active @endif">
                                                <a href="{{asset('listing_fact_fourni?active=7&champ=2-1&choix=1')}}" class="menu-link">
                                                    <div class="menu-text">Liste Factures</div>
                                                </a>
                                            </div> 
                                            <div class="menu-item @if($active == 7 && $champ == "2-1" && $choix == "2") active @endif">
                                                <a href="{{asset('list_reglement_fourni?active=7&champ=2-1&choix=2')}}" class="menu-link">
                                                    <div class="menu-text">Règlements</div>
                                                </a>
                                            </div> 
                                            <div class="menu-item @if($active == 7 && $champ == "2-1" && $choix == "3") active @endif">
                                                <a href="{{asset('ligne_facture_fourni?active=7&champ=2-1&choix=3')}}" class="menu-link">
                                                    <div class="menu-text">Ligne de facture</div>
                                                </a>
                                            </div>  
                                        </div>
                                    </div>
                                </div>
                        </div>                      
                  @endif
                  @if($entite_mods->mod_banque_caisse == 1 && $dateJour <= $entite_mods->validite_mod) 
                      <div class="menu-item has-sub @if($active == 8) active @endif">
                          <a href="javascript:;" class="menu-link">
                              <div class="menu-icon">
                                  <i class="fa fa-credit-card"></i>
                              </div>
                              <div class="menu-text">Banques | Caisses</div>
                              <div class="menu-caret"></div>
                          </a>  
                          <div class="menu-submenu">                                
                              <div class="menu-item @if($active == 8 && $champ == "1-1") active @endif">
                                  <a href="{{asset('banque_caisse?active=8&champ=1-1')}}" class="menu-link">
                                      <div class="menu-text">Liste des comptes</div>
                                  </a>
                              </div> 
                              <div class="menu-item @if($active == 8 && $champ == "1-2") active @endif">
                                <a href="{{asset('listing_ecriture?active=8&champ=1-2')}}" class="menu-link">
                                    <div class="menu-text">Liste écritures</div>
                                </a>
                            </div> 
                            <div class="menu-item @if($active == 8 && $champ == "1-3") active @endif">
                                <a href="{{asset('listing_paie_divers?active=8&champ=1-3')}}" class="menu-link">
                                    <div class="menu-text">Paiements divers</div>
                                </a>
                            </div> 
                            <div class="menu-item @if($active == 8 && $champ == "1-4") active @endif">
                                <a href="{{asset('virement_interne?active=8&champ=1-4')}}" class="menu-link">
                                    <div class="menu-text">Virement interne</div>
                                </a>
                            </div>  
                          </div>                    
                      </div>
                  @endif
                  @if($entite_mods->mod_fabrication == 1 && $dateJour <= $entite_mods->validite_mod)            
                      <div class="menu-item has-sub @if($active == 9) active @endif">
                          <a href="javascript:;" class="menu-link">
                              <div class="menu-icon">
                                  <i class="fa fa-shapes"></i>
                              </div>
                              <div class="menu-text">Gestion Fabrication</div>
                              <div class="menu-caret"></div>
                          </a>
                          <div class="menu-submenu">                        
                              <div class="menu-item has-sub @if($active == 9 && $champ == "1-1") active @endif">
                                  <a href="javascript:;" class="menu-link">
                                      <div class="menu-text">Nommenclature</div>
                                      <div class="menu-caret"></div>
                                  </a>
                                  <div class="menu-submenu">
                                      {{-- <div class="menu-item @if($active == 9 && $champ == "1-1" && $choix == "1") active @endif">
                                          <a href="{{asset('nouvelle_nomencla?active=9&champ=1-1&choix=1')}}" class="menu-link">
                                              <div class="menu-text">Nouveau</div>
                                          </a>
                                      </div> --}}
                                      <div class="menu-item @if($active == 9 && $champ == "1-1" && $choix == "2") active @endif">
                                          <a href="{{asset('listing_nomencla?active=9&champ=1-1&choix=2')}}" class="menu-link">
                                              <div class="menu-text">Liste Nomenclature</div>
                                          </a>
                                      </div>                             
                                  </div>
                              </div>
                              <div class="menu-item has-sub @if($active == 9 && $champ == "2-1") active @endif">
                                  <a href="javascript:;" class="menu-link">
                                      <div class="menu-text">Ordres de Fabrication</div>
                                      <div class="menu-caret"></div>
                                  </a>
                                  <div class="menu-submenu">
                                      {{-- <div class="menu-item @if($active == 9 && $champ == "2-1" && $choix == "1") active @endif">
                                          <a href="{{asset('nouveau_ordrefab?active=9&champ=2-1&choix=1')}}" class="menu-link">
                                              <div class="menu-text">Nouvelle Ordre</div>
                                          </a>
                                      </div> --}}
                                      <div class="menu-item @if($active == 9 && $champ == "2-1" && $choix == "2") active @endif">
                                          <a href="{{asset('listing_ordre?active=9&champ=2-1&choix=2')}}" class="menu-link">
                                              <div class="menu-text">Liste Ordres Fab.</div>
                                          </a>
                                      </div> 
                                  </div>
                              </div>
                          </div>
                      </div> 
                  @endif 
                  @if($entite_mods->mod_ticket == 1 && $dateJour <= $entite_mods->validite_mod) 
                      <div class="menu-item has-sub @if($active == 13) active @endif">
                          <a href="javascript:;" class="menu-link">
                              <div class="menu-icon">
                                  <i class="fas fa-ticket"></i>
                              </div>
                              <div class="menu-text">Ticket 
                                  {{-- <span class="bagde badge-danger px-1 rounded"><small>New</small></span> --}}
                              </div>
                              <div class="menu-caret"></div>
                          </a>
                          <div class="menu-submenu">
                              {{-- <div class="menu-item @if($active == 13 && $champ == "1-1") active @endif">
                                  <a href="{{asset('nouv_ticket?active=13&champ=1-1')}}" class="menu-link">
                                      <div class="menu-text">Nouveau ticket 
                                          <span class="bagde badge-danger px-1 rounded"><small>New</small></span>
                                      </div>
                                  </a>
                              </div> --}}
                              <div class="menu-item @if($active == 13 && $champ == "1-2") active @endif">
                                  <a href="{{asset('liste_ticket?active=13&champ=1-2')}}" class="menu-link">
                                    @if(auth()->user()->societe == 'Administration')
                                      <div class="menu-text">Consulter tickets</div>
                                    @else 
                                      <div class="menu-text">Mes tickets</div>
                                    @endif   
                                  </a>
                              </div>
                              {{-- <div class="menu-item @if($active == 13 && $champ == "1-3") active @endif">
                                  <a href="{{asset('mes_tickets?active=13&champ=1-3')}}" class="menu-link">
                                      <div class="menu-text">Mes tickets</div>
                                  </a>
                              </div> --}}
                          </div>
                      </div>
                  @endif
                  @if($entite_mods->mod_tache == 1 && $dateJour <= $entite_mods->validite_mod) 
                      <div class="menu-item has-sub @if($active == 14) active @endif">
                          <a href="javascript:;" class="menu-link">
                              <div class="menu-icon">
                                  <i class="fas fa-check-circle"></i>
                              </div>
                              <div class="menu-text">Gestion Tâches 
                                  {{-- <span class="bagde badge-danger px-1 rounded"><small>New</small></span> --}}
                              </div>
                              <div class="menu-caret"></div>
                          </a>
                          <div class="menu-submenu">
                              <div class="menu-item @if($active == 14 && $champ == "1-1") active @endif">
                                  <a href="{{asset('taches?active=14&champ=1-1')}}" class="menu-link">
                                      <div class="menu-text">Tâches 
                                          {{-- <span class="bagde badge-danger px-1 rounded"><small>New</small></span> --}}
                                      </div>
                                  </a>
                              </div>
                              <div class="menu-item @if($active == 14 && $champ == "1-2") active @endif">
                                  <a href="{{asset('etapes_taches?active=14&champ=1-2')}}" class="menu-link">
                                      <div class="menu-text">Etape</div>
                                  </a>
                              </div>
                          </div>
                      </div>
                  @endif                
                  @if($entite_mods->mod_gestion_stock == 1 && $dateJour <= $entite_mods->validite_mod) 
                    @if(auth()->user()->societe == "Administration" && auth()->user()->type_user == "Administrateur")           
                        <div class="menu-item has-sub @if($active == 15) active @endif">
                            <a href="javascript:;" class="menu-link">
                                <div class="menu-icon">
                                    <i class="fab fa-cc-mastercard"></i>
                                </div>
                                <div class="menu-text">Gestion Paie</div>
                                <div class="menu-caret"></div>
                            </a>
                            <div class="menu-submenu">                        
                                <div class="menu-item has-sub @if($active == 15 && $champ == "1-1") active @endif">
                                    <a href="javascript:;" class="menu-link">
                                        <div class="menu-text">Paie</div>
                                        <div class="menu-caret"></div>
                                    </a>
                                    <div class="menu-submenu">
                                        <div class="menu-item @if($active == 15 && $champ == "1-1" && $choix == "1") active @endif">
                                            <a href="{{asset('nouveau_produit?active=15&champ=1-1&choix=1')}}" class="menu-link">
                                                <div class="menu-text">Nouvelle paie</div>
                                            </a>
                                        </div>
                                        <div class="menu-item @if($active == 15 && $champ == "1-1" && $choix == "2") active @endif">
                                            <a href="{{asset('produit?active=15&champ=1-1&choix=2')}}" class="menu-link">
                                                <div class="menu-text">Liste des paies</div>
                                            </a>
                                        </div>                                                                                                           
                                    </div>
                                </div>
                                <div class="menu-item has-sub @if($active == 15 && $champ == "2-1") active @endif">
                                    <a href="javascript:;" class="menu-link">
                                        <div class="menu-text">Avances et prêts</div>
                                        <div class="menu-caret"></div>
                                    </a>
                                    <div class="menu-submenu">
                                        <div class="menu-item @if($active == 15 && $champ == "2-1" && $choix == "1") active @endif">
                                            <a href="{{asset('nouveau_avance?active=15&champ=2-1&choix=1')}}" class="menu-link">
                                                <div class="menu-text">Nouvelle avance</div>
                                            </a>
                                        </div>
                                        <div class="menu-item @if($active == 15 && $champ == "2-1" && $choix == "2") active @endif">
                                            <a href="{{asset('liste_avance?active=15&champ=2-1&choix=2')}}" class="menu-link">
                                                <div class="menu-text">Liste des avances</div>
                                            </a>
                                        </div>                                                                                                           
                                    </div>
                                </div>
                                {{-- <div class="menu-item @if($active == 15 && $champ == "2-1" && $choix == "1") active @endif">
                                    <a href="{{asset('categorie?active=4&champ=2-1&choix=1')}}" class="menu-link">
                                        <div class="menu-text">Catégorie</div>
                                    </a>
                                </div> --}}
                                <div class="menu-item has-sub @if($active == 15 && $champ == "3-1") active @endif">
                                    <a href="javascript:;" class="menu-link">
                                        <div class="menu-text">Paramètres</div>
                                        <div class="menu-caret"></div>
                                    </a>
                                    <div class="menu-submenu">                                    
                                        <div class="menu-item @if($active == 15 && $champ == "3-1" && $choix == "1") active @endif">
                                            <a href="{{asset('liste_categorie?active=15&champ=3-1&choix=1')}}" class="menu-link">
                                                <div class="menu-text">Catégorie paie</div>
                                            </a>
                                        </div>
                                        <div class="menu-item @if($active == 15 && $champ == "3-1" && $choix == "2") active @endif">
                                            <a href="{{asset('grille_salaire?active=15&champ=3-1&choix=2')}}" class="menu-link">
                                                <div class="menu-text">Grille de salaire</div>
                                            </a>
                                        </div>
                                        <div class="menu-item @if($active == 15 && $champ == "3-1" && $choix == "3") active @endif">
                                            <a href="{{asset('mouvements?active=15&champ=3-1&choix=3')}}" class="menu-link">
                                                <div class="menu-text">Règle de paie</div>
                                            </a>
                                        </div>
                                        <div class="menu-item @if($active == 15 && $champ == "3-1" && $choix == "4") active @endif">
                                            <a href="{{asset('mouvements?active=15&champ=3-1&choix=4')}}" class="menu-link">
                                                <div class="menu-text">Journal de paie</div>
                                            </a>
                                        </div>                                    
                                    </div>
                                </div>                      
                            </div>
                        </div> 
                    @endif              
                  @endif
                  @if($entite_mods->mod_gestion_commercial == 1 && $dateJour <= $entite_mods->validite_mod) 
                    @if(auth()->user()->societe == "Administration")           
                        <div class="menu-item has-sub @if($active == 16) active @endif">
                          <a href="javascript:;" class="menu-link">
                              <div class="menu-icon">
                                  <i class="fas fa-users"></i>
                              </div>
                              <div class="menu-text">Commercial 
                                  {{-- <span class="bagde badge-danger px-1 rounded"><small>New</small></span> --}}
                              </div>
                              <div class="menu-caret"></div>
                          </a>
                          <div class="menu-submenu">
                              {{-- <div class="menu-item @if($active == 16 && $champ == "1-1") active @endif">
                                  <a href="{{asset('taches?active=16&champ=1-1')}}" class="menu-link">
                                      <div class="menu-text">Tableau de bord 
                                      </div>
                                  </a>
                              </div> --}}
                              {{-- <div class="menu-item @if($active == 16 && $champ == "1-2") active @endif">
                                  <a href="{{asset('nouv_souscription?active=16&champ=1-2')}}" class="menu-link">
                                      <div class="menu-text">Nouvelle souscription</div>
                                  </a>
                              </div> --}}
                              <div class="menu-item @if($active == 16 && $champ == "1-3") active @endif">
                                  <a href="{{asset('liste_souscription?active=16&champ=1-3')}}" class="menu-link">
                                      <div class="menu-text">Liste souscription</div>
                                  </a>
                              </div>
                          </div>
                        </div>
                    @endif              
                  @endif          
                  @if($entite_mods->mod_multisociete == 1 && $dateJour <= $entite_mods->validite_mod) 
                      <div class="menu-item has-sub @if($active == 11) active @endif">
                          <a href="javascript:;" class="menu-link">
                              <div class="menu-icon">
                                  <i class="fas fa-sitemap"></i>
                              </div>
                              <div class="menu-text">Multi-sociétés 
                                  {{-- <span class="bagde badge-danger px-1 rounded"><small>New</small></span> --}}
                              </div>
                              <div class="menu-caret"></div>
                          </a>
                          <div class="menu-submenu">
                              <div class="menu-item @if($active == 11 && $champ == "1-1") active @endif">
                                  <a href="{{asset('liste_societe?active=11&champ=1-1')}}" class="menu-link">
                                      <div class="menu-text">Sociétés 
                                          {{-- <span class="bagde badge-danger px-1 rounded"><small>New</small></span> --}}
                                      </div>
                                  </a>
                              </div>
                              {{-- <div class="menu-item @if($active == 11 && $champ == "1-2") active @endif">
                                  <a href="{{asset('nouv_transfert_filiale?active=11&champ=1-2')}}" class="menu-link">
                                      <div class="menu-text">Nouveau transfert</div>
                                  </a>
                              </div> --}}
                              <div class="menu-item @if($active == 11 && $champ == "1-3") active @endif">
                                  <a href="{{asset('transfert_filiale?active=11&champ=1-3')}}" class="menu-link">
                                      <div class="menu-text">Consulter transferts</div>
                                  </a>
                              </div>
                          </div>
                      </div>
                  @endif 
                  @if($entite_mods->mod_administration == 1 && $dateJour <= $entite_mods->validite_mod) 
                      <div class="menu-item has-sub @if($active == 12) active @endif">
                          <a href="javascript:;" class="menu-link">
                              <div class="menu-icon">
                                  <i class="fa fa-cog fa-spin"></i>
                              </div>
                              <div class="menu-text">Paramètres</div>
                              <div class="menu-caret"></div>
                          </a>
                          <div class="menu-submenu">                              
                              <div class="menu-item @if($active == 12 && $champ == "1-1") active @endif">
                                <a href="{{asset('utilisateurs?active=12&champ=1-1')}}" class="menu-link">
                                    <div class="menu-text">Utilisateurs</div>
                                </a>
                            </div>
                              <div class="menu-item @if($active == 12 && $champ == "1-2") active @endif">
                                  <a href="{{asset('role_privillege?active=12&champ=1-2')}}" class="menu-link">
                                      <div class="menu-text">Rôles & Privilège</div>
                                  </a>
                              </div>
                              <div class="menu-item @if($active == 12 && $champ == "1-3") active @endif">
                                  <a href="{{asset('entite?active=12&champ=1-3')}}" class="menu-link">
                                      <div class="menu-text">Entités</div>
                                  </a>
                              </div>
                              <div class="menu-item @if($active == 12 && $champ == "1-4") active @endif">
                                <a href="{{asset('devise?active=12&champ=1-4')}}" class="menu-link">
                                    <div class="menu-text">Pays|Devise|Taxe</div>
                                </a>
                              </div>
                              <div class="menu-item @if($active == 12 && $champ == "1-5") active @endif">
                                <a href="{{asset('departement?active=12&champ=1-5')}}" class="menu-link">
                                    <div class="menu-text">Départements</div>
                                </a>
                              </div>   
                              <div class="menu-item @if($active == 12 && $champ == "1-6") active @endif">
                                <a href="{{asset('poste_travail?active=12&champ=1-6')}}" class="menu-link">
                                    <div class="menu-text">Poste de travail</div>
                                </a>
                              </div>   
                              <div class="menu-item @if($active == 12 && $champ == "1-7") active @endif">
                                <a href="{{asset('config?active=12&champ=1-7')}}" class="menu-link">
                                    <div class="menu-text">Configuration</div>
                                </a>
                              </div>                                   
                              @if(auth()->user()->societe == "Administration")
                                  <div class="menu-item @if($active == 12 && $champ == "1-8") active @endif">
                                      <a href="{{asset('solde_clients?active=12&champ=1-8')}}" class="menu-link">
                                          <div class="menu-text">Solde clients</div>
                                      </a>
                                  </div>
                                  <div class="menu-item @if($active == 12 && $champ == "1-9") active @endif">
                                      <a href="{{asset('logActivity?active=12&champ=1-9')}}" class="menu-link">
                                          <div class="menu-text">Activités</div>
                                      </a>
                                  </div>                           
                              @endif                                  
                          </div>
                      </div>
                  @endif
                  {{-- @if($entite_mods->mod_visiteurs == 1 && $dateJour <= $entite_mods->validite_mod) 
                      <div class="menu-item has-sub @if($active == 8) active @endif">
                          <a href="javascript:;" class="menu-link">
                              <div class="menu-icon">
                                  <i class="fa fa-cog fa-spin"></i>
                              </div>
                              <div class="menu-text">Gestion visiteurs</div>
                              <div class="menu-caret"></div>
                          </a>
                          <div class="menu-submenu">
                              <div class="menu-item @if($active == 8 && $champ == "8-1") active @endif">
                                  <a href="{{asset('utilisateurs?active=8&champ=8-1')}}" class="menu-link">
                                      <div class="menu-text">Utilisateurs</div>
                                  </a>
                              </div>
                              <div class="menu-item @if($active == 8 && $champ == "8-2") active @endif">
                                  <a href="{{asset('role_privillege?active=8&champ=8-2')}}" class="menu-link">
                                      <div class="menu-text">Rôles & Privilège</div>
                                  </a>
                              </div>
                              <div class="menu-item @if($active == 8 && $champ == "8-3") active @endif">
                                  <a href="{{asset('liste_entite?active=8&champ=8-3')}}" class="menu-link">
                                      <div class="menu-text">Entités</div>
                                  </a>
                              </div>
                              <div class="menu-item @if($active == 8 && $champ == "8-3") active @endif">
                                <a href="{{asset('devise')}}" class="menu-link">
                                    <div class="menu-text">Pays|Devise|Taxe</div>
                                </a>
                              </div>                                
                              @if(auth()->user()->societe == "Administration" && auth()->user()->type_user == "Administrateur")
                                  <div class="menu-item @if($active == 8 && $champ == "8-5") active @endif">
                                      <a href="{{asset('logActivity?active=8&champ=8-5')}}" class="menu-link">
                                          <div class="menu-text">Activités</div>
                                      </a>
                                  </div>
                              @endif 
                              @if(auth()->user()->societe == "Administration")
                                  <div class="menu-item @if($active == 8 && $champ == "8-6") active @endif">
                                      <a href="{{asset('commerciaux?active=8&champ=8-6')}}" class="menu-link">
                                          <div class="menu-text">Commerciaux</div>
                                      </a>
                                  </div> 
                              @endif                                  
                          </div>
                      </div>
                  @endif   --}}
                   {{-- @if($entite_mods->mod_pressing == "1" && $dateJour <= $entite_mods->validite_mod)       
                      <div class="menu-item has-sub @if($active == 3 ) active @endif">
                          <a href="javascript:;" class="menu-link">
                              <div class="menu-icon">
                                  <i class="fa fa-recycle fa-spin"></i>
                              </div>
                              <div class="menu-text">Gestion Pressing</div>
                              <div class="menu-caret"></div>
                          </a>
                          <div class="menu-submenu">                                
                              <div class="menu-item @if($active == 3 && $champ == "1-1") active @endif">
                                  <a href="{{asset('tableau_pressing?active=3&champ=1-1&choix=1')}}" class="menu-link">
                                      <div class="menu-text">Tableau de bord</div>
                                  </a>
                              </div>   
                              <div class="menu-item @if($active == 3 && $champ == "2-1") active @endif">
                                  <a href="{{asset('depot_article?active=3&champ=2-1&choix=1')}}" class="menu-link">
                                      <div class="menu-text">Dépôt</div>
                                  </a>
                              </div>                                
                              <div class="menu-item @if($active == 3 && $champ == "3-1") active @endif">
                                  <a href="{{asset('listing_depot?active=3&champ=3-1&choix=1')}}" class="menu-link">
                                      <div class="menu-text">Listing dépôt</div>
                                  </a>
                              </div>   
                              <div class="menu-item @if($active == 3 && $champ == "4-1") active @endif">
                                  <a href="{{asset('prestation?active=3&champ=4-1&choix=1')}}" class="menu-link">
                                      <div class="menu-text">Prestation</div>
                                  </a>
                              </div>                                   
                              <div class="menu-item @if($active == 3 && $champ == "5-1") active @endif">
                                  <a href="{{asset('article_pressing?active=3&champ=5-1&choix=1')}}" class="menu-link">
                                      <div class="menu-text">Article</div>
                                  </a>
                              </div>                            
                              <div class="menu-item @if($active == 3 && $champ == "6-1") active @endif">
                                  <a href="{{asset('categorie_pressing?active=3&champ=6-1&choix=1')}}" class="menu-link">
                                      <div class="menu-text">Catégorie</div>
                                  </a>
                              </div>
                              <div class="menu-item @if($active == 3 && $champ == "7-1") active @endif">
                                  <a href="{{asset('operation_pressing?active=3&champ=7-1&choix=1')}}" class="menu-link">
                                      <div class="menu-text">Caisse & Dépense</div>
                                  </a>
                              </div>
                              <div class="menu-item @if($active == 3 && $champ == "8-1") active @endif">
                                  <a href="{{asset('stat_pressing?active=3&champ=8-1&choix=1')}}" class="menu-link">
                                      <div class="menu-text">Statistique</div>
                                  </a>
                              </div>
                          </div>
                      </div>  
                  @endif     --}}
                  {{-- <div class="menu-item has-sub @if($active == 13 ) active @endif">
                      <a href="{{asset('calendrier?active=13')}}" class="menu-link">
                          <div class="menu-icon">
                              <i class="fa fa-calendar"></i>
                          </div>
                          <div class="menu-text">Agenda</div>
                      </a>                
                  </div>  --}}
                  {{-- <div class="menu-item has-sub @if($active == 5) active @endif">
                      <a href="javascript:;" class="menu-link">
                          <div class="menu-icon">
                              <i class="fa fa-money-bill"></i>
                          </div>
                          <div class="menu-text">Facturation</div>
                          <div class="menu-caret"></div>
                      </a>                            
                      <div class="menu-submenu">
                          <div class="menu-item has-sub @if($active == 5 && $champ == "1-1") active @endif">
                              <a href="javascript:;" class="menu-link">
                                  <div class="menu-text">Clients</div>
                                  <div class="menu-caret"></div>
                              </a>
                              <div class="menu-submenu">
                                  <div class="menu-item  @if($active == 5 && $champ == "1-1" && $choix == "1") active @endif">
                                      <a href="{{asset('fact_clt?active=5&champ=1-1&choix=1')}}" class="menu-link">
                                          <div class="menu-text">Factures clients</div>
                                      </a>
                                  </div>  
                                  <div class="menu-item  @if($active == 5 && $champ == "1-1" && $choix == "2") active @endif">
                                      <a href="{{asset('avoir_clt?active=5&champ=1-1&choix=2')}}" class="menu-link">
                                          <div class="menu-text">Avoir clients</div>
                                      </a>
                                  </div> 
                              </div>
                          </div>
                          <div class="menu-item has-sub @if($active == 5 && $champ == "2-1") active @endif">
                              <a href="javascript:;" class="menu-link">
                                  <div class="menu-text">Fournisseurs</div>
                                  <div class="menu-caret"></div>
                              </a>
                              <div class="menu-submenu">
                                  <div class="menu-item  @if($active == 5 && $champ == "2-1" && $choix == "1") active @endif">
                                      <a href="{{asset('fact_fournis?active=5&champ=2-1&choix=1')}}" class="menu-link">
                                          <div class="menu-text">Factures fournisseurs</div>
                                      </a>
                                  </div>  
                                  <div class="menu-item  @if($active == 5 && $champ == "2-1" && $choix == "2") active @endif">
                                      <a href="{{asset('avoir_fournis?active=5&champ=2-1&choix=2')}}" class="menu-link">
                                          <div class="menu-text">Avoir fournisseurs</div>
                                      </a>
                                  </div> 
                              </div>
                          </div>
                          <div class="menu-item has-sub @if($active == 5 && $champ == "3-1") active @endif">
                              <a href="javascript:;" class="menu-link">
                                  <div class="menu-text">Bon de commande</div>
                                  <div class="menu-caret"></div>
                              </a>
                              <div class="menu-submenu">
                                  <div class="menu-item  @if($active == 5 && $champ == "3-1" && $choix == "1") active @endif">
                                      <a href="{{asset('nouveau_bcmd?active=5&champ=3-1&choix=1')}}" class="menu-link">
                                          <div class="menu-text">Nouv. Commande</div>
                                      </a>
                                  </div>  
                                  <div class="menu-item  @if($active == 5 && $champ == "3-1" && $choix == "2") active @endif">
                                      <a href="{{asset('listing_cmd?active=5&champ=3-1&choix=2')}}" class="menu-link">
                                          <div class="menu-text">Bon Commandes</div>
                                      </a>
                                  </div> 
                              </div>
                          </div>
                          <div class="menu-item has-sub @if($active == 5 && $champ == "4-1") active @endif">
                              <a href="javascript:;" class="menu-link">
                                  <div class="menu-text">Devis / Proforma</div>
                                  <div class="menu-caret"></div>
                              </a>
                              <div class="menu-submenu">
                                  <div class="menu-item  @if($active == 5 && $champ == "4-1" && $choix == "1") active @endif">
                                      <a href="{{asset('nouveau_bcmd?active=5&champ=4-1&choix=1')}}" class="menu-link">
                                          <div class="menu-text">Nouveau</div>
                                      </a>
                                  </div>
                                  <div class="menu-item  @if($active == 5 && $champ == "4-1" && $choix == "2") active @endif">
                                      <a href="{{asset('listing_proforma?active=5&champ=4-1&choix=2')}}" class="menu-link">
                                          <div class="menu-text">Proforma</div>
                                      </a>
                                  </div> 
                                  <div class="menu-item  @if($active == 5 && $champ == "4-1" && $choix == "3") active @endif">
                                      <a href="{{asset('listing_devis?active=5&champ=4-1&choix=3')}}" class="menu-link">
                                          <div class="menu-text">Devis</div>
                                      </a>
                                  </div>                                                                    
                              </div>
                          </div>                                
                      </div>
                  </div>                --}}
                  {{-- <div class="menu-item has-sub">
                      <a href="javascript:;" class="menu-link">
                          <div class="menu-icon"><i class="fa fa-hdd"></i></div>
                          <div class="menu-text">Email</div>
                          <div class="menu-badge">10</div>
                      </a>
                      <div class="menu-submenu">
                          <div class="menu-item">
                              <a href="#" class="menu-link"><div class="menu-text">Inbox</div></a>
                          </div>
                          <div class="menu-item">
                              <a href="#" class="menu-link"><div class="menu-text">Compose</div></a>
                          </div>
                          <div class="menu-item">
                              <a href="#" class="menu-link"><div class="menu-text">Detail</div></a>
                          </div>
                      </div>
                  </div>   --}}
              @endforeach
              <div class="menu-item d-flex">
                  <a href="javascript:;" class="app-sidebar-minify-btn ms-auto" data-toggle="app-sidebar-minify"><i class="fa fa-angle-double-left"></i></a>
              </div>
          </div>
      </div>
  </div>
  <div class="app-sidebar-bg"></div>
  <div class="app-sidebar-mobile-backdrop"><a href="#" data-dismiss="app-sidebar-mobile" class="stretched-link"></a></div>
</div>