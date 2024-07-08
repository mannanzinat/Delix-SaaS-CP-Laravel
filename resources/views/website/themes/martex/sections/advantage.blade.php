<!-- FEATURES-2
============================================= -->
<section id="advantage" class="py-100 features-section division">
    <div class="container">
        <!-- SECTION TITLE -->	
        <div class="row justify-content-center">	
            <div class="col-md-10 col-lg-9">
                <div class="section-title mb-80">	
                    <!-- Title -->	
                    <h2 class="s-50 w-700">{{ setting('advantage_section_title', app()->getLocale()) }}</h2>	
                    <!-- Text -->	
                    <p class="s-21 color--grey">{{ setting('advantage_section_subtitle', app()->getLocale()) }}</p>
                </div>	
            </div>
        </div>
        <!-- FEATURES-2 WRAPPER -->
        <div class="fbox-wrapper text-center">
            <div class="row row-cols-1 row-cols-md-3">
                @foreach($advantages as $index=>$advantage) 
                <!-- FEATURE BOX #1 -->
                <div class="col">
                    <div class="fbox-2 fb-1 wow fadeInUp">
                        <!-- Image -->
                        <div class="fbox-img gr--whitesmoke h-175">
                            <img class="img-fluid" src="{{ static_asset(@$advantage->image['original_image'])}}" alt="{{ @$advantage->language->title}}">
                        </div>
                        <!-- Text -->
                        <div class="fbox-txt">
                            <h6 class="s-22 w-700">{{ @$advantage->language->title}}</h6>
                            <p>{!! @$advantage->language->description!!}</p>
                        </div>
                    </div>
                </div>	<!-- END FEATURE BOX #1 -->	
                @endforeach
            </div>  <!-- End row -->  
        </div>	<!-- END FEATURES-2 WRAPPER -->
    </div>     <!-- End container -->
</section>	<!-- END FEATURES-2 -->
<!-- DIVIDER LINE -->
<hr class="divider">