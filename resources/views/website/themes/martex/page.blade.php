@extends('website.themes.' . active_theme() . '.master')
@section('content')
    @push('css')
        <style>
            .navbar-light .wsmenu>.wsmenu-list>li>a.h-link {
                color: #353f4f;
            }

            .tra-menu .wsmenu>.wsmenu-list>li>a,
            .aqua-menu .wsmenu>.wsmenu-list>li>a,
            .blue-menu .wsmenu>.wsmenu-list>li>a {
                color: #353f4f !important;
            }

            .last-link {
                color: #353f4f !important;
                border-color: #353f4f !important;
            }

            .last-link:hover {
                color: #fff !important;
            }

            .desktoplogo {
                margin-top: 0px;
            }

            .navbar-light .wsmenu-list > li > a.h-link:hover {
                color: #353f4f !important;
            }

            .wsmenu > .wsmenu-list > li a.btn:hover {
                color: #fff !important;
            }


        </style>

    @endpush

    <!-- PRIVACY PAGE
       ============================================= -->
    <section id="privacy-page" class="gr--whitesmoke pb-80 inner-page-hero division">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-10">
                    <!-- INNER PAGE TITLE -->
                    <div class="inner-page-title">
                        <h2 class="s-52 w-700">{!! $page_info->title !!}</h2>
                        {{-- <p class="p-lg">This policy is effective as of 11th November 2022</p> --}}
                    </div>
                    <!-- TEXT BLOCK -->
                    <div class="txt-block legal-info">
                        {!! $page_info->content !!}
                    </div> <!-- END TEXT BLOCK -->
                </div>
            </div> <!-- End row -->
        </div> <!-- End container -->
    </section> <!-- END PRIVACY PAGE -->
@endsection
