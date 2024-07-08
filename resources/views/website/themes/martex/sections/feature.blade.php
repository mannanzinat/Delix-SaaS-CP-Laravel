   <!-- FEATURES==================== -->
        <section id="features" class="pt-100 features-section division">
            <div class="container">
                <!-- SECTION TITLE -->
                <div class="row justify-content-center">
                    <div class="col-md-10 col-lg-9">
                        <div class="section-title mb-70">  
                            <!-- Title -->
                            <h2 class="s-50 w-700">{!! setting('feature_section_title',app()->getLocale()) !!}</h2>
                            <!-- Text -->
                            <p class="s-21 color--grey">{!! setting('feature_section_subtitle',app()->getLocale()) !!}</p>
                        </div>
                    </div>
                </div>
                <!-- FEATURES-11 WRAPPER -->
                <div class="fbox-wrapper">
                    <div class="row row-cols-1 row-cols-md-2 rows-3">
                        @if(!is_null($features))
                        @foreach(@$features as $index => $feature)
                        <div class="col">
                            <div class="fbox-11 fb-1 wow fadeInUp">
                                <!-- Icon -->
                                <div class="fbox-ico-wrap">
                                    <div class="fbox-ico ico-50">
                                        <div class="shape-ico color--theme">
                                            <!-- Vector Icon -->
                                            <span class="{{ @$feature->icon }}"></span>
                                            <!-- Shape -->
                                            <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M69.8,-23C76.3,-2.7,57.6,25.4,32.9,42.8C8.1,60.3,-22.7,67,-39.1,54.8C-55.5,42.7,-57.5,11.7,-48.6,-11.9C-39.7,-35.5,-19.8,-51.7,5.9,-53.6C31.7,-55.6,63.3,-43.2,69.8,-23Z"
                                                    transform="translate(100 100)" />
                                            </svg>
                                        </div>
                                    </div>
                                </div> <!-- End Icon -->
                                <!-- Text -->
                                <div class="fbox-txt">
                                    <h6 class="s-22 w-700">{{ @$feature->language->title, app()->getLocale() }}</h6>
                                    <p>
                                        @if(!is_null($feature->language))
                                            @foreach(@$feature->language->description as $description)
                                                {!! $description !!}
                                            @endforeach
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div> <!-- END FEATURE BOX #1 -->
                        @endforeach
                        @endif
                    </div> <!-- End row -->
                </div> <!-- END FEATURES-11 WRAPPER -->
            </div> <!-- End container -->
        </section> <!-- END FEATURES-11 -->