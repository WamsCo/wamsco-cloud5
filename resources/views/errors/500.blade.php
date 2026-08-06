<!DOCTYPE html>
<html lang="fr" class="dark-mode">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>500 - Erreur Serveur</title>
        <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
        <meta content="" name="description" />
        <meta content="WamsCo" name="author" />
        <link rel="shortcut icon" href="storage/default/favicon.ico"> 
        <link rel="stylesheet" href="fontawesome-6.1.1/css/all.min.css">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
        <link href="assets_connexion/css/vendor.min.css" rel="stylesheet" />
        <link href="assets_connexion/css/default/app.min.css" rel="stylesheet" />
        <link href="assets_connexion/css/default/wamsco.css" rel="stylesheet" /> 
    </head>
    <body class='pace-top'>
        <div id="loader" class="app-loader">
            <span class="spinner"></span>
        </div>
        <div id="app" class="app">
            <div class="login login-with-news-feed">    
                <div class="news-feed">
                    <div class="news-image" style="background-image: url(storage/fond/background.jpg)"></div>
                    <div class="news-caption">
                        <h4 class="caption-title"><b>WamsCo</b>Cloud</h4>
                        <p>Application de gestion commerciale pour les PME, TPE etc...</p>
                    </div>
                </div>
                <div class="login-container" style="background: #2d353c;">
                    <div class="login-header mb-30px">
                        <div class="brand">
                            <div class="d-flex align-items-center">
                                <span class="position_logo"><img class="logo_wamsco" src="storage/default/logo-blanc.png"> </span>
                            </div>
                            <small style="color: #ffffff">Votre réussite à portée de main</small>
                        </div>                
                    </div>
                    <div class="login-content">
                        <p class="login-box-msg text-white fw-bold fs-1 text-center">Oops! 500</p>
                        <p class="login-box-msg text-white fw-bold fs-1 text-center">Erreur Serveur Interne</p>
                        @include('flash::message')
                        <form action="#" method="GET" class="fs-13px">
                            <div class="form-floating mb-15px">                        
                            </div>
                            <div class="form-floating mb-15px">                       
                            </div>
                            <div class="mb-15px">
                                <a href="./connexion" class="btn btn-danger d-block h-45px w-100 btn-lg fs-14px"><i class="fa fa-hand-point-right blink"></i> Connectez-vous en cliquant ici !</a>
                            </div>
                            <div class="mb-40px pb-40px text-dark">
                                <div class="social-auth-links text-center mb-3">
                                    <p>- Pages WamsCo -</p>
                                    <a href="https://www.facebook.com/wamsco/" target="_blank" class="btn btn-block btn-primary"><i class="fab fa-facebook-square mr-2"></i> Facebook</a>
                                    <a href="https://www.linkedin.com/company/42819872/" target="_blank" class="btn btn-block btn-secondary"><i class="fab fa-linkedin mr-2"></i> Linkedin</a>
                                </div>
                            </div>
                            <hr class="bg-gray-600 opacity-2" />
                            <div class="text-gray-600 text-center  mb-0">Tous droits réservés <span class="wamsco_color">WamsCo</span> &copy; {{date('Y')}}</div>
                        </form>
                    </div>        
                </div>        
            </div>    
        </div>
        <script src="assets_connexion/js/vendor.min.js" type="text/javascript"></script>
        <script src="assets_connexion/js/app.min.js" type="text/javascript"></script>
        <script src="cdn-cgi/scripts/7d0fa10a/cloudflare-static/rocket-loader.min.js" data-cf-settings="71da03acba23c94fc228ebc0-|49" defer=""></script>
    </body>
</html>