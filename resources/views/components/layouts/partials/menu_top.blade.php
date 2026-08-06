<div id="header" class="app-header">
  <div class="navbar-header">
      <a href="bienvenue?active=1" wire:navigate class="navbar-brand">
          <img class="logo_wams" src="storage/default/logo-blanc.png"/>
          {{-- <b>WamsCo</b> Cloud --}}          
      </a>
      <button type="button" class="navbar-mobile-toggler" data-toggle="app-sidebar-mobile">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
      </button>
  </div>  
  <div class="navbar-nav"> 
      @foreach($entite_mod as $entite_mods)      
          <label for="" class="date_expira">Expire le: <span class="expirat">{{date('d-m-Y', strtotime($entite_mods->validite_mod))}}</span> 
              @if($nbjoursRestant >= 15) 
                      (<span class="text-vert fw-bold">-{{$nbjoursRestant}} Jours</span>)
              @elseif($nbjoursRestant < 15 && $nbjoursRestant > 5) 
                  (<span class="text-warning fw-bold">-{{$nbjoursRestant}} Jours</span>)
              @elseif($nbjoursRestant <= 5 && $nbjoursRestant >= 0)                    
                  @if($nbjoursRestant == 0)
                      (<span class="blink text-orange fw-bold">Dernier Jour</span>)
                  @else
                      (<span class="blink text-red fw-bold">{{$nbjoursRestant}} Jours</span>)
                  @endif   
              @else
                  (<span class="text-red fw-bold">Expirer</span>)
              @endif <br>
              <div class="fs-10px date_expira">Votre plan: <span class="expirat"><i class="fa fa-money-bill"></i> {{$entite_mods->periode}}</span></div>
          </label>
      @endforeach
      <div class="navbar-item navbar-form d-none d-md-block" style="line-height:0; padding:0 5px;">
          <a href="https://api.whatsapp.com/send?phone=+237654258009&text=Bonjour WamsCo, nous souhaiterons avoir une aide sur l'application WamsCo-cloud. Merci." target="_blank" style="padding-right: 5px;border-radius: 4px;" class="dropdown-item media">
              <div class="media-body">
                  <h6 class="media-heading" style="margin-bottom: 0px;"><i class="fa fa-hand-point-right text-warning"></i> Besoin d'aide <i class="fa fa-question-circle"></i></h6>                       
                  <div class="text-muted fs-10px"><i class="fa fa-phone"></i> +237 654 25 80 09 <i class="fab fa-whatsapp"></i></div>
              </div>
          </a>
      </div>
      <div class="navbar-item navbar-form d-none d-md-block" style="line-height:0; padding:0 5px;">
          <a href="{{asset('choix_plan')}}" style="padding-right: 5px;border-radius: 4px;" class="dropdown-item media">
              <div class="media-body">
                  <h6 class="media-heading" style="margin-bottom: 0px;"><i class="fa fa-user-circle text-secondary"></i> Mon abonnement</h6>   
              </div>
          </a>
      </div>
      {{-- <div class="navbar-item navbar-form">
          <form action="#" method="POST" name="search">
              <div class="form-group">
                  <input type="text" class="form-control" placeholder="Enter keyword" />
                  <button type="submit" class="btn btn-search"><i class="fa fa-search"></i></button>
              </div>
          </form>
      </div> --}}        
      <div class="navbar-item dropdown">
          <a href="#" data-bs-toggle="dropdown" class="navbar-link dropdown-toggle icon">
              <i class="fa fa-bell"></i><span class="badge">4</span>
          </a>
          <div class="dropdown-menu media-list dropdown-menu-end">
              <div class="dropdown-header">Informations utilies (4)</div>                
              <a href="https://wamsco-cloud.net" target="_blank" class="dropdown-item media">
                  <div class="media-left">
                      {{-- <img src="storage/default/logo-blanc.png" class="media-object" style="height: 22px;" alt="logo" />  --}}
                  </div>
                  <div class="media-body">
                      <h6 class="media-heading"><i class="fa fa-hand-point-right text-warning"></i> Site web WamsCo</i></h6>                       
                      <div class="text-muted fs-10px">Trouver toutes les informations...</div>
                  </div>
              </a>
              <a href="https://api.whatsapp.com/send?phone=+237654258009&text=Bonjour WamsCo, nous souhaiterons avoir une aide sur l'application WamsCo-cloud. Merci." target="_blank" class="dropdown-item media">
                  <div class="media-left">
                      {{-- <img src="storage/default/logo-blanc.png" class="media-object" style="height: 22px;" alt="logo" />     --}}
                  </div>
                  <div class="media-body">
                      <h6 class="media-heading"><i class="fa fa-hand-point-right text-warning"></i> Besoin d'aide</i></h6>                       
                      <div class="text-muted fs-10px"><i class="fa fa-phone"></i> +237 654 25 80 09 <i class="fab fa-whatsapp"></i></div>
                  </div>
              </a>
              <a href="https://www.youtube.com/channel/UCGEOP66xBGtzt2FdsQVwPXQ" target="_blank" class="dropdown-item media">
                  <div class="media-left">
                      {{-- <img src="storage/default/logo-blanc.png" class="media-object" style="height: 22px;" alt="logo" /> --}}
                  </div>
                  <div class="media-body">
                      <h6 class="media-heading"><i class="fa fa-hand-point-right text-warning"></i> Tutoriels</i></h6> 
                      <div class="text-muted fs-10px">Trouver tous les tutoriels en cliquant</div>
                  </div>
              </a>
              <a href="{{asset('choix_plan')}}" class="dropdown-item media">
                  <div class="media-left">
                      <i class="fa fa-user media-object bg-gray-500"></i>
                  </div>
                  <div class="media-body">
                      <h6 class="media-heading">Mon abonnement <i class="fa fa-fa fa-user-circle text-warning"></i></h6>
                      <div class="text-muted fs-10px">N'attendez pas la suspension</div>
                      <div class="text-muted fs-10px">Solde : <span class="fw-bold text-vert">{{number_format($soldeClient,0,',',' ')}}</span> FCFA</div>
                  </div>
              </a>
              {{-- <a href="javascript:;" >
                  <div class="media-left">
                      <img src="storage/logo_entite/logo.png" class="media-object" alt="" />
                      <i class="fab fa-facebook-messenger text-blue media-object-icon"></i>
                  </div>
                  <div class="media-body">
                      <h6 class="media-heading">WamsCo</h6>
                      <a href=" target="_blank" class="more-btn"><i class="fa fa-plus-circle"></i> Demander une aide</a>
                      <p>+237 654 25 80 09 <i class="fa fa-whatsapp"></i></p>
                      <div class="text-muted fs-10px">35 minutes ago</div>
                  </div>
              </a> --}}
              {{-- <a href="javascript:;" class="dropdown-item media">
                  <div class="media-left">
                      <i class="fa fa-plus media-object bg-gray-500"></i>
                  </div>
                  <div class="media-body">
                      <h6 class="media-heading"> New User Registered</h6>
                      <div class="text-muted fs-10px">1 hour ago</div>
                  </div>
              </a> --}}
              {{-- <a href="javascript:;" class="dropdown-item media">
                  <div class="media-left">
                      <i class="fa fa-envelope media-object bg-gray-500"></i>
                      <i class="fab fa-google text-warning media-object-icon fs-14px"></i>
                  </div>
                  <div class="media-body">
                      <h6 class="media-heading"> New Email From John</h6>
                      <div class="text-muted fs-10px">2 hour ago</div>
                  </div>
              </a> --}}
              {{-- <div class="dropdown-footer text-center">
                  <a href="javascript:;" class="text-decoration-none">View more</a>
              </div> --}}
          </div>
      </div>
      <div class="navbar-item navbar-user dropdown">
          <a href="#" class="navbar-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
              @if(auth()->user()->profil != null)
                  <img src="storage/{{auth()->user()->profil}}" alt="Profil" />
              @else
                  <img src="storage/default/user_man.png" alt="Profil" />
              @endif
              <span><span class="d-none d-md-inline">{{Str::limit(auth()->user()->name, 20)}}</span><b class="caret"></b></span>
          </a>
          <div class="dropdown-menu dropdown-menu-end me-1">
              <a href="mon_compte?id={{auth()->user()->id}}&active=12&champ=1-1" wire:navigate class="dropdown-item text-black">Editer Profil</a>
              {{-- <a href="javascript:;" class="dropdown-item text-black">Calendar</a> --}}
              {{-- <a href="javascript:;" class="dropdown-item text-black">Setting</a> --}}
              <div class="dropdown-divider"></div>
              <a href="deconnexion" class="dropdown-item text-black">Déconnexion</a>
          </div>
      </div>
  </div>
</div>