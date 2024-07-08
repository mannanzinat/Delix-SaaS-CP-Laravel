@extends('website.themes.' . active_theme() . '.master')
<style>
    #subscription-form .modal-body-content {
    padding: 35px 40px 15px;
}
</style>
@section('content')
    @include('website.themes.martex.sections.hero')
    @include('website.themes.martex.sections.counter')
    @include('website.themes.martex.sections.advantage')
    {{-- Service Section 1 --}}
    <!-- TEXT CONTENT============================================= -->
    <section class="bg--white-400 py-100 ct-01 content-section division"> 
        <div class="container">
            <!-- SECTION CONTENT (ROW) -->
            <div class="row d-flex align-items-center">
                <!-- TEXT BLOCK -->
                <div class="col-md-6 order-last order-md-2">
                    <div class="txt-block left-column wow fadeInRight">
                        <!-- Section ID -->
                        <span class="section-id color--grey">{!! setting('service_1_subtitle',app()->getLocale()) !!}</span>
                        <!-- Title -->
                        <h2 class="s-46 w-700">{!! setting('service_1_title',app()->getLocale()) !!}</h2>
                        {!! setting('service_1_description',app()->getLocale()) !!}
                        <!-- Link -->
                        <div class="txt-block-tra-link mt-25">
                            <a href="#features-5" class="tra-link ico-20 color--theme">
                                Friendly with others <span class="flaticon-next"></span>
                            </a>
                        </div>
                    </div>
                </div> <!-- END TEXT BLOCK -->
                <!-- IMAGE BLOCK -->
                <div class="col-md-6 order-first order-md-2">
                    <div class="img-block right-column wow fadeInLeft">
                        <img class="img-fluid" src="{{ getFileLink('original_image',setting('service_1_image')) }}"
                            alt="{!! setting('service_1_title',app()->getLocale()) !!}">
                    </div>
                </div>
            </div> <!-- END SECTION CONTENT (ROW) -->
        </div> <!-- End container -->
    </section> <!-- END TEXT CONTENT -->
    @include('website.themes.martex.sections.feature')
    {{--    @include('website.themes.martex.sections.story') --}}
    {{--    @include('website.themes.martex.sections.testimonial') --}}
    {{--    @include('website.themes.martex.sections.unique_feature') --}}
    {{--    @include('website.themes.martex.sections.feature') --}}
    {{--    @include('website.themes.martex.sections.ai_chat') --}}
    {{--    @include('website.themes.martex.sections.advantage') --}}
    {{--    @include('website.themes.martex.sections.cta') --}}
     {{-- Service Section 2 --}}
    <!-- TEXT CONTENT=============================== -->
    <section class="pt-100 ct-02 content-section division">
        <div class="container">
            <!-- SECTION CONTENT (ROW) -->
            <div class="row d-flex align-items-center">
                <!-- IMAGE BLOCK -->
                <div class="col-md-6">
                    <div class="img-block left-column wow fadeInRight">
                        <img class="img-fluid" src="{{ getFileLink('original_image',setting('service_2_image')) }}"
                            alt="{!! setting('service_2_title',app()->getLocale()) !!}">
                    </div>
                </div>
                <!-- TEXT BLOCK -->
                <div class="col-md-6">
                    <div class="txt-block right-column wow fadeInLeft">
                        {!! setting('service_2_description',app()->getLocale()) !!}
                    </div>
                </div> <!-- END TEXT BLOCK -->
            </div> <!-- END SECTION CONTENT (ROW) -->
        </div> <!-- End container -->
    </section> <!-- END TEXT CONTENT -->
     {{-- Service Section 3 --}}
    <!-- TEXT CONTENT  ============================================= -->
    <section class="pt-100 ct-01 content-section division">
        <div class="container">
            <!-- SECTION CONTENT (ROW) -->
            <div class="row d-flex align-items-center">
                <!-- TEXT BLOCK -->
                <div class="col-md-6 order-last order-md-2">
                    <div class="txt-block left-column wow fadeInRight">
                        <!-- Section ID -->
                        <span class="section-id">{!! setting('service_3_subtitle',app()->getLocale()) !!}</span>
                        <!-- Title -->
                        <h2 class="s-46 w-700">{!! setting('service_3_title',app()->getLocale()) !!}</h2>
                        {!! setting('service_3_description',app()->getLocale()) !!}
                    </div>
                </div> <!-- END TEXT BLOCK -->
                <!-- IMAGE BLOCK -->
                <div class="col-md-6 order-first order-md-2">
                    <div class="img-block right-column wow fadeInLeft">
                        <img class="img-fluid" src="{{ getFileLink('original_image',setting('service_3_image')) }}"
                            alt="content-image">
                    </div>
                </div>
            </div> <!-- END SECTION CONTENT (ROW) -->
        </div> <!-- End container -->
    </section> <!-- END TEXT CONTENT -->
    @include('website.themes.martex.sections.pricing')
    @include('website.themes.martex.sections.testimonial')
    @include('website.themes.martex.sections.faq')
    @include('website.themes.martex.sections.partner')
    @include('website.themes.martex.sections.cta')
    @include('website.themes.martex.sections.subscription_modal')
    @push('js')
        <script>
           $(document).ready(function() {
                function checkPlans() {
                    if ($('.billed_checkbox').is(':checked')) {
                        $('.is_free').removeClass('d-block').addClass('d-none');
                        // Show yearly plans, hide monthly plans
                        $('.plan_yearly').removeClass('d-none').addClass('d-block');
                        $('.plan_monthly').removeClass('d-block').addClass('d-none');
                        $('.plan_yearly').css('visibility', 'visible');
                        $('.plan_monthly').css('visibility', 'hidden');

                        $('.is_free').css('visibility', 'hidden');
                    } else {
                        // Show monthly plans, hide yearly plans
                        $('.is_free').removeClass('d-none').addClass('d-block');
                        $('.plan_yearly').removeClass('d-block').addClass('d-none');
                        $('.plan_monthly').removeClass('d-none').addClass('d-block');
                        $('.plan_yearly').css('visibility', 'hidden');
                        $('.plan_monthly').css('visibility', 'visible');
                        $('.is_free').css('visibility', 'visible');
                    }
                }
                // Bind the checkPlans function to the checkbox change event
                $('.billed_checkbox').on('change', checkPlans);
                // Ensure the correct plans are shown on page load
                checkPlans();
            });

        </script>
    @endpush
@endsection
