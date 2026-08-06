<div id="appn" class="appn">
    <div class="login login-with-news-feed">    
        <div class="news-feed">
            <div class="news-image" style="background-image: url(storage/fond/background.jpg)"></div>
            <div class="news-caption">                
                <h2>Inscription sur la plateforme</h2>
                <h4 class="caption-title"><b>WamsCo</b> Cloud</h4>
                <p>Accès gratuit et immédiat, paiement possible ultérieurement.</p>
                {{-- <p>Application de gestion commerciale pour les PME, TPE etc...</p> --}}
            </div>
        </div>
        <div class="login-container" style="padding: 15px;">
            <div class="login-header mb-30px">
                <div class="brand">
                    <div class="d-flex align-items-center">
                        <span class="position_logo"><img class="logo_wamsco" src="assets/img/logo/logo-blanc.png"> </span>
                    </div>
                    <small>Votre réussite à portée de main</small>
                </div>                
            </div>
            <div class="login-content">
                @include('flash::message')
                <form action="#" method="GET" class="fs-13px">
                    <div class="form-floating mb-15px">
                        <input type="text" name="nom_utilisateur" wire:model.defer="nom_utilisateur" class="form-control h-45px fs-13px @error('nom_utilisateur') is-invalid @enderror" placeholder="Entrez votre email">
                        <label for="nom_utilisateur" class="d-flex align-items-center fs-13px text-gray-600">Votre nom*</label>
                        @error('nom_utilisateur')<div class="text-danger invalid-feedback">{{ $message }}</div>@enderror
                    </div>                    
                    <div class="form-floating mb-15px">
                        <input type="email" name="email" wire:model.defer="email" class="form-control h-45px fs-13px @error('email') is-invalid @enderror" placeholder="Entrez votre email">
                        <label for="email" class="d-flex align-items-center fs-13px text-gray-600">Votre email*</label>
                        @error('email')<div class="text-danger invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <div class="row gx-3">
                            <div class="col-md-6 mb-2 mb-md-0">
                                <div class="form-floating mb-1px">
                                    <input type="text" name="societe" wire:model.defer="societe" class="form-control h-45px fs-13px @error('societe') is-invalid @enderror" placeholder="Nom de votre commerce">
                                    <label for="societe" class="d-flex align-items-center fs-13px text-gray-600">Nom de votre commerce*</label>
                                    @error('societe')<div class="text-danger invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-1px">
                                    <input type="text" name="telephone" wire:model.defer="telephone" class="form-control h-45px fs-13px @error('telephone') is-invalid @enderror" placeholder="Votre Téléphone">
                                    <label for="telephone" class="d-flex align-items-center fs-13px text-gray-600">Votre Téléphone*</label>
                                    @error('telephone')<div class="text-danger invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="row gx-3">
                            <div class="col-md-6 mb-2 mb-md-0">
                                <div class="input-group mb-1px">
                                    <select wire:model.defer="pays" class="form-control form-select h-45px fs-13px @error('pays') is-invalid @enderror" name="country" id="country" data-msg="Veuillez sélectionner votre pays.">
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
                                    @error('pays')<div class="text-danger invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-1px">
                                    <input type="text" name="ville" wire:model.defer="ville" class="form-control h-45px fs-13px @error('ville') is-invalid @enderror" placeholder="Votre ville">
                                    <label for="ville" class="d-flex align-items-center fs-13px text-gray-600">Votre ville*</label>
                                    @error('ville')<div class="text-danger invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-2">
                        <div class="row gx-3">
                            <div class="col-md-6 mb-2 mb-md-0">
                                <div class="form-floating mb-1px">
                                    <input type="password" name="password" wire:model.defer="password" class="form-control h-45px fs-13px @error('password') is-invalid @enderror" placeholder="Entrez votre mot de passe">
                                    <label for="password" class="d-flex align-items-center fs-13px text-gray-600">Votre mot de passe*</label>
                                    @error('password')<div class="text-danger invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-1px">
                                    <input type="password" name="password_confirmation" wire:model.defer="password_confirmation" class="form-control h-45px fs-13px @error('password_confirmation') is-invalid @enderror" placeholder="Entrez votre mot de passe">
                                    <label for="password_confirmation" class="d-flex align-items-center fs-13px text-gray-600">Confirmer mot de passe*</label>
                                    @error('password_confirmation')<div class="text-danger invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-2">
                        <div class="row gx-3">
                            {{-- Independant --}}
                            <div class="col-md-12">
                                <div class="form-floating mb-1px">
                                    <div class="input-group mt-2">
                                        <label for="plano" class="col-md-12 mb-2 fs-5">Choisissez votre plan</label>
                                        <label for="Mensuel" class="col-md-12 mb-2">Plan : <span class="fw-bold text-info">Mensuel</span></label>
                                        <div class="col-md-12 pt-1 px-2" style="display: flex; gap:10px;">                                            
                                            <div class="form-check">
                                                <input type="radio" wire:model.defer="choix_plan" value="Essai Gratuit" name="flexRadioDefault" id="flexRadioDefault5" class="form-check-input @error('choix_plan') is-invalid @enderror">
                                                <label class="form-check-label" for="flexRadioDefault5">Essai Gratuit <span style="color: #ccffcc;">/ 14 Jours : <span class="text-white">0 FCFA</span></span></label>
                                            </div>
                                        </div>
                                        @error('choix_plan')<div class="text-danger invalid-feedback">{{ $message }}</div>@enderror      
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-floating mb-1px">
                                    <div class="input-group mt-2">
                                        <div class="col-md-12 pt-1 px-2" style="display: flex; gap:10px;">
                                            <div class="form-check mb-2">
                                                <input type="radio" wire:model.defer="choix_plan" value="Independant Mensuel" name="flexRadioDefault" id="flexRadioDefault3" class="form-check-input @error('choix_plan') is-invalid @enderror">
                                                <label class="form-check-label" for="flexRadioDefault3">Independant <span class="text-info">/ Mensuel : <span class="text-white">10 000 FCFA</span></span></label>
                                            </div>
                                        </div>
                                        @error('choix_plan')<div class="text-danger invalid-feedback">{{ $message }}</div>@enderror      
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-floating mb-1px">
                                    <div class="input-group mt-2">
                                        <div class="col-md-12 pt-1 px-2" style="display: flex; gap:10px;">
                                            <div class="form-check mb-2">
                                                <input type="radio" wire:model.defer="choix_plan" value="Standard Mensuel" name="flexRadioDefault" id="flexRadioDefault1" class="form-check-input @error('choix_plan') is-invalid @enderror">
                                                <label class="form-check-label" for="flexRadioDefault1">Standard <span class="text-info">/ Mensuel : <span class="text-white">20 000 FCFA</span></span></label>
                                            </div>
                                        </div>
                                        @error('choix_plan')<div class="text-danger invalid-feedback">{{ $message }}</div>@enderror      
                                    </div>
                                </div>
                            </div>
                            {{-- Fin Independant --}}
                            
                            {{-- Standard --}}
                            <div class="col-md-12 mb-2 mb-md-0">
                                <div class="form-floating mb-1px">
                                    <div class="input-group">
                                        <label for="Annuel" class="col-md-12 mb-2 mt-3">Plan : <span class="fw-bold text-success">Annuel</span></label>
                                        <div class="col-md-12 pt-1 px-2" style="display: flex; gap:10px;">
                                             <div class="form-check mb-2">
                                                <input type="radio" wire:model.defer="choix_plan" value="Independant Annuel" name="flexRadioDefault" id="flexRadioDefault4" class="form-check-input @error('choix_plan') is-invalid @enderror">
                                                <label class="form-check-label" for="flexRadioDefault4">Independant <span class="text-success">/ Annuel : <span class="text-white">100 000 FCFA</span></span> » (Economisez 2 mois)</label>
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                            </div>
                            <div class="col-md-12 mb-2 mb-md-0">
                                <div class="form-floating mb-1px">
                                    <div class="input-group">
                                        <div class="col-md-12 pt-1 px-2" style="display: flex; gap:10px;">                                  
                                            <div class="form-check mb-2">
                                                <input type="radio" wire:model.defer="choix_plan" value="Standard Annuel" name="flexRadioDefault" id="flexRadioDefault2" class="form-check-input @error('choix_plan') is-invalid @enderror">
                                                <label class="form-check-label" for="flexRadioDefault2">Standard <span class="text-success">/ Annuel : <span class="text-white">200 000 FCFA</span></span> » (Economisez 2 mois)</label>
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                            </div>
                            {{-- Fin Standard --}} 
                            
                            {{-- <div class="col-md-12 mb-2 mb-md-0">
                                <div class="form-floating mb-1px">
                                    <div class="input-group">
                                        <label for="duree" class="col-md-12">Choisissez votre plan</label>
                                        <div class="col-md-12 pt-1 px-2" style="display: flex; gap:10px;">
                                            <div class="form-check mb-2">
                                                <input type="radio" wire:model.defer="choix_plan" value="Standard Mensuel" name="flexRadioDefault" id="flexRadioDefault1" class="form-check-input @error('choix_plan') is-invalid @enderror">
                                                <label class="form-check-label" for="flexRadioDefault1">Standard <span class="text-white">/ Mensuel : 20 000 FCFA</span></label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input type="radio" wire:model.defer="choix_plan" value="Standard Annuel" name="flexRadioDefault" id="flexRadioDefault2" class="form-check-input @error('choix_plan') is-invalid @enderror">
                                                <label class="form-check-label" for="flexRadioDefault2">Standard <span class="text-white">/ Annuel : 200 000 FCFA</span></label>
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                            </div> --}}
                            {{-- <div class="col-md-12">
                                <div class="form-floating mb-1px">
                                    <div class="input-group mt-2">
                                        <div class="col-md-12 pt-1 px-2" style="display: flex; gap:10px;">                                            
                                            <div class="form-check mb-2">
                                                <input type="radio" wire:model.defer="choix_plan" value="Independant Mensuel" name="flexRadioDefault" id="flexRadioDefault3" class="form-check-input @error('choix_plan') is-invalid @enderror">
                                                <label class="form-check-label plan_pressing" for="flexRadioDefault3">Independant <span class="text-white">/ Mensuel : 10 000 FCFA</span></label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input type="radio" wire:model.defer="choix_plan" value="Independant Annuel" name="flexRadioDefault" id="flexRadioDefault4" class="form-check-input @error('choix_plan') is-invalid @enderror">
                                                <label class="form-check-label plan_pressing" for="flexRadioDefault4">Independant <span class="text-white">/ Annuel : 100 000 FCFA</span></label>
                                            </div>
                                        </div>
                                        @error('choix_plan')<div class="text-danger invalid-feedback">{{ $message }}</div>@enderror      
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    </div>  

                    <div class="form-floating mb-15px">
                        <input type="number" name="captcha" wire:model.defer="captcha" class="form-control h-45px fs-13px @error('captcha') is-invalid @enderror" placeholder="Entrez le resultat">
                        <label for="captcha" class="d-flex align-items-center fs-13px text-gray-600">Captcha* 5+4= ? (ecrivez le resultat)</label>
                        @error('captcha')<div class="text-danger invalid-feedback">{{ $message }}</div>@enderror
                    </div>  
                    <div class="mb-15px d-flex">
                        <button class="btn btn-danger d-block h-45px w-100 btn-lg fs-14px" wire:click.prevent="store()"><i class="fa fa-hand-point-right text-white"></i> Démarrer maintenant</button>
                        <div wire:loading.delay>        
                            <img src="storage/default/circle_loading.gif" width="64" height="64"  style="margin-left: 1%;position: relative;top: -8px;">
                        </div>
                    </div>
                    <div class="mb-2px pb-2px text-dark">
                        <div class="social-auth-links text-center mb-3">
                            <p>- Pages WamsCo -</p>
                            <a href="/connexion" class="btn btn-block btn-dark"><i class="fa fa-user mr-1"></i> Se connecter</a>
                            <a href="./" class="btn btn-block btn-dark"><i class="fa fa-home mr-1"></i> Accueil site</a>
                            <a href="https://www.facebook.com/wamsco/" target="_blank" class="btn btn-block btn-primary mt-2 mt-md-0"><i class="fab fa-facebook-square mr-2"></i> Facebook</a>
                        </div>
                    </div>
                    <hr class="bg-gray-600 opacity-2" />
                    <div class="text-gray-600 text-center  mb-0">Tous droits réservés <span class="wamsco_color">WamsCo</span> &copy; {{date('Y')}}</div>
                </form>
            </div>        
        </div>        
    </div>    
</div>
