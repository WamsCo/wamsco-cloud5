
<div id="content" class="app-content p-0">    
    <div class="profile">
        <div class="profile-header rounded-0">        
            <div class="profile-header-cover"></div>      
            <div class="profile-header-content">
                <div class="profile-header-info">
                    <h4 class="mt-0 mb-1"><i class="fa fa-home" aria-hidden="true"></i> Bienvenue</h4>
                    <p class="mb-2"></p>
                    {{-- <a href="#" class="btn btn-sm btn-default" title="Cliquez pour enregistrer" data-toggle="tooltip" wire:click.prevent="store()"><i class="fa fa-check"></i> Valider</a>
                    <a href="{{asset('entrepot?active=4&champ=5-1&choix=2')}}" class="btn btn-sm btn-red" title="Cliquez pour annuler"><i class="fa fa-close"></i></a> --}}
                </div>        
            </div> 
        </div>
    </div>
    <div class="profile-content container-fluid px-4">
        @include('flash::message')     
        <div class="tab-content p-0">    
            @foreach($entite_mod as $entite_mods)
                <div class="row">
                    <div class="col-sm-4 col-lg-3 mb-4">
                        <div class="cardor border-0 bg-white-500 text-white">
                            @if($entite_mods->mod_gestion_tier == 1 && $dateJour <= $entite_mods->validite_mod)
                                <a href="{{asset('listing-tiers?active=3&champ=3-2')}}" wire:navigate class="bloc_mod" title="Acteurs clés du système: Clients, Fournisseurs, Prospects etc...">
                                    <div class="bloc_img_text">
                                        <div class="part_img">
                                            {{-- <i class="fa fa-users" class=""></i> --}}
                                            <img src="storage/img_module/8.png" alt="" class="img_mod">
                                        </div>
                                        <div class="part_text">
                                            <h5 class="card-title"><i class="fa fa-users"></i> Tiers</h5>
                                            <p class="truncate_wamsco mb-1">Acteurs clés du système: Clients, Fournisseurs, Prospects etc...</p>
                                            <p class="card-text fw-bold badge bg-green mt-2">Activer</p>
                                        </div>
                                    </div>
                                </a>
                            @else
                                <a href="{{asset('choix_plan')}}" wire:navigate class="bloc_mod">
                                    <div class="bloc_img_text">
                                        <div class="part_img">
                                            <img src="storage/img_module/8.png" alt="" class="img_mod">
                                        </div>
                                        <div class="part_text">
                                            <h5 class="card-title"><i class="fa fa-users"></i> Tiers</h5>
                                            <p class="truncate_wamsco mb-1">Acteurs clés du système: Clients, Fournisseurs, Prospects etc...</p>
                                            <p class="card-text fw-bold badge bg-danger mt-2">Débloquer le module</p>
                                        </div>
                                    </div>
                                </a>
                            @endif
                        </div>                    
                    </div>
                    <div class="col-sm-4 col-lg-3 mb-4">
                        <div class="cardor border-0 bg-white-500 text-white">
                            @if($entite_mods->mod_crm == 1 && $entite_mods->mod_gestion_tier == 1 && $dateJour <= $entite_mods->validite_mod)
                                <a href="{{asset('pipeline_tiers?active=3&champ=3-3&choix=1')}}" class="bloc_mod" title="Le CRM est à la fois une stratégie d'entreprise et un ensemble d'outils logiciels qui permettent de gérer et d'analyser les interactions d'une entreprise avec ses clients et prospects">
                                    <div class="bloc_img_text">
                                        <div class="part_img">
                                            {{-- <i class="fa fa-users" class=""></i> --}}
                                            <img src="storage/img_module/9.png" alt="" class="img_mod">
                                        </div>
                                        <div class="part_text">
                                            <h5 class="card-title"><i class="fa fa-users"></i> CRM</h5>
                                            <p class="truncate_wamsco mb-1">Le CRM est à la fois une stratégie d'entreprise et un ensemble d'outils logiciels qui permettent de gérer et d'analyser les interactions d'une entreprise avec ses clients et prospects</p>
                                            <p class="card-text fw-bold badge bg-green mt-2">Activer</p>
                                            {{-- <p class="card-text fw-bold badge bg-danger mt-2 blink">New</p> --}}
                                        </div>
                                    </div>
                                </a>
                            @else
                                <a href="{{asset('choix_plan')}}" wire:navigate class="bloc_mod">
                                    <div class="bloc_img_text">
                                        <div class="part_img">
                                            <img src="storage/img_module/8.png" alt="" class="img_mod">
                                        </div>
                                        <div class="part_text">
                                            <h5 class="card-title"><i class="fa fa-users"></i> CRM</h5>
                                            <p class="truncate_wamsco mb-1">Le CRM est à la fois une stratégie d'entreprise et un ensemble d'outils logiciels qui permettent de gérer et d'analyser les interactions d'une entreprise avec ses clients et prospects</p>
                                            <p class="card-text fw-bold badge bg-danger mt-2">Débloquer le module</p>
                                        </div>
                                    </div>
                                </a>
                            @endif
                        </div>                    
                    </div>
                    <div class="col-sm-4 col-lg-3 mb-4">
                        <div class="cardor border-0 bg-white-500 text-white">
                            @if($entite_mods->mod_gestion_stock == 1 && $dateJour <= $entite_mods->validite_mod)
                                <a href="{{asset('produit?active=4&champ=1-1&choix=2')}}" wire:navigate class="bloc_mod" title="Ensemble des marchandises, matières premières, composants ou produits finis d'une entreprise (Produit, Catégorie, Entrepôt, Mouvements)">
                                    <div class="bloc_img_text">
                                        <div class="part_img">
                                            {{-- <i class="fa fa-users" class=""></i> --}}
                                            <img src="storage/img_module/6.png" alt="" class="img_mod">
                                        </div>
                                        <div class="part_text">
                                            <h5 class="card-title"><i class="fa fa-box-open"></i> Stock</h5>
                                            <p class="truncate_wamsco mb-1">Ensemble des marchandises, matières premières, composants ou produits finis d'une entreprise (Produit, Catégorie, Entrepôt, Mouvements)</p>
                                            <p class="card-text fw-bold badge bg-green mt-2">Activer</p>
                                        </div>
                                    </div>
                                </a>
                            @else
                                <a href="{{asset('choix_plan')}}" wire:navigate class="bloc_mod">
                                    <div class="bloc_img_text">
                                        <div class="part_img">
                                            <img src="storage/img_module/6.png" alt="" class="img_mod">
                                        </div>
                                        <div class="part_text">
                                            <h5 class="card-title"><i class="fa fa-box-open"></i> Stock</h5>
                                            <p class="truncate_wamsco mb-1">Ensemble des marchandises, matières premières, composants ou produits finis d'une entreprise (Produit, Catégorie, Entrepôt, Mouvements)</p>
                                            <p class="card-text fw-bold badge bg-danger mt-2">Débloquer le module</p>
                                        </div>
                                    </div>
                                </a>
                            @endif
                        </div>                    
                    </div>
                    <div class="col-sm-4 col-lg-3 mb-4">
                        <div class="cardor border-0 bg-white-500 text-white">
                            @if($entite_mods->mod_pointe_vente == 1 && $dateJour <= $entite_mods->validite_mod)
                                <a href="{{asset('pos_sessions?active=5&champ=1-1')}}" wire:navigate class="bloc_mod" title="Système de vente conviviale, performant et intuitif pour tous type de commerce.">
                                    <div class="bloc_img_text">
                                        <div class="part_img">
                                            <img src="storage/img_module/1.png" alt="" class="img_mod">
                                        </div>
                                        <div class="part_text">
                                            <h5 class="card-title"><i class="fa fa-cash-register"></i> Point Vente</h5>
                                            <p class="truncate_wamsco mb-1">Système de vente conviviale, performant et intuitif pour tous type de commerce.</p>
                                            <p class="card-text fw-bold badge bg-green mt-2">Activer</p>
                                        </div>
                                    </div>
                                </a>
                            @else
                                <a href="{{asset('choix_plan')}}" wire:navigate class="bloc_mod">
                                    <div class="bloc_img_text">
                                        <div class="part_img">
                                            <img src="storage/img_module/1.png" alt="" class="img_mod">
                                        </div>
                                        <div class="part_text">
                                            <h5 class="card-title"><i class="fa fa-cash-register"></i> Point Vente</h5>
                                            <p class="truncate_wamsco mb-1">Système de vente conviviale, performant et intuitif pour tous type de commerce.</p>
                                            <p class="card-text fw-bold badge bg-danger mt-2">Débloquer le module</p>
                                        </div>
                                    </div>
                                </a>
                            @endif
                        </div>                    
                    </div>
                    <div class="col-sm-4 col-lg-3 mb-4">
                        <div class="cardor border-0 bg-white-500 text-white">
                            @if($entite_mods->mod_restaurant == 1 && $dateJour <= $entite_mods->validite_mod)
                                <a href="{{asset('restau_sessions?active=5&champ=2-1&choix=1')}}" wire:navigate class="bloc_mod" title="Module permettant la gestion complète des commandes, du suivi de la préparation des repas ainsi que de l’organisation et de l’occupation des tables au sein d’un restaurant.">
                                    <div class="bloc_img_text">
                                        <div class="part_img">
                                            <img src="storage/img_module/restos.jpg" alt="" class="img_mod">
                                        </div>
                                        <div class="part_text">
                                            <h5 class="card-title"><i class="fa fa-cash-register"></i> Restaurant</h5>
                                            <p class="truncate_wamsco mb-1">Module permettant la gestion complète des commandes, du suivi de la préparation des repas ainsi que de l’organisation et de l’occupation des tables au sein d’un restaurant.</p>
                                            <p class="card-text fw-bold badge bg-green mt-2">Activer</p>
                                        </div>
                                    </div>
                                </a>
                            @else
                                <a href="{{asset('choix_plan')}}" wire:navigate class="bloc_mod">
                                    <div class="bloc_img_text">
                                        <div class="part_img">
                                            <img src="storage/img_module/restos.jpg" alt="" class="img_mod">
                                        </div>
                                        <div class="part_text">
                                            <h5 class="card-title"><i class="fa fa-cash-register"></i> Restaurant</h5>
                                            <p class="truncate_wamsco mb-1">Module permettant la gestion complète des commandes, du suivi de la préparation des repas ainsi que de l’organisation et de l’occupation des tables au sein d’un restaurant.</p>
                                            <p class="card-text fw-bold badge bg-danger mt-2">Débloquer le module</p>
                                        </div>
                                    </div>
                                </a>
                            @endif
                        </div>                    
                    </div> 
                    <div class="col-sm-4 col-lg-3 mb-4">
                        <div class="cardor border-0 bg-white-500 text-white">
                            @if($entite_mods->mod_cuisine == 1 && $entite_mods->mod_pointe_vente == 1 && $dateJour <= $entite_mods->validite_mod)
                                <a href="{{asset('cuisine?active=5&champ=1-3')}}" wire:navigate class="bloc_mod" title="Système d'affichage des commandes en cuisine, leur statut et en temps réel.">
                                    <div class="bloc_img_text">
                                        <div class="part_img">
                                            <img src="storage/img_module/cuisine.jpg" alt="" class="img_mod">
                                        </div>
                                        <div class="part_text">
                                            <h5 class="card-title" style="font-size: 14px;"><i class="fa fa-tv"></i> Ecran cuisine</h5>
                                            <p class="truncate_wamsco mb-1">Système d'affichage des commandes en cuisine, leur statut et en temps réel.</p>
                                            <p class="card-text fw-bold badge bg-green mt-2">Activer</p>
                                        </div>
                                    </div>
                                </a>
                            @else
                                <a href="{{asset('choix_plan')}}" wire:navigate class="bloc_mod">
                                    <div class="bloc_img_text">
                                        <div class="part_img">
                                            <img src="storage/img_module/cuisine.jpg" alt="" class="img_mod">
                                        </div>
                                        <div class="part_text">
                                            <h5 class="card-title" style="font-size: 14px;"><i class="fa fa-tv"></i> Ecran cuisine</h5>
                                            <p class="truncate_wamsco mb-1">Système d'affichage des commandes en cuisine, leur statut et en temps réel.</p>
                                            <p class="card-text fw-bold badge bg-danger mt-2">Débloquer le module</p>
                                        </div>
                                    </div>
                                </a>
                            @endif
                        </div>                    
                    </div>
                    <div class="col-sm-4 col-lg-3 mb-4">
                        <div class="cardor border-0 bg-white-500 text-white">
                            @if($entite_mods->mod_cmd == 1 && $dateJour <= $entite_mods->validite_mod)
                                <a href="{{asset('listing_cmd_clt?active=6&champ=1-1&choix=2')}}" wire:navigate class="bloc_mod" title="Acte par lequel un acheteur s'engage à acquérir des produits ou des services auprès d'un vendeur (Proforma, Commande, Expédition, Réception etc.).">
                                    <div class="bloc_img_text">
                                        <div class="part_img">
                                            <img src="storage/img_module/person.jpg" alt="" class="img_mod">
                                        </div>
                                        <div class="part_text">
                                            <h5 class="card-title"><i class="fas fa-file-invoice"></i> Commande</h5>
                                            <p class="truncate_wamsco mb-1">Acte par lequel un acheteur s'engage à acquérir des produits ou des services auprès d'un vendeur (Proforma, Commande, Expédition, Réception etc.).</p>
                                            <p class="card-text fw-bold badge bg-green mt-2">Activer</p>
                                        </div>
                                    </div>
                                </a>
                            @else
                                <a href="{{asset('choix_plan')}}" wire:navigate class="bloc_mod">
                                    <div class="bloc_img_text">
                                        <div class="part_img">
                                            <img src="storage/img_module/person.jpg" alt="" class="img_mod">
                                        </div>
                                        <div class="part_text">
                                            <h5 class="card-title"><i class="fas fa-file-invoice"></i> Commande</h5>
                                            <p class="truncate_wamsco mb-1">Acte par lequel un acheteur s'engage à acquérir des produits ou des services auprès d'un vendeur (Proforma, Commande, Expédition, Réception etc.).</p>
                                            <p class="card-text fw-bold badge bg-danger mt-2">Débloquer le module</p>
                                        </div>
                                    </div>
                                </a>
                            @endif
                        </div>                    
                    </div>
                    <div class="col-sm-4 col-lg-3 mb-4">
                        <div class="cardor border-0 bg-white-500 text-white">
                            @if($entite_mods->mod_facturation == 1 && $dateJour <= $entite_mods->validite_mod)
                                <a href="{{asset('listing_fact_clt?active=7&champ=1-1&choix=1')}}" wire:navigate class="bloc_mod" title="Une facture est un document légal, commercial et comptable qui constate officiellement une transaction de vente de biens ou de prestation de services.">
                                    <div class="bloc_img_text">
                                        <div class="part_img">
                                            <img src="storage/img_module/5.png" alt="" class="img_mod">
                                        </div>
                                        <div class="part_text">
                                            <h5 class="card-title"><i class="fa fa-money-bill"></i> Facturation</h5>
                                            <p class="truncate_wamsco mb-1">Une facture est un document légal, commercial et comptable qui constate officiellement une transaction de vente de biens ou de prestation de services.</p>
                                            <p class="card-text fw-bold badge bg-green mt-2">Activer</p>
                                        </div>
                                    </div>
                                </a>
                            @else
                                <a href="{{asset('choix_plan')}}" wire:navigate class="bloc_mod">
                                    <div class="bloc_img_text">
                                        <div class="part_img">
                                            <img src="storage/img_module/5.png" alt="" class="img_mod">
                                        </div>
                                        <div class="part_text">
                                            <h5 class="card-title"><i class="fa fa-money-bill"></i> Facturation</h5>
                                            <p class="truncate_wamsco mb-1">Une facture est un document légal, commercial et comptable qui constate officiellement une transaction de vente de biens ou de prestation de services.</p>
                                            <p class="card-text fw-bold badge bg-danger mt-2">Débloquer le module</p>
                                        </div>
                                    </div>
                                </a>
                            @endif
                        </div>                    
                    </div>                    
                    <div class="col-sm-4 col-lg-3 mb-4">
                        <div class="cardor border-0 bg-white-500 text-white">
                            @if($entite_mods->mod_banque_caisse == 1 && $dateJour <= $entite_mods->validite_mod)
                                <a href="{{asset('banque_caisse?active=8&champ=1-1')}}" wire:navigate class="bloc_mod" title="Ensemble des opérations de trésorerie courante gérées directement par l'entreprise (Finances, comptes, écritures, virement)">
                                    <div class="bloc_img_text">
                                        <div class="part_img">
                                            <img src="storage/img_module/7.png" alt="" class="img_mod">
                                        </div>
                                        <div class="part_text">
                                            <h5 class="card-title" style="font-size: 13px;"><i class="fa fa-credit-card"></i> Banque/Caisse</h5>
                                            <p class="truncate_wamsco mb-1">Ensemble des opérations de trésorerie courante gérées directement par l'entreprise (Finances, comptes, écritures, virement)</p>
                                            <p class="card-text fw-bold badge bg-green mt-2">Activer</p>
                                        </div>
                                    </div>
                                </a>
                            @else
                                <a href="{{asset('choix_plan')}}" wire:navigate class="bloc_mod">
                                    <div class="bloc_img_text">
                                        <div class="part_img">
                                            <img src="storage/img_module/7.png" alt="" class="img_mod">
                                        </div>
                                        <div class="part_text">
                                            <h5 class="card-title" style="font-size: 13px;"><i class="fa fa-credit-card"></i> Banque/Caisse</h5>
                                            <p class="truncate_wamsco mb-1">Ensemble des opérations de trésorerie courante gérées directement par l'entreprise (Finances, comptes, écritures, virement)</p>
                                            <p class="card-text fw-bold badge bg-danger mt-2">Débloquer le module</p>
                                        </div>
                                    </div>
                                </a>
                            @endif
                        </div>                    
                    </div>
                    <div class="col-sm-4 col-lg-3 mb-4">
                        <div class="cardor border-0 bg-white-500 text-white">
                            @if($entite_mods->mod_fabrication == 1 && $dateJour <= $entite_mods->validite_mod)
                                <a href="{{asset('listing_ordre?active=9&champ=2-1&choix=2')}}" class="bloc_mod" title="La Fabrication à partir d'une nomenclature est le processus clé qui transforme une liste structurée de composants en un produit fini vendable.">
                                    <div class="bloc_img_text">
                                        <div class="part_img">
                                            <img src="storage/img_module/10.png" alt="" class="img_mod">
                                        </div>
                                        <div class="part_text">
                                            <h5 class="card-title" style="font-size: 14px;"><i class="fa fa-shapes"></i> Fabrication</h5>
                                            <p class="truncate_wamsco mb-1">La Fabrication à partir d'une nomenclature est le processus clé qui transforme une liste structurée de composants en un produit fini vendable.</p>
                                            <p class="card-text fw-bold badge bg-green mt-2">Activer</p>
                                            {{-- <p class="card-text fw-bold badge bg-danger mt-2 blink">New</p> --}}
                                        </div>
                                    </div>
                                </a>
                            @else
                                <a href="{{asset('choix_plan')}}" wire:navigate class="bloc_mod">
                                    <div class="bloc_img_text">
                                        <div class="part_img">
                                            <img src="storage/img_module/10.png" alt="" class="img_mod">
                                        </div>
                                        <div class="part_text">
                                            <h5 class="card-title"><i class="fa fa-shapes"></i> Fabrication</h5>
                                            <p class="truncate_wamsco mb-1">La Fabrication à partir d'une nomenclature est le processus clé qui transforme une liste structurée de composants en un produit fini vendable.</p>
                                            <p class="card-text fw-bold badge bg-danger mt-2">Débloquer le module</p>
                                        </div>
                                    </div>
                                </a>
                            @endif
                        </div>                    
                    </div>
                    <div class="col-sm-4 col-lg-3 mb-4">
                        <div class="cardor border-0 bg-white-500 text-white">
                            @if($entite_mods->mod_ticket == 1 && $dateJour <= $entite_mods->validite_mod)
                                <a href="{{asset('liste_ticket?active=13&champ=1-2')}}" class="bloc_mod" title="Le ticket est un enregistrement formel et numéroté d'une demande ou d'un problème qu'un client ou un employé soumet à une équipe de support (service après-vente, assistance technique, etc.).">
                                    <div class="bloc_img_text">
                                        <div class="part_img">
                                            <img src="storage/img_module/ticket.jpg" alt="" class="img_mod">
                                        </div>
                                        <div class="part_text">
                                            <h5 class="card-title" style="font-size: 14px;"><i class="fa fa-ticket"></i> Ticket</h5>
                                            <p class="truncate_wamsco mb-1">Le ticket est un enregistrement formel et numéroté d'une demande ou d'un problème qu'un client ou un employé soumet à une équipe de support (service après-vente, assistance technique, etc.).</p>
                                            <p class="card-text fw-bold badge bg-green mt-2">Activer</p>
                                            {{-- <p class="card-text fw-bold badge bg-danger mt-2 blink">New</p> --}}
                                        </div>
                                    </div>
                                </a>
                            @else
                                <a href="{{asset('choix_plan')}}" wire:navigate class="bloc_mod">
                                    <div class="bloc_img_text">
                                        <div class="part_img">
                                            <img src="storage/img_module/ticket.jpg" alt="" class="img_mod">
                                        </div>
                                        <div class="part_text">
                                            <h5 class="card-title"><i class="fa fa-ticket"></i> Ticket</h5>
                                            <p class="truncate_wamsco mb-1">Le ticket est un enregistrement formel et numéroté d'une demande ou d'un problème qu'un client ou un employé soumet à une équipe de support (service après-vente, assistance technique, etc.).</p>
                                            <p class="card-text fw-bold badge bg-danger mt-2">Débloquer le module</p>
                                        </div>
                                    </div>
                                </a>
                            @endif
                        </div>                    
                    </div>
                    <div class="col-sm-4 col-lg-3 mb-4">
                        <div class="cardor border-0 bg-white-500 text-white">
                            @if($entite_mods->mod_tache == 1 && $dateJour <= $entite_mods->validite_mod)
                                <a href="{{asset('taches?active=14&champ=1-1')}}" class="bloc_mod" title="La gestion des tâches est le processus qui consiste à identifier, suivre et exécuter le travail nécessaire pour atteindre un objectif.">
                                    <div class="bloc_img_text">
                                        <div class="part_img">
                                            <img src="storage/img_module/taches.png" alt="" class="img_mod">
                                        </div>
                                        <div class="part_text">
                                            <h5 class="card-title" style="font-size: 14px;"><i class="fa fa-tasks"></i> Gestion Tâches</h5>
                                            <p class="truncate_wamsco mb-1">La gestion des tâches est le processus qui consiste à identifier, suivre et exécuter le travail nécessaire pour atteindre un objectif.</p>
                                            <p class="card-text fw-bold badge bg-green mt-2">Activer</p>
                                            {{-- <p class="card-text fw-bold badge bg-danger mt-2 blink">New</p> --}}
                                        </div>
                                    </div>
                                </a>
                            @else
                                <a href="{{asset('choix_plan')}}" wire:navigate class="bloc_mod">
                                    <div class="bloc_img_text">
                                        <div class="part_img">
                                            <img src="storage/img_module/taches.png" alt="" class="img_mod">
                                        </div>
                                        <div class="part_text">
                                            <h5 class="card-title"><i class="fa fa-tasks"></i> Gestion Tâches</h5>
                                            <p class="truncate_wamsco mb-1">La gestion des tâches est le processus qui consiste à identifier, suivre et exécuter le travail nécessaire pour atteindre un objectif.</p>
                                            <p class="card-text fw-bold badge bg-danger mt-2">Débloquer le module</p>
                                        </div>
                                    </div>
                                </a>
                            @endif
                        </div>                    
                    </div>
                    <div class="col-sm-4 col-lg-3 mb-4">
                        <div class="cardor border-0 bg-white-500 text-white">
                            @if($entite_mods->mod_tache == 1 && $dateJour <= $entite_mods->validite_mod)
                                <a href="{{asset('#')}}" class="bloc_mod" title="c'est un savant mélange de calculs précis, de respect du droit du travail et de conformité fiscale.">
                                    <div class="bloc_img_text">
                                        <div class="part_img">
                                            <img src="storage/img_module/paie.jpg" alt="" class="img_mod">
                                        </div>
                                        <div class="part_text">
                                            <h5 class="card-title" style="font-size: 14px;"><i class="fab fa-cc-mastercard"></i> Gestion Paie</h5>
                                            <p class="truncate_wamsco mb-1">C'est un savant mélange de calculs précis, de respect du droit du travail et de conformité fiscale.</p>
                                            {{-- <p class="card-text fw-bold badge bg-green mt-2">Activer</p> --}}
                                            <p class="card-text fw-bold badge bg-danger mt-2 blink">Bientôt dispo.</p>
                                        </div>
                                    </div>
                                </a>
                            @else
                                <a href="{{asset('choix_plan')}}" wire:navigate class="bloc_mod">
                                    <div class="bloc_img_text">
                                        <div class="part_img">
                                            <img src="storage/img_module/paie.jpg" alt="" class="img_mod">
                                        </div>
                                        <div class="part_text">
                                            <h5 class="card-title"><i class="fab fa-cc-mastercard"></i> Gestion Paie</h5>
                                            <p class="truncate_wamsco mb-1">C'est un savant mélange de calculs précis, de respect du droit du travail et de conformité fiscale.</p>
                                            <p class="card-text fw-bold badge bg-danger mt-2">Débloquer le module</p>
                                        </div>
                                    </div>
                                </a>
                            @endif
                        </div>                    
                    </div>
                    @if(auth()->user()->societe == "Administration")
                    <div class="col-sm-4 col-lg-3 mb-4">
                        <div class="cardor border-0 bg-white-500 text-white">
                            @if($entite_mods->mod_gestion_commercial == 1 && $dateJour <= $entite_mods->validite_mod)
                                <a href="{{asset('liste_souscription?active=16&champ=1-3')}}" wire:navigate class="bloc_mod" title="Une personne dont le travail consiste à vendre des produits ou des services pour une entreprise.">
                                    <div class="bloc_img_text">
                                        <div class="part_img">
                                            <img src="storage/img_module/commercial.png" alt="" class="img_mod">
                                        </div>
                                        <div class="part_text">
                                            <h5 class="card-title"><i class="fas fa-sitemap"></i> Commercial</h5>
                                            <p class="truncate_wamsco mb-1">Une personne dont le travail consiste à vendre des produits ou des services pour une entreprise.</p>
                                            <p class="card-text fw-bold badge bg-green mt-2">Activer</p> 
                                        </div>
                                    </div>                                    
                                </a>
                            @else
                                <a href="{{asset('choix_plan')}}" wire:navigate class="bloc_mod">
                                    <div class="bloc_img_text">
                                        <div class="part_img">
                                            <img src="storage/img_module/2.png" alt="" class="img_mod">
                                        </div>
                                        <div class="part_text">
                                            <h5 class="card-title"><i class="fas fa-sitemap"></i> Commercial</h5>
                                            <p class="truncate_wamsco mb-1">Une personne dont le travail consiste à vendre des produits ou des services pour une entreprise..</p>
                                            <p class="card-text fw-bold badge bg-danger mt-2">Débloquer le module</p> 
                                        </div>
                                    </div>
                                </a>
                            @endif
                        </div>                    
                    </div>
                    @endif
                    <div class="col-sm-4 col-lg-3 mb-4">
                        <div class="cardor border-0 bg-white-500 text-white">
                            @if($entite_mods->mod_multisociete == 1 && $dateJour <= $entite_mods->validite_mod)
                                <a href="{{asset('liste_societe?active=11&champ=1-1')}}" wire:navigate class="bloc_mod" title="Avec WamsCo, gérer plusieurs sociétés sans contrainte au meme endroit et avec un seul compte.">
                                    <div class="bloc_img_text">
                                        <div class="part_img">
                                            <img src="storage/img_module/2.png" alt="" class="img_mod">
                                        </div>
                                        <div class="part_text">
                                            <h5 class="card-title"><i class="fas fa-sitemap"></i> Multi-société</h5>
                                            <p class="truncate_wamsco mb-1">Avec WamsCo, gérer plusieurs sociétés sans contrainte au meme endroit et avec un seul compte.</p>
                                            <p class="card-text fw-bold badge bg-green mt-2">Activer</p> 
                                        </div>
                                    </div>                                    
                                </a>
                            @else
                                <a href="{{asset('choix_plan')}}" wire:navigate class="bloc_mod">
                                    <div class="bloc_img_text">
                                        <div class="part_img">
                                            <img src="storage/img_module/2.png" alt="" class="img_mod">
                                        </div>
                                        <div class="part_text">
                                            <h5 class="card-title"><i class="fas fa-sitemap"></i> Multi-société</h5>
                                            <p class="truncate_wamsco mb-1">Avec WamsCo, gérer plusieurs sociétés sans contrainte au meme endroit et avec un seul compte.</p>
                                            <p class="card-text fw-bold badge bg-danger mt-2">Débloquer le module</p> 
                                        </div>
                                    </div>
                                </a>
                            @endif
                        </div>                    
                    </div> 
                    <div class="col-sm-4 col-lg-3 mb-4">
                        <div class="cardor border-0 bg-white-500 text-white">
                            @if($entite_mods->mod_administration == 1 && $dateJour <= $entite_mods->validite_mod)
                                <a href="{{asset('entite?active=12&champ=1-3')}}" wire:navigate class="bloc_mod" title="Gérer tous vos collaborateurs au meme endroit: utilisateurs, rôles, taxe, devise etc...">
                                    <div class="bloc_img_text">
                                        <div class="part_img">
                                            <img src="storage/img_module/parametre.jpg" alt="" class="img_mod">
                                        </div>
                                        <div class="part_text">
                                            <h5 class="card-title"><i class="fa fa-cog fa-spin"></i> Paramètres</h5>
                                            <p class="truncate_wamsco mb-1">Gérer tous vos collaborateurs au meme endroit: utilisateurs, rôles, taxe, devise etc...</p>
                                            <p class="card-text fw-bold badge bg-green mt-2">Activer</p>
                                        </div>
                                    </div>
                                </a>
                            @else
                                <a href="{{asset('choix_plan')}}" wire:navigate class="bloc_mod">
                                    <div class="bloc_img_text">
                                        <div class="part_img">
                                            <img src="storage/img_module/parametre.jpg" alt="" class="img_mod">
                                        </div>
                                        <div class="part_text">
                                            <h5 class="card-title"><i class="fa fa-cog fa-spin"></i> Paramètres</h5>
                                            <p class="truncate_wamsco mb-1">Gérer tous vos collaborateurs au meme endroit: utilisateurs, rôles, taxe, devise etc...</p>
                                            <p class="card-text fw-bold badge bg-danger mt-2">Débloquer le module</p>
                                        </div>
                                    </div>
                                </a>
                            @endif
                        </div>                    
                    </div>                     
                    {{-- <div class="col-sm-4 col-lg-3 mb-4">
                        <div class="cardor border-0 bg-white-500 text-white">
                            @if($entite_mods->mod_paie == 1 && $dateJour <= $entite_mods->validite_mod)
                                <a href="#" class="bloc_mod">
                                    <div class="bloc_img_text">
                                        <div class="part_img">
                                            <img src="storage/img_module/paie.jpg" alt="" class="img_mod">
                                        </div>
                                        <div class="part_text">
                                            <h5 class="card-title"><i class="fa fa-money-bill"></i> Paie</h5>
                                            <p class="truncate_wamsco mb-1">Gérer vos bulletins de paie, congés etc...</p>
                                            <p class="card-text fw-bold badge bg-green mt-2">Activer</p>
                                            <p class="card-text fw-bold badge bg-warning mt-2">En cours de dev</p>
                                        </div>
                                    </div>
                                </a>
                            @else
                                <a href="https://api.whatsapp.com/send?phone=+237654258009&text=Bonjour WamsCo, nous souhaiterons activer ce module (Gestion de paie). Quelles sont les modalités? Merci." target="_blank" class="bloc_mod">
                                <a href="#" class="bloc_mod">
                                    <div class="bloc_img_text">
                                        <div class="part_img">
                                            <img src="storage/img_module/paie.jpg" alt="" class="img_mod">
                                        </div>
                                        <div class="part_text">
                                            <h5 class="card-title"><i class="fa fa-money-bill"></i> Paie</h5>
                                            <p class="truncate_wamsco mb-1">Gérer vos bulletins de paie, congés etc...</p>
                                            <p class="card-text fw-bold badge bg-danger mt-2">Débloquer le module</p>
                                            <p class="card-text fw-bold badge bg-warning mt-2">En cours de dev</p>
                                        </div>
                                    </div>
                                </a>
                            @endif
                        </div>                    
                    </div> --}}
                    {{-- <div class="col-sm-4 col-lg-3 mb-4">
                        <div class="cardor border-0 bg-white-500 text-white">
                            @if($entite_mods->mod_gestion_employe == 1 && $dateJour <= $entite_mods->validite_mod)
                                <a href="{{asset('employes?active=12&champ=1-1&choix=1')}}" class="bloc_mod">
                                    <div class="bloc_img_text">
                                        <div class="part_img">
                                            <img src="storage/img_module/person.jpg" alt="" class="img_mod">
                                        </div>
                                        <div class="part_text">
                                            <h5 class="card-title"><i class="fa fa-users"></i> Employé</h5>
                                            <p class="truncate_wamsco mb-1">Gérer vos employés, poste de travail etc...</p>
                                            <p class="card-text fw-bold badge bg-green mt-2">Activer</p>
                                        </div>
                                    </div>
                                </a>
                            @else
                                <a href="https://api.whatsapp.com/send?phone=+237654258009&text=Bonjour WamsCo, nous souhaiterons activer ce module (Gestion employés). Quelles sont les modalités? Merci." target="_blank" class="bloc_mod">
                                    <div class="bloc_img_text">
                                        <div class="part_img">
                                            <img src="storage/img_module/person.jpg" alt="" class="img_mod">
                                        </div>
                                        <div class="part_text">
                                            <h5 class="card-title"><i class="fa fa-users"></i> Employés</h5>
                                            <p class="truncate_wamsco mb-1">Gérer vos employés, poste de travail etc...</p>
                                            <p class="card-text fw-bold badge bg-danger mt-2">Désactiver</p>
                                        </div>
                                    </div>
                                </a>
                            @endif
                        </div>                    
                    </div> --}}
                    {{-- <div class="col-sm-4 col-lg-3 mb-4">
                        <div class="cardor border-0 bg-white-500 text-white">
                            @if($entite_mods->mod_pressing == 1 && $dateJour <= $entite_mods->validite_mod)
                                <a href="{{asset('tableau_pressing?active=3&champ=1-1&choix=1')}}" class="bloc_mod">
                                    <div class="bloc_img_text">
                                        <div class="part_img">
                                            <img src="storage/img_module/4.png" alt="" class="img_mod">
                                        </div>
                                        <div class="part_text">
                                            <h5 class="card-title"><i class="fa fa-recycle fa-spin"></i> Pressing</h5>
                                            <p class="truncate_wamsco mb-1">Module de gestion simple, performant et efficace.</p>
                                            <p class="card-text fw-bold badge bg-green mt-2">Activer</p>
                                        </div>
                                    </div>
                                </a>
                            @else
                                <a href="https://api.whatsapp.com/send?phone=+237654258009&text=Bonjour WamsCo, nous souhaiterons activer ce module (Gestion Pressing). Quelles sont les modalités? Merci." target="_blank" class="bloc_mod">
                                    <div class="bloc_img_text">
                                        <div class="part_img">
                                            <img src="storage/img_module/4.png" alt="" class="img_mod">
                                        </div>
                                        <div class="part_text">
                                            <h5 class="card-title"><i class="fa fa-recycle fa-spin"></i> Pressing</h5>
                                            <p class="truncate_wamsco mb-1">Module de gestion simple, performant et efficace.</p>
                                            <p class="card-text fw-bold badge bg-danger mt-2">Désactiver</p> 
                                        </div>
                                    </div>
                                </a>
                            @endif
                        </div>                    
                    </div>  --}}                    
                </div>
            @endforeach
        </div>
    </div>
</div>
