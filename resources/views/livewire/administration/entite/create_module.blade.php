{{-- <div wire:ignore.self class="modal fade" id="selectModuleModal" tabindex="-1" role="dialog" data-bs-backdrop="static" aria-labelledby="slidemodalLabel" aria-hidden="true"> --}}
<div wire:ignore.self class="modal fade" id="createModuleModal" tabindex="-1" data-bs-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
    <form enctype="multipart/form-data"> 
        @csrf   
        <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">             
                <div class="modal-header py-1" style="background-color: #56585d; color:#ffffff;">                    
                    <h5 class="pt-2"><i class="fas fa-university"></i> Nouvelle société</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">   
                    <div class="row">                                    
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="nom_desig">
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bordure" style="background: #ffffff;"><i class="fas fa-university"></i> <span class="hidden-xs sombre">&nbsp; Raison sociale »</span></span>
                                    </div>
                                    <input type="text" wire:model.defer="raison_sociale" placeholder="Ex: wamsco-sas" style="background: #ffffff; color:#ff1012;" class="form-control bordure @error('raison_sociale') is-invalid @enderror"/>
                                    @error('raison_sociale')<div class="text-danger invalid-feedback">{{ $message }}</div>@enderror
                                </div>									                               
                            </div>	                                                                                               
                        </div> 
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <label for="reference" class="col-md-12 fw-semibold">Validité module(s)</label>
                            <input type="date" name="validite_mod" wire:model.defer="validite_mod" class="form-control fw-bold bordure @error('validite_mod') is-invalid @enderror"/>
                            @error('validite_mod')<div class="text-danger invalid-feedback">{{ $message }}</div>@enderror  
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <label for="reference" class="col-md-12 fw-semibold">Nombre utilisateur(Max)</label>
                            <select name="nbre_user_max" wire:model.defer="nbre_user_max" class="form-control form-select fw-bold bordure  @error('nbre_user_max') is-invalid @enderror">
                                <option value=""></option>
                                @for($i = 1; $i <= 100; $i += 1)
                                    <option value="{{$i}}">{{$i}}</option> 
                                @endfor                                                                                                
                            </select> 
                            @error('nbre_user_max')<div class="text-danger invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <label for="nombre_societe" class="col-md-12 fw-semibold">Nombre Société(Max)</label>
                            <select name="nombre_societe" wire:model.defer="nombre_societe" class="form-control form-select fw-bold bordure @error('nombre_societe') is-invalid @enderror">
                                <option value=""></option>
                                @for($i = 1; $i <= 10; $i += 1)
                                    <option value="{{$i}}">{{$i}}</option> 
                                @endfor                                                                                                
                            </select> 
                            @error('nombre_societe')<div class="text-danger invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <label for="recommandation" class="col-md-12 fw-semibold">Recommandation</label>
                            <select name="recommandation" wire:model.defer="recommandation" class="form-control form-select fw-bold bordure @error('recommandation') is-invalid @enderror">
                                <option value="contact@wamsco-cloud.net">contact@wamsco-cloud.net</option>
                                <optgroup style="color: #c6c9c9;" label="Email">
                                    @foreach($utilisateurAll as $utilisateurAlls)                                    
                                        <option value="{{$utilisateurAlls->email}}">{{$utilisateurAlls->name}} » {{$utilisateurAlls->email}}</option> 
                                    @endforeach    
                                </optgroup>                                                                                                
                            </select>
                            @error('recommandation')<div class="text-danger invalid-feedback">{{ $message }}</div>@enderror  
                        </div>  
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <label for="montant_paye" class="col-md-12 fw-semibold">Montant Payé</label>
                            <select name="montant_paye" wire:model.defer="montant_paye" class="form-control form-select fw-bold bordure @error('montant_paye') is-invalid @enderror" style="color:#094972;">
                                <option value=""></option>
                                @for($i = 0; $i <= 200000; $i += 1000)
                                    <option value="{{$i}}">{{$i}} {{$devise}}</option> 
                                @endfor                                                                                                
                            </select>
                            @error('montant_paye')<div class="text-danger invalid-feedback">{{ $message }}</div>@enderror  
                        </div>                      
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <label for="periode" class="col-md-12 fw-semibold">Choisissez un plan</label>
                            <select name="periode" wire:model.defer="periode" class="form-control form-select fw-bold bordure @error('periode') is-invalid @enderror" style="color:#094972;;">
                                <option value=""></option>
                                <optgroup style="color: #838383;" label="Gratuit">
                                    <option value="Essai Gratuit">Essai Gratuit / 14 Jours : 0 FCFA</option>                                                    
                                </optgroup>
                                <optgroup style="color: #838383;" label="Independant">
                                    <option value="Independant Mensuel">Mensuel : 10 000 FCFA</option>                                                    
                                    <option value="Independant Annuel">Annuel : 100 000 FCFA</option> 
                                </optgroup> 
                                <optgroup style="color: #838383;" label="Standard">
                                    <option value="Standard Mensuel">Mensuel : 20 000 FCFA</option>                                                    
                                    <option value="Standard Annuel">Annuel : 200 000 FCFA</option> 
                                </optgroup> 
                            </select>
                            @error('periode')<div class="text-danger invalid-feedback">{{ $message }}</div>@enderror                             
                        </div>                        
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <label for="taux_commission" class="col-md-12 fw-semibold">Taux Commission</label>
                            <select name="taux_commission" wire:model.defer="taux_commission" class="form-control form-select fw-bold bordure @error('taux_commission') is-invalid @enderror" style="color:#f95900;">
                                <option value=""></option> 
                                @for($i = 0; $i <= 50; $i += 1)
                                    <option value="{{$i}}">{{$i}}%</option> 
                                @endfor                                                                                                
                            </select> 
                            @error('taux_commission')<div class="text-danger invalid-feedback">{{ $message }}</div>@enderror
                        </div>                        
                        <div class="col-md-6 col-sm-6 col-xs-12 mb-3">
                            <label for="etat_commission" class="col-md-12 fw-semibold">Etat Commission</label>
                            <select name="etat_commission" wire:model.defer="etat_commission" class="form-control form-select fw-bold bordure @error('etat_commission') is-invalid @enderror" style="color:#f95900">
                                <option value=""></option>
                                <optgroup style="color: #838383;" label="Paiements">
                                    <option value="Gratuit">Gratuit</option>                                        
                                    <option value="Non payé">Non payé</option>
                                    <option value="Payé">Payé</option>
                                    {{-- <option value="Espèces">Espèces</option>                                        
                                    <option value="Acompte">Acompte</option>
                                    <option value="Orange-Money">Orange-Money</option>                                        
                                    <option value="MTN-Mobil-Money">MTN-Mobil-Money</option>                                        
                                    <option value="Chèque">Chèque</option>                                        
                                    <option value="Virement-Bancaire">Virement-Bancaire</option> --}}
                                </optgroup>                                                                                               
                            </select> 
                            @error('etat_commission')<div class="text-danger invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="g_tier" wire:model="mod_gestion_tier">
                                <label class="form-check-label fw-semibold" for="g_tier">Gestion Tier</label>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="g_crm" wire:model="mod_crm">
                                <label class="form-check-label fw-semibold" for="g_crm">CRM</label>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="g_stock" wire:model="mod_gestion_stock">
                                <label class="form-check-label fw-semibold" for="g_stock">Gestion Stock</label>
                            </div> 
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="pv"  wire:model="mod_pointe_vente">
                                <label class="form-check-label fw-semibold" for="pv">Point de vente</label>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="restau"  wire:model="mod_restaurant">
                                <label class="form-check-label fw-semibold" for="restau">Gestion Restaurant</label>
                            </div>  
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="cui"  wire:model="mod_cuisine">
                                <label class="form-check-label fw-semibold" for="cui">Affichage Cuisine</label>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="g_cmd" wire:model="mod_cmd">
                                <label class="form-check-label fw-semibold" for="g_cmd">Commande</label>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="g_ticket" wire:model="mod_ticket">
                                <label class="form-check-label fw-semibold" for="g_ticket">Ticket</label>
                            </div>  
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="g_factu" wire:model="mod_facturation">
                                <label class="form-check-label fw-semibold" for="g_factu">Facturation</label>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="g_bq" wire:model="mod_banque_caisse">
                                <label class="form-check-label fw-semibold" for="g_bq">Gestion Banque | Caisse</label>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="g_fab" wire:model="mod_fabrication">
                                <label class="form-check-label fw-semibold" for="g_fab">Gestion Fabrication</label>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="g_tache" wire:model="mod_tache">
                                <label class="form-check-label fw-semibold" for="g_tache">Gestion Tâches</label>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="g_com" wire:model="mod_gestion_commercial">
                                <label class="form-check-label fw-semibold text-danger" for="g_com">Gestion Commerciale (Admin)</label>
                            </div>
                            <div class="form-check form-switch mb-3"> 
                                <input class="form-check-input" type="checkbox" id="g_msoc" wire:model="mod_multisociete">
                                <label class="form-check-label fw-semibold" for="g_msoc">Gestion Multi-société</label>
                            </div>
                            {{-- <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="g_employe" wire:model="mod_gestion_employe">
                                <label class="form-check-label fw-semibold" for="g_employe">Gestion Employé</label>
                            </div>   --}}
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="administra" wire:model="mod_administration">
                                <label class="form-check-label fw-semibold" for="administra">Administration</label>
                            </div>                   
                        </div>
                    </div>
                </div>
                <div class="modal-footer py-1" style="background-color: #56585d; color:#ffffff;">                    
                    <button class="btn btn-sm btn-secondary fw-bold" wire:click.prevent="store()" title="Cliquez pour enregistrer" data-toggle="tooltip"><i class="fa fa-save"></i> Enregistrer</button>
                    <a href="#" class="btn btn-sm btn-danger" data-bs-dismiss="modal" title="Cliquez pour annuler" data-toggle="tooltip"><i class="fa fa-close"></i></a>
                </div>              
            </div>
        </div>
    </form>   
</div> 