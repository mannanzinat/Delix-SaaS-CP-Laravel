	<!-- Banner Section Start -->
    <section class="banner__section">
        <div class="container">
            <div class="row align-items-center"> 
                <div class="col-12">
                    <div class="hero__text text-center wow fadeInUp" data-wow-delay=".2s">
                        <h1 class="title gradient">{!! setting('hero_title',app()->getLocale()) !!}</h1>
                        {{-- <h1 class="title gradient">With <span>WhatsApp</span> Marketing</h1> --}}
                        <p class="desc">
                            {!! setting('hero_description',app()->getLocale()) !!}
                        </p>
                        @if (setting('hero_main_action_btn_enable') || setting('hero_secondary_action_btn_enable'))
                        <div class="btn__group">
                            @if (setting('hero_main_action_btn_enable'))
                            <a href="{!! setting('hero_main_action_btn_url',app()->getLocale()) !!}" class="btn btn-primary">
                                {!! setting('hero_main_action_btn_label',app()->getLocale()) !!}
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                            @endif
                            @if (setting('hero_secondary_action_btn_enable'))
                            <a data-fancybox data-width="640" data-height="360" href="{!! setting('hero_secondary_action_btn_url',app()->getLocale()) !!}" class="btn btn-secondary">
                                <i class="fa-regular fa-circle-play"></i>
                                {!! setting('hero_secondary_action_btn_label',app()->getLocale()) !!}
                            </a>
                            @endif
                        </div>  
                        @endif     
                    </div>
                    <div class="banner__thumb wow fadeInUp" data-wow-delay=".3s">
                        <img src="{{  getFileLink('original_image',setting('header1_hero_image1')) }}" alt="banner-thumb" />
                    </div>
                </div>
            </div>
        </div>
    </section>   
    <!-- Banner Section End -->