<div wire:ignore.self class="modal fade" id="CreationClientModal" tabindex="-1" data-bs-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
        <div class="modal-header py-2" style="background-color: #56585d; color:#ffffff;">
            <h5 class="modal-title" id="exampleModalCenteredScrollableTitle"><i class="fa fa-user-plus text-vert"></i> Création client </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        {{-- Debut chargement --}}        
        <div wire:loading class="chargement_pos">
            <label for=""></label>
            <img src="storage/default/circle_loading.gif" width="64" height="64" style="background: #28282800; margin-left: -3px;">
        </div>
        {{-- Fin chargement --}} 
        <div class="modal-body py-0 px-0">
            <div class="card-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="row mb-1">
                                {{-- <label for="nom" class="col-lg-2 col-md-5 col-sm-3 fw-bold fs-5 col-form-label">Opportunité</label> --}}
                                <div class="col-lg-10 col-md-10 col-sm-10">
                                    <input type="text" wire:model="nom" placeholder="Nom du tiers" class="form-control bordure fs-3 w-100 px-0 @error('nom') is-invalid @enderror" id="nom">
                                </div>
                                <div class="d-flex justify-content-start">
                                    @error('nom') <span class="text-danger">{{$message}}</span> @enderror 
                                </div>
                            </div>                                            
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">                                                                                   
                            <div class="row mb-1">
                                <label for="raison_sociale" class="col-lg-4 col-md-5 col-sm-3 fw-bold col-form-label">Raison sociale</label>
                                <div class="col-lg-8 col-md-7 col-sm-9">
                                    <input type="text" wire:model="raison_sociale" placeholder="Ex: WamsCo Sarl" class="form-control bordure w-100 @error('raison_sociale') is-invalid @enderror" id="raison_sociale">
                                </div>
                                <div class="d-flex justify-content-start">
                                    @error('raison_sociale') <span class="text-danger">{{ $message }}</span> @enderror 
                                </div>
                            </div>
                            <div class="row mb-1">
                                <label for="type_tiers" class="col-lg-4 col-md-5 col-sm-3 fw-bold col-form-label">Type du tiers</label>
                                <div class="col-lg-8 col-md-7 col-sm-9">                                                        
                                    <select id="type_tiers" wire:model.live="type_tiers" class="form-control form-select bordure w-auto @error('type_tiers') is-invalid @enderror">
                                        <option value=""></option>
                                        <option value="Client">Client</option>
                                        <option value="Fournisseur">Fournisseur</option>                                       
                                        <option value="Prospect">Prospect</option>	
                                    </select> 
                                </div>
                                <div class="d-flex justify-content-start">
                                    @error('type_tiers') <span class="text-danger">{{ $message }}</span> @enderror 
                                </div>
                            </div>
                            <div class="row mb-1">
                                <label for="telephone" class="col-lg-4 col-md-5 col-sm-3 fw-bold col-form-label">Téléphone</label>
                                <div class="col-lg-8 col-md-7 col-sm-9">
                                    <input type="text" wire:model="telephone" placeholder="" class="form-control bordure w-100 @error('telephone') is-invalid @enderror" id="telephone">
                                </div>
                                <div class="d-flex justify-content-start">
                                    @error('telephone') <span class="text-danger">{{ $message }}</span> @enderror 
                                </div>
                            </div>  
                            <div class="row mb-1">
                                <label for="email" class="col-lg-3 col-md-5 col-sm-3 fw-bold col-form-label">E-mail</label>
                                <div class="col-lg-9 col-md-7 col-sm-9">
                                    <input type="email" wire:model="email" placeholder="Ex: email@wamsco-cloud.net" class="form-control bordure w-100 @error('email') is-invalid @enderror" id="email">
                                </div>
                                <div class="d-flex justify-content-start">
                                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror 
                                </div>
                            </div>
                            <div class="row mb-1">
                                <label for="inputPassword3" class="col-sm-3 col-form-label">Statut</label>
                                <div class="col-sm-9">
                                    <div class="card-body">
                                        <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" wire:model="statut" value="1" name="inlineRadioOptions" id="inlineRadio1" value="option1">
                                        <label class="form-check-label" for="inlineRadio1">Activer</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" wire:model="statut" value="0" name="inlineRadioOptions" id="inlineRadio2" value="option2" checked="">
                                        <label class="form-check-label" for="inlineRadio2">Désactiver</label>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-start">
                                        @error('statut') <span class="text-danger">{{ $message }}</span> @enderror 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="row mb-1">
                                <label for="sexe" class="col-sm-3 fw-bold col-form-label">Sexe</label>
                                <div class="col-sm-9">                                                        
                                    <select id="sexe" wire:model="sexe" class="form-control form-select bordure w-auto @error('sexe') is-invalid @enderror">
                                        <option value=""></option>
                                        <option value="Masculin">Masculin</option>												 
                                        <option value="Feminin">Feminin</option>
                                    </select> 
                                </div>
                                <div class="d-flex justify-content-start">
                                    @error('sexe') <span class="text-danger">{{ $message }}</span> @enderror 
                                </div>
                            </div>
                            <div class="row mb-1">
                                <label for="inputPassword3" class="col-lg-3 col-md-5 col-sm-3 col-form-label">Ville</label>
                                <div class="col-lg-9 col-md-7 col-sm-9">
                                    <input type="text" wire:model="ville" placeholder="Ex: Douala" class="form-control bordure w-75 @error('ville') is-invalid @enderror" id="inputPassword3">
                                </div>
                                <div class="d-flex justify-content-start">
                                    @error('ville') <span class="text-danger">{{ $message }}</span> @enderror 
                                </div>
                            </div>                              
                            <div class="row mb-1">
                                <label for="adresse" class="col-lg-3 col-md-5 col-sm-3 col-form-label">Adresse</label>
                                <div class="col-lg-9 col-md-7 col-sm-9">
                                    <input type="text" wire:model="adresse" placeholder="Ex: Rue joe 23" class="form-control bordure w-75 @error('adresse') is-invalid @enderror" id="adresse">
                                </div>
                                <div class="d-flex justify-content-start">
                                    @error('adresse') <span class="text-danger">{{ $message }}</span> @enderror 
                                </div>
                            </div>
                            <div class="row mb-1">
                                <label for="pays" class="col-lg-3 col-md-5 col-sm-3 col-form-label">Pays</label>
                                <div class="col-lg-9 col-md-7 col-sm-9">                                                        
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
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 px-0">
                            <div class="table-responsive mt-2">
                                <div class="table-responsive mt-1"> 
                                    <ul class="nav nav-tabs border-0" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#contacts">Autres informations</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#contact">Contact</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content table-responsive border-topk px-3" style="border-top-style: dashed; border-top-width: 1px;border-top-color: #0e04043d;">
                                        <div id="contacts" class="tab-pane">
                                            <div class="row mt-2">
                                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                    <div class="w_horizontal_separator mt-4 mb-3 text-bleu text-uppercase fw-bolder small">Autre Information</div>
                                                    <div class="row mb-1">
                                                        <label for="code_postal" class="col-lg-4 col-md-5 col-sm-4 col-form-label">Code postal</label>
                                                        <div class="col-lg-8 col-md-7 col-sm-8">
                                                            <input type="text" wire:model="code_postal" placeholder="Ex: 0000" class="form-control bordure w-50 @error('code_postal') is-invalid @enderror" id="code_postal">
                                                        </div>
                                                        <div class="d-flex justify-content-start">
                                                            @error('code_postal') <span class="text-danger">{{ $message }}</span> @enderror 
                                                        </div>
                                                    </div>
                                                    <div class="row mb-1">
                                                        <label for="site_web" class="col-lg-3 col-md-5 col-sm-3 col-form-label">Site web</label>
                                                        <div class="col-lg-9 col-md-7 col-sm-9">
                                                            <input type="text" wire:model="site_web" placeholder="Ex: www.wamsco-cloud.net" class="form-control bordure w-100 @error('site_web') is-invalid @enderror" id="site_web">
                                                        </div>
                                                        <div class="d-flex justify-content-start">
                                                            @error('site_web') <span class="text-danger">{{ $message }}</span> @enderror 
                                                        </div>
                                                    </div>                                                                       
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                    <div class="w_horizontal_separator mt-4 mb-3 text-bleu text-uppercase fw-bolder small">Information commerciale</div>
                                                    <div class="row mb-1">
                                                        <label for="commercial_charge" class="col-lg-5 col-md-5 col-sm-7 col-form-label">Affecter commercial</label>
                                                        <div class="col-lg-7 col-md-7 col-sm-7">                                                        
                                                            <select id="commercial_charge" wire:model="commercial_charge" class="form-control form-select bordure w-100 @error('commercial_charge') is-invalid @enderror">
                                                                <option value=""></option>
                                                                @foreach($utilisateur as $utilisateurs)
                                                                    <option value="{{$utilisateurs->name}}">{{$utilisateurs->name}} ({{$utilisateurs->type_user}})</option>
                                                                @endforeach
                                                            </select> 
                                                        </div>
                                                        <div class="d-flex justify-content-start">
                                                            @error('commercial_charge') <span class="text-danger">{{ $message }}</span> @enderror 
                                                        </div>
                                                    </div>                                                               
                                                </div>  
                                            </div>                                                                
                                        </div>
                                        <div id="contact" class="tab-pane active">          
                                        </div>
                                    </div>                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer py-1" style="background-color: #56585d; color:#ffffff;">
            <button type="submit" wire:click.prevent="creerTier()" class="btn btn-sm btn-secondary fw-bold"><i class="fa fa-check"></i> Valider</button>
            <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal"><i class="fa fa-close"></i> Fermer</button>
        </div>
    </div>
    </div>
</div>