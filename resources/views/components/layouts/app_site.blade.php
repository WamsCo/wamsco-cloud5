<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="WamsCo-cloud est un ERP de gestion commerciale comportant plusieurs modules répondant à de nombreux besoins d'entreprises de toute taille, de la PME au grand groupe mais aussi pour les indépendants, auto-entrepreneurs et autres." />
	<meta name="author" content="WamsCo">
	<meta name="robots" content="index,follow" />
	<meta name="title" content="WamsCo-cloud est un ERP(Progiciel de gestion intégré) de gestion commerciale comportant plusieurs modules répondant à de nombreux besoins d'entreprises de toute tailles, de la PME au grand groupe mais aussi pour les indépendants, auto-entrepreneurs et autres." />
	<meta name="keywords" content="Agence digitale Cameroun,logiciel de gestion, site web, wamsco-cloud, erp, apps, saas, business, applications de gestion sur messure au Cameroun" />
	<title>{{ $title }}</title>
	<link rel="shortcut icon" href="site/default/files/image/favicon.ico" type="image/x-icon">
	<link rel="canonical" href="https://wamsco-cloud.net" />
	<link rel="shortlink" href="https://wamsco-cloud.net" />
	{{-- avec app --}}
	<link href="assets/css/vendor.min.css" rel="stylesheet" />
	<link href="assets/css/default/app.min.css" rel="stylesheet" />
	<link href="assets/css/default/wamsco.css" rel="stylesheet" />
	{{-- app --}}
	<meta property="og:site_name" content="WamsCo Cloud" />
	<meta property="og:type" content="website" />
	<meta property="og:url" content="https://wamsco-cloud.net" />
	<meta property="og:title" content="WamsCo Cloud logiciel de point de vente" />
	<meta property="og:description" content="WamsCo-cloud est un ERP de gestion commerciale comportant plusieurs modules répondant à de nombreux besoins d'entreprises de toute taille." />
	<meta property="og:image:type" content="image/png" />
	<meta property="og:image:width" content="450" />
	<meta property="og:image:height" content="450" />
	<meta property="og:image:alt" content="WamsCo Cloud" />
	<meta name="twitter:card" content="summary" />
	<meta name="twitter:description" content="WamsCo-cloud est un ERP de gestion commerciale comportant plusieurs modules répondant à de nombreux besoins d'entreprises de toute taille." />
	<meta name="twitter:title" content="WamsCo Cloud" />
	<meta name="twitter:image:alt" content="WamsCo Cloud" />
	<meta name="MobileOptimized" content="width" />
	<meta name="HandheldFriendly" content="true" />
	<link href="assets/css/app.min.css" rel="stylesheet" />
	{{-- <link href="assets/css/bootstrap.min.css" rel="stylesheet" /> --}}
	<link rel="stylesheet" media="all" href="site/default/files/css/wamsco.css" />
	<link rel="stylesheet" media="all" href="site/default/files/css/super.css" />
	<link rel="stylesheet" media="all" href="site/default/files/css/style.css" />
	<link rel="stylesheet" media="all" href="http://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&amp;display=swap" />
	<link href="fontawesome-6.1.1/css/all.min.css" rel="stylesheet">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> {{-- Pour la langue --}}
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-7K5RH9S94J"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());
		gtag('config', 'G-7K5RH9S94J');
	</script>	
	{{-- Meta Pixel Code --}}
	<script>
		!function(f,b,e,v,n,t,s)
		{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
		n.callMethod.apply(n,arguments):n.queue.push(arguments)};
		if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
		n.queue=[];t=b.createElement(e);t.async=!0;
		t.src=v;s=b.getElementsByTagName(e)[0];
		s.parentNode.insertBefore(t,s)}(window, document,'script',
		'https://connect.facebook.net/en_US/fbevents.js');
		fbq('init', '679216284195252');
		fbq('track', 'PageView');
	</script>
		<noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=679216284195252&ev=PageView&noscript=1"/></noscript>
	{{-- End Meta Pixel Code --}}	
	{{-- clarity --}}
	<script type="text/javascript">
		(function(c,l,a,r,i,t,y){
			c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
			t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
			y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
		})(window, document, "clarity", "script", "u5153hqtui");
	</script>
	{{-- fin clarity  --}}
	@livewireStyles
</head>
<body itemscope itemtype="https://schema.org/WebPage">	
		<div class="mainwrap">
			<div class="topmenu mobile">
				<div id="block-topmenumobilearrow">
					<div>
						<div class="mobile-arrow">&nbsp;</div>
					</div>
				</div>
				<div id="block-sitebranding-2">
					<a href="./" rel="home">
						<img src="site/default/files/image/logo-noire.png" alt="Accueil" />
					</a>
				</div>
				<div class="language-switcher-language-url" id="block-languageswitcher-3" role="navigation">
					<div class="menu-button earth-img"></div>
					{{-- <div>Français</div> --}}
					{{-- <ul class="links">
						<li hreflang="id" data-drupal-link-system-path="&lt;front&gt;"><a href="id.html"class="language-link" hreflang="id"	data-drupal-link-system-path="&lt;front&gt;">Anglais</a></li>
						<li hreflang="fr" data-drupal-link-system-path="&lt;front&gt;" class="is-active"><a	href="fr.html" class="language-link is-active" hreflang="fr" data-drupal-link-system-path="&lt;front&gt;">Français</a></li>
					</ul> --}}
				</div>
				<nav itemscope itemtype="https://schema.org/SiteNavigationElement"
					aria-labelledby="block-topmenu-3-menu" id="block-topmenu-3">
					<div class="visually-hidden" id="block-topmenu-3-menu">top menu</div>
					<ul class="lmenu">
						<li>
							<a href="./"><i class="fa fa-home"></i> @lang('site.menu_accueil')</a>
						</li>
						{{-- <li>
							<a href="./#fonctionnalite"><i class="fa fa-cog fa-spin"></i> @lang('site.menu_fonctionnalites')</a>
						</li> --}}
						<li>
							<a href="prix"><i class="fa fa-money-bill"></i> @lang('site.prix')</a>
						</li>
					</ul>
				</nav>
				{{-- <div id="block-appmarketplace-2" class="a_mp_mob">
					<div><a href="#">App Marketplace</a>
					</div>
				</div> --}}
				<div id="block-help-3">
					<div>
						<div><a class="help" target="_blank" href="https://api.whatsapp.com/send?phone=+237654258009&text=Bonjour WamsCo, nous souhaiterons avoir une aide par rapport à votre application. Merci."><i class="fa fa-circle-question"></i> @lang('site.aide') </a></div>
					</div>
				</div>
				<div id="block-help-3">
					{{-- <div>
						<div><a class="help" target="_blank" href="{{asset('storage/manuel_users/Setup_WamsCo.zip')}}" download="Setup-WamsCo" title="Taille 273Mo"><i class="fa fa-download"></i> @lang('site.telecharger')</a></div>
					</div> --}}
				</div>
				{{-- <nav itemscope itemtype="https://schema.org/SiteNavigationElement"
					aria-labelledby="block-product-top-menu" id="block-product-top">
					<div id="block-product-top-menu">Produits</div>
					<ul class="lmenu">
						<li>
							<a href="#" data-drupal-link-system-path="node/46">WamsCo PDV</a>
						</li>
						<li>
							<a href="#">Back Office</a>
						</li>
						<li>
							<a href="#">Tableau de bord</a>
						</li>
						<li>
							<a href="#">Système affichage cuisine</a>
						</li>
						<li>
							<a href="#">Ecran d’Affichage de client</a>
						</li>
						<li>
							<a href="#">Gestion des	Employés</a>
						</li>
						<li>
							<a href="#">Gestion de plusieurs magasins</a>
						</li>
						<li>
							<a href="#">Gestion de Stock</a>
						</li>
					</ul>
				</nav> --}}
				{{-- <div class="views-element-container" id="block-views-block-products-block-6">
					<div>Types d&#039;entreprises</div>
					<div>
						<div class="js-view-dom-id-7a3d0ab2cc5160cd29237b937313922d8e25eb18cf2028b83c45331a30e77310">
							<div class="prod-open">
								<ul class="menu">
									<li>
										<div class="views-field views-field-title"><span class="field-content"><a href="#" hreflang="fr">Épicerie</a></span>
										</div>
									</li>
									<li>
										<div class="views-field views-field-title"><span class="field-content"><a href="#" hreflang="fr">Café</a></span></div>
									</li>
									<li>
										<div class="views-field views-field-title"><span class="field-content"><a href="#" hreflang="fr">Restaurant</a></span></div>
									</li>
									<li>
										<div class="views-field views-field-title"><span class="field-content"><a href="" hreflang="fr">Retail</a></span></div>
									</li>
									<li>
										<div class="views-field views-field-title"><span class="field-content"><a href="#" hreflang="fr">Bar</a></span></div>
									</li>
									<li>
										<div class="views-field views-field-title"><span class="field-content"><a href="#" hreflang="fr">Magasin de	mode</a></span></div>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div> --}}
				{{-- <nav itemscope itemtype="https://schema.org/SiteNavigationElement"
					aria-labelledby="block-smallbusiness-2-menu" id="block-smallbusiness-2">
					<div class="visually-hidden" id="block-smallbusiness-2-menu">Small Business</div>
					<ul class="lmenu">
						<li>
							<a href="#">Small Business</a>
						</li>
					</ul>
				</nav> --}}
				{{-- <div class="views-element-container" id="block-views-block-products-block-3-3">
					<div>Point de Vente</div>
					<div>
						<div class="js-view-dom-id-036559061cf4c53d1818e8da05ff01de96cc4188e35aafb71941467cfd457710">
							<div class="prod-open">
								<ul class="menu">
									<li>
										<div class="views-field views-field-title"><span class="field-content"><a href="#" hreflang="fr">Programme defidélité</a></span></div>
									</li>
									<li>
										<div class="views-field views-field-title"><span class="field-content"><a href="#" hreflang="fr">Acceptez les cartes de crédit</a></span></div>
									</li>
									<li>
										<div class="views-field views-field-title"><span class="field-content"><a href="#" hreflang="fr">SumUp</a></span></div>
									</li>
									<li>
										<div class="views-field views-field-title"><span class="field-content"><a href="#" hreflang="fr">Zettle</a></span></div>
									</li>
									<li>
										<div class="views-field views-field-title"><span class="field-content"><a href="#" hreflang="fr">API</a></span>
										</div>
									</li>
								</ul>
							</div>
						</div>
					</div>

				</div> --}}
				<div id="block-signintest">
					<div>
						<div id="href" style="font-size: 15px; font-weight:bold;"><a class="signk" href="connexion"><i class="fa fa-user-circle" style="margin-right: 5px;margin-left: 10px;margin-top: 3px;"></i> @lang('site.espace_client')</a></div>
					</div>
				</div>
				<div id="block-signintest">
					<div>
						<div id="href" style="font-size: 15px; font-weight:bold;"><a class="signk" href="prix"><span class="lien"><i class="fa fa-users" style="margin-right: 2px;margin-left: 10px;"></i></span> @lang('site.devenir_client')</a></div>
					</div>
				</div>
				<div id="block-signintest">
					<div>
						<div id="href" style="font-size: 13px;"><a class="signk" style="color: black; font-weight:bold;" href="https://api.whatsapp.com/send?phone=+237654258009&text=Bonjour WamsCo, nous souhaiterons avoir une assistance sur votre application. Merci." target="_blank"><span class="lien"><i class="fa fa-phone" style="margin-right: 2px;margin-left: 10px;"></i></span> @lang('site.contactez_nous') <i class="fab fa-whatsapp" style="color:green;font-weight:bold;"></i> +237 654 258 009</a></div>
					</div>
				</div>
				<div class="lang_wamsco">
					<select class="form-control lang_select changeLang">
						<option value="fr" {{ session()->get('locale') == 'fr' ? 'selected' : '' }}> Français</option>
						<option value="en" {{ session()->get('locale') == 'en' ? 'selected' : '' }}> Anglais</option>
					</select>
				</div>
			</div>
			<div id="overlay"></div>
			<div id="overlay-product"></div>
			<div id="video_block">
				<div class="popup_wrapper">
					<div class="mobile-arrow"></div>
					<iframe class="popup_content"></iframe>
				</div>
			</div>
			<header class="header" itemscope itemtype="https://schema.org/WPHeader">
				<div class="overheader">
					<div class="overheaderin">
						<div class="overheaderpad">
							<div class="rightpart overhead-lang">
								<div class="signin">
									<div id="block-communityandblog">
										<div>
											<div class="zero-menu" style="font-weight: 600;margin-right: 23px;"><i class="far fa-clock me-2"></i> @lang('site.ouverture') 9:00-18:00 </div>
											<div id="gtx-trans" style="position: absolute; left: 79px; top: -20px;">
												<div class="gtx-trans-icon"> </div>
											</div>
										</div>
									</div>
									<div id="block-communityandblog">
										<div>
											<div class="zero-menu"><a href="https://www.paypal.com/donate?hosted_button_id=BP9PNAYJ3EE5U&source=url" target="_blank"  rel="noopener"><img class="faire_don" src="storage/img_static/don.png"></a></div>
											<div id="gtx-trans" style="position: absolute; left: 79px; top: -20px;">
												<div class="gtx-trans-icon"> </div>
											</div>
										</div>
									</div>
									<div id="block-communityandblog">
										<div>
											<div class="zero-menu"><a href="https://api.whatsapp.com/send?phone=+237654258009&text=Bonjour WamsCo, nous souhaiterons avoir un entretien avec vous par rapport à votre application. Merci." target="_blank" title="cliquez pour envoyer un message whatsapp" rel="noopener"><i class="fa fa-phone" style="margin-right: 5px;margin-top: 3px;"></i>+237 654 258 009 <i class="fab fa-whatsapp" style="margin-right: 5px;margin-top: 3px; color:green;font-weight:bold;"></i></a></div>
											<div id="gtx-trans" style="position: absolute; left: 79px; top: -20px;">
												<div class="gtx-trans-icon"> </div>
											</div>
										</div>
									</div>
									<div id="block-communityandblog">
										<div>
											<div class="zero-menu"><a href="mailto:contact@wamsco-cloud.net" target="_blank" rel="noopener"><i class="fa fa-envelope" style="margin-right: 5px;margin-top: 3px;"></i>contact@wamsco-cloud.net</a></div>
											<div id="gtx-trans" style="position: absolute; left: 79px; top: -20px;">
												<div class="gtx-trans-icon"> </div>
											</div>
										</div>
									</div>									
									<div id="block-blockforsign">
										<div>
											<div id="href-web" style="font-size: 14px; font-weight:bold;"><a class="sign" href="connexion"><i class="fa fa-user-circle" style="margin-right: 5px;margin-top: 3px;"></i> @lang('site.espace_client')</a></div>
										</div> 
									</div>&nbsp;&nbsp; ou &nbsp;&nbsp;
									<div id="block-blockforsign">
										<div>
											<div id="href-web" style="font-size: 14px; font-weight:bold;"><a class="sign" href="prix"><span class="lien"><i class="fa fa-users" style="margin-right: 5px;"></i></span> @lang('site.devenir_client')</a></div>
										</div>
									</div>
								</div>
								<div class="language-switcher-language-url" id="block-languageswitcher-2" role="navigation">
									<div class="lang_wamsco">
										<select class="form-control lang_select changeLang">
											<option value="fr" {{ session()->get('locale') == 'fr' ? 'selected' : '' }}> Français</option>
											<option value="en" {{ session()->get('locale') == 'en' ? 'selected' : '' }}> Anglais</option>
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="headermain">
					<div class="fr_banner_block">
					</div>
					<div class="headerin">
						<div class="headerpad">
							<div class="mobile-button">
								<div class="mobile-button-lines">
									<span class="mobile-button-line one">&nbsp;</span>
									<span class="mobile-button-line two">&nbsp;</span>
									<span class="mobile-button-line three">&nbsp;</span>
								</div>
							</div>
							<div id="block-sitebranding" class="logo-style">
								<a href="./"  wire:navigate rel="home">
									<img src="storage/default/logo-blanc.png" style="width:150px" alt="Accueil" />
								</a>
							</div>
							<div class="topmenu">
								<nav itemscope itemtype="https://schema.org/SiteNavigationElement"
									aria-labelledby="block-topmenu-2-menu" id="block-topmenu-2">
									<div class="visually-hidden" id="block-topmenu-2-menu">Top menu</div>
									<ul class="lmenu">
										<li>
											<a href="./"><i class="fa fa-home"></i> @lang('site.menu_accueil')</a>
										</li>
										{{-- <li>
											<a href="./#fonctionnalite" wire:navigate><i class="fa fa-cog fa-spin"></i> @lang('site.menu_fonctionnalites') </a>
										</li> --}}
										<li>
											<a href="prix"><i class="fa fa-money-bill"></i> @lang('site.prix')</a>
										</li>
									</ul>
								</nav>
								{{-- <div class="views-element-container business_types"
									id="block-views-block-business-types-in-header-block-1">
									<div>Types d&#039;entreprises</div>
									<div>
										<div
											class="js-view-dom-id-73a8e00153b4da251a09e92ce37bfbdf9aef7128ee618ef2c6744f1922c74305">
											<div class="business-types-wrapper">
												<ul class="business-types-menu">
													<li>
														<div class="views-field views-field-title"><span class="field-content"><a href="#" hreflang="fr">Épicerie</a></span></div>
													</li>
													<li>
														<div class="views-field views-field-title"><span class="field-content"><a href="#" hreflang="fr">Café</a></span></div>
													</li>
													<li>
														<div class="views-field views-field-title"><span class="field-content"><a href="#" hreflang="fr">Restaurant</a></span></div>
													</li>
													<li>
														<div class="views-field views-field-title"><span class="field-content"><a href="#" hreflang="fr">Retail</a></span></div>
													</li>
													<li>
														<div class="views-field views-field-title"><span class="field-content"><a href="#" hreflang="fr">Bar</a></span></div>
													</li>
													<li>
														<div class="views-field views-field-title"><span class="field-content"><a href="#" hreflang="fr">Magasin de mode</a></span></div>
													</li>

												</ul>
											</div>
										</div>
									</div>
								</div> --}}
								{{-- <nav itemscope itemtype="https://schema.org/SiteNavigationElement"
									aria-labelledby="block-producttop-menu" id="block-producttop">
									<div id="block-producttop-menu">Produits</div>
									<ul class="lmenu">
										<li>
											<a href="#"	data-drupal-link-system-path="node/46">WamsCo PDV</a>
										</li>
										<li>
											<a href="#" data-drupal-link-system-path="node/11">Back	Office</a>
										</li>
										<li>
											<a href="#" data-drupal-link-system-path="node/12">Tableau de bord</a>
										</li>
										<li>
											<a href="#"	data-drupal-link-system-path="node/13">Système d&#039;affichage
												cuisine</a>
										</li>
										<li>
											<a href="#"	data-drupal-link-system-path="node/14">Ecran d’Affichage de client</a>
										</li>
										<li>
											<a href="#"	data-drupal-link-system-path="node/17">Gestion des Employés</a>
										</li>
										<li>
											<a href="#"	data-drupal-link-system-path="node/56">Gestion de plusieurs	magasins</a>
										</li>
										<li>
											<a href="#" data-drupal-link-system-path="node/15">Gestion de Stock</a>
										</li>
									</ul>
								</nav> --}}
								{{-- <div id="block-appmarketplace" class="a_mp">
									<div>
										<a href="#">App Marketplace</a>
									</div>
								</div> --}}
								<div id="block-help-2">
									<div>
										<div><a class="help" target="_blank" href="https://api.whatsapp.com/send?phone=+237654258009&text=Bonjour WamsCo, nous souhaiterons avoir une aide par rapport à votre application. Merci."><i class="fa fa-circle-question"></i> @lang('site.aide') </a></div>
									</div>
								</div>
								<div id="block-help-2">
									{{-- <div>
										<div><a class="help" target="_blank" href="{{asset('storage/manuel_users/Setup_WamsCo.zip')}}" download="Setup-WamsCo" title="Taille 273Mo"><i class="fa fa-download"></i> @lang('site.telecharger')</a></div>
									</div> --}}
								</div>
							</div>
							<div class="header-dash">
								<div id="block-loyversecom-getstartedfree">
									<div>
										<div><a class="dash-button" href="connexion?demo=essai"><i class="fa fa-cog fa-spin"></i> @lang('site.essai_free') <span class="hidden-word"> </span></a></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</header>

			{{$slot}}

			<footer class="footer" style="background: #2b2b2b;" itemscope="" itemtype="https://schema.org/WPFooter">
				<div class="footer-main bloc_footer">
					<div>
						<p style="color:#afafaf; font-size:14px;" itemprop="copyrightYear">Tous droits réservés. © 2017 - {{date('Y')}} WamsCo.</p>
					</div>
					<div class="footer-soc">
						<div class="foo-so"><span itemscope="" itemtype="http://schema.org/Organization"><a class="fb" href="https://www.facebook.com/WAMSCO/"	itemprop="sameAs" target="_blank" rel="noopener"> </a></span></div>
						<div class="foo-so"><span itemscope="" itemtype="http://schema.org/Organization"><a class="tw"	href="https://twitter.com/collinswamba" itemprop="sameAs" target="_blank" rel="noopener"> </a></span></div>
						<div class="foo-so"><span itemscope="" itemtype="http://schema.org/Organization"><a class="youtube" href="https://www.youtube.com/channel/UCGEOP66xBGtzt2FdsQVwPXQ" itemprop="sameAs" target="_blank" rel="noopener"> </a></span></div>
						<div class=""><span itemscope="" itemtype="http://schema.org/Organization"> <a class="inst" href="https://www.linkedin.com/company/wamsco-service/" itemprop="sameAs" target="_blank" rel="noopener"><i class="fab fa-linkedin-in" style="padding-top: 17px;	padding-left: 8px; color: #b2b2b2; font-size: 21px;"></i></a></span></div>
					</div>
				</div>
			</footer>
		</div>		
		<script src="site/default/files/js/js_vjrewt9Ub_VdOnWG7fQvzLvZnbEPsJs5UlnSApFDVF0.js"></script>
		<script src="../ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js"></script>
		<script src="site/default/files/js/js_tOzKkVzYfM7vCVddzUEF4B_6bjcyMXuFt1qg65eJ7DM.js"></script>
		{{-- pour la langue --}}
		<script type="text/javascript">  
			var url = "{{ route('changeLang') }}";		  
			$(".changeLang").change(function(){
				window.location.href = url + "?lang="+ $(this).val();
			});		  
		</script>
		{{-- Fin Langue --}}
		@livewireScripts
		{{--Start of Tawk.to Script--}}
		<script type="text/javascript">
			var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
			(function(){
			var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
			s1.async=true;
			s1.src='https://embed.tawk.to/65b7c3200ff6374032c61f12/1hlat4ebm';
			s1.charset='UTF-8';
			s1.setAttribute('crossorigin','*');
			s0.parentNode.insertBefore(s1,s0);
			})();
		</script>
			{{--End of Tawk.to Script--}}
	</body>
</html>
