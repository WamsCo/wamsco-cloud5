<div id="app" class="app">
    <div class="login login-with-news-feed">    
        <div class="news-feed">
            <div class="news-image" style="background-image: url(storage/fond/background16.jpg)"></div>
            <div class="news-caption">
                <h4 class="caption-title"><b>WamsCo</b> Cloud</h4>
                <p>Application de gestion commerciale pour les PME, TPE etc...</p>
            </div>
        </div>
        <div class="login-container">
            <div class="login-header mb-30px">
                <div class="brand">
                    <div class="d-flex align-items-center">
                        <span class="position_logo"><img class="logo_wamsco" src="storage/default/logo-blanc.png"> </span>
                    </div>
                    <small>Votre réussite à portée de main</small>
                    @if($demo == "essai")
                        <small style="margin-top:15px;">Login:<span style="color: #c09d00;"> infos@wamsco-cloud.net</span></small>
                        <small>Mot de passe:<span style="color: #c09d00;"> bienvenue@2025</span></small>
                    @endif
                </div>                
            </div>
            <div class="login-content">
                @include('flash::message')
                <form action="#" method="GET" class="fs-13px">
                    <div class="form-floating mb-15px">
                        <input type="email" name="email" wire:model.defer="email" class="form-control h-45px fs-13px @error('email') is-invalid @enderror" placeholder="Entrez votre email">
                        <label for="emailAddress" class="d-flex align-items-center fs-13px text-gray-600">Entrez votre email</label>
                        @error('email')<div class="text-danger invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-floating mb-10px">
                        <input type="password" name="password" wire:model.defer="password" class="form-control h-45px fs-13px @error('password') is-invalid @enderror" placeholder="Entrez votre mot de passe">
                        <label for="password" class="d-flex align-items-center fs-13px text-gray-600">Entrez votre mot de passe</label>
                        @error('password')<div class="text-danger invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3" style="text-align: right;">
                        <a href="/forgot_password" class="text-red-300">Mot de passe oublié?</a>
                      </div>
                    <div class="mb-15px d-flex">
                        <button class="btn btn-danger d-block h-45px w-100 btn-lg fs-14px" wire:click.prevent="login()"><i class="fa fa-hand-point-right text-white blink"></i> Connexion</button>
                        <div wire:loading.delay>        
                            <img src="storage/default/circle_loading.gif" width="64" height="64"  style="margin-left: 1%;position: relative;top: -8px;">
                        </div>
                    </div>
                    <div class="mb-40px pb-40px text-dark">
                        <div class="social-auth-links text-center mb-3">
                            <p>- Pages WamsCo -</p>
                            <a href="/prix" class="btn btn-block btn-dark"><i class="fa fa-pencil mr-1"></i> S'inscrire</a>
                            <a href="./" class="btn btn-block btn-dark"><i class="fa fa-home mr-1"></i> Accueil site</a>
                            <a href="https://www.facebook.com/wamsco/" target="_blank" class="btn btn-block btn-primary"><i class="fab fa-facebook-square mr-2"></i> Facebook</a>
                        </div>
                    </div>
                    <hr class="bg-gray-600 opacity-2" />
                    <div class="text-gray-600 text-center  mb-0">Tous droits réservés <span class="wamsco_color">WamsCo</span> &copy; {{date('Y')}}</div>
                </form>
            </div>        
        </div>        
    </div>    
</div>