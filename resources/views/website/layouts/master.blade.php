<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Delix - SaaS Html Template</title>
        <meta name="description" content="Delix - SaaS Html Template" />
        <link rel="shortcut icon" href="{{ asset('website') }}/assets/images/logo/favicon.png" type="image/f-icon" />

        <!-- font awesome -->
        <link rel="stylesheet" href="{{ asset('website') }}/assets/css/all.min.css" />
        <!-- bootstraph -->
        <link rel="stylesheet" href="{{ asset('website') }}/assets/css/bootstrap.min.css" />
        <!-- Swiper js -->
        <link rel="stylesheet" href="{{ asset('website') }}/assets/css/swiper-bundle.min.css" />
        <!-- Animate Css -->
        <link rel="stylesheet" href="{{ asset('website') }}/assets/css/animate.css" />
        <!-- FancyBox -->
        <link rel="stylesheet" href="{{ asset('website') }}/assets/css/jquery.fancybox.min.css" />
        <!-- Animate Animation -->
        <link rel="stylesheet" href="{{ asset('website') }}/assets/css/aos.css" />
        <!-- Select 2  -->
        <link rel="stylesheet" href="{{ asset('website') }}/assets/css/select2.min.css" />
        <!-- User's CSS Here -->
        <link rel="stylesheet" href="{{ asset('website') }}/assets/css/style.css" />

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

        @stack('css')
    </head>
    <body>
        <!-- Preloader -->
        <div id="preloader">
            <div id="status"><img src="{{ asset('website') }}/assets/images/logo/favicon.png" alt="logo" /></div>
        </div>
        <!--Preloader  -->
        @include('website.layouts.header')
            @yield('content')
        @include('website.layouts.footer')

        <!-- JS -->
        <script src="{{ asset('website') }}/assets/js/jquery-3.6.0.min.js"></script>
        <script src="{{ asset('website') }}/assets/js/bootstrap.min.js"></script>
        <script src="{{ asset('website') }}/assets/js/smooth-scroll.js"></script>
        <script src="{{ asset('website') }}/assets/js/gsap.min.js"></script>
        <script src="{{ asset('website') }}/assets/js/jquery.fancybox.min.js"></script>
        <script src="{{ asset('website') }}/assets/js/swiper-bundle.min.js"></script>
        <script src="{{ asset('website') }}/assets/js/appear.min.js"></script>
        <script src="{{ asset('website') }}/assets/js/aos.js"></script>
        <script src="{{ asset('website') }}/assets/js/jquery.cycleText.js"></script>
        <script src="{{ asset('website') }}/assets/js/select2.min.js"></script>
        <script src="{{ asset('website') }}/assets/js/typer.min.js"></script>
        <script src="{{ asset('website') }}/assets/js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        {!! Toastr::message() !!}
        @if (session()->has('error'))
            <script>
                toastr.error("{{ session('error') }}")
            </script>
        @endif
        @if (session()->has('danger'))
            <script>
                toastr.error("{{ session('danger') }}")
            </script>
        @endif
        @if (session()->has('success'))
            <script>
                toastr.success("{{ session('success') }}")
            </script>
        @endif
        @stack('js')
    </body>
</html>
