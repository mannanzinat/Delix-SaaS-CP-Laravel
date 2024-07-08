
<!-- HERO========== -->	
<section id="hero-14" class="bg--scroll hero-section">
    <div class="container text-center">	
        <!-- HERO TEXT -->	
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-9">
                <div class="hero-14-txt color--white wow fadeInUp">
                    <!-- Title -->
                    <h2 class="s-60 w-700"> {!! setting('hero_title',app()->getLocale()) !!}</h2>
                    <!-- Text -->
                    <p class="s-21">
                        {!! setting('hero_description',app()->getLocale()) !!}
                    </p>
                    {{-- @dd(setting('hero_main_action_btn_enable') || setting('hero_secondary_action_btn_enable')) --}}
                    @if (setting('hero_main_action_btn_enable') || setting('hero_secondary_action_btn_enable'))
                    <!-- Buttons -->	
						<div class="btns-group">
                            @if (setting('hero_main_action_btn_enable'))
							<a href="{!! setting('hero_main_action_btn_url',app()->getLocale()) !!}" class="btn r-04 btn--theme hover--black">
                                {!! setting('hero_main_action_btn_label',app()->getLocale()) !!}
                            </a>
                            @endif
                            @if (setting('hero_secondary_action_btn_enable'))
							<a href="{!! setting('hero_secondary_action_btn_url',app()->getLocale()) !!}" class="btn r-04 btn--tra-white hover--theme">
                                {!! setting('hero_secondary_action_btn_label',app()->getLocale()) !!}
                            </a>
                            @endif
						</div>
                    @endif
                    <!-- Text -->
                    <p class="btn-txt ico-15">
                        <span class="flaticon-check"></span>
                        {!! setting('hero_subtitle',app()->getLocale()) !!}                   
                    </p>
                </div>	
            </div>
        </div>	<!-- END HERO TEXT -->	
        <!-- HERO IMAGE -->
        <div class="row">
            <div class="col">
                <div class="hero-14-img wow fadeInUp">
                    <img class="img-fluid" src="{{  getFileLink('original_image',setting('header1_hero_image1')) }}" alt="{!! setting('hero_title',app()->getLocale()) !!}">
                </div>
            </div>	
        </div>	<!-- END HERO IMAGE -->
    </div>	   <!-- End container --> 	
    <!-- WAVE SHAPE BOTTOM -->	
    <div class="wave-shape-bottom">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 190"><path fill-opacity="1" d="M0,32L120,53.3C240,75,480,117,720,117.3C960,117,1200,75,1320,53.3L1440,32L1440,320L1320,320C1200,320,960,320,720,320C480,320,240,320,120,320L0,320Z"></path></svg>
    </div>		
</section>	<!-- END HERO-14 -->	
