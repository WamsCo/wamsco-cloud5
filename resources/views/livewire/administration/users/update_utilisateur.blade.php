<div wire:ignore.self class="modal fade" id="updateUserModal" tabindex="-1" data-bs-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
        <div class="modal-header py-2" style="background-color: #56585d; color:#ffffff;">
        <h5 class="modal-title" id="exampleModalCenteredScrollableTitle"> Modification {{$this->society}} » <span class="text-vert">{{$this->nom}}</span> </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body py-0 px-0">
            <div class="card-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="row mb-1">                                
                                {{-- <label for="email" class="col-lg-2 col-md-5 col-sm-3 fw-bold fs-5 col-form-label">Opportunité</label> --}}
                                <div class="col-lg-10 col-md-10 col-sm-10">
                                    <input type="text" wire:model="email" placeholder="Email » par ex: contact@wamsco-cloud.net" readonly style="background-color: #ffffff;" class="form-control bordure fs-3 w-100 px-0 @error('email') is-invalid @enderror" id="email">
                                </div>
                                <label for="email" class="col-lg-2 col-md-2 col-sm-2 fw-semibold fs-6 col-form-label d-flex align-items-center"><span class="fw-semibold">@if($this->confirmerEmail == 1) <span class="badge bg-success"><i class="fa-solid fa-thumbs-up"></i> Confirmé</span> @else <span class="badge bg-danger blink"><i class="fa-solid fa-thumbs-down"></i> Non-confirmé</span> @endif</span></label>
                                <div class="d-flex justify-content-start">
                                    @error('email') <span class="text-danger">{{$message}}</span> @enderror 
                                </div>
                            </div>                                            
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="row mb-1">
                                <label for="titre" class="col-lg-2 col-md-2 col-sm-2 fw-bold col-form-label">Titre</label>
                                <div class="col-lg-10 col-md-10 col-sm-10">                                                        
                                    <select id="titre" wire:model="titre" class="form-control form-select bordure w-auto @error('titre') is-invalid @enderror">
                                        <option value=""></option>
                                        <option value="M">M</option>
                                        <option value="Mme">Mme</option>
                                        <option value="Dr">Dr</option>
                                        <option value="Pr">Pr</option>
                                    </select> 
                                </div>
                                <div class="d-flex justify-content-start">
                                    @error('titre') <span class="text-danger">{{ $message }}</span> @enderror 
                                </div>
                            </div>                                                                                   
                            <div class="row mb-1">
                                <label for="nom" class="col-lg-3 col-md-3 col-sm-3 fw-bold col-form-label">Nom</label>
                                <div class="col-lg-9 col-md-9 col-sm-9">
                                    <input type="text" wire:model="nom" placeholder="Ex: John Doe" class="form-control bordure w-100 @error('nom') is-invalid @enderror" id="nom">
                                </div>
                                <div class="d-flex justify-content-start">
                                    @error('nom') <span class="text-danger">{{ $message }}</span> @enderror 
                                </div>
                            </div>
                            <div class="row mb-1">
                                <label for="telephone" class="col-lg-3 col-md-3 col-sm-3 fw-bold col-form-label">Téléphone</label>
                                <div class="col-lg-9 col-md-9 col-sm-9">
                                    <input type="text" wire:model="telephone" placeholder="" class="form-control bordure w-75 @error('telephone') is-invalid @enderror" id="telephone">
                                </div>
                                <div class="d-flex justify-content-start">
                                    @error('telephone') <span class="text-danger">{{ $message }}</span> @enderror 
                                </div>
                            </div>
                            <div class="row mb-1">
                                <label for="password_actuel" class="col-lg-5 col-md-5 col-sm-4 fw-bold col-form-label">Mot de passe actuel</label>
                                <div class="col-lg-7 col-md-7 col-sm-8">
                                    <input type="password" wire:model="password_actuel" placeholder="Mot de passe actuel" style=" color: #393b83;" class="form-control fw-semibold bordure w-100 @error('password_actuel') is-invalid @enderror" id="password_actuel">
                                </div>
                                <div class="d-flex justify-content-start">
                                    @error('password_actuel') <span class="text-danger">{{ $message }}</span> @enderror 
                                </div>
                            </div> 
                            <div class="row mb-1">
                                <label for="password" class="col-lg-4 col-md-4 col-sm-3 fw-bold col-form-label">Mot de passe</label>
                                <div class="col-lg-8 col-md-8 col-sm-9">
                                    <input type="password" wire:model="password" placeholder="Mot de passe" style=" color: #393b83;" class="form-control fw-semibold bordure w-100 @error('password') is-invalid @enderror" id="password">
                                </div>
                                <div class="d-flex justify-content-start">
                                    @error('password') <span class="text-danger">{{ $message }}</span> @enderror 
                                </div>
                            </div>                                        
                            <div class="row mb-1">
                                <label for="password_confirmation" class="col-lg-4 col-md-4 col-sm-3 fw-bold col-form-label">Confirmer passe</label>
                                <div class="col-lg-8 col-md-8 col-sm-9">
                                    <input type="password" wire:model="password_confirmation" placeholder="Confirmer le mot de passe" class="form-control bordure w-100 @error('password_confirmation') is-invalid @enderror" id="password_confirmation">
                                </div>
                                <div class="d-flex justify-content-start">
                                    @error('password_confirmation') <span class="text-danger">{{ $message }}</span> @enderror 
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="row mb-1">
                                <label for="sexe" class="col-lg-3 col-md-3 col-sm-3 fw-bold col-form-label">Sexe</label>
                                <div class="col-lg-9 col-md-9 col-sm-9">                                                        
                                    <select id="sexe" wire:model="sexe" class="form-control form-select bordure w-50 @error('sexe') is-invalid @enderror">
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
                                <label for="nationalite" class="col-lg-4 col-md-4 col-sm-3 fw-bold col-form-label">Nationalité (pays)</label>
                                <div class="col-lg-8 col-md-8 col-sm-9">                                                        
                                    <select name="nationalite" wire:model.defer="nationalite" class="form-control form-select bordure w-75 @error('nationalite') is-invalid @enderror" style="color: #555555; font-weight: 600;">
                                        <option value="">Choix pays</option>	
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
                                    @error('nationalite') <span class="text-danger">{{ $message }}</span> @enderror 
                                </div>
                            </div>
                            @if(auth()->user()->societe == "Administration")
                                <div class="row mb-1">
                                    <label for="societe" class="col-lg-3 col-md-3 col-sm-3 fw-bold col-form-label">Société</label>
                                    <div class="col-lg-9 col-md-9 col-sm-9">                                                        
                                        <select id="societe" wire:model.live="societe" class="form-control form-select bordure w-75 @error('societe') is-invalid @enderror">
                                            <option value=""></option>
                                            @foreach ($entite as $entites )
                                                <option value="{{$entites->id}}">{{$entites->enseigne}}</option> 
                                            @endforeach 
                                        </select> 
                                    </div>
                                    <div class="d-flex justify-content-start">
                                        @error('societe') <span class="text-danger">{{ $message }}</span> @enderror 
                                    </div>
                                </div>
                            @endif
                            <div class="row mb-1">
                                <label for="role" class="col-lg-3 col-md-3 col-sm-3 fw-bold col-form-label">Rôle</label>
                                <div class="col-lg-9 col-md-9 col-sm-9">                                                        
                                    <select id="role" wire:model="role" class="form-control form-select bordure w-75 @error('role') is-invalid @enderror">
                                        {{-- <option value=""></option> --}}
                                        <option value="{{$role}}">{{$role}}</option>
                                        @foreach($privillege as $privilleges)
                                            @if($role != $privilleges->nom)                                                                                  
                                                <option value="{{$privilleges->nom}}"><span class="text-danger">{{$privilleges->nom}} » {{Str::limit($privilleges->description,22)}}</span></option>
                                            @endif																												      
                                        @endforeach
                                    </select> 
                                </div>
                                <div class="d-flex justify-content-start">
                                    @error('role') <span class="text-danger">{{ $message }}</span> @enderror 
                                </div>
                            </div>  
                            <div class="row mb-1">
                                <label for="date_valide" class="col-lg-3 col-md-3 col-sm-3 fw-bold col-form-label">Validité</label>
                                <div class="col-lg-9 col-md-9 col-sm-9">
                                    <input type="date" wire:model="date_valide" class="form-control bordure w-50 @error('date_valide') is-invalid @enderror" id="date_valide">
                                </div>
                                <div class="d-flex justify-content-start">
                                    @error('date_valide') <span class="text-danger">{{ $message }}</span> @enderror 
                                </div>
                            </div>
                            <div class="row mb-1">
                                <label for="matricule" class="col-lg-3 col-md-3 col-sm-3 fw-bold col-form-label">Matricule</label>
                                <div class="col-lg-9 col-md-9 col-sm-9">
                                    <input type="text" wire:model="matricule" placeholder="Ex: M-A250420-1552" style="color: #ff332e;" class="form-control bordure w-auto @error('matricule') is-invalid @enderror" id="matricule">
                                </div>
                                <div class="d-flex justify-content-start">
                                    @error('matricule') <span class="text-danger">{{ $message }}</span> @enderror 
                                </div>
                            </div>                                                                                         
                        </div>                                        
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="row mb-1">
                                <label for="salarie" class="col-sm-3 fw-bold col-form-label">Salarié</label>
                                <div class="col-sm-9">
                                    <div class="card-body">
                                        <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" wire:model="salarie" value="1" name="inlineRadioOptions1" id="inlineRadio1">
                                        <label class="form-check-label" for="inlineRadio1">Oui</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" wire:model="salarie" value="0" name="inlineRadioOptions2" id="inlineRadio2" checked="">
                                        <label class="form-check-label" for="inlineRadio2">Non</label>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-start">
                                        @error('salarie') <span class="text-danger">{{ $message }}</span> @enderror 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="row mb-1">
                                <label for="etat" class="col-sm-3 fw-bold col-form-label">Etat</label> 
                                <div class="col-sm-9">
                                    <div class="card-body">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" wire:model="etat" value="1" name="inlineRadioOptions5" id="inlineRadio3">
                                            <label class="form-check-label" for="inlineRadio3">Activer</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" wire:model="etat" value="0" name="inlineRadioOptions6" id="inlineRadio4" checked="">
                                            <label class="form-check-label" for="inlineRadio4">Désactiver</label>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-start">
                                        @error('etat') <span class="text-danger">{{ $message }}</span> @enderror 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                            <div class="table-responsive mt-2">
                                <ul class="nav nav-tabs border-0" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#personnel">Personnel</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#travail">Travail</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#contrat">Contrat </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#paie">Paie</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#cnps">CNPS</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#note">Note</a>
                                    </li>
                                </ul>
                                <div class="tab-content table-responsive border-topk px-3" style="border-top-style: dashed; border-top-width: 1px;border-top-color: #0e04043d;">
                                    <div id="personnel" class="tab-pane">
                                        <div class="row mt-2 mb-2">
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <div class="w_horizontal_separator mt-1 mb-3 text-bleu text-uppercase fw-bolder small w-75">Informations Personnels</div>                                                
                                                <div class="row mb-1">
                                                    <label for="cni" class="col-lg-3 col-md-3 col-sm-3 col-form-label">N° CNI</label>
                                                    <div class="col-lg-9 col-md-9 col-sm-9">
                                                        <input type="text" wire:model="cni" placeholder="Par ex: 109092000" class="form-control bordure w-75 @error('cni') is-invalid @enderror" id="cni">
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('cni') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <label for="passeport" class="col-lg-4 col-md-4 col-sm-4 col-form-label">N° Passeport</label>
                                                    <div class="col-lg-8 col-md-8 col-sm-8">
                                                        <input type="text" wire:model="passeport" placeholder="Par ex: 1898979790" class="form-control bordure w-75 @error('passeport') is-invalid @enderror" id="passeport">
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('passeport') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div> 
                                                <div class="row mb-1">
                                                    <label for="niu" class="col-sm-3 col-form-label">N.I.U</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" wire:model="niu" placeholder="" class="form-control bordure w-auto @error('niu') is-invalid @enderror" id="niu">
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('niu') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>                                                
                                                <div class="row mb-1">
                                                    <label for="date_naissance" class="col-lg-5 col-md-5 col-sm-5 col-form-label">Date de naissance</label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7">
                                                        <input type="date" wire:model="date_naissance" class="form-control bordure w-auto @error('date_naissance') is-invalid @enderror" id="date_naissance">
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('date_naissance') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <label for="lieu_naissance" class="col-lg-4 col-md-4 col-sm-4 col-form-label">Lieu de naissance</label>
                                                    <div class="col-lg-8 col-md-8 col-sm-8">
                                                        <input type="text" wire:model="lieu_naissance" placeholder="Ex: Douala" class="form-control bordure w-100 @error('lieu_naissance') is-invalid @enderror" id="lieu_naissance">
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('lieu_naissance') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <label for="etat_civil" class="col-lg-3 col-md-3 col-sm-3 col-form-label">État civil</label>
                                                    <div class="col-lg-9 col-md-9 col-sm-9">                                                        
                                                        <select id="etat_civil" wire:model="etat_civil" class="form-control form-select bordure w-auto @error('etat_civil') is-invalid @enderror">
                                                            <option value=""></option>	
                                                            <option value="Célibataire">Célibataire</option>	
                                                            <option value="Marié">Marié</option>
                                                            <option value="Veuf">Veuf</option>
                                                            <option value="Divorcé">Divorcé(e)</option>                                                    
                                                        </select> 
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('etat_civil') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <label for="nbre_enfant" class="col-lg-6 col-md-6 col-sm-6 col-form-label">Nombre d'enfants à charge</label>
                                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                                        <input type="number" wire:model="nbre_enfant" placeholder="Ex: 02" min="0" class="form-control bordure w-75 @error('nbre_enfant') is-invalid @enderror" id="nbre_enfant">
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('nbre_enfant') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>                                                                                                                                                                                                                                                               
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <div class="w_horizontal_separator mt-1 mb-3 text-bleu text-uppercase fw-bolder small w-25">Autre</div>
                                                <div class="row mb-1">
                                                    <label for="nom_conjoint" class="col-sm-5 col-md-5 col-sm-5 col-form-label">Nom du conjoint(e)</label>
                                                    <div class="col-sm-7 col-md-7 col-sm-7">
                                                        <input type="text" wire:model="nom_conjoint" placeholder="Ex: Marie tams" class="form-control bordure w-75 @error('nom_conjoint') is-invalid @enderror" id="nom_conjoint">
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('nom_conjoint') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <label for="date_nais_conjoint" class="col-sm-6 col-md-6 col-sm-5 col-form-label">Date naissance conjoint(e)</label>
                                                    <div class="col-sm-6 col-md-6 col-sm-7">
                                                        <input type="date" wire:model="date_nais_conjoint"  class="form-control bordure w-75 @error('date_nais_conjoint') is-invalid @enderror" id="date_nais_conjoint">
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('date_nais_conjoint') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <label for="persone_contact_urgence" class="col-lg-6 col-md-6 col-sm-6 col-form-label">A contacter (En urgence)</label>
                                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                                        <input type="text" wire:model="persone_contact_urgence"  class="form-control bordure w-100 @error('persone_contact_urgence') is-invalid @enderror" id="persone_contact_urgence">
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('persone_contact_urgence') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <label for="telephone_urgence" class="col-sm-5 col-md-5 col-sm-5 col-form-label">Téléphone (Urgence)</label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7">
                                                        <input type="text" wire:model="telephone_urgence"  class="form-control bordure w-100 @error('telephone_urgence') is-invalid @enderror" id="telephone_urgence">
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('telephone_urgence') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>                                                        
                                            </div>  
                                        </div>                                                             
                                    </div>
                                    <div id="travail" class="tab-pane">
                                        <div class="row mt-2 mb-2">
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <div class="w_horizontal_separator mt-1 mb-3 text-bleu text-uppercase fw-bolder small w-50">Travail</div>                                                
                                                <div class="row mb-1">
                                                    <label for="departement" class="col-lg-4 col-md-4 col-sm-4 col-form-label">Département</label>
                                                    <div class="col-lg-8 col-md-8 col-sm-8">                                                        
                                                        <select id="departement" wire:model="departement" class="form-control form-select bordure w-75 @error('departement') is-invalid @enderror">
                                                            <option value=""></option>
                                                            @foreach($departe as $departes)
                                                                <option value="{{$departes->id}}">{{$departes->nom_departement}}</option>
                                                            @endforeach
                                                        </select> 
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('departement') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <label for="poste_travail" class="col-lg-4 col-md-4 col-sm-4 col-form-label">Poste de travail</label>
                                                    <div class="col-lg-8 col-md-8 col-sm-8">                                                        
                                                        <select id="poste_travail" wire:model="poste_travail" class="form-control form-select bordure w-75 @error('poste_travail') is-invalid @enderror">
                                                            <option value=""></option>
                                                            @foreach($posteTravail as $posteTravails)
                                                                <option value="{{$posteTravails->id}}">{{$posteTravails->nom_poste}}</option>
                                                            @endforeach
                                                        </select> 
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('poste_travail') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>                                         
                                                <div class="row mb-1">
                                                    <label for="lieu_travail" class="col-sm-5 col-md-5 col-sm-5 col-form-label">Lieu de travail</label>
                                                    <div class="col-sm-7 col-md-7 col-sm-7">
                                                        <input type="text" wire:model="lieu_travail" placeholder="Ex: Yaounde" class="form-control bordure w-100 @error('lieu_travail') is-invalid @enderror" id="lieu_travail">
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('lieu_travail') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <label for="adresse_travail" class="col-sm-5 col-md-5 col-sm-5 col-form-label">Adresse de travail</label>
                                                    <div class="col-sm-7 col-md-7 col-sm-7">
                                                        <input type="text" wire:model="adresse_travail" placeholder="Ex: Rue po" class="form-control bordure w-100 @error('adresse_travail') is-invalid @enderror" id="adresse_travail">
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('adresse_travail') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>                                                                                                                                                                                                                                                       
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <div class="w_horizontal_separator mt-1 mb-3 text-bleu text-uppercase fw-bolder small w-50">Valideur</div>                                                
                                                <div class="row mb-1">
                                                    <label for="responsable_rh" class="col-lg-4 col-md-4 col-sm-4 col-form-label">Responsable RH</label>
                                                    <div class="col-lg-8 col-md-8 col-sm-8">                                                        
                                                        <select id="responsable_rh" wire:model="responsable_rh" class="form-control form-select bordure w-100 @error('responsable_rh') is-invalid @enderror">
                                                            <option value=""></option> 
                                                            @foreach($utilisa as $utilisas)
                                                                <option value="{{$utilisas->name}}">{{$utilisas->name}}</option>
                                                            @endforeach                                                   
                                                        </select> 
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('responsable_rh') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <label for="manager" class="col-lg-4 col-md-4 col-sm-4 col-form-label">Manager</label>
                                                    <div class="col-lg-8 col-md-8 col-sm-8">                                                        
                                                        <select id="manager" wire:model="manager" class="form-control form-select bordure w-100 @error('manager') is-invalid @enderror">
                                                            <option value=""></option>
                                                            @foreach($utilisa as $utilisas)
                                                                <option value="{{$utilisas->name}}">{{$utilisas->name}}</option>
                                                            @endforeach
                                                        </select> 
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('manager') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <label for="validateur_conges" class="col-lg-5 col-md-5 col-sm-5 col-form-label">Validateur congés</label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7">                                                        
                                                        <select id="validateur_conges" wire:model="validateur_conges" class="form-control form-select bordure w-100 @error('validateur_conges') is-invalid @enderror">
                                                            <option value=""></option>
                                                            @foreach($utilisa as $utilisas)
                                                                <option value="{{$utilisas->name}}">{{$utilisas->name}}</option>
                                                            @endforeach                                                            
                                                        </select> 
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('validateur_conges') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>                                                                                                                                                                                                                                                      
                                            </div>
                                        </div>                                                                  
                                    </div>
                                    <div id="contrat" class="tab-pane active">
                                        <div class="row mt-2 mb-2">
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <div class="w_horizontal_separator mt-1 mb-3 text-bleu text-uppercase fw-bolder small w-50">Aperçu du contrat</div>
                                                <div class="row mb-1">
                                                    <label for="type_contrat" class="col-lg-4 col-md-4 col-sm-4 fw-bold col-form-label">Type de contrat</label>
                                                    <div class="col-lg-8 col-md-8 col-sm-8">                                                        
                                                        <select id="type_contrat" wire:model="type_contrat" class="form-control form-select text-bleu bordure w-75 @error('type_contrat') is-invalid @enderror">
                                                            <option value=""></option>
                                                            <option value="CDI">CDI</option>
                                                            <option value="CDD">CDD</option>                                                                                                                                 
                                                            <option value="Contrat d’intérim">Contrat d’intérim</option>                                                                                                                                 
                                                            <option value="Contrat d’apprentissage">Contrat d’apprentissage</option>                                                                                                                                 
                                                            <option value="Contrat à temps partiel">Contrat à temps partiel</option>                                                                                                                                 
                                                            <option value="Contrat saisonnier">Contrat saisonnier</option>                                                                                                                                 
                                                        </select> 
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('type_contrat') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <label for="type_employe" class="col-lg-4 col-md-4 col-sm-4 fw-bold col-form-label">Type d'employé</label>
                                                    <div class="col-lg-8 col-md-8 col-sm-8">                                                        
                                                        <select id="type_employe" wire:model="type_employe" class="form-control form-select bordure w-75 @error('type_employe') is-invalid @enderror">
                                                            <option value=""></option>
                                                            <option value="Salarié">Salarié</option>
                                                            <option value="Stagiaire">Stagiaire</option>
                                                            <option value="Contractant">Contractant</option>
                                                            <option value="Freelance">Freelance</option>                                                   
                                                        </select> 
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('type_employe') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <label for="type_salaire" class="col-lg-4 col-md-4 col-sm-4 fw-bold col-form-label">Type de salaire</label>
                                                    <div class="col-lg-8 col-md-8 col-sm-8">                                                        
                                                        <select id="type_salaire" wire:model="type_salaire" class="form-control form-select bordure w-75 @error('type_salaire') is-invalid @enderror">
                                                            <option value=""></option>
                                                            <option value="Salaire Horaire">Salaire Horaire</option>
                                                            <option value="Salaire Mensuel">Salaire Mensuel</option>
                                                            <option value="Autres">Autres</option>                                                
                                                        </select> 
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('type_salaire') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <label for="date_debut_contrat" class="col-lg-5 col-md-5 col-sm-5 fw-bold col-form-label">Date début du contrat</label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7">
                                                        <input type="date" wire:model="date_debut_contrat"  class="form-control bordure w-auto @error('date_debut_contrat') is-invalid @enderror" id="date_debut_contrat">
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('date_debut_contrat') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div> 
                                                <div class="row mb-1">
                                                    <label for="date_fin_contrat" class="col-lg-5 col-md-5 col-sm-5 col-form-label">Date fin du contrat</label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7">
                                                        <input type="date" wire:model="date_fin_contrat"  class="form-control bordure w-auto @error('date_fin_contrat') is-invalid @enderror" id="date_fin_contrat">
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('date_fin_contrat') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>                                                                                                                                                                                                                                                                                                   
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <div class="w_horizontal_separator mt-1 mb-3 text-bleu text-uppercase fw-bolder small w-50">Horaire</div>                                            
                                                <div class="row mb-1">
                                                    <label for="horaire_journalier" class="col-sm-5 col-md-5 col-sm-5 fw-bold col-form-label">Horaire journalier</label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7">
                                                        <input type="number" wire:model.live="horaire_journalier" placeholder="Ex: 8h" style=" color:#ff5200;" class="form-control bordure w-50 @error('horaire_journalier') is-invalid @enderror" id="horaire_journalier">
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('horaire_journalier') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <label for="horaire_hebdo" class="col-lg-6 col-md-6 col-sm-6 fw-bold col-form-label">Horaire Hebdomadaire</label>
                                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                                        <input type="text" wire:model="horaire_hebdo" placeholder="Ex: 40h" readonly style="background-color: #ffffff; color:#ff5200;" class="form-control bordure w-50 @error('horaire_hebdo') is-invalid @enderror" id="horaire_hebdo">
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('horaire_hebdo') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <label for="horaire_mensuel" class="col-lg-4 col-md-4 col-sm-4 fw-bold col-form-label">Horaire Mensuel</label>
                                                    <div class="col-lg-8 col-md-8 col-sm-8">
                                                        <input type="text" wire:model="horaire_mensuel" placeholder="Ex: 173h" readonly style="background-color: #ffffff; color:#ff5200;" class="form-control bordure w-50 @error('horaire_mensuel') is-invalid @enderror" id="horaire_mensuel">
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('horaire_mensuel') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>                                                                                                                                                                                                                                                
                                            </div>                                                                                                        
                                        </div>                                                                                                        
                                    </div>
                                    <div id="paie" class="tab-pane">
                                        <div class="row mt-2 mb-2">
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <div class="w_horizontal_separator mt-1 mb-3 text-bleu text-uppercase fw-bolder small w-50">Aperçu paie</div>
                                                <div class="row mb-1">
                                                    <label for="salaire" class="col-lg-4 col-md-4 col-sm-4 col-form-label">Salaire Mensuel</label>
                                                    <div class="col-lg-8 col-md-8 col-sm-8">
                                                        <input type="number" wire:model="salaire" placeholder="Ex: 100 000" class="form-control bordure w-auto @error('salaire') is-invalid @enderror" id="salaire">
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('salaire') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div> 
                                                <div class="row mb-1">
                                                    <label for="categorie" class="col-lg-3 col-md-3 col-sm-3 col-form-label">Catégorie</label>
                                                    <div class="col-lg-9 col-md-9 col-sm-9">                                                        
                                                        <select id="categorie" wire:model="categorie" class="form-control form-select bordure w-auto @error('categorie') is-invalid @enderror">
                                                            <option value=""></option>
                                                            @for($i = 1; $i <= 12; $i += 1)
                                                                <option value="{{ $i }}">{{ $i }}</option>
                                                            @endfor                                                     
                                                        </select> 
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('categorie') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <label for="echelon" class="col-lg-3 col-md-3 col-sm-3 col-form-label">Echelon</label>
                                                    <div class="col-lg-9 col-md-9 col-sm-9">                                                        
                                                        <select id="echelon" wire:model="echelon" class="form-control form-select bordure w-auto @error('echelon') is-invalid @enderror">
                                                            <option value=""></option>                                                                   
                                                            <option value="A">A</option>                                                                   
                                                            <option value="B">B</option>                                                                   
                                                            <option value="C">C</option>                                                                   
                                                            <option value="D">D</option>                                                                   
                                                            <option value="E">E</option>                                                                   
                                                            <option value="F">F</option>                                                                   
                                                            <option value="G">G</option>                                
                                                        </select> 
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('echelon') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>                                                                                                                                                                                                                                                                                                                                           
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <div class="w_horizontal_separator mt-1 mb-3 text-bleu text-uppercase fw-bolder small w-50">Paimenent</div>                                            
                                                <div class="row mb-1">
                                                    <label for="mode_paiement" class="col-lg-5 col-md-5 col-sm-5 col-form-label">Mode de paimenent</label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7">                                                        
                                                        <select id="mode_paiement" wire:model="mode_paiement" class="form-control form-select bordure w-auto @error('mode_paiement') is-invalid @enderror">
                                                            <option value=""></option>
                                                            <option value="Chèque">Chèque</option>                                        
                                                            <option value="Espèces">Espèces</option>                                        
                                                            <option value="Orange-Money">Orange-Money</option>                                        
                                                            <option value="MTN-Mobil-Money">MTN-Mobil-Money</option>                                        
                                                            <option value="Virement-Bancaire">Virement-Bancaire</option>                                                    
                                                        </select> 
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('mode_paiement') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <label for="nom_banque" class="col-lg-5 col-md-5 col-sm-5 col-form-label">Nom de la banque</label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7">
                                                        <input type="text" wire:model="nom_banque" placeholder="Ex: Bicec" class="form-control bordure w-auto @error('nom_banque') is-invalid @enderror" id="nom_banque">
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('nom_banque') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div> 
                                                <div class="row mb-1">
                                                    <label for="numero_compte" class="col-lg-5 col-md-5 col-sm-5 col-form-label">Numero de compte</label>
                                                    <div class="col-lg-7 col-md-7 col-sm-7">
                                                        <input type="text" wire:model="numero_compte" placeholder="Ex: 1123458P" class="form-control bordure w-auto @error('numero_compte') is-invalid @enderror" id="numero_compte">
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('numero_compte') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div> 
                                                <div class="row mb-1">
                                                    <label for="rib" class="col-lg-3 col-md-3 col-sm-3 col-form-label">R.I.B</label>
                                                    <div class="col-lg-9 col-md-9 col-sm-9">
                                                        <input type="text" wire:model="rib" placeholder="" class="form-control bordure w-auto @error('rib') is-invalid @enderror" id="rib">
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('rib') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>                                                 
                                            </div>                                                                                                                                                                                                                                         
                                        </div>                                                                                                        
                                    </div> 
                                    <div id="cnps" class="tab-pane">
                                        <div class="row mt-2 mb-2">
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <div class="w_horizontal_separator mt-1 mb-3 text-bleu text-uppercase fw-bolder small w-25">CNPS</div>
                                                <div class="row mb-1">
                                                    <label for="cnps" class="col-lg-3 col-md-3 col-sm-3 col-form-label">N° CNPS</label>
                                                    <div class="col-lg-9 col-md-9 col-sm-9">
                                                        <input type="text" wire:model="cnps" placeholder="" class="form-control bordure w-auto @error('cnps') is-invalid @enderror" id="cnps">
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('cnps') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <div class="w_horizontal_separator mt-1 mb-3 text-bleu text-uppercase fw-bolder small w-25">DIPE</div>                                                
                                                <div class="row mb-1">
                                                    <label for="dipe" class="col-lg-3 col-md-3 col-sm-3 col-form-label">DIPE</label>
                                                    <div class="col-lg-9 col-md-9 col-sm-9">
                                                        <input type="text" wire:model="dipe" placeholder="" class="form-control bordure w-auto @error('dipe') is-invalid @enderror" id="dipe">
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('dipe') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                                                                  
                                    </div>
                                    <div id="note" class="tab-pane">
                                            <div class="row mt-2 mb-2">
                                            <div class="col-lg-12 col-md-12 col-sm-12">                                                        
                                                <textarea rows="5" wire:model="note_interne" class="form-control px-0 bordure @error('note_interne') is-invalid @enderror" id="note_interne" placeholder="Ajouter une note..."></textarea>
                                            </div>
                                            <div class="d-flex justify-content-start">
                                                @error('note_interne') <span class="text-danger">{{ $message }}</span> @enderror 
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
        <div class="modal-footer py-1" style="background-color: #56585d; color:#ffffff;">
            <button type="submit" wire:click.prevent="update()" class="btn btn-sm btn-secondary fw-bold"><i class="fa fa-check"></i> Modifier</button>
            <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal"><i class="fa fa-close"></i> Annuler</button>
            @if($this->confirmerEmail == 0)
                @if($this->envoi_mail == 1)
                    <button type="submit" wire:click.prevent="EmailConfirmation()" title="Cliquez pour envoyer un email de confirmation" class="btn btn-sm btn-secondary fw-bold"><i class="fa fa-paper-plane"></i> Envoyer email confirmation</button>
                @else
                    <button type="submit" wire:click.prevent="EmailConfirmation()" title="Cliquez pour confirmer l'email manuellement" class="btn btn-sm btn-secondary fw-bold"><i class="fa fa-paper-plane"></i> Confirmer email</button>
                @endif
            @endif
        </div>
    </div>
    </div>
</div>