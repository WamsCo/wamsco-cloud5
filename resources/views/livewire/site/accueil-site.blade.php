<div>
    <section class="content-section">
        <section class="content-text">
            <div class="content-textpad">
                <div data-drupal-messages-fallback class="hidden"></div>
                <div id="block-mainhomepage">
                    <div>                                                
                        <section style="background: #ffffff">
                            <div class="block_video_youtube">                            
                                <div class="part_video">
                                    <div class="bloc_video_propos" data-nav-dots="false" data-nav-arrow="true" data-items="1" data-lg-items="1" data-md-items="1" data-sm-items="1" data-xs-items="1" data-space="0" data-autoplay="true">
                                        <div>
                                            <iframe class="donnee_youtube" src="https://www.youtube.com/embed/CO1P4NeGOEs?si=lxUou_cUL60kEfQB" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                        </div>                            
                                    </div>
                                </div>
                                <div class="part_text2" id="propos">
                                    {{-- <h2 class="wams">WamsCo-Cloud</h2> --}}
                                    <h2 class="titre_haut">@lang('site.title')</h2>
                                    <div class="description_wams">@lang('site.message')</div>
                                    <p class="slogan_wams">@lang('site.slogan')</p>
                                </div>
                            </div>
                        </section>                       
                        <section class="hp_empowering">
                            <div class="hp_empowering_texts">
                                <h2>@lang('site.title_section_platform')</h2>
                                <p>@lang('site.description_section_platform') <span style="color:#fe4f51; font-weight:bold;">WamsCo</span>-Cloud.</p>
                            </div>
                            <div class="hp_empowering_imgs">
                                <picture><img alt="Logiciel de gestion des stocks" width="368" height="368" src="site/default/files/image/WamsCo-flyers4.png" style="border-radius: 10px;box-shadow: 0px 1px 4px 1px #5e5e5e;"/></picture>
                                <picture><img alt="Logiciel de gestion des stocks" width="368" height="368" src="site/default/files/image/WamsCo-flyers2.png" style="border-radius: 10px;box-shadow: 0px 1px 4px 1px #5e5e5e;"/></picture>
                                <picture><img alt="Logiciel de gestion des stocks" width="368" height="368" src="site/default/files/image/WamsCo-flyers.png" style="border-radius: 10px;box-shadow: 0px 1px 4px 1px #5e5e5e;"/></picture>
                                {{-- <picture><img alt="Logiciel de gestion des stocks" width="368" height="368" src="site/default/files/image/pos2.png" style="border-radius: 10px;box-shadow: 2px 2px 11px 2px #5e5e5e;"/></picture> --}}
                                {{-- <picture class="empowering-img-margin"><img alt="Logiciel de gestion des stocks" width="368" height="368" src="site/default/files/image/ordi_pv.jpg" /></picture> --}}
                                {{-- <picture><img alt="Logiciel de gestion des stocks" width="368" height="368" src="site/default/files/image/formation.jpg" style="border-radius: 10px;box-shadow: 2px 2px 11px 2px #5e5e5e;"/></picture> --}}
                            </div>
                        </section>
                        <section class="hp_tools" style="background-color: #ffffff; padding-top:25px;" id="">
                            <h2 class="hp_tools_title">@lang('site.title_section_outil')</h2>
                            <div class="row profile-content container-fluid px-2" style=" max-width: 1312px; margin: auto;">
                                <div class="col-sm-4 col-lg-3 mb-4">
                                    <div class="cardor border-0 bg-white-500 text-white">
                                        <a href="{{asset('#')}}" wire:navigate class="bloc_mod" title="Acteurs clés du système: Clients, Fournisseurs, Prospects etc...">
                                            <div class="bloc_img_text">
                                                <div class="part_img">
                                                    <img src="storage/img_module/8.png" alt="" class="img_mod">
                                                </div>
                                                <div class="part_text2">
                                                    <h5 class="card-title taille_titre_mod"><i class="fa fa-users"></i> Tiers</h5>
                                                    <p class="truncate_wamsco mb-1">Acteurs clés du système: Clients, Fournisseurs, Prospects etc...</p>
                                                    {{-- <p class="card-text fw-bold badge bg-green mt-2">Activer</p> --}}
                                                </div>
                                            </div>
                                        </a>                                        
                                    </div>                    
                                </div>
                                <div class="col-sm-4 col-lg-3 mb-4">
                                    <div class="cardor border-0 bg-white-500 text-white">
                                        <a href="{{asset('#')}}" wire:navigate class="bloc_mod" title="Le CRM est à la fois une stratégie d'entreprise et un ensemble d'outils logiciels qui permettent de gérer et d'analyser les interactions d'une entreprise avec ses clients et prospects">
                                            <div class="bloc_img_text">
                                                <div class="part_img">
                                                    <img src="storage/img_module/9.png" alt="" class="img_mod">
                                                </div>
                                                <div class="part_text2">
                                                    <h5 class="card-title taille_titre_mod"><i class="fa fa-users"></i> CRM</h5>
                                                    <p class="truncate_wamsco mb-1">Le CRM est à la fois une stratégie d'entreprise et un ensemble d'outils logiciels qui permettent de gérer et d'analyser les interactions d'une entreprise avec ses clients et prospects</p>
                                                    {{-- <p class="card-text fw-bold badge bg-green mt-2">Activer</p> --}}
                                                </div>
                                            </div>
                                        </a>                                        
                                    </div>                    
                                </div>
                                 <div class="col-sm-4 col-lg-3 mb-4">
                                    <div class="cardor border-0 bg-white-500 text-white">
                                        <a href="{{asset('#')}}" wire:navigate class="bloc_mod" title="Ensemble des marchandises, matières premières, composants ou produits finis d'une entreprise (Produit, Catégorie, Entrepôt, Mouvements)">
                                            <div class="bloc_img_text">
                                                <div class="part_img">
                                                    <img src="storage/img_module/6.png" alt="" class="img_mod">
                                                </div>
                                                <div class="part_text2">
                                                    <h5 class="card-title taille_titre_mod"><i class="fa fa-box-open"></i> Stock</h5>
                                                    <p class="truncate_wamsco mb-1">Ensemble des marchandises, matières premières, composants ou produits finis d'une entreprise (Produit, Catégorie, Entrepôt, Mouvements)</p>
                                                    {{-- <p class="card-text fw-bold badge bg-green mt-2">Activer</p> --}}
                                                </div>
                                            </div>
                                        </a>
                                    </div>                    
                                </div>
                                <div class="col-sm-4 col-lg-3 mb-4">
                                    <div class="cardor border-0 bg-white-500 text-white">
                                        <a href="{{asset('#')}}" wire:navigate class="bloc_mod" title="Système de vente conviviale, performant et intuitif pour tous type de commerce.">
                                            <div class="bloc_img_text">
                                                <div class="part_img">
                                                    <img src="storage/img_module/1.png" alt="" class="img_mod">
                                                </div>
                                                <div class="part_text2">
                                                    <h5 class="card-title taille_titre_mod"><i class="fa fa-cash-register"></i> Point Vente</h5>
                                                    <p class="truncate_wamsco mb-1">Système de vente conviviale, performant et intuitif pour tous type de commerce.</p>
                                                    {{-- <p class="card-text fw-bold badge bg-green mt-2">Activer</p> --}}
                                                </div>
                                            </div>
                                        </a>                                        
                                    </div>                    
                                </div> 
                                <div class="col-sm-4 col-lg-3 mb-4">
                                    <div class="cardor border-0 bg-white-500 text-white">
                                        <a href="{{asset('#')}}" wire:navigate class="bloc_mod" title="Module permettant la gestion complète des commandes, du suivi de la préparation des repas ainsi que de l’organisation et de l’occupation des tables au sein d’un restaurant.">
                                            <div class="bloc_img_text">
                                                <div class="part_img">
                                                    <img src="storage/img_module/restos.jpg" alt="" class="img_mod">
                                                </div>
                                                <div class="part_text2">
                                                    <h5 class="card-title taille_titre_mod"><i class="fa fa-cash-register"></i> Restaurant</h5>
                                                    <p class="truncate_wamsco mb-1">Module permettant la gestion complète des commandes, du suivi de la préparation des repas ainsi que de l’organisation et de l’occupation des tables au sein d’un restaurant.</p>
                                                    {{-- <p class="card-text fw-bold badge bg-green mt-2">Activer</p> --}}
                                                </div>
                                            </div>
                                        </a>                                        
                                    </div>                    
                                </div> 
                                <div class="col-sm-4 col-lg-3 mb-4">
                                    <div class="cardor border-0 bg-white-500 text-white">
                                        <a href="{{asset('#')}}" wire:navigate class="bloc_mod" title="Système d'affichage des commandes en cuisine, leur statut et en temps réel.">
                                            <div class="bloc_img_text">
                                                <div class="part_img">
                                                    <img src="storage/img_module/cuisine.jpg" alt="" class="img_mod">
                                                </div>
                                                <div class="part_text2">
                                                    <h5 class="card-title taille_titre_mod"><i class="fa fa-tv"></i> Ecran cuisine</h5>
                                                    <p class="truncate_wamsco mb-1">Système d'affichage des commandes en cuisine, leur statut et en temps réel.</p>
                                                    {{-- <p class="card-text fw-bold badge bg-green mt-2">Activer</p> --}}
                                                </div>
                                            </div>
                                        </a>
                                    </div>                    
                                </div>
                                <div class="col-sm-4 col-lg-3 mb-4">
                                    <div class="cardor border-0 bg-white-500 text-white">
                                        <a href="{{asset('#')}}" wire:navigate class="bloc_mod" title="Acte par lequel un acheteur s'engage à acquérir des produits ou des services auprès d'un vendeur (Proforma, Commande, Expédition, Réception etc.).">
                                            <div class="bloc_img_text">
                                                <div class="part_img">
                                                    <img src="storage/img_module/person.jpg" alt="" class="img_mod">
                                                </div>
                                                <div class="part_text2">
                                                    <h5 class="card-title taille_titre_mod"><i class="fas fa-file-invoice"></i> Commande</h5>
                                                    <p class="truncate_wamsco mb-1">Acte par lequel un acheteur s'engage à acquérir des produits ou des services auprès d'un vendeur (Proforma, Commande, Expédition, Réception etc.).</p>
                                                    {{-- <p class="card-text fw-bold badge bg-green mt-2">Activer</p> --}}
                                                </div>
                                            </div>
                                        </a>                            
                                    </div>                    
                                </div>
                                <div class="col-sm-4 col-lg-3 mb-4">
                                    <div class="cardor border-0 bg-white-500 text-white">
                                        <a href="{{asset('#')}}" wire:navigate class="bloc_mod" title="Une facture est un document légal, commercial et comptable qui constate officiellement une transaction de vente de biens ou de prestation de services.">
                                            <div class="bloc_img_text">
                                                <div class="part_img">
                                                    <img src="storage/img_module/5.png" alt="" class="img_mod">
                                                </div>
                                                <div class="part_text2">
                                                    <h5 class="card-title taille_titre_mod"><i class="fa fa-money-bill"></i> Facturation</h5>
                                                    <p class="truncate_wamsco mb-1">Une facture est un document légal, commercial et comptable qui constate officiellement une transaction de vente de biens ou de prestation de services.</p>
                                                    {{-- <p class="card-text fw-bold badge bg-green mt-2">Activer</p> --}}
                                                </div>
                                            </div>
                                        </a>
                                    </div>                    
                                </div>                    
                                <div class="col-sm-4 col-lg-3 mb-4">
                                    <div class="cardor border-0 bg-white-500 text-white">
                                        <a href="{{asset('#')}}" wire:navigate class="bloc_mod" title="Ensemble des opérations de trésorerie courante gérées directement par l'entreprise (Finances, comptes, écritures, virement)">
                                            <div class="bloc_img_text">
                                                <div class="part_img">
                                                    <img src="storage/img_module/7.png" alt="" class="img_mod">
                                                </div>
                                                <div class="part_text2">
                                                    <h5 class="card-title taille_titre_mod"><i class="fa fa-credit-card"></i> Banque/Caisse</h5>
                                                    <p class="truncate_wamsco mb-1">Ensemble des opérations de trésorerie courante gérées directement par l'entreprise (Finances, comptes, écritures, virement)</p>
                                                    {{-- <p class="card-text fw-bold badge bg-green mt-2">Activer</p> --}}
                                                </div>
                                            </div>
                                        </a>
                                    </div>                    
                                </div>
                                <div class="col-sm-4 col-lg-3 mb-4">
                                    <div class="cardor border-0 bg-white-500 text-white">
                                        <a href="{{asset('#')}}" class="bloc_mod" title="La Fabrication à partir d'une nomenclature est le processus clé qui transforme une liste structurée de composants en un produit fini vendable.">
                                            <div class="bloc_img_text">
                                                <div class="part_img">
                                                    <img src="storage/img_module/10.png" alt="" class="img_mod">
                                                </div>
                                                <div class="part_text2">
                                                    <h5 class="card-title taille_titre_mod"><i class="fa fa-shapes"></i> Fabrication</h5>
                                                    <p class="truncate_wamsco mb-1">La Fabrication à partir d'une nomenclature est le processus clé qui transforme une liste structurée de composants en un produit fini vendable.</p>
                                                    {{-- <p class="card-text fw-bold badge bg-green mt-2">Activer</p> --}}
                                                </div>
                                            </div>
                                        </a>
                                    </div>                    
                                </div>
                                <div class="col-sm-4 col-lg-3 mb-4">
                                    <div class="cardor border-0 bg-white-500 text-white">
                                        <a href="{{asset('#')}}" class="bloc_mod" title="Le ticket est un enregistrement formel et numéroté d'une demande ou d'un problème qu'un client ou un employé soumet à une équipe de support (service après-vente, assistance technique, etc.).">
                                            <div class="bloc_img_text">
                                                <div class="part_img">
                                                    <img src="storage/img_module/ticket.jpg" alt="" class="img_mod">
                                                </div>
                                                <div class="part_text2">
                                                    <h5 class="card-title taille_titre_mod"><i class="fa fa-shapes"></i> Ticket</h5>
                                                    <p class="truncate_wamsco mb-1">Le ticket est un enregistrement formel et numéroté d'une demande ou d'un problème qu'un client ou un employé soumet à une équipe de support (service après-vente, assistance technique, etc.).</p>
                                                    {{-- <p class="card-text fw-bold badge bg-green mt-2">Activer</p> --}}
                                                </div>
                                            </div>
                                        </a>
                                    </div>                    
                                </div>
                                <div class="col-sm-4 col-lg-3 mb-4">
                                    <div class="cardor border-0 bg-white-500 text-white">
                                        <a href="{{asset('#')}}" class="bloc_mod" title="La gestion des tâches est le processus qui consiste à identifier, suivre et exécuter le travail nécessaire pour atteindre un objectif.">
                                            <div class="bloc_img_text">
                                                <div class="part_img">
                                                    <img src="storage/img_module/taches.png" alt="" class="img_mod">
                                                </div>
                                                <div class="part_text2">
                                                    <h5 class="card-title taille_titre_mod"><i class="fa fa-shapes"></i> Gestion Tâches</h5>
                                                    <p class="truncate_wamsco mb-1">La gestion des tâches est le processus qui consiste à identifier, suivre et exécuter le travail nécessaire pour atteindre un objectif.</p>
                                                    {{-- <p class="card-text fw-bold badge bg-green mt-2">Activer</p> --}}
                                                </div>
                                            </div>
                                        </a>
                                    </div>                    
                                </div>
                                <div class="col-sm-4 col-lg-3 mb-4">
                                    <div class="cardor border-0 bg-white-500 text-white">
                                        <a href="{{asset('#')}}" wire:navigate class="bloc_mod" title="Avec WamsCo, gérer plusieurs sociétés sans contrainte au meme endroit et avec un seul compte.">
                                            <div class="bloc_img_text">
                                                <div class="part_img">
                                                    <img src="storage/img_module/2.png" alt="" class="img_mod">
                                                </div>
                                                <div class="part_text2">
                                                    <h5 class="card-title taille_titre_mod"><i class="fas fa-sitemap"></i> Multi-société</h5>
                                                    <p class="truncate_wamsco mb-1">Avec WamsCo, gérer plusieurs sociétés sans contrainte au meme endroit et avec un seul compte.</p>
                                                    {{-- <p class="card-text fw-bold badge bg-green mt-2">Activer</p>  --}}
                                                </div>
                                            </div>                                    
                                        </a>
                                    </div>                    
                                </div> 
                            </div>
                        </section>
                        {{-- <section class="hp_tools" id="">
                            <h2 class="hp_tools_title">@lang('site.title_section_outil')</h2>

                            <div class="hp_tools_tabs" id="tab">
                                <div class="tabs_wrapper">
                                    <a class="tab_link tabs-icons1" href="#tab1">@lang('site.gestion_pv')</a> 
                                    <a class="tab_link tabs-icons1" href="#tab2">@lang('site.gestion_fabrication')</a> 
                                    <a class="tab_link tabs-icons2" href="#tab3">@lang('site.gestion_stocks')</a> 
                                    <a class="tab_link tabs-icons3" href="#tab4">@lang('site.gestion_analyse_ventes')</a> 
                                    <a class="tab_link tabs-icons4" href="#tab5">@lang('site.gestion_employes')</a>
                                    <a class="tab_link tabs-icons5" href="#tab6">@lang('site.gestion_clients')</a>
                                    <a class="tab_link tabs-icons5" href="#tab7">@lang('site.gestion_mulit_mag')</a> 
                                    <a class="tab_link tabs-icons5" href="#tab8">@lang('site.gestion_pressing')</a></div>
                            </div>

                            <div class="hp_tools_content hp_tools_pointofsale" id="tab1"> 
                                <figure class="hp_tools_img"><img alt="Logiciel de vente puissant et facile à utiliser" width="537" height="338" src="site/default/files/image/formation3.jpg"/></figure>
                                <div class="hp_tools_block">
                                    <h3 class="hp_tools_block_title"><i class="fa fa-desktop"></i> @lang('site.gestion_pv')</h3>
                                    <p class="hp_tools_subtitle">@lang('site.gestion_pv_title')</p>
                                    <ul class="hp_tools_text">
                                        <li>@lang('site.gestion_pv_option1')</li>
                                        <li>@lang('site.gestion_pv_option2')</li>
                                        <li>@lang('site.gestion_pv_option3')</li>                                     
                                        <li>@lang('site.gestion_pv_option4')</li>                                     
                                        <li>@lang('site.gestion_pv_option5')</li>                                     
                                        <li>@lang('site.gestion_pv_option6')</li>                                     
                                        <li>@lang('site.gestion_pv_option7')</li>                                     
                                    </ul>
                                </div>
                            </div>

                            <div class="hp_tools_content hp_tools_payments" id="tab2">
                                <figure class="hp_tools_img"><img alt="Image qui illustre la gestion de la fabrication" width="546" height="306" src="/storage/img_module/10.png"/></figure>
                                <div class="hp_tools_block">
                                    <div class="hp_tools_icon icon_payments"> </div>
                                    <h3 class="hp_tools_block_title">@lang('site.gestion_fabrications')</h3>
                                    <p class="hp_tools_subtitle">@lang('site.gestion_fab_title')</p>
                                    <ul class="hp_tools_text">
                                        <li>@lang('site.gestion_fab_option1')</li>
                                        <li>@lang('site.gestion_fab_option2')</li>
                                </div>
                            </div>

                            <div class="hp_tools_content hp_tools_inventory" id="tab3">
                                <figure class="hp_tools_img"><img alt="Gestion des stocks" height="400"
                                        src="site/default/files/image/stock.png" width="567" /></figure>
                                <div class="hp_tools_block">
                                    <h3 class="hp_tools_block_title"><i class="fa fa-shopping-cart"></i> @lang('site.gestion_stocks')</h3>
                                    <p class="hp_tools_subtitle">@lang('site.gestion_stock_title')</p>
                                    <ul class="hp_tools_text">
                                        <li>@lang('site.gestion_stock_option1')</li>
                                        <li>@lang('site.gestion_stock_option2')</li>
                                        <li>@lang('site.gestion_stock_option3')</li>
                                        <li>@lang('site.gestion_stock_option4')</li>
                                        <li>@lang('site.gestion_stock_option5')</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="hp_tools_content hp_tools_analytics" id="tab4">
                                <figure class="hp_tools_img"><img alt="Rapports d'analyse des ventes" height="433" src="site/default/files/image/stat.jpg" width="560" /></figure>
                                <div class="hp_tools_block">
                                    <h3 class="hp_tools_block_title"><i class="fa fa-chart-pie"></i> Analyse de ventes</h3>
                                    <p class="hp_tools_subtitle">Vérifiez vos rapports depuis un téléphone, une tablette ou un ordinateur à tout moment et partout où vous vous trouvez.</p>

                                    <ul class="hp_tools_text">
                                        <li>Afficher les revenus, les ventes moyennes et les bénéfices.</li>
                                        <li>Suivre l'évolution des ventes et réagir rapidement aux changements.</li>
                                        <li>Déterminer les articles et les catégories les plus vendus.</li>
                                        <li>Afficher l'historique complet des ventes.</li>
                                        <li>Exporter les données de vente vers les feuilles de calcul excel.</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="hp_tools_content" id="tab5">
                                <figure class="hp_tools_img"><img alt="Gestion des employés" height="399" src="site/default/files/image/users.jpg" width="594" /></figure>
                                <div class="hp_tools_block">
                                    <h3 class="hp_tools_block_title"><i class="fa fa-users"></i> Gestion des employés/utilisateurs</h3>
                                    <p class="hp_tools_subtitle">Gérez facilement votre équipe et prenez des décisions réfléchies.</p>
                                    <ul class="hp_tools_text">
                                        <li>Suivez les ventes par l'employé et déterminer les meilleures résultats.</li>
                                        <li>Définissez la hiérarchie de vos employés / utilisateurs</li>
                                        <li>Définissez les différents niveaux d'accès pour protéger les informations sensibles</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="hp_tools_content hp_tools_crm" id="tab6"> 
                                <figure class="hp_tools_img"><img alt="Suivie des clients"height="488" src="site/default/files/image/client.png" width="534" /></figure>
                                <div class="hp_tools_block">
                                    <h3 class="hp_tools_block_title"><i class="fa fa-user"></i> Suivie des clients</h3>
                                    <p class="hp_tools_subtitle">Transformez les clients occasionnels en clients réguliers</p>
                                    <ul class="hp_tools_text">
                                        <li>Restez connecté en permanence avec vos clients</li>
                                        <li>Construisez votre clientèle</li>
                                        <li>Consulter l'historique des achats du client pour offrir un service personalisé</li>
                                        <li>Prendre des notes sur les préférences des clients</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="hp_tools_content" id="tab7">
                                <figure class="hp_tools_img"><img alt="Gestion de plusieurs magasins" height="385" src="site/default/files/image/multi.png" width="556" /></figure>
                                <div class="hp_tools_block">
                                    <h3 class="hp_tools_block_title"><i class="fa fa-toilets-portable"></i> Gestion de plusieurs magasins</h3>
                                    <p class="hp_tools_subtitle">Développez votre entreprise en transformant un seul magasin en une centaines</p>
                                    <ul class="hp_tools_text">
                                        <li>Accès instantané aux données de vos différents magasins avec un seul compte.</li>
                                        <li>Transferez les stocks d'un magasin a l'autre à tout moment.</li>
                                        <li>Comparez les performances de vos magasins.</li>
                                        <li>Gérer les articles, les employés et les clients sur plusieurs sites avec un seul compte.</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="hp_tools_content hp_tools_integrations" id="tab8">
                                <figure class="hp_tools_img"><img alt="Gestion de pressing" height="332" src="site/default/files/image/pressing2.jpg" width="555" /></figure>
                                <div class="hp_tools_block">
                                    <h3 class="hp_tools_block_title"><i class="fa fa-recycle fa-spin"></i> Gestion de pressing</h3>
                                    <p class="hp_tools_subtitle">Gérer facilement et en tout simplicité votre Pressing.</p>
                                    <ul class="hp_tools_text">
                                        <li>Enregistrer les dépôts de linge en quelque clic.</li>
                                        <li>Planifier la date de livraison en fonction de la charge.</li>
                                        <li>Verifiez l'etat d'avancement de la prestation ou dépôts de linge.</li>
                                        <li>Suivre l'évolution des ventes et réagir rapidement aux changements.</li>
                                        <li>Faites des remises, Tva et autres.</li>
                                    </ul>
                                </div>
                            </div>
                            <div style="display:flex; justify-content:center; gap:15px; padding-bottom: 42px;">
                                <div>                         
                                    <a class="dash-button button_dev_clt" style="background: #0c1031; font-weight:700" href="inscription"><i class="fa fa-users"></i> @lang('site.devenir_client') <span class="hidden-word"> </span></a>
                                </div>
                                <div>
                                    <a class="dash-button" style="font-weight: 700" href="connexion?demo=essai"><i class="fa fa-cog fa-spin"></i> @lang('site.essai_free') <span class="hidden-word"> </span></a>
                                </div>
                            </div>                            
                        </section> --}}
                        <section class="hp_fits gray" style="background-color: #f6f9fb;" id="entreprise">
                            <h3 class="hp_fits_title">Le logiciel <span style="color:#fe4f51; font-weight:bold;">WamsCo</span> s'adapte avec votre entreprise</h3>
                            <div class="hp_fits_wrapper">                                
                                <dl>
                                    <dt class="img_group"> <span class="icon_group"><i class="fa fa-basket-shopping"></i></span></dt>
                                    <dt>Vente au détail</dt>
                                    <dd>Pharmacies</dd>
                                    <dd>Boutique</dd>
                                    <dd>Quincailleries</dd>
                                    <dd>Épicerie</dd>
                                    <dd>Magasin coopérative</dd>
                                    <dd>Magasin d'alcools</dd>
                                    <dd>Mode</dd>
                                    <dd>Bijouterie</dd>
                                    <dd>Kiosque</dd>
                                </dl>
                                <dl>
                                    <dt class="img_group">  <span class="icon_group"><i class="fa fa-wine-bottle"></i></span></dt>
                                    <dt>Nourriture et boisson</dt>
                                    <dd>Snack</dd>
                                    <dd>Bar</dd>
                                    <dd>Restaurant</dd>
                                    <dd>Macquis</dd>
                                    <dd>Café</dd>
                                    <dd>Restauration rapide</dd>
                                    <dd>Pizzeria</dd>
                                    <dd>Boulangerie</dd>
                                    <dd>Super-marché et Mini</dd>
                                </dl>
                                <dl>
                                    <dt class="img_group"> <span class="icon_group"><i class="fa fa-bell-concierge"></i></span></dt>
                                    <dt>Services</dt>
                                    <dd>Transport</dd>
                                    <dd>Lave-Auto</dd>
                                    <dd>Salon</dd>
                                    <dd>Locations</dd>
                                    <dd>Hôtels</dd>
                                    <dd>Pressing</dd>
                                </dl>
                            </div>
                        </section>
                        <section class="hp_testimonials" style="background: #ffffff">
                            <h3 class="hp_testimonials_title">Témoignages</h3>
                            <ul class="hp_testimonials_wrapper">
                                <li class="testimonial testimonial_1">
                                    <figure class="gh_icon icon-live"><img alt="redshark" data-entity-type="" data-entity-uuid="" style="width: 100px; border-radius: 50%;" src="storage/img_static/profile.jpg" /></figure>
                                    <div class="testimonial_text">
                                        <p><span style="color:#fe4f51; font-weight:bold;">WamsCo</span> à vraiment transformé notre perception, La simplicité d'utilisation et l'intégration  de la solution ont été un élément déterminant du choix de la solution.</p>
                                        <p class="testimonial_signature">Louise<em>, Marketing</em>
                                        </p>
                                    </div>
                                </li>
                                <li class="testimonial testimonial_2">
                                    <figure class="gh_icon icon-live"><img alt="just1swap" data-entity-type="" data-entity-uuid="" style="width: 100px; border-radius: 50%;" src="storage/img_static/femme-africaine.jpg" /></figure>
                                    <div class="testimonial_text">
                                        <p>le système <span style="color:#fe4f51; font-weight:bold;">WamsCo</span> est facile à programmer et à utiliser, et il est également très facile d'ajouter des images pour le produits ou l'articles. J'aime beaucoup le dynamisme de l'entreprise, je l'ai particulierement ressenti au niveau du travail;</p>
                                        <p class="testimonial_signature">Normande<em>, Financiere</em></p>
                                    </div>
                                </li>
                                <li class="testimonial testimonial_3">
                                    <figure class="gh_icon icon-live"><img alt="perfumeria"
                                            data-entity-type="" data-entity-uuid="" style="width: 100px; border-radius: 50%;" src="storage/img_static/femme-noire.jpg" />
                                    </figure>
                                    <div class="testimonial_text">
                                        <p><span style="color:#fe4f51; font-weight:bold;">WamsCo</span> nous a permis de gérer facilement notre entreprise, quelle que soit la distance et avec tout appareil qu'on a sous la main.</p>
                                        <p class="testimonial_signature">Leticia H.<em>, Cadre commercial</em></p>
                                    </div>
                                </li>                                
                            </ul>                            
                        </section>
                        {{-- <section class="hp_products gray" id="nos_services">                            
                            <h3 class="hp_products_title">Autres services</h3>
                            <ul class="hp_products_wrapper">
                                <li class="product our-prod1" data-href="#"> 
                                    <span class="icon_prod"><i class="fa fa-puzzle-piece"></i></span>  
                                    <div class="product_cont">
                                        <p class="product_title"><a href="#">Création site web</a></p>
                                        <p class="product_text"><strong style="color:#fe4f51;">WamsCo</strong> crée des sites web professionel sur mesure, dynamique et optimisé pour le référencement naturel. Ces sites sont destinés aux particuliers et entreprises avec un accent sur l'expérience utilisateur et les performances.</p>                                    
                                    </div>
                                </li>
                                <li class="product our-prod2" data-href="#">
                                <span class="icon_prod"><i class="fa fa-code"></i></span> 
                                    <div class="product_cont">
                                        <p class="product_title"><a href="#">Création application web</a></p>
                                        <p class="product_text">Spécialiste du développement des applications web sur mesure. <strong style="color:#fe4f51;">WamsCo</strong> développe des logiciels en ligne qui peuvent être utilisé en ligne via votre navigateur internet avec un accent important sur l'expérience utilisateur et les performances. </p>
                                    </div>
                                </li>
                                <li class="product our-prod3" data-href="#">
                                    <span class="icon_prod"><i class="fa fa-sitemap"></i></span> 
                                    <div class="product_cont">
                                        <p class="product_title"><a href="#">Développement  logiciel</a></p>
                                        <p class="product_text"><strong style="color:#fe4f51;">WamsCo</strong> accompagne les entreprises dans la numérisation et la digitalisation en apportant la technologie et l'innovation dans leur processus metier.</p>
                                    </div>
                                </li>
                                <li class="product our-prod4" data-href="#">
                                    <span class="icon_prod"><i class="fa fa-cogs"></i></span> 
                                    <div class="product_cont">
                                        <p class="product_title"><a href="#">Edition & intégration ERP</a></p>
                                        <p class="product_text"><strong style="color:#fe4f51;">WamsCo</strong> intègre des solutions ERP open source pour les sociétes de grandes et petites tailles pour leur rendre plus productives et performantes dans leur activité.</p>
                                    </div>
                                </li>
                                <li class="product our-prod5" data-href="#">
                                    <span class="icon_prod"><i class="fa fa-cog fa-spin"></i></span> 
                                    <div class="product_cont">
                                        <p class="product_title"><a href="#">Consultation & Expertise</a></p>
                                        <p class="product_text"> Profitez de l'expertise de nos spécialistes informatiques pour vos besoins logiciels ou site web en entreprise ou autre, ce qui vous aidera à réussir dans le monde numérique.</p>
                                    </div>
                                </li>
                                <li class="product our-prod5" data-href="#">
                                    <span class="icon_prod"><i class="fa fa-recycle fa-spin"></i></span> 
                                    <div class="product_cont">
                                        <p class="product_title"><a href="#">Testeur logiciel</a></p>
                                        <p class="product_text"><strong style="color:#fe4f51;">WamsCo</strong> est votre partenaire idéal pour tester vos logiciels de tout type, qu'ils soient web, mobile ou desktop. Expertise de nos spécialistes informatiques permet de mener à bien cette tâche et de vous donner un avis neutre.</p>
                                    </div>
                                </li>
                            </ul>
                        </section>   --}}
                        <section class="hp_get_help gray" style="background-color: #f6f9fb;">
                            <h3 class="hp_get_help_title">Obtenez l'aide dont vous avez besoin</h3>
                            <ul class="hp_get_help_wrapper">
                                <li class="how_use">
                                    <p class="title"><i class="fa fa-headset"></i> Chat en direct</p>
                                    <p>Obtenez une assistance rapide 24/24 et 7/7 de notre équipe d'assistance clientèle.</p>
                                </li>
                                <li class="how_use">
                                    <p class="title"><i class="fa fa-circle-question"></i> Centre d'aide</p>
                                    <p>Un ensemble complet de tutoriels vidéo qui vous permet une prise en main facile de l'application.</p>
                                </li>
                                <li class="how_use">
                                    <p class="title"><i class="fa fa-comments"></i> Communauté</p>
                                    <p>Apprenez des autres vendeurs comment utiliser <span style="color:#fe4f51; font-weight:bold;">WamsCo</span> et sur des sujets liés au développement des entreprises.</p>
                                </li>
                            </ul>
                            <div style="display:flex; justify-content:center; gap:15px; padding-bottom: 25px;">
                                <div>                         
                                    <a class="dash-button button_dev_clt" style="background: #0c1031; font-weight:700" href="prix"><i class="fa fa-users"></i> @lang('site.devenir_client') <span class="hidden-word"> </span></a>
                                </div>
                                <div>
                                    <a class="dash-button" style="font-weight: 700" href="connexion?demo=essai"><i class="fa fa-cog fa-spin"></i> @lang('site.essai_free') <span class="hidden-word"> </span></a>
                                </div>
                            </div>  
                        </section>
                        <section class="hp_lpartner">
                            <figure class=""><i class="fa fa-handshake" style="font-size: 45px;"></i> </figure>
                            <h3 class="hp_lpartner_title">Devenir un partenaire de <span style="color:#fe4f51; font-weight:bold;">WamsCo</span></h3>
                            <span class="hp_lpartner_slogan">Avançons ensemble et aidons d'autres entreprises à se développer.</span>
                            <span class="hp_lpartner_text"> Si vous souhaitez servir les entreprises locales et les aider à mettre en place des solutions de vente au détail via un logiciel tout en un moderne et optimisé,
                                <a href="https://api.whatsapp.com/send?phone=+237654258009&text=Bonjour WamsCo, nous souhaiterons entrer en partenariat avec vous, sur votre application WamsCo-cloud. Merci." rel="noopener nofollow" target="_blank">cliquez ici partenariat.</a>                                
                            </span>
                        </section>
                        <section class="hp_products gray" style="background-color: #ffffff;">
                            <h3 class="hp_products_title">Quelques clients</h3>
                            <div class="section pt-4 pb-5" style="background-color: #ffffff;">
                                <marquee behavior="alternate" scrollamount="8" >
                                <div class="block_logo_client">
                                    <div class="imge-carousel">
                                        <div class="imge_card" style="">
                                            <img src="storage/img_static/runway.png" class="image_client" alt="">
                                        </div>
                                        <div class="imge_card">
                                            <img src="storage/img_static/rk.png" class="image_client" alt="">
                                        </div>
                                        <div class="imge_card">
                                            <img src="storage/img_static/em.png" class="image_client" alt="">
                                        </div>
                                        <div class="imge_card">
                                            <img src="storage/img_static/lawnco.png" class="image_client" alt="">
                                        </div>
                                        <div class="imge_card">
                                            <img src="storage/img_static/ecos.png" class="image_client" alt="">
                                        </div>
                                        <div class="imge_card">
                                            <img src="storage/img_static/nora.png" class="image_client" alt="">
                                        </div>
                                        <div class="imge_card">
                                            <img src="storage/img_static/bugatti.png" class="image_client" alt="">
                                        </div>
                                        <div class="imge_card">
                                            <img src="storage/img_static/vemaspro.png" class="image_client" alt="">
                                        </div>
                                    </div>
                                </div>
                                </marquee>
                            </div>                              
                        </section>
                        <section class="hp_questions">
                            <div class="hp_questions_wrapper">
                                <h3 class="hp_questions_title">Foire aux Questions</h3>
                                <div itemscope="" itemtype="http://schema.org/FAQPage">
                                    <nav class="accordion-homepage frequently" data-stats-ve="1">
                                        <section class="parent" itemprop="mainEntity" itemscope=""
                                            itemtype="http://schema.org/Question">
                                            <h3 class="clickquestion" itemprop="name"><span style="color:#343232; font-weight:600;">WamsCo-cloud</span> est-il gratuit?<svg class="up" viewbox="0 0 24 24">
                                                <path d="M7.41 15.41L12 10.83l4.59 4.58L18 14l-6-6-6 6z"></path>
                                                </svg><svg class="down" viewbox="0 0 24 24">
                                                    <path d="M7.41 7.84L12 12.42l4.59-4.58L18 9.25l-6 6-6-6z"></path>
                                                </svg></h3>
                                            <div class="overflow" itemprop="acceptedAnswer" itemscope=""
                                                itemtype="http://schema.org/Answer">
                                                <div class="children">
                                                    <div class="child" itemprop="text"><b>Vous pouvez utiliser l'application <span style="color:#fe4f51; font-weight:bold;">WamsCo-cloud</span> gratuitement.</b> Vous pouvez ajouter des articles ou produits ou services,
                                                       effectuer des commandes, établier des factures, analyses vos ventes, visualiser vos paiements ou règlements et bien d'autre fonctionnalité sans aucun paiement pendant 14 jours.<br />
                                                       Veuillez consulter notre <a href="/prix" target="_blank">page des tarifs</a> pour plus d'informations.</div>
                                                </div>
                                            </div>
                                        </section><!-- section -->
                                        <section class="parent" itemprop="mainEntity" itemscope=""
                                            itemtype="http://schema.org/Question">
                                            <h3 class="clickquestion" itemprop="name">Comment utiliser <span style="color:#343232; font-weight:600;">WamsCo-cloud</span>? <svg class="up" viewbox="0 0 24 24">
                                                    <path d="M7.41 15.41L12 10.83l4.59 4.58L18 14l-6-6-6 6z">
                                                    </path>
                                                </svg><svg class="down" viewbox="0 0 24 24">
                                                    <path d="M7.41 7.84L12 12.42l4.59-4.58L18 9.25l-6 6-6-6z">
                                                    </path>
                                                </svg></h3>

                                            <div class="overflow" itemprop="acceptedAnswer" itemscope=""
                                                itemtype="http://schema.org/Answer">
                                                <div class="children">
                                                    <div class="child" itemprop="text">
                                                        Créer votre compte en remplissent le formulaire
                                                        d'<a href="/prix" target="_blank">inscription</a>.<br />
                                                        Effectuez les réglages nécessaires dans la Gestion de stock, configuration et vous pouvez commencer à effectuer vos premières ventes !
                                                    </div>
                                                </div>
                                            </div>
                                        </section><!-- section -->
                                        <section class="parent" itemprop="mainEntity" itemscope=""
                                            itemtype="http://schema.org/Question">
                                            <h3 class="clickquestion" itemprop="name"><span style="color:#343232; font-weight:600;">WamsCo-cloud</span> fonctionne-t-il hors ligne ? <svg class="up" viewbox="0 0 24 24">
                                                    <path d="M7.41 15.41L12 10.83l4.59 4.58L18 14l-6-6-6 6z">
                                                    </path>
                                                </svg><svg class="down" viewbox="0 0 24 24">
                                                    <path d="M7.41 7.84L12 12.42l4.59-4.58L18 9.25l-6 6-6-6z">
                                                    </path>
                                                </svg></h3>

                                            <div class="overflow" itemprop="acceptedAnswer" itemscope=""
                                                itemtype="http://schema.org/Answer">
                                                <div class="children">
                                                    <div class="child" itemprop="text"><b>Logiciel <span style="color:#343232; font-weight:600;">WamsCo-cloud</span> fonctionne hors ligne et en ligne.</b><br /> Votre appareil
                                                        peut faire des ventes avec la version locale sans internet.<br />
                                                        Cependant, toutes les fonctionnalités ne sont pas prises en
                                                        charge hors ligne, telles que Multi-société, informations en temps réels, plusieurs utilisateurs à la fois, sur téléphone ou tablette. Seul un ordinateur pourra faire des opérations à la fois, ou la mise en reseau 
                                                        et quelques autres restrictions.</div>
                                                </div>
                                            </div>
                                        </section><!-- section -->
                                        <section class="parent" itemprop="mainEntity" itemscope=""
                                            itemtype="http://schema.org/Question">
                                            <h3 class="clickquestion" itemprop="name">Quel type de matériel peut fonctionner avec <span style="color:#343232; font-weight:600;">WamsCo-cloud</span> ?<svg class="up"
                                                    viewbox="0 0 24 24">
                                                    <path d="M7.41 15.41L12 10.83l4.59 4.58L18 14l-6-6-6 6z">
                                                    </path>
                                                </svg><svg class="down" viewbox="0 0 24 24">
                                                    <path d="M7.41 7.84L12 12.42l4.59-4.58L18 9.25l-6 6-6-6z">
                                                    </path>
                                                </svg></h3>

                                            <div class="overflow" itemprop="acceptedAnswer" itemscope=""
                                                itemtype="http://schema.org/Answer">
                                                <div class="children">
                                                    <div class="child" itemprop="text">Avec l'application <span style="color:#fe4f51; font-weight:bold;">WamsCo-cloud</span>, vous pouvez vous connecté et utiliser n'importe quel terminal (téléphone, tablette ou ordinateur) que vous souhaitez
                                                        des imprimantes de reçus, tiroirs-caisses, et d'autres matériels.</div>
                                                </div>
                                            </div>
                                        </section><!-- section -->
                                        <!--question5-->
                                        <section class="parent" itemprop="mainEntity" itemscope=""
                                            itemtype="http://schema.org/Question">
                                            <h3 class="clickquestion"><span itemprop="name"><span style="color:#343232; font-weight:600;">WamsCo-cloud</span> a-t-il la fonctionnalité des cartes de fidélité?</span><svg
                                                    class="up" viewbox="0 0 24 24">
                                                    <path d="M7.41 15.41L12 10.83l4.59 4.58L18 14l-6-6-6 6z">
                                                    </path>
                                                </svg><svg class="down" viewbox="0 0 24 24">
                                                    <path d="M7.41 7.84L12 12.42l4.59-4.58L18 9.25l-6 6-6-6z">
                                                    </path>
                                                </svg></h3>

                                            <div class="overflow" itemprop="acceptedAnswer" itemscope=""
                                                itemtype="http://schema.org/Answer">
                                                <div class="children">
                                                    <div class="child" itemprop="text"><span style="color:#fe4f51; font-weight:bold;">WamsCo-cloud</span> à un programme de fidélité intégré qui permet de récompenser vos
                                                        clients fidèles pour leur visite de votre magasin, restaurant ou autres. Lorsque le client visite votre
                                                        magasin, restaurant ou autres, le caissier peut l'identifier rapidement le client avec son numéro de téléphone</a>.</div>
                                                </div>
                                            </div>
                                        </section><!-- section -->
                                    </nav>
                                </div>
                            </div>
                            <div style="display:flex; justify-content:center; gap:15px; padding-top: 42px;">
                                <div>                         
                                    <a class="dash-button button_dev_clt" style="background: #0c1031; font-weight:700" href="prix"><i class="fa fa-users"></i> @lang('site.devenir_client') <span class="hidden-word"> </span></a>
                                </div>
                                <div>
                                    <a class="dash-button" style="font-weight: 700" href="connexion?demo=essai"><i class="fa fa-cog fa-spin"></i> @lang('site.essai_free') <span class="hidden-word"> </span></a>
                                </div>
                            </div>  
                        </section>
                    </div>
                </div>
                <div class="views-element-container">
                    <div
                        class="js-view-dom-id-0b1b38d79914eff4e3b5b19a461be8ece7b1ac8775ce9dcd6dc7a27297203687">
                    </div>
                </div>
            </div>
        </section>
    </section>
</div>
