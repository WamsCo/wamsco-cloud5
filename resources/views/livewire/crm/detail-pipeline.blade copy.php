<div x-data="{selection: @entangle('selection').defer}">
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
                        {{-- <h1 class="page-header mb-1">{{$title_fils}} » <span style="color:yellow">{{$categoriecount}}</span></h1>  --}}
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
                <div class="card mb-0 bg-2k">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center justify-content-between gap-1">                                                       
                            <div class="d-flex align-items-center justify-content-between gap-1">                            
                                <button class="btn btn-sm btn-secondary" wire:click.prevent="update()" title="Cliquez pour modifier" data-toggle="tooltip"><i class="fa fa-check"></i> Modifier</button>
                                <a href="pipeline_tiers?active=3&champ=3-3&choix=1" class="btn btn-sm btn-danger" title="Cliquez pour fermer" data-toggle="tooltip"><i class="fa fa-close"></i> Fermer</a>
                                @if($confirmer === $this->ids)                                                               
                                    <a wire:click.prevent="supprimer({{$this->ids}})" class="btn btn-sm btn-outline-danger bg-danger text-white blink" style="font-size:11px;"  title="Cliquez pour confirmer la suppression" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-trash"> Confirmer ?</i></a>
                                @else
                                    <a wire:click.prevent="confirmerDelete({{$this->ids}})" class="btn btn-sm btn-outline-muted"  title="Cliquez pour supprimer" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-trash"></i></a>
                                @endif  
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between gap-1">                                                       
                            <div class="d-flex align-items-center justify-content-between gap-1">                            
                                <a href="pipeline_tiers?active=3&champ=3-3&choix=1" class="btn btn-sm btn-bleu d-none d-md-block ms-auto"><i class="fa fa-chevron-left"></i> Retour liste</a>
                                <button class="btn btn-sm btn-default new_color" wire:click.prevent="precedant({{$this->ids}})" title="Précédant" data-toggle="tooltip"><i class="fa fa-chevron-left"></i></button>
                                <a href="#" class="btn btn-sm btn-default new_color" wire:click.prevent="suivant({{$this->ids}})" title="Suivant" data-toggle="tooltip"><i class="fa fa-chevron-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-header d-flex align-items-center justify-content-between py-1">
                        <div class="d-flex align-items-center justify-content-between gap-1">
                            <div class="d-flex align-items-center justify-content-between gap-1"> 
                                <a href="#" class="btn btn-sm btn-default" wire:click.prevent="creerProforma()" title="Cliquez pour créer une nouvelle proforma" data-toggle="tooltip" style="color: #161a1d;"><i class="fa fa-plus-circle"></i> Créer proforma</a>
                                <a href="#" class="btn btn-sm btn-default" wire:click.prevent="creerFacture()" title="Cliquez pour créer une nouvelle facture" data-toggle="tooltip"><i class="fa fa-plus-circle"></i> Créer facture</a>
                            </div> 
                        </div>                            
                        <div class="d-flex align-items-center justify-content-between gap-1">
                            <div class="statusref">
                                @if($this->priorite == "Très élevé")
                                    <span class="badge badge-danger py-0 blink" title="{{$this->priorite}}"><i class="fa fa-battery-half"></i> {{$this->priorite}}</span>
                                @elseif($this->priorite == "Haute")
                                    <span class="badge bg-success py-0" title="{{$this->priorite}}"><i class="fa fa-battery-half"></i> {{$this->priorite}}</span>                                
                                @else
                                    <span class="badge bg-info py-0" title="{{$this->priorite}}"><i class="fa fa-battery-half"></i> {{$this->priorite}}</span>  
                                @endif
                            </div>                            
                        </div>
                    </div>
                    <div class="card-body no_bordure border-top taille_ecran">
                        <div class="row mx-0 pt-0">
                            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12 px-1 mb-1 border-end border-bottom rounded-bottom">
                                <div class="hauteur_ecran">
                                    <div class="row pt-1 px-2 pb-2">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label for="nom_opportunite" class="col-lg-12 col-form-label">Opportunité » <span class="text-vert">OPP-{{$this->ids}}</span></label>
                                            <div class="row mb-3">
                                                <div class="col-lg-10 col-md-10 col-sm-10">
                                                    <input type="text" wire:model="nom_opportunite" placeholder="Nom de l’opportunité" class="form-control bordure fs-3 w-100 px-0 @error('nom_opportunite') is-invalid @enderror" id="nom_opportunite">
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('nom_opportunite') <span class="text-danger">{{$message}}</span> @enderror 
                                                </div>
                                            </div>                                            
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <div class="row mb-1">
                                                <label for="montant_attendu" class="col-lg-4 col-md-5 col-sm-4 fw-bold col-form-label">Revenu attendu</label>
                                                <div class="col-lg-8 col-md-7 col-sm-8">
                                                    <div class="d-flex align-items-center">
                                                        <input type="number" wire:model="montant_attendu" min="0" class="form-control bordure w-50 fw-bold @error('montant_attendu') is-invalid @enderror" id="montant_attendu"> 
                                                        <span class="fw-semibold text-bleu fs-6">{{$this->devise}}</span>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('montant_attendu') <span class="text-danger">{{ $message }}</span> @enderror 
                                                </div>
                                            </div> 
                                            <div class="row mb-1">
                                                <label for="client" class="col-lg-3 col-md-4 col-sm-4 fw-bold col-form-label">Contact</label>
                                                <div class="col-lg-9 col-md-8 col-sm-8">    
                                                    <input type="search" name="client" wire:model.live="client" wire:keyup="searchResult" placeholder="Commencez à écrire... A-Z ou 0-9" class="form-control bordure w-100 @error('client') is-invalid @enderror" id="client"/>
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
                                                <label for="poste_contact" class="col-lg-3 col-md-5 col-sm-3 fw-bold col-form-label">Poste</label>
                                                <div class="col-lg-9 col-md-7 col-sm-9">
                                                    <input type="text" wire:model="poste_contact" placeholder="Ex: Comptable" class="form-control bordure text-wamsco w-75 @error('poste_contact') is-invalid @enderror" id="poste_contact">
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('poste_contact') <span class="text-danger">{{ $message }}</span> @enderror 
                                                </div>
                                            </div>
                                            <div class="row mb-1">
                                                <label for="telephone_contact" class="col-lg-3 col-md-5 col-sm-3 fw-bold col-form-label">Téléphone</label>
                                                <div class="col-lg-9 col-md-7 col-sm-9">
                                                    <input type="text" wire:model="telephone_contact" class="form-control bordure w-100 @error('telephone_contact') is-invalid @enderror" id="telephone_contact">
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('telephone_contact') <span class="text-danger">{{ $message }}</span> @enderror 
                                                </div>
                                            </div>
                                            <div class="row mb-1">
                                                <label for="email_contact" class="col-lg-3 col-md-5 col-sm-3 fw-bold col-form-label">E-mail</label>
                                                <div class="col-lg-9 col-md-7 col-sm-9">
                                                    <input type="email" wire:model="email_contact"  class="form-control bordure w-100 @error('email_contact') is-invalid @enderror" id="email_contact">
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('email_contact') <span class="text-danger">{{ $message }}</span> @enderror 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <div class="row mb-1">
                                                <label for="vendeur" class="col-lg-3 col-md-5 col-sm-3 fw-bold col-form-label">Vendeur</label>
                                                <div class="col-lg-9 col-md-7 col-sm-9">                                                        
                                                    <select id="vendeur" wire:model="vendeur" class="form-control form-select bordure w-75 @error('vendeur') is-invalid @enderror">
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
                                                <label for="date_cloture" class="col-lg-5 col-md-5 col-sm-5 col-form-label">Date clôture prévue?</label>
                                                <div class="col-lg-7 col-md-7 col-sm-7">
                                                    <input type="date" wire:model="date_cloture" class="form-control bordure text-danger w-75 @error('date_cloture') is-invalid @enderror" id="date_cloture">
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('date_cloture') <span class="text-danger">{{ $message }}</span> @enderror 
                                                </div>
                                            </div>                                            
                                            <div class="row mb-1">
                                                <label for="evolution" class="col-lg-3 col-md-3 col-sm-3 fw-bold col-form-label">Étapes</label>
                                                <div class="col-lg-9 col-md-9 col-sm-9">                                                        
                                                    <select id="evolution" wire:model="evolution" class="form-control fw-bold text-vert form-select bordure w-75 @error('evolution') is-invalid @enderror">
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
                                                <label for="priorite" class="col-lg-3 col-md-3 col-sm-3 fw-bold col-form-label">Priorité</label>
                                                <div class="col-lg-9 col-md-9 col-sm-9">                                                        
                                                    <select id="priorite" wire:model="priorite" class="form-control form-select fw-bold text-primary bordure w-50 @error('priorite') is-invalid @enderror" id="priorite">
                                                        <option value=""></option>	
                                                        <option value="Faible">Faible</option>	
                                                        <option value="Haute">Haute</option>
                                                        <option value="Très élevé">Très élevé</option>                               
                                                    </select> 
                                                </div>
                                                <div class="d-flex justify-content-start">
                                                    @error('priorite') <span class="text-danger">{{ $message }}</span> @enderror 
                                                </div>
                                            </div>   
                                        </div>                                        
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 px-0 mb-3">
                                            <div class="table-responsive mt-2">
                                                <div class="table-responsive mt-1"> 
                                                    <ul class="nav nav-tabs border-0" role="tablist">                                                        
                                                        <li class="nav-item">
                                                            <a class="nav-link active" data-bs-toggle="tab" href="#contact">Contacts</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" data-bs-toggle="tab" href="#home">Note</a>
                                                        </li>
                                                    </ul>
                                                    <div class="tab-content table-responsive border-topk px-3" style="border-top-style: dashed; border-top-width: 1px;border-top-color: #0e04043d;">
                                                        <div id="home" class="tab-pane">
                                                            <div class="row">
                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">                               
                                                                    <div class="row mb-4">
                                                                        {{-- <label for="note" class="col-lg-1 col-md-2 col-sm-2 col-form-label" style="font-weight: 400;">Note</label> --}}
                                                                        <div class="col-lg-12 col-md-12 col-sm-12">                                                        
                                                                            <textarea rows="7" wire:model="note" class="form-control px-0 bordure @error('note') is-invalid @enderror" id="note" placeholder="Ajouter une description..."></textarea>
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
                                                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                                    <div class="w_horizontal_separator mt-4 mb-3 text-bleu text-uppercase fw-bolder small">Informations sur la société</div>
                                                                    <div class="row mb-1">
                                                                        <label for="nom_societe" class="col-lg-4 col-md-5 col-sm-4 fw-semibold col-form-label">Nom société</label>
                                                                        <div class="col-lg-8 col-md-7 col-sm-8">
                                                                            <div class="d-flex align-items-center">
                                                                                <input type="text" wire:model="nom_societe" placeholder="Ex: WamsCo Sarl" class="form-control bordure w-100 text-bleu @error('nom_societe') is-invalid @enderror" id="nom_societe"> 
                                                                            </div>
                                                                        </div>
                                                                        <div class="d-flex justify-content-start">
                                                                            @error('nom_societe') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                        </div>
                                                                    </div>                                                                     
                                                                    <div class="row mb-1">
                                                                        <label for="adresse_societe" class="col-lg-3 col-md-5 col-sm-3 fw-semibold col-form-label">Adresse</label>
                                                                        <div class="col-lg-9 col-md-7 col-sm-9">
                                                                            <input type="text" wire:model="adresse_societe" placeholder="Ex: Rue po" class="form-control bordure text-wamsco w-100 @error('adresse_societe') is-invalid @enderror" id="adresse_societe">
                                                                        </div>
                                                                        <div class="d-flex justify-content-start">
                                                                            @error('adresse_societe') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-1">
                                                                        <label for="ville" class="col-sm-3 fw-semibold col-form-label">Ville</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" wire:model="ville" placeholder="Ex: Douala" class="form-control bordure text-wamsco w-75 @error('ville') is-invalid @enderror" id="ville">
                                                                        </div>
                                                                        <div class="d-flex justify-content-start">
                                                                            @error('ville') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-1">
                                                                        <label for="pays" class="col-sm-3 fw-semibold col-form-label">Pays</label>
                                                                        <div class="col-sm-9">                                                        
                                                                            <select name="pays" wire:model="pays" class="form-control form-select bordure w-100 @error('pays') is-invalid @enderror" style="color: #6b6b6b;">
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
                                                                        <label for="langue" class="col-lg-3 col-md-5 col-sm-3 fw-semibold col-form-label">Langue</label>
                                                                        <div class="col-lg-9 col-md-7 col-sm-9">                                                        
                                                                            <select id="langue" wire:model="langue" class="form-control form-select bordure text-wamsco w-75 @error('langue') is-invalid @enderror">
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
                                                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                                    <div class="w_horizontal_separator mt-4 mb-3 text-bleu text-uppercase fw-bolder small">Autres Informations</div> 
                                                                    <div class="row mb-1">
                                                                        <label for="secteur_activite" class="col-sm-4 fw-semibold col-form-label">Secteur d'activité</label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" wire:model="secteur_activite" placeholder="Ex: Restaurant" class="form-control bordure text-vert w-100 @error('secteur_activite') is-invalid @enderror" id="secteur_activite">
                                                                        </div>
                                                                        <div class="d-flex justify-content-start">
                                                                            @error('secteur_activite') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                        </div>
                                                                    </div>                                                                    
                                                                    <div class="row mb-1">
                                                                        <label for="site_web" class="col-sm-4 fw-semibold col-form-label">Site Web</label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" wire:model="site_web" placeholder="Ex: www.wamsco-cloud.net" class="form-control bordure text-wamsco w-100 @error('site_web') is-invalid @enderror" id="site_web">
                                                                        </div>
                                                                        <div class="d-flex justify-content-start">
                                                                            @error('site_web') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-1">
                                                                        <label for="recommande_par" class="col-sm-5 fw-semibold col-form-label">Recommandé par</label>
                                                                        <div class="col-sm-7">
                                                                            <input type="text" wire:model="recommande_par" placeholder="Ex: John Doe" class="form-control bordure text-wamsco w-100 @error('recommande_par') is-invalid @enderror" id="recommande_par">
                                                                        </div>
                                                                        <div class="d-flex justify-content-start">
                                                                            @error('recommande_par') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-1">
                                                                        <label for="telephone_recommande_par" class="col-sm-4 fw-semibold col-form-label">Téléphone</label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" wire:model="telephone_recommande_par" placeholder="" class="form-control bordure text-wamsco w-100 @error('telephone_recommande_par') is-invalid @enderror" id="telephone_recommande_par">
                                                                        </div>
                                                                        <div class="d-flex justify-content-start">
                                                                            @error('telephone_recommande_par') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-1">
                                                                        <label for="source" class="col-sm-3 fw-semibold col-form-label">Source</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" wire:model="source" placeholder="Ex: Facebook" class="form-control bordure text-bleu w-100 @error('source') is-invalid @enderror" id="source">
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
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 px-0 pt-0 mb-1 rounded-bottom">
                                <div class="hauteur_ecran">
                                    <div class="d-flex align-items-center justify-content-between px-2 py-2 border-bottom">
                                        <div class="">
                                            <button class="btn btn-sm btn-bleu fw-bold" wire:click.prevent="" title="Cliquez pour modifier" data-toggle="tooltip"><i class="fa fa-check"></i> Activité</button>
                                            <button class="btn btn-sm btn-default fw-bold" wire:click.prevent="noter()" title="Cliquez pour ajouter une note" data-toggle="tooltip"><i class="fa fa-pencil"></i> Note</button>
                                        </div>
                                        <div>    
                                            {{-- <button class="btn btn-sm btn-default" wire:click.prevent="" title="Cliquez pour modifier" data-toggle="tooltip"><i class="fa fa-check"></i> Modifier</button>                                                                                                                   --}}
                                        </div>                                        
                                    </div> 
                                    @if($ouverture === 1)
                                        <div class="d-flexf align-items-center justify-content-between px-2 py-2">
                                            <div class="">
                                                <div class="row mb-1">
                                                    <label for="source" class="col-sm-1 fw-semibold col-form-label py-1">
                                                        <div class="position-relative bg-inherit rounded-3">
                                                            @if(auth()->user()->profil != null)  
                                                                <a href="detail_user?id={{auth()->user()->id}}&active=12&champ=1-1" wire:navigate>
                                                                    <img class="image_log object-fit-cover rounded-3 cursor-pointer" src="storage/{{auth()->user()->profil}}">
                                                                </a>
                                                            @else       
                                                                <a href="detail_user?id={{auth()->user()->id}}&active=12&champ=1-1" wire:navigate>
                                                                    <img class="image_log object-fit-cover rounded-3 cursor-pointer" src="storage/default/user_man.png"> 
                                                                </a>                    
                                                            @endif
                                                        </div>
                                                    </label>
                                                    <div class="col-sm-11">
                                                        <textarea rows="2" wire:model="note_interne" class="form-control text-bleu w-100 @error('note_interne') is-invalid @enderror" id="note_interne" placeholder="Enregistrer une note interne 😊"></textarea>
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('note_interne') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-end"> 
                                                <button class="btn btn-xs btn-secondary fw-bold" wire:click.prevent="publierNote()" title="Cliquez pour publier" data-toggle="tooltip"><i class="fa fa-check"></i> Publier</button>
                                                <button class="btn btn-xs btn-danger fw-bold" wire:click.prevent="onDataOuverture()" title="Cliquez pour fermer" data-toggle="tooltip"><i class="fa fa-close"></i></button>
                                            </div>
                                        </div> 
                                    @endif
                                    <div class="d-flex align-items-center justify-content-between px-2 pt-3">
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
                                                        <span><i class="fas fa-calendar-alt text-danger"></i> {{$logs->id}}</span> &nbsp; <span><i class="fas fa-university text-dangerg"></i> {{$logs->user_societe}}</span>&nbsp; 
                                                        {{-- tester si la phrase commence par "Note »" --}}
                                                        @if(Str::startsWith($logs->subject, 'Note »'))
                                                            <span wire:click.prevent="ModifNote({{$logs->id}})"><i class="fas fa-pencil text-muted pointer"></i></span>
                                                        @endif
                                                    </div>
                                                    <div class="text-bleu fw-semibold">
                                                        {{-- tester si la phrase commence par "Note »" / ensuite il va a la ligne si l'user va a la ligne au niveau de textarea --}}
                                                        @if(Str::startsWith($logs->subject, 'Note »'))
                                                            {!! '<span class="text-primary fw-normal white-space-preline">'.nl2br(e(Str::after($logs->subject, 'Note »'))).'</span>' !!}
                                                        @else
                                                            {!!$logs->subject!!}
                                                        @endif                                                        
                                                    </div>                                                   
                                                </div>                                                
                                            </div>
                                            @if($ouvrir === $logs->id)
                                                <div class="d-flexf align-items-center justify-content-between px-2 py-2">
                                                    <div class="row mb-1">
                                                        <div class="col-sm-11">
                                                            <textarea rows="2" wire:model="note_internes" class="form-control text-bleu w-100 @error('note_internes') is-invalid @enderror" id="note_internes" placeholder="Enregistrer une note interne 😊"></textarea>
                                                        </div>
                                                        <div class="d-flex justify-content-start">
                                                            @error('note_internes') <span class="text-danger">{{ $message }}</span> @enderror 
                                                        </div>
                                                    </div>
                                                    <div class="text-start"> 
                                                        <button class="btn btn-xs btn-secondary fw-bold" wire:click.prevent="modifierNote()" title="Cliquez pour publier" data-toggle="tooltip"><i class="fa fa-check"></i> Modifier</button>
                                                        <button class="btn btn-xs btn-danger fw-bold" wire:click.prevent="onDataOuvrir()" title="Cliquez pour fermer" data-toggle="tooltip"><i class="fa fa-close"></i></button>
                                                    </div>
                                                </div> 
                                            @endif
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
</div>




