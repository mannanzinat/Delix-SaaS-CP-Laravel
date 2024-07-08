<!doctype html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html lang="{{ systemLanguage() ? systemLanguage()->locale : 'en' }}"
        dir="{{ systemLanguage() ? systemLanguage()->text_direction : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="DSAThemes">
    <meta name="description" content="Martex - Software, App, SaaS & Startup Landing Pages Pack">
    <meta name="keywords" content="Responsive, HTML5, DSAThemes, Landing, Software, Mobile App, SaaS, Startup, Creative, Digital Product">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name="paginate" content="{{ setting('paginate') }}"/>
    <!-- Open Graph -->
    <meta property="og:title" content="{{ setting('og_title')}}"/>
    <meta property="og:description" content="{{ setting('meta_description')}}"/>
    <meta property="og:url" content="{{ url('/')}}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:locale" content="{{ app()->getLocale() }}"/>
    <meta property="og:site_name" content="{{ setting('system_name') }}"/>
    <meta property="og:image" content="{{ getFileLink('original_image',setting('og_image')) }}"/>
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <!-- END Open Graph -->
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="{{ setting('system_name') }}" />
    <meta name="twitter:creator" content="{{ setting('author_name')}}" />
    <meta name="twitter:title" content="{{ setting('meta_title')}}" />
    <meta name="twitter:description" content="{{ setting('meta_description')}}" />
    <meta name="twitter:image" content="{{ getFileLink('original_image',setting('og_image')) }}" />
    <!-- END Card -->
    @if(setting('meta_title') != '')
        <title>{{setting('meta_title')}}</title>
    @else
        <title>@yield('title',setting('system_name'))</title>
    @endif

    @php
        $icon = setting('favicon');
    @endphp

    @if ($icon)
        <link rel="apple-touch-icon" sizes="57x57"
              href="{{ $icon != [] && @is_file_exists($icon['image_57x57_url']) ? static_asset($icon['image_57x57_url']) : static_asset('images/default/favicon/favicon-57x57.png') }}">
        <link rel="apple-touch-icon" sizes="60x60"
              href="{{ $icon != [] && @is_file_exists($icon['image_60x60_url']) ? static_asset($icon['image_60x60_url']) : static_asset('images/default/favicon/favicon-60x60.png') }}">
        <link rel="apple-touch-icon" sizes="72x72"
              href="{{ $icon != [] && @is_file_exists($icon['image_72x72_url']) ? static_asset($icon['image_72x72_url']) : static_asset('images/default/favicon/favicon-72x72.png') }}">
        <link rel="apple-touch-icon" sizes="76x76"
              href="{{ $icon != [] && @is_file_exists($icon['image_76x76_url']) ? static_asset($icon['image_76x76_url']) : static_asset('images/default/favicon/favicon-76x76.png') }}">
        <link rel="apple-touch-icon" sizes="114x114"
              href="{{ $icon != [] && @is_file_exists($icon['image_114x114_url']) ? static_asset($icon['image_114x114_url']) : static_asset('images/default/favicon/favicon-114x114.png') }}">
        <link rel="apple-touch-icon" sizes="120x120"
              href="{{ $icon != [] && @is_file_exists($icon['image_120x120_url']) ? static_asset($icon['image_120x120_url']) : static_asset('images/default/favicon/favicon-120x120.png') }}">
        <link rel="apple-touch-icon" sizes="144x144"
              href="{{ $icon != [] && @is_file_exists($icon['image_144x144_url']) ? static_asset($icon['image_144x144_url']) : static_asset('images/default/favicon/favicon-144x144.png') }}">
        <link rel="apple-touch-icon" sizes="152x152"
              href="{{ $icon != [] && @is_file_exists($icon['image_152x152_url']) ? static_asset($icon['image_152x152_url']) : static_asset('images/default/favicon/favicon-152x152.png') }}">
        <link rel="apple-touch-icon" sizes="180x180"
              href="{{ $icon != [] && @is_file_exists($icon['image_180x180_url']) ? static_asset($icon['image_180x180_url']) : static_asset('images/default/favicon/favicon-180x180.png') }}">
        <link rel="icon" type="image/png" sizes="192x192"
              href="{{ $icon != [] && @is_file_exists($icon['image_192x192_url']) ? static_asset($icon['image_192x192_url']) : static_asset('images/favicon-192x192.png') }}">
        <link rel="icon" type="image/png" sizes="32x32"
              href="{{ $icon != [] && @is_file_exists($icon['image_32x32_url']) ? static_asset($icon['image_32x32_url']) : static_asset('images/default/favicon/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="96x96"
              href="{{ $icon != [] && @is_file_exists($icon['image_96x96_url']) ? static_asset($icon['image_96x96_url']) : static_asset('images/default/favicon/favicon-96x96.png') }}">
        <link rel="icon" type="image/png" sizes="16x16"
              href="{{ $icon != [] && @is_file_exists($icon['image_16x16_url']) ? static_asset($icon['image_16x16_url']) : static_asset('images/default/favicon/favicon-16x16.png') }}">
        <link rel="manifest" href="{{ static_asset('images/default/favicon/manifest.json') }}">

        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage"
              content="{{ $icon != [] && @is_file_exists($icon['image_144x144_url']) ? static_asset($icon['image_144x144_url']) : static_asset('images/default/favicon/favicon-144x144.png') }}">
    @else
        <link rel="shortcut icon" href="{{ static_asset('images/default/favicon/favicon-96x96.png') }}">
    @endif

    <style>
      @if (base64_decode(setting('custom_css')))
          {{ base64_decode(setting('custom_css')) }}
      @endif
  </style>
  
    @if (setting('is_google_analytics_activated') && setting('tracking_code'))
        {!! base64_decode(setting('tracking_code')) !!}
    @endif
    @if (setting('custom_header_script'))
        {!! base64_decode(setting('custom_header_script')) !!}
    @endif
    @if (setting('is_facebook_pixel_activated') && setting('facebook_pixel_id'))
        {!! base64_decode(setting('facebook_pixel_id')) !!}
    @endif
    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet">
    <!-- BOOTSTRAP CSS -->
    <link href="{{ static_asset('website/themes/martex/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- FONT ICONS -->
    <link href="{{ static_asset('website/themes/martex/css/flaticon.css')}}" rel="stylesheet">
    <!-- PLUGINS STYLESHEET -->
    <link href="{{ static_asset('website/themes/martex/css/menu.css')}}" rel="stylesheet">
    <link id="effect" href="{{ static_asset('website/themes/martex/css/dropdown-effects/fade-down.css')}}" media="all" rel="stylesheet">
    <link href="{{ static_asset('website/themes/martex/css/magnific-popup.css')}}" rel="stylesheet">
    <link href="{{ static_asset('website/themes/martex/css/owl.carousel.min.css')}}" rel="stylesheet">
    <link href="{{ static_asset('website/themes/martex/css/owl.theme.default.min.css')}}" rel="stylesheet">
    <link href="{{ static_asset('website/themes/martex/css/lunar.css')}}" rel="stylesheet">
    <!-- ON SCROLL ANIMATION -->
    <link href="{{ static_asset('website/themes/martex/css/animate.css')}}" rel="stylesheet">
    <!-- TEMPLATE CSS -->
    <!-- <link href="{{ static_asset('website/themes/martex/css/blue-theme.css')}}" rel="stylesheet"> -->
    <!-- <link href="{{ static_asset('website/themes/martex/css/crocus-theme.css')}}" rel="stylesheet"> -->
    <!--<link href="{{ static_asset('website/themes/martex/css/green-theme.css')}}" rel="stylesheet"> -->
    <!-- <link href="{{ static_asset('website/themes/martex/css/magenta-theme.css')}}" rel="stylesheet"> -->
    <!-- <link href="{{ static_asset('website/themes/martex/css/pink-theme.css')}}" rel="stylesheet"> -->
    <link href="{{ static_asset('website/themes/martex/css/purple-theme.css')}}" rel="stylesheet"> 
    <!-- <link href="{{ static_asset('website/themes/martex/css/skyblue-theme.css')}}" rel="stylesheet">-->
    <!-- <link href="{{ static_asset('website/themes/martex/css/red-theme.css')}}" rel="stylesheet"> -->
    <!-- <link href="{{ static_asset('website/themes/martex/css/violet-theme.css')}}" rel="stylesheet"> -->
    <!-- RESPONSIVE CSS -->
    <link href="{{ static_asset('website/themes/martex/css/responsive.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{ static_asset('admin/css/toastr.min.css') }}">

    @stack('css')

</head>
<body>
<!-- PRELOADER SPINNER
============================================= -->
<div id="loading" class="loading--theme">
    <div id="loading-center"><span class="loader"></span></div>
</div>
<!-- PAGE CONTENT
============================================= -->
<div id="page" class="page font--jakarta">
    @include('website.themes.martex.header')
    @yield('content')
    @include('website.themes.martex.footer')
</div>	<!-- END PAGE CONTENT -->
<!-- EXTERNAL SCRIPTS
============================================= -->

<script src="{{ static_asset('website/themes/martex/js/jquery-3.7.0.min.js')}}"></script>
<script src="{{ static_asset('website/themes/martex/js/bootstrap.min.js')}}"></script>
<script src="{{ static_asset('website/themes/martex/js/modernizr.custom.js')}}"></script>
<script src="{{ static_asset('website/themes/martex/js/jquery.easing.js')}}"></script>
<script src="{{ static_asset('website/themes/martex/js/jquery.appear.js')}}"></script>
<script src="{{ static_asset('website/themes/martex/js/menu.js')}}"></script>
<script src="{{ static_asset('website/themes/martex/js/owl.carousel.min.js')}}"></script>
<script src="{{ static_asset('website/themes/martex/js/pricing-toggle.js')}}"></script>
<script src="{{ static_asset('website/themes/martex/js/jquery.magnific-popup.min.js')}}"></script>
<script src="{{ static_asset('website/themes/martex/js/request-form.js')}}"></script>
<script src="{{ static_asset('website/themes/martex/js/jquery.validate.min.js')}}"></script>
<script src="{{ static_asset('website/themes/martex/js/jquery.ajaxchimp.min.js')}}"></script>
<script src="{{ static_asset('website/themes/martex/js/popper.min.js')}}"></script>
<script src="{{ static_asset('website/themes/martex/js/lunar.js')}}"></script>
<script src="{{ static_asset('website/themes/martex/js/wow.js')}}"></script>
<script src="{{ static_asset('admin/js/toastr.min.js') }}"></script>
<script src="{{ static_asset('website/themes/martex/js/custom.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.7/axios.min.js"></script>
{!! Toastr::message() !!}

<script>
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        @if (session()->has('danger'))
            toastr.error("{{ session('danger') }}");
        @endif

        @if (session()->has('success'))
            toastr.success("{{ session('success') }}");
        @endif
    </script>

<!-- Custom Script -->
@stack('js')

</body>
</html>