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
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center justify-content-between gap-1">                                                       
                            <div class="d-flex align-items-center justify-content-between gap-1">                            
                                <button class="btn btn-sm btn-secondary" wire:click.prevent="update()" title="Cliquez pour créer" data-toggle="tooltip"><i class="fa fa-check"></i> Modifier</button>
                                <a href="/entrepot?active=4&champ=3-1&choix=2" wire:navigate class="btn btn-sm btn-danger" title="Cliquez pour fermer" data-toggle="tooltip"><i class="fa fa-close"></i> Fermer</a>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between gap-1">                                                       
                            <div class="d-flex align-items-center justify-content-between gap-1">                            
                                <a href="/entrepot?active=4&champ=3-1&choix=2" wire:navigate class="btn btn-sm btn-bleu d-none d-md-block ms-auto"><i class="fa fa-chevron-left"></i> Retour liste</a>
                                <button class="btn btn-sm btn-default new_color" wire:click.prevent="precedant({{$this->ids}})" title="Précédant" data-toggle="tooltip"><i class="fa fa-chevron-left"></i></button>
                                <a href="#" class="btn btn-sm btn-default new_color" wire:click.prevent="suivant({{$this->ids}})" title="Suivant" data-toggle="tooltip"><i class="fa fa-chevron-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body no_bordure border-top taille_ecran_trans">                        
                        <div class="container-fluid pt-0 px-0">
                            <div class="row mx-0 pt-0">
                                <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12 mb-1 border-end border-bottom rounded-bottom">
                                    <div class="hauteur_ecran">
                                        <div class="row pt-3 px-0">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="row mb-3">
                                                    {{-- <label for="nom" class="col-lg-2 col-md-5 col-sm-3 fw-bold fs-5 col-form-label">Nom</label> --}}
                                                    <div class="col-lg-10 col-md-10 col-sm-10">
                                                        <input type="text" wire:model="nom" placeholder="Nom de l'entrepôt" class="form-control bordure fs-3 w-100 px-0 @error('nom') is-invalid @enderror" id="nom">
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('nom') <span class="text-danger">{{$message}}</span> @enderror 
                                                    </div>
                                                </div>                                            
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <div class="row mb-1">
                                                    <label for="reference" class="col-lg-4 col-md-5 col-sm-3 fw-bold col-form-label">Référence</label>
                                                    <div class="col-lg-8 col-md-7 col-sm-9">
                                                        <input type="text" wire:model="reference" placeholder="Ex: MG-01" class="form-control bordure w-75 @error('reference') is-invalid @enderror" id="reference">
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('reference') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <label for="entrepot_parent" class="col-lg-4 col-md-5 col-sm-3 col-form-label">Ajouter dans</label>
                                                    <div class="col-lg-8 col-md-7 col-sm-9">                                                        
                                                        <select id="entrepot_parent" wire:model="entrepot_parent" class="form-control form-select bordure w-100 @error('entrepot_parent') is-invalid @enderror">
                                                            <option value=""></option>   
                                                            @if($entrepotTest > 0)
                                                                @foreach($entrepot as $entrepots)                                                                                                                                                                
                                                                    <option value="{{$entrepots->nom}}">{{$entrepots->nom}}</option>
                                                                @endforeach
                                                            @endif                                                                                                         
                                                        </select> 
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('entrepot_parent') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>                                                
                                                <div class="row mb-1">
                                                    <label for="inputPassword3" class="col-lg-4 col-md-5 col-sm-3 col-form-label">Ville</label>
                                                    <div class="col-lg-8 col-md-7 col-sm-9">
                                                        <input type="text" wire:model="ville" placeholder="Ex: Douala" class="form-control bordure w-75" id="inputPassword3">
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('ville') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <label for="pays" class="col-lg-4 col-md-5 col-sm-3 col-form-label">Pays</label>
                                                    <div class="col-lg-8 col-md-7 col-sm-9">                                                        
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
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">                                                
                                                <div class="row mb-1">
                                                    <label for="telephone" class="col-lg-4 col-md-5 col-sm-3 col-form-label">Téléphone</label>
                                                    <div class="col-lg-8 col-md-7 col-sm-9">
                                                        <input type="text" wire:model="telephone" placeholder="Ex: 600 000 000" class="form-control bordure w-75 @error('telephone') is-invalid @enderror" id="telephone">
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('telephone') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>
                                                
                                                <div class="row mb-1">
                                                    <label for="email" class="col-lg-4 col-md-5 col-sm-3 col-form-label">Email</label>
                                                    <div class="col-lg-8 col-md-7 col-sm-9">
                                                        <input type="text" wire:model="email" placeholder="Ex: email@wamsco-cloud.net" class="form-control bordure w-100" id="email">
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('email') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>                                            
                                                <div class="row mb-1">
                                                    <label for="adresse" class="col-lg-4 col-md-5 col-sm-3 col-form-label">Adresse</label>
                                                    <div class="col-lg-8 col-md-7 col-sm-9">
                                                        <input type="text" wire:model="adresse" placeholder="Ex: Rue joe 23" class="form-control bordure w-100" id="adresse">
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('adresse') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>                                                
                                                <div class="row mb-1">
                                                    <label for="code_postal" class="col-lg-4 col-md-5 col-sm-3 col-form-label">Code postal</label>
                                                    <div class="col-lg-8 col-md-7 col-sm-93">
                                                        <input type="text" wire:model="code_postal" placeholder="Ex: 0000" class="form-control bordure w-50" id="code_postal">
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('code_postal') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>                                                                            
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="row">
                                                    <label for="inputPassword3" class="col-lg-2 col-md-2 col-sm-3 fw-bold col-form-label">Etat</label>
                                                    <div class="col-lg-10 col-md-10 col-sm-9">
                                                        <div class="card-body">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" wire:model.live="etat" value="1" name="inlineRadioOptions" id="inlineRadio1" value="option1">
                                                                <label class="form-check-label" for="inlineRadio1">Activer</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" wire:model.live="etat" value="0" name="inlineRadioOptions" id="inlineRadio2" value="option2" checked="">
                                                                <label class="form-check-label" for="inlineRadio2">Désactiver</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('etat') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>
                                            </div>                                        
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 px-0">
                                                <div class="table-responsive mt-2">
                                                    <div class="table-responsive mt-1"> 
                                                        <ul class="nav nav-tabs border-0" role="tablist">
                                                            <li class="nav-item">
                                                                <a class="nav-link active" data-bs-toggle="tab" href="#home">Note</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" data-bs-toggle="tab" href="#autre">Autres</a>
                                                            </li>
                                                        </ul>
                                                        <div class="tab-content table-responsive border-topk px-3" style="border-top-style: dashed; border-top-width: 1px;border-top-color: #0e04043d;">
                                                            <div id="home" class="tab-pane active">
                                                                <div class="row mb-1">
                                                                    {{-- <label for="description" class="col-lg-4 col-md-5 col-sm-3 col-form-label">Description</label> --}}
                                                                    <div class="col-lg-12 col-md-12 col-sm-12">                                                        
                                                                        <textarea rows="6" wire:model="description" class="form-control bordure px-0" id="description" placeholder="Ajouter une description..."></textarea>
                                                                    </div>
                                                                    <div class="d-flex justify-content-start">
                                                                        @error('description') <span class="text-danger">{{ $message }}</span> @enderror 
                                                                    </div>
                                                                </div>                                                              
                                                            </div>
                                                            <div id="autre" class="tab-pane">          
                                                            </div>
                                                        </div>                    
                                                    </div>
                                                </div>
                                            </div>                                        
                                        </div>
                                    </div>                                    
                                </div>
                                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 px-0 pt-3 mb-1 rounded-bottom">
                                    <div class="hauteur_ecran">
                                        <div class="d-flex align-items-center justify-content-between px-2">
                                            <div class="">
                                                <h5 class="text-bleu"><i class="fas fa-calendar-alt" style=" color: #393b83;" title="Derniers événements liés"></i> @if($logCount > 1) Les {{$logCount}} derniers événements @else L'événement @endif</h5>
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
                    </div>                    
                </div>
            </div>
        </div>
    </div>    
</div>