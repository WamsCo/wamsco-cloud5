<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="utf-8" />
      <title>{{$title}}</title>
      <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
      <meta name="description" content="WamsCo-cloud est un ERP de gestion commerciale comportant plusieurs modules répondant à de nombreux besoins d'entreprises de toute taille, de la PME au grand groupe mais aussi pour les indépendants, auto-entrepreneurs et autres."/>
		  <meta name="author" content="WamsCo"/>
      <link rel="shortcut icon" href="storage/default/favicon.ico">
      <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
      <link href="assets/css/vendor.min.css" rel="stylesheet" />
      <link href="assets/css/default/app.min.css" rel="stylesheet" />
      <link href="assets/css/default/wamsco.css" rel="stylesheet" />
      <link href="fontawesome-6.1.1/css/all.min.css" rel="stylesheet" />
      <link href="assets/plugins/jvectormap-next/jquery-jvectormap.css" rel="stylesheet" />
      <link href="assets/plugins/nvd3/build/nv.d3.css" rel="stylesheet" />
      <link href="assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" />      
      {{-- <!-- required files -->
      <link href="assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
      <link href="assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet" />
      <script src="assets/plugins/datatables.net/js/jquery.dataTables.min.js"></script>
      <script src="assets/plugins/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
      <script src="assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
      <script src="assets/plugins/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script> --}}
      <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
          gtag('config', 'G-7K5RH9S94J');
      </script>
      @livewireStyles 
  </head>
  <body>
      {{-- <div id="loader" class="app-loader">
        <span class="spinner"></span>
      </div> --}}
      <div id="app" class="app app-header-fixed app-sidebar-fixed">
        @include('components.layouts.partials.menu_top_pos')        
        {{$slot}}
          {{-- @include('components.layouts.partials.paramettre')  --}}
        <a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top" data-toggle="scroll-to-top"><i class="fa fa-angle-up"></i></a>
      </div>
      <script src="assets/js/vendor.min.js" type="7e4b0754ad2f9026861b69a2-text/javascript"></script>
      <script src="assets/js/app.min.js" type="7e4b0754ad2f9026861b69a2-text/javascript"></script>
      <script src="assets/plugins/d3/d3.min.js" type="7e4b0754ad2f9026861b69a2-text/javascript"></script>
      <script src="assets/plugins/nvd3/build/nv.d3.min.js" type="7e4b0754ad2f9026861b69a2-text/javascript"></script>
      <script src="assets/plugins/jvectormap-next/jquery-jvectormap.min.js" type="7e4b0754ad2f9026861b69a2-text/javascript"></script>
      <script src="assets/plugins/jvectormap-next/jquery-jvectormap-world-mill.js" type="7e4b0754ad2f9026861b69a2-text/javascript"></script>
      <script src="assets/plugins/apexcharts/dist/apexcharts.min.js" type="7e4b0754ad2f9026861b69a2-text/javascript"></script>
      <script src="assets/plugins/moment/min/moment.min.js" type="7e4b0754ad2f9026861b69a2-text/javascript"></script>
      <script src="assets/plugins/bootstrap-daterangepicker/daterangepicker.js" type="7e4b0754ad2f9026861b69a2-text/javascript"></script>
      <script src="assets/js/demo/dashboard-v3.js" type="7e4b0754ad2f9026861b69a2-text/javascript"></script>
      <script src="assets/cdn-cgi/scripts/7d0fa10a/cloudflare-static/rocket-loader.min.js" data-cf-settings="7e4b0754ad2f9026861b69a2-|49" defer="">
      </script>
        <script defer src="https://static.cloudflareinsights.com/beacon.min.js/v652eace1692a40cfa3763df669d7439c1639079717194" integrity="sha512-Gi7xpJR8tSkrpF7aordPZQlW2DLtzUlZcumS8dMQjwDHEnw9I7ZLyiOj/6tZStRBGtGgN6ceN6cMH8z7etPGlw==" data-cf-beacon='{"rayId":"6f483764add003ae","version":"2024.12.0","r":1,"token":"4db8c6ef997743fda032d4f73cfeff63","si":100}' crossorigin="anonymous">
      </script>
      @livewireScripts
      @include('sweetalert::alert')
      <script>           
        window.addEventListener('alert',(event)=>{
          let data = event.detail;
              Swal.fire({
                  title: data.title,
                  timer: data.timer,
                  icon: data.icon,
                  toast:data.toast,
                  showConfirmButton: data.showConfirmButton,
                  position:data.position,
              });
        });            
    </script>
    <script>     
        document.addEventListener('livewire:init', () => {
            Livewire.on('fermerTier',()=>{ 
              $('#SelectClientModal').modal('hide')
            });
            Livewire.on('fermerCreateTier',()=>{ 
              $('#CreationClientModal').modal('hide')
            });
            Livewire.on('fermerPosPaie',()=>{ 
              $('#saissieReglementPosModal').modal('hide')
            });
        });
    </script>   
    @stack('scripts')
  </body>
</html>






