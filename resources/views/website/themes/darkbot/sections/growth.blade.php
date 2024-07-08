@if (setting('growth_section_enable') == 1)
    <!-- Growth Section Start -->
    <section class="growth__section" id="growth">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section__title text-center wow fadeInUp" data-wow-delay=".2s">
                        <h2 class="title">{!! setting('growth_section_title', app()->getLocale()) !!}</h2>
                        <p class="desc">
                            {!! setting('growth_section_subtitle', app()->getLocale()) !!}
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-10 m-auto">
                    <div class="advantage__card grid-2 wow fadeInUp" data-wow-delay=".3s">
                        <div class="advantage__content">
                            <h4 class="title"> {!! setting('growth_description', app()->getLocale()) !!}</h4>
                        </div>
                        <div class="advantage__thumb">
                            <img src="{{ getFileLink('original_image', setting('growth_section_thumbnail')) }}"
                                alt="growth-thumb" />
                            <a data-fancybox data-width="640" data-height="360" class="video__play"
                                href="{!! setting('growth_action_url', app()->getLocale()) !!}">
                                <i class="fas fa-play"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Growth Section End -->
@endif
