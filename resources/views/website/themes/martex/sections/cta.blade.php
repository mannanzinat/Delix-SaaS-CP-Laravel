@if (setting('cta_enable') == 1)
    <!-- BANNER-7============================================= -->
    <section id="cta" class="mt-100 bg--05 bg--scroll banner-section">
        <div class="banner-overlay py-100 h-auto">
            <div class="container">
                <!-- BANNER-7 WRAPPER -->
                <div class="banner-7-wrapper">
                    <div class="row justify-content-center">
                        <!-- BANNER-7 TEXT -->
                        <div class="col-md-8">
                            <div class="banner-7-txt color--white text-center">
                                <!-- Title -->
                                <h2 class="s-50 w-700">{!! setting('cta_title', app()->getLocale()) !!}</h2>
                                <!-- Button -->
                                {{-- {!! setting('cta_main_action_btn_url', app()->getLocale()) !!} --}}
                                <a  data-bs-toggle="modal" data-bs-target="#subscription-form" href="javascript:void(0);"
                                    class="btn r-04 btn--theme hover--tra-white">{!! setting('cta_main_action_btn_label', app()->getLocale()) !!}</a>
                                <!-- Button Text -->
                                @if (setting('cta_subtitle', app()->getLocale()))
                                    <p class="p-sm btn-txt ico-15">
                                        <span class="flaticon-check"></span>
                                        {!! setting('cta_subtitle', app()->getLocale()) !!}
                                    </p>
                                @endif

                            </div>
                        </div>
                    </div> <!-- End row -->
                </div> <!-- END BANNER-7 WRAPPER -->
            </div> <!-- End container -->
        </div> <!-- End banner overlay -->
    </section> <!-- END BANNER-7 -->

@endif
