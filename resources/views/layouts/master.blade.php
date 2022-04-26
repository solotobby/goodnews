<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <base href="../">
    <meta charset="utf-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="GoodNews Infotech">
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="{{ asset('res/images/favicon.png') }}">
    <!-- Page Title  -->
    <title>@yield('title')</title>
    <!-- StyleSheets  -->
    <link rel="stylesheet" href="{{asset('res/assets/css/dashlite.css?ver=3.0.0')}}">
    <link id="skin-default" rel="stylesheet" href="{{ asset('res/assets/css/theme.css?ver=3.0.0')}}">
</head>

<body class="nk-body bg-lighter npc-general has-sidebar ">
    <div class="nk-app-root">
        <!-- main @s -->
        <div class="nk-main ">
            <!-- sidebar @s -->

            @include('layouts.component.side-bar')
            
            <!-- sidebar @e -->
            <!-- wrap @s -->
            <div class="nk-wrap ">

                <!-- main header @s -->
                @include('layouts.component.menu-bar')
                
                <!-- main header @e -->

                <!-- content @s -->
                <div class="nk-content ">
                    @yield('content')
                </div>
                <!-- content @e -->
                <!-- footer @s -->
                @include('layouts.component.footer')
                <!-- footer @e -->
            </div>
            <!-- wrap @e -->
        </div>
        <!-- main @e -->
    </div>
    <!-- app-root @e -->
    
    <!-- JavaScript -->
    <script src="{{asset('res/assets/js/bundle.js?ver=3.0.0') }}"></script>
    <script src="{{ asset('res/assets/js/scripts.js?ver=3.0.0') }}"></script>
    <script src="{{ asset('res/assets/js/charts/gd-default.js?ver=3.0.0') }}"></script>
</body>

</html>