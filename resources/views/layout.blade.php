<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', 'FS19 - Webstats')</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/w3.css') }}" />
        @yield('stylesheet', '')

        <!-- we fav icon -->
        <link rel="icon" href="{{ asset('img/favicon.ico') }}">

        <style>
            html,body,h1,h2,h3,h4,h5 {font-family: "Raleway", sans-serif}            
        </style>

        <script>
            function close_open_submenu(id) {
                var x = document.getElementById(id);
                if (x.className.indexOf("w3-show") == -1) {
                    //x.className += " w3-show"; 
                    x.className = x.className.replace("w3-hide", "w3-show");
                    x.previousElementSibling.className += " w3-dark-grey";
                } else { 
                    //x.className = x.className.replace(" w3-show", "");
                    //x.previousElementSibling.className = 
                    x.className = x.className.replace("w3-show", "w3-hide");
                    x.previousElementSibling.className = x.previousElementSibling.className.replace(" w3-dark-grey", "");
                }
            }
        </script>
    </head>
    <body class="w3-light-grey">
        <!-- Sidebar/menu -->
        <nav class="w3-sidebar w3-collapse w3-white" style="z-index:3;width:230px;" id="mySidebar"><br>
          <div class="w3-container w3-row">
            <div class="w3-col s4">
              <img src="{{ asset('img/fs19_logo_full.jpg') }}" class="w3-margin-right" style="width:80px">
            </div>
            <div class="w3-col s8 w3-bar" style="font-size: x-large; margin-top: 5px; padding-left: 15px">
              <span>Webstats</span>
            </div>
          </div>
          <hr>
          <div class="w3-bar-block">
            <a href="http://ws.nuba.synology.me/" class="w3-bar-item w3-button w3-padding {{ ($active_mi == 'home_overview') ? 'w3-blue' : '' }}"><i class="fa fa-globe fa-fw"></i>Overview</a>
            <a href="/stats" class="w3-bar-item w3-button w3-padding {{ ($active_mi == 'stats') ? 'w3-blue' : '' }}"><i class="fa fa-pie-chart fa-fw"></i>Statistics</a>
            <a id="myBtn" onclick="close_open_submenu('animals')" href="javascript:void(0)" class="w3-bar-item w3-button">
                <i class="fa fa-bars fa-fw"></i>Animals<i class="fa fa-caret-down w3-margin-left"></i>
            </a>
            <div id="animals" class="w3-hide w3-animate-left w3-light-grey">
                <a href="#" class="w3-bar-item w3-button w3-padding"><i class="fa fa-paw fa-fw" aria-hidden="true"></i>Horses</a>
                <a href="#" class="w3-bar-item w3-button w3-padding"><i class="fa fa-paw fa-fw" aria-hidden="true"></i>Sheeps</a>            
                <a href="#" class="w3-bar-item w3-button w3-padding"><i class="fa fa-paw fa-fw" aria-hidden="true"></i>Cows</a>
                <a href="#" class="w3-bar-item w3-button w3-padding"><i class="fa fa-paw fa-fw" aria-hidden="true"></i>Pigs</a>
                <a href="#" class="w3-bar-item w3-button w3-padding"><i class="fa fa-paw fa-fw" aria-hidden="true"></i>Chickens</a>
                <a href="#" class="w3-bar-item w3-button w3-padding"><i class="fa fa-paw fa-fw" aria-hidden="true"></i>Dog</a>
            </div>
            <a id="myBtn" onclick="close_open_submenu('property')" href="javascript:void(0)" class="w3-bar-item w3-button">
                <i class="fa fa-bars fa-fw"></i>Property<i class="fa fa-caret-down w3-margin-left"></i>
            </a>
            <div id="property" class="w3-hide w3-animate-left w3-light-grey">
                <a href="#" class="w3-bar-item w3-button w3-padding"><i class="fa fa-apple fa-fw" aria-hidden="true"></i>Silo</a>
                <a href="#" class="w3-bar-item w3-button w3-padding"><i class="fa fa-cubes fa-fw" aria-hidden="true"></i>Pallets</a>
                <a href="#" class="w3-bar-item w3-button w3-padding"><i class="fa fa-truck fa-fw" aria-hidden="true"></i>Vehicles</a>
                <a href="#" class="w3-bar-item w3-button w3-padding"><i class="fa fa-building fa-fw" aria-hidden="true"></i>Buildings</a>
            </div>     
            <a id="myBtn" onclick="close_open_submenu('finance')" href="javascript:void(0)" class="w3-bar-item w3-button">
                <i class="fa fa-bars fa-fw"></i>Finance<i class="fa fa-caret-down w3-margin-left"></i>
            </a>
            <div id="finance" class="w3-hide w3-animate-left w3-light-grey">
                <a href="#" class="w3-bar-item w3-button w3-padding"><i class="fa fa-calendar fa-fw" aria-hidden="true"></i>Overview</a>
                <a href="#" class="w3-bar-item w3-button w3-padding"><i class="fa fa-list fa-fw" aria-hidden="true"></i>Balance sheet</a>
                <a href="#" class="w3-bar-item w3-button w3-padding"><i class="fa fa-eur fa-fw" aria-hidden="true"></i>Financial indicators</a>
            </div>   
            <a id="myBtn" onclick="close_open_submenu('prices')" href="javascript:void(0)" class="w3-bar-item w3-button">
                <i class="fa fa-bars fa-fw"></i>Market prices<i class="fa fa-caret-down w3-margin-left"></i>
            </a>
            <div id="prices" class="w3-hide w3-animate-left w3-light-grey">
                <a href="#" class="w3-bar-item w3-button w3-padding"><i class="fa fa-eye fa-fw" aria-hidden="true"></i>Overview</a>
                <a href="#" class="w3-bar-item w3-button w3-padding"><i class="fa fa-shopping-basket fa-fw" aria-hidden="true"></i>Analisys</a>            
            </div>                   
            <a href="/missions" class="w3-bar-item w3-button w3-padding {{ ($active_mi == 'missions') ? 'w3-blue' : '' }}"><i class="fa fa-star fa-fw"></i>Missions</a>
            <a href="/farms" class="w3-bar-item w3-button w3-padding {{ ($active_mi == 'farms') ? 'w3-blue' : '' }}"><i class="fa fa-home fa-fw"></i>Farms</a>
            <a href="/fields" class="w3-bar-item w3-button w3-padding {{ ($active_mi == 'fields') ? 'w3-blue' : '' }}"><i class="fa fa-map fa-fw"></i>Fields</a>
            <a href="/forestry" class="w3-bar-item w3-button w3-padding {{ ($active_mi == 'forestry') ? 'w3-blue' : '' }}"><i class="fa fa-tree fa-fw"></i>Forestry</a>
            <a href="/about" class="w3-bar-item w3-button w3-padding {{ ($active_mi == 'about') ? 'w3-blue' : '' }}"><i class="fa fa-info-circle fa-fw"></i>About</a>
            <a href="/settings" class="w3-bar-item w3-button w3-padding {{ ($active_mi == 'settings') ? 'w3-blue' : '' }}"><i class="fa fa-cog fa-fw"></i>Settings</a>
            <a href="/test" class="w3-bar-item w3-button w3-padding {{ ($active_mi == 'test') ? 'w3-blue' : '' }}"><i class="fa fa-minus fa-fw"></i>test</a>            
            <a href="/load_savegame" class="w3-bar-item w3-button w3-padding {{ ($active_mi == 'load_savegame') ? 'w3-blue' : '' }}"><i class="fa fa-minus fa-fw"></i>load_savegame</a>
            <a href="/load_vehicles" class="w3-bar-item w3-button w3-padding {{ ($active_mi == 'load_vehicles') ? 'w3-blue' : '' }}"><i class="fa fa-minus fa-fw"></i>load_vehicles</a>
            <a href="/load_items" class="w3-bar-item w3-button w3-padding {{ ($active_mi == 'load_items') ? 'w3-blue' : '' }}"><i class="fa fa-minus fa-fw"></i>load_items</a>
            <!-- SEPARATE EXTERNAL LINKS WITH HORIZONTAL LINE -->
            <hr>
            <a href="http://fs19.nuba.synology.me/" target="_blank" class="w3-bar-item w3-button w3-padding"><i class="fa fa-server fa-fw"></i>Server</a>
            <a href="http://stats1.nuba.synology.me/index.php" target="_blank" class="w3-bar-item w3-button w3-padding"><i class="fa fa-minus fa-fw"></i>OLD Stats</a>
            <br>
            <br>
          </div>
        </nav>


        <!-- Overlay effect when opening sidebar on small screens -->
        <div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

        <!-- !PAGE CONTENT! -->
        <div class="w3-main" style="margin-left:230px;">

          <!-- Header -->
          <header class="w3-container" style="padding-top:22px">
            <h5><b><i class="fa fa-dashboard fa-fw"></i>@yield('header_text','My Dashboard')</b></h5>
          </header>

          @yield('content')

          <!-- Footer -->
          <footer class="w3-container w3-padding-16 w3-light-grey">
            <p>
                Powered by:
                &nbsp;&nbsp;&nbsp;<a href="https://www.w3schools.com/w3css/w3css_templates.asp" target="_blank">w3.css</a>
                &nbsp;&nbsp;&nbsp;<a href="https://laravel.com/docs/8.x" target="_blank">Laravel</a> v{{ Illuminate\Foundation\Application::VERSION }}
                &nbsp;&nbsp;&nbsp;<a href="https://www.php.net/" target="_blank">PHP</a> v{{ PHP_VERSION }}
                &nbsp;&nbsp;&nbsp;<a href="https://mariadb.com/" target="_blank">Maria DB</a> v10
                &nbsp;&nbsp;&nbsp;<a href="https://httpd.apache.org/" target="_blank">Apache</a> v2.4
                &nbsp;&nbsp;&nbsp;<a href="https://www.synology.com/en-global/products" target="_blank">Synology</a>
            </p>
          </footer>

          <!-- End page content -->
        </div>
    </body>
</html>