<!DOCTYPE html>
<html lang="fr" class="dark-mode">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>{{$title}}</title>
        <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
        <meta name="description" content="WamsCo-cloud est un ERP de gestion commerciale comportant plusieurs modules répondant à de nombreux besoins d'entreprises de toute taille, de la PME au grand groupe mais aussi pour les indépendants, auto-entrepreneurs et autres."/>
        <meta name="author" content="WamsCo"/>
        <link rel="shortcut icon" href="storage/default/favicon.ico"> 
        <link rel="stylesheet" href="fontawesome-6.1.1/css/all.min.css">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
        <link href="assets/css/vendor.min.css" rel="stylesheet" />
        <link href="assets/css/default/app.min.css" rel="stylesheet" />
        <link href="assets/css/default/wamsco.css" rel="stylesheet" />        
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-7K5RH9S94J"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', 'G-7K5RH9S94J');
        </script>
        @livewireStyles
    </head>
    <body class='pace-top'>
        <div id="loader" class="app-loader">
            <span class="spinner"></span>
        </div>

        {{ $slot }}

        <script src="assets/js/vendor.min.js" type="text/javascript"></script>
        <script src="assets/js/app.min.js" type="text/javascript"></script>
        <script src="cdn-cgi/scripts/7d0fa10a/cloudflare-static/rocket-loader.min.js" data-cf-settings="71da03acba23c94fc228ebc0-|49" defer=""></script>
        @livewireScripts 
        @include('sweetalert::alert')          
    </body>
</html>