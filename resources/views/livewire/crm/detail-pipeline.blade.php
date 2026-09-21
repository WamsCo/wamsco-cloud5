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
                @include('flash::message')
                <div class="card mb-0 bg-2k">
                    @foreach($tier as $tiers)
                        <div class="container-fluid py-2 px-2">
                            <div class="row">
                                <!-- SIDEBAR -->
                                <div class="col-lg-3">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-body border border-light-subtle rounded-3 bg-white position-relative custom-scroll" style="height: calc(100vh - 132px); overflow-y: auto;">
                                            <div class="text-centerk">
                                                <div class="d-flex align-items-centerk">
                                                    <img src="{{ asset('storage/default/user_man.png')}}" width="70" height="70" class="rounded-circle border">
                                                    <div class="ms-2">
                                                        <h4 class="mb-0 fw-bold" title="{{$tiers->nom}}">{{Str::limit($tiers->nom.'', 42) ?? 'Inconu(e)'}}</h4>
                                                        <div class="text-success fw-bold">{{ $tiers->type_tiers }}</div>
                                                        <span class="text-success fw-semibold">@if($tiers->code_tier)<i class="fas fa-user-circle text-bleu"></i> <span class="text-bleu fw-semibold" title="Code {{$tiers->type_tiers}}">{{$tiers->code_tier}}</span>@endif</span>
                                                        {{-- <br><small class="text-muted">REF-001</small><br> --}}
                                                        <div class="text-muted">
                                                            @if($tiers->etat == 1)
                                                                <span class="badge bg-success py-0" title="Activer">Activer</span>
                                                            @else
                                                                <span class="badge bg-danger py-0" title="Désactiver">Désactiver </span>
                                                            @endif
                                                        </div>
                                                    </div>                                                    
                                                </div>
                                            </div>
                                            <hr class="my-2">
                                            <div class="d-flex gap-1 mb-1">
                                                {{-- <button href="#" wire:click.prevent="edit({{$tiers->id}})" data-bs-toggle="modal" data-bs-target="#soldeTiersModal" title="Cliquez pour recharger le compte client" data-toggle="tooltip" class="btn btn-sm btn-white text-danger flex-fill fw-semibold ms-auto"><i class="fas fa-file-invoice-dollar"></i> Recharge</button> --}}
                                                <button href="{{asset('pipeline_tiers?active=3&champ=3-3&choix=1')}}" wire:navigate class="btn btn-sm btn-outline-success flex-fill fw-semibold ms-auto"><i class="fa fa-close"></i> Fermer</button>
                                                <button href="#" wire:click.prevent="update()" title="Cliquez pour modifier" data-toggle="tooltip" class="btn btn-sm btn-outline-secondary flex-fill fw-semibold ms-auto"><i class="fa fa-pen"></i> Modifier</button>
                                                @if($confirmer === $this->ids)                                                               
                                                    <button wire:click.prevent="supprimer({{$this->ids}})" class="btn btn-sm btn-outline-danger bg-danger text-white blink" style="font-size:11px;"  title="Cliquez pour confirmer la suppression" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-trash"> Confirmer ?</i></button>
                                                @else
                                                    <button wire:click.prevent="confirmerDelete({{$this->ids}})" class="btn btn-sm btn-outline-muted" title="Cliquez pour supprimer" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-trash"></i></button>
                                                @endif 
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between gap-1 mb-3"> 
                                                <a class="btn btn-sm btn-outline-primary flex-fill fw-semibold ms-auto" wire:click.prevent="creerProforma()" title="Cliquez pour créer une nouvelle proforma" data-toggle="tooltip"><i class="fa fa-dolly"></i> Créer proforma</a>
                                                <a class="btn btn-sm btn-outline-green flex-fill fw-semibold ms-auto" wire:click.prevent="creerFacture()" title="Cliquez pour créer une nouvelle facture" data-toggle="tooltip"><i class="fa fa-money-bill"></i> Créer facture</a>
                                            </div>                                             
                                            <h6 class="fw-bold mb-1">Opportunité » <span class="text-vert">OPP-{{$this->ids}}</span> </h6>
                                            <div class="crm-card">
                                                <div class="card-body px-0 py-1">
                                                    <div class="row mb-2">
                                                        <div class="">
                                                            <textarea rows="2" wire:model="nom_opportunite" class="form-control bordure text-bleu fs-5 px-0 @error('nom_opportunite') is-invalid @enderror" id="nom_opportunite" placeholder="Nom de l’opportunité"></textarea>
                                                        </div>
                                                        <div class="d-flex justify-content-start">
                                                            @error('nom_opportunite') <span class="text-danger">{{$message}}</span> @enderror 
                                                        </div>
                                                    </div>
                                                    <div class="row mb-1">
                                                        <label for="montant_attendu" class="form-label fw-semibold text-muted">Revenu attendu</label>
                                                        <div class="">
                                                            <div class="d-flex align-items-center">
                                                                <input type="number" wire:model="montant_attendu" min="0" class="form-control bordure w-75 fw-bold @error('montant_attendu') is-invalid @enderror" id="montant_attendu"> 
                                                                <span class="fw-semibold text-bleu fs-6">{{$this->devise}}</span>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex justify-content-start">
                                                            @error('montant_attendu') <span class="text-danger">{{ $message }}</span> @enderror 
                                                        </div>
                                                    </div> 
                                                    <div class="row mb-1">
                                                        <label for="probabilite" class="form-label fw-semibold text-muted">Probabilité succès</label>
                                                        <div class="">                                                        
                                                            <select id="probabilite" wire:model="probabilite" class="form-control form-select fw-bold text-bleu bordurek @error('probabilite') is-invalid @enderror" id="probabilite">
                                                                @for($i = 0; $i <= 100; $i += 10)
                                                                    <option value="{{$i}}%">Probabilité succès » {{$i}}%</option>
                                                                @endfor                                                                                       
                                                            </select> 
                                                        </div>
                                                        <div class="d-flex justify-content-start">
                                                            @error('probabilite') <span class="text-danger">{{ $message }}</span> @enderror 
                                                        </div>
                                                    </div>   
                                                    <div class="row mb-1">
                                                        <label for="date_cloture" class="form-label fw-semibold text-muted">Date clôture</label>
                                                        <div class="">
                                                            <input type="date" wire:model="date_cloture" class="form-control bordurek text-danger @error('date_cloture') is-invalid @enderror" id="date_cloture">
                                                        </div>
                                                        <div class="d-flex justify-content-start">
                                                            @error('date_cloture') <span class="text-danger">{{ $message }}</span> @enderror 
                                                        </div>
                                                    </div> 
                                                    <div class="row mb-1">
                                                        <label for="secteur_activite" class="form-label fw-semibold text-muted">Secteur d'activité</label>
                                                        <div class="">
                                                            <input type="text" wire:model="secteur_activite" placeholder="Ex: Restaurant" class="form-control bordurek text-vert @error('secteur_activite') is-invalid @enderror" id="secteur_activite">
                                                        </div>
                                                        <div class="d-flex justify-content-start">
                                                            @error('secteur_activite') <span class="text-danger">{{ $message }}</span> @enderror 
                                                        </div>
                                                    </div>                                                         
                                                    <div class="row mb-1">
                                                        <label for="client" class="form-label fw-semibold text-muted">Contact</label>
                                                        <div class="">    
                                                            <input type="search" name="client" wire:model.live="client" wire:keyup="searchResult" placeholder="Commencez à écrire... A-Z ou 0-9" class="form-control bordurek @error('client') is-invalid @enderror" id="client"/>
                                                            <div class="bloc_search_client" style="position: absolute;">                       
                                                                @if($showdiv)
                                                                    @if($recordCount > 0)
                                                                        <table class="table table-hover mb-0 text-nowrap w-75" style="overflow: auto; background:#f7f7f7;">                                                        
                                                                            <tbody>
                                                                                @if(!empty($records))                                                                
                                                                                    @foreach($records as $record)
                                                                                        <tr>                                         
                                                                                            <td class="pointer fw-semibold" wire:click="ajouterTier({{$record->id}})"><i class="fas fa-user-circle"></i> {{Str::limit($record->nom, 22)}} » <span style="color: #8fbc8f; font-weight:600">{{Str::limit($record->telephone, 22)}}</span></td>   
                                                                                        </tr>                                        
                                                                                    @endforeach
                                                                                    <tr>                                         
                                                                                        <td class="pointer fw-semibold text-danger"><a href="nouveau_tiers?active=3&champ=3-1" target="_blank" class="text-danger"><i class="fas fa-user-plus"></i> Créer client</a></td>  
                                                                                    </tr> 
                                                                                @endif                               
                                                                            </tbody>                                        
                                                                        </table>
                                                                        @endif	
                                                                @endif	
                                                            </div>
                                                        </div>
                                                        <div class="d-flex justify-content-start">
                                                            @error('client') <span class="text-danger">{{ $message }}</span> @enderror 
                                                        </div>
                                                    </div>
                                                    <div class="row mb-1">
                                                        <label for="poste_contact" class="form-label fw-semibold text-muted">Poste</label>
                                                        <div class="">
                                                            <input type="text" wire:model="poste_contact" placeholder="Ex: Comptable" class="form-control bordurek text-wamsco @error('poste_contact') is-invalid @enderror" id="poste_contact">
                                                        </div>
                                                        <div class="d-flex justify-content-start">
                                                            @error('poste_contact') <span class="text-danger">{{ $message }}</span> @enderror 
                                                        </div>
                                                    </div>
                                                    <div class="row mb-1">
                                                        <label for="telephone_contact" class="form-label fw-semibold text-muted">Téléphone</label>
                                                        <div class="">
                                                            <input type="text" wire:model="telephone_contact" class="form-control bordurek @error('telephone_contact') is-invalid @enderror" id="telephone_contact">
                                                        </div>
                                                        <div class="d-flex justify-content-start">
                                                            @error('telephone_contact') <span class="text-danger">{{ $message }}</span> @enderror 
                                                        </div>
                                                    </div>
                                                    <div class="row mb-1">
                                                        <label for="email_contact" class="form-label fw-semibold text-muted">E-mail</label>
                                                        <div class="">
                                                            <input type="email" wire:model="email_contact"  class="form-control bordurek @error('email_contact') is-invalid @enderror" id="email_contact">
                                                        </div>
                                                        <div class="d-flex justify-content-start">
                                                            @error('email_contact') <span class="text-danger">{{ $message }}</span> @enderror 
                                                        </div>
                                                    </div>
                                                    <div class="row mb-1">
                                                        <label for="vendeur" class="form-label fw-semibold text-muted">Assigner à (vendeur)</label>
                                                        <div class="">                                                        
                                                            <select id="vendeur" wire:model="vendeur" class="form-control form-select text-danger bordurek @error('vendeur') is-invalid @enderror">
                                                                <option value=""></option>	
                                                                @foreach($user as $users)
                                                                    <option value="{{$users->id}}">{{$users->name}}</option>
                                                                @endforeach 
                                                            </select> 
                                                        </div>
                                                        <div class="d-flex justify-content-start">
                                                            @error('vendeur') <span class="text-danger">{{ $message }}</span> @enderror 
                                                        </div>
                                                    </div>                                                                                            
                                                    <div class="row mb-1">
                                                        <label for="evolution" class="form-label fw-semibold text-muted">Étapes</label>
                                                        <div class="">                                                        
                                                            <select id="evolution" wire:model="evolution" class="form-control fw-bold text-vert form-select bordurek @error('evolution') is-invalid @enderror">
                                                                <option value=""></option>	
                                                                @foreach($etape as $etapes)
                                                                    <option value="{{$etapes->id}}">{{$etapes->nom_etape}}</option>
                                                                @endforeach 
                                                            </select> 
                                                        </div>
                                                        <div class="d-flex justify-content-start">
                                                            @error('evolution') <span class="text-danger">{{ $message }}</span> @enderror 
                                                        </div>
                                                    </div>
                                                    <div class="row mb-1">
                                                        <label for="priorite" class="form-label fw-semibold text-muted">Priorité</label>
                                                        <div class="">                                                        
                                                            <select id="priorite" wire:model="priorite" class="form-control form-select fw-bold text-primary bordurek @error('priorite') is-invalid @enderror" id="priorite">
                                                                <option value=""></option>	
                                                                <option value="Faible">Faible</option>	
                                                                <option value="Moyenne">Moyenne</option>
                                                                <option value="Haute">Haute</option>
                                                                <option value="Très élevé">Très élevé</option>                               
                                                            </select> 
                                                        </div>
                                                        <div class="d-flex justify-content-start">
                                                            @error('priorite') <span class="text-danger">{{ $message }}</span> @enderror 
                                                        </div>
                                                    </div>   
                                                    <div class="table-responsive mt-2">
                                                        <div class="table-responsive mt-1"> 
                                                            <ul class="nav nav-tabs border-0" role="tablist">                                                        
                                                                <li class="nav-item">
                                                                    <a class="nav-link active" data-bs-toggle="tab" href="#contact">Contacts</a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a class="nav-link" data-bs-toggle="tab" href="#noter">Note</a>
                                                                </li>
                                                            </ul>
                                                            <div class="tab-content table-responsive border-topk px-1" style="border-top-style: dashed; border-top-width: 1px;border-top-color: #0e04043d;">
                                                                <div id="noter" class="tab-pane">
                                                                    <div class="row">
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">                               
                                                                            <div class="row mb-4">
                                                                                <div class="col-lg-12 col-md-12 col-sm-12">                                                        
                                                                                    <textarea rows="7" wire:model="note" class="form-control px-0 bordurek @error('note') is-invalid @enderror" id="note" placeholder="Ajouter une note..."></textarea>
                                                                                </div>
                                                                                <div class="d-flex justify-content-start">
                                                                                    @error('note') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                                </div>
                                                                            </div>                                          
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div id="contact" class="tab-pane active">                               
                                                                    <div class="row mt-2">
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                            <div class="w_horizontal_separator mt-2 mb-2 text-bleu text-uppercase fw-bolder small">Informations société</div>
                                                                            <div class="row mb-1">
                                                                                <label for="nom_societe" class="form-label fw-semibold text-muted">Nom société</label>
                                                                                <div class="">
                                                                                    <div class="d-flex align-items-center">
                                                                                        <input type="text" wire:model="nom_societe" placeholder="Ex: WamsCo Sarl" class="form-control bordurek text-bleu @error('nom_societe') is-invalid @enderror" id="nom_societe"> 
                                                                                    </div>
                                                                                </div>
                                                                                <div class="d-flex justify-content-start">
                                                                                    @error('nom_societe') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                                </div>
                                                                            </div>                                                                     
                                                                            <div class="row mb-1">
                                                                                <label for="adresse_societe" class="form-label fw-semibold text-muted">Adresse</label>
                                                                                <div class="">
                                                                                    <input type="text" wire:model="adresse_societe" placeholder="Ex: Rue po" class="form-control bordurek text-wamsco @error('adresse_societe') is-invalid @enderror" id="adresse_societe">
                                                                                </div>
                                                                                <div class="d-flex justify-content-start">
                                                                                    @error('adresse_societe') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                                </div>
                                                                            </div>
                                                                            <div class="row mb-1">
                                                                                <label for="ville" class="form-label fw-semibold text-muted">Ville</label>
                                                                                <div class="">
                                                                                    <input type="text" wire:model="ville" placeholder="Ex: Douala" class="form-control bordurek text-wamsco @error('ville') is-invalid @enderror" id="ville">
                                                                                </div>
                                                                                <div class="d-flex justify-content-start">
                                                                                    @error('ville') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                                </div>
                                                                            </div>
                                                                            <div class="row mb-1">
                                                                                <label for="pays" class="form-label fw-semibold text-muted">Pays</label>
                                                                                <div class="">                                                        
                                                                                    <select name="pays" wire:model="pays" class="form-control form-select bordurek @error('pays') is-invalid @enderror" style="color: #6b6b6b;">
                                                                                        <option value="" selected="" disabled="">Pays</option>
                                                                                        <option value="Afghanistan">Afghanistan</option>
                                                                                        <option value="Åland Islands">Åland Islands</option>
                                                                                        <option value="Albania">Albania</option>
                                                                                        <option value="Algeria">Algeria</option>
                                                                                        <option value="American Samoa">American Samoa</option>
                                                                                        <option value="Andorra">Andorra</option>
                                                                                        <option value="Angola">Angola</option>
                                                                                        <option value="Anguilla">Anguilla</option>
                                                                                        <option value="Antarctica">Antarctica</option>
                                                                                        <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                                                                                        <option value="Argentina">Argentina</option>
                                                                                        <option value="Armenia">Armenia</option>
                                                                                        <option value="Aruba">Aruba</option>
                                                                                        <option value="Australia">Australia</option>
                                                                                        <option value="Austria">Austria</option>
                                                                                        <option value="Azerbaijan">Azerbaijan</option>
                                                                                        <option value="Bahamas">Bahamas</option>
                                                                                        <option value="Bahrain">Bahrain</option>
                                                                                        <option value="Bangladesh">Bangladesh</option>
                                                                                        <option value="Barbados">Barbados</option>
                                                                                        <option value="Belarus">Belarus</option>
                                                                                        <option value="Belgium">Belgium</option>
                                                                                        <option value="Belize">Belize</option>
                                                                                        <option value="Benin">Benin</option>
                                                                                        <option value="Bermuda">Bermuda</option>
                                                                                        <option value="Bhutan">Bhutan</option>
                                                                                        <option value="Bolivia, Plurinational State of">Bolivia, Plurinational State of</option>
                                                                                        <option value="Bonaire, Sint Eustatius and Saba">Bonaire, Sint Eustatius and Saba</option>
                                                                                        <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                                                                                        <option value="Botswana">Botswana</option>
                                                                                        <option value="Bouvet Island">Bouvet Island</option>
                                                                                        <option value="Brazil">Brazil</option>
                                                                                        <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                                                                                        <option value="Brunei Darussalam Darussalam">Brunei Darussalam</option>
                                                                                        <option value="Bulgaria">Bulgaria</option>
                                                                                        <option value="Burkina Faso">Burkina Faso</option>
                                                                                        <option value="Burundi">Burundi</option>
                                                                                        <option value="Cambodia">Cambodia</option>
                                                                                        <option value="Cameroon">Cameroon</option>
                                                                                        <option value="Canada">Canada</option>
                                                                                        <option value="Cape Verde">Cape Verde</option>
                                                                                        <option value="Cayman Islands">Cayman Islands</option>
                                                                                        <option value="Central African Republic">Central African Republic</option>
                                                                                        <option value="Chad">Chad</option>
                                                                                        <option value="Chile">Chile</option>
                                                                                        <option value="China">China</option>
                                                                                        <option value="Christmas Island">Christmas Island</option>
                                                                                        <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                                                                                        <option value="Colombia">Colombia</option>
                                                                                        <option value="Comoros">Comoros</option>
                                                                                        <option value="Congo">Congo</option>
                                                                                        <option value="Congo, the Democratic Republic of the">Congo, the Democratic Republic of the</option>
                                                                                        <option value="Cook Islands">Cook Islands</option>
                                                                                        <option value="Costa Rica">Costa Rica</option>
                                                                                        <option value="Côte d'Ivoire">Côte d'Ivoire</option>
                                                                                        <option value="Croatia">Croatia</option>
                                                                                        <option value="Cuba">Cuba</option>
                                                                                        <option value="Curaçao">Curaçao</option>
                                                                                        <option value="Cyprus">Cyprus</option>
                                                                                        <option value="Czech Republic">Czech Republic</option>
                                                                                        <option value="Denmark">Denmark</option>
                                                                                        <option value="Djibouti">Djibouti</option>
                                                                                        <option value="Dominica">Dominica</option>
                                                                                        <option value="Dominican Republic">Dominican Republic</option>
                                                                                        <option value="Ecuador">Ecuador</option>
                                                                                        <option value="Egypt">Egypt</option>
                                                                                        <option value="El Salvador">El Salvador</option>
                                                                                        <option value="Equatorial Guinea">Equatorial Guinea</option>
                                                                                        <option value="Eritrea">Eritrea</option>
                                                                                        <option value="Estonia">Estonia</option>
                                                                                        <option value="Ethiopia">Ethiopia</option>
                                                                                        <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                                                                                        <option value="Faroe Islands">Faroe Islands</option>
                                                                                        <option value="Fiji">Fiji</option>
                                                                                        <option value="Finland">Finland</option>
                                                                                        <option value="France">France</option>
                                                                                        <option value="French Guiana">French Guiana</option>
                                                                                        <option value="French Polynesia">French Polynesia</option>
                                                                                        <option value="French Southern Territories">French Southern Territories</option>
                                                                                        <option value="Gabon">Gabon</option>
                                                                                        <option value="Gambia">Gambia</option>
                                                                                        <option value="Georgia">Georgia</option>
                                                                                        <option value="Germany">Germany</option>
                                                                                        <option value="Ghana">Ghana</option>
                                                                                        <option value="Gibraltar">Gibraltar</option>
                                                                                        <option value="Greece">Greece</option>
                                                                                        <option value="Greenland">Greenland</option>
                                                                                        <option value="Grenada">Grenada</option>
                                                                                        <option value="Guadeloupe">Guadeloupe</option>
                                                                                        <option value="Guam">Guam</option>
                                                                                        <option value="Guatemala">Guatemala</option>
                                                                                        <option value="Guernsey">Guernsey</option>
                                                                                        <option value="Guinea">Guinea</option>
                                                                                        <option value="Guinea-Bissau">Guinea-Bissau</option>
                                                                                        <option value="Guyana">Guyana</option>
                                                                                        <option value="Haiti">Haiti</option>
                                                                                        <option value="Heard Island and McDonald Islands">Heard Island and McDonald Islands</option>
                                                                                        <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
                                                                                        <option value="Honduras">Honduras</option>
                                                                                        <option value="Hong Kong">Hong Kong</option>
                                                                                        <option value="Hungary">Hungary</option>
                                                                                        <option value="Iceland">Iceland</option>
                                                                                        <option value="India">India</option>
                                                                                        <option value="Indonesia">Indonesia</option>
                                                                                        <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
                                                                                        <option value="Iraq">Iraq</option>
                                                                                        <option value="Ireland">Ireland</option>
                                                                                        <option value="Isle of Man">Isle of Man</option>
                                                                                        <option value="Israel">Israel</option>
                                                                                        <option value="Italy">Italy</option>
                                                                                        <option value="Jamaica">Jamaica</option>
                                                                                        <option value="Japan">Japan</option>
                                                                                        <option value="Jersey">Jersey</option>
                                                                                        <option value="Jordan">Jordan</option>
                                                                                        <option value="Kazakhstan">Kazakhstan</option>
                                                                                        <option value="Kenya">Kenya</option>
                                                                                        <option value="Kiribati">Kiribati</option>
                                                                                        <option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
                                                                                        <option value="Korea, Republic of">Korea, Republic of</option>
                                                                                        <option value="Kuwait">Kuwait</option>
                                                                                        <option value="Kyrgyzstan">Kyrgyzstan</option>
                                                                                        <option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
                                                                                        <option value="Latvia">Latvia</option>
                                                                                        <option value="Lebanon">Lebanon</option>
                                                                                        <option value="Lesotho">Lesotho</option>
                                                                                        <option value="Liberia">Liberia</option>
                                                                                        <option value="Libya">Libya</option>
                                                                                        <option value="Liechtenstein">Liechtenstein</option>
                                                                                        <option value="Lithuania">Lithuania</option>
                                                                                        <option value="Luxembourg">Luxembourg</option>
                                                                                        <option value="Macao">Macao</option>
                                                                                        <option value="Macedonia, the former Yugoslav Republic of">Macedonia, the former Yugoslav Republic of</option>
                                                                                        <option value="Madagascar">Madagascar</option>
                                                                                        <option value="Malawi">Malawi</option>
                                                                                        <option value="Malaysia">Malaysia</option>
                                                                                        <option value="Maldives">Maldives</option>
                                                                                        <option value="Mali">Mali</option>
                                                                                        <option value="Malta">Malta</option>
                                                                                        <option value="Marshall Islands"></option>
                                                                                        <option value="Martinique">Martinique</option>
                                                                                        <option value="Mauritania">Mauritania</option>
                                                                                        <option value="Mauritius">Mauritius</option>
                                                                                        <option value="Mayotte">Mayotte</option>
                                                                                        <option value="Mexico">Mexico</option>
                                                                                        <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
                                                                                        <option value="Moldova, Republic of">Moldova, Republic of</option>
                                                                                        <option value="Monaco">Monaco</option>
                                                                                        <option value="Mongolia">Mongolia</option>
                                                                                        <option value="Montenegro">Montenegro</option>
                                                                                        <option value="Montserrat">Montserrat</option>
                                                                                        <option value="Morocco">Morocco</option>
                                                                                        <option value="Mozambique">Mozambique</option>
                                                                                        <option value="Myanmar">Myanmar</option>
                                                                                        <option value="Namibia">Namibia</option>
                                                                                        <option value="Nauru">Nauru</option>
                                                                                        <option value="Nepal">Nepal</option>
                                                                                        <option value="Netherlands">Netherlands</option>
                                                                                        <option value="New Caledonia">New Caledonia</option>
                                                                                        <option value="New Zealand">New Zealand</option>
                                                                                        <option value="Nicaragua">Nicaragua</option>
                                                                                        <option value="Niger">Niger</option>
                                                                                        <option value="Nigeria">Nigeria</option>
                                                                                        <option value="Niue">Niue</option>
                                                                                        <option value="Norfolk Island">Norfolk Island</option>
                                                                                        <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                                                                                        <option value="Norway">Norway</option>
                                                                                        <option value="Oman">Oman</option>
                                                                                        <option value="Pakistan">Pakistan</option>
                                                                                        <option value="Palau">Palau</option>
                                                                                        <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
                                                                                        <option value="Panama">Panama</option>
                                                                                        <option value="Papua New Guinea">Papua New Guinea</option>
                                                                                        <option value="Paraguay">Paraguay</option>
                                                                                        <option value="Peru">Peru</option>
                                                                                        <option value="Philippines">Philippines</option>
                                                                                        <option value="Pitcairn">Pitcairn</option>
                                                                                        <option value="Poland">Poland</option>
                                                                                        <option value="Portugal">Portugal</option>
                                                                                        <option value="Puerto Rico">Puerto Rico</option>
                                                                                        <option value="Qatar">Qatar</option>
                                                                                        <option value="Réunion">Réunion</option>
                                                                                        <option value="Romania">Romania</option>
                                                                                        <option value="Russian Federation">Russian Federation</option>
                                                                                        <option value="Rwanda">Rwanda</option>
                                                                                        <option value="Saint Barthélemy">Saint Barthélemy</option>
                                                                                        <option value="Saint Helena, Ascension and Tristan da Cunha">Saint Helena, Ascension and Tristan da Cunha</option>
                                                                                        <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                                                                                        <option value="Saint Lucia">Saint Lucia</option>
                                                                                        <option value="Saint Martin (French part)">Saint Martin (French part)</option>
                                                                                        <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                                                                                        <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
                                                                                        <option value="Samoa">Samoa</option>
                                                                                        <option value="San Marino">San Marino</option>
                                                                                        <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                                                                                        <option value="Saudi Arabia">Saudi Arabia</option>
                                                                                        <option value="Senegal">Senegal</option>
                                                                                        <option value="Serbia">Serbia</option>
                                                                                        <option value="Seychelles">Seychelles</option>
                                                                                        <option value="Sierra Leone">Sierra Leone</option>
                                                                                        <option value="Singapore">Singapore</option>
                                                                                        <option value="Sint Maarten (Dutch part)">Sint Maarten (Dutch part)</option>
                                                                                        <option value="Slovakia">Slovakia</option>
                                                                                        <option value="Slovenia">Slovenia</option>
                                                                                        <option value="Solomon Islands">Solomon Islands</option>
                                                                                        <option value="Somalia">Somalia</option>
                                                                                        <option value="South Africa">South Africa</option>
                                                                                        <option value="South Georgia and the South Sandwich Islands">South Georgia and the South Sandwich Islands</option>
                                                                                        <option value="South Sudan">South Sudan</option>
                                                                                        <option value="Spain">Spain</option>
                                                                                        <option value="Sri Lanka">Sri Lanka</option>
                                                                                        <option value="Sudan">Sudan</option>
                                                                                        <option value="Suriname">Suriname</option>
                                                                                        <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                                                                                        <option value="Swaziland">Swaziland</option>
                                                                                        <option value="Sweden">Sweden</option>
                                                                                        <option value="Switzerland">Switzerland</option>
                                                                                        <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                                                                                        <option value="Taiwan, Province of China">Taiwan, Province of China</option>
                                                                                        <option value="Tajikistan">Tajikistan</option>
                                                                                        <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
                                                                                        <option value="Thailand">Thailand</option>
                                                                                        <option value="Timor-Leste">Timor-Leste</option>
                                                                                        <option value="Togo">Togo</option>
                                                                                        <option value="Tokelau">Tokelau</option>
                                                                                        <option value="Tonga">Tonga</option>
                                                                                        <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                                                                                        <option value="Tunisia">Tunisia</option>
                                                                                        <option value="Turkey">Turkey</option>
                                                                                        <option value="Turkmenistan">Turkmenistan</option>
                                                                                        <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                                                                                        <option value="Tuvalu">Tuvalu</option>
                                                                                        <option value="Uganda">Uganda</option>
                                                                                        <option value="Ukraine">Ukraine</option>
                                                                                        <option value="United Arab Emirates">United Arab Emirates</option>
                                                                                        <option value="United Kingdom">United Kingdom</option>
                                                                                        <option value="United States">United States</option>
                                                                                        <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                                                                                        <option value="Uruguay">Uruguay</option>
                                                                                        <option value="Uzbekistan">Uzbekistan</option>
                                                                                        <option value="Vanuatu">Vanuatu</option>
                                                                                        <option value="Venezuela, Bolivarian Republic of">Venezuela, Bolivarian Republic of</option>
                                                                                        <option value="Viet Nam">Viet Nam</option>
                                                                                        <option value="Virgin Islands, British">Virgin Islands, British</option>
                                                                                        <option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
                                                                                        <option value="Wallis and Futuna">Wallis and Futuna</option>
                                                                                        <option value="Western Sahara">Western Sahara</option>
                                                                                        <option value="Yemen">Yemen</option>
                                                                                        <option value="Zambia">Zambia</option>
                                                                                        <option value="Zimbabwe">Zimbabwe</option>                                                                                                    
                                                                                    </select> 
                                                                                </div>
                                                                                <div class="d-flex justify-content-start">
                                                                                    @error('pays') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                                </div>
                                                                            </div> 
                                                                            <div class="row mb-1">
                                                                                <label for="langue" class="form-label fw-semibold text-muted">Langue</label>
                                                                                <div class="">                                                        
                                                                                    <select id="langue" wire:model="langue" class="form-control form-select bordurek text-wamsco w-75 @error('langue') is-invalid @enderror">
                                                                                        <option value=""></option>
                                                                                        <option value="Français">Français</option>
                                                                                        <option value="Anglais">Anglais</option>
                                                                                    </select> 
                                                                                </div>
                                                                                <div class="d-flex justify-content-start">
                                                                                    @error('langue') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                            <div class="w_horizontal_separator mt-2 mb-2 text-bleu text-uppercase fw-bolder small">Autres Informations</div>                                                                                                                                              
                                                                            <div class="row mb-1">
                                                                                <label for="site_web" class="form-label fw-semibold text-muted">Site Web</label>
                                                                                <div class="">
                                                                                    <input type="text" wire:model="site_web" placeholder="Ex: www.wamsco-cloud.net" class="form-control bordurek text-wamsco @error('site_web') is-invalid @enderror" id="site_web">
                                                                                </div>
                                                                                <div class="d-flex justify-content-start">
                                                                                    @error('site_web') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                                </div>
                                                                            </div>
                                                                            <div class="row mb-1">
                                                                                <label for="recommande_par" class="form-label fw-semibold text-muted">Recommandé par</label>
                                                                                <div class="">
                                                                                    <input type="text" wire:model="recommande_par" placeholder="Ex: John Doe" class="form-control bordurek text-wamsco @error('recommande_par') is-invalid @enderror" id="recommande_par">
                                                                                </div>
                                                                                <div class="d-flex justify-content-start">
                                                                                    @error('recommande_par') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                                </div>
                                                                            </div>
                                                                            <div class="row mb-1">
                                                                                <label for="telephone_recommande_par" class="form-label fw-semibold text-muted">Téléphone</label>
                                                                                <div class="">
                                                                                    <input type="text" wire:model="telephone_recommande_par" placeholder="" class="form-control bordurek text-wamsco @error('telephone_recommande_par') is-invalid @enderror" id="telephone_recommande_par">
                                                                                </div>
                                                                                <div class="d-flex justify-content-start">
                                                                                    @error('telephone_recommande_par') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                                </div>
                                                                            </div>
                                                                            <div class="row mb-1">
                                                                                <label for="campagne" class="form-label fw-semibold text-muted">Campagne</label>
                                                                                <div class="">
                                                                                    <input type="text" wire:model="campagne" placeholder="Ex. Campagne Facebook septembre" class="form-control bordurek text-wamsco @error('campagne') is-invalid @enderror" id="campagne">
                                                                                </div>
                                                                                <div class="d-flex justify-content-start">
                                                                                    @error('campagne') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                                </div>
                                                                            </div>
                                                                            <div class="row mb-1">
                                                                                <label for="source" class="form-label fw-semibold text-muted">Source</label>
                                                                                <div class="">                                                        
                                                                                    <select wire:model="source" class="form-control form-select fw-semibold text-bleu bordurek @error('source') is-invalid @enderror" id="source">
                                                                                        <option value=""></option>	
                                                                                        <option value="Site web">Site web</option>
                                                                                        <option value="WhatsApp">WhatsApp</option>
                                                                                        <option value="Facebook">Facebook</option>                               
                                                                                        <option value="TikTok">TikTok</option>                               
                                                                                        <option value="Instagram">Instagram</option>                               
                                                                                        <option value="Google">Google</option>                               
                                                                                        <option value="Google Maps">Google Maps</option>                               
                                                                                        <option value="Email">Email</option>
                                                                                        <option value="Commercial">Commercial</option>
                                                                                        <option value="Partenaire">Partenaire</option>                               
                                                                                    </select> 
                                                                                </div>
                                                                                <div class="d-flex justify-content-start">
                                                                                    @error('source') <span class="text-danger">{{ $message }}</span> @enderror 
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
                                            {{-- <div class="w_horizontal_separator mt-4 mb-2 text-bleu text-uppercase fw-bolder small">Details tier</div>
                                            <div class="table-responsive">
                                                <table class="table table-borderless text-nowrap m-0">
                                                    <tr>
                                                        <td class="text-muted fw-semibold">Raison sociale <i class="fas fa-info-circle" title="Nom officiel d'une entreprise" data-bs-toggle="tooltip" data-bs-placement="top" style=" vertical-align: middle; cursor: help"></i></td>
                                                        <td>{{ $tiers->raison_sociale }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted fw-semibold">Type</td>
                                                        <td>{{ $tiers->type_tiers }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted fw-semibold">Téléphone</td>
                                                        <td>{{ $tiers->telephone }}</td>
                                                    </tr>
                                                    @if($tiers->email)
                                                        <tr>
                                                            <td class="text-muted fw-semibold">Email</td>
                                                            <td>{{ $tiers->email }}</td>
                                                        </tr>
                                                    @endif
                                                    <tr>
                                                        <td class="text-muted fw-semibold">Sexe</td>
                                                        <td class="fw-semibold @if($tiers->sexe == "Masculin") text-success @else text-info @endif">{{ $tiers->sexe }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted fw-semibold">Solde <i class="fas fa-file-invoice-dollar"></i></td>
                                                        <td class="text-primary fw-bold">{{ number_format($tiers->solde,0,',',' ') }}FCFA</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted fw-semibold">Ville | Pays</td>
                                                        <td>{{ $tiers->ville }}|{{ $tiers->pays }}</td>
                                                    </tr>
                                                    @if($tiers->adresse)
                                                    <tr>
                                                        <td class="text-muted fw-semibold">Adresse</td>
                                                        <td>{{ $tiers->adresse }}</td>
                                                    </tr>
                                                    @endif
                                                    @if($tiers->code_postal)
                                                    <tr>
                                                        <td class="text-muted fw-semibold">Code postal</td>
                                                        <td>{{ $tiers->code_postal }}</td>
                                                    </tr>
                                                    @endif
                                                    @if($tiers->site_web)
                                                        <tr>
                                                            <td class="text-muted fw-semibold">Site web</td>
                                                            <td><a href="/{{ $tiers->site_web }}" target="_blank">{{ $tiers->site_web }}</a></td>
                                                        </tr>
                                                    @endif
                                                    @if($tiers->commercial)
                                                        <tr>
                                                            <td class="text-muted fw-semibold">Commercial</td>
                                                            <td>{{ $tiers->commercial }}</td>
                                                        </tr>
                                                    @endif
                                                </table>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                                <!-- CONTENU -->
                                <div class="col-lg-9">
                                    <!-- KPI -->
                                    <div class="card shadow-sm border border-light-subtle rounded-3 bg-white">
                                        <div class="card-body p-2">
                                            <div class="d-flex align-items-center justify-content-between flex-nowrap overflow-auto py-2">
                                                
                                                <div class="d-flex align-items-center px-3 border-end flex-fill">
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 44px; height: 44px; background-color: #eaf2ff; color: #0d6efd;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                                        </svg>
                                                    </div>
                                                    <div class="text-nowrap">
                                                        <span class="d-block fw-bold text-secondary" style="font-size: 11px;">Date de création</span>
                                                        <span class="d-block fw-semibold text-dark" style="font-size: 13px;">{{ $date_created_at->format('d/m/Y : H:i:s') }}</span>
                                                        {{-- <span class="d-block text-muted" style="font-size: 11px; margin-top: -2px;">GMT+1</span> --}}
                                                    </div>
                                                </div>

                                                <div class="d-flex align-items-center px-3 border-end flex-fill">
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 44px; height: 44px; background-color: #e8f5e9; color: #198754;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                                                        </svg>
                                                    </div>
                                                    <div class="text-nowrap">
                                                        <span class="d-block fw-bold text-secondary" style="font-size: 11px;">Dernière activité</span>
                                                        <span class="d-block fw-semibold text-dark" style="font-size: 13px;">{{ $date_updated_at->format('d/m/Y : H:i:s') }}</span>
                                                        {{-- <span class="d-block text-muted" style="font-size: 11px; margin-top: -2px;">GMT+1</span> --}}
                                                    </div>
                                                </div>

                                                <div class="d-flex align-items-center px-3 border-end flex-fill">
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 44px; height: 44px; background-color: #f3e5f5; color: #6f42c1;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                                        </svg>
                                                    </div>
                                                    <div class="text-nowrap">
                                                        <span class="d-block fw-bold text-secondary" style="font-size: 11px;">Phase du cycle de vie</span>
                                                        <span class="d-block fw-bold text-dark mt-1" style="font-size: 13px;">{{ $tiers->type_tiers }}</span>
                                                    </div>
                                                </div>

                                                <div class="d-flex align-items-center px-3 border-end flex-fill">
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 44px; height: 44px; background-color: #fff3e0; color: #fd7e14;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                        </svg>
                                                    </div>
                                                    <div class="text-nowrap">
                                                        <span class="fw-bold text-secondary" style="font-size: 11px;">Factures</span>
                                                        <span class="d-block fw-bold text-dark mt-1" style="font-size: 14px;">{{$FactCount}}</span>
                                                    </div>
                                                </div>

                                                <div class="d-flex align-items-center px-3 border-end flex-fill">
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 44px; height: 44px; background-color: #e0f7fa; color: #0dcaf0;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5h16.5a1.5 1.5 0 011.5 1.5v11.25a1.5 1.5 0 01-1.5 1.5H3.75a1.5 1.5 0 01-1.5-1.5V6a1.5 1.5 0 011.5-1.5zm10.5 7.5a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                                        </svg>
                                                    </div>
                                                    <div class="text-nowrap">
                                                        <span class="d-block fw-bold text-secondary" style="font-size: 11px;">Montant TTC</span>
                                                        <span class="d-block fw-bold text-dark mt-1" style="font-size: 14px;">{{number_format($FactMontant_ttc,0,',',' ')}} <span style="font-size: 10px;">{{$this->devise}}</span></span>
                                                    </div>
                                                </div>

                                                <div class="d-flex align-items-center px-3 border-end flex-fill">
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 44px; height: 44px; background-color: #fff8e1; color: #ffc107;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
                                                        </svg>
                                                    </div>
                                                    <div class="text-nowrap">
                                                        <span class="d-block fw-bold text-secondary" style="font-size: 11px;">Marge</span>
                                                        <span class="d-block fw-bold text-dark mt-1" style="font-size: 14px;">{{number_format($FactMarge,0,',',' ')}} <span style="font-size: 10px;">{{$this->devise}}</span></span>
                                                    </div>
                                                </div>

                                                <div class="d-flex align-items-center px-3 flex-fill">
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 44px; height: 44px; background-color: #ffebee; color: #dc3545;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v3" />
                                                        </svg>
                                                    </div>
                                                    <div class="text-nowrap">
                                                        <span class="d-block fw-bold text-secondary" style="font-size: 11px;">Créance</span>
                                                        <span class="d-block fw-bold text-dark mt-1" style="font-size: 14px;">{{number_format($FactReste_a_percevoir,0,',',' ')}} <span style="font-size: 10px;">{{$this->devise}}</span></span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>                                    
                                    <!-- ONGLETS -->
                                    <div class="card-header d-flex align-items-center justify-content-between border-0 px-0">
                                        <!-- Nav tabs -->
                                        <ul class="nav nav-tabs margin_ajuste border-0" role="tablist">
                                            <li class="nav-item">                                                
                                                <a class="nav-link active" data-bs-toggle="tab" href="#home">Vue d'ensemble</a>                                                
                                            </li> 
                                             <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#activites">Activités récentes ({{$logCount}})</a>
                                            </li>  
                                            {{-- <li class="nav-item">
                                                <a class="nav-link pointer" wire:click.prevent="soldeTier()">Solde</a>
                                            </li>  --}}
                                        </ul>
                                        <!-- Tab panes -->                        
                                        <div class="d-flex align-items-center justify-content-between gap-1">
                                            <div class="d-flex align-items-center justify-content-between gap-1"> 
                                                {{-- <a href="facturationcltkk-pdf?code=" class="btn btn-sm btn-default" title="Cliquez pour imprimer" data-toggle="tooltip" style="color: #161a1d;"><i class="fa fa-print"></i>Ticket</a> --}}
                                                {{-- <a href="facturationclt-pdf?code=Fact/191224/a0adc871" class="btn btn-sm btn-default" title="Cliquez pour imprimer" data-toggle="tooltip" target="blank"><i class="fa fa-print"></i>A4</a> --}}
                                            </div>                            
                                            <div class="d-flex align-items-center justify-content-between gap-1">                            
                                                <a href="{{asset('pipeline_tiers?active=3&champ=3-3&choix=1')}}" wire:navigate class="btn btn-sm btn-bleu d-none d-md-block ms-auto"><i class="fa fa-chevron-left"></i> Retour liste</a>
                                                <button class="btn btn-sm btn-default new_color" @if($this->ids) wire:click.prevent="precedant({{$this->ids}})" @else wire:click.prevent="precedant({{$this->id}})" @endif title="Précédant" data-toggle="tooltip"><i class="fa fa-chevron-left"></i></button>
                                                <a href="#" class="btn btn-sm btn-default new_color" @if($this->ids) wire:click.prevent="suivant({{$this->ids}})" @else wire:click.prevent="suivant({{$this->id}})" @endif title="Suivant" data-toggle="tooltip"><i class="fa fa-chevron-right"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-content">
                                        <div id="home" class="container-fluid tab-pane active pas_bordure">
                                            <div class="row">
                                                <div class="col-lg-8 col-md-8 col-xs-12">
                                                    <div class="card shadow-sm">  
                                                        <div class="card shadow-sm border border-light-subtle rounded-3 bg-white p-2">    
                                                            <h6 class="fw-bold text-bleu mb-3"><i class="fas fa-calendar-alt" style=" color: #393b83;" title="Activités Planifiées"></i> @if($logCount > 1) Activités Planifiées @else Activité Planifiée @endif ({{$planifierCount}})</h6>
                                                            <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-3">
                                                                <div class="d-flex gap-2 align-items-center">
                                                                    <div class="input-group input-group-sm border rounded-2" style="width: 240px; background: #fff;">
                                                                        <input type="search" wire:model.live="ParNote" id="ParNote" class="form-control border-0 shadow-none bg-transparent" placeholder="Rechercher une activité..." style="font-size: 13px;">
                                                                        <button class="btn border-0 text-muted shadow-none d-flex align-items-center" type="button">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 14px; height: 14px;">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                                                            </svg>
                                                                        </button>
                                                                    </div>                                                            
                                                                    {{-- <button class="btn btn-sm btn-outline-secondary bg-white border-light-subtle shadow-sm d-flex align-items-center gap-1" type="button" style="font-size: 13px;">
                                                                        Ajouter une activité
                                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 12px; height: 12px;">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                                                        </svg>
                                                                    </button> --}}
                                                                </div>
                                                                {{-- <div class="d-flex align-items-center gap-2">
                                                                    <span class="text-dark fw-medium" style="font-size: 13px;">Filtrer :</span>
                                                                    <button class="btn btn-sm btn-outline-secondary bg-white border-light-subtle shadow-sm d-flex align-items-center gap-1" type="button" style="font-size: 13px;">
                                                                        Tout
                                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 12px; height: 12px;">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                                                        </svg>
                                                                    </button>
                                                                </div> --}}
                                                            </div>
                                                            <div class="position-relative custom-scroll" style="height: calc(100vh - 408px); overflow-y: auto;">  
                                                                <div class="position-absolute border-start" style="left: 17px; top: 10px; bottom: 0; border-color: #dee2e6 !important; border-width: 2px !important; z-index: 1;"></div>
                                                                @foreach($planifier as $planifiers)
                                                                    <div class="d-flex mb-3 position-relative" style="z-index: 2;">
                                                                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 bg-white" style="width: 36px; height: 36px;">
                                                                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background-color: #f1f3f5; color: #198754;">
                                                                                @if($planifiers->type_activite == "Note")
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 16px; height: 16px;">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                                                    </svg>                                                                                    
                                                                                @elseif($planifiers->type_activite == "Appel")
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16" style="width: 14px; height: 14px;">
                                                                                        <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/>
                                                                                    </svg>
                                                                                @elseif($planifiers->type_activite == "Email")
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16" style="width: 14px; height: 14px;">
                                                                                        <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555ZM0 4.697v7.104l5.803-3.558L0 4.697ZM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757Zm3.436-.586L16 11.801V4.697l-5.803 3.546Z"/>
                                                                                    </svg>
                                                                                @elseif($planifiers->type_activite == "Rendez-vous")
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 15px; height: 15px;">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                                                                    </svg>
                                                                                @elseif($planifiers->type_activite == "Tâche")
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 12px; height: 12px;">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 01-.923 1.785A5.969 5.969 0 006 21c1.282 0 2.47-.402 3.445-1.069.5-.34 1.148-.285 1.64.107A8.38 8.38 0 0012 20.25z" />
                                                                                    </svg>  
                                                                                @else
                                                                                    <div class="position-relative bg-inherit rounded-3">
                                                                                        @if($planifiers->profil != null)  
                                                                                            <a href="detail_user?id={{$planifiers->user_id}}&active=12&champ=1-1" wire:navigate>
                                                                                                <img class="image_log object-fit-cover rounded-3 cursor-pointer" src="storage/{{$planifiers->profil}}">
                                                                                            </a>
                                                                                        @else       
                                                                                            <a href="detail_user?id={{$planifiers->user_id}}&active=12&champ=1-1" wire:navigate>
                                                                                                <img class="image_log object-fit-cover rounded-3 cursor-pointer" src="storage/default/user_man.png"> 
                                                                                            </a>                    
                                                                                        @endif
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                        <div class="card border border-light-subtle shadow-sm flex-grow-1 ms-3">
                                                                            <div class="card-body p-2 d-flex justify-content-between align-items-center">
                                                                                <div>
                                                                                    <div class="d-flex align-items-center gap-1">
                                                                                        <span class="fw-bold text-dark" style="font-size: 13px;"><i class="fas fa-spinner fa-spin text-bleu"></i> {{$planifiers->type_activite}}</span>
                                                                                        <span class="text-muted" style="font-size: 12px;"><a href="detail_user?id={{$planifiers->user_id}}&active=12&champ=1-1" wire:navigate class="text-muted">par {{$planifiers->nom_user}}</a></span>
                                                                                        @if($planifiers->statut == "Terminer")
                                                                                            <span class="text-vert fw-semibold"><i class="fas fa-check"></i> Terminer</span>
                                                                                        @else
                                                                                            <span wire:click.prevent="ModifNote({{$planifiers->id}})"><i class="fas fa-pencil text-muted pointer"></i></span>
                                                                                        @endif
                                                                                    </div>
                                                                                    <div class="text-secondary mt-1" style="font-size: 13px;">
                                                                                        <span class="fw-semibold text-muted pb-3" style="font-size: 13px;">
                                                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 12px; height: 12px; color:#8a2be2;">
                                                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 01-.923 1.785A5.969 5.969 0 006 21c1.282 0 2.47-.402 3.445-1.069.5-.34 1.148-.285 1.64.107A8.38 8.38 0 0012 20.25z" />
                                                                                            </svg> 
                                                                                            {{$planifiers->sujet}}
                                                                                        </span>
                                                                                        <span style="white-space: pre-line;">
                                                                                            {{ $planifiers->commentaire }}
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="d-flex align-items-center gap-3">
                                                                                    @if($planifiers->statut == "Terminer")
                                                                                        <span class="text-muted text-nowrap fw-semibold" style="font-size: 11px;">{{\Carbon\Carbon::parse($planifiers->updated_at)->locale('fr')->translatedFormat('j F Y \à H:i')}}</span>
                                                                                    @else
                                                                                        @php
                                                                                            $dateEcheance = \Carbon\Carbon::parse($planifiers->date_echeance);
                                                                                            $maintenant = now();
                                                                                            $joursDifference = $maintenant->copy()->startOfDay()->diffInDays($dateEcheance->copy()->startOfDay(), false);
                                                                                        @endphp

                                                                                        @if($joursDifference < 0)
                                                                                            <span class="badge bg-danger">Retard {{ abs($joursDifference) }} {{ abs($joursDifference) > 1 ? 'jours' : 'jour' }}</span>
                                                                                        @elseif($joursDifference == 0)
                                                                                            <span class="badge bg-warning">Aujourd'hui à {{ $dateEcheance->format('H:i') }}</span>
                                                                                        @elseif($joursDifference == 1)
                                                                                            <span class="badge bg-info">Demain à {{ $dateEcheance->format('H:i') }}</span>
                                                                                        @elseif($joursDifference <= 7)
                                                                                            <span class="badge bg-primary">Dans {{ $joursDifference }} jours à {{ $dateEcheance->format('H:i') }}</span>
                                                                                        @else
                                                                                            <span class="text-muted" style="font-size:11px;">{{ $dateEcheance->locale('fr')->translatedFormat('j F Y \à H:i') }}</span>
                                                                                        @endif
                                                                                    @endif
                                                                                    <button class="btn btn-sm btn-link text-muted p-0 text-decoration-none d-flex align-items-center">
                                                                                        @if($approuver === $planifiers->id) 
                                                                                            <i class="fa fa-trash text-danger blink" wire:click.prevent="ecraser({{$planifiers->id}})" title="Cliquez pour confirmer la suppression"></i>
                                                                                        @else
                                                                                            <i class="fa fa-trash" wire:click.prevent="approuverDelete({{$planifiers->id}})" title="Cliquez pour supprimer"></i>
                                                                                        @endif
                                                                                    </button>                                                                                    
                                                                                </div>                                                                                                                                                               
                                                                            </div>
                                                                            @if($ouvrir === $planifiers->id)
                                                                                <div class="d-flexf align-items-center justify-content-between px-2 py-2">
                                                                                    <div class="row mb-2">
                                                                                        <label for="type_activites" class="form-label fw-semibold text-muted">Type</label>
                                                                                        <div class="">                                                        
                                                                                            <select id="type_activites" wire:model="type_activites" class="form-control form-select fw-bold bordurek w-100 @error('type_activites') is-invalid @enderror">
                                                                                                <option value=""></option>	
                                                                                                <option value="Appel">Appel</option>	
                                                                                                <option value="Email">Email</option>	
                                                                                                <option value="Note">Note</option>	
                                                                                                <option value="WhatsApp">WhatsApp</option>	
                                                                                                <option value="Tâche">Tâche</option>                                                                                    
                                                                                                <option value="Rendez-vous">Rendez-vous</option>	
                                                                                            </select> 
                                                                                        </div>
                                                                                        <div class="d-flex justify-content-start">
                                                                                            @error('type_activites') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="row mb-2">
                                                                                        <label for="sujets" class="form-label fw-semibold text-muted">Sujet</label>
                                                                                        <div class="">
                                                                                            <input type="text" wire:model="sujets" placeholder="Sujet" class="form-control bordurek text-bleu w-100 @error('sujets') is-invalid @enderror" id="sujets">
                                                                                        </div>
                                                                                        <div class="d-flex justify-content-start">
                                                                                            @error('sujets') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="row mb-2">
                                                                                        <label for="date_echeance" class="form-label fw-semibold text-muted">Date échéance</label>
                                                                                        <div class="">
                                                                                            <input type="datetime-local" wire:model="date_echeances" class="form-control bordurek text-vert w-100 @error('date_echeance') is-invalid @enderror" id="date_echeance">
                                                                                        </div>
                                                                                        <div class="d-flex justify-content-start">
                                                                                            @error('date_echeance') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="row mb-1">
                                                                                        <div class="col-sm-12">
                                                                                            <textarea rows="5" wire:model="commentaires" class="form-control text-bleu w-100 @error('commentaires') is-invalid @enderror" id="commentaires" placeholder="Enregistrer un commentaire 😊"></textarea>
                                                                                        </div>
                                                                                        <div class="d-flex justify-content-start">
                                                                                            @error('commentaires') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                                        </div>
                                                                                    </div>                                                                                      
                                                                                    <div class="text-start"> 
                                                                                        <button class="btn btn-xs btn-secondary fw-bold" wire:click.prevent="modifierNote()" title="Cliquez pour modifier" data-toggle="tooltip"><i class="fa fa-pencil"></i> Modifier</button>
                                                                                        <button class="btn btn-xs btn-danger fw-bold" wire:click.prevent="onDataOuvrir()" title="Cliquez pour fermer" data-toggle="tooltip"><i class="fa fa-close"></i></button>
                                                                                        <button class="btn btn-xs btn-white fw-bold" wire:click.prevent="Terminer()" title="Cliquez pour clôturer" data-toggle="tooltip"><i class="fa fa-check"></i> Terminer</button>
                                                                                    </div>
                                                                                </div> 
                                                                            @endif 
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div> 
                                                            <div class="alert mt-3 mb-0 d-flex justify-content-between align-items-center rounded-2 px-3 py-2" style="background-color: #f4f8ff; border: 1px solid #cce0ff;">
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#0d6efd" style="width: 18px; height: 18px;">
                                                                        <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm8.706-1.442c1.146-.573 2.437.463 2.126 1.732l-1.162 4.646c-.23.916.634 1.56 1.393 1.144a.75.75 0 01.684 1.326c-1.63 1.09-3.7-.068-3.136-1.92l1.162-4.646c.15-.6-.35-1.12-1-.74a.75.75 0 01-.767-1.302zM12 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" />
                                                                    </svg>
                                                                    <span class="text-dark" style="font-size: 13px;">
                                                                        <span class="fw-bold">Voir plus d'opportunités</span> 
                                                                        <span class="text-muted ms-1">et développer votre portefeuille de prospects</span>
                                                                    </span>
                                                                </div>
                                                                <a href="{{asset('pipeline_tiers?active=3&champ=3-3&choix=1')}}" wire:navigate class="text-decoration-none fw-semibold text-primary" style="font-size: 13px;">Voir plus</a>
                                                            </div>
                                                        </div>                                                
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-xs-12">
                                                    <div class="card shadow-sm">
                                                        <div class="card shadow-sm border border-light-subtle rounded-3 bg-white p-2">
                                                            <h6 class="fw-bold text-bleu mb-1"><i class="fas fa-calendar-alt" style=" color: #393b83;" title="Derniers événements liés"></i> Planifier une activité</h6>
                                                            <div class="position-relative custom-scroll" style="height: calc(100vh - 305px); overflow-y: auto;">
                                                               <div class="crm-cardk">
                                                                    {{-- <div class="card-header bg-white border-0 py-3">
                                                                        <h6 class="mb-0 fw-bold">Nouvelle activité</h6>
                                                                    </div> --}}
                                                                    <div class="card-body">
                                                                        <div class="row mb-2">
                                                                            <label for="type_activite" class="form-label fw-semibold text-muted">Type</label>
                                                                            <div class="">                                                        
                                                                                <select id="type_activite" wire:model="type_activite" class="form-control form-select fw-bold bordure w-100 @error('type_activite') is-invalid @enderror">
                                                                                    <option value=""></option>	
                                                                                    <option value="Appel">Appel</option>	
                                                                                    <option value="Email">Email</option>	
                                                                                    <option value="Note">Note</option>	
                                                                                    <option value="WhatsApp">WhatsApp</option>	
                                                                                    <option value="Tâche">Tâche</option>                                                                                    
                                                                                    <option value="Rendez-vous">Rendez-vous</option>	
                                                                                </select> 
                                                                            </div>
                                                                            <div class="d-flex justify-content-start">
                                                                                @error('type_activite') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                            </div>
                                                                        </div>
                                                                        <div class="row mb-2">
                                                                            <label for="sujet" class="form-label fw-semibold text-muted">Sujet</label>
                                                                            <div class="">
                                                                                <input type="text" wire:model="sujet" placeholder="" class="form-control bordurek text-wamsco w-100 @error('sujet') is-invalid @enderror" id="sujet">
                                                                            </div>
                                                                            <div class="d-flex justify-content-start">
                                                                                @error('sujet') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                            </div>
                                                                        </div>
                                                                        <div class="row mb-2">
                                                                            <label for="date_echeance" class="form-label fw-semibold text-muted">Date échéance</label>
                                                                            <div class="">
                                                                                <input type="datetime-local" wire:model="date_echeance" class="form-control bordurek text-vert w-100 @error('date_echeance') is-invalid @enderror" id="date_echeance">
                                                                            </div>
                                                                            <div class="d-flex justify-content-start">
                                                                                @error('date_echeance') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                            </div>
                                                                        </div>                                                                         
                                                                        <div class="row mb-3">
                                                                            <label class="form-label fw-semibold text-muted">Commentaire</label>
                                                                            <div class="">                                                        
                                                                                <textarea rows="5" wire:model="commentaire" class="form-control borduref @error('commentaire') is-invalid @enderror" id="commentaire" placeholder="Ajouter une commentaire... 😊"></textarea>
                                                                            </div>
                                                                            <div class="d-flex justify-content-start">
                                                                                @error('commentaire') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                            </div>
                                                                        </div>
                                                                        <button href="#" wire:click.prevent="saveActivite()" title="Cliquez pour Enregistrer" data-toggle="tooltip" class="btn btn-md btn-wamsco w-100"><i class="fas fa-save"></i> Enregistrer</button>
                                                                    </div>
                                                                </div>
                                                            </div>                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="activites" class="container-fluid tab-pane fade pas_bordure">  
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12">   
                                                    <div class="card shadow-sm border border-light-subtle rounded-3 bg-white p-2">
                                                        <h6 class="fw-bold text-bleu mb-3"><i class="fas fa-calendar-alt" style=" color: #393b83;" title="Activités récentes"></i> @if($logCount > 1) Activités récentes @else Activité récente @endif ({{$logCount}})</h6>
                                                        <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-3">
                                                            <div class="d-flex gap-2 align-items-center">
                                                                {{-- <div class="input-group input-group-sm border rounded-2" style="width: 240px; background: #fff;">
                                                                    <input type="search" wire:model.live="activite" id="activite" class="form-control border-0 shadow-none bg-transparent" placeholder="Rechercher une activité..." style="font-size: 13px;">
                                                                    <button class="btn border-0 text-muted shadow-none d-flex align-items-center" type="button">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 14px; height: 14px;">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                                                        </svg>
                                                                    </button>
                                                                </div>  --}}
                                                                {{-- <button class="btn btn-sm btn-outline-secondary bg-white border-light-subtle shadow-sm d-flex align-items-center gap-1" type="button" style="font-size: 13px;">
                                                                    Ajouter une activité
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 12px; height: 12px;">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                                                    </svg>
                                                                </button> --}}
                                                            </div>
                                                            {{-- <div class="d-flex align-items-center gap-2">
                                                                <span class="text-dark fw-medium" style="font-size: 13px;">Filtrer :</span>
                                                                <button class="btn btn-sm btn-outline-secondary bg-white border-light-subtle shadow-sm d-flex align-items-center gap-1" type="button" style="font-size: 13px;">
                                                                    Tout
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 12px; height: 12px;">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                                                    </svg>
                                                                </button>
                                                            </div> --}}
                                                        </div>
                                                        <div class="position-relative custom-scroll" style="height: calc(100vh - 325px); overflow-y: auto;">  
                                                            <div class="position-absolute border-start" style="left: 17px; top: 10px; bottom: 0; border-color: #dee2e6 !important; border-width: 2px !important; z-index: 1;"></div>
                                                            @foreach($log as $logs)
                                                            <div class="d-flex mb-3 position-relative" style="z-index: 2;">
                                                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 bg-white" style="width: 36px; height: 36px;">
                                                                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background-color: #f5e8e8; color: #198754;">
                                                                        {{-- <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16" style="width: 14px; height: 14px;">
                                                                            <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/>
                                                                        </svg> --}}
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
                                                                </div>
                                                                <div class="card border border-light-subtle shadow-sm flex-grow-1 ms-3">
                                                                    <div class="card-body p-2 d-flex justify-content-between align-items-center">
                                                                        <div>
                                                                            <div class="d-flex align-items-center gap-1">
                                                                                <span class="text-muted" style="font-size: 13px;"><i class="fas fa-calendar-alt text-danger"></i> {{$logs->id}}</span>
                                                                                <span class="fw-bold text-dark" style="font-size: 12px;"><a href="detail_user?id={{$logs->user_id}}&active=12&champ=1-1" wire:navigate><i class="fas fa-user-circle text-muted"></i> par {{$logs->user_email}}</a></span>
                                                                            </div>
                                                                            <div class="text-secondary mt-1" style="font-size: 13px;">
                                                                               {{-- Protège le contenu avec e() (évite injection HTML) / le dernier mot : créée ou modifiée, Applique <strong> seulement sur ce mot --}}
                                                                                {!! preg_replace('/(créée|modifiée)$/u','<strong>$1</strong>',e($logs->subject)) !!}
                                                                            </div>
                                                                        </div>
                                                                        <div class="d-flex align-items-center gap-3">
                                                                            <span class="text-muted text-nowrap" style="font-size: 11px;">{{ \Carbon\Carbon::parse($logs->created_at)->locale('fr')->translatedFormat('j F Y \à H:i:s') }}</span>
                                                                            <button class="btn btn-sm btn-link text-muted p-0 text-decoration-none d-flex align-items-center">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16" style="width: 16px; height: 16px;"><path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/></svg>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
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
                    @endforeach 
                </div>
            </div>
        </div>
    </div>
</div>