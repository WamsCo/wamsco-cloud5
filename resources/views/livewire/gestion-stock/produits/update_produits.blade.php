<div wire:ignore.self class="modal fade" id="updateProduitModal" tabindex="-1" data-bs-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
        <div class="modal-header py-2" style="background-color: #56585d; color:#ffffff;">
        <h5 class="modal-title" id="exampleModalCenteredScrollableTitle"><i class="fas fa-cube"></i> Modification » <span class="text-vert">{{$this->nom_produit}}</span> </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body py-0 px-0">            
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="row mb-3">
                            {{-- <label for="nom_produit" class="col-lg-2 col-md-5 col-sm-3 fw-bold fs-5 col-form-label">Opportunité</label> --}}
                            <div class="col-lg-10 col-md-10 col-sm-10">
                                <input type="text" wire:model="nom_produit" placeholder="Nom du produit" class="form-control bordure fs-3 w-100 px-0 @error('nom_produit') is-invalid @enderror" id="nom_produit">
                            </div>
                            <div class="d-flex justify-content-start">
                                @error('nom_produit') <span class="text-danger">{{$message}}</span> @enderror 
                            </div>
                        </div>                                            
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">                                                                                   
                        <div class="row mb-1">
                            <label for="reference" class="col-lg-4 col-md-5 col-sm-3 fw-bold col-form-label">Référence</label>
                            <div class="col-lg-8 col-md-7 col-sm-9">
                                <input type="text" wire:model="reference" placeholder="Ex: MG-001" class="form-control bordure w-100 @error('reference') is-invalid @enderror" id="reference">
                            </div>
                            <div class="d-flex justify-content-start">
                                @error('reference') <span class="text-danger">{{ $message }}</span> @enderror 
                            </div>
                        </div>
                        <div class="row mb-1">
                            <label for="code_barre" class="col-lg-4 col-md-5 col-sm-3 fw-bold col-form-label">Code barre <span class="text-danger pointer" wire:click.prevent="genererCodeBarre()"><i class="fa fa-barcode text-black"></i> <i class="fa fa-refresh"></i></span> </label>
                            <div class="col-lg-8 col-md-7 col-sm-9">
                                <input type="text" wire:model="code_barre" placeholder="Ex: 101001101101" class="form-control bordure w-100 @error('code_barre') is-invalid @enderror" id="code_barre">
                            </div>
                            <div class="d-flex justify-content-start">
                                @error('code_barre') <span class="text-danger">{{ $message }}</span> @enderror 
                            </div>
                        </div>
                        <div class="row mb-1">
                            <label for="type_produit" class="col-lg-4 col-md-5 col-sm-3 fw-bold col-form-label">Type</label>
                            <div class="col-lg-8 col-md-7 col-sm-9">                                                        
                                <select id="type_produit" wire:model.live="type_produit" class="form-control form-select bordure w-50 @error('type_produit') is-invalid @enderror">
                                    {{-- <option value=""></option> --}}
                                    <option value="Produit">Produit</option>   
                                    <option value="Service">Service</option>	
                                </select> 
                            </div>
                            <div class="d-flex justify-content-start">
                                @error('type_produit') <span class="text-danger">{{ $message }}</span> @enderror 
                            </div>
                        </div>
                        <div class="row mb-1">
                            <label for="nature_produit" class="col-lg-4 col-md-5 col-sm-3 fw-bold col-form-label">Nature</label>
                            <div class="col-lg-8 col-md-7 col-sm-9">                                                        
                                <select id="nature_produit" wire:model="nature_produit" class="form-control form-select bordure w-100 @error('nature_produit') is-invalid @enderror">
                                    <option value="Matière première">Matière première</option>   
                                    <option value="Manufacturé">Manufacturé</option>
                                    <option value="Manufacturé - Matière première">Manufacturé - Matière première</option> 
                                </select> 
                            </div>
                            <div class="d-flex justify-content-start">
                                @error('nature_produit') <span class="text-danger">{{ $message }}</span> @enderror 
                            </div>
                        </div>                                                                                                
                        <div class="row mb-1">
                            <label for="inputPassword3" class="col-sm-3 fw-bold col-form-label">Etat</label>
                            <div class="col-sm-9">
                                <div class="card-body">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" wire:model.live="etat" value="1" name="inlineRadioOptions" id="inlineRadio3">
                                        <label class="form-check-label" for="inlineRadio3">Activer</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" wire:model.live="etat" value="0" name="inlineRadioOptions" id="inlineRadio4">
                                        <label class="form-check-label" for="inlineRadio4">Désactiver</label>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-start">
                                    @error('etat') <span class="text-danger">{{ $message }}</span> @enderror 
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">                                                                           
                        <div class="row mb-1">
                            <label for="categorie" class="col-lg-4 col-md-5 col-sm-3 fw-bold col-form-label">Catégorie</label>
                            <div class="col-lg-8 col-md-7 col-sm-9">                                                        
                                <select id="categorie" wire:model="categorie" class="form-control form-select bordure w-100 @error('categorie') is-invalid @enderror">
                                    <option value=""></option>	
                                    @foreach($listCategorie as $listCategories)
                                        <option value="{{$listCategories->nom_categorie}}">{{$listCategories->nom_categorie}}  @if($listCategories->restaurant == "Oui") (Restaurant) @endif</option>
                                    @endforeach	
                                </select> 
                            </div>
                            <div class="d-flex justify-content-start">
                                @error('categorie') <span class="text-danger">{{ $message }}</span> @enderror 
                            </div>
                        </div>
                        @if($type_produit == "Produit")
                            <div class="row mb-1">
                                <label for="entrepot" class="col-lg-5 col-md-5 col-sm-4 fw-bold col-form-label">Entrepôt/Magasin</label>
                                <div class="col-lg-7 col-md-7 col-sm-8">                                                        
                                    <select id="entrepot" wire:model="entrepot" class="form-control form-select bordure w-100 @error('entrepot') is-invalid @enderror">
                                        <option value=""></option>                                                                
                                        @foreach($listEntrepot as $listEntrepots)
                                            @if($listEntrepots->active == 1)
                                                <option value="{{$listEntrepots->id}}">{{$listEntrepots->nom}}</option>
                                            @endif
                                        @endforeach	
                                    </select> 
                                </div>
                                <div class="d-flex justify-content-start">
                                    @error('entrepot') <span class="text-danger">{{ $message }}</span> @enderror 
                                </div>
                            </div>
                        @endif
                        <div class="row mb-1">
                            <label for="fournisseur" class="col-lg-4 col-md-5 col-sm-3 fw-bold col-form-label">Fournisseur</label>
                            <div class="col-lg-8 col-md-7 col-sm-9">                                                        
                                <select id="fournisseur" wire:model="fournisseur" class="form-control form-select bordure w-auto @error('fournisseur') is-invalid @enderror">
                                    <option value=""></option>	
                                    @foreach($listFourni as $listFournis)
                                        <option value="{{$listFournis->nom}}">{{$listFournis->nom}} @if($listFournis->raison_sociale) ({{$listFournis->raison_sociale}}) @endif</option>		
                                    @endforeach	
                                </select> 
                            </div>
                            <div class="d-flex justify-content-start">
                                @error('fournisseur') <span class="text-danger">{{ $message }}</span> @enderror 
                            </div>
                        </div>                                     
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="row mb-1">
                            <label for="description" class="col-lg-2 col-md-3 col-sm-4 col-form-label">Description</label>
                            <div class="col-lg-10 col-md-9 col-sm-9">                                                        
                                <textarea rows="2" wire:model="description" class="form-control bordure w-100 @error('description') is-invalid @enderror" id="description" placeholder="Ajouter une description..."></textarea>
                            </div>
                            <div class="d-flex justify-content-start">
                                @error('description') <span class="text-danger">{{ $message }}</span> @enderror 
                            </div>
                        </div>
                    </div>                                         
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 px-0">
                        <div class="table-responsive mt-2">
                            <div class="table-responsive mt-1"> 
                                <ul class="nav nav-tabs border-0" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#info">Informations</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#autre">Autres</a>
                                    </li>
                                </ul>
                                <div class="tab-content table-responsive border-topk px-3" style="border-top-style: dashed; border-top-width: 1px;border-top-color: #0e04043d;">
                                    <div id="info" class="tab-pane active">
                                        <div class="row mt-2">
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <div class="w_horizontal_separator mt-1 mb-3 text-bleu text-uppercase fw-bolder small">Information sur le prix</div>                                                                        
                                                <div class="row mb-1">
                                                    <label for="tva" class="col-lg-4 col-md-5 col-sm-3 col-form-label">Taxe à la vente</label>
                                                    <div class="col-lg-8 col-md-7 col-sm-9">                                                        
                                                        <select id="tva" wire:model="tva" class="form-control form-select bordure w-75 @error('tva') is-invalid @enderror">
                                                            {{-- <option value=""></option>		 --}}
                                                            <option value="0">0%</option>	
                                                            @foreach($listedeviseTva as $listedeviseTvas)
                                                                @if($listedeviseTvas->taxe == "Tva")
                                                                    <option value="{{$listedeviseTvas->taux_tva}}">{{$listedeviseTvas->taux_tva}}% ({{$listedeviseTvas->taxe}}) → {{$listedeviseTvas->devise}}</option>
                                                                @endif
                                                            @endforeach	
                                                        </select> 
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('tva') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>                               
                                                <div class="row mb-1">
                                                    <label for="prix_achat" class="col-lg-5 col-md-5 col-sm-4 fw-bold col-form-label">Prix achat <span style="font-size: 11px" class="text-bleu">({{$devise}})</span></label>
                                                    <div class="col-lg-7 col-md-7 col-sm-8">
                                                        <input type="text" wire:model="prix_achat" placeholder="Ex: 2000" class="form-control bordure w-50 @error('prix_achat') is-invalid @enderror" id="telephone">
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('prix_achat') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>                                                
                                                <div class="row mb-1">
                                                    <label for="prix_vente_min" class="col-lg-6 col-md-6 col-sm-5 fw-bold col-form-label">Prix vente mini <span style="font-size: 11px" class="text-bleu">({{$devise}})</span></label>
                                                    <div class="col-lg-6 col-md-6 col-sm-7">
                                                        <input type="text" wire:model="prix_vente_min" placeholder="Ex: 5000" class="form-control bordure w-75 @error('prix_vente_min') is-invalid @enderror" id="prix_vente_min">
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('prix_vente_min') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div> 
                                                <div class="row mb-1">
                                                    <label for="prix_vente" class="col-lg-5 col-md-5 col-sm-4 fw-bold col-form-label">Prix vente <span style="font-size: 11px" class="text-bleu">({{$devise}})</span></label>
                                                    <div class="col-lg-7 col-md-7 col-sm-8">
                                                        <input type="text" wire:model="prix_vente" placeholder="Ex: 5000" class="form-control bordure w-50 @error('prix_vente') is-invalid @enderror" id="prix_vente_min">
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('prix_vente') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>                                                                     
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <div class="w_horizontal_separator mt-1 mb-3 text-bleu text-uppercase fw-bolder small">Information commerciale</div>
                                                @if($type_produit == "Produit")
                                                    <div class="row mb-1">
                                                        <label for="limite_stock_alerte" class="col-lg-5 col-md-5 col-sm-4 fw-bold col-form-label">Alerte stock PV</label>
                                                        <div class="col-lg-7 col-md-7 col-sm-8">
                                                            <input type="text" wire:model="limite_stock_alerte" placeholder="Ex: 5000" class="form-control bordure w-50 @error('limite_stock_alerte') is-invalid @enderror" id="prix_vente_min">
                                                        </div>
                                                        <div class="d-flex justify-content-start">
                                                            @error('limite_stock_alerte') <span class="text-danger">{{ $message }}</span> @enderror 
                                                        </div>
                                                    </div> 
                                                @endif                                                          
                                                <div class="row mb-1">
                                                    <label for="pays_origine" class="col-lg-5 col-md-5 col-sm-4 col-form-label">Pays</label>
                                                    <div class="col-lg-7 col-md-7 col-sm-8">                                                        
                                                        <select name="pays_origine" wire:model="pays_origine" class="form-control form-select bordure w-100 @error('pays_origine') is-invalid @enderror" style="color: #6b6b6b;">
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
                                                        @error('pays_origine') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div> 
                                                @if($type_produit == "Produit")                                                                                
                                                    <div class="row mb-1">
                                                        <label for="date_peremption" class="col-lg-5 col-md-5 col-sm-4 col-form-label">Date péremption</label>
                                                        <div class="col-lg-7 col-md-7 col-sm-8">
                                                            <input type="date" wire:model="date_peremption" placeholder="Ex: Rue joe 23" class="form-control bordure w-100 @error('date_peremption') is-invalid @enderror" id="date_peremption">
                                                        </div>
                                                        <div class="d-flex justify-content-start">
                                                            @error('date_peremption') <span class="text-danger">{{ $message }}</span> @enderror 
                                                        </div>
                                                    </div>
                                                @endif                                                
                                                <div class="row mb-1">
                                                    <label for="responsable_achat" class="col-lg-5 col-md-5 col-sm-4 col-form-label">Responsable achat</label>
                                                    <div class="col-lg-7 col-md-7 col-sm-8">                                                        
                                                        <select id="responsable_achat" wire:model="responsable_achat" class="form-control form-select bordure w-100 @error('responsable_achat') is-invalid @enderror">
                                                            <option value=""></option>	
                                                            @foreach($listUser as $listUsers)
                                                                <option value="{{$listUsers->name}}">{{$listUsers->name}}→{{$listUsers->type_user}}</option>		
                                                            @endforeach	
                                                        </select> 
                                                    </div>
                                                    <div class="d-flex justify-content-start">
                                                        @error('responsable_achat') <span class="text-danger">{{ $message }}</span> @enderror 
                                                    </div>
                                                </div>                                                              
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
        <div class="modal-footer py-1" style="background-color: #56585d; color:#ffffff;">
            <button type="submit" wire:click.prevent="update()" class="btn btn-sm btn-secondary fw-semibold"><i class="fa fa-save"></i> Modifier</button>
            <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal"><i class="fa fa-close"></i> Fermer</button>
        </div>
    </div>
    </div>
</div>